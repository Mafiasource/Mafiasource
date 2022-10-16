<?PHP

$loggedSession = $user->checkLoggedSession(false) ? true : false;
if(!$userData)
{
    if($loggedSession)
        $userData = $user->getUserData();
}
$ajaxRequest = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === "XMLHttpRequest" ? true : false;
if(!$loggedSession || !is_object($userData) || $security->checkSSL() === false || $ajaxRequest === false)
{
    exit(0);
}
