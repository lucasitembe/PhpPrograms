 
<?php 

	require_once('../includes/connection.php');       
  session_start();
	isset($_POST['type']) ? $type = mysqli_real_escape_string($conn,$_POST['type']) : $type != '';
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $Stage_ID = $_POST['Stage_ID'];
    if(!empty($type)){
      $select_cancer_type = mysqli_query($conn, "SELECT Cancer_Name FROM  tbl_cancer_type WHERE Cancer_Name LIKE '%$type%'") or die(mysqli_error($conn));
      if(mysqli_num_rows($select_cancer_type)>0){
          echo "This type of protocal already exist";
      }else{
        $insert_cache_qry = mysqli_query($conn,"INSERT INTO  tbl_cancer_type(Cancer_Name,added_by,Stage_ID, date_time) VALUES('$type','$Employee_ID', '$Stage_ID',NOW())") or die(mysqli_error($conn));
        if($insert_cache_qry){
            echo "<b>Type of Protocal:</b>
                <select  name='type_of_cancer_id' id='type_of_cancer_id' style='width:50%; padding-top:4px; padding-bottom:4px;'>";
                $query_cancer_Type = mysqli_query($conn,"SELECT cancer_type_id,Cancer_Name FROM tbl_cancer_type") or die(mysqli_error($conn));
              echo '<option value="">~~~~~Select Type of Cancer~~~~~</option>';
              while ($row_type_of_cancer= mysqli_fetch_assoc($query_cancer_Type)) {
              echo '<option value="' . $row_type_of_cancer['cancer_type_id'] . '">' . $row_type_of_cancer['Cancer_Name'] . '</option>';
              
              }
          
          echo "</select>
          <span><a class='art-button-green' href='#' type='button' onclick='cancer_protocal_dialog()'>ADD PROTOCAL</a></span>";
        }else{
          echo "Failed to add try again";
        }
      }
    } 
            
?>
 

