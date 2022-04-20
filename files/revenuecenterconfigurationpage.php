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


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
    <a href='systemconfiguration.php?SystemConfiguration=SystemConfigurationThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>


<?php
    //select systemconfiguration based on branch
    if(isset($_SESSION['userinfo']['Branch_ID'])){
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }else{
        $Branch_ID = 0;
    }
    $select_system_configuration = mysqli_query($conn,"select Centralized_Collection,Departmental_Collection,Mobile_Payment,Imbalance_Discharge,Inpatient_Prepaid,price_precision,c.currency_id,currency_name,currency_code,enable_zeroing_price from tbl_system_configuration s left join tbl_currency c ON c.currency_id=s.currency_id  where Branch_ID = '$Branch_ID'") or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($select_system_configuration)){
        $Centralized_Collection = $row['Centralized_Collection'];
        $Departmental_Collection = $row['Departmental_Collection'];
        $Mobile_Payment = $row['Mobile_Payment'];
        $Imbalance_Discharge = $row['Imbalance_Discharge'];
        $Inpatient_Prepaid = $row['Inpatient_Prepaid'];
        $price_precision = $row['price_precision'];//
        $currency_name = $row['currency_name'];
        $enable_zeroing_price = $row['enable_zeroing_price'];
    }
    
    if($currency_name =='NULL' || empty($currency_name)){
       $currency_name='Not Set'; 
    }
?>
<br/><br/><br/><br/><br/><br/>
<fieldset>  
            <legend align=center><b>REVENUE CENTER CONFIGURATION</b></legend>
        <center><table width = 100%>
            <tr>
                <td style="text-align: center;">
                    <input type='checkbox' name='Centralized_Collection' id='Centralized_Collection' value='yes' disabled='disabled' <?php if(strtolower($Centralized_Collection) =='yes'){ echo 'checked="checked"'; }?>><b>Allow Centralized Collection</b>
                </td> 
                <td style="text-align: center;">
                    <input type='checkbox' name='Departmental_Collection' id='Departmental_Collection' value='yes' disabled='disabled' <?php if(strtolower($Departmental_Collection) =='yes'){ echo 'checked="checked"'; }?>><b>Allow Departmental Collection</b>
                </td>
                <td style="text-align: center;">
                    <input type='checkbox' name='Mobile_Payment' id='Mobile_Payment' value='yes' disabled='disabled' <?php if(strtolower($Mobile_Payment) =='yes'){ echo 'checked="checked"'; }?>><b>Allow ePayment Collection</b>
                </td>
                <td style="text-align: center;">
                    <input type='checkbox' name='Imbalance_Discharge' id='Imbalance_Discharge' value='yes' disabled='disabled' <?php if(strtolower($Imbalance_Discharge) =='yes'){ echo 'checked="checked"'; }?>><b>Allow Imbalance Payments Discharge</b>
                </td>
            </tr>
            <tr>   
                <td style="text-align: center;">
                    <input type='checkbox' name='Inpatient_Prepaid' id='Inpatient_Prepaid' value='yes' disabled='disabled' <?php if(strtolower($Inpatient_Prepaid) =='yes'){ echo 'checked="checked"'; }?>><b>Allow Inpatient Prepaid</b>
                </td>
                <td style="text-align: center;">
                    <input type='checkbox' name='price_precission' id='price_precission' value='yes' disabled='disabled' <?php if(strtolower($price_precision) =='yes'){ echo 'checked="checked"'; }?>><b>Allow Price Precision</b>
                </td>
                <td style="text-align: center;">
                    <strong> Currency:</strong><b><?php echo $currency_name; ?></b>
                </td>
                <td style="text-align: center;">
                     <input type='checkbox' name='price_precission' id='price_precission' value='yes' disabled='disabled' <?php if(strtolower($enable_zeroing_price) =='yes'){ echo 'checked="checked"'; }?>> <strong>Enable Billing Item To Zero Price:</strong><b><?php echo $currency_name; ?></b>
                </td>
                <td width="10%" style="text-align: center;">
                    <a href='editrevenuecenterconfigurationpage.php?EditRevenueVenterConfiguration=EditRevenueVenterConfigurationThisPage' class='art-button-green'>Update</a>
                </td>
            </tr>
        </table>
        </center>
</fieldset><br/>

<?php
    include("./includes/footer.php");
?>