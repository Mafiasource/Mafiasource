<?PHP

namespace src\Business\Logic\game\Statics;

use src\Data\config\DBConfig;

class Donator
{
    public function adjustWaitingTime($wt, $donatorID)
    {
        if((date('N') == 5 && date('H') >= 14) || date('N') >= 6 || (date('N') <= 1 && date('H') < 14))
        {
            if($donatorID == 10)
                return $wt *= 0.5;
            elseif($donatorID >= 5 && $donatorID >= 1)
                return $wt *= 0.75;
        }
        elseif(strtotime("2021-01-05 14:00:00") < strtotime('now') && strtotime("2021-01-08 14:00:00") > strtotime('now'))
            return $wt *= 0.5;
        
        return $wt;
    }
}
