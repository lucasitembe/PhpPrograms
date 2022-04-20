<?php
include("./includes/connection.php");
include("./includes/header.php");
$controlforminput = '';
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}
?>
<a href="generalledgercenter.php" class="art-button-green">BACK</a>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>

<center>
    <table width=60%><tr><td>
        <center>
            <fieldset>
                <legend align="center" ><b>GPITG SUPERUSER AUTHENTICATION</b></legend>
                <table width = '100%'>
                    <form action='#' method='post' name='myForm' id='myForm'  >
                        <tr>
                            <td width=30% style="text-align:right;">Username</td>
                            <td width=70%>
                                <input type='text' name='Username' required='required' size=70 id='Supervisor_Username' placeholder='Supervisor Username' autocomplete='off'>
                            </td>
                        </tr> 
                        <tr>
                            <td style="text-align:right;">Password</td>
                            <td width=70%>
                                <input type='password' name='Passsword' required='required' size=70 id='Supervisor_Password' placeholder='Supervisor Password'>
                            </td> 
                        </tr>
                        <tr>
                            <td colspan=2 style='text-align: center;'>
                                <input type='submit' name='submit' id='submit' value='LOGIN' class='art-button-green'>
                                <input type='reset' name='clear' id='clear' value='CLEAR' class='art-button-green'> 
                                <a href='./index.php?TransactionAccessDenied=TransactionAccessDeniedThisPage' class='art-button-green'>CANCEL</a>
                                <input type='hidden' name='submittedSupervisorInformationForm' value='true'/> 
                            </td>
                        </tr>
                    </form></table>
            </fieldset>
        </center></td></tr>
    </table>
</center>
<!--
-->

<?php
if(isset($_POST['submit'])){
 $Username=$_POST['Username'];  
 $Passsword=$_POST['Passsword'];
 if($Username=="bibigpitg" && $Passsword=="kalamuvyukigwa" ){
     $_SESSION['superuser']="yes";
     header("location:general_ledger_buttons.php");
 }
}
include("./includes/footer.php");
