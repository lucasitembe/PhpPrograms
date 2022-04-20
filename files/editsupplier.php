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
    
    include '../account/includes/LanguagesArray.php';
    $InputError = 0;
    include '../account/includes/CountriesArray.php';
?>

<?php
    if(isset($_SESSION['userinfo'])){  
?>
    <a href='otherconfigurations.php?OtherConfigurations=OtherConfigurationsThisForm' class='art-button-green'>
        BACK
    </a>
<?php  } ?>

<br/><br/>


<center>
    <table width=100%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b>ADD NEW SUPPLIER</b></b></legend>
                    <table width=100%>
                        <form action='#' method='post' name='myForm' id='myForm'>
                                <tr>
				    <td style='text-align: right;' width=20%>Supplier Name</b></td>
				    <td width='30%'>
					<input type='text' name='Supplier_Name' id='Supplier_Name' autocomplete='off' placeholder='Enter Supplier Name'>
				    </td>
		
                                    <td width=20% style='text-align: right;'>Supplier Address</b></td>
                                    <td width=30%>
					<textarea name='Supplier_Address' size=70 id='Supplier_Address' autocomplete='off' placeholder='Enter Supplier Address' cols='10' rows=2></textarea>
				    </td>
                                </tr>
	                             <tr>
                                    <td style='text-align: right;'>Contact person name</b></td>
                                    <td>
					<input type='text' name='Contact_person' size=70 id='Contact_person' autocomplete='off' placeholder='Enter Contact person name'>
				    </td>
                                
                      <td style='text-align: right;'>Contact person mobile Number</b></td>
                                    <td>
					<input type='text' name='Mobile_Number' size=70 id='Mobile_Number' autocomplete='off' placeholder='Enter Mobile Number'>
				    </td>
                    </tr> 
                                <tr>
                                    <td style='text-align: right;'>Contact person Email</b></td>
                                    <td>
					<input type='text' name='Supplier_Email' required='required' autocomplete='off' size=70 id='Supplier_Email' placeholder='Contact person Email'>
				    </td>
                                
                                    <td style='text-align: right;'></b></td>
                                    <td>
				    </td>
                    </tr> 
                        <tr>
                                    <td colspan=2 style='text-align: right;'>
                                        <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                                        <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                                        <input type='hidden' name='submittedAddNewSupplierForm' value='true'/> 
                                    </td>
                                </tr>
                        </form></table>
            </fieldset>
        </center></td></tr></table>
</center>

   
<?php
	if(isset($_POST['submittedAddNewSupplierForm'])){
		$Supplier_Name = mysqli_real_escape_string($conn,$_POST['Supplier_Name']);
        $Supplier_Address = mysqli_real_escape_string($conn,$_POST['Supplier_Address']);
		$Mobile_Number = mysqli_real_escape_string($conn,$_POST['Mobile_Number']);
		$Supplier_Email = mysqli_real_escape_string($conn,$_POST['Supplier_Email']);
        $Contact_person = mysqli_real_escape_string($conn,$_POST['Contact_person']);        
                
		$sql = "insert into tbl_supplier(Supplier_Name,Postal_Address,
						    Mobile_Number,Supplier_Email,Contact_person)
						
						values('$Supplier_Name','$Supplier_Address',
							'$Mobile_Number','$Supplier_Email','$Contact_person')";
		
		if(!mysqli_query($conn,$sql)){ 
			$error = '1062yes';
			    if(mysql_errno()."yes" == $error){ 
                            ?>
                            
				<script type='text/javascript'>
					alert('SUPPLIER NAME ALREADY EXISTS! \nTRY ANOTHER NAME');
					</script>
                            
                        <?php
			}
		}
		else { 
           echo "<script type='text/javascript'>
			    alert('SUPPLIER ADDED SUCCESSFULLY');
			    </script>";
                    
		}
	}
?>
<?php
    include("./includes/footer.php");
?>