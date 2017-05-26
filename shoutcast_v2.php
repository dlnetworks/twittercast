<?php

include 'EpiCurl.php';
include 'EpiOAuth.php';
include 'EpiTwitter.php';

// Consumer key token
$consumer_key = 'xxxxxxxxxxxxxxxxxxxxxx';

// Consumer secret token
$consumer_secret = 'xxxxxxxxxxxxxxxxxxxx';

// Access Token
$token = 'xxxxxxxxxxxxxxxxxxxxx';

// Access Token Secret
$secret= 'xxxxxxxxxxxxxxxxxxxxx';

// URL to your Shoutcast server, including port (no http://)
$server = "127.0.0.1:8000";

// Shoutcast stream ID

$sid = "1";

// Admin password for your Shoutcast server
$password = "changemenow";

// END CONFIGURATION

$twitterObj = new EpiTwitter($consumer_key, $consumer_secret, $token, $secret);
$twitterObjUnAuth = new EpiTwitter($consumer_key, $consumer_secret);

// opens the xml and puts it to a variable for processing
$mysession = curl_init();
curl_setopt($mysession, CURLOPT_URL, "http://$server/admin.cgi?sid=$sid&mode=viewxml");
curl_setopt($mysession, CURLOPT_HEADER, false);
curl_setopt($mysession, CURLOPT_RETURNTRANSFER, true);
curl_setopt($mysession, CURLOPT_POST, false);
curl_setopt($mysession, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($mysession, CURLOPT_USERPWD, "admin:$password");
curl_setopt($mysession, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($mysession, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
$xml = curl_exec($mysession);
curl_close($mysession);

// replace dodgy character encoding data from xml
$xml = str_replace("***x27;", "'", $xml);
$xml = str_replace("&apos;", "'", $xml);
$xml = str_replace("&gt;", ">", $xml);

$tweet = "";
$listeners = "0";
$current_song = "";

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
global $current_song;

// check your XML stream from sc_serv for the tags available to you
// im just using current listeners and current song title

if ($curTag == "^SHOUTCASTSERVER^CURRENTLISTENERS") {
$listeners = $data;
}

if ($curTag == "^SHOUTCASTSERVER^SONGTITLE") {
$current_song = $data;
}

}

// control for parsing xml data
$xml_parser = xml_parser_create();
xml_set_element_handler($xml_parser, "startElement", "endElement");
xml_set_character_data_handler($xml_parser, "characterData");
xml_parse($xml_parser, $xml);
xml_parser_free($xml_parser);

// print "$tweet";
print "$current_song";

// tweet that shit
$twitterObj->post('/statuses/update.json', array('status' => $current_song));

?>
