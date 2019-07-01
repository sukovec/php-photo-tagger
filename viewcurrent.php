<?php

require "base.php";

if (!array_key_exists("mywatchid", $_COOKIE)) { 
	echo "<hr /><a href='setid.php'>Go Here</a><hr />";
	throw new Exception("No ID is set in cookies: "); 
}

$watchID = $_COOKIE["mywatchid"];


output_head("Watch current, he");
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.min.js" type="text/javascript"></script>

<script>

var wsbroker = "vps.sukovec.cz"; // location.hostname;  // mqtt websocket enabled broker
var wsport = 9002; // port for above
var client = new Paho.MQTT.Client(wsbroker, wsport, "/ws", "myclientid_" + parseInt(Math.random() * 100, 10));

client.onConnectionLost = function (responseObject) {
	console.log("CONNECTION LOST - " + responseObject.errorMessage);
};
client.onMessageArrived = function (message) {
	console.log("RECEIVE ON " + message.destinationName + " PAYLOAD " + message.payloadString);

	document.getElementById("finalimage").src="getimg.php?type=thumb&img=" + message.payloadString;
};


var options = {
timeout: 3,
	keepAliveInterval: 30,
	onSuccess: function () {
		console.log("CONNECTION SUCCESS");
		client.subscribe('/watch/<?php echo $watchID;?>', {qos: 1});
	},
	onFailure: function (message) {
		console.log("CONNECTION FAILURE - " + message.errorMessage);
	}
};

console.log("CONNECT TO " + wsbroker + ":" + wsport);
client.connect(options);

</script>

<img src="" id="finalimage" />

<?php

output_foot();
