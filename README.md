Twittercast - Shoutcast/Icecast Song Title To Twitter Status Script

Supports Shoutcast V1 and V2 or Icecast

1. Register a new app at http://dev.twitter.com/apps/new
2. Fill in API keys and server info in shoutcast_v1.php or shoutcast_v2.php or icecast.php AND scroll to the bottom and enter in the full path to your title.txt file
3. Upload title.txt, shoutcast_v1.php or shoutcast_v2.php or icecast.php, EpiCurl.php, EpiOAuth.php, EpiSequence.php, and EpiTwitter.php to your webserver IN THE SAME DIRECTORY!
4. Change the file permissions for title.txt writable by ALL (chmod 777)
5. Visit shoutcast_v1.php or shoutcast_v2.php or icecast.php in your browser to trigger an update or setup a cron job

cron job example:

*/3 * * * * php5 /home/username/public_html/domain.com/twittercast/shoutcast_v1.php

In this example php will execute shoutcast_v1.php once ever 3 minutes. You may change to your desire.

some systems have different settings when running php from the command line... so you may also try:

*/3 * * * * wget http://www.domain.com/twittercast/shoutcast_v1.php

man crontab for more info.
man wget for more info

This script should work properly on a defalt LAMP stack.

php module requirements

sockets
curl
pcre

I can provide twittercast hosting services if desired.
