Twittercast - Shoutcast/Icecast Song Title To Twitter Status Script


1. Register a new app at http://dev.twitter.com/apps/new
2. Fill in API keys and server info in shoutcast.php or icecast.php (dont forget to set paths for title.txt!)
3. Upload title.txt, shoutcast.php/icecast.php, EpiCurl.php, EpiOAuth.php, and EpiTwitter.php to your webserver IN THE SAME DIRECTORY!
4. Change the file permissions for title.txt writable by ALL (chmod 777)
5. Visit shoutcast.php/icecast.php in your browser to trigger an update or setup a cron job.

cron job example:

*/3 * * * * php /home/username/public_html/domain.com/twittercast/shoutcast.php

In this example php will execute shoutcast.php once ever 3 minutes. You may change to your desire.

man crontab for more info.

Please also note that this script may not work with your hosting provider. Many shared hosting providers have php configurations that will not support this script (no fsockopen).

I provide Twittercast hosting if needed. Please contact.
