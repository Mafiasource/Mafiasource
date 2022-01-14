<?PHP

namespace src\Business;

use app\config\Routing;
use src\Business\GarageService;
use src\Business\FamilyCrimeService;
use src\Business\FamilyRaidService;
use src\Business\PossessionService;
use src\Business\SmuggleService;
use src\Business\PrisonService;
use src\Data\StateDAO;

class StateService
{
    private $data;
    public $allowedStates = array();
    public $allowedCities = array();
    public $distances = array(
        1 => array("from" => 1, "to" => 2, "kms" => "152"),
        array("from" => 1, "to" => 3, "kms" => "269"),
        array("from" => 1, "to" => 4, "kms" => "4125"),
        array("from" => 1, "to" => 5, "kms" => "4202"),
        array("from" => 1, "to" => 6, "kms" => "3858"),
        array("from" => 1, "to" => 7, "kms" => "7548"),
        array("from" => 1, "to" => 8, "kms" => "7991"),
        array("from" => 1, "to" => 9, "kms" => "7714"),
        array("from" => 1, "to" => 10, "kms" => "5381"),
        array("from" => 1, "to" => 11, "kms" => "5385"),
        array("from" => 1, "to" => 12, "kms" => "5068"),
        array("from" => 1, "to" => 13, "kms" => "6106"),
        array("from" => 1, "to" => 14, "kms" => "5199"),
        array("from" => 1, "to" => 15, "kms" => "5978"),
        array("from" => 1, "to" => 16, "kms" => "7565"),
        array("from" => 1, "to" => 17, "kms" => "7825"),
        array("from" => 1, "to" => 18, "kms" => "8463"),
        
        array("from" => 2, "to" => 1, "kms" => "152"),
        array("from" => 2, "to" => 3, "kms" => "148"),
        array("from" => 2, "to" => 4, "kms" => "4022"),
        array("from" => 2, "to" => 5, "kms" => "4095"),
        array("from" => 2, "to" => 6, "kms" => "3771"),
        array("from" => 2, "to" => 7, "kms" => "7465"),
        array("from" => 2, "to" => 8, "kms" => "7905"),
        array("from" => 2, "to" => 9, "kms" => "7635"),
        array("from" => 2, "to" => 10, "kms" => "5290"),
        array("from" => 2, "to" => 11, "kms" => "5292"),
        array("from" => 2, "to" => 12, "kms" => "4976"),
        array("from" => 2, "to" => 13, "kms" => "5997"),
        array("from" => 2, "to" => 14, "kms" => "5087"),
        array("from" => 2, "to" => 15, "kms" => "5861"),
        array("from" => 2, "to" => 16, "kms" => "7455"),
        array("from" => 2, "to" => 17, "kms" => "7707"),
        array("from" => 2, "to" => 18, "kms" => "8323"),
        
        array("from" => 3, "to" => 1, "kms" => "269"),
        array("from" => 3, "to" => 2, "kms" => "148"),
        array("from" => 3, "to" => 4, "kms" => "4049"),
        array("from" => 3, "to" => 5, "kms" => "4116"),
        array("from" => 3, "to" => 6, "kms" => "3818"),
        array("from" => 3, "to" => 7, "kms" => "7515"),
        array("from" => 3, "to" => 8, "kms" => "7950"),
        array("from" => 3, "to" => 9, "kms" => "7688"),
        array("from" => 3, "to" => 10, "kms" => "5331"),
        array("from" => 3, "to" => 11, "kms" => "5330"),
        array("from" => 3, "to" => 12, "kms" => "5016"),
        array("from" => 3, "to" => 13, "kms" => "6014"),
        array("from" => 3, "to" => 14, "kms" => "5101"),
        array("from" => 3, "to" => 15, "kms" => "5868"),
        array("from" => 3, "to" => 16, "kms" => "7469"),
        array("from" => 3, "to" => 17, "kms" => "7710"),
        array("from" => 3, "to" => 18, "kms" => "8283"),
        
        array("from" => 4, "to" => 1, "kms" => "4125"),
        array("from" => 4, "to" => 2, "kms" => "4022"),
        array("from" => 4, "to" => 3, "kms" => "4049"),
        array("from" => 4, "to" => 5, "kms" => "180"),
        array("from" => 4, "to" => 6, "kms" => "560"),
        array("from" => 4, "to" => 7, "kms" => "3534"),
        array("from" => 4, "to" => 8, "kms" => "3940"),
        array("from" => 4, "to" => 9, "kms" => "3734"),
        array("from" => 4, "to" => 10, "kms" => "1337"),
        array("from" => 4, "to" => 11, "kms" => "1313"),
        array("from" => 4, "to" => 12, "kms" => "1030"),
        array("from" => 4, "to" => 13, "kms" => "1994"),
        array("from" => 4, "to" => 14, "kms" => "1127"),
        array("from" => 4, "to" => 15, "kms" => "1936"),
        array("from" => 4, "to" => 16, "kms" => "3453"),
        array("from" => 4, "to" => 17, "kms" => "3763"),
        array("from" => 4, "to" => 18, "kms" => "4839"),
        
        array("from" => 5, "to" => 1, "kms" => "4202"),
        array("from" => 5, "to" => 2, "kms" => "4095"),
        array("from" => 5, "to" => 3, "kms" => "4116"),
        array("from" => 5, "to" => 4, "kms" => "180"),
        array("from" => 5, "to" => 6, "kms" => "738"),
        array("from" => 5, "to" => 7, "kms" => "3518"),
        array("from" => 5, "to" => 8, "kms" => "3912"),
        array("from" => 5, "to" => 9, "kms" => "3725"),
        array("from" => 5, "to" => 10, "kms" => "1342"),
        array("from" => 5, "to" => 11, "kms" => "1304"),
        array("from" => 5, "to" => 12, "kms" => "1049"),
        array("from" => 5, "to" => 13, "kms" => "1904"),
        array("from" => 5, "to" => 14, "kms" => "1010"),
        array("from" => 5, "to" => 15, "kms" => "1815"),
        array("from" => 5, "to" => 16, "kms" => "3364"),
        array("from" => 5, "to" => 17, "kms" => "3654"),
        array("from" => 5, "to" => 18, "kms" => "4683"),
        
        array("from" => 6, "to" => 1, "kms" => "3858"),
        array("from" => 6, "to" => 2, "kms" => "3771"),
        array("from" => 6, "to" => 3, "kms" => "3818"),
        array("from" => 6, "to" => 4, "kms" => "560"),
        array("from" => 6, "to" => 5, "kms" => "738"),
        array("from" => 6, "to" => 7, "kms" => "3697"),
        array("from" => 6, "to" => 8, "kms" => "4134"),
        array("from" => 6, "to" => 9, "kms" => "3873"),
        array("from" => 6, "to" => 10, "kms" => "1526"),
        array("from" => 6, "to" => 11, "kms" => "1539"),
        array("from" => 6, "to" => 12, "kms" => "1217"),
        array("from" => 6, "to" => 13, "kms" => "2386"),
        array("from" => 6, "to" => 14, "kms" => "1601"),
        array("from" => 6, "to" => 15, "kms" => "2396"),
        array("from" => 6, "to" => 16, "kms" => "3819"),
        array("from" => 6, "to" => 17, "kms" => "4174"),
        array("from" => 6, "to" => 18, "kms" => "5357"),
        
        array("from" => 7, "to" => 1, "kms" => "7548"),
        array("from" => 7, "to" => 2, "kms" => "7465"),
        array("from" => 7, "to" => 3, "kms" => "7515"),
        array("from" => 7, "to" => 4, "kms" => "3534"),
        array("from" => 7, "to" => 5, "kms" => "3518"),
        array("from" => 7, "to" => 6, "kms" => "3697"),
        array("from" => 7, "to" => 8, "kms" => "471"),
        array("from" => 7, "to" => 9, "kms" => "244"),
        array("from" => 7, "to" => 10, "kms" => "2202"),
        array("from" => 7, "to" => 11, "kms" => "2221"),
        array("from" => 7, "to" => 12, "kms" => "2516"),
        array("from" => 7, "to" => 13, "kms" => "1929"),
        array("from" => 7, "to" => 14, "kms" => "2722"),
        array("from" => 7, "to" => 15, "kms" => "2304"),
        array("from" => 7, "to" => 16, "kms" => "1419"),
        array("from" => 7, "to" => 17, "kms" => "1910"),
        array("from" => 7, "to" => 18, "kms" => "3761"),
        
        array("from" => 8, "to" => 1, "kms" => "7991"),
        array("from" => 8, "to" => 2, "kms" => "7905"),
        array("from" => 8, "to" => 3, "kms" => "7950"),
        array("from" => 8, "to" => 4, "kms" => "3940"),
        array("from" => 8, "to" => 5, "kms" => "3912"),
        array("from" => 8, "to" => 6, "kms" => "4134"),
        array("from" => 8, "to" => 7, "kms" => "471"),
        array("from" => 8, "to" => 9, "kms" => "441"),
        array("from" => 8, "to" => 10, "kms" => "2622"),
        array("from" => 8, "to" => 11, "kms" => "2631"),
        array("from" => 8, "to" => 12, "kms" => "2937"),
        array("from" => 8, "to" => 13, "kms" => "2208"),
        array("from" => 8, "to" => 14, "kms" => "3061"),
        array("from" => 8, "to" => 15, "kms" => "2548"),
        array("from" => 8, "to" => 16, "kms" => "1346"),
        array("from" => 8, "to" => 17, "kms" => "1760"),
        array("from" => 8, "to" => 18, "kms" => "3559"),
        
        array("from" => 9, "to" => 1, "kms" => "7714"),
        array("from" => 9, "to" => 2, "kms" => "7635"),
        array("from" => 9, "to" => 3, "kms" => "7688"),
        array("from" => 9, "to" => 4, "kms" => "3734"),
        array("from" => 9, "to" => 5, "kms" => "3725"),
        array("from" => 9, "to" => 6, "kms" => "3873"),
        array("from" => 9, "to" => 7, "kms" => "244"),
        array("from" => 9, "to" => 8, "kms" => "441"),
        array("from" => 9, "to" => 10, "kms" => "2398"),
        array("from" => 9, "to" => 11, "kms" => "2423"),
        array("from" => 9, "to" => 12, "kms" => "2710"),
        array("from" => 9, "to" => 13, "kms" => "2171"),
        array("from" => 9, "to" => 14, "kms" => "2952"),
        array("from" => 9, "to" => 15, "kms" => "2547"),
        array("from" => 9, "to" => 16, "kms" => "1613"),
        array("from" => 9, "to" => 17, "kms" => "2083"),
        array("from" => 9, "to" => 18, "kms" => "3921"),
        
        array("from" => 10, "to" => 1, "kms" => "5381"),
        array("from" => 10, "to" => 2, "kms" => "5290"),
        array("from" => 10, "to" => 3, "kms" => "5331"),
        array("from" => 10, "to" => 4, "kms" => "1337"),
        array("from" => 10, "to" => 5, "kms" => "1342"),
        array("from" => 10, "to" => 6, "kms" => "1526"),
        array("from" => 10, "to" => 7, "kms" => "2202"),
        array("from" => 10, "to" => 8, "kms" => "2622"),
        array("from" => 10, "to" => 9, "kms" => "2398"),
        array("from" => 10, "to" => 11, "kms" => "102"),
        array("from" => 10, "to" => 12, "kms" => "315"),
        array("from" => 10, "to" => 13, "kms" => "1068"),
        array("from" => 10, "to" => 14, "kms" => "898"),
        array("from" => 10, "to" => 15, "kms" => "1293"),
        array("from" => 10, "to" => 16, "kms" => "2361"),
        array("from" => 10, "to" => 17, "kms" => "2780"),
        array("from" => 10, "to" => 18, "kms" => "4252"),
        
        array("from" => 11, "to" => 1, "kms" => "5385"),
        array("from" => 11, "to" => 2, "kms" => "5292"),
        array("from" => 11, "to" => 3, "kms" => "5330"),
        array("from" => 11, "to" => 4, "kms" => "1313"),
        array("from" => 11, "to" => 5, "kms" => "1304"),
        array("from" => 11, "to" => 6, "kms" => "1539"),
        array("from" => 11, "to" => 7, "kms" => "2221"),
        array("from" => 11, "to" => 8, "kms" => "2631"),
        array("from" => 11, "to" => 9, "kms" => "2423"),
        array("from" => 11, "to" => 10, "kms" => "102"),
        array("from" => 11, "to" => 12, "kms" => "324"),
        array("from" => 11, "to" => 13, "kms" => "989"),
        array("from" => 11, "to" => 14, "kms" => "802"),
        array("from" => 11, "to" => 15, "kms" => "1198"),
        array("from" => 11, "to" => 16, "kms" => "2316"),
        array("from" => 11, "to" => 17, "kms" => "2723"),
        array("from" => 11, "to" => 18, "kms" => "4169"),
        
        array("from" => 12, "to" => 1, "kms" => "5068"),
        array("from" => 12, "to" => 2, "kms" => "4976"),
        array("from" => 12, "to" => 3, "kms" => "5016"),
        array("from" => 12, "to" => 4, "kms" => "1030"),
        array("from" => 12, "to" => 5, "kms" => "1049"),
        array("from" => 12, "to" => 6, "kms" => "1217"),
        array("from" => 12, "to" => 7, "kms" => "2516"),
        array("from" => 12, "to" => 8, "kms" => "2937"),
        array("from" => 12, "to" => 9, "kms" => "2710"),
        array("from" => 12, "to" => 10, "kms" => "315"),
        array("from" => 12, "to" => 11, "kms" => "324"),
        array("from" => 12, "to" => 13, "kms" => "1268"),
        array("from" => 12, "to" => 14, "kms" => "834"),
        array("from" => 12, "to" => 15, "kms" => "1415"),
        array("from" => 12, "to" => 16, "kms" => "2634"),
        array("from" => 12, "to" => 17, "kms" => "3030"),
        array("from" => 12, "to" => 18, "kms" => "4421"),
        
        array("from" => 13, "to" => 1, "kms" => "6106"),
        array("from" => 13, "to" => 2, "kms" => "5997"),
        array("from" => 13, "to" => 3, "kms" => "6014"),
        array("from" => 13, "to" => 4, "kms" => "1994"),
        array("from" => 13, "to" => 5, "kms" => "1904"),
        array("from" => 13, "to" => 6, "kms" => "2386"),
        array("from" => 13, "to" => 7, "kms" => "1929"),
        array("from" => 13, "to" => 8, "kms" => "2208"),
        array("from" => 13, "to" => 9, "kms" => "2171"),
        array("from" => 13, "to" => 10, "kms" => "1068"),
        array("from" => 13, "to" => 11, "kms" => "989"),
        array("from" => 13, "to" => 12, "kms" => "1268"),
        array("from" => 13, "to" => 14, "kms" => "919"),
        array("from" => 13, "to" => 15, "kms" => "407"),
        array("from" => 13, "to" => 16, "kms" => "1461"),
        array("from" => 13, "to" => 17, "kms" => "1789"),
        array("from" => 13, "to" => 18, "kms" => "3185"),
        
        array("from" => 14, "to" => 1, "kms" => "5199"),
        array("from" => 14, "to" => 2, "kms" => "5087"),
        array("from" => 14, "to" => 3, "kms" => "5101"),
        array("from" => 14, "to" => 4, "kms" => "1127"),
        array("from" => 14, "to" => 5, "kms" => "1010"),
        array("from" => 14, "to" => 6, "kms" => "1601"),
        array("from" => 14, "to" => 7, "kms" => "2722"),
        array("from" => 14, "to" => 8, "kms" => "3061"),
        array("from" => 14, "to" => 9, "kms" => "2952"),
        array("from" => 14, "to" => 10, "kms" => "898"),
        array("from" => 14, "to" => 11, "kms" => "802"),
        array("from" => 14, "to" => 12, "kms" => "834"),
        array("from" => 14, "to" => 13, "kms" => "919"),
        array("from" => 14, "to" => 15, "kms" => "809"),
        array("from" => 14, "to" => 16, "kms" => "2368"),
        array("from" => 14, "to" => 17, "kms" => "2644"),
        array("from" => 14, "to" => 18, "kms" => "3765"),
        
        array("from" => 15, "to" => 1, "kms" => "5978"),
        array("from" => 15, "to" => 2, "kms" => "5861"),
        array("from" => 15, "to" => 3, "kms" => "5868"),
        array("from" => 15, "to" => 4, "kms" => "1936"),
        array("from" => 15, "to" => 5, "kms" => "1815"),
        array("from" => 15, "to" => 6, "kms" => "2396"),
        array("from" => 15, "to" => 7, "kms" => "2304"),
        array("from" => 15, "to" => 8, "kms" => "2548"),
        array("from" => 15, "to" => 9, "kms" => "2547"),
        array("from" => 15, "to" => 10, "kms" => "1293"),
        array("from" => 15, "to" => 11, "kms" => "1198"),
        array("from" => 15, "to" => 12, "kms" => "1415"),
        array("from" => 15, "to" => 13, "kms" => "407"),
        array("from" => 15, "to" => 14, "kms" => "809"),
        array("from" => 15, "to" => 16, "kms" => "1627"),
        array("from" => 15, "to" => 17, "kms" => "1849"),
        array("from" => 15, "to" => 18, "kms" => "3013"),
        
        array("from" => 16, "to" => 1, "kms" => "7565"),
        array("from" => 16, "to" => 2, "kms" => "7455"),
        array("from" => 16, "to" => 3, "kms" => "7469"),
        array("from" => 16, "to" => 4, "kms" => "3453"),
        array("from" => 16, "to" => 5, "kms" => "3364"),
        array("from" => 16, "to" => 6, "kms" => "3819"),
        array("from" => 16, "to" => 7, "kms" => "1419"),
        array("from" => 16, "to" => 8, "kms" => "1346"),
        array("from" => 16, "to" => 9, "kms" => "1613"),
        array("from" => 16, "to" => 10, "kms" => "2361"),
        array("from" => 16, "to" => 11, "kms" => "2316"),
        array("from" => 16, "to" => 12, "kms" => "2634"),
        array("from" => 16, "to" => 13, "kms" => "1461"),
        array("from" => 16, "to" => 14, "kms" => "2368"),
        array("from" => 16, "to" => 15, "kms" => "1627"),
        array("from" => 16, "to" => 17, "kms" => "529"),
        array("from" => 16, "to" => 18, "kms" => "2375"),
        
        array("from" => 17, "to" => 1, "kms" => "7825"),
        array("from" => 17, "to" => 2, "kms" => "7707"),
        array("from" => 17, "to" => 3, "kms" => "7710"),
        array("from" => 17, "to" => 4, "kms" => "3763"),
        array("from" => 17, "to" => 5, "kms" => "3654"),
        array("from" => 17, "to" => 6, "kms" => "4174"),
        array("from" => 17, "to" => 7, "kms" => "1910"),
        array("from" => 17, "to" => 8, "kms" => "1760"),
        array("from" => 17, "to" => 9, "kms" => "2083"),
        array("from" => 17, "to" => 10, "kms" => "2780"),
        array("from" => 17, "to" => 11, "kms" => "2723"),
        array("from" => 17, "to" => 12, "kms" => "3030"),
        array("from" => 17, "to" => 13, "kms" => "1789"),
        array("from" => 17, "to" => 14, "kms" => "2644"),
        array("from" => 17, "to" => 15, "kms" => "1849"),
        array("from" => 17, "to" => 16, "kms" => "529"),
        array("from" => 17, "to" => 18, "kms" => "1857"),
        
        array("from" => 18, "to" => 1, "kms" => "8463"),
        array("from" => 18, "to" => 2, "kms" => "8323"),
        array("from" => 18, "to" => 3, "kms" => "8283"),
        array("from" => 18, "to" => 4, "kms" => "4839"),
        array("from" => 18, "to" => 5, "kms" => "4683"),
        array("from" => 18, "to" => 6, "kms" => "5357"),
        array("from" => 18, "to" => 7, "kms" => "3761"),
        array("from" => 18, "to" => 8, "kms" => "3559"),
        array("from" => 18, "to" => 9, "kms" => "3921"),
        array("from" => 18, "to" => 10, "kms" => "4252"),
        array("from" => 18, "to" => 11, "kms" => "4169"),
        array("from" => 18, "to" => 12, "kms" => "4421"),
        array("from" => 18, "to" => 13, "kms" => "3185"),
        array("from" => 18, "to" => 14, "kms" => "3765"),
        array("from" => 18, "to" => 15, "kms" => "3013"),
        array("from" => 18, "to" => 16, "kms" => "2375"),
        array("from" => 18, "to" => 17, "kms" => "1857"),
    );
    
    public function __construct()
    {
        $this->data = new StateDAO();
        
        $states = $this->data->getStates();
        $cities = $this->data->getCities();
        
        foreach($states AS $state)
            $this->allowedStates[] = $state->getName();
        
        foreach($cities AS $city)
            $this->allowedCities[] = $city->getName();
    }
    
    public function __destruct()
    {
        $this->data = null;
    }
    
    public function calculatePrice($fromID, $toID, $type = false, $raw = false)
    {
        global $language;
        $l        = $language->travelLangs();
        if($type == false) $type = "airplane";
        if(isset($_SESSION['UID']))
        {
            foreach($this->distances AS $row)
            {
                if($row['from'] == $fromID && $row['to'] == $toID) $kms = $row['kms'];
            }
            if(isset($kms))
            {
                switch($type)
                {
                    case "airplane":
                        $price = $kms * 0.75;
                        $sec = $kms * 0.025;
                        break;
                    case "train":
                        $price = $kms * 0.5;
                        $sec = $kms * 0.05;
                        break;
                    case "bus":
                        $price = $kms * 0.25;
                        $sec = $kms * 0.075;
                        break;
                    case "vehicle":
                        $price = $kms * 0.05;
                        $sec = $kms * 0.066;
                        break;
                    default:
                        $price = $kms * 0.75;
                        $sec = $kms * 0.025;
                        break;
                }
            }
            if(isset($price) && isset($kms) && isset($sec))
            {
                global $route;
                
                if(!$raw)
                    global $twig;
                
                $routeNotPossible = (
                    (    ($toID == 1 && $fromID !== 1 && $fromID !== 2 && $fromID !== 3)
                      || ($fromID == 1 && $toID !== 1 && $toID !== 2 && $toID !== 3)
                      || ($toID == 2 && $fromID !== 1 && $fromID !== 2 && $fromID !== 3)
                      || ($fromID == 2 && $toID !== 1 && $toID !== 2 && $toID !== 3)
                      || ($toID == 3 && $fromID !== 1 && $fromID !== 2 && $fromID !== 3)
                      || ($fromID == 3 && $toID !== 1 && $toID !== 2 && $toID !== 3)
                    )
                    && ($type == "bus" || $type == "train" || $type == "vehicle")
                ); // True / False
                if($routeNotPossible === true && $raw == false)
                    return $twig->render("/src/Views/game/Ajax/travel.city.possible.twig", array('routeNotPossible' => true, 'langs' => $l));
                elseif($routeNotPossible === false && $raw == false)
                    return $twig->render("/src/Views/game/Ajax/travel.city.possible.twig", array('routeNotPossible' => false, 'langs' => $l,
                        'city' => $this->data->getCityNameById($toID), 'price' => $price));
                elseif($routeNotPossible === true)
                    return FALSE;
                else
                {
                    $arr = array('price' => $price, 'sec' => $sec);
                    return $arr;
                }
            }
        }
    }
    
    public function handleTravel($post)
    {
        global $language;
        global $langs;
        $l        = $language->travelLangs();
        global $security;
        global $userData;
        $cityID = (int)$post['cityID'];
        $cityTo  = $this->data->getCityNameById($cityID);
        $type = $security->xssEscape($post['type']);
        if(isset($post['vehicle'])) $garageVehicleID = (int)$post['vehicle'];
        
        $famCrimeService = new FamilyCrimeService();
        $stateID = $this->getStateIdByCityId($cityID);
        
        $famRaidService = new FamilyRaidService();
        
        if($_POST['security-token'] != $security->getToken())
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
        if(!in_array($cityTo, $this->allowedCities))
        {
            $error = $l['INVALID_DESTINATION'];
        }
        if($cityTo == false)
        {
            $error =  $l['INVALID_DESTINATION'];
        }
        if($famCrimeService->userInsideFamilyCrime())
        {
            if($userData->getStateID() !== $stateID)
                $error = $l['CANNOT_TRAVEL_WHEN_IN_CRIME'];
        }
        if($famRaidService->userInsideAcceptedFamilyRaid())
        {
            if($userData->getStateID() !== $stateID)
                $error = $l['CANNOT_TRAVEL_WHEN_IN_RAID'];
        }
        
        $arr = $this->calculatePrice($userData->getCityID(), $cityID, $type, true);
        
        if($type == "vehicle")
        {
            $garage = new GarageService();
            if(!$garage->hasSpaceLeftInGarage($stateID))
            {
                if($userData->getStateID() !== $stateID)
                    $error = $l['TRAVEL_VEHICLE_NO_SPACE_GARAGE'];
            }
            if(!$garage->hasGarageInState($stateID))
            {
                if($userData->getStateID() !== $stateID)
                    $error = $l['TRAVEL_VEHICLE_NO_GARAGE'];
            }
            $inGarage = $garage->isVehicleInGarageInState($userData->getStateID(), $garageVehicleID);
            if($inGarage == FALSE)
            {
                $error = $l['TRAVEL_VEHICLE_NO_VEHICLE'];
            }
        }
        if($arr == FALSE)
        {
            $error = $l['ROUTE_NOT_POSSIBLE'];
        }
        else
        {
            $sec = $arr['sec'];
            $price = $arr['price'];
            if($userData->getCash() < $price)
            {
                $error = $langs['NOT_ENOUGH_MONEY_CASH'];
            }
            if($userData->getCTravelTime() > time())
            {
                global $route;
                
                global $lang;
                $counter = "enkele";
                if($lang == "en") $counter = "a few";
                $error = $route->replaceMessagePart($counter, $langs['TRAVELING'], '/{sec}/');
            }
        }
        $smuggleService = new SmuggleService($userData->getCityID(), 'drugs', $userData->getDonatorID());
        $units = $smuggleService->getSmugglingUnitsInPossession();
        $sPossess = array(1 => 0, 0, 0, 0, 0);
        foreach($units AS $u)
            $sPossess[$u->getTypeNr()] += $u->getInPossession();
        
        $randMaxes = array(90, 110, 130, 150);
        if($userData->getCharType() == 6)
            $randMaxes = array(100, 120, 140, 160);
        
        $catchRand = $security->randInt(1, $randMaxes[0]);
        if($type == "train")
            $catchRand = $security->randInt(2, $randMaxes[1]);
        elseif($type == "bus")
            $catchRand = $security->randInt(5, $randMaxes[2]);
        elseif($type == "vehicle")
            $catchRand = $security->randInt(9, $randMaxes[3]);
            
        $max = (int)(($userData->getRankID() + '1' ) * 100) + ($userData->getSmugglingCapacity() * 100);
        if($userData->getCBribingPolice() < time() && (
          $catchRand < 20 && count($units) > 0 ||
            ($sPossess[1] > $max || $sPossess[1] < 0) || ($sPossess[2] > $max || $sPossess[2] < 0) || ($sPossess[3] > $max || $sPossess[3] < 0) ||
            ($sPossess[4] > $max || $sPossess[4] < 0) || ($sPossess[5] > $max || $sPossess[5] < 0)
          )
        )
        {
            $prison = new PrisonService();
            $prison->putUserInPrison($userData->getId(), time() + 120);
            $smuggleService->removeAllSmugglingUnits();
            $error = $l['CAUGHT_BY_BORDER_PATROL'];
        }
        
        if(isset($error))
        {
            return array("error" => Routing::errorMessage($error));
        }
        else
        {
            global $route;
            
            $possession = new PossessionService();
            $possessionId = 4; //Reisbureau | Possession logic
            $possessId = $possession->getPossessIdByPossessionId($possessionId, $userData->getStateID(), $userData->getCityID()); // Possess table record id
            $pData = $possession->getPossessionByPossessId($possessId); // Possession table data + possess table data

            if($type == "vehicle")
            {
                if($userData->getStateID() !== $stateID)
                    $garage->moveVehicleToGarageInState($garageVehicleID, $stateID);
            }
            $this->data->travelTo($this->data->getStateIdByCityId($cityID), $cityID, $sec, $price, $pData);
            
            $replacedMessage = $route->replaceMessagePart('<strong>'.$cityTo.'</strong>', $l['TRAVEL_TO_SUCCESS'], '/{state}/');
            $replacedMessage = $route->replaceMessagePart(number_format($price, 0, '', ','), $replacedMessage, '/{price}/');
            $replacedMessage = $route->replaceMessagePart(round($sec), $replacedMessage, '/{sec}/');
            return Routing::successMessage($replacedMessage);
        }
    }
    
    public function getRandCityIdByStateId($stateID)
    {
        global $security;
        $min = 1;
        if($stateID > 1)
            $min = (($stateID - 1) * 3) + 1;
        
        $max = $min + 2;
        return $security->randInt($min, $max);
        //return $this->data->getRandCityIdByStateId($stateID);
    }
    
    public function getStatesButHomestate($stateID)
    {
        return $this->data->getStatesButHomestate($stateID);
    }
    
    public function getStates()
    {
        return $this->data->getStates();
    }
    
    public function getCitiesButHomeCity($cityID)
    {
        return $this->data->getCitiesButHomeCity($cityID);
    }
    
    public function getCities()
    {
        return $this->data->getCities();
    }
    
    public function getStateNameById($id)
    {
        return $this->data->getStateNameById($id);
    }
    
    public function getCityNameById($id)
    {
        return $this->data->getCityNameById($id);
    }
    
    public function getStateIdByName($name)
    {
        return $this->data->getStateIdByName($name);
    }
    
    public function getCityIdByName($name)
    {
        return $this->data->getCityIdByName($name);
    }
    
    public function getStateIdByCityId($id)
    {
        return $this->data->getStateIdByCityId($id);
    }
}