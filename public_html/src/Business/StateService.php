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

/* Class has some cleaning up TO DO move as much HTML/CSS/JS as possible into the View layer. */

class StateService
{
    private $data;

    //TODO OVERSETTEN NAAR EEN JSON OF ENV FILE.
    private $countryArray =  array(
        "Hawaii" => array("Honolulu", "Kahului", "Kailua Kona"),
        "California" => array("Los Angeles", "San Diego", "San Fransisco"),
        "New York" => array("Buffalo", "NY City", "Kingston"),
        "Colorado" => array("Denver", "Colorado Springs", "Grand Junction"),        
        "Texas" => array( "Dallas", "El Paso", "San Antonio"),
        "Florida" => array("Jacksonville", "Miami", "Panama City")
    );
    private $countryData;

    //TODO VERVANGEN DOOR ZOALS ZIE COUNTRY ARRAY
    public $allowedStates = array("Hawaii", "California", "New York", "Colorado", "Texas", "Florida");
    public $allowedCities = array("Honolulu", "Kahului", "Kailua Kona", "Los Angeles", "San Diego", "San Fransisco", "Buffalo", "NY City", "Kingston",  "Denver", "Colorado Springs", "Grand Junction",  "Dallas", "El Paso", "San Antonio", "Jacksonville", "Miami", "Panama City");
    
    /*
        TODO GEBRUIK MAKEN VAN DE X & Y VAN GOOGLE MAPS EN DAAR OP EEN BEREKENING MAKEN (x - x2) - (y - y2)
    */
    public $distances = array(

        /*
            JSON VOORBEELD  hawaii : {
                [0] : {
                    naam : "Honolulu",
                    gcoord : array("x" => 123, "y" => 321) (google coords)
                }
            }
        */
        1 => array("from" => "Honolulu", "to" => "Kahului", "kms" => "152"),
        array("from" => "Honolulu", "to" => "Kailua Kona", "kms" => "269"),
        array("from" => "Honolulu", "to" => "Los Angeles", "kms" => "4125"),
        array("from" => "Honolulu", "to" => "San Diego", "kms" => "4202"),
        array("from" => "Honolulu", "to" => "San Fransisco", "kms" => "3858"),
        array("from" => "Honolulu", "to" => "Buffalo", "kms" => "7548"),
        array("from" => "Honolulu", "to" => "NY City", "kms" => "7991"),
        array("from" => "Honolulu", "to" => "Kingston", "kms" => "7714"),
        array("from" => "Honolulu", "to" => "Denver", "kms" => "5381"),
        array("from" => "Honolulu", "to" => "Colorado Springs", "kms" => "5385"),
        array("from" => "Honolulu", "to" => "Grand Junction", "kms" => "5068"),
        array("from" => "Honolulu", "to" => "Dallas", "kms" => "6106"),
        array("from" => "Honolulu", "to" => "El Paso", "kms" => "5199"),
        array("from" => "Honolulu", "to" => "San Antonio", "kms" => "5978"),
        array("from" => "Honolulu", "to" => "Jacksonville", "kms" => "7565"),
        array("from" => "Honolulu", "to" => "Miami", "kms" => "7825"),
        array("from" => "Honolulu", "to" => "Panama City", "kms" => "8463"),
        
        array("from" => "Kahului", "to" => "Honolulu", "kms" => "152"),
        array("from" => "Kahului", "to" => "Kailua Kona", "kms" => "148"),
        array("from" => "Kahului", "to" => "Los Angeles", "kms" => "4022"),
        array("from" => "Kahului", "to" => "San Diego", "kms" => "4095"),
        array("from" => "Kahului", "to" => "San Fransisco", "kms" => "3771"),
        array("from" => "Kahului", "to" => "Buffalo", "kms" => "7465"),
        array("from" => "Kahului", "to" => "NY City", "kms" => "7905"),
        array("from" => "Kahului", "to" => "Kingston", "kms" => "7635"),
        array("from" => "Kahului", "to" => "Denver", "kms" => "5290"),
        array("from" => "Kahului", "to" => "Colorado Springs", "kms" => "5292"),
        array("from" => "Kahului", "to" => "Grand Junction", "kms" => "4976"),
        array("from" => "Kahului", "to" => "Dallas", "kms" => "5997"),
        array("from" => "Kahului", "to" => "El Paso", "kms" => "5087"),
        array("from" => "Kahului", "to" => "San Antonio", "kms" => "5861"),
        array("from" => "Kahului", "to" => "Jacksonville", "kms" => "7455"),
        array("from" => "Kahului", "to" => "Miami", "kms" => "7707"),
        array("from" => "Kahului", "to" => "Panama City", "kms" => "8323"),
        
        array("from" => "Kailua Kona", "to" => "Honolulu", "kms" => "269"),
        array("from" => "Kailua Kona", "to" => "Kahului", "kms" => "148"),
        array("from" => "Kailua Kona", "to" => "Los Angeles", "kms" => "4049"),
        array("from" => "Kailua Kona", "to" => "San Diego", "kms" => "4116"),
        array("from" => "Kailua Kona", "to" => "San Fransisco", "kms" => "3818"),
        array("from" => "Kailua Kona", "to" => "Buffalo", "kms" => "7515"),
        array("from" => "Kailua Kona", "to" => "NY City", "kms" => "7950"),
        array("from" => "Kailua Kona", "to" => "Kingston", "kms" => "7688"),
        array("from" => "Kailua Kona", "to" => "Denver", "kms" => "5331"),
        array("from" => "Kailua Kona", "to" => "Colorado Springs", "kms" => "5330"),
        array("from" => "Kailua Kona", "to" => "Grand Junction", "kms" => "5016"),
        array("from" => "Kailua Kona", "to" => "Dallas", "kms" => "6014"),
        array("from" => "Kailua Kona", "to" => "El Paso", "kms" => "5101"),
        array("from" => "Kailua Kona", "to" => "San Antonio", "kms" => "5868"),
        array("from" => "Kailua Kona", "to" => "Jacksonville", "kms" => "7469"),
        array("from" => "Kailua Kona", "to" => "Miami", "kms" => "7710"),
        array("from" => "Kailua Kona", "to" => "Panama City", "kms" => "8283"),
        
        array("from" => "Los Angeles", "to" => "Honolulu", "kms" => "4125"),
        array("from" => "Los Angeles", "to" => "Kahului", "kms" => "4022"),
        array("from" => "Los Angeles", "to" => "Kailua Kona", "kms" => "4049"),
        array("from" => "Los Angeles", "to" => "San Diego", "kms" => "180"),
        array("from" => "Los Angeles", "to" => "San Fransisco", "kms" => "560"),
        array("from" => "Los Angeles", "to" => "Buffalo", "kms" => "3534"),
        array("from" => "Los Angeles", "to" => "NY City", "kms" => "3940"),
        array("from" => "Los Angeles", "to" => "Kingston", "kms" => "3734"),
        array("from" => "Los Angeles", "to" => "Denver", "kms" => "1337"),
        array("from" => "Los Angeles", "to" => "Colorado Springs", "kms" => "1313"),
        array("from" => "Los Angeles", "to" => "Grand Junction", "kms" => "1030"),
        array("from" => "Los Angeles", "to" => "Dallas", "kms" => "1994"),
        array("from" => "Los Angeles", "to" => "El Paso", "kms" => "1127"),
        array("from" => "Los Angeles", "to" => "San Antonio", "kms" => "1936"),
        array("from" => "Los Angeles", "to" => "Jacksonville", "kms" => "3453"),
        array("from" => "Los Angeles", "to" => "Miami", "kms" => "3763"),
        array("from" => "Los Angeles", "to" => "Panama City", "kms" => "4839"),
        
        array("from" => "San Diego", "to" => "Honolulu", "kms" => "4202"),
        array("from" => "San Diego", "to" => "Kahului", "kms" => "4095"),
        array("from" => "San Diego", "to" => "Kailua Kona", "kms" => "4116"),
        array("from" => "San Diego", "to" => "Los Angeles", "kms" => "180"),
        array("from" => "San Diego", "to" => "San Fransisco", "kms" => "738"),
        array("from" => "San Diego", "to" => "Buffalo", "kms" => "3518"),
        array("from" => "San Diego", "to" => "NY City", "kms" => "3912"),
        array("from" => "San Diego", "to" => "Kingston", "kms" => "3725"),
        array("from" => "San Diego", "to" => "Denver", "kms" => "1342"),
        array("from" => "San Diego", "to" => "Colorado Springs", "kms" => "1304"),
        array("from" => "San Diego", "to" => "Grand Junction", "kms" => "1049"),
        array("from" => "San Diego", "to" => "Dallas", "kms" => "1904"),
        array("from" => "San Diego", "to" => "El Paso", "kms" => "1010"),
        array("from" => "San Diego", "to" => "San Antonio", "kms" => "1815"),
        array("from" => "San Diego", "to" => "Jacksonville", "kms" => "3364"),
        array("from" => "San Diego", "to" => "Miami", "kms" => "3654"),
        array("from" => "San Diego", "to" => "Panama City", "kms" => "4683"),
        
        array("from" => "San Fransisco", "to" => "Honolulu", "kms" => "3858"),
        array("from" => "San Fransisco", "to" => "Kahului", "kms" => "3771"),
        array("from" => "San Fransisco", "to" => "Kailua Kona", "kms" => "3818"),
        array("from" => "San Fransisco", "to" => "Los Angeles", "kms" => "560"),
        array("from" => "San Fransisco", "to" => "San Diego", "kms" => "738"),
        array("from" => "San Fransisco", "to" => "Buffalo", "kms" => "3697"),
        array("from" => "San Fransisco", "to" => "NY City", "kms" => "4134"),
        array("from" => "San Fransisco", "to" => "Kingston", "kms" => "3873"),
        array("from" => "San Fransisco", "to" => "Denver", "kms" => "1526"),
        array("from" => "San Fransisco", "to" => "Colorado Springs", "kms" => "1539"),
        array("from" => "San Fransisco", "to" => "Grand Junction", "kms" => "1217"),
        array("from" => "San Fransisco", "to" => "Dallas", "kms" => "2386"),
        array("from" => "San Fransisco", "to" => "El Paso", "kms" => "1601"),
        array("from" => "San Fransisco", "to" => "San Antonio", "kms" => "2396"),
        array("from" => "San Fransisco", "to" => "Jacksonville", "kms" => "3819"),
        array("from" => "San Fransisco", "to" => "Miami", "kms" => "4174"),
        array("from" => "San Fransisco", "to" => "Panama City", "kms" => "5357"),
        
        array("from" => "Buffalo", "to" => "Honolulu", "kms" => "7548"),
        array("from" => "Buffalo", "to" => "Kahului", "kms" => "7465"),
        array("from" => "Buffalo", "to" => "Kailua Kona", "kms" => "7515"),
        array("from" => "Buffalo", "to" => "Los Angeles", "kms" => "3534"),
        array("from" => "Buffalo", "to" => "San Diego", "kms" => "3518"),
        array("from" => "Buffalo", "to" => "San Fransisco", "kms" => "3697"),
        array("from" => "Buffalo", "to" => "NY City", "kms" => "471"),
        array("from" => "Buffalo", "to" => "Kingston", "kms" => "244"),
        array("from" => "Buffalo", "to" => "Denver", "kms" => "2202"),
        array("from" => "Buffalo", "to" => "Colorado Springs", "kms" => "2221"),
        array("from" => "Buffalo", "to" => "Grand Junction", "kms" => "2516"),
        array("from" => "Buffalo", "to" => "Dallas", "kms" => "1929"),
        array("from" => "Buffalo", "to" => "El Paso", "kms" => "2722"),
        array("from" => "Buffalo", "to" => "San Antonio", "kms" => "2304"),
        array("from" => "Buffalo", "to" => "Jacksonville", "kms" => "1419"),
        array("from" => "Buffalo", "to" => "Miami", "kms" => "1910"),
        array("from" => "Buffalo", "to" => "Panama City", "kms" => "3761"),
        
        array("from" => "NY City", "to" => "Honolulu", "kms" => "7991"),
        array("from" => "NY City", "to" => "Kahului", "kms" => "7905"),
        array("from" => "NY City", "to" => "Kailua Kona", "kms" => "7950"),
        array("from" => "NY City", "to" => "Los Angeles", "kms" => "3940"),
        array("from" => "NY City", "to" => "San Diego", "kms" => "3912"),
        array("from" => "NY City", "to" => "San Fransisco", "kms" => "4134"),
        array("from" => "NY City", "to" => "Buffalo", "kms" => "471"),
        array("from" => "NY City", "to" => "Kingston", "kms" => "441"),
        array("from" => "NY City", "to" => "Denver", "kms" => "2622"),
        array("from" => "NY City", "to" => "Colorado Springs", "kms" => "2631"),
        array("from" => "NY City", "to" => "Grand Junction", "kms" => "2937"),
        array("from" => "NY City", "to" => "Dallas", "kms" => "2208"),
        array("from" => "NY City", "to" => "El Paso", "kms" => "3061"),
        array("from" => "NY City", "to" => "San Antonio", "kms" => "2548"),
        array("from" => "NY City", "to" => "Jacksonville", "kms" => "1346"),
        array("from" => "NY City", "to" => "Miami", "kms" => "1760"),
        array("from" => "NY City", "to" => "Panama City", "kms" => "3559"),
        
        array("from" => "Kingston", "to" => "Honolulu", "kms" => "7714"),
        array("from" => "Kingston", "to" => "Kahului", "kms" => "7635"),
        array("from" => "Kingston", "to" => "Kailua Kona", "kms" => "7688"),
        array("from" => "Kingston", "to" => "Los Angeles", "kms" => "3734"),
        array("from" => "Kingston", "to" => "San Diego", "kms" => "3725"),
        array("from" => "Kingston", "to" => "San Fransisco", "kms" => "3873"),
        array("from" => "Kingston", "to" => "Buffalo", "kms" => "244"),
        array("from" => "Kingston", "to" => "NY City", "kms" => "441"),
        array("from" => "Kingston", "to" => "Denver", "kms" => "2398"),
        array("from" => "Kingston", "to" => "Colorado Springs", "kms" => "2423"),
        array("from" => "Kingston", "to" => "Grand Junction", "kms" => "2710"),
        array("from" => "Kingston", "to" => "Dallas", "kms" => "2171"),
        array("from" => "Kingston", "to" => "El Paso", "kms" => "2952"),
        array("from" => "Kingston", "to" => "San Antonio", "kms" => "2547"),
        array("from" => "Kingston", "to" => "Jacksonville", "kms" => "1613"),
        array("from" => "Kingston", "to" => "Miami", "kms" => "2083"),
        array("from" => "Kingston", "to" => "Panama City", "kms" => "3921"),
        
        array("from" => "Denver", "to" => "Honolulu", "kms" => "5381"),
        array("from" => "Denver", "to" => "Kahului", "kms" => "5290"),
        array("from" => "Denver", "to" => "Kailua Kona", "kms" => "5331"),
        array("from" => "Denver", "to" => "Los Angeles", "kms" => "1337"),
        array("from" => "Denver", "to" => "San Diego", "kms" => "1342"),
        array("from" => "Denver", "to" => "San Fransisco", "kms" => "1526"),
        array("from" => "Denver", "to" => "Buffalo", "kms" => "2202"),
        array("from" => "Denver", "to" => "NY City", "kms" => "2622"),
        array("from" => "Denver", "to" => "Kingston", "kms" => "2398"),
        array("from" => "Denver", "to" => "Colorado Springs", "kms" => "102"),
        array("from" => "Denver", "to" => "Grand Junction", "kms" => "315"),
        array("from" => "Denver", "to" => "Dallas", "kms" => "1068"),
        array("from" => "Denver", "to" => "El Paso", "kms" => "898"),
        array("from" => "Denver", "to" => "San Antonio", "kms" => "1293"),
        array("from" => "Denver", "to" => "Jacksonville", "kms" => "2361"),
        array("from" => "Denver", "to" => "Miami", "kms" => "2780"),
        array("from" => "Denver", "to" => "Panama City", "kms" => "4252"),
        
        array("from" => "Colorado Springs", "to" => "Honolulu", "kms" => "5385"),
        array("from" => "Colorado Springs", "to" => "Kahului", "kms" => "5292"),
        array("from" => "Colorado Springs", "to" => "Kailua Kona", "kms" => "5330"),
        array("from" => "Colorado Springs", "to" => "Los Angeles", "kms" => "1313"),
        array("from" => "Colorado Springs", "to" => "San Diego", "kms" => "1304"),
        array("from" => "Colorado Springs", "to" => "San Fransisco", "kms" => "1539"),
        array("from" => "Colorado Springs", "to" => "Buffalo", "kms" => "2221"),
        array("from" => "Colorado Springs", "to" => "NY City", "kms" => "2631"),
        array("from" => "Colorado Springs", "to" => "Kingston", "kms" => "2423"),
        array("from" => "Colorado Springs", "to" => "Denver", "kms" => "102"),
        array("from" => "Colorado Springs", "to" => "Grand Junction", "kms" => "324"),
        array("from" => "Colorado Springs", "to" => "Dallas", "kms" => "989"),
        array("from" => "Colorado Springs", "to" => "El Paso", "kms" => "802"),
        array("from" => "Colorado Springs", "to" => "San Antonio", "kms" => "1198"),
        array("from" => "Colorado Springs", "to" => "Jacksonville", "kms" => "2316"),
        array("from" => "Colorado Springs", "to" => "Miami", "kms" => "2723"),
        array("from" => "Colorado Springs", "to" => "Panama City", "kms" => "4169"),
        
        array("from" => "Grand Junction", "to" => "Honolulu", "kms" => "5068"),
        array("from" => "Grand Junction", "to" => "Kahului", "kms" => "4976"),
        array("from" => "Grand Junction", "to" => "Kailua Kona", "kms" => "5016"),
        array("from" => "Grand Junction", "to" => "Los Angeles", "kms" => "1030"),
        array("from" => "Grand Junction", "to" => "San Diego", "kms" => "1049"),
        array("from" => "Grand Junction", "to" => "San Fransisco", "kms" => "1217"),
        array("from" => "Grand Junction", "to" => "Buffalo", "kms" => "2516"),
        array("from" => "Grand Junction", "to" => "NY City", "kms" => "2937"),
        array("from" => "Grand Junction", "to" => "Kingston", "kms" => "2710"),
        array("from" => "Grand Junction", "to" => "Denver", "kms" => "315"),
        array("from" => "Grand Junction", "to" => "Colorado Springs", "kms" => "324"),
        array("from" => "Grand Junction", "to" => "Dallas", "kms" => "1268"),
        array("from" => "Grand Junction", "to" => "El Paso", "kms" => "834"),
        array("from" => "Grand Junction", "to" => "San Antonio", "kms" => "1415"),
        array("from" => "Grand Junction", "to" => "Jacksonville", "kms" => "2634"),
        array("from" => "Grand Junction", "to" => "Miami", "kms" => "3030"),
        array("from" => "Grand Junction", "to" => "Panama City", "kms" => "4421"),
        
        array("from" => "Dallas", "to" => "Honolulu", "kms" => "6106"),
        array("from" => "Dallas", "to" => "Kahului", "kms" => "5997"),
        array("from" => "Dallas", "to" => "Kailua Kona", "kms" => "6014"),
        array("from" => "Dallas", "to" => "Los Angeles", "kms" => "1994"),
        array("from" => "Dallas", "to" => "San Diego", "kms" => "1904"),
        array("from" => "Dallas", "to" => "San Fransisco", "kms" => "2386"),
        array("from" => "Dallas", "to" => "Buffalo", "kms" => "1929"),
        array("from" => "Dallas", "to" => "NY City", "kms" => "2208"),
        array("from" => "Dallas", "to" => "Kingston", "kms" => "2171"),
        array("from" => "Dallas", "to" => "Denver", "kms" => "1068"),
        array("from" => "Dallas", "to" => "Colorado Springs", "kms" => "989"),
        array("from" => "Dallas", "to" => "Grand Junction", "kms" => "1268"),
        array("from" => "Dallas", "to" => "El Paso", "kms" => "919"),
        array("from" => "Dallas", "to" => "San Antonio", "kms" => "407"),
        array("from" => "Dallas", "to" => "Jacksonville", "kms" => "1461"),
        array("from" => "Dallas", "to" => "Miami", "kms" => "1789"),
        array("from" => "Dallas", "to" => "Panama City", "kms" => "3185"),
        
        array("from" => "El Paso", "to" => "Honolulu", "kms" => "5199"),
        array("from" => "El Paso", "to" => "Kahului", "kms" => "5087"),
        array("from" => "El Paso", "to" => "Kailua Kona", "kms" => "5101"),
        array("from" => "El Paso", "to" => "Los Angeles", "kms" => "1127"),
        array("from" => "El Paso", "to" => "San Diego", "kms" => "1010"),
        array("from" => "El Paso", "to" => "San Fransisco", "kms" => "1601"),
        array("from" => "El Paso", "to" => "Buffalo", "kms" => "2722"),
        array("from" => "El Paso", "to" => "NY City", "kms" => "3061"),
        array("from" => "El Paso", "to" => "Kingston", "kms" => "2952"),
        array("from" => "El Paso", "to" => "Denver", "kms" => "898"),
        array("from" => "El Paso", "to" => "Colorado Springs", "kms" => "802"),
        array("from" => "El Paso", "to" => "Grand Junction", "kms" => "834"),
        array("from" => "El Paso", "to" => "Dallas", "kms" => "919"),
        array("from" => "El Paso", "to" => "San Antonio", "kms" => "809"),
        array("from" => "El Paso", "to" => "Jacksonville", "kms" => "2368"),
        array("from" => "El Paso", "to" => "Miami", "kms" => "2644"),
        array("from" => "El Paso", "to" => "Panama City", "kms" => "3765"),
        
        array("from" => "San Antonio", "to" => "Honolulu", "kms" => "5978"),
        array("from" => "San Antonio", "to" => "Kahului", "kms" => "5861"),
        array("from" => "San Antonio", "to" => "Kailua Kona", "kms" => "5868"),
        array("from" => "San Antonio", "to" => "Los Angeles", "kms" => "1936"),
        array("from" => "San Antonio", "to" => "San Diego", "kms" => "1815"),
        array("from" => "San Antonio", "to" => "San Fransisco", "kms" => "2396"),
        array("from" => "San Antonio", "to" => "Buffalo", "kms" => "2304"),
        array("from" => "San Antonio", "to" => "NY City", "kms" => "2548"),
        array("from" => "San Antonio", "to" => "Kingston", "kms" => "2547"),
        array("from" => "San Antonio", "to" => "Denver", "kms" => "1293"),
        array("from" => "San Antonio", "to" => "Colorado Springs", "kms" => "1198"),
        array("from" => "San Antonio", "to" => "Grand Junction", "kms" => "1415"),
        array("from" => "San Antonio", "to" => "Dallas", "kms" => "407"),
        array("from" => "San Antonio", "to" => "El Paso", "kms" => "809"),
        array("from" => "San Antonio", "to" => "Jacksonville", "kms" => "1627"),
        array("from" => "San Antonio", "to" => "Miami", "kms" => "1849"),
        array("from" => "San Antonio", "to" => "Panama City", "kms" => "3013"),
        
        array("from" => "Jacksonville", "to" => "Honolulu", "kms" => "7565"),
        array("from" => "Jacksonville", "to" => "Kahului", "kms" => "7455"),
        array("from" => "Jacksonville", "to" => "Kailua Kona", "kms" => "7469"),
        array("from" => "Jacksonville", "to" => "Los Angeles", "kms" => "3453"),
        array("from" => "Jacksonville", "to" => "San Diego", "kms" => "3364"),
        array("from" => "Jacksonville", "to" => "San Fransisco", "kms" => "3819"),
        array("from" => "Jacksonville", "to" => "Buffalo", "kms" => "1419"),
        array("from" => "Jacksonville", "to" => "NY City", "kms" => "1346"),
        array("from" => "Jacksonville", "to" => "Kingston", "kms" => "1613"),
        array("from" => "Jacksonville", "to" => "Denver", "kms" => "2361"),
        array("from" => "Jacksonville", "to" => "Colorado Springs", "kms" => "2316"),
        array("from" => "Jacksonville", "to" => "Grand Junction", "kms" => "2634"),
        array("from" => "Jacksonville", "to" => "Dallas", "kms" => "1461"),
        array("from" => "Jacksonville", "to" => "El Paso", "kms" => "2368"),
        array("from" => "Jacksonville", "to" => "San Antonio", "kms" => "1627"),
        array("from" => "Jacksonville", "to" => "Miami", "kms" => "529"),
        array("from" => "Jacksonville", "to" => "Panama City", "kms" => "2375"),
        
        array("from" => "Miami", "to" => "Honolulu", "kms" => "7825"),
        array("from" => "Miami", "to" => "Kahului", "kms" => "7707"),
        array("from" => "Miami", "to" => "Kailua Kona", "kms" => "7710"),
        array("from" => "Miami", "to" => "Los Angeles", "kms" => "3763"),
        array("from" => "Miami", "to" => "San Diego", "kms" => "3654"),
        array("from" => "Miami", "to" => "San Fransisco", "kms" => "4174"),
        array("from" => "Miami", "to" => "Buffalo", "kms" => "1910"),
        array("from" => "Miami", "to" => "NY City", "kms" => "1760"),
        array("from" => "Miami", "to" => "Kingston", "kms" => "2083"),
        array("from" => "Miami", "to" => "Denver", "kms" => "2780"),
        array("from" => "Miami", "to" => "Colorado Springs", "kms" => "2723"),
        array("from" => "Miami", "to" => "Grand Junction", "kms" => "3030"),
        array("from" => "Miami", "to" => "Dallas", "kms" => "1789"),
        array("from" => "Miami", "to" => "El Paso", "kms" => "2644"),
        array("from" => "Miami", "to" => "San Antonio", "kms" => "1849"),
        array("from" => "Miami", "to" => "Jacksonville", "kms" => "529"),
        array("from" => "Miami", "to" => "Panama City", "kms" => "1857"),
        
        array("from" => "Panama City", "to" => "Honolulu", "kms" => "8463"),
        array("from" => "Panama City", "to" => "Kahului", "kms" => "8323"),
        array("from" => "Panama City", "to" => "Kailua Kona", "kms" => "8283"),
        array("from" => "Panama City", "to" => "Los Angeles", "kms" => "4839"),
        array("from" => "Panama City", "to" => "San Diego", "kms" => "4683"),
        array("from" => "Panama City", "to" => "San Fransisco", "kms" => "5357"),
        array("from" => "Panama City", "to" => "Buffalo", "kms" => "3761"),
        array("from" => "Panama City", "to" => "NY City", "kms" => "3559"),
        array("from" => "Panama City", "to" => "Kingston", "kms" => "3921"),
        array("from" => "Panama City", "to" => "Denver", "kms" => "4252"),
        array("from" => "Panama City", "to" => "Colorado Springs", "kms" => "4169"),
        array("from" => "Panama City", "to" => "Grand Junction", "kms" => "4421"),
        array("from" => "Panama City", "to" => "Dallas", "kms" => "3185"),
        array("from" => "Panama City", "to" => "El Paso", "kms" => "3765"),
        array("from" => "Panama City", "to" => "San Antonio", "kms" => "3013"),
        array("from" => "Panama City", "to" => "Jacksonville", "kms" => "2375"),
        array("from" => "Panama City", "to" => "Miami", "kms" => "1857"),
    );
    
    #region Default Constructor
    public function __construct()
    {
        $this->data = new StateDAO();
        $this->countryData = json_encode($this->countryArray);
    }
    
    public function __destruct()
    {
        $this->data = null;
    }
    #endregion
    public function calculatePrice($from, $to, $type = false, $raw = false)
    {
        global $language;
        $l        = $language->travelLangs();
        
        $type = !isset($type) ? "airplane" : $type;

        if(isset($_SESSION['UID']))
        {
            foreach($this->distances AS $row)
            {
                if($row['from'] == $from && $row['to'] == $to) $kms = $row['kms'];
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
            
                //Is route contains city in same state and we using an bus car or train
                $validRoute = ($this->isValidRoute($from, $to)); // TRUE : FALSE     
                
                if(!$validRoute && $type != 'airplane')
                    return "<img src='".PROTOCOL.STATIC_SUBDOMAIN.".".$route->settings['domainBase']."/web/public/images/icons/cross.png' class='icon' alt='Error'/>&nbsp;".$l['ROUTE_NOT_POSSIBLE']."";
                elseif($raw == false)
                    return "<img src='".PROTOCOL.STATIC_SUBDOMAIN.".".$route->settings['domainBase']."/web/public/images/icons/help.png' class='icon' alt='Help'/>&nbsp;<strong>".$to."</strong> ".$l['COSTS'].": $".number_format($price,0,'',',')."";
                    
                 return array('price' => $price, 'sec' => $sec);
            
            }
        }
    }
    public function isValidRoute($fromCity, $toCity)
    {
        $data = json_decode($this->countryData);
        foreach( $data as $state )
        {
            if(in_array($fromCity, $state) && in_array($toCity, $state) )
                return true;
            else
                continue;
        }
    
      return false;
    }
    public function handleTravel($post)
    {
        global $language;
        global $langs;
        $l        = $language->travelLangs();
        global $security;
        global $userData;

        //Able to have multiple errors
        $error = array();

        $cityID = (int)$post['cityID'];
        $cityTo  = $this->data->getCityNameById($cityID);
        $type = $security->xssEscape($post['type']);
        if(isset($post['vehicle'])) $garageVehicleID = (int)$post['vehicle'];
        
        $famCrimeService = new FamilyCrimeService();
        $stateID = $this->getStateIdByCityId($cityID);
        
        $famRaidService = new FamilyRaidService();
        
        if($_POST['security-token'] != $security->getToken())
        {
            $error[] = $langs['INVALID_SECURITY_TOKEN'];
        }
        if($userData->getInPrison())
        {
            $error[] = $langs['CANT_DO_THAT_IN_PRISON'];
        }
        if($userData->getTraveling())
        {
            $error[] = $langs['CANT_DO_THAT_TRAVELLING'];
        }
        if(!in_array($cityTo, $this->allowedCities))
        {
            $error[] = $l['INVALID_DESTINATION'];
        }
        if($cityTo == false)
        {
            $error[] =  $l['INVALID_DESTINATION'];
        }
        if($famCrimeService->userInsideFamilyCrime())
        {
            if($userData->getStateID() !== $stateID)
                $error[] = $l['CANNOT_TRAVEL_WHEN_IN_CRIME'];
        }
        if($famRaidService->userInsideAcceptedFamilyRaid())
        {
            if($userData->getStateID() !== $stateID)
                $error[] = $l['CANNOT_TRAVEL_WHEN_IN_RAID'];
        }
        
        $arr = $this->calculatePrice($userData->getCity(), $cityTo, $type, true);
        
        if($type == "vehicle")
        {
            $garage = new GarageService();
            if(!$garage->hasSpaceLeftInGarage($stateID))
            {
                if($userData->getStateID() !== $stateID)
                    $error[] = $l['TRAVEL_VEHICLE_NO_SPACE_GARAGE'];
            }
            if(!$garage->hasGarageInState($stateID))
            {
                if($userData->getStateID() !== $stateID)
                    $error[] = $l['TRAVEL_VEHICLE_NO_GARAGE'];
            }
            $inGarage = $garage->isVehicleInGarageInState($userData->getStateID(), $garageVehicleID);
            if($inGarage == FALSE)
            {
                $error[] = $l['TRAVEL_VEHICLE_NO_VEHICLE'];
            }
        }
        if($arr == FALSE)
        {
            $error[] = $l['ROUTE_NOT_POSSIBLE'];
        }
        else
        {
            $sec = $arr['sec'];
            $price = $arr['price'];
            if($userData->getCash() < $price)
            {
                $error[] = $langs['NOT_ENOUGH_MONEY_CASH'];
            }
            if($userData->getCTravelTime() > time())
            {
                global $route;
                
                global $lang;
                $counter = "enkele";
                if($lang == "en") $counter = "a few";
                $error[] = $route->replaceMessagePart($counter, $langs['TRAVELING'], '/{sec}/');
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
            
        $max = (($userData->getRankID() + '1' ) * 100);
        if($catchRand < 20 && count($units) > 0 ||
            ($sPossess[1] > $max || $sPossess[1] < 0) || ($sPossess[2] > $max || $sPossess[2] < 0) || ($sPossess[3] > $max || $sPossess[3] < 0) ||
            ($sPossess[4] > $max || $sPossess[4] < 0) || ($sPossess[5] > $max || $sPossess[5] < 0)
        )
        {
            $prison = new PrisonService();
            $prison->putUserInPrison($userData->getId(), time() + 120);
            $smuggleService->removeAllSmugglingUnits();
            $error[] = $l['CAUGHT_BY_BORDER_PATROL'];
        }
        global $route;
        //When our array containts atleast 1 error
        if(count($error) != 0)
        {
            //TODO handle multiple error message
            return array("error" => $route->errorMessage($error[0]));
        }       
        else
        {
            
            
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
    #region GETTERS
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
    #endregion
}
