<!DOCTYPE html>
<html>
<head>
    <title>Update password</title>
    <meta name="viewport" content="width=device-width, initial-scale=0.9">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ-sDdzI_R3oK43XPF9i6LIJBbY9vSBjeMwF0XMwXEzDA&usqp=CAU&ec=48665701');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: 0; /* Remove default margin */
            padding-top: 50px; /* Adjust padding */
        }
        .error-message {
            background-color: #ff6666;
            color: white;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            margin-bottom: 10px; /* Reduced margin */
        }
        .success-message {
            background-color: green;
            color: white; /* Changed text color to white */
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            margin-bottom: 10px; /* Reduced margin */
        }
        form {
            display: inline-block;
            text-align: left;
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
            width: 150px; /* Reduced width */
            padding: 15px;
            background-color: green;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .login-button {
            width: 150px; /* Button width */
            padding: 15px;
            background-color: blue; /* Blue color */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button {
            width: 150px; /* Reduced button size */
            padding: 15px; /* Reduce padding */
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-family: Arial, sans-serif; /* Apply the same font family */
            font-weight: bold; /* Make button text bold */
            text-transform: uppercase;
        }
    </style>
</head>
<body>

<?php
$error_message = "";
$mobile_value = "";
$password_error_message = ""; // Added for password mismatch error

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch data from form
    $mobile = $_POST["mobile"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $password_error_message = "Passwords do not match.";
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

        // Prepare statement to check if mobile number exists in the database
        $check_mobile_stmt = $conn->prepare("SELECT * FROM Register WHERE mobile = ?");
        $check_mobile_stmt->bind_param("s", $mobile);
        $check_mobile_stmt->execute();
        $check_mobile_result = $check_mobile_stmt->get_result();

        if ($check_mobile_result->num_rows == 0) {
            // Mobile number does not exist in the database
            $error_message = "Mobile number does not exist.";
        } else {
            // Mobile number exists, update the password
            $update_password_stmt = $conn->prepare("UPDATE Register SET password = ? WHERE mobile = ?");
            $update_password_stmt->bind_param("ss", $password, $mobile);
            $update_password_stmt->execute();

            // Close update password statement
            $update_password_stmt->close();

            // Password updated successfully
            $error_message = "Password updated successfully.";
        }

        // Close mobile check statement
        $check_mobile_stmt->close();

        // Close connection
        $conn->close();
    }
}
?>

<?php if (!empty($error_message)): ?>
    <div class="<?php echo strpos($error_message, 'Password updated successfully') !== false ? 'success-message' : 'error-message'; ?>"><?php echo $error_message; ?></div>
<?php else: ?>
    <h2 style="color:white;">Update Password</h2>
<?php endif; ?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validateForm()">
    <div>
        <label for="mobile">Mobile Number:</label>
        <input type="tel" id="mobile" name="mobile" required value="<?php echo $mobile_value; ?>">
    </div>
    <div class="password-container">
        <label for="password">Enter New Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <span class="password-toggle" onclick="togglePassword()">👁️‍🗨️</span>
    </div>
    <div>
        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && $password_error_message !== ""): ?>
            <div class="error-message"><?php echo $password_error_message; ?></div>
        <?php endif; ?>
    </div>
    <div>
        <button style="background-color:green;color:white;" type="submit">Update</button>
        <button class="login-button" onclick="window.location.href='Login.php'">Login</button> <!-- Added Login button -->
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
        var mobile = document.getElementById("mobile").value;
        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("confirm_password").value;

        if (mobile.length !== 10 || isNaN(mobile)) {
            showError("Mobile number must be 10 digits long and contain only numbers.");
            return false;
        }

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

        return true;
    }

    function showError(message) {
        var errorMessageElement = document.querySelector(".error-message");
        errorMessageElement.textContent = message;
        errorMessageElement.style.display = "block";
    }
</script>

</body>
</html>
