<?php

class RPSController {

    private $db;

    private $input = [];

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
            default:
                $this->showHomePage();
                break;
        }
    }

    public function showHomePage($message = '') {
        include("./homepage.php");
    }

    public function showSignUpPage($message = '') {
        include("./signup.php");
    }

    public function showGlobalStats() {
        include("./globalstats.php");
    }

    public function playGame() {
        include("./game.php");
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
