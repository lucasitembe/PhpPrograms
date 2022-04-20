<?php
include("./includes/functions.php");

include("./includes/header.php");

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if(isset($_GET['dhis2_dataset_name'])){
  $dhis2_dataset_name=$_GET['dhis2_dataset_name'];
}else{
    $dhis2_dataset_name="";
}
if(isset($_GET['dataset_id'])){
   $dataset_id=$_GET['dataset_id'];
}else{
    $dataset_id="";
}
if(isset($_GET['dhis2_auto_dataset_id'])){
    $dhis2_auto_dataset_id=$_GET['dhis2_auto_dataset_id'];
}else{
    $dhis2_auto_dataset_id="";
}

///uploading function
function upload($image,$path)
{
$ext=substr(strrchr($_FILES[$image]['name'],'.'),1);
$picName=rand().".$ext";
if(move_uploaded_file($_FILES[$image]['tmp_name'],$path.$picName))
{
return $picName;
} else
{ echo '.'; }
}

if(isset($_POST['uploading_btn'])){
   $count_uploded=0;
   $count_fail_uploded=0;
   $count_exist=0;
   $count_total_item=0;
   include("PHPExcel/IOFactory.php");
	
	$filename=upload("data_set_excel_file","excelfiles/");
	 $ext=substr(strrchr($filename,'.'),1);
         
         $sql_insert_items_result=false;
         
	if($ext=="xls"){
	
        if(!empty($filename)){
            ///delete previous price per this category
         $objPHPExcel=PHPExcel_IOFactory::load("excelfiles/$filename");
	foreach($objPHPExcel->getWorksheetIterator()as $worksheet){
		$highestRow=$worksheet->getHighestRow();
		for($row=2;$row<=$highestRow;$row++){
			$string_of_id=mysqli_real_escape_string($conn,$worksheet->getCellByColumnAndRow(0,$row)->getValue());
			$display_name=mysqli_real_escape_string($conn,$worksheet->getCellByColumnAndRow(1,$row)->getValue());
			$array_of_id=explode(".",$string_of_id);
                        $dataElement=$array_of_id[0];
                        $categoryOptionCombo=$array_of_id[1];
                        
                        //check if exist displayname
                        $sql_check_if_exist_result=mysqli_query($conn,"SELECT dhis2_dataelement_id FROM tbl_dhis2_dataelements WHERE dhis2_dataelement_id='$dataElement' AND categoryOptionCombo='$categoryOptionCombo' AND dataset_id='$dataset_id'") or die(mysqli_error($conn));
                        if(mysqli_num_rows( $sql_check_if_exist_result)<=0){
                            ///SAVE data element to t=database
                            $sql_insert_data_element_to_database_reuslt=mysqli_query($conn,"INSERT INTO tbl_dhis2_dataelements(dhis2_dataelement_id,categoryOptionCombo,displayname,dataset_id) VALUES('$dataElement','$categoryOptionCombo','$display_name','$dataset_id')") or die(mysqli_error($conn));
                            if($sql_insert_data_element_to_database_reuslt){
                                $count_uploded++;
                            }else{
                                $count_fail_uploded++; 
                            }
                        }else{
                            $count_exist++;
                        }
                        $count_total_item++;
            }	
        }
      }
    }else{
         echo   $message2="<script>alert('Invalid File Format...please select Excel File with extension .xls And Try Again')</script>";	
    }
    
  
        echo "<script>alert('$count_uploded/$count_total_item DATA ELEMENTS UPLODED SUCCESSFULL! ....$count_exist DATA ELEMENTS EXIST....$count_fail_uploded FAIL TO UPLOAD')</script>";
   
}
?>
<a href='dhis2_hmis_dataelements.php?dhis2_auto_dataset_id=<?= $dhis2_auto_dataset_id ?>&&dhis2_dataset_name=<?= $dhis2_dataset_name ?>&&dataset_id=<?= $dataset_id ?>' class='art-button-green'>
        BACK
</a>
<br/><br/>
<style>
    table,tr,td{
        border:none!important;
    }
</style>
<fieldset>
    <legend align="right" style="text-align:right;background-color:#006400;color:white;padding:5px;"><b><?= $dhis2_dataset_name ?></b></legend>
    <div  class="col-md-6"style='margin-top:15px;height: 370px;overflow-y: scroll;overflow-x: hidden;background: #FFFFFF'>
        <form action="" method="POST">
            <table class="table">
                <tr>
                    <caption><b><?= $dhis2_dataset_name ?></b></caption>
                </tr>
                <tr>
                    <td>Data Element</td>
                    <td><input type="text" placeholder="Enter Data Element" name="dataElement"required="" class="form-control"/></td>
                </tr>
                <tr>
                    <td>Category Option Combo</td>
                    <td><input type="text" placeholder="Enter Category Option Combo" name='categoryOptionCombo' required="" class="form-control"/></td>
                </tr>
                <tr>
                    <td>Display Name</td>
                    <td><input type="text" placeholder="Enter Display Name" name="display_name"required="" class="form-control"/></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" value="SAVE" class="art-button-green pull-right"/>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <div  class="col-md-6"style='margin-top:15px;height: 370px;overflow-y: scroll;overflow-x: hidden;background: #FFFFFF'>
        <form action="" method="POST" enctype="multipart/form-data">
            <table class="table">
                <tr>
                    <caption><b>Hospital Taarifa ya Fedha za Kuendeshea Huduma ~~UPLOAD FROM EXCEL</b></caption>
                </tr>
                <tr>
                    <td>Select Excel File</td>
                    <td><input type="file" placeholder="Enter Data Element" name='data_set_excel_file'required="" class="form-control-file"/></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name='uploading_btn' value="UPLOAD EXCEL" class="art-button-green pull-right"/>
                    </td>
                </tr>
            </table>
            <?php 
            ?>
        </form>
    </div>
</fieldset>
<?php
include("./includes/footer.php");
?>
