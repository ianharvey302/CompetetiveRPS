let inGame = false;
$(document).ready(function() {
    $.get("?command=enqueue", function (opponentName) {
        console.log("Added to database!!!");
        console.log(JSON.parse(opponentName));
    }); 
});