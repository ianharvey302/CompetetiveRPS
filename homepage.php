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
         
        <title>Competitive RPS</title>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js" integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        
        <script>
            $(document).ready(function() {
                $("#username").on("keyup", updateButton);
                determineVisibilities();
            });

            function updateButton() {
                if($("#username").val().length != 0) {
                    $("#loginButton").prop("disabled", false);
                }
                else {
                    $("#loginButton").prop("disabled", true);
                }
            }

            function determineVisibilities() {
                var signed_in = <?php
                        if(!isset($_SESSION['signed_in'])) {
                            echo "true";
                        } else {
                            echo "false";
                        }
                    ?>;
                console.log(signed_in);
                if(signed_in) {
                    $("#welcome").remove();
                    $("#login").show();
                }
                else {
                    $("#welcome").show();
                    $("#login").remove();
                }
            }
        </script>
    </head>
    <body>
        <!--Navbar stuff-->
        <?php
            include("navbar.php");
        ?>
        <div class="main-container">
            <div class="home-center justify-content-center">
                <div id="Grid-Left" class="home-column">
                    <div id="CardOne" class="card position-relative border border-dark border-2 rounded">
                        <img class="card-img-top" src="images/GamePreview.png" alt="A preview of a game">
                        <div class="card-body">
                            <a href="?command=play" class="card-button btn w-100 stretched-link">
                                Play
                            </a>
                        </div>
                    </div>
                    <div id="CardTwo" class="card position-relative border border-dark border-2 rounded">
                        <img class="card-img-top" src="images/GlobalStatsPreview.png" alt="A preview of the global stats page">
                        <div class="card-body">
                            <a href="?command=global" class="card-button btn w-100 stretched-link">
                                Global Stats
                            </a>
                        </div>
                    </div>
                </div>
                <div id="Grid-Right" class="card position-relative">
                    <div id="login">
                        <div class="card-header text-center">
                        Login
                        </div>
                        <div class="card-body">
                            <div id="Login-Form" class="container">
                                <form class="flexform" action="?command=login" method="post">
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" aria-describedby="passwordHelp" placeholder="Enter your password">
                                        <small id="passwordHelp" class="form-text">*Not required if not set.</small>
                                    </div>
                                    <button id="loginButton" type="submit" class="btn btn-lg" disabled>Login</button>
                                </form> 
                                <span>~ OR ~</span>
                                <a style="width: 100%;" href="?command=signup" class="btn btn-lg">Sign Up</a>
                            </div>
                        </div>
                    </div>
                    <div id="welcome">
                        <div class="card-header text-center" aria-label="User Welcome" role="banner">
                            Welcome <?php echo $_SESSION["username"] ?>!
                        </div>
                        <div class="card-body">
                            <div id="Profile-Buttons" class="container">
                                <a style="width: 100%" href="?command=profile" class="btn btn-lg">View Profile</a>
                                <a style="width: 100%" href="?command=logout" class="btn btn-lg">Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <?=$message?>
            </div>

        </div>

        <!--Bootstrap and Less imports-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/less" ></script>
    </body>
</html>