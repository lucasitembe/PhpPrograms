<?php
$db = null;

  try {
    $db = new PDO("mysql:host=" . EPAY_SERVER_HOST . ";port=".EPAY_SERVER_PORT.";dbname=" . EPAY_SERVER_DB, EPAY_SERVER_USER, EPAY_SERVER_PASS);
               
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch(PDOException $ex) {
    //user friendly message
    echo $ex->getMessage();
}

function getRecord($sql){
	global $db;
try {
   $stmt = $db->query($sql);
   return $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $ex) {
   echo $ex->getMessage();
   return false;
}
}

function saveInfo($sql){
	global $db;
try {
  $affected_rows = $db->exec($sql);
  
  return true;
}catch(PDOException $ex) {
  echo $ex->getMessage();
 
  return false;
}
}

function getRowCount($sql) {
    global $db;
    try {
        $stmt = $db->query($sql);
        return $stmt->rowCount();
    } catch (PDOException $ex) {
        echo $ex->getMessage();
        return false;
    }
}

?>
