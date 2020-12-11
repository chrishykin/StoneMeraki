<?php

$visit_timeframe = 3; // The length (in minutes) we classify as a visit.

// mySQL;
include 'config/env.php';

if ($_SERVER['REQUEST_METHOD'] == "GET") {
	if ($_GET['expose'] == "tiddlydumb") {
		// Responding to the request key.
		echo "c8bb2a41f8720202ec08d9039d6b46d2fd0b5c85\n";
	}
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	// Takes raw data from the request
	$json = file_get_contents('php://input');
	// Converts it into an Object
	$data = json_decode($json);
	
	// DB connection on POST request.

	$link = mysqli_connect($mysql_server,$mysql_user,$mysql_password,$mysql_db,3306);

	if (!$link) {
		error_log('Connect Error (' . mysqli_connect_errno() . ') '
				. mysqli_connect_error());
		die();
	} 


	// The requests are per Access point, containing an array of Observations per POST
	$apMac = $data->{'data'}->{'apMac'};

	foreach ($data->{'data'}->{'observations'} as $observation) {

		$ipv4 = $observation->{'ipv4'};
		$ssid = $observation->{'ssid'};
		$os = $observation->{'os'};
		$clientMac = $observation->{'clientMac'};
		$seenEpoch = $observation->{'seenEpoch'};
		$rssi = $observation->{'rssi'};
		$ipv6 = $observation->{'ipv6'};
		$manufacturer = $observation->{'manufacturer'};
		$lat = $observation->{'location'}->{"lat"};
		$lng = $observation->{'location'}->{"lng"};
		$unc = $observation->{'location'}->{"unc"};

		// With the Observations table, we simply dump whatever we get, into the database.

		$query = "INSERT INTO `meraki_observations`(`apMac`, `apFloors`, `apTags`, `ipv4`, `latitude`, `longitude`, `unc`, `x`, `y`, `seenTime`, `ssid`, `os`, `clientMac`, `seenEpoch`, `rssi`,".
			 " `ipv6`, `manufacturer`) ". 
			 "VALUES (\"$apMac\",\"\",\"\",\"$ipv4\",\"$lat\",\"$lng\",\"$unc\",null,null,now(),".
			 "\"$ssid\",\"$os\",\"$clientMac\"".
			 ",\"$seenEpoch\",\"$rssi\",\"$ipv6\",\"$manufacturer\")";

		mysqli_query($link,$query);

		// Next, the visits table. The logic here is straightforward enough.
		// Have we seen the client before within the given time window ($visit_timeframe)? If so, we update the last_seen time. 
		// If not, we create a new visit instead.

		// You could do this slightly differently to give yourself more flexibility in the definition of a visit, but the idea here, because of the volume is to reduce the amount of Queries we undertake per call.

		$query2 = "update `meraki_visits` set endTime = now() where clientMac = \"$clientMac\" and (TIME_TO_SEC(TIMEDIFF(now(), ENDTIME))/60) < $visit_timeframe order by observation_id desc limit 1"; 
		mysqli_query($link,$query2);

		if (mysqli_affected_rows($link) == 0) { // we didn't update anything that met the criteria.
			$query3 = "INSERT INTO `meraki_visits`".
				  "(`apMac`, `apFloors`, `apTags`, `ipv4`, `latitude`, `longitude`, `unc`, `x`, `y`, `startTime`, `endTime`, `visitTime`, `ssid`, `os`, `clientMac`, `seenEpoch`, `rssi`,".
				  " `ipv6`, `manufacturer`) ".
				  "VALUES (\"$apMac\",\"\",\"\",\"$ipv4\",\"$lat\",\"$lng\",\"$unc\",null,null,now(),now(),null,".
                                  "\"$ssid\",\"$os\",\"$clientMac\"".
				  ",\"$seenEpoch\",\"$rssi\",\"$ipv6\",\"$manufacturer\")";
			mysqli_query($link,$query3);
	
		}

	}
}
?>
