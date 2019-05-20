<?php

namespace QuickBooksOnline\Payments\Facade;

use QuickBooksOnline\Payments\Module\ModuleConstants;

class FacadeConverter{

    public static function toUpperCaseClassName(string $name){
       return ucfirst($name);
    }

    public static function removeNullFrom($obj){
       $obj = (object) array_filter((array)$obj, function($val) {
          return $val !== null;
       });
       $properties = get_object_vars($obj);
       foreach($properties as $key => $value){
         if(is_object($value)){
            $removed = FacadeConverter::removeNullFrom($value);
            $obj->{$key} = $removed;
         }
       }
       return $obj;
    }

    public static function getJsonFrom($obj){
      $obj = FacadeConverter::removeNullFrom($obj);
      return json_encode($obj);
    }

    public static function isAssociatedArray($data) : bool{
      if(is_array($data)){
          foreach($data as $key => $dataMemeber){
              if(is_integer($key)){
                  return true;
              }
          }
      }
      return false;
    }

    public static function objectFrom(string $body, string $type){
          $arrayRepresent = json_decode($body, true);
          if(json_last_error() != JSON_ERROR_NONE){
              throw new \RuntimeException("Cannot convert $body to Object.");
          }
          $class = ModuleConstants::NAMESPACE_MODULE . $type;
          if(class_exists($class)){
              if(FacadeConverter::isAssociatedArray($arrayRepresent)){
                $body = array();
                foreach($arrayRepresent as $val){
                   $obj = new $class($val);
                   $body[] = $obj;
                }
                return $body;
              }else{
                $obj = new $class($arrayRepresent);
                return $obj;
              }
          }else{
            throw new \RuntimeException("Class not find for " . $type);
          }
    }

    public static function updateResponseBodyToObj(&$response)
    {
        if (!$response->failed() && !empty($response->getBody())) {
            $objBody = FacadeConverter::objectFrom($response->getBody(), $response->getAssociatedRequest()->getRequestType());
            $response->setBody($objBody);
        }
    }


}
