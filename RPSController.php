<?php

class RPSController {

    private $db;

    private $input = [];

    private $PATHSTRING = "./"; //use for local development
    // private $PATHSTRING = "/path/to/secret/"; //use for server


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
        $res = $this->db->query("SELECT
            SUM(numwin) as sum_numwin,
            SUM(numtie) as sum_numtie,
            SUM(numloss) as sum_numloss,
            SUM(numrock) as sum_numrock,
            SUM(numrockwin) as sum_numrockwin,
            SUM(numrocktie) as sum_numrocktie,
            SUM(numrockloss) as sum_numrockloss,
            SUM(numpaper) as sum_numpaper,
            SUM(numpaperwin) as sum_numpaperwin,
            SUM(numpapertie) as sum_numpapertie,
            SUM(numpaperloss) as sum_numpaperloss,
            SUM(numscissors) as sum_numscissors,
            SUM(numscissorswin) as sum_numscissorswin,
            SUM(numscissorstie) as sum_numscissorstie,
            SUM(numscissorsloss) as sum_numscissorsloss
            FROM users;");
        $numWin = $res[0]["sum_numwin"];
        $numTie = $res[0]["sum_numtie"];
        $numLoss = $res[0]["sum_numloss"];
        $numRock = $res[0]["sum_numrock"];
        $numRockWin = $res[0]["sum_numrockwin"];
        $numRockTie = $res[0]["sum_numrocktie"];
        $numRockLoss = $res[0]["sum_numrockloss"];
        $numPaper = $res[0]["sum_numpaper"];
        $numPaperWin = $res[0]["sum_numpaperwin"];
        $numPaperTie = $res[0]["sum_numpapertie"];
        $numPaperLoss = $res[0]["sum_numpaperloss"];
        $numScissors = $res[0]["sum_numscissors"];
        $numScissorsWin = $res[0]["sum_numscissorswin"];
        $numScissorsTie = $res[0]["sum_numscissorstie"];
        $numScissorsLoss = $res[0]["sum_numscissorsloss"];
        list($total, $pWin, $pTie, $pLoss) = $this->percentages($numWin, $numTie, $numLoss);
        list($totalRPS, $pRock, $pPaper, $pScissors) = $this->percentages($numRock, $numPaper, $numScissors);
        list($totalRock, $pRockWin, $pRockTie, $pRockLoss) = $this->percentages($numRockWin, $numRockTie, $numRockLoss);
        list($totalPaper, $pPaperWin, $pPaperTie, $pPaperLoss) = $this->percentages($numPaperWin, $numPaperTie, $numPaperLoss);
        list($totalScissors, $pScissorsWin, $pScissorsTie, $pScissorsLoss) = $this->percentages($numScissorsWin, $numScissorsTie, $numScissorsLoss);
        include($this->PATHSTRING . "globalstats.php");
    }

    public function showProfile() {
        if(!isset($_SESSION['signed_in'])) {
            $this->showHomePage();
            return;
        }
        $username = $_SESSION["username"];
        $res = $this->db->query("select * from users where username = $1;", $username);
        $numWin = $res[0]["numwin"];
        $numTie = $res[0]["numtie"];
        $numLoss = $res[0]["numloss"];
        $numRock = $res[0]["numrock"];
        $numRockWin = $res[0]["numrockwin"];
        $numRockTie = $res[0]["numrocktie"];
        $numRockLoss = $res[0]["numrockloss"];
        $numPaper = $res[0]["numpaper"];
        $numPaperWin = $res[0]["numpaperwin"];
        $numPaperTie = $res[0]["numpapertie"];
        $numPaperLoss = $res[0]["numpaperloss"];
        $numScissors = $res[0]["numscissors"];
        $numScissorsWin = $res[0]["numscissorswin"];
        $numScissorsTie = $res[0]["numscissorstie"];
        $numScissorsLoss = $res[0]["numscissorsloss"];
        list($total, $pWin, $pTie, $pLoss) = $this->percentages($numWin, $numTie, $numLoss);
        list($totalRPS, $pRock, $pPaper, $pScissors) = $this->percentages($numRock, $numPaper, $numScissors);
        list($totalRock, $pRockWin, $pRockTie, $pRockLoss) = $this->percentages($numRockWin, $numRockTie, $numRockLoss);
        list($totalPaper, $pPaperWin, $pPaperTie, $pPaperLoss) = $this->percentages($numPaperWin, $numPaperTie, $numPaperLoss);
        list($totalScissors, $pScissorsWin, $pScissorsTie, $pScissorsLoss) = $this->percentages($numScissorsWin, $numScissorsTie, $numScissorsLoss);
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
        $_SESSION["match_result"] = "Win";
        $move = $_POST["game_move"];
        switch($move) {
            case "rock":
                $this->db->query("update users set numwin = numwin + 1 where username = $1;", $username);
                $this->db->query("update users set numrock = numrock + 1 where username = $1;", $username);
                $this->db->query("update users set numrockwin = numrockwin + 1 where username = $1;", $username);
                break;
            case "paper":
                $this->db->query("update users set numwin = numwin + 1 where username = $1;", $username);
                $this->db->query("update users set numpaper = numpaper + 1 where username = $1;", $username);
                $this->db->query("update users set numpaperwin = numpaperwin + 1 where username = $1;", $username);
                break;
            case "scissors":
                $this->db->query("update users set numwin = numwin + 1 where username = $1;", $username);
                $this->db->query("update users set numscissors = numscissors + 1 where username = $1;", $username);
                $this->db->query("update users set numscissorswin = numscissorswin + 1 where username = $1;", $username);
                break;
            case "forfeit":
                $_SESSION["match_result"] = "Lose";
                $this->db->query("update users set numloss = numloss + 1 where username = $1;", $username);
                $this->db->query("update users set numrock = numrock + 1 where username = $1;", $username);
                $this->db->query("update users set numrockloss = numrockloss + 1 where username = $1;", $username);
                break;
        }
        include($this->PATHSTRING . "results.php");
    }

    public function dumpUserStats() {
        $userProfile = $this->createUserJson();
        echo $userProfile;
    }

    public function createUserJson() {
        $username = $_SESSION["username"];
        $res = $this->db->query("select * from users where username = $1;", $username);
        $numWin = $res[0]["numwin"];
        $numTie = $res[0]["numtie"];
        $numLoss = $res[0]["numloss"];
        $numRock = $res[0]["numrock"];
        $numRockWin = $res[0]["numrockwin"];
        $numRockTie = $res[0]["numrocktie"];
        $numRockLoss = $res[0]["numrockloss"];
        $numPaper = $res[0]["numpaper"];
        $numPaperWin = $res[0]["numpaperwin"];
        $numPaperTie = $res[0]["numpapertie"];
        $numPaperLoss = $res[0]["numpaperloss"];
        $numScissors = $res[0]["numscissors"];
        $numScissorsWin = $res[0]["numscissorswin"];
        $numScissorsTie = $res[0]["numscissorstie"];
        $numScissorsLoss = $res[0]["numscissorsloss"];

        $userProfile = array('username' => $username, 'numWin' => $numWin, 'numLoss' =>$numLoss, 'numTie' => $numTie, 'numRock' => $numRock, 
            'numRockWin' => $numRockWin, 'numRockLoss' => $numRockLoss, 'numRockTie' => $numRockTie, 'numPaper' => $numPaper, 'numPaperWins' => $numPaperWin, 
            'numPaperLoss' => $numPaperLoss, 'numPaperTie' => $numPaperTie, 'numScissors' => $numScissors, 'numScissorWin' => $numScissorsWin, 
            'numScissorLoss' => $numScissorsLoss, 'NumScissorTie' => $numScissorsTie);
        return json_encode($userProfile, JSON_PRETTY_PRINT);
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
