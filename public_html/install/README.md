## Getting started with a few notes
This install directory should be deleted after a successful installation.
In this document '/../' refers to one directory above the rooted public_html directory on the web server.
Don't forget to add the [missing resources (download)](https://www.mafiasource.nl/web/downloads/public_html.zip) to your public_html directory.
/admin access equals superpowers, beware to whom you grant access.
Admin fields that seem locked can be overwritten.

## Requirements
This application runs stable on web servers with the following (min) requirements:
- Web server 128MB+ RAM - 256MB+ storage 1GB+ preferred - Linux OS x32_64 kernel v3.10+
- Apache 2
- PHP 5.6 or higher with extensions PDO, PDO_MYSQL, DOM, IMAP, SOCKETS, GD
- PHP 7.2 or higher with extensions PDO, PDO_MYSQL, DOM, IMAP, SOCKETS, GD (Preferred)
- PHP 8.0 or higher with extensions PDO, ND_PDO_MYSQL, DOM, IMAP, SOCKETS, GD
- MySQL 13 or higher or MariaDB 10 or higher

## Dependencies, managed manually
All come bundled within the source in /vendor/ updating any library can result in new errors.
Except for these first few packages updates are likely to remain unnecessary for various library packages.
Execute the following composer commands in the vendor directory to update used libraries:
- composer require doctrine/common
- composer require "twig/twig:^3.0"
- composer require voku/anti-xss
- composer require phpmailer/phpmailer

Some libraries not governed by composer package manager:
- /vendor/Images/SimpleImage.php
- /vendor/SessionManager.php

Available with composer package manager but hosted in a different location:
- In /web/lib/ckeditor/ run: composer require "ckeditor/ckeditor:^4.0"
- Running the same command in ../ckeditorgame/ will override the limited text editor for game users!
Customize ckeditor(game) at: https://ckeditor.com/ckeditor-4/download/

## Built in simplified installation process
Requires a web server, domainname, mysql credentials and a correct configured SSL/HTTPS certificate.
No SSL support? Skip to 'App wont work on a localhost environment without SSL support.' first.
1) Create a new subdomain 'static' for your domainname and link it to your public_html directory.
2) Have a fresh database name, user and password ready create these new credentials if necessary.
3) Upload /../security.php and public_html to your web server.
4) After uploading browse to your website through following URI: https://www.domainname.ex/install
5) Follow installation instructions on screen, finish with a successful installation.

The following source code files should have been modified after a successful installation:
- /../security.php
- /.htaccess
- /app/config/config.php
- replaces all hard coded mafiasource.nl instances in all .css, .json and .xml files.
- activates all /app/cronjob/ jobs on the web server.

A successful installation should render a fresh copy of Mafiasource on your web server without issues.
Remove the entire /install/ directory in public_html if this is the case.
Otherwise, refer to 'Successful installation but still a blank application like initially.'

You can backup your customized security, htaccess, config and the few *.css, *.json and *.xml files somewhere safe.
Overwrite these custom backup files after every merge and rebase to stay up-to-date with possible future updates.
This tactic won't update .css style changes, unless these files are changed manually.

## Troubleshooting common problems and answers
Please keep the following in mind throughout some P) and A) topics defined below.
- Try to change as little as possible manually especially during troubleshooting.
- Only these globals should be changed freely SSL_ENABLED, DEVELOPMENT before install.
- All good before installation = a working /install and blank / web root without any errors. (Ideal, use installer)

###### P) Application installer takes forever to load, than redirects me to a not found page.
###### A) You are trying to connect to a mysql host that is unreachable by the application.
Or might even simply choose not to reply, both cases are quite hard to troubleshoot but you can start here:
1) Verify if you can ping the mysql host, retrieve an IP address when successful.
2) OPTIONAL Some mysql servers can operate on a different port than default 3306. Add your custom port in:
    1) /app/config/config.php Line 22 add into PDO_CONSTRING: ;port=PORTNO
    2) /install/config/DBConfig.php Line 21 add into _PDO_CONSTRING_INSTALL: ;port=PORTNO
3) Instead of trying to connect through DNS you can try to insert the server's IP address as mysql host.

###### P) App wont work on a localhost environment without SSL support.
###### A) Application can throw a server error due to 2 miss configurations set while browsing localhost.
1) In /app/config/config.php edit SSL_ENABLED from true to false. (Not localhost, no SSL? Steps 2 and 4 not required)
2) Add the following lines to your hosts file: (Tested on xampp for Windows with php8.0.8)
```
    #C:/Windows/system32/drivers/etc/hosts (Windows) or /etc/hosts (Linux)
    127.0.0.1 localhost
    127.0.0.1 static.localhost
    ## Optional! Test your own ficticious domain locally: (http://www.domainname.ex)
    #127.0.0.1 domainname.ex
    #127.0.0.1 static.domainname.ex
    #127.0.0.1 www.domainname.ex
    #127.0.0.1 www.static.domainname.ex
```
3) Ready to install on a local non-secure environment at http://localhost/install or http://www.domainname.ex/install
4) Server error persists? Edit /.htaccess and comment out all lines from 88 to 94 including, after installation.
5) Still nothing? Maybe '500 or above server error persist' could help.

###### P) Successful installation but still a blank application like initially.
###### A) Standard configurations should be replaced everywhere, permissions and or restrictions could have avoided this.
1) Check the first 3 files (security, htaccess, config) to verify that all definitions are correct including SSL_ENABLED.
Making these changes without the installation GUI can solve this issue without altering permissions or php settings.
In .htaccess only the correct domainname is of concern.
2) If your website works, manually find and replace all mafiasource.nl instances to solve styling, json and or xml issues.
3) Import /install/config/clean-database.sql manually into your database.
4) Configure /app/cronjob/ jobs on the web server to finalize installation.
```
    # Cronjobs: Replace brackets [..] with php binary locaction and the user directory (/[userDir]/public_html) name.
      0	  *   *	  *   *   [/path/to/php] /home/[userDir]/public_html/app/cronjob/hour.php	    
      */5 *   *	  *   *   [/path/to/php] /home/[userDir]/public_html/app/cronjob/five_minutes.php	    
      *	  *   *	  *   *   [/path/to/php] /home/[userDir]/public_html/app/cronjob/one_minute.php	    
      0	  0   *	  *   *   [/path/to/php] /home/[userDir]/public_html/app/cronjob/day.php	    
      0	  19  *   *   0   [/path/to/php] /home/[userDir]/public_html/app/cronjob/week.php	    
      0	  4   *   *   *   [/path/to/php] /home/[userDir]/public_html/app/cronjob/dbbackup.php
```

###### P) Getting message 'An error occured while connecting..'
###### A) Will pop-up after a successful manual server installation but without a proper database connection.
1) MySQL is not running on the configured web server. (Server side)
2) The MySQL server database name and or database user and or password are incorrectly configured. (Server side)
3) OPTIONAL Remote MySQL? Make sure your web server IP address is whitelisted if remote connections are restricted. (MySQL Server side)
4) The provided database credentials in /../security.php are invalid. (Application side)
5) Double check any connection restrictions that might have been set.

###### P) 500 or above server error persist
###### A) Not entirely sure yet as there could be a number of (still unknown) reaons
- Missing resources can cause internal server error on strict configured web servers
- Enable any missing extensions
- Make sure PHP5.6 or above is installed
- Check if Apache2 is functioning correctly, any hints within apache error logs?
- Remove /.htaccess to see if server error disappears if so test piece by piece to find error causing lines.
- DEVELOPMENT global set to true can enable live errors.

If all else failed please create an issue, describe your problem as best you can.

## LICENSE DISCLAIMER
The license included by this software does NOT apply to the following resources used by this project:
  /vendor/ + All files and subdirectories and files (Licenses included)
  /web/lib/ + All files and subdirectories and files (Licenses included)
  /web/public/foto.php (License top of file)
  /web/public/images/captcha/fonts/ + All subdirectories and files (Unknown holders)
  /web/public/images/users/ + All subdirectories and files (Unknown holders)
  /web/public/images/families/ + All subdirectories and files (Unknown holders)

Unknown holders own licenses to most 'by user inputted' resources.

## Donate
Any spare crypto sent my way is greatly appreciated!
ETH: 0x6508a7d92fF6eE978E82481C98E991D808283FE5
BTC: bc1qcj2fr8t6feaedzmy5fxmtnyyn2qe52n2re59nc
