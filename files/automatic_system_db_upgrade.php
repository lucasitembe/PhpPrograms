<?php
/******gkcchief 28.06.2019******/
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
    <a href='systemconfiguration.php' class='art-button-green'>
        BACK
    </a>
<?php 
    //select all column from ehms database table
    $sql_select_all_column_from_ehms_db_table_result=mysqli_query($conn,"SELECT `COLUMN_NAME` 
FROM `INFORMATION_SCHEMA`.`COLUMNS` 
WHERE `TABLE_SCHEMA`='bugando_new' 
    AND `TABLE_NAME`='tbl_privileges'") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_all_column_from_ehms_db_table_result)>0){
        while($column_rows=mysqli_fetch_assoc($sql_select_all_column_from_ehms_db_table_result)){
            $COLUMN_NAME=$column_rows['COLUMN_NAME'];
            echo "<br/>$COLUMN_NAME";
        }
    }
?>
