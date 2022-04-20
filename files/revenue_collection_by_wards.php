<?php
include("includes/connection.php");
include("includes/header.php");

$display = "<option selected='selected' value='All' selected='selected'>All Sponsors</option>";
$select = mysqli_query($conn,"SELECT Guarantor_Name, Sponsor_ID FROM tbl_sponsor ORDER BY Guarantor_Name AND active_sponsor = 'active'") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);
if($num > 0){
    while ($listssss = mysqli_fetch_array($select)) {
        $display .= "<option value=".$listssss['Sponsor_ID'].">".$listssss['Guarantor_Name']."</option>";

    }
}

$Select_ward = mysqli_query($conn, "SELECT Hospital_Ward_Name, Hospital_Ward_ID FROM tbl_hospital_ward WHERE ward_status = 'active' ORDER BY Hospital_Ward_Name ASC ") or die(mysqli_error($conn));
    if(mysqli_num_rows($Select_ward)>0){
$data = "<option selected='selected' value='All' selected='selected'>All Wards</option>";
        while($dt = mysqli_fetch_assoc($Select_ward)){
            $Hospital_Ward_Name  = $dt['Hospital_Ward_Name'];
            $Hospital_Ward_ID  = $dt['Hospital_Ward_ID'];

        $data .= "<option value=".$Hospital_Ward_ID.">".$Hospital_Ward_Name."</option>";

        }
    }
?>
<a href="generalledgercenter.php?GeneralLedger" class='art-button-green'>BACK</a>

<br>
<br>


<center>
<fieldset width=''>
    <table  class="table table-collapse" style="border-collapse: collapse;border:1px solid black; width: 100%">
        <tr>
            <td>
                <input type='text' name='Date_From' title='Incase You want to filter by Date, Fill these Date fields' placeholder='Start Date' id='date_From' style="text-align: center">    
            </td>
            <td>
                <input type='text' name='Date_To' title='Incase You want to filter by Date, Fill these Date fields' placeholder='End Date' id='date_To' style="text-align: center">
            </td> 
            <td>
                    <select name='Sponsor_ID' id='Sponsor_ID' onchange='filter_list()'  style='text-align: center;width:100%;display:inline'>
                        <?= $display ?>
                    </select>
                </td>
                <td width= '10%'>
                    <select name="Hospital_Ward_ID" id="Hospital_Ward_ID"  onchange='filter_list()' style='width: 100%'>
                        <?php
                            echo $data;
                        ?>
                    </select>
                </td>
                <td width='23%'>
                    <input type='submit' name='Print_Filter' id='Print_Filter' onclick='filter_list()' class='art-button-green' value='FILTER'>
                
                <!-- <input type='button' name='Print_Filter' id='Print_List' class='art-button-green' value='PRIVIEW &amp; PRINT'> -->
                <!-- <input type='button'  onclick='check_assign()' class='art-button-green' value='PREPARE LIST'> -->
                </td>
        </tr>
    </table>

</fieldset>
</center>
<fieldset>  
    <legend align=center><b>REVENUE COLLECTION BY WARDS</b></legend>
    <div class="box box-primary" style="height: 540px;overflow-y: scroll;overflow-x: hidden">
        <table class="table table-collapse table-striped " style="border-collapse: collapse !important">
            <tr style='background: #dedede; position: static !important;'>
                <th style='width: 5%;'>S/No</th>
                <th style='text-align: left;'>HOSPITAL WARD</th>
                <th style='text-align: right;'>CASH</th>
                <th style='text-align: right;'>CREDIT</th>
                <th style='text-align: right;'>MSAMAHA</th>
                <th style='text-align: right;'>SUB-TOTAL</th>
            </tr>
            <tbody id='Search_Iframe'>
        </table>
    </div>
</fieldset>

</div>

<link rel="stylesheet" href="css/uploadfile.css" media="screen">
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<link rel="stylesheet" href="js/fancybox/jquery.fancybox.css" type="text/css" media="screen" />   
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script><script src="css/jquery.datetimepicker.js"></script>
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script src="css/jquery-ui.js"></script>
<script src="css/scripts.js"></script>
<script src="js/jquery.form.js"></script>
<script type="text/javascript" src="js/fancybox/jquery.fancybox.pack.js"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<link rel="stylesheet" href="css/ui.notify.css" media="screen">
<script src="js/jquery.notify.min.js"></script> 
<script src="js/select2.min.js"></script>

<script language="javascript" type="text/javascript">
    $(document).ready(function(){
            filter_list();
	});
    function filter_list(){
		Sponsor_ID = $('#Sponsor_ID').val();
		Date_From = $('#date_From').val();
		Date_To = $('#date_To').val();
		Hospital_Ward_ID = $('#Hospital_Ward_ID').val();

        // alert(Hospital_Ward_ID);
        document.getElementById('Search_Iframe').innerHTML = '<center><div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div></center>';

        // $('#Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        if (window.XMLHttpRequest) {
            myObjectPost = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPost = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPost.overrideMimeType('text/xml');
        }
        myObjectPost.onreadystatechange = function () {
            dataPost = myObjectPost.responseText;
            if (myObjectPost.readyState == 4) {
                document.getElementById('Search_Iframe').innerHTML = dataPost;
                // $("#Submit_data").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectPost.open('GET', 'ajax_revenue_collection_by_wards.php?Hospital_Ward_ID='+Hospital_Ward_ID+'&Date_To='+Date_To+'&Date_From='+Date_From+'&Sponsor_ID='+Sponsor_ID, true);
        myObjectPost.send();

    }
</script>

<script>
    $('#date_From').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#date_From').datetimepicker({value: '', step: 1});
    $('#date_To').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#date_To').datetimepicker({value: '', step: 1});
    
         $('#date_Fromx').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#date_Fromx').datetimepicker({value: '', step: 1});

    $(document).ready(function (e){
        $("#Sponsor_ID").select2();
        $("#Hospital_Ward_ID").select2();
    });
</script>

<?php
    include("./includes/footer.php");
?>