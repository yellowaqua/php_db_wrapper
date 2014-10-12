<?php 
/**ODBC Wrapper Class
* @param DSN
* @param DB User ID
* @param DB User Password
*/
class DB_ODBC implements  DB_Interface {

   private $dbh;
   private $stmt;
   
   /**Exception flg
    * [true]  throw new Exception
    * [false] Boolean
    */
   private $exception;
   /**PHP charset
    */
   private $php_charset = 'UTF-8';
   /**Database charset
    */
   private $db_charset  = 'SJIS-win';

   /**construct
    * @param DSN
    * @param DB User ID
    * @param DB Password
   */
   public function __construct($dsn, $uid, $pwd) {
       $this->dbh = odbc_connect($dsn, $uid, $pwd);
       if ($this->dbh === false) {
           throw new Exception("odbc_connect Failure...");
       }
       $this->exception = TRUE;
   }

   /**Prepares a statement for execution 
    * @param String SQL
    * @return odbc result ID／FALSE／exception
    */
   public function prepare($sql) {
       $sql = mb_convert_encoding($sql, $this->db_charset, $this->php_charset);
       $this->stmt = odbc_prepare($this->dbh, $sql);
       if($this->exception && !$this->stmt) {
           throw new Exception($this->errormsg().' odbc_prepare Failure...');
       }
       return $this->stmt;
   }

   /**Execute a prepared statement
    * parameter: Only use question mark placeholder. Don't use named placeholder.
    * @param Array
    * @return Boolean／exception
    */
   public function execute($bind_array=array()) {
       if (!empty($bind_array)) {
           mb_convert_variables($this->db_charset, $this->php_charset, $bind_array);
       }
       $bool = odbc_execute($this->stmt, $bind_array);
       if($this->exception && !$bool) {
           throw new Exception($this->errormsg()." odbc_execute Failure...");
       }
       return $bool;
   }

   /**Execute a prepared statement
    * and mb_convert_variables
    * parameter: Only use question mark placeholder. Don't use named placeholder.
    * @param Array
    * @return Array／exception
    */
   public function resultset($bind_array=array()) {
       $bool = odbc_execute($this->stmt, $bind_array);
       if($this->exception && !$bool) {
           throw new Exception($this->errormsg()." odbc_execute Failure...");
       }
       $result = '';
       while( $row = odbc_fetch_array($this->stmt) ) {
           /**key and value encoding
           $r = '';
           foreach ($row as $key => $val) {
               $key = mb_convert_encoding($key, $this->php_charset, $this->db_charset);
               $val = mb_convert_encoding($val, $this->php_charset, $this->db_charset);
               $r[$key] = $val;
           }
           $result[] = $r;
           */

           //--only value encoding(key do not encoding)
           mb_convert_variables($this->php_charset, $this->db_charset, $row);
           $result[] = $row;
       }
       if (empty($result)) {
           $result = array();
       }
       return $result;
   }

   /**Toggle autocommit behaviour
    * @return Boolean／exception
    */
   public function begin() {
       // TRUE:auto-commit /  FALSE:Disabling auto-commit(starting a transaction.)
       $bool = odbc_autocommit($this->dbh, FALSE);
       if($this->exception && !$bool) {
           throw new Exception($this->errormsg()." odbc_autocommit Failure...");
       }
       return $bool;
   }

   /**Commit an ODBC transaction
    * @return Boolean／exception
    */
   public function commit() {
       $bool = odbc_commit($this->dbh);
       if($this->exception && !$bool) {
           throw new Exception($this->errormsg()." odbc_commit Failure...");
       }
       // start auto-commit
       if($this->exception && !odbc_autocommit($this->dbh, TRUE)){
           throw new Exception("odbc_autocommit Failure...");
       } 
       return $bool;
   }

   /**Rollback a transaction
    * @return Boolean／exception
    */
   public function rollback() {
       $bool = odbc_rollback($this->dbh);
       if($this->exception && !$bool) {
           throw new Exception($this->errormsg()." odbc_rollback Failure...");
       }
        // start auto-commit
       if($this->exception && !odbc_autocommit($this->dbh, TRUE)){
           throw new Exception("odbc_autocommit Failure...");
       } 
       return $bool;
   }

   /**Close an ODBC connection
    */
   public function close() {
       odbc_close($this->dbh);
   }
//-- end DB_Interface method -----------------------------------

   /**Get the last error message
    * Returns a string containing the last ODBC error message, or an empty string if there has been no errors.
    * @return String
    */
   public function errormsg() {
       $errormsg = odbc_errormsg($this->dbh);
       return mb_convert_encoding($errormsg, $this->php_charset, $this->db_charset);
   }
}
?>
