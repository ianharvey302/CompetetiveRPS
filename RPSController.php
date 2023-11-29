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
                $this->showQueue();
                break;
            case "enqueue":
                $this->enqueue();
                break;
            case "dequeue":
                $this->dequeue();
                break;
            case "shoot":
                $this->shoot();
                break;
            case "logout":
                $this->logout();
                break;
            case "global":
                $this->showGlobalStats();
                break;
            case "globalStats":
                $this->getGlobalStats();
                break;
            case "profile":
                $this->showProfile();
                break;
            case "dump_stats":
                $this->dumpUserStats();
                break;
            case "checkUsername":
                $this->checkUsername();
                break;
            default:
                $this->showHomePage();
                break;
        }
    }

    public function showQueue() {
        include($this->PATHSTRING . "queue.php");
    }

    public function enqueue() {
        $username = (isset($_SESSION['signed_in'])) ? $_SESSION["username"] : "Guest";
        $_SESSION["user_match_id"] = $this->db->enqueuePlayer($username);
        $opponent = $this->db->getFirstAvailablePlayer($_SESSION["user_match_id"]);
        while(!$opponent) {
            $opponent = $this->db->getFirstAvailablePlayer($_SESSION["user_match_id"]);
        }
        $_SESSION["opponent"] = $opponent;
        $this->db->dequeuePlayer($_SESSION["user_match_id"]);
        echo json_encode($opponent, JSON_PRETTY_PRINT);
    }

    public function dequeue() {
        $this->db->dequeuePlayer($_SESSION["user_match_id"]);
        echo json_encode(true, JSON_PRETTY_PRINT);
    }

    public function showHomePage($message = '') {
        include($this->PATHSTRING . "homepage.php");
    }

    public function showSignUpPage($message = '') {
        include($this->PATHSTRING . "signup.php");
    }

    public function showGlobalStats() {
        include($this->PATHSTRING . "globalstats.php");
    }

    public function getGlobalStats() {
        echo json_encode($this->db->getGlobalStats());
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

    public function shoot() {
        $username = (isset($_SESSION['signed_in'])) ? $_SESSION["username"] : "Guest";
        $userMove = str_replace([' '], '', $_GET["move"]);
        $this->db->createMoveTable($_SESSION["user_match_id"], $userMove);
        if ($userMove == "forfeit") {
            echo json_encode(["result" => "loss", "usermove" => $userMove, "oppmove" => "unknown"], JSON_PRETTY_PRINT);
            $this->db->loseByDefault($_SESSION["username"]);
            return;
        }
        $opponentMove = $this->db->getMoveEntry($_SESSION["opponent"]["id"]);
        while (!$opponentMove) {
            $opponentMove = $this->db->getMoveEntry($_SESSION["opponent"]["id"]);
        }
        $opponentMove = str_replace([' '], '', $opponentMove);
        if ($opponentMove == "forfeit") {
            echo json_encode(["result" => "win", "usermove" => $_GET["move"], "oppmove" => $opponentMove], JSON_PRETTY_PRINT);
            $this->db->winByDefault($_SESSION["username"]);
            return;
        }
        $gameResult = $this->gameLogic($_GET["move"], $opponentMove);
        $this->inputResultIntoDB($userMove, $gameResult, $username);
        echo json_encode(["result" => $gameResult, "usermove" => $_GET["move"], "oppmove" => $opponentMove], JSON_PRETTY_PRINT);
    }

    public function gameLogic($userMove, $opponentMove) {
        if ($userMove == $opponentMove) {
            return "tie";
        } elseif (
            ($userMove == "rock" && $opponentMove == "scissors") ||
            ($userMove == "paper" && $opponentMove == "rock") ||
            ($userMove == "scissors" && $opponentMove == "paper")
        ) {
            return "win";
        } elseif (
            ($userMove == "scissors" && $opponentMove == "rock") ||
            ($userMove == "rock" && $opponentMove == "paper") ||
            ($userMove == "paper" && $opponentMove == "scissors")
        ) {
            return "loss";
        }
        return "error";
    }

    public function inputResultIntoDB($usermove, $gameResult, $username) {
        switch($usermove) {
            case "rock":
                $this->handleRock($gameResult, $username);
                break;
            case "paper":
                $this->handlePaper($gameResult, $username);
                break;
            case "scissors":
                $this->handleScissors($gameResult, $username);
                break;
        }          
    }

    public function handleRock($gameResult, $username) {
        switch($gameResult) {
            case "win":
                $this->db->rockWin($username);
                break;
            case "loss":
                $this->db->rockLoss($username);
                break;
            case "tie":
                $this->db->rockTie($username);
                break;
        }
    }

    public function handlePaper($gameResult, $username) {
        switch($gameResult) {
            case "win":
                $this->db->paperWin($username);
                break;
            case "loss":
                $this->db->paperLoss($username);
                break;
            case "tie":
                $this->db->paperTie($username);
                break;
        }
    }

    public function handleScissors($gameResult, $username) {
        switch($gameResult) {
            case "win":
                $this->db->scissorsWin($username);
                break;
            case "loss":
                $this->db->scissorsLoss($username);
                break;
            case "tie":
                $this->db->scissorsTie($username);
                break;
        }
    }

    public function dumpUserStats() {
        if(!isset($_GET["username"]))
            echo json_encode($this->db->getUserStats($_SESSION["username"]), JSON_PRETTY_PRINT);
        else
            echo json_encode($this->db->getUserStats($_GET["username"]), JSON_PRETTY_PRINT);
        // echo json_encode($this->db->query("SELECT * FROM matchmaking"), JSON_PRETTY_PRINT);
        // $this->db->query("DROP TABLE users"); //Quick way to drop user table for development get rid of this later
        // $this->db->query("DROP TABLE matchmaking");
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
            $this->showSignUpPage();
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

    public function checkUsername() {
        $res = $this->db->query("select * from users where username = $1;", $_GET["username"]);
        echo json_encode(["result" => empty($res)], JSON_PRETTY_PRINT);
    }
}
