<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $db_name = "learnwithcapy";

    // Siguraduhing mayroong POST request at mayroong data mula sa form
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["rating"]) && isset($_POST["feedback_text"])) {
        $rating = $_POST["rating"];
        $feedbackText = $_POST["feedback_text"];

        // Konektahin sa database
        $conn = new mysqli($servername, $username, $password, $db_name);

        // Suriin kung successful ang connection
        if ($conn->connect_error) {
            die("Connection Failed: " . $conn->connect_error);
        }

        // Prepare INSERT statement para sa database (using parameterized query)
        $sql = "INSERT INTO feed_back (rating, feedback) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);

        // Bind ng parameters at execution ng statement
        $stmt->bind_param("is", $rating, $feedbackText);
        if ($stmt->execute()) {
            echo "Feedback successfully saved!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // Isara ang statement at connection
        $stmt->close();
        $conn->close();
    } else {
        echo "Incomplete data received.";
    }
?>
