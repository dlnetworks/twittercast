Twittercast - Shoutcast/Icecast Song Title To Twitter PHP Status Script

Supports Shoutcast V1 and V2 or Icecast

1. Register a new app at http://dev.twitter.com/apps/new
2. Fill in API keys and server info in shoutcast_v1.php or shoutcast_v2.php or icecast.php
3. Upload all files to your webserver in a web accessable directory
4. Set permissions on uploaded files to 755 or +x
4. Visit shoutcast_v1.php or shoutcast_v2.php or icecast.php in your browser to trigger an update or setup a cron job

Customized output examples:

V1:

$twitterObj->post('/statuses/update.json', array('status' => '#tag '.$song[1].' http://url.com #notagbacks'));

V2:

$twitterObj->post('/statuses/update.json', array('status' => '#tag '.$current_song.' http://url.com #notagbacks'));

Iceacst:

$twitterObj->post('/statuses/update.json', array('status' => '#tag '.$current_song.' - '.$listeners.' Listeners http://url.com #notagbacks'));

cron job example:

*/3 * * * * php /home/username/public_html/domain.com/twittercast/shoutcast_v1.php

In this example php will execute shoutcast_v1.php once ever 3 minutes. You may change to your desire.

some systems have different settings when running php from the command line... so you may also try:

*/3 * * * * wget http://www.domain.com/twittercast/shoutcast_v1.php

man crontab for more info.
man wget for more info

This script will work properly on a default LAMP stack.

** Many shared hosting prividers disable required php modules and outbound ports. Please check with your provider for supprt **

php module requirements

sockets
curl
pcre

I provide twittercast hosting if needed.


** Credits to https://github.com/jmathai/twitter-async for EpiTwitter **
