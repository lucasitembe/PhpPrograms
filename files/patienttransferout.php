<?php 
include ("./includes/header.php");
include ("./includes/connection.php");
include ("functions/object.functions.php");

$Wards = json_decode(SelectActiveGeneralWards($conn, 'ordinary_ward'), true);
// print_r($Wards);

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Admission_Works'])) {
        if ($_SESSION['userinfo']['Admission_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        } else {
            @session_start();
            if (!isset($_SESSION['Admission_Supervisor'])) {
                header("Location:./deptsupervisorauthentication.php?SessionCategory=Admission&InvalidSupervisorAuthentication=yes");
            }
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
};
echo '<a href="patienttransferin.php?PatientTransferIn=PatientTransferInThisPage" class="art-button-green">PATIENT TRANSFER IN</a>
<a href="wardtransferpage.php?WardTransfer=WardTransferThisPage" class="art-button-green">BACK</a>
<br/><br/>
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    #sss:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>
<fieldset>
    <table width="100%">
        <tr>
            <td width="10%" style="text-align: right;">Sponsor Name</td>
            <td>
                <select name=\'Sponsor_ID\' id=\'Sponsor_ID\' onchange="filter_Patient2()">
                    <option value="0">All Sponsor</option>
                    ';
$select = mysqli_query($conn, "SELECT Sponsor_ID, Guarantor_Name from tbl_sponsor order by Guarantor_Name") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);
if ($num > 0) {
    while ($data = mysqli_fetch_array($select)) {;
        echo '                                <option value=\'';
        echo $data['Sponsor_ID'];;
        echo '\'>';
        echo strtoupper($data['Guarantor_Name']);;
        echo '</option>
                    ';
    }
};
echo '                </select>
            </td>
            <td style="text-align: right;">Ward Name</td>
            <td>
                <select onchange="filter_Patient2()" name=\'Ward_id\' id="Ward_id">
                    <option value="0">All Ward</option>
                ';
                    // if(sizeof($Wards)>0){
                        foreach($Wards as $dts):
                            $Hospital_Ward_ID = $dts['Hospital_Ward_ID'];
                            $Hospital_Ward_Name = $dts['Hospital_Ward_Name'];

                            echo "<option vakue='".$Hospital_Ward_ID."'>".$Hospital_Ward_Name."</option>";
                        endforeach;
                    // }
echo '                </select>
            </td>
            <td width="20%">
                <input type=\'text\' name=\'P_Name\' style=\'text-align: center;\' id=\'P_Name\' oninput="filter_Patient(\'Patient_Name\')" autocomplete="off" placeholder=\'~~~~ ~~~ Enter Patient Name ~~~ ~~~~\'>
            </td>
            <td width="20%">
                <input type=\'text\' name=\'P_Number\' style=\'text-align: center;\' id=\'P_Number\' oninput="filter_Patient(\'P_Number\')" autocomplete="off" placeholder=\'~~~~ ~~~ Enter Patient Number ~~~ ~~~~\'>
            </td>
        </tr>
    </table>
</fieldset>
<fieldset style="overflow-y: scroll; height: 400px; background-color: white;" id="Patient_Area">
    <legend align="left"><b>PATIENT TRANSFER OUT</b></legend>
    <table width="100%">
';
$temp = 0;
$Title = '<tr><td colspan="12"><hr></td></tr>
        <tr>
            <td width="5%"><b>SN</b></td>
            <td width="15%"><b>PATIENT NAME</b></td>
            <td width="7%"><b>PATIENT #</b></td>
            <td width="14%"><b>SPONSOR NAME</b></td>
            <td width="14%"><b>PATIENT AGE</b></td>
            <td width="6%"><b>GENDER</b></td>
            <td width="9%"><b>WARD NAME</b></td>
            <td width="13%"><b>ADMITTED DATE</b></td>
            <td width="8%"><b>ACTION</a></td>
        </tr>
        <tr><td colspan="12"><hr></td></tr>';
    
$select = mysqli_query($conn, "SELECT sp.Guarantor_Name, pr.Gender, pr.Registration_ID, pr.Date_Of_Birth, hw.Hospital_Ward_Name, pr.Patient_Name, hw.Hospital_Ward_Name, a.Admission_Status, a.Admission_Date_Time, a.Admision_ID
                            from tbl_admission a inner join tbl_patient_registration pr on a.Registration_ID = pr.Registration_ID
                            inner join tbl_hospital_ward hw on a.Hospital_Ward_ID = hw.Hospital_Ward_ID
                            inner join tbl_sponsor sp  on pr.Sponsor_ID = sp.Sponsor_ID
                            where
                            a.Admission_Status != 'Discharged' AND
                            a.ward_room_id != 0 AND
                            a.Bed_Name !=''
                            and hw.ward_type = 'ordinary_ward' AND hw.ward_status = 'active'
                            order by a.Admision_ID desc limit 200") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);
if ($num > 0) {
    while ($data = mysqli_fetch_array($select)) {
        $date1 = new DateTime();
        $date2 = new DateTime($data['Date_Of_Birth']);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age.= $diff->m . " Months, ";
        $age.= $diff->d . " Days";
        if ($temp % 30 == 0) {
            echo $Title;
        };
        echo '            <tr id="sss">
                <td>';
        echo ++$temp;;
        echo '</td>
                <td>';
        echo ucwords(strtolower($data['Patient_Name']));;
        echo '</td>
                <td>';
        echo $data['Registration_ID'];;
        echo '</td>
                <td>';
        echo $data['Guarantor_Name'];;
        echo '</td>
                <td>';
        echo $age;;
        echo '</td>
                <td>';
        echo $data['Gender'];;
        echo '</td>
                <td>';
        echo $data['Hospital_Ward_Name'];;
        echo '</td>
                <td>';
        echo @date("d F Y H:i:s", strtotime($data['Admission_Date_Time']));;
        echo '</td>
                <td width="8%" style="text-align: center;"><input type="button" class="art-button-green" value="TRANSFER PATIENT" onclick="Transfer_Patient(';
        echo $data['Registration_ID'];;
        echo ',';
        echo $data['Admision_ID'];;
        echo ')"></td>
            </tr>
';
    }
};
echo '    </table>
</fieldset>

<div id="Patient_Details">

</div>

<div id="Patient_Details_Dialog">
    Are you sure you want to process transfer?
    <table width="100%">
        <tr>
            <td id="Button_Area" style="text-align: right;">

            </td>
        </tr>
    </table>
</div>

<script type="text/javascript">
    function Transfer_Patient(Registration_ID,Admision_ID){
        if(window.XMLHttpRequest){
            myObjectGetDetails = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectGetDetails = new ActiveXObject(\'Micrsoft.XMLHTTP\');
            myObjectGetDetails.overrideMimeType(\'text/xml\');
        }

        myObjectGetDetails.onreadystatechange = function (){
            dataDetails = myObjectGetDetails.responseText;
            if (myObjectGetDetails.readyState == 4) {
                document.getElementById(\'Patient_Details\').innerHTML = dataDetails;
                $("#Patient_Details").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectGetDetails.open(\'GET\',\'Patient_Transfer_Out_Details.php?Registration_ID=\'+Registration_ID+\'&Admision_ID=\'+Admision_ID,true);
        myObjectGetDetails.send();
    }
</script>

<script type="text/javascript" language="javascript">
    function Get_Ward_Beds(Hospital_Ward_ID) {
        if(Hospital_Ward_ID==null || Hospital_Ward_ID==\'\'){
           alert(\'Please select hospital ward\');
             $(\'#Hospital_Ward_ID\').focus();
            document.getElementById(\'Hospital_Ward_ID\').style.border = \'1px solid red\';
            document.getElementById(\'Bed_ID\').innerHTML ="<option selected=\'selected\'></option>";
            document.getElementById(\'bedNumber\').innerHTML =\'\';
           exit;
        }
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm = new ActiveXObject(\'Micrsoft.XMLHTTP\');
            mm.overrideMimeType(\'text/xml\');
        }

        mm.onreadystatechange = function(){
           var data = mm.responseText.split(\'#$%^$##%$&&\');
            document.getElementById(\'Bed_ID\').innerHTML = data[0];
            document.getElementById(\'bedNumber\').innerHTML = data[1];
        }; //specify name of function that will handle server response....
        mm.open(\'GET\', \'Get_Ward_Beds.php?Hospital_Ward_ID=\' + Hospital_Ward_ID, true);
        mm.send();
    }
</script>

<script type="text/javascript">
    function Get_Ward_Room(Hospital_Ward){
       $.ajax({
           type:\'GET\',
           url:\'ward_room_selection_option.php\',
           data:{ward_id:Hospital_Ward},
           success:function (data){
               $("#room_id").html(data)
               $("#Bed_ID").html("")
           }
       });
   }

    function get_ward_room_bed(ward_room_id){
       var Hospital_Ward_ID=$("#Hospital_Ward_ID").val();
        $.ajax({
           type:\'GET\',
           url:\'ward_bed_selection_option_for_transfer.php\',
           data:{ward_id:Hospital_Ward_ID,ward_room_id:ward_room_id},
           success:function (data){
               $("#Bed_ID").html(data)
           }
       });
   }
   function checkPatientNumber(bed_id){
       var ward_room_id=$("#room_id").val();
       var Bed_ID=$("#Bed_ID option:selected").text();
        $.ajax({
           type:\'GET\',
           url:\'Get_bed_patients_number.php\',
           data:{bed_id:Bed_ID,ward_room_id:ward_room_id},
           success:function (data){
               if(data>0){
                    alert(\'There are already \'+data+\' patient(s) in this bed.Please Make sure to discharge those patient to continue\');
                    $("#Bed_ID").html("<option></option>");
                    var ward_room=$("#room_id").val();
                    get_ward_room_bed(ward_room)
                }
           }
       });
   }

    function Create_Patient_transfer_Dialog(Registration_ID,Admision_ID){
        var Bed_ID = document.getElementById("Bed_ID").value;
        var room_id = document.getElementById("room_id").value;
        var reason = document.getElementById("reason").value;
        var Hospital_Ward_ID = document.getElementById("Hospital_Ward_ID").value;
        $("#Bed_ID").css("border","");
        $("#room_id").css("border","");
        $("#Hospital_Ward_ID").css("border","");
        if(Hospital_Ward_ID != null && Hospital_Ward_ID != \'\' && Bed_ID != null && reason !=\'\' && Bed_ID != \'\'&&room_id!=\'\'&&room_id!=null){
            document.getElementById("Button_Area").innerHTML = \'<input type="button" value="YES" class="art-button-green" onclick="Create_Patient_transfer(\'+Registration_ID+\',\'+Admision_ID+\')"><input type="button" value="CANCEL" class="art-button-green" onclick="Close_Transfer_Dialog()">\';
            $("#Patient_Details_Dialog").dialog("open");
        }else{
            if (Hospital_Ward_ID == \'\' || Hospital_Ward_ID == null) {
                document.getElementById("Hospital_Ward_ID").focus();
                document.getElementById("Hospital_Ward_ID").style = \'border: 3px solid red\';
            }
            if (reason == \'\' || reason == null) {
                document.getElementById("reason").focus();
                document.getElementById("reason").style = \'border: 3px solid red\';
            }

            if (Bed_ID == \'\' || Bed_ID == null) {
                document.getElementById("Bed_ID").focus();
                document.getElementById("Bed_ID").style = \'border: 3px solid red\';
            }
            if (room_id == \'\' || room_id == null) {
                document.getElementById("room_id").focus();
                document.getElementById("room_id").style = \'border: 3px solid red\';
            }
        }
    }
</script>

<script type="text/javascript">
    function Close_Transfer_Dialog(){
        $("#Patient_Details_Dialog").dialog("close");
    }
</script>
<script type="text/javascript">
    function Create_Patient_transfer(Registration_ID,Admision_ID){
        var Bed_ID = document.getElementById("Bed_ID").value;
        var room_id = document.getElementById("room_id").value;
        var reason = document.getElementById("reason").value;
        var ward_from_id = document.getElementById("ward_from_id").value;
        var Hospital_Ward_ID = document.getElementById("Hospital_Ward_ID").value;
        if(window.XMLHttpRequest){
            myObjectTrans = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectTrans = new ActiveXObject(\'Micrsoft.XMLHTTP\');
            myObjectTrans.overrideMimeType(\'text/xml\');
        }

        myObjectTrans.onreadystatechange = function (){
            dataDetails = myObjectTrans.responseText;
            if (myObjectTrans.readyState == 4) {
                console.log(dataDetails);
                alert("Transfer processed successfully");
                $("#Patient_Details").dialog("close");
                $("#Patient_Details_Dialog").dialog("close");
            }
        }; //specify name of function that will handle server response........

        myObjectTrans.open(\'GET\', \'Create_Patient_transfer.php?Registration_ID=\'+Registration_ID+\'&Admision_ID=\'+Admision_ID+\'&Hospital_Ward_ID=\'+Hospital_Ward_ID+\'&Bed_ID=\'+Bed_ID+\'&room_id=\'+room_id+\'&reason=\'+reason+\'&ward_from_id=\'+ward_from_id, true);
        myObjectTrans.send();
    }
</script>

<script type="text/javascript">
    function filter_Patient(parameter){
        var Patient_Name = document.getElementById("P_Name").value;
        var Patient_Number = document.getElementById("P_Number").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Ward_id = document.getElementById("Ward_id").value;

        if(parameter == \'Patient_Name\'){
            document.getElementById("P_Number").value = \'\';
        }else{
            document.getElementById("P_Name").value = \'\';
            document.getElementById("Sponsor_ID").value = 0;
        }

        if(window.XMLHttpRequest){
            myObjectSearch = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectSearch = new ActiveXObject(\'Micrsoft.XMLHTTP\');
            myObjectSearch.overrideMimeType(\'text/xml\');
        }

        myObjectSearch.onreadystatechange = function (){
            dataSearch = myObjectSearch.responseText;
            if (myObjectSearch.readyState == 4) {
                document.getElementById("Patient_Area").innerHTML = dataSearch;
            }
        }; //specify name of function that will handle server response........

        myObjectSearch.open(\'GET\', \'Patient_Transfer_Out_Search_Patient.php?Patient_Number=\'+Patient_Number+\'&Patient_Name=\'+Patient_Name+\'&Sponsor_ID=\'+Sponsor_ID+\'&Ward_id=\'+Ward_id, true);
        myObjectSearch.send();
    }
</script>

<script type="text/javascript">
    function filter_Patient2(){
        var Patient_Name = document.getElementById("P_Name").value;
        var Patient_Number = document.getElementById("P_Number").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Ward_id = document.getElementById("Ward_id").value;

        if(window.XMLHttpRequest){
            myObjectSearch = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectSearch = new ActiveXObject(\'Micrsoft.XMLHTTP\');
            myObjectSearch.overrideMimeType(\'text/xml\');
        }

        myObjectSearch.onreadystatechange = function (){
            dataSearch = myObjectSearch.responseText;
            if (myObjectSearch.readyState == 4) {
                document.getElementById("Patient_Area").innerHTML = dataSearch;
            }
        }; //specify name of function that will handle server response........

        myObjectSearch.open(\'GET\', \'Patient_Transfer_Out_Search_Patient.php?Patient_Number=\'+Patient_Number+\'&Patient_Name=\'+Patient_Name+\'&Sponsor_ID=\'+Sponsor_ID+\'&Ward_id=\'+Ward_id, true);
        myObjectSearch.send();
    }
</script>
<script>
    $(document).ready(function (e){
        $("select").select2();
    });
</script>
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">

<script>
    $(document).ready(function(){
        $("#Patient_Details").dialog({ autoOpen: false, width:\'75%\',height:220, title:\'PATIENT TRANSFER DETAIL\',modal: true});
        $("#Patient_Details_Dialog").dialog({ autoOpen: false, width:\'40%\',height:230, title:\'WARD TRANSFER\',modal: true});
    });
</script>

';
include ("./includes/footer.php");
