<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- URL: https://cs4640.cs.virginia.edu/gxz6ja/CompetitiveRPS/ -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 

        <meta name="author" content="Ian Harvey, Andrew Fournie">
        <meta name="description" content="Play Rock Paper Scissors online with your friends!">
        <meta name="keywords" content="Rock Paper Scissors, Competitive, RPS, Home">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="stylesheet/less" type="text/css" href="styles/main.less">

        <link rel="stylesheet" href="styles/main.css">
         
        <title>Competitive RPS</title>
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
            <div class="home-center justify-content-center">
                <div id="Grid-Left" class="home-column">
                    <div id="CardOne" class="card position-relative">
                        <img class="card-img-top" src="images/DefaultProfile.png" alt="A preview of a game">
                        <div class="card-body">
                            <a href="?command=play" class="card-button btn w-100 stretched-link">
                                Play
                            </a>
                        </div>
                    </div>
                    <div id="CardTwo" class="card position-relative">
                        <img class="card-img-top" src="images/DefaultProfile.png" alt="A preview of the global stats page">
                        <div class="card-body">
                            <a href="?command=global" class="card-button btn w-100 stretched-link">
                                Global Stats
                            </a>
                        </div>
                    </div>
                </div>
                <div id="Grid-Right" class="card position-relative">
                    <?php
                    if (!isset($_SESSION['signed_in'])) {
                        echo '
                        <div class="card-header text-center">
                        Login
                        </div>
                        <div class="card-body">
                            <div id="Login-Form" class="container">
                                <form class="flexform" action="?command=login" method="post">
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control" id="username" placeholder="Enter your username" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" id="password" aria-describedby="passwordHelp" placeholder="Enter your password">
                                        <small id="passwordHelp" class="form-text">*Not required if not set.</small>
                                    </div>
                                    <button type="submit" class="btn btn-lg">Login</button>
                                    <span>~ OR ~</span>
                                    <button type="submit" formaction="?command=signup" class="btn btn-lg">Sign Up</button>
                                </form> 
                            </div>
                        </div>
                        ';
                    }
                    
                    else echo '
                        <div class="card-header text-center">
                            Welcome ' . $_SESSION["username"] . '
                        </div>
                        <div class="card-body">
                            <div id="Profile-Buttons" class="container">
                                <a href="?command=profile" class="btn btn-lg">View Profile</button>
                                <a href="?command=logout" class="btn btn-lg">Logout</button>
                            </div>
                        </div>
                    ';
                    ?>

                </div>
            </div>

        </div>

        <!--Bootstrap and Less imports-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/less" ></script>
    </body>
</html>