<link rel="stylesheet" href="table33.css" media="screen">
<script>
 function update_price_discount_quantity(ppilc,field,instance,ppil){
   //alert(ppilc+" "+field+" "+instance.value);
     var Price,Quantity,Discount;
  
       Price=parseInt(document.getElementById('Price_'+ppilc).value.replace(/[^0-9\.]+/g,""));
	   Quantity=parseInt(document.getElementById('Quantity_'+ppilc).value.replace(/[^0-9\.]+/g,""));
	   Discount=parseInt(document.getElementById('Discount_'+ppilc).value.replace(/[^0-9\.]+/g,""));
	   var subTotal;
		
		if( isNaN(Quantity) ){
		// alert('Invalid Quantity Value');
		 //instance.value=1;
		 Quantity=1;
		 if(Price >=0){
		   subTotal=numberFormart((Price-Discount)*Quantity);
		 }else{
		   subTotal=0;
		 }
		 
		
		  exit;
		
		}
		subTotal=numberFormart((Price-Discount)*Quantity);
	
	     document.getElementById('Sub_total_'+ppilc).value=subTotal;
		if(Quantity <=0 ){
		   Quantity=1
		}
		  //ajax requests
		   
		   var mypostrequest=new ajaxRequest();
			mypostrequest.onreadystatechange=function(){
			 if (mypostrequest.readyState==4){
			  if (mypostrequest.status==200 || window.location.href.indexOf("http")==-1){
			   //document.getElementById("result").innerHTML=mypostrequest.responseText;
			    //alert("Request success");
				//alert(mypostrequest.responseText);
				if(mypostrequest.responseText=='1'){
				document.getElementById("tansactionedited").innerHTML='Quantity edited successifully';
				 }else{
				   //alert(mypostrequest.responseText);
				    alert("An error has occured while updating the quantity. Please try again later.");
				 }
			  }
			  else{
			   alert("An error has occured making the request");
			  }
			 }
			}
	     	var parameters="updateTransactiondetails=true&Price="+Price+"&Quantity="+Quantity+"&Discount="+Discount+"&ppilc="+ppilc+"&ppil="+ppil;
			mypostrequest.open("POST", "patientbillingtransactionpost.php", true);
			mypostrequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			mypostrequest.send(parameters);
			//alert(parameters);
		
		
		//alert(Price +" "+Quantity+" "+Discount+" "+ppil);
	

 }
</script>
<script>
function ajaxRequest(){
 var activexmodes=["Msxml2.XMLHTTP", "Microsoft.XMLHTTP"]; //activeX versions to check for in IE
 if (window.ActiveXObject){ //Test for support for ActiveXObject in IE first (as XMLHttpRequest in IE7 is broken)
  for (var i=0; i<activexmodes.length; i++){
   try{
    return new ActiveXObject(activexmodes[i]);
   }
   catch(e){
    //suppress error
   }
  }
 }
 else if (window.XMLHttpRequest) // if Mozilla, Safari etc
  return new XMLHttpRequest();
 else
  return false;
}
</script>
<script>
  function numberFormart(num) {
    var p = num.toFixed(0).split(".");
    return "" + p[0].split("").reverse().reduce(function(acc, num, i, orig) {
        return  num + (i && !(i % 3) ? "," : "") + acc;
    }, "") ;
}
</script>
<?php
    include("./includes/connection.php");
    $temp = 1;
    $total = 0;
    echo '<center><table width =100% border=0>';
    echo '<tr id="thead"><td width = "2%">Sn</td><td><b>Check in type</b></td>
                <td><b>Location</b></td>
                <td><b>Item</b></td>
                <td><b>Edited By</b></td>
                <td><b>Reason</b></td>
                <td><b>Date & Time</b></td>
                
                    <td style="text-align: left;"><b>Price</b></td>
                        <td style="text-align: left;"><b>Discount</b></td>
                            <td style=""><b>Quantity</b></td>
                                <td style="text-align: left;"><b>Sub total</b></td></tr>';
    
    $select_Transaction_Items = mysqli_query($conn,
       "select ppi.Check_In_Type,ppi.changed_By,ppi.changed_Reasons,e.Employee_Name,ppi.changed_Date, ppi.Patient_Direction, t.Product_Name, ppi.Price, ppi.Discount, ppi.Quantity,ppi.Patient_Payment_Item_List_ID,pp.Patient_Payment_ID
	   FROM tbl_transaction_items_history ppi LEFT JOIN tbl_patient_payments pp ON pp.Patient_Payment_ID = ppi.Patient_Payment_ID 
	   JOIN tbl_items t ON t.item_id = ppi.item_id LEFT JOIN tbl_employee e ON e.Employee_ID=ppi.changed_By
	   WHERE
            pp.Patient_Payment_ID = '$Patient_Payment_ID' 
            and Patient_Payment_Item_List_ID <> '$Patient_Payment_Item_List_ID' order by changed_Date") or die(mysqli_error($conn)); 

    $num_rows=  mysqli_num_rows($select_Transaction_Items);
    while($row = mysqli_fetch_array($select_Transaction_Items)){
	    $pilc=0;//$row['Payment_Item_Cache_List_ID'];
        $ppil=$row['Patient_Payment_Item_List_ID'];
        echo "<tr><td style='width:5%'>".$temp."</td>";
        echo "<td>".$row['Check_In_Type']."</td>";
        echo "<td>".$row['Patient_Direction']."</td>";
        echo "<td title='".number_format($row['Price'])."'>".$row['Product_Name']."</td>";
        echo "<td>".$row['Employee_Name']."</td>";
        echo "<td>".$row['changed_Reasons']."</td>";
        echo "<td>".$row['changed_Date']."</td>";
        echo "<td  style='text-align: right;width:6%'><input disabled='disabled' type='text' style='text-align: left;' name='Price' id='Price_$pilc' oninput=\"update_price_discount_quantity($pilc,'Price',this,$ppil)\" value='".($row['Price']?number_format($row['Price']):0)."'></td>";
        echo "<td  style='text-align:right;width:60px'><input disabled='disabled' style='text-align: left;'  type='text' name='Discount' id='Discount_$pilc' oninput=\"update_price_discount_quantity($pilc,'Discount',this,$ppil)\" value='".($row['Discount']?number_format($row['Discount']):0)."'></td>";
        echo "<td  style=''><input style='width:50px'  type='text' name='Quantity' id='Quantity_$pilc' disabled='true' value='".($row['Quantity']?number_format($row['Quantity']):0)."'></td>";
        echo "<td  style='text-align: right;width:60px'><input style='text-align: left;'  type='text' name='TotPrice' id='Sub_total_$pilc' disabled='disabled' value='".number_format(($row['Price'] - $row['Discount'])*$row['Quantity'])."'></td>";
        $total = $total + (($row['Price'] - $row['Discount'])*$row['Quantity']);
	$temp++;
    }   echo "</tr>";
        echo "<tr><td colspan=11 style='text-align: right;'><b> TOTAL : ".number_format($total)."</b></td></tr>";
?></table></center>

