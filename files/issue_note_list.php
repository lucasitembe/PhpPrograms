<?php
        include("./includes/header.php");
        include("./includes/connection.php");
        include("./includes/functions.php");

        $requisit_officer=$_SESSION['userinfo']['Employee_Name'];
    
        if(!isset($_SESSION['userinfo'])){
                @session_destroy();
                header("Location: ../index.php?InvalidPrivilege=yes");
        }
        if(isset($_SESSION['userinfo'])){
                if(isset($_SESSION['userinfo']['Storage_And_Supply_Work'])){
                        if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){
                                header("Location: ./index.php?InvalidPrivilege=yes");
                        } 
                }else{
                        header("Location: ./index.php?InvalidPrivilege=yes");
                }
        }else{
                @session_destroy();
                header("Location: ../index.php?InvalidPrivilege=yes");
        }
 

        if(isset($_SESSION['userinfo'])){
                if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
                        echo "<a href='previousgrnlist.php?PreviousGrnList=PreviousGrnListThisPage' class='art-button-green'>PREVIOUS ISSUE NOTE</a>";
                }
        }
        if(isset($_SESSION['userinfo'])){
                if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
                        echo "<a href='goodreceivednote.php?GoodReceivedNote=GoodReceivedNoteThisPage' class='art-button-green'>BACK</a>";
                }
        }


 ?>
 <br/><br/>
 <center><form name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data" action="" method="post">
            <table width=50%> 
                    <tr> 
                        <td><b>From<b></td>
                        <td><input type='text' name='Date_From' id='date_From' required='required'></td>
                        <td><b>To<b></td>
                        <td><input type='text' name='Date_To' id='date_To' required='required'></td>
                        <td width=7%><input name='' type='submit' value='FILTER' class='art-button-green'></td>
                    </tr>
            </table>
        </form>
 </center>
<fieldset>
        <legend align='right'><b><?php if(isset($_SESSION['Storage'])){ echo $_SESSION['Storage']; }?>, Issue Note</b></legend>
<iframe src='a2list_iframe.php' width=100% height=350px></iframe>
</fieldset>
<?php     
	include("./includes/footer.php");
?>
