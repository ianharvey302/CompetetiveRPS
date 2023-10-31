<?php

class RPSController {

    private $input = [];

    public function __construct($input) {
        session_start();
        
        $this->input = $input;
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

    public function showHomePage() {
        include("./homepage.php");
    }

    public function showGlobalStats() {
        include("./globalstats.php");
    }

    public function playGame() {
        include("./game.php");
    }

    public function login() {

        if(isset($_POST["fullname"])) {
            $_SESSION["username"] = $_POST["password"];
        }

        if(isset($_POST["email"])) {
            $_SESSION["email"] = $_POST["email"];
        }

        $_SESSION["signed_in"] = true;
        include("./homepage.php");

    }

    public function createUser() {
        if(isset($_POST["fullname"])) {
            $_SESSION["username"] = $_POST["password"];
        }

        if(isset($_POST["email"])) {
            $_SESSION["email"] = $_POST["email"];
        }

        $_SESSION["signed_in"] = true;
        include("./homepage.php");

    }

     public function logout() {
        session_destroy();
        session_start();
    }

}
