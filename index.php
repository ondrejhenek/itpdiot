<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

	<title>ITPD IOT</title>
	<meta property="og:title" content="ITPD IOT">

	<meta name="author" content="Ondřej Henek">
	<meta name="description" content="IT Product Design @SDU.dk - Internet of Things project">
	<meta property="og:description" content="IT Product Design @SDU.dk - Internet of Things project">

	<meta property="og:type" content="website">
	<meta property="og:locale" content="en_US">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- 
	<script type="text/javascript" src="https://code.jquery.com/jquery-2.2.1.min.js"></script>
 -->
</head>

<body>


<div class="container text-center">
	<a class="btn btn-lg btn-success ajax-link" onclick="switchLED()" id="ledSwitch">
		TURN ON
	</a>
	<a class="btn btn-lg btn-primary ajax-link" onclick="moveRandom()">
		MOVE RANDOM
	</a>
</div>


<!-- User Input -->
<input id="userinput" type="text" width="300px">
<a href="javascript:send()">SEND</a>

<pre id="log">
</pre>


<!-- WEBSOCKET SIMPLE SCRIPT -->
<script type="text/javascript">
	var logdiv = document.getElementById('log');
	var led = false;
	var ledSwitch = document.getElementById('ledSwitch');

	function logf(str){
		logdiv.innerHTML += str + "\n";
	}


	WebSocket.prototype.sendMsg = function(msg) {
		logf('sent: '+ msg);
		this.send(msg);
	}

	// create WebSocket
	var ws = new WebSocket('ws://achex.ca:4010');

	// add event handler for incomming message
	ws.onmessage = function(evt){
		var my_received_message = evt.data;
		logf('received: ' + my_received_message);
	};

	// add event handler for diconnection 
	ws.onclose= function(evt){
		logf('log: Diconnected');
	};

	// add event handler for error 
	ws.onerror= function(evt){
		logf('log: Error');
	};

	// add event handler for new connection 
	ws.onopen= function(evt){
		logf('log: Connected');
		ws.sendMsg('{"setID":"itpdiotserver","passwd":"none"}');
	};

	// make a simple send function
	function send(){
		var input = document.getElementById('userinput');
		// send content of input field into websocket
		ws.sendMsg(input.value);
		// erase input field
	}
	//***************************

	function switchLED() {
		var newValue;
		if (led) { // turn off!
			newValue = 0;
			ledSwitch.innerHTML = 'LED ON';
			ledSwitch.classList.remove('btn-danger');
			ledSwitch.classList.add('btn-success');
		} else {
			newValue = 1;
			ledSwitch.innerHTML = 'LED OFF';
			ledSwitch.classList.remove('btn-success');
			ledSwitch.classList.add('btn-danger');
		}
		ws.sendMsg('{"to":"itpdiot","led":"' +newValue+ '"}');
		led = newValue;
	}

	function moveRandom() {
		var x = Math.floor(Math.random() * 180);
		var y = Math.floor(Math.random() * 180);
		ws.sendMsg('{"to":"itpdiot","servox":"' +x+ '","servoy":"' +y+ '"}');
	}
</script>


</body></html>

