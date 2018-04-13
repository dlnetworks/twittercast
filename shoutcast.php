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
# shoutcast DNAS version (1 or 2)
# stream id (for v2 DNAS)
# add or remove servers as needed

$ip[0] = "127.0.0.1";
$port[0] = "8000";
$dnasv[0] = "2";
$sid[0] = "1";

$ip[1] = "127.0.0.1";
$port[1] = "8000";
$dnasv[1] = "2";
$sid[1] = "2";

$ip[2] = "127.0.0.1";
$port[2] = "8000";
$dnasv[2] = "1";
$sid[2] = "1";

# url to include at the end of the tweet
$url = "https://www.domain.com";

# text to iclude at the beginning of the tweet
$prefix = "#hastag";

# include listener count in tweet (0 to disable)
$count = "1";

# full path to title.txt file
$path = "/full/path/to/title.txt";

# if the tweet contains this text, do not tweet - set to "" to disable
$adtext = "sponsor";

// END CONFIGURATION

$twitterObj = new EpiTwitter($consumer_key, $consumer_secret, $token, $secret);
$twitterObjUnAuth = new EpiTwitter($consumer_key, $consumer_secret);

$i = "0";
$servers = count($ip);
while($i<=$servers)	{
	$fp = @fsockopen($ip[$i],$port[$i],$errno,$errstr,$timeout);
	if ($fp) {
		if ($dnasv[$i] == 2) {
			fputs($fp, "GET /7.html?sid=$sid[$i] HTTP/1.0\r\nUser-Agent: Mozilla/5.0 (The King Kong of Lawn Care)\r\n\r\n");
		}
		if  ($dnasv[$i] == 1) {
			fputs($fp, "GET /7.html HTTP/1.0\r\nUser-Agent: Mozilla/5.0 (The King Kong of Lawn Care)\r\n\r\n");
		}
		while (!feof($fp)) {
			$info = fgets($fp);
			};
		$info = str_replace('<HTML><meta http-equiv="Pragma" content="no-cache"></head><body>', "", $info);
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

if ($count == 1) {
	$tweet = "$prefix $song - $total_listeners Locked - $url";
} else {
	$tweet = "$prefix $song - $url";
}
if ($refresh != "0") {
	print "<html><head><meta http-equiv=\"refresh\" content=\"$refresh\"></head><body>$tweet</body></html>\n";
} else {
	print "$tweet";
}

$fh = @fopen($path, 'r+'); 
$playing = @fread($fh, filesize($path)); 

if ($playing == $song."\n") { 
  	fclose($fh); 
  	die(0);
} else { 
  	@fclose($fh); 
  	$fh = fopen($path, 'w'); 
  	fwrite($fh, $song."\n");
  	fclose($fh);
	if ($adtext !== "") {
		$adchk = strpos($tweet, $adtext);
		if ($adchk === false) {
			$twitterObj->post('/statuses/update.json', array('status' => $tweet)); 
		} else {
			print "Ad detected! Not tweeting.\n"; 
		}
	} else {
		$twitterObj->post('/statuses/update.json', array('status' => $tweet));
	}
} 
?>
