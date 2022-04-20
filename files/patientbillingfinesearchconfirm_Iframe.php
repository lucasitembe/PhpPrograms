<link rel="stylesheet" href="table.css" media="screen">
<?php
    session_start();
    include("./includes/connection.php");
    
    //get variables from previous form
    if(isset($_GET['Type'])){
	$Type = $_GET['Type'];
    }else{
	header("location : ../index.php");
    }
    if(isset($_GET['Item_Category_Name'])){
	$Item_Category_Name = $_GET['Item_Category_Name'];
    }else{
	header("location : ../index.php");
    }
    
    if(isset($_GET['Registration_ID'])){
	$Registration_ID = $_GET['Registration_ID'];
    }else{
	header("location : ../index.php");
    }
    if(isset($_GET['item_name'])){
	$item_name = $_GET['item_name'];
    }else{
	$item_name = '';
    }
    $Guarantor_Name = $_GET['Guarantor_Name'];
    ?>
<?php
//get the current date
		$Today_Date = mysqli_query($conn,"select now() as today");
		while($row = mysqli_fetch_array($Today_Date)){
		    $original_Date = $row['today'];
		    $new_Date = date("Y-m-d", strtotime($original_Date));
		    $Today = $new_Date; 
		}
?>
    <script type='text/javascript'>
        function removeitem(payment_item_list_cache_ID) {
             if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    }
	    mm.onreadystatechange= AJAXP; //specify name of function that will handle server response....
	    mm.open('GET','removeitempatientbillingfinesearchitem.php?payment_item_list_cache_ID='+payment_item_list_cache_ID,true);
	    mm.send();
        }
        function AJAXP() {
	var data = mm.responseText;
            if(mm.readyState == 4){
                document.location.reload();
            }
        }
    </script>
	    <table width='100%'>
		<tr id='thead'>
                    <td width='5%'><b>SN</b></td>
                    <td><b>Item</b></td>
		    <td width='6%'><b>Type Of Check In</b></td>
                    <td width='10%'><b>Patient Direction</b></td>
		    <td width='10%'><b>Consultant</b></td>
		    <td width='5%'><b>Discount</b></td>
                    <td width='5%'><b>Price</b></td>
                    <td width='5%'><b>Balance</b></td>
		    <td width='5%'><b>Quantity</b></td>
		    <td width='5%'><b>Amount</b></td>
		    <td width='5%'><b>Action</b></td>
                </tr>
                <?php
                $i = 1;
		$qr = "SELECT * FROM tbl_payment_item_list_cache pilc,tbl_items i WHERE pilc.Employee_ID=".$_SESSION['userinfo']['Employee_ID']."
		      AND pilc.Registration_ID=$Registration_ID AND i.Item_ID = pilc.Item_ID";
		$result = mysqli_query($conn,$qr);
		while($row = @mysqli_fetch_assoc($result)){
		    $Item_ID=$row['Item_ID'];
		    ?>
		    <tr>
		    <td style='text-align: center;vertical-align: middle;' id='thead'><?php echo $i;?></td>
                    <td>
			<input type='text' id='Item_ID' name='Item_ID' style='width: 100%;' value='<?php echo $row['Product_Name']; ?>' readonly='readonly' onchange="getPrice('<?php echo $Item_ID; ?>')" onclick="getPrice('<?php echo $Item_ID; ?>')" required='required'>
		    </td>
		    <td>
			<input type='text' id='Type_Of_Check_In_<?php echo $Item_ID; ?>' name='Type_Of_Check_In_<?php echo $Item_ID; ?>' value='<?php echo $row['Check_In_Type']; ?>' readonly='readonly'>
		    </td>
		    <td>
			<input type='text' id='Patient_Direction_<?php echo $Item_ID; ?>' name='Patient_Direction_<?php echo $Item_ID; ?>' value='<?php echo $row['Patient_Direction']; ?>' style='width: 100%;'required='required'>
		    </td>
		    <td>
			<input type='text' name='Consultant_<?php echo $Item_ID; ?>' id='Consultant_<?php echo $Item_ID; ?>' value='<?php echo $row['Consultant_ID']; ?>' >
		    </td>
		    <td width='5%'><input type='text' name='Discount_<?php echo $Item_ID; ?>' value='<?php echo $row['Discount']; ?>' id='Discount_<?php echo $Item_ID; ?>' size=11 style='text-align: right;'></td>
		    <td width='5%'><input type='text' name='price_<?php echo $Item_ID; ?>' id='price_<?php echo $Item_ID; ?>' value='<?php
			if(isset($row['bill_type'])){
			 $Billing_Type = $row['bill_type'];   
			}else{
			    $Billing_Type = '';
			}
			if($Billing_Type=='Outpatient Credit'||$Billing_Type=='Inpatient Credit'){
				if(strtolower($Guarantor_Name)=='nhif'){
				echo $row['Selling_Price_NHIF'];
				}else{
				    echo $row['Selling_Price_Credit'];
				}
			}elseif($Billing_Type=='Outpatient Cash'||$Billing_Type=='Inpatient Cash'){
				echo $row['Selling_Price_Cash'];
			}
		    ?>' size=11 style='text-align: right;' readonly></td>
		    <td><input type='text' id='balance_<?php echo $Item_ID; ?>' size=10 name='balance_<?php echo $Item_ID; ?>' readonly='readonly' value='1'></td>
		    <td width='5%'><input type='text' id='quantity_<?php echo $Item_ID; ?>' name='quantity_<?php echo $Item_ID; ?>' value='<?php echo $row['Quantity']; ?>' size=15 onchange="getPrice('<?php echo $row['Item_ID']; ?>')" onkeyup="getPrice('<?php echo $row['Item_ID']; ?>')" style='text-align: right;' required='required'></td>
		    <td><input type='text' name='ammount_<?php echo $Item_ID; ?>' id='ammount_<?php echo $Item_ID; ?>' size=15 style='text-align: right;' readonly></td>
		    <td><input type='button' value='Remove' onclick="removeitem('<?php echo $row['payment_item_list_cache_ID']; ?>')"></td>
                </tr>
		<?php
			    $i++;
	    
		}
			    ?>
	    </table>