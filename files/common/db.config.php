<?php 
    declare(strict_types=1);

    class DBConfig{
        public $conn = null;
        private $config = array('host' => 'localhost','username' => 'root','password' => '2#dbrooti#@Gpitg','database' => 'ehms_database');

        public function connect(){
            if(is_null($this->conn)){
                $db = $this->config;
                $this->conn = mysqli_connect($db['host'],$db['username'],$db['password'],$db['database']);
                return (!$this->conn) ? 'Not Connected' : $this->conn;
            }
        }

        public function processSingleFetchQuery_($sql_query,$error_msg){
            $result = array();
            $query = $this->conn->query($sql_query) or die($this->conn->errno." : ".$error_msg);
            while($details = $query->fetch_assoc()){ array_push($result,$details); }
            mysqli_free_result($query);
            return json_encode($result);
        }

        public function processUpdateQuery(string $table_name,array $set_values,array $where_value,$error_msg){
            $set_query_values = "";$where_query_values = "";$set_ = "";$where_ = "";
            foreach($set_values as $value){ $set_ .= " ".$value[0]." ".$value[1]." ".$value[2]." ,"; }
            foreach($where_value as $value){ $where_ .= " ".$value[0]." ".$value[1]." ".$value[2]." AND"; }
            $set_query_values =  rtrim($set_," , ");
            $where_query_values =  rtrim($where_," AND");
            $query = "UPDATE {$table_name} SET {$set_query_values} WHERE {$where_query_values}";
            $sql_query = $this->db_connect->query($query) or die($this->db_connect->errno.": ".$error_msg);
            return ($sql_query) ? 1 : 0;
        }

        public function processInsertQuery(array $Insert_Values,$error_msg){
            $columns_name = "";
            $columns_values = "";
            $table_name = $Insert_Values['table_name'];
            foreach($Insert_Values["column_values"] as $key => $value){
                $columns_name .= $key.",";
                $columns_values .= "'".$value."',";
            }
            $trimmed_column_name = rtrim($columns_name,",");
            $trimmed_column_values = rtrim($columns_values,",");
            $sql = "INSERT INTO $table_name ($trimmed_column_name) VALUES ($trimmed_column_values)";
            $Process_Query = $this->db_connect->query($sql) or die($this->db_connect->errno.": ".$error_msg);
            return ($Process_Query) ? 1 : 0;
        }
    }
?>

