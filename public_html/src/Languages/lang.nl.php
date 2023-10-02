<?PHP

namespace src\Languages;

class GetLanguageContent
{
    public $langMap = array();
    public $lang = 'NL';
    
    public function __construct()
    {
        $this->langMap = $this->initBaseLangs();
    }
    
    public function initBaseLangs() // Base langs outgame + homepage
    {
        global $user;
        global $route;
        if($user->notIngame())
        {
            $langs = array(
                'HOME' => "Home",
                'LOGIN' => "Inloggen",
                'REGISTER' => "Registreren",
                'SCREENSHOTS' => "Screenshots",
                'FORUM' => "Forum",
                'USERNAME' => "Gebruikersnaam",
                'PASSWORD' => "Wachtwoord",
                'END_COPY' => "Alle rechten voorbehouden.",
                'TERMS_AND_CONDITIONS' => "Algemene voorwaarden",
                'PRIVACY_POLICY' => "Privacybeleid",
                'OFFLINE_MSG' => "We zijn momenteel offline wegens onderhoud.", //voorbereidingen voor komende reset!",
                'ONLINE_MSG' => "Er zijn momenteel <span class='online'><strong>{online}</strong></span> spelers online!",
                'PLAYERS_BEFORE_MSG' => "met <span class='total'>{totalPlayers}</span> criminelen! Of",
                'REGISTER_BTN' => "Doe mee",
                'CHANGE_LANG_SUCCESS' => "You have changed the language settings to English.",
                'COOKIES_ACCEPT' => "Onze website maakt gebruik van cookies om uw browse ervaring te verbeteren, <a href='/privacy-policy'><strong>bekijk privacybeleid</strong></a> voor meer informatie.",
                'LINK_PARTNERS_INFO' => "Maak kennis met onze <a href='".$route->getRouteByRouteName('link-partners')."'>linkpartners</a>.",
                'DOWNLOAD_APP' => "Download de app",
                'WRONG_CAPTCHA' => "Je hebt de verkeerde code ingevoerd!", // Use in & out-game
                'INVALID_SECURITY_TOKEN' => "Ongeldige beveiligingssleutel, vernieuw de pagina (F5) en probeer a.u.b. opnieuw. Indien u essentiele cookies blokkeert kan ook geen geldige beveiligingssleutel worden verleend.", // Use in & out-game
                'INFORMATION' => "Informatie", // Use in & out-game
                'TOPLIST' => "Toplijst", // Use in & out-game
                'NONE' => "Geen", // Use in & out-game
            );
        }
        if(!$user->notIngame()) // Base langs ingame
        {
            $langs = array(
                'GENERAL' => "Algemeen",
                'INFORMATION' => "Informatie", // Use in & out-game
                'NEWS' => "Nieuws",
                'PRISON' => "Gevangenis",
                'HONOR_POINTS' => "Eerpunten",
                'TRAVEL_AGENCY' => "Reisbureau",
                'GAME' => "Spel",
                'MARKET' => "Markt",
                'STOCK_EXCHANGE' => "Beurs",
                'EQUIPMENT_STORES' => "Uitrusting Winkels",
                'ESTATE_AGENCY' => "Makelaardij",
                'BULLET_FACTORIES' => "Kogelfabrieken",
                'HITLIST' => "Aanslagenlijst",
                'MURDER' => "Moorden",
                'HOSPITAL' => "Ziekenhuis",
                'GYM' => "Sportschool",
                'GROUND_MAP' => "Plattegrond",
                'GROUND' => "Plattegronden",
                'MISSIONS' => "Missies",
                'POSSESSION' => "Bezitting",
                'POSSESSIONS' => "Bezittingen",
                'DONATION_SHOP' => "Donatieshop",
                'COMMUNICATION' => "Communicatie",
                'SOCCER_BETTING' => "Voetbal Wedden",
                'DOBBLING' => "Dobbelen",
                'FIFTY_GAMES' => "50/50 Spelen",
                'SLOT_MACHINE' => "Fruitmachine",
                'LOTTERY' => "Loterij",
                'PROFILE' => "Profiel",
                'FAMILY' => "Familie",
                'STATE' => "Staat",
                'CITY' => "Stad",
                'PROFESSION' => "Beroep",
                'CASH' => "Contant",
                'HEALTH' => "Leven",
                'CRIMES' => "Misdaden",
                'STEAL_VEHICLES' => "Voertuigen Stelen",
                'DRUGS_LIQUIDS' => "Drugs en Drank",
                'SMUGGLING' => "Smokkelen",
                'PROPERTIES' => "Eigendommen",
                'LIST' => "Lijst",
                'NAME' => "Naam",
                'PAGE' => "Pagina",
                'RAID' => "Overval",
                'MERCENARIES' => "Huurlingen",
                'HISTORY' => "Geschiedenis",
                'MANAGEMENT' => "Beheer",
                'INVITATIONS' => "Uitnodigingen",
                'CREATE_FAMILY' => "Familie Aanmaken",
                'SHARE_MAFIASOURCE' => "".$route->settings['gamename']." Delen",
                'MEMBERS' => "Leden",
                'TOPLIST' => "Toplijst", // Use in & out-game
                'LOGOUT' => "Uitloggen",
                'MESSAGE' => "Bericht",
                'MESSAGES' => "Berichten",
                'CLICK_MISSION' => "Klikmissie",
                'TRAVEL' => "Reizen",
                'FRIENDS_BLOCK' => "Vrienden / blokkeren",
                'FRIENDS' => "Vrienden",
                'NOTIFICATIONS' => "Notificaties",
                'NOW' => "Nu",
                'END_COPY' => "Alle rechten voorbehouden.",
                'SETTINGS' => "Instellingen",
                'BOXES' => "Boxen",
                'COMMIT_CRIME' => "Misdaad Plegen",
                'WEAPON' => "Wapen",
                'PIMP_WHORES' => "Hoeren Pimpen",
                'BOMBARDEMENT' => "Aanslag Plegen",
                'CRIME' => "Misdaad",
                'LATEST' => "Laatste",
                'USERNAME' => "Gebruikersnaam",
                'USER' => "Gebruiker",
                'WRONG_CAPTCHA' => "Je hebt de verkeerde code ingevoerd!", // Use in & out-game
                'INVALID_ACTION' => "Je hebt een ongeldige actie geselecteerd!",
                'CANNOT_COMMIT_ACTION_SELF' => "Je kan deze actie niet uitvoeren op jezelf.",
                'INVALID_SECURITY_TOKEN' => "Ongeldige beveiligings sleutel, vernieuw de pagina (F5) en probeer a.u.b. opnieuw.", // Use in & out-game
                'WAITING_TIME_NOT_PASSED' => "De wachttijd is nog niet verstreken!",
                'NOT_ENOUGH_MONEY_BANK' => "Je hebt niet genoeg geld op je bank staan!",
                'NOT_ENOUGH_MONEY_CASH' => "Je hebt niet genoeg geld contant staan!",
                'PLAYER_DOESNT_EXIST' => "Deze speler bestaat niet!",
                'BETWEEN_100_AND_999M' => "Gelieve een bedrag te kiezen tussen $100 en $999,999,999.",
                'BETWEEN_1_AND_999M' => "Gelieve een bedrag te kiezen tussen $1 en $999,999,999.",
                'BETWEEN_0_AND_999M' => "Gelieve een bedrag te kiezen tussen $0 en $999,999,999.",
                'MESSAGE_UNDER_75_CHARS' => "Gelieve een bericht te typen met minder dan 75 karaters.",
                'PLACE_MESSAGE' => "Plaats Bericht",
                'TYPE_A_MESSAGE' => "Typ een bericht",
                'POST_SUCCESS' => "Je bericht werd toegevoegd.",
                'MONEY' => "Geld",
                'BULLETS' => "Kogels",
                'HELPSYSTEM_FOR' => "Helpsysteem voor",
                'NO_CONTENT_YET' => "Hiervoor is nog geen content beschikbaar, probeer a.u.b. later opnieuw.",
                'WRITE_PLAYERNAME' => "Typ een spelersnaam",
                'SENDER' => "Verzender",
                'RECEIVER' => "Ontvanger",
                'CLOSE' => "Sluiten",
                'SAVE' => "Opslaan",
                'NONE' => "Geen", // Use in & out-game
                'ROUND' => "Ronde",
                'ACTION' => "Actie",
                'PICTURE' => "Afbeelding",
                'TRANSFER' => "Overdragen",
                'EDIT' => "Aanpassen",
                'SEND' => "Verzenden",
                'SENT' => "Verzonden",
                'REPORT' => "Meld",
                'DELETE' => "Verwijder",
                'DELETED' => "Verwijderd",
                'CANCEL' => "Annuleren",
                'VEHICLE' => "Voertuig",
                'AIRPLANE' => "Vliegtuig",
                'WITH' => "Met",
                'TO' => "Naar",
                'THIS' => "Dit",
                'DEZETHIS' => "Deze", // NL-EN fix
                'THESE' => "Deze",
                'OWNER' => "Eigenaar",
                'HAS_NO_OWNER_YET' => "Heeft nog geen eigenaar!",
                'HAVE_NO_OWNER_YET' => "Hebben nog geen eigenaar!",
                'IS_THE_OWNER_OF' => "Is de eigenaar van",
                'BUY' => "Kopen",
                'BUY_' => "Koop",
                'SELL' => "Verkopen",
                'FOR' => "Voor",
                'FROM' => "Van",
                'TOTAL' => "Totaal",
                'TOTALE' => "Totale",
                'NUMBER' => "Aantal",
                'DATE' => "Datum",
                'BACK' => "Terug",
                'TRAVELING' => "Je bent momenteel aan het reizen, wacht a.u.b. nog <span id='cdTravelTime'>{sec}</span> seconden.",
                'CANT_DO_THAT_IN_PRISON' => "Je kan dit niet doen zolang je in de gevangenis zit.",
                'CANT_DO_THAT_TRAVELLING' => "Je kan dit niet doen zolang je aan het reizen bent.",
                'MAKE_A_CHOICE' => "Maak een keuze",
                'THE_UNITED_STATES' => "De Verenigde Staten",
                'WHORES' => "Hoeren",
                'LUCKY_BOXES' => "Lucky Boxen",
                'RANK_POINTS' => "Rankpunten",
                'AVERAGE' => "Gemiddelde",
                'DONATE' => "Doneren",
                'AMOUNT' => "Bedrag",
                'AMNT' => "Aantal", // Amount fix dutch
                'DONATIONS' => "Donaties",
                'LAST' => "Laatste",
                'DONATION' => "Donatie",
                'SINCE' => "Sinds",
                'BEGINNING' => "Begin",
                'LOGS' => "Logboeken",
                'ACCEPT' => "Accepteren",
                'ACCEPTED' => "Geaccepteerd",
                'DENY' => "Weigeren",
                'DENIED' => "Geweigerd",
                'PENDING' => "In afwachting",
                'STAKE' => "Inzet",
                'PLAY' => "Spelen",
                'FOUND_CREDITS' => "Je hebt {credits} credits gevonden met {action}!",
                'WAITING_TIMES' => "Wachttijden",
            );
        }
        return $langs;
    }
    
    public function loginLangs()
    {
        $langs = array(
            'WRONG_USERNAME_OR_PASS' => "Je hebt een ongeldige gebruikersnaam of wachtwoord ingevoerd!",
            'LOGIN_FAILED_WARNING' => "Je hebt {attempts} inlogpogingen over!",
            'TEMPORARILY_IP_BANNED' => "Je hebt geen login pogingen meer over dit kan tot 72 uur aanhouden.",
            'PRE_TITLE_TXT' => "Inloggen op",
            'FORGOT_PASSWORD' => "Gebruikersnaam / wachtwoord vergeten?"
        );
        return $langs;
    }
    
    public function screenshotsLangs()
    {
        $langs = array(
            'MOOD_IMAGES' => "Enkele sfeerbeelden",
        );
        return $langs;
    }
    
    public function registerLangs()
    {
        global $route;
        $langs = array(
            'SELECT_TAG_CHOOSE' => "Maak een keuze..",
            'CARJACKER' => "Autodief - Steel makkelijker auto's",
            'PRISON_BREAKER' => "Uitbreker - Breek makkelijker spelers uit de gevangenis",
            'THIEF' => "Dief - Pleeg makkelijker misdaden",
            'PIMP' => "Pimper - Je pimpt tot 15% meer hoeren",
            'BANKER' => "Bankier - Krijg 3% rente op je bank i.p.v. 1%",
            'SMUGGLER' => "Smokkelaar - Je wordt minder snel betrapt door de douane",
            'EMAIL' => "Email adres",
            'PROFESSION' => "Beroep",
            'REGISTER_BTN_PAGE' => "Registreren!",
            'PRE_TITLE_TXT' => "Registreren op",
            'ENCRYPTED' => "Versleuteld opgeslagen",
            'REFRESH' => "Vernieuwen",
            'EMAIL_INFO' => "Vul een geldig e-mailadres in! Nodig voor onder andere wachtwoord vergeten.",
            'USERNAME_INFO' => "Alleen letters, getallen of een streepje(-), begin met minimaal 1 letter. 3-15 tekens.",
            'INVALID_USERNAME' => "Je hebt een ongeldige gebrukersnaam ingevoerd. Alleen letters, getallen of een streepje(-), begin met minimaal 1 letter. 3-15 tekens.",
            'INVALID_EMAIL' => "Je hebt een ongeldig email adres ingegeven!",
            'INVALID_PASS' => "Je wachtwoord moet minimaal 6 tekens lang zijn!",
            'PASSES_DONT_MATCH' => "De opgegeven wachtwoorden zijn ongelijk!",
            'INVALID_PROFESSION' => "Ongeldig beroep geselecteerd!",
            'USERNAME_TAKEN' => "Deze gebruikersnaam is al bezet!",
            'EMAIL_TAKEN' => "Dit e-mail adres is in gebruik!",
            'ALREADY_REGISTERED' => "Er is al een account geregistreerd vanaf dit netwerk!",
            'REGISTERED_SUCCESSFUL' => "Welkom crimineel! Je hebt succesvol je account geregistreerd op ".$route->settings['gamename']."!",
            'TERMS_CONDITIONS_INFO' => "Met uw gebruik van onze website wordt automatisch verwacht dat je akkoord gaat met onze <a href='".$route->getRouteByRouteName('terms-and-conditions')."'><strong>algemene voorwaarden</strong></a>.",
        );
        return $langs;
    }
    
    public function recoverPasswordLangs()
    {
        $registerLangs = $this->registerLangs();
        global $route;
        $langs = array(
            'RECOVER_PASSWORD_FOR' => "Wachtwoord herstellen voor",
            'RECOVER_PASSWORD_INFO' => "Indien u uw gebruikersnaam vergeten bent kan je m.b.v. je emailadres deze optie benutten om uw gebruikersnaam te achterhalen. Herstelsleutels zijn 2 uren geldig.",
            'RECOVER_PASSWORD' => "Herstel wachtwoord",
            'OR' => "OF",
            'INVALID_USERNAME' => $registerLangs['INVALID_USERNAME'],
            'INVALID_EMAIL' => $registerLangs['INVALID_EMAIL'],
            'RECOVER_PASSWORD_EMAIL_MESSAGE' => "Beste {username}<br /><br />We hebben een aanvraag ontvangen om je verloren wachtwoord te herstellen, als jij dit niet was dan kan je deze email negeren.<br /><br />Om een nieuw wachtwoord in te stellen moet je op volgende URL drukken of deze kopie&euml;ren naar je adres balk: <a href='".PROTOCOL.strtolower($route->settings['domain'])."/recover-password/key/{key}'>".PROTOCOL.strtolower($route->settings['domain'])."/recover-password/key/{key}</a><br />Daarna volg je de instructies op het scherm.<br /><br />",
            'RECOVER_PASSWORD_EMAIL_MESSAGE_PRIVATEID' => "Een PrivateID is aanwezig op jouw account. Als je uw PrivateID wil deactiveren, dan moet je op volgende URL drukken of deze kopie&euml;ren naar je adres balk: <a href='".PROTOCOL.strtolower($route->settings['domain'])."/recover-password/disable-privateid/{key}'>".PROTOCOL.strtolower($route->settings['domain'])."/recover-password/disable-privateid/{key}</a><br /><br />",
            'RECOVER_PASSWORD_EMAIL_FOOTER' => "Indien je op een niet gevonden pagina beland dan zijn bovenstaande link(s) verlopen. Je kan een nieuwe aanvraag doen op <a href='".PROTOCOL.strtolower($route->settings['domain'])."'>".PROTOCOL.strtolower($route->settings['domain'])."/recover-password</a><br /><br /><br />Met vriendelijke groeten<br />".ucfirst($route->settings['domainBase']),
            'RECOVER_PASSWORD_EMAIL_SUBJECT' => "Wachtwoord herstellen op ".$route->settings['gamename'],
            'RECOVER_PASSWORD_REQUEST_SUCCESS' => "We hebben een email verzonden met verdere instructies om je verloren wachtwoord te herstellen.<br />Pas op! De link die we je verstuurden zal vervallen binnen 2 uren vanaf nu.",
            'NEW' => "Nieuw",
            'UPDATE_PASSWORD' => "Wachtwoord aanpassen",
            'RECOVER_PASSWORD_SUCCESS' => "Je hebt je wachtwoord succesvol aangepast.",
            'REFRESH' => $registerLangs['REFRESH'],
            'DEACTIVATE_PRIVATEID_SUCCESS' => $this->settingsLangs()['DEACTIVATE_PRIVATEID_SUCCESS']
        );
        return $langs;
    }
    
    public function changeEmailLangs()
    {
        global $route;
        $langs = array(
            'CHANGE_EMAIL_INFO' => "<strong>Opgepast!</strong> Je staat op het punt uw email adres aan te passen voor ".$route->settings['gamename'].", controlleer onderstaande gegevens goed.",
            'CHANGE_EMAIL_ON' => "Email aanpassen op",
            'NEW_EMAIL' => "Nieuw email adres",
            'CHANGE_EMAIL' => "Email adres aanpassen",
            'CHANGE_EMAIL_SUCCESS' => "Je hebt je email adres succesvol aangepast!"
        );
        return $langs;
    }
    
    public function notFoundLangs()
    {
        $langs = array(
            'PAGE_DOESNT_EXIST' => "Het spijt ons want... De pagina die je bezocht bestaat niet. (meer)",
            'GO_BACK' => "Ga terug"
        );
        return $langs;
    }
    
    public function newsLangs()
    {
        $langs = array(
            'POSTED_ON' => "Gepost op",
            'NEWS_NEWS' => "Nieuws",
            'NEWS_UPDATES' => "Updates"
        );
        return $langs;
    }
    
    public function statusLangs()
    {
        global $route;
        $donationShopLangs = $this->donationShopLangs();
        $langs = array(
            'CLICK_FOR_REFERRAL_INFO' => "Klik <strong>hier</strong> om de referral uitleg te zien!",
            'REFERRAL_INFO' => "Hier zie je een overzicht van je account. De referral link is belangrijk. Door vrienden of familieleden zich te laten aanmelden op ".$route->settings['gamename']." via jouw link krijgt ".$route->settings['gamename']." meer spelers en kan jij geld verdienen. Je krijgt $1,000,000 als iemand zich via jouw link aanmeldt. <s>Je krijgt ook $1,000,000 als een referral van jou 100 credits koopt. Je krijgt $500,000 als een referral van jouw referral 100 credits koopt. Je krijgt ten slotte $250,000 als een referral van de referral van je referral 100 credits koopt. (Zie voorbeeld).<br /><br />Speler A haalt een referral genaamd speler B en krijgt eenmalig $1,000,000.<br />Speler B koopt 100 credits. Speler A krijgt $1,000,000.<br /><br />Speler B haalt drie referrals: spelers C, D en E.<br />Speler C koopt 100 credits. Speler D koopt 500 credits en speler E koopt 200 credits.<br />Speler B ontvangt nu $8,000,000 (er wordt immers 8 keer 100 credits gekocht).<br />Speler A ontvangt nu $4,000,000 (omdat hij speler B als referral heeft).<br /><br />Spelers C, D en E halen bij elkaar 8 referrals. Deze 8 referrals kopen bij elkaar 1,700 credits.<br />Spelers C, D en E krijgt dan totaal $17,000,000.<br />Speler B krijgt nu $8,500,000 en speler A krijgt $4,250,000.<br /><br />Als je dus veel referrals hebt kan je veel geld verdienen. Dit wordt nog eens veel en veel meer als ook jouw referrals weer referrals hebben, en de referrals daarvan!</s>",
            'STATUSBARS' => "Statusbalken",
            'SCORE_HOUR' => "Score punten per uur",
            'PROTECTION' => "Bescherming",
            'EXPERIENCE' => "Ervaring",
            'WINDOW' => "Raam",
            'WARNS' => "Waarschuwingen",
            'STREET' => "Straat",
            'PROTECTED' => "Je bent beschermd tegen aanvallen tot",
            'TAKE_AWAY_PROTECTION' => "Klik <strong>hier</strong> om je bescherming weg te halen.",
            'LAST_CHANCE' => "Laatste kans",
            'EQUIPMENT' => "Uitrusting",
            'RESIDENCE' => "Woning",
            'COPY' => "Kopiëren",
            'COPIED' => "Gekopieerd",
            'HALVING_TIMES' => $donationShopLangs['HALVING_TIMES'],
            'BRIBING_BORDER_PATROL' => $donationShopLangs['BRIBING_BORDER_PATROL']
        );
        return $langs;
    }
    
    public function bankLangs()
    {
        global $route;
        $langs = array(
            'SWISS' => "Zwitserse",
            'FINANCIAL' => "Financieel",
            'BANK_LOGS' => "Logboeken",
            'BANK_TRANSFER' => "Geld overzetten",
            'SUMMARY' => "Overzicht",
            'WHORES_HOUR' => "Hoeren gemiddeld per uur",
            'POSSESSIONS_TOTAL' => "Bezittingen (totaal)",
            'GROUND_HOUR' => "Plattegronden (per uur)",
            'RECEIVED' => "Ontvangen",
            'CANNOT_SEND_MONEY_SELF' => "Je kan geen geld doneren aan jezelf.",
            'DONATE_MONEY_TO_USER' => "Je hebt succesvol $&#8203;{amount} gedoneerd aan {username} min {transactionPercent}% transactiekosten!",
            'INVALID_ACTION' => "Ongeldige transactie methode geselecteerd!",
            'TRANSFER_MONEY_SUCCESS' => "Je hebt $&#8203;{amount} naar je {action} overgezet!",
            'NOT_ENOUGH_MONEY_SWISS' => "Je hebt niet genoeg geld op je zwitserse bank!",
            'SWISS_BANK_FULL' => "Je kunt niet zoveel geld storen op je zwitserse bank!",
            'WITHDRAW_MONEY_FROM_BANK' => "Geld van de bank halen",
            'STORE_MONEY_IN_BANK' => "Geld op de bank storten",
            'SWISS_BANK_INFO' => "Geld op je zwitserse bank blijft beschikbaar na een mogelijke dood.",
            'SWISS_TRANSACTION_INFO' => "De bank van ".$route->settings['gamename']." neemt 5% transactiekosten om geld naar de zwitserse bank te storten!",
            'SPACE_VAULT' => "Inhoud kluis",
            'SPACE_LEFT' => "Ruimte over",
            'WHORES_STREET' => "Hoeren op straat",
            'WHORES_WINDOW' => "Hoeren achter het raam",
            'NIGHTCLUB' => "Nachtclub",
            'DAILY' => "Dagelijkse",
            'INTEREST' => "Rente",
            'NO_DONATIONS_TO_VIEW' => "Geen donaties om weer te geven.",
        );
        return $langs;
    }
    
    public function prisonLangs()
    {
        $langs = array(
            'NO_PRISONERS' => "Er zijn op dit moment geen gevangenen.",
            'TIME_LEFT' => "Tijd over",
            'BUY_OUT' => "Uitkopen",
            'BREAK_OUT' => "Uitbreken",
            'USER_NOT_IN_PRISON' => "Deze gebruiker zit niet (meer) in de gevangenis.",
            'CANNOT_BREAK_SELF_OUT' => "Je kan jezelf niet uitbreken!",
            'NOT_ENOUGH_CASH_TO_BUY_OUT' => "Je hebt niet genoeg geld contant om de waarborg te betalen.",
            'USER_BOUGHT_OUT_PRISON' => "Je hebt <strong>{playerName}</strong> uit de gevangenis gekocht!",
            'USER_BREAK_OUT_OF_PRISON' => "Je hebt {playerName} uit de gevangenis gebroken, je hebt ook enkele rankpunten verdient!",
            'USER_BREAK_OUT_OF_PRISON_FAIL' => "Je poging om {playerName} uit de gevangenis te breken is mislukt, je bent opgepakt voor 3 minuten en de tijd van je compaan werd verlengd met 2 minuten.",
            'NO_BREAK_USER_JAILED' => "Je kan niemand uit de gevangenis breken wanneer je zelf in de gevangenis zit.",
            'IN_PRISON' => "Je zit momenteel in de gevangenis het is onmogelijk om te"
        );
        return $langs;
    }
    
    public function honorPointsLangs()
    {
        $langs = array(
            'EXCHANGE' => "Wisselen",
            'EXCHANGE_SINGLE' => "Wissel",
            'SEND_HONOR_POINTS_INFO' => "Eerpunten verzenden naar andere spelers zal je eigen ranking in de toplijst lichtjes verminderen, tenzij je kan terug verdienen wat je verzonden hebt in een korte tijd.",
            'YOU_HAVE_X_HONOR_POINTS' => "Je hebt <strong><i id='userHonorPoints'>{honorPoints}</i> <span id='userHonorPointsChange'></span></strong> eerpunten in je bezit.",
            'BEWARE_EXCHANGE' => "Opgelet! Je zult x aantal eerpunten (in bezit) inruilen voor de geselecteerde beloning na een druk op de knop rechts.",
            'NO_EXISTING_REWARD' => "Je hebt een ongeldige beloning geselecteerd.",
            'NOT_ENOUGH_HONOR_POINTS' => "Je hebt niet zoveel eerpunten.",
            'CANNOT_SEND_HP_TO_SELF' => "Je kan geen eerpunten verzenden naar jezelf..",
            'EXCHANGE_HONOR_POINTS_SUCCESS' => "Je hebt {exchangeAmount} eerpunten ingewisseld voor {exchangedValue} {exchangedWhat}.",
            'SEND_HONOR_POINTS_SUCCESS' => "Je hebt {amount} eerpunten verzonden naar {username}!",
            'LATEST_10_OBTAINED_HP' => "Laatste 10 ontvangen eerpunten",
            'LATEST_10_LOST_HP' => "Laatste 10 verzonden eerpunten",
            'NO_HP_LOGS_TO_VIEW' => "Voorlopig geen logs om weer te geven.", // FIXEN! TODO NO_LOGS_TO_VIEW baselang multiple pages
            'HONOR_POINTS_INFO' => "Niemand kan je eer raken, al je punten blijven behouden na je dood."
        );
        return $langs;
    }
    
    public function travelLangs()
    {
        global $route;
        $langs = array(
            'TRAIN' => "Trein",
            'AIRPLANE_INFO' => "Reizen met het vliegtuig gaat het snelst maar is niet altijd veilig tijdens het smokkelen.",
            'TRAIN_INFO' => "Reizen met de trein gaat relatief snel en veilig.",
            'BUS_INFO' => "Reizen met de bus gaat traag maar is wel vrij veilig tijdens het smokkelen.",
            'VEHICLE_INFO' => "Reis met een voertuig, de veiligste maar traagste manier om te smokkelen en je betaald enkel je benzine.",
            'BORDER_PATROL_INFO' => "In de <a href='".$route->getRouteByRouteName('donation-shop')."'><strong>Donatieshop</strong></a> kan je de douane omkopen.",
            'BOOK_TICKET' => "Ticket kopen",
            'COSTS' => "Kosten",
            'ROUTE_NOT_POSSIBLE' => "Route <strong>niet mogelijk</strong> door reis optie, overweeg het vliegtuig.",
            'TRAVEL_VEHICLE_NO_SPACE_GARAGE' => "Je hebt geen plaats meer vrij in je garage in de staat waar je wilt naar reizen.",
            'TRAVEL_VEHICLE_NO_GARAGE' => "Je hebt geen garage in de staat waar je wilt naar reizen.",
            'TRAVEL_VEHICLE_NO_VEHICLE' => "Geen voertuig geselecteerd om mee te reizen!",
            'INVALID_DESTINATION' => "Ongeldige bestemming geselecteerd!",
            'CANNOT_TRAVEL_WHEN_IN_CRIME' => "Je kan niet reizen naar een andere staat terwijl je in een georganiseerde familie misdaad zit.",
            'CANNOT_TRAVEL_WHEN_IN_RAID' => "Je kan niet reizen naar een andere staat terwijl je in een georganiseerde familie overval zit.",
            'CAUGHT_BY_BORDER_PATROL' => "Je bent betrapt tijdens het smokkelen van goederen, je werd voor opgepakt voor 2 minuten en al je smokkelwaar werd in beslag genomen!",
            'TRAVEL_TO_SUCCESS' => "Je bent op reis gegaan naar {state} voor $&#8203;{price}, je zal je bestemming bereiken in ongeveer {sec} seconden."
        );
        return $langs;
    }
    
    public function marketLangs()
    {
        $langs = array(
            'ANONYMOUS' => "Anoniem",
            'PRICE' => "Prijs",
            'PLACE_OR_REQUEST_X_ON_MARKET' => "Plaats of vraag {typeName} aan op de markt",
            'NO_ITEMS_IN_MARKET' => "Er zijn momenteel geen {typeName} in de aanbieding.",
            'NO_REQUESTS_IN_MARKET' => "Er zijn momenteel geen aanvragen naar {typeName}.",
            'ITEMS_SALE_INFO' => "Bovenstaande {typeName} worden verkocht door de eigenaar.",
            'ITEMS_REQUEST_INFO' => "Bovenstaande {typeName} worden gekocht door de gebruiker.",
            'OFFER' => "Bod",
            'WRONG_MARKET_TYPE_SELECTED' => "Je hebt een ongeldige categorie opgegeven!",
            'AMOUNT_RANGE_BETWEEN_25_10K' => "Gelieve een aantal tussen 25 en 10,000 in te voeren.",
            'PRICE_RANGE_BETWEEN_250K_9.999B' => "Voer een prijs in tussen $250,000 en $9,999,999,999.",
            'NOT_ENOUGH_AMOUNT_FOR_SALE' => "Je hebt niet zoveel {typeName} om te verkopen!",
            'MARKET_ITEM_ADD_SUCCESS' => "Je hebt {typeName} op de markt geplaatst. Je wordt zo dadelijk omgeleid.",
            'MARKET_ITEM_REQUEST_SUCCESS' => "Je hebt een aanvraag naar {typeName} gedaan op de markt. Je wordt zo dadelijk omgeleid.",
            'MARKET_ITEM_DOESNT_EXIST' => "Je hebt een ongeldig item geselecteerd.",
            'CANT_BUY_SELL_OWN_MARKET_ITEM' => "Je kan geen actie ondernemen op je eigen markt item.",
            'BOUGHT_MARKET_ITEM_SUCCESS' => "Je hebt {amount} {typeName} <strong>gekocht</strong> voor $&#8203;{price}.",
            'ACCEPT_MARKET_ITEM_SUCCESS' => "Je hebt {amount} {typeName} <strong>verkocht</strong> voor $&#8203;{price}.",
            'MARKET_DEATH_INFO' => "Enkel te koop geplaatste credits en/of eerpunten blijven op de markt staan na je dood.",
            'SUPPLY_AND_DEMAND_INFO' => "Wat op de markt word geplaatst kan niet worden weggehaald zonder dood te gaan."
        );
        return $langs;
    }
    
    public function stockExchangeLangs()
    {
        global $route;
        $langs = array(
            'EXCHANGE' => "Beurs",
            'BUSINESS' => "Bedrijf",
            'DIFFERENCE' => "Verschil",
            'DIFF' => "Versch",
            'HIGH' => "Hoog",
            'LOW' => "Laag",
            'PRICE' => $this->marketLangs()['PRICE'],
            'EACH' => "Per",
            'CURRENT' => "Huidige",
            'CLOSING' => "Slot",
            'HIGHEST_DAY' => "Hoogste dag",
            'LOWEST_DAY' => "Laagste dag",
            'DAY' => "Dag",
            'PURCHASE' => "Aankoop",
            'PROFIT' => "Winst",
            'EXCHANGE_DORMANT' => "De beurs staat momenteel stil tot 06:00.",
            'DONT_OWN_STOCKS' => "Je hebt momenteel geen stocks in je portfolio.",
            'BUSINESS_STOCKS_INFO' => "Je hebt <strong><span id='stockAmount'>{stocks}</span><span id='stockAmountChange'></span></strong> stocks in je bezit van {business}.",
            'BUSINESS_DONATOR_INFO' => "Je kan 1,000,000 stocks in totaal bezitten. Word <a href=".$route->getRouteByRouteName('donation-shop')."><strong>Donateur</strong></a> om je limiet te verhogen.",
            'BUSINESS_VIP_INFO' => "Je kan 2,500,000 stocks in totaal bezitten. Word <a href=".$route->getRouteByRouteName('donation-shop')."><strong>VIP</strong></a> om je limiet te verhogen.",
            'AVERAGE_DAY_PRICE_15_DAYS' => "Gemiddelde dag prijs van de laatste 15 dagen",
            'NOT_DEVISABLE_BY_100' => "Zorg dat je aantal stocks deelbaar is door 100.",
            'CANNOT_BUY_OVER_LIMIT' => "Je kan niet zoveel stocks bezitten.",
            'DONT_OWN_THAT_MANY' => "Je hebt niet zoveel stocks van dit bedrijf in je bezit.",
            'AMOUNT_RANGE_BETWEEN_100_AND_5M' => "Gelieve een aantal tussen 100 en 5,000,000 in te voeren.",
            'BOUGHT_STOCKS_SUCCESS' => "Je hebt {stocks} stocks van {business} aangekocht voor $&#8203;{price}.",
            'SOLD_STOCKS_SUCCESS' => "{stocks} stocks van {business} werden verkocht voor $&#8203;{price}.",
        );
        return $langs;
    }
    
    public function equipmentStoresLangs()
    {
        $langs = array(
            'WEAPONS' => ucfirst($this->langMap['WEAPON']."s"),
            'PROTECTION' => $this->statusLangs()['PROTECTION'],
            'AIRPLANES' => ucfirst($this->langMap['AIRPLANE']."en"),
            'PRICE' => $this->marketLangs()['PRICE'],
            'AVERAGE_WEAPON_EXP_TRAIN' => "Gemiddelde wapen- ervaring en -training",
            'IN_POSSESSION' => $this->smugglingLangs()['IN_POSSESSION'],
            'NOT_ENOUGH_WEAPON_EXP_TRAIN' => "Niet genoeg wapen- evraring en/of training",
            'DAMAGE' => "Schade",
            'DAMAGE_PER_HIT' => "Schade per geraakt shot",
            'BOMBING_POWER' => "Bombardeer kracht",
            'EQUIP' => "Uitrusten",
            'EQUIPPED' => "Uitgerust",
            'EQUIPMENT_DOESNT_EXIST' => "Deze uitrusting bestaat niet.",
            'ALREADY_OWN_EQUIPMENT' => "Je bezit reeds deze uitrusting.",
            'BOUGHT_EQUIPMENT_SUCCESS' => "Je hebt een {name} gekocht voor $&#8203;{price} en dit meteen uitgerust!",
            'DONT_OWN_EQUIPMENT' => "Je bezit deze uitrusting niet.",
            'SOLD_EQUIPMENT_SUCCESS' => "Je hebt een {name} verkocht voor $&#8203;{price}!",
            'EQUIP_EQUIPMENT_SUCCESS' => "Je hebt een {name} uitgerust.",
        );
        return $langs;
    }
    
    public function estateAgencyLangs()
    {
        $esLangs = $this->equipmentStoresLangs();
        $langs = array(
            'PRICE' => $this->marketLangs()['PRICE'],
            'IN_POSSESSION' => $this->smugglingLangs()['IN_POSSESSION'],
            'EQUIP' => $esLangs['EQUIP'],
            'EQUIPPED' => $esLangs['EQUIPPED'],
            'DEFENCE' => "Verdediging",
            'RESIDENCE_DOESNT_EXIST' => "Deze woning bestaat niet.",
            'ALREADY_OWN_RESIDENCE' => "Je bezit reeds deze woning.",
            'BOUGHT_RESIDENCE_SUCCESS' => "Je hebt een {name} gekocht voor $&#8203;{price} en deze meteen uitgerust!",
            'DONT_OWN_RESIDENCE' => "Je bezit deze woning niet.",
            'SOLD_RESIDENCE_SUCCESS' => "Je hebt jouw {name} verkocht voor $&#8203;{price}!",
            'EQUIP_RESIDENCE_SUCCESS' => "Je hebt jouw {name} als huidige woning uitgerust.",
        );
        return $langs;
    }
    
    public function bulletFactoriesLangs()
    {
        $langs = array(
            'BULLET_FACTORY' => "Kogelfabriek",
            'THIS_BF_IS_CURRENTLY' => "Deze kogelfabriek is op het moment aan het",
            'PRODUCING' => "Produceren",
            'DORMANT' => "Stilstaan",
            'BULLETS_FOR_SALE' => "Kogels te koop",
            'PRICE_EACH_BULLET' => "Prijs per kogel",
            'ATM_NO_MORE_BULLETS_FOR_SALE_IN_THIS' => "Momenteel zijn er geen kogels meer te koop in deze",
            'PRODUCTION' => "Productie",
            'PRICE' => $this->marketLangs()['PRICE'],
            'BETWEEN_1_AND_9M_BULLETS' => "Je moet tussen de 1 en de 9,999,999 kogels kopen!",
            'NOT_THAT_MANY_BULLETS_IN_FACTORY' => "Deze kogelfabriek heeft niet meer zoveel kogels over.",
            'BOUGHT_BULLETS_SUCCESS' => "Je hebt {bullets} kogels gekocht en in totaal $&#8203;{price} betaald."
        );
        return $langs;
    }
    
    public function hitlistLangs()
    {
        $langs = array(
            'ANONYMOUS' => $this->marketLangs()['ANONYMOUS'],
            'PRIZE' => $this->luckyboxLangs()['PRIZE'],
            'COSTS_ANONYMOUS' => "Kost 30% extra",
            'REASON' => "Reden",
            'ORDER' => "Bestelling plaatsen",
            'ORDERER' => "Opdrachtgever",
            'BUY_OUT' => $this->prisonLangs()['BUY_OUT'],
            'NO_ORDERS' => "Er zijn momenteel geen openstaande opdrachten op de aanslagenlijst.",
            'PRIZE_ATLEAST_10K' => "De beloning moet minstens $10,000 bedragen!",
            'PLAYER_ALREADY_ON_HITLIST' => "Deze speler staat al op de aanslagenlijst!",
            'PLAYER_ALREADY_DEAD' => $this->murderLangs()['PLAYER_ALREADY_DEAD'],
            'CANNOT_ORDER_SELF_HITLIST' => "Je kan jezelf niet op de aanslagenlijst zetten!",
            'ORDER_HITLIST_RECORD_SUCCESS' => "Je hebt {user} op de aanslagenlijst geplaatst voor $&#8203;{price}.",
            'PLAYER_NOT_ON_HITLIST' => "Deze speler staat niet op de aanslagenlijst!",
            'CANNOT_BUY_OUT_SELF_HITLIST' => "Je kan jezelf nier van de aanslagenlijst kopen!",
            'BUY_OUT_HITLIST_RECORD_SUCCESS' => "Je hebt {user} uitgekocht van de aanslagenlijst voor $&#8203;{price}."
        );
        return $langs;
    }
    
    public function murderLangs()
    {
        global $route;
        $langs = array(
            'MERCENARIES' => "Huurlingen",
            'IN_POSSESSION' => $this->smugglingLangs()['IN_POSSESSION'],
            'CURRENT' => "Huidig",
            'CANT_MURDER_WITH_PROTECTION' => "Je kan deze speler niet aanvallen wanneer je bescherming hebt. Ga naar de <a href='".$route->getRouteByRouteName('status')."'><strong>Status</strong></a> pagina om je bescherming weg te halen.",
            'FIRE_1_BULLET_MIN' => "Je moet minimaal 1 kogel afvuren!",
            'DONT_OWN_THAT_MANY_BULLETS' => "Zoveel kogels hebt je niet in je bezit!",
            'INVALID_AMOUNT_OF_BULLETS' => "Je kan maximaal 9,999,999,999 kogels afvuren!",
            'CANNOT_MURDER_WITH_CASH_OR_BANK_IN_MIN' => "Je kan niemand aanvallen als je contant of bank in het min staat!",
            'ALREADY_ATTACKED_PLAYER_LAST_10MIN' => "Je hebt deze speler al aangevallen in de afgelopen 10 minuten!",
            'CANT_MURDER_PLAYER_IN_ALLIANCE_WITH_FAMILY' => "Je kan geen spelers aanvallen van een familie in alliantie!",
            'NOT_IN_SAME_CITY' => "Je bent niet in dezelfde stad als je slachtoffer!",
            'CANT_MURDER_PLAYER_WITH_PROTECTION' => "Je kan deze speler onder bescherming niet aanvallen!",
            'CANT_MURDER_TEAM_MEMBER' => "Je kan dit teamlid niet aanvallen!",
            'CANNOT_MURDER_PLAYER_WITH_CASH_OR_BANK_IN_MIN' => "Je kan niemand aanvallen waarbij contant of bank in het min staat!",
            'CANT_MURDER_PLAYER_INSIDE_FAMILY' => "Je kan dit familielid niet aanvallen!",
            'CANNOT_ATTACK' => "Je kan deze speler niet aanvallen! Kijk bij <a href='".$route->getRouteByRouteName('ranks-score')."'><strong>Ranks & Score</strong></a> om te zien wie je kan aanvallen.",
            'MURDER_PLAYER_SUCCESS_WEAPON_EXP' => "Je hebt <strong>{exp}%</strong> wapen ervaring erbij gekregen.<br /><br /><br />",
            'MURDER_PLAYER_SUCCESS_HEADSHOT' => "<strong>Jij schoot een <font color=red>H</font><font color=black>E</font><font color=red>A</font><font color=black>D</font><font color=red>S</font><font color=black>H</font><font color=red>O</font><font color=black>T</font> op {victim}</strong><br />Deze moord koste je maar 1 kogel en je hebt hierbij <strong>$500,000</strong> ontvangen!<br />",
            'MURDER_PLAYER_ON_HITLIST_SUCCESS' => "{victim} stond ook op de aanslagenlijst. Je hebt daardoor nog eens $&#8203;{prize} gekregen!",
            'TAKE_OVER_POSSESSION_TOOK_OVER' => "<div>✓Je hebt een {possessionName} overgenomen!.</div>",
            'TAKE_OVER_POSSESSION_STATUS_ERROR' => "<div>✘Je hebt geen {possessionName} overgenomen omdat je nog bescherming had, deze is tekoop gezet.</div>",
            'TAKE_OVER_POSSESSION_SELF_ERROR' => "<div>✘Je hebt geen {possessionName} overgenomen omdat je al een {possessionName} bezit, deze is tekoop gezet.</div>",
            'TAKE_OVER_POSSESSION_SELF_COUNTRY_ERROR' => "<div>✘Je hebt geen {possessionName} overgenomen omdat je al een land bezittingen bezit, deze is tekoop gezet.</div>",
            'TAKE_OVER_POSSESSION_FAMILY_ERROR' => "<div>✘Je hebt geen {possessionName} overgenomen omdat je familie het maximum aantal bezit, deze is tekoop gezet.</div>",
            'TAKE_OVER_POSSESSION_FAMILY_COUNTRY_ERROR' => "<div>✘Je hebt geen {possessionName} overgenomen omdat je familie al het maximum aantal land bezittingen bezit, deze is tekoop gezet.</div>",
            'MURDER_SUCCESS_DIED_VICTIM_SURVIVED' => "Jij schoot op <strong>{victim}</strong>, hij <strong>overleefde</strong> jouw schoten.<br />Jij ging wel dood door zijn laatste schot!<br><strong>{victim}</strong> heeft ook nog eens <strong>$&#8203;{stolenMoney}</strong> gestolen!",
            'MURDER_SUCCESS_BOTH_DIED' => "Jij schoot op <strong>{victim}</strong>, hij ging <strong>dood</strong> door jouw schoten.<br />Jij ging ook <strong>dood</strong> door zijn laatste schot!",
            'MURDER_SUCCESS_KILLED_VICTIM' => "Jij schoot op <strong>{victim}</strong>, hij ging <strong>dood</strong> door jouw schoten.<br />Je hebt ook nog <strong>$&#8203;{stolenMoney}</strong> gestolen!",
            'MURDER_SUCCESS_BOTH_SURVIVED_VICTIM_STOLE' => "Jij schoot op <strong>{victim}</strong>, hij <strong>overleefde</strong> jouw schoten.<br />Hij heeft ook nog <strong>$&#8203;{stolenMoney}</strong> gestolen!",
            'MURDER_SUCCESS_BOTH_SURVIVED_ATTACKER_STOLE' => "Jij schoot op <strong>{victim}</strong>, hij <strong>overleefde</strong> jouw schoten.<br />Je hebt ook nog <strong>$&#8203;{stolenMoney}</strong> gestolen!",
            'EXPERIENCE' => $this->statusLangs()['EXPERIENCE'],
            'ALREADY_100_WEAPON_TRAINING' => "Je hebt reeds 100% wapen training, verwerf ervaring door spelers aan te vallen.",
            'TRAIN_WEAPON_TRAINING_SUCCESS' => "Je hebt je wapen skills getraind en {percent}% verdient voor jouw training balk.",
            'ALL_YOUR' => "Al je",
            'DOUBLE_OF_ATTACKER' => "Dubbel zoveel als je aanvaller",
            'SAME_AS_ATTACKER' => "Evenveel als je aanvaller",
            'HALF_OF_ATTACKER' => "De helft van je aanvaller",
            'INVALID_BACKFRITE_TYPE' => "Je hebt een ongeldig backfire type opgegeven!",
            'BACKFIRE_PERCENT_BETWEEN_0_AND_100' => "Het backfire percentage moet tussen de 0% en 100% liggen!",
            'BACKFIRE_NUMBER_HIGHER_OR_0' => "Het aantal backfire kogels moet hoger of gelijk aan 0 zijn!",
            'BACKFIRE_SETTINGS_SAME_AS_CURRENT' => "Deze backfire instellingen zijn al van toepassing!",
            'SET_BACKFIRE_SUCCESS' => "Je hebt je backfire aanpassingen opgeslagen.",
            'VICTIM' => "Slachtoffer",
            'TIME_LEFT' => $this->prisonLangs()['TIME_LEFT'],
            'NOW_IN' => "Nu in",
            'LAST_SEEN_IN' => "Laatst gezien in",
            'SEARCHING' => "Bezig met zoeken",
            'NO_DETECTIVES' => "Je hebt momenteel geen detectieves ingehuurd.",
            'HIRE_DETECTIVE' => "Detective inhuren",
            'TIME' => "Tijd",
            'SHADOW' => $this->ranksScoreLangs()['SHADOW'],
            'SHADOW_INFO' => "Blijven volgen na vondst",
            'HOUR' => $this->familyPropertiesLangs()['HOUR'],
            'CANT_HIRE_WITH_PROTECTION' => "Je kan geen detective inhuren wanneer je bescherming hebt. Ga naar de <a href='".$route->getRouteByRouteName('status')."'><strong>Status</strong></a> pagina om je bescherming weg te halen.",
            'INVALID_TIME' => "Je hebt een ongeldige tijd geselecteerd!",
            'ALL_DETECTIVES_IN_USE' => "Al je detectives zijn al in gebruik!",
            'PLAYER_ALREADY_IN_SEARCHLIST' => "Je hebt al een detective die opzoek gaat naar deze speler.",
            'PLAYER_ALREADY_DEAD' => "Deze speler is reeds dood.",
            'CANNOT_ATTACK_CANNOT_HIRE' => "Je kan geen detective inhuren voor deze speler omdat je hem niet kan aanvallen! Kijk bij <a href='".$route->getRouteByRouteName('ranks-score')."'><strong>Ranks & Score</strong></a> om te zien wie je kan aanvallen.",
            'HIRE_DETECTIVE_SUCCESS' => "Je hebt een detective ingehuurd dat opzoek gaat naar {victim}.",
        );
        return $langs;
    }
    
    public function murderLogLangs()
    {
        $langs = array(
            'ATTACK' => "Aanval",
            'ATTACKED_BY' => "Aangevallen door",
            'RESULT' => "Resultaat",
            'YOU' => "Jij",
            'YOUJOU' => "Jou",
            'YOUR' => "Jouw",
            'HE' => "Hij",
            'HIS' => "Zijn",
            'YALL' => "Jullie",
            'SHOT' => "schoot op",
            'SHOTS' => "schoten",
            'BOTH_DIED_BY_THE' => "gingen beide dood door de",
            'SURVIVED_THE' => "overleefde de",
            'DIED_BY' => "ging dood door",
            'NO_ATTACK_LOGS' => "Er zijn nog geen aanval logboeken terug te vinden (op deze pagina).",
            'NO_ATTACKED_LOGS' => "Er zijn nog geen aangevallen door logboeken terug te vinden (op deze pagina).",
            'THEY' => "Zij"
        );
        return $langs;
    }
    
    public function hospitalLangs()
    {
        $langs = array(
            'HEAL' => "Genezen",
            'NO_PLAYER_NEEDS_HEALING' => "Er zijn geen spelers gewond die jij kan genezen!",
            'MEMBER_NOT_WOUNDED' => "Deze speler is niet gewond!",
            'CANNOT_HEAL_DEAD_MEMBER' => "Je kan geen dode spelers genezen.",
            'HEAL_MEMBER_SUCCESS' => "{member} is helemaal genezen! Je hebt $&#8203;{costs} ziekenhuiskosten bestaald.",
        );
        return $langs;
    }
    
    public function redLightDistrictLangs()
    {
        $langs = array(
            'BUY_WINDOWS' => "Ramen Kopen",
            'WINDOWS_AVAILABLE' => "Ramen beschikbaar",
            'BUY_WINDOWS_INFO' => "Hoeren achter een raam verdienen tot gemiddeld $3 per uur meer als hoeren op straat. Voeg straat hoeren toe aan je familie bordeel zodat ze niet verloren gaan na je dood.",
            'USER_WHORES_INFO' => "Je hebt <strong class='gray'><span id='totalWhores'>{totalWhores}</span><span id='totalWhoresChange'></span> hoeren</strong> waarvan <strong class='gray'><span id='whoresStreet'>{streetWhores}</span><span id='whoresStreetChange'></span> op straat</strong> en <strong class='gray'><span id='whoresWindow'>{windowWhores}</span><span id='whoresWindowChange'></span> achter een raam</strong>.",
            'TOTAL_PROFITS_FROM_WHORES' => "Winst door hoeren",
            'TOTAL_PIMPS_COMMITTED' => "Aantal keren gepimpt",
            'TOTAL_WHORES_PIMPED' => "Totaal hoeren gepimpt",
            'PIMP_WHORES_INFO' => "Al je hoeren zullen verdwijnen na je dood. Je zal geld verdienen voor iedere hoer per uur, hoe meer hoeren je hebt gepimpt hoe meer geld je zult ontvangen.",
            'PIMP_WHORES_SELF_SUCCESS' => "Je hebt {amount} hoeren gepimpt, je hebt ze meteen aan het werk gezet.",
            'CANNOT_PIMP_FOR_SELF' => "Je kan niet voor je zelf pimpen via je eigen profiel.",
            'PIMP_WHORES_FOR_OTHER_SUCCESS' => "Je hebt {amount} hoeren gepimpt voor {user}, hij zette ze onmiddelijk aan het werk.",
            'SUMMARY' => "Overzicht",
            'BEHAND_A_WINDOW' => "Achter een raam",
            'PRICE_EACH_WINDOW' => "Prijs per raam",
            'WHORES_AVAILABLE' => "Hoeren beschikbaar",
            'WINDOWS' => "Ramen",
            'NOT_ENOUGH_WINDOWS_LEFT' => "Er zijn niet genoeg ramen over in dit red light district.",
            'NOT_ENOUGH_STREET_WHORES' => "Zoveel hoeren op straat heb je niet.",
            'INVALID_AMOUNT_WINDOWS' => "Je hebt een ongeldig aantal ramen opgegeven.",
            'BUY_WINDOWS_SUCCESS' => "Je hebt {amount} ramen gekocht in dit red light district! Je hoeren werden meteen aan het werk gezet.",
            'TAKE_AWAY' => "Weghalen",
            'INVALID_STATE_SELECTED' => "Je hebt een ongeldige staat geselecteerd om hoeren weg te halen achter ramen.",
            'NOT_THAT_MUCH_WHORES_WINDOW' => "Zoveel hoeren heb je niet achter deze ramen!",
            'TAKE_AWAY_WINDOWS_SUCCESS' => "Je hebt {amount} hoeren achter ramen in {state} weggehaald!",
            'NO_WHORE_BEHIND_WINDOWS' => "Je hebt geen hoeren achter ramen."
        );
        return $langs;
    }
    
    public function gymLangs()
    {
        $langs = array(
            'TRAIN_PUSH_UP' => "25x opdrukken",
            'TRAIN_CYCLE' => "2 km. fietsen",
            'TRAIN_BENCH_PRESS' => "25 kg. bankdrukken",
            'TRAIN_RUN' => "5 km. hardlopen",
            'FAST_ACTION_ON' => "Snelle actie op",
            'PUSH_UPS' => "Opdrukken",
            'CYCLING' => "Fietsen",
            'BENCH_PRESSING' => "Bankdrukken",
            'RUNNING' => "Hardlopen",
            'TRAIN_INFO' => "Train tot 100% om steeds sneller te kunnen sporten.",
            'POWER' => "Kracht",
            'PROFIT_LOSS_RATIO' => "Winst/Verlies ratio",
            'TOTAL_PROFIT' => "Totale winst",
            'SCORE_POINTS_EARNED' => "Scorepunten verdient",
            'COMPETITIONS' => "Wedstrijden",
            'NO_COMPETITIONS_ATM' => "Er zijn geen open wedstrijden op dit moment.",
            'COMPETITION' => "Wedstrijd",
            'ARM_WRESTLING' => "Armdrukken",
            'SPRINT' => "500 m. sprint",
            'TUG_OF_WAR' => "Touwtrekken",
            'TRIATLON' => "2 km. triatlon",
            'WRESTLE' => "Worstelen",
            'CHALLENGE' => "Uitdagen",
            'STAKE_BETWEEN_50_5M' => "Zet tussen de $50 en $5,000,000 in.",
            'GYM_INFO' => "Je sportschool training, kracht en cardio zullen resetten na je dood en zullen ook traag afnemen als je geen training hebt gehad voor over 48 uur wat kan resulteren in een tijdsverlies voor iedere training.",
            'GYM_TRAINING_DOESNT_EXIST' => "Deze training bestaat niet.",
            'GYM_FAST_ACTION_CHANGE_SUCCESS' => "Je hebt je snelle actie ingesteld op {type}.",
            'TRAIN_GYM_BOTH_STATS_SUCCESS' => "Je bent begonnen met {type} en verdiende {power} kracht en {cardio} cardio.",
            'TRAIN_GYM_POWER_SUCCESS' => "Je bent begonnen met {type} en verdiende {power} kracht.",
            'TRAIN_GYM_CARDIO_SUCCESS' => "Je bent begonnen met {type} en verdiende {cardio} cardio.",
            'LOCATION' => $this->possessionsLangs()['LOCATION'],
            'COMPETITION_DOESNT_EXIST' => "Je hebt een ongeldige wedstrijd geselecteerd.",
            'COMPETITION_ALREADY_STARTED_COMPETITION' => "Je hebt al een wedstrijd gestart, wacht a.u.b. tot een speler jouw uitdaagt.",
            'COMPETITION_CREATE_COMPETITION_SUCCESS' => "Je hebt een {competition} wedstrijd met een inzet van $&#8203;{stake} in {location} gestart, wacht nu tot een speler je prestatie komt uitdagen in dezelfde gym.",
            'COMPETITION_IN_OTHER_CITY' => "Deze wedstrijd word in {location} gehouden, gelieve naar daar te reizen om deel te nemen.",
            'COMPETITION_CANNOT_CHALLENGE_SELF' => "Je kan jezelf niet uitdagen op jouw eigen wedstrijd.",
            'COMPETITION_CHALLENGE_WIN' => "Je hebt deze speler uitgedaagd en gewonnen! Je hebt $&#8203;{profits} inzet en {scorePoints} scorepunten verdient!",
            'COMPETITION_CHALLENGE_LOSE' => "De speler die je uitdaagde heeft je verslagen! Je hebt $&#8203;{stake} inzet verloren maar wel {scorePoints} scorepunten verdient!",
            'COMPETITION_CHALLENGE_DRAW' => "Het werd een zware wedstrijd die eindigde in gelijkspel! Je hebt je inzet niet verloren en {scorePoints} scorepunten verdient!"
        );
        return $langs;
    }
    
    public function groundLangs()
    {
        $langs = array(
            'VIEW' => "Bekijk",
            'FROM' => "Vanuit",
            'OF' => $this->garageLangs()['OF'],
            'NO_ONE' => "Niemand",
            'IS_THE_OWNER_OF_THIS' => $this->langMap['IS_THE_OWNER_OF']. " " . $this->langMap['DEZETHIS'],
            'LOCATION' => $this->possessionsLangs()['LOCATION'],
            'GROUND' => "Plattegrond",
            'BOMB_GROUND' => "Aanslag plegen",
            'BOMBS' => "Bommen",
            'BOMB' => "Bombarderen",
            'BOMBING_INFO' => "Max. 35, $10,000 per bom.",
            'BUILDINGS' => "Gebouwen",
            'PRICE' => $this->marketLangs()['PRICE'],
            'PROFIT' => $this->stockExchangeLangs()['PROFIT'] . " / Uur",
            'GROUND_OWNER_INFO' => "Koop gebouwen om ieder uur geld te verdienen! Upgrade jouw gebouwen capaciteit om tot 25% meer te verdienen per uur.",
            'GROUND_ALREADY_OWNED' => "Dit plattegrond gebied is van een andere speler.",
            'USER_TRAVEL_SAME_STATE_AS_MAP' => "Je moet reizen naar een stad in {state} om dit landje te kunnen kopen.",
            'ALREADY_OWN_MAX_GROUND' => "Je bezit al {limit} landjes over alle staten!",
            'BUY_GROUND_SUCCESS' => "Je hebt dit landje gekocht in {state} voor $100,000!",
            'USER_BUY_BUILDING_TRAVEL_SAME_STATE_AS_MAP' => "Je moet reizen naar een stad in {state} om een gebouw op dit landje te kunnen kopen.",
            'USER_UPGRADE_BUILDING_TRAVEL_SAME_STATE_AS_MAP' => "Je moet reizen naar een stad in {state} om dit gebouw verder te kunnen upgraden.",
            'DONT_OWN_THIS_GROUND' => "Je bezit dit landje niet!",
            'ALREADY_OWN_THIS_BUILDING' => "Je bezit dit gebouw al op dit landje.",
            'ALREADY_UPGRADED_THIS_BUILDING' => "Dit gebouw heeft al zijn maximum capaciteit bereikt.",
            'BUY_GROUND_BUILDING_SUCCESS' => "Je hebt een {building} gekocht op je landje in de staat {state}. Je hebt $&#8203;{price} betaald voor dit gebouw.",
            'UPGRADE_GROUND_BUILDING_SUCCESS' => "Je hebt je {building} ge-upgrade op je landje in de staat {state}. Je hebt $&#8203;{price} betaald.",
            'BOMBS_BETWEEN_1_AND_35' => "Je kan maar tussen de 1 en 35 bommen dragen.",
            'DONT_OWN_AIRPLANE' => "Je hebt geen vliegtuig, je kan er één kopen in de <a href='/game/equipment-stores/airplanes'><strong>uitrustingen winkels</strong></a>",
            'CANNOT_BOMB_OWN_GROUND' => "Je kan je eigen landje niet bombarderen!",
            'USER_BOOMB_TRAVEL_SAME_STATE_AS_MAP' => "Je moet reizen naar een stad in {state} om dit landje te kunnen bombarderen.",
            'BOMB_GROUND_SUCCESS' => "Je hebt dit landje veroverd in {state}! Je hebt $&#8203;{price} betaald in bom kosten en 2 rankpunten verdient.",
            'BOMB_GROUND_FAILED' => "Je luchtaanval mislukte om dit landje over te nemen! Je hebt $&#8203;{price} betaald in bom kosten."
        );
        return $langs;
    }
    
    public function missionsLangs()
    {
        $langs = array(
            'PRIZE' => $this->luckyboxLangs()['PRIZE'],
            'EACH' => $this->stockExchangeLangs()['EACH'],
            'MISSIONS_INFO' => "Speel missies volledig vrij om hun badge vrij te spelen op je spelersprofiel.",
            "PUBLIC" => "Publieke",
            "MISSION" => "Missie",
            "TIME_LEFT" => "Tijd over",
            "PAYOUT" => "Uitbetaal",
            "PROGRESS" => "Vooruitgang",
        );
        return $langs;
    }
    
    public function dailyChallengesLangs()
    {
        $langs = array(
            'PRIZE' => $this->luckyboxLangs()['PRIZE'],
            'EXPERIENCE' => $this->statusLangs()['EXPERIENCE'],
            'DAILY_INFO' => "Voltooi alle uitdagingen om je <strong>{luckies}</strong> bonus lucky box(en) te ontvangen voor vandaag.",
        );
        return $langs;
    }
    
    public function possessionsLangs()
    {
        $rldLangs = $this->redLightDistrictLangs();
        $famPropLangs = $this->familyPropertiesLangs();
        global $route;
        $langs = array(
            'COUNTRY_POSSESSIONS' => "Land bezittingen",
            'STATE_POSSESSIONS' => "Staat bezittingen",
            'CITY_POSSESSIONS' => "Stad bezittingen",
            'TOTAL_REVENUE' => "Totale omzet",
            'MANAGE' => "Beheren",
            'DROP' => "Wegdoen",
            'LOCATION' => "Locatie",
            'EVERYWHERE_IN_THE_US' => "Overal",
            'VIEW_FROM' => "Bekijk bezittingen vanuit",
            'UNKNOWN_POSSESSION' => "Deze bezitting bestaat niet!",
            'CANT_BUY_FROM_DIFFERENT_STATE' => "Je kan deze bezitting niet kopen vanuit een andere staat.",
            'CANT_BUY_FROM_DIFFERENT_CITY' => "Je kan deze bezitting niet kopen vanuit een andere stad.",
            'CANT_BUY_WITH_PROTECTION' => "Je kan geen bezitting kopen met bescherming. <a href='".$route->getRouteByRouteName('status')."'><strong>Klik hier</strong></a> om naar de status pagina te gaan om je bescherming weg te halen.",
            'CANT_RECEIVE_WITH_PROTECTION' => "Je kan geen bezitting ontvangen met bescherming. <a href='".$route->getRouteByRouteName('status')."'><strong>Klik hier</strong></a> om naar de status pagina te gaan om je bescherming weg te halen.",
            'POSSESSION_HAS_OWNER_ALREADY' => "Deze bezitting is al in het bezit van een andere speler.",
            'ALREADY_OWN_SAME_POSSESSION' => "Je hebt deze bezitting al ergens anders, je kan deze vanuit iedere locatie wegdoen op de bezittingen pagina.",
            'RECEIVER_ALREADY_OWN_SAME_POSSESSION' => "Deze speler bezit deze bezitting al ergens anders.",
            'FAMILY_MAX_POSSESSION' => "Je familie bezit al het maximum aantal van deze bezitting.",
            'RECEIVER_FAMILY_MAX_POSSESSION' => "Deze speler zijn familie bezit al het maximum aantal van deze bezitting.",
            'FAMILY_MAX_COUNTRY_POSSESSION' => "Je familie bezit al een land bezitting.",
            'RECEIVER_FAMILY_MAX_COUNTRY_POSSESSION' => "Deze speler zijn familie bezit al een land bezitting.",
            'USER_ALREADY_OWN_COUNTRY_POSSESSION' => "Je bezit al een land bezitting.",
            'RECEIVER_ALREADY_OWN_COUNTRY_POSSESSION' => "Deze speler bezit al een land bezitting.",
            'BOUGHT_POSSESSION_SUCCESS' => "Je hebt $&#8203;{price} betaald aan de notaris en je werd de nieuwe eigenaar van {pName} in {location}!",
            'DROP_POSSESS_CONFIRM' => "Weet je zeker dat je deze bezitting wil wegdoen?<br /><br /><form id='dropPossessionConfirm' class='ajaxForm' method='POST' action='". $route->getAjaxRouteByRouteName('drop-possession') ."' data-response='#dropPossessionResponse{id}'><input type='hidden' name='security-token' value='{securityToken}'/><input type='hidden' name='id' value='{id}'/><input type='submit' class='btn button alert-btn' name='drop-possess-confirm' value='Wegdoen'/>&nbsp;<button type='button' class='button alert-btn' data-dismiss='alert' aria-hidden='true'>".$this->langMap['CANCEL']."</button></form>",
            'DROP_POSSESS_SUCCESS' => "Je hebt je {pName} weggedaan, deze is terug te koop geplaatst.",
            'ALREADY_TRANSFERED_TO_RECEIVER' => "Je hebt deze bezitting al overgedragen naar deze speler.",
            'TRANSFER_POSSESS_REQUEST_SUCCESS' => "Je hebt je {pName} in {location} verzonden naar {user}, hij heeft 48u de tijd om dit verzoek te behandelen.",
            'SENDER_DOESNT_OWN_POSSESSION' => "{sender} kan deze bezitting niet meer versturen. Dit verzoek is nu verwijderd.",
            'ACCEPT_TRANSFER_POSSESS_SUCCESS' => "Je hebt de {pName} in {location} van {sender} geacepteerd, je bent nu de nieuwe eigenaar van deze bezitting.",
            'DENY_TRANSFER_POSSESS_SUCCESS' => "Je hebt de {pName} in {location} van {sender} geweigerd.",
            'PRICE_2500_IF_OVER_10M_BULLETS' => "De prijs kan enkel nog op $2,500 gezet worden wanneer de fabriek meer dan 10,000,000 kogels bevat.",
            'PRICE_ALREADY_SET' => "Deze prijs is al van toepassing!",
            'BETWEEN_200_AND_2500_BULLET_PRICE' => "De prijs per kogel moet tussen de $200 en $2,500 liggen!",
            'CHANGE_BULLET_PRICE_SUCCESS' => "Je hebt de prijs per kogel ingesteld op $&#8203;{price}.",
            'BETWEEN_50_AND_300_WINDOW_PRICE' => "De prijs per raam moet tussen de $50 en de $300 liggen!",
            'CHANGE_WINDOW_PRICE_SUCCESS' => "Je hebt de prijs per raam ingesteld op $&#8203;{price}.",
            'BETWEEN_1K_AND_500K_STAKE' => "De maximum inzet moet tussen de $1,000 en  $500,000 liggen!",
            'BETWEEN_1K_AND_500K_MASS_MESSAGE' => "De prijs voor een massa bericht moet tussen de $1,000 en  $500,000 liggen!",
            'CHANGE_STAKE_SUCCESS' => "Je hebt de maximale inzet ingesteld op $&#8203;{stake}.",
            'CHANGE_MASS_MESSAGE_PRICE_SUCCESS' => "Je hebt de prijs voor een massa bericht ingesteld op $&#8203;{stake}.",
            'INVALID_PRODUCTION' => $famPropLangs['INVALID_PRODUCTION'],
            'SET_BF_PRODUCTION_SUCCESS' => $famPropLangs['SET_BF_PRODUCTION_SUCCESS'],
            'BUY_WINDOWS_SUCCESS' => "Je hebt 10,000 ramen gekocht voor $1,000,000.",
            'OVERVIEW' => "Overzicht",
            'PROFIT_HOUR' => $this->stockExchangeLangs()['PROFIT'] . " dit uur",
            'BULLET' => "Kogel",
            'PRICE' => $this->marketLangs()['PRICE'],
            'PRODUCTION' => $this->bulletFactoriesLangs()['PRODUCTION'],
            'WINDOWS' => $rldLangs['WINDOWS'],
            'BUY_WINDOWS' => $rldLangs['BUY_WINDOWS'],
            'PRICE_EACH_WINDOW' => $rldLangs['PRICE_EACH_WINDOW'],
            'MAXIMUM_STAKE' => "Maximum inzet",
            'CASINO_INFO' => "Je hebt <strong>$&#8203;<span id='casinoStakeAmount'>{money}</span><span id='casinoStakeAmountChange'></span></strong> om te vergokken...<br />De maximale inzet is <strong>$&#8203;{max}</strong>",
            'STAKE_BETWEEN_1_AND_MAX' => "Zet tussen de $1 en de $&#8203;{max} in.",
            'CANNOT_PLAY_IN_OWN_CASINO' => "Je kan niet in je eigen {casinoName} spelen!",
            'PLAY_CASINO_BROKE_TOOK_OVER' => "Je hebt deze {casinoName} blut gespeeld en je bent nu de nieuwe eigenaar.",
            'PLAY_CASINO_BROKE_STATUS_ERROR' => "Je hebt deze {casinoName} blut gespeeld maar omdat je nog bescherming had is deze tekoop gezet.",
            'PLAY_CASINO_BROKE_SELF_ERROR' => "Je hebt deze {casinoName} blut gespeeld maar omdat je al een {casinoName} bezit is deze tekoop gezet.",
            'PLAY_CASINO_BROKE_FAMILY_ERROR' => "Je hebt deze {casinoName} blut gespeeld maar omdat je familie het maximum aantal bezit is deze tekoop gezet.",
        );
        return $langs;
    }
    
    public function donationShopLangs()
    {
        global $route;
        $registerLangs = $this->registerLangs();
        $langs = array(
            'CREDITS_INFO' => "Je hebt <strong>{credits} credits</strong> in je bezit. <!-- <a href='#'><i class='donator'>Doneren!</i></a> -->",
            'DONATION_SHOP_INFO' => "Alle status voordelen kan je vinden op de <a href='javascript:void(0);' class='ajaxTab help' data-tab='help'><img src='/foto/web/public/images/icons/help.png' class='icon' alt='Help'/> Help pagina.</a>",
            'ACHIEVED_HIGHEST_STATUS' => "Je hebt de hoogste status in ".$route->settings['gamename']." bereikt!",
            'PROFIT_DISCOUNT' => "Profiteer van korting door onmiddelijk Gold Member te worden.",
            'FAMILY_IS_VIP' => "Jouw familie heeft een VIP status!",
            'NOT_ENOUGH_CREDITS' => "Je hebt niet genoeg credits voor deze aankoop.",
            'USER_ALREADY_HAS_STATUS' => "Je hebt deze status reeds aangekocht.",
            'FAMILY_ALREADY_VIP' => "Je familie is al VIP.",
            'NO_FAMILY' => "Je kan dit pas aankopen als je deel uitmaakt van een familie.",
            'BOUGHT_STATUS_SUCCESS' => "Je hebt een {status} status aangekocht voor {credits} credits!",
            'BOUGHT_FAMILY_VIP_SUCCESS' => "Je hebt een VIP familie status aangekocht voor 500 credits!",
            'BOUGHT_LUCKYBOX_SUCCESS' => "Je hebt {boxes} boxen gekocht voor {credits} credits!",
            'HALVING_TIMES' => "Wachttijden halveren voor 12 uur",
            'BOUGHT_HALVING_TIMES_SUCCESS' => "Je hebt 63 credits uitgegeven om vanaf nu uw wachttijden voor 12 uur te halveren.",
            'BRIBING_BORDER_PATROL' => "Douane omkopen voor 8 uur",
            'BOUGHT_BRIBING_POLICE_SUCCESS' => "Je gaf {credits} credits aan de douane om 8 uur lang te kunnen smokkelen zonder gepakt te worden.",
            'GROUND' => "Extra plattegrond landje",
            'BOUGHT_GROUND_SUCCESS' => "Je hebt 100 credits ingewisseld voor een extra landje op de plattegrond.",
            'SMUGGLING_CAPACITY' => "100 extra smokkel draagvermogen",
            'BOUGHT_SMUGGLING_CAPACITY_SUCCESS' => "Je hebt 100 extra smokkel capaciteit gekocht voor 100 credits!",
            'NEW_PROFESSION' => "Nieuw beroep",
            'SELECT_TAG_CHOOSE' => $registerLangs['SELECT_TAG_CHOOSE'],
            'CARJACKER' => $registerLangs['CARJACKER'],
            'PRISON_BREAKER' => $registerLangs['PRISON_BREAKER'],
            'THIEF' => $registerLangs['THIEF'],
            'PIMP' => $registerLangs['PIMP'],
            'BANKER' => $registerLangs['BANKER'],
            'SMUGGLER' => $registerLangs['SMUGGLER'],
            'INVALID_PROFESSION' => $registerLangs['INVALID_PROFESSION'],
            'BOUGHT_NEW_PROFESSION_SUCCESS' => "Voor 50 credits werd je beroep veranderd in {profession}.",
            'CONTINUE' => "Doorgaan",
            'CAN_RECEIVE' => "Je kunt nog tot {credits} credits ontvangen als donatie beloning.",
            'LIMIT_RESET' => "Op {date} word je limiet terug op 5,000 gezet.",
            'DONATE_BTN_HEAD' => "<h4>Even je aandacht</h4><p>Ontvang onmiddellijk credits na een donatie van een willekeurig bedrag vanaf minstens 1 euro. Donaties die 50 euro overschrijden zullen enkel tot 5,000 credits opleveren elke maand met uitzondering van bonus credits en acties.</p><h4>Doneer veilig via PayPal</h4>",
            'DONATE_BTN_FOOT' => "<small>Alle transacties zijn beveiligd en versleuteld voordat ze worden verzonden.</small><h4>Problemen?</h4><p>Contacteer een Administrator of <span style='color:#3498db'><a href='mailto:info@".$route->settings['domainBase']."?subject=".$route->settings['gamename']." donatieshop probleem'><strong>stuur ons een email</strong></a></span> voor hulp.</p>",
            'DONATE_REWARDED_ALREADY' => "Deze donatiebonus is al geclaimd!",
            'DONATE_ERROR' => "Er is een probleem opgetreden met je donatie, contacteer een Administrator voor hulp.",
            'DONATE_SUCCESS' => "Je donatie werd ontvangen en {credits} credits werden aan je account toegevoegd als beloning. Bedankt!",
            'DONATE_SUCCESS_HIT_LIMIT' => "Je hebt wel je limiet bereikt die zal resetten binnen 31 dagen.",
            'DONATE_SUCCESS_LIMIT' => "Je donatie werd ontvangen maar door jouw limiet heb je geen credits ontvangen! Bedankt!",
            'DONATOR_LIST_INFO' => "Ik wil mijn gebruikersnaam toevoegen aan de ledenlijst van donateurs.",
            'LEAVE_DONATOR_LIST_SUCCESS' => "Je hebt de donateurs ledenlijst verlaten.",
            'DONATOR_LIST_APPLICATION_SUCCESS' => "Je hebt je succesvol aangemeld voor de donateurs ledenlijst.",
            "CHANGE_NAME" => "Naam wijzigingen",
            "NEW_NAME" => "Nieuwe naam",
            "INVALID_USERNAME" => $registerLangs['INVALID_USERNAME'],
            "USERNAME_TAKEN" => $registerLangs['USERNAME_TAKEN'],
            "BOUGHT_NICKNAME_SUCCESS" => "Je hebt succesvol je naam gewijzigd!"
        );
        return $langs;
    }
    
    public function pollLangs()
    {
        $langs = array(
            'NO_ACTIVE_POLL' => "Er is momenteel geen poll actief.",
            'CLICK_TO_VIEW_HISTORY' => "<strong>Klik hier</strong> om de poll geschiedenis te bekijken",
            'NO_HISTORY_TO_VIEW' => "Er is momenteel geen poll geschiedenis om weer te geven.",
            'STARTED_ON' => "Gestart op",
            'ENDED_ON' => "Beëindigd op",
            'TOTAL_VOTES' => "Totaal aantal stemmen",
            'VOTE' => "Stemmen",
            'INVALID_POLL' => "Deze poll bestaat niet!",
            'INVALID_ANSWER' => "Dit antwoord bestaat niet!",
            'SELECT_ANSWER' => "Gelieve een antwoord te selecteren.",
            'ALREADY_VOTED_THIS_QUESTION' => "Je hebt reeds gestemd op deze poll, bedankt!",
            'VOTE_SUCCESS' => "Bedankt om te stemmen op deze poll, je kan de resultaten hieronder bekijken:"
        );
        return $langs;
    }
    
    public function forumLangs()
    {
        $langs = array(
            'VIEW_FORUM_OUTGAME' => "&raquo; Bezoek het forum met een homepagina layout in een nieuw tabblad.",
            'REACTION' => "Reactie",
            'BY' => "Door",
            'ON' => "Op",
            'TITLE' => "Titel",
            'NEW_TOPIC' => "Nieuw topic maken",
            'POST_TOPIC' => "Topic posten",
            'NEW_REACTION' => "Reageren",
            'PLACE_REACTION' => "Reactie plaatsen",
            'REACT_FAST' => "Snel reageren",
            'REPLY_TOO_SHORT_OR_LONG' => "Je reactie moet tussen de 2 en de 10,000 tekens in totaal bevatten.",
            'TOPIC_TOO_SHORT_OR_LONG' => "Je topic bericht moet tussen de 2 en de 10,000 tekens in totaal bevatten.",
            'TITLE_TOO_SHORT_OR_LONG' => "Je topic titel moet tussen de 2 en de 100 tekens in totaal bevatten.",
            'NO_PERMISSIONS_TO_CREATE_TOPIC' => "Je hebt geen rechten om een topic in deze categorie te maken.",
            'TOPIC_DOESNT_EXIST' => "Topic bestaat niet!",
            'TOPIC_ADDED_SUCCESS' => "Je hebt je topic toegevoegd aan deze categorie! Je wordt zo dadelijk omgeleid.",
            'REACTION_ADDED_SUCCESS' => "Gelukt, je reactie werd toegevoegd aan dit topic! Je wordt zo dadelijk omgeleid.",
            'EDIT_YOUR_LAST_REACTION' => "Nog niemand heeft na jouw laatste reactie gepost, je kan deze nog bewerken.",
            'NO_REACTIONS_YET' => "Er zijn nog geen reacties in dit topic.",
            'NO_TOPICS_YET' => "Er zijn nog geen topics in dit forum.",
            'TOPIC_CLOSED' => "Dit topic is gesloten!",
            'EDIT_TOPIC' => "Topic bewerken",
            'EDIT_REACTION' => "Reactie bewerken",
            'REACTION_DOESNT_EXIST' => "Reactie bestaat niet!",
            'REACTION_EDITED_SUCCESS' => "Gelukt, je reactie werd bewerkt in dit topic! Je wordt zo dadelijk omgeleid.",
        );
        return $langs;
    }
    
    public function shoutboxLangs()
    {
        global $route;
        $langs = array(
            'MESSAGE_NOT_IN_RANGE' => "Je bericht moet tussen de 2 en de 200 karakters lang zijn!",
            'INVALLID_FAMILY' => "Je kan niet posten in een andere familie shoutbox!",
            'SHOUTBOX_RESET' => "Iedere Zondag om 19u word de shoutbox gereset en worden berichten ouder dan 7 dagen verwijderd. Op de shoutbox gelden dezelfde <a href='".$route->getRouteByRouteName('information-rules')."'><strong>Regels</strong></a> als op ieder ander gedeelte van het spel!",
            'WAIT_TILL_SOMEBODY_ELSE_POSTED' => "Wacht alstublieft tot iemand anders een bericht plaatst!",
            'LOOKS_SILENT' => "Het lijkt hier stil."
        );
        return $langs;
    }
    
    public function fiftyGamesLangs()
    {
        $gymLangs = $this->gymLangs();
        $langs = array(
            'CHALLENGE' => $gymLangs['CHALLENGE'],
            'START_GAME' => "Spel Starten",
            'NO_GAMES_ATM' => "Er is momenteel geen spel te vinden.",
            'CANNOT_PLAY_OWN_GAME' => "Je kan geen actie ondernemen op je eigen spel.",
            'NOT_ENOUGH_AMOUNT_TYPE' => "Je hebt niet genoeg {type} om in te zetten in dit spel.",
            'INVALID_GAME' => "Dit spel bestaat (niet) meer.",
            'FIFTY_GAME_LOST' => "Je hebt je inzet van {amount} {type} verloren.",
            'FIFTY_GAME_WON' => "Je hebt dit spel gewonnen en je inzet van {amount} {type} verdubbeld.",
            'FIFTY_GAME_LOST_CASH' => "Je hebt je inzet van $&#8203;{amount} {type} verloren.",
            'FIFTY_GAME_WON_CASH' => "Je hebt dit spel gewonnen en je inzet van $&#8203;{amount} {type} verdubbeld.",
            'ALREADY_STARTED_GAME' => "Je hebt hier al een 50/50 spel gestart.",
            'STAKE_BETWEEN_100K_AND_100M' => "Je moet tussen de $100,000 en $100,000,000 contant inzetten.",
            'STAKE_BETWEEN_100_AND_10K' => "Je moet tussen de 100 en 10,000 hoeren inzetten.",
            'STAKE_BETWEEN_10_AND_1K' => "Je moet tussen de 10 en 1,000 eerpunten inzetten.",
            'INVALID_TYPE' => "Ongeldige actie, vernieuw deze pagina en probeer a.u.b. opnieuw.",
            'CREATE_GAME_SUCCESS' => "Je hebt een 50/50 spel gestart met een inzet van {amount} {type}.",
            'CREATE_GAME_SUCCESS_CASH' => "Je hebt een 50/50 spel gestart met een inzet van $&#8203;{amount} {type}.",
        );
        return $langs;
    }
    
    public function dobblingLangs()
    {
        $langs = array(
            'YOUR_THROW' => "Jouw worp",
            'HIS_THROW' => "Zijn worp",
            'PLAY_DOBBLING_SUCCESS_BROKE_EVEN' => "Je hebt je inzet terug verdient!",
            'PLAY_DOBBLING_SUCCESS_WON' => "Je hebt $&#8203;{profits} gewonnen!",
            'PLAY_DOBBLING_SUCCESS_LOST' => "Je hebt $&#8203;{losses} verloren!"
        );
        return $langs;
    }
    
    public function racetrackLangs()
    {
        $langs = array(
            'PLAY_RACETRACK_SUCCESS_WON' => "Je hebt $&#8203;{profits} gewonnen!",
            'PLAY_RACETRACK_SUCCESS_LOST' => "Je hebt $&#8203;{losses} verloren!"
        );
        return $langs;
    }
    
    public function rouletteLangs()
    {
        $langs = array(
            'RED' => "Rood",
            'BLACK' => "Zwart",
            'ODD' => "Oneven",
            '1_COL' => "1e Kol",
            '2_COL' => "2e Kol",
            '3_COL' => "3e Kol",
            'PLAY_ROULETTE_SUCCESS_BROKE_EVEN' => "Balletje landde op {rolled} je hebt je inzet terug verdient!",
            'PLAY_ROULETTE_SUCCESS_WON' => "Balletje landde op {rolled} je hebt $&#8203;{profits} gewonnen!",
            'PLAY_ROULETTE_SUCCESS_LOST' => "Balletje landde op {rolled} je hebt $&#8203;{losses} verloren!",
        );
        return $langs;
    }
    
    public function slotMachineLangs()
    {
        $langs = array(
            'WINNING_COMBINATIONS' => "Winnende combinaties",
            'POSITION_DOESNT_MATTER' => "Positie maakt niets uit",
            'ALL_FRUITS' => "Alle stukken fruit",
            'PROFIT' => $this->stockExchangeLangs()['PROFIT'],
            'PLAY_SLOT_MACHINE_SUCCESS_BROKE_EVEN' => "Je hebt je inzet terug verdient!",
            'PLAY_SLOT_MACHINE_SUCCESS_WON' => "Je hebt $&#8203;{profits} gewonnen!",
            'PLAY_SLOT_MACHINE_SUCCESS_LOST' => "Je hebt $&#8203;{losses} verloren!"
        );
        return $langs;
    }
    
    public function blackjackLangs()
    {
        $langs = array(
            'YOUR' => "Jouw",
            'POINTS' => "Punten",
            'HIS' => "Zijn",
            'PICK_A_CARD' => "Trek een kaart",
            'QUIT' => $this->familyRaidLangs()['QUIT'],
            'CARD' => "Kaart",
            'CARDS' => "Kaarten",
            'PLAY_BLACKJACK_SUCCESS_BROKE_EVEN' => "Je hebt je inzet terug verdient!",
            'PLAY_BLACKJACK_SUCCESS_WON' => "Je hebt $&#8203;{profits} gewonnen!",
            'PLAY_BLACKJACK_SUCCESS_LOST' => "Je hebt $&#8203;{losses} verloren!",
            'FRESH_DECK_INFO' => "Om het tellen van kaarten tegen te gaan, begint elk spel met een nieuw geschud kaartspel."
        );
        return $langs;
    }
    
    public function lotteryLangs()
    {
        $langs = array(
            'PRIZE_POOL' => "Prijzenpot",
            'LATEST_WINNERS' => "Laatste Winnaars",
            'DAY' => $this->stockExchangeLangs()['DAY'],
            'WEEKLY' => "Wekelijkse",
            'PLACE' => "Plaats",
            'TICKETS_SOLD' => "Loten verkocht",
            'TICKET' => "Lot",
            'FIRST' => "Eerste",
            'SECOND' => "Tweede",
            'THIRD' => "Derde",
            'FOURTH' => "Vierde",
            'FIFTH' => "Vijfde",
            'SIXTH' => "Zesde",
            'SEVENTH' => "Zevende",
            'EIGHTH' => "Achtste",
            'NINTH' => "Negende",
            'DAILY_AFTER_SUPERPOT' => "Nieuwe dagelijkse trekkingen na de superpot!",
            'HAS_TICKET_FOR_DRAWING' => "Je hebt een lot met code <strong>{code}</strong> voor volgende trekking!",
            'BOUGHT_TICKET_SUCCESS' => "Je hebt een {type} lot gekocht voor $&#8203;{price}.",
            'DAY_LOTTERY_INFO' => "De dagelijkse trekking vindt elke dag om 19:00 plaats.",
            'WEEK_LOTTERY_INFO' => "De wekelijkse trekking gebeurt iedere Vrijdag om 19:00.",
            'NO_WINNERS_PREVIOUS_DRAW' => "Niemand heeft een prijs gewonnen in de vorige trekking."
        );
        return $langs;
    }
    
    public function profileLangs()
    {
        $langs = array(
            'PROFILE_HEADING' => "Spelers profiel {name}",
            'LAST_ONLINE' => "Laatst online",
            'NO_TOPLIST' => "Plaats in Toplijst",
            'REPORT_PROFILE' => "Profiel melden",
            'NO_PROFILE' => "Geen profiel beschikbaar",
            'PLAYER_INFO' => "Speler info",
            'PROFILE_VIEWS' => "x Bekeken",
            'SEND_MESSAGE' => "Bericht verzenden",
            'SEND_FRIEND_INVITE' => "Vriend toevoegen",
            'PIMP_FOR_PLAYER' => "<input type='submit' name='pimp' class='pimp' value='Pimpen'/> voor speler",
            'ATTACK_PLAYER' => "Speler aanvallen",
            'LANGUAGE' => "Taal"
        );
        return $langs;
    }
    
    public function crimesLangs()
    {
        global $route;
        $langs = array(
            'SPONTANEOUS' => "Spontaan",
            'ORGANIZED' => "Georganiseerd",
            'CRIME_PROFITS' => "Misdaad winsten",
            'SUCCESS_FAIL_RATIO' => "Gelukt/mislukt ratio",
            'RANK_POINTS_COLLECTED' => "Rankpunten verzameld",
            'COMMIT_CRIME' => "Misdaad plegen",
            'SPONTANEOUS_CRIME_INFO' => "Je stats zullen resetten na je dood.",
            'INVALID_CRIME_SELECTED' => "Je kan deze misdaad (nog) niet plegen!",
            'COMMIT_CRIME_SUCCESS' => "Je hebt deze misdaad succesvol gepleegd, je hebt $&#8203;{stolenMoney} contant gestolen en {rankpoints} rankpunt(en) verdient!",
            'COMMIT_CRIME_SUCCESS_BUT_HURT' => "Je bent gewond geraakt en hebt <strong>{hurtPercent}%</strong> leven en <strong>{bullets}</strong> kogels verloren maar je hebt wel $&#8203;{stolenMoney} contant gestolen en {rankpoints} rankpunt(en) verdient!",
            'COMMIT_CRIME_SUCCESS_BUT_HEAT' => "Je hebt <strong>{bullets}</strong> kogels moeten afvuren maar je hebt wel $&#8203;{stolenMoney} contant gestolen en {rankpoints} rankpunt(en) verdient!",
            'COMMIT_CRIME_ARRESTED' => "Je bent betrapt door de politie bij het plegen van deze misdaad! Je werd gearresteerd voor <strong>90 seconden</strong>.",
            'COMMIT_ORGANIZED_CRIME_ARRESTED' => "Je bent betrapt door de politie bij het plegen van deze misdaad! Je werd gearresteerd voor <strong>{prisonTime} seconden</strong>.",
            'COMMIT_ORGANIZED_CRIME_PARTICIPANT_ARRESTED' => "{user} werd betrapt door de politie bij het plegen van deze misdaad! Hij werd gearresteerd voor <strong>{prisonTime} seconden</strong>.",
            'COMMIT_CRIME_FAILED' => "De misdaad die je pleegde is mislukt.",
            'COMMIT_CRIME_FAILED_AND_HURT' => "De misdaad die je pleegde is mislukt. Je bent gewond geraakt en hebt <strong>{hurtPercent}%</strong> leven en <strong>{bullets}</strong> kogels verloren tijdens het ontsnappen van de politie!",
            'IN_PROGRESS' => "Wordt uitgevoerd",
            'READY' => "Klaar",
            'LEADER' => "Leider",
            'DRIVER' => "Chauffeur",
            'RAIDER' => "Overvaller",
            'GROUND' => "Grond persoon",
            'ORGANIZED_CRIME_NEED_LV_15' => "Je moet minimaal level 15 misdaden hebben om een georganiseerde misdaad te kunnen plegen!",
            'ORGANIZED_CRIME_INFO' => "50 kogels + uitgerust vuurwapen vereist om verrassingen af te kunnen vechten! Georganiseerde misdaden kunnen fout aflopen, bezoek op tijd een ziekenhuis wanneer je weinig leven hebt.",
            'NEED_FIRE_WEAPON_EQUIPPED' => "Je hebt een geweer nodig met minstens 50 kogels, je kan <a href='/game/equipment-stores'><strong>hier</strong></a> een geweer kopen of (indien beschikbaar) <a href='/game/bullet-factories'><strong>hier</strong></a> kogels.",
            'ALREADY_PREPARED_FOR_CRIME' => "Je doet al mee aan deze georganiseerde misdaad!",
            'INVALID_JOB_SELECTED' => "Je hebt een ongeldige job taak gekozen!",
            'PLAYER_NOT_EXPERIENCED_ENOUGH' => "Deze speler heeft nog niet genoeg ervaring!",
            'TYPE_NOT_EXPERIENCED_ENOUGH' => "De gekozen {type} heeft nog niet genoeg ervaring!",
            'PLAYER_PART_OF_DIFFERENT_CRIME' => "Deze speler maakt al deel uit van deze misdaad met iemand anders.",
            'TYPE_PART_OF_DIFFERENT_CRIME' => "De {type} die je hebt gekozen doet al mee aan deze misdaad met een andere crew.",
            'CANNOT_INVITE_CRIME_SELF' => "Je kan jezelf niet uitnodigen voor een georganiseerde overval!",
            'SELECTED_PLAYER_MULTIPLE_TIMES' => "Je hebt een speler meerdere keren gekozen!",
            'PREPARE_ORGANIZED_CRIME_2_SUCCESS' => "Je hebt {username} uitgenodigd voor een georganiseerde misdaad als {job}, wacht voor zijn bevestiging.",
            'PREPARE_ORGANIZED_CRIME_3_SUCCESS' => "Je hebt een georganiseerde misdaad voorbereid voor jezelf, {getaway}, {ground} en {intel}, wacht op hun bevestiging.",
            'NOT_PREPARED_FOR_CRIME' => "Je bent niet voorbereid voor deze misdaad!",
            'ALREADY_PREPARED_AND_READY' => "Je bent al klaar met de voorbereidingen voor deze misdaad!",
            'VEHICLE_NOT_IN_CURRENT_GARAGE' => "Dit voertuig is niet aanwezig in je huidige garage!",
            'INVALID_WEAPON' => "Je hebt een ongeldig wapen geselecteerd!",
            'INVALID_INTEL' => "Je hebt een ongeldig intel pakket geselecteerd!",
            'READY_UP_ORGANIZED_CRIME_SUCCESS' => "Je bent nu klaar voor deze georganiseerde misdaad.",
            'LEADER_STOP_ORGANIZED_CRIME_CONFIRM' => "Weet je zeker dat je deze georganiseerde misdaad wil stopzetten? Je crew zullen alle (gekochte) spullen verliezen.<br /><br /><form id='interactOrganizedCrime' class='ajaxForm' method='POST' action='". $route->getAjaxRouteByRouteName('prepare-organized-crime') ."' data-response='#refreshCrimeResponse'><input type='hidden' name='security-token' value='{securityToken}'/><input type='hidden' name='id' value='{id}'/><input type='submit' name='stop-confirm' class='button' value='Misdaad stoppen'/>&nbsp;<button type='button' class='button alert-btn' data-dismiss='alert' aria-hidden='true'>".$this->langMap['CANCEL']."</button></form>",
            'LEADER_STOP_ORGANIZED_CRIME_SUCCESS' => "Je hebt de georganiseerde misdaad stopgezet.",
            'PARTICIPANT_DENY_ORGANIZED_CRIME_CONFIRM' => "Weet je zeker dat je deze georganiseerde misdaad wil weigeren?<br /><br /><form id='interactOrganizedCrime' class='ajaxForm' method='POST' action='". $route->getAjaxRouteByRouteName('prepare-organized-crime') ."' data-response='#refreshCrimeResponse'><input type='hidden' name='security-token' value='{securityToken}'/><input type='hidden' name='id' value='{id}'/><input type='submit' name='deny-confirm' class='button' value='Misdaad weigeren'/>&nbsp;<button type='button' class='button alert-btn' data-dismiss='alert' aria-hidden='true'>".$this->langMap['CANCEL']."</button></form>",
            'PARTICIPANT_DENY_ORGANIZED_CRIME_SUCCESS' => "Je hebt de georganiseerde misdaad geweigerd.",
            'MEMBER_NOT_READY' => "{user} moet nog {waitingTime} seconden wachten.",
            'ONE_OR_MORE_IN_PRISON' => "Eén of meerdere deelnemers zitten in de gevangenis.",
            'DRIVER_INVITED_TO_ORGANIZED_CRIME_3' => "Je hebt {username} uitgenodigd als Getaway chauffeur voor jullie georganiseerde misdaad in Las Vegas.",
            'GROUND_INVITED_TO_ORGANIZED_CRIME_3' => "Je hebt {username} uitgenodigd als Grond persoon voor jullie georganiseerde misdaad in Las Vegas.",
            'INTEL_INVITED_TO_ORGANIZED_CRIME_3' => "Je hebt {username} uitgenodigd als Intel voor jullie georganiseerde misdaad in Las Vegas.",
            'EXECUTE_ORGANIZED_CRIME_3' => "Je bent samen met je crew op uitje naar Las Vegas, je krijgt binnenkort een melding.",
        );
        return $langs;
    }
    
    public function stealVehiclesLangs()
    {
        $crimesLangs = $this->crimesLangs();
        $langs = array(
            'STOLEN_VEHICLES_PROFITS' => "Totaal gestolen waarde",
            'SUCCESS_FAIL_RATIO' => $crimesLangs['SUCCESS_FAIL_RATIO'],
            'RANK_POINTS_COLLECTED' => $crimesLangs['RANK_POINTS_COLLECTED'],
            'STEAL_VEHICLE' => "Voertuig stelen",
            'STEAL_VEHICLES_INFO' => "Als je een voertuig wenst te bewaren in je huidige staat zul je een Garage moeten kopen. Eindig met het kopen van een garage in iedere staat om overal te kunnen reizen met een voertuig.",
            'INVALID_STEAL_VEHICLE_SELECTED' => "Je kan dit voertuig (nog) niet stelen!",
            'STEAL_VEHICLE_SUCCESS' => "Je hebt succesvol een {stolenVehicle} <strong>gestolen</strong> met {damage}% schade en je hebt ook {rankpoints} rankpunt(en) verdient!{addSome}{addSome2}{addSome3}<br /><br />{picture}",
            'STEAL_VEHICLE_ARRESTED' => "Je poging om een voertuig te stelen is <strong>mislukt</strong>, je werd gearresteerd voor <strong>150 seconden</strong>.",
            'STEAL_VEHICLE_FAILED' => "Het stelen van een voertuig is <strong>mislukt</strong>.",
            'STORE_OR_SELL_VEHIICLE' => "Wat wil je met het voertuig gaan doen? <button type='button' name='store' class='store_vehicle button'>Breng naar garage</button>&nbsp;<button type='button' name='sell' class='sell_vehicle button'>Verkopen $&#8203;{price}</button>",
            'VEHICLE_TOTAL_LOSS_SOLD' => "Het voertuig die je stal was (bijna) total loss, je hebt het verkocht voor <strong>$&#8203;{price}</strong>.",
            'NO_GARAGE_VEHICLE_SOLD' => "Je hebt geen garage in {state}, je hebt het voertuig verkocht voor <strong>$&#8203;{price}</strong>.",
            'STORE_VEHICLE_NO_GARAGE' => "Je hebt geen garage in {state}, je kan dit voertuig niet bewaren.<br />Je hebt het voertuig verkocht voor {price}.",
            'NO_VEHICLE_TO_STORE' => "Onbekend voertuig, actie opgeschort.",
            'NOT_ENOUGH_SPACE_GARAGE' => "Je hebt niet genoeg plaats in je garage. Verkoop een voertuig in je garage en keer terug om dit voertuig te bewaren.",
            'NO_SPACE_VEHICLE_SOLD' => "Je hebt niet genoeg plaats in je garage om dit voertuig te bewaren, je hebt het verkocht voor <strong>$&#8203;{price}</strong>",
            'VEHICLE_STORED_IN_GARAGE' => "Je hebt dit voertuig naar je garage in de staat {state} gebracht."           
        );
        return $langs;
    }
    
    public function drugsLiquidsLangs()
    {
        $langs = array(
            'LIQUIDS_BREWERY' => "Drankbrouwerij",
            'MAX_UNITS_INFO' => "Je kunt <strong>{maxUnits} productieunits</strong> tegelijk beheren.",
            'COSTS' => $this->travelLangs()['COSTS'],
            'PRODUCING' => $this->bulletFactoriesLangs()['PRODUCING'],
            'CREATE' => "Maken",
            'SECONDS_TO_GO' => "Seconden te gaan",
            'DONE' => "Klaar",
            'PRODUCED' => "Geproduceerd",
            'COLLECT' => "Binnenhalen",
            'NO_UNITS_PRODUCING' => "Er zijn momenteel geen units aan het produceren.",
            'NO_UNITS_LEFT_TO_PRODUCE' => "Je hebt je maximale producerende untis capatiteit bereikt.",
            'INVALID_UNIT_TYPE' => "Je hebt een ongeldig type geselecteerd!",
            'BOUGHT_ONE_UNIT_SUCCESS' => "Je hebt een unit {unit} gekocht voor $&#8203;{price} en de productie gestart.",
            'BOUGHT_MAX_UNITS_SUCCESS' => "Je hebt {unitAmount} units {unit} gekocht voor $&#8203;{price} en de productie gestart.",
            'SELECT_VALID_UNITS' => "Gelieve geldige units te selecteren om binnen te halen!",
            'COLLECTED_UNITS_SUCCESS' => "Je hebt {units} units binnengehaald met in totaal {amount} eenheden!",
            'UNFINISHED_UNITS_NOT_COLLECTED' => "Je hebt {units} units nog niet binnengehaald omdat deze nog niet klaar waren met produceren.",
            'INVALID_UNITS_NOT_COLLECTED' => "Je hebt {units} ongeldige units geselecteerd!",
            'CAPACITY_FULL_UNITS_NOT_COLLECTED' => "Je hebt {units} units niet binnengehaald omdat je niet meer genoeg ruimte had om eenheden te dragen.",
        );
        return $langs;
    }
    
    public function smugglingLangs()
    {
        $langs = array(
            'LIQUIDS' => "Drank",
            'FIREWORKS' => "Vuurwerk",
            'WEAPONS' => ucfirst($this->langMap['WEAPON']."s"),
            'EXOTIC_ANIMALS' => "Exotische dieren",
            'PROFIT_INDEX' => "Winst index",
            'SUCCESS_FAIL_RATIO' => $this->crimesLangs()['SUCCESS_FAIL_RATIO'], // Double
            'SMUGGLING_PROFITS' => "Smokkel winsten",
            'UNITS_SMUGGLED' => "Gesmokkeld",
            'UNITS_IN_POSSESSION' => "In bezit",
            'UNITS_AVAILABLE' => "Plaats vrij",
            'EACH' => "Per stuk",
            'VIEWING_PROFIT_INDEX_FOR' => "Je bekijkt de winst index voor",
            'IN_POSSESSION' => "In bezit",
            'INVALID_UNIT_SELECTED' => "Je hebt een ongeldige smokkel eenheid geselecteerd of een ongeldig aantal ingevoerd.",
            'CANNOT_CARRY_THAT_MUCH' => "Zoveel {type} kan je niet dragen, je kan nog {units} eenheden dragen.",
            'DONT_HAVE_THAT_MANY' => "Je hebt niet zoveel {unitName} in je bezit.",
            'BOUGHT_X_UNITS_SUCCESS' => "Je hebt {units} {unitName} gekocht voor $&#8203;{price}.",
            'SOLD_X_UNITS_SUCCESS' => "Je hebt {units} {unitName} verkocht voor $&#8203;{price}.",
            'SMUGGLING_INFO' => "Om ervaring op te doen met smokkelen hoef je alleen maar goederen in de ene stad te kopen om dan naar een andere stad te reizen en je eerder gekochte goederen te verkopen. Uw draagvermogen zal bij elke behaalde rank met 100 toenemen."
        );
        return $langs;
    }
    
    public function garageLangs() //Garage & Fam garage langs
    {
        $marketLangs = $this->marketLangs();
        global $route;
        $langs = array(
            'VEHICLES' => "Voertuigen",
            'SHOP' => "Winkel",
            'VEHICLE_BUSINESS' => "Voertuig Handelszaak",
            'SPACE' => "Ruimte",
            'HAS_GARAGE_IN_STATE_ALREADY' => "Je bezit al een garage in deze staat.",
            'GARAGE_OPTION_DOESNT_EXIST' => "Je hebt een ongeldige garage optie geselecteerd.",
            'GARAGE_BOUGHT_IN_STATE' => "Je hebt een garage gekocht in de staat {state}!",
            'NO_GARAGE_IN_STATE' => "Je hebt geen garage in deze staat!",
            'NO_SPACE_LEFT_GARAGE_IN_STATE' => "Je hebt geen plaats meer in je garage in de staat {state}!",
            'X_SPACE_LEFT_GARAGE_IN_STATE' => "Je hebt {x} plaatsen vrij in je garage in {state}!",
            'HAS_FAMILY_GARAGE_ALREADY' => "Je bezit al een familie garage.",
            'FAMILY_GARAGE_BOUGHT' => "Je hebt een familie garage gekocht, nu is het mogelijk om familie misdaden te plegen met familieleden!",
            'NO_FAMILY_GARAGE' => "Jullie hebben geen familie garage! Alleen de baas of onderbaas kan er één kopen.",
            'NO_SPACE_LEFT_FAMILY_GARAGE' => "Julie hebben geen plaats meer in de familie garage!",
            'X_SPACE_LEFT_FAMILY_GARAGE' => "Jullie hebben {x} plaatsen vrij in de familie garage!",
            'REPAIR' => "Repareren",
            'DAMAGE' => $this->equipmentStoresLangs()['DAMAGE'],
            'VALUE' => "Waarde",
            'TUNE' => "Afstemmen",
            'COSTS' => $this->travelLangs()['COSTS'],
            'VEHICLE_NOT_OWNED_BY_USER' => "Je bezit dit voertuig niet (meer).",
            'VEHICLE_NOT_IN_CURRENT_GARAGE' => $this->crimesLangs()['VEHICLE_NOT_IN_CURRENT_GARAGE'],
            'NO_REPAIR_NEEDED_FOR_VEHICLE' => "Dit voertuig is niet beschadigd en heeft geen reparaties nodig!",
            'REPAIR_VEHICLE_SUCCESS' => "Je hebt dit voertuig gerepareerd voor $&#8203;{costs}.",
            'SELL_VEHICLE_SUCCESS' => "Je hebt dit voertuig verkocht voor $&#8203;{price}.",
            'PRICE' => $marketLangs['PRICE'],
            'MORE_INFO' => "Meer info",
            'VEHICLE_DOESNT_EXIST' => "Dit voertuig bestaat niet!",
            'NO_GARAGE_WITH_FREE_SPACE' => "Je hebt geen garage beschikbaar met ten minste 1 vrije plaats.",
            'BUY_VEHICLE_CHOOSE_GARAGE' => "Aankop bijna voltooid, naar welke garage mag je nieuwe {vehicle} gebracht worden?<br /><br /><form id='interactVehicleShop' class='ajaxForm' method='POST' action='". $route->getAjaxRouteByRouteName('garage-interact-vehicle') ."' data-response='#vehicleActionResponse_{vehicleID}'><input type='hidden' name='securityToken' value='{securityToken}'/><input type='hidden' name='id' value='{vehicleID}' /><input type='hidden' name='action' value='bought' />{garagesSelection}</form>",
            'BOUGHT_VEHICLE_SUCCESS' => "Aankoop voltooid, je nieuwe {vehicle} werd zonet geleverd voor $&#8203;{price}.",
            'HORSEPOWER' => "Paardenkracht",
            'HP' => "PK",
            'TOPSPEED' => "Topsnelheid",
            'ACCELERATION' => "Acceleratie",
            'CONTROL' => "Wegligging",
            'BREAKING' => "Remmen",
            'LV_TO_STEAL' => "Stelen op level",
            'THE' => "De",
            'OF' => "Van",
            'TIRES' => "Banden",
            'ENGINE' => "Motor",
            'EXHAUST' => "Uitlaat",
            'SHOCK_ABSORBERS' => "Schokbrekers",
            'OVERVIEW' => $this->possessionsLangs()['OVERVIEW'],
            'TUNE_VEHICLE_DAMAGED' => "Repareer a.u.b. eerst uw voertuig voor je deze gaat afstemmen.",
            'CANNOT_SELL_TUNED_VEHICLE' => "Je kan geen afgestemd voertuig verkopen, verkoop eerst je afstemmingen!",
            'TUNE_ITEM_IN_POSSESSION' => "Je hebt deze afstemmingen al ge&Iuml;nstalleerd! Verkoop eerst je actieve afstemming.",
            'TUNE_ITEM_NOT_IN_POSSESSION' => "Je hebt deze afstemmingen niet in je bezit.",
            'BUY_VEHICLE_TUNE_ITEM_SUCCESS' => "Je hebt nieuwe {itemName} {type} ge&Iuml;nstalleerd voor $&#8203;{costs}.",
            'SELL_VEHICLE_TUNE_ITEM_SUCCESS' => "Je hebt je {itemName} {type} verkocht voor $&#8203;{price}.",
            /** FAMILY GARAGE LANGS HERE: **/
            'NO_VEHICLES_IN_FAMILY_GARAGE' => "Er staan geen voertuigen in jullie familiegarage.",
            'SELECT_ONE_OR_MORE_VEHICLES' => "Selecteer één of meerdere voertuigen!",
            'SELL_FAMILY_VEHICLES_SUCCESS' => "Je hebt alle geselecteerde voertuigen verkocht voor $&#8203;{money}.",
            'NOT_ENOUGH_CRUSH_CONVERT_CAPACITY' => "Jullie hebben niet genoeg crush of convert capaciteit over. Koop er meer bij de <a href='".$route->getRouteByRouteName('family-garage-crusher-converter')."'><strong>crusher & converter</strong></a>.",
            'CRUSH_CONVERT_FAMILY_VEHICLES_SUCCESS' => "Je hebt alle geselecteerde voertuigen gecrushed en geconvert! {bullets} kogels werden toegevoegd aan de familie opslagplaats.",
            'CRUSH_CONVERT_FAMILY_VEHICLES_CAP_SUCCESS' => "Je hebt enkele geselecteerde voertuigen gecrushed en geconvert! {bullets} kogels werden toegevoegd aan de familie opslagplaats. {unhandled} voertuigen werden niet behandeld koop nieuwe <a href='".$route->getRouteByRouteName('family-garage-crusher-converter')."'><strong>crusher & converter</strong></a> cpaciteit.",
            'SMALL' => "Klein",
            'MEDIUM' => "Middel",
            'LARGE' => "Groot",
            'ITEM_DOESNT_EXIST' => $marketLangs['MARKET_ITEM_DOESNT_EXIST'],
            'FAMILY_BOUGHT_ITEM_ALREADY' => "Jullie familie heeft dit al aangekocht!",
            'FAMILY_CRUSHER_BOUGHT' => "Je hebt een nieuwe crusher gekocht voor de familie. De familie heeft $&#8203;{price} betaald, jullie kunnen nu {capacity} voertuigen crushen!",
            'FAMILY_CONVERTER_BOUGHT' => "Je hebt een nieuwe converter gekocht voor de familie. De familie heeft $&#8203;{price} betaald, jullie kunnen nu {capacity} voertuigen converten!",
            'FAMILY_HAS_NO_CRUSHER' => "De familie heeft nog geen crusher.<br />De baas of bankbeheerder kan er één kopen.",
            'FAMILY_HAS_NO_CONVERTER' => "De familie heeft nog geen converter.<br />De baas of bankbeheerder kan er één kopen.",
            'FAMILY_CAN_CRUSH_X_VEHICLES' => "De familie kan nog <strong>{capacity}</strong> voertuigen crushen.",
            'FAMILY_CAN_CONVERT_X_VEHICLES' => "De familie kan nog <strong>{capacity}</strong> voertuigen converten."
        );
        return $langs;
    }
    
    public function streetraceLangs()
    {
        $str = "Streetrace";
        $famCrimeLangs = $this->familyCrimeLangs();
        $langs = array(
            'TITLE' => ucfirst($str),
            'DESCRIPTION' => "Er zijn 4 soorten ".strtolower($str)."s: Highway, Route66, Drift Race en City Race.<br />Voor elk soort race moet je auto goed zijn op andere punten.<br /><br />De winnaar van de ".strtolower($str)." krijgt 3 keer de inzet, en de nummer 2 krijgt zijn inzet terug.<br />De nummer 3 en 4 verliezen hun geld.",
            'ORGANIZE' => $famCrimeLangs['ORGANIZE'],
            'PARTICIPANTS' => $famCrimeLangs['PARTICIPANTS'],
            'JOIN' => $famCrimeLangs['JOIN'],
            'LEAVE' => $this->familyLangs()['LEAVE'],
            'QUIT' => $this->familyRaidLangs()['QUIT'],
            'RESULTS' => "Uitslag",
            'NO_VEHICLE_TO_RACE' => "Je hebt geen voertuig beschikbaar in jouw garages.",
            'ALREADY_PART_OF_RACE' => "Je doet al mee aan een ".strtolower($str)."!",
            'NO_PART_OF_RACE' => "Je doet nog niet mee aan een ".strtolower($str)."!",
            'INVALID_RACE' => "Je hebt een ongeldige ".strtolower($str)." opgegeven!",
            'INVALID_RACE_TYPE' => "Je hebt een ongeldig race type opgegeven!",
            'INVALID_STAKE' => "Je hebt een ongeldige inzet gekozen!",
            'INVALID_VEHICLE' => "Je hebt een onbekend voertuig gekozen!",
            'RACE_ALREADY_FULL' => "Deze ".strtolower($str)." zit al vol!",
            'RACE_NOT_READY_YET' => "Deze ".strtolower($str)." is nog niet klaar om te starten!",
            'ORGANIZE_RACE_SUCCESS' => "Je hebt een ".strtolower($str)." gestart!",
            'JOIN_RACE_SUCCESS' => "Je doet nu mee aan deze ".strtolower($str)."!",
            'QUIT_RACE_SUCCESS' => "Je hebt deze ".strtolower($str)." gestopt!",
            'LEAVE_RACE_SUCCESS' => "Je bent uit de ".strtolower($str)." gestapt!",
            'RACE_SUCCESS_LOST_NTH' => "Je bent {nth} geworden in de ".strtolower($str)." en je hebt je inzet verloren!",
            'RACE_SUCCESS_EVEN_SECOND' => "Je bent tweede geworden in de ".strtolower($str)." en je hebt je inzet teruggekregen!",
            'RACE_SUCCESS_WON_FIRST' => "Je bent eerste geworden in de ".strtolower($str)." en je hebt $&#8203;{price} gewonnen!"
        );
        return $langs;
    }
    
    public function familyLangs() // Family list  ,    page, bank  ,    tiny bit family garage (rest in garage langs)   &    management
    {
        global $route;
        $langs = array(
            'BOSS' => "Baas",
            'BANKMANAGER' => "Bankbeheerder",
            'UNDERBOSS' => "Onderbaas",
            'ALLIANCES' => "Allianties",
            'JOIN' => "Joinen",
            'LEAVE' => "Verlaten",
            'LEAVE_COSTS' => "Verlaatkosten",
            'INTEREST' => "Rente",
            'MEMBERS_AMOUNT' => "Aantal leden",
            'SEARCH_FAMILY' => "Familie opzoeken..",
            'BULLET_FACTORY' => ucwords($this->bulletFactoriesLangs()['BULLET_FACTORY']),
            'BROTHEL' => $this->familyPropertiesLangs()['BROTHEL'],
            'FAMILY_HAS_NO_MISSIONS' => "Geen behaalde missies.",
            'FAMILY_HAS_NO_ALLIANCES' => "Geen allianties met andere families.",
            'FAMILY_HAS_NO_PROPERTIES' => "Deze familie heeft geen eigendommen.",
            'FAMILY_HAS_NO_PROFILE' => "Deze familie heeft geen familie profiel.",
            'FAMILY_DOESNT_EXIST' => "Deze familie bestaat niet!",
            'NO_JOINS_ALLOWED' => "Deze familie heeft joinen uitgeschakeld.",
            'ALREADY_ATTEMPTED_JOIN' => "Je hebt reeds geprobeert om deze familie te joinen, wacht a.u.b. op een reactie van de familie baas/bazen.",
            'ALREADY_INVITED_TO_FAMILY' => "Deze familie heeft je al uitgenodigd om hen te joinen, ga a.u.b. naar <a href='/game/family-invitations'><strong>Familie Uitnodigingen</strong></a> om hun verzoek te accepteren.",
            'USER_ALREADY_ATTEMPTED_JOIN' => "Dit lid heeft reeds gejoind, je kan deze hier bovenaan accepteren.",
            'USER_ALREADY_INVITED_TO_FAMILY' => "Dit lid is al eerder uitgenodigd voor de familie.",
            'ALREADY_PART_OF_A_FAMILY' => "Verlaat je huidige familie om een andere te kunnen joinen.",
            'FAMILY_JOINED_SUCCESSFUL' => "Je hebt gejoind bij de familie <strong>{familyName}</strong>, nu moet je afwachten op een reactie van de familie baas of onderbaas.",
            'FAMILY_BOSS_CANNOT_LEAVE' => "Je kan de familie niet verlaten als hun baas, <a href='".$route->getRouteByRouteName('family-management-manage-family')."'><strong>klik hier</strong></a> om de familie te beheren.",
            'LEFT_FAMILY_SUCCESSFUL' => "Je hebt {familyName} verlaten voor een bedrag van $&#8203;{leaveCosts}.",
            'NO_FAMILIES_FOUND' => "Geen families gevonden, probeer a.u.b. opneieuw met een andere zoekterm.",
            'FAMILY_SEARCH_SUCCESSFUL' => "Zoeken voltooid, volgende familie(s) werden gevonden:",
            'SEED_MONEY' => "Startkapitaal",
            'CREATE_FAMILY_INFO' => "Als je deel uitmaakt van een familie kan je alle familie voordelen optimaal benutten! Je moet minimaal Legendary Godfather met alle levels 10+ zijn om een familie op te kunnen starten.",
            'CREATE_FAMILY_WARNING' => "Families zullen uitsterven wanneer alle spelers in een familie meer dan 24u dood zijn, alle behaalde familie vorderingen zullen hierdoor verdwijnen. Maak alleen zelf een familie aan als je weet dat deze actief zal spelen. Je kan altijd een bestaande familie die leden toelaat joinen.",
            'FAMILYNAME_ALREADY_TAKEN' => "Deze familie naam is reeds bezet.",
            'INVALID_FAMILYNAME' => "Een familie naam kan alleen letters, getallen of een streepje bevatten, minimaal 1 letter. 3-15 tekens.",
            'CANNOT_CREATE_FAMILY' => "Je voldoet niet aan de benodigdheden om een familie te starten, train verder en probeer later opnieuw.",
            'CREATE_FAMILY_SUCCESS' => "Je hebt een familie genaamd <strong>{familyName}</strong> opgericht met een startkapitaal van <strong>$&#8203;{seedMoney}</strong>",
            'FAMILY_PAGE_HEADING' => "Familie pagina van {familyName}",
            'DONATE_FAMILY_SUCCESS' => "Je hebt <strong>$&#8203;{amount}</strong> gedoneerd aan je familie min enkele transactiekosten.",
            'USER_NOT_INSIDE_FAMILY' => "Dit lid zit niet in jouw familie.",
            'USER_ALREADY_IN_DIFFERENT_FAMILY' => "Dit lid zit al in een andere familie.",
            'NOT_ENOUGH_MONEY_FAMBANK' => "Er staat niet genoeg geld op je familie bank.",
            'NO_RIGHTS_FAMILY_BANK' => "Je hebt geen bank beheer rechten voor jouw familie.",
            'NO_RIGHTS_FAMILY_MANAGEMENT' => "Je hebt geen rechten om deze familie te beheren.",
            'BANK_TRANSFER_FAMILY_SUCCESS' => "Je hebt <strong>$&#8203;{amount}</strong> overgeschreven naar de bank van <strong>{user}</strong>.",
            'JOINED_MEMBERS' => "Gejoinde leden",
            'INVITED_MEMBERS' => "Uitgenodigde leden",
            'KICK_MEMBERS' => "Leden kicken",
            'INVITE_MEMBERS' => "Leden uitnodigen",
            'JOIN_POLICY' => "Join beleid",
            'MEMBERS_MAY_JOIN' => "Leden <u>mogen</u> joinen.",
            'MEMBERS_MAY_NOT_JOIN' => "Leden <u>mogen niet</u> joinen.",
            'INVITE' => "Uitnodigen",
            'NO_JOINED_MEMBERS_YET' => "Er heeft nog niemand de familie gejoind tot nu toe.",
            'NO_INVITED_MEMBERS_YET' => "Er is nog niemand uitgenodigd voor de familie tot nu toe.",
            'NO_BANK_LOGS_TO_VIEW' => "Geen bank logboeken om weer te geven.",
            'USER_DID_NOT_JOIN' => "Dit lid heeft de familie niet gejoind.",
            'HANDLE_JOIN_SUCCESS' => "Je hebt {user} {commitedAction}.",
            'USER_NOT_INVITED' => "Dit lid werd niet uitgenodigd voor de familie",
            'HANDLE_INVITE_SUCCESS' => "Je hebt de uitnodiging voor {user} {commitedAction}.",
            'KICK_FAMILY_MEMBER_SUCCESS' => "Je hebt {user} uit de familie gekicked.",
            'INVITE_FAMILY_MEMBER_SUCCESS' => "Je hebt {user} uigenodigd om de familie te joinen.",
            'CHANGE_JOINPOLICY_SUCCESS' => "Je hebt het join beleid aangepast, de familie is nu {openclosed}.",
            'OPEN' => "Open",
            'CLOSED' => "Gesloten",
            'IMAGE' => "Afbeelding",
            'UPLOAD_FAMILY_IMAGE_WRONG_FILE' => "Een familie {type} uploaden kan alleen als de extensie .jpg, .png of .gif is.",
            'UPLOAD_FAMILY_IMAGE_SUCCESS' => "Je hebt een nieuwe familie {type} ge-upload.",
            'UPLOAD_FAMILY_IMAGE_FAILED' => "Er is een fout opgetreden bij het uploaden van een nieuwe familie {type}, probeer a.u.b. opnieuw!",
            'FAMILY_PROFILE_TO_LONG' => "Je familie profiel mag niet langer zijn dan {max} karakters.",
            'FAMILY_MESSAGE_TO_LONG' => "Je familie bericht mag niet langer zijn dan 10,000 karakters.",
            'UPDATE_FAMILY_PROFILE_SUCCESS' => "Je hebt je familie profiel aangepast!",
            'UPDATE_FAMILY_MESSAGE_SUCCESS' => "Je hebt je familie bericht aangepast!",
            'NO_FAMILY_INVITATIONS_YET' => "Je hebt momenteel nog geen familie uitnodigingen.",
            'ALREADY_IN_DIFFERENT_FAMILY' => "Je bent reeds lid bij een andere familie, verlaat deze familie eerst.",
            'INVALID_FAMILY_INVITATION' => "Je hebt een ongeldige uitnodiging geselecteerd.",
            'HANDLE_INVITATION_SUCCESS' => "Je hebt de uitnodiging van de familie {family} {committedAction}!{add}",
            'NOW_MEMBER_OF_FAMILY' => " Je bent nu een lid van de familie.", /** additional {add} of above^ **/
            'NO_FAMILY_MESSAGE' => "Er is geen familie bericht beschikbaar.",
            'ABOLISH' => "Opheffen",
            'ABOLISH_FAMILY_AND_PROGRESS' => "Wil je de familie en alle huidige vooruitgang verwijderen?",
            'ABOLISH_FAMILY_CONFIRM' => "Ben je zeker dat je de volledige familie wil opheffen? <strong>{username}</strong> zal de familie gaan missen!<br /><br /><form id='abolishFamilyConfirm' class='ajaxForm' method='POST' action='". $route->getAjaxRouteByRouteName('abolish-family') ."' data-response='#abolishFamilyResponse'><input type='hidden' name='security-token' value='{securityToken}'/><input type='submit' class='btn button alert-btn' name='abolish-confirm' value='Opheffen!'/>&nbsp;<button type='button' class='button alert-btn' data-dismiss='alert' aria-hidden='true'>".$this->langMap['CANCEL']."</button></form>",
            'ABOLISH_FAMILY_SUCCESS' => "Je hebt de familie en al jullie vooruitgang verwijderd.",
            'UNLIMITED' => "Ongelimiteerd",
            'UP_TO' => "Tot",
            'EACH_DAY' => "per dag",
            'FAMILY_MANAGEMENT_ONLY_BOSS' => "Alleen de familie baas kan dit deel van de instellingen beheren.",
            'USER_ALREADY_IN_FAMILY_TOP' => "Dit lid heeft reeds een status in de familie top.",
            'FAMILY_BOSS_REQUIRED' => "Een familie baas is verplicht, gelieve de optie hieronder te gebruiken om je familie op te heffen!",
            'FAMILY_NEEDS_VIP_STATUS' => "De familie heeft hiervoor een VIP status nodig. <a href='".$route->getRouteByRouteName('donation-shop')."'><strong>Klik hier</strong></a> om een VIP familie status aan te kopen.",
            'FAMILY_BOSS_CHANGE_CONFIRM' => "Ben je zeker dat je het familie leiderschap wil overdragen naar <strong>{username}</strong>? Je zal alle controle over de familie verliezen na de overdracht.<br /><br /><form id='manageFamilyBossConfirm' class='ajaxForm' method='POST' action='". $route->getAjaxRouteByRouteName('manage-family-top') ."' data-response='#manageFamilyTopResponse'><input type='hidden' name='security-token' value='{securityToken}'/><input type='hidden' name='boss-confirm' value='{username}'/><input type='submit' class='btn button alert-btn' name='boss-change-confirm' value='".$this->langMap['TRANSFER']."'/>&nbsp;<button type='button' class='button alert-btn' data-dismiss='alert' aria-hidden='true'>".$this->langMap['CANCEL']."</button></form>",
            'FAMILY_TOP_STATUS_SET_TO_USER' => "Je hebt {username} als nieuwe {statusName} van de familie gemaakt!",
            'FAMILY_TOP_STATUS_SET_EMPTY' => "Je hebt de {statusName} van de familie verwijderd! Deze status is terug leeg.",
            'ALLIANCE' => "Alliantie",
            'REQUEST' => "Aanvragen",
            'FAMILY_MANAGEMENT_NO_ALLIANCES' => "De familie heeft nog geen actieve allianties of alliantie verzoeken.",
            'ALREADY_PENDING_OR_ALLIANCE' => "Jullie hebben reeds een actieve / in behandeling alliantie met deze familie.",
            'NO_ALLIANCE_WITH_SELF' => "Je kan geen alliantie aangaan met je eigen familie!",
            'FAMILY_ALLIANCE_INVITE_SUCCESS' => "Je hebt de familie {family} een aanvraag verzonden om een alliantie te sluiten.",
            'ALREADY_ACTIVE_ALLIANCE' => "Jullie hebben al een alliantie met deze familie.",
            'NO_PENDING_OR_ALLIANCE' => "Jullie hebben geen actieve / in behandeling alliantie met deze familie.",
            'FAMILY_HANDLE_ALLIANCE_SUCCESS' => "Je hebt de familie alliantie met {family} {type}!",
            'BETWEEN_0_AND_100K' => "Je moet een bedrag opgeven tussen de $0 en de $100,000.",
            'MANAGE_LEAVE_COSTS_SUCCESS' => "Je hebt de verlaatkosten ingesteld op $&#8203;{costs}.",
            'SEND_FAMILY_MASS_MESSAGE_SUCCESS' => "Je hebt een massabericht verzonden naar al je familieleden..",
            'NO_DONATIONS_TO_VIEW' => $this->bankLangs()['NO_DONATIONS_TO_VIEW']
        );
        return $langs;
    }
    
    public function familyPropertiesLangs()
    {
        $bfLangs = $this->bulletFactoriesLangs();
        $langs = array(
            'BULLET_FACTORY' => ucwords($bfLangs['BULLET_FACTORY']),
            'BROTHEL' => "Bordeel",
            'FAMILY_BF_IS_CURRENTLY' => "De familiale kogelfabriek is op het moment aan het",
            'PRODUCING' => $bfLangs['PRODUCING'],
            'DORMANT' => "Stilstaan",
            'PRODUCE' => "Produceren",
            'HOUR' => "Uur",
            'PROFIT' => $this->stockExchangeLangs()['PROFIT'],
            'ADD' => "Toevoegen",
            'TAKE_AWAY' => $this->redLightDistrictLangs()['TAKE_AWAY'],
            'HAS_NO_PROPERTY_TYPE' => "De familie heeft nog geen {property}, de baas of bankbeheerder kan er een kopen.",
            'NO_RIGHTS_FAMILY_PROPERTY' => "Je hebt geen rechten om eigendommen te beheren.",
            'INVALID_PROPERTY' => "Ongeldige eigendom geselecteerd!",
            'ALREADY_OWN_PROPERTY' => "Dit eigendom bezit je al!",
            'CANNOT_UPGRADE_PROPERTY' => "Dit eigendom kan je niet upgraden!",
            'BOUGHT_PROPERTY_SUCCESS' => "Je hebt een {property} aangekocht voor de familie!",
            'UPGRADE_PROPERTY_SUCCESS' => "Je hebt de familiale {property} ge-upgrade voor $&#8203;{price}.",
            'INVALID_PRODUCTION' => "Je hebt een ongeldige productie eenheid geselecteerd.",
            'SET_BF_PRODUCTION_SUCCESS' => "Je hebt de kogelfabriek productie ingesteld op {production} kogels per uur.",
            'BETWEEN_1_AND_999K_BULLETS' => "Je moet tussen de 1 en de 999,999 kogels invoeren!",
            'USER_NOT_ENOUGH_BULLETS' => "Zoveel kogels heb je niet!",
            'NOT_ENOUGH_SPACE_BF' => "Aantal overschrijd de capaciteit van de kogelfabriek!",
            'DONATE_BULLETS_SUCCESS' => "Je hebt {bullets} kogels gedoneerd naar de kogelreserve van de familie.",
            'NOT_ENOUGH_FAMILY_BULLETS' => "De familie heeft niet zoveel kogels!",
            'BETWEEN_100_AND_100K_BULLETS' => "Je moet tussen de 100 en de 100,000 kogels invoeren!",
            'SEND_FAMILY_BULLETS_SUCCESS' => "Je hebt {bullets} kogels verzonden naar {user}.",
            'BETWEEN_1_AND_99K_WHORES' => "Je moet tussen de 1 en de 99,999 hoeren invoeren!",
            'NOT_ENOUGH_SPACE_BROTHEL' => "Aantal overschrijd de capaciteit van het bordeel!",
            'ADD_WHORES_SUCCESS' => "Je hebt {whores} straat hoeren toegevoegd aan het familie bordeel.",
            'TAKE_AWAY_WHORES_SUCCESS' => "Je hebt {whores} hoeren weggestuurd vanuit het familie bordeel terug naar de straten.",
        );
        return $langs;
    }
    
    public function familyCrimeLangs()
    {
        $famLangs = $this->familyLangs();
        global $route;
        $langs = array(
            'NO_FAMILY_GARAGE' => "Jullie hebben geen familie garage om familie misdaden te plegen.",
            'NO_FAMILY_GARAGE_WITH_LINK' => "Jullie hebben geen familie garage om familie misdaden te plegen, de baas of onderbaas kan er <a href='".$route->getRouteByRouteName('family-garage')."'><strong>hier</strong></a> een kopen.",
            'PARTICIPANTS' => "Deelnemers",
            'CAR_FAIR' => "Autobeurs",
            'ORGANIZE' => "Organiseren",
            'LEAVE' => $famLangs['LEAVE'],
            'JOIN' => "Deelnemen",
            'NO_FAMILY_CRIMES_FOUND' => "Er zijn geen familie misdaden in voorbereiding te vinden.",
            'INVALID_PARTICIPANTS_SELECTED' => "Je hebt een ongeldig aantal deelnemers geselecteerd.",
            'INVALID_CRIME_SELECTED' => "Je hebt een ongeldige misdaad geselecteerd.",
            'ALREADY_INSIDE_FAMILY_CRIME' => "Je bent reeds deelnemer in een georganiseerde misdaad, verlaat deze eerst.",
            'ORGANIZE_FAMILY_CRIME_SUCCESS' => "Je hebt een {crime} georganiseerd voor jouw en je familie leden in {state}.",
            'FAMILY_CRIME_FULL' => "Deze familie misdaad lijkt vol te zitten.",
            'NOT_INSIDE_SAME_STATE' => "Je bent niet in dezelfde staat als de familie misdaad.",
            'NOT_PART_OF_FAMILY_CRIME' => "Je maakt geen deel uit van deze familie misdaad!",
            'MEMBER_NOT_READY' => "{user} is nog niet klaar en moet nog {waitingTime} seconden wachten.",
            'ONE_OR_MORE_IN_PRISON' => "Eén of meerdere familie leden zitten in de gevangenis.",
            'NOT_ENOUGH_MEMBERS_YET' => "Er zijn te weinig leden om deze misdaad te plegen.",
            'JOINED_FAMILY_CRIME' => "Je doet nu mee aan deze familie misdaad!",
            'LEFT_FAMILY_CRIME' => "Je hebt deze familie misdaad verlaten!",
            'LEFT_FAMILY_CRIME_AS_LAST' => "Je bent uit de familie misdaad gestapt! Omdat je de laatste deelnemer was is de misdaad verwijderd!",
            'FAMILY_CRIME_FAILED' => "De familie misdaad is mislukt!",
            'ARE_ALSO_IMPRISONED' => "zijn ook nog eens opgepakt!",
            'FAMILY_CRIME_SUCCESS' => "De familie misdaad is gelukt, julie hebben een {carName} met {damage}% schade gestolen!<br /><br /><img src='{imageSrc}' class='middle' alt='{carName}'/>",
            'FAMILY_CRIME_SUCCESS_GARAGE_FULL' => "De familie misdaad is gelukt, jullie hebben een {carName} met {damage}% damage gestolen &amp; verkocht voor $&#8203;{price} wegens een volle garage!<br /><br /><img src='{imageSrc}' class='middle' alt='{carName}'/>",
            'FAMILY_CRIME_SUCCESS_MULTIPLE' => "De familie misdaad is gelukt, volgende voeruigen werden gestolen:<br />{add}<br /><br /><br />{add2}<br /><br /><br />{add3}",
            'FAMILY_CRIME_MULTIPLE_ADD' => "een {carName} met {damage}% schade!<br /><br /><img src='{imageSrc}' class='middle' alt='{carName}'/>",
            'FAMILY_CRIME_MULTIPLE_GARAGE_FULL_ADD' => "een verkochte {carName} met {damage}% schade voor $&#8203;{price} wegens een volle garage<br /><br /><img src='{imageSrc}' class='middle' alt='{carName}'/>",
        );
        return $langs;
    }
    
    public function familyRaidLangs()
    {
        $famCrimeLangs = $this->familyCrimeLangs();
        $crimesLangs = $this->crimesLangs();
        global $route;
        $langs = array(
            'NOT_IN_RAID_INFO' => "Je doet nog niet mee aan een overval!<br />Hieronder kan je een overval organiseren.",
            'LEADER' => $crimesLangs['LEADER'],
            'DRIVER' => $crimesLangs['DRIVER'],
            'BOMB' => "Bom",
            'ORGANIZE' => $famCrimeLangs['ORGANIZE'],
            'READY' => $crimesLangs['READY'],
            'WAITING' => "Wachten",
            'QUIT' => "Stoppen",
            'BULLETS_INFO' => "$50 per kogel, tussen 50 en 1,000 kogels!",
            'RAID_TYPE_NOT_AVAILABLE' => "De geselecteerde {type} is momenteel niet beschikbaar.",
            'RAID_TYPE_NOT_IN_FAMILY' => "De geselecteerde {type} zit niet in de familie!",
            'ALREADY_INSIDE_FAMILY_RAID' => "Je bent reeds een deelnemer in een overval, verlaat deze eerst.",
            'SELECTED_USER_MULTIPLE_TIMES' => "Je hebt een lid meerdere keren geselecteerd!",
            'ORGANIZE_FAMILY_RAID_SUCCESS' => "Je hebt een nieuwe familie overval gestart, wacht tot je groep klaar is.",
            'NOT_PARTICIPATING_IN_RAID' => "Je doet niet mee aan een overval!",
            'VEHICLE_NOT_IN_CURRENT_GARAGE' => $crimesLangs['VEHICLE_NOT_IN_CURRENT_GARAGE'],
            'NOT_IN_SAME_STATE_AS_RAID' => "Je moet in de staat <strong>{state}</strong> zijn om deel te kunnen nemen aan deze overval.",
            'ALREADY_PREPARED' => "Je hebt je reeds klaargemaakt vor deze overval!",
            'INVALID_EQUIPMENT' => "Je hebt een ongeldige uitrusting geselecteerd!",
            'QUIT_RAID_MESSAGE' => "Weet je zeker dat je deze overval wilt stoppen? Je zult al je (gekochte) spullen verliezen.<br /><br /><form id='interactFamilyRaid' class='ajaxForm' method='POST' action='". $route->getAjaxRouteByRouteName('interact-family-raid') ."' data-response='#familyRaidResponse'><input type='hidden' name='security-token' value='{securityToken}'/><input type='hidden' name='familyRaidID' value='{frid}'/><input type='submit' name='{typeRaw}-quit-confirm' class='button' value='Overval stoppen'/>&nbsp;<button type='button' class='button alert-btn' data-dismiss='alert' aria-hidden='true'>".$this->langMap['CANCEL']."</button></form>",
            'PREPARE_RAID_TYPE_SUCCESS' => "Je hebt jezelf klaargemaakt als {type} voor deze overval.",
            'DENY_RAID_SUCCESS' => "Je hebt geweigerd deel te nemen aan deze overval.",
            'QUIT_RAID_SUCCESS' => "Je bent uit deze overval gestapt en bent al je (gekochte) spullen kwijt.",
            'ONLY_LEADER_CAN_START_RAID' => "Enkel de leider kan deze overval starten.",
            'RAID_PARTICIPANT_CHANGED' => "Je hebt de deelnemer voor deze overval aangepast.",
            'LEADER_QUIT_RAID_CONFIRM' => "Weet je zeker dat je deze overval wilt stoppen? Je crew zullen alle (gekochte) spullen verliezen.<br /><br /><form id='interactFamilyRaid' class='ajaxForm' method='POST' action='". $route->getAjaxRouteByRouteName('interact-family-raid') ."' data-response='#familyRaidResponse'><input type='hidden' name='security-token' value='{securityToken}'/><input type='hidden' name='familyRaidID' value='{frid}'/><input type='submit' name='quit-confirm' class='button' value='Overval stoppen'/>&nbsp;<button type='button' class='button alert-btn' data-dismiss='alert' aria-hidden='true'>".$this->langMap['CANCEL']."</button></form>",
            'LEADER_QUIT_RAID_SUCCESS' => "Je hebt deze famili overval stopgezet, jij en je crew kunnen een nieuwe overval beginnen.",
            'RAID_NOT_READY_TO_START' => "Niet alle deelnemers zijn klaar om deze overval te starten.",
            'ONE_OR_MORE_IN_PRISON' => $famCrimeLangs['ONE_OR_MORE_IN_PRISON'],
            'START_FAMILY_RAID_SUCCESS' => "Jullie hebben de bank van <strong>{state}</strong> beroofd!<br />Jullie hebben <strong>$&#8203;{stolenAmount}</strong> gestolen, dat is <strong>$&#8203;{stolenEach}</strong> per persoon!",
            'START_FAMILY_RAID_FAIL' => "De overval op de bank van <strong>{state}</strong> is <span class='crimson'><strong>mislukt</strong></span>!",
            'HIRE_VEHICLE' => "Voertuig huren (+$15,000)",
            'HIRED_VEHICLE' => "Gehuurd voertuig",
        );
        return $langs;
    }
    
    public function familyMercenariesLangs()
    {
        $langs = array(
            'BOUGHT' => "Gekocht",
            'AVAILABLE' => "Beschikbaar",
            'USED' => "Gebruikt",
            'BUYER' => "Aankoper",
            'MERCENARIES_INFO' => "Huurlingen kosten $&#8203;{price} per huurling en kunnen alle familie leden bijstaan in familie misdaden.",
            'NO_MERCENARIES_BOUGHT' => "De familie heeft nog geen huurlingen aangekocht.",
            'BUY_BETWEEN_2_AND_999_MERCENARIES' => "Je kan maar tussen de 2 en 999 huurlingen aankopen per keer!",
            'BOUGHT_MERCENARIES_SUCCESS' => "Je hebt {mercenaries} huurlingen aangekocht voor $&#8203;{price}."
        );
        return $langs;
    }
    
    public function onlineToplistLangs()
    {
        $langs = array(
            'NO_MEMBERS_ONLINE' => "Er zijn geen leden online.",
            'NO_FAM_MEMBERS_ONLINE' => "Er zijn geen familie leden online.",
            'NO_TEAM_MEMBERS_ONLINE' => "Er zijn geen team leden online.",
            'SEARCH_BY_RANK' => "Zoeken op rank",
            'SEARCH_PLAYER' => "Speler opzoeken..",
            'NO_PLAYERS_FOUND' => "Geen spelers gevonden, probeer a.u.b. opnieuw met een andere zoekterm.",
            'USER_SEARCH_SUCCESSFUL' => "Zoeken voltooid, volgende speler(s) werden gevonden:",
            'BLOCK_VIEW' => "Grid weergave",
            'LIST_VIEW' => "Lijst weergave",
        );
        return $langs;
    }
    
    public function informationLangs()
    {
        global $route;
        $langs = array(
            'STATISTICS' => "Statistieken",
            'RULES' => "Regels",
            'THANKS_TO_ALL_DONATORS' => "Graag bedanken wij alle betalende spelers! Zonder hun kon ".$route->settings['gamename']." niet bestaan.",
            'AMOUNT_OF_MEMBERS' => "Ledenaantal",
            'TOTAL_CASH_MONEY' => "Totaal contant geld",
            'TOTAL_BANK_MONEY' => "Totaal bank geld",
            'TOTAL_MONEY' => "Totaal geld",
            'AVERAGE_MONEY_MEMBER' => "Gemiddeld geld per lid",
            'AMOUNT_OF_FAMILIES' => "Aantal families",
            'TOTAL_MEMBER_BULLETS' => "Totaal leden kogels",
            'AVERAGE_BULLETS_MEMBER' => "Gemiddelde kogels per lid",
            'DEAD_MEMBERS_NOW' => "Dode leden op dit moment",
            'TOTAL_BANNED_PLAYERS' => "Totaal verbannen leden",
            'RICHEST' => "Rijkste",
            'PLAYER' => "Speler",
            'MOST_HONORABLE' => "Eervolste",
            'NEWEST' => "Nieuwste",
            'MOST_SUCCESSFUL_BUSTS' => "Meeste gevangenis uitbraken",
            'MOST_STOLEN_VEHICLES' => "Meeste voertuigen gestolen",
            'MOST_SUCCESSFUL_CRIMES' => "Meeste gelukte misdaden",
            'MOST_WHORES_PIMPED' => "Meeste hoeren gepimpt",
            'MOST_UNITS_SMUGGLED' => "Meeste goederen gesmokkeld",
            'POPULATION' => "Bevolking",
            'MOST_REFERRAL_PROFITS' => "Meeste referral opbrengsten",
            'VIEWING_ROUND_FROM' => "Je bekijkt de hall of fame vanaf ronde",
            'CURRENT' => $this->murderLangs()['CURRENT'],
            'ROUND_PLAYED_FROM' => "Deze ronde werd gepeeld van",
            'NOW' => "Nu", // Override
            'TO' => "T.e.m.", // Override
            'OF_WHICH_ARE_BANNED' => "Ervan zijn verbannen"
        );
        return $langs;
    }
    
    public function ranksScoreLangs()
    {
        global $route;
        $langs = array(
            'WHO_CAN_I_ATTACK' => "Wie kan ik aanvallen?",
            'OPTIONS' => "Opties",
            'REQUIREMENTS' => "Eisen",
            'SHADOW' => "Schaduw",
            'WITHOUT_PROTECTION' => "Zonder bescherming",
            'RANKS_SCORE_INFO' => "<p>* Max. = maximaal, Min. = minimaal.</p><p>Wordt jij aangevallen, dan kan je altijd minstens eenmalig terug aanvallen binnen de 24u, ongeacht je rank.</p><p>Scums die langer dan 5 dagen niet online gekomen zijn kan je altijd aanvallen ongeacht je rank!</p><p>Dode spelers die langer dan 7 dagen niet online zijn gekomen worden iedere nacht terug tot leven gewekt.</p><p>De persoonlijke score wordt elk uur gecontroleerd en bijgewerkt.<br />De score is gebaseerd op jouw aantal hoeren, eerpunten, kills, landjes op de plattegrond en je rank.<br />Hoe meer je er hebt hoe meer score je ontvangt, je kan jouw score per uur controlleren op de <a href='". $route->getRouteByRouteName('status') ."'><strong>status pagina</strong></a>.</p><p>Iedere <a href='". $route->getRouteByRouteName('gym') ."'><strong>sportschool</strong></a> wedstrijd levert altijd 25 of meer score op.<br />Daarnaast kan ook nog af en toe score verzameld worden met <a href='". $route->getRouteByRouteName('daily-challenges') ."'><strong>daily challenges</strong></a> & missies >&nbsp;<a href='". $route->getRouteByRouteName('missions-public-mission') ."'><strong>publieke missie</strong></a>.</p>",
        );
        return $langs;
    }
    
    public function settingsLangs()
    {
        global $route;
        $langs = array(
            'EMAIL_UNKNOWN_IP_DETECTED' => "Je kan je email adres niet veranderen omdat je IP nog niet als veilig word erkend. Dit kan tot 24 uur duren nadat een nieuw IP-adres is gedetecteerd.",
            'CHANGE_EMAIL_NEED_TO_VERIFY' => "We hebben je een email verstuurd met verdere instructies naar je oude email adres, kleine tip: {coveredEmail}<br />Pas op! De link om uw email aan te passen zal vervallen binnen 2 uren vanaf nu.",
            'SAME_EMAIL_NO_CHANGE' => "Je kan het email adres niet veranderen omdat dit het huidige email adres is.",
            'CHANGE_EMAIL_DEACTIVATE_PRIVATEID' => "Om je email adres te wijzigen moet eerst je PrivateID gedeactiveerd worden. Na de email wijziging kan je een nieuwe PrivateID genereren.",
            'CHANGE_EMAIL_MESSAGE' => "Beste {username}<br /><br />We hebben een aanvraag ontvangen om je email adres te veranderen in: <strong>{newEmail}</strong><br />Indien je zelf deze aanvraag niet hebt gedaan raden we je aan om je wachtwoord zo snel mogelijk te veranderen om problemen te voorkomen.<br /><br />Om je email adres aan te passen moet je op volgende URL drukken of deze kopie&euml;ren naar je adres balk: <a href='".PROTOCOL.strtolower($route->settings['domain'])."/change-email/key/{key}'>".PROTOCOL.strtolower($route->settings['domain'])."/change-email/key/{key}</a><br />Daarna volg je de instructies op het scherm.<br />Indien je op een niet gevonden pagina beland dan is bovenstaande link verlopen. Je kan een nieuwe aanvraag doen in-game op ".strtolower($route->settings['domain'])."<br /><br /><br />Met vriendelijke groeten<br />".ucfirst($route->settings['domainBase']),
            'CHANGE_EMAIL_SUBJECT' => "Email adres aanpassen op ".$route->settings['gamename']."",
            'TESTAMENT_NOT_FOR_OWN' => "Je kan je testament niet overdragen naar je eigen account!",
            'CHANGE_TESTAMENT_SUCCESS' => "Je hebt je testament overgedragen naar <strong>{username}</strong>!",
            'UPLOAD_AVATAR_WRONG_FILE' => "Een avatar uploaden kan alleen als de extensie .jpg, .png of .gif is.",
            'UPLOAD_AVATAR_FAILED' => "Er is een fout opgetreden bij het uploaden van een nieuwe avatar, probeer a.u.b. opnieuw!",
            'UPLOAD_AVATAR_SUCCESS' => "Je avatar werd succesvol ge-upload!",
            'PROFILE_TO_LONG' => "Je profiel mag niet langer zijn dan {max} karakters.",
            'UPDATE_PROFILE_SUCCESS' => "Je hebt je profiel aangepast!",
            'EDIT_EMAIL' => "Email aanpassen",
            'ENCRYPTED' => "Versleuteld",
            'TRANSFER_TESTAMENT' => "Testament overdragen",
            'UPLOAD_AVATAR' => "Avatar Uploaden",
            'EDIT_PROFILE' => "Profiel aanpassen",
            'EDIT_PASSWORD' => "Verander uw wachtwoord",
            'OLD_PASSWORD' => "Huidig wachtwoord",
            'NEW_PASSWORD' => "Nieuw wachtwoord",
            'NEW_PASSWORD_CONFIRM' => "Bevestig nieuw wachtwoord",
            'PASSWORDS_DONT_MATCH' => "De nieuwe wachtwoorden kwamen niet overeen!",
            'OLD_PASSWORD_INCORRECT' => "Het huidige wachtwoord is niet correct, probeer opnieuw.",
            'INVALID_NEW_PASS' => "Je nieuw wachtwoord moet minimaal 6 tekens lang zijn!",
            'PASSWORD_UNKNOWN_IP_DETECTED' => "Je kan je wachtwoord niet veranderen omdat je IP nog niet als veilig word erkend. Dit kan tot 24 uur duren nadat een nieuw IP-adres is gedetecteerd.",
            'PASSWORD_CHANGE_SUCCESS' => "Je wachtwoord werd succesvol aangepast. Om veiligheids redenen hebben we je overal afgemeld.",
            'PASSWORD' => "Wachtwoord",
            'PRIVATEID_GRADE_1' => "4 Karakters - Goed",
            'PRIVATEID_GRADE_2' => "5 Karakters - Zeer goed",
            'PRIVATEID_GRADE_3' => "6 Karakters - Uitstekend",
            'GENERATE' => "Genereren",
            'DEACTIVATE' => "Deactiveren",
            'NOT_ACTIVE' => "Niet actief",
            'PRIVATEID_INFO' => "Met PrivateID kunt u een verborgen gebruikersnaam voor uw account instellen, eenmaal geactiveerd kunt u alleen inloggen met uw PrivateID in het gebruikersnaamveld.<br /><strong>Voorzichtig!</strong> PrivateID is alleen zichtbaar tijdens elke generatie waarna het onomkeerbaar wordt opgeslagen in ons systeem. De laatst gegenereerde PrivateID is altijd de juiste, tenzij deze werd gedeactiveerd.<br /><strong>".strtolower($route->settings['domain'])."/recover-password</strong> wordt gebruikt om een verloren PrivateID te deactiveren. (Uitloggen & email toegang vereist)",
            'PRIVATEID_UNKNOWN_IP_DETECTED' => "Je kan je PrivateID niet wijzigen omdat je IP nog niet als veilig word erkend. Dit kan tot 24 uur duren nadat een nieuw IP-adres is gedetecteerd.",
            'PRIVATEID_ALREADY_ACTIVE' => "Deactiveer je PrivateID vooralleer je een nieuwe genereert.",
            'ACTIVATE_PRIVATEID_SUCCESS' => "Je kan nu enkel inloggen met deze hoofdlettergevoelig verborgen gebruikersnaam: <code><strong>{pid}</strong></code><br />Sla dit niet digitaal op om een hogere veiligheid van uw PrivateID te garanderen.",
            'PRIVATEID_NOT_ACTIVE' => "Je hebt op dit moment geen Private ID actief.",
            'DEACTIVATE_PRIVATEID_SUCCESS' => "Je hebt je PrivateID gedeactiveerd, je kunt weer inloggen met je gewone gebruikersnaam.",
            'PRIVATEID_INCORRECT' => "De PrivateID die u hebt ingevoerd is onjuist.",
        );
        return $langs;
    }
    
    public function messagesLangs()
    {
        $langs = array(
            'MESSAGE_NOT_IN_RANGE' => "Je bericht mag enkel tussen de 2 en de 1,000 karakters lang zijn!",
            'NEW_MESSAGE' => "Nieuw bericht",
            'NO_MESSAGE_TO_SELF' => "Nee! Je kan geen bericht versturen naar jezelf..",
            'LAST_ON' => "Laatst op",
            'NO_MESSAGES_TO_VIEW' => "Geen berichten om weer te geven",
            'WROTE_ON' => "Schreef op",
            'NO_MESSAGES_INFO' => "Geen berichten gevonden in huidige conversatie, wil je een <a href=\"javascript:void(0);\" onclick=\"document.getElementById('new-msg').style.display='block';document.getElementById('new-msg-info').style.display='none';\"><strong>Nieuw bericht</strong></a> versturen naar iemand?",
            'ALL_THESE' => "Al deze",
            'SELECTED' => "Geselecteerde",
            'CONVERSATION' => "Gesprek",
            'MARKUP' => "Opmaak",
            'BOLD' => "Dikdrukken",
            'ITALIC' => "Schuin",
            'UNDERLINE' => "Onderstrepen",
            'STRIKETROUGH' => "Doorstrepen",
            'FONT_SIZE' => "Tekstgrootte",
            'FONT_COLOR' => "Tekstkleur"
        );
        return $langs;
    }
    
    public function notificationsLangs()
    {
        $langs = array(
            'NOTIFICATION' => "Notificatie",
            'NO_NOTIFICATIONS_TO_VIEW' => "Je hebt nog geen notificaties."
        );
        return $langs;
    }
    
    public function friendsBlockLangs()
    {
        global $route;
        $langs = array(
            'BLOCKED' => "Geblokkeerd",
            'BLOCKLIST' => "Blokkeerlijst",
            'INVITE_FRIEND' => "Als vriend uitnodigen",
            'BLOCK_USER' => "Blokkeer gebruiker",
            'ALREADY_FRIENDS_WITH_USER' => "Deze speler staat al in jouw vriendenlijst.",
            'CANNOT_BECOME_FRIENDS' => "Je kan geen vrienden worden met deze speler.",
            'ALREADY_REQUESTED_FRIENDSHIP' => "Je hebt deze speler al gevraagd om vrienden te worden, je moet wachten op een antwoord.",
            'CANNOT_BLOCK_FRIEND' => "Je kan geen vriend blokkeren, verwijder deze vriend eerst.",
            'USER_ALREADY_BLOCKED' => "Je hebt deze gebruiker al geblokkeerd.",
            'USER_MAX_FRIEND_BLOCK' => "Je hebt reeds {max} vrienden / blokkeringen!",
            'INVITE_FRIEND_SUCCESS' => "Je hebt een vriendschapsverzoek vezonden naar <strong>{user}</strong>.",
            'BLOCK_USER_SUCCESS' => "Je heb {user} succesvol geblokkeerd.",
            'DELETE_FRIEND_CONFIRM' => "Ben je zeker dat je <strong>{username}</strong> wilt verwijderen als vriend?<br /><br /><form id='interactFriendsList' class='ajaxForm' method='POST' action='". $route->getAjaxRouteByRouteName('interact-friends-list') ."' data-response='#interactFriendslistResponse'><input type='hidden' name='security-token' value='{securityToken}'/><input type='hidden' name='friend' value='{fid}'/><input type='hidden' name='delete-confirm' value=''/><input type='submit' class='btn button alert-btn' name='delete-confirm-friend' value='".$this->langMap['DELETE']."'/>&nbsp;<button type='button' class='btn button alert-btn' data-dismiss='alert' aria-hidden='true'>".$this->langMap['CANCEL']."</button></form>",
            'FRIEND_DELETED' => "Je hebt <strong>{username}</strong> verwijdert als vriend.",
            'BLOCK_DELETED' => "Je hebt <strong>{username}</strong> verwijdert uit je blokkeerlijst.",
            'FRIEND_REQUEST_ACCEPTED' => "Je hebt het vriendscapsverzoek van <strong>{username}</strong> geaccepteerd.",
            'FRIEND_REQUEST_DENIED' => "Je hebt het vriendschapsverzoek van <strong>{username}</strong> afgewezen."
        );
        return $langs;
    }
    
    public function luckyboxLangs()
    {
        $langs = array(
            'MORE_INFO' => $this->garageLangs()['MORE_INFO'],
            'YOU_HAVE' => "Je hebt",
            'NO_LUCKY_BOX' => "Je hebt geen lucky box.",
            'OPEN_BOX_SUCCESS' => "Je hebt {amount} {prize} gevonden in je box!",
            'PRIZE' => "Beloning",
            'CHANCE' => "Kans",
            'BETWEEN' => "Tussen de",
            'AND' => "En",
            'TO' => "Tot", // Unnecessary but added for same amt. of lines (is translated differently in dutch version)
            'LUCKY_BOX_INFO' => "Lucky boxen verdwijnen als je doodgaat, je kan tot <strong>{boxes} boxen</strong> dragen vooralleer je er geen meer kan verdienen in {gamename}!",
            'PLEASE_CALM_DOWN' => "Doe rustig aan a.u.b.",
        );
        return $langs;
    }
    
    public function captchaTestLangs()
    {
        $langs = array(
            'CAPTCHA_TEST_TITLE' => "Gelieve deze code te verifi&euml;ren voor je doorgaat",
            'NEW_CODE' => "Nieuwe code a.u.b"
        );
        return $langs;
    }
    
    public function restInPeaceLangs()
    {
        $registerLangs = $this->registerLangs();
        $langs = array(
            'YOUR_DEAD' => "Jeetje, je bent dood",
            'RETRY' => "Probeer opnieuw",
            'SELECT_TAG_CHOOSE' => $registerLangs['SELECT_TAG_CHOOSE'],
            'CARJACKER' => $registerLangs['CARJACKER'],
            'PRISON_BREAKER' => $registerLangs['PRISON_BREAKER'],
            'THIEF' => $registerLangs['THIEF'],
            'PIMP' => $registerLangs['PIMP'],
            'BANKER' => $registerLangs['BANKER'],
            'SMUGGLER' => $registerLangs['SMUGGLER'],
            'INVALID_USERNAME' => $registerLangs['INVALID_USERNAME'],
            'USERNAME_TAKEN' => $registerLangs['USERNAME_TAKEN'],
            'INVALID_PROFESSION' => $registerLangs['INVALID_PROFESSION'],
        );
        return $langs;
    }
}
