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
	if(isset($_POST['submittedAddNewCurrencyForm'])){
		$currency_name = mysqli_real_escape_string($conn,$_POST['currency_name']);
                $currency_code = mysqli_real_escape_string($conn,$_POST['currency_code']);
                $currency_symbol = mysqli_real_escape_string($conn,$_POST['currency_symbol']);
                
		$sql = "insert into tbl_currency(currency_name,currency_code,currency_symbol,employee_id,date_modified)
                    values('$currency_name','$currency_code','$currency_symbol','".$_SESSION['userinfo']['Employee_ID']."',NOW())";
		
		if(!mysqli_query($conn,$sql)){ 
			$error = '1062yes';
			    if(mysql_errno()."yes" == $error){ 
                            ?>
                            
                            <script type='text/javascript'>
                                alert('CURRENCY ALREADY EXISTS! \nTRY ANOTHER NAME');
                                window.location=window.location.href;
                                </script>
                            
                        <?php
			}
		}
		else { 
                    echo "<script type='text/javascript'>
			    alert('CURRENCY ADDED SUCCESSFUL');
                            window.location=window.location.href;
			    </script>"; 
		}
	}
?>
<br/><br/><br/><br/><br/>

<center>
    <table width=40%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b>ADD NEW CURRENCY</b></legend>
                    <table>
                        <form action='#' method='post' name='myForm' id='myForm'>
                               <tr>
                                    <td width=40% style='text-align: right;'><b>CURRENCY NAME</b></td>
                                    <td width=60%><input type='text' name='currency_name' required='required' autocomplete="off" size=70 id='currency_name' placeholder='Enter Currency Name'></td>
                                </tr> 
                                <tr>
                                    <td width=40% style='text-align: right;'><b>CURRENCY CODE</b></td>
                                    <td width=60%><input type='text' name='currency_code' required='required' autocomplete="off" size=70 id='currency_code' placeholder='Enter Currency Code'></td>
                                </tr> 
                                <tr>
                                    <td width=40% style='text-align: right;'><b>CURRENCY SYMBOL</b></td>
                                    <td width=60%><input type='text' name='currency_symbol'  autocomplete="off" size=70 id='currency_symbol' placeholder='Enter Currency Symble'></td>
                                </tr> 
                                <tr>
                                    <td colspan=2 style='text-align: right;'>
                                        <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                                        <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                                        <input type='hidden' name='submittedAddNewCurrencyForm' value='true'/> 
                                    </td>
                                </tr>
                        </form></table>
            </fieldset>
        </center></td></tr></table>
</center>


<?php
    include("./includes/footer.php");
?>