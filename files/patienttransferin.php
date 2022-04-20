<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
    	@session_destroy();
    	header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_SESSION['userinfo'])){
    	if(isset($_SESSION['userinfo']['Admission_Works'])){
    	    if($_SESSION['userinfo']['Admission_Works'] != 'yes'){
                header("Location: ./index.php?InvalidPrivilege=yes");
            }else{
                @session_start();
                if(!isset($_SESSION['Admission_Supervisor'])){
                    header("Location:./deptsupervisorauthentication.php?SessionCategory=Admission&InvalidSupervisorAuthentication=yes");
                }
            }
    	}else{
    	    header("Location: ./index.php?InvalidPrivilege=yes");
    	}
    }else{
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
<a href="patienttransferout.php?PatientTransferOut=PatientTransferOutThisPage" class="art-button-green">PATIENT TRANSFER OUT</a>
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
                <select name='Sponsor_ID' id='Sponsor_ID' onchange="filter_Patient2()">
                    <option value="0">All Sponsor</option>
                    <?php
                        $select = mysqli_query($conn,"select Sponsor_ID, Guarantor_Name from tbl_sponsor order by Guarantor_Name") or die(mysqli_error($conn));
                        $num = mysqli_num_rows($select);
                        if($num > 0){
                            while ($data = mysqli_fetch_array($select)) {
                    ?>
                                <option value='<?php echo $data['Sponsor_ID']; ?>'><?php echo strtoupper($data['Guarantor_Name']); ?></option>
                    <?php
                            }
                        }
                    ?>
                </select>
            </td>
            <td style="text-align: right;">Ward Name</td>
            <td>
                <select onchange="filter_Patient2()" name='Ward_id' id="Ward_id">
                    <option value="0">All Ward</option>
                <?php
                    $select = mysqli_query($conn,"select Hospital_Ward_ID, Hospital_Ward_Name from tbl_hospital_ward") or die(mysqli_error($conn));
                    while ($row = mysqli_fetch_array($select)) {
                ?>
                        <option value="<?php echo $row['Hospital_Ward_ID'] ?>"><?php echo ucwords(strtolower($row['Hospital_Ward_Name'])); ?></option>
                <?php 
                    }
                ?>
                </select>
            </td>
            <td width="20%">
                <input type='text' name='P_Name' style='text-align: center;' id='P_Name' oninput="filter_Patient('Patient_Name')" autocomplete="off" placeholder='~~~~ ~~~ Enter Patient Name ~~~ ~~~~'>
            </td>
            <td width="20%">
                <input type='text' name='P_Number' style='text-align: center;' id='P_Number' oninput="filter_Patient('P_Number')" autocomplete="off" placeholder='~~~~ ~~~ Enter Patient Number ~~~ ~~~~'>
            </td>
        </tr>
    </table>
</fieldset>
<fieldset style="overflow-y: scroll; height: 400px; background-color: white;" id="Patient_Area">
    <legend align="left"><b>PATIENT TRANSFER IN</b></legend>
    <table width="100%">        
<?php
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
    
    $select = mysqli_query($conn,"SELECT sp.Guarantor_Name, pr.Gender, pr.Registration_ID, pr.Date_Of_Birth, hw.Hospital_Ward_Name, pr.Patient_Name, hw.Hospital_Ward_Name, a.Admission_Status, a.Admission_Date_Time, a.Admision_ID
                            FROM tbl_hospital_ward hw, tbl_patient_registration pr, tbl_sponsor sp, tbl_admission a, tbl_patient_transfer_details ptd WHERE
                            a.Registration_ID = pr.Registration_ID and
                            ptd.Registration_ID = pr.Registration_ID and
                            a.Admision_ID = ptd.Admision_ID and
                            pr.Sponsor_ID = sp.Sponsor_ID and
                            ptd.Transfer_Status = 'pending' and
                            hw.Hospital_Ward_ID = a.Hospital_Ward_ID and Admission_Status != 'Discharged' order by ptd.Transfer_Detail_ID desc limit 20") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while ($data = mysqli_fetch_array($select)) {

            $date1 = new DateTime($Today);
            $date2 = new DateTime($data['Date_Of_Birth']);
            $diff = $date1->diff($date2);
            $age = $diff->y . " Years, ";
            $age .= $diff->m . " Months, ";
            $age .= $diff->d . " Days";
            if($temp%30 == 0){ echo $Title; }
?>
            <tr id="sss">
                <td><?php echo ++$temp; ?></td>
                <td><?php echo ucwords(strtolower($data['Patient_Name'])); ?></td>
                <td><?php echo $data['Registration_ID']; ?></td>
                <td><?php echo $data['Guarantor_Name']; ?></td>
                <td><?php echo $age; ?></td>
                <td><?php echo $data['Gender']; ?></td>
                <td><?php echo $data['Hospital_Ward_Name']; ?></td>
                <td><?php echo @date("d F Y H:i:s",strtotime($data['Admission_Date_Time'])); ?></td>
                <td width="8%" style="text-align: center;"><input type="button" class="art-button-green" value="RECEIVE PATIENT" onclick="Re_Admit_Patient(<?php echo $data['Registration_ID']; ?>,<?php echo $data['Admision_ID']; ?>)"></td>
            </tr>
<?php
        }
    }
?>
    </table>
</fieldset>

<div id="Patient_Details">

</div>

<div id="Patient_Details_Dialog">
    Are you sure you want to receive selected patient?
    <table width="100%">
        <tr>
            <td id="Button_Area" style="text-align: right;">
                
            </td>
        </tr>
    </table>
</div>

<div id="Patient_Details_Dialog2">
    Are you sure you want to cancel transfer process?
    <table width="100%">
        <tr>
            <td id="Button_Area2" style="text-align: right;"></td>
        </tr>
    </table>
</div>

<script type="text/javascript">
    function Close_Transfer_Dialog2(){
        $("#Patient_Details_Dialog2").dialog("close");
    }
</script>

<script type="text/javascript">
    function Re_Admit_Patient(Registration_ID,Admision_ID){
        if(window.XMLHttpRequest){
            myObjectGetDetails = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectGetDetails = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetDetails.overrideMimeType('text/xml');
        }

        myObjectGetDetails.onreadystatechange = function (){
            dataDetails = myObjectGetDetails.responseText;
            if (myObjectGetDetails.readyState == 4) {
                document.getElementById('Patient_Details').innerHTML = dataDetails;
                $("#Patient_Details").dialog("open");
            }
        }; //specify name of function that will handle server response........
        
        myObjectGetDetails.open('GET','Patient_Transfer_In.php?Registration_ID='+Registration_ID+'&Admision_ID='+Admision_ID,true);
        myObjectGetDetails.send();
    }
</script>

<script type="text/javascript" language="javascript">
    function Get_Ward_Beds(Hospital_Ward_ID) {
        if(Hospital_Ward_ID==null || Hospital_Ward_ID==''){
           alert('Please select hospital ward');
             $('#Hospital_Ward_ID').focus();
            document.getElementById('Hospital_Ward_ID').style.border = '1px solid red';
            document.getElementById('Bed_ID').innerHTML ="<option selected='selected'></option>";
            document.getElementById('bedNumber').innerHTML ='';
           exit;
        }
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }

        mm.onreadystatechange = function(){
           var data = mm.responseText.split('#$%^$##%$&&');
            document.getElementById('Bed_ID').innerHTML = data[0];
            document.getElementById('bedNumber').innerHTML = data[1];
        }; //specify name of function that will handle server response....
        mm.open('GET', 'Get_Ward_Beds.php?Hospital_Ward_ID=' + Hospital_Ward_ID, true);
        mm.send();
    }
</script>

<script type="text/javascript">
    function Cancel_Patient_transfer_Dialog(Registration_ID,Admision_ID){
        document.getElementById("Button_Area2").innerHTML = '<input type="button" value="YES" class="art-button-green" onclick="Cancel_Patient_transfer('+Registration_ID+','+Admision_ID+')"><input type="button" value="CANCEL" class="art-button-green" onclick="Close_Transfer_Dialog2()">';
        $("#Patient_Details_Dialog2").dialog("open");
    }
</script>

<script type="text/javascript">
    function Cancel_Patient_transfer(Registration_ID,Admision_ID){
        if(window.XMLHttpRequest){
            myObjectCancel = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectCancel = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectCancel.overrideMimeType('text/xml');
        }

        myObjectCancel.onreadystatechange = function (){
            dataCanc = myObjectCancel.responseText;
            if (myObjectCancel.readyState == 4) {
                alert("Transfer process cancelled successfully");
                filter_Patient2();
                $("#Patient_Details_Dialog2").dialog("close");
                $("#Patient_Details").dialog("close");
                $("#Patient_Details_Dialog").dialog("close");
            }
        }; //specify name of function that will handle server response........
        
        myObjectCancel.open('GET', 'Cancel_Patient_transfer_In.php?Registration_ID='+Registration_ID+'&Admision_ID='+Admision_ID, true);
        myObjectCancel.send();
    }
</script>

<script type="text/javascript">
    function Create_Patient_transfer_Dialog(Registration_ID,Admision_ID){

        var Bed_ID = document.getElementById("Bed_ID").value;
        var room_id = document.getElementById("room_id").value;

        var Hospital_Ward_ID = document.getElementById("Hospital_Ward_ID").value;
        if(Hospital_Ward_ID != null && Hospital_Ward_ID != '' && Bed_ID != null && Bed_ID != ''){
            document.getElementById("Button_Area").innerHTML = '<input type="button" value="YES" class="art-button-green" onclick="Create_Patient_transfer('+Registration_ID+','+Admision_ID+','+room_id+')"><input type="button" value="CANCEL" class="art-button-green" onclick="Close_Transfer_Dialog()">';
            $("#Patient_Details_Dialog").dialog("open");
        }else{
            if (Hospital_Ward_ID == '' || Hospital_Ward_ID == null) {
                document.getElementById("Hospital_Ward_ID").focus();
                document.getElementById("Hospital_Ward_ID").style = 'border: 3px solid red';
            }
            
            if (Bed_ID == '' || Bed_ID == null) {
                document.getElementById("Bed_ID").focus();
                document.getElementById("Bed_ID").style = 'border: 3px solid red';
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
    function Get_Ward_Room(Hospital_Ward){
       $.ajax({
           type:'GET',
           url:'ward_room_selection_option.php',
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
           type:'GET',
           url:'ward_bed_selection_option_for_transfer.php',
           data:{ward_id:Hospital_Ward_ID,ward_room_id:ward_room_id},
           success:function (data){
               $("#Bed_ID").html(data)
           }
       }); 
   }
   function checkPatientNumber(bed_id){
       var ward_room_id=$("#room_id").val();
       var Bed_ID = $("#Bed_ID option:selected").text();
       var Hospital_Ward_ID=$("#Hospital_Ward_ID").val();
        $.ajax({
           type:'GET',
           url:'Get_bed_patients_number.php',
           data:{bed_id:Bed_ID,ward_room_id:ward_room_id,Hospital_Ward_ID:Hospital_Ward_ID},
           success:function (data){
               if(data>0){
                    alert('There are already '+data+' patient(s) in this bed.Please Make sure to discharge those patient to continue');
                    $("#Bed_ID").html("<option></option>");
                    var ward_room=$("#room_id").val(); 
                    get_ward_room_bed(ward_room)
                }
           }
       }); 
   }
    function Create_Patient_transfer(Registration_ID,Admision_ID,room_id){
        
        var Bed_ID = $("#Bed_ID option:selected").text();
        var Hospital_Ward_ID = document.getElementById("Hospital_Ward_ID").value;
        
        if(window.XMLHttpRequest){
            myObjectTrans = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectTrans = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectTrans.overrideMimeType('text/xml');
        }

        myObjectTrans.onreadystatechange = function (){
            dataTran = myObjectTrans.responseText;
            if (myObjectTrans.readyState == 4) {
                alert("Transfer process completed successfully");
                filter_Patient2();
                $("#Patient_Details").dialog("close");
                $("#Patient_Details_Dialog").dialog("close");
            }
        }; //specify name of function that will handle server response........
        
        myObjectTrans.open('GET', 'Create_Patient_transfer_In.php?Registration_ID='+Registration_ID+'&Admision_ID='+Admision_ID+'&Hospital_Ward_ID='+Hospital_Ward_ID+'&Bed_ID='+Bed_ID+'&room_id='+room_id, true);
        myObjectTrans.send();
    }
</script>

<script type="text/javascript">
    function filter_Patient(parameter){
        var Patient_Name = document.getElementById("P_Name").value;
        var Patient_Number = document.getElementById("P_Number").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Ward_id = document.getElementById("Ward_id").value;

        if(parameter == 'Patient_Name'){
            document.getElementById("P_Number").value = '';
        }else{
            document.getElementById("P_Name").value = '';
            document.getElementById("Sponsor_ID").value = 0;
        }

        if(window.XMLHttpRequest){
            myObjectSearch = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectSearch = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearch.overrideMimeType('text/xml');
        }

        myObjectSearch.onreadystatechange = function (){
            dataSearch = myObjectSearch.responseText;
            if (myObjectSearch.readyState == 4) {
                document.getElementById("Patient_Area").innerHTML = dataSearch;
            }
        }; //specify name of function that will handle server response........
        
        myObjectSearch.open('GET', 'Patient_Transfer_In_Search_Patient.php?Patient_Number='+Patient_Number+'&Patient_Name='+Patient_Name+'&Sponsor_ID='+Sponsor_ID+'&Ward_id='+Ward_id, true);
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
            myObjectSearch = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearch.overrideMimeType('text/xml');
        }

        myObjectSearch.onreadystatechange = function (){
            dataSearch = myObjectSearch.responseText;
            if (myObjectSearch.readyState == 4) {
                document.getElementById("Patient_Area").innerHTML = dataSearch;
            }
        }; //specify name of function that will handle server response........
        
        myObjectSearch.open('GET', 'Patient_Transfer_In_Search_Patient.php?Patient_Number='+Patient_Number+'&Patient_Name='+Patient_Name+'&Sponsor_ID='+Sponsor_ID+'&Ward_id='+Ward_id, true);
        myObjectSearch.send();
    }
</script>


<script>
function getBeds(){
    var room_id = $("#room_id").val();
    var ward_id = $("#Hospital_Ward_ID").val();

    var data = {
        room_id:room_id,
        ward_id:ward_id,
    }

$.ajax({
    method:"GET",
    url:'get_room_beds.php',
    data:data,
    success:function(response){
        $("#Bed_ID").html(response);
    }
})
}


function getRooms(ward_id){

    var data = {
        ward_id:ward_id,
    }

    $.ajax({
        method:"GET",
        url:"get_ward_rooms.php",
        data:data,
        success:function(response){
            $("#room_id").html(response);
        }
    })
}
</script>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script>
    $(document).ready(function (e){
        $("select").select2();
    });
</script>
<script>
    $(document).ready(function(){
        $("#Patient_Details").dialog({ autoOpen: false, width:'75%',height:220, title:'PATIENT TRANSFER DETAIL',modal: true});
        $("#Patient_Details_Dialog").dialog({ autoOpen: false, width:'40%',height:130, title:'eHMS 2.0',modal: true});
        $("#Patient_Details_Dialog2").dialog({ autoOpen: false, width:'40%',height:130, title:'eHMS 2.0',modal: true});
    });
</script>

<?php
    include("./includes/footer.php");
?>