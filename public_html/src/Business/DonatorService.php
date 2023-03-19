<?PHP

namespace src\Business;

use src\Business\Logic\game\Statics\Donator AS DonatorStatics;
use app\config\Routing;
use src\Business\FamilyService;
use src\Data\DonatorDAO;
 
class DonatorService extends DonatorStatics
{
    private $data;
    
    public $statuses = array();
    public $luckyboxAmnt = 15; //15;
    public $luckyboxCr = 300; //300;
    
    public function __construct()
    {
        $this->data = new DonatorDAO();
        
        global $lang;
        $dName = "donator";
        if($lang == 'nl') $dName = "donateur";
        $this->statuses = array(
            1 =>  ucfirst($dName),
            5 => "VIP",
            10 => "Gold Member"
        );
    }
    
    public function __destruct()
    {
        $this->data = null;
    }
    
    public function getRecordsCount($type)
    {
        return $this->data->getRecordsCount($type);
    }
    
    public function interactDonationShop($post)
    {
        global $security;
        global $language;
        global $langs;
        $l = $language->donationShopLangs();
        global $userData;
        $family = new FamilyService();
        
        $donator = isset($post['donator']) ? true : null;
        $vip = isset($post['vip']) ? true : null;
        $goldMember = isset($post['gold-member']) ? true : null;
        $vipFamily = isset($post['vip-family']) ? true : null;
        $luckybox = isset($post['luckybox']) ? true : null;
        $halvingTimes = isset($post['halving-times']) ? true : null;
        $bribingPolice = isset($post['bribing-police']) ? true : null;
        $ground = isset($post['ground']) ? true : null;
        $smuggling = isset($post['smuggling-capacity']) ? true : null;
        $profession = isset($post['new-profession']) ? true : null;
        $newNickname = isset($post['new-name']) ? true : null;

        $familyData = $family->getFamilyDataByName($userData->getFamily());
        if(is_object($familyData)) $familyVip = $familyData->getVip();
        $hasStatus = $hasFamilyStatus = false;
        if(isset($donator))
        {
            $donatorID = 1;
            $creditsNeeded = 100;
        }
        elseif(isset($vip))
        {
            $donatorID = 5;
            $creditsNeeded = 500;
        }
        elseif(isset($goldMember))
        {
            $donatorID = 10;
            $creditsNeeded = 1250;
        }
        elseif(isset($vipFamily))
            $creditsNeeded = 500;
        elseif(isset($luckybox))
            $creditsNeeded = $this->luckyboxCr;
        elseif(isset($halvingTimes))
            $creditsNeeded = 63;
        elseif(isset($bribingPolice) && $userData->getCharType() == 6)
            $creditsNeeded = 28;
        elseif(isset($bribingPolice) && $userData->getCharType() != 6)
            $creditsNeeded = 38;
        elseif(isset($ground))
            $creditsNeeded = 100;
        elseif(isset($smuggling))
            $creditsNeeded = 100;
        elseif(isset($profession))
            $creditsNeeded = 50;
        elseif(isset($newNickname))
            $creditsNeeded = 100;
        
        $donationShopData = $this->data->getDonationShopData();
        if(isset($donator) || isset($vip) || isset($goldMember) || isset($vipFamily))
        {
            switch($userData->getDonatorID())
            {
                case 1:
                    if(isset($vip))
                        $creditsNeeded = 400;
                    elseif(isset($goldMember))
                        $creditsNeeded = 1400;
                    
                    $hasStatus = isset($donator) && !isset($vipFamily) ? true : false;
                    break;
                case 5:
                    if(isset($goldMember))
                        $creditsNeeded = 1000;
                    
                    $hasStatus = (isset($donator) || isset($vip)) && !isset($vipFamily) ? true : false;
                    break;
                case 10:
                    $hasStatus = !isset($vipFamily) ? true : false;
                    break;
            }
            $hasFamilyStatus = isset($familyVip) && $familyVip == true && isset($vipFamily) ? true : false;
        }
        elseif(
            (isset($halvingTimes) && $userData->getCHalvingTimes() > time()) ||
            (isset($bribingPolice) && $userData->getCBribingPolice() > time()) ||
            (isset($ground) && $donationShopData['ground'] == 5) ||
            (isset($smuggling) && $donationShopData['smugglingCapacity'] == 20)
        )
            $hasStatus = true;

        if($security->checkToken($post['security-token']) == FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if($userData->getCredits() < $creditsNeeded)
        {
            $error = $l['NOT_ENOUGH_CREDITS'];
        }
        if($hasStatus === true)
        {
            $error = $l['USER_ALREADY_HAS_STATUS'];
        }
        if($hasFamilyStatus)
        {
            $error = $l['FAMILY_ALREADY_VIP'];
        }
        if(isset($vipFamily) && !is_object($familyData))
        {
            $error = $l['NO_FAMILY'];
        }
        if(isset($profession) && ($post['profession'] < 1 || $post['profession'] > 6 || $post['profession'] == $userData->getCharType()))
        {
            $error = $l['INVALID_PROFESSION'];
        }

        if(isset($newNickname)) {
            if(!UserService::is_name($post['nickname'])) {
                $error = $l['INVALID_USERNAME'];
            } else {
                $UserService = new UserService();

                if($UserService->checkUsernameExists($post['nickname']) === true) {
                    $error = $l['USERNAME_TAKEN'];
                }
            }
        }

        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            global $route;
            if(isset($donatorID))
            {
                $this->data->buyStatus($donatorID, $creditsNeeded);
                
                $replaces = array(
                    array('part' => $this->statuses[$donatorID], 'message' => $l['BOUGHT_STATUS_SUCCESS'], 'pattern' => '/{status}/'), //
                    array('part' => number_format($creditsNeeded, 0, '', ','), 'message' => FALSE, 'pattern' => '/{credits}/')
                );
                $replacedMessage = $route->replaceMessageParts($replaces);
            }
            elseif($vipFamily)
            {
                $this->data->buyFamilyVip($familyData->getId(), $creditsNeeded);
                $replacedMessage = $l['BOUGHT_FAMILY_VIP_SUCCESS'];
            }
            elseif($luckybox)
            {
                $this->data->buyLuckybox($this->luckyboxAmnt, $creditsNeeded);
                
                $replaces = array(
                    array('part' => number_format($this->luckyboxAmnt, 0, '', ','), 'message' => $l['BOUGHT_LUCKYBOX_SUCCESS'], 'pattern' => '/{boxes}/'), //
                    array('part' => number_format($creditsNeeded, 0, '', ','), 'message' => FALSE, 'pattern' => '/{credits}/')
                );
                $replacedMessage = $route->replaceMessageParts($replaces);
            }
            elseif($halvingTimes)
            {
                $this->data->buyHalvingTimes($creditsNeeded);
                $replacedMessage = $l['BOUGHT_HALVING_TIMES_SUCCESS'];
            }
            elseif($bribingPolice)
            {
                $this->data->buyBribingPolice($creditsNeeded);
                $replacedMessage = $route->replaceMessagePart(number_format($creditsNeeded, 0, '', ','), $l['BOUGHT_BRIBING_POLICE_SUCCESS'], '/{credits}/');
            }
            elseif($ground)
            {
                $this->data->buyGround($creditsNeeded);
                $replacedMessage = $l['BOUGHT_GROUND_SUCCESS'];
            }
            elseif($smuggling)
            {
                $this->data->buySmugglingCapacity($creditsNeeded);
                $replacedMessage = $l['BOUGHT_SMUGGLING_CAPACITY_SUCCESS'];
            }
            elseif($profession)
            {
                global $user;
                $this->data->buyNewProfession($creditsNeeded, $post['profession']);
                $userData = $user->getUserData();
                $replacedMessage = $route->replaceMessagePart($userData->getProfession(), $l['BOUGHT_NEW_PROFESSION_SUCCESS'], '/{profession}/');
            }
            elseif($newNickname)
            {
                $this->data->changeNickName((int)$creditsNeeded, $post['nickname']);
                $replacedMessage = $l['BOUGHT_NICKNAME_SUCCESS'];
            }
            
            return $route->successMessage($replacedMessage);
        }
    }
    
    public function donate($post)
    {
        global $route;
        global $security;
        global $language;
        global $langs;
        $l = $language->donationShopLangs();
        
        if($security->checkToken($post['security-token']) == FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if(isset($error))
        {
            return $route->errorMessage($error);
        }
        else
        {
            global $twig;
            global $lang;
            
            return $twig->render("/src/Views/game/Ajax/tabs/paypal/donate.btn.twig", array(
                'lang' => $lang,
                'securityToken' => $security->getToken(),
                'env' => PP_ENV,
                'btnID' => PP_BTN_ID,
                'langs' => $l
            ));
        }
    }
    
    public function validateDonation($post)
    { // Ignore security-token on purpose to avoid possible failed donations for an expired token.
        global $route;
        global $language;
        global $langs;
        $l = $language->donationShopLangs();
        
        $paymentID = $post['tx'];

        if($this->data->donationExistsByTxID($paymentID))
        {
            $error = $l['DONATE_REWARDED_ALREADY'];
        }
        if(isset($error))
        {
            return $route->errorMessage($error);
        }
        else
        {
            $sbAdd = "";
            if(PP_SANDBOX)
                $sbAdd = "sandbox.";
            
            $url = "https://api-m." . $sbAdd . "paypal.com/v2/payments/captures/" . $paymentID;
            $clientId = PP_CLIENT;
            $secret = PP_SECRET;
            $auth = $clientId . ":" . $secret;
            
            $headers = [
                "Content-Type: application/json",
                "X-Content-Type-Options:nosniff",
                "Accept:application/json",
                "Cache-Control:no-cache"
            ];
            
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_ENCODING, "utf8");
            curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
            curl_setopt($curl, CURLOPT_TIMEOUT, 0);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($curl, CURLOPT_USERPWD, $auth);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($curl);
            
            curl_close($curl);
            
            $replacedMessage = $l['DONATE_ERROR'];
            if(empty($result)) return $route->errorMessage($replacedMessage);
            else
            {
                $json = json_decode($result);
            }
            
            if(is_object($json) && isset($json->status) && $json->status === "COMPLETED")
            {
                $replacedMessage = $l['DONATE_SUCCESS_LIMIT'];
                $dData = $this->data->getDonationData();
                $crPoss = isset($dData['cr']) ? (int)$dData['cr'] : 0;
                $crLimit = 5000;
                $crDiff = $crLimit - $crPoss;
                $cr = (int)$json->amount->value * 100;
                if($crPoss + $cr < $crLimit && $cr >= 100)
                {
                    $this->data->addDonationCredits($cr);
                    $replacedMessage = $route->replaceMessagePart(number_format($cr, 0, '', ','), $l['DONATE_SUCCESS'], '/{credits}/');
                }
                elseif($crDiff > 0 && $cr >= 100)
                {
                    $this->data->addDonationCredits($crDiff);
                    $replacedMessage = $route->replaceMessagePart(number_format($crDiff, 0, '', ','), $l['DONATE_SUCCESS'], '/{credits}/');
                    $replacedMessage .= " " . $l['DONATE_SUCCESS_HIT_LIMIT'];
                }
                $this->data->saveCompletedDonation($json);
                
                return $route->successMessage($replacedMessage);
            }
            return $route->errorMessage($replacedMessage);
        }
    }
    
    public function leaveDonatorList()
    {
        global $route;
        global $language;
        $l = $language->donationShopLangs();
        
        $this->data->leaveDonatorList();
        return $route->errorMessage($l['LEAVE_DONATOR_LIST_SUCCESS']);
    }
    
    public function donatorListApplication($post)
    {
        global $route;
        global $security;
        global $language;
        global $langs;
        $l = $language->donationShopLangs();
        
        if($security->checkToken($post['security-token']) == FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        
        if(isset($error))
        {
            return $route->errorMessage($error);
        }
        $this->data->donatorListApplication();
        return $route->successMessage($l['DONATOR_LIST_APPLICATION_SUCCESS']);
    }
    
    public function getDonatorList()
    {
        return $this->data->getDonatorList();
    }
    
    public function getDonationData($allTime = false)
    {
        return $this->data->getDonationData($allTime);
    }
    
    public function getDonationShopData()
    {
        return $this->data->getDonationShopData();
    }
}
