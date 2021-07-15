<?php
require_once "db_repository.php";

class query_builder
{
    public $action_type;

    function __construct($action_type)
    {
        $this->action_type = $action_type;
    }

   function build($commandsArray){
       switch ($this->action_type) {
           case 0:
               $query = $this->build_insert_update_query($commandsArray);
               break;
           case 1:
               $query = $this->build_select_query($commandsArray);
               break;
           default:
               $query = null;
       }
       
       return $query;
   }


   private function build_insert_update_query($commandsArray = array()){
       if(count($commandsArray) > 0 ){
           $dbrepoObj = new db_repository();

           $command_id = $commandsArray[1];
           array_splice($commandsArray,0,2);
           $command_tokens = implode(" ", $commandsArray);

           $docs = $dbrepoObj->executeQuery("SELECT * FROM document WHERE ID = $command_id");

           if(count($docs) > 0){
               $query = "UPDATE document SET TOKENS = '$command_tokens' WHERE ID = $command_id";
           }else{
               $query = "INSERT INTO document(ID, TOKENS) value ($command_id, '$command_tokens')";
           }
       }
       return $query;
   }

    private function build_select_query($commandsArray = array()){
        array_splice($commandsArray,0,1);
        $command_tokens_expression = preg_replace('/\s+/', '', implode(" ", $commandsArray));
        $command_tokens_array = preg_split('/[^\w\ _]+/', $command_tokens_expression);
        $already_mapped_values = array();
        foreach ($command_tokens_array as $value) {
            if(!empty($value) && !in_array($value,$already_mapped_values)){
                $command_tokens_expression = str_replace($value, " TOKENS like '%".$value."%' ", $command_tokens_expression);
                $already_mapped_values[] = $value;
            }
        }
        $command_tokens_expression = str_replace('|', " OR ", str_replace('&', " AND ", $command_tokens_expression));
        $resp = "SELECT ID FROM document WHERE ".$command_tokens_expression;
        return $resp;

    }
}