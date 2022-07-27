<?PHP

use src\Business\MemberService;
use src\Business\AdminService;

require_once __DIR__ . '/.inc.head.php';

if(isset($_POST) && !empty($_POST))
{
    if(isset($_POST['submit-account-settings']) && $security->checkToken($_POST['submit-account-settings']))
    {
        $allowedFields = array("naam", "voornaam", "email", "adres", "postcode", "gemeente");
        foreach($_POST AS $key => $value) if(in_array($key, $allowedFields) && !empty($value)) $data[$key] = $security->xssEscape($value);
        
        $mmbr = new AdminService("member");
        $memberSettings = $mmbr->getTableRowById($_SESSION['cp-logon']['MID']);
        if(isset($data['email']) && (!MemberService::is_email($data['email']) || ($member->emailExists($data['email']) && $data['email'] != $memberSettings[0][0]['email'])))
            $response = $route->errorMessage("Je hebt een ongeldig email adres opgegeven!");
        else
            $response = $member->saveNewAccountSettings($data);
    }
    elseif(isset($_POST['submit-passchange']) && $security->checkToken($_POST['submit-passchange']))
    {
        if(isset($_POST['password_old']) && $member->verifyPassword($_POST['password_old']))
        {
            if(isset($_POST['password']) && isset($_POST['password_repeat']) && $_POST['password'] === $_POST['password_repeat'])
            {
                if(strlen($_POST['password']) >= 5)
                {
                    $member->changePassword($_POST['password']);
                    unset($_SESSION['cp-logon']);
                    $response = $route->successMessage("Wachtwoord werd succesvol aangepast, om veiligheids reden word je automatisch overal uitgelogd.");
                    
                    $response['alert']['message'] .=
                    '<script type="text/javascript">
                    window.setTimeout(function(){
                        window.location.href = "http://'.$_SERVER['HTTP_HOST'].$route->getRouteByRouteName('admin-login').'";
                    }, 2000);
                    </script>';
                }
                else
                    $response = $route->errorMessage("Het nieuwe wachtwoord moet minimaal 5 tekens bevatten!");
            }
            else
                $response = $route->errorMessage("Het nieuwe en te herhalen wachtwoord kwam niet overeen.");
        }
        else
            $response = $route->errorMessage("Je hebt een onjuist huidig wachtwoord opgegeven.");
    }
    else
        $response = $route->errorMessage("Verkeerde gegevens ontvangen. (Vernieuw de pagina met shortkey: f5 of ctrl + f5)");
    
    $twigVars['response'] = $response;
    
    print_r($twig->render('/src/Views/admin/Ajax/.default.response.twig', $twigVars));
    exit(0);
}
else
{
    $table = new AdminService("member");
    $settings = $table->getTableRowById($_SESSION['cp-logon']['MID']);
    $gemeentes = $member->getGemeentes();
    
    require_once __DIR__ . '/.inc.foot.php';
    $twigVars['settings'] = $settings[0][0];
    $twigVars['gemeentes'] = $gemeentes;
    
    print_r($twig->render('/src/Views/admin/account-settings.twig', $twigVars));
}
