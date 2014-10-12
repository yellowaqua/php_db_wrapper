<?php
/**
* interface
*/
interface DB_Interface {
   /**
    * prepare
    */
   public function prepare($sql);

   /**
    * Executes a prepared statement
    */
   public function execute($bind_array);
   
   /**
    * Executes a prepared statement and return resultset
    */
   public function resultset($bind_array=array());
   
   /**
    * Initiates a transaction
   public function begin();

   /**
    * Commits a transaction
    */
   public function commit();
   
   /**
    * Rolls back a transaction
    */
   public function rollback();
   
   /**
    * close database
    */
   public function close();
   
} // END interface DB_Interfase
?>
