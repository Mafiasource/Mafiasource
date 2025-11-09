<?PHP

namespace src\Languages;

class Notifications
{
    private $lang = 'en';
    public $note = ''; // Init
    
    public $notes = array(
        'EN' => array(
            /* FAMILY */
            "USER_JOINED_FAMILY" => "{user} asked to join your family, you can manage this request <a href='/game/family-management'><strong>here</strong></a>.",
            "ACCEPTED_JOINED_USER_TO_FAMILY" => "The family {family} has <strong>accepted</strong> your request to join their family.",
            "DENIED_JOINED_USER_TO_FAMILY" => "The family {family} has <strong>denied</strong> your request to join their family.",
            "USER_INVITED_TO_FAMILY" => "The family {family} has invited you to join their family, you can accept or deny this request <a href='/game/family-invitations'><strong>here</strong></a>.",
            "FAMILY_ALLIANCE_REQUESTED" => "The family {family} would like to start an alliance with your family, you can accept or deny this request <a href='/game/family-management/alliances'><strong>here</strong></a>.",
            "FAMILY_ALLIANCE_ACCEPTED" => "The family {family} has <strong>accepted</strong> your request tot start an alliance.",
            "FAMILY_ALLIANCE_DENIED" => "The family {family} has <strong>denied</strong> your request tot start an alliance.",
            
            /* FAMILY RAID */
            "DRIVER_INVITED_TO_FAMILY_RAID" => "You are invited as <strong>Driver</strong> for a family robbery on the bank of <strong>{state}</strong><br /><a href='/game/family-raid'><strong>Click here</strong></a> to view this invitation!",
            "BOMB_EXPERT_INVITED_TO_FAMILY_RAID" => "You are invited as <strong>Bomb expert</strong> for a family robbery on the bank of <strong>{state}</strong><br /><a href='/game/family-raid'><strong>Click here</strong></a> to view this invitation!",
            "WEAPON_EXPERT_INVITED_TO_FAMILY_RAID" => "You are invited as <strong>Weapon expert</strong> for a family robbery on the bank of <strong>{state}</strong><br /><a href='/game/family-raid'><strong>Click here</strong></a> to view this invitation!",
            "START_FAMILY_RAID_SUCCESS" => "You have robbed the bank of <strong>{state}</strong>!<br />You have stolen <strong>$&#8203;{stolenAmount}</strong> in total, that's <strong>$&#8203;{stolenEach}</strong> for each person!",
            "START_FAMILY_RAID_FAIL" => "The robbery on the bank of <strong>{state}</strong> has <span class='crimson'><strong>failed</strong></span>!",
            
            /* USERS & FRIENDS */
            "USER_INVITED_FRIEND" => "{user} sent you a friend invite, <a href='javascript:void(0);' class='ajaxTab' data-tab='friends'><strong>click here</strong></a> to accept or deny this request.",
            "FRIEND_REQUEST_ACCEPTED" => "{user} has accepted your friend request.",
            "FRIEND_REQUEST_DENIED" => "{user} has denied your friend request.",
            "USER_PIMPED_FOR_YOU" => "{user} has pimped <strong>{whores} hoes</strong> for you. <a href='/game/profile/{user}/pimp-for-player'><strong>click here</strong></a> to pimp some hoes back for {user}.",
            
            /* GYM */
            "GYM_COMPETITION_CHALLENGE_WIN" => "You won a gym competition against <strong>{user}</strong>, You've earned a $&#8203;{profits} stake and {scorePoints} score points!",
            "GYM_COMPETITION_CHALLENGE_LOSE" => "<strong>{user}</strong> beat you in a gym competition, You've lost your $&#8203;{stake} stake but earned {scorePoints} score points!",
            "GYM_COMPETITION_CHALLENGE_DRAW" => "The gym competition was tough with {user}, you received your $&#8203;{stake} stake back and also earned {scorePoints} scorepoints!",
            
            /* GROUND */
            "GROUND_BOMBARDEMENT_SUCCESS" => "{user} has conquered your ground area in the state {state}!",
            "GROUND_BOMBARDEMENT_FAILED" => "An attempt to conquer your ground area in {state} has failed by {user}.",
            
            /* FIFTY GAMES */
            "FIFTY_GAME_CHALLENGE_WIN" => "You've beat {user} in a 50/50 game and doubled your stake of {stake} {type}.",
            "FIFTY_GAME_CHALLENGE_WIN_CASH" => "You've beat {user} in a 50/50 game and doubled your stake of $&#8203;{stake} {type}.",
            "FIFTY_GAME_CHALLENGE_LOSE" => "{user} beat you in a 50/50 game and you lost your stake of {stake} {type}.",
            "FIFTY_GAME_CHALLENGE_LOSE_CASH" => "{user} beat you in a 50/50 game and you lost your stake of $&#8203;{stake} {type}.",

            /* STREETRACE */
            "STREETRACE_RESULT_PRIZE" => "You've finished in {placeOrdinal} in the {race} streetrace and received $&#8203;{prize}.",
            "STREETRACE_RESULT_LOSS" => "You've finished in {placeOrdinal} in the {race} streetrace and lost your stake.",

            /* LOTTERY */
            "USER_WON_WEEKLY_LOTTERY" => "You won at no. {place} and received $&#8203;{prize} with the weekly superpot.",
            "USER_WON_DAILY_LOTTERY" => "You won at no. {place} and received $&#8203;{prize} with the daily lottery.",
            "OWNER_CANNOT_KEEP_LOTTERY" => "You could not pay the costs to keep your Lottery running, your Lottery has been put on sale.",
            
            /* POSSESSIONS */
            "POSSESS_TRANSFER_REQUEST" => "{user} sent you his {possession} in {location}, <a href='javascript:void(0);' class='ajaxTab' data-tab='possession.manage' data-transfer='{id}'><strong>click here</strong></a> to accept or deny this possession.",
            "POSSESS_TRANSFER_ACCEPTED" => "{user} has accepted your {possession} in {location}, he is now the new owner.",
            "POSSESS_TRANSFER_DENIED" => "{user} has denied your {possession} in {location}.",
            
            /* ORGANIZED CRIMES */
            "USER_INVITED_TO_ORGANIZED_CRIME_2" => "{user} has invited you as {job} for an organized crime on Route 66.",
            "DRIVER_INVITED_TO_ORGANIZED_CRIME_3" => "{user} has invited you as Getaway driver for an organized crime in Las Vegas.",
            "GROUND_INVITED_TO_ORGANIZED_CRIME_3" => "{user} has invited you as Ground person for an organized crime in Las Vegas.",
            "INTEL_INVITED_TO_ORGANIZED_CRIME_3" => "{user} has invited you as Intel for an organized crime in Las Vegas.",
            "LEADER_STOPPED_ORGANIZED_CRIME" => "{user} has stopped your organized crime together.",
            "PARTICIPANT_DENIED_ORGANIZED_CRIME" => "{user} has denied your organized crime request, you can still invite another player.",
            "ORGANIZED_CRIME_2_FAILED_AND_HURT" => "The organized crime you committed on Route 66 has failed. You got hurt and lost <strong>{hurtPercent}%</strong> health and <strong>{bullets}</strong> bullets while losing the cops!",
            "ORGANIZED_CRIME_2_SUCCESS_BUT_HURT" => "During the organized crime on Route 66 you got hurt and lost <strong>{hurtPercent}%</strong> health and <strong>{bullets}</strong> bullets but you've stolen $&#8203;{stolenMoney} cash and gained {rankpoints} rankpoint(s)!",
            "ORGANIZED_CRIME_2_SUCCESS_BUT_HEAT" => "During the organized crime on Route 66 you had to fire off <strong>{bullets}</strong> bullets but you've stolen $&#8203;{stolenMoney} cash and gained {rankpoints} rankpoint(s)!",
            "ORGANIZED_CRIME_2_SUCCESS" => "You successfully committed the organized crime on Route 66, you've stolen $&#8203;{stolenMoney} cash and gained {rankpoints} rankpoint(s)!",
            "ORGANIZED_CRIME_2_ARRESTED" => "You got caught by the police committing the organized crime on Route 66! You were arrested for <strong>{prisonTime} seconds</strong>.",
            "ORGANIZED_CRIME_2_STARTER_ARRESTED" => "{user} got caught by the police committing the organized crime on Route 66! He got arrested for <strong>{prisonTime} seconds</strong>.",
            "ORGANIZED_CRIME_2_FAILED" => "The organized crime on Route 66 you committed has failed.",
            "ORGANIZED_CRIME_3_FAILED_AND_HURT" => "The organized crime you committed in Las Vegas has failed. You got hurt and lost <strong>{hurtPercent}%</strong> health and <strong>{bullets}</strong> bullets while losing the cops!",
            "ORGANIZED_CRIME_3_SUCCESS_BUT_HURT" => "During the organized crime in Las Vegas you got hurt and lost <strong>{hurtPercent}%</strong> health and <strong>{bullets}</strong> bullets but you've stolen $&#8203;{stolenMoney} cash and gained {rankpoints} rankpoint(s)!",
            "ORGANIZED_CRIME_3_SUCCESS_BUT_HEAT" => "During the organized crime in Las Vegas you had to fire off <strong>{bullets}</strong> bullets but you've stolen $&#8203;{stolenMoney} cash and gained {rankpoints} rankpoint(s)!",
            "ORGANIZED_CRIME_3_SUCCESS" => "You successfully committed the organized crime in Las Vegas, you've stolen $&#8203;{stolenMoney} cash and gained {rankpoints} rankpoint(s)!",
            "ORGANIZED_CRIME_3_ARRESTED" => "You got caught by the police committing the organized crime in Las Vegas! You were arrested for <strong>{prisonTime} seconds</strong>.",
            "ORGANIZED_CRIME_3_OTHERS_ARRESTED" => "{users} were caught by the police committing the organized crime in Las Vegas! They got arrested for <strong>{prisonTime} seconds</strong>.",
            "ORGANIZED_CRIME_3_FAILED" => "The organized crime in Las Vegas you committed has failed.",
            
            /* CASINOS */
            "USER_PLAYED_CASINO_BROKE" => "{user} has bankrupted your {casinoName}!",
            
            /* MURDER */
            "MURDERED_BY_ATTACKER_HEADSHOT" => "<strong>{attacker}</strong> shot you, you died from his hits!<br>He made a headshot, you didn't stand a chance to fire off your backfire!",
            "SURVIVED_ATTACK_KILLED_ATTACKER" => "You got attacked by <strong>{attacker}</strong> but you <strong>killed</strong> him and also stole <strong>$&#8203;{stolenMoney}</strong> cash money!",
            "SURVIVED_ATTACK_KILLED_ATTACKER_ADD_HITLIST" => "You got attacked by <strong>{attacker}</strong> but you <strong>killed</strong> him and also stole <strong>$&#8203;{stolenMoney}</strong> cash money!<br>{attacker} was also listed on the hitlist. Because of this your received an additional $&#8203;{prize}!",
            "DIED_IN_ATTACK_KILLED_ATTACKER" => "You got attacked by <strong>{attacker}</strong>, you both died by the fired shots.",
            "MURDERED_BY_ATTACKER" => "You got murdered by <strong>{attacker}</strong>.",
            "SURVIVED_ATTACK_STOLE_ATTACKER" => "You got attacked by <strong>{attacker}</strong> and you stole <strong>$&#8203;{stolenMoney}</strong>!",
            "SURVIVED_ATTACK_ATTACKER_STOLE" => "You got attacked by <strong>{attacker}</strong> and he also stole <strong>$&#8203;{stolenMoney}</strong>!",
            
            /* MISSIONS */
            "USER_ACHIEVED_MISSION" => "You earned $&#8203;{bank} bank money and {hp} honor points with the Mission {mission}!",
            
            /* DAILY CHALLENGES */
            "DAILY_CHALLENGE_COMPLETED" => "You completed the daily challenge {challenge} and received {prizeAmount} {prize} for this!",
            "ALL_DAILY_CHALLENGES_COMPLETED" => "You completed all daily challenges and received {luckies} luckybox(es)!",
            
            /* MARKET */
            "MARKET_SOLD_CREDITS_SUCCESS" => "Your {amount} credits on the market were sold for $&#8203;{price}!",
            "MARKET_BOUGHT_CREDITS_SUCCESS" => "You've received {amount} credits through the market!",
            "MARKET_SOLD_HOES_SUCCESS" => "Your {amount} hoes on the market were sold for $&#8203;{price}!",
            "MARKET_BOUGHT_HOES_SUCCESS" => "You've received {amount} hoes through the market!",
            "MARKET_SOLD_HP_SUCCESS" => "Your {amount} honor points on the market were sold for $&#8203;{price}!",
            "MARKET_BOUGHT_HP_SUCCESS" => "You've received {amount} honor points through the market!",
            
            /* PUBLIC MISSION */
            "USER_WON_PUBLIC_MISSION" => "You ended at no. {place} in the past Public Mission and received {prizeAmount} {prize} &amp; {prize2Amount} {prize2}.",
        ),
        'NL' => array(
            /* FAMILY */
            "USER_JOINED_FAMILY" => "{user} vraagt om je familie te joinen, je kan dit verzoek <a href='/game/family-management'><strong>hier</strong></a> behandelen.",
            "ACCEPTED_JOINED_USER_TO_FAMILY" => "De familie {family} heeft je verzoek om de familie te joinen <strong>geaccepteerd</strong>.",
            "DENIED_JOINED_USER_TO_FAMILY" => "De familie {family} heeft je verzoek om de familie te joinen <strong>geweigerd</strong>.",
            "USER_INVITED_TO_FAMILY" => "Da familie {family} heeft je uitgenodigd om hun familie te joinen, je kan dit verzoek <a href='/game/family-invitations'><strong>hier</strong></a> accepteren of weigeren.",
            "FAMILY_ALLIANCE_REQUESTED" => "De familie {family} wil een alliantie starten met jouw familie, je kan dit verzoek <a href='/game/family-management/alliances'><strong>hier</strong></a> accepteren of weigeren.",
            "FAMILY_ALLIANCE_ACCEPTED" => "De familie {family} heeft jullie verzoek tot een alliantie <strong>geaccepteerd</strong>.",
            "FAMILY_ALLIANCE_DENIED" => "De familie {family} heeft jullie verzoek tot een alliantie <strong>geweigerd</strong>.",
            
            /* FAMILY RAID */
            "DRIVER_INVITED_TO_FAMILY_RAID" => "Je bent uitgenodigd als <strong>Chauffeur</strong> voor een familieoverval op de bank van <strong>{state}</strong><br /><a href='/game/family-raid'><strong>Klik hier</strong></a> om hem te bekijken!",
            "BOMB_EXPERT_INVITED_TO_FAMILY_RAID" => "Je bent uitgenodigd als <strong>Bom expert</strong> voor een familieoverval op de bank van <strong>{state}</strong><br /><a href='/game/family-raid'><strong>Klik hier</strong></a> om hem te bekijken!",
            "WEAPON_EXPERT_INVITED_TO_FAMILY_RAID" => "Je bent uitgenodigd als <strong>Wapen expert</strong> voor een familieoverval op de bank van <strong>{state}</strong><br /><a href='/game/family-raid'><strong>Klik hier</strong></a> om hem te bekijken!",
            "START_FAMILY_RAID_SUCCESS" => "Jullie hebben de bank van <strong>{state}</strong> beroofd!<br />Jullie hebben <strong>$&#8203;{stolenAmount}</strong> gestolen, dat is <strong>$&#8203;{stolenEach}</strong> per persoon!",
            "START_FAMILY_RAID_FAIL" => "De overval op de bank van <strong>{state}</strong> is <span class='crimson'><strong>mislukt</strong></span>!",
            
            /* USERS & FRIENDS */
            "USER_INVITED_FRIEND" => "{user} heeft je een vriendschapsverzoek gestuurd, <a href='javascript:void(0);' class='ajaxTab' data-tab='friends'><strong>klik hier</strong></a> om dit verzoek te accepteren of weigeren.",
            "FRIEND_REQUEST_ACCEPTED" => "{user} heeft je vriendschapsverzoek geaccepteerd.",
            "FRIEND_REQUEST_DENIED" => "{user} heeft je vriendschapsverzoek afgewezen.",
            "USER_PIMPED_FOR_YOU" => "{user} heeft <strong>{whores} hoeren</strong> voor je gepimpt. <a href='/game/profile/{user}/pimp-for-player'><strong>klik hier</strong></a> om terug te pimpen voor {user}.",
            
            /* GYM */
            "GYM_COMPETITION_CHALLENGE_WIN" => "Je hebt gewonnen tegen <strong>{user}</strong> in een sportschool wedstrijd, je hebt $&#8203;{profits} inzet verdient en {scorePoints} scorepunten verdient!",
            "GYM_COMPETITION_CHALLENGE_LOSE" => "<strong>{user}</strong> heeft je verslagen in een sportschool wedstrijd, je hebt $&#8203;{stake} inzet verloren maar wel {scorePoints} scorepunten verdient!",
            "GYM_COMPETITION_CHALLENGE_DRAW" => "Het werd een zware sportschool wedstrijd met {user}, je hebt je $&#8203;{stake} inzet teruggekregen en ook {scorePoints} scorepunten verdient!",
            
            /* GROUND */
            "GROUND_BOMBARDEMENT_SUCCESS" => "{user} heeft je landje in de staat {state} gebombardeerd en overgenomen!",
            "GROUND_BOMBARDEMENT_FAILED" => "Een poging om je landje in {state} over te nemen is mislukt door {user}.",
            
            /* FIFTY GAMES */
            "FIFTY_GAME_CHALLENGE_WIN" => "Je hebt {user} verslagen in een 50/50 spel en je inzet van {stake} {type} verdubbeld.",
            "FIFTY_GAME_CHALLENGE_WIN_CASH" => "Je hebt {user} verslagen in een 50/50 spel en je inzet van $&#8203;{stake} {type} verdubbeld.",
            "FIFTY_GAME_CHALLENGE_LOSE" => "Je hebt verloren van {user} in een 50/50 spel en je inzet van {stake} {type} verloren.",
            "FIFTY_GAME_CHALLENGE_LOSE_CASH" => "Je hebt verloren van {user} in een 50/50 spel en je inzet van $&#8203;{stake} {type} verloren.",

            /* STREETRACE */
            "STREETRACE_RESULT_PRIZE" => "Je eindigde op de {placeNl} in de {race} streetrace en ontving $&#8203;{prize}.",
            "STREETRACE_RESULT_LOSS" => "Je eindigde op de {placeNl} in de {race} streetrace en verloor je inzet.",

            /* LOTTERY */
            "USER_WON_WEEKLY_LOTTERY" => "Je hebt de {place}e prijs gewonnen t.w.v. $&#8203;{prize} met de wekelijkse superpot.",
            "USER_WON_DAILY_LOTTERY" => "Je hebt de {place}e prijs gewonnen t.w.v. $&#8203;{prize} met de dagelijkse loterij.",
            "OWNER_CANNOT_KEEP_LOTTERY" => "Je kon de kosten niet meer betalen om je Loterij draaiende te houden, je Loterij werd te koop gezet.",
            
            /* POSSESSIONS */
            "POSSESS_TRANSFER_REQUEST" => "{user} heeft je zijn {possession} in {location} toegezonden, <a href='javascript:void(0);' class='ajaxTab' data-tab='possession.manage' data-transfer='{id}'><strong>klik hier</strong></a> om deze te accepteren of weigeren.",
            "POSSESS_TRANSFER_ACCEPTED" => "{user} heeft je {possession} in {location} geaccepteerd, hij is nu de nieuwe eigenaar.",
            "POSSESS_TRANSFER_DENIED" => "{user} heeft je {possession} in {location} geweigerd.",
            
            /* ORGANIZED CRIMES */
            "USER_INVITED_TO_ORGANIZED_CRIME_2" => "{user} heeft je uitgenodigd als {job} voor een georganiseerde misdaad op Route 66.",
            "DRIVER_INVITED_TO_ORGANIZED_CRIME_3" => "{user} heeft je uitgenodigd als Getaway chauffeur voor een georganiseerde misdaad in Las Vegas.",
            "GROUND_INVITED_TO_ORGANIZED_CRIME_3" => "{user} heeft je uitgenodigd als Grond persoon voor een georganiseerde misdaad in Las Vegas.",
            "INTEL_INVITED_TO_ORGANIZED_CRIME_3" => "{user} heeft je uitgenodigd als Intel voor een georganiseerde misdaad in Las Vegas.",
            "LEADER_STOPPED_ORGANIZED_CRIME" => "{user} heeft jullie georganiseerde misdaad stopgezet.",
            "PARTICIPANT_DENIED_ORGANIZED_CRIME" => "{user} heeft jullie georganiseerde misdaad geweigerd, je kan een andere speler in zijn plaats uitnodigen.",
            "ORGANIZED_CRIME_2_FAILED_AND_HURT" => "De georganiseerde misdaad die je pleegde op Route 66 is mislukt. Je bent gewond geraakt en hebt <strong>{hurtPercent}%</strong> leven en <strong>{bullets}</strong> kogels verloren tijdens het ontsnappen van de politie!",
            "ORGANIZED_CRIME_2_SUCCESS_BUT_HURT" => "Tijdens de georganiseerde misdaad op Route 66 ben je gewond geraakt en hierdoor <strong>{hurtPercent}%</strong> leven en <strong>{bullets}</strong> kogels verloren maar je hebt wel $&#8203;{stolenMoney} contant gestolen en {rankpoints} rankpunt(en) verdient!",
            "ORGANIZED_CRIME_2_SUCCESS_BUT_HEAT" => "Tijdens de georganiseerde misdaad op Route 66 heb je <strong>{bullets}</strong> kogels moeten afvuren maar je hebt wel $&#8203;{stolenMoney} contant gestolen en {rankpoints} rankpunt(en) verdient!",
            "ORGANIZED_CRIME_2_SUCCESS" => "Je hebt de georganiseerde misdaad op Route 66 succesvol gepleegd, je hebt $&#8203;{stolenMoney} contant gestolen en {rankpoints} rankpunt(en) verdient!",
            "ORGANIZED_CRIME_2_ARRESTED" => "Je bent betrapt door de politie bij het plegen van de georganiseerde misdaad op Route 66! Je werd gearresteerd voor <strong>{prisonTime} seconden</strong>.",
            "ORGANIZED_CRIME_2_STARTER_ARRESTED" => "{user} werd betrapt door de politie bij het plegen van de georganiseerde misdaad op Route 66! Hij werd gearresteerd voor <strong>{prisonTime} seconden</strong>.",
            "ORGANIZED_CRIME_2_FAILED" => "De georganiseerde misdaad op Route 66 die je pleegde is mislukt.",
            "ORGANIZED_CRIME_3_FAILED_AND_HURT" => "De georganiseerde misdaad die je pleegde in Las Vegas is mislukt. Je bent gewond geraakt en hebt <strong>{hurtPercent}%</strong> leven en <strong>{bullets}</strong> kogels verloren tijdens het ontsnappen van de politie!",
            "ORGANIZED_CRIME_3_SUCCESS_BUT_HURT" => "Tijdens de georganiseerde misdaad in Las Vegas ben je gewond geraakt en hierdoor <strong>{hurtPercent}%</strong> leven en <strong>{bullets}</strong> kogels verloren maar je hebt wel $&#8203;{stolenMoney} contant gestolen en {rankpoints} rankpunt(en) verdient!",
            "ORGANIZED_CRIME_3_SUCCESS_BUT_HEAT" => "Tijdens de georganiseerde misdaad in Las Vegas heb je <strong>{bullets}</strong> kogels moeten afvuren maar je hebt wel $&#8203;{stolenMoney} contant gestolen en {rankpoints} rankpunt(en) verdient!",
            "ORGANIZED_CRIME_3_SUCCESS" => "Je hebt de georganiseerde misdaad in Las Vegas succesvol gepleegd, je hebt $&#8203;{stolenMoney} contant gestolen en {rankpoints} rankpunt(en) verdient!",
            "ORGANIZED_CRIME_3_ARRESTED" => "Je bent betrapt door de politie bij het plegen van de georganiseerde misdaad in Las Vegas! Je werd gearresteerd voor <strong>{prisonTime} seconden</strong>.",
            "ORGANIZED_CRIME_3_OTHERS_ARRESTED" => "{users} werden betrapt door de politie bij het plegen van de georganiseerde misdaad in Las Vegas! Ze werden gearresteerd voor <strong>{prisonTime} seconden</strong>.",
            "ORGANIZED_CRIME_3_FAILED" => "De georganiseerde misdaad in Las Vegas die je pleegde is mislukt.",
            
            /* CASINOS */
            "USER_PLAYED_CASINO_BROKE" => "{user} heeft je {casinoName} blutgespeeld!",
            
            /* MURDER */
            "MURDERED_BY_ATTACKER_HEADSHOT" => "<strong>{attacker}</strong> schoot op jouw, jij ging dood door zijn schoten!<br />Hij maakte een headshot dus je had geen kans je backfire af te vuren!",
            "SURVIVED_ATTACK_KILLED_ATTACKER" => "Je bent aangevallen door <strong>{attacker}</strong> maar je hebt hem <strong>vermoord</strong> en nog eens <strong>$&#8203;{stolenMoney}</strong> gestolen!",
            "SURVIVED_ATTACK_KILLED_ATTACKER_ADD_HITLIST" => "Je bent aangevallen door <strong>{attacker}</strong> maar je hebt hem <strong>vermoord</strong> en nog eens <strong>$&#8203;{stolenMoney}</strong> gestolen!<br />{attacker} stond ook op de aanslagenlijst. Je hebt daardoor nog eens $&#8203;{prize} gekregen!",
            "DIED_IN_ATTACK_KILLED_ATTACKER" => "Je bent aangevallen door <strong>{attacker}</strong>, jullie zijn allebei dood gegaan door de schoten.",
            "MURDERED_BY_ATTACKER" => "Je werd vermoord door <strong>{attacker}</strong>.",
            "SURVIVED_ATTACK_STOLE_ATTACKER" => "Je bent aangevallen door <strong>{attacker}</strong> en je hebt <strong>$&#8203;{stolenMoney}</strong> gestolen!",
            "SURVIVED_ATTACK_ATTACKER_STOLE" => "Je bent aangevallen door <strong>{attacker}</strong> hij heeft ook nog <strong>$&#8203;{stolenMoney}</strong> gestolen!",
            
            /* MISSIONS */
            "USER_ACHIEVED_MISSION" => "Je hebt $&#8203;{bank} bankgeld en {hp} eerpunten verdient met de Missie {mission}!",
            
            /* DAILY CHALLENGES */
            "DAILY_CHALLENGE_COMPLETED" => "Je hebt de dagelijkse uitdaging {challenge} voltooid en hierdoor {prizeAmount} {prize} verdient!",
            "ALL_DAILY_CHALLENGES_COMPLETED" => "Je hebt alle dagelijkse uitdagingen voltooid en hierdoor {luckies} luckybox(en) ontvangen!",
            
            /* MARKET */
            "MARKET_SOLD_CREDITS_SUCCESS" => "Jouw {amount} credits op de markt werden verkocht voor $&#8203;{price}!",
            "MARKET_BOUGHT_CREDITS_SUCCESS" => "Je hebt {amount} credits ontvangen via de markt!",
            "MARKET_SOLD_HOES_SUCCESS" => "Jouw {amount} hoeren op de markt werden verkocht voor $&#8203;{price}!",
            "MARKET_BOUGHT_HOES_SUCCESS" => "Je hebt {amount} hoeren ontvangen via de markt!",
            "MARKET_SOLD_HP_SUCCESS" => "Jouw {amount} eerpunten op de markt werden verkocht voor $&#8203;{price}!",
            "MARKET_BOUGHT_HP_SUCCESS" => "Je hebt {amount} eerpunten ontvangen via de markt!",
            
            /* PUBLIC MISSION */
            "USER_WON_PUBLIC_MISSION" => "Je bent op de {place}e plaats ge&euml;indigd in de Publieke Missie en hierdoor {prizeAmount} {prize} &amp; {prize2Amount} {prize2} ontvangen.",
        )
    );
    
    public function __construct($note = false)
    {
        global $lang;
        $this->lang = $lang;
        if($note != false && array_key_exists($note,$this->notes['EN'])) $this->note = $note;
    }
    
    public function getNotification()
    {
        if(in_array($this->lang,array('nl','en')) && $this->note != '')
            return $this->notes[strtoupper($this->lang)][$this->note];
    }
    
    public function setNotification($note)
    {
        if(in_array($note,$this->notes['EN']))
        {
            $this->note = $note;
            return TRUE;
        }
        else return FALSE;
    }
}
