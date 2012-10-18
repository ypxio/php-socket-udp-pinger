<?php

/*
 * Simple php udp socket client
 */
function getmicrotime()
{
	list($usec, $sec) = explode(" ",microtime());
	return ((float)$usec + (float)$sec);
}

// Reduce errors
error_reporting( ~E_WARNING );

$server = '127.0.0.1';
$port = 9999;

$sock = socket_create(AF_INET, SOCK_DGRAM, 0);

// echo "Socket created \n";

// Communication loop
// while(1)
// {
	// Take some input to send
	// echo 'Enter a message to send : ';
	echo "\n";
	echo 'Data : ';
	$data = fgets(STDIN);

	echo 'Jumlah Paket : ';
	$count = fgets(STDIN);

	$data = rtrim($data, "\r\n");
	$count = rtrim($count, "\r\n");

	echo "\nPING $server with data = \"$data\" $count times\n\n";
	
	$total_time = 0;

	$packet_time = array();
	$success = array();
	$fail = array();

	for($i = 1; $i <= $count ; $i++)
	{
		// Send the message to the server
		$time_start = getmicrotime();
		$tes2 = socket_sendto( $sock, $data , strlen($data) , 0 , $server , $port );
		if(!$tes2)
		{
			echo "rto";
		}
		// Now receive reply from server and print it
		$tes = socket_recv ( $sock , $reply , 2045 , MSG_WAITALL );
		// echo $tes;
		if($tes)
		{
			array_push($success, 1);
		}
		else
		{
			array_push($fail, 1);
		}

		$time_taken = round(getmicrotime() - $time_start, 7);
		$time_taken *= 1000;

		// print reply from server
		// echo "Reply : $reply from $server: time=$time_taken ms\n";
		echo "$reply from $server: icmp_req=$i time=$time_taken ms\n";

		$total_time += $time_taken;

		array_push($packet_time, $time_taken);

		sleep(1); // delay 1 seconds
	}

// 	--- 127.0.0.1 ping statistics ---
// 8 packets transmitted, 8 received, 0% packet loss, time 6998ms
// rtt min/avg/max/mdev = 0.068/0.077/0.111/0.013 ms
	$max_time = max($packet_time);
	$min_time = min($packet_time);
	$avg_time = $total_time / $count;
	$success_count = count($success);
	$percent = 100 - (($count/$success_count)*100);
	
	echo "\n--- ".$server." ping statistics ---\n";
	echo $count." packets transmitted, ".$success_count." received, ".$percent."% packet loss, time ".$total_time."ms";
	echo "\n";
	echo "rtt min/avg/max/mdev = ".$min_time."/".$avg_time."/".$max_time."/undefined ms";
	echo "\n\n";
		// print_r($packet_time);
	// echo count($fail);

	
// }
