<?PHP

use src\Business\AdminService;
use src\Business\MemberService;

require_once __DIR__ . '/.inc.head.php';

if($member->getStatus() > 2) $route->headTo('admin');

if(isset($_POST) && !empty($_POST))
{
    if(isset($_POST['submit-create-member']) && $security->checkToken($_POST['submit-create-member']))
    {
        $allowedFields = array("naam", "voornaam", "email", "password", "password_check", "adres", "postcode", "gemeente");
        foreach($allowedFields AS $f)
        {        
            if(isset($_POST[$f]) && $f != 'password' && $f != 'password_check')
                $data[$f] = $security->xssEscape($_POST[$f]);
            elseif(isset($_POST[$f]) && ($f == 'password' || $f == 'password_check'))
                $data[$f] = $_POST[$f];
            else
                $data[$f] = "";
        }
        if(!isset($data['email']) || (!MemberService::is_email($data['email']) || $member->emailExists($data['email'])))
            $response = $route->errorMessage("Je hebt een ongeldig email adres opgegeven!");
        elseif(!isset($data['password']) || strlen($data['password']) < 5)
            $response = $route->errorMessage("Het wachtwoord moet minimaal 5 tekens bevatten!");
        elseif((!isset($data['password']) && !isset($data['password_check'])) || ($data['password'] != $data['password_check']))
            $response = $route->errorMessage("Beide wachtwoorden komen niet overeen.");
        else
        {
            $member->createMember($data['email'], $data['password'], $data['naam'], $data['voornaam'], $data['adres'], $data['gemeente'], $data['postcode']);
            $response = $route->successMessage("Account succesvol aangemaakt!");
        }
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
    
    print_r($twig->render('/src/Views/admin/create-member.twig', $twigVars));
}
