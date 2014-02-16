Twittercast - Shoutcast/Icecast Song Title To Twitter Status Script

Supports Shoutcast V1 and V2 or Icecast

1. Register a new app at http://dev.twitter.com/apps/new
2. Fill in API keys and server info in shoutcast.php or icecast.php (dont forget to set paths for title.txt!)
3. Upload title.txt, shoutcast_v1.php or shoutcast_v2.php or icecast.php, EpiCurl.php, EpiOAuth.php, and EpiTwitter.php to your webserver IN THE SAME DIRECTORY!
4. Change the file permissions for title.txt writable by ALL (chmod 777)
5. Visit shoutcast_v1.php or shoutcast_v2.php or icecast.php in your browser to trigger an update or setup a cron job.

cron job example:

*/3 * * * * php /home/username/public_html/domain.com/twittercast/shoutcast.php

In this example php will execute shoutcast.php once ever 3 minutes. You may change to your desire.

man crontab for more info.

ssh user@webhost.com




Please also note that this script may not work with your hosting provider. Many shared hosting providers have php configurations that will not support this script (no fsockopen).

I provide Twittercast hosting if needed. Please contact.

