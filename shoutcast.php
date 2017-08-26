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

# Number of seconds before connecton times out.
$timeout = "5";

# server ip or hostname (without http://)
$ip = "127.0.0.1";

# server port
$port = "80";

# stream id. for V1 use sid 1
$sid = "1";

# url to include at the end of the tweet
$url = "https://www.domain.com";

# text to iclude at the beginning of the tweet
$prefix = "#hastag";

//END CONFIGURATION

$twitterObj = new EpiTwitter($consumer_key, $consumer_secret, $token, $secret);
$twitterObjUnAuth = new EpiTwitter($consumer_key, $consumer_secret);
$fp = @fsockopen($ip,$port,$errno,$errstr,$timeout);
if ($fp) {
	fputs($fp, "GET /7.html?sid=$sid HTTP/1.0\r\nUser-Agent: Mozilla/5.0 (The King Kong of Lawn Care)\r\n\r\n");
	while (!feof($fp)) {
		$info = fgets($fp);
		};
	$info = str_replace('<html><body>', "", $info);
	$info = str_replace('</body></html>', "", $info);
	$stats = explode(',', $info);
	$song = $stats[6];
};

$tweet = "$prefix: $song - $url";

print "$tweet"; // output preview

$twitterObj->post('/statuses/update.json', array('status' => $tweet)); 
?>
