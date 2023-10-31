<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 

        <meta name="author" content="Ian Harvey, Andrew Fournie">
        <meta name="description" content="Play Rock Paper Scissors online with your friends!">
        <meta name="keywords" content="Rock Paper Scissors, Competitive, RPS, Home">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="stylesheet/less" type="text/css" href="styles/main.less">

        <link rel="stylesheet" href="styles/main.css">
         
        <title>Ongoing Game - Competitive RPS</title>
    </head>
    <body>
        <!--Navbar stuff-->
        <nav class="navbar navbar-expand">
            <a class="navbar-brand" href="?command=home">
                Competitive RPS
            </a>
            <ul class="navbar-nav me-auto">
                <li class="vr"></li>
                <li class="nav-item">
                    <a href="?command=play" class="btn btn-lg">Play!</a>
                </li>
            </ul>
            <ul class="nav navbar-nav ms-auto">
                <li class="nav-item">
                    <!--For the account pfp-->
                    <a class="nav-link" href="#"  id="ProfileIcon">
                        <span id="Profile-Text">Profile</span>
                        <img class="rounded"  src="images/DefaultProfile.png" alt="The user's profile picture">
                    </a>
                </li>
            </ul>
        </nav>

        <div class="main-container">
            <div class="centered-container-flex">
                <div class="match-text">
                    <h1>Opponent: <strong>Guest</strong></h1>
                    <h5><strong>Guest</strong> is selecting move</h5>
                    <h3>Select Your Move (10s left):</h3>
                </div>
                <div class="match-moves" role="group" aria-label="Moves">
                    <button type="button" class="btn move-btn rock-btn">ROCK</button>
                    <button type="button" class="btn move-btn paper-btn">PAPER</button>
                    <button type="button" class="btn move-btn scissor-btn">SCISSORS</button>
                </div>
                <div class="match-submit">
                    <button type="button" class="btn btn-lg lock-btn">LOCK IN</button>
                    <a href="#" class="nav-link forfeit-btn">Forfeit</a>
                </div>
            </div>
        </div>

        <!--Bootstrap and Less imports-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/less" ></script>
    </body>
</html>