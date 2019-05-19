<?php
namespace QuickBooksOnline\Payments\Module;
use QuickBooksOnline\Payments\Facade\FacadeConverter;


class DeviceInfo{
  public $id;
  public $type;
  public $longitude;
  public $latitude;
  public $encrypted;
  public $phoneNumber;
  public $macAddress;
  public $ipAddress;

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
