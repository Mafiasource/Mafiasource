<?PHP

if(!$user->checkLoggedSession($update = false)) exit(0);
if(!is_object($userData)) exit(0);
if($security->checkSSL() === false) exit(0);
