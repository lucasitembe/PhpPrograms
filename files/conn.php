<?php 
    declare(strict_types=1);
    class DBConfig{
        public $conn=null;
        private $config =array('host'=>'localhost', 'username'=>'jafar', 'password'=>'officialJ7.', 'database'=>'ehms_lugalo');
        public function connect(){;
            if(is_null($this->conn)){
                $db =$this->config;
                $this->conn=mysqli_connect($db['host'], $db['username'], $db['password'], $db['database']);
                if(!$this->conn){
                    return "DB Not connected";
                }else{
                    return $this->conn;
                }
            }
        }
    }
?>