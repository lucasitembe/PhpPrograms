<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    $temp = 0;
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

<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Reception_Works'] == 'yes'){
?>
    <a href='./receptionsetup.php?ReceptionSetup=ReceptionSetupThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
<br/><br/>
<fieldset>
            <legend align=center><b>ADD NEW PF3 REASON</b></legend>
        <center>
        <br/><br/><br/>
        <form action="#" method="post">
            <table width = 60%>
                <tr>
                    <td style="text-align: right" width="10%">Reason</td>
                    <td>
                        <input type="text" name="Reason_Name" id="Reason_Name" placeholder="Reason Name" autocomplete="off" required='required'>
                    </td>
                    <td width="10%">
                        <input type="submit" name="submit" id="submit" value="SAVE" class="art-button-green">
                        <input type="hidden" name="Submit_Reason" id="Submit_Reason" value="true">
                    </td>
                </tr>
            </table>
        </form>
        <br/><br/>
        <table width="60%">
            <tr>
                <td>
                    <fieldset style='overflow-y: scroll; height: 200px;' id='Reason_Area'>
                        <table width="100%">
                            <tr><td colspan=2><hr></td></tr>
			    <tr>
                                <td width="10%"><b>SN</b></td>
                                <td><b>REASON</b></td>
                            </tr>
			    <tr><td colspan=2><hr></td></tr>
                            <?php
                                $select = mysqli_query($conn,"select * from tbl_pf3_reasons order by Reason_id desc") or die(mysqli_error($conn));
                                $no = mysqli_num_rows($select);
                                if($no > 0){
                                    while ($row = mysqli_fetch_array($select)) {
                            ?>
                                        <tr>
                                            <td width="10%"><?php echo ++$temp; ?></td>
                                            <td><?php echo $row['Reason_Name']; ?></td>
                                        </tr>  
                            <?php
                                    }
                                }
                            ?>
                        </table>
                    </fieldset>
                </td>
            </tr>
        </table>
        </center>
</fieldset><br/>
<?php
    if(isset($_POST['Submit_Reason'])){
        $Reason_Name = mysqli_real_escape_string($conn,$_POST['Reason_Name']);
        if($Reason_Name != null && $Reason_Name != ''){
            $insert = mysqli_query($conn,"INSERT INTO tbl_pf3_reasons(Reason_Name) 
                                    VALUES ('$Reason_Name')");
            if($insert){
                header("Location: ./addnewreason.php?AddNewPfReason=AddNewPfReasonThisPage");
            }else{
                $error = '1062yes';
                    if(mysql_errno()."yes" == $error){ 
                    echo '<script>
                        alert("Reason Name Already Exist! Try Another Name");
                        document.location = "addnewreason.php?AddNewPfReason=AddNewPfReasonThisPage";
                        </script>'; 
                    }else{
                    echo '<script>
                        alert("Process Fail! Please Try Again");
                        document.location = "addnewreason.php?AddNewPfReason=AddNewPfReasonThisPage";
                        </script>'; 
                    }
            }
        }
    }
?>
<?php
    include("./includes/footer.php");
?>