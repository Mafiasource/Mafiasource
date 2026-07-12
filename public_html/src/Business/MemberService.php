<?PHP

namespace src\Business;

use src\Business\Logic\LoginAbuseService;
use src\Business\UserCoreService;
use src\Data\MemberDAO;

class MemberService
{
    private $data;
    public $cities = array(
        1 => 'Aartselaar',
        'Antwerpen',
        'Arendonk',
        'Baarle-Hertog',
        'Balen',
        'Beerse',
        'Berlaar',
        'Boechout',
        'Bonheiden',
        'Boom',
        'Bornem',
        'Borsbeek',
        'Brasschaat',
        'Brecht',
        'Dessel',
        'Duffel',
        'Edegem',
        'Essen',
        'Geel',
        'Grobbendonk',
        'Heist-op-den-Berg',
        'Hemiksem',
        'Herentals',
        'Herenthout',
        'Herselt',
        'Hoogstraten',
        'Hove',
        'Hulshout',
        'Kalmthout',
        'Kapellen',
        'Kasterlee',
        'Kontich',
        'Laakdal',
        'Lier',
        'Lille',
        'Lint',
        'Malle',
        'Mechelen',
        'Meerhout',
        'Merksplas',
        'Mol',
        'Mortsel',
        'Niel',
        'Nijlen',
        'Olen',
        'Oud-Turnhout',
        'Putte',
        'Puurs',
        'Ranst',
        'Ravels',
        'Retie',
        'Rijkevorsel',
        'Rumst',
        'Schelle',
        'Schilde',
        'Schoten',
        'Sint-Amands',
        'Sint-Katelijne-Waver',
        'Stabroek',
        'Turnhout',
        'Vorselaar',
        'Vosselaar',
        'Westerlo',
        'Wijnegem',
        'Willebroek',
        'Wommelgem',
        'Wuustwezel',
        'Zandhoven',
        'Zoersel',
        'Zwijndrecht',
        'Alken',
        'As',
        'Beringen',
        'Bilzen',
        'Bocholt',
        'Borgloon',
        'Bree',
        'Diepenbeek',
        'Dilsen-Stokkem',
        'Genk',
        'Gingelom',
        'Halen',
        'Ham',
        'Hamont-Achel',
        'Hasselt',
        'Hechtel-Eksel',
        'Heers',
        'Herk-de-Stad',
        'Herstappe',
        'Heusden-Zolder',
        'Hoeselt',
        'Houthalen-Helchteren',
        'Kinrooi',
        'Kortessem',
        'Lanaken',
        'Leopoldsburg',
        'Lommel',
        'Lummen',
        'Maaseik',
        'Maasmechelen',
        'Meeuwen-Gruitrode',
        'Neerpelt',
        'Nieuwerkerken',
        'Opglabbeek',
        'Overpelt',
        'Peer',
        'Riemst',
        'Sint-Truiden',
        'Tessenderlo',
        'Tongeren',
        'Voeren',
        'Wellen',
        'Zonhoven',
        'Zutendaal',
        'Aalst',
        'Aalter',
        'Assenede',
        'Berlare',
        'Beveren',
        'Brakel',
        'Buggenhout',
        'De Pinte',
        'Deinze',
        'Denderleeuw',
        'Dendermonde',
        'Destelbergen',
        'Eeklo',
        'Erpe-Mere',
        'Evergem',
        'Gavere',
        'Gent',
        'Geraardsbergen',
        'Haaltert',
        'Hamme',
        'Herzele',
        'Horebeke',
        'Kaprijke',
        'Kluisbergen',
        'Knesselare',
        'Kruibeke',
        'Kruishoutem',
        'Laarne',
        'Lebbeke',
        'Lede',
        'Lierde',
        'Lochristi',
        'Lokeren',
        'Lovendegem',
        'Maarkedal',
        'Maldegem',
        'Melle',
        'Merelbeke',
        'Moerbeke',
        'Nazareth',
        'Nevele',
        'Ninove',
        'Oosterzele',
        'Oudenaarde',
        'Ronse',
        'Sint-Gillis-Waas',
        'Sint-Laureins',
        'Sint-Lievens-Houtem',
        'Sint-Martens-Latem',
        'Sint-Niklaas',
        'Stekene',
        'Temse',
        'Waarschoot',
        'Waasmunster',
        'Wachtebeke',
        'Wetteren',
        'Wichelen',
        'Wortegem-Petegem',
        'Zele',
        'Zelzate',
        'Zingem',
        'Zomergem',
        'Zottegem',
        'Zulte',
        'Zwalm',
        'Aarschot',
        'Affligem',
        'Asse',
        'Beersel',
        'Begijnendijk',
        'Bekkevoort',
        'Bertem',
        'Bever',
        'Bierbeek',
        'Boortmeerbeek',
        'Boutersem',
        'Diest',
        'Dilbeek',
        'Drogenbos',
        'Galmaarden',
        'Geetbets',
        'Glabbeek',
        'Gooik',
        'Grimbergen',
        'Haacht',
        'Halle',
        'Herent',
        'Herne',
        'Hoegaarden',
        'Hoeilaart',
        'Holsbeek',
        'Huldenberg',
        'Kampenhout',
        'Kapelle-op-den-Bos',
        'Keerbergen',
        'Kortenaken',
        'Kortenberg',
        'Kraainem',
        'Landen',
        'Lennik',
        'Leuven',
        'Liedekerke',
        'Linkebeek',
        'Linter',
        'Londerzeel',
        'Lubbeek',
        'Machelen',
        'Meise',
        'Merchtem',
        'Opwijk',
        'Oud-Heverlee',
        'Overijse',
        'Pepingen',
        'Roosdaal',
        'Rotselaar',
        'Scherpenheuvel-Zichem',
        'Sint-Genesius-Rode',
        'Sint-Pieters-Leeuw',
        'Steenokkerzeel',
        'Ternat',
        'Tervuren',
        'Tielt-Winge',
        'Tienen',
        'Tremelo',
        'Vilvoorde',
        'Wemmel',
        'Wezembeek-Oppem',
        'Zaventem',
        'Zemst',
        'Zoutleeuw',
        'Alveringem',
        'Anzegem',
        'Ardooie',
        'Avelgem',
        'Beernem',
        'Blankenberge',
        'Bredene',
        'Brugge',
        'Damme',
        'De Haan',
        'De Panne',
        'Deerlijk',
        'Dentergem',
        'Diksmuide',
        'Gistel',
        'Harelbeke',
        'Heuvelland',
        'Hooglede',
        'Houthulst',
        'Ichtegem',
        'Ieper',
        'Ingelmunster',
        'Izegem',
        'Jabbeke',
        'Knokke-Heist',
        'Koekelare',
        'Koksijde',
        'Kortemark',
        'Kortrijk',
        'Kuurne',
        'Langemark-Poelkapelle',
        'Ledegem',
        'Lendelede',
        'Lichtervelde',
        'Lo-Reninge',
        'Menen',
        'Mesen',
        'Meulebeke',
        'Middelkerke',
        'Moorslede',
        'Nieuwpoort',
        'Oostende',
        'Oostkamp',
        'Oostrozebeke',
        'Oudenburg',
        'Pittem',
        'Poperinge',
        'Roeselare',
        'Ruiselede',
        'Spiere-Helkijn',
        'Staden',
        'Tielt',
        'Torhout',
        'Veurne',
        'Vleteren',
        'Waregem',
        'Wervik',
        'Wevelgem',
        'Wielsbeke',
        'Wingene',
        'Zedelgem',
        'Zonnebeke',
        'Zuienkerke',
        'Zwevegem'
    );
    
    public function __construct()
    {
        $this->data = new MemberDAO();
    }
    
    public function __destruct()
    {
        $this->data = null;
    }
    
    public static function is_email($em){
    	
    	$ema = filter_var($em, FILTER_VALIDATE_EMAIL);
    	
    	if(!$ema){
    		return FALSE;
    	} else {
    		return TRUE;
    	}
    }
    
    public function login($email, $password, $securityToken, $remember = false)
    {
        global $security;
        global $language;
        global $langs;
        global $user;
        $l = $language->loginLangs();
        $l['NONE'] = $langs['NONE'];
        $loginAbuse = new LoginAbuseService($this->data);
        $ipAddr = UserCoreService::getIP();
        $permBan = $user->checkPermBannedIP($ipAddr);
        // $type 1=Credentials | 2=Violation | 3=Warning | 4=Temp. IP Ban | 5=Perm. IP Ban
        $loginAbuseState = $loginAbuse->getLoginAbuseState($l, $permBan);
        $laMsg = $loginAbuseState['message'];
        $type = $loginAbuseState['type'];

        if($security->checkToken($securityToken) == FALSE || !$user->ipValid)
            $error = "Ongeldige security token, als dit probleem blijft aanhouden zorg dan voor een constante verbinding met dezelfde modem.";

        if($loginAbuseState['blocked'])
            $error = $l['TEMPORARILY_IP_BANNED'] . " ";

        if(isset($error))
        {
            $this->data->loginFailed($email, $type);
            return $laMsg === $error ? $laMsg : $laMsg . $error;
        }

        if($this->data->emailExists($email) == FALSE)
        {
            $this->data->loginFailed($email, 1);
            $error = "Ongeldige gebruikersnaam of wachtwoord.";
        }
        else
        {
            if($this->data->login($email, $password, $remember) == FALSE)
            {
                $this->data->loginFailed($email, 1);
                $error = "Ongeldige gebruikersnaam of wachtwoord.";
            }
        }
        if(isset($error))
            return $laMsg . $error;
        else
            return TRUE;
    }
    
    public function createMember($email, $password, $naam = "", $voornaam = "", $adres = "", $gemeente = "", $postcode = "")
    {
        return $this->data->createMember($email, $password, $naam, $voornaam, $adres, $gemeente, $postcode);
    }
    
    public function emailExists($email)
    {
        return $this->data->emailExists($email);
    }
    
    public function redirectIfLoggedOut()
    {
        global $route;
        if(!isset($_SESSION['cp-logon']) && isset($_COOKIE['rememberme']) && isset($_COOKIE['MID']))
        {
            if($this->adminCookieLoginAbuseBlocked())
                $route->headTo('admin-login');

            if(!$this->data->verifyCookieHash($_COOKIE['rememberme'], $_COOKIE['MID']))
                $route->headTo('admin-login');
        }
        if(!isset($_SESSION['cp-logon'])) // Re-check could be set in verifyCookieHash on success.
            $route->headTo('admin-login');
    }
    
    public function redirectIfLoggedIn()
    {
        global $route;
        if(!isset($_SESSION['cp-logon']) && isset($_COOKIE['rememberme']) && isset($_COOKIE['MID']))
        {
            if($this->adminCookieLoginAbuseBlocked())
                return;

            if($this->data->verifyCookieHash($_COOKIE['rememberme'], $_COOKIE['MID']))
                return $route->headTo('admin');
        }
        if(isset($_SESSION['cp-logon']))
            return $route->headTo('admin');
    }

    private function adminCookieLoginAbuseBlocked()
    {
        $ipAddr = UserCoreService::getIP();
        if($this->data->cookieLoginAbuseRequiresPermanentBan($ipAddr))
        {
            $this->data->addPermanentBannedIP($ipAddr);
            return TRUE;
        }

        return FALSE;
    }
    
    public function getStatus($id = "")
    {
        return $this->data->getStatus($id);
    }
    
    public function saveNewAccountSettings($data)
    {
        return $this->data->saveNewAccountSettings($data);
    }
    
    public function verifyPassword($password)
    {
        return $this->data->verifyPassword($password);
    }
    
    public function changePassword($password)
    {
        return $this->data->changePassword($password);
    }
    
    public function getGemeentes()
    {
        return $this->cities;
    }
}
