<?php
header('Content-Type: text/html; charset=utf-8');

include 'EpiCurl.php';
include 'EpiOAuth.php';
include 'EpiTwitter.php';

// Consumer key token
$consumer_key = '********************';

// Consumer secret token
$consumer_secret = '*******************************************';

// Access Token
$token = '**************************************************';

// Access Token Secret
$secret= '******************************************';

// refresh time in seconds (0 to disable)
$refresh = "60";

// server ip/hostname (without http://)
// server port
// stream id (use "1" for DNAS 1.x)
// dnas admin pass

$ip = "127.0.0.1";
$port = "8000";
$dnas = "2";
$sid = "1";
$pass = "adminpass";

// if the title contains this text, do not tweet - set to "" to disable
$adtext1 = "sponsor1";
$adtext2 = "sponsor2";

// text to iclude at the beginning of the tweet - set to "" to disable
$prefix = "#NowPlaying";

// url to include at the end of the tweet - set to "" to disable
$weburl = "https://www.domain.com";

// include listener count in tweet (0 to disable)
$count = "1";

// full path to title.txt file
$path = "/full/path/to/title.txt";

// if the tweet contains this text, do not tweet - set to "" to disable
$adtext1 = "sponsor1";
$adtext2 = "sponsor2";

// END CONFIGURATION

$twitterObj = new EpiTwitter($consumer_key, $consumer_secret, $token, $secret);
$twitterObjUnAuth = new EpiTwitter($consumer_key, $consumer_secret);

$url = "http://$ip:$port/admin.cgi?mode=viewxml&sid=$sid&pass=$pass";

$stats = simplexml_load_file($url);

$title = $stats->SONGTITLE;
$listeners = $stats->CURRENTLISTENERS;

// build tweet

$tweet = "$title";
	
if ($prefix !== "") {
	$tweet = "$prefix $tweet";
}
if ($count === 1) {
	$tweet = "$tweet $listeners Locked";
}
if ($weburl !== "") {
	$tweet = "$tweet $weburl";
}

$fh = @fopen($path, 'r+'); 
$playing = @fread($fh, filesize($path)); 

if ($playing == $title."\n") { 
  	fclose($fh); 
  	die(0);
} else { 
  	@fclose($fh); 
  	$fh = fopen($path, 'w'); 
  	fwrite($fh, $title."\n");
  	fclose($fh);
	if ($adtext1 !== "" || $adtext2 !== "") {
		if (strpos($tweet, $adtext1) === false || strpos($tweet, $adtext2) === false) {
			$twitterObj->post('/statuses/update.json', array('status' => $tweet)); 
		} else {
			print "Ad detected! Not tweeting.\n"; 
		}
	} else {
		$twitterObj->post('/statuses/update.json', array('status' => $tweet));
	}
}

if ($refresh != "0") {
	print "<html><head><meta http-equiv=\"refresh\" content=\"$refresh\"></head><body>$tweet</body></html>\n";
} else {
	print "<html><head></head><body>$tweet</body></html>\n";
}
?>
