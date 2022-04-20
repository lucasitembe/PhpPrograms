<?php 
    class DBConfig{
        public $conn = null;
        private array $config = array('host' => 'localhost','username' => 'ehms_user','password' => 'root','database' => 'ehms_database');

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

