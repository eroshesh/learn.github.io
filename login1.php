<?php
    session_start();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $db_name = "learnwithcapy";
    
    $conn = new mysqli($servername, $username, $password, $db_name);
    
    if ($conn->connect_error) {
        die("Connection Failed: " . $conn->connect_error);
    }

    if(isset($_POST['submit'])){
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM logintbl WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows == 1){
            // After validating the username and password
            $_SESSION['username'] = $username;

            header("Location: Home.php");
            exit();
        } else {
            // Set an error message for invalid username or password
            $error_message = 'Invalid username or password';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
</head>
<body>

    <?php
        // Display the error message if it's not empty
        if (!empty($error_message)) {
            echo '<p>' . $error_message . '</p>';
        }
    ?>

    <!-- Your login form goes here -->
    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <br>
        <input type="submit" name="submit" value="Login">
    </form>

</body>
</html>