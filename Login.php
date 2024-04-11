<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
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
        input[type="submit"],
        button {
            width: 300px; /* Reduce button size */
            padding: 10px; /* Reduce padding */
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-family: Arial, sans-serif; /* Apply the same font family */
            font-weight: bold; /* Make button text bold */
            text-transform: uppercase; /* Convert text to uppercase */
        }
        input[type="submit"] {
            background-color: blue; /* Change login button color to blue */
            color: white;
        }
        button {
            background-color: green; /* Change register button color to green */
            color: white;
        }
        a {
            text-decoration: underline;
            color: blue;
            margin-left:67px;
        }
    </style>
</head>
<body>

<?php
// Database connection details
$servername = "localhost"; // Replace "localhost" with your database host name if it's different
$username_db = "root"; // Replace "root" with your database username
$password_db = ""; // Replace "" with your database password
$dbname = "Register"; // Replace "Register" with your database name

// Initialize error message variables
$username_error_message = "";
$password_error_message = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch data from form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Create connection
    $conn = new mysqli($servername, $username_db, $password_db, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare statement to fetch user data based on username or mobile number
    $stmt_username = $conn->prepare("SELECT * FROM Register WHERE username = ? OR mobile = ?");
    $stmt_username->bind_param("ss", $username, $username);
    $stmt_username->execute();
    $result_username = $stmt_username->get_result();

    // Check if username or mobile number exists
    if ($result_username->num_rows == 0) {
        // Set username error message
        $username_error_message = "Username or mobile number does not match.";
    } else {
        // Prepare statement to fetch user data based on username/mobile number and password
        $stmt_password = $conn->prepare("SELECT * FROM Register WHERE (username = ? OR mobile = ?) AND password = ?");
        $stmt_password->bind_param("sss", $username, $username, $password);
        $stmt_password->execute();
        $result_password = $stmt_password->get_result();

        // Check if password matches
        if ($result_password->num_rows == 0) {
            // Set password error message
            $password_error_message = "Password does not match.";
        } else {
            // Redirect to dashboard after successful login
            header("Location: Forgotpassword.php");
            exit();
        }
        // Close password statement
        $stmt_password->close();
    }
    // Close username statement and connection
    $stmt_username->close();
    $conn->close();
}
?>

<?php if (!empty($username_error_message) || !empty($password_error_message)): ?>
    <h2 style="color:white";>
        <div class="error-message">
            <?php echo !empty($username_error_message) ? $username_error_message : ""; ?>
            <?php echo !empty($password_error_message) ? $password_error_message : ""; ?>
        </div>
    </h2>
<?php else: ?>
    <h2 style="color:white";>Login Page</h2>
<?php endif; ?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <div>
        <label for="username">Username or Mobile Number:</label>
        <input type="text" id="username" name="username" required>
    </div>
    <div class="password-container">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <span class="password-toggle" onclick="togglePassword()">👁️‍🗨️</span>
    </div>
    <div>
        <input type="submit" value="Login">
    </div>
    <div>
        <a href="Forgotpassword.php">Forgotten password?</a> <br><br> <button onclick="location.href='Register.php'">Register</button>
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
</script>

</body>
</html>
