<?php

class Tag {
  protected $name, $id_user, $cr, $cg, $cb;

  function __construct($name, $id_user, $cr, $cg, $cb) {
    $this->name = $name;
    $this->id_user = $id_user;
    $this->cr = $cr;
    $this->cg = $cg;
    $this->cb = $cb;
  }

  public function __get( $property ){
      if( property_exists ( $this, $property) )
          return $this->$property;
  }

  public function __set( $property, $value ){
      if( property_exists ( $this, $property) )
          $this->$property = $value;
      return $this;
}
}


 ?>
