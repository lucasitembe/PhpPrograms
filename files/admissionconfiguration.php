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

    if(isset($_SESSION['userinfo'])){
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }else{
        $Branch_ID = 0;
    }

    if(isset($_GET['frompage']) && $_GET['frompage'] == "addmission"){
?>
<a href='admissionworkspage.php?section=Admission&AdmisionWorks=AdmisionWorksThisPage' class='art-button-green'>BACK</a>
<?php
    }else{
    ?>
    <a href='setupandconfiguration.php?BackToSetupAndConfiguration=BackTosetupAndConfigurationThisPage' class='art-button-green'>BACK</a>
<?php
    }
?>

<br>
<br>
<br>
<br>
<fieldset>
        <legend align=center><b>ADMISSION CONFIGURATIONS</b></legend>
        <center>
	    <table width = 60%>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <button style='width: 100%; height: 100%' onclick="Enable_Module()">
                        Enable / Disable Transfer In & Transfer Out Module
                    </button>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href='adddischargereason.php?AdmisionWorks=AdmisionWorksThisPage'>
                        <button style='width: 100%; height: 100%'>
                        Add New Discharge Reason
                        </button>
                    </a>
                </td>
            </tr>
    	    <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                <a href='editdischargereasonlist.php?AdmisionWorks=AdmisionWorksThisPage'>
    			    <button style='width: 100%; height: 100%'>
    				Edit Discharge Reason
    			    </button>
    			</a>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href='addhospitalward.php?AdmisionWorks=AdmisionWorksThisPage'>
                        <button style='width: 100%; height: 100%'>
        				    Add New Hospital Ward
        			    </button>
        			</a>
                </td>
            </tr>
    	    <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href='edithospitalwardlist.php?AdmisionWorks=AdmisionWorksThisPage'>
        			    <button style='width: 100%; height: 100%'>
        				Edit Hospital Ward
        			    </button>
        			</a>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href='add_or_edit_theater.php?AdmisionWorks=AdmisionWorksThisPage&frompage=addmission'>
                        <button style='width: 100%; height: 100%'>
                            Add / Edit Theater Room
                        </button>
                    </a>
                </td>
            </tr>
    	    <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href='setuptomergewardwithdepartment.php?AdmisionWorks=AdmisionWorksThisPage'>
        			    <button style='width: 100%; height: 100%'>
        				Merge Ward(s) with Department(s)
        			    </button>
        			</a>
                </td>
            </tr>
    	    <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href='setuptomergewardwithsubdepartment.php?AdmisionWorks=AdmisionWorksThisPage'>
        			    <button style='width: 100%; height: 100%'>
        				Merge Ward(s) with Sub-Department(s)
        			    </button>
        			</a>
                </td>
            </tr>
			
			<tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href='manageAdmission.php?AdmisionWorks=AdmisionWorksThisPage'>
			    <button style='width: 100%; height: 100%'>
				Manage admission
			    </button>
			</a>
                </td>
            </tr>
            <tr class="hide">
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href='manageAccomodations.php?AdmisionWorks=AdmisionWorksThisPage'>
			    <button style='width: 100%; height: 100%'>
				Manage Accomodation
			    </button>
			</a>
                </td>
            </tr>
        </table>
    </center>
</fieldset>

<?php
    //get last status
    $slct = mysqli_query($conn,"select Transfer_Patient_Module_Status from tbl_system_configuration where Branch_ID = '$Branch_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($slct);
    if($num > 0){
        while ($data = mysqli_fetch_array($slct)) {
            $Module_Status = $data['Transfer_Patient_Module_Status'];
        }
    }else{
        $Module_Status = '';
    }
?>
<div id="Modile_Dialog">
    <input type="radio" name="module" id="Enabled_radio" <?php if(strtolower($Module_Status) == 'enabled'){ echo "checked='checked'"; } ?>><label for="Enabled_radio">Enable Module</label>&nbsp;&nbsp;&nbsp;
    <input type="radio" name="module" id="Disabled_radio" <?php if(strtolower($Module_Status) == 'disabled'){ echo "checked='checked'"; } ?>><label for="Disabled_radio">Disable Module</label>&nbsp;&nbsp;&nbsp;
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
            document.location = 'Enable_Transfer_Module.php?Status='+Status;
        }
    }
</script>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">

<script>
    $(document).ready(function(){
        $("#Modile_Dialog").dialog({ autoOpen: false, width:'35%',height:110, title:'ENABLE / DISABLE MODULE',modal: true});
    });
</script>
<?php
    include("./includes/footer.php");
?>