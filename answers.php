<?php    
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "learnwithcapy";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$Login = 'logintbl';
$question = 'questions';
$tableName = 'answers';
$score = 'user_scores';

$data = json_decode(file_get_contents("php://input"));

if ($data) {
    if (isset($data->quizPin, $data->questionText, $data->questionType, $data->userAnswer)) {
        $quizPin = $data->quizPin;
        $questionText = $data->questionText;
        $questionType = $data->questionType;
        $userAnswer = $data->userAnswer;
        
        // Fetch username from logintbl based on quizPin
        $usernameQuery = "SELECT username FROM $Login WHERE $quizPin = ?";
        $stmtUsername = $conn->prepare($usernameQuery);
        $stmtUsername->bind_param("s", $quizPin);
        $stmtUsername->execute();
        $stmtUsername->bind_result($username);
        $stmtUsername->fetch();
        $stmtUsername->close();

        if (!isset($_SESSION['username'])) {
            echo "No users logged in";
            exit;
        }
        $username = $_SESSION['username'];

        $correctAnswersQuery = "SELECT id, correct_answer FROM $question WHERE quiz_pin = ?";
        $stmtCorrectAnswers = $conn->prepare($correctAnswersQuery);
        $stmtCorrectAnswers->bind_param("s", $quizPin);
        $stmtCorrectAnswers->execute();
        $resultCorrectAnswers = $stmtCorrectAnswers->get_result();
        $stmtCorrectAnswers->close();

        $correctAnswersData = [];
        while ($row = $resultCorrectAnswers->fetch_assoc()) {
            $correctAnswersData[$row['id']] = $row['correct_answer'];
        }

        $questionIdQuery = "SELECT id FROM $question WHERE quiz_pin = ? AND question_text = ?";
        $stmtQuestionId = $conn->prepare($questionIdQuery);
        $stmtQuestionId->bind_param("ss", $quizPin, $questionText);
        $stmtQuestionId->execute();
        $stmtQuestionId->bind_result($questionId);
        $stmtQuestionId->fetch();
        $stmtQuestionId->close();

        if (isset($correctAnswersData[$questionId])) {
            $correctAnswer = $correctAnswersData[$questionId];
            $isCorrect = ($userAnswer == $correctAnswer);

            $scoreQuery = "SELECT SUM(score) FROM $score WHERE quiz_pin = ? AND username = ?";
            $stmtScore = $conn->prepare($scoreQuery);
            $stmtScore->bind_param("ss", $quizPin, $username);
            $stmtScore->execute();
            $stmtScore->bind_result($currentScore);
            $stmtScore->fetch();
            $stmtScore->close();

            if ($isCorrect) {
                // Check if the user's score already exists in the table
                $existingScoreQuery = "SELECT COUNT(*) FROM $score WHERE quiz_pin = ? AND username = ?";
                $stmtExistingScore = $conn->prepare($existingScoreQuery);
                $stmtExistingScore->bind_param("ss", $quizPin, $username);
                $stmtExistingScore->execute();
                $stmtExistingScore->bind_result($rowCount);
                $stmtExistingScore->fetch();
                $stmtExistingScore->close();

                if ($rowCount > 0) {
                    // Update the existing row with the new score
                    $updateScoreQuery = "UPDATE $score SET score = score + 1 WHERE quiz_pin = ? AND username = ?";
                    $stmtUpdateScore = $conn->prepare($updateScoreQuery);
                    $stmtUpdateScore->bind_param("ss", $quizPin, $username);
                    $stmtUpdateScore->execute();
                    $stmtUpdateScore->close();
                } else {
                    // Insert a new row for the user's score for this quiz
                    $insertScoreQuery = "INSERT INTO $score (quiz_pin, username, score) VALUES (?, ?, 1)";
                    $stmtInsertScore = $conn->prepare($insertScoreQuery);
                    $stmtInsertScore->bind_param("ss", $quizPin, $username);
                    $stmtInsertScore->execute();
                    $stmtInsertScore->close();
                }
            }

            // Get the highest score for the user in the current quiz
            $highestScoreQuery = "SELECT MAX(score) FROM $score WHERE quiz_pin = ? AND username = ?";
            $stmtHighestScore = $conn->prepare($highestScoreQuery);
            $stmtHighestScore->bind_param("ss", $quizPin, $username);
            $stmtHighestScore->execute();
            $stmtHighestScore->bind_result($highestScore);
            $stmtHighestScore->fetch();
            $stmtHighestScore->close();

            // Show the highest score on the screen
            echo "Answer submitted successfully! Current Score: $username: $currentScore";
        } else {
            echo "Invalid question ID";
        }
    } else {
        echo "Missing required data properties";
    }
} else {
    echo "Invalid data format";
}

$conn->close();
?>
