<?php
namespace QuickBooksOnline\Payments\Module;
use QuickBooksOnline\Payments\Facade\FacadeConverter;

class Charge extends Entity{

   public $status;
   public $amount;
   public $currency;
   public $token;
   public $card;
   public $context;
   public $description;
   public $authCode;
   public $captureDetail;
   public $refundDetaill;
   public $capture;
   public $avsStreet;
   public $avsZip;
   public $cardSecurityCodeMatch;
   public $appType;
   public $cardOnFile;
   public $type;

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
