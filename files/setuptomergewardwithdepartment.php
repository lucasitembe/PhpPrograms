<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }


     $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
     $Current_Username = $_SESSION['userinfo']['Given_Username'];

//    $sql_check_prevalage="SELECT edit_items FROM tbl_privileges WHERE Free_Items_works='yes' AND "
//            . "Given_Username='$Current_Username'";
//
//    $sql_check_prevalage_result=mysqli_query($conn,$sql_check_prevalage);
//    if(!mysqli_num_rows($sql_check_prevalage_result)>0){
//        ?>
<!--                    <script>
                        var privalege= alert("You don't have the privelage to access this button")
                            document.location="./index.php?InvalidPrivilege=yes";
                    </script>-->

<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes') {

if(isset($_GET['frompage']) && $_GET['frompage'] == "addmission") {
?>
<a href='admissionconfiguration.php?AdmisionWorks=AdmisionWorksThisPage&frompage=addmission' class='art-button-green'>
    <b>BACK</b>
</a>

<?php
    } else {
?>
<a href='admissionconfiguration.php?AdmisionWorks=AdmisionWorksThisPage' class='art-button-green'>
    <b>BACK</b>
</a>

<?php
    }
?>

<?php  }
} ?>

<br/><br/>
<style>
    table,tr,td{
        border:none!important;
    }
</style>

<fieldset>
    <legend align="center"> SETUP TO MERGE DEPARTIMENT(S) WITH WARD(S)</legend>

    <div class="row">
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header">
                    <h4>LIST OF ALL DEPARTMENTS</h4><br>
                </div>
               <input type="text" id='depertment_search_value' onkeyup="search_depertments()"placeholder="search depertment name" class="form-control" style="width:90%; text-align:center;"/></span></caption>
               <table class="table">
                    <tr style="border-bottom:1px solid #328CAF!important;">
                        <td>
                            <label>
                                <input type='checkbox'id='select_all_depertments'>SELECT ALL DEPARTMENTS
                            </label>
                        </td>
                    </tr>
                </table>
                <div class="box-body" style="height: 420px;overflow-y: auto;overflow-x: auto">
                    <div id="depertments_list">
                        <table class="table">
                        <?php
                        $sql_result=mysqli_query($conn,"SELECT finance_department_code,finance_department_id,finance_department_name FROM tbl_finance_department WHERE enabled_disabled='enabled' LIMIT 20") or die(mysqli_error($conn));
                                if(mysqli_num_rows($sql_result)>0){
                                        while($category_rows=mysqli_fetch_assoc($sql_result)){
                                            $Depertment_ID=$category_rows['finance_department_id'];
                                                $Depertment_Name=$category_rows['finance_department_name'];
                                                echo "<tr>
                                                            <td>
                                                                <label style='font-weight:normal'>
                                                                    <input type='checkbox'class='Depertment_ID' name='Depertment_ID' value='$Depertment_ID'>$Depertment_Name
                                                                </label>
                                                            </td>
                                                            
                                                    </tr>";
                                        }
                                    }else{
                                        echo "<tr>
                                                    <td>
                                                        <label style='color:red;'>
                                                            SORRY, NO RESULT FOUND!
                                                        </label>
                                                    </td>
                                                    
                                            </tr>";
                                    }
                                ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header">
                  <h4>LIST OF WARDS</h4>
                </div>
                <input type="text" id='ward_search_value' onkeyup="search_ward()"placeholder="Search ward name" class="form-control" style="width:90%; margin-top:10px !important; text-align:center;"/></span></caption>
                <table class="table">
                    <tr style="border-bottom:1px solid #328CAF!important;">
                        <td>
                            <label>
                                <input type='checkbox'id='select_all_wards'>SELECT ALL WARDS
                            </label>
                        </td>
                        <td width="5%">
                        <a class="btn btn-default pull-right btn-sm" onclick="select_all_wards()" style="text-decoration:none!important;"><i id="attach_cat_icon" style="color:#328CAF" class="fa fa-send fa-1x"></i> MERGE </a>
                        </td>
                    </tr>
                </table>
                <div class="box-body" style="height: 417px;overflow-y: auto;overflow-x: hidden">
                    <div id="wards_list">
                        <table class="table">
                        <?php 
                        $sql_result=mysqli_query($conn,"SELECT Hospital_Ward_ID,Hospital_Ward_Name FROM tbl_hospital_ward LIMIT 20") or die(mysqli_error($conn));
                        if(mysqli_num_rows($sql_result)>0){
                            while($Ward_Row=mysqli_fetch_assoc($sql_result)){
                                $Ward_ID=$Ward_Row['Hospital_Ward_ID'];
                                    $Hospital_Ward_Name=$Ward_Row['Hospital_Ward_Name'];
                                        echo  "<tr>
                                                    <td>
                                                        <label style='font-weight:normal'>
                                                            <input type='checkbox' class='Ward_ID' name='Ward_ID' value='$Ward_ID'> $Hospital_Ward_Name
                                                        </label>
                                                    </td>
                                                </tr>";
                                }
                            }else{
                                echo "<tr>
                                        <td>
                                            <label style='color:red;'>
                                                SORRY, NO RESULT FOUND!
                                            </label>
                                        </td>  
                                    </tr>";
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header">
                    <h5>LIST OF MERGED DEPARTMENT(S) AND WARD(S)</h5>
                </div>

                <input type="text" id='search_department_ward_value' onkeyup="search_department_ward()" placeholder="search by department name" class="form-control" style="width:90%; margin-top:10px !important; text-align:center;"/></span></caption>

                <table class="table">
                    <tr style="border-bottom:1px solid #328CAF!important;">
                        <td>
                            <label>
                                <input type='checkbox'id='select_all_department_word'>SELECT ALL MERGED WARD(S)
                            </label>
                        </td>
                        <td width="5%">
                        <a href="#" class="btn btn-default pull-right btn-sm" onclick="delete_department_ward()" style="color:red;text-decoration:none!important;"><i id="attach_cat_icon"  class="fa fa-trash fa-1x"></i> DELETE ALL SELECTED</a>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <center class="text-info">
                                <span id="msg"></span>
                            </center>
                        </td>
                    </tr>
                </table>

                <div class="loading" style="display:none">
                    <center>
                        <img style="border: none;" src="images/please_wait_loading.gif" alt="Loading gif" class="img img-responsive">
                    </center>
                </div>

                <div class="box-body" id="box-body"  style="height: 420px; overflow-y: auto; overflow-x: hidden">
                    <div id='merged_wards_depertments_list'>
                        <table class="table">
                            <?php 
                                $get_wards_departments = mysqli_query($conn,"SELECT DISTINCT d.department_id, f.finance_department_name FROM tbl_department_ward AS d, tbl_finance_department AS f WHERE f.enabled_disabled='enabled' AND f.finance_department_id = d.department_id");
                                if(mysqli_num_rows($get_wards_departments)){
                                while ($row1=mysqli_fetch_array($get_wards_departments)) {
                                    $department_id = $row1['department_id'];
                            ?>
                                <tr style="border:2px solid #328CAF!important;background: #C0C0C0;">
                                    <th colspan="2"><?= $row1['finance_department_name'] ?></th>
                                </tr>
                            <?php 
                             $get_department = mysqli_query($conn,"SELECT department_ward_id,ward_id FROM tbl_department_ward WHERE department_id='$department_id'  ORDER BY department_id ASC");
                             $i=1;
                             while ($row2=mysqli_fetch_array($get_department)) { 
                                 $ward_id = $row2["ward_id"];
                                 $department_ward_id=$row2["department_ward_id"];
                                 $get_ward = mysqli_query($conn,"SELECT Hospital_Ward_ID,Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_ID='$ward_id'");
                                 $row3 = mysqli_fetch_array($get_ward);
                                 
                                 $ward_name = $row3['Hospital_Ward_Name'];
                            ?>
                                <tr>
                                    <td width="1%" style="border:1px solid #328CAF!important;"><?= $i ?></td>
                                    <td width="99%" style="border:1px solid #328CAF!important;">
                                        <label for="merged_Depertment_ID_<?= $department_ward_id  ?>"  style='font-weight:normal'>
                                            <input type="checkbox" id="merged_Depertment_ID_<?= $department_ward_id ?>" class="merged_Depertment_ID" value="<?= $department_ward_id  ?>"> <?= $ward_name; ?>
                                        </label>
                                    </td>
                                </tr>
                            <?php $i++;}}}else{?>
                                <tr>
                                    <td width="10%"></td>
                                    <td width="90%">
                                        <label>
                                            <b style="color: red;">NO, DATA FOUND!</b>
                                        </label>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</fieldset>

<script>

$("#select_all_depertments").click(function (e){
    $(".Depertment_ID").not(this).prop('checked', this.checked); 
});

$("#select_all_wards").click(function (e){
    $(".Ward_ID").not(this).prop('checked', this.checked); 
});

$("#select_all_department_word").click(function (e){
    $(".merged_Depertment_ID").not(this).prop('checked', this.checked); 
});

function search_depertments(){
    var depertment_search_value = $("#depertment_search_value").val();
    
    $.ajax({
        type:'POST',
        url:'ajax_department_search.php',
        data:{depertment_search_value:depertment_search_value},
        success:function(data){
            $("#depertments_list").html(data); 
        }
    });
}

function search_ward(){
    var ward_search_value = $("#ward_search_value").val();
    $.ajax({
        type:'POST',
        url:'ajax_ward_search.php',
        data:{ward_search_value:ward_search_value},
        success:function(data){
            $("#wards_list").html(data); 
        }
    });
}

function search_department_ward(){
    var search_department_ward_value = $("#search_department_ward_value").val();
    $.ajax({
        type:'POST',
        url:'ajax_search_department_ward.php',
        data:{search_department_ward_value:search_department_ward_value},
        success:function(data){
            $("#merged_wards_depertments_list").html(data); 
        }
    });
}

function delete_department_ward(){
    var merged_department = []; 
    $(".merged_Depertment_ID:checked").each(function() {
        merged_department.push($(this).val());
       
    });

    if(merged_department==""){
        alert("Select merged ward to delete.");
    }else{
         console.log(merged_department);
        if (confirm('Are you sure you want to delete? This action is irreversible!.')){
            $('#box-body').hide();$('.loading').show();
            $.ajax({
                type:'POST',
                url:'ajax_delete_merged_item.php',
                data:{merged_department:merged_department},
                success:function(data){
                    $('.loading').hide();$('#box-body').show();
                    // alert(data);
                   // console.log(data);
                    // alert('Successfully deleted free item(s)');
                    $("#msg").html('Data successfully deleted. '); 
                    $("#merged_wards_depertments_list").load(" #merged_wards_depertments_list"); //consider space 
                    
                }
            });
        }
    } 
    
}

function select_all_wards(){
    var selected_departments = []; 
    $(".Depertment_ID:checked").each(function() {
        selected_departments.push($(this).val());
    });

    var selected_wards = []; 
    $(".Ward_ID:checked").each(function() {
        selected_wards.push($(this).val());
    });

    if(selected_departments==""){
        alert("Department(s) must be selected first.");
    }else if(selected_wards==""){
        alert("Ward(s) must be selected before submitting.");
    }
    else{
        if (confirm('Are you sure you want to merge selected item(s)?')){
            $('.box-body').hide();$('.loading').show();
            $.ajax({
                type:'POST',
                url:'ajax_add_departments_wards.php',
                data:{selected_departments:selected_departments,selected_wards:selected_wards},
                success:function(data){
                    $('.loading').hide();$('.box-body').show();
                    $("#merged_wards_depertments_list").load(" #merged_wards_depertments_list"); //consider the space 
                    // alert('Successfully added item(s)');
                    $("#msg").html('Merged successfully');
                }
            });
        }
    }
}

</script>

<?php
include("./includes/footer.php");
?>
