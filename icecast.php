<?php

include 'EpiCurl.php';
include 'EpiOAuth.php';
include 'EpiTwitter.php';

#Consumer key token
$consumer_key = 'xxxxxxxxxxxxxxxxxxxxxxxxxxx';

#Consumer secret token
$consumer_secret = 'xxxxxxxxxxxxxxxxxxxxxxxxxxx';

#Access Token
$token = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

#Access Token Secret 
$secret= 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

// IP:port or hostname:port of your Icecast server (no http://)
$server = "127.0.0.1:8000";

// specific stream mountoint (including preceeding slash)
$mount = "/mount";

// username and password for your Icecast server
$username = "source";
$password = "password";

// END CONFIGURATION

$twitterObj = new EpiTwitter($consumer_key, $consumer_secret, $token, $secret);
$twitterObjUnAuth = new EpiTwitter($consumer_key, $consumer_secret);

// opens the xml and puts it to a variable for processing
$mysession = curl_init();
curl_setopt($mysession, CURLOPT_URL, "http://$server/admin/stats.xml?mount=$mount");
curl_setopt($mysession, CURLOPT_HEADER, false);
curl_setopt($mysession, CURLOPT_RETURNTRANSFER, true);
curl_setopt($mysession, CURLOPT_POST, false);
curl_setopt($mysession, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($mysession, CURLOPT_USERPWD, "$username:$password");
curl_setopt($mysession, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($mysession, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
$xml = curl_exec($mysession);
curl_close($mysession);

// replace dodgy character encoding data from xml
$xml = str_replace("***x27;", "'", $xml);
$xml = str_replace("&apos;", "'", $xml);
$xml = str_replace("&gt;", ">", $xml);

// functions for parsing xml data
function startElement($parser, $name, $attrs) {
global $curTag;
$curTag .= "^$name";
}
function endElement($parser, $name) {
global $curTag;
$caret_pos = strrpos($curTag, '^');
$curTag = substr($curTag, 0, $caret_pos);
}

// translate XML data into usable variables
function characterData($parser, $data) {
global $curTag;

// add more variables here to get more info from XML
global $listeners;
global $title;
global $artist;
global $current_track;

if ($curTag == "^ICESTATS^SOURCE^LISTENERS") {
$listeners = $data;
}

if ($curTag == "^ICESTATS^SOURCE^ARTIST") {
$artist = $data;
}
  
if ($curTag == "^ICESTATS^SOURCE^TITLE") {
$title = $data;
}}

// control for parsing xml data
$xml_parser = xml_parser_create();
xml_set_element_handler($xml_parser, "startElement", "endElement");
xml_set_character_data_handler($xml_parser, "characterData");
xml_parse($xml_parser, $xml);
xml_parser_free($xml_parser);

// build tweet
$current_track = "#NowPlaying: $artist - $title - $listeners Listeners";
print "$current_track";

// post the tweet
$twitterObj->post('/statuses/update.json', array('status' => $current_track));

?>
