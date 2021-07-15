<?php

class dbconn
{
    function __construct($servername, $username, $password, $dbname)
    {
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
    }

    function db_instance(){
       return $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
    }

}