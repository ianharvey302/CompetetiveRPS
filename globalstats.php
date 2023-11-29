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
         
        <title>Global Stats - Competitive RPS</title>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js" integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script>
            $(document).ready(function() {
                refreshGlobal();
            });

            function refreshGlobal() {
                $.get("?command=globalStats", (data) => {
                    var result = JSON.parse(data);
                    console.log(result);
                    if(parseInt(result['numWin']) + parseInt(result['numTie']) + parseInt(result['numLoss']) == 0) {
                        $("#usage-per").html("");
                        $("#noGame").show();
                    }
                    else {
                        $("#noGame").hide();
                        $("#usage-per").html("<h3><span style='color: #0b8b0b'>Win</span>-<span style='color: #59e6ff'>Tie</span>-<span style='color: #ef0000'>Loss</span> Percentage</h3><div id='WLT' class='progress'></div><h3><span style='color: #be5847'>Rock</span>-<span style='color: #ff5599'>Paper</span>-<span style='color: #92705f'>Scissor</span> Usage</h3><div id='RPS' class='progress'></div>");
                        $("#WLT").html(wlt(result['numWin'], result['numTie'], result['numLoss']));
                        $("#RPS").html(rps(result['numRock'], result['numPaper'], result['numScissors']));
                    }
                    if(parseInt(result['numRock']) == 0) {
                        $("#rock").html("");
                        $("#noRock").show();
                    }
                    else {
                        $("#noRock").hide();
                        $("#rock").html("<h3><span style='color: #be5847'>Rock</span> <span style='color: #0b8b0b'>Win</span>-<span style='color: #59e6ff'>Tie</span>-<span style='color: red'>Loss</span> Percentage</h3><div id='rock-wr' class='progress'></div");
                        $("#rock-wr").html(wlt(result['numRockWin'], result['numRockTie'], result['numRockLoss']));
                    }
                    if(parseInt(result['numPaper']) == 0) {
                        $("#paper").html("");
                        $("#noPaper").show();
                    }
                    else {
                        $("#noPaper").hide();
                        $("#paper").html("<h3><span style='color: #ff5599'>Paper</span> <span style='color: #0b8b0b'>Win</span>-<span style='color: #59e6ff'>Tie</span>-<span style='color: red'>Loss</span> Percentage</h3><div id='paper-wr' class='progress'></div>");
                        $("#paper-wr").html(wlt(result['numPaperWin'], result['numPaperTie'], result['numPaperLoss']));
                    }
                    if(parseInt(result['numScissors']) == 0) {
                        $("#scissors").html("");
                        $("#noScissors").show();
                    }
                    else {
                        $("#noScissors").hide();
                        $("#scissors").html("<h3><span style='color: #92705f'>Scissor</span> <span style='color: #0b8b0b'>Win</span>-<span style='color: #59e6ff'>Tie</span>-<span style='color: red'>Loss</span> Percentage</h3><div id='scissor-wr' class='progress'></div>");
                        $("#scissor-wr").html(wlt(result['numScissorsWin'], result['numScissorsTie'], result['numScissorsLoss']));
                    }
                });
                setTimeout(refreshGlobal, 20000);
            }
            function wlt(x, y, z) {
                var total = parseInt(x) + parseInt(y) + parseInt(z);
                var px = x * 100 / total;
                var py = y / total * 100;
                var pz = z / total * 100;
                return "<div class='progress-bar win' role='progressbar' style='width: " + px + "%' aria-valuenow='" + px + "' aria-valuemin='0' aria-valuemax='100'>" + px.toFixed(2) + "%</div>" +
                       "<div class='progress-bar tie' role='progressbar' style='width: " + py + "%' aria-valuenow='" + py + "' aria-valuemin='0' aria-valuemax='100'>" + py.toFixed(2) + "%</div>" +
                       "<div class='progress-bar loss' role='progressbar' style='width: " + pz + "%' aria-valuenow='" + pz + "' aria-valuemin='0' aria-valuemax='100'>" + pz.toFixed(2) + "%</div>";
            }

            function rps(x, y, z) {
                var total = parseInt(x) + parseInt(y) + parseInt(z);
                var px = x * 100 / total;
                var py = y / total * 100;
                var pz = z / total * 100;
                return "<div class='progress-bar rock-btn' role='progressbar' style='width: " + px + "%' aria-valuenow='" + px + "' aria-valuemin='0' aria-valuemax='100'>" + px.toFixed(2) + "%</div>" +
                       "<div class='progress-bar paper-btn' role='progressbar' style='width: " + py + "%' aria-valuenow='" + py + "' aria-valuemin='0' aria-valuemax='100'>" + py.toFixed(2) + "%</div>" +
                       "<div class='progress-bar scissor-btn' role='progressbar' style='width: " + pz + "%' aria-valuenow='" + pz + "' aria-valuemin='0' aria-valuemax='100'>" + pz.toFixed(2) + "%</div>";
            }
        </script>
    </head>
    <body>
        <!--Navbar stuff-->
        <?php
            include("navbar.php");
        ?>

        <div class="main-container">
            <div class="centered-container-flex">
                <h1>Global Stats</h1>
                <div id="usage-per" class="usage-per"></div>
                <h3 id="noGame">No games have been played yet</h3>
                <div id="wl-per" class="wl-per">
                    <div id="rock"></div>
                    <h3 id="noRock">No players have played rock in a game yet</h3>
                    <div id="paper"></div>
                    <h3 id="noPaper">No players have played paper in a game yet</h3>
                    <div id="scissors"></div>
                    <h3 id="noScissors">No players have played scissors in a game yet</h3>
                </div>
            </div>
        </div>

        <!--Bootstrap and Less imports-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/less" ></script>
    </body>
</html>