<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz Result</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        #resultContainer {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 30px;
            width: 60%;
            margin: 20px auto;
        }

        h1 {
            text-align: center;
            color: #007bff;
        }

        .user-score {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            text-align: center;
        }

        .user-score h2 {
            color: #555;
        }

        hr {
            margin: 20px 0;
            border: none;
            border-top: 1px solid #ddd;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 12px 24px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 25px; /* Increased border-radius for a rounder button */
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div id="resultContainer">
        <h1>Quiz Result</h1>
        <?php
        session_start(); // Start the session

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "learnwithcapy";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if the user is logged in
        if (isset($_SESSION['username'])) {
            $loggedInUsername = $_SESSION['username'];
            $quizPin = isset($_GET['quizPIN']) ? $_GET['quizPIN'] : null;

            if ($quizPin) {
                // Fetch user scores for the logged-in user and the specific quiz
                $userScoresQuery = "SELECT * FROM user_scores WHERE quiz_pin = ? AND username = ?";
                $stmtUserScores = $conn->prepare($userScoresQuery);
                $stmtUserScores->bind_param("ss", $quizPin, $loggedInUsername);
                $stmtUserScores->execute();
                $resultUserScores = $stmtUserScores->get_result();
                $stmtUserScores->close();

                // Display the user's scores
                while ($row = $resultUserScores->fetch_assoc()) {
                    echo '<div class="user-score">';
                    echo '<h2>Username: ' . $row['username'] . '</h2>';
                    echo '<p>Score: ' . $row['score'] . '</p>';
                    // Add other information you want to display
                    echo '</div>';
                    echo '<hr>';
                }
            } else {
                echo "<p>Invalid quiz PIN</p>";
            }
        } else {
            echo "<p>User not logged in</p>";
        }

        $conn->close();
        ?>
        <button onclick="goToHome()">Go To Home</button>
    </div>

    <script>
        function goToHome() {
            window.location.href = "Home.php";
        }
    </script>
</body>
</html>
