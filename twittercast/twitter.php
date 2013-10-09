<?php

include 'EpiCurl.php';
include 'EpiOAuth.php';
include 'EpiTwitter.php';

#Consumer key token
$consumer_key = 'Consumer_Key_Token';

#Consumer secret token
$consumer_secret = 'Consumer_Secret_Token';

#Access Token
$token = 'Access_Token';

#Access Token Secret 
$secret= 'Access_Token_Secret';

$twitterObj = new EpiTwitter($consumer_key, $consumer_secret, $token, $secret);
$twitterObjUnAuth = new EpiTwitter($consumer_key, $consumer_secret);


$refresh = "60";  // Page refresh time in seconds. Put 0 for no refresh
$timeout = "5"; // Number of seconds before connecton times out - a higher value will slow the page down if any servers are offline

$ip[1] = "123.123.123.123"; 
$port[1] = "8000";

$ip[2] = "123.123.123.123."; 
$port[2] = "8002";



// Add or remove servers as needed.


$servers = count($ip);
?>

<?php
$i = "1";
while($i<=$servers)
	{
	$fp = @fsockopen($ip[$i],$port[$i],$errno,$errstr,$timeout);
	if (!$fp) 
		{ 
		$listeners[$i] = "0";
		$msg[$i] = "<span class=\"red\">ERROR [Connection refused / Server down]</span>";
		$error[$i] = "1";
		} 
	else
		{ 
		fputs($fp, "GET /7.html HTTP/1.0\r\nUser-Agent: Mozilla\r\n\r\n");
		while (!feof($fp)) 
			{
			$info = fgets($fp);
			}
		$info = str_replace('<HTML><meta http-equiv="Pragma" content="no-cache"></head><body>', "", $info);
		$info = str_replace('</body></html>', "", $info);
		$stats = explode(',', $info);
		if (empty($stats[1]) )
			{
			$listeners[$i] = "0";
			$msg[$i] = "<span class=\"red\">ERROR [There is no source connected]</span>";
			$error[$i] = "1";
			}
		else
			{
			if ($stats[1] == "1")
				{
				$song[$i] = $stats[6];
				$listeners[$i] = $stats[4];
				$max[$i] =  $stats[3];
				$bitrate[$i] = $stats[5];
				$peak[$i] = $stats[2];
				if ($stats[0] == $max[$i]) 
					{ 
					$msg[$i] .= "<span class=\"red\">";
					}
				$msg[$i] .= "Server is up at $bitrate[$i] kbps with $listeners[$i] of $max[$i] listeners";
				if ($stats[0] == $max[$i]) 
					{ 
					$msg[$i] .= "</span>";
					}
				$msg[$i] .= "\n    <p><b>Listener peak:</b> $peak[$i]";
				}
			else
				{
				$listeners[$i] = "0";
				$msg[$i] = "    <span class=\"red\">ERROR [Cannot get info from server]</span>";
				$error[$i] = "1";
				}
			}
		}
	$i++;
	}
$total_listeners = array_sum($listeners) ;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php
if ($refresh != "0") 
	{
	print "<meta http-equiv=\"refresh\" content=\"$refresh\">\n";
	}
print "<title>TwitterCast - $song[1] - $total_listeners Listeners</title>";
?>
</head>
<body><center>
<?php
print "$song[1] - $total_listeners Listeners</a>";
 

$fh = @fopen('title.txt', 'r+'); 
$track = @fread($fh, filesize('title.txt')); 
if ($track == $song[1]."\n"){ 
  fclose($fh); 
  die(0); 
}else{ 
  @fclose($fh); // if it errors, then the file doesn't exist, and the stream was never open 
  $fh = fopen('title.txt', 'w'); 
  fwrite($fh, $song[1]."\n");
  fclose($fh);
  $twitterObj->post('/statuses/update.json', array('status' => '#NowPlaying ' .$song[1])); 
} 

?>
</center>
</body>
</html>



