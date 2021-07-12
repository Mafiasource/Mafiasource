<?PHP

namespace src\Business;

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
        if($security->checkToken($securityToken) == FALSE)
            $error = "Ongeldige security token, als dit probleem blijft aanhouden zorg dan voor een constante verbinding met dezelfde modem.";
        else
        {
            if($this->data->emailExists($email) == FALSE)
                $error = "Ongeldige gebruikersnaam of wachtwoord.";
            else
            {
                if($this->data->login($email, $password, $remember) == FALSE)
                    $error = "Ongeldige gebruikersnaam of wachtwoord.";
            }
        }
        if(isset($error))
            return $error;
        else
            return TRUE;
    }
    
    public function checkCookieHash($hash, $id)
    {
        return $this->data->checkCookieHash($hash, $id);
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
        if(isset($_COOKIE['rememberme']) && isset($_COOKIE['MID']))
        {
            if(!$this->checkCookieHash($_COOKIE['rememberme'], $_COOKIE['MID']))
                $route->headTo('admin-login');
        }
        if(!isset($_SESSION['cp-logon']))
            $route->headTo('admin-login');
    }
    
    public function redirectIfLoggedIn()
    {
        global $route;
        if(isset($_COOKIE['rememberme']) && isset($_COOKIE['MID']))
        {
            if($this->checkCookieHash($_COOKIE['rememberme'], $_COOKIE['MID']))
                $route->headTo('admin');
        }
        if(isset($_SESSION['cp-logon']))
            $route->headTo('admin');
    }
    
    public function getStatus($id = "")
    {
        return $this->data->getStatus($id);
    }
    
    public function saveNewAccountSettings($data)
    {
        return $this->data->saveNewAccountSettings($data);
    }
    
    public function checkPassword($password)
    {
        return $this->data->checkPassword($password);
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
