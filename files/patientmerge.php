<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Patient_Record_Works'])){
	    if($_SESSION['userinfo']['Patient_Record_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    //section to help back buttons
    if(isset($_GET['section'])){
	$section = $_GET['section'];
    }else{
	$section = '';
    }
    $Current_Username = $_SESSION['userinfo']['Given_Username'];
     
    $sql_check_prevalage="SELECT Edit_Patient_Information FROM tbl_privileges WHERE Edit_Patient_Information='yes' AND "
            . "Given_Username='$Current_Username'";
    
    $sql_check_prevalage_result=mysqli_query($conn,$sql_check_prevalage);
    if(!mysqli_num_rows($sql_check_prevalage_result)>0){
        ?>
                    <script>
                        var privalege= alert("You don't have the privelage to access this button")
                            document.location="./patientrecords.php?InvalidPrivilege=yes";
                    </script>
                    <?php
    }
?>

<style>
#Search_Iframe{
    overflow-y: scroll;
    height: 650px;
}
</style>
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Patient_Record_Works'] == 'yes'){ 
?>
    <a href='#?SearchListPatientBilling=SearchListPatientBillingThisPage' class='art-button-green'>
        INPATIENT LIST
    </a>
<?php  } } ?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Patient_Record_Works'] == 'yes'){ 
?>
    <a href='patientrecords.php?PatientRecords=PatientRecordsThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>


<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        
        $age = $Today - $original_Date; 
    }
?>

		<br>
        <?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        
        $age = $Today - $original_Date; 
    }
?>

<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>

<script language="javascript" type="text/javascript">
        function searchPatient(){
            var Patient_Name = document.getElementById("Search_Patient").value;
            var Patient_Number = document.getElementById("Patient_Number").value;
            var Phone_Number = document.getElementById("Phone_Number").value;
        document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        $.ajax({
            type:'GET',
            url:'Patientfile_Merge_List_Iframe.php',
            data:{Patient_Name:Patient_Name,Patient_Number:Patient_Number,Phone_Number:Phone_Number},
            success:function(data){
                $("#Search_Iframe").html(data);
            }
        });
    }
     $(document).ready(function () {
        searchPatient();
    });
</script>

<br/>
<center>
    <table width=100%>
        <tr>
            <td width=30%>
                <input type='text' name='Search_Patient' id='Search_Patient' style='text-align: center;' oninput='searchPatient()' placeholder='Search Patient Name'>
            </td>
            <td width=30%>
                <input type='text' name='Patient_Number' id='Patient_Number' style='text-align: center;' oninput='searchPatient()' placeholder='Search Patient Number'>
            </td>
            <td width=30%>
                <input type='text' name='Phone_Number' id='Phone_Number' style='text-align: center;' oninput='searchPatient()' placeholder='Search Old Patient Number'>
            </td>
        </tr>


    </table>
    <div id="louder">
</center>
<fieldset>  
            <legend align=center><b>PATIENTS TO MERGE LIST</b></legend>
        <center>
            <table width=100% border=1>
                <tr>
            <td id='Search_Iframe'>
		<!-- <iframe width='100%' height=400px src='search_engineers_list_Iframe.php'></iframe> -->
            </td>
        </tr>
            </table>
        </center>
</fieldset>
<script type="text/javascript" src="js/afya_card.js"></script>
<?php
    include("./includes/footer.php");
?>
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="script.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="script.responsive.js"></script>
<link rel="stylesheet" href="css/dialog/zebra_dialog.css" media="screen">
<script src="js/zebra_dialog.js"></script>
<script src="js/ehms_zebra_dialog.js"></script>

<div id="Patient_Merge_files">
    <input type='text' id='last_patient_number' value='' readonly='readonly'>
    <input type='text'id='new_patient_number' placeholder='Fill the Merged Patient Number'><br/>
    <input type='button'id='merge' value='MERGE FILES' class='art-button-green' onclick='merge_datas()'>
</div>

<div id="display">
    Patient File Successfully merged
    <input type='button'id='merge' value='CLOSE' class='art-button-green' onclick='close_dialog_2()'>
</div>


<script>
   $(document).ready(function(){
      $("#Patient_Merge_files").dialog({ autoOpen: false, width:"35%",height:150, title:'MERGING PATIENT FILES',modal: true});
      $("#display").dialog({ autoOpen: false, width:"35%",height:140, title:'MERGING PATIENT FILES',modal: true});
   });
</script>


<script>
function pop_merge(Registration_ID) {       
    $("#Patient_Merge_files").dialog("open");
    document.getElementById("last_patient_number").value = Registration_ID;
}
function close_dialog() {       
    $("#Patient_Merge_files").dialog("close");
    // document.getElementById("display").style.display = "none";
}
function close_dialog_2() {       
    $("#Patient_Merge_files").dialog("close");
    $("#display").dialog("close");
    document.getElementById("louder").style.display = "none";
}

function merge_datas() {       
    var old_Registration_ID = document.getElementById("last_patient_number").value;
    var new_Registration_ID = document.getElementById("new_patient_number").value;

    if(new_Registration_ID == ''){
        alert("PLEASE FILL ANOTHER FILE NUMBER")
    }else{
        // alert("IENDELEE")
        if(confirm("ARE YOU SURE YOU WANT TO MERGE")){
            document.getElementById('louder').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        $.ajax({
                type:'post',
                url: 'merge_patient_file.php',
                data : {
                    old_Registration_ID:old_Registration_ID,
                    new_Registration_ID:new_Registration_ID
               },
               success : function(data){
                   if(data=='Successfull merged'){
                    $("#display").dialog("open");
                   }else{
                       alert(data);
                       document.getElementById("louder").style.display = "none";
                   }
                   var new_Registration_ID = document.getElementById("new_patient_number").value='';
                   close_dialog();
               }
           });
        }
    }
}
</script>
</script>
