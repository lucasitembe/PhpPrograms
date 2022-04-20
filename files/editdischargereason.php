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
<a href='editdischargereasonlist.php?AdmisionWorks=AdmisionWorksThisPage' class='art-button-green'>
    <b>BACK</b>
</a>
<br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>

<?php
$Discharge_Reason_ID = mysqli_real_escape_string($conn,$_GET['Discharge_Reason_ID']);

	if(isset($_POST['submittedAddNewReasonForm'])){
		
	    $Discharge_Reason = mysqli_real_escape_string($conn,$_POST['Discharge_Reason']);
	    $discharge_condition = mysqli_real_escape_string($conn,$_POST['discharge_condition']);
            
	    $sql = "UPDATE  tbl_discharge_reason SET Discharge_Reason='$Discharge_Reason'
		    WHERE Discharge_Reason_ID='$Discharge_Reason_ID'";
	    
	     if (!mysqli_query($conn,$sql)) {
        echo '<script>
	  alert("An error has occured! Please try again later");
          window.location="editdischargereason.php?Discharge_Reason_ID='.$Discharge_Reason_ID.'&AdmisionWorks=AdmisionWorksThisPage";
	</script>';
      
    } else {
         echo '<script>
	  alert("Reason Updated Successfully !");
          window.location="editdischargereason.php?Discharge_Reason_ID='.$Discharge_Reason_ID.'&AdmisionWorks=AdmisionWorksThisPage";
	</script>';
       
    }
	}
        
            
	    $sql = "SELECT * FROM tbl_discharge_reason WHERE Discharge_Reason_ID='$Discharge_Reason_ID'";
            $result=  mysqli_query($conn,$sql) or die(mysqli_error($conn));
            $row=  mysqli_fetch_assoc($result);
	
	
?>
<center>
    <table width=50%><tr><td>
        <center>
            <fieldset>
                <form action='#' method='post'>
                    <legend align="center" ><b>EDIT DISCHARGE REASON</b></legend>
                    <table>
                                <tr>
                                    <td width=30%><b>Reason :</b></td>
                                    <td width=70%>
                                        <input type='text' name='Discharge_Reason' required='required' size=70 id='Discharge_Reason' value="<?php echo $row['Discharge_Reason']?>" placeholder='Enter Discharge Reason'>
                                    </td>
                                </tr>
<!--                                <tr>
                                    <td>
                                        <b>Discharge Condition :</b>
                                    </td>
                                    <td>
                                        <select class="form-control" name="discharge_condition"required="" title="Select Discharge Condition">
                                            <option value=""></option>
                                            <option value="alive" <?php if($row['discharge_condition']=='alive'){ echo "selected='selected'";}?>>Alive</option>
                                            <option value="dead" <?php if($row['discharge_condition']=='dead'){ echo "selected='selected'";}?>>Dead</option>
                                        </select>
                                    </td>
                                </tr>-->
                                <tr>
                                    <td colspan=2 style='text-align: right;'>
                                        <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                                        <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                                        <input type='hidden' name='submittedAddNewReasonForm' value='true'/> 
                                    </td>
                                </tr>
                        </table>
                    </form>
            </fieldset>
        </center></td></tr></table>
</center>
<?php
    include("./includes/footer.php");
?>