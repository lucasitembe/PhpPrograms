<?php

// class DbConnection{
//   private $host = 'localhost';
//   private $user = 'root';
//   private $password = 'MAttaka@123';
//   private $db = 'ehms_kcmc';
//   private $conn = null;
//
//   public static function getDB()
//   {
//     try {
//         $this->conn = new mysqli($this->host,$this->user,$this->password,$this->db);
//         if($this->conn->connect_error)
//         {
//           echo "Not connected";
//         }
//     } catch(Exception $e) {
//         echo $e->getMessage();
//     }
//      return $this->conn;
//   }
//
//
// }
//
// class BLogic extends DbConnection{
// //include("../../includes/connection.php");
//   private $data = $conn;
//
//   public $data = null;
//   $dbl = new DbConnection;
//   $this->data = $dbl->getDB();
//   // public function test()
//   // {
//   //
//   //   if($this->data)
//   //   {
//   //     return "connected";
//   //   }else{
//   //     return "not connected";
//   //   }
//   //
//   //   //return $this->data;
//   // }
// }
//
// $bl = new BLogic();
//
// echo $bl->data;
 ?>
