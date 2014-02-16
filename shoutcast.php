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

$refresh = "60";  // Page refresh time in seconds. Put 0 for no refresh. (only used if updating via browser)
$timeout = "5"; // Number of seconds before connecton times out.

$ip[1] = "123.123.123.123"; // IP address of shoutcast server
$port[1] = "8000"; // Port of shoutcast server

// now go edit the paths to the title.txt file down at the bottom
// now go edit the paths to the title.txt file down at the bottom
// now go edit the paths to the title.txt file down at the bottom

$twitterObj = new EpiTwitter($consumer_key, $consumer_secret, $token, $secret);
$twitterObjUnAuth = new EpiTwitter($consumer_key, $consumer_secret);

$servers = count($ip);

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
				
				if ($stats[0] == $max[$i]) 
					{ 
					$msg[$i] .= "</span>";
					}
				
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
print "<title>$song[1]</title>"; // sure in the title too
?>
</head>
<body><center>
<?php
print "$song[1]"; // so you can see what the output looks like 
 

$fh = @fopen('/full/path/to/title.txt', 'r+'); // full path to title.txt
$track = @fread($fh, filesize('/full/path/to/title.txt')); // full path to title.txt
if ($track == $song[1]."\n"){ 
  fclose($fh); 
  die(0); 
}else{ 
  @fclose($fh); // if it errors, then the file doesn't exist, and the stream was never open 
  $fh = fopen('/full/path/to/title.txt', 'w'); // full path to title.txt
  fwrite($fh, $song[1]."\n");
  fclose($fh);
  $twitterObj->post('/statuses/update.json', array('status' => $song[1])); // Tweet that shit. 
} 

?>
</center>
</body>
</html>



