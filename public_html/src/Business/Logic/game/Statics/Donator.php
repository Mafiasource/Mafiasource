<?PHP

namespace src\Business\Logic\game\Statics;

use src\Data\config\DBConfig;

class Donator
{
    public function adjustWaitingTime($wt, $donatorID, $halvingTimesTime = 0)
    {
        // Halving times for donators and up, weekends
        if(((date('N') == 5 && date('H') >= 14) || (date('N') == 1 && date('H') < 14)) || date('N') >= 6)
        {
            if($donatorID == 10)
                $wt *= 0.5;
            elseif($donatorID <= 5 && $donatorID >= 1)
                $wt *= 0.75;
        }
        
        // User halving times from donationshop
        if($halvingTimesTime > time())
            $wt *= 0.5;
        
        // Halving times event example
        if(strtotime("2022-05-17 14:00:00") < strtotime('now') && strtotime("2023-05-22 14:00:00") > strtotime('now'))
            $wt *= 0.5;
        
        return round($wt);
    }
}
