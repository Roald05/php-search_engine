<?php
require_once "query_builder.php";
$se = new search_engine($_POST["inputCommand"]);

class search_engine
{
    public $inputCommandArray;

    public function __construct($inputCommand)
    {
        echo $this->index($inputCommand);
    }

    public function index($inputCommand = null){
        $resp = "No command detected";
        if(isset($inputCommand))
            $resp = $this->start($inputCommand);
        return $resp;
    }

    private function start($inputCommand = null){
        $resp = "Command is invalid";

        $inputCommandArray = explode(" ",$inputCommand);
        $this->inputCommandArray = $inputCommandArray;

        switch ($inputCommandArray[0]) {
            case "index":
                $action_type = 0;
                if(!is_numeric($inputCommandArray[1]) || $inputCommandArray[1] <= 0)
                    goto end;
                break;
            case "query":
                $action_type = 1;
                break;
            default:
                goto end;
        }

        $query = $this->build_query($inputCommandArray , $action_type);
        if(isset($query))
            $resp = $this->check_execute_command($query, $action_type);

        end:
        return $resp;
    }

    private function build_query($inputCommandArray = null, $action_type = null){
        $queryBuilderObj = new query_builder($action_type);
        return $queryBuilderObj->build($inputCommandArray);
    }

    private function check_execute_command($query = null, $action_type = null){
        $db_repositoryObj = new db_repository();

        switch ($action_type) {
            case 0:
                $result = $db_repositoryObj->executeNonQuery($query);
                break;
            case 1:
                $result = $db_repositoryObj->executeQuery($query);
                break;
        }

        return $this->parse_result($result,$action_type);
    }

    private function parse_result($result = null, $action_type = null){
        $resp = "Internal error";
        if(is_array($result))
             if(count($result) > 0)
                 $resp = "query results ".implode(" ", $result);
             else
                 $resp = "query error no results ";
        elseif(is_bool($result) && $result > 0)
            $resp = "index ok ".$this->inputCommandArray[1];
        else
            switch ($action_type) {
                case 0:
                    $resp = "index error command not executed successfully";
                    break;
                case 1:
                    $resp = "query error query not executed successfully";
                    break;
            }
        return $resp;
    }
}