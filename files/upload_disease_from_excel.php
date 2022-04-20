<?php
include("./includes/header.php");
include("./includes/connection.php");
if (isset($_SESSION['userinfo'])) {
 
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}


  $Current_Username = $_SESSION['userinfo']['Given_Username'];
  $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
//     
//    $sql_check_prevalage="SELECT edit_items FROM tbl_privileges WHERE can_load_item_from_excel='yes' AND "
//            . "Given_Username='$Current_Username'";
//    
//    $sql_check_prevalage_result=mysqli_query($conn,$sql_check_prevalage);
//    if(!mysqli_num_rows($sql_check_prevalage_result)>0){
//        ?>
<!--        <script>
            var privalege= alert("You don't have the privelage to access this button")
                document.location="./index.php?InvalidPrivilege=yes";
        </script>-->
<?php
//    }
?>
<a href="diseaseconfiguration.php?OtherConfigurations=OtherConfigurationsThisForm" class="art-button-green">BACK</a>
<br/>
<br/>
<br/>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">LOAD ICD 10 DISEASES FROM EXCEL FILE</div>
            <div class="panel-body">
                <form action="" method="POST" class="form-horizontal" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="col-md-3">
                            Select Disease Category
                        </label>
                        <div class="col-md-9">
                            <select class="form-control" id="disease_category_ID" name="disease_category_ID"onchange="fill_subcategory()">
                                <option value=""></option> 
                                <?php 
                                    $sql_select_query_result=mysqli_query($conn,"SELECT disease_category_ID,category_discreption FROM tbl_disease_category") or die(mysqli_error($conn));
                                    if(mysqli_num_rows($sql_select_query_result)>0){
                                        while($select_result_roews=mysqli_fetch_assoc($sql_select_query_result)){
                                            $disease_category_ID=$select_result_roews['disease_category_ID'];
                                            $category_discreption=$select_result_roews['category_discreption'];
                                            echo "<option value='$disease_category_ID'>$category_discreption</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3">
                            Select Disease Sub Category
                        </label>
                        <div class="col-md-9">
                            <select class="form-control" id="subcategory_ID" name="subcategory_ID">
                                <option value=""></option> 
                                
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-3">
                            Select Excel File
                        </div>
                        <div class="col-xs-6">
                            <input type="file" name="excel_file" id="excel_file"/>
                        </div>
                        <div class="col-xs-3">
                            <input type="submit" value="UPLOAD" onclick="return validate_input()" name="uploading_btn" class="art-button-green pull-right">
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-md-12">
                        <div style="background: #038AC4;color:#FFFFFF;padding: 5px;text-shadow: 2px 2px 2px black;">
                            <p>1.Excel file supported is <b>EXCEL 97-2003 WORKBOOK WITH EXTENSION .xls</b></p>
                            <p>2.Only Two Column Allowed,first column must be <b>DISEASE CODE(A1)</b> and second column must be <b>DISEASE NAME(B1)</b> </p>
                            <!--<p>3.The first row must be heading which is <b>ITEM NAME(A1)</b> and <b>ITEM PRICE(B1)</b></p>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function fill_subcategory(){
      var disease_category_ID=$("#disease_category_ID").val();
      $.ajax({
          type:'GET',
          url:'disease_fetch_subcategory.php',
          data:{disease_category_ID:disease_category_ID},
          success:function (data){
            $("#subcategory_ID").html(data);
          }
      }); 
    }
    function validate_input(){ 
      var disease_category_ID=$("#disease_category_ID").val();  
      var subcategory_ID=$("#subcategory_ID").val();  
      var excel_file=$("#excel_file").val();  
      if(disease_category_ID==""){
          $("#disease_category_ID").css("border","2px solid red");
      }else{
          $("#disease_category_ID").css("border","");
      }
      if(subcategory_ID==""){
          $("#subcategory_ID").css("border","2px solid red");
      }else{
          $("#subcategory_ID").css("border","");
      }
      if(excel_file==""){
          $("#excel_file").css("border","2px solid red");
      }else{
          $("#excel_file").css("border","");
      }
      if(excel_file!=""&&subcategory_ID!=""&&disease_category_ID!=""){
            if(confirm("Are you sure You Whant to Upload the selected Excel File")){
                return true;
            }else{
                return false;
            }
      }else{
          
          return false;
      }
     return false; 
    }
</script>
<?php 
///upl;oading function
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
   $subcategory_ID= $_POST['subcategory_ID'];
   $disease_category_ID= $_POST['disease_category_ID'];
   $count_uploded=0;
   $count_fail_uploded=0;
   $count_fail_insert_item=0;
   include("PHPExcel/IOFactory.php");
	
	$filename=upload("excel_file","excelfiles/");
	 $ext=substr(strrchr($filename,'.'),1);
         
         $sql_insert_items_result=false;
         
	if($ext=="xls"){
	
        if(!empty($filename)){
	$objPHPExcel=PHPExcel_IOFactory::load("excelfiles/$filename");
	foreach($objPHPExcel->getWorksheetIterator()as $worksheet){
		$highestRow=$worksheet->getHighestRow();
		for($row=1;$row<=$highestRow;$row++){
			$disease_code=mysqli_real_escape_string($conn,$worksheet->getCellByColumnAndRow(0,$row)->getValue());
			$disease_name=mysqli_real_escape_string($conn,$worksheet->getCellByColumnAndRow(1,$row)->getValue());
                        //check if disease with the same code exist
                        $sql_check_if_the_disease_code_exist_result=mysqli_query($conn,"SELECT disease_code FROM tbl_disease WHERE disease_code='$disease_code'") or die(mysqli_error($conn));
		
                        if(mysqli_num_rows($sql_check_if_the_disease_code_exist_result)>0){
                           //skip this disease;
                        }else{
                           //insert all disease that are not in the database 
                            $sql_insert_result=mysqli_query($conn,"INSERT INTO tbl_disease(disease_code,nhif_code,disease_name,subcategory_ID,Employee_ID,Date_And_Time,disease_version)
                                    VALUES('$disease_code','$disease_code','$disease_name','$subcategory_ID','$Employee_ID',(select now()),'icd_10')
                                    ") or die(mysqli_error($conn));
                            if($sql_insert_result){
                                $count_uploded++;
                            }else{
                                $count_fail_uploded++;
                            }
                        }
                }
            }	
	}
    }else{
         echo   $message2="<script>alert('Invalid File Format...please select Excel File with extension .xls And Try Again')</script>";	
    
         die();
    }
    if($count_uploded>0||$count_fail_uploded>0){
        if($count_fail_uploded>0){$count_fail_uploded="---$count_fail_uploded FAIL";}else{$count_fail_uploded="";}
        echo "<script>alert('$count_uploded ITEMS UPLODED SUCCESSFULL! $count_fail_uploded')</script>";
    }else{
        echo "<script>alert('FAIL TO UPLOAD!PLEASE TRY AGAIN')</script>";
    }
}

?>
<?php
include("./includes/footer.php");
