<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    $_SESSION['outpatient_nurse_com'] = 'no';
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
	
    if(isset($_SESSION['userinfo'])){
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }else{
        $Branch_ID = 0;
    }

	//to get section
    if(isset($_GET['section'])){
        $section = $_GET['section'];
	}else{
        $section='';
	}
?>
<a href='deptsupervisorauthentication.php?SessionCategory=Admission&ChangeLocationAdmission=ChangeLocationAdmissionThisPage' class='art-button-green'>CHANGE DEPARTMENT</a>
<input type="button" value="BACK" onclick="history.go(-1)" class="art-button-green">
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<br/>
<br/>
<br/>
<fieldset>
    <legend style="width:18%;font-size:13px" align="center"><b>ADMISSION MAIN MENU</b></legend>
        <table width = 50% class="table">
        <tr>
        <td style='text-align: center; height: 40px; width: 50%'>
                        <a href='patient_with_concert_form.php?patient_with_concert_form=patient_with_concert_form&frompage=addmission'> 
                            <button style='width: 50%; height: 100%'>
                                Patient With Online Consent Forms
                            </button>
                        </a>
                    </td> 
        </tr>
        <tr>
        <td style='text-align: center; height: 40px;width: 50%'>
                        <a href='patient_with_concert_form_attached.php?patient_with_concert_form_attached=patient_with_concert_form_attached&frompage=addmission'> 
                            <button style='width: 50%; height: 100%'>
                                Patient With Attached Consent Forms
                            </button>
                        </a>
                    </td> 
   
        </tr>
                            </table>

</fieldset>
<?php
    include("./includes/footer.php");
?>
