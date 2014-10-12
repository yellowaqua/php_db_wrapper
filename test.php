<?php

$dbhost = 'your db host';
$dbname = 'your db name';
$uid = 'your id';
$pwd = 'your password';
$db = '';
try {
// TODO DB_PDO or DB_ODBC new instance
	// if php5.4 (use Microsoft Drivers 3.0 for SQL Server for PHP)
	//$dsn = "sqlsrv:Server=$dbhost;Database=$dbname;";
    //$db = new DB_PDO($dsn, $uid, $pwd);
	
	// if php5.5 or later(use SQLServer Native Client 11.0)
    //$dsn = "Driver={SQL Server Native Client 11.0};Server=$dbhost;Database=$dbname;";
    //$db = new DB_ODBC($dsn, $uid, $pwd);
}
catch(Exception $e) {
   echo $e;
   exit;
}
try {
   echo "<br />insert - - - - - - - - - - - - - - - - - - - - - <br />";
   $sql = 'insert into user_mst ("login_id", "passwd", "user_name") values (?, ?, ?)';
   $db->begin();
   
   for ($i=0; $i < 10; $i++) {
       /*
       if ($i == 5) {
           throw new Exception("rollback test !!!!!!");
       }
       */
       $id = "id".$i;
       $pw = "pw".$i;
       $nm = "hogehoge".phpversion();
       $bind = array($id, $pw, $nm);
       $db->prepare($sql);
       $db->execute($bind);
   }
   $db->commit();

   echo "<br />SELECT ALL - - - - - - - - - - - - - - - - - - - - - <br />";
   $db->prepare('select * from user_mst order by login_id');
   $res = $db->resultset();
   foreach ((array)$res as $val) {
      if(empty($val))continue;
       echo $val['login_id']."  :  ".$val['passwd']."  :  ".$val['user_name']."<br />";
   }
   
   echo "<br />SELECT - - - - - - - - - - - - - - - - - - - - - <br />";
   $params = array("id2", "pw2");
   $db->prepare("select * from user_mst where login_id = ? and passwd = ?");
   $res = $db->resultset($params);
   foreach ((array)$res as $val) {
       if(empty($val)){ continue;}
       echo $val['login_id']."  :  ".$val['passwd']."  :  ".$val['user_name']."<br />";
   }
}
catch(Exception $e) {
   echo $e;
   $db->rollback();
}
$db->close();

?>
