<?php 

// aida

class User
{
    protected $id, $username, $password_hash, $birth_date, $about;

    public function __construct( $id, $username, $password_hash, $birth_date, $about)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password_hash = $password_hash;
        $this->birth_date = $birth_date;
        $this->about = $about;
    }
    //getteri,setteri
    public function __get( $property) 
    {
        if ( property_exists( $this, $property))
            return $this->$property; 
    }
    public function __set( $property, $value)
    {
        if ( property_exists( $this, $property))
            $this->$property = value;
        return $this;
    }
}
?>