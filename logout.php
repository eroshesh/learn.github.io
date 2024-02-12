<?php
    session_start();
    if (!isset($_SESSION['username'])) {
        // Redirect or handle the case when the user is not logged in
        header("Location: login.php");
        exit();
    }
?>
<!DOCTYPE html>
<html>
<head>
  <title>Assets</title>
  <link rel="stylesheet" type="text/css" href="logout.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    
    <div class="navbar">
        <div class="back">
            <a href="Home.php"><i class="material-icons">arrow_back</i></a>
        </div>

        <div class="logobar">
            <img src="logo latest.jpg">
        </div>
    </div>

    <div class="buttons">
        <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
        <a href="login.php"><button id="logoutButton">LOGOUT</button></a>
    </div>
</body>
</html>
