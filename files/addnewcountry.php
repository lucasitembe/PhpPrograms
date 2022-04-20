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
	if(isset($_POST['submittedAddNewCountryForm'])){
		  
		$country = mysqli_real_escape_string($conn,$_POST['country']);  
                
                
		$sql = "insert into tbl_country(Country_Name) values('$country')";
		//$check=mysqli_query($conn,$sql) or die(mysqli_error($conn));
		if(!mysqli_query($conn,$sql)){ 
			$error = '1062yes';
			    if(mysql_errno()."yes" == $error){ 
                            ?>
                            
                            <script type='text/javascript'>
                                alert('COUNTRY NAME ALREADY EXISTS! \nTRY ANOTHER NAME');
                                </script>
                            
                        <?php
			}
		}
		else { 
                    echo "<script type='text/javascript'>
			    alert('COUNTRY ADDED SUCCESSFUL');
			    </script>"; 
		}
	}
?>
<br/><br/><br/><br/><br/>

<center>
    <table width=40%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b>ADD NEW COUNTRY</b></legend>
                    <table>
                        <form action='#' method='post' name='myForm' id='myForm'> 
							<tr>
                                    <td width=40% style='text-align: right;'><b>COUNTRY COUNTRY</b></td>
                                    <td width=60%>
											<input type='text' name='country' required='required' size=70 id='country' placeholder='Enter Country Name'>
										
									</td>
                                </tr>    		
							                                    
                                <tr>
                                    <td colspan=2 style='text-align: right;'>
                                        <input type='submit' name='submit' id='submit' value='SAVE ' class='art-button-green'>
                                        <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                                        <input type='hidden' name='submittedAddNewCountryForm' value='true'/> 
                                    </td>
                                </tr>
                        </form></table>
            </fieldset>
        </center></td></tr></table>
</center>


<?php
    include("./includes/footer.php");
?>