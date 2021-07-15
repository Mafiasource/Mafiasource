<?PHP

namespace src\Business\Logic\game\Statics;

class BulletFactory
{
    public $productionCosts = array(
        '1000' => 500000,
        '5000' => 700000,
        '10000' => 2000000,
        '50000' => 6000000,
        '100000' => 10000000,
        '500000' => 33000000,
        '1000000' => 60000000
    ); // Statics used in cronjobs
    public $productionPrices = array(0 => 0); // Statics used in Possession Management (PossessionService) only includes the extra '0' => 0
    
    public function __construct()
    {
        foreach($this->productionCosts AS $p => $c)
            $this->productionPrices[$p] = $c;
    }
}
