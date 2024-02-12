<?php
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "learnwithcapy";

    $conn = new mysqli($host, $user, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT DISTINCT quiz_pin FROM questions";
    $result = $conn->query($sql);

    $quizPins = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $quizPins[] = $row['quiz_pin'];
        }
    }

    header('Content-Type: application/json');
    echo json_encode($quizPins);
?>
