<?PHP
 
namespace src\Business;

use src\Business\CrimeService;
use src\Business\MissionService;
use src\Business\NotificationService;
use src\Business\DailyChallengeService;
use src\Business\PublicMissionService;
use src\Data\SmuggleDAO;
use app\config\Routing;

//DB: 1=Drugs, 2=Liquids, 3=Fireworks, 4=Weapons, 5=Exotic Animals
 
class SmuggleService
{
    private $data;
    public $pricesDrugs;
    public $pricesLiquids;
    public $pricesFireworks;
    public $pricesWeapons;
    public $pricesAnimals;
    
    public $prices;
    // Define each unit id to a 'virtual' id variant. used for profit index and selling / buying units forms, know what you do before editting any code below!
    public $unitNumbers = array(1 => "1", "1", "1", "1", "1",  "2", "2", "2", "2", "2",  "3", "3", "3", "3", "3",  "4", "4", "4", "4", "4",  "5", "5", "5", "5", "5",  "6", "6", "6", "6", "6",  "7", "7", "7", "7", "7",  "8", "8", "8", "8", "8",  "9", "9", "9", "9", "9",  "10", "10", "10", "10", "10",  "11", "11", "11", "11"); 
    public $typeNames;
    
    public function __construct($cityID, $tab, $donatorID) // Constructor only called once and otherwise referenced to as global since it carries alot of data.
    {
        $this->data = new SmuggleDAO();
        
        global $language;
        $l = $language->smugglingLangs();
        $this->typeNames = array(1 => "Drugs", $l['LIQUIDS'], $l['FIREWORKS'], $l['WEAPONS'], $l['EXOTIC_ANIMALS']);                
        
        switch($tab)
        {
            case 'drugs':
                $GoldMemberPrices_d = "i:1;i:110;i:2;i:117;i:3;i:123;i:4;i:128;i:5;i:111;i:6;i:134;i:7;i:155;i:8;i:170;i:9;i:148;i:10;i:133;i:11;i:286;";
                if($donatorID >= 10) $GoldMemberPrices_d = "i:1;i:109;i:2;i:118;i:3;i:124;i:4;i:129;i:5;i:110;i:6;i:133;i:7;i:154;i:8;i:168;i:9;i:147;i:10;i:132;i:11;i:285;";
                $this->pricesDrugs = "a:18:{i:1;a:11:{".$GoldMemberPrices_d."}i:2;a:11:{i:1;i:111;i:2;i:112;i:3;i:110;i:4;i:120;i:5;i:130;i:6;i:119;i:7;i:146;i:8;i:152;i:9;i:198;i:10;i:233;i:11;i:197;}i:3;a:11:{i:1;i:111;i:2;i:112;i:3;i:122;i:4;i:127;i:5;i:111;i:6;i:130;i:7;i:126;i:8;i:148;i:9;i:218;i:10;i:270;i:11;i:180;}i:4;a:11:{i:1;i:110;i:2;i:114;i:3;i:110;i:4;i:121;i:5;i:121;i:6;i:119;i:7;i:117;i:8;i:162;i:9;i:134;i:10;i:149;i:11;i:329;}i:5;a:11:{i:1;i:111;i:2;i:116;i:3;i:111;i:4;i:112;i:5;i:130;i:6;i:140;i:7;i:121;i:8;i:136;i:9;i:153;i:10;i:240;i:11;i:258;}i:6;a:11:{i:1;i:109;i:2;i:110;i:3;i:112;i:4;i:122;i:5;i:130;i:6;i:131;i:7;i:129;i:8;i:114;i:9;i:113;i:10;i:145;i:11;i:117;}i:7;a:11:{i:1;i:109;i:2;i:110;i:3;i:115;i:4;i:114;i:5;i:128;i:6;i:139;i:7;i:146;i:8;i:120;i:9;i:156;i:10;i:162;i:11;i:142;}i:8;a:11:{i:1;i:110;i:2;i:117;i:3;i:114;i:4;i:112;i:5;i:130;i:6;i:136;i:7;i:133;i:8;i:118;i:9;i:151;i:10;i:173;i:11;i:298;}i:9;a:11:{i:1;i:111;i:2;i:114;i:3;i:120;i:4;i:120;i:5;i:120;i:6;i:125;i:7;i:115;i:8;i:141;i:9;i:216;i:10;i:231;i:11;i:178;}i:10;a:11:{i:1;i:110;i:2;i:110;i:3;i:114;i:4;i:126;i:5;i:123;i:6;i:139;i:7;i:140;i:8;i:125;i:9;i:176;i:10;i:140;i:11;i:303;}i:11;a:11:{i:1;i:110;i:2;i:113;i:3;i:111;i:4;i:124;i:5;i:118;i:6;i:143;i:7;i:135;i:8;i:123;i:9;i:198;i:10;i:215;i:11;i:223;}i:12;a:11:{i:1;i:111;i:2;i:117;i:3;i:112;i:4;i:120;i:5;i:113;i:6;i:129;i:7;i:110;i:8;i:118;i:9;i:116;i:10;i:187;i:11;i:232;}i:13;a:11:{i:1;i:109;i:2;i:115;i:3;i:114;i:4;i:120;i:5;i:123;i:6;i:133;i:7;i:118;i:8;i:116;i:9;i:124;i:10;i:165;i:11;i:119;}i:14;a:11:{i:1;i:109;i:2;i:114;i:3;i:116;i:4;i:112;i:5;i:128;i:6;i:119;i:7;i:143;i:8;i:138;i:9;i:208;i:10;i:290;i:11;i:193;}i:15;a:11:{i:1;i:111;i:2;i:112;i:3;i:108;i:4;i:119;i:5;i:111;i:6;i:142;i:7;i:128;i:8;i:152;i:9;i:146;i:10;i:185;i:11;i:305;}i:16;a:11:{i:1;i:110;i:2;i:116;i:3;i:116;i:4;i:116;i:5;i:122;i:6;i:127;i:7;i:132;i:8;i:114;i:9;i:206;i:10;i:214;i:11;i:208;}i:17;a:11:{i:1;i:111;i:2;i:110;i:3;i:110;i:4;i:122;i:5;i:130;i:6;i:139;i:7;i:112;i:8;i:152;i:9;i:115;i:10;i:233;i:11;i:312;}i:18;a:11:{i:1;i:111;i:2;i:115;i:3;i:118;i:4;i:114;i:5;i:122;i:6;i:129;i:7;i:136;i:8;i:136;i:9;i:198;i:10;i:142;i:11;i:197;}}";
                if($cityID !== FALSE) $this->prices = serialize(unserialize($this->pricesDrugs)[$cityID]);
                else $this->prices = $this->pricesDrugs;
                break;
            case 'liquids':
                $GoldMemberPrices_l = "i:1;i:111;i:2;i:120;i:3;i:113;i:4;i:118;i:5;i:113;i:6;i:116;i:7;i:148;i:8;i:180;i:9;i:226;i:10;i:165;i:11;i:164;";
                if($donatorID >= 10) $GoldMemberPrices_l = "i:1;i:113;i:2;i:119;i:3;i:112;i:4;i:117;i:5;i:112;i:6;i:115;i:7;i:147;i:8;i:179;i:9;i:225;i:10;i:164;i:11;i:163;";
                $this->pricesLiquids = "a:18:{i:1;a:11:{".$GoldMemberPrices_l."}i:2;a:11:{i:1;i:109;i:2;i:116;i:3;i:120;i:4;i:110;i:5;i:134;i:6;i:131;i:7;i:117;i:8;i:158;i:9;i:157;i:10;i:180;i:11;i:117;}i:3;a:11:{i:1;i:109;i:2;i:114;i:3;i:116;i:4;i:126;i:5;i:110;i:6;i:133;i:7;i:112;i:8;i:112;i:9;i:213;i:10;i:168;i:11;i:286;}i:4;a:11:{i:1;i:109;i:2;i:115;i:3;i:115;i:4;i:119;i:5;i:124;i:6;i:139;i:7;i:153;i:8;i:170;i:9;i:150;i:10;i:226;i:11;i:214;}i:5;a:11:{i:1;i:110;i:2;i:112;i:3;i:117;i:4;i:118;i:5;i:119;i:6;i:126;i:7;i:118;i:8;i:152;i:9;i:127;i:10;i:280;i:11;i:256;}i:6;a:11:{i:1;i:110;i:2;i:115;i:3;i:120;i:4;i:112;i:5;i:126;i:6;i:137;i:7;i:126;i:8;i:166;i:9;i:219;i:10;i:163;i:11;i:303;}i:7;a:11:{i:1;i:111;i:2;i:111;i:3;i:115;i:4;i:122;i:5;i:128;i:6;i:145;i:7;i:111;i:8;i:130;i:9;i:162;i:10;i:257;i:11;i:174;}i:8;a:11:{i:1;i:112;i:2;i:110;i:3;i:120;i:4;i:117;i:5;i:117;i:6;i:111;i:7;i:152;i:8;i:126;i:9;i:165;i:10;i:144;i:11;i:249;}i:9;a:11:{i:1;i:111;i:2;i:113;i:3;i:115;i:4;i:116;i:5;i:115;i:6;i:130;i:7;i:129;i:8;i:126;i:9;i:122;i:10;i:212;i:11;i:278;}i:10;a:11:{i:1;i:109;i:2;i:115;i:3;i:123;i:4;i:111;i:5;i:117;i:6;i:129;i:7;i:121;i:8;i:128;i:9;i:111;i:10;i:186;i:11;i:339;}i:11;a:11:{i:1;i:111;i:2;i:117;i:3;i:115;i:4;i:111;i:5;i:114;i:6;i:127;i:7;i:122;i:8;i:130;i:9;i:207;i:10;i:230;i:11;i:152;}i:12;a:11:{i:1;i:112;i:2;i:116;i:3;i:118;i:4;i:124;i:5;i:132;i:6;i:111;i:7;i:161;i:8;i:168;i:9;i:120;i:10;i:131;i:11;i:238;}i:13;a:11:{i:1;i:110;i:2;i:117;i:3;i:117;i:4;i:112;i:5;i:119;i:6;i:134;i:7;i:126;i:8;i:152;i:9;i:156;i:10;i:198;i:11;i:256;}i:14;a:11:{i:1;i:111;i:2;i:113;i:3;i:115;i:4;i:126;i:5;i:115;i:6;i:119;i:7;i:129;i:8;i:166;i:9;i:123;i:10;i:139;i:11;i:323;}i:15;a:11:{i:1;i:109;i:2;i:116;i:3;i:120;i:4;i:110;i:5;i:134;i:6;i:137;i:7;i:119;i:8;i:114;i:9;i:157;i:10;i:180;i:11;i:119;}i:16;a:11:{i:1;i:112;i:2;i:110;i:3;i:116;i:4;i:117;i:5;i:110;i:6;i:123;i:7;i:142;i:8;i:126;i:9;i:165;i:10;i:214;i:11;i:289;}i:17;a:11:{i:1;i:112;i:2;i:112;i:3;i:118;i:4;i:124;i:5;i:132;i:6;i:111;i:7;i:158;i:8;i:128;i:9;i:115;i:10;i:131;i:11;i:248;}i:18;a:11:{i:1;i:109;i:2;i:114;i:3;i:123;i:4;i:114;i:5;i:126;i:6;i:136;i:7;i:113;i:8;i:136;i:9;i:209;i:10;i:276;i:11;i:184;}}";
                if($cityID !== FALSE) $this->prices = serialize(unserialize($this->pricesLiquids)[$cityID]);
                else $this->prices = $this->pricesLiquids;
                break;
            case 'fireworks':
                $GoldMemberPrices_f = "i:1;i:110;i:2;i:120;i:3;i:118;i:4;i:112;i:5;i:116;i:6;i:131;i:7;i:120;i:8;i:171;i:9;i:146;i:10;i:141;i:11;i:377;";
                if($donatorID >= 10) $GoldMemberPrices_f = "i:1;i:109;i:2;i:121;i:3;i:117;i:4;i:113;i:5;i:115;i:6;i:132;i:7;i:119;i:8;i:172;i:9;i:145;i:10;i:142;i:11;i:376;";
                $this->pricesFireworks = "a:18:{i:1;a:11:{".$GoldMemberPrices_f."}i:2;a:11:{i:1;i:110;i:2;i:112;i:3;i:113;i:4;i:117;i:5;i:129;i:6;i:134;i:7;i:166;i:8;i:128;i:9;i:139;i:10;i:189;i:11;i:305;}i:3;a:11:{i:1;i:113;i:2;i:118;i:3;i:119;i:4;i:119;i:5;i:113;i:6;i:145;i:7;i:141;i:8;i:138;i:9;i:120;i:10;i:119;i:11;i:116;}i:4;a:11:{i:1;i:113;i:2;i:113;i:3;i:113;i:4;i:115;i:5;i:122;i:6;i:130;i:7;i:143;i:8;i:135;i:9;i:221;i:10;i:278;i:11;i:271;}i:5;a:11:{i:1;i:110;i:2;i:117;i:3;i:123;i:4;i:127;i:5;i:131;i:6;i:118;i:7;i:115;i:8;i:110;i:9;i:234;i:10;i:203;i:11;i:290;}i:6;a:11:{i:1;i:113;i:2;i:111;i:3;i:115;i:4;i:114;i:5;i:117;i:6;i:123;i:7;i:158;i:8;i:167;i:9;i:143;i:10;i:203;i:11;i:327;}i:7;a:11:{i:1;i:114;i:2;i:117;i:3;i:122;i:4;i:111;i:5;i:130;i:6;i:134;i:7;i:164;i:8;i:154;i:9;i:193;i:10;i:125;i:11;i:143;}i:8;a:11:{i:1;i:111;i:2;i:113;i:3;i:118;i:4;i:111;i:5;i:122;i:6;i:148;i:7;i:119;i:8;i:133;i:9;i:229;i:10;i:161;i:11;i:377;}i:9;a:11:{i:1;i:114;i:2;i:113;i:3;i:117;i:4;i:117;i:5;i:123;i:6;i:147;i:7;i:122;i:8;i:140;i:9;i:226;i:10;i:270;i:11;i:325;}i:10;a:11:{i:1;i:111;i:2;i:112;i:3;i:114;i:4;i:112;i:5;i:123;i:6;i:145;i:7;i:120;i:8;i:175;i:9;i:185;i:10;i:115;i:11;i:379;}i:11;a:11:{i:1;i:110;i:2;i:115;i:3;i:117;i:4;i:114;i:5;i:120;i:6;i:125;i:7;i:163;i:8;i:137;i:9;i:155;i:10;i:164;i:11;i:361;}i:12;a:11:{i:1;i:112;i:2;i:115;i:3;i:123;i:4;i:116;i:5;i:132;i:6;i:116;i:7;i:134;i:8;i:136;i:9;i:232;i:10;i:112;i:11;i:287;}i:13;a:11:{i:1;i:111;i:2;i:113;i:3;i:116;i:4;i:119;i:5;i:115;i:6;i:129;i:7;i:116;i:8;i:111;i:9;i:123;i:10;i:162;i:11;i:373;}i:14;a:11:{i:1;i:110;i:2;i:117;i:3;i:121;i:4;i:116;i:5;i:130;i:6;i:117;i:7;i:132;i:8;i:148;i:9;i:187;i:10;i:116;i:11;i:279;}i:15;a:11:{i:1;i:114;i:2;i:113;i:3;i:117;i:4;i:117;i:5;i:127;i:6;i:118;i:7;i:117;i:8;i:162;i:9;i:216;i:10;i:270;i:11;i:119;}i:16;a:11:{i:1;i:113;i:2;i:115;i:3;i:113;i:4;i:123;i:5;i:124;i:6;i:128;i:7;i:153;i:8;i:175;i:9;i:229;i:10;i:144;i:11;i:322;}i:17;a:11:{i:1;i:111;i:2;i:111;i:3;i:123;i:4;i:125;i:5;i:116;i:6;i:138;i:7;i:164;i:8;i:134;i:9;i:193;i:10;i:135;i:11;i:147;}i:18;a:11:{i:1;i:110;i:2;i:112;i:3;i:119;i:4;i:113;i:5;i:118;i:6;i:146;i:7;i:153;i:8;i:124;i:9;i:142;i:10;i:261;i:11;i:277;}}";
                if($cityID !== FALSE) $this->prices = serialize(unserialize($this->pricesFireworks)[$cityID]);
                else $this->prices = $this->pricesFireworks;
                break;
            case 'weapons':
                $GoldMemberPrices_w = "i:1;i:113;i:2;i:111;i:3;i:118;i:4;i:113;i:5;i:117;i:6;i:153;i:7;i:166;i:8;i:191;i:9;i:231;i:10;i:181;i:11;i:339;";
                if($donatorID >= 10) $GoldMemberPrices_w = "i:1;i:114;i:2;i:110;i:3;i:117;i:4;i:112;i:5;i:118;i:6;i:152;i:7;i:165;i:8;i:192;i:9;i:230;i:10;i:180;i:11;i:340;";
                $this->pricesWeapons = "a:18:{i:1;a:11:{".$GoldMemberPrices_w."}i:2;a:11:{i:1;i:113;i:2;i:117;i:3;i:121;i:4;i:119;i:5;i:117;i:6;i:116;i:7;i:138;i:8;i:120;i:9;i:180;i:10;i:220;i:11;i:313;}i:3;a:11:{i:1;i:111;i:2;i:116;i:3;i:114;i:4;i:112;i:5;i:119;i:6;i:142;i:7;i:149;i:8;i:182;i:9;i:175;i:10;i:202;i:11;i:131;}i:4;a:11:{i:1;i:113;i:2;i:110;i:3;i:118;i:4;i:117;i:5;i:111;i:6;i:146;i:7;i:133;i:8;i:183;i:9;i:216;i:10;i:194;i:11;i:245;}i:5;a:11:{i:1;i:115;i:2;i:115;i:3;i:120;i:4;i:122;i:5;i:132;i:6;i:122;i:7;i:149;i:8;i:187;i:9;i:171;i:10;i:203;i:11;i:249;}i:6;a:11:{i:1;i:110;i:2;i:117;i:3;i:120;i:4;i:115;i:5;i:122;i:6;i:137;i:7;i:155;i:8;i:123;i:9;i:217;i:10;i:114;i:11;i:351;}i:7;a:11:{i:1;i:113;i:2;i:116;i:3;i:112;i:4;i:118;i:5;i:114;i:6;i:140;i:7;i:160;i:8;i:181;i:9;i:160;i:10;i:280;i:11;i:129;}i:8;a:11:{i:1;i:111;i:2;i:116;i:3;i:115;i:4;i:110;i:5;i:134;i:6;i:131;i:7;i:114;i:8;i:135;i:9;i:141;i:10;i:229;i:11;i:277;}i:9;a:11:{i:1;i:114;i:2;i:111;i:3;i:112;i:4;i:114;i:5;i:121;i:6;i:120;i:7;i:157;i:8;i:154;i:9;i:172;i:10;i:145;i:11;i:326;}i:10;a:11:{i:1;i:115;i:2;i:117;i:3;i:120;i:4;i:126;i:5;i:124;i:6;i:138;i:7;i:154;i:8;i:159;i:9;i:186;i:10;i:239;i:11;i:187;}i:11;a:11:{i:1;i:113;i:2;i:119;i:3;i:114;i:4;i:121;i:5;i:134;i:6;i:112;i:7;i:135;i:8;i:176;i:9;i:144;i:10;i:265;i:11;i:294;}i:12;a:11:{i:1;i:113;i:2;i:118;i:3;i:111;i:4;i:122;i:5;i:138;i:6;i:124;i:7;i:134;i:8;i:134;i:9;i:197;i:10;i:232;i:11;i:384;}i:13;a:11:{i:1;i:110;i:2;i:115;i:3;i:115;i:4;i:122;i:5;i:118;i:6;i:144;i:7;i:138;i:8;i:182;i:9;i:175;i:10;i:243;i:11;i:341;}i:14;a:11:{i:1;i:112;i:2;i:116;i:3;i:111;i:4;i:118;i:5;i:117;i:6;i:131;i:7;i:148;i:8;i:120;i:9;i:157;i:10;i:221;i:11;i:216;}i:15;a:11:{i:1;i:114;i:2;i:115;i:3;i:114;i:4;i:110;i:5;i:131;i:6;i:123;i:7;i:159;i:8;i:121;i:9;i:161;i:10;i:203;i:11;i:147;}i:16;a:11:{i:1;i:113;i:2;i:116;i:3;i:118;i:4;i:116;i:5;i:119;i:6;i:114;i:7;i:160;i:8;i:136;i:9;i:200;i:10;i:115;i:11;i:252;}i:17;a:11:{i:1;i:115;i:2;i:110;i:3;i:121;i:4;i:117;i:5;i:111;i:6;i:126;i:7;i:130;i:8;i:186;i:9;i:149;i:10;i:277;i:11;i:130;}i:18;a:11:{i:1;i:111;i:2;i:119;i:3;i:113;i:4;i:116;i:5;i:126;i:6;i:132;i:7;i:120;i:8;i:125;i:9;i:217;i:10;i:134;i:11;i:382;}}";
                if($cityID !== FALSE) $this->prices = serialize(unserialize($this->pricesWeapons)[$cityID]);
                else $this->prices = $this->pricesWeapons;
                break;
            case 'exotic-animals':
                $GoldMemberPrices_a = "i:1;i:107;i:2;i:120;i:3;i:126;i:4;i:111;i:5;i:109;i:6;i:145;i:7;i:110;i:8;i:136;i:9;i:264;i:10;i:121;";
                if($donatorID >= 10) $GoldMemberPrices_a = "i:1;i:108;i:2;i:119;i:3;i:127;i:4;i:110;i:5;i:110;i:6;i:144;i:7;i:111;i:8;i:135;i:9;i:265;i:10;i:120;";
                $this->pricesAnimals = "a:18:{i:1;a:10:{".$GoldMemberPrices_a."}i:2;a:10:{i:1;i:115;i:2;i:112;i:3;i:126;i:4;i:117;i:5;i:121;i:6;i:127;i:7;i:129;i:8;i:161;i:9;i:252;i:10;i:114;}i:3;a:10:{i:1;i:110;i:2;i:118;i:3;i:123;i:4;i:123;i:5;i:138;i:6;i:111;i:7;i:156;i:8;i:131;i:9;i:165;i:10;i:202;}i:4;a:10:{i:1;i:116;i:2;i:118;i:3;i:122;i:4;i:126;i:5;i:137;i:6;i:148;i:7;i:158;i:8;i:133;i:9;i:185;i:10;i:150;}i:5;a:10:{i:1;i:113;i:2;i:121;i:3;i:126;i:4;i:112;i:5;i:122;i:6;i:123;i:7;i:167;i:8;i:174;i:9;i:210;i:10;i:209;}i:6;a:10:{i:1;i:113;i:2;i:110;i:3;i:124;i:4;i:128;i:5;i:121;i:6;i:126;i:7;i:123;i:8;i:193;i:9;i:210;i:10;i:233;}i:7;a:10:{i:1;i:111;i:2;i:111;i:3;i:112;i:4;i:130;i:5;i:134;i:6;i:135;i:7;i:142;i:8;i:145;i:9;i:176;i:10;i:224;}i:8;a:10:{i:1;i:115;i:2;i:113;i:3;i:125;i:4;i:126;i:5;i:123;i:6;i:115;i:7;i:142;i:8;i:176;i:9;i:160;i:10;i:245;}i:9;a:10:{i:1;i:114;i:2;i:119;i:3;i:126;i:4;i:110;i:5;i:126;i:6;i:135;i:7;i:148;i:8;i:176;i:9;i:161;i:10;i:241;}i:10;a:10:{i:1;i:111;i:2;i:110;i:3;i:120;i:4;i:115;i:5;i:139;i:6;i:113;i:7;i:145;i:8;i:184;i:9;i:202;i:10;i:224;}i:11;a:10:{i:1;i:113;i:2;i:118;i:3;i:112;i:4;i:129;i:5;i:137;i:6;i:125;i:7;i:113;i:8;i:138;i:9;i:237;i:10;i:114;}i:12;a:10:{i:1;i:114;i:2;i:116;i:3;i:120;i:4;i:124;i:5;i:133;i:6;i:137;i:7;i:131;i:8;i:169;i:9;i:196;i:10;i:301;}i:13;a:10:{i:1;i:110;i:2;i:121;i:3;i:122;i:4;i:128;i:5;i:121;i:6;i:141;i:7;i:141;i:8;i:132;i:9;i:175;i:10;i:114;}i:14;a:10:{i:1;i:114;i:2;i:110;i:3;i:118;i:4;i:125;i:5;i:123;i:6;i:132;i:7;i:152;i:8;i:163;i:9;i:161;i:10;i:235;}i:15;a:10:{i:1;i:112;i:2;i:112;i:3;i:112;i:4;i:119;i:5;i:131;i:6;i:134;i:7;i:123;i:8;i:192;i:9;i:211;i:10;i:163;}i:16;a:10:{i:1;i:116;i:2;i:113;i:3;i:126;i:4;i:110;i:5;i:122;i:6;i:111;i:7;i:147;i:8;i:144;i:9;i:198;i:10;i:189;}i:17;a:10:{i:1;i:115;i:2;i:119;i:3;i:114;i:4;i:116;i:5;i:127;i:6;i:126;i:7;i:113;i:8;i:136;i:9;i:175;i:10;i:300;}i:18;a:10:{i:1;i:111;i:2;i:117;i:3;i:121;i:4;i:119;i:5;i:138;i:6;i:119;i:7;i:132;i:8;i:173;i:9;i:245;i:10;i:157;}}";
                if($cityID !== FALSE) $this->prices = serialize(unserialize($this->pricesAnimals)[$cityID]);
                else $this->prices = $this->pricesAnimals;
                break;
        }
    }
    
    public function __destruct()
    {
        $this->data = null;
    }
    
    public function getRecordsCount()
    {
        return $this->data->getRecordsCount();
    }
    
    public static function getTypeIdByType($type)
    {
        switch($type)
        {
            case 'drugs':
                $typeNr = 1;
                break;
            case 'liquids':
                $typeNr = 2;
                break;
            case 'fireworks':
                $typeNr = 3;
                break;
            case 'weapons':
                $typeNr = 4;
                break;
            case 'exotic-animals':
                $typeNr = 5;
                break;
        }
        if(isset($typeNr)) return $typeNr;
        else return FALSE;
    }
    
    public static function getTypeByUnitID($id)
    {
        switch(true)
        {
            case ($id == 1 || $id == 6 || $id == 11 || $id == 16 || $id == 21 || $id == 26 || $id == 31 || $id == 36 || $id == 41 || $id == 46 || $id == 51):
                $type = 'drugs';
                break;
            case ($id == 2 || $id == 7 || $id == 12 || $id == 17 || $id == 22 || $id == 27 || $id == 32 || $id == 37 || $id == 42 || $id == 47 || $id == 52):
                $type = 'liquids';
                break;
            case ($id == 3 || $id == 8 || $id == 13 || $id == 18 || $id == 23 || $id == 28 || $id == 33 || $id == 38 || $id == 43 || $id == 48 || $id == 53):
                $type = 'fireworks';
                break;
            case ($id == 4 || $id == 9 || $id == 14 || $id == 19 || $id == 24 || $id == 29 || $id == 34 || $id == 39 || $id == 44 || $id == 49 || $id == 54):
                $type = 'weapons';
                break;
            case ($id == 5 || $id == 10 || $id == 15 || $id == 20 || $id == 25 || $id == 30 || $id == 35 || $id == 40 || $id == 45 || $id == 50):
                $type = 'exotic-animals';
                break;
        }
        if(isset($type)) return $type;
        else return FALSE;
    }
    
    public function buyOrSellSmuggleUnits($post)
    {
        global $userService;
        global $userData;
        global $smuggle;
        global $language;
        global $langs;
        $l = $language->smugglingLangs();
        global $security;
        
        $id = (int)$post['id'];
        $type = self::getTypeIdByType($post['type']);
        $sData = $this->data->getSmugglingUnitById($id);
        $prices = unserialize($smuggle->prices);
        $amount = (int)$post['amount'];
        $totalPrice = ($amount * (int)$prices[$this->unitNumbers[$id]]);
        
        if($security->checkToken($post['security-token']) == FALSE)
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
        $sPage = $this->data->getSmugglingPageInfo($type);
        $userSmuggleLv = $sPage['user']->getSmugglingLv();
        $userSmuggleXp = $sPage['user']->getSmugglingXpRaw();
        
        if(($id < 1 || $id > 54) || !array_key_exists($id, $this->unitNumbers)
            || $type === FALSE || $sData === FALSE || ($sData['smuggle']->getType() != $type) || $amount > 9999
            || $amount < 1 || $sData['smuggle']->getLevel() > $userService->getUserProfile($userData->getUsername())->getSmugglingLv())
        {
            $error = $l['INVALID_UNIT_SELECTED'];
        }
        if(isset($post['buy']))
        {
            if($totalPrice > $userData->getCash())
            {
                $error = $langs['NOT_ENOUGH_MONEY_CASH'];
            }
            if($amount > $sData['unitsInfo']->getMaxCapacity())
            {
                global $route;
                
                $replacedMessage = $route->replaceMessagePart(strtolower($this->typeNames[$type]), $l['CANNOT_CARRY_THAT_MUCH'], '/{type}/');
                $replacedMessage = $route->replaceMessagePart(strtolower($sPage['unitsInfo']->getMaxCapacity()), $replacedMessage, '/{units}/');
                $error = $replacedMessage;
            }
        }
        elseif(isset($post['sell']))
        {
            if($amount > $sData['smuggle']->getUnitInfo()->getInPossession())
            {
                global $route;
                
                $replacedMessage = $route->replaceMessagePart(strtolower($sData['smuggle']->getName()), $l['DONT_HAVE_THAT_MANY'], '/{unitName}/');
                $error = $replacedMessage;
            }
        }
        else
        {
            $error = $langs['INVALID_ACTION'];
        }
        if(isset($error))
        {
            return Routing::errorMessage($error);
        }
        else
        {
            if(isset($post['buy']))
            {
                global $route;
                
                $_SESSION['smuggle'][$post['type']][$id] = $userData->getCityID();
                if($post['type'] == 'weapons')
                    $_SESSION['smuggle']['state']['weapons'][$id] = $userData->getStateID();
                
                $this->data->buyUnits($type, $this->unitNumbers[$id], $amount, $totalPrice);
                
                $replaces = array(
                    array('part' => number_format($amount, 0, '', ','), 'message' => $l['BOUGHT_X_UNITS_SUCCESS'], 'pattern' => '/{units}/'),
                    array('part' => strtolower($sData['smuggle']->getName()), 'message' => FALSE, 'pattern' => '/{unitName}/'),
                    array('part' => number_format($totalPrice, 0, '', ','), 'message' => FALSE, 'pattern' => '/{price}/')
                );
                $replacedMessage = $route->replaceMessageParts($replaces);
                return Routing::successMessage($replacedMessage);
            }
            elseif(isset($post['sell']))
            {
                if(isset($_SESSION['smuggle'][$post['type']][$id]) && $_SESSION['smuggle'][$post['type']][$id] != $userData->getCityID())
                {
                    $newLvlData = CrimeService::levelCalculations($userSmuggleLv, $userSmuggleXp);
                    unset($_SESSION['smuggle'][$post['type']][$id]);
                    
                    $missionService = new MissionService();
                    if($userSmuggleLv < $newLvlData['levelAfter'])
                    {
                        $m = 4;
                        $mTierProgress = $missionService->getMissionTierAndProgressByMission($m);
                        
                        $missionTier = $missionService->missionTiers[$m];
                        $todo = $missionTier['todo'][$mTierProgress['t']];
                        $bank = $missionTier['prizeMoney'][$mTierProgress['t']];
                        $hp = $missionTier['prizeHp'][$mTierProgress['t']];
                        if($mTierProgress['p'] + 1 >= $todo && $todo > $mTierProgress['p'])
                        {
                            $missionService->payoutMissionPrize($bank, $hp);
                            
                            $notification = new NotificationService();
                            $params = "mission=".$missionService->missions[$m]."&bank=".number_format($bank, 0, '', ',')."&hp=".number_format($hp, 0, '', ',');
                            $notification->sendNotification($userData->getId(), 'USER_ACHIEVED_MISSION', $params);
                        }
                    }
                    if(isset($_SESSION['smuggle']['state']['weapons'][$id]) && $_SESSION['smuggle']['state']['weapons'][$id] != $userData->getStateID() && $post['type'] == 'weapons')
                    {
                        $m = 8;
                        $mTierProgress = $missionService->getMissionTierAndProgressByMission($m);
                        if($mTierProgress['t'] == $userData->getStateID())
                        {
                            $missionTier = $missionService->missionTiers[$m];
                            $todo = $missionTier['todo'][$mTierProgress['t']];
                            $bank = $missionTier['prizeMoney'][$mTierProgress['t']];
                            $hp = $missionTier['prizeHp'][$mTierProgress['t']];
                            if($mTierProgress['p'] + $amount >= $todo && $todo > $mTierProgress['p'])
                            {
                                $missionService->addToMission8Count($todo - $mTierProgress['p']);
                                $missionService->payoutMissionPrize($bank, $hp);
                                
                                $notification = new NotificationService();
                                $params = "mission=".$missionService->missions[$m]."&bank=".number_format($bank, 0, '', ',')."&hp=".number_format($hp, 0, '', ',');
                                $notification->sendNotification($userData->getId(), 'USER_ACHIEVED_MISSION', $params);
                            }
                            elseif($todo > $mTierProgress['p'])
                                $missionService->addToMission8Count($amount);
                        }
                        unset($_SESSION['smuggle']['state']['weapons'][$id]);
                    }
                    
                    $dailyChallengeService = new DailyChallengeService();
                    $publicMissionService = new PublicMissionService();
                    switch($post['type'])
                    {
                        default:
                        case 'drugs':
                            $challengeID = $missionID = 2;
                            break;
                        case 'liquids':
                            $challengeID = 5;
                            $missionID = 4;
                            break;
                        case 'fireworks':
                            $challengeID = 8;
                            $missionID = 6;
                            break;
                        case 'weapons':
                            $challengeID = 11;
                            $missionID = 8;
                            break;
                        case 'exotic-animals':
                            $challengeID = 14;
                            $missionID = 10;
                            break;
                    }
                    $dailyChallengeService->addToDailiesIfActive($challengeID, $amount);
                    $publicMissionService->addToPublicMisionIfActive($missionID, $amount);
                    
                    $this->data->sellUnits($type, $this->unitNumbers[$id], $amount, $totalPrice, $newLvlData);
                }
                else
                    $this->data->sellUnits($type, $this->unitNumbers[$id], $amount, $totalPrice);
                                    
                global $route;
                
                $replaces = array(
                    array('part' => number_format($amount, 0, '', ','), 'message' => $l['SOLD_X_UNITS_SUCCESS'], 'pattern' => '/{units}/'),
                    array('part' => strtolower($sData['smuggle']->getName()), 'message' => FALSE, 'pattern' => '/{unitName}/'),
                    array('part' => number_format($totalPrice, 0, '', ','), 'message' => FALSE, 'pattern' => '/{price}/')
                );
                $replacedMessage = $route->replaceMessageParts($replaces);
                
                if(isset($newLvlData))
                {
                    global $userService;
                    $searchCreditsMessage = $userService->searchCredits($langs['SMUGGLING']);
                    if($searchCreditsMessage)
                        return array(Routing::successMessage($replacedMessage), $searchCreditsMessage);
                }
                return Routing::successMessage($replacedMessage);                    
            }
        }
    }
    
    public function getSmugglingPageInfo($type = 1)
    {
        return $this->data->getSmugglingPageInfo($type);
    }
    
    public function getSmugglingUnitById($id)
    {
        return $this->data->getSmugglingUnitById($id);
    }
    
    public function getSmugglingUnitsInPossession()
    {
        return $this->data->getSmugglingUnitsInPossession();
    }
    
    public function removeAllSmugglingUnits()
    {
        return $this->data->removeAllSmugglingUnits();
    }
}
