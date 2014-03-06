<?php

class databaseForm extends CFormModel {

    public $host;
    public $database_name;
    public $port_name;
    public $database_username;
    public $database_password;

    public function rules() {
        return array(
            array('host','required'),
            array('database_name', 'required'),
            array('port_name', 'required'),
            array('database_username', 'required'),
            array('database_password', 'required'),
        );
    }

}

?>