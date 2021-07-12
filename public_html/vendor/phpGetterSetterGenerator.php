<?php
/**
 * Generates getter and setter methods for a PHP class
 * @author Michael Angstadt
 */
class GetterSetterGen{
  /**
   * The source code of the PHP class.
   * @var string
   */
  private $class;
   
  /**
   * The newline string to use.
   * @var string
   */
  private $newline;
   
  /**
   * The indent character to use.
   * @var string
   */
  private $indentChar;
   
  /**
   * The number of indent characters to use for a single indentation.
   * @var integer
   */
  private $indentCount;
   
  /**
   * @param string $class the source code of the PHP class
   * @param string $newline (optional) the newline string to use
   * @param string $indentChar (optional) the indent character to use
   * @param integer $indentCount (optional) the number of indent characters to use for a single indentation
   */
  public function __construct($class, $newline = "\n", $indentChar = "\t", $indentCount = 1){
    $this->class = $class;
    $this->newline = $newline;
    $this->indentChar = $indentChar;
    $this->indentCount = $indentCount;
  }
   
  /**
   * Generates the source code for the getter/setter methods.
   * @return string the getter/setter source code
   */
  public function generate(){
    $code = "";
     
    $count = preg_match_all("/(private|protected)\\s+\\$(.*?)(=.*?)?;/", $this->class, $matches);
    for ($i = 0; $i < $count; $i++){
      $name = trim($matches[2][$i]);
      $ucName = ucfirst($name);
      $getter = "{$this->indent(1)}public function get$ucName(){" . "$this->newline{$this->indent(2)}return \$this->$name;$this->newline{$this->indent(1)}}";
      $setter = "{$this->indent(1)}public function set$ucName(\$$name){" . "$this->newline{$this->indent(2)}\$this->$name = \$$name;$this->newline{$this->indent(1)}}";
      $code .= "$getter{$this->newline}{$this->newline}$setter";
       
      if ($i < $count-1){
        $code .= "{$this->newline}{$this->newline}";
      }
    }
     
    return $code;
  }
   
  /**
   * Indents a line of code.
   * @param integer $level the indentation level
   */
  private function indent($level){
    return str_repeat(str_repeat($this->indentChar, $this->indentCount), $level);
  }
}