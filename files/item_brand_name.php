<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }


     $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
     $Current_Username = $_SESSION['userinfo']['Given_Username'];

    $sql_check_prevalage="SELECT edit_items FROM tbl_privileges WHERE edit_items='yes' AND "
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
    /*if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	    if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes' || $_SESSION['userinfo']['Reception_Works'] == 'yes' || $_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }*/

   if(isset($_SESSION['userinfo'])){
        //if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Pharmacy'] == 'yes'){
?>
    <a href='itemsconfiguration.php?ItemsConfiguration=ItemsConfigurationThisPage' class='art-button-green'>
        BACK
    </a>
<?php  }




?>

<br/><br/>
<style>
    table,tr,td{
        border:none!important;
    }
</style>
</style>
    <div id="showdata" style="width:100%;  overflow:hidden;display:none;">
            <div id="parameters">
                <table width=100% >
                    <tr>
                        <td width="40%">
                            <strong>Enter Brand Name:</strong>
                        </td>
                        <td>
                            <input type='text' name='Brand_name' style='padding-left:12px; height:28px;' id='Brand_name' required='required' placeholder='Enter Brand Name'>
                        </td>
                        <td>
                            <button class='art-button-green' id="itemIDAdd"  onclick="add_Brand_name()" style="margin-left:13px !important; background-color:white !important;" >ADD</button>
                        </td>
                    </tr>
                </table>
                <div id="DelResults"></div>
                <div id="ItemParameters"></div>
            </div>

        </div>
<fieldset>
    <legend align="center"> PHARMACEUTICAL ITEMS AND BRAND NAME MERGING</legend>

    <div class="row">
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header">
                    <h4>GENERIC ITEMS</h4>
                </div>
               <input type="text" id='search_value' onkeyup="search_item()"placeholder="Search item" class="form-control" style="width:90%;"/></span></caption>
                <div class="box-body" style="height: 420px;overflow-y: auto;overflow-x: auto">
                    <table class="table">
                        <tr>
                            <td width="5%">S/No.</td>
                            <td width='40%'>Pharmaceutical Names</td>
                            <td width='5%'>Action</td>
                        </tr>
                        <tbody id="table_search">
                            <?php
                            $sql_select_phamathetical_item=mysqli_query($conn,"SELECT Product_Name,Item_ID FROM tbl_items WHERE Consultation_Type='Pharmacy' AND item_kind = 'generic' LIMIT 50") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_phamathetical_item)>0){
                                $count=1;
                                while($phamathetical_elemetn_id_rows=mysqli_fetch_assoc($sql_select_phamathetical_item)){
                                    $phamathetical_dataelement_id=$phamathetical_elemetn_id_rows['Item_ID'];
                                    $displayname=$phamathetical_elemetn_id_rows['Product_Name'];

                                         $sql_brand_id=mysqli_fetch_assoc(mysqli_query($conn,"SELECT brand_name_id FROM tbl_phamathetical_item_brand_name WHERE phamathetical_item_id='$phamathetical_dataelement_id'"))['brand_name_id'];
                                    echo "<tr>
                                            <td>$count.</td>
                                            <td>$displayname</td>
                                            <td>
                                                <input type='button' id='Product_Name_search' style='color:green;' value='>>' onclick='open_phamathetica_item(\"$phamathetical_dataelement_id\",\"$sql_brand_id\")'/>
                                            </td>
                                         </tr>";
                                    $count++;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header">
                  <h4>BRAND ITEMS</h4>
                    <!--form action=""  method="POST" enctype="multipart/form-data">
                    <div class="col-md-4"><input type="file" placeholder="Enter Data Element" name="data_set_excel_file" equired="" class="form-control-file"/></div>
                     <input type="submit" name='uploading' value="UPLOAD EXCEL" class="art-button-green pull-right"/>
                     </form>
                    <div class="col-md-5" style="margin-top:20px; margin-left:225px;"><button type='button' style='color:white !important; height:27px !important;' class='art-button-green' onclick='add_Brand_name();'>New Brand Name</button></div-->
                    <input type="text" id='search_value2' onkeyup="search_item2()"placeholder="Search brand name" class="form-control" style="width:90%; margin-top:10px !important;"/></span></caption>
                    <!--<div class="col-md-12"><button type='button' style='color:white !important;' class='art-button-green' onclick='addorganism();'>Add Organism</button></div></div>-->
                </div>
                <div class="box-body" style="height: 360px;overflow-y: auto;overflow-x: hidden">
                    <table class="table">
                        <tr>
                          <td>S/No.</td>
                            <td>BRAND NAME</td>
                            <td>ACTION</td>
                        </tr>
                        <tbody id="list_of_values_source_items">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header">
                    <h4>BRAND GENERIC LIST</h4>

                </div>
                <div class="box-body" style="height: 450px">
                    <div class='row' style='height:150px;overflow-y: auto;overflow-x: hidden'>
                        <div class="col-md-12">
                            <table class="table">
                                <!--tr>
                                <caption><b> ASSIGNED PHARMACEUTICAL ITEM TO MERGE TO BRAND NAME</b></caption>
                              </tr-->
                                <tr>
                                    <!--td>S/No.</td-->
                                    <td>Generic Name</td>
                                </tr>
                                <tbody id='Phamathetical_opening_area'>
                                    <tr><td><input type="text" value=""class='hide' id="selected_Phamathetical_dataelement_id"/></tr></td>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--div class="row">
                        <div class="col-md-12">
                            <hr/>
                        </div>
                    </div-->
                    <div class='row' style='height:230px;overflow-y: auto;overflow-x: hidden;'>
                        <div class="col-md-12">
                            <table class="table">
                                <tr>
                                <caption><b>LIST OF BRAND NAME</b></caption>
                                </tr>
                                <tr>
                                    <td>S/No.</td>
                                    <td>BRAND NAME</td>
                                    <td>ACTION</td>
                                </tr>
                                <tbody id="list_of_merged_data_source_items">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</fieldset>

<script>
         function add_Brand_name(){
                            //alert(Item_ID+"  "+Item_Subcategory_ID+" "+item_Name);
                            $("#showdata").dialog("option", "title");
                               $("#itemIDAdd").attr("onClick", "add_Brand_name1()");
//                              $("#Cached").html('');
                            $("#showdata").dialog("open");

                        }
         function  add_Brand_name1(){
          var Brand_name = document.getElementById('Brand_name').value;
            if (Brand_name == '') {
                alert("Enter Brand Name");
                exit();
            }
        $.ajax({
            type: 'GET',
            url: 'Add_brand_name.php',
            data: {Brand_name:Brand_name},
            success: function (result) {
//               $('#Cached').html(result);
                 fill_brand_name();
                 alert("success data saved");
                 document.getElementById('Brand_name').value = "";
//                  getdropdown();
            }, error:function(x,y,z){
                console.log(x+y+z);
            }
        });}
     $(document).ready(function () {
         fill_brand_name();
        $("#showdata").dialog({autoOpen: false, width: '60%', title: 'New Brand Name', modal: true, position: 'center'});
        //alert(firstSelected);
    });
    function fill_brand_name(){
//      var data_element_value_source=$("#data_element_value_source").val();
      $.ajax({
          type:'POST',
          url:'display_brand_name.php',
          data:"",
          success:function(data){
             $("#list_of_values_source_items").html(data);
          },
          error:function(x,y,z){
              console.log(z);
          }
      });
    }
    function search_item(){
        var search_value=$("#search_value").val();
        var Product_Name_search=$("#Product_Name_search").val();
          $.ajax({
          type:'POST',
          url:'phamathetical_search.php',
          data:{Product_Name_search:Product_Name_search,search_value:search_value},
          success:function(data){
             $("#table_search").html(data);
          },
          error:function(x,y,z){
              console.log(z);
          }
      });
    }

       function search_item2(){
        var search_value2=$("#search_value2").val();
          $.ajax({
          type:'POST',
          url:'phamathetical_brand_search.php',
          data:{search_value2:search_value2},
          success:function(data){
             $("#list_of_values_source_items").html(data);
          },
          error:function(x,y,z){
              console.log(z);
          }
      });
    }
    function open_phamathetica_item(phamathetical_dataelement_id,brand_id){
        console.log("hguytfy"+brand_id);
       $.ajax({
          type:'POST',
          url:'ajax_get_data_phamathetical_items.php',
          data:{phamathetical_dataelement_id:phamathetical_dataelement_id},
          success:function(data){
             $("#Phamathetical_opening_area").html(data);
             get_list_of_brand_name(brand_id,phamathetical_dataelement_id);
          },
          error:function(x,y,z){
              console.log(z);
          }
      });
    }
    function merge_value_source_to_data_element(Item_ID){
//       var data_element_value_source=$("#data_element_value_source").val();
       var selected_Phamathetical_dataelement_id=$("#selected_Phamathetical_dataelement_id").val();
       if(selected_Phamathetical_dataelement_id==""){
           alert("Select Pharmaceutical item to merge")
       }else{
            $.ajax({
               type:'POST',
               url:'merge_phamthetical_to_brand_name.php',
               data:{Item_ID:Item_ID,selected_Phamathetical_dataelement_id:selected_Phamathetical_dataelement_id},
               dataType:'json',
               success:function(data){
                 // $("#data_element_opening_area").html(data);
                 if(data.status == 'success'){
                    // alert("Merged Successfully");
                     get_list_of_brand_name(Item_ID,selected_Phamathetical_dataelement_id);
                 }else if(data.status == 'exist'){
                   alert('ITEM IS ALREADY MERGED AS A BRAND !!');
                 }else if(data.status == 'fail'){
                   alert('PROCESS FAILS');
                 }else if(data.status == 'same'){
                   alert("YOU CAN'T MERGE THE SAME ITEM !!");
                 }
               },
               error:function(x,y,z){
                   console.log(z);
               }
           });
        }
 }
 function get_list_of_brand_name(Item_ID,selected_Phamathetical_dataelement_id){
      $.ajax({
               type:'POST',
               url:'get_list_of_brand_name.php',
               data:{Item_ID:Item_ID,selected_Phamathetical_dataelement_id:selected_Phamathetical_dataelement_id},
               success:function(data){
                 $("#list_of_merged_data_source_items").html(data);
                 console.log(data)
               },
               error:function(x,y,z){
                   console.log(z);
               }
           });
 }
 function remove_all_items_merged_to_this_value_source(Item_ID,selected_Phamathetical_dataelement_id){
      $.ajax({
               type:'POST',
               url:'remove_phathetical_brand_name.php',
               data:{Item_ID:Item_ID,selected_Phamathetical_dataelement_id:selected_Phamathetical_dataelement_id},
               success:function(data){
                get_list_of_brand_name(Item_ID,selected_Phamathetical_dataelement_id);
               },
               error:function(x,y,z){
                   console.log(z);
               }
           });
 }
</script>
<?php
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
if(isset($_POST['uploading'])){
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
			$brand_name=mysqli_real_escape_string($conn,$worksheet->getCellByColumnAndRow(0,$row)->getValue());
                        //check if exist displayname
                        $sql_check_if_exist_result=mysqli_query($conn,"SELECT brand_name FROM tbl_brand_name WHERE brand_name='$brand_name'") or die(mysqli_error($conn));
                        if(mysqli_num_rows( $sql_check_if_exist_result)<=0){
                            ///SAVE data element to t=database
                            $sql_insert_data_element_to_database_reuslt=mysqli_query($conn,"INSERT INTO tbl_brand_name(brand_name) VALUES('$brand_name')") or die(mysqli_error($conn));
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

include("./includes/footer.php");
?>
