<?PHP

/* Entity for table seo */

namespace src\Entities;

class Seo
{
    private $id;
    private $route;
    private $title;
    private $subject;
    private $image;
    private $url;
    private $description;
    private $keywords;
    
   	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getRoute(){
		return $this->route;
	}

	public function setRoute($route){
		$this->route = $route;
	}

	public function getTitle(){
		return $this->title;
	}

	public function setTitle($title){
		$this->title = $title;
	}

	public function getSubject(){
		return $this->subject;
	}

	public function setSubject($subject){
		$this->subject = $subject;
	}

	public function getImage(){
		return $this->image;
	}

	public function setImage($image){
		$this->image = $image;
	}

	public function getUrl(){
		return $this->url;
	}

	public function setUrl($url){
		$this->url = $url;
	}

	public function getDescription(){
		return $this->description;
	}

	public function setDescription($description){
		$this->description = $description;
	}

	public function getKeywords(){
		return $this->keywords;
	}

	public function setKeywords($keywords){
		$this->keywords = $keywords;
	}
}
