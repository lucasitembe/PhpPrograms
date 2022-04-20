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

$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	
    }

$age = date_diff(date_create($DOB), date_create('today'))->y;
?>
<?php
if (isset($_POST['submitRadilogyform'])) {
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
       
        	// assign variable into database tbl_testing_record
	//$Days=mysqli_real_escape_string($conn,$_POST['Days']);
	$temp=mysqli_real_escape_string($conn,$_POST['temp']);
	$pr=mysqli_real_escape_string($conn,$_POST['pr']);
	$resp=mysqli_real_escape_string($conn,$_POST['resp']);
	$so=mysqli_real_escape_string($conn,$_POST['so']);
	$bwt=mysqli_real_escape_string($conn,$_POST['bwt']);
        $feedamt=mysqli_real_escape_string($conn,$_POST['feedamt']);
	$feedleftincup=mysqli_real_escape_string($conn,$_POST['feedleftincup']);
	$orallytaken=mysqli_real_escape_string($conn,$_POST['orallytaken']);
	$amtTakenByngt=mysqli_real_escape_string($conn,$_POST['amtTakenByngt']);
	$amtVommited=mysqli_real_escape_string($conn,$_POST['amtVommited']);
        $diarrhoea=mysqli_real_escape_string($conn,$_POST['diarrhoea']);
	$remarks=mysqli_real_escape_string($conn,$_POST['remarks']);
        
	
        $Registration_ID = mysqli_real_escape_string($conn,$_POST['registration_ID']);
        $consultation_ID = mysqli_real_escape_string($conn,$_POST['consultation_ID']);
	
        $insert_testing_record = "INSERT INTO tbl_mulnutrition_observation(Registration_ID,consultation_ID,employee_ID,date_time,temp,Pr,Resp,So,daily_bwt,feed_amount,cup_Amount,oral_taken,ngt_taken,vomitted_mls,diarrhoea,Remarks)
                                    VALUES('$Registration_ID','$consultation_ID','$Employee_ID',NOW(),'$temp','$pr','$resp','$so','$bwt','$feedamt','$feedleftincup','$orallytaken','$amtTakenByngt','$amtVommited','$diarrhoea','$remarks')";
	
	 if(!mysqli_query($conn,$insert_testing_record)){
            die(mysqli_error($conn));
         }
         
    ?>
<script>
   alert("SAVED SUCCESSIFULLY");
   window.location="nursecommunication_mulnutrition.php?<?php echo $_SERVER['QUERY_STRING']?>";
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
            <b>MALNUTRITION OBSERVATION CHART</b><br/>
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
                                    <td style="text-align:right;font-size:14px" width="13%"><b>Date and Time</b></td>
                                    <td>
                                        <input type="text" name="date" class="input_style" readonly value='<?php echo $original_Date; ?>' />
                                    </td>
                                    <td style="text-align:right;font-size:14px" width="13%"><b>Temp(c)</b></td>
                                    <td>
                                      <input type="text" name="temp" id="temp" class="input_style"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;font-size:14px" width="13%"><b>Pr</b></td>
                                    <td>
                                        <input type="text" name="pr" id="pr" class="input_style" />
                                    </td> 
                                    <td style="text-align:right;font-size:14px" width="13%"><b>Resp</b></td>
                                    <td>
                                        <input type="text" name="resp" id="resp" class="input_style"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;font-size:14px" width="13%" required="required"><b>So2</b></td>
                                    <td  ><input type="text" name="so" id="so" class="input_style"/></td>
                                
                                    <td style="text-align:right;font-size:14px " width="13%" ><b>Daily BWT</b></td>
                                    <td ><input type="text" name="bwt" id="bwt" class="input_style"  /></td>
                                </tr>
                                 <tr>
                                    <td style="text-align:right;font-size:14px" width="13%" required="required"><b>AMT of feeds/mls</b></td>
                                    <td  ><input type="text" name="feedamt" id="feedamt" class="input_style"  /></td>
                                
                                    <td style="text-align:right;font-size:14px " width="13%" ><b>AMT left in the cup</b></td>
                                    <td ><input type="text" name="feedleftincup" id="feedleftincup" class="input_style"  /></td>
                                </tr>
                                 <tr>
                                    <td style="text-align:right;font-size:14px" width="13%" required="required"><b>AMT taken orally/mls</b></td>
                                    <td  ><input type="text" name="orallytaken" id="orallytaken" class="input_style"  /></td>
                                
                                    <td style="text-align:right;font-size:14px " width="13%" ><b>AMT taken by ngt/mls</b></td>
                                    <td ><input type="text" name="amtTakenByngt" id="amtTakenByngt" class="input_style"  /></td>
                                </tr>
                                 <tr>
                                    <td style="text-align:right;font-size:14px" width="13%" required="required"><b>EST. vommited mls</b></td>
                                    <td  ><input type="text" name="amtVommited" id="amtVommited" class="input_style"  /></td>
                                
                                    <td style="text-align:right;font-size:14px " width="13%" ><b>Diarrhoea if present</b></td>
                                    <td >
                                        <select class="input_style" name="diarrhoea" id="diarrhoea">
                                            <option value="No"> No </option>
                                            <option value="Yes">Yes</option>
                                        </select>
                                    </td>
                                </tr>
                                 <tr>
                                    <td style="text-align:right;font-size:14px" width="13%" required="required"><b>Remarks</b></td>
                                    <td  ><textarea name="remarks" id="remarks" class="input_style" style="height: 20px" ></textarea></td>
                                
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
        <table width='100%'>
            <tr>
                <td>    
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' readonly id="start_date" placeholder="Start Date"/>
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' readonly id="end_date" placeholder="End Date"/>&nbsp;
                    <input type="button" value="Filter" style='text-align: center;width:15%;' class="art-button-green" onclick="filterPatient()">
                    <a href="nursecommunication_mulnutrition_print.php?Registration_ID=<?php echo $_GET['Registration_ID']?>&consultation_ID=<?php echo $_GET['consultation_ID']?>" id="printPreview" class="art-button-green" target="_blank" style="float:right">Preview</a>
             
                </td>
            </tr>
        </table>
    </center>    
</fieldset>
<br/>
<div id="Search_Iframe" <?php echo $divStyle ?>>
    <?php include 'mulnutrition_observation_Iframe.php'; ?>
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
        
         $('#printPreview').attr('href', 'nursecommunication_mulnutrition_print.php?start=' + start + '&end=' + end + '&Registration_ID=' + Registration_ID + '&consultation_ID=' + consultation_ID);

        
         document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
       
        
         $.ajax({
            type: "GET",
            url: "mulnutrition_observation_Iframe.php",
            data: 'start=' + start + '&end=' + end + '&Registration_ID=' + Registration_ID+ '&consultation_ID=' + consultation_ID,
            
            success: function (data) {
              if(data != ''){
               $('#Search_Iframe').html(data);
               $.fn.dataTableExt.sErrMode = 'throw';
                $('#nurse_mulnitrution').DataTable({
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
        $('#nurse_mulnitrution').DataTable({
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