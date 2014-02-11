var randomNumber;
var bet;
var wallet;
var round;

function initialize() {
    input_txt.value = "";
    
	wallet = 10;
	round = 1;
    document.getElementById("input_txt").focus();

    document.getElementById("bet_button").disabled = false;

    var input = document.getElementById("input_txt");
}
function isInteger(n) {
    return n >>> 0 === parseInt(n) && n > 0;
}
function guessNumber() {
	randomNumber = Math.ceil(Math.random() * 100);
    bet = parseInt(document.getElementById("input_txt").value);

    document.getElementById("input_txt").focus();

    if (isInteger(bet)) {
		if ((wallet - bet) >= 0) {
			if (randomNumber > 51) {
				wallet += bet;
				document.getElementById("message_txt").innerHTML ="Congrats, you won!";
				document.getElementById("results_table").innerHTML += "<table><thead><tr><th> <th> Result<th> Balance<tbody><tr><th>Round " + round + "<td>" + bet + "<td>" + wallet + "</table>";
				input_txt.value = "";
				round += 1;
			} else if (randomNumber < 50) {
				wallet -= bet;
				document.getElementById("message_txt").innerHTML = "Sorry, you lost. :( ";
				document.getElementById("results_table").innerHTML += "<table><thead><tr><th> <th> Result<th> Balance<tbody><tr><th>Round " + round + "<td> - " + bet + "<td>" + wallet + "</table>";
				input_txt.value = "";
				round += 1;
			} else {
				document.getElementById("message_txt").innerHTML = "Invalid entry.";
				input_txt.value = "";
			}
		} else {
			document.getElementById("message_txt").innerHTML = "Insufficient Funds";
		}
	} else {
		document.getElementById("message_txt").innerHTML = "Invalid Entry";
	}
}

function playAgain() {
    initialize();
}
window.onload = initialize();
