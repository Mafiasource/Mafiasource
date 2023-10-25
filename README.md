[![Codacy Badge](https://api.codacy.com/project/badge/Grade/c04f46d9b89e4f68a68950c614e1eede)](https://app.codacy.com/gh/Mafiasource/Mafiasource?utm_source=github.com&utm_medium=referral&utm_content=Mafiasource/Mafiasource&utm_campaign=Badge_Grade_Settings)
## About
Mafiasource is a free text based online Mafia RPG evolved from crimeclub.
Conquer states and cities in the U.S.
Build an empire with your friends and family and pave your way to the wealthiest Mafia families.
Will you become the best gangster?

Inspired by crimeclub.nl made open-source for all enthusiasts, enjoy a fork!
Version 3 its development started in January 2016 starting from a custom MVC pattern design.
The last known bug got fixed on June 26th 2021, all errors should log to all error_log files.
Future bug fixes and security patches will be pushed to the main branch.
Official website releases and feature developments were halted as of October 7th 2023.

This project is not a professional application and shouldn't be used as such.
Especially true for almost all security practices found inside this project.

Refer to [/install/README.md](https://github.com/Mafiasource/Mafiasource/blob/main/public_html/install/README.md) for more information regarding the installation process.
Made possible by © 2016 Michael Carrein inspired by © 2006 crimeclub.nl

## Directory above web root
Contents of the web server home directory (one directory above public_html) where sensitive statics are defined.
- credentials.php = sensitive statics, these don't guarantee a restriction from public_html/ authorized developers since they could print these values easily without direct access.
- error_log should log all cronjob thrown errors. Not necessarily localhost. (not included)
- public_html/ the actual web app root directory.

## Missing resources
Can be downloaded from: https://download.mafiasource.nl/web/downloads/public_html.zip
These include all images excluded in gitignore and 1 custom ckeditor(game) package.

## Donate
Any spare crypto sent my way is greatly appreciated!
ETH: 0x6508a7d92fF6eE978E82481C98E991D808283FE5
BTC: bc1qcj2fr8t6feaedzmy5fxmtnyyn2qe52n2re59nc
