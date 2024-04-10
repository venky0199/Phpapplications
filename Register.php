<!DOCTYPE html>
<html>
<head>
    
    <title>Registration Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=0.9">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ-sDdzI_R3oK43XPF9i6LIJBbY9vSBjeMwF0XMwXEzDA&usqp=CAU&ec=48665701');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        h2 {
            color: white;
        }
        .error-message {
            background-color: #ff6666;
            color: white;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        form {
            display: inline-block;
            text-align: left;
            margin-top: 50px;
            padding: 40px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.8);
        }
        div {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: -1.5px;
        }
        input[type="text"],
        input[type="password"],
        input[type="tel"] {
            width: 300px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-weight: bold;
            border: 2px solid black;
        }
        .password-container {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }
        input[type="submit"] {
            width: 300px;
            padding: 15px;
            background-color: green;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            
        }
        button {
            width: 160px; /* Reduce button size */
            padding: 10px; /* Reduce padding */
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-family: Arial, sans-serif; /* Apply the same font family */
            font-weight: bold; /* Make button text bold */
            text-transform: uppercase; 
    </style>
</head>
<body>

<?php
$error_message = "";
$username_value = "";
$mobile_value = "";
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch data from form
    $username = $_POST["username"];
    $mobile = $_POST["mobile"];
    $password = $_POST["password"];

    // Check if password contains at least one uppercase letter
    if (!preg_match('/[A-Z]/', $password)) {
        $error_message = "Password must contain at least one uppercase letter.";
    } else {
        // Database connection details
        $servername = "localhost"; // Replace "localhost" with your database host name if it's different
        $username_db = "root"; // Replace "root" with your database username
        $password_db = ""; // Replace "" with your database password
        $dbname = "Register"; // Replace "Register" with your database name

        // Create connection
        $conn = new mysqli($servername, $username_db, $password_db, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare statement to check if username already exists // here Redister is an table name
        $check_username_stmt = $conn->prepare("SELECT * FROM Register WHERE username = ?");
        $check_username_stmt->bind_param("s", $username);
        $check_username_stmt->execute();
        $check_username_result = $check_username_stmt->get_result();

        if ($check_username_result->num_rows > 0) {
            // Username already exists in the database
            $error_message = "Username already taken. Please choose another one.";
            $mobile_value = $mobile;
        } else {
            // Prepare statement to check if mobile number already exists
            $check_mobile_stmt = $conn->prepare("SELECT * FROM Register WHERE mobile = ?");
            $check_mobile_stmt->bind_param("s", $mobile);
            $check_mobile_stmt->execute();
            $check_mobile_result = $check_mobile_stmt->get_result();

            if ($check_mobile_result->num_rows > 0) {
                // Mobile number already exists in the database
                $error_message = "Mobile number already taken. Please choose another one.";
                $username_value = $username;
            } else {
                // Prepare and bind statement to insert data into database // here username, mpobile, password are the variables in the database where our data us stored
                $stmt = $conn->prepare("INSERT INTO Register (username, mobile, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $username, $mobile, $password);

                // Execute the statement
                if ($stmt->execute() === TRUE) {
                    // Redirect to login page after successful registration
                    header("Location: Login.php");
                    exit();
                } else {
                    $error_message = "Error: " . $stmt->error;
                }

                // Close statement
                $stmt->close();
            }

            // Close mobile check statement
            $check_mobile_stmt->close();
        }

        // Close username check statement
        $check_username_stmt->close();

        // Close connection
        $conn->close();
    }
}
?>

<?php if (empty($error_message)): ?>
<h2>Registration Page</h2>
<?php else: ?>
<div id="errorMessage" class="error-message"><?php echo $error_message; ?></div>
<?php endif; ?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validateForm()">
    <div>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required value="<?php echo $username_value; ?>">
    </div>
    <div>
        <label for="mobile">Mobile Number:</label>
        <input type="tel" id="mobile" name="mobile" required value="<?php echo $mobile_value; ?>">
    </div>
    <div class="password-container">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <span class="password-toggle" onclick="togglePassword()">👁️‍🗨️</span>
    </div>
    <div>
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
    </div>
    <div>
        <button type="submit" style="background-color: green; color: white; border: none; border-radius: 5px;length:20px cursor: pointer; font-weight: bold;">Register</button>
        <button type="submit"  style="background-color: blue; color: white; border: none; border-radius: 5px;length:20px cursor: pointer; font-weight: bold;" onclick="window.location.href='Login.php'">Login</button>
    </div>
</form>

<script>
    function togglePassword() {
        var passwordField = document.getElementById("password");
        if (passwordField.type === "password") {
            passwordField.type = "text";
        } else {
            passwordField.type = "password";
        }
    }

    function validateForm() {
        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("confirm_password").value;
        var mobile = document.getElementById("mobile").value;
        var username = document.getElementById("username").value;

        if (password.length < 6 || password.length > 12) {
            showError("Password must be between 6 and 12 characters long.");
            return false;
        }

        if (!password.match(/[A-Z]/)) {
            showError("Password must contain at least one uppercase letter.");
            return false;
        }

        if (!password.match(/[a-z]/)) {
            showError("Password must contain at least one lowercase letter.");
            return false;
        }

        if (!password.match(/[!@#$%^&*(),.?":{}|<>]/)) {
            showError("Password must contain at least one special character.");
            return false;
        }

        if (password !== confirmPassword) {
            showError("Passwords do not match.");
            return false;
        }

        if (mobile.length !== 10 || isNaN(mobile)) {
            showError("Mobile number must be 10 digits long and contain only numbers.");
            return false;
        }

        if (username.length < 6 || username.length > 12) {
            showError("Username must be between 6 and 12 characters long.");
            return false;
        }

        return true;
    }

    function showError(message) {
        var errorMessageElement = document.getElementById("errorMessage");
        errorMessageElement.textContent = message;
        errorMessageElement.style.display = "block";
    }
</script>

</body>
</html>
