<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	    if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
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
<a href="otherconfigurations.php?OtherConfigurations=OtherConfigurationsThisForm" class="art-button-green">BACK</a>
<br/><br/>

<?php
	if(isset($_POST['submittedAddNewVitalForm'])){
		$Vital_Name = mysqli_real_escape_string($conn,$_POST['Vital_Name']);
                
		$sql = "insert into tbl_vital(Vital)
                    values('$Vital_Name')";
		
		if(!mysqli_query($conn,$sql)){ 
			$error = '1062yes';
			    if(mysql_errno()."yes" == $error){ 
                            ?>
                            
                            <script type='text/javascript'>
                                alert('VITAL ALREADY EXISTS! \nTRY ANOTHER NAME');
                                </script>
                            
                        <?php
			}
		}
		else { 
                    echo "<script type='text/javascript'>
			    alert('VITAL ADDED SUCCESSFUL');
			    </script>"; 
		}
	}
?>
<br/><br/><br/><br/><br/>

<center>
    <table width=40%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b>ADD NEW VITAL</b></legend>
                    <table>
                        <form action='#' method='post' name='myForm' id='myForm'>
                               
                                <tr>
                                    <td width=40% style='text-align: right;'><b>VITAL NAME</b></td>
                                    <td width=60%><input type='text' name='Vital_Name' required='required' size=70 id='Vital_Name' placeholder='Enter Vital Name'></td>
                                </tr>                                  
                                <tr>
                                    <td colspan=2 style='text-align: right;'>
                                        <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                                        <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                                        <input type='hidden' name='submittedAddNewVitalForm' value='true'/> 
                                    </td>
                                </tr>
                        </form></table>
            </fieldset>
        </center></td></tr></table>
</center>


<?php
    include("./includes/footer.php");
?>