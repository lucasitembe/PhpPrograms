<?php
include("./includes/functions.php");
//include("./includes/laboratory_specimen_collection_header.php");
include("./includes/header.php");
$DateGiven = date('Y-m-d');
?>
<?php
//get sub department id
$Sub_Department_ID = '';
if (isset($_SESSION['Laboratory'])) {
    $Sub_Department_Name = $_SESSION['Laboratory'];
    $select_sub_department = mysqli_query($conn,"select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name'");
    while ($row = mysqli_fetch_array($select_sub_department)) {
        $Sub_Department_ID = $row['Sub_Department_ID'];
    }
} else {
    $Sub_Department_ID = '';
}

$query = mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
$dataSponsor = '';
$dataSponsor.='<option value="All">All Sponsors</option>';

while ($row = mysqli_fetch_array($query)) {
    $dataSponsor.= '<option value="' . $row['Sponsor_ID'] . '">' . $row['Guarantor_Name'] . '</option>';
}

//subcategory

$query2 = mysqli_query($conn,"SELECT rnm.Item_ID, Product_Name FROM tbl_template_report_nm rnm, tbl_items i WHERE rnm.Item_ID=i.Item_ID") or die(mysqli_error($conn));
$dataSubCategory = '';
$dataSubCategory.='<option value="All">All Test</option>';

while ($row = mysqli_fetch_array($query2)) {
    $Item_ID = $row['Item_ID'];
    $Product_Name = $row['Product_Name'];
    $dataSubCategory.= "<option value='$Item_ID'>$Product_Name</option>";
}
?>
<input type='button' name='' id='' value='NUCLEAR MEDICINE ITEM' onclick='template_setup()' class='art-button-green' />

<a href="Nuclearmedicineworks.php" class="art-button-green">BACK</a>

<!-- <a href="#" onclick="goBack()"class="art-button-green">BACK</a>
<script>
function goBack() {
    window.history.back();
} -->
</script>
<fieldset style='margin-top:15px;'>
    <legend align="center" style="text-align:center;background-color:#006400;color:white;padding:5px;"><b>TEST  DONE NUCLEAR MEDICINE REPORT</b></legend>

    <center>
        <table  class="hiv_table" style="width:100%;margin-top:5px;">
            <tr> 
                <td style="width: 20px;text-align:center ">
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="date_From" placeholder="Start Date"/>
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="date_To" placeholder="End Date"/>&nbsp;
                    <select id="sponsorID" style='text-align: center;padding:4px; width:20%;display:inline'>
                        <?php echo $dataSponsor ?>
                    </select>
                    <select id="subCatID" style='text-align: center;padding:4px; width:15%;display:inline'>
                        <?php echo $dataSubCategory ?>
                    </select>
                    <select id="Itemstatus" style='text-align: center;padding:4px; width:15%;display:inline'>
                        <option value="All">~~Select Status~~</option>
                        <option value="served">Done</option>
                        <option value="paid">Paid</option>
                        <option value="active">Active</option>
                        <option value="pending">Pending</option>
                        <option value="removed">Removed</option>
                    </select>
                    <input type="button" value="Filter" class="art-button-green" onclick="filterLabpatient()">
                    <input type="button"  id="printPreview" class="art-button-green" value="PRINT REPORT" onclick="Previe_Pdf()">
                </td>

            </tr> 
        </table>
    </center>
    <center>
        <table  class="hiv_table" style="width:100%">
            <tr>
                <td>
                    <div style="width:100%; height:450px;overflow-x: hidden;overflow-y: auto"  id="Search_Iframe">
                        <?php include './nuclear_medicine_report_iframe.php'; ?> 
                    </div>
                </td>
            </tr>
        </table>

    </center>
</fieldset>
<div id="divtemplatesetup"></div>

<br/>
<?php
include("./includes/footer.php");
?>


<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js"></script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script> 

<script>
        $('#date_From').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            startDate: 'now'
        });
        $('#date_From').datetimepicker({value: '', step: 01});
        $('#date_To').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            startDate: 'now'
        });
        $('#date_To').datetimepicker({value: '', step: 01});

        function filterLabpatient() {
            var fromDate = document.getElementById('date_From').value;
            var toDate = document.getElementById('date_To').value;
            var Sponsor = document.getElementById('sponsorID').value;
            var Item_ID = document.getElementById('subCatID').value;
            var Itemstatus =document.getElementById('Itemstatus').value;

            if (fromDate == '' || toDate == '') {
                alert('Please enter both dates to filter');
                exit;
            }

            
           
            $.ajax({
                type: 'POST',
                url: 'nuclear_medicine_report_iframe.php',
                data: 'action=getItem&fromDate=' + fromDate + '&toDate=' + toDate + '&Sponsor=' + Sponsor + '&Item_ID=' + Item_ID+'&Itemstatus='+Itemstatus,
                beforeSend: function (xhr) {
                    document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
                },
                success: function (html) {
                    if (html != '') {
                        $('#Search_Iframe').html(html);
                    }
                }

            });

        }
         
    function Previe_Pdf(){
        var fromDate= $('#date_From').val();
        var toDate=$('#date_To').val();
        var Sponsor = $('#sponsorID').val();
        var Item_ID = document.getElementById('subCatID').value;
        var Itemstatus =document.getElementById('Itemstatus').value;
        if(fromDate =='' || toDate==''){
            alert('Please enter both date start and end date');
            exit;
        }
        window.open('nuclear_medicine_reportprint.php?fromDate=' + fromDate + '&toDate=' + toDate + '&Sponsor=' + Sponsor + '&Item_ID=' + Item_ID+'&Itemstatus='+Itemstatus, '_blank');
    };
   

</script>
<script>
function template_setup(){
        $.ajax({
            type:'POST',
            url:'Nm/item_report_setup.php',
            data:{setupreport:''},
            success:function(data){
                $("#divtemplatesetup").dialog({
                    width: '90%',
                    height: 650,
                    modal: true,
                })
                $("#divtemplatesetup").html(data);
                display_selected_setup();
            }
        })
    }

    function ajax_search_setup(){
        var Product_Name = $("#setup_name").val();
        $.ajax({
            type:'POST',
            url:'Nm/item_report_setup.php',
            data:{Product_Name:Product_Name},
            success:function(responce){
                $("#list_of_all_setup").html(responce);
            }
        });
    }
    function save_template_setup(Item_ID){
        
        $.ajax({
            type: 'POST',
            url: 'Nm/item_report_setup.php',
            data:{save_setup:'', Item_ID:Item_ID},
            success:function(responce){
                display_selected_setup()
            }
        });
    }
    function  display_selected_setup(){
        $.ajax({
            type:'POST',
            url:'Nm/item_report_setup.php',
            data:{selected_item:''  },
            success:function(responce){
                $("#list_of_selected_setup").html(responce)
            }
        });
    }

    function remove_template_setup(Procedure_ID){
        $.ajax({
            type:'POST',
            url:'Nm/item_report_setup.php',
            data:{Procedure_ID:Procedure_ID, Remove_item:''},
            success:function(responce){
                display_selected_setup()
            }
        });
    }
    function view_setup_selected(){
        var Registration_ID='<?php echo $Registration_ID; ?>';
        var Payment_Cache_ID='<?= $Payment_Cache_ID ?>';
        $.ajax({
            type:'POST',
            url:'Nm/item_report_setup.php',
            data:{Registration_ID:Registration_ID, Payment_Cache_ID:Payment_Cache_ID},
            success:function(responce){
                $("#proposed_setup").val(responce)
                $("#setup_list").dialog("close")}
        });
    }
    function update_template(Template_ID){
        var protocal =$("#protocal").val();
        var findings = $("#findings").val();
        var procedure_done =$("#procedure_done").val();
        var functions =$("#functions").val();
        var phase = $("#phase").val();
        $.ajax({
            type:'POST',
            url:'Nm/item_report_setup.php',
            data:{protocal:protocal,Template_ID:Template_ID,findings:findings,procedure_done:procedure_done,functions:functions,phase:phase, update_item:''},
            success:function(responce){
                display_selected_setup()
            }
        });
    }
</script>
<script>
    $('select').select2();
    $(document).ready(function () {
        $('.numberTests').dataTable({
            "bJQueryUI": true
        });
    });
</script>


<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<!--End datetimepicker-->