<?php
    include("../includes/connection.php");

    function Start_Transaction(){
        mysql_query("START TRANSACTION");
    }

    function Commit_Transaction(){
        mysql_query("COMMIT");
    }

    function Rollback_Transaction(){
        mysql_query("ROLLBACK");
    }

    function Query_DB($sql, $params = array()) {
        $error = false;
        $errorMsg = "";
        $count = 0;
        $data = array();

        $query = mysql_query($sql);
        if ($query) {
            $count = mysql_num_rows($query);
            if ($count > 0) {
                while ($row = mysql_fetch_array($query)) {
                    array_push($data, $row);
                }

                mysql_free_result($query);
            }
        } else {
            $error = true;
            $errorMsg  = 'Invalid query : ' . mysql_error() . "\n";
            $errorMsg .= 'Whole query : ' . $sql;
        }

        return array("error"=>$error, "errorMsg"=>$errorMsg, "count"=> $count, "data"=>$data);
    }

    function Action_DB($action, $table, $where = array(), $and_where = array(), $limit = 10) {
        $limit_statement = "";
        if ($limit > 0) {
            $limit_statement = "LIMIT {$limit}";
        }

        if (count($where) === 3){
            $operators = array('=','>','<','>=','<=', 'like');

            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];

            $and_where_statement = "";
            if (count($and_where) === 3){
                $and_field = $and_where[0];
                $and_operator = $and_where[1];
                $and_value = $and_where[2];

                if (in_array($and_operator, $operators)) {
                    $and_where_statement = "AND {$and_field} {$and_operator} '{$and_value}'";
                }
            }

            if (in_array($operator, $operators)) {
                //echo "{$action} FROM {$table} WHERE {$field} {$operator} '{$value}' {$and_where_statement} {$limit_statement}";
                return Query_DB("{$action} FROM {$table} WHERE {$field} {$operator} '{$value}' {$and_where_statement} {$limit_statement}");
            }
        } else {
            return Query_DB("{$action} FROM {$table} {$limit_statement}");
        }
    }

    function Get_From($table, $where = array(), $and_where = array(), $limit = 10) {
        return Action_DB("SELECT *", $table, $where, $and_where, $limit);
    }

    function Delete_From($table, $where = array()) {
        $sql = "DELETE FROM {$table}";
        if (count($where) === 3){
            $operators = array('=','>','<','>=','<=', 'like');

            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];

            if (in_array($operator, $operators)) {
                $sql = "DELETE FROM {$table} WHERE {$field} {$operator} '{$value}'";
            }
        }

        $error = false;
        $errorMsg = "";

        $query = mysql_query($sql);
        if (!$query) {
            $error = true;
            $errorMsg  = 'Invalid query : ' . mysql_error() . "\n";
            $errorMsg .= 'Whole query : ' . $sql;
        }

        return array("error"=>$error, "errorMsg"=>$errorMsg);
    }

    function Insert_DB($table, $inserts) {
        $error = false;
        $errorMsg = "";
        $id = "";

        $values = array_map('mysql_real_escape_string', array_values($inserts));
        $keys = array_keys($inserts);

        $keys_statement ="";
        for($i = 0; $i < count($keys) - 1; $i++){
            if ($values[$i] != null) {
                $keys_statement .= "{$keys[$i]},";
            }
        }
        $keys_statement .= "{$keys[count($keys) - 1]}";

        $values_statement ="";
        for($i = 0; $i < count($values) - 1; $i++){
            if ($values[$i] != null) {
                $values_statement .= "'{$values[$i]}',";
            }
        }
        $values_statement .= "'{$values[count($values) - 1]}'";

        $sql = "INSERT INTO {$table} ({$keys_statement}) VALUES ({$values_statement})";

        $query = mysql_query($sql);
        if ($query) {
            $id = mysql_insert_id();
        } else {
            $error = true;
            $errorMsg  = 'Invalid query : ' . mysql_error() . "\n";
            $errorMsg .= 'Whole query : ' . $sql;
        }

        return array("error"=>$error, "errorMsg"=>$errorMsg, "id" => $id);
    }

    function Update_DB($table, $inserts, $where = array(), $and_where = array()) {
        $error = false;
        $errorMsg = "";
        $sql = "EMPTY SQL STATEMENT";

        //$values = array_map('mysql_real_escape_string', array_values($inserts));
        $values = array_values($inserts);
        $keys = array_keys($inserts);

        $x = 0;
        $set = '';
        foreach($keys as $field){

            if (is_array($values[$x])){
                $value_statement = "";
                foreach($values[$x] as $value_stmt){
                    $value_statement .= "{$value_stmt} ";
                }
                $set .= "{$field} = {$value_statement}";
            } else {
                $set .= "{$field} = '{$values[$x]}'";
            }

            if($x < count($values) - 1){
                $set .= ", ";
            }
            $x++;
        }

        if (count($where) === 3){
            $operators = array('=','>','<','>=','<=', 'like');

            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];

            $and_where_statement = "";
            if (count($and_where) === 3){
                $and_field = $and_where[0];
                $and_operator = $and_where[1];
                $and_value = $and_where[2];

                if (in_array($and_operator, $operators)) {
                    $and_where_statement = "AND {$and_field} {$and_operator} '{$and_value}'";
                }
            }

            if (in_array($operator, $operators)) {
                $sql = "UPDATE {$table} SET {$set} WHERE {$field} {$operator} {$value} {$and_where_statement}";
            }
        } else {
            $sql = "UPDATE {$table} SET {$set}";
        }


        $query = mysql_query($sql);
        if (!$query) {
            $error = true;
            $errorMsg  = 'Invalid query : ' . mysql_error() . "\n";
            $errorMsg .= 'Whole query : ' . $sql;
        }

        return array("error"=>$error, "errorMsg"=>$errorMsg);
    }

    function Prepare_For_Like_Operator($Like_Value){

        $Like_Value = str_replace("+", " ", $Like_Value);

        $Like_Val_Search = "%";
        $Like_Values = preg_split('/\s+/', $Like_Value);
        foreach($Like_Values as $Like_Val) {
            $Like_Val_Search .= "{$Like_Val}%";
        }
        return $Like_Val_Search;
        /*
        if (count($Like_Values) > 1) {
            $i=1; $Like_Value = $Like_Values[0] . "%";
            for($i; $i< count($Like_Values)-1; $i++){
                $Like_Value .= $Like_Values[$i] . "%";
            }
            $Like_Value .= $Like_Values[$i];
        }
        return $Like_Value;

        /**
         * ANOTHER WAY TO DO THIS
        if ($Search_Value != "") {
        $Product_Names = explode(' ', $Search_Value);
        $Product_Name_Search = "%";
        foreach($Product_Names as $Product_Name) {
        $Product_Name_Search .="{$Product_Name}%";
        }
        }
         */
    }

    function Get_Today_Date() {
        $DateTime = new DateTime(Get_Time_Now());

        return $DateTime->format("Y-m-d");
    }

    function Get_This_Month_Start_Date() {
        $DateTime = new DateTime('first day of this month');

        return $DateTime->format("Y-m-d");
    }

    function Get_This_Month_End_Date() {
        $DateTime = new DateTime('last day of this month');

        return $DateTime->format("Y-m-d");
    }

    function Get_Time_Now() {
        date_default_timezone_set("Africa/Dar_es_Salaam");
        $DateTime = new DateTime();
        return $DateTime->format("Y-m-d H:i:s");
    }

    function Get_Time($Date, $Date_Offset) {
        $DateTime = new DateTime($Date);

        $DateTime->modify($Date_Offset);
        return $DateTime->format("Y-m-d H:i:s");
    }

    function Get_Day_Beginning($Date) {
        $DateTime = new DateTime($Date);

        $DateTime->setTime(0, 0, 0);
        return $DateTime->format("Y-m-d H:i:s");
    }

    function Get_Day_Ending($Date) {
        $DateTime = new DateTime($Date);

        $DateTime->setTime(23, 59, 59);
        return $DateTime->format("Y-m-d H:i:s");
    }

    function in_multiarray($elem, $array, $field) {
        $top = sizeof($array) - 1;
        $bottom = 0;
        while($bottom <= $top)
        {
            if($array[$bottom][$field] == $elem)
                return true;
            else
                if(is_array($array[$bottom][$field]))
                    if(in_multiarray($elem, ($array[$bottom][$field])))
                        return true;

            $bottom++;
        }
        return false;
    }
?>