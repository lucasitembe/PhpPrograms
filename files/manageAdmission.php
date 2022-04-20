<?php
include("./includes/connection.php");
include("./includes/header.php");
$controlforminput = '';
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo']['Setup_And_Configuration'])) {
    if ($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes') {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}


    if(isset($_GET['frompage']) && $_GET['frompage'] == "addmission") {
?>
<a href='admissionconfiguration.php?AdmisionWorks=AdmisionWorksThisPage&frompage=addmission' class='art-button-green'>
    <b>BACK</b>
</a>

<?php
    } else {
?>
<a href='admissionconfiguration.php?AdmisionWorks=AdmisionWorksThisPage' class='art-button-green'>
    <b>BACK</b>
</a>

<?php
    }
?>

<br/>
<br/>
<?php

    if(isset($_SESSION['userinfo']['Branch_ID'])){
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }else{
        $Branch_ID = 0;
    }
	
	
	 $query = mysqli_query($conn,"SELECT Can_admit_before_discharge FROM tbl_system_configuration WHERE Branch_ID='$Branch_ID'");
	 $row=mysqli_fetch_assoc($query);
	 $Can_admit_before_discharge=$row['Can_admit_before_discharge'];
        

?>
<center>
    <center>
        <fieldset>

            <legend align="center" ><b>Manage Patient admission</b></legend>
            <div id="Search_Iframe" style="height: 500px;overflow-y: auto;overflow-x: hidden">
                <table width="60%" style="background-color:white" >
                    
                        <tr>
                            <td>Allow patient admission before discharge</td>
							
							<td> 
							<?php
							 if($Can_admit_before_discharge=='yes'){
								 echo "<input type='checkbox' id='patient_discharge' name='patient_discharge' checked='true'>";
							 }else{
								echo "<input type='checkbox' id='patient_discharge' name='patient_discharge'>";
						 
							 }
							
							?>
								
							</td>
							<td><input type="button" onclick="manageAdmission()" value="Save Changes" id="SaveUpdates" class="art-button-green"></td>
                        </tr>
                    

                    
                </table>
            </div>


        </fieldset>
    </center>
</center>

<script type="text/javascript">
   function manageAdmission(){
	   
	  if(confirm('Are you sure you want to save changes?')){
	  var tt;
	  var Branch_ID='<?php echo $Branch_ID;?>';
	  if($('#patient_discharge').is(':checked')){
		  tt='yes';
	  }else{
		  tt='no';
	  }
	  
	  $.ajax({
           type: 'POST',
           url: 'save_admission_set.php',
		   data: 'action=save&tt='+tt+'&Branch_ID='+Branch_ID,
           cache: false,
           success: function (html) {
               
               alert(html);
           }
       });
	   }
   }
</script>

<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<?php
include("./includes/footer.php");
?>