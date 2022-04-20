<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
     
?>
<a href='setupandconfiguration.php?BackToSetupAndConfiguration=BackTosetupAndConfigurationThisPage' class='art-button-green'>BACK</a>

<?php
    //select systemconfiguration based on branch
    if(isset($_SESSION['userinfo']['Branch_ID'])){
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }else{
        $Branch_ID = 0;
    }

    $select_system_configuration = mysqli_query($conn,"select opd_patients_days from tbl_system_configuration where Branch_ID = '$Branch_ID'");
    while($row = mysqli_fetch_array($select_system_configuration)){
        $opd_patients_days = $row['opd_patients_days'];  
    }
	
    ?>


<?php
    if(isset($_POST['update_link'])){
        $opd_patients_days = $_POST['opd_patients_days'];
        
        $update_query = mysqli_query($conn,"update tbl_system_configuration set opd_patients_days = '$opd_patients_days'    
                                        where Branch_ID = '$Branch_ID'") or die(mysqli_error($conn));
         // header("Location: ./receptionconfigurationpage.php?ReceptionConfiguration=ReceptionConfigurationThisPage");
        
    }


?>

<script type="text/javascript">
    //function enable_button(status)
    //{
    //status=!status;
    //document.myForm.update_link.visible = status;
    //}
     
    function ShoElement(){
        document.getElementById("update_link").style.visibility = 'visible';
    }
</script>



<br/><br/><br/><br/><br/><br/>
<fieldset>  
            <legend align=center><b>DOCTOR&CloseCurlyQuote;S PAGE CONFIGURATION</b></legend>
    <form action='#' method='post' name='myForm' id='myForm' name='myForm' enctype="multipart/form-data"> 
        <center><br/>
            <table width = "80%">
                <tr>
                    <td style='text-align: right'>
                        <label for="Reception_Configuration">OPD patients visible days</label>
                    </td>
                    <td style='text-align: left; color:black; border:2px solid #ccc;padding-right:10px'>
                        <input type='text' name='opd_patients_days' id='opd_patients_days' onclick='ShoElement()' onkeypress="ShoElement()"  value='<?php echo $opd_patients_days;?>'>
                    </td>
                    
                     <td colspan="2" style="text-align: left;">
                        <input type='submit' id='update_link' name='update_link' value='Save Changes' class='art-button-green' style='visibility: hidden;'>
                    </td>
                </tr>
            
            </table>
        </center>
    </form>
</fieldset><br/>

<?php
    include("./includes/footer.php");
?>