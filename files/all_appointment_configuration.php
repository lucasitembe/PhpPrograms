<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
//    if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
//	if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
//	    header("Location: ./index.php?InvalidPrivilege=yes");
//	}
//    }else{
//	@session_destroy();
//	header("Location: ../index.php?InvalidPrivilege=yes");
//    }
     
?>
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ 
?>
    <a href='./receptionsetup.php?ReceptionWork=ReceptionWorkThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
<br/><br/><br/><br/><br/><br/>
<fieldset>  
            <legend align=center><b>APPOINTMENT</b></legend>
        <center><table width = 60%>
            <tr>
				<td style='text-align: center; height: 40px; width: 25%;'>
                    <a href='number_of_appointment_to_clinic.php?numberofappointment=numberofappointment'>
                        <button style='width: 100%; height: 100%'>
                            SET NUMBER OF APPOINTMENT TO CLINIC 
                        </button>
                    </a>
                </td>
            </tr>
        </table>
        </center>
</fieldset><br/>

<?php
    include("./includes/footer.php");
?>