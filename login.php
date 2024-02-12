<?php
    header("Access-Control-Allow-Origin: *");
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
  <title>Login Form</title>
  <link rel="stylesheet" href="login.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <script>
        function displayError(message) {
            alert(message);
        }
    </script>
    
</head>
<body>
  
  <?php
        // Display the error message if it's not empty
        if (!empty($error_message)) {
            echo '<script>displayError("Invalid username or Password");</script>';
        }
  ?>
  <div class="navbar">
    <a href="../path_login/path_login.php"><i class="material-icons">arrow_back</i></a>
  </div>
  <div class="login-container">
    <div class="logo-container">
      <img src="logo3.png" alt="Logo">
    </div> 
    <h2>Login</h2>
    <form action="login.php" method="post">
      <div class="input-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username">
      </div>
      <div class="input-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password">
      </div>
      <button type="submit" name="submit">Login</button>
    </form>
  </div>
</body>
</html>
