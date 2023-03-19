## Getting started with a few notes
Don't forget to add the [missing resources (download)](https://download.mafiasource.nl/web/downloads/public_html.zip) to your public_html directory.
This install directory should be deleted after a successful installation.
In this document '/../' refers to one directory above the rooted public_html directory on the web server.
/admin access equals superpowers, beware to whom you grant access.
Admin fields that seem locked can be overwritten.

## Requirements
This application runs stable on web servers with the following (min) requirements
- Web server 128MB+ RAM, 256MB+ storage 1GB+ preferred - Linux OS x32_64 kernel v3.10+
- Apache 2
- PHP 8.0 or higher with extensions PDO, ND_PDO_MYSQL or PDO_MYSQL, DOM, SOCKETS, GD, CURL
- MySQL 8 or higher or MariaDB 10 or higher

## Dependencies
Only 1 comes bundled within the source its missing resources, customized /web/vendor/ckeditorgame/
1) All missing dependencies can be installed through [composer](https://getcomposer.org/)
2) Make sure composer is installed and php recognized as a command, add it to your system variables if needed.
3) From your CLI browse to public_html and public_html/web, execute the following command in __both directories__:
```
composer install
```

Execute the following command in __both directories__ to update used dependencies in the future.
```
composer update
```

Installing with composer.phar file instead, execute the following commands in both directories.
Make sure php is recognized as a command, add it to your system variables if needed.
```
curl -sS https://getcomposer.org/installer | php
php composer.phar install
```

To update in the future, execute the following in __both directtories__.
```
php composer.phar update
```
4) You are ready to move on to [Built in simplified installation process](#built-in-simplified-installation-process)

Some libraries not governed by composer package manager:
- /vendor/SimpleImage.php
- /vendor/SessionManager.php

Customize your [ckeditor(game)](https://ckeditor.com/ckeditor-4/download/)

## Built in simplified installation process
Requires a web server, domainname, mysql credentials and a correct configured SSL/HTTPS certificate.
No SSL support? Skip to [App wont work on a localhost environment without SSL support.](#p-app-wont-work-on-a-localhost-environment-without-ssl-support) first.
1) Configure static subdomain, localhost? see [hosts](#p-app-wont-work-on-a-localhost-environment-without-ssl-support) file.
  * Create a new subdomain 'static' for your domainname and link it to your public_html directory.
  * HTTPS? Make sure the static subdomain has a working certificate as well.
2) Have a fresh database name, user and password ready create these new credentials if necessary.
3) Upload /../credentials.php and public_html to your web server.
4) After uploading browse to your website through following URI: https://www.domainname.ex/install (https:// in front is important if applicable)
5) Follow installation instructions on screen, finish with a successful installation.

A successful installation should render a fresh copy of Mafiasource on your web server www.domainname.ex without issues. (CTRL + F5 might be required)
Remove the entire /install/ directory in public_html if this is the case.
Otherwise, refer to [Successful installation but still a blank application like initially.](#p-successful-installation-but-still-a-blank-application-like-initially)

The following source code files should have been modified after a successful installation:
- /../credentials.php
- /.htaccess
- /app/config/config.php
- Replaces all hard coded static.mafiasource.nl instances in:
  * /sw.js
  * /web/public/css/game.min.css
  * /web/public/css/homepage.min.css
- Lastly, does not replace but Activates all /app/cronjob/ jobs on the web server. (Only if PHP's exec() function is enabled / allowed)

You can backup the above modified source code files somewhere safe for later use.
Overwrite or gitignore these modified backup files after every merge and rebase to stay up-to-date with possible future updates.
This tactic won't update .css style changes, unless these files are changed manually.

## Troubleshooting common problems and answers
Please keep the following in mind throughout some P) and A) topics defined below.
- Try to change as little as possible manually especially during troubleshooting.
- Only these globals should be changed freely SSL_ENABLED, DEVELOPMENT before install.
- All good before installation = a working "/install" and blank "/" web root without any errors. (Ideal, use installer)

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
1) In /app/config/config.php edit SSL_ENABLED from true to false. (Not localhost but no SSL? Step 2 not required)
2) Add the following lines to your hosts file: (Tested on xampp for Windows with php8.0.8)
```
    #C:/Windows/system32/drivers/etc/hosts (Windows) or /etc/hosts (Linux)
    127.0.0.1 localhost
    127.0.0.1 static.localhost
    127.0.0.1 www.localhost
    ## Optional! Test your own ficticious domain locally: (http://www.domainname.ex)
    #127.0.0.1 domainname.ex
    #127.0.0.1 static.domainname.ex
    #127.0.0.1 www.domainname.ex
```
3) Ready to install on a local non-secure environment at http://localhost/install or http://www.domainname.ex/install
4) Still nothing? Maybe [500 or above server error persists](#p-500-or-above-server-error-persists) could help.

###### P) Successful installation but still a blank application like initially.
###### A) Standard configurations should be replaced everywhere, permissions and or restrictions could have avoided this.
1) Check the first 3 files (credentials, htaccess, config) to verify that all definitions are correct including SSL_ENABLED.
  - Making these changes without the installation GUI can solve this issue without altering permissions or php settings.
  - All good except /../credentials.php? Some servers only allow read but not write or nothing at all outside document root, in that case:
    - move /../credentials.php in public_html
    - modify app/config/config.php remove one path traversal for credentials.php '../'
    - edit /.htaccess to deny all access to credentials.php (preferably after installation with GUI, keeps lines the same during install)
  - In .htaccess only the correct domainname is of concern.
2) If your website works, manually find and replace all mafiasource.nl instances to solve styling / css issues.
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
3) MySQL database is not added to the MySQL user with the minimal permissions: DELETE, SELECT, DROP, INSERT, UPDATE (Server side)
4) OPTIONAL Remote MySQL? Make sure your web server IP address is whitelisted if remote connections are restricted. (MySQL Server side)
5) The provided database credentials in /../credentials.php are invalid. (Application side)
6) Double check any (remote) connection restrictions that might have been set.

###### P) 500 or above server error persists
###### A) Make sure the following requirements are met:
- [missing resources (download)](https://download.mafiasource.nl/web/downloads/public_html.zip) can cause internal server error on strict configured web servers
- Install any missing [dependencies](#dependencies)
- Enable any missing [extensions](#requirements)
- Make sure PHP 8.0 or above is installed, any hints within php error_log?
- Check if Apache2 is functioning correctly, any hints within apache error logs?
- Remove /.htaccess to see if server error disappears. If so test piece by piece to find error causing lines.
  - Some servers won't allow php_value or Options try to #comment these, Mafiasource can be installed without those.
  - If your apache server doesn't allow DirectoryIndex than Mafiasource cannot be installed among possible other restrictions. (rare, custom setups)
- DEVELOPMENT global set to true can enable live PHP errors to the browser, except for fatal errors refer to php error_log

If all else failed please create an issue, describe your problem as best you can.

## LICENSE DISCLAIMER
The license included by this software does NOT apply to the following resources used by this project:
- jQuery: +UI +UI touch punch + touchswipe + imageMapResizer (modified), Popper JS & Bootstrap: CSS + JS (Front-end resources)
- /sw.js (License top of file)
- /vendor/SessionManager.php (License top of file)
- /vendor/SimpleImage.php (License top of file)
- /web/public/foto.php (License top of file)
- /web/public/images/captcha/fonts/ + All subdirectories and files (Unknown holders)
- /web/public/images/users/ + All subdirectories and files (Unknown holders)
- /web/public/images/families/ + All subdirectories and files (Unknown holders)

Unknown holders own licenses to most 'by user inputted' resources.

## Donate
Any spare crypto sent my way is greatly appreciated!
ETH: 0x6508a7d92fF6eE978E82481C98E991D808283FE5
BTC: bc1qcj2fr8t6feaedzmy5fxmtnyyn2qe52n2re59nc
