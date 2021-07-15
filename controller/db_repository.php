<?php
require_once "../config/dbconn.php";

class db_repository
{
    function __construct()
    {
        $this->dbconn = new dbconn("localhost",  "root", "", "intelycaretaskdb");
    }

    function executeQuery($query = null){
        $rows = array();
        $result = mysqli_query($this->dbconn->db_instance(), $query);
        if ($result)
            while ($row = $result->fetch_array(MYSQLI_NUM))
            {
                $rows[] = $row[0];
            }
        return $rows;
    }

    function executeNonQuery($query = null){
        return mysqli_query($this->dbconn->db_instance(), $query);
    }
}