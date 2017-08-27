<?php

include 'EpiCurl.php';
include 'EpiOAuth.php';
include 'EpiTwitter.php';

# Consumer key token
$consumer_key = '********************';

# Consumer secret token
$consumer_secret = '*******************************************';

# Access Token
$token = '**************************************************';

# Access Token Secret
$secret= '******************************************';

# Number of seconds before connection times out.
$timeout = "5";

# refresh time in seconds (0 to disable)
$refresh = "60";

# server ip/hostname (without http://)
# server port
# stream id (sc V1 use sid 1)
# song from first server used for tweet
# add or remove servers as needed

$ip[0] = "127.0.0.1";
$port[0] = "8000";
$sid[0] = "1";

$ip[1] = "127.0.0.1";
$port[1] = "8000";
$sid[1] = "2";

$ip[2] = "127.0.0.1";
$port[2] = "8000";
$sid[2] = "3";

# url to include at the end of the tweet
$url = "https://www.domain.com";

# text to iclude at the beginning of the tweet
$prefix = "#hastag";

# include listener count in tweet (0 to disable)
$count = "1";

// END CONFIGURATION

$twitterObj = new EpiTwitter($consumer_key, $consumer_secret, $token, $secret);
$twitterObjUnAuth = new EpiTwitter($consumer_key, $consumer_secret);

$i = "0";
$servers = count($ip);
while($i<=$servers)	{
	$fp = @fsockopen($ip[$i],$port[$i],$errno,$errstr,$timeout);
	if ($fp) {
		fputs($fp, "GET /7.html?sid=$sid[$i] HTTP/1.0\r\nUser-Agent: Mozilla/5.0 (The King Kong of Lawn Care)\r\n\r\n");
		while (!feof($fp)) {
			$info = fgets($fp);
			};
		$info = str_replace('<html><body>', "", $info);
		$info = str_replace('</body></html>', "", $info);
		$stats = explode(',', $info);
		$track[$i] = $stats[6];
		$listeners[$i] = $stats[0];
		};
	$i++;
};

$i = "0";
$song = $track[0];
$total_listeners = array_sum($listeners);

if ($count != "0") 
	{
	$tweet = "$prefix $song - $total_listeners Locked - $url";
	}
else
	{
	$tweet = "$prefix $song - $url";
	}

if ($refresh != "0") 
	{
	print "<html><head><meta http-equiv=\"refresh\" content=\"$refresh\"></head><body>$tweet</body></html>\n";
	}
else
	{
	print "$tweet";
	}

$twitterObj->post('/statuses/update.json', array('status' => $tweet));

?>
