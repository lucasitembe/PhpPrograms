<?php
include("./includes/laboratory_result_header.php");

$query = mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
$dataSponsor = '';
$dataSponsor.='<option value="All">All Sponsors</option>';

while ($row = mysqli_fetch_array($query)) {
    $dataSponsor.= '<option value="' . $row['Sponsor_ID'] . '">' . $row['Guarantor_Name'] . '</option>';
}
?>
<fieldset style="margin-top:20px;">
    <legend id="resultsconsultationLablist" style="font-weight: bold;font-size: 16px;background-color: #006400; padding:10px; color:white;width: auto"><b>LAB RESULTS - CONSULTED PATIENTS</b>  </legend>
    <script language="javascript" type="text/javascript">
    </script>


    <center>
        <table  class="hiv_table" style="width:100%;margin-top:5px;">
            <tr> 
                <td style="width: 20px;text-align:center ">
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="Date_From" placeholder="Start Date"/>
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="Date_To" placeholder="End Date"/>&nbsp;
                    <select id="sponsorID" style='text-align: center;padding:4px; width:15%;display:inline'>
                        <?php echo $dataSponsor ?>
                    </select>
                    <input type="button" value="Filter" class="art-button-green" onclick="filterLabpatient()">
                    <input type="text" autocomplete="off" id="searchbarcode" style='text-align: center;width:35%;display:inline' placeholder="Search BarCode"  oninput="searchbarcode(this.value)" /></td>

<!--                                                            <td style="text-align:right;width:10px"><b>Date From<b></td>
       <td width="70px"><input type='text' name='Date_From' id='date_From' required="required"></td>
       <td style="text-align:right;width:10px"><b>Date To<b></td>
       <td width="70px"><input type='text' name='Date_To' id='date_To' required="required"></td>
       <td width="30px"><input type="submit" name="submit" value="Filter" class="art-button-green" /></td>-->
            </tr> 
        </table>
    </center>


    <hr width="100%">
    <center>
        <table  class="hiv_table" style="width:100%">
            <tr>
                <td>
                    <div id='Search_Iframe' style="width:100%;height:420px;overflow-x: hidden;overflow-y: auto">
                        <?php include 'getConsultedPatientfromspeciemenlist.php'; ?>
                    </div>
                </td>
            </tr>
        </table>
    </center>
</fieldset>


<div id="labResults" style="display: none">
    <div align="center" style="display: none" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>

    <div id="showLabResultsHere"></div>

</div>


<div id="labGeneral" style="display: none">
    <div align="center" style="display: none" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>

    <div id="showGeneral"></div>

</div>
<div id="historyResults1" style="display:none">


</div>
<br/>
<?php
include("./includes/footer.php");
?>
<link rel="stylesheet" href="css/uploadfile.css" media="screen">
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<link rel="stylesheet" href="js/fancybox/jquery.fancybox.css" type="text/css" media="screen" />


<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script src="css/jquery-ui.js"></script>
<script src="css/scripts.js"></script>
<script src="js/jquery.form.js"></script>
<script type="text/javascript" src="js/fancybox/jquery.fancybox.pack.js"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script>
    function filterLabpatient() {
        var Date_From = document.getElementById('Date_From').value;
        var Date_To = document.getElementById('Date_To').value;
        var Sponsor = document.getElementById('sponsorID').value;

        if (Date_From == '' || Date_To == '') {
            alert('Please enter both dates to filter');
            exit;
        }

        document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }
        mm.onreadystatechange = AJAXP; //specify name of function that will handle server response....
        mm.open('GET', 'getConsultedPatientfromspeciemenlist.php?filterlabpatientdate=true&Date_From=' + Date_From + '&Date_To=' + Date_To + '&Sponsor=' + Sponsor, true);
        mm.send();

    }
    function AJAXP() {
        var data = mm.responseText;
        if (mm.readyState == 4 && mm.status == 200) {
            document.getElementById('Search_Iframe').innerHTML = data;
            $.fn.dataTableExt.sErrMode = 'throw';
            $('#resultsPatientList').DataTable({
                "bJQueryUI": true

            });
        }

    }

</script> 
<script>
    $('#Date_From').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:    'now'
    });
    $('#Date_From').datetimepicker({value: '', step: 30});
    $('#Date_To').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:'now'
    });
    $('#Date_To').datetimepicker({value: '', step: 30});
//    
</script>
<script>
    function searchbarcode(value) {
        $.ajax({
            type: 'POST',
            url: 'getConsultedPatientfromspeciemenlist.php',
            data: 'action=barcode&value=' + value,
            beforeSend: function (xhr) {
                document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

            },
            success: function (html) {
                if (html != '') {
                    $('#Search_Iframe').html(html);
                    $.fn.dataTableExt.sErrMode = 'throw';
                    $('#resultsPatientList').DataTable({
                        "bJQueryUI": true

                    });
                }
            }
        });
    }
</script>
<script>
                        $(document).ready(function () {
                            $('.fancybox').fancybox();
                            $('#resultsPatientList').DataTable({
                                "bJQueryUI": true
                            });
                        });
</script>
<!--End datetimepicker-->
