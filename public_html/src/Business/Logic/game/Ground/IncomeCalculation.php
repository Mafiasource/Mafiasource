<?PHP

namespace src\Business\Logic\game\Ground;

class IncomeCalculation
{
    public static function getIncomeByLevel($baseIncome, $level)
    {
        $income = $baseIncome;
        if($level == 2)
            $income *= 1.1;
        elseif($level == 3)
            $income *= 1.15;
        elseif($level == 4)
            $income *= 1.2;
        elseif($level == 5)
            $income *= 1.25;
        
        return round($income);
    }
}
