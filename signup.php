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
         
        <title>Sign Up - Competitive RPS</title>
    </head>
    <body>
        <!--Navbar stuff-->
        <?php
            include("./navbar.php");
        ?>
        <div class="main-container">
            <div class="home-center justify-content-center">
                <div id="Grid-Right" class="card position-relative">
                    <div class="card-header text-center">
                        Login
                    </div>
                    <div class="card-body">
                        <div id="Login-Form" class="container">
                            <form class="flexform" action="?command=createUser" method="post">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" aria-describedby="passwordHelp" placeholder="Enter your password">
                                    <small id="passwordHelp" class="form-text">Must include at least one number or special character.</small><br>
                                    <small id="passwordHelp" class="form-text">*Not required.</small>
                                </div>
                                <button type="submit" class="btn btn-lg">Sign Up</button>
                            </form> 
                        </div>
                        <br>
                        <?=$message?>
                    </div>
                </div>
            </div>
        </div>

        <!--Bootstrap and Less imports-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/less" ></script>
    </body>
</html>