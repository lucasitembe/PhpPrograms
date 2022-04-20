<?php
include("../includes/connection.php");
if(isset($_POST['action'])){
    if($_POST['action']=='delete'){
        
        $deleteQuery="  DELETE FROM tbl_parameters WHERE parameter_ID ='".$_POST['id']."'";
        $query=  mysql_query($deleteQuery);
        if($query){
            echo 'Deleted successfully';
        }  else {
            echo 'Delete error'; 
        }
    }
    
}else{
$id=$_POST['id'];
$ParameterName=$_POST['ParameterName'];
$unitofmeasure=$_POST['unitofmeasure'];
$lowervalue=$_POST['lowervalue'];
$highervalue=$_POST['highervalue'];
$Operator=$_POST['Operator'];
$results=$_POST['results'];
$edit="UPDATE tbl_parameters SET Parameter_Name='".$ParameterName."', unit_of_measure='".$unitofmeasure."',lower_value='".$lowervalue."',operator='".$Operator."',higher_value='".$highervalue."',result_type='".$results."' WHERE parameter_ID='".$id."'";
$query=  mysql_query($edit);
if($query){
    echo 'Updated successfully';
}  else {
    echo 'Update failed';
}


};
