<?PHP

/* Entity for general statistic with 1 key and 1 value. (General stat) */

namespace src\Entities\Statistic;

class GeneralStatistic
{
    private $key;
    private $value;
    
   	public function getKey(){
		return $this->key;
	}

	public function setKey($key){
		$this->key = $key;
	}

	public function getValue(){
		return $this->value;
	}

	public function setValue($value){
		$this->value = $value;
	}
}
