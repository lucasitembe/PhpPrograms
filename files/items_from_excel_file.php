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
     
    $sql_check_prevalage="SELECT edit_items FROM tbl_privileges WHERE can_load_item_from_excel='yes' AND "
            . "Given_Username='$Current_Username'";
    
    $sql_check_prevalage_result=mysqli_query($conn,$sql_check_prevalage);
    if(!mysqli_num_rows($sql_check_prevalage_result)>0){
        ?>
        <script>
            var privalege= alert("You don't have the privelage to access this button")
                document.location="./index.php?InvalidPrivilege=yes";
        </script>
<?php
    }
?>
<a href="items_from_excel_others.php" class="art-button-green">NON MEDICAL ITEMS</a>
<a href="itemsconfiguration.php" class="art-button-green">BACK</a>
<br/>
<br/>
<br/>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">LOAD ITEM FROM EXCEL FILE</div>
            <div class="panel-body">
                <form action="" method="POST" class="form-horizontal" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="col-md-3">
                            Select Sponsor
                        </label>
                        <div class="col-md-9">
                            <select class="form-control" name="Sponsor_ID" id="Sponsor_ID"> 
                                <option value=""></option>
                                <?php 
                                    $sql_sponsor_result=mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor WHERE auto_item_update_api<>'1'") or die(mysqli_error($conn));
                                    if(mysqli_num_rows($sql_sponsor_result)>0){
                                        while($sponsor_row=mysqli_fetch_assoc($sql_sponsor_result)){
                                            $Sponsor_ID=$sponsor_row['Sponsor_ID'];
                                            $Guarantor_Name=$sponsor_row['Guarantor_Name'];
                                            echo "<option value='$Sponsor_ID'>$Guarantor_Name</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3">
                            Select Item Type
                        </label>
                        <div class="col-md-9">
                            <select class="form-control" name="item_type" id="item_type">
                                <option value=""></option> 
                                <option value="Service">Serviced Item</option>
                                <option value="Pharmacy">Pharmaceutical Item</option>
                                <option value="Others">Other Item</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3">
                            Select Item Category
                        </label>
                        <div class="col-md-9">
                            <select class="form-control"id="Item_Category_ID" name="Item_Category_ID"onchange="fill_subcategory()">
                                <option value=""></option> 
                                <?php 
                                    $sql_sponsor_result=mysqli_query($conn,"SELECT Item_Category_ID,Item_Category_Name FROM tbl_item_category") or die(mysqli_error($conn));
                                    if(mysqli_num_rows($sql_sponsor_result)>0){
                                        while($sponsor_row=mysqli_fetch_assoc($sql_sponsor_result)){
                                            $Item_Category_ID=$sponsor_row['Item_Category_ID'];
                                            $Item_Category_Name=$sponsor_row['Item_Category_Name'];
                                            echo "<option value='$Item_Category_ID'>$Item_Category_Name</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3">
                            Select Item Sub Category
                        </label>
                        <div class="col-md-9">
                            <select class="form-control" id="item_sub_category" name="item_sub_category">
                                <option value=""></option> 
                                
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3">
                            Select Item Consultation Type
                        </label>
                        <div class="col-md-9">
                            <select class="form-control" name="consultation_type" id="consultation_type">
                                <option value=""></option> 
                                <option value="Pharmacy">Pharmacy</option> 
                                <option value="Laboratory">Laboratory</option> 
                                <option value="Radiology">Radiology</option> 
                                <option value="Surgery">Surgery</option> 
                                <option value="Procedure">Procedure</option> 
                                <option value="Optical">Optical</option> 
                                <option value="Others">Others</option> 
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
                        <div style="background: #038AC4;color:#FFFFFF;padding: 5px">
                            <p>1.Excel file supported is <b>EXCEL 97-2003 WORKBOOK WITH EXTENSION .xls</b></p>
                            <p>2.Only Two Column Allowed,first column must be <b>ITEM NAME(A1)</b> and second column must be <b>ITEM PRICE(B1)</b> </p>
                            <p>3.The first row must be heading which is <b>ITEM NAME(A1)</b> and <b>ITEM PRICE(B1)</b></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function fill_subcategory(){
      var Item_Category_ID=$("#Item_Category_ID").val();
      $.ajax({
          type:'GET',
          url:'item_subcategory.php',
          data:{Item_Category_ID:Item_Category_ID},
          success:function (data){
            $("#item_sub_category").html(data);
          }
      }); 
    }
    function validate_input(){
      var Sponsor_ID=$("#Sponsor_ID").val();  
      var item_type=$("#item_type").val();  
      var Item_Category_ID=$("#Item_Category_ID").val();  
      var item_sub_category=$("#item_sub_category").val();  
      var consultation_type=$("#consultation_type").val();  
      var excel_file=$("#excel_file").val();  
      if(Sponsor_ID==""){
          $("#Sponsor_ID").css("border","2px solid red");
      }else{
          $("#Sponsor_ID").css("border","");
      }
      if(item_type==""){
          $("#item_type").css("border","2px solid red");
      }else{
          $("#item_type").css("border","");
      }
      if(Item_Category_ID==""){
          $("#Item_Category_ID").css("border","2px solid red");
      }else{
          $("#Item_Category_ID").css("border","");
      }
      if(item_sub_category==""){
          $("#item_sub_category").css("border","2px solid red");
      }else{
          $("#item_sub_category").css("border","");
      }
      if(consultation_type==""){
          $("#consultation_type").css("border","2px solid red");
      }else{
          $("#consultation_type").css("border","");
      }
      if(excel_file==""){
          $("#excel_file").css("border","2px solid red");
      }else{
          $("#excel_file").css("border","");
      }
      if(excel_file!=""&&consultation_type!=""&&item_sub_category!=""&&Item_Category_ID!=""&&item_type!=""&&Sponsor_ID!=""){
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
   $consultation_type= $_POST['consultation_type'];
   $item_sub_category= $_POST['item_sub_category'];
   $Item_Category_ID= $_POST['Item_Category_ID'];
   $item_type= $_POST['item_type'];
   $Sponsor_ID= $_POST['Sponsor_ID'];
   $count_uploded=0;
   $count_fail_uploded=0;
   $count_fail_insert_item=0;
   include("PHPExcel/IOFactory.php");
	
	$filename=upload("excel_file","excelfiles/");
	 $ext=substr(strrchr($filename,'.'),1);
         
         $sql_insert_items_result=false;
         
	if($ext=="xls"){
	
        if(!empty($filename)){
            ///delete previous price per this category
        //$sql_remove_previous_price_reuslt=mysqli_query($conn,"DELETE FROM tbl_item_price WHERE Sponsor_ID='$Sponsor_ID' AND Item_ID IN (SELECT Item_ID FROM tbl_items WHERE Item_Subcategory_ID='$item_sub_category')") or die(mysqli_error($conn));
	$objPHPExcel=PHPExcel_IOFactory::load("excelfiles/$filename");
	foreach($objPHPExcel->getWorksheetIterator()as $worksheet){
		$highestRow=$worksheet->getHighestRow();
		for($row=1;$row<=$highestRow;$row++){
			$Product_Name=mysqli_real_escape_string($conn,$worksheet->getCellByColumnAndRow(0,$row)->getValue());
			$item_price=mysqli_real_escape_string($conn,$worksheet->getCellByColumnAndRow(1,$row)->getValue());
			
		//check if the item with the same name exist
                $sql_select_item_result=mysqli_query($conn,"SELECT Item_ID FROM tbl_items WHERE Item_Subcategory_ID='$item_sub_category' AND Product_Name='$Product_Name' AND Consultation_Type='$consultation_type'") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_select_item_result)>0){
                    $item_id_rw=mysqli_fetch_assoc($sql_select_item_result);
                    $Item_ID= $item_id_rw['Item_ID'];
                    $sql_insert_item_price1="INSERT INTO tbl_item_price(Sponsor_ID,Item_ID,Items_Price,last_updated_by) VALUES('$Sponsor_ID','$Item_ID','$item_price','$Employee_ID') ON DUPLICATE KEY UPDATE Items_Price='$item_price',last_updated_by='$Employee_ID'";
                    $sql_insert_item_price_result1=mysqli_query($conn,$sql_insert_item_price1) or die(mysqli_error($conn));
                        if($sql_insert_item_price_result1){
                           $count_uploded++; 
                        }else{
                           $count_fail_uploded++;
                        }
                        
                }else{
                     $sql_insert_items_result=mysqli_query($conn,"INSERT INTO tbl_items(Unit_Of_Measure,Product_Code,Item_Type,Product_Name,Item_Subcategory_ID,Consultation_Type) VALUES('Pieces','$item_type','$item_type','$Product_Name','$item_sub_category','$consultation_type')") or die(mysqli_error($conn));  
                }
                
                
               if($sql_insert_items_result){
                    
                        $select_item_id_result=mysqli_query($conn,"SELECT Item_ID FROM tbl_items ORDER BY Item_ID DESC LIMIT 1") or die(mysqli_error($conn));
                        $iterm_id_row=mysqli_fetch_assoc($select_item_id_result);
                        $Item_ID=$iterm_id_row['Item_ID'];
                        
                        $sql_insert_item_price="INSERT INTO tbl_item_price(Sponsor_ID,Item_ID,Items_Price,last_updated_by) VALUES('$Sponsor_ID','$Item_ID','$item_price','$Employee_ID') ON DUPLICATE KEY UPDATE Items_Price='$item_price',last_updated_by='$Employee_ID'";
                        $sql_insert_item_price_result=mysqli_query($conn,$sql_insert_item_price) or die(mysqli_error($conn));
                        if($sql_insert_item_price_result){
                           $count_uploded++; 
                        }else{
                           $count_fail_uploded++;
                        }
                        
                }else{
                     $count_fail_insert_item++; 
                  }      
		}
            }	
	}
    }else{
         echo   $message2="<script>alert('Invalid File Format...please select Excel File with extension .xls And Try Again')</script>";	
    
         die();
    }
    if($count_uploded>0){
        if($count_fail_uploded>0){$count_fail_uploded="---$count_fail_uploded FAIL";}else{$count_fail_uploded="";}
        echo "<script>alert('$count_uploded ITEMS UPLODED SUCCESSFULL! $count_fail_uploded')</script>";
    }else{
        echo "<script>alert('FAIL TO UPLOAD!PLEASE TRY AGAIN')</script>";
    }
}

?>
<?php
include("./includes/footer.php");
