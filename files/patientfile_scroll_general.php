
<style>
    
    .userinfo td,tr{
        height:20px ;
        border:none !important; 
    }
    .userinfo tr{
        border:none !important;
    }
    .headerTitle{
        background:#ccc;padding:5px;font-size: x-large;font-weight:bold;text-align:left;  
        width:100%;    
    }
    .modificationStats:hover{
        text-decoration: underline;
        cursor:pointer;
        color: rgb(145,0,0);
    }

    .prevHistory:hover{
        text-decoration: underline;
        cursor:pointer;
        color: rgb(145,0,0); 
    }
    .no_color{
        color:inherit;
        text-decoration:none;  
    }
    .headerPage{
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
<?php
include("./includes/header_general.php");
include("./includes/connection.php");
 if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	die( "<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this page yet.Try login again or contact administrator for support!<p>");

    }
    if(isset($_SESSION['userinfo'])){
       if(isset($_SESSION['userinfo']['Patient_Record_Works'])){
	    if($_SESSION['userinfo']['Patient_Record_Works'] != 'yes'){
		die( "<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this resource.Please contact administrator for support!<p>");
	    }
	}else{
	    	die( "<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this page yet.Try login again or contact administrator for support!<p>");

	}
      
    }else{
	@session_destroy();
	   	die( "<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this page yet.Try login again or contact administrator for support!<p>");

    }
//get the current date
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}
//    select patient information
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
    $select_Patient = mysqli_query($conn,"select
                            Old_Registration_Number,Title,Patient_Name,pr.Sponsor_ID,Date_Of_Birth,
                                    Gender,pr.Region,pr.Country,pr.Diseased,pr.District,pr.Ward,pr.Patient_Picture,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID,
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,Claim_Number_Status,
                                                        Registration_ID,sp.Postal_Address,sp.Benefit_Limit
                                      from tbl_patient_registration pr, tbl_sponsor sp
                                        where pr.Sponsor_ID = sp.Sponsor_ID and 
                                        Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Patient);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Patient)) {
            $Registration_ID = $row['Registration_ID'];
            $Old_Registration_Number = $row['Old_Registration_Number'];
            $Title = $row['Title'];
            $Patient_Name = $row['Patient_Name'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $Gender = $row['Gender'];
            $Country = $row['Country'];
            $Patient_Picture = $row['Patient_Picture'];
            $Deseased = ucfirst(strtolower($row['Diseased']));
            $Sponsor_Postal_Address = $row['Postal_Address'];
            $Benefit_Limit = $row['Benefit_Limit'];
            $Region = $row['Region'];
            $District = $row['District'];
            $Ward = $row['Ward'];
            $Guarantor_Name = $row['Guarantor_Name'];
            $Claim_Number_Status = $row['Claim_Number_Status'];
            $Member_Number = $row['Member_Number'];
            $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
            $Phone_Number = $row['Phone_Number'];
            $Email_Address = $row['Email_Address'];
            $Occupation = $row['Occupation'];
            $Employee_Vote_Number = $row['Employee_Vote_Number'];
            $Emergence_Contact_Name = $row['Emergence_Contact_Name'];
            $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
            $Company = $row['Company'];
            $Employee_ID = $row['Employee_ID'];
            $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
            // echo $Ward."  ".$District."  ".$Ward; exit;
        }

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, " . $diff->m . " Months, " . $diff->d . " Days, " . $diff->h . " Hours";
    } else {
        $Registration_ID = '';
        $Old_Registration_Number = '';
        $Title = '';
        $Patient_Name = '';
        $Sponsor_ID = '';
        $Date_Of_Birth = '';
        $Gender = '';
        $Country = '';
        $Deseased = '';
        $Sponsor_Postal_Address = '';
        $Benefit_Limit = '';
        $Patient_Picture = '';
        $Region = '';
        $District = '';
        $Ward = '';
        $Guarantor_Name = '';
        $Claim_Number_Status = '';
        $Member_Number = '';
        $Member_Card_Expire_Date = '';
        $Phone_Number = '';
        $Email_Address = '';
        $Occupation = '';
        $Employee_Vote_Number = '';
        $Emergence_Contact_Name = '';
        $Emergence_Contact_Number = '';
        $Company = '';
        $Employee_ID = '';
        $Registration_Date_And_Time = '';
        $age = 0;
    }
} else {
    $Registration_ID = '';
    $Old_Registration_Number = '';
    $Title = '';
    $Sponsor_ID = '';
    $Date_Of_Birth = '';
    $Gender = '';
    $Country = '';
    $Deseased = '';
    $Sponsor_Postal_Address = '';
    $Benefit_Limit = '';
    $Patient_Picture = '';
    $Region = '';
    $District = '';
    $Ward = '';
    $Guarantor_Name = '';
    $Claim_Number_Status = '';
    $Member_Number = '';
    $Member_Card_Expire_Date = '';
    $Phone_Number = '';
    $Email_Address = '';
    $Occupation = '';
    $Employee_Vote_Number = '';
    $Emergence_Contact_Name = '';
    $Emergence_Contact_Number = '';
    $Company = '';
    $Employee_ID = '';
    $Registration_Date_And_Time = '';
    $age = 0;
}
?>
<a href="Patientfile_Record_Detail_General.php?<?php echo $_SERVER['QUERY_STRING'] ?>" class="art-button-green">BACK</a>
<a href="print_patient_file_scroll.php?Registration_ID=<?php echo $_GET['Registration_ID'] ?>" class="art-button-green">PRINT PATIENT FILE</a>

<!-- get employee id-->
<?php
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}
$sponsoDetails = '';
if (strtolower($Guarantor_Name) != 'cash') {
    $sponsoDetails = ',&nbsp;&nbsp;<b>Address:</b>  ' . $Sponsor_Postal_Address . ' ,&nbsp;&nbsp;<b>Benefit Limit:</b>' . $Benefit_Limit . '';
}


echo '<fieldset style="width:99%;height:460px ;padding:5px;background-color:white;margin-top:20px;overflow-x:hidden;overflow-y:scroll">
    <div style="padding:5px; width:99%;font-size:larger;border:1px solid #000;  background:#ccc;text-align:center  ">
        <b align="center">PATIENT MEDICATION RECORD</b>
    </div>
    <div style="margin:2px;border:1px solid #000">
        <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
            <tr>
                <td style="width:10%;text-align:right "><b>Patient Name:</b></td><td colspan="" style="width:30%;text-align:left ">'. $Patient_Name.'</td>
                <td style="width:10%;text-align:right "><b>Country:</b></td><td colspan="">'. $Country.'</td>
                <td style="width:10%;text-align:right "><b>Region:</b></td><td colspan="">'.$Region.'</td>
                <td rowspan="4" width="100">
                    <img width="120" height="90" name="Patient_Picture" id="Patient_Pictured" src="./patientImages/'. $Patient_Picture.'">
                </td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right"><b>Registration #:</b></td><td>'. $Registration_ID.'</td><td style="text-align:right"><b>Phone #:</b></td><td style="">'. $Phone_Number .'</td><td style="text-align:right"><b>District:</b></td><td style="">'. $District.'</td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right"><b>Date of Birth:</b></td><td style="">'.date("j F, Y", strtotime($Date_Of_Birth)).' </td><td style="text-align:right"><b>Gender:</b></td><td style="">'. $Gender .'</td><td style="text-align:right"><b>Diseased:</b></td><td style="">'. $Deseased .'</td>
            </tr>
            <tr>
                <td style="width:14%;text-align:right"><b>Insurance Details:</b></td><td colspan="2" style="width:100%;text-align:left"> '. $Guarantor_Name .$sponsoDetails.'</td>
            </tr>
        </table>
    </div>';

include 'patientfile_scroll_iframe.php';

echo '
</fieldset>
<div style="margin-top:10px">';
//if ($hasOutpatientDetails) {
//    echo ' <a href="#outpatient" class="art-button-green" style="float:left;font-size:18px ">Go to outpatient details</a>';
//}if ($hasInpatientDetails) {
//    echo '<a href="#inpatient" class="art-button-green" style="float:right;font-size:18px">Go to inpatient details</a>';
//}

echo '</div>

<div id="showdata" style="width:100%;overflow-x:hidden;display:none;overflow-y:scroll">
    <div id="my">
    </div>
</div>
<div id="showdataComm" style="width:100%;overflow-x:hidden;height:520px;display:none;overflow-y:scroll">
    <div id="myComm">
    </div>
</div>
<div id="historyResults1" style="display:none"></div>';
?>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<link rel="stylesheet" href="js/fancybox/jquery.fancybox.css" type="text/css" media="screen" />
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script type="text/javascript" src="js/fancybox/jquery.fancybox.pack.js"></script>

<script>
    //Results modifications
    $('.modificationStats').click(function () {
        $('#labGeneral').fadeOut();
        var parameter = $(this).attr('id');
        var paymentID = $(this).attr('paymentID');
        var parameterName = $(this).attr('paraname');

        // alert('parameter='+parameter+'&paymentID='+paymentID); 
        $("#historyResults1").dialog('option', 'title', parameterName);
        $.ajax({
            type: 'POST',
            url: 'requests/resultModification.php',
            data: 'parameter=' + parameter + '&paymentID=' + paymentID,
            cache: false,
            success: function (html) {
                //alert(html);
                $('#historyResults1').html(html);
                $('#historyResults1').dialog('open');
            }
        });
    });


    $('#historyResults1').on("dialogclose", function ( ) {

        $('#labGeneral').fadeIn(1000);
    });


    $('.prevHistory').click(function () {
        var itemID = $('.productID').val();
        var patientID = $(this).attr('name');
        var parameterID = $(this).attr('id');
        var parameterName = $('.parameterName').val();
        $("#historyResults1").dialog('option', 'title', parameterName);
        var ppil = $(this).attr('ppil');
        //alert('action=history&itemID='+itemID+'&patientID='+patientID+'&parameterID='+parameterID+'&ppil='+ppil);        
        $.ajax({
            type: 'POST',
            url: 'requests/resultModification.php',
            data: 'action=history&itemID=' + itemID + '&patientID=' + patientID + '&parameterID=' + parameterID + '&ppil=' + ppil,
            cache: false,
            success: function (html) {
                $('#historyResults1').html(html);
                $('#historyResults1').dialog('open');
            }
        });



    });

</script>
<script>
    $(document).ready(function () {
        $('.fancybox').fancybox();
        $('.fancyboxRadimg').fancybox();
        $('#historyResults1').dialog({
            autoOpen: false,
            modal: true,
            width: 600,
            minHeight: 400,
            resizable: true,
            draggable: true
        });

        $("#showdata").dialog({autoOpen: false, width: '90%', title: 'PATIENT RADIOLOGY IMAGING', modal: true, position: 'middle'});
        $("#showdataResult").dialog({autoOpen: false, width: '98%', height: 500, title: 'PATIENT RESULT', modal: true, position: 'middle'});
        $("#showdataComm").dialog({autoOpen: false, width: '90%', height: 650, title: 'COMMENT AND DESCRIPTION', modal: true, position: 'center'});

    });
</script>
<script>
    function CloseImage() {
        document.getElementById('imgViewerImg').src = '';
        document.getElementById('imgViewer').style.visibility = 'hidden';
    }

    function zoomIn(imgId, inVal) {
        if (inVal == 'in') {
            var zoomVal = 10;
        } else {
            var zoomVal = -10;
        }
        var Sizevalue = document.getElementById(imgId).style.width;
        Sizevalue = parseInt(Sizevalue) + zoomVal;
        document.getElementById(imgId).style.width = Sizevalue + '%';
    }


</script>
<script >
    function radiologyviewimage(href, itemName) {
        var datastring = href;
        //alert(datastring);
        $("#showdata").dialog("option", "title", "PATIENT RADIOLOGY IMAGING ( " + itemName + " )");
        $.ajax({
            type: 'GET',
            url: 'requests/radiologyviewimage_doctor.php',
            data: datastring,
            success: function (result) {
                $('#my').html(result);
                $("#showdata").dialog("open");
            }, error: function (err, msg, errorThrows) {
                alert(err);
            }
        });


    }
</script>

<script >
    function commentsAndDescription(href, itemName) {
        var datastring = href;
        $("#showdataComm").dialog("option", "title", "COMMENT AND DESCRIPTION ( " + itemName + " )");
        //alert(datastring);
        $.ajax({
            type: 'GET',
            url: 'requests/RadiologyPatientTestsComments_doctor.php',
            data: datastring,
            success: function (result) {
                //alert(result);
                $("#myComm").html(result);
                $("#showdataComm").dialog("open");
            }, error: function (err, msg, errorThrows) {
                alert(err);
            }
        });


    }
</script>
<?php
//include("./includes/footer.php");
?>