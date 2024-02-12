<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="design.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<head>
    <title>LearnWithCapy</title>
    <link rel="icon" type="images/png" href="logo latest.jpg" />
</head>
<body>
    <div class="bar">       
        <div id="Sidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>

        <div class="navshow">
            <div class="barclick">
            <a href="Games.html">Games</a>
            <a href="Todo.html">To-do List</a>
            <a href="Namepicker.html">Namepicker</a>
            <a href="join.php">Join Quiz</a>
            <a href="Schedule.html">Schedule</a>
            <a href="Feedback.html">Feedback</a>
            </div>
        </div>
    </div>
    <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776;</span>

    <div class="logobar">
        <a href="logout.php"><img src="logo latest.jpg"></a>
    </div>
    </div>
    <div class="row">
            <div class="col">
                <h1>LearnwithCapy</h1>
                <p>ASDWASDWASDWASDWASDWASDAWDASD.</p>
            </div>    
            <div class="col">
                <div class="card card1">
                    <a href="informations.html"><h6>Informations</h6></a>
                    
                </div>
                <div class="card card2">
                    <a href="features.html"><h6>Features</h6></a>
                    
                </div>
                <div class="card card3">
                    <a href="tutorials.html"><h6>Tutorials</h6></a>
                    
                </div>
                <div class="card card4">
                    <a href="announcements"><h6>Announcements</h6></a>
                    
                </div>
            </div>
        </div>

    <script>
        function openNav() {
            document.getElementById("Sidenav").style.width = "250px";
        }

        function closeNav() {
            document.getElementById("Sidenav").style.width = "0";
        }
    </script>

</body>
</html>