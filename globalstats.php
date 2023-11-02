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
    </head>
    <body>
        <!--Navbar stuff-->
        <?php
            include("navbar.php");
        ?>

        <div class="main-container">
            <div class="centered-container-flex">
                <h1>Global Stats</h1>
                <div class="usage-per">
                    <?php
                        if($total > 0) {
                            echo '
                            <h3><span style="color: #0b8b0b">Win</span>-<span style="color: #59e6ff">Tie</span>-<span style="color: #ef0000">Loss</span> Percentage</h3>
                            <div class="progress">
                                <div class="progress-bar win" role="progressbar" style="width: ' . $pWin . '%" aria-valuenow="' . $pWin .'" aria-valuemin="0" aria-valuemax="100">' . $pWin . '%</div>
                                <div class="progress-bar tie" role="progressbar" style="width: ' . $pTie . '%" aria-valuenow="' . $pTie . '" aria-valuemin="0" aria-valuemax="100">' . $pTie . '%</div>
                                <div class="progress-bar loss" role="progressbar" style="width: ' . $pLoss .'%" aria-valuenow="' . $pLoss .'" aria-valuemin="0" aria-valuemax="100">' . $pLoss .'%</div>
                            </div>
                            <h3><span style="color: #be5847">Rock</span>-<span style="color: #ff5599">Paper</span>-<span style="color: #92705f">Scissor</span> Usage</h3>
                            <div class="progress">
                                <div class="progress-bar rock-btn" role="progressbar" style="width: ' . $pRock . '%" aria-valuenow="' . $pRock .'" aria-valuemin="0" aria-valuemax="100">' . $pRock . '%</div>
                                <div class="progress-bar paper-btn" role="progressbar" style="width: ' . $pPaper . '%" aria-valuenow="' . $pPaper . '" aria-valuemin="0" aria-valuemax="100">' . $pPaper . '%</div>
                                <div class="progress-bar scissor-btn" role="progressbar" style="width: ' . $pScissors .'%" aria-valuenow="' . $pScissors .'" aria-valuemin="0" aria-valuemax="100">' . $pScissors .'%</div>
                            </div>
                            ';
                        }
                        else {
                            echo "<h3>No games have been played yet</h3>";
                        }
                    ?>
                </div>
                <div class="wl-per">
                    <?php
                        if($numRock > 0) {
                            echo '
                            <h3><span style="color: #be5847">Rock</span> <span style="color: #0b8b0b">Win</span>-<span style="color: #59e6ff">Tie</span>-<span style="color: red">Loss</span> Percentage</h3>
                            <div id="rock-wr" class="progress">
                                <div class="progress-bar win" role="progressbar" style="width: ' . $pRockWin .'%" aria-valuenow="' . $pRockWin .'" aria-valuemin="0" aria-valuemax="100">' . $pRockWin .'%</div>
                                <div class="progress-bar tie" role="progressbar" style="width: ' . $pRockTie .'%" aria-valuenow="' . $pRockTie .'" aria-valuemin="0" aria-valuemax="100">' . $pRockTie .'%</div>
                                <div class="progress-bar loss" role="progressbar" style="width: ' . $pRockLoss .'%" aria-valuenow="' . $pRockLoss .'" aria-valuemin="0" aria-valuemax="100">' . $pRockLoss .'%</div>
                            </div>
                            ';
                        }
                        else {
                            echo "<h3>No games have had rock played yet</h3>";
                        }
                    ?>
                    <?php
                        if($numPaper > 0) {
                            echo '
                            <h3><span style="color: #ff5599">Paper</span> <span style="color: #0b8b0b">Win</span>-<span style="color: #59e6ff">Tie</span>-<span style="color: red">Loss</span> Percentage</h3>
                            <div id="paper-wr" class="progress">
                                <div class="progress-bar win" role="progressbar" style="width: ' . $pPaperWin .'%" aria-valuenow="' . $pPaperWin .'" aria-valuemin="0" aria-valuemax="100">' . $pPaperWin .'%</div>
                                <div class="progress-bar tie" role="progressbar" style="width: ' . $pPaperTie .'%" aria-valuenow="' . $pPaperTie .'" aria-valuemin="0" aria-valuemax="100">' . $pPaperTie .'%</div>
                                <div class="progress-bar loss" role="progressbar" style="width: ' . $pPaperLoss .'%" aria-valuenow="' . $pPaperLoss .'" aria-valuemin="0" aria-valuemax="100">' . $pPaperLoss .'%</div>
                            </div>
                            ';
                        }
                        else {
                            echo "<h3>No games have had paper played yet</h3>";
                        }
                    ?>
                    <?php
                        if($numScissors > 0) {
                            echo '
                            <h3><span style="color: #92705f">Scissor</span> <span style="color: #0b8b0b">Win</span>-<span style="color: #59e6ff">Tie</span>-<span style="color: red">Loss</span> Percentage</h3>
                            <div id="scissor-wr" class="progress">
                                <div class="progress-bar win" role="progressbar" style="width: ' . $pScissorsWin .'%" aria-valuenow="' . $pScissorsWin .'" aria-valuemin="0" aria-valuemax="100">' . $pScissorsWin .'%</div>
                                <div class="progress-bar tie" role="progressbar" style="width: ' . $pScissorsTie .'%" aria-valuenow="' . $pScissorsTie .'" aria-valuemin="0" aria-valuemax="100">' . $pScissorsTie .'%</div>
                                <div class="progress-bar loss" role="progressbar" style="width: ' . $pScissorsLoss .'%" aria-valuenow="' . $pScissorsLoss .'" aria-valuemin="0" aria-valuemax="100">' . $pScissorsLoss .'%</div>
                            </div>
                            ';
                        }
                        else {
                            echo "<h3>No games have had scissors played yet</h3>";
                        }
                    ?>
                </div>
            </div>
        </div>

        <!--Bootstrap and Less imports-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/less" ></script>
    </body>
</html>