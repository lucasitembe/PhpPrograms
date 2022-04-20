<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    if(isset($_GET['Payment_Cache_ID'])){
    $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
    $payment_cache_ID = $Payment_Cache_ID;
    }else{
        $Payment_Cache_ID = 0;
    }
    if(isset($_GET['Consultation_Type'])){
        $Consultation_Type = $_GET['Consultation_Type'];
    }
    else{
        $Consultation_Type = 0;
    }
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }
    else{
        $Registration_ID = 0;
    }
    if(isset($_GET['consultation_id'])){
        $consultation_id = $_GET['consultation_id'];
    }
    else{
        header("location : ./index/php");
    }
?>
    <script type='text/javascript'>
        function removeitem(Payment_Item_Cache_List_ID) {
             if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    }
	    mm.onreadystatechange= AJAXP; //specify name of function that will handle server response....
	    mm.open('GET','removeitemcahe.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID,true);
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
    <td width='2%'><b>SN</b></td>
    <td width='35%'><b>Description</b></td>
    <td><b>Comments</b></td>
    <td style='width: 5%;'><b>Quantity</b></td>
    <td style='width: 5%;'><b>Balance</b></td>
    <td align='right' style='width: 8%;'><b>Cash</b></td>
    <td align='right' style='width: 8%;'><b>Credit</b></td>
    <td align='center'><b>Action</b></td>
    </tr>
    <tr>
	<td colspan=8><hr></td>
    </tr>
    <?php
    $qr = "SELECT * FROM tbl_item_list_cache il,tbl_payment_cache pc,tbl_items it
		    WHERE pc.Payment_Cache_ID=il.Payment_Cache_ID AND
		    pc.consultation_id = $consultation_id
                    AND il.Item_ID = it.Item_ID AND il.Check_In_Type ='$Consultation_Type'";
    $result = mysqli_query($conn,$qr);
    $i =1;
    $Cashsum = 0;
    $Creditsum = 0;
    while($row = mysqli_fetch_assoc($result)){
        
        if($row['Transaction_Type']=='Cash'){
            $Cash = $row['Price']*$row['Quantity'];
            $Credit = 0;
        }else{
            $Credit = $row['Price']*$row['Quantity'];
            $Cash = 0;
        }
        $Cashsum+=$Cash;
        $Creditsum+=$Credit;
        ?>
    <tr>
    <td style='text-align: center;'><b><?php echo $i;?></b></td>
    <td><input style='width: 100%;' type='text' value='<?php echo $row['Product_Name'];?>' readonly></td>
    <td><input style='width: 100%;' type='text' value='<?php echo $row['Doctor_Comment'];?>' readonly></td>
    <td><input style='width: 100%;text-align: center;' type='text' value='<?php echo $row['Quantity'];?>' readonly ></td>
    <td>NA&nbsp;</td>
    <td align='right'><input style='width: 100%;text-align: right;' type='text' value='<?php if($Cash !=0){echo number_format($Cash);}else{echo '0';}?>'readonly ></td>
    <td align='right'><input style='width: 100%;text-align: right;' type='text' value='<?php if($Credit !=0){echo number_format($Credit);}else{echo '0';}?>' readonly ></td>
    <td align='center' width='2%' style='text-align: right;'><?php
	if($row['Status']!='paid'){
	?>
	<input type='button' value='Remove' onclick="removeitem('<?php echo $row['Payment_Item_Cache_List_ID']?>')">
	<?php }else echo '&nbsp'; ?>
	</td>
    </tr>
    <tr>
	<td colspan=8><hr></td>
    </tr>
        <?php
        $i++;
    }
    if(mysql_numrows($result)==0){
     ?>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
     <?php
    }
    ?>
    <tr>
    <td colspan=5 >TOTAL</td>
    <td><input type='text' value='<?php echo number_format($Cashsum); ?>' style='width: 100%;text-align: center' readonly='readonly'</td>
    <td><input type='text' value='<?php echo number_format($Creditsum); ?>' style='width: 100%;text-align: center' readonly='readonly'</td>
    <td align='center'><b></b></td>
    </tr>
</table>