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

$twitterObj = new EpiTwitter($consumer_key, $consumer_secret, $token, $secret);
$twitterObjUnAuth = new EpiTwitter($consumer_key, $consumer_secret);
 
$SERVER = 'http://127.0.0.1:8035'; // url to icecast server and port
$STATS_FILE = '/status.xsl?mount=/stream'; // path to status.xsl and mountpoint

// END CONFIGURATION

//create a new curl resource 
$ch = curl_init(); 

//set url 
curl_setopt($ch,CURLOPT_URL,$SERVER.$STATS_FILE); 

//return as a string 
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 

//$output = our stauts.xsl file 
$output = curl_exec($ch); 

//close curl resource to free up system resources 
curl_close($ch); 

//build array to store our radio stats for later use 
$radio_info = array(); 
$radio_info['server'] = $SERVER; 
$radio_info['title'] = ''; 
$radio_info['description'] = ''; 
$radio_info['content_type'] = ''; 
$radio_info['mount_start'] = ''; 
$radio_info['bit_rate'] = ''; 
$radio_info['listeners'] = ''; 
$radio_info['most_listeners'] = ''; 
$radio_info['genre'] = ''; 
$radio_info['url'] = ''; 
$radio_info['now_playing'] = array(); 
$radio_info['now_playing']['artist'] = ''; 
$radio_info['now_playing']['track'] = ''; 

//loop through $ouput and sort into our different arrays 
$temp_array = array(); 

$search_for = "<td\s[^>]*class=\"streamdata\">(.*)<\/td>"; 
$search_td = array('<td class="streamdata">','</td>'); 

if(preg_match_all("/$search_for/siU",$output,$matches)) { 
   foreach($matches[0] as $match) { 
      $to_push = str_replace($search_td,'',$match); 
      $to_push = trim($to_push); 
      array_push($temp_array,$to_push); 
   } 
} 

//sort our temp array into our ral array 
$radio_info['title'] = $temp_array[0]; 
$radio_info['description'] = $temp_array[1]; 
$radio_info['content_type'] = $temp_array[2]; 
$radio_info['mount_start'] = $temp_array[3]; 
$radio_info['bit_rate'] = $temp_array[4]; 
$radio_info['listeners'] = $temp_array[5]; 
$radio_info['most_listeners'] = $temp_array[6]; 
$radio_info['genre'] = $temp_array[7]; 
$radio_info['url'] = $temp_array[8]; 
$x = explode(" - ",$temp_array[9]); 
$radio_info['now_playing']['artist'] = $x[0]; 
$radio_info['now_playing']['track'] = $x[1]; 

?>

<?php foreach($radio_info as $title => $data) { switch($title) { case 'now_playing' : ?>
<?php print "$data[artist] - $data[track]"; // so you can see what the output looks like

$twitterObj->post('/statuses/update.json', array('status' => $data[artist] .' - ' .$data[track])); 
} } ?> 
