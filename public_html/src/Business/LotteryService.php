<?PHP

namespace src\Business;

use app\config\Routing;
use src\Business\PossessionService;
use src\Business\Logic\game\Statics\Lottery AS LotteryStatics;
use src\Business\DailyChallengeService;
use src\Data\LotteryDAO;
 
class LotteryService extends LotteryStatics
{
    private $data;
    
    public function __construct()
    {
        parent::__construct();
        $this->data = new LotteryDAO();
    }
    
    public function __destruct()
    {
        $this->data = null;
    }
    
    public function getRecordsCount($type)
    {
        return $this->data->getRecordsCount($type);
    }
    
    public function buyTicket($post)
    {
        global $security;
        global $userData;
        global $language;
        global $route;
        global $langs;
        $l        = $language->lotteryLangs();
        $type = (int)$post['type'];
        
        $price = $type == 1 ? $this->weekPrice : $this->dayPrice;
        $typeName = $type == 1 ? "Week" : $l['DAY'];
        
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
        if($userData->getCash() < $price)
        {
            $error = $langs['NOT_ENOUGH_MONEY_CASH'];
        }
        if($type == 0 && $this->weeklyDrawing == true)
        {
            $error = $l['DAILY_AFTER_SUPERPOT'];
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            $possession = new PossessionService();
            $possessionId = 18; //Loterij | Possession logic
            $possessId = $possession->getPossessIdByPossessionId($possessionId); // Possess table record id
            $pData = $possession->getPossessionByPossessId($possessId); // Possession table data + possess table data
            
            $dailyChallengeService = new DailyChallengeService();
            $dailyChallengeService->addToDailiesIfActive(7, $price);
            
            $this->data->buyTicket($type, $price, $pData);
            $replaces = array(
                array('part' => strtolower($typeName), 'message' => $l['BOUGHT_TICKET_SUCCESS'], 'pattern' => '/{type}/'),
                array('part' => number_format($price, 0, '', ','), 'message' => FALSE, 'pattern' => '/{price}/')
            );
            $replacedMessage = $route->replaceMessageParts($replaces);
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function getLotteryTicketByType($typeID)
    {
        return $this->data->getLotteryTicketByType($typeID);
    }
    
    public function getLastLotteryWinnersByType($typeID)
    {
        return $this->data->getLastLotteryWinnersByType($typeID);
    }
}
