<?php 
/** PDO Wrapper Class
* @param DSN
* @param DB User ID
* @param DB User Password
*/
class DB_PDO implements  DB_Interface {

   private $dbh;
   private $stmt;

   /**constract
    * @param DSN
    * @param DB User ID
    * @param DB User Password
   */
   public function __construct($dsn, $uid, $pwd) {
       $this->dbh = new PDO($dsn, $uid, $pwd);
       $this->dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); //
       $this->dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
   }

   /**Prepares a statement for execution and returns a statement object
    * @return PODStatement/FALSE/Exception
    */
   public function prepare($sql) {
       $this->stmt = $this->dbh->prepare($sql);
       return $this->stmt;
   }

   /**Executes a prepared statement
    * @return Boolean
    */
   public function execute($bind_array=array()) {
       if (!empty($bind_array)) {
           $this->bind($bind_array);
       }
       return $this->stmt->execute();
   }
   
   /** Executes a prepared statement and return resultset
    * @return Array/False
    */
   public function resultset($bind_array=array()) {
       if (!empty($bind_array)) {
           $this->bind($bind_array);
       }
       $this->stmt->execute();
       return $this->stmt->fetchAll();
   }

   /**Initiates a transaction
    * @return Boolean
    */
   public function begin() {
       return $this->dbh->beginTransaction();
   }
   
   /**Commits a transaction
    * @return Boolean
    */
   public function commit() {
       return $this->dbh->commit();
   }

   /**Rolls back a transaction
    * @return Boolean
    */
   public function rollback() {
       return $this->dbh->rollBack();
   }

   /**PDO close
    */
   public function close() {
       $this->dbh = null;
   }
//-- end DB_Interface method -----------------------------------

   /**Binds a value to a parameter
    * question mark placeholder: array("val1", "val2")
    * named placeholder: array(":key1" => "val1", ":key2" => "val2")
    * @param Array 
    */
   public function bind($bind_array) {
       $i = 1;
       $param = '';
       foreach ($bind_array as $key => $value) {
           switch( true ) {
               case is_int($value):
                   $type = PDO::PARAM_INT;
                   break;
               case is_bool($value):
                   $type = PDO::PARAM_BOOL;
                   break;
               case is_null($value):
                   $type = PDO::PARAM_NULL;
                   break;
               default:
                   $type = PDO::PARAM_STR;
           }
           if (is_int($key)) {
               // question mark placeholder
               $param = $i;
               $i++;
           }else {
               // named placeholder
               $param = $key;
           }
           $this->stmt->bindValue($param, $value, $type);
       }
   }
}
?>
