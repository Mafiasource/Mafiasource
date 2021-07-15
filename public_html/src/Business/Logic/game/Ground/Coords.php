<?PHP

namespace src\Business\Logic\game\Ground;

class Coords
{
    protected $groundCoords;
    
    public function __construct($stateID)
    {
        $groundCoords = $this->getCoordsByStateID($stateID);
        $this->groundCoords = $groundCoords;
    }
    
    public function getCoordsByStateID($stateID)
    {
        switch($stateID)
        {
            /**
             * HAWAII
             **/
            case 1:
                $groundCoords = array(
                	1 => array(
                		"left_px" => "54",
                		"top_px" => "10",
                		"coords" => "54,10,92,48"
                	),
                	array(
                		"left_px" => "93",
                		"top_px" => "10",
                		"coords" => "93,10,131,48"
                	),
                 	array(
                		"left_px" => "132",
                		"top_px" => "10",
                  		"coords" => "132,10,170,48"
                	),
                	array(
                		"left_px" => "171",
                		"top_px" => "10",
                		"coords" => "171,10,209,48"
                	),
                	array(
                		"left_px" => "15",
                		"top_px" => "49",
                		"coords" => "15,49,53,87"
                	),
                	array(
                		"left_px" => "54",
                		"top_px" => "49",
                		"coords" => "54,49,92,87"
                	),
                	array(
                		"left_px" => "93",
                		"top_px" => "49",
                		"coords" => "93,49,131,87"
                	),
                	array(
                		"left_px" => "132",
                		"top_px" => "49",
                		"coords" => "132,49,170,87"
                	),
                	array(
                		"left_px" => "171",
                		"top_px" => "49",
                		"coords" => "171,49,209,87"
                	),
                	array(
                		"left_px" => "210",
                		"top_px" => "49",
                		"coords" => "210,49,248,87"
                	),
                	array(
                		"left_px" => "249",
                		"top_px" => "49",
                		"coords" => "249,49,287,87"
                	),
                	array(
                		"left_px" => "288",
                		"top_px" => "49",
                		"coords" => "288,49,326,87"
                	),
                	array(
                		"left_px" => "15",
                		"top_px" => "88",
                		"coords" => "15,88,53,126"
                	),
                	array(
                		"left_px" => "132",
                		"top_px" => "88",
                		"coords" => "132,88,170,126"
                	),
                	array(
                		"left_px" => "171",
                		"top_px" => "88",
                		"coords" => "171,88,209,126"
                	),
                	array(
                		"left_px" => "210",
                		"top_px" => "88",
                		"coords" => "210,88,248,126"
                	),
                	array(
                		"left_px" => "249",
                		"top_px" => "88",
                		"coords" => "249,88,287,126"
                	),
                	array(
                		"left_px" => "288",
                		"top_px" => "88",
                		"coords" => "288,88,326,126"
                	),
                	array(
                		"left_px" => "327",
                		"top_px" => "88",
                		"coords" => "327,88,365,126"
                	),
                	array(
                		"left_px" => "366",
                		"top_px" => "88",
                		"coords" => "366,88,404,126"
                	),
                	array(
                		"left_px" => "405",
                		"top_px" => "88",
                		"coords" => "405,88,443,126"
                	),
                	array(
                		"left_px" => "249",
                		"top_px" => "127",
                		"coords" => "249,127,287,165"
                	),
                	array(
                		"left_px" => "288",
                		"top_px" => "127",
                		"coords" => "288,127,326,165"
                	),
                	array(
                		"left_px" => "327",
                		"top_px" => "127",
                  		"coords" => "327,127,365,165"
                	),
                	array(
                		"left_px" => "366",
                		"top_px" => "127",
                		"coords" => "366,127,404,165"
                	),
                	array(
                		"left_px" => "405",
                		"top_px" => "127",
                		"coords" => "405,127,443,165"
                	),
                	array(
                		"left_px" => "444",
                		"top_px" => "127",
                		"coords" => "444,127,482,165"
                	),
                	array(
                		"left_px" => "483",
                		"top_px" => "127",
                		"coords" => "483,127,521,165"
                	),
                	array(
                		"left_px" => "522",
                		"top_px" => "127",
                		"coords" => "522,127,560,165"
                	),
                	array(
                		"left_px" => "405",
                		"top_px" => "166",
                		"coords" => "405,166,443,204"
                	),
                	array(
                		"left_px" => "444",
                		"top_px" => "166",
                		"coords" => "444,166,482,204"
                	),
                	array(
                		"left_px" => "483",
                		"top_px" => "166",
                		"coords" => "483,166,521,204"
                	),
                	array(
                		"left_px" => "522",
                		"top_px" => "166",
                		"coords" => "522,166,560,204"
                	),
                	array(
                		"left_px" => "561",
                		"top_px" => "166",
                		"coords" => "561,166,599,204"
                	),
                	array(
                		"left_px" => "405",
                		"top_px" => "205",
                		"coords" => "405,205,443,243"
                	),
                	array(
                		"left_px" => "444",
                		"top_px" => "205",
                		"coords" => "444,205,482,243"
                	),
                	array(
                		"left_px" => "483",
                		"top_px" => "205",
                		"coords" => "483,205,521,243"
                	),
                	array(
                		"left_px" => "522",
                		"top_px" => "205",
                		"coords" => "522,205,560,243"
                	),
                	array(
                		"left_px" => "561",
                		"top_px" => "205",
                		"coords" => "561,205,599,243"
                	),
                	array(
                		"left_px" => "444",
                		"top_px" => "244",
                		"coords" => "444,244,482,282"
                	),
                	array(
                		"left_px" => "483",
                		"top_px" => "244",
                		"coords" => "483,244,521,282"
                	),
                	array(
                		"left_px" => "522",
                		"top_px" => "244",
                		"coords" => "522,244,560,282"
                	),
                	array(
                		"left_px" => "444",
                		"top_px" => "283",
                		"coords" => "444,283,482,321"
                	),
                	array(
                		"left_px" => "483",
                		"top_px" => "283",
                		"coords" => "483,283,521,321"
                    )
                );
                break;
            
            /**
             * CALIFORNIA
             **/
            case 2:
                $groundCoords = array(
                	1 => array(
                		"left_px" => "22",
                		"top_px" => "17",
                		"coords" => "22,17,60,55"
                	),
                	array(
                		"left_px" => "61",
                		"top_px" => "17",
                		"coords" => "61,17,99,55"
                	),
                 	array(
                		"left_px" => "100",
                		"top_px" => "17",
                  		"coords" => "100,17,138,55"
                	),
                	array(
                		"left_px" => "139",
                		"top_px" => "17",
                		"coords" => "139,17,177,55"
                	),
                	array(
                		"left_px" => "22",
                		"top_px" => "56",
                		"coords" => "22,56,60,94"
                	),
                	array(
                		"left_px" => "61",
                		"top_px" => "56",
                		"coords" => "61,56,99,94"
                	),
                	array(
                		"left_px" => "100",
                		"top_px" => "56",
                		"coords" => "100,56,138,94"
                	),
                	array(
                		"left_px" => "139",
                		"top_px" => "56",
                		"coords" => "139,56,177,94"
                	),
                	array(
                		"left_px" => "22",
                		"top_px" => "95",
                		"coords" => "22,95,60,133"
                	),
                	array(
                		"left_px" => "61",
                		"top_px" => "95",
                		"coords" => "61,95,99,133"
                	),
                	array(
                		"left_px" => "100",
                		"top_px" => "95",
                		"coords" => "100,95,138,133"
                	),
                	array(
                		"left_px" => "139",
                		"top_px" => "95",
                		"coords" => "139,95,177,133"
                	),
                	array(
                		"left_px" => "22",
                		"top_px" => "134",
                		"coords" => "22,134,60,172"
                	),
                	array(
                		"left_px" => "61",
                		"top_px" => "134",
                		"coords" => "61,134,99,172"
                	),
                	array(
                		"left_px" => "100",
                		"top_px" => "134",
                		"coords" => "100,134,138,172"
                	),
                	array(
                		"left_px" => "139",
                		"top_px" => "134",
                		"coords" => "139,134,177,172"
                	),
                	array(
                		"left_px" => "22",
                		"top_px" => "173",
                		"coords" => "22,173,60,211"
                	),
                	array(
                		"left_px" => "61",
                		"top_px" => "173",
                		"coords" => "61,173,99,211"
                	),
                	array(
                		"left_px" => "100",
                		"top_px" => "173",
                		"coords" => "100,173,138,211"
                	),
                	array(
                		"left_px" => "139",
                		"top_px" => "173",
                		"coords" => "139,173,177,211"
                	),
                	array(
                		"left_px" => "178",
                		"top_px" => "173",
                		"coords" => "178,173,216,211"
                	),
                	array(
                		"left_px" => "61",
                		"top_px" => "212",
                		"coords" => "61,212,99,250"
                	),
                	array(
                		"left_px" => "100",
                		"top_px" => "212",
                		"coords" => "100,212,138,250"
                	),
                	array(
                		"left_px" => "139",
                		"top_px" => "212",
                  		"coords" => "139,212,177,250"
                	),
                	array(
                		"left_px" => "178",
                		"top_px" => "212",
                		"coords" => "178,212,216,250"
                	),
                	array(
                		"left_px" => "217",
                		"top_px" => "212",
                		"coords" => "217,212,255,250"
                	),
                	array(
                		"left_px" => "61",
                		"top_px" => "251",
                		"coords" => "61,251,99,289"
                	),
                	array(
                		"left_px" => "100",
                		"top_px" => "251",
                		"coords" => "100,251,138,289"
                	),
                	array(
                		"left_px" => "139",
                		"top_px" => "251",
                		"coords" => "139,251,177,289"
                	),
                	array(
                		"left_px" => "178",
                		"top_px" => "251",
                		"coords" => "178,251,216,289"
                	),
                	array(
                		"left_px" => "217",
                		"top_px" => "251",
                		"coords" => "217,251,255,289"
                	),
                	array(
                		"left_px" => "256",
                		"top_px" => "251",
                		"coords" => "256,251,294,289"
                	),
                	array(
                		"left_px" => "100",
                		"top_px" => "290",
                		"coords" => "100,290,138,328"
                	),
                	array(
                		"left_px" => "139",
                		"top_px" => "290",
                		"coords" => "139,290,177,328"
                	),
                	array(
                		"left_px" => "178",
                		"top_px" => "290",
                		"coords" => "178,290,216,328"
                	),
                	array(
                		"left_px" => "217",
                		"top_px" => "290",
                		"coords" => "217,290,255,328"
                	),
                	array(
                		"left_px" => "256",
                		"top_px" => "290",
                		"coords" => "256,290,294,328"
                	),
                	array(
                		"left_px" => "295",
                		"top_px" => "290",
                		"coords" => "295,290,333,328"
                	),
                	array(
                		"left_px" => "100",
                		"top_px" => "329",
                		"coords" => "100,329,138,367"
                	),
                	array(
                		"left_px" => "139",
                		"top_px" => "329",
                		"coords" => "139,329,177,367"
                	),
                	array(
                		"left_px" => "178",
                		"top_px" => "329",
                		"coords" => "178,329,216,367"
                	),
                	array(
                		"left_px" => "217",
                		"top_px" => "329",
                		"coords" => "217,329,255,367"
                	),
                	array(
                		"left_px" => "256",
                		"top_px" => "329",
                		"coords" => "256,329,294,367"
                	),
                	array(
                		"left_px" => "295",
                		"top_px" => "329",
                		"coords" => "295,329,333,367"
                	),
                	array(
                		"left_px" => "334",
                		"top_px" => "329",
                		"coords" => "334,329,372,367"
                	),
                	array(
                		"left_px" => "139",
                		"top_px" => "368",
                		"coords" => "139,368,177,406"
                	),
                	array(
                		"left_px" => "178",
                		"top_px" => "368",
                		"coords" => "178,368,216,406"
                	),
                	array(
                		"left_px" => "217",
                		"top_px" => "368",
                		"coords" => "217,368,255,406"
                	),
                	array(
                		"left_px" => "256",
                		"top_px" => "368",
                		"coords" => "256,368,294,406"
                	),
                	array(
                		"left_px" => "295",
                		"top_px" => "368",
                		"coords" => "295,368,333,406"
                	),
                	array(
                		"left_px" => "334",
                		"top_px" => "368",
                		"coords" => "334,368,372,406"
                	),
                	array(
                		"left_px" => "217",
                		"top_px" => "407",
                		"coords" => "217,407,255,445"
                	),
                	array(
                		"left_px" => "256",
                		"top_px" => "407",
                		"coords" => "256,407,294,445"
                	),
                	array(
                		"left_px" => "295",
                		"top_px" => "407",
                		"coords" => "295,407,333,445"
                	),
                	array(
                		"left_px" => "334",
                		"top_px" => "407",
                		"coords" => "334,407,372,445"
                	),
                	array(
                		"left_px" => "256",
                		"top_px" => "446",
                		"coords" => "256,446,294,484"
                	),
                	array(
                		"left_px" => "295",
                		"top_px" => "446",
                		"coords" => "295,446,333,484"
                	)
                );
                break;
            
            /**
             * NEW YORK
             **/
            case 3:
                $groundCoords = array(
                	1 => array(
                		"left_px" => "255",
                		"top_px" => "15",
                		"coords" => "255,15,293,53"
                	),
                	array(
                		"left_px" => "294",
                		"top_px" => "15",
                		"coords" => "294,15,332,53"
                	),
                 	array(
                		"left_px" => "333",
                		"top_px" => "15",
                  		"coords" => "333,15,371,53"
                	),
                	array(
                		"left_px" => "372",
                		"top_px" => "15",
                		"coords" => "372,15,410,53"
                	),
                	array(
                		"left_px" => "216",
                		"top_px" => "54",
                		"coords" => "216,54,254,92"
                	),
                	array(
                		"left_px" => "255",
                		"top_px" => "54",
                		"coords" => "255,54,293,92"
                	),
                	array(
                		"left_px" => "294",
                		"top_px" => "54",
                		"coords" => "294,54,332,92"
                	),
                	array(
                		"left_px" => "333",
                		"top_px" => "54",
                		"coords" => "333,54,371,92"
                	),
                	array(
                		"left_px" => "372",
                		"top_px" => "54",
                		"coords" => "372,54,410,92"
                	),
                	array(
                		"left_px" => "216",
                		"top_px" => "93",
                		"coords" => "216,93,254,131"
                	),
                	array(
                		"left_px" => "255",
                		"top_px" => "93",
                		"coords" => "255,93,293,131"
                	),
                	array(
                		"left_px" => "294",
                		"top_px" => "93",
                		"coords" => "294,93,332,131"
                	),
                	array(
                		"left_px" => "333",
                		"top_px" => "93",
                		"coords" => "333,93,371,131"
                	),
                	array(
                		"left_px" => "372",
                		"top_px" => "93",
                		"coords" => "372,93,410,131"
                	),
                	array(
                		"left_px" => "216",
                		"top_px" => "132",
                		"coords" => "216,132,254,170"
                	),
                	array(
                		"left_px" => "255",
                		"top_px" => "132",
                		"coords" => "255,132,293,170"
                	),
                	array(
                		"left_px" => "294",
                		"top_px" => "132",
                		"coords" => "294,132,332,170"
                	),
                	array(
                		"left_px" => "333",
                		"top_px" => "132",
                		"coords" => "333,132,371,170"
                	),
                	array(
                		"left_px" => "372",
                		"top_px" => "132",
                		"coords" => "372,132,410,170"
                	),
                	array(
                		"left_px" => "60",
                		"top_px" => "171",
                		"coords" => "60,171,98,209"
                	),
                	array(
                		"left_px" => "99",
                		"top_px" => "171",
                		"coords" => "99,171,137,209"
                	),
                	array(
                		"left_px" => "138",
                		"top_px" => "171",
                		"coords" => "138,171,176,209"
                	),
                	array(
                		"left_px" => "177",
                		"top_px" => "171",
                		"coords" => "177,171,215,209"
                	),
                	array(
                		"left_px" => "216",
                		"top_px" => "171",
                  		"coords" => "216,171,254,209"
                	),
                	array(
                		"left_px" => "255",
                		"top_px" => "171",
                		"coords" => "255,171,293,209"
                	),
                	array(
                		"left_px" => "294",
                		"top_px" => "171",
                		"coords" => "294,171,332,209"
                	),
                	array(
                		"left_px" => "333",
                		"top_px" => "171",
                		"coords" => "333,171,371,209"
                	),
                	array(
                		"left_px" => "372",
                		"top_px" => "171",
                		"coords" => "372,171,410,209"
                	),
                	array(
                		"left_px" => "21",
                		"top_px" => "210",
                		"coords" => "21,210,59,248"
                	),
                	array(
                		"left_px" => "60",
                		"top_px" => "210",
                		"coords" => "60,210,98,248"
                	),
                	array(
                		"left_px" => "99",
                		"top_px" => "210",
                		"coords" => "99,210,137,248"
                	),
                	array(
                		"left_px" => "138",
                		"top_px" => "210",
                		"coords" => "138,210,176,248"
                	),
                	array(
                		"left_px" => "177",
                		"top_px" => "210",
                		"coords" => "177,210,215,248"
                	),
                	array(
                		"left_px" => "216",
                		"top_px" => "210",
                		"coords" => "216,210,254,248"
                	),
                	array(
                		"left_px" => "255",
                		"top_px" => "210",
                		"coords" => "255,210,293,248"
                	),
                	array(
                		"left_px" => "294",
                		"top_px" => "210",
                		"coords" => "294,210,332,248"
                	),
                	array(
                		"left_px" => "333",
                		"top_px" => "210",
                		"coords" => "333,210,371,248"
                	),
                	array(
                		"left_px" => "372",
                		"top_px" => "210",
                		"coords" => "372,210,410,248"
                	),
                	array(
                		"left_px" => "21",
                		"top_px" => "249",
                		"coords" => "21,249,59,287"
                	),
                	array(
                		"left_px" => "60",
                		"top_px" => "249",
                		"coords" => "60,249,98,287"
                	),
                	array(
                		"left_px" => "99",
                		"top_px" => "249",
                		"coords" => "99,249,137,287"
                	),
                	array(
                		"left_px" => "138",
                		"top_px" => "249",
                		"coords" => "138,249,176,287"
                	),
                	array(
                		"left_px" => "177",
                		"top_px" => "249",
                		"coords" => "177,249,215,287"
                	),
                	array(
                		"left_px" => "216",
                		"top_px" => "249",
                		"coords" => "216,249,254,287"
                	),
                	array(
                		"left_px" => "255",
                		"top_px" => "249",
                		"coords" => "255,249,293,287"
                	),
                	array(
                		"left_px" => "294",
                		"top_px" => "249",
                		"coords" => "294,249,332,287"
                	),
                	array(
                		"left_px" => "333",
                		"top_px" => "249",
                		"coords" => "333,249,371,287"
                	),
                	array(
                		"left_px" => "372",
                		"top_px" => "249",
                		"coords" => "372,249,410,287"
                	),
                	array(
                		"left_px" => "294",
                		"top_px" => "288",
                		"coords" => "294,288,332,326"
                	),
                	array(
                		"left_px" => "333",
                		"top_px" => "288",
                		"coords" => "333,288,371,326"
                	),
                	array(
                		"left_px" => "372",
                		"top_px" => "288",
                		"coords" => "372,288,410,326"
                	),
                	array(
                		"left_px" => "294",
                		"top_px" => "327",
                		"coords" => "294,327,332,365"
                	),
                	array(
                		"left_px" => "333",
                		"top_px" => "327",
                		"coords" => "333,327,371,365"
                	),
                	array(
                		"left_px" => "372",
                		"top_px" => "327",
                		"coords" => "372,327,410,365"
                	),
                	array(
                		"left_px" => "411",
                		"top_px" => "327",
                		"coords" => "411,327,449,365"
                	),
                	array(
                		"left_px" => "450",
                		"top_px" => "327",
                		"coords" => "450,327,488,365"
                	),
                	array(
                		"left_px" => "333",
                		"top_px" => "366",
                		"coords" => "333,366,371,404"
                	),
                	array(
                		"left_px" => "372",
                		"top_px" => "366",
                		"coords" => "372,366,410,404"
                	),
                	array(
                		"left_px" => "411",
                		"top_px" => "366",
                		"coords" => "411,366,449,404"
                	),
                	array(
                		"left_px" => "450",
                		"top_px" => "366",
                		"coords" => "450,366,488,404"
                	)
                );
                break;
            
            /**
             * COLORADO
             **/
            case 4:
                $groundCoords = array(
                	1 => array(
                		"left_px" => "20",
                		"top_px" => "18",
                		"coords" => "20,18,58,56"
                	),
                	array(
                		"left_px" => "59",
                		"top_px" => "18",
                		"coords" => "59,18,97,56"
                	),
                 	array(
                		"left_px" => "98",
                		"top_px" => "18",
                  		"coords" => "98,18,136,56"
                	),
                	array(
                		"left_px" => "137",
                		"top_px" => "18",
                		"coords" => "137,18,175,56"
                	),
                	array(
                		"left_px" => "176",
                		"top_px" => "18",
                		"coords" => "176,18,214,56"
                	),
                	array(
                		"left_px" => "215",
                		"top_px" => "18",
                		"coords" => "215,18,253,56"
                	),
                	array(
                		"left_px" => "254",
                		"top_px" => "18",
                		"coords" => "254,18,292,56"
                	),
                	array(
                		"left_px" => "293",
                		"top_px" => "18",
                		"coords" => "293,18,331,56"
                	),
                	array(
                		"left_px" => "332",
                		"top_px" => "18",
                		"coords" => "332,18,370,56"
                	),
                	array(
                		"left_px" => "371",
                		"top_px" => "18",
                		"coords" => "371,18,409,56"
                	),
                	array(
                		"left_px" => "410",
                		"top_px" => "18",
                		"coords" => "410,18,448,56"
                	),
                	array(
                		"left_px" => "449",
                		"top_px" => "18",
                		"coords" => "449,18,487,56"
                	),
                	array(
                		"left_px" => "488",
                		"top_px" => "18",
                		"coords" => "488,18,526,56"
                	),
                	array(
                		"left_px" => "527",
                		"top_px" => "18",
                		"coords" => "527,18,565,56"
                	),
                	array(
                		"left_px" => "20",
                		"top_px" => "57",
                		"coords" => "20,57,58,95"
                	),
                	array(
                		"left_px" => "59",
                		"top_px" => "57",
                		"coords" => "59,57,97,95"
                	),
                	array(
                		"left_px" => "98",
                		"top_px" => "57",
                		"coords" => "98,57,136,95"
                	),
                	array(
                		"left_px" => "137",
                		"top_px" => "57",
                		"coords" => "137,57,175,95"
                	),
                	array(
                		"left_px" => "176",
                		"top_px" => "57",
                		"coords" => "176,57,214,95"
                	),
                	array(
                		"left_px" => "215",
                		"top_px" => "57",
                		"coords" => "215,57,253,95"
                	),
                	array(
                		"left_px" => "254",
                		"top_px" => "57",
                		"coords" => "254,57,292,95"
                	),
                	array(
                		"left_px" => "293",
                		"top_px" => "57",
                		"coords" => "293,57,331,95"
                	),
                	array(
                		"left_px" => "332",
                		"top_px" => "57",
                		"coords" => "332,57,370,95"
                	),
                	array(
                		"left_px" => "371",
                		"top_px" => "57",
                  		"coords" => "371,57,409,95"
                	),
                	array(
                		"left_px" => "410",
                		"top_px" => "57",
                		"coords" => "410,57,448,95"
                	),
                	array(
                		"left_px" => "449",
                		"top_px" => "57",
                		"coords" => "449,57,487,95"
                	),
                	array(
                		"left_px" => "488",
                		"top_px" => "57",
                		"coords" => "488,57,526,95"
                	),
                	array(
                		"left_px" => "527",
                		"top_px" => "57",
                		"coords" => "527,57,565,95"
                	),
                	array(
                		"left_px" => "20",
                		"top_px" => "96",
                		"coords" => "20,96,58,134"
                	),
                	array(
                		"left_px" => "59",
                		"top_px" => "96",
                		"coords" => "59,96,97,134"
                	),
                
                	array(
                		"left_px" => "98",
                		"top_px" => "96",
                		"coords" => "98,96,136,134"
                	),
                	array(
                		"left_px" => "137",
                		"top_px" => "96",
                		"coords" => "137,96,175,134"
                	),
                	array(
                		"left_px" => "176",
                		"top_px" => "96",
                		"coords" => "176,96,214,134"
                	),
                	array(
                		"left_px" => "215",
                		"top_px" => "96",
                		"coords" => "215,96,253,134"
                	),
                	array(
                		"left_px" => "254",
                		"top_px" => "96",
                		"coords" => "254,96,292,134"
                	),
                	array(
                		"left_px" => "293",
                		"top_px" => "96",
                		"coords" => "293,96,331,134"
                	),
                	array(
                		"left_px" => "332",
                		"top_px" => "96",
                		"coords" => "332,96,370,134"
                	),
                	array(
                		"left_px" => "371",
                		"top_px" => "96",
                		"coords" => "371,96,409,134"
                	),
                	array(
                		"left_px" => "410",
                		"top_px" => "96",
                		"coords" => "410,96,448,134"
                	),
                	array(
                		"left_px" => "371",
                		"top_px" => "96",
                		"coords" => "371,96,409,134"
                	),
                	array(
                		"left_px" => "410",
                		"top_px" => "96",
                		"coords" => "410,96,448,134"
                	),
                	array(
                		"left_px" => "449",
                		"top_px" => "96",
                		"coords" => "449,96,487,134"
                	),
                	array(
                		"left_px" => "488",
                		"top_px" => "96",
                		"coords" => "488,96,526,134"
                	),
                	array(
                		"left_px" => "527",
                		"top_px" => "96",
                		"coords" => "527,96,565,134"
                	),
                	array(
                		"left_px" => "20",
                		"top_px" => "135",
                		"coords" => "20,135,58,173"
                    ),
                	array(
                		"left_px" => "59",
                		"top_px" => "135",
                		"coords" => "59,135,97,173"
                    ),
                	array(
                		"left_px" => "98",
                		"top_px" => "135",
                		"coords" => "98,135,136,173"
                    ),
                	array(
                		"left_px" => "137",
                		"top_px" => "135",
                		"coords" => "137,135,175,173"
                    ),
                	array(
                		"left_px" => "176",
                		"top_px" => "135",
                		"coords" => "176,135,214,173"
                    ),
                	array(
                		"left_px" => "215",
                		"top_px" => "135",
                		"coords" => "215,135,253,173"
                    ),
                	array(
                		"left_px" => "254",
                		"top_px" => "135",
                		"coords" => "254,135,292,173"
                    ),
                	array(
                		"left_px" => "293",
                		"top_px" => "135",
                		"coords" => "293,135,331,173"
                    ),
                	array(
                		"left_px" => "332",
                		"top_px" => "135",
                		"coords" => "332,135,370,173"
                    ),
                	array(
                		"left_px" => "371",
                		"top_px" => "135",
                		"coords" => "371,135,409,173"
                    ),
                	array(
                		"left_px" => "410",
                		"top_px" => "135",
                		"coords" => "410,135,448,173"
                    ),
                	array(
                		"left_px" => "449",
                		"top_px" => "135",
                		"coords" => "449,135,487,173"
                    ),
                	array(
                		"left_px" => "488",
                		"top_px" => "135",
                		"coords" => "488,135,526,173"
                    ),
                	array(
                		"left_px" => "527",
                		"top_px" => "135",
                		"coords" => "527,135,565,173"
                    ),
                	array(
                		"left_px" => "20",
                		"top_px" => "174",
                		"coords" => "20,173,58,212"
                    ),
                	array(
                		"left_px" => "59",
                		"top_px" => "174",
                		"coords" => "59,173,97,212"
                    ),
                	array(
                		"left_px" => "98",
                		"top_px" => "174",
                		"coords" => "98,173,136,212" 
                    ),
                	array(
                		"left_px" => "137",
                		"top_px" => "174",
                		"coords" => "137,173,175,212"
                    ),
                	array(
                		"left_px" => "176",
                		"top_px" => "174",
                		"coords" => "176,173,214,212"
                    ),
                	array(
                		"left_px" => "215",
                		"top_px" => "174",
                		"coords" => "215,173,253,212"
                    ),
                	array(
                		"left_px" => "254",
                		"top_px" => "174",
                		"coords" => "254,173,292,212"
                    ),
                	array(
                		"left_px" => "293",
                		"top_px" => "174",
                		"coords" => "293,173,331,212"
                    ),
                	array(
                		"left_px" => "332",
                		"top_px" => "174",
                		"coords" => "332,173,370,212"
                    ),
                	array(
                		"left_px" => "371",
                		"top_px" => "174",
                		"coords" => "371,173,409,212"
                    ),
                	array(
                		"left_px" => "410",
                		"top_px" => "174",
                		"coords" => "410,173,448,212"
                    ),
                	array(
                		"left_px" => "449",
                		"top_px" => "174",
                		"coords" => "449,173,487,212"
                    ),
                	array(
                		"left_px" => "488",
                		"top_px" => "174",
                		"coords" => "488,173,526,212"
                    ),
                	array(
                		"left_px" => "527",
                		"top_px" => "174",
                		"coords" => "527,173,565,212"
                    ),
                	array(
                		"left_px" => "20",
                		"top_px" => "213",
                		"coords" => "20,212,58,251"
                    ),
                	array(
                		"left_px" => "59",
                		"top_px" => "213",
                		"coords" => "59,212,97,251"
                    ),
                	array(
                		"left_px" => "98",
                		"top_px" => "213",
                		"coords" => "98,212,136,251"
                    ),
                	array(
                		"left_px" => "137",
                		"top_px" => "213",
                		"coords" => "137,212,175,251"
                    ),
                	array(
                		"left_px" => "176",
                		"top_px" => "213",
                		"coords" => "176,212,214,251"
                    ),
                	array(
                		"left_px" => "215",
                		"top_px" => "213",
                		"coords" => "215,212,253,251"
                    ),
                	array(
                		"left_px" => "254",
                		"top_px" => "213",
                		"coords" => "254,212,292,251"
                    ),
                	array(
                		"left_px" => "293",
                		"top_px" => "213",
                		"coords" => "293,212,331,251"
                    ),
                	array(
                		"left_px" => "332",
                		"top_px" => "213",
                		"coords" => "332,212,370,251"
                    ),
                	array(
                		"left_px" => "371",
                		"top_px" => "213",
                		"coords" => "371,212,409,251"
                    ),
                	array(
                		"left_px" => "410",
                		"top_px" => "213",
                		"coords" => "410,212,448,251"
                    ),
                	array(
                		"left_px" => "449",
                		"top_px" => "213",
                		"coords" => "449,212,487,251"
                    ),
                	array(
                		"left_px" => "488",
                		"top_px" => "213",
                		"coords" => "488,212,526,251"
                    ),
                	array(
                		"left_px" => "527",
                		"top_px" => "213",
                		"coords" => "527,212,565,251"
                    ),
                 	array(
                		"left_px" => "20",
                		"top_px" => "252",
                		"coords" => "20,251,58,290"
                    ),
                	array(
                		"left_px" => "59",
                		"top_px" => "252",
                		"coords" => "59,251,97,290"
                    ),
                	array(
                		"left_px" => "98",
                		"top_px" => "252",
                		"coords" => "98,251,136,290"
                    ),
                	array(
                		"left_px" => "137",
                		"top_px" => "252",
                		"coords" => "137,251,175,290"
                    ),
                	array(
                		"left_px" => "176",
                		"top_px" => "252",
                		"coords" => "176,251,214,290"
                    ),
                	array(
                		"left_px" => "215",
                		"top_px" => "252",
                		"coords" => "215,251,253,290"
                    ),
                	array(
                		"left_px" => "254",
                		"top_px" => "252",
                		"coords" => "254,251,292,290"
                    ),
                	array(
                		"left_px" => "293",
                		"top_px" => "252",
                		"coords" => "293,251,331,290"
                    ),
                	array(
                		"left_px" => "332",
                		"top_px" => "252",
                		"coords" => "332,251,370,290"
                    ),
                	array(
                		"left_px" => "371",
                		"top_px" => "252",
                		"coords" => "371,251,409,290"
                    ),
                	array(
                		"left_px" => "410",
                		"top_px" => "252",
                		"coords" => "410,251,448,290"
                    ),
                	array(
                		"left_px" => "449",
                		"top_px" => "252",
                		"coords" => "449,251,487,290" 
                    ),
                	array(
                		"left_px" => "488",
                		"top_px" => "252",
                		"coords" => "488,251,526,290"
                    ),
                	array(
                		"left_px" => "527",
                		"top_px" => "252",
                		"coords" => "527,251,565,290"
                    ),
                	array(
                		"left_px" => "20",
                		"top_px" => "291",
                		"coords" => "20,290,58,329"
                    ),
                	array(
                		"left_px" => "59",
                		"top_px" => "291",
                		"coords" => "59,290,97,329"
                    ),
                	array(
                		"left_px" => "98",
                		"top_px" => "291",
                		"coords" => "98,290,136,329"
                    ),
                	array(
                		"left_px" => "137",
                		"top_px" => "291",
                		"coords" => "137,290,175,329"
                    ),
                	array(
                		"left_px" => "176",
                		"top_px" => "291",
                		"coords" => "176,290,214,329"
                    ),
                	array(
                		"left_px" => "215",
                		"top_px" => "291",
                		"coords" => "215,291,253,329"
                    ),
                	array(
                		"left_px" => "254",
                		"top_px" => "291",
                		"coords" => "254,290,292,329"
                    ),
                	array(
                		"left_px" => "293",
                		"top_px" => "291",
                		"coords" => "293,290,331,329"
                    ),
                	array(
                		"left_px" => "332",
                		"top_px" => "291",
                		"coords" => "332,290,370,329"
                    ),
                	array(
                		"left_px" => "371",
                		"top_px" => "291",
                		"coords" => "371,290,409,329"
                    ),
                	array(
                		"left_px" => "410",
                		"top_px" => "291",
                		"coords" => "410,290,448,329"
                    ),
                	array(
                		"left_px" => "449",
                		"top_px" => "291",
                		"coords" => "449,290,487,329"
                    ),
                	array(
                		"left_px" => "488",
                		"top_px" => "291",
                		"coords" => "488,290,526,329"
                    ),
                	array(
                		"left_px" => "527",
                		"top_px" => "291",
                		"coords" => "527,290,565,329"
                    ),
                	array(
                		"left_px" => "20",
                		"top_px" => "330",
                		"coords" => "20,329,58,368"
                    ),
                	array(
                		"left_px" => "59",
                		"top_px" => "330",
                		"coords" => "59,329,97,368"
                    ),
                	array(
                		"left_px" => "98",
                		"top_px" => "330",
                		"coords" => "98,329,136,368"
                    ),
                	array(
                		"left_px" => "137",
                		"top_px" => "330",
                		"coords" => "137,329,175,368"
                    ),
                	array(
                		"left_px" => "176",
                		"top_px" => "330",
                		"coords" => "176,329,214,368"
                    ),
                    array(
                		"left_px" => "215",
                		"top_px" => "330",
                		"coords" => "215,329,253,368"
                    ),
                	array(
                		"left_px" => "254",
                		"top_px" => "330",
                		"coords" => "254,329,292,368"
                    ),
                	array(
                		"left_px" => "293",
                		"top_px" => "330",
                		"coords" => "293,329,331,368"
                    ),
                	array(
                		"left_px" => "332",
                		"top_px" => "330",
                		"coords" => "332,329,370,368"
                    ),
                	array(
                		"left_px" => "371",
                		"top_px" => "330",
                		"coords" => "371,329,409,368"
                    ),
                	array(
                		"left_px" => "410",
                		"top_px" => "330",
                		"coords" => "410,329,448,368"
                    ),
                	array(
                		"left_px" => "449",
                		"top_px" => "330",
                		"coords" => "449,329,487,368"
                    ),
                	array(
                		"left_px" => "488",
                		"top_px" => "330",
                		"coords" => "488,329,526,368"
                    ),
                	array(
                		"left_px" => "527",
                		"top_px" => "330",
                		"coords" => "527,329,565,368"
                    ),
                	array(
                		"left_px" => "20",
                		"top_px" => "369",
                		"coords" => "20,368,58,407"
                    ),
                	array(
                		"left_px" => "59",
                		"top_px" => "369",
                		"coords" => "59,368,97,407"
                    ),
                	array(
                		"left_px" => "98",
                		"top_px" => "369",
                		"coords" => "98,368,136,407"
                    ),
                	array(
                		"left_px" => "137",
                		"top_px" => "369",
                		"coords" => "137,368,175,407"
                    ),
                	array(
                		"left_px" => "176",
                		"top_px" => "369",
                		"coords" => "176,368,214,407"
                    ),
                	array(
                		"left_px" => "215",
                		"top_px" => "369",
                		"coords" => "215,368,253,407"
                    ),
                	array(
                		"left_px" => "254",
                		"top_px" => "369",
                		"coords" => "254,368,292,407"
                    ),
                	array(
                		"left_px" => "293",
                		"top_px" => "369",
                		"coords" => "293,368,331,407"
                    ),
                	array(
                		"left_px" => "332",
                		"top_px" => "369",
                		"coords" => "332,368,370,407"
                    ),
                	array(
                		"left_px" => "371",
                		"top_px" => "369",
                		"coords" => "371,368,409,407"
                    ),
                	array(
                		"left_px" => "410",
                		"top_px" => "369",
                		"coords" => "410,368,448,407"
                    ),
                	array(
                		"left_px" => "449",
                		"top_px" => "369",
                		"coords" => "449,368,487,407"
                    ),
                	array(
                		"left_px" => "488",
                		"top_px" => "369",
                		"coords" => "488,368,526,407"
                    ),
                	array(
                		"left_px" => "527",
                		"top_px" => "369",
                		"coords" => "527,368,565,407"
                    ),
                	array(
                		"left_px" => "20",
                		"top_px" => "408",
                		"coords" => "20,407,58,446"
                    ),
                	array(
                		"left_px" => "59",
                		"top_px" => "408",
                		"coords" => "59,407,97,446"
                    ),
                	array(
                		"left_px" => "98",
                		"top_px" => "408",
                		"coords" => "98,407,136,446"
                    ),
                	array(
                		"left_px" => "137",
                		"top_px" => "408",
                		"coords" => "137,407,175,446"
                    ),
                	array(
                		"left_px" => "176",
                		"top_px" => "408",
                		"coords" => "176,407,214,446"
                    ),
                	array(
                		"left_px" => "215",
                		"top_px" => "408",
                		"coords" => "215,407,253,446"
                    ),
                	array(
                		"left_px" => "254",
                		"top_px" => "408",
                		"coords" => "254,407,292,446"
                    ),
                	array(
                		"left_px" => "293",
                		"top_px" => "408",
                		"coords" => "293,407,331,446"
                    ),
                	array(
                		"left_px" => "332",
                		"top_px" => "408",
                		"coords" => "332,407,370,446"
                    ),
                	array(
                		"left_px" => "371",
                		"top_px" => "408",
                		"coords" => "371,407,409,446"
                    ),
                	array(
                		"left_px" => "410",
                		"top_px" => "408",
                		"coords" => "410,407,448,446"
                    ),
                	array(
                		"left_px" => "449",
                		"top_px" => "408",
                		"coords" => "449,407,487,446"
                    ),
                	array(
                		"left_px" => "488",
                		"top_px" => "408",
                		"coords" => "488,407,526,446"
                    ),
                	array(
                		"left_px" => "527",
                		"top_px" => "408",
                		"coords" => "527,407,565,446"
                    )
                );
                break;
            
            /**
             * TEXAS
             **/
            case 5:
                $groundCoords = array(
                    // Rij 1
                	1 => array(
                		"left_px" => "164",
                		"top_px" => "8",
                		"coords" => "164,8,202,45"
                	),
                	array(
                		"left_px" => "203",
                		"top_px" => "8",
                		"coords" => "203,8,241,45"
                	),
                	array(
                		"left_px" => "242",
                		"top_px" => "8",
                		"coords" => "242,8,280,45"
                	),
                	array(
                		"left_px" => "281",
                		"top_px" => "8",
                		"coords" => "281,8,319,45"
                	),
                    
                    // Rij 2
                	array(
                		"left_px" => "164",
                		"top_px" => "46",

                		"coords" => "164,46,202,84"
                	),
                	array(
                		"left_px" => "203",
                		"top_px" => "46",
                		"coords" => "203,46,241,84"
                	),
                	array(
                		"left_px" => "242",
                		"top_px" => "46",
                		"coords" => "242,46,280,84"
                	),
                	array(
                		"left_px" => "281",
                		"top_px" => "46",
                		"coords" => "281,46,319,84"
                	),
                    
                    // Rij 3
                	array(
                		"left_px" => "164",
                		"top_px" => "85",
                		"coords" => "164,85,202,123"
                	),
                	array(
                		"left_px" => "203",
                		"top_px" => "85",
                		"coords" => "203,85,241,123"
                	),
                	array(
                		"left_px" => "242",
                		"top_px" => "85",
                		"coords" => "242,85,280,123"
                	),
                	array(
                		"left_px" => "281",
                		"top_px" => "85",
                		"coords" => "281,85,319,123"
                	),
                    
                    // Rij 4
                	array(
                		"left_px" => "164",
                		"top_px" => "124",
                		"coords" => "164,124,202,162"
                	),
                	array(
                		"left_px" => "203",
                		"top_px" => "124",
                		"coords" => "203,124,241,162"
                	),
                	array(
                		"left_px" => "242",
                		"top_px" => "124",
                		"coords" => "242,124,280,162"
                	),
                	array(
                		"left_px" => "281",
                		"top_px" => "124",
                		"coords" => "281,124,319,162"
                	),
                	array(
                		"left_px" => "320",
                		"top_px" => "124",
                		"coords" => "320,124,358,162"
                	),
                	array(
                		"left_px" => "359",
                		"top_px" => "124",
                		"coords" => "359,124,397,162"
                	),
                	array(
                		"left_px" => "398",
                		"top_px" => "124",
                		"coords" => "398,124,436,162"
                	),
                	array(
                		"left_px" => "437",
                		"top_px" => "124",
                		"coords" => "437,124,475,162"
                	),
                	array(
                		"left_px" => "476",
                		"top_px" => "124",
                		"coords" => "476,124,514,162"
                	),
                	array(
                		"left_px" => "515",
                		"top_px" => "124",
                		"coords" => "515,124,553,162"
                	),
                    
                    // Rij 5
                	array(
                		"left_px" => "164",
                		"top_px" => "163",
                		"coords" => "164,163,202,201"
                	),
                	array(
                		"left_px" => "203",
                		"top_px" => "163",
                		"coords" => "203,163,241,201"
                	),
                	array(
                		"left_px" => "242",
                		"top_px" => "163",
                		"coords" => "242,163,280,201"
                	),
                	array(
                		"left_px" => "281",
                		"top_px" => "163",
                		"coords" => "281,163,319,201"
                	),
                	array(
                		"left_px" => "320",
                		"top_px" => "163",
                		"coords" => "320,163,358,201"
                	),
                	array(
                		"left_px" => "359",
                		"top_px" => "163",
                		"coords" => "359,163,397,201"
                	),
                	array(
                		"left_px" => "398",
                		"top_px" => "163",
                		"coords" => "398,163,436,201"
                	),
                    array(
                		"left_px" => "437",
                		"top_px" => "163",
                		"coords" => "437,163,475,201"
                	),
                	array(
                		"left_px" => "476",
                		"top_px" => "163",
                		"coords" => "476,163,514,201"
                	),
                	array(
                		"left_px" => "515",
                		"top_px" => "163",
                		"coords" => "515,163,553,201"
                	),
                	array(
                		"left_px" => "554",
                		"top_px" => "163",
                		"coords" => "554,163,592,201"
                	),
                    
                    // Rij 6
                	array(
                		"left_px" => "164",
                		"top_px" => "202",
                		"coords" => "164,202,202,240"
                	),
                	array(
                		"left_px" => "203",
                		"top_px" => "202",
                		"coords" => "203,202,241,240"
                	),
                	array(
                		"left_px" => "242",
                		"top_px" => "202",
                		"coords" => "242,202,280,240,"
                	),
                	array(
                		"left_px" => "281",
                		"top_px" => "202",
                		"coords" => "281,202,319,240"
                	),
                	array(
                		"left_px" => "320",
                		"top_px" => "202",
                		"coords" => "320,202,358,240"
                	),
                	array(
                		"left_px" => "359",
                		"top_px" => "202",
                		"coords" => "359,202,397,240"
                	),
                	array(
                		"left_px" => "398",
                		"top_px" => "202",
                		"coords" => "398,202,436,240"
                	),
                	array(
                		"left_px" => "437",
                		"top_px" => "202",
                		"coords" => "437,202,475,240"
                	),
                	array(
                		"left_px" => "476",
                		"top_px" => "202",
                		"coords" => "476,202,514,240"
                	),
                	array(
                		"left_px" => "515",
                		"top_px" => "202",
                		"coords" => "515,202,553,240"
                	),
                	array(
                		"left_px" => "554",
                		"top_px" => "202",
                		"coords" => "554,202,592,240"
                	),
                    
                    //  Rij 7
                	array(
                		"left_px" => "8",
                		"top_px" => "241",
                		"coords" => "8,241,46,279"
                	),
                	array(
                		"left_px" => "47",
                		"top_px" => "241",
                		"coords" => "47,241,85,279"
                	),
                	array(
                		"left_px" => "86",
                		"top_px" => "241",
                		"coords" => "86,241,124,279"
                	),
                	array(
                		"left_px" => "125",
                		"top_px" => "241",
                		"coords" => "125,241,163,279"
                	),
                	array(
                		"left_px" => "164",
                		"top_px" => "241",
                		"coords" => "164,241,202,279"
                	),
                	array(
                		"left_px" => "203",
                		"top_px" => "241",
                		"coords" => "203,241,241,279"
                	),
                	array(
                		"left_px" => "242",
                		"top_px" => "241",
                		"coords" => "242,241,280,279"
                	),
                	array(
                		"left_px" => "281",
                		"top_px" => "241",
                		"coords" => "281,241,319,279"
                	),
                	array(
                		"left_px" => "320",
                		"top_px" => "241",
                		"coords" => "320,241,358,279"
                	),
                	array(
                		"left_px" => "359",
                		"top_px" => "241",
                		"coords" => "359,241,397,279"
                	),
                	array(
                		"left_px" => "398",
                		"top_px" => "241",
                		"coords" => "398,241,436,279"
                	),
                	array(
                		"left_px" => "437",
                		"top_px" => "241",
                		"coords" => "437,241,475,279"
                	),
                	array(
                		"left_px" => "476",
                		"top_px" => "241",
                		"coords" => "476,241,514,279"
                	),
                	array(
                		"left_px" => "515",
                		"top_px" => "241",
                		"coords" => "515,241,553,279"
                	),
                	array(
                		"left_px" => "554",
                		"top_px" => "241",
                		"coords" => "554,241,592,279"
                	),
                    
                    // Rij 8
                	array(
                		"left_px" => "47",
                		"top_px" => "280",
                		"coords" => "47,280,85,318"
                	),
                	array(
                		"left_px" => "86",
                		"top_px" => "280",
                		"coords" => "86,280,124,318"
                	),
                	array(
                		"left_px" => "125",
                		"top_px" => "280",
                		"coords" => "125,280,163,318"
                	),
                	array(
                		"left_px" => "164",
                		"top_px" => "280",
                		"coords" => "164,280,202,318"
                	),
                	array(
                		"left_px" => "203",
                		"top_px" => "280",
                		"coords" => "203,280,241,318"
                	),
                	array(
                		"left_px" => "242",
                		"top_px" => "280",
                		"coords" => "242,280,280,318"
                	),
                	array(
                		"left_px" => "281",
                		"top_px" => "280",
                		"coords" => "281,280,319,318"
                	),
                	array(
                		"left_px" => "320",
                		"top_px" => "280",
                		"coords" => "320,280,358,318"
                	),
                	array(
                		"left_px" => "359",
                		"top_px" => "280",
                		"coords" => "359,280,397,318"
                	),
                	array(
                		"left_px" => "398",
                		"top_px" => "280",
                		"coords" => "398,280,436,318"
                	),
                	array(
                		"left_px" => "437",
                		"top_px" => "280",
                		"coords" => "437,280,475,318"
                	),
                	array(
                		"left_px" => "476",
                		"top_px" => "280",
                		"coords" => "476,280,514,318"
                	),
                	array(
                		"left_px" => "515",
                		"top_px" => "280",
                		"coords" => "515,280,553,318"
                	),
                	array(
                		"left_px" => "554",
                		"top_px" => "280",
                		"coords" => "554,280,592,318"
                	),
                    
                    // Rij 9
                	array(
                		"left_px" => "86",
                		"top_px" => "319",
                		"coords" => "86,319,124,357"
                	),
                	array(
                		"left_px" => "125",
                		"top_px" => "319",
                		"coords" => "125,319,163,357"
                	),
                	array(
                		"left_px" => "164",
                		"top_px" => "319",
                		"coords" => "164,319,202,357"
                	),
                	array(
                		"left_px" => "203",
                		"top_px" => "319",
                		"coords" => "203,319,241,357"
                	),
                	array(
                		"left_px" => "242",
                		"top_px" => "319",
                		"coords" => "242,319,280,357"
                	),
                	array(
                		"left_px" => "281",
                		"top_px" => "319",
                		"coords" => "281,319,319,357"
                	),
                	array(
                		"left_px" => "320",
                		"top_px" => "319",
                		"coords" => "320,319,358,357"
                	),
                	array(
                		"left_px" => "359",
                		"top_px" => "319",
                		"coords" => "359,319,397,357"
                	),
                	array(
                		"left_px" => "398",
                		"top_px" => "319",
                		"coords" => "398,319,436,357"
                	),
                	array(
                		"left_px" => "437",
                		"top_px" => "319",
                		"coords" => "437,319,475,357"
                	),
                	array(
                		"left_px" => "476",
                		"top_px" => "319",
                		"coords" => "476,319,514,357"
                	),
                	array(
                		"left_px" => "515",
                		"top_px" => "319",
                		"coords" => "515,319,553,357"
                	),
                	array(
                		"left_px" => "554",
                		"top_px" => "319",
                		"coords" => "554,319,592,357"
                	),
                    
                    // Rij 10
                	array(
                		"left_px" => "86",
                		"top_px" => "358",
                		"coords" => "86,358,124,396"
                	),
                	array(
                		"left_px" => "125",
                		"top_px" => "358",
                		"coords" => "125,358,163,396"
                	),
                	array(
                		"left_px" => "164",
                		"top_px" => "358",
                		"coords" => "164,358,202,396"
                	),
                    // -- leeg vakje --
                	array(
                		"left_px" => "242",
                		"top_px" => "358",
                		"coords" => "242,358,280,396"
                	),
                	array(
                		"left_px" => "281",
                		"top_px" => "358",
                		"coords" => "281,358,319,396"
                	),
                	array(
                		"left_px" => "320",
                		"top_px" => "358",
                		"coords" => "320,358,358,396"
                	),
                	array(
                		"left_px" => "359",
                		"top_px" => "358",
                		"coords" => "359,358,397,396"
                	),
                	array(
                		"left_px" => "398",
                		"top_px" => "358",
                		"coords" => "398,358,436,396"
                	),
                	array(
                		"left_px" => "437",
                		"top_px" => "358",
                		"coords" => "437,358,475,396"
                	),
                	array(
                		"left_px" => "476",
                		"top_px" => "358",
                		"coords" => "476,358,514,396"
                	),
                	array(
                		"left_px" => "515",
                		"top_px" => "358",
                		"coords" => "515,358,553,396"
                	),
                    
                    // Rij 11
                	array(
                		"left_px" => "281",
                		"top_px" => "397",
                		"coords" => "281,397,319,435"
                	),
                	array(
                		"left_px" => "320",
                		"top_px" => "397",
                		"coords" => "320,397,358,435"
                	),
                	array(
                		"left_px" => "359",
                		"top_px" => "397",
                		"coords" => "359,397,397,435"
                	),
                	array(
                		"left_px" => "398",
                		"top_px" => "397",
                		"coords" => "398,397,436,435"
                	),
                	array(
                		"left_px" => "437",
                		"top_px" => "397",
                		"coords" => "437,397,475,435"
                	),
                	array(
                		"left_px" => "476",
                		"top_px" => "397",
                		"coords" => "476,397,514,435"
                	),
                    
                    // Rij 12
                	array(
                		"left_px" => "281",
                		"top_px" => "436",
                		"coords" => "281,436,319,474"
                	),
                	array(
                		"left_px" => "320",
                		"top_px" => "436",
                		"coords" => "320,436,358,474"
                	),
                	array(
                		"left_px" => "359",
                		"top_px" => "436",
                		"coords" => "359,436,397,474"
                	),
                	array(
                		"left_px" => "398",
                		"top_px" => "436",
                		"coords" => "398,436,436,474"
                	),
                    
                    // Rij 13
                	array(
                		"left_px" => "320",
                		"top_px" => "475",
                		"coords" => "320,475,358,513"
                	),
                	array(
                		"left_px" => "359",
                		"top_px" => "475",
                		"coords" => "359,475,397,513"
                	),
                	array(
                		"left_px" => "398",
                		"top_px" => "475",
                		"coords" => "398,475,436,513"
                	),
                    
                    // Rij 14
                	array(
                		"left_px" => "320",
                		"top_px" => "514",
                		"coords" => "320,514,358,552"
                	),
                	array(
                		"left_px" => "359",
                		"top_px" => "514",
                		"coords" => "359,514,397,552" 
                	),
                	array(
                		"left_px" => "398",
                		"top_px" => "514",
                		"coords" => "398,514,436,552"
                	)
                );
                break;
            
            /**
             * FLORIDA
             **/
            case 6:
                $groundCoords = array(
                    // Rij 1
                	1 => array(
                		"left_px" => "9",
                		"top_px" => "8",
                		"coords" => "9,8,47,46"
               	    ),
                    array(
                        "left_px" => "48",
                        "top_px" => "8",
                        "coords" => "48,8,86,46"
                    ),
                    array(
                        "left_px" => "87",
                        "top_px" => "8",
                        "coords" => "87,8,125,46"
                    ),
                    array(
                        "left_px" => "126",
                        "top_px" => "8",
                        "coords" => "126,8,164,46"
                    ),
                    
                    array(
                		"left_px" => "9",
                		"top_px" => "47",
                		"coords" => "9,47,47,85"
               	    ),
                    array(
                        "left_px" => "48",
                        "top_px" => "47",
                        "coords" => "48,47,86,85"
                    ),
                    array(
                        "left_px" => "87",
                        "top_px" => "47",
                        "coords" => "87,47,125,85"
                    ),
                    array(
                        "left_px" => "126",
                        "top_px" => "47",
                        "coords" => "126,47,164,85"
                    ),
                    array(
                        "left_px" => "165",
                        "top_px" => "47",
                        "coords" => "165,47,203,85"
                    ),
                    array(
                        "left_px" => "204",
                        "top_px" => "47",
                        "coords" => "204,47,242,85"
                    ),
                    array(
                        "left_px" => "243",
                        "top_px" => "47",
                        "coords" => "243,47,281,85"
                    ),
                    array(
                        "left_px" => "282",
                        "top_px" => "47",
                        "coords" => "282,47,320,85"
                    ),
                    array(
                        "left_px" => "321",
                        "top_px" => "47",
                        "coords" => "321,47,359,85"
                    ),
                    array(
                        "left_px" => "360",
                        "top_px" => "47",
                        "coords" => "360,47,398,85"
                    ),
                    
                    array(
                        "left_px" => "126",
                        "top_px" => "86",
                        "coords" => "126,86,164,124"
                    ),
                    array(
                        "left_px" => "165",
                        "top_px" => "86",
                        "coords" => "165,47,203,124"
                    ),
                    array(
                        "left_px" => "243",
                        "top_px" => "86",
                        "coords" => "243,86,281,124"
                    ),
                    array(
                        "left_px" => "282",
                        "top_px" => "86",
                        "coords" => "282,86,320,124"
                    ),
                    array(
                        "left_px" => "321",
                        "top_px" => "86",
                        "coords" => "321,86,359,124"
                    ),
                    array(
                        "left_px" => "360",
                        "top_px" => "86",
                        "coords" => "360,86,398,124"
                    ),
                    
                    array(
                        "left_px" => "282",
                        "top_px" => "125",
                        "coords" => "282,125,320,163"
                    ),
                    array(
                        "left_px" => "321",
                        "top_px" => "125",
                        "coords" => "321,125,359,163"
                    ),
                    array(
                        "left_px" => "360",
                        "top_px" => "125",
                        "coords" => "360,125,398,163"
                    ),
                    array(
                        "left_px" => "399",
                        "top_px" => "125",
                        "coords" => "399,125,437,163"
                    ),
                    
                    array(
                        "left_px" => "321",
                        "top_px" => "164",
                        "coords" => "321,164,359,202"
                    ),
                    array(
                        "left_px" => "360",
                        "top_px" => "164",
                        "coords" => "360,164,398,202"
                    ),
                    array(
                        "left_px" => "399",
                        "top_px" => "164",
                        "coords" => "399,164,437,202"
                    ),
                    
                    array(
                        "left_px" => "282",
                        "top_px" => "203",
                        "coords" => "282,203,320,241"
                    ),
                    array(
                        "left_px" => "321",
                        "top_px" => "203",
                        "coords" => "321,203,359,241"
                    ),
                    array(
                        "left_px" => "360",
                        "top_px" => "203",
                        "coords" => "360,203,398,241"
                    ),
                    array(
                        "left_px" => "399",
                        "top_px" => "203",
                        "coords" => "399,203,437,241"
                    ),
                    
                    array(
                        "left_px" => "282",
                        "top_px" => "242",
                        "coords" => "282,242,320,280"
                    ),
                    array(
                        "left_px" => "321",
                        "top_px" => "242",
                        "coords" => "321,242,359,280"
                    ),
                    array(
                        "left_px" => "360",
                        "top_px" => "242",
                        "coords" => "360,242,398,280"
                    ),
                    array(
                        "left_px" => "399",
                        "top_px" => "242",
                        "coords" => "399,242,437,280"
                    ),
                    array(
                        "left_px" => "438",
                        "top_px" => "242",
                        "coords" => "438,242,476,280"
                    ),
                    
                    array(
                        "left_px" => "321",
                        "top_px" => "281",
                        "coords" => "321,281,359,319"
                    ),
                    array(
                        "left_px" => "360",
                        "top_px" => "281",
                        "coords" => "360,281,398,319"
                    ),
                    array(
                        "left_px" => "399",
                        "top_px" => "281",
                        "coords" => "399,281,437,319"
                    ),
                    array(
                        "left_px" => "438",
                        "top_px" => "281",
                        "coords" => "438,281,476,319"
                    ),
                    
                    array(
                        "left_px" => "321",
                        "top_px" => "320",
                        "coords" => "321,320,359,358"
                    ),
                    array(
                        "left_px" => "360",
                        "top_px" => "320",
                        "coords" => "360,320,398,358"
                    ),
                    array(
                        "left_px" => "399",
                        "top_px" => "320",
                        "coords" => "399,320,437,358"
                    ),
                    array(
                        "left_px" => "438",
                        "top_px" => "320",
                        "coords" => "438,320,476,358"
                    ),
                    
                    array(
                        "left_px" => "360",
                        "top_px" => "359",
                        "coords" => "360,359,398,397"
                    ),
                    array(
                        "left_px" => "399",
                        "top_px" => "359",
                        "coords" => "399,359,437,397"
                    ),
                    array(
                        "left_px" => "438",
                        "top_px" => "359",
                        "coords" => "438,359,476,397"
                    ),
                    
                    array(
                        "left_px" => "399",
                        "top_px" => "398",
                        "coords" => "399,398,437,436"
                    ),
                    array(
                        "left_px" => "438",
                        "top_px" => "398",
                        "coords" => "438,398,476,436"
                    ),
                    
                    array(
                        "left_px" => "399",
                        "top_px" => "437",
                        "coords" => "399,437,437,475"
                    ),
                    array(
                        "left_px" => "438",
                        "top_px" => "437",
                        "coords" => "438,437,476,475"
                    ),
                    
                    array(
                        "left_px" => "360",
                        "top_px" => "476",
                        "coords" => "360,476,398,514"
                    ),
                );
                break;
            default:
                $groundCoords = false;
                break;
        }
        return $groundCoords;
    }
}
