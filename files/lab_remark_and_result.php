<?php
// include('./includes/connection.php');
#done by malopa

#get lab remark
function getRemarkDescriptionValue($itemListCacheId){
        global $conn;
    $dataFromAtachment = array();
    $sql = "SELECT Description,Attachment_Url 
            FROM tbl_attachment 
            WHERE item_list_cache_id='$itemListCacheId'
            ";
    $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
    while($row = mysqli_fetch_assoc($result)){
            extract($row);
            array_push($dataFromAtachment,$Description,$Attachment_Url);
    }
    return $dataFromAtachment;
}

#get result from table tbl_test_parameter_results
function getResult($itemListCacheId){
        global $conn;
    $refTestResultId = getTestResultId($itemListCacheId);
    $sql= "SELECT result 
            FROM tbl_tests_parameters_results
            WHERE ref_test_result_ID = '$refTestResultId'";
    $QueryResult = mysqli_query($conn,$sql) or die(mysqli_error($conn));
    $row = mysqli_fetch_assoc($QueryResult);
    extract($row);
    return $result;
}


function getTestResultId($itemListCacheId){
        global $conn;
    $sql = "SELECT test_result_ID 
            FROM tbl_test_results
            WHERE payment_item_ID = '$itemListCacheId'
            ";
    $queryResult = mysqli_query($conn,$sql) or die(mysqli_error($conn));
    $row = mysqli_fetch_assoc($queryResult);
    extract($row);
    return  $test_result_ID;  
}


?>