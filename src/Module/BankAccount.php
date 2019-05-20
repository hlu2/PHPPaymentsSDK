<?php
namespace QuickBooksOnline\Payments\Module;
use QuickBooksOnline\Payments\Facade\FacadeConverter;


class BankAccount extends Entity{
  public $updated;
  public $name;
  public $routingNumber;
  public $accountNumber;
  public $inputType;
  public $accountType;
  public $phone;
  public $country;
  public $bankCode;
  public $default;
  public $entityVersion;
  public $entityId;
  public $entityType;

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
       } else{
         throw new \RuntimeException("Property name: [" . $name . "] is not a valid field for: [" . get_class($this) . "]. Please check your keys.");
       }
    }
  }
}
