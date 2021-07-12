<?PHP

/* Entity for table user_captcha */

namespace src\Entities;

class UserCaptcha
{
    private $id;
    private $security;
    private $count;
    private $success;
    private $fail;
    private $unsolved;
    
    private $securityTodo;
    
	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getSecurity(){
		return $this->security;
	}

	public function setSecurity($security){
		$this->security = $security;
	}

	public function getCount(){
		return $this->count;
	}

	public function setCount($count){
		$this->count = $count;
	}

	public function getSuccess(){
		return $this->success;
	}

	public function setSuccess($success){
		$this->success = $success;
	}

	public function getFail(){
		return $this->fail;
	}

	public function setFail($fail){
		$this->fail = $fail;
	}

	public function getUnsolved(){
		return $this->unsolved;
	}

	public function setUnsolved($unsolved){
		$this->unsolved = $unsolved;
	}
    
	public function getSecurityTodo(){
		return $this->securityTodo;
	}

	public function setSecurityTodo($securityTodo){
		$this->securityTodo = $securityTodo;
	}
}
