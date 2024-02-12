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
            $_SESSION['logged_in_users'][$username] = true;

            header("Location: Home.php");
            exit();
        } else {
            echo 'Invalid username or password';
        }
    }
?>
