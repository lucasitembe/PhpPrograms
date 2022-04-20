<?php
/*writen by gkc*/
include("./includes/header.php");
@session_start();
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if(isset($_SESSION['from']) &&  $_SESSION['from']=="ebill"){
    unset($_SESSION['from']);
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['General_Ledger'])) {
        if ($_SESSION['userinfo']['General_Ledger'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
 $sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
    while($date = mysqli_fetch_array($sql_date_time)){
        $Current_Date_Time = $date['Date_Time'];
    }
    $Filter_Value = substr($Current_Date_Time,0,11);
    $Start_Date = $Filter_Value.' 00:00';
    $End_Date = $Current_Date_Time;
    
    

?>
<a href='revenue_collection_summary_report_setup.php' class='art-button hide'>
    REPORT ITEMS SETUP
</a>
<a href="general_ledger_previous_collection_fill_empty_aid.php" class="art-button-green">PREVIOUS COLLECTION SETUP</a>
<a href='generalledgercenter.php' class='art-button-green'>
    BACK
</a>
<br/>
<style>
    .rows_list{
        cursor: pointer;
    }
    .rows_list:active{
        color: #328CAF!important;
        font-weight:normal!important;
    }
    .rows_list:hover{
        color:#00416a;
        background: #CCCCCC;
        font-weight:bold;
    }
    .department_row{
        cursor: pointer;
        color: #0079AE!important;
    }
    .department_row:active{
        cursor: pointer;
        color: #00416a!important;
        background: #CCCCCC!important;
    }
    .department_row:hover{
        cursor: pointer;
        background: #dedede;
    }
</style>
<fieldset>  
    <legend align=center><b>REVENUE COLLECTIONS SUMMARY</b></legend>
   <center> 
        <table>
            <tr>
                <td><input type="tex" class="form-control" placeholder="Start Date" style="background:#FFFFFF!important" value="<?= $Start_Date ?>" readonly="readonly"id="start_date"></td>
                <td><input type="tex" class="form-control" placeholder="End Date" style="background:#FFFFFF!important" value="<?= $End_Date ?>"readonly="readonly" id="end_date"></td>
                <td>
                    <select class="form-control">
                        <option value="All">All Sponsor</option>
                        <?php 
                            $sql_select_list_off_sponsor=mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
                        
                            if(mysqli_num_rows($sql_select_list_off_sponsor)>0){
                                while($sponsor_rows=mysqli_fetch_assoc($sql_select_list_off_sponsor)){
                                    $Sponsor_ID=$sponsor_rows['Sponsor_ID'];
                                    $Guarantor_Name=$sponsor_rows['Guarantor_Name'];
                                    echo "<option value='$Sponsor_ID'>$Guarantor_Name</option>";
                                }
                            }
                        ?>
                    </select>
                </td>
                <td>
                    <select class="form-control">
                        <option>All Employee</option>
                        <?php 
                             $sql_select_all_employee_result=mysqli_query($conn,"SELECT Employee_ID,Employee_Name FROM tbl_employee WHERE Account_Status='active'") or die(mysqli_error($conn));
                             if(mysqli_num_rows($sql_select_all_employee_result)>0){
                                while($employee_rows=mysqli_fetch_assoc($sql_select_all_employee_result)){
                                   $Employee_ID=$employee_rows['Employee_ID'];
                                   $Employee_Name=$employee_rows['Employee_Name'];
                                   echo "<option value='$Employee_ID'>$Employee_Name</option>";
                                } 
                             }
                        ?>
                    </select>
                </td>
                <td>
                    <select class="form-control">
                        <option>All patient type</option>
                        <option>OUT PATIENT</option>
                        <option>IN PATIENT</option>
                    </select>
                </td>
                <td>
                    <select class="form-control">
                        <option>All Ward</option>
                        <?php 
                            $sql_select_all_wards_result=mysqli_query($conn,"SELECT Hospital_Ward_ID,Hospital_Ward_Name FROM tbl_hospital_ward") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_all_wards_result)>0){
                                while($ward_rows=mysqli_fetch_assoc($sql_select_all_wards_result)){
                                   $Hospital_Ward_ID=$ward_rows['Hospital_Ward_ID'];
                                   $Hospital_Ward_Name=$ward_rows['Hospital_Ward_Name'];
                                   echo "<option value='$Hospital_Ward_ID'>$Hospital_Ward_Name</option>";
                                }
                            }
                        ?>
                    </select>
                </td>
                <td>
                    <select class="form-control">
                        <option>All Clinic</option>
                        <?php 
                            $sql_select_all_clinics_result=mysqli_query($conn,"SELECT Clinic_ID,Clinic_Name FROM tbl_clinic WHERE Clinic_Status='Available'") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_all_clinics_result)>0){
                                while($clinic_rows=mysqli_fetch_assoc($sql_select_all_clinics_result)){
                                   $Clinic_ID=$clinic_rows['Clinic_ID'];
                                   $Clinic_Name=$clinic_rows['Clinic_Name'];
                                   echo "<option value='$Clinic_ID'>$Clinic_Name</option>";
                                }
                            }
                        ?>
                    </select>
                </td>
                <td><input type='button' value='FILTER' class='art-button-green' onclick='correct_summary_report()'/></td>
                <td><input type='button' onclick='previewrevenuecollection()' value='PREVIEW' class='art-button-green'/></td>
            </tr>
        </table>
   </center>
    <div class="box box-primary" style="height:400px;overflow-y: scroll;overflow-x: hidden">
        <div id="progress_bar"></div>
        <table class="table table-hover">
            <tr>
                <td width="5%"><b>S/No.</b></td>
                <td style="width:20%">
                    <b>DEPARTMENT NAME</b>
                </td>
                <td style="width:10%">
                    <b>NO OF SERVICE</b>
                </td>
                <td style="width:10%">
                    <b>NO OF PATIENT</b>
                </td>
                <td>
                    <b>CASH</b>
                </td>
                <td>
                    <b>CREDIT</b>
                </td>
                <td>
                    <b>MSAMAHA</b>
                </td>
                <td>
                    <b>TOTAL</b>
                </td>
            </tr>
            <tbody id='report_body'>
            
            </tbody>
        </table>
    </div>
 </fieldset> 
<div id="selected_department_div"></div>
<div id="open_category_detail_div"></div>
<div id="open_item_detail_div"></div>
<div id="open_sub_category_detail_div"></div>
<div id="open_department_detail_div"></div>
<div id="open_sub_department_detail_div"></div>
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $('#start_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:    'now'
    });
    $('#start_date').datetimepicker({value: '', step: 01});
    $('#end_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:'now'
    });
    $('#end_date').datetimepicker({value: '', step: 01});
</script>
<script>
    function filter_revenue_collection_summary(){
       var start_date= $('#start_date').val();
       var end_date= $('#end_date').val();
       document.getElementById('report_body').innerHTML = '<tr><td colspan="7"><h4 style="color:#099015"><b>Fetch and calculate.Please Wait . . . </b></h4><div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div></td></tr>';
       $.ajax({
           type:'POST',
           url:'ajax_filter_revenue_collection_summary.php',
           data:{start_date:start_date,end_date:end_date},
           success:function(data){
               $("#report_body").html(data); 
           }
       });
    }
    function correct_summary_report(){
       var start_date= $('#start_date').val();
       var end_date= $('#end_date').val();
       document.getElementById('report_body').innerHTML = '<tr><td colspan="7"><h4 style="color:#C76300"><b>Scanning Report Parameter.Please Wait . . . </b></h4><div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div></td></tr>';
       $.ajax({
           type:'POST',
           url:'ajax_correct_summary_report.php',
           data:{start_date:start_date,end_date:end_date},
           success:function(data){
               console.log("==>"+data);
//               $("#report_body").html(data);
               filter_revenue_collection_summary();
           }
       }); 
    }
     $(document).ready(function () {
        correct_summary_report();
        //$("select").select2();
    })
    function open_selected_department_details(finance_department_name,finance_department_id){
       var start_date= $('#start_date').val();
       var end_date= $('#end_date').val();
       document.getElementById('progress_bar').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
       $.ajax({
           type:'POST',
           url:'ajax_open_selected_department_details.php',
           data:{finance_department_id:finance_department_id,start_date:start_date,end_date:end_date,finance_department_name:finance_department_name},
           success:function(data){
               $("#selected_department_div").html(data);
               $("#selected_department_div").dialog({
                        title: finance_department_name+'~~>Department Collection',
                        width: '90%',
                        height: 550,
                        modal: true,
                    }); 
                    $("#progress_bar").html("");
           }
       }); 
    }
    function open_pharmathetical_detail(finance_department_id,finance_department_name){
       var start_date= $('#start_date').val();
       var end_date= $('#end_date').val();
       document.getElementById('selected_dept_progess_div').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
       $.ajax({
           type:'POST',
           url:'ajax_open_pharmathetical_detail.php',
           data:{finance_department_id:finance_department_id,start_date:start_date,end_date:end_date},
           success:function(data){
               $("#open_category_detail_div").html(data);
               $("#open_category_detail_div").dialog({
                        title: finance_department_name+'~~>PHARMACY ~~>Department Collection',
                        width: '90%',
                        height: 550,
                        modal: true,
                    });
                    $("#selected_dept_progess_div").html("")
           },error:function(x,y,z){
               console.log(x+y+z);
           }
       }); 
    }
    function open_laboratory_detail(finance_department_id,finance_department_name){
       var start_date= $('#start_date').val();
       var end_date= $('#end_date').val();
       document.getElementById('selected_dept_progess_div').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
       $.ajax({
           type:'POST',
           url:'ajax_open_laboratory_detail.php',
           data:{finance_department_id:finance_department_id,start_date:start_date,end_date:end_date},
           success:function(data){
               $("#open_category_detail_div").html(data);
               $("#open_category_detail_div").dialog({
                        title: finance_department_name+'~~>LABORATORY ~~>Department Collection',
                        width: '90%',
                        height: 550,
                        modal: true,
                    });
                    $("#selected_dept_progess_div").html("")
           },error:function(x,y,z){
               console.log(x+y+z);
           }
       }); 
    }
    function open_radiology_detail(finance_department_id,finance_department_name){
       var start_date= $('#start_date').val();
       var end_date= $('#end_date').val();
       document.getElementById('selected_dept_progess_div').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
       $.ajax({
           type:'POST',
           url:'ajax_open_radiology_detail.php',
           data:{finance_department_id:finance_department_id,start_date:start_date,end_date:end_date},
           success:function(data){
               $("#open_category_detail_div").html(data);
               $("#open_category_detail_div").dialog({
                        title: finance_department_name+'~~>RADIOLOGY ~~>Department Collection',
                        width: '90%',
                        height: 550,
                        modal: true,
                    });
                    $("#selected_dept_progess_div").html("")
           },error:function(x,y,z){
               console.log(x+y+z);
           }
       }); 
    }
    function open_all_other_category_detail(finance_department_id,finance_department_name,Item_report_category){
       var start_date= $('#start_date').val();
       var end_date= $('#end_date').val();
       document.getElementById('selected_dept_progess_div').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
       $.ajax({
           type:'POST',
           url:'ajax_open_all_other_category_detail.php',
           data:{finance_department_id:finance_department_id,start_date:start_date,end_date:end_date,Item_report_category:Item_report_category},
           success:function(data){
               $("#open_category_detail_div").html(data);
               $("#open_category_detail_div").dialog({
                        title: finance_department_name+'~~>'+Item_report_category+' ~~>Department Collection',
                        width: '90%',
                        height: 550,
                        modal: true,
                    });
                    $("#selected_dept_progess_div").html("")
           },error:function(x,y,z){
               console.log(x+y+z);
           }
       }); 
    }
    //open_department_detail_div
    function open_selected_pharmacy_detail(Sub_Department_ID,finance_department_id,finance_department_name,Sub_Department_Name){
         var start_date= $('#start_date').val();
       var end_date= $('#end_date').val();
       document.getElementById('selected_dept_progess_div').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
       $.ajax({
           type:'POST',
           url:'ajax_open_selected_pharmacy_detail.php',
           data:{finance_department_id:finance_department_id,start_date:start_date,end_date:end_date,Sub_Department_ID:Sub_Department_ID},
           success:function(data){
               $("#open_department_detail_div").html(data);
               $("#open_department_detail_div").dialog({
                        title: finance_department_name+'~~>'+Sub_Department_Name+' ~~>Department Collection',
                        width: '90%',
                        height: 550,
                        modal: true,
                    });
                    $("#selected_dept_progess_div").html("")
           },error:function(x,y,z){
               console.log(x+y+z);
           }
       });  
    }
    function open_selected_laboratory_detail(Item_Subcategory_ID,finance_department_id,finance_department_name,Item_Subcategory_Name){
         var start_date= $('#start_date').val();
       var end_date= $('#end_date').val();
       document.getElementById('selected_dept_progess_div').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
       $.ajax({
           type:'POST',
           url:'ajax_open_selected_laboratory_detail.php',
           data:{finance_department_id:finance_department_id,start_date:start_date,end_date:end_date,Item_Subcategory_ID:Item_Subcategory_ID},
           success:function(data){
               $("#open_department_detail_div").html(data);
               $("#open_department_detail_div").dialog({
                        title: finance_department_name+'~~>'+Item_Subcategory_Name+' ~~>Department Collection',
                        width: '90%',
                        height: 550,
                        modal: true,
                    });
                    $("#selected_dept_progess_div").html("")
           },error:function(x,y,z){
               console.log(x+y+z);
           }
       });  
    }
    function open_radiology_laboratory_detail(Item_Subcategory_ID,finance_department_id,finance_department_name,Item_Subcategory_Name){
         var start_date= $('#start_date').val();
       var end_date= $('#end_date').val();
       document.getElementById('selected_dept_progess_div').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
       $.ajax({
           type:'POST',
           url:'ajax_open_radiology_laboratory_detail.php',
           data:{finance_department_id:finance_department_id,start_date:start_date,end_date:end_date,Item_Subcategory_ID:Item_Subcategory_ID},
           success:function(data){
               $("#open_department_detail_div").html(data);
               $("#open_department_detail_div").dialog({
                        title: finance_department_name+'~~>'+Item_Subcategory_Name+' ~~>Department Collection',
                        width: '90%',
                        height: 550,
                        modal: true,
                    });
                    $("#selected_dept_progess_div").html("")
           },error:function(x,y,z){
               console.log(x+y+z);
           }
       });  
    }
    
      function previewrevenuecollection(){
                 var start_date= $('#start_date').val();
                 var end_date= $('#end_date').val();
           
		window.open('preview_revenue_collection_summary.php?start_date='+start_date+'&end_date='+end_date, '_blank');
	}
        //*****************************************************************new function ---*gkcchief*---**********************************
        function open_department_detail(Department_ID,finance_department_id,finance_department_name,Department_Name){
            var start_date= $('#start_date').val();
            var end_date= $('#end_date').val();
            document.getElementById('selected_normal_dept_progess_div').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            $.ajax({
                type:'POST',
                url:'ajax_open_department_detail.php',
                data:{finance_department_id:finance_department_id,start_date:start_date,end_date:end_date,Department_ID:Department_ID,finance_department_name:finance_department_name,Department_Name:Department_Name},
                success:function(data){
                    $("#open_department_detail_div").html(data);
                    $("#open_department_detail_div").dialog({
                             title: finance_department_name+'~~>'+Department_Name+' ~~>Department Collection',
                             width: '90%',
                             height: 550,
                             modal: true,
                         });
                         $("#selected_normal_dept_progess_div").html("")
                },error:function(x,y,z){
                    console.log(x+y+z);
                }
            });
        }
        function open_sub_department_detail(Department_ID,finance_department_id,Sub_Department_ID,finance_department_name,Department_Name,Sub_Department_Name){
            var start_date= $('#start_date').val();
            var end_date= $('#end_date').val();
            document.getElementById('selected_sub_dept_progess_div').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            $.ajax({
                type:'POST',
                url:'ajax_open_sub_department_detail.php',
                data:{finance_department_id:finance_department_id,start_date:start_date,end_date:end_date,Department_ID:Department_ID,Sub_Department_ID:Sub_Department_ID,finance_department_name:finance_department_name,Department_Name:Department_Name,Sub_Department_Name:Sub_Department_Name},
                success:function(data){
                    $("#open_sub_department_detail_div").html(data);
                    $("#open_sub_department_detail_div").dialog({
                             title: finance_department_name+'~~>'+Department_Name+' ~~>'+Sub_Department_Name+' ~~> Collection',
                             width: '90%',
                             height: 550,
                             modal: true,
                         });
                         $("#selected_sub_dept_progess_div").html("")
                },error:function(x,y,z){
                    console.log(x+y+z);
                }
            });
        }
        function open_category_detail(Item_Category_ID,Department_ID,finance_department_id,Sub_Department_ID,finance_department_name,Department_Name,Sub_Department_Name,Item_Category_Name){
            var start_date= $('#start_date').val();
            var end_date= $('#end_date').val();
            document.getElementById('selected_category_dept_progess_div').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            $.ajax({
                type:'POST',
                url:'ajax_open_category_detail.php',
                data:{finance_department_id:finance_department_id,start_date:start_date,end_date:end_date,Department_ID:Department_ID,Sub_Department_ID:Sub_Department_ID,finance_department_name:finance_department_name,Department_Name:Department_Name,Sub_Department_Name:Sub_Department_Name,Item_Category_ID:Item_Category_ID,Item_Category_Name:Item_Category_Name},
                success:function(data){
                    $("#open_category_detail_div").html(data);
                    $("#open_category_detail_div").dialog({
                             title: finance_department_name+'~~>'+Department_Name+' ~~>'+Sub_Department_Name+' ~~>'+Item_Category_Name+ '~~> Collection',
                             width: '90%',
                             height: 550,
                             modal: true,
                         });
                         $("#selected_category_dept_progess_div").html("")
                },error:function(x,y,z){
                    console.log(x+y+z);
                }
            });
        }
        function open_sub_category_detail(Item_Subcategory_ID,Department_ID,finance_department_id,Sub_Department_ID,finance_department_name,Department_Name,Sub_Department_Name,Item_Subcategory_Name,Item_Category_Name){
            var start_date= $('#start_date').val();
            var end_date= $('#end_date').val();
            document.getElementById('selected_sub_category_dept_progess_div').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            $.ajax({
                type:'POST',
                url:'ajax_open_sub_category_detaill.php',
                data:{finance_department_id:finance_department_id,start_date:start_date,end_date:end_date,Department_ID:Department_ID,Sub_Department_ID:Sub_Department_ID,finance_department_name:finance_department_name,Department_Name:Department_Name,Sub_Department_Name:Sub_Department_Name,Item_Subcategory_ID:Item_Subcategory_ID,Item_Category_Name:Item_Category_Name,Item_Subcategory_Name:Item_Subcategory_Name},
                success:function(data){
                    $("#open_sub_category_detail_div").html(data);
                    $("#open_sub_category_detail_div").dialog({
                             title: finance_department_name+'~~>'+Department_Name+' ~~>'+Sub_Department_Name+'~~>'+Item_Category_Name+'~~>'+Item_Subcategory_Name+ '~~> Collection',
                             width: '90%',
                             height: 550,
                             modal: true,
                         });
                         $("#selected_sub_category_dept_progess_div").html("")
                },error:function(x,y,z){
                    console.log(x+y+z);
                }
            });
        }
        function open_item_detail(Item_ID,Item_Subcategory_ID,Department_ID,finance_department_id,Sub_Department_ID,finance_department_name,Department_Name,Sub_Department_Name,Item_Subcategory_Name,Product_Name,Item_Category_Name){
            var start_date= $('#start_date').val();
            var end_date= $('#end_date').val();
            document.getElementById('selected_item_progess_div').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            $.ajax({
                type:'POST',
                url:'ajax_open_item_detaill.php',
                data:{finance_department_id:finance_department_id,start_date:start_date,end_date:end_date,Department_ID:Department_ID,Sub_Department_ID:Sub_Department_ID,finance_department_name:finance_department_name,Department_Name:Department_Name,Sub_Department_Name:Sub_Department_Name,Item_Subcategory_ID:Item_Subcategory_ID,Item_ID:Item_ID},
                success:function(data){
                    $("#open_item_detail_div").html(data);
                    $("#open_item_detail_div").dialog({
                             title: finance_department_name+'~~>'+Department_Name+' ~~>'+Item_Category_Name+'~~>'+Sub_Department_Name+' ~~>'+Item_Subcategory_Name+'~~>'+Product_Name+ '~~> Collection',
                             width: '90%',
                             height: 550,
                             modal: true,
                         });
                         $("#selected_item_progess_div").html("")
                },error:function(x,y,z){
                    console.log(x+y+z);
                }
            });
        }
</script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<?php
include("./includes/footer.php");
?>