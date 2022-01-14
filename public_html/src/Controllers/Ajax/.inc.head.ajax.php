<?PHP

$loggedSession = $user->checkLoggedSession(false) ? true : false;
if(!isset($_SESSION['UID']) || !$userData)
{
    if($loggedSession) $userData = $user->getUserData();
}
if(!$loggedSession || !is_object($userData) || $security->checkSSL() === false)
{
    exit(0);
}
