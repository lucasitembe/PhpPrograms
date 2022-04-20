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
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>

<?php
if (isset($_POST['submittedAddNewReasonForm'])) {

    $Discharge_Reason = mysqli_real_escape_string($conn,$_POST['Discharge_Reason']);
    $discharge_condition = mysqli_real_escape_string($conn,$_POST['discharge_condition']);
    $sql = "insert into tbl_discharge_reason(Discharge_Reason,discharge_condition)
		    values ('$Discharge_Reason','$discharge_condition')";

    if (!mysqli_query($conn,$sql)) {
        echo '<script>
	  alert("An error has occured! Please try again later");
          window.location="adddischargereason.php";
	</script>';
      
    } else {
         echo '<script>
	  alert("Reason Added Successfully !");
          window.location="adddischargereason.php";
	</script>';
       
    }
}


?>
<center>
    <table width=50%><tr><td>
        <center>
            <fieldset>
                <form action='#' method='post'>
                    <legend align="center" ><b>ADD NEW DISCHARGE REASON</b></legend>
                    <table>
                        <tr>
                            <td width=30%><b>Reason :</b></td>
                            <td width=70%>
                                <input type='text' class="form-control"name='Discharge_Reason' required='required' size=70 id='Discharge_Reason' placeholder='Enter Discharge Reason'>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Discharge Condition :</b>
                            </td>
                            <td>
                                <select class="form-control" name="discharge_condition"required="" title="Select Discharge Condition">
                                    <option value=""></option>
                                    <option value="alive">Alive</option>
                                    <option value="dead">Dead</option>
                                </select>
                            </td>
                        </tr>
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