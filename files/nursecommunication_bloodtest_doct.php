<?php
include("./includes/header.php");
include("./includes/connection.php");

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

    $backlink = $_SERVER['HTTP_REFERER'];
?>
<a href="doctorspageinpatientwork.php?<?php echo $_SERVER['QUERY_STRING'] ?>" alt="" class='art-button-green'>
    BACK
</a>


<?php
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}

if ($Registration_ID != 0) {
    $select_patien_details = mysqli_query($conn,"
		SELECT Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
			FROM 
				tbl_patient_registration pr, 
				tbl_sponsor sp
			WHERE 
				pr.Registration_ID = '$Registration_ID' AND
				sp.Sponsor_ID = pr.Sponsor_ID
				") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_patien_details);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_patien_details)) {
            $Member_Number = $row['Member_Number'];
            $Patient_Name = $row['Patient_Name'];
            $Registration_ID = $row['Registration_ID'];
            $Gender = $row['Gender'];
            $Sponsor = $row['Guarantor_Name'];
            $DOB = $row['Date_Of_Birth'];
        }
    } else {
        $Member_Number = '';
        $Patient_Name = '';
        $Gender = '';
        $Registration_ID = 0;
    }
} else {
    $Member_Number = '';
    $Patient_Name = '';
    $Gender = '';
    $Registration_ID = 0;
}

$age = date_diff(date_create($DOB), date_create('today'))->y;
?>
<style>
    select,input{
        padding:5px;
        font-size:18px;
        width:100%; 
    }

    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;

    }	

</style>
<br/>
<fieldset>
    <legend align="center" style='padding:10px;color:white;background-color:#2D8AAF;text-align:center'><b>
            <b>BLOOD GLUCOSE TESTING RECORD</b><br/>
            <?php echo "<b>" . $Patient_Name . "</b>  | " . $Gender . "</b> | <b>" . $age . " years</b> | <b>" . $Sponsor . "</b>"; ?></b>
    </legend>
   
    <center>
        <table width='100%' >
            <tr>
                <td>    
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' readonly id="start_date" placeholder="Start Date"/>
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' readonly id="end_date" placeholder="End Date"/>&nbsp;
                    <input type="button" value="Filter" style='text-align: center;width:15%;' class="art-button-green" onclick="filterPatient()">
                    <a href="nursecommunication_bloodtest_print.php?Registration_ID=<?php echo $_GET['Registration_ID']?>&consultation_ID=<?php echo $_GET['consultation_ID']?>" id="printPreview" class="art-button-green" target="_blank" style="float:right">Preview</a>
                </td>
            </tr>
        </table>
    </center>    
</fieldset>
<br/>
<div id="Search_Iframe" style="height: 380px;overflow-y: auto;overflow-x: hidden;background-color:white ">
    <?php include 'nursecommunication_bloodtest_Iframe.php'; ?>
</div>

<script>
    function filterPatient() {
        var start = document.getElementById('start_date').value;
        var end = document.getElementById('end_date').value;
        var Registration_ID = '<?php echo $_GET['Registration_ID']; ?>';
        var consultation_ID = '<?php echo $_GET['consultation_ID']; ?>';
        
        if(start =='' || end==''){
            alert("Please enter both dates");
            exit;
        }
        
         $('#printPreview').attr('href', 'nursecommunication_bloodtest_print.php?start=' + start + '&end=' + end + '&Registration_ID=' + Registration_ID + '&consultation_ID=' + consultation_ID);

         document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
       
        
         $.ajax({
            type: "GET",
            url: "nursecommunication_bloodtest_Iframe.php",
            data: 'start=' + start + '&end=' + end + '&Registration_ID=' + Registration_ID+ '&consultation_ID=' + consultation_ID,
            
            success: function (data) {
              if(data != ''){
               $('#Search_Iframe').html(data);
               $.fn.dataTableExt.sErrMode = 'throw';
                $('#bloodtest').DataTable({
                    "bJQueryUI": true,
                    "bFilter": false,
                    "sPaginationType": "fully_numbers",
                    "sDom": 't'

                });
              }
            }
        });
    }
</script>

<script src="css/jquery.datetimepicker.js"></script>
<script>
    $(document).ready(function () {
        $.fn.dataTableExt.sErrMode = 'throw';
        $('#bloodtest').DataTable({
            "bJQueryUI": true,
            "bFilter": false,
            "sPaginationType": "fully_numbers",
            "sDom": 't'

        });


        $('#start_date').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            startDate: 'now'
        });
        $('#start_date').datetimepicker({value: '', step: 30});

        $('#end_date').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            startDate: 'now'
        });
        $('#end_date').datetimepicker({value: '', step: 30});
    });

</script>

<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>

<?php
include("./includes/footer.php");
?>