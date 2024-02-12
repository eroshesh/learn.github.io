<?php
    session_start();
    if (!isset($_SESSION['username'])) {
        // Redirect or handle the case when the user is not logged in
        header("Location: login.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz</title>
    <link rel="stylesheet" href="quiz.css">
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

        #quizContainer {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 30px;
            width: 80%;
            margin: 120px auto 20px;

        }

        .question-form {
            margin-bottom: 20px;
        }

        fieldset {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 10px;
        }

        legend {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 10px;
        }

        label {
            margin-right: 10px;
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

        /* Style for the dialog */
        #scoreDialog {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            text-align: center; /* Center the content within the dialog */
        }    
    </style>
</head>
<body>
    <div id="quizContainer"></div>
    <dialog id="scoreDialog">
        <p id="scoreMessage"></p>
        <button id="closeDialog">Close</button>
    </dialog>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var urlParams = new URLSearchParams(window.location.search);
            var quizPin = urlParams.get('quizPIN'); 
            var questions;
            quizPin = encodeURIComponent(quizPin);

            if (quizPin) {
                fetch('questions.php?quiz_pin=' + quizPin)
                    .then(response => {
                        if (response.ok) {
                            return response.json();
                        } else {
                            throw new Error('Error fetching questions for the quiz pin');
                        }
                    })
                    .then(data => {
                        console.log(data);
                        if (Array.isArray(data)) {
                            questions = data;
                            displayQuestions(questions);
                        } else {
                            console.error('Invalid data format: questions is not an array');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error fetching questions for the quiz pin');
                    });
            }

            function displayQuestions(questions) {
            var quizContainer = document.getElementById('quizContainer');
            var currentDate = new Date();

            questions.forEach(question => {
                var deadlineDate = new Date(question.deadline_date + ' ' + question.deadline_time);

                // Check if the deadline has not passed
                if (deadlineDate > currentDate) {
                    var form = document.createElement('form');
                    form.classList.add('question-form');

                    var fieldset = document.createElement('fieldset');
                    fieldset.classList.add('question');

                    var legend = document.createElement('legend');
                    legend.textContent = question.question_text;
                    fieldset.appendChild(legend);

                    var optionType = question.question_type.toLowerCase().trim();

                    if (optionType === 'multiple choice') {
                        var options = question.options.split(',');
                        options.forEach((option, index) => {
                            var optionInput = document.createElement('input');
                            optionInput.type = 'radio';
                            optionInput.name = 'question_' + question.id;
                            var uniqueId = `option_${question.id}_${index}`;
                            optionInput.value = option.trim();
                            optionInput.setAttribute('id', uniqueId);

                            var optionLabel = document.createElement('label');
                            optionLabel.textContent = option.trim();
                            optionLabel.setAttribute('for', uniqueId);

                            fieldset.appendChild(optionInput);
                            fieldset.appendChild(optionLabel);
                            fieldset.appendChild(document.createElement('br'));
                        });
                    } else if (optionType === 'true or false') {
                        var trueInput = document.createElement('input');
                        trueInput.type = 'radio';
                        trueInput.name = 'question_' + question.id;
                        var uniqueTrueId = `true_${question.id}`;
                        trueInput.value = 'True';
                        trueInput.setAttribute('id', uniqueTrueId);

                        var trueLabel = document.createElement('label');
                        trueLabel.textContent = 'True';
                        trueLabel.setAttribute('for', uniqueTrueId);

                        fieldset.appendChild(trueInput);
                        fieldset.appendChild(trueLabel);

                        var falseInput = document.createElement('input');
                        falseInput.type = 'radio';
                        falseInput.name = 'question_' + question.id;
                        var uniqueFalseId = `false_${question.id}`;
                        falseInput.value = 'False';
                        falseInput.setAttribute('id', uniqueFalseId);

                        var falseLabel = document.createElement('label');
                        falseLabel.textContent = 'False';
                        falseLabel.setAttribute('for', uniqueFalseId);

                        fieldset.appendChild(falseInput);
                        fieldset.appendChild(falseLabel);
                        fieldset.appendChild(document.createElement('br'));
                    }

                    form.appendChild(fieldset);
                    quizContainer.appendChild(form);
                } else {
                    // Redirect to home.php if the deadline has passed
                    window.location.href = 'home.php';
                }
            });

                var submitButton = document.createElement('button');
                submitButton.type = 'button'; // Change the type to 'button'
                submitButton.textContent = 'Submit';
                quizContainer.appendChild(submitButton);

                submitButton.addEventListener('click', function() {
                    // Loop through all questions and submit answers
                    questions.forEach(question => {
                        var selectedAnswer = getSelectedAnswer(question.id);
                        if (selectedAnswer !== null) {
                            submitAnswer(question, selectedAnswer);
                        } else {
                            alert('Please select an answer for Question ' + question.id);
                        }
                    });
                });

            }

            function getSelectedAnswer(questionId) {
                var radioButtons = document.querySelectorAll('input[name="question_' + questionId + '"]');
                for (var i = 0; i < radioButtons.length; i++) {
                    if (radioButtons[i].checked) {
                        return radioButtons[i].value;
                    }
                }
                return null;
            }

            function submitAnswer(question, userAnswer) {
                fetch('answers.php?quiz_pin=' + quizPin, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        quizPin: quizPin,
                        questionText: question.question_text,
                        questionType: question.question_type,
                        userAnswer: userAnswer
                    })
                })
                .then(response => {
                    if (response.ok) {
                        return response.text();
                    } else {
                        throw new Error('Error submitting answer for question ' + question.id);
                    }
                })
                .then(data => {
                    console.log(data);
            
                    // Redirect to a new page upon successful submission
                    window.location.href = 'result.php?quizPIN=' + quizPin;
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error submitting answer for question ' + question.id);
                });
            }
        });
    </script>
</body>
</html>
