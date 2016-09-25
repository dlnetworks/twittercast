<?php

include 'EpiCurl.php';
include 'EpiOAuth.php';
include 'EpiTwitter.php';

#Consumer key token
$consumer_key = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

#Consumer secret token
$consumer_secret = 'xxxxxxxxxxxxxxxxxxxxxxxxxx';

#Access Token
$token = 'xxxxxxxxxxxxxxxxxxxxxxxxx';

#Access Token Secret 
$secret= 'xxxxxxxxxxxxxxxxxxxxxxxxxxx';

$ip[1] = "127.0.0.1"; // IP address of shoutcast server
$port[1] = "8000"; // Port of shoutcast server

//END CONFIGURATION
$twitterObj = new EpiTwitter($consumer_key, $consumer_secret, $token, $secret);
$twitterObjUnAuth = new EpiTwitter($consumer_key, $consumer_secret);

$servers = count($ip);
$i = "1";
while($i<=$servers)
	{
	$fp = @fsockopen($ip[$i],$port[$i],$errno,$errstr,5);
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

// tweet preview
print "$song[1]"; 

// post tweet
$twitterObj->post('/statuses/update.json', array('status' => $song[1]));  

?>
