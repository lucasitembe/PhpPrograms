
<?php
include("./includes/header.php");
include("./includes/connection.php");
$requisit_officer = $_SESSION['userinfo']['Employee_Name'];

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
//    if (isset($_SESSION['userinfo']['Admission_Works'])) {
//        if ($_SESSION['userinfo']['Admission_Works'] != 'yes') {
//            header("Location: ./index.php?InvalidPrivilege=yes");
//        }
//    } else {
//        header("Location: ./index.php?InvalidPrivilege=yes");
//    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}


$nav='';
$divStyle='style="height: 110px;overflow-y: auto;overflow-x: hidden;background-color:white"';

if(isset($_GET['discharged'])){
   $nav='&discharged=discharged';
   $divStyle='style="height: 280px;overflow-y: auto;overflow-x: hidden;background-color:white"';
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Admission_Works'] == 'yes') {

      $backlink = $_SERVER['HTTP_REFERER'];
        ?>
        <a href="nursecommunicationpage.php?<?php echo $_SERVER['QUERY_STRING'] ?>" alt="" class='art-button-green'>
            BACK
        </a>
    <?php }
}
?>


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
<?php
if (isset($_POST['submitRadilogyform'])) {
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
       
     // assign variable into database tbl_testing_record
    $Days = mysqli_real_escape_string($conn,$_POST['Days']);
    $date = mysqli_real_escape_string($conn,$_POST['date']);
    $type = mysqli_real_escape_string($conn,$_POST['type']);
    $meal = mysqli_real_escape_string($conn,$_POST['meal']);
    $notes = mysqli_real_escape_string($conn,$_POST['notes']);
    $registration_ID = mysqli_real_escape_string($conn,$_POST['registration_ID']);
    $consultation_ID = mysqli_real_escape_string($conn,$_POST['consultation_ID']);
    $Glucose = mysqli_real_escape_string($conn,$_POST['Glucose']);

    /// insert data into tbl_testing_record

    $insert_testing_record = "
		INSERT INTO 
			tbl_testing_record(Registration_ID,consultation_ID,employee_ID,Days,date,type,meal,notes,Glucose)
				VALUES('$registration_ID','$consultation_ID','$Employee_ID',DAYNAME(NOW()),NOW(),'$type','$meal','$notes','$Glucose')";

    if (!mysqli_query($conn,$insert_testing_record)) {
        die(mysqli_error($conn));
    }
    ?>
<script>
   alert("SAVED SUCCESSIFULLY");
   window.location="nursecommunication_bloodtest.php?<?php echo $_SERVER['QUERY_STRING']?>";
</script>
<?php
  
}

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
<fieldset>
    <legend align="center" style='padding:10px;color:white;background-color:#2D8AAF;text-align:center'><b>
            <b>BLOOD GLUCOSE TESTING RECORD</b><br/>
            <?php echo "<b>" . $Patient_Name . "</b>  | " . $Gender . "</b> | <b>" . $age . " years</b> | <b>" . $Sponsor . "</b>"; ?></b>
    </legend>
    
    <?php if(empty($nav)){?>
    <center>
        <table width="100%" class="hiv_table" > 

            <tr>
                <td colspan="4" width="100%"><hr></td>
            </tr>
            <tr>
                <td colspan="4">


                    <form action="#" method="POST"  name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
                        <center>
                            <table style="width:100%" class="hiv_table"  >

                                <tr>
                                    <td style="text-align:right;font-size:16px" width="13%"><b>Day</b></td>
                                    <td>
                                        <input type='text' readonly='readonly' value='<?php echo date('l'); ?>' disabled="disabled" />
                                        <input type='hidden' readonly='readonly' name='Days' value='<?php echo date('l'); ?>' />
                                    </td>
                                    <td style="text-align:right;font-size:16px" width="13%"><b>Date and Time</b></td>
                                    <td>
                                        <input type="text" value="&nbsp;&nbsp;&nbsp;&nbsp;<?php echo date('d-m-y h:i:s'); ?>" disabled="disabled" />
                                        <input name='date' type="hidden" value="<?php echo date('d-m-y h:i:s'); ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;font-size:16px" width="13%"><b>Type</b></td>
                                    <td>
                                        <select class="list_style" name="type" required="required">
                                            <option value="">Select Type</option>
                                            <option value="Type 1">Type 1</option>
                                            <option value="Type 2">Type 2</option>
                                        </select>
                                    </td> 
                                    <td style="text-align:right;font-size:16px" width="13%"><b>Meal</b></td>
                                    <td>
                                        <select class="list_style" name='meal' required="required" >
                                            <option value="">Select From List</option>
                                            <option value="Morning">Morning</option>
                                            <option value="Lunch Time">Lunch Time</option>
                                            <option value="Bed Time">Bed Time</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;font-size:16px" width="13%" required="required"><b>Glucose</b></td>
                                    <td  ><input name="Glucose" type="text"  required="required"/></td>
                                    <td style="text-align:right;font-size:16px " width="13%" ><b>Notes</b></td>
                                    <td ><textarea name="notes" type="text" required="required"></textarea></td>
                                </tr>
                                <tr>		


                                    <td style='text-align: center;' colspan="4">
                                        <br/>
                                        <input type='hidden' name="registration_ID" value='<?php echo $_GET['Registration_ID']; ?>'/>
                                        <input type='hidden' name="consultation_ID" value='<?php echo $_GET['consultation_ID']; ?>'/>
                                        <input type='submit' name='submitRadilogyform' id='submit' value='ADD' onclick="return confirm('Are you sure you want to save infos?')" class='art-button-green' style="width:10%">
                                        <input type='hidden' name='submitRadilogyform' value='true'>
                                    </td>
                                </tr>
                            </table> 
                        </center>
                    </form>


                </td>
            </tr>

        </table>               
    </center>
    <?php }?>
</fieldset> 
<br/>
<fieldset>
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
<div id="Search_Iframe" <?php echo $divStyle ?>>
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