let userMove = false;
let opponentName = null;
let opponentJSON = null;
let matchResultString = false;
let enqueueAjax;
let gameAjax;

$(document).ready(function() {
    enqueueAjax = $.get("?command=enqueue", handleEnqueueAjax);

    // $(window).on("beforeunload", function() {
    //     enqueueAjax.abort();
    //     navigator.sendBeacon("?command=dequeue", "");
    //     return "Are you sure you want to leave?";
    // });
});

function handleMoveButtonClick() {
    userMove = $(this).attr("value");
    $(this).css("background-color", "");
    $(".match-moves > .move-btn").each(function() {
        if ($(this).attr("value") != userMove) {
            $(this).css("background-color", "#666666");
        }
    });
}

function handleLockButtonClick() {
    $(".move-btn, .lock-btn, .forfeit-btn").prop("disabled", true);
    $(this).css("background-color", "#dc3545");

    gameAjax = $.get("?command=shoot&move=" + userMove, function(data) {
        gameResult = JSON.parse(data).result;
        switch (gameResult) {
            case "win":
                matchResultString = "Win";
                break;
            case "loss":
                matchResultString = "Lose";
                break;
            case "tie":
                matchResultString = "Tied";
                break;
            default:
                matchResultString = "Errored Out";
        }
        loadResultsScreen(matchResultString);
        $("#new-game").on("click", function() {
            loadQueueScreen();
            enqueueAjax = $.get("?command=enqueue", handleEnqueueAjax);
        });
    });
}

function handleForfeitButtonClick() {
    navigator.sendBeacon("?command=shoot&move=forfeit", "");
    loadResultsScreen("Lose");
}

function handleEnqueueAjax(data) {
    opponentJSON = data;
    opponentName = JSON.parse(data).username;
    console.log("Added to database!!!");
    console.log(opponentJSON);
    loadGameScreen();

    $(".match-moves > .move-btn").on("click", handleMoveButtonClick);
    $(".lock-btn").on("click", handleLockButtonClick);
    $(".forfeit-btn").on("click", handleForfeitButtonClick);

}

function loadQueueScreen() {
    $("#game-container").empty();
    $("#game-container").html(
        '<h1 style="font-size: 10vh; text-align:center;">Finding Game ...</h1>' +
        '<div style="margin-top: 20%;">' +
        '<a href="?command=home" class="btn btn-lg">Cancel</a>' +
        '</div>'
    );
}

function loadGameScreen() {
    $("#game-container").empty();
    $("#game-container").html(
        '<div class="match-text">' +
        '<h1>Opponent: <strong>' + opponentName + '</strong></h1>' +
        '<h3>Select Your Move (10s left):</h3>' +
        '</div>' +
        '<div class="flexform centered-container-flex">' +
        '<div class="match-moves" role="group" aria-label="Moves">' +
        '<button type="button" value="rock" class="btn move-btn rock-btn">ROCK</button>' +
        '<button type="button" value="paper" class="btn move-btn paper-btn">PAPER</button>' +
        '<button type="button" value="scissors" class="btn move-btn scissor-btn">SCISSORS</button>' +
        '</div>' +
        '<div class="match-submit">' +
        '<button type="button" class="btn btn-lg lock-btn">LOCK IN</button>' +
        '<button type="button" value="forfeit" class="nav-link forfeit-btn">Forfeit</button>' +
        '</div>' +
        '</div>'
    );
}

function loadResultsScreen(matchResultString) {
    $("#game-container").empty();
    $("#game-container").html(
        '<div class="match-text">' +
            '<h1>Match Results (Opponent: <strong>' + opponentName + '</strong>)</h1>' +
        '</div>' +
        '<h1 style="font-size: 10vh;">You ' + matchResultString +'!</h1>' +
        '<div style="margin-top: 20%;">' +
            '<button id="new-game" type="button" class="btn btn-lg">Find New Game</button>' +
            '<a href="?command=home" class="btn btn-lg">Back to Home</a>' +
        '</div>'
    );
}