<?PHP

namespace src\Business\Logic\game\Statics;

class Lottery
{
    public $superpotDay = 5; //Superpot day of week CAN NEVER BE 1 (unless changing constructor) range between 2-7
    public $dayPrice = 150000; //Price ticket day lottery
    public $weekPrice = 300000; //Price ticket week lottery
    public $dailyPotRatios = array(1 => "0.58", "0.28", "0.14"); //Daily winning ratios
    public $weeklyPotRatios = array(1 => "0.5", "0.3", "0.1", "0.06", "0.02", "0.008", "0.006", "0.004", "0.002"); //Weekly winning ratios
    public $weeklyDrawDay = false; //Init
    public $weeklyDrawing = false; //Init
    
    public function __construct()
    {
        $this->weeklyDrawDay = date('N') == $this->superpotDay ? true : false; //Draw day (also used in cron)
        $this->weeklyDrawing = (($this->weeklyDrawDay == true  && date('H') < 19) || (date('N') == ($this->superpotDay - 1) && date('H') >= 19)) ? true : false; //Draw day including time
    }
}
