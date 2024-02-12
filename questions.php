<?php
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "learnwithcapy";

    $conn = new mysqli($host, $user, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $quiz_pin = $_GET['quiz_pin'];

    $sql = "SELECT id, question_text, options, question_type, deadline_date, deadline_time FROM questions WHERE quiz_pin = '$quiz_pin'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $questions = [];
        while ($row = $result->fetch_assoc()) {
            $questions[] = $row;
        }

        echo json_encode($questions);
    } else {
        echo json_encode(["message" => "No questions found for the given quiz pin"]);
    }

    $conn->close();
?>
