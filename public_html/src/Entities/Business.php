<?PHP

/* Entity for table business */

namespace src\Entities;

class Business
{
    private $id;
    private $name;
    private $openingPrice;
    private $lastPrice;
    private $closePrice;
    private $highPrice;
    private $lowPrice;
    
    private $difference;
    private $diffPercent;
    private $stocks;
    private $stocksCount;
    private $stocksPrice;
    private $stocksProfit;
    private $stocksPercent;
    
    
	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getName(){
		return $this->name;
	}

	public function setName($name){
		$this->name = $name;
	}

	public function getOpeningPrice(){
		return $this->openingPrice;
	}

	public function setOpeningPrice($openingPrice){
		$this->openingPrice = $openingPrice;
	}

	public function getLastPrice(){
		return $this->lastPrice;
	}

	public function setLastPrice($lastPrice){
		$this->lastPrice = $lastPrice;
	}

	public function getClosePrice(){
		return $this->closePrice;
	}

	public function setClosePrice($closePrice){
		$this->closePrice = $closePrice;
	}

	public function getHighPrice(){
		return $this->highPrice;
	}

	public function setHighPrice($highPrice){
		$this->highPrice = $highPrice;
	}

	public function getLowPrice(){
		return $this->lowPrice;
	}

	public function setLowPrice($lowPrice){
		$this->lowPrice = $lowPrice;
	}
    
    public function getDifference(){
		return $this->difference;
	}

	public function setDifference($difference){
		$this->difference = $difference;
	}

	public function getDiffPercent(){
		return $this->diffPercent;
	}

	public function setDiffPercent($diffPercent){
		$this->diffPercent = $diffPercent;
	}
    
    public function getStocks(){
		return $this->stocks;
	}

	public function setStocks($stocks){
		$this->stocks = $stocks;
	}
    
    public function getStocksCount(){
		return $this->stocksCount;
	}

	public function setStocksCount($stocksCount){
		$this->stocksCount = $stocksCount;
	}
    
    public function getStocksPrice(){
		return $this->stocksPrice;
	}

	public function setStocksPrice($stocksPrice){
		$this->stocksPrice = $stocksPrice;
	}
    
    public function getStocksProfit(){
		return $this->stocksProfit;
	}

	public function setStocksProfit($stocksProfit){
		$this->stocksProfit = $stocksProfit;
	}
    
    public function getStocksPercent(){
		return $this->stocksPercent;
	}

	public function setStocksPercent($stocksPercent){
		$this->stocksPercent = $stocksPercent;
	}
}
