<?php
    @session_start();
    include("./includes/connection.php"); 
   // echo  $_SESSION['Payment_Cache_ID'];
    //exit;
    if(isset($_SESSION['Payment_Cache_ID'])){
        $Payment_Cache_ID = $_SESSION['Payment_Cache_ID'];
    }else{
	$Payment_Cache_ID = '';
    }
    if(isset( $_SESSION['Transaction_Type'])){
        $Transaction_Type =  $_SESSION['Transaction_Type'];
    }else{
	$Transaction_Type = '';
    }
    
    if(isset( $_SESSION['Sub_Department_ID'])){
	$Sub_Department_ID =  $_SESSION['Sub_Department_ID'];
    }else{
	$Sub_Department_ID = 0;
    }
    $total = 0;
    $temp = 1;
    $data='';
	$dataAmount='';
    
    //generate sql

   // echo $Transaction_Status_Title;
         echo '<center><table width =100% border=0>';
         echo '<tr id="thead"><td style="text-align: center;"><b>Sn</b></td>
                <td><b>Item Name</b></td>
                    <td style="text-align:right;" width=8%><b>Price</b></td>
                        
                            <td style="text-align: center;" width=8%><b>Quantity</b></td>
                                <td style="text-align: left  ;" width=8%><b>Sub Total</b></td>
                                 <td style="text-align: left  ;" width=8%><b>Action</b></td>
                                    </tr>';
        $select_Transaction_Items_Active =mysqli_query($conn,"
		select ilc.status, ilc.Price, ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
		from tbl_item_list_cache ilc, tbl_Items its
		    where ilc.item_id = its.item_id and
                    ilc.status = 'removed' and
			ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			    ilc.Sub_Department_ID = '$Sub_Department_ID' and
                                ilc.Transaction_Type = '$Transaction_Type' and
                                        ilc.Check_In_Type = 'Laboratory'"); 

        
       // echo $select_Transaction_Items_Active;
        
        
    $no = mysqli_num_rows($select_Transaction_Items_Active);
    
	//display all medications that not approved
	if($no > 0){
	    while($row = mysqli_fetch_array($select_Transaction_Items_Active)){
                $status = $row['status'];
		echo "<tr><td width='5%'><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
		echo "<td><input type='text' value='".$row['Product_Name']."' readonly='readonly' size='100%'></td>";
		echo "<td style='text-align:right;'><input  type='text' readonly='readonly' value='".number_format($row['Price'])."' style='text-align:right;'></td>";
		//echo "<td><input type='text' style='text-align:right;' value='".number_format($row['Discount'])."'></td>";
		?>
	    <td style='text-align:right;'>
		<?php if($row['Edited_Quantity'] == 0){  $Quantity = $row['Quantity']; ?>
		    <input type='text' readonly='readonly' value='<?php echo $Quantity;?>' onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" style='text-align: center;'>
		<?php }else{ $Quantity = $row['Edited_Quantity']; ?>
		    <input type='text' readonly='readonly' value='<?php echo $Quantity; ?>' onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"];?>',this.value)" style='text-align: center;'>
		<?php } ?>
	    </td>
	    <?php
            
		echo "<td><input type='text' name='Sub_Total' id='Sub_Total' readonly='readonly' value='".number_format(($row['Price'] - $row['Discount']) * $Quantity)."' style='text-align:right;'></td>";	
		echo "<td><input type='checkbox' class='paythis' id='".$row["Payment_Item_Cache_List_ID"]."'></td>";
	        $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
		$temp++;
	    }   
//            echo "<td></td>";
            echo "</tr>";
		 $dataAmount= "<td colspan=7 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='button' class='art-button paymentTestID' id='paymentTestID' value='Make Payment'></td>";
	    
	   
	} 
        echo '</table></center>';
    
    if(isset($_POST['action'])){
        $datastring=$_POST['datastring'];
      if($_POST['action']=='makepayment'){
          $results=  explode('@', $datastring);
          foreach ($results as $value) {
              $makepayment=mysqli_query($conn,"UPDATE tbl_item_list_cache SET Status='active' WHERE Payment_Item_Cache_List_ID='".$value."'");
          }
      } 
    }
 
?>

<script type="text/javascript">
    $(document).on('click','.paymentTestID',function(){
      var locate=$(location).attr('href');
     var datastring='';
     var num=1;
     var n = $(".paythis:checked").length;
    if(n>0){
    if(confirm("Are you sure you want to perform this transaction ?")){
    $('.paythis').each(function(){
    var id=$(this).attr('id');
    var checked=$(this).is(':checked');
     if(checked){
     if(num==1){
       datastring=id; 
     }else{
       datastring+='@'+id;  
     }
     num++;
  // alert(datastring);
   }else{
       
   }

   }); 
        $.ajax({
        type:'POST', 
        url:'Patient_Billing_Unpaid_Laboratory_Iframe.php',
        data:'action=makepayment&datastring='+datastring,
        cache:false,
        success:function(){
          // alert(html);
          window.location.href=locate;
        }
           
     }); 
       
      }else{
            
      }   

    }else{
        alert('Choose at least one test to make payment');
    }

    });
</script>