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

	echo "\nMengirim \"$data\" ke $server sebanyak $count kali\n\n";
	
	$total_time = 0;

	for($i = 1; $i <= $count ; $i++)
	{
		// Send the message to the server
		$time_start = getmicrotime();
		socket_sendto( $sock, $data , strlen($data) , 0 , $server , $port );
		
		// Now receive reply from server and print it
		socket_recv ( $sock , $reply , 2045 , MSG_WAITALL );
		$time_taken = round(getmicrotime() - $time_start, 7);
		$time_taken *= 1000;

		// print reply from server
		// echo "Reply : $reply from $server: time=$time_taken ms\n";
		echo "Balasan dari $server: data=$reply waktu=$time_taken ms\n";

		$total_time += $time_taken;

		sleep(1);
	}

	echo "\nTotal time = $total_time ms\n";
	
// }
