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

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='setupandconfiguration.php?BackToSetupAndConfiguration=BackTosetupAndConfigurationThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
<br/><br/><br/><br/><br/><br/>

<fieldset>  
    <legend align=center><b>COMPANY CONFIGURATION</b></legend>
    <center><table width = 60%>
        <tr>
            <td style='text-align: center; height: 40px; width: 50%;'>
                <a href='addnewcompany.php?AddNewCompany=AddNewCompanyThisPage'>
                    <button style='width: 100%; height: 100%'>
                        Add New Company
                    </button>
                </a>
            </td>
            <td style='text-align: center; height: 40px; width: 50%;'>
                    <a href='#'>
                        <button style='width: 100%; height: 100%'>
                            Edit Company Name
                        </button>
                    </a>
            </td>
        </tr>
        <tr>
            <td style='text-align: center; height: 40px; width: 100%;' colspan="2">
                <button style='width: 100%; height: 100%' onclick="Enable_Module()">
                    Enable / Disable Splash Index Page
                </button>
            </td>
        </tr> 
        </table>
        </center>
</fieldset>

<?php
    //get last status
    $slct = mysqli_query($conn,"select Enable_Splash_Index from tbl_system_configuration") or die(mysqli_error($conn));
    $num = mysqli_num_rows($slct);
    if($num > 0){
        while ($data = mysqli_fetch_array($slct)) {
            $Module_Status = $data['Enable_Splash_Index'];
        }
    }else{
        $Module_Status = '';
    }
?>
<div id="Modile_Dialog">
    <input type="radio" name="module" id="Enabled_radio" <?php if(strtolower($Module_Status) == 'yes'){ echo "checked='checked'"; } ?>><label for="Enabled_radio">Enable</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <!--<input type="radio" name="module" id="Disabled_radio" <?php if(strtolower($Module_Status) == 'no'){ echo "checked='checked'"; } ?>><label for="Disabled_radio">Disable</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
    <input type="button" value="UPDATE" class="art-button-green" onclick="Update_Module()">
</div>

<script type="text/javascript">
    function Enable_Module(){
        $("#Modile_Dialog").dialog("open");
    }
</script>
<script type="text/javascript">
    function Update_Module(){
        var value1 = document.getElementById("Enabled_radio").checked;
        var value2 = document.getElementById("Disabled_radio").checked;
        var msg = confirm("Are you sure you want to update?");
        var Status = '';
        if(value1 == true){
            Status = 'Enabled';
        }else{
            Status = 'Disabled';
        }
        if(msg == true){
            document.location = 'Enable_Splash_Form.php?Status='+Status;
        }
    }
</script>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">

<script>
    $(document).ready(function(){
        $("#Modile_Dialog").dialog({ autoOpen: false, width:'35%',height:110, title:'eHMS 2.0',modal: true});
    });
</script>

<?php
    include("./includes/footer.php");
?>