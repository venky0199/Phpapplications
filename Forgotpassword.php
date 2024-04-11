<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=0.78">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ-sDdzI_R3oK43XPF9i6LIJBbY9vSBjeMwF0XMwXEzDA&usqp=CAU&ec=48665701');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
            padding: 50px;
            border-radius: 20px;
        }
        .text-container {
            padding: 40px;
            border-radius: 40px;
            margin-bottom: 100px;
            display: inline-block;
            margin-top: -50px;
        }
        form {
            padding: 30px; /* Increased padding for the form */
            border-radius: 10px; /* Adjusted border-radius */
            background-color: white;
        }
        label {
            display: block;
            margin-bottom: 10px;
            color: black;
        }
        input[type="text"],
        input[type="password"] {
            width: 200px;
            padding: 10px;
            border: 2px solid black;
            border-radius: 5px;
            margin-right: 10px;
            margin-bottom: 10px;
            font-weight: bold;
        }
        input[type="submit"],
        input[type="button"] {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
        }
        input[type="button"] {
            background-color: #dc3545;
            color: #fff;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        input[type="button"]:hover {
            background-color: #bd2130;
        }
        .error-message {
            color: red;
            margin-top: 10px;
        }
        .success-message {
            color: green;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="login-container">
    <form method="post" action="">
        <input type="text" id="username_or_mobile" placeholder="username or mobile number" name="username" required>
        <input type="password" id="password" placeholder="password" name="password" required>
        <input type="submit" value="Login">
        <input type="button" value="Register" onclick="window.location.href='Register.php'">
        <?php
        // Check if form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Fetch data from form
            $username = isset($_POST["username"]) ? $_POST["username"] : "";
            $password = isset($_POST["password"]) ? $_POST["password"] : "";

            // Check if username and password are provided
            if (!empty($username) && !empty($password)) {
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

                // Prepare statement to fetch user data based on username or mobile number
                $stmt_username = $conn->prepare("SELECT * FROM Register WHERE username = ? OR mobile = ?");
                $stmt_username->bind_param("ss", $username, $username);
                $stmt_username->execute();
                $result_username = $stmt_username->get_result();

                // Check if username or mobile number exists
                if ($result_username->num_rows == 0) {
                    // Set username error message
                    echo "<div class='error-message'>Username or mobile number incorrect.</div>";
                } else {
                    // Prepare statement to fetch user data based on username/mobile number and password
                    $stmt_password = $conn->prepare("SELECT * FROM Register WHERE (username = ? OR mobile = ?) AND password = ?");
                    $stmt_password->bind_param("sss", $username, $username, $password);
                    $stmt_password->execute();
                    $result_password = $stmt_password->get_result();

                    // Check if password matches
                    if ($result_password->num_rows == 0) {
                        // Set password error message
                        echo "<div class='error-message'>Password is incorrect.</div>";
                    } else {
                        // Redirect to forgot password page after successful login
                        header("Location: Register.php");
                        exit();
                    }
                    // Close password statement
                    $stmt_password->close();
                }
                // Close username statement and connection
                $stmt_username->close();
                $conn->close();
            } 
        }
        ?>
    </form>
</div>
<div class="text-container">
    <form method="post" action="">
        <h2 style="color: black;">Forgot Password?</h2>
        <b><label for="username_or_mobile">Enter Username or Mobile Number:</label></b>
        <input type="text" id="username_or_mobile" name="username_or_mobile" required><br>
        <input type="submit" value="Search">
        <input type="button" value="Cancel" onclick="window.location.href='Login.php'">
        <?php
        // Check if form 2 (forgot password) is submitted and if username_or_mobile key is set
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["forgot_password_form"]) && isset($_POST["username_or_mobile"])) {
            // Fetch data from form
            $username_or_mobile = isset($_POST["username_or_mobile"]) ? $_POST["username_or_mobile"] : "";

            // Check if username or mobile number is provided
            if (!empty($username_or_mobile)) {
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

                // Prepare statement to fetch user data based on username or mobile number
                $stmt = $conn->prepare("SELECT * FROM Register WHERE username = ? OR mobile = ?");
                if ($stmt === false) {
                    die("Error preparing statement: " . $conn->error);
                }

                // Bind parameters
                $stmt->bind_param("ss", $username_or_mobile, $username_or_mobile);
                $stmt->execute();
                $result = $stmt->get_result();

                // Check if username or mobile number exists
                if ($result->num_rows == 0) {
                    // Set username or mobile number error message
                    echo "<div class='error-message'>Username or mobile number not found.</div>";
                } else {
                    // If username or mobile number is found, redirect to a new page
                    header("Location: PasswordCorrection.php");
                    exit();
                }

                // Close statement and connection
                $stmt->close();
                $conn->close();
            } 
        }
        ?>
        <input type="hidden" name="forgot_password_form" value="1">
    </form>
</div>
</body>
</html>
