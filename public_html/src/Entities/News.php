<?PHP

/* Entity for table news */

namespace src\Entities;

class News
{
    private $id;
    private $type;
    private $title;
    private $article;
    private $date;
    
    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setType($type)
    {
        $this->type = $type;
    }
    
    public function getType()
    {
        return $this->type;
    }
    
    public function setTitle($title)
    {
        $this->title = $title;
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function setArticle($article)
    {
        $this->article = $article;
    }
    
    public function getArticle()
    {
        return $this->article;
    }
    
    public function setDate($date)
    {
        $this->date = $date;
    }
    
    public function getDate()
    {
        return $this->date;
    }
}
