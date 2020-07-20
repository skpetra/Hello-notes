<?php

require_once __DIR__ . '/../model/note.class.php';

class _404Controller{

    public function index(){
        require_once __DIR__ . '/__header.php';
        echo '<script language="javascript">';
        echo 'alert("This URL doesn’t exist ")';
        echo '</script>';
        require_once __DIR__ . '/__footer.php';
    }

    
}

?>