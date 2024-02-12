<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "learnwithcapy";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Debugging
var_dump($_GET);

// Check if the 'id' parameter is set and not empty
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT username FROM logintbl WHERE id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $username = $row["username"];
    } else {
        $username = "No Username Found";
    }

    $stmt->close();
} else {
    $username = "No ID provided";
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Assets</title>
    <link rel="stylesheet" type="text/css" href="logout.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

    <div class="navbar">
        <div class="navicon">
            <a href="Home.html"><i class="fa-solid fa-arrow-left"></i></a>
        </div>
        <div class="logobar">
            <img src="logo3.png" alt="">
        </div>
    </div>

    <div class="buttons">
        <?php
        // Display a message if 'id' is not provided
        if ($username === "No ID provided") {
            echo "<p>Please provide an ID in the URL.</p>";
        } else {
            echo "<p>Welcome, $username!</p>";
        }
        ?>
        <a href="login.html"><button id="logoutButton">LOGOUT</button></a>
    </div>
</body>
</html>
