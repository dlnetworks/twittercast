Twittercast - Shoutcast Song Title To Twitter Status Script


1. Register a new app at http://dev.twitter.com/apps/new
2. Fill in tokens and server info in twitter.php
3. Upload title.txt, twitter.php, EpiCurl.php, EpiOAuth.php, and EpiTwitter.php to your webserver IN THE SAME FOLDER!
4. Make sure that title.txt is writable by ALL (chmod 777)
5. Visit twitter.php in your browser or setup a cron job (explained earlier in this thread)

cron job example:

*/3 * * * * php /home/username/public_html/domain.com/twittercast/twitter.php

where the number '3' means that it will fire every 3 mins. you may change to your desire.

please also note that this script may not work with your hosting provider.
some hosting providers have specific php configurations that are missing some of the requirements needed for this script to work.
