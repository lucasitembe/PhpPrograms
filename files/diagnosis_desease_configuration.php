<?php
include("./includes/functions.php");

include("./includes/header.php");

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

$DateGiven = date('Y-m-d');
?>
<?php
//get sub department id


$query = mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
$dataSponsor = '';
$dataSponsor.='<option value="All">All Sponsors</option>';

while ($row = mysqli_fetch_array($query)) {
    $dataSponsor.= '<option value="' . $row['Sponsor_ID'] . '">' . $row['Guarantor_Name'] . '</option>';
}


?>
<a href="dailyPatientAttendance.php" class='art-button-green'>DAILY PATIENT ATTENDANCE</a>
<a href='diagnosis_report_home.php?GovernmentReports=GovernmentReportsThisPage' class='art-button-green'>
        BACK </a>
<br/>
<br/>
<fieldset>
    <legend align=center><b>ATTACH DESEASE</b></legend>
    <div class="row">
        <div class="col-sm-6">
            <input type="text"style="text-align: center"class="form-control"id="all_sub_category_search_box" onkeyup="search_deseases_from_all_list()" placeholder="----------------Search----------------"/>
            <br/>
            <div class="box box-primary" style="height: 400px;overflow: auto">
                <div class="box-header">
                    <div class="col-sm-8"> <h4 class="box-title">List Of Deseases</h4></div>
                    <div class="col-sm-4">
                        <a href="#" class="btn btn-default pull-right" onclick="attach_sub_category_to_category()"><i id="attach_cat_icon" style="color:#328CAF" class="fa fa-send fa-2x"></i></a>
                    </div>
                </div>
                <div class="box-body" >
                    <label><input type="checkbox" id="select_all_checkbox"> Select All</label>
                    <div id="all_sub_category_list_body">
                        <table class="table">
                        <?php 
                            $sql_select_category_result=mysqli_query($conn,"SELECT disease_name,disease_ID FROM tbl_disease WHERE disease_version='icd_10'") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_category_result)>0){
                                while($category_rows=mysqli_fetch_assoc($sql_select_category_result)){
                                    $disease_ID=$category_rows['disease_ID'];
                                    $disease_name=$category_rows['disease_name'];
                                    echo "<tr>
                                                <td>
                                                    <label style='font-weight:normal'>
                                                        <input type='checkbox'class='Item_Subcategory_ID' name='Item_Subcategory_ID' value='$disease_ID'>$disease_name
                                                    </label>
                                                </td>
                                                
                                          </tr>";
                                }
                            }
                        ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <br>
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <br/>
                    <div class="box box-primary" style="height: 400px;overflow: auto">
                        <div class="box-header">
                            <div class="col-sm-12"><p id="sub_category_list_tittle" style="font-size:17px">List of Deseases Used in Report</p></div>
                            <div class="col-sm-6" style="display:none">
                                <input type="text" style="text-align:center" class="form-control" id="attached_sub_category_search_box" placeholder="--------Search--------" />
                            </div>
                            <label><input type="checkbox" id="select_all_checkbox1"> Select All</label> <input type="button"  onclick="delete_deseaes()" class="art-button-green" value="Delete" />
                        </div>
                        
                        <div class="box-body" id="attached_sub_category_body">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</fieldset>

 <script>
     $("#select_all_checkbox").click(function (e){
         $(".Item_Subcategory_ID").not(this).prop('checked', this.checked); 
         
     });    
     $("#select_all_checkbox1").click(function (e){
         $(".diagnosis_id").not(this).prop('checked', this.checked); 
         
     });    
     function attach_sub_category_to_category(){
        var selected_sub_Category = []; 
        $(".Item_Subcategory_ID:checked").each(function() {
		selected_sub_Category.push($(this).val());
	});
  
          if(selected_sub_Category==""){
              alert("Select disease first");
              
          }
        $.ajax({
            type:'POST',
            url:'ajax_attach_deseases.php',
            data:{selected_sub_Category:selected_sub_Category},
            success:function(data){
                console.log(data);
                $("#attached_sub_category_body").html(data);
//                refresh_content()
            }
        });
       //refresh_content() 
     }
     function delete_deseaes(){
        var selected_desease = []; 
        $(".diagnosis_id:checked").each(function() {
		selected_desease.push($(this).val());
	});

          if(selected_desease==""){
              alert("Select disease first");
              
          }
        $.ajax({
            type:'POST',
            url:'delete_deseases.php',
            data:{selected_desease:selected_desease},
            success:function(data){
                console.log(data);
              refresh_content();
            }
        });
       //refresh_content() 
     }
          function refresh_content(){
         $.ajax({
             type:'POST',
             url:'ajax_attach_deseases.php',
             data:'',
             success:function(data){
                  $("#attached_sub_category_body").html(data); 
             }
         });
     }
     
     function search_deseases_from_all_list(){
    var Item_Subcategory_Name = $("#all_sub_category_search_box").val();
     $.ajax({
             type:'POST',
             url:'search_deseases.php',
             data:{Item_Subcategory_Name:Item_Subcategory_Name},
             success:function(data){
                  $("#all_sub_category_list_body").html(data); 
             }
         });
}
      </script>
    <script type="text/javascript">
    $(document).ready(function () {
             refresh_content();

    });
</script>