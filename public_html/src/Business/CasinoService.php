<?PHP

namespace src\Business;

use app\config\Routing;
use src\Business\PossessionService;
use src\Business\NotificationService;
use src\Business\DailyChallengeService;
use src\Data\CasinoDAO;

class CasinoService
{
    private $data;
    public $pData;
    public $casinoPossessionIds = array(13, 14, 15, 16, 17);
    public $racetrackBlackFontColors = array(1 => "FFFF00", 5 => "#00FF00", 7 => "#FFFFFF", 10 => "#C0C0C0");
    public $racetrackColors = array(1 => "#FFFF00", "#FF00FF", "#FF0000", "#0000FF", "#00FF00", "#800080", "#FFFFFF", "#0000A0", "#000000", "#C0C0C0", "#804000");
    public $rouletteReds = array(1, 3, 5, 7, 9, 12, 14, 16, 18, 19, 21, 23, 25, 27, 30, 32, 34, 36);
    public $rouletteComboFields = array(
        "_1st" => "1_col", "_2nd" => "2_col", "_3rd" => "3_col",
        "red", "_1_18" => "1_18", "_1_12" => "1_12",
    	"black", "_19_36" => "19_36", "_13_24" => "13_24",
    	"even", "odd","_25_36" => "25_36"
    );
    public $slotMachineWinningCombinations = array(
        '0.5' => array("cherry", "*", "*"),
        1 => array("cherry", "cherry", "*"),
        2 => array("cherry", "cherry", "cherry"),
        3 => array("cherry", "orange", "plum"),
        4 => array("lemon", "watermelon", "strawberry"),
        5 => array("orange", "orange", "orange"),
        6 => array("plum", "plum", "plum"),
        7 => array("lemon", "lemon", "lemon"),
        8 => array("watermelon", "watermelon", "watermelon"),
        9 => array("strawberry", "strawberry", "strawberry")
    );

    public function __construct($pData = false)
    {
        $this->data = new CasinoDAO($pData);
        $this->pData = $pData;
    }

    public function __destruct()
    {
        $this->data = null;
    }

    public function getRecordsCount()
    {
        return $this->data->getRecordsCount();
    }
    
    private static function getSlotMachineFruitNamesByIds($fruit1, $fruit2, $fruit3)
    {
        $slotMachineFruits = array(1 => "cherry", "orange", "plum", "lemon", "watermelon", "strawberry");
        if(array_key_exists($fruit1, $slotMachineFruits) && array_key_exists($fruit2, $slotMachineFruits) && array_key_exists($fruit3, $slotMachineFruits))
            return array($slotMachineFruits[$fruit1], $slotMachineFruits[$fruit2], $slotMachineFruits[$fruit3]);
        
        return false;
    }
    
    private static function getSlotMachineWinningComboMultipliers()
    {
        return array(
            666 => 9,
            555 => 8,
            444 => 7,
            333 => 6,
            222 => 5,
            456 => 4, 465 => 4, 546 => 4, 564 => 4, 645 => 4, 654 => 4, // Positionless
            123 => 3, 132 => 3, 213 => 3, 231 => 3, 312 => 3, 321 => 3, // Positionless | +123, 132
            111 => 2,
            112 => 1, 113 => 1, 114 => 1, 115 => 1, 116 => 1,
            121 => '0.5', 122 => '0.5', 124 => '0.5', 125 => '0.5', 126 => '0.5', // -123
            131 => '0.5', 133 => '0.5', 134 => '0.5', 135 => '0.5', 136 => '0.5', // -132
            141 => '0.5', 142 => '0.5', 143 => '0.5', 144 => '0.5', 145 => '0.5', 146 => '0.5',
            151 => '0.5', 152 => '0.5', 153 => '0.5', 154 => '0.5', 155 => '0.5', 156 => '0.5',
            161 => '0.5', 162 => '0.5', 163 => '0.5', 164 => '0.5', 165 => '0.5', 166 => '0.5'
        );
    }
    
    private static function getBlackjackCards()
    {
        return array(
            1 => 11, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10, 11 => 10, 12 => 10, 13 => 10,
            14 => 11, 15 => 2, 16 => 3, 17 => 4, 18 => 5, 19 => 6, 20 => 7, 21 => 8, 22 => 9, 23 => 10, 24 => 10, 25 => 10, 26 => 10,
            27 => 11, 28 => 2, 29 => 3, 30 => 4, 31 => 5, 32 => 6, 33 => 7, 34 => 8, 35 => 9, 36 => 10, 37 => 10, 38 => 10, 39 => 10,
            40 => 11, 41 => 2, 42 => 3, 43 => 4, 44 => 5, 45 => 6, 46 => 7, 47 => 8, 48 => 9, 49 => 10, 50 => 10, 51 => 10, 52 => 10
        );
    }
    
    private static function getBlackjackAces()
    {
        return array(1, 14, 27, 40);
    }
    
    private static function calculateBlackjackScoreByCards($cards)
    {
        $a_s = self::getBlackjackAces();
        $c_s = self::getBlackjackCards();
        $aces = 0;
        $score = 0;
        foreach($cards AS $c)
        {
            if(in_array($c, $a_s))
                $aces++;
            else
                $score += $c_s[$c];
        }
        for($i = 1; $i <= 4; $i++)
        {
            if($aces == $i)
            {
                if($score <= 10)
                    $score += (10 + $i);
                else
                    $score += $i;
            }
        }
        return $score;
    }
    
    private static function generateBlackjackComputerCards()
    {
        global $security;
        $computerReady = isset($_SESSION['blackjack']['computer_ready']) ? true : false;
        $cardsLeft = self::getBlackjackCards();
        while($computerReady === false)
        {
            foreach($_SESSION['blackjack']['computer_cards'] AS $c)
                unset($cardsLeft[$c]);
            
            $cardPicked = false;
            while($cardPicked === false)
            {
                $randKey = $security->randInt(1, 52);
                $cardComputer = !array_key_exists($randKey, $cardsLeft) ? null : $randKey;
                if(is_numeric($cardComputer)) $cardPicked = true;
            }
            unset($cardsLeft[$cardComputer]);
            
            $scoreThen = self::calculateBlackjackScoreByCards($_SESSION['blackjack']['computer_cards']);
            
            $_SESSION['blackjack']['computer_cards'][] = $cardComputer;
            
            $scoreNow = self::calculateBlackjackScoreByCards($_SESSION['blackjack']['computer_cards']);
            
            if($scoreNow >= 21)
            {
                $diffNow = $scoreNow - 21;
                $diffThen = 21 - $scoreThen;
                if($scoreNow == 21)
                    $computerReady = true;
                else
                {
                    if($diffNow > $diffThen)
                    {
                        $key = array_search($cardComputer, $_SESSION['blackjack']['computer_cards']);
                        unset($_SESSION['blackjack']['computer_cards'][$key]);
                    }
                    $computerReady = true;
                }
            }
        }
        return true;
    }
    
    private static function pickRandomBlackjackCard()
    {
        global $security;
        $cardsLeft = self::getBlackjackCards();
        if(isset($_SESSION['blackjack']['computer_cards']))
        {
            foreach($_SESSION['blackjack']['computer_cards'] AS $c)
                unset($cardsLeft[$c]);
        }
        if(isset($_SESSION['blackjack']['cards']))
        {
            foreach($_SESSION['blackjack']['cards'] AS $c)
                unset($cardsLeft[$c]);
        }
        $cardPicked = false;
        while($cardPicked === false)
        {
            $randKey = $security->randInt(1, 52);
            $card = !array_key_exists($randKey, $cardsLeft) ? null : $randKey;
            if(is_numeric($card)) $cardPicked = true;
        }
        return $card;
    }
    
    private function handlePlayedBrokeMessage($gameResponse, $standardMessage)
    {
        if(!is_bool($gameResponse) && is_object($this->pData))
        {
            global $language;
            $pl       = $language->possessionsLangs();
            global $route;
            if(is_array($gameResponse))
            {
                switch($gameResponse['reason'])
                {
                    default: case 'status':
                        $msgAdd = $pl['PLAY_CASINO_BROKE_STATUS_ERROR'];
                        break;
                    case 'self':
                        $msgAdd = $pl['PLAY_CASINO_BROKE_SELF_ERROR'];
                        break;
                    case 'family':
                        $msgAdd = $pl['PLAY_CASINO_BROKE_FAMILY_ERROR'];
                        break;
                }
            }
            elseif($gameResponse == 'took-over')
                $msgAdd = $pl['PLAY_CASINO_BROKE_TOOK_OVER'];
            
            $successAdd = $msgAdd == $pl['PLAY_CASINO_BROKE_TOOK_OVER'] ? true : false;
            
            $msgAdd = $route->replaceMessagePart(strtolower($this->pData->getName()), $msgAdd, '/{casinoName}/');
            
            $replacedMessages = array();
            $replacedMessages[] = Routing::successMessage($standardMessage);
            if($successAdd === true)
                $replacedMessages[] = Routing::successMessage($msgAdd);
            else
                $replacedMessages[] = Routing::errorMessage($msgAdd);
            
            return $replacedMessages;
        }
        return false;
    }
    
    public function playDobbling($post)
    {
        global $security;
        global $userData;
        global $language;
        global $route;
        global $langs;
        $l        = $language->dobblingLangs();
        $pl       = $language->possessionsLangs();
        $stake = (int)$post['stake'];
        
        if($security->checkToken($post['security-token']) ==  FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if($userData->getInPrison())
        {
            $error = $langs['CANT_DO_THAT_IN_PRISON'];
        }
        if($userData->getTraveling())
        {
            $error = $langs['CANT_DO_THAT_TRAVELLING'];
        }
        if(!is_object($this->pData))
        {
            $error = $pl['UNKNOWN_POSSESSION'];
        }
        if($userData->getCash() < $stake)
        {
            $error = $langs['NOT_ENOUGH_MONEY_CASH'];
        }
        if($stake < 1 || $stake > $this->pData->getPossessDetails()->getStake())
        {
            $error = $route->replaceMessagePart(number_format($this->pData->getPossessDetails()->getStake(), 0, '', ','), $pl['STAKE_BETWEEN_1_AND_MAX'], '/{max}/');
        }
        if($this->pData->getPossessDetails()->getUserID() == $userData->getId())
        {
            $error = $route->replaceMessagePart(strtolower($this->pData->getName()), $pl['CANNOT_PLAY_IN_OWN_CASINO'], '/{casinoName}/');
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            $rolled = $security->randInt(1, 6);
            $computerRolled = $security->randInt(1, 6);

            global $twig;
            $rolledHtml = $twig->render('/src/Views/game/Ajax/dobbling.results.twig', array('rolled' => $rolled, 'ownerRolled' => $computerRolled, 'langs' => $l));
            
            $profitsLosses = 0;
            if($rolled > $computerRolled)
                $profitsLosses += $stake * 2;
            elseif($rolled === $computerRolled)
                $profitsLosses += $stake;
            
            $profitsLosses -= $stake;
            
            $dailyChallengeService = new DailyChallengeService();
            $dailyChallengeService->addToDailiesIfActive(7, $stake);
            
            $gameResponse = $this->data->userPlayedCasinoGame($profitsLosses, $this->pData);
            
            if($profitsLosses == 0)
            {
                // Broke even
                $replacedMessage = $l['PLAY_DOBBLING_SUCCESS_BROKE_EVEN'];
            }
            elseif($profitsLosses > 0)
            {
                // Won
                $replacedMessage = $route->replaceMessagePart(number_format(abs($profitsLosses), 0, '', ','), $l['PLAY_DOBBLING_SUCCESS_WON'], '/{profits}/');
                
                if($replacedMessages = $this->handlePlayedBrokeMessage($gameResponse, $replacedMessage))
                {
                    // Send notification
                    $notification = new NotificationService();
                    $params = "user=".$userData->getUsername()."&casinoName=".$this->pData->getName();
                    $notification->sendNotification($this->pData->getPossessDetails()->getUserID(), 'USER_PLAYED_CASINO_BROKE', $params);
                    
                    return array_merge(array('html' => $rolledHtml), $replacedMessages);
                }
            }
            elseif($profitsLosses < 0)
            {
                // Lost
                $replacedMessage = $route->replaceMessagePart(number_format(abs($profitsLosses), 0, '', ','), $l['PLAY_DOBBLING_SUCCESS_LOST'], '/{losses}/');
                return array_merge(array('html' => $rolledHtml), array(Routing::errorMessage($replacedMessage)));
            }
            
            return array_merge(array('html' => $rolledHtml), array(Routing::successMessage($replacedMessage)));
        }
    }
    
    public function playRacetrack($post)
    {
        global $security;
        global $userData;
        global $language;
        global $route;
        global $langs;
        $l        = $language->racetrackLangs();
        $pl       = $language->possessionsLangs();
        $horse = (int)$post['horse'];
        $stake = (int)$post['stake'];
        
        if($security->checkToken($post['security-token']) ==  FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if($userData->getInPrison())
        {
            $error = $langs['CANT_DO_THAT_IN_PRISON'];
        }
        if($userData->getTraveling())
        {
            $error = $langs['CANT_DO_THAT_TRAVELLING'];
        }
        if(!is_object($this->pData))
        {
            $error = $pl['UNKNOWN_POSSESSION'];
        }
        if($horse < 1 || $horse > 11)
        {
            $error = $langs['INVALID_ACTION'];
        }
        if($userData->getCash() < $stake)
        {
            $error = $langs['NOT_ENOUGH_MONEY_CASH'];
        }
        if($stake < 1 || $stake > $this->pData->getPossessDetails()->getStake())
        {
            $error = $route->replaceMessagePart(number_format($this->pData->getPossessDetails()->getStake(), 0, '', ','), $pl['STAKE_BETWEEN_1_AND_MAX'], '/{max}/');
        }
        if($this->pData->getPossessDetails()->getUserID() == $userData->getId())
        {
            $error = $route->replaceMessagePart(strtolower($this->pData->getName()), $pl['CANNOT_PLAY_IN_OWN_CASINO'], '/{casinoName}/');
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
      		$raced = $security->randInt(1, (($horse + 1) * 10));
            if($raced < 10)
            {
                // User won
                $otherColors = $rtColors = $this->racetrackColors;
                $winningKey = $horse;
                $winningColor = $userColor = $rtColors[$winningKey];
            }
            else
            {
                // User lost
                $colors = $otherColors = $rtColors = $this->racetrackColors;
                unset($colors[$horse]);
                $winningKey = array_rand($colors);
                $winningColor = $rtColors[$winningKey];
            }
            unset($otherColors[$winningKey]);
            shuffle($otherColors);
            $placedColors = array(1 => $winningColor);
            foreach($otherColors AS $c)
                $placedColors[] = $c;
            
            global $twig;
            $racedHtml = $twig->render('/src/Views/game/Ajax/racetrack.results.twig', array('colors' => $placedColors, 'blackFonts' => $this->racetrackBlackFontColors));
            
            $profitsLosses = 0;
            if(isset($userColor))
                $profitsLosses += $stake * ($horse + 1);
            
            $profitsLosses -= $stake;
            
            $dailyChallengeService = new DailyChallengeService();
            $dailyChallengeService->addToDailiesIfActive(7, $stake);
            
            $gameResponse = $this->data->userPlayedCasinoGame($profitsLosses, $this->pData);
            
            if($profitsLosses > 0)
            {
                // Won
                $replacedMessage = $route->replaceMessagePart(number_format(abs($profitsLosses), 0, '', ','), $l['PLAY_RACETRACK_SUCCESS_WON'], '/{profits}/');
                
                if($replacedMessages = $this->handlePlayedBrokeMessage($gameResponse, $replacedMessage))
                {
                    // Send notification
                    $notification = new NotificationService();
                    $params = "user=".$userData->getUsername()."&casinoName=".$this->pData->getName();
                    $notification->sendNotification($this->pData->getPossessDetails()->getUserID(), 'USER_PLAYED_CASINO_BROKE', $params);
                    
                    return array_merge(array('html' => $racedHtml), $replacedMessages);
                }
            }
            elseif($profitsLosses < 0)
            {
                // Lost
                $replacedMessage = $route->replaceMessagePart(number_format(abs($profitsLosses), 0, '', ','), $l['PLAY_RACETRACK_SUCCESS_LOST'], '/{losses}/');
                return array_merge(array('html' => $racedHtml), array(Routing::errorMessage($replacedMessage)));
            }
            
            return array_merge(array('html' => $racedHtml), array(Routing::successMessage($replacedMessage)));
        }
    }
    
    public function playRoulette($post)
    {
        global $security;
        global $userData;
        global $language;
        global $route;
        global $langs;
        $l        = $language->rouletteLangs();
        $pl       = $language->possessionsLangs();
        
        $playedFields = array();
        $fields = $this->rouletteComboFields;
        $stakedTotal = 0;
        for($i = 0; $i < 37; $i++)
            $fields[] = "n".$i;
        
        foreach($fields AS $field)
        {
            if(isset($post[$field]) && !(strlen($post[$field]) > 7 || ((!ctype_digit($post[$field]) || $post[$field] < 1) && strlen($post[$field]) > 0)) && is_numeric($post[$field]))
            {
                $playedFields[] = $field;
                $stakedTotal += (int)$post[$field];
            }
        }
        
        if($security->checkToken($post['security-token']) ==  FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if($userData->getInPrison())
        {
            $error = $langs['CANT_DO_THAT_IN_PRISON'];
        }
        if($userData->getTraveling())
        {
            $error = $langs['CANT_DO_THAT_TRAVELLING'];
        }
        if(!is_object($this->pData))
        {
            $error = $pl['UNKNOWN_POSSESSION'];
        }
        if($userData->getCash() < $stakedTotal)
        {
            $error = $langs['NOT_ENOUGH_MONEY_CASH'];
        }
        if($stakedTotal < 1 || $stakedTotal > $this->pData->getPossessDetails()->getStake())
        {
            $error = $route->replaceMessagePart(number_format($this->pData->getPossessDetails()->getStake(), 0, '', ','), $pl['STAKE_BETWEEN_1_AND_MAX'], '/{max}/');
        }
        if($this->pData->getPossessDetails()->getUserID() == $userData->getId())
        {
            $error = $route->replaceMessagePart(strtolower($this->pData->getName()), $pl['CANNOT_PLAY_IN_OWN_CASINO'], '/{casinoName}/');
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            $rolled = $security->randInt(0, 36);
            $reds = $this->rouletteReds;
            $firstCol = $secondCol = $thirdCol = array();
            for($i = 1; $i < 37; $i += 3)
            {
                $firstCol[] = $i;
				$secondCol[] = $i + 1;
				$thirdCol[] = $i + 2;
			}
            
            $profitsLosses = 0;
            foreach($playedFields AS $field)
            {
                $stake = (int)$post[$field];
                if($field == "n" . $rolled)
                {
                    if($field == "n0" && $rolled == 0) // 0 Not part of any col or others, slighly higher profits
                        $profitsLosses += ($stake * 37);
                    else
                        $profitsLosses += ($stake * 36);
                }
                elseif(($field == "1_col" && in_array($rolled, $firstCol)) || ($field == "2_col" && in_array($rolled, $secondCol)) || ($field == "3_col" && in_array($rolled, $thirdCol)) ||
                    ($field == "1_12" && $rolled >= 1 && $rolled <= 12) || ($field == "13_24" && $rolled >= 13 && $rolled <= 24) || ($field == "25_36" && $rolled >= 25 && $rolled <= 36)
                )
                    $profitsLosses += ($stake * 3);
                elseif(($field == "red" && in_array($rolled, $reds)) || ($field == "black" && !in_array($rolled, $reds)) ||
                    ($field == "even" && !($rolled & 1)) || ($field == "odd" && ($rolled & 1)) ||
                    ($field == "1_18" && $rolled >= 1 && $rolled <= 18) || ($field == "19_36" && $rolled >= 19 && $rolled <= 36)
                )
                    $profitsLosses += ($stake * 2);
                
                $profitsLosses -= $stake;
            }
            
            $dailyChallengeService = new DailyChallengeService();
            $dailyChallengeService->addToDailiesIfActive(7, $stake);
            
            $gameResponse = $this->data->userPlayedCasinoGame($profitsLosses, $this->pData);
            
            if($profitsLosses == 0)
            {
                // Broke even
                $replacedMessage = $route->replaceMessagePart($rolled, $l['PLAY_ROULETTE_SUCCESS_BROKE_EVEN'], '/{rolled}/');
            }
            elseif($profitsLosses > 0)
            {
                // Won
                $replaces = array(
                    array('part' => $rolled, 'message' => $l['PLAY_ROULETTE_SUCCESS_WON'], 'pattern' => '/{rolled}/'),
                    array('part' => number_format(abs($profitsLosses), 0, '', ','), 'message' => FALSE, 'pattern' => '/{profits}/')
                );
                $replacedMessage = $route->replaceMessageParts($replaces);
                
                if($replacedMessages = $this->handlePlayedBrokeMessage($gameResponse, $replacedMessage))
                {
                    // Send notification
                    $notification = new NotificationService();
                    $params = "user=".$userData->getUsername()."&casinoName=".$this->pData->getName();
                    $notification->sendNotification($this->pData->getPossessDetails()->getUserID(), 'USER_PLAYED_CASINO_BROKE', $params);
                    
                    return $replacedMessages;
                }
            }
            elseif($profitsLosses < 0)
            {
                // Lost
                $replaces = array(
                    array('part' => $rolled, 'message' => $l['PLAY_ROULETTE_SUCCESS_LOST'], 'pattern' => '/{rolled}/'),
                    array('part' => number_format(abs($profitsLosses), 0, '', ','), 'message' => FALSE, 'pattern' => '/{losses}/')
                );
                $replacedMessage = $route->replaceMessageParts($replaces);
                return Routing::errorMessage($replacedMessage);
            }
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function playSlotMachine($post)
    {
        global $security;
        global $userData;
        global $language;
        global $route;
        global $langs;
        $l        = $language->slotMachineLangs();
        $pl       = $language->possessionsLangs();
        $stake = (int)$post['stake'];
        
        if($security->checkToken($post['security-token']) ==  FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if($userData->getInPrison())
        {
            $error = $langs['CANT_DO_THAT_IN_PRISON'];
        }
        if($userData->getTraveling())
        {
            $error = $langs['CANT_DO_THAT_TRAVELLING'];
        }
        if(!is_object($this->pData))
        {
            $error = $pl['UNKNOWN_POSSESSION'];
        }
        if($userData->getCash() < $stake)
        {
            $error = $langs['NOT_ENOUGH_MONEY_CASH'];
        }
        if($stake < 1 || $stake > $this->pData->getPossessDetails()->getStake())
        {
            $error = $route->replaceMessagePart(number_format($this->pData->getPossessDetails()->getStake(), 0, '', ','), $pl['STAKE_BETWEEN_1_AND_MAX'], '/{max}/');
        }
        if($this->pData->getPossessDetails()->getUserID() == $userData->getId())
        {
            $error = $route->replaceMessagePart(strtolower($this->pData->getName()), $pl['CANNOT_PLAY_IN_OWN_CASINO'], '/{casinoName}/');
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            $fruit1 = $security->randInt(1, 6);
            $fruit2 = $security->randInt(1, 6);
            $fruit3 = $security->randInt(1, 6);
            $fruits = self::getSlotMachineFruitNamesByIds($fruit1, $fruit2, $fruit3);
            if($fruits !== false)
            {
                global $twig;
                $rolledImages = $twig->render('/src/Views/game/Ajax/slot.machine.images.twig', array('fruits' => $fruits));
                
                $winningComboMultipliers = self::getSlotMachineWinningComboMultipliers();
                $profitsLosses = 0;
                $fruitNrs = $fruit1 . $fruit2 . $fruit3;
                $multiplier = array_key_exists($fruitNrs, $winningComboMultipliers) ? $winningComboMultipliers[$fruitNrs] : null;
                if($multiplier)
                    $profitsLosses += $stake * $multiplier;
                
                $profitsLosses -= $stake;
                
                $dailyChallengeService = new DailyChallengeService();
                $dailyChallengeService->addToDailiesIfActive(7, $stake);
                
                $gameResponse = $this->data->userPlayedCasinoGame($profitsLosses, $this->pData);
                
                if($profitsLosses == 0)
                {
                    // Broke even
                    $replacedMessage = $l['PLAY_SLOT_MACHINE_SUCCESS_BROKE_EVEN'];
                }
                elseif($profitsLosses > 0)
                {
                    // Won
                    $replacedMessage = $route->replaceMessagePart(number_format(abs($profitsLosses), 0, '', ','), $l['PLAY_SLOT_MACHINE_SUCCESS_WON'], '/{profits}/');
                    
                    if($replacedMessages = $this->handlePlayedBrokeMessage($gameResponse, $replacedMessage))
                    {
                        // Send notification
                        $notification = new NotificationService();
                        $params = "user=".$userData->getUsername()."&casinoName=".$this->pData->getName();
                        $notification->sendNotification($this->pData->getPossessDetails()->getUserID(), 'USER_PLAYED_CASINO_BROKE', $params);
                        
                        return array_merge(array('images' => $rolledImages), $replacedMessages);
                    }
                }
                elseif($profitsLosses < 0)
                {
                    // Lost
                    $replacedMessage = $route->replaceMessagePart(number_format(abs($profitsLosses), 0, '', ','), $l['PLAY_SLOT_MACHINE_SUCCESS_LOST'], '/{losses}/');
                    return array_merge(array('images' => $rolledImages), array(Routing::errorMessage($replacedMessage)));
                }
                
                return array_merge(array('images' => $rolledImages), array(Routing::successMessage($replacedMessage)));
            }
        }
    }
    
    public function playBlackjack($post)
    { // Session based game multiple requests processed though here (play, pick-a-card & stop)
        global $security;
        global $userData;
        global $language;
        global $route;
        global $langs;
        $l        = $language->blackjackLangs();
        $pl       = $language->possessionsLangs();
        if(!isset($_SESSION['blackjack']['stake']))
            $stake = (int)$post['stake'];
        else
            $stake = $_SESSION['blackjack']['stake'];
        
        $stop = isset($_POST['stop']) ? $post['stop'] : null;
        
        if($security->checkToken($post['security-token']) ==  FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if($userData->getInPrison())
        {
            $error = $langs['CANT_DO_THAT_IN_PRISON'];
        }
        if($userData->getTraveling())
        {
            $error = $langs['CANT_DO_THAT_TRAVELLING'];
        }
        if(!is_object($this->pData))
        {
            $error = $pl['UNKNOWN_POSSESSION'];
        }
        if($userData->getCash() < $stake)
        {
            $error = $langs['NOT_ENOUGH_MONEY_CASH'];
        }
        if($stake < 1 || $stake > $this->pData->getPossessDetails()->getStake())
        {
            $error = $route->replaceMessagePart(number_format($this->pData->getPossessDetails()->getStake(), 0, '', ','), $pl['STAKE_BETWEEN_1_AND_MAX'], '/{max}/');
        }
        if($this->pData->getPossessDetails()->getUserID() == $userData->getId())
        {
            $error = $route->replaceMessagePart(strtolower($this->pData->getName()), $pl['CANNOT_PLAY_IN_OWN_CASINO'], '/{casinoName}/');
        }
        
        if(isset($error))
        {
            global $twig;
            $twigVars = array('securityToken' => $security->getToken(), 'langs' => array_merge($langs, $l), 'actionUri' => $route->getAjaxRouteByRouteName('play-blackjack'), 'stake' => $stake);
            $stakeHtml = $twig->render('/src/Views/game/Ajax/blackjack.stake.twig', $twigVars);
            return array_merge(array('html' => $stakeHtml), array(Routing::errorMessage($error)));
        }
        else
        {
            global $twig;
            if(!isset($_SESSION['blackjack']['stake']))
                $_SESSION['blackjack']['stake'] = $stake;
            if(!isset($_SESSION['blackjack']['cards']) && !isset($_SESSION['blackjack']['computer_cards']))
                $_SESSION['blackjack']['cards'] = $_SESSION['blackjack']['computer_cards'] = array();
            
            $cardCards = isset($_SESSION['blackjack']['computer_ready']) ? $l['CARDS'] : $l['CARD'];
            if(!isset($_SESSION['blackjack']['computer_ready'])) self::generateBlackjackComputerCards();
            
            if(!isset($stop))
            {
                $card = self::pickRandomBlackjackCard();
        		$_SESSION['blackjack']['cards'][] = $card;
            }
    		
            $score = self::calculateBlackjackScoreByCards($_SESSION['blackjack']['cards']);
            if($score >= 21 || $stop)
                $scoreComputer = self::calculateBlackjackScoreByCards($_SESSION['blackjack']['computer_cards']);
            
            if(isset($scoreComputer) || isset($stop)) // Finished game
            {
                $profitsLosses = 0;
                if($score == 21 && count($_SESSION['blackjack']['cards']) == 2) // You had a BLACKJACK!
                {
                    $profitsLosses += $stake * 4;
                    $blackjack = true;
                }
                elseif($scoreComputer == 21 && count($_SESSION['blackjack']['computer_cards']) == 2) // Owner had a BLACKJACK!
                    $computerBlackjack = true;
                else
                {
                    if($score === $scoreComputer) // Draw!
                        $profitsLosses += $stake;
                    else
                    {
                        if($score == 21 || ($scoreComputer > 21 && $score < 21))
        					$profitsLosses += $stake * 2;
        				elseif($scoreComputer < 21 && $score < 21)
                        {
        					if($score > $scoreComputer)
        						$profitsLosses += $stake * 2;
        				}	
        				elseif($score > 21 && $scoreComputer > 21)
                        {
        					if($score < $scoreComputer)
        						$profitsLosses += $stake * 2;
                        }
                    }
                }
                $profitsLosses -= $stake;
            }
            $card = isset($card) ? $card : false;
            $twigVars = array(
                'securityToken' => $security->getToken(),
                'langs' => $l,
                'actionUri' => $route->getAjaxRouteByRouteName('play-blackjack'),
                'cardCards' => $cardCards,
                'allCards' => self::getBlackjackCards(),
                'card' => $card,
                'score' => $score,
                'cards' => $_SESSION['blackjack']['cards'], 
                'computerCards' => $_SESSION['blackjack']['computer_cards']
            );
            $response = $twig->render('/src/Views/game/Ajax/blackjack.cards.twig', $twigVars);
            
            if(isset($profitsLosses))
            {
                $twigVars['stake'] = $stake;
                $twigVars['scoreComputer'] = $scoreComputer;
                $twigVars['langs'] = array_merge($langs, $l);
                if(isset($blackjack)) $twigVars['blackjack'] = $blackjack;
                if(isset($computerBlackjack)) $twigVars['computerBlackjack'] = $computerBlackjack;
                $html = $twig->render('/src/Views/game/Ajax/blackjack.cards.twig', $twigVars);
                
                $dailyChallengeService = new DailyChallengeService();
                $dailyChallengeService->addToDailiesIfActive(7, $stake);
            
                $gameResponse = $this->data->userPlayedCasinoGame($profitsLosses, $this->pData);
                
                if($profitsLosses == 0)
                {
                    // Broke even
                    $replacedMessage = $l['PLAY_BLACKJACK_SUCCESS_BROKE_EVEN'];
                }
                elseif($profitsLosses > 0)
                {
                    // Won
                    $replacedMessage = $route->replaceMessagePart(number_format(abs($profitsLosses), 0, '', ','), $l['PLAY_BLACKJACK_SUCCESS_WON'], '/{profits}/');
                    
                    if($replacedMessages = $this->handlePlayedBrokeMessage($gameResponse, $replacedMessage))
                    {
                        // Send notification
                        $notification = new NotificationService();
                        $params = "user=".$userData->getUsername()."&casinoName=".$this->pData->getName();
                        $notification->sendNotification($this->pData->getPossessDetails()->getUserID(), 'USER_PLAYED_CASINO_BROKE', $params);
                        
                        $response = $mergedResponse = array_merge(array('html' => $html), $replacedMessages);
                    }
                }
                elseif($profitsLosses < 0)
                {
                    // Lost
                    $replacedMessage = $route->replaceMessagePart(number_format(abs($profitsLosses), 0, '', ','), $l['PLAY_BLACKJACK_SUCCESS_LOST'], '/{losses}/');
                    $response = $mergedResponse = array_merge(array('html' => $html), array(Routing::errorMessage($replacedMessage)));
                }
                
                if(!isset($mergedResponse))
                    $response = array_merge(array('html' => $html), array(Routing::successMessage($replacedMessage)));
                
                unset($_SESSION['blackjack']); // Successful finish, unset session var 'blackjack'
            }
            
            if(!isset($_SESSION['blackjack']['computer_ready']) && isset($_SESSION['blackjack']['computer_cards'])) $_SESSION['blackjack']['computer_ready'] = true;
            return $response;
        }
    }
}
