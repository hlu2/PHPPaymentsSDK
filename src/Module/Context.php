<?php
namespace QuickBooksOnline\Payments\Module;
use QuickBooksOnline\Payments\Facade\FacadeConverter;


class Context {
  public $deviceInfo;
  public $mobile;
  public $recurring;
  public $isEcommerce;
  public $tax;

  public function __construct(array $array = array()){
    foreach($array as $name => $value){
       if(property_exists(get_class($this), $name)){
          if(isset($value)){
              if(is_array($value)){
                $className = ModuleConstants::NAMESPACE_MODULE . FacadeConverter::toUpperCaseClassName($name);
                $obj = new $className($value);
                $this->{$name} = $obj;
              }else{
                $this->{$name} = $value;
              }
          }
       }
    }
  }
}
