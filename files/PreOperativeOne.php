<?php
    include("./includes/header.php");
    include("./includes/connection.php");
	$temp = 1;
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Reception_Works'])){
	    if($_SESSION['userinfo']['Reception_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>

	<a href='index.php?Appointment=AppointmentThisPage'  class='art-button-green'>
        BACK
	</a>
	
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>


<center>
    <table width=70%><tr><td>
        <center>
            <fieldset>
                <legend align="center" ></legend>
                    <table>
                        <tr>
			    <td style='text-align: center; height: 40px; width: 10%;'>
				<a href='searchPatients.php?Patients=PatientsThisPage'>
				    <button style='width: 100%; height: 90%;'>
					<b>Pre-Operative Check List</b>
				    </button>
				</a>
			    </td> 
			</tr> 
                    </table>
            </fieldset>
        </center></td></tr></table>
</center>
	
<?php
include("./includes/footer.php");
?>