<?php

class RPSController {

    private $db;

    private $input = [];

    private $PATHSTRING = "./"; //use for local development
    // private $PATHSTRING = "/students/gxz6ja/students/gxz6ja/rps_secret/templates/"; //use for server


    public function __construct($input) {
        session_start();
        $this->db = new Database();
        
        $this->input = $input;

        if(isset($_COOKIE["username"])) {
            $_SESSION["username"] = $_COOKIE["username"];
            $_SESSION["signed_in"] = true;
        }
    }

    public function run() {
        // Get the command
        $command = "home";
        if (isset($this->input["command"]))
            $command = $this->input["command"];

        switch($command) {
            case "login":
                $this->login();
                break;
            case "signup":
                $this->signUp();
                break;
            case "createUser":
                $this->createUser();
                break;
            case "play":
                $this->playGame();
                break;
            case "logout":
                $this->logout();
                break;
            case "global":
                $this->showGlobalStats();
                break;
            case "profile":
                $this->showProfile();
                break;
            case "dump_stats":
                $this->dumpUserStats();
                break;
            default:
                $this->showHomePage();
                break;
        }
    }

    public function showHomePage($message = '') {
        include($this->PATHSTRING . "homepage.php");
    }

    public function showSignUpPage($message = '') {
        include($this->PATHSTRING . "signup.php");
    }

    public function showGlobalStats() {
        $globalProfile = $this->db->getGlobalStats();
        list($total, $pWin, $pTie, $pLoss) = $this->percentages($globalProfile["numWin"], $globalProfile["numLoss"], $globalProfile["numTie"]);
        list($totalRPS, $pRock, $pPaper, $pScissors) = $this->percentages($globalProfile["numRock"], $globalProfile["numPaper"], $globalProfile["numScissors"]);
        list($totalRock, $pRockWin, $pRockTie, $pRockLoss) = $this->percentages($globalProfile["numRockWin"], $globalProfile["numRockTie"], $globalProfile["numRockLoss"]);
        list($totalPaper, $pPaperWin, $pPaperTie, $pPaperLoss) = $this->percentages($globalProfile["numPaperWin"], $globalProfile["numPaperTie"], $globalProfile["numPaperLoss"]);
        list($totalScissors, $pScissorsWin, $pScissorsTie, $pScissorsLoss) = $this->percentages($globalProfile["numScissorsWin"], $globalProfile["numScissorsTie"], $globalProfile["numScissorsLoss"]);
        include($this->PATHSTRING . "globalstats.php");
    }

    public function showProfile() {
        if(!isset($_SESSION['signed_in'])) {
            $this->showHomePage();
            return;
        }
        $username = $_SESSION["username"];
        $userProfile = $this->db->getUserStats($username);
        list($total, $pWin, $pTie, $pLoss) = $this->percentages($userProfile["numWin"], $userProfile["numLoss"], $userProfile["numTie"]);
        list($totalRPS, $pRock, $pPaper, $pScissors) = $this->percentages($userProfile["numRock"], $userProfile["numPaper"], $userProfile["numScissors"]);
        list($totalRock, $pRockWin, $pRockTie, $pRockLoss) = $this->percentages($userProfile["numRockWin"], $userProfile["numRockTie"], $userProfile["numRockLoss"]);
        list($totalPaper, $pPaperWin, $pPaperTie, $pPaperLoss) = $this->percentages($userProfile["numPaperWin"], $userProfile["numPaperTie"], $userProfile["numPaperLoss"]);
        list($totalScissors, $pScissorsWin, $pScissorsTie, $pScissorsLoss) = $this->percentages($userProfile["numScissorsWin"], $userProfile["numScissorsTie"], $userProfile["numScissorsLoss"]);
        include($this->PATHSTRING . "profile.php");
    }

    public function percentages($wins, $ties, $losses) {
        $total = $wins + $ties + $losses;
        if($total != 0) {
            return array($total, round($wins / $total * 100, 2), round($ties / $total * 100, 2), round($losses / $total * 100, 2));
        }
        else {
            return array(0, 0, 0, 0);
        }
    }

    public function playGame() {
        if (isset($_POST["game_move"]) and !isset($_SESSION["move_was_submitted"])) {
            $this->gameLogic();
            unset($_POST["game_move"]);
            $_SESSION["move_was_submitted"] = true;
            return;
        }
        else if (isset($_POST["game_move"])) {
            include($this->PATHSTRING . "results.php");
        }
        else {
            include($this->PATHSTRING . "game.php");
            unset($_SESSION["move_was_submitted"]);
        }
    }

    public function gameLogic() {
        $username = $_SESSION["username"];
        $move = $_POST["game_move"];
        if ($move == "forfeit") {
            $this->db->query("update users set numloss = numloss + 1 where username = $1;", $username);
            $_SESSION["displayed_match_result"] = "Forfeited the Match";
            include($this->PATHSTRING . "results.php");
            return;
        }
        $temp_game_outcome = rand(1, 100);
        switch($temp_game_outcome) {
            case $temp_game_outcome < 33.33:
                $this->db->query("update users set numwin = numwin + 1 where username = $1;", $username);
                $this->db->query("update users set num" . $move . " = num" . $move . " + 1 where username = $1;", $username);
                $this->db->query("update users set num" . $move . "win = num" . $move . "win + 1 where username = $1;", $username);
                $_SESSION["displayed_match_result"] = "Win";
                break;
            case $temp_game_outcome < 66.66:
                $this->db->query("update users set numloss = numloss + 1 where username = $1;", $username);
                $this->db->query("update users set num" . $move . " = num" . $move . " + 1 where username = $1;", $username);
                $this->db->query("update users set num" . $move . "loss = num" . $move . "loss + 1 where username = $1;", $username);
                $_SESSION["displayed_match_result"] = "Lose";
                break;
            case $temp_game_outcome >= 66.66:
                $this->db->query("update users set numtie = numtie + 1 where username = $1;", $username);
                $this->db->query("update users set num" . $move . " = num" . $move . " + 1 where username = $1;", $username);
                $this->db->query("update users set num" . $move . "tie = num" . $move . "tie + 1 where username = $1;", $username);
                $_SESSION["displayed_match_result"] = "Tied";
                break;
            }
        include($this->PATHSTRING . "results.php");
    }

    public function dumpUserStats() {
        echo json_encode($this->db->getUserStats($_SESSION["username"]), JSON_PRETTY_PRINT);
        // $this->db->query("DROP TABLE users"); //Quick way to drop user table for development get rid of this latet
    }

    public function login() {
        if(isset($_POST["username"]) and isset($_POST["password"])) {
            $res = $this->db->query("select * from users where username = $1;", $_POST["username"]);
            if(!empty($res)) {
                if (password_verify($_POST["password"], $res[0]["password"])) {
                    $_SESSION["username"] = $res[0]["username"];
                    $_SESSION["signed_in"] = true;
                    setcookie("username", $_SESSION["username"]);
                    $this->showHomePage();
                }
                else {
                    $message = "<div class=\"alert alert-danger text-center\" role=\"alert\">Password incorrect</div>";
                    $this->showHomePage($message);
                }
            }
            else {
                $message = "<div class=\"alert alert-danger text-center\" role=\"alert\">Username not found</div>";
                $this->showHomePage($message);
                return;
            }
        }
        else {
            $this->showHomePage();
        }
    }

    public function signUp() {
        if(!isset($_SESSION['signed_in'])) {
            $this->showSignUpPage();
        }
        else {
            $this->showHomePage();
        }
    }

    public function createUser() {
        if(isset($_SESSION["signed_in"])) {
            $this->showHomePage();
        }

        $res = $this->db->query("select * from users where username = $1;", $_POST["username"]);
        if(!empty($res)) {
            $message = "<div class=\"alert alert-danger text-center\" role=\"alert\">Username already taken</div>";
            $this->showSignUpPage($message);
            return;
        }

        if($_POST["password"] != "" and strlen($_POST["password"]) < 8) {
            $message = "<div class=\"alert alert-danger text-center\" role=\"alert\">Password must be at least 8 characters long if set</div>";
            $this->showSignUpPage($message);
            return;
        }

        if($_POST["password"] != "" and !preg_match("/^.*[\W\d_]+.*$/", $_POST["password"])) {
            $message = "<div class=\"alert alert-danger text-center\" role=\"alert\">Password must contain at least one special character.</div>";
            $this->showSignUpPage($message);
            return;
        }

        if(preg_match("/^Guest/i", $_POST["username"])) {
            $message = "<div class=\"alert alert-danger text-center\" role=\"alert\">Username cannot contain \"Guest\" </div>";
            $this->showSignUpPage($message);
            return;
        }

        $this->db->query("insert into users (username, password) values ($1, $2);",
            $_POST["username"],
            password_hash($_POST["password"], PASSWORD_DEFAULT));

        $_SESSION["username"] = $_POST["username"];
        $_SESSION["signed_in"] = true;
        setcookie("username", $_SESSION["username"]);
        $this->showHomePage();
    }

    public function logout() {
        session_destroy();
        setcookie("username", "", time()-3600);
        session_start();
        $this->showHomePage();
    }

}
