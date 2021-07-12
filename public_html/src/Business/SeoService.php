<?PHP

namespace src\Business;

use src\Business\Logic\SeoURL;
use src\Data\SeoDAO;
 
class SeoService extends SeoURL
{
    private $data;
    
    public function __construct()
    {
        $this->data = new SeoDAO();
    }
    
    public function __destruct()
    {
        $this->data = null;
    }
    
    public function getRecordsCount()
    {
        return $this->data->getRecordsCount();
    }
    
    public static function transliterateString($txt)
    {
        $transliterationTable = array('á' => 'a', 'Á' => 'A', 'à' => 'a', 'À' => 'A', 'a' => 'a', 'A' => 'A', 'â' => 'a', 'Â' => 'A', 'å' => 'a', 'Å' => 'A', 'ã' => 'a', 'Ã' => 'A', 'a' => 'a', 'A' => 'A', 'a' => 'a', 'A' => 'A', 'ä' => 'ae', 'Ä' => 'AE', 'æ' => 'ae', 'Æ' => 'AE', '?' => 'b', '?' => 'B', 'c' => 'c', 'C' => 'C', 'c' => 'c', 'C' => 'C', 'c' => 'c', 'C' => 'C', 'c' => 'c', 'C' => 'C', 'ç' => 'c', 'Ç' => 'C', 'd' => 'd', 'D' => 'D', '?' => 'd', '?' => 'D', 'd' => 'd', 'Ð' => 'D', 'ð' => 'dh', 'Ð' => 'Dh', 'é' => 'e', 'É' => 'E', 'è' => 'e', 'È' => 'E', 'e' => 'e', 'E' => 'E', 'ê' => 'e', 'Ê' => 'E', 'e' => 'e', 'E' => 'E', 'ë' => 'e', 'Ë' => 'E', 'e' => 'e', 'E' => 'E', 'e' => 'e', 'E' => 'E', 'e' => 'e', 'E' => 'E', '?' => 'f', '?' => 'F', 'ƒ' => 'f', 'ƒ' => 'F', 'g' => 'g', 'G' => 'G', 'g' => 'g', 'G' => 'G', 'g' => 'g', 'G' => 'G', 'g' => 'g', 'G' => 'G', 'h' => 'h', 'H' => 'H', 'h' => 'h', 'H' => 'H', 'í' => 'i', 'Í' => 'I', 'ì' => 'i', 'Ì' => 'I', 'î' => 'i', 'Î' => 'I', 'ï' => 'i', 'Ï' => 'I', 'i' => 'i', 'I' => 'I', 'i' => 'i', 'I' => 'I', 'i' => 'i', 'I' => 'I', 'j' => 'j', 'J' => 'J', 'k' => 'k', 'K' => 'K', 'l' => 'l', 'L' => 'L', 'l' => 'l', 'L' => 'L', 'l' => 'l', 'L' => 'L', 'l' => 'l', 'L' => 'L', '?' => 'm', '?' => 'M', 'n' => 'n', 'N' => 'N', 'n' => 'n', 'N' => 'N', 'ñ' => 'n', 'Ñ' => 'N', 'n' => 'n', 'N' => 'N', 'ó' => 'o', 'Ó' => 'O', 'ò' => 'o', 'Ò' => 'O', 'ô' => 'o', 'Ô' => 'O', 'o' => 'o', 'O' => 'O', 'õ' => 'o', 'Õ' => 'O', 'ø' => 'oe', 'Ø' => 'OE', 'o' => 'o', 'O' => 'O', 'o' => 'o', 'O' => 'O', 'ö' => 'oe', 'Ö' => 'OE', '?' => 'p', '?' => 'P', 'r' => 'r', 'R' => 'R', 'r' => 'r', 'R' => 'R', 'r' => 'r', 'R' => 'R', 's' => 's', 'S' => 'S', 's' => 's', 'S' => 'S', 'š' => 's', 'Š' => 'S', '?' => 's', '?' => 'S', 's' => 's', 'S' => 'S', '?' => 's', '?' => 'S', 'ß' => 'SS', 't' => 't', 'T' => 'T', '?' => 't', '?' => 'T', 't' => 't', 'T' => 'T', '?' => 't', '?' => 'T', 't' => 't', 'T' => 'T', 'ú' => 'u', 'Ú' => 'U', 'ù' => 'u', 'Ù' => 'U', 'u' => 'u', 'U' => 'U', 'û' => 'u', 'Û' => 'U', 'u' => 'u', 'U' => 'U', 'u' => 'u', 'U' => 'U', 'u' => 'u', 'U' => 'U', 'u' => 'u', 'U' => 'U', 'u' => 'u', 'U' => 'U', 'u' => 'u', 'U' => 'U', 'ü' => 'ue', 'Ü' => 'UE', '?' => 'w', '?' => 'W', '?' => 'w', '?' => 'W', 'w' => 'w', 'W' => 'W', '?' => 'w', '?' => 'W', 'ý' => 'y', 'Ý' => 'Y', '?' => 'y', '?' => 'Y', 'y' => 'y', 'Y' => 'Y', 'ÿ' => 'y', 'Ÿ' => 'Y', 'z' => 'z', 'Z' => 'Z', 'ž' => 'z', 'Ž' => 'Z', 'z' => 'z', 'Z' => 'Z', 'þ' => 'th', 'Þ' => 'Th', 'µ' => 'u', '?' => 'a', '?' => 'a', '?' => 'b', '?' => 'b', '?' => 'v', '?' => 'v', '?' => 'g', '?' => 'g', '?' => 'd', '?' => 'd', '?' => 'e', '?' => 'E', '?' => 'e', '?' => 'E', '?' => 'zh', '?' => 'zh', '?' => 'z', '?' => 'z', '?' => 'i', '?' => 'i', '?' => 'j', '?' => 'j', '?' => 'k', '?' => 'k', '?' => 'l', '?' => 'l', '?' => 'm', '?' => 'm', '?' => 'n', '?' => 'n', '?' => 'o', '?' => 'o', '?' => 'p', '?' => 'p', '?' => 'r', '?' => 'r', '?' => 's', '?' => 's', '?' => 't', '?' => 't', '?' => 'u', '?' => 'u', '?' => 'f', '?' => 'f', '?' => 'h', '?' => 'h', '?' => 'c', '?' => 'c', '?' => 'ch', '?' => 'ch', '?' => 'sh', '?' => 'sh', '?' => 'sch', '?' => 'sch', '?' => '', '?' => '', '?' => 'y', '?' => 'y', '?' => '', '?' => '', '?' => 'e', '?' => 'e', '?' => 'ju', '?' => 'ju', '?' => 'ja', '?' => 'ja');
        return str_replace(array_keys($transliterationTable), array_values($transliterationTable), $txt);
    }
    
    public static function seoUrl($string)
    {
        return parent::format($string);
    }
    
    public static function deSeoUrl($string)
    {
        $string = preg_replace("/[\s-]+/", " ", $string);
        $string = preg_replace("/[\s_]/", " ", $string);
        return ucfirst($string);
    }
    
    public function getSeoDataByRouteName($rn)
    {
        return $this->data->getSeoDataByRouteName($rn);
    }
}
