<!DOCTYPE html>
<html lang="en">
<head>
    <title>Join</title>
    <link rel="stylesheet" href="join.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <div class="navbar">
        <div class="back">
            <a href="Home.php"><i class="material-icons">arrow_back</i></a>
        </div>
    </div>
    <div class="quiz">
        <h1>Join Quiz</h1>
    <form id="joinForm">
        <label for="quizPin">Enter Quiz PIN:</label>
        <input type="text" id="quizPin" name="quizPin">
        <button type="submit">Join Quiz</button>
    </form>
    </div>
    

    <script>
        document.getElementById("joinForm").addEventListener("submit", function(event) {
            event.preventDefault();

            var quizPinInput = document.getElementById("quizPin").value;

            fetch('pin.php')
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    } else {
                        throw new Error('Error in fetching quiz PINs');
                    }
                })
                .then(data => {
                    console.log(data); // Debugging: Check kung nakuha ng tama ang mga quiz PINs
                    var quizPINs = data;

                    if (quizPINs.includes(quizPinInput)) {
                        var pathToQuizHTML = "../Student/quiz.php";
                        var url = new URL(pathToQuizHTML, window.location.href);
                        url.searchParams.append('quizPIN', quizPinInput);
                        window.location.href = url.href;
                    } else {
                        alert("Invalid Quiz PIN");
                    }
                })
                .catch(error => {
                    console.error('Error:', error); // Console log ang error message
                    alert('Error in fetching quiz PINs');
                });
        });
    </script>
</body>
</html>
