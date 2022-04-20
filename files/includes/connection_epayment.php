<?php
$db_conn = null;

  try {
    $db_conn = new PDO("mysql:host=" . EPAY_SERVER_HOST . ";port=".EPAY_SERVER_PORT.";dbname=" . EPAY_SERVER_DB, EPAY_SERVER_USER, EPAY_SERVER_PASS);
               
	$db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db_conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch(PDOException $ex) {
    //user friendly message
    echo $ex->getMessage();
}

function getRecord($sql){
	global $db_conn;
try {
   $stmt = $db_conn->query($sql);
   return $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $ex) {
   echo $ex->getMessage();
   return false;
}
}

function saveInfo($sql){
	global $db_conn;
try {
  $affected_rows = $db_conn->exec($sql);
  
  return true;
}catch(PDOException $ex) {
  echo $ex->getMessage();
 
  return false;
}
}

function getRowCount($sql) {
    global $db_conn;
    try {
        $stmt = $db_conn->query($sql);
        return $stmt->rowCount();
    } catch (PDOException $ex) {
        echo $ex->getMessage();
        return false;
    }
}

?>
