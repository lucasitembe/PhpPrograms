<?php
include("./includes/functions.php");
//include("./includes/laboratory_specimen_collection_header.php");
include("./includes/header.php");
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

//subcategory

$query2 = mysqli_query($conn,"SELECT Item_Subcategory_ID, Item_Subcategory_Name FROM tbl_item_subcategory WHERE Item_Subcategory_ID IN(SELECT Item_Subcategory_ID FROM tbl_items WHERE Consultation_Type='Radiology' GROUP BY Item_Subcategory_ID) ORDER BY Item_Subcategory_Name") or die(mysqli_error($conn));
$dataSubCategory = '';
$dataSubCategory.='<option value="All">All Subcategory</option>';

while ($row = mysqli_fetch_array($query2)) {
    $dataSubCategory.= '<option value="' . $row['Item_Subcategory_ID'] . '">' . $row['Item_Subcategory_Name'] . '</option>';
}
?>
<a href="radiologyreports.php" class="art-button-green">BACK</a>
<style>
    .daterange{
        background-color: rgb(3, 125, 176);
        color: white;
        display: block;
        width: 99.2%;
        padding: 4px;
        font-family: times;
        font-size: large;
        font-weight: bold;
    }
</style> 
<fieldset style='margin-top:15px;'>
    <legend align="right" style="text-align:right;background-color:#006400;color:white;padding:5px;"><b>RADIOLOGY INVESTIGATION REPORT</b></legend>

    <center>
        <table  class="hiv_table" style="width:100%;margin-top:5px;">
            <tr> 
                <td style="width: 20px;text-align:center ">
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="date_From" placeholder="Start Date"/>
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="date_To" placeholder="End Date"/>&nbsp;
                  
				<select name="Employee_ID" style='text-align: center;width:15%;display:inline;padding:4px' id="Employee_ID">
					<option selected="selected" value="All">All Employee</option>
					<?php
						$select_details = mysqli_query($conn,"SELECT Employee_ID, Employee_Name FROM tbl_employee where	Employee_ID IN(SELECT Consultant_ID FROM tbl_item_list_cache WHERE Check_In_Type = 'Radiology') ORDER  BY Employee_Name") or die(mysqli_error($conn));
		    				$num = mysqli_num_rows($select_details);
		    				if($num > 0){
		    					while ($data = mysqli_fetch_assoc($select_details)) {
					?>
									<option value="<?php echo $data['Employee_ID']; ?>"><?php echo ucwords(strtolower($data['Employee_Name'])); ?></option>

					<?php 		}
							}
					?>
				</select>
				<select name="Employee_ID_done" style='text-align: center;width:15%;display:inline;padding:4px' id="Employee_ID_done">
					<option selected="selected" value="All">Employee Done</option>
					<?php
						$select_details = mysqli_query($conn,"SELECT Employee_ID, Employee_Name FROM tbl_employee where	Employee_ID IN(SELECT Consultant_ID FROM tbl_item_list_cache WHERE Check_In_Type = 'Radiology') ORDER  BY Employee_Name") or die(mysqli_error($conn));
		    				$num = mysqli_num_rows($select_details);
		    				if($num > 0){
		    					while ($data = mysqli_fetch_assoc($select_details)) {
					?>
									<option value="<?php echo $data['Employee_ID']; ?>"><?php echo ucwords(strtolower($data['Employee_Name'])); ?></option>

					<?php 		}
							}
					?>
				</select>
			
                    <select id="sponsorID" style='text-align: center;padding:4px; width:15%;display:inline'>
                        <?php echo $dataSponsor ?>
                    </select>
                    <select id="subCatID" style='text-align: center;padding:4px; width:15%;display:inline'>
                        <?php echo $dataSubCategory ?>
                    </select>
                    <select id="test_done_process" style='text-align: center;padding:4px; width:15%;display:inline'>
                        <option value="All">All Done &  Not Done</option>
                        <option value="done">Done</option>
                        <option value="not_done">Note Done</option>
                    </select>
                    <input type="button" value="Filter" class="art-button-green" onclick="filterLabpatient()">
                </td>

            </tr> 
        </table>
    </center>
    <center>
        <table  class="box box-primary" style="width:100%">
            <tr>
                <td>
                    <div style="width:100%; height:500px;overflow-x: hidden;overflow-y: auto" class='box box-primary' id="Search_Iframe">
                        <?php include 'rad_invest_report_frame.php'; ?> 
                    </div>
                </td>
            </tr>
        </table>

    </center>
</fieldset>
<center> 
    <a href="rad_invest_report_print.php" target="_blank" id="printPreview" class="art-button-green">PRINT REPORT PDF</a>
    <a href="rad_invest_report_print_excell.php" target="_blank" id="printPreview_Excell" class="art-button-green">PRINT REPORT EXCELL</a>
</center> 

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
                        $('#date_From').datetimepicker({value: '', step: 30});
                        $('#date_To').datetimepicker({
                            dayOfWeekStart: 1,
                            lang: 'en',
                            startDate: 'now'
                        });
                        $('#date_To').datetimepicker({value: '', step: 30});

                        function filterLabpatient() {
                            var fromDate = document.getElementById('date_From').value;// $('#date_From').val();
                            var toDate = document.getElementById('date_To').value;//$('#date_To').val();
                            var Sponsor = document.getElementById('sponsorID').value;
                            var Employee_ID = document.getElementById('Employee_ID').value;
                            var Employee_ID_done = document.getElementById('Employee_ID_done').value;
                            var SubCategory = document.getElementById('subCatID').value;
                            var test_done_process = document.getElementById('test_done_process').value;


                            if (fromDate == '' || toDate == '') {
                                alert('Please enter both dates to filter');
                                exit;
                            }

                            $('#printPreview').attr('href', 'rad_invest_report_print.php?fromDate=' + fromDate + '&toDate=' + toDate + '&Sponsor=' + Sponsor + '&SubCategory=' + SubCategory+'&test_done_process='+test_done_process+'&Employee_ID='+Employee_ID+'&Employee_ID_done='+Employee_ID_done);


                            $('#printPreview_Excell').attr('href', 'rad_invest_report_print_excell.php?fromDate=' + fromDate + '&toDate=' + toDate + '&Sponsor=' + Sponsor + '&SubCategory=' + SubCategory+'&test_done_process='+test_done_process+'&Employee_ID='+Employee_ID+'&Employee_ID_done='+Employee_ID_done);

                            $.ajax({
                                type: 'GET',
                                url: 'rad_invest_report_frame.php',
                                data: 'action=getItem&fromDate=' + fromDate + '&toDate=' + toDate + '&Sponsor=' + Sponsor + '&SubCategory=' + SubCategory+'&test_done_process='+test_done_process+'&Employee_ID='+Employee_ID+'&Employee_ID_done='+Employee_ID_done,
                                beforeSend: function (xhr) {
                                    document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
                                },
                                success: function (html) {
                                    if (html != '') {
                                       
                                        $('#Search_Iframe').html(html);
                                         $('.display').dataTable({
                                            "bJQueryUI": true
                                        });
                                    }
                                }

                            });

                        }


</script>

<script>
    $(document).ready(function () {
        //$.fn.dataTableExt.sErrMode = 'throw';
        $('.display').dataTable({
            "bJQueryUI": true
        });
         $('select').select2();
    });
</script>

