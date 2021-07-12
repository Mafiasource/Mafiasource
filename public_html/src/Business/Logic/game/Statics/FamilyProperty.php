<?PHP

namespace src\Business\Logic\game\Statics;

class FamilyProperty
{
    /* Prop statics */
    public $price = 15000000; // Price for bulletfactory or brothel 15M
    public $upgradePrices = array(1 => 150000, 200000, 250000, 300000, 400000, 550000, 800000, 1000000, 1300000, 1700000, 2200000, 2800000, 3500000, 4300000);
    /* BF statics */
    public $bfCapacities = array(0 => 0, 100000, 110000, 130000, 160000, 200000, 250000, 310000, 380000, 460000, 550000, 650000, 760000, 880000, 1010000, 1150000);
    public $bfProductionCosts = array(
        '500' => 250000,
        '2500' => 350000,
        '5000' => 1000000,
        '25000' => 3000000,
        '50000' => 5000000,
        '250000' => 16500000,
        '500000' => 30000000
    );
    public $bfProductions = array(0 => 0); // Init
    public $bfProductionPrices = array(0 => 0); // Init
    /* Brothel statics */
    public $brothelCapacities = array(0 => 0, 5000, 7000, 10000, 14000, 19000, 25000, 32000, 40000, 49000, 59000, 70000, 82000, 95000, 109000, 123000);
    
    public function __construct()
    {
        foreach($this->bfProductionCosts AS $p => $c)
        {
            array_push($this->bfProductions, $p);
            array_push($this->bfProductionPrices, $c);
        }
    }
}
