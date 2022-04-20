<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    $Title_Control = "False";
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ./index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Patients_Billing_Works'])){
	    if($_SESSION['userinfo']['Patients_Billing_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ./index.php?InvalidPrivilege=yes");
    }
    
    if(empty($_GET['Registration_ID']) or $_GET['Registration_ID']==0){
        echo "<script>"
        . "alert('Please select a patient to continue.');"
                . "window.location='inpatientbill.php?InPatientsBillingWorks=InPatientsBillingWorks';"
                . "</script>";   
    }
    $Registration_ID = '';
    if(isset($_GET['Registration_ID'])){
	$Registration_ID = $_GET['Registration_ID'];
    }
    if(isset($_GET['Registration_ID']) ){
	$Select_Patient = "select Patient_Name, Hospital_Ward_Name,Folio_Number,Discharge_Clearance_Status
        from tbl_patient_registration pr,tbl_Hospital_Ward hw,tbl_admission ad where pr.registration_id = '$Registration_ID'
        AND ad.registration_id = pr.registration_id AND hw.Hospital_Ward_ID = ad.Hospital_Ward_ID
	and ad.Admision_ID = (SELECT MAX(Admision_ID) FROM tbl_admission WHERE registration_id = pr.registration_id) and ad.admission_status='pending'";
	$result = mysqli_query($conn,$Select_Patient) or die(mysqli_error($conn));
	$row = mysqli_fetch_array($result);
	$patient_name = ucfirst($row['Patient_Name']);
        $folio = $row['Folio_Number'];
        $Hospital_Ward_Name = $row['Hospital_Ward_Name'];
        $Discharge_Clearance_Status = $row['Discharge_Clearance_Status'];
    }else{
	$patient_name ='';
    }
?>
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Patients_Billing_Works'] == 'yes'){ 
?>
    <a href='./inpatientbill.php?type=cash&Registration_ID=<?php echo $Registration_ID;?>&GeneralLedgerWork=GeneralLedgerWorkThiPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
<style>
    table,tr,td{
        border:none !important;
    }
    .individualBill:hover{
        color:red;
        cursor: pointer;
    }
</style>
<br><br><br>
<div id="Display_bill_details" style="width:50%;background-color: white;font-size: large;overflow-x:hidden;overflow-y:scroll " >
    <div id='Details_Area'  >
	
    </div>
</div>
<div id="bill_removal" style="width:50%;background-color: white;font-size: large;overflow-x:hidden;overflow-y:scroll;display:none " >
    <div id='Details_Area_Bill' style="padding:20px;border:1px solid #C3E8F3;width:100%  " >
        <center>
           <table width="90%">
            <tr>
                <td style="text-align:right;font-weight:bold;width:20% ">
                    Reason for Removing
                </td>
                <td>
                    <textarea style="width:100%" rows="3" id="remove_reasons"></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:center">
                    <button type="button" class="art-button-green" id="item_removal_ID" onclick="removeItem()">Remove</button>
                </td>
            </tr>
          </table>
        </center>    
    </div>
</div>
<div id="bill_edit" style="width:50%;background-color: white;font-size: large;overflow-x:hidden;overflow-y:scroll;display:none " >
    <div id='Details_Area_Bill_edit' style="padding:20px;border:1px solid #C3E8F3;width:100%  " >
        <center>
           <table width="90%">
            <tr>
                <td style="text-align:right;font-weight:bold;width:20% ">
                    EDIT QUANTITY
                </td>
                <td>
                    <input type="text" value="" style="width:100%" id="edited_quantity" />
                </td>
            </tr>
            <tr>
                <td style="text-align:right;font-weight:bold;width:20% ">
                    Reason for Editing
                </td>
                <td>
                    <textarea style="width:100%" rows="3" id="edit_reasons"></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:center">
                    <button type="button" class="art-button-green" id="item_edit_ID" onclick="savedEditItem()">Save</button>
                </td>
            </tr>
          </table>
        </center>    
    </div>
</div>
<!--<table width='100%'>
    <tr>
        <td><center><b>&nbsp;Inpatient Cash Bill For <?php //echo $patient_name;?>, Folio Number  <?php //echo $folio;?>, <?php //echo $Hospital_Ward_Name;?> Ward</b></center></td>
    </tr>
    <tr> 
         <td>
            <center>
            <a href='inpatientsummarycashbillprint.php?Registration_ID=<?php //echo $Registration_ID;?>&folio=<?php //echo $folio; ?>'  style='text-decoration: none;' target='_blank'><input type='button' value='Print Summary Bill' class='art-button-green'></a>
            <a href='inpatientdetailedcashbillprint.php?Registration_ID=<?php //echo $Registration_ID;?>&folio=<?php //echo $folio; ?>'' style='text-decoration: none;' target='_blank'><input type='button' value='Print Detailed Bill' class='art-button-green'></a>
            <?php
//		if(isset($_SESSION['userinfo'])){
//		    if($_SESSION['userinfo']['Patients_Billing_Works'] == 'yes'){ 
	    ?>
		//<?php //if($Discharge_Clearance_Status=='not cleared'){?>
		<a href='./allowdischarge.php?Registration_ID=<?php //echo $Registration_ID;?>&GeneralLedgerWork=GeneralLedgerWorkThiPage' class='art-button-green'>
		    Allow Discharge
		</a>
		//<?php //}?>
	    //<?php  //// } ?>
	    </center>
         </td>
    </tr>
</table>-->
</center>
<?php

   $select_Patients_details = mysqli_query($conn,
            "select * from tbl_patient_registration pr ,tbl_sponsor s, tbl_admission ad,tbl_employee e,tbl_hospital_ward hp,tbl_beds bd where
		pr.registration_id = ad.registration_id and Admission_Status = 'pending' and
		s.Sponsor_ID = pr.Sponsor_ID and e.Employee_ID= ad.Admission_Employee_ID
                and hp.Hospital_Ward_ID= ad.Hospital_Ward_ID and bd.Bed_ID= ad.Bed_ID
		AND pr.Registration_ID='$Registration_ID'
		") or die(mysqli_error($conn));
   
  //$rs=  mysqli_query($conn,$qr) or die(mysqli_error($conn));
   $patient=  mysqli_fetch_array($select_Patients_details);
   
   $Discharge_Clearance_Status=$patient['Discharge_Clearance_Status'];
   
    $bl=  mysqli_query($conn,"SELECT Billing_Type FROM tbl_patient_payments WHERE Registration_ID=$Registration_ID AND Folio_Number = '$folio' ORDER BY Patient_Payment_ID DESC LIMIT 1 ") or die(mysqli_error($conn));
    $Billing_Type=  mysqli_fetch_assoc($bl)['Billing_Type'];
    if($Billing_Type == 'Outpatient Cash'){
        $Billing_Type='Inpatient Cash';
    }
  // $patient_Name=$patient['Patient_Name'];
    $stb='';$aditm='';
    if($Discharge_Clearance_Status=='cleared'){
       $stb='<div style="padding:7px 10px 10px;font-weight:bold;font-size:18px;margin-top:">Cleared</div>';
       $aditm='';
    }  else {
        $stb='<button class="art-button-green" style="float:right" type="button" id="allowdisharge" Onclick="allowdishcarge('.$folio.','.$Registration_ID.','.$patient['Admision_ID'].')"> Allow Discharge</button>';
        $aditm='<button style="float:right" type="button" id="addItembtn" onclick="add_Item_bill('.$Registration_ID.','.$folio.')" class="art-button-green">Add Item</button>';
    }
  
  echo '<fieldset style="background-color:white"><center>
        <table width="80%">
         <tr>
           <td>
               <table border="0" width="100%" >
                    <tr>
                <td style="text-align:ridght"><b>Patient Name</b></td><td>'.$patient['Patient_Name'].'</td><td style="text-align:rigdht" ><b>Sponsor</b></td><td colspan="">'.$patient['Guarantor_Name'].'</td>
               </tr>
               <tr>
                <td style="text-align:ridght"><b>Admitted By</b></td><td>'.$patient['Employee_Name'].'</td><td style="text-align:ridght"><b>Admission Date</b></td><td>'.$patient['Admission_Date_Time'].'</td>
               </tr>
               <tr>
                <td style="text-align:ridght"><b>Ward</b></td><td>'.$patient['Hospital_Ward_Name'].'</td><td style="text-align:rigdht"><b>Bed #</b></td><td colspan="">'.$patient['Bed_Name'].'</td>
               </tr>
               <tr>
                 <td style="text-align:ridght" ><b>Follio #</b></td><td style="text-align:left" colspan="">'.$folio.'</td><td style="text-align:rigdht"><b>Billing Type</b></td><td>'.$Billing_Type.'</td> 
               </tr>
                </table>
           </td>
           <td style="">
           <br/>
               <table border="0" width="100%" >
                    <tr >
                      <td >
                        <span style="float:right;color:#037CB0;" id="clearStatusPatient">'.$stb.'</span>
                      </td>
                      <td >
                        <a style="float:right" href="print_bill.php?Registration_ID='.$Registration_ID.'&folio='.$folio.'" target="_blank" class="art-button-green">Print</a>
                         '.$aditm.' 
                      </td>
                    </tr>
                </table>     
           </td>
         </tr>
        </table>
         </center>
          </fieldset><br/>
        ';
   
?>
<div id="Items_Div_Area" style="width:50%;" >
  
</div>
<fieldset style="background-color:white ">  
        <center>
            <table width=100% border=1>
                <tr>
                    <td colspan="2">
                        <div style="width:100%;height: 250px;overflow-x: hidden;overflow-y: scroll">
                            <?php include 'cashbill_Iframe.php';?>
                        </div>
                        <!--<iframe width='100%' height=275px src="cashbill_Iframe.php?Registration_ID=<?php echo $Registration_ID; ?>&folio=<?php echo $folio; ?>"></iframe>-->
                    </td>
                </tr>
                <tr><td colspan='2'><hr/></td></tr>
                 <tr style="font-weight: bold">
                    <td style="text-align: right">Total Balance Due</td><td style="text-align: right;padding-right: 30px"><?php echo number_format($toal_balance_Due);?></td>
                </tr>
                <tr style="font-weight: bold">
                    <td style="text-align: right">Amount Paid</td><td style="text-align: right;padding-right: 30px;"><?php echo number_format($Amount_In_Hand);?></td>
                </tr>
                <tr style="font-weight: bold">
                    <td style="text-align: right"><?php if(($Amount_In_Hand-$toal_balance_Due)>0){echo 'Over Paid Amount';}else{echo 'Remaining Balance';}?></td><td style="text-align: right;padding-right: 30px;<?php if(($Amount_In_Hand-$toal_balance_Due)>0){echo 'color:blue';}else{echo 'color:red';}?>"><?php echo number_format($Amount_In_Hand-$toal_balance_Due);?></td>
                </tr>
            </table>
        </center>
</fieldset><br/>
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<!--<script src="script.js"></script>-->
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<!--<script src="script.responsive.js"></script>-->

<script>
   $(document).ready(function(){
      $("#Display_bill_details").dialog({ autoOpen: false, width:'70%',height:550, title:'DETAILS FOR ITEM BILLED',modal: true});
      $("#Items_Div_Area").dialog({ autoOpen: false, width:950,height:450, title:'ADD NEW ITEM',modal: true});
      $("#bill_removal").dialog({ autoOpen: false, width:950,height:450, title:'REMOVE ITEM',modal: true});
      $("#bill_edit").dialog({ autoOpen: false, width:950,height:450, title:'EDIT ITEM',modal: true});
        <?php
        if(($Amount_In_Hand-$toal_balance_Due) < 0  && $Billing_Type =='Inpatient Cash'){
            ?>
              $('#allowdisharge').remove(); 
             
            <?php   
        }

      ?>
      
   });
</script>
<script>
 function openDialogPatient(){
     $("#Display_bill_details").dialog("open");
 }
</script>
<script>
 function individualPaymentDetais(Item_ID,folio,Billing_Type,Registration_ID){
             // alert(Item_ID+' '+folio+' '+Billing_Type+' '+Registration_ID);
        if(window.XMLHttpRequest) {
	    myObject = new XMLHttpRequest();
	}else if(window.ActiveXObject){ 
	    myObject = new ActiveXObject('Micrsoft.XMLHTTP');
	    myObject.overrideMimeType('text/xml');
	}
        
        myObject.onreadystatechange = function (){
            data = myObject.responseText;
            if (myObject.readyState == 4) {
               //alert(data);
                document.getElementById('Details_Area').innerHTML=data;
                $("#Display_bill_details").dialog("open");
            }
        }; //specify name of function that will handle server response........
	myObject.open('GET','inpatient_get_item_bill.php?Item_ID='+Item_ID+'&Billing_Type='+Billing_Type+'&folio='+folio+'&Registration_ID='+Registration_ID,true);
	myObject.send();
    
    
 }
</script>
<script>
  function allowdishcarge(folio,Registration_ID,Admision_ID){
   if(confirm('Are you sure you want to clear patient bill?')){   
     if(window.XMLHttpRequest) {
	    myObject = new XMLHttpRequest();
	}else if(window.ActiveXObject){ 
	    myObject = new ActiveXObject('Micrsoft.XMLHTTP');
	    myObject.overrideMimeType('text/xml');
	}
        
        myObject.onreadystatechange = function (){
           var data = myObject.responseText;
            if (myObject.readyState == 4) {
               if(data=='Allowed'){
                   alert("Patient ready for discharge.Nurse responsible can discharge the patient now.");
                   document.getElementById('clearStatusPatient').innerHTML='<div style="padding:7px 10px 10px;font-weight:bold;font-size:18px;margin-top:">Cleared</div>';
                   document.getElementById('addItembtn').remove();
               }else{
                   //alert(data);
                 alert("An error has occured! Please try again letter.");
                  
               }
               
            }
        }; //specify name of function that will handle server response........
	myObject.open('GET','inpatient_allow_discharge.php?Admision_ID='+Admision_ID+'&folio='+folio+'&Registration_ID='+Registration_ID,true);
	myObject.send();
    }
  }
</script>
<script>
$("#edited_quantity").bind("keydown", function (event) {
          // alert(event.keyCode);  
    if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 ||
         // Allow: Ctrl+A
        (event.keyCode == 65 && event.ctrlKey === true) ||
         
        // Allow: Ctrl+C
        (event.keyCode == 67 && event.ctrlKey === true) ||
         
        // Allow: Ctrl+V
        (event.keyCode == 86 && event.ctrlKey === true) ||
         
        // Allow: home, end, left, right
        (event.keyCode >= 35 && event.keyCode <= 39)) {
          // let it happen, don't do anything
          return;
    } else {
        // Ensure that it is a number and stop the keypress
        if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
            event.preventDefault();
        }  
    }
});  
</script>
<script>
  function editItem(ppil,item_name,Quantity){
      //bill_edit
       document.getElementById('item_edit_ID').setAttribute('onclick','savedEditItem('+ppil+')');
       document.getElementById('edited_quantity').value=Quantity;
       $("#bill_edit").dialog("option","title","EDITTING ( "+item_name+" )");
       $("#bill_edit").dialog('open');//item_edit_ID
      //alert(ppil+' '+item_name+' '+Quantity);
  }
</script>
<script>
  function removeItem(ppil,item_name){
       document.getElementById('item_removal_ID').setAttribute('onclick','start_removeItem('+ppil+')');
       $("#bill_removal").dialog("option","title","REMOVING ( "+item_name+" )");
       $("#bill_removal").dialog('open');
       //alert(ppil+' '+item_name);
  }
</script>
<script>
    function savedEditItem(ppil){
      var edited_quantity=document.getElementById('edited_quantity').value;
      var edit_reasons=document.getElementById('edit_reasons').value;
       //alert(ppil+' '+edited_quantity);
    
      if(edit_reasons == '' || edited_quantity=='' || edited_quantity==0){
          alert('All fied are required.Please enter valid entry');
      }else{
         if(confirm("Are you sure you want to edit the item?")){
                if(window.XMLHttpRequest) {
                  myObjectRefreshDiv = new XMLHttpRequest();
                }else if(window.ActiveXObject){ 
                    myObjectRefreshDiv = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObjectRefreshDiv.overrideMimeType('text/xml');
                }
                //document.location = "./Get_Items_Price.php?Item_Name="+Item_Name;
                myObjectRefreshDiv.onreadystatechange = function (){
                   var data = myObjectRefreshDiv.responseText;
                    if (myObjectRefreshDiv.readyState == 4) { 
                       if(data=='Success'){
                           alert("Item edited Successifuly");
                           $("#bill_edit").dialog('close');
                           $("#Display_bill_details").dialog('close');
                            document.location.reload();
                       }else{
                           //alert("An error has occured! Please try again later.");
                           alert(data);
                       }
                    }
                }; //specify name of function that will handle server response........

                myObjectRefreshDiv.open('GET','remove_edit_Items_Bill.php?action=edit&ppil='+ppil+'&edited_quantity='+edited_quantity,true);
                myObjectRefreshDiv.send();
          }
      }
    }
</script>
<script>
  function start_removeItem(ppil){
      var remove_reasons=document.getElementById('remove_reasons').value;
      var myObjectRefreshDiv;
      if(remove_reasons==''){
         alert('Please add the reasons for the removal'); 
      }else{
          if(confirm("Are you sure you want to remove the item?")){
                if(window.XMLHttpRequest) {
                  myObjectRefreshDiv = new XMLHttpRequest();
                }else if(window.ActiveXObject){ 
                    myObjectRefreshDiv = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObjectRefreshDiv.overrideMimeType('text/xml');
                }
                //document.location = "./Get_Items_Price.php?Item_Name="+Item_Name;
                myObjectRefreshDiv.onreadystatechange = function (){
                   var data = myObjectRefreshDiv.responseText;
                    if (myObjectRefreshDiv.readyState == 4) { 
                       if(data=='Success'){
                           alert("Item Removed Successifuly");
                           $("#bill_removal").dialog('close');
                           $("#Display_bill_details").dialog('close');
                            document.location.reload();
                       }else{
                            alert("An error has occured! Please try again later.");
                       }
                    }
                }; //specify name of function that will handle server response........

                myObjectRefreshDiv.open('GET','remove_edit_Items_Bill.php?action=remove&ppil='+ppil+'&remove_reasons='+remove_reasons,true);
                myObjectRefreshDiv.send();
          }
      }
      //alert(ppil);
  }
</script>
<script>
    function add_Item_bill(Registration_ID,folio) {
       // alert(Registration_ID+' '+folio);
      
        if(window.XMLHttpRequest) {
            myObjectRefreshDiv = new XMLHttpRequest();
        }else if(window.ActiveXObject){ 
            myObjectRefreshDiv = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRefreshDiv.overrideMimeType('text/xml');
        }
        //document.location = "./Get_Items_Price.php?Item_Name="+Item_Name;
        myObjectRefreshDiv.onreadystatechange = function (){
            data999 = myObjectRefreshDiv.responseText;
            if (myObjectRefreshDiv.readyState == 4) { 
                document.getElementById('Items_Div_Area').innerHTML = data999;
                //clearContent();
                openItemDialog();
            }
        }; //specify name of function that will handle server response........
          
        myObjectRefreshDiv.open('GET','Refresh_inpatient_div.php',true);
        myObjectRefreshDiv.send();
    }
</script>
<script>
	function Get_Item_Name(Item_Name,Item_ID){
	    document.getElementById('Quantity').value = '';
	  //  document.getElementById('Comment').value = '';

	    var Temp = '';
	    if(window.XMLHttpRequest) {
            myObjectGetItemName = new XMLHttpRequest();
	    }else if(window.ActiveXObject){
    		myObjectGetItemName = new ActiveXObject('Micrsoft.XMLHTTP');
    		myObjectGetItemName.overrideMimeType('text/xml');
	    }
	    
	    document.getElementById("Item_Name").value = Item_Name;
	    document.getElementById("Item_ID").value = Item_ID;
            document.getElementById("Quantity").value = 1;
        //document.getElementById("Quantity").focus();
	}
</script>



<script>
   function openItemDialog(){
      $("#Items_Div_Area").dialog("open");
   }
</script>
<script>
    function Get_Selected_Item(){
        var Billing_Type ='<?php echo $Billing_Type; ?>';
        var Item_ID = document.getElementById("Item_ID").value;
        var Quantity = document.getElementById("Quantity").value;
        var Item_Name=document.getElementById("Item_Name").value;
        var Price=document.getElementById("Price").value;
        var Guarantor_Name = '<?php echo $Registration_ID; ?>';
        var Folio_Number = '<?php echo $folio; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';

	if ( Item_Name != '' && Item_Name != null && Item_ID != '' && Item_ID != null && Quantity != '' && Quantity != null && Billing_Type != '' && Billing_Type != null ) {
	    var r = confirm("Are you sure you want to save information?\nClick OK to proceed or Cancel to stop process");
            if(r == true){
             
             if(window.XMLHttpRequest){
	       myObject2 = new XMLHttpRequest();
	     }else if(window.ActiveXObject){
	       myObject2 = new ActiveXObject('Microsoft.XMLHTTP');
	       myObject2.overrideMimeType('text/xml');
	     }
	     myObject2.onreadystatechange = function (){
	      var  data = myObject2.responseText;
		 
	       if (myObject2.readyState == 4) {
		  //alert("One Item Added");
		  //document.getElementById('Picked_Items_Fieldset').innerHTML = data;
		  //update_Billing_Type(Registration_ID);
		  //Update_Claim_Form_Number();
		  document.getElementById("Item_Name").value = '';
		  document.getElementById("Item_ID").value = '';
		  document.getElementById("Quantity").value = '';
		  document.getElementById("Price").value = '';
		  document.getElementById("Search_Value").focus();
		 
                 if(data == 'added'){
                     document.getElementById("statusmsg").innerHTML = '<span style="color:green;font-size:17px">Item Added Sucessifully</span>';
	         }else if(data == 'error'){
                     document.getElementById("statusmsg").innerHTML = '<span style="color:red;font-size:17px">An error has occured! Please try again later</span>';
                 }
               }
	     }; //specify name of function that will handle server response........Folio_Number
	     
	     //myObject.open('GET','Perform_Reception_Transaction.php?Registration_ID='+Registration_ID+'&Item_ID='+Item_ID+'&Type_Of_Check_In='+Type_Of_Check_In+'&direction='+direction+'&Quantity='+Quantity'&Consultant='+Consultant,true);
	     myObject2.open('GET','Add_Selected_Items_Bill.php?Registration_ID='+Registration_ID+'&Folio_Number='+Folio_Number+'&Item_ID='+Item_ID+'&Price='+Price+'&Item_Name='+Item_Name+'&Quantity='+Quantity+'&Billing_Type='+Billing_Type+'&Guarantor_Name='+Guarantor_Name+'&Billing_Type='+Billing_Type,true);
	     myObject2.send();
         } 
	 }else if (Registration_ID != '' && Registration_ID != null && (Item_Name == '' || Item_Name == null || Item_ID == '' || Item_ID == null) != '' && Quantity != '' && Quantity != null){
	     alertMessage();
	 }else{
	     if(Quantity=='' || Quantity == null){
	       document.getElementById("Quantity").focus();
	       document.getElementById("Quantity").style = 'border: 3px solid red';
	     }
	 }
     }
</script>

<script type="text/javascript">
    function alertMessage(){
        alert("Select Item First");
        document.getElementById("Quantity").value = '';
    }
</script>

<script>
   function getItemsList(Item_Category_ID){
      document.getElementById("Search_Value").value = ''; 
      document.getElementById("Item_Name").value = '';
      document.getElementById("Item_ID").value = '';
       //var Type_Of_Check_In = document.getElementById("Type_Of_Check_In").value;

      if(window.XMLHttpRequest) {
	  myObject = new XMLHttpRequest();
      }else if(window.ActiveXObject){ 
	  myObject = new ActiveXObject('Microsoft.XMLHTTP');
	  myObject.overrideMimeType('text/xml');
      }
      //alert(data);
  
      myObject.onreadystatechange = function (){
		  data265 = myObject.responseText;
		  if (myObject.readyState == 4) {
		      //document.getElementById('Approval').readonly = 'readonly';
		      document.getElementById('Items_Fieldset').innerHTML = data265;
		  }
	      }; //specify name of function that will handle server response........
      myObject.open('GET','Get_List_Of_Departmental_Filtered_Items_inpatient.php?Item_Category_ID='+Item_Category_ID,true);
      myObject.send();
   }
</script>
<script>
   function Get_Item_Price(Item_ID){
      var Billing_Type =  '<?php echo $Billing_Type ?>';
      if(window.XMLHttpRequest) {
	  myObject = new XMLHttpRequest();
      }else if(window.ActiveXObject){ 
	  myObject = new ActiveXObject('Micrsoft.XMLHTTP');
	  myObject.overrideMimeType('text/xml');
      }
      //document.location = "./Get_Items_Price.php?Item_Name="+Item_Name;
      myObject.onreadystatechange = function (){
	  data = myObject.responseText;
	  
	  if (myObject.readyState == 4) { 
	      document.getElementById('Price').value = data;
	      //alert(data);
	  }
      }; //specify name of function that will handle server response........
      
      myObject.open('GET','Get_Items_Price.php?Item_ID='+Item_ID+'&Guarantor_Name=<?php echo $patient['Guarantor_Name'] ?>&Billing_Type='+Billing_Type,true);
      myObject.send();
  }
</script>
<script>
   function getItemsListFiltered(Item_Name){
      document.getElementById("Item_Name").value = '';
      document.getElementById("Item_ID").value = '';
      document.getElementById("Quantity").value = '';
      var Item_Category_ID = document.getElementById("Item_Category_ID").value;
      if (Item_Category_ID == '' || Item_Category_ID == null) {
	  Item_Category_ID = 'All';
      }
      
      if(window.XMLHttpRequest) {
	  myObject = new XMLHttpRequest();
      }else if(window.ActiveXObject){ 
	  myObject = new ActiveXObject('Micrsoft.XMLHTTP');
	  myObject.overrideMimeType('text/xml');
      }
  
      myObject.onreadystatechange = function (){
	 data135 = myObject.responseText;
	 if (myObject.readyState == 4) {
	     //document.getElementById('Approval').readonly = 'readonly';
	     document.getElementById('Items_Fieldset').innerHTML = data135;
	 }
      }; //specify name of function that will handle server response........
      myObject.open('GET','Get_List_Of_Departmental_Filtered_Items_inpatient.php?Item_Category_ID='+Item_Category_ID+'&Item_Name='+Item_Name,true);
      myObject.send();
   }
</script>
<script>
    function refreshPage(){
        window.location.reload();
    }
</script>

<?php
    include("./includes/footer.php");
?>