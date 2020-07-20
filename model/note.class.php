<?php

class Note {
	
	protected $id, $id_user, $title, $content, $date;

    function __construct( $id, $id_user, $title, $content, $date ){

        $this->id = $id;
        $this->id_user = $id_user;
        $this->title = $title;
        $this->content = $content;
        $this->date = $date;
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