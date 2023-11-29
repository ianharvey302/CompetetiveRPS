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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js" integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script>
            var usernameGood = false;
            var passwordGood = true;

            $(document).ready(function() {
                $("#username").on("keyup", checkUsername);
                $("#password").on("keyup", checkPassword);
            });

            function checkUsername() {
                if($("#username").val().length == 0) {
                    $("#usernameMessage").html("");
                    usernameGood = false;
                    enableCheck();
                    return;
                }

                $.get("?command=checkUsername&username=" + $("#username").val(), function(data) {
                    var result = JSON.parse(data).result;
                    if(!result) {
                        $("#usernameMessage").html("<div class=\"alert alert-danger text-center\" role=\"alert\">Username already taken</div>");
                        usernameGood = false;
                    }
                    else {
                        $("#usernameMessage").html("");
                        usernameGood = true;
                    }
                    enableCheck();
                });
            }

            function checkPassword() {
                if($("#password").val().length != 0 && $("#password").val().length < 8) {
                    $("#passwordMessage").html("<div class=\"alert alert-danger text-center\" role=\"alert\">Password must be at least 8 characters long if set</div>");
                    passwordGood = false;
                }
                else if($("#password").val().length != 0 && $("#password").val().search(/^.*[\W\d_]+.*$/) == -1) {
                    $("#passwordMessage").html("<div class=\"alert alert-danger text-center\" role=\"alert\">Password must contain at least one special character.</div>");
                    passwordGood = false;
                }
                else if($("#password").val().length != 0 && $("#password").val().search(/Guest/) != -1) {
                    $("#passwordMessage").html("<div class=\"alert alert-danger text-center\" role=\"alert\">Username cannot contain \"Guest\" </div>");
                    passwordGood = false;
                }
                else {
                    $("#passwordMessage").html("");
                    passwordGood = true; 
                }
                enableCheck();
            }

            function enableCheck() {
                console.log(usernameGood);
                if(usernameGood && passwordGood)
                    $("#submitButton").prop("disabled", false);
                else
                    $("#submitButton").prop("disabled", true);
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
                <div id="Grid-Right" class="card position-relative">
                    <div class="card-header text-center">
                        Sign Up
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
                                <button id="submitButton" type="submit" class="btn btn-lg" disabled>Sign Up</button>
                            </form> 
                        </div>
                        <br>
                        <div id="usernameMessage"></div>
                        <div id="passwordMessage"></div>
                    </div>
                </div>
            </div>
        </div>

        <!--Bootstrap and Less imports-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/less" ></script>
    </body>
</html>