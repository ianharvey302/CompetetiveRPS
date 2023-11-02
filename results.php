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
         
        <title>Match Results - Competitive RPS</title>
    </head>
    <body>
        <!--Navbar stuff-->
        <?php
            include("navbar.php");
        ?>

        <div class="main-container">
            <div class="centered-container-flex">
                <div class="match-text">
                    <h1>Match Results (Opponent: <strong>Guest</strong>)</h1>
                </div>
                <h1 style="font-size: 10vh;">You <?php echo $_SESSION["displayed_match_result"]?>!</h1>
                <div style="margin-top: 20%;">
                <a href="?command=play" class="btn btn-lg">Play Again</a>
                <a href="?command=home" class="btn btn-lg">Back to Home</a>
                </div>
            </div>
        </div>

        <!--Bootstrap and Less imports-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/less" ></script>
    </body>
</html>