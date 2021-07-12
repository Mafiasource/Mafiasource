<?PHP

use src\Business\UserService;

require_once __DIR__ . '/.inc.head.ajax.php';

if(isset($_POST['security-token']) && $security->checkToken($_POST['security-token']))
{
    $userService = new UserService();
    $userService->removeStatusProtection();
}
