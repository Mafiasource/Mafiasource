<?PHP

namespace src\Business;

use src\Business\RedLightDistrictService;
use src\Business\NotificationService;
use src\Data\MarketDAO;
use app\config\Routing;

/* Class has some cleaning up TO DO move as much HTML/CSS/JS as possible into the View layer. */
 
class MarketService
{
    private $data;
    
    public function __construct()
    {
        $this->data = new MarketDAO();
    }
    
    public function __destruct()
    {
        $this->data = null;
    }
    
    public function getRecordsCount()
    {
        return $this->data->getRecordsCount();
    }
    
    public function addOrRequestMarketItem($post)
    {
        global $language;
        global $langs;
        $l = $language->marketLangs();
        global $security;
        global $userData;
        global $route;
        $post['price'] = (int)round($post['price']);
        $post['amount'] = (int)round($post['amount']);
        
        switch($post['category'])
        {
            case 'Credits': $typeID = 0; break;    case 'Hoes': case 'Hoeren': $typeID = 1; break;    case 'Honor Points': case 'Eerpunten': $typeID = 2; break;
        }
        
        if($security->checkToken($post['security-token']) ==  FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if(!isset($typeID) || $typeID < 0 || $typeID > 2)
        {
            $error = $l['WRONG_MARKET_TYPE_SELECTED'];
        }
        if($post['price'] < 250000 || $post['price'] > 9999999999)
        {
            $error = $l['PRICE_RANGE_BETWEEN_250K_9.999B'];
        }
        if($post['amount'] < 25 || $post['amount'] > 10000)
        {
            $error = $l['AMOUNT_RANGE_BETWEEN_25_10K'];
        }
        if($post['requested'] == 0 && isset($typeID))
        {
            $rld = new RedLightDistrictService();
            switch($post['category'])
            {
                case 'Credits': $amountPossession = $userData->getCredits(); break;
                case 'Hoes': case 'Hoeren': $amountPossession = $rld->getRedLightDistrictPageInfo()->getWhoresStreet(); break;
                case 'Honor Points': case 'Eerpunten': $amountPossession = $userData->getHonorPoints(); break;
            }
            if($amountPossession < $post['amount'])
            {
                $error = $replacedMessage = $route->replaceMessagePart(strtolower($post['category']), $l['NOT_ENOUGH_AMOUNT_FOR_SALE'], '/{typeName}/');
            }
        }
        elseif(isset($typeID))
        {
            if($userData->getCash() < $post['price'])
            {
                $error = $langs['NOT_ENOUGH_MONEY_CASH'];
            }
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            $anon = 0;//Init
            $rdContent = "<script type='text/javascript'>if($('input[name=amount]').length){ $('input[name=amount]').val(''); } if($('input[name=price]').length){ $('input[name=price]').val(''); }window.setTimeout(function(){ window.location.href = '".$route->getPrevRoute()."'; }, 3000);</script>";
            if(isset($post['anonymous']) && $post['anonymous'] == 1) $anon = 1;
            if($post['requested'] == 0)
            {
                $this->data->addMarketItem($typeID, $post['amount'], $post['price'], $anon);
                $replacedMessage = $route->replaceMessagePart(strtolower($post['category']), $l['MARKET_ITEM_ADD_SUCCESS'], '/{typeName}/');
                return Routing::successMessage($replacedMessage.$rdContent);
            }
            else
            {
                $this->data->requestMarketItem($typeID, $post['amount'], $post['price'], $anon);
                $replacedMessage = $route->replaceMessagePart(strtolower($post['category']), $l['MARKET_ITEM_REQUEST_SUCCESS'], '/{typeName}/');
                return Routing::successMessage($replacedMessage.$rdContent);
            }
        }
    }
    
    public function buyOrAcceptMarketItem($post)
    {
        global $language;
        global $langs;
        $l        = $language->marketLangs();
        global $security;
        global $userData;
        global $route;
        $post['id'] = (int)round($post['id']);
        
        $itemData = $this->data->getMarketItemById($post['id']);
        
        if($security->checkToken($post['security-token']) ==  FALSE)
        {
            $error = $langs['INVALID_SECURITY_TOKEN'];
        }
        if(!isset($itemData) || empty($itemData))
        {
            $error = $l['MARKET_ITEM_DOESNT_EXIST'];
        }
        else
        {
            if(is_object($itemData) && $itemData->getRequested() == 0)
            {
                if($itemData->getPrice() > $userData->getCash())
                {
                    $error = $langs['NOT_ENOUGH_MONEY_CASH'];
                }
            }
            elseif(is_object($itemData) && $itemData->getRequested() == 1)
            {
                $rld = new RedLightDistrictService();
                switch($itemData->getType())
                {
                    case 0: $amountPossession = $userData->getCredits(); break;
                    case 1: $amountPossession = $rld->getRedLightDistrictPageInfo()->getWhoresStreet(); break;
                    case 2: $amountPossession = $userData->getHonorPoints(); break;
                }
                if($amountPossession < $itemData->getAmount())
                {
                    $error = $replacedMessage = $route->replaceMessagePart(strtolower($this->data->types[$itemData->getType()]), $l['NOT_ENOUGH_AMOUNT_FOR_SALE'], '/{typeName}/');
                }
            }
            if(is_object($itemData) && $itemData->getUserID() == $_SESSION['UID'])
            {
                $error = $l['CANT_BUY_SELL_OWN_MARKET_ITEM'];
            }
        }
        
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            switch($itemData->getType())
            {
                case 0: $noteType = "CREDITS"; break;
                case 1: $noteType = "HOES"; break;
                case 2: $noteType = "HP"; break;
            }
            if($itemData->getRequested() == 0)
            { // MARKET_SOLD_CREDITS_SUCCESS, MARKET_SOLD_HOES_SUCCESS, MARKET_SOLD_HP_SUCCESS
                $notification = new NotificationService();
                $params = "amount=".$itemData->getAmount()."&price=".number_format($itemData->getPrice());
                $notification->sendNotification($itemData->getUserID(), 'MARKET_SOLD_' . $noteType . '_SUCCESS', $params);
                
                $this->data->buyMarketItem($itemData);
                $replacedMessage = $route->replaceMessagePart(number_format($itemData->getPrice()), $l['BOUGHT_MARKET_ITEM_SUCCESS'], '/{price}/');
                $replacedMessage = $route->replaceMessagePart(number_format($itemData->getAmount(), 0, '', ','), $replacedMessage, '/{amount}/');
                $replacedMessage = $route->replaceMessagePart(strtolower($this->data->types[$itemData->getType()]), $replacedMessage, '/{typeName}/');
                return Routing::successMessage($replacedMessage);
            }
            elseif($itemData->getRequested() == 1)
            { // MARKET_BOUGHT_CREDITS_SUCCESS, MARKET_BOUGHT_HOES_SUCCESS, MARKET_BOUGHT_HP_SUCCESS
                $notification = new NotificationService();
                $params = "amount=".$itemData->getAmount()."&price=".number_format($itemData->getPrice());
                $notification->sendNotification($itemData->getUserID(), 'MARKET_BOUGHT_' . $noteType . '_SUCCESS', $params);
                
                $this->data->acceptMarketItem($itemData);
                $replacedMessage = $route->replaceMessagePart(number_format($itemData->getPrice()), $l['ACCEPT_MARKET_ITEM_SUCCESS'], '/{price}/');
                $replacedMessage = $route->replaceMessagePart(number_format($itemData->getAmount(), 0, '', ','), $replacedMessage, '/{amount}/');
                $replacedMessage = $route->replaceMessagePart(strtolower($this->data->types[$itemData->getType()]), $replacedMessage, '/{typeName}/');
                return Routing::successMessage($replacedMessage);
            }
        }
    }
    
    public function getMarketOffersByType($typeID)
    {
        return $this->data->getMarketOffersByType($typeID);
    }
    
    public function getMarketRequestsByType($typeID)
    {
        return $this->data->getMarketRequestsByType($typeID);
    }
}
