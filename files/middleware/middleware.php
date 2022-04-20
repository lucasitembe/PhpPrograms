<?php
require "connection.php"; 
    function query_insert($json_data){
        global $conn;
        $data_array=array();
        $table_name=array();
        $table_data=array();
        $response = "";
        try{
            $data_array=json_decode($json_data,true);
            $table_name=$data_array['table'];
            $table_data=$data_array['data'];
            foreach($table_data as $rows){
                $query ="INSERT INTO ".$table_name;
                $column = array();
                $value = array();
                foreach($rows as $column_Name => $column_Data){
                    array_push($column,$column_Name);
                    array_push($value,mysqli_real_escape_string($conn,$column_Data));
                }
                $query .= " (".join(",",$column).") values('".join("','",$value)."')";
                $response = mysqli_query($conn,$query);
                $response  = mysqli_insert_id($conn);
                if(!$response){
                    error_log(mysqli_error($conn)."\n",3,"/var/www/html/ehms/files/middleware/logs-".date('Y-m-d').".log");
                    die(json_encode(array("Error"=>503)));
                }
            }
      }catch(Exception $e){
        return json_encode(array("Error"=>$e));
      }
      return $response;
    }

    function query_update($json_data){
        global $conn;
        $data_array=array();
        $table_name=array();
        $table_data=array();
        $condition=array();
        $response = "";
        try{
            $data_array=json_decode($json_data,true);
            $table_name=$data_array['table'];
            $table_data=$data_array['data'];
            $condition=$data_array['condition'];
            foreach($table_data as $rows){
                $query ="UPDATE ".$table_name." SET ";
                $set_values = array();
                $condition_value = array();
                foreach($rows as $column_Name => $column_Data){
                    array_push($set_values,$column_Name."='".mysqli_real_escape_string($conn,$column_Data)."'");
                }
                foreach($condition as $column_Name => $column_Data){
                    array_push($condition_value,mysqli_real_escape_string($conn,$column_Name)."='".mysqli_real_escape_string($conn,$column_Data)."'");
                }
                $query .= join(",",$set_values)." WHERE ".join(" AND ",$condition_value);
                $response = mysqli_query($conn,$query);
                if(!$response){
                    error_log(mysqli_error($conn)."\n",3,"/var/www/html/middle_wr/logs-".date('Y-m-d').".log");
                    die(json_encode(array("Error"=>503)));
                }
            }
      }catch(Exception $e){
        return json_encode(array("Error"=>$e));
      }
      return $response;
    }

    function query_select($json_data){
       
        global $conn;
        $data_array=array();
        $table_name=array();
        $table_data=array();
        $condition=array();
        $response = "";
        try{
            $data_array=json_decode($json_data,true);
            $table_name=$data_array['table'];
            $table_column=$data_array['column'];
            $condition=$data_array['condition'];
            $condition_value = array();
            if(sizeof($condition)==0){
                $condition_value = array("1");
            }
            if(sizeof($table_column)==0){
                $table_column = array("*");
            }
            foreach($condition as $column_Name => $column_Data){
                array_push($condition_value,mysqli_real_escape_string($conn,$column_Name)."='".mysqli_real_escape_string($conn,$column_Data)."'");
            }
            
            $query ="SELECT ".join(",",$table_column)." FROM ".$table_name." WHERE ".join(" AND ",$condition_value);
            $query_response = mysqli_query($conn,$query) or die(mysqli_error($conn));
            if(mysqli_num_rows($query_response) > 0){$response = json_encode(mysqli_fetch_assoc($query_response));}
            else {
                error_log(mysqli_error($conn)."\n",3,"/var/www/html/middle_wr/logs-".date('Y-m-d').".log");
                die(json_encode(array("Error"=>503)));
            }
      }catch(Exception $e){
        return json_encode(array("Error"=>$e));
      }
      return $response;
    }
?>