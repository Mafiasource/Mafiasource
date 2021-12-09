<?PHP

$loggedSession = $user->checkLoggedSession(false) ? true : false;
if(!$userSession || !$userData)
{
    if($loggedSession) $userData = $user->getUserData();
}
if(!$loggedSession || !is_object($userData) || $security->checkSSL() === false)
{
    exit(0);
}
