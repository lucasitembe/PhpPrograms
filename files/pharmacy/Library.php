<?php 
    declare(strict_types=1);

    class Library{

        public $connect;

        function __construct($connect){
            $this->connect = $connect;
        }

        # Read data from a sinle database
        function readDataFromTable(array $tableName,array $selectCondition,array $whereCondition,int $queryLimit,bool $orderBy,string $orderByColumn){
            $results = array();
            $columns = '';
            $tableNameColumns = '';
            $whereSelectCondition = '';

            foreach($selectCondition as $element){ $columns.= $element.","; }
            foreach($tableName as $element){ $tableNameColumns.= $element.","; }
            foreach($whereCondition as $element){ $whereSelectCondition.= $element." AND "; }

            $resultColumns = rtrim($columns,',');
            $tableColumns = rtrim($tableNameColumns,',');
            $querySelectWhereCondition = rtrim($whereSelectCondition," AND ");

            $orderByCondition = ($orderBy === true) ? " ORDER BY $orderByColumn ASC " : " ORDER BY $orderByColumn DESC ";
            $whereConditionQuery = (sizeof($whereCondition) > 0) ? " WHERE $querySelectWhereCondition " : "";

            $readData = mysqli_query($this->connect,"SELECT $resultColumns FROM $tableColumns $whereConditionQuery $orderByCondition LIMIT $queryLimit") or die("Error Found No ::: ".mysqli_errno($this->connect)." ::: ".mysqli_error($this->connect));

            if(mysqli_num_rows($readData) > 0){
                while($data = mysqli_fetch_array($readData)){ array_push($results,$data); }
            }else{
                array_push($results,'No Data Found'); 
            }

            mysqli_free_result($readData);
            return json_encode($results);
        }

        function getCurrentDateTime(){
            return mysqli_fetch_assoc(mysqli_query($this->connect,"SELECT NOW() AS today"))['today'];
        }
    }
?>