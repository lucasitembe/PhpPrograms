<?php
include("./includes/connection.php");
$Employee_ID  = $_POST['Employee_ID'];
$finance_department_id = $_POST['department_id'];
$action = $_POST['action'];

//results
$results = array();
switch ($action) {
  case 'check':
    {
      //check the assignment existance

      $select_existance = mysqli_query($conn,"SELECT * FROM tbl_assign_finance_department WHERE Employee_ID = '$Employee_ID' AND finance_department_id = '$finance_department_id' ");
      $results['ona']=mysqli_num_rows($select_existance);
      if(mysqli_num_rows($select_existance) > 0){
        $results['message'] = 'exists';
        }else{
          $results['ujumbe'] = mysqli_query($conn,"INSERT INTO tbl_assign_finance_department(Employee_ID,finance_department_id) VALUES('$Employee_ID' ,'$finance_department_id')") or die(mysqli_error($conn));
          if(mysqli_affected_rows() > 0){
            $results['message'] = 'ok';
          }else{
            $results['message'] = 'error';
          }
      }
    }
    break;
  case 'uncheck':
    {
      $delete = mysqli_query($conn,"DELETE FROM tbl_assign_finance_department WHERE Employee_ID = '$Employee_ID' AND finance_department_id = '$finance_department_id' ");
      if(mysqli_affected_rows() > 0){
        $results['message'] = 'del_ok';
      }else{
        $results['message'] = 'del_error';
      }
    }
    break;
}

echo json_encode($results);
 ?>
