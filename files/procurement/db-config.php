<?php 
    declare(strict_types=1);
     class DBConfig{
        public $conn = null;
        private $config = array('host' => 'localhost','username' => 'root','password' => '2#dbrooti#@Gpitg','database' => 'ehms_database');

        public function connect(){
            if(is_null($this->conn)){
                $db = $this->config;
                $this->conn = mysqli_connect($db['host'],$db['username'],$db['password'],$db['database']);
                if(!$this->conn){
                    return 'Not Connected';
                }else{
                    return $this->conn;
                }
            }
        }
    }
?>

