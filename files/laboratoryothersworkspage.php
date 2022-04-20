<?php
   include("./includes/header.php");
   include("./includes/connection.php");
   if(!isset($_SESSION['userinfo'])){
       @session_destroy();
       header("Location: ../index.php?InvalidPrivilege=yes");
   }
   if(isset($_SESSION['userinfo'])){
       if(isset($_SESSION['userinfo']['Revenue_Center_Works'])){
    	   if($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes'){
    	       header("Location: ./index.php?InvalidPrivilege=yes");
    	   }
       }else{
	       header("Location: ./index.php?InvalidPrivilege=yes");
       }
   }else{
       @session_destroy();
	   header("Location: ../index.php?InvalidPrivilege=yes");
   }   

   //get employee name
   if(isset($_SESSION['userinfo']['Employee_Name'])){
      $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
   }else{
      $Employee_Name = '';
   }

?>

<?php
    if(isset($_GET['Transaction_Type'])){
	$Transaction_Type = $_GET['Transaction_Type'];
	 $_SESSION['Transaction_Type']=$_GET['Transaction_Type'];
    }else{
	$Transaction_Type = '';
    }
    if(isset($_GET['Payment_Cache_ID'])){
	$Payment_Cache_ID = $_GET['Payment_Cache_ID'];
	$_SESSION['Payment_Cache_ID']=$_GET['Payment_Cache_ID'];
    }else{
	$Payment_Cache_ID = '';
    }


    //get today date
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
    }
?>


<script type="text/javascript">
    function gotolink(){
        var patientlist = document.getElementById('patientlist').value;
        if(patientlist=='OUTPATIENT CASH'){
            document.location = "revenuecenterlaboratorylist.php?Billing_Type=OutpatientCash&PharmacyList=PharmacyListThisForm";
        }else if (patientlist=='OUTPATIENT CREDIT') {
            document.location = "revenuecenterlaboratorylist.php?Billing_Type=OutpatientCredit&PharmacyList=PharmacyListThisForm";
        }else if (patientlist=='INPATIENT CASH') {
            document.location = "revenuecenterlaboratorylist.php?Billing_Type=InpatientCash&PharmacyList=PharmacyListThisForm";
        }else if (patientlist=='INPATIENT CREDIT') {
            document.location = "revenuecenterlaboratorylist.php?Billing_Type=InpatientCredit&PharmacyList=PharmacyListThisForm";
        }else if (patientlist=='PATIENT LIST') {
            document.location = "laboratorypatientlist.php?LaboratoryPatientList=laboratorypatientlistThisPage";
        }else{
            alert("Choose Type Of Patients To View");
        }
    }
</script>

<label style='border: 1px ;padding: 8px;margin-right: 7px;' class='art-button-green'>
<select id='patientlist' name='patientlist' onchange='gotolink()'>
    <option> Select List To View</option>
    <option>
        OUTPATIENT CASH
    </option>
    <option>
        INPATIENT CASH
    </option>
    <option>
        PATIENT LIST
    </option>
</select>
</label> 


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
    <a href='laboratorypatientlist.php?LaboratoryPatientList=laboratorypatientlistThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
<!-- old date function -->
<?php
    /*$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        
        $age = $Today - $original_Date; 
    }*/
?>
<!-- end of old date function -->


<!-- new date function (Contain years, Months and days)--> 
<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	$age ='';
    }
?>
<!-- end of the function -->


 

<!--Approved message-->
    <script type='text/javascript'>
	function approved_Message(){
	    alert('    Successfully Approved! Please notify PATIENT to go to CASHIER for payment and then return to PHARMACY to pick up their medication   ');   
	}
	
	function approved_Message2(){
	    alert('    The Bill is already APPROVED! if not yet, please notify PATIENT to go to CASHIER for payment then return to PHARMACY to pick up medication   ');   
	}
	
	function Payment_approved_Message(){
	    alert('    Patient\'s medication is not yet paid. Please advice PATIENT to go to CASHIER for payment then return to PHARMACY to pick up medication   ');
	}
	
	
    </script>
<!-- end of approved message-->




<!--Getting employee name -->
<?php
    if(isset($_SESSION['userinfo']['Employee_Name'])){
	   $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
	   $Employee_Name = 'Unknown Employee';
    }
?>




<?php
//    select patient information
    if(isset($_GET['Registration_ID'])){ 
        $Registration_ID = $_GET['Registration_ID']; 
        $select_Patient = mysqli_query($conn,"select * from tbl_patient_registration pr, tbl_sponsor sp where
				       sp.Sponsor_ID = pr.Sponsor_ID and
					  pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select_Patient);
        
        if($no>0){
            while($row = mysqli_fetch_array($select_Patient)){ 
                $Registration_ID = $row['Registration_ID'];
                $Old_Registration_Number = $row['Old_Registration_Number'];
                $Title = $row['Title'];
                $Patient_Name = $row['Patient_Name'];
                $Sponsor_ID = $row['Sponsor_ID'];
                $Date_Of_Birth = $row['Date_Of_Birth'];
                $Gender = $row['Gender'];
                $Region = $row['Region'];
                $District = $row['District'];
                $Ward = $row['Ward'];
                $Guarantor_Name = $row['Guarantor_Name'];
                $Member_Number = $row['Member_Number'];
                $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
                $Phone_Number = $row['Phone_Number'];
                $Email_Address = $row['Email_Address'];
                $Occupation = $row['Occupation'];
                $Employee_Vote_Number = $row['Employee_Vote_Number'];
                $Emergence_Contact_Name = $row['Emergence_Contact_Name'];
                $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
                $Company = $row['Company'];
                $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
		
		
		
		
               // echo $Ward."  ".$District."  ".$Ward; exit;
            }
            
	     $age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
	   // if($age == 0){
		
		$date1 = new DateTime($Today);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
        }else{
            $Registration_ID = '';
            $Old_Registration_Number = '';
            $Title = '';
            $Patient_Name = '';
            $Sponsor_ID = ''; 
            $Date_Of_Birth = '';
            $Gender = '';
            $Region = '';
            $District = '';
            $Ward = '';
            $Guarantor_Name = '';
            $Member_Number = '';
            $Member_Card_Expire_Date = '';
            $Phone_Number = '';
            $Email_Address = '';
            $Occupation = '';
            $Employee_Vote_Number = '';
            $Emergence_Contact_Name = '';
            $Emergence_Contact_Number = '';
            $Company = '';
            $Employee_ID = '';
            $Registration_Date_And_Time = ''; 
        }
    }else{
		$Registration_ID = '';
		$Old_Registration_Number = '';
		$Title = '';
		$Patient_Name = '';
		$Sponsor_ID = '';
		$Date_Of_Birth = '';
		$Gender = '';
		$Region = '';
		$District = '';
		$Ward = '';
		$Guarantor_Name = '';
		$Member_Number = '';
		$Member_Card_Expire_Date = '';
		$Phone_Number = '';
		$Email_Address = '';
		$Occupation = '';
		$Employee_Vote_Number = '';
		$Emergence_Contact_Name = '';
		$Emergence_Contact_Number = '';
		$Company = '';
		$Employee_ID = '';
		$Registration_Date_And_Time = '';
	}

    //get sponsor details
    $select_dets = mysqli_query($conn,"select Claim_Number_Status, Folio_Number_Status from tbl_sponsor where Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
    $nm = mysqli_num_rows($select_dets);
    if($nm > 0){
        while ($data = mysqli_fetch_array($select_dets)) {
            $Claim_Number_Status = strtolower($data['Claim_Number_Status']);
            $Folio_Number_Status = strtolower($data['Folio_Number_Status']);
        }
    }else{
        $Claim_Number_Status = '';
        $Folio_Number_Status = '';
    }
?>



<!-- get employee id-->
<?php
    if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }
?>

<?php
    if(strtolower($Guarantor_Name) != 'cash'){
        //get the last folio number if available
        $get_folio = mysqli_query($conn,"select Folio_Number, Claim_Form_Number from tbl_patient_payments where
                                    Registration_ID = '$Registration_ID' and
                                    Receipt_Date = '$Today' and
                                    $Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
        $numrow = mysqli_num_rows($get_folio);
        if($numrow > 0){
            while ($data = mysqli_fetch_array($get_folio)) {
                $Folio_Number = $data['Folio_Number'];
                $Claim_Form_Number = $data['Claim_Form_Number'];
            }
        }else{
            $Folio_Number = '';
            $Claim_Form_Number = '';
        }
    }else{
        $Folio_Number = '';
        $Claim_Form_Number = '';
    }
?>


<script language="javascript" type="text/javascript">
    function searchPatient(Patient_Name){
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=100% src='viewpatientsIframe.php?Patient_Name="+Patient_Name+"'></iframe>";
    }
</script>

<!--get sub department name-->
<br/><br/>
<fieldset>  
    <legend align="right"><b>Revenue Center ~ Laboratory</b></legend>
        <center>
            <table width=100%>
                <tr>
                    <td width='10%' style='text-align: right;'>Patient Name</td>
                    <td width='15%'><input type='text' name='Patient_Name' disabled='disabled' id='Patient_Name' value='<?php echo $Patient_Name; ?>'></td>
                    <td width='12%' style='text-align: right;'>Card Expire Date</td>
                    <td width='15%'><input type='text' name='Card_ID_Expire_Date' disabled='disabled' id='Card_ID_Expire_Date' value='<?php echo $Member_Card_Expire_Date; ?>'></td> 
                    <td width='11%' style='text-align: right;'>Gender</td>
                    <td width='12%'><input type='text' name='Receipt_Number' disabled='disabled' id='Receipt_Number' value='<?php echo $Gender; ?>'></td>
                </tr> 
                <tr>
                    <td style='text-align: right;'>Billing Type</td> 
    				<td id="Billing_Type_Area">
                                        <select name='Billing_Type' id='Billing_Type'>
    				       <?php
    					  $select_bill_type = mysqli_query($conn,
    								  "select Billing_Type
    								  from tbl_laboratory_items_list_cache alc
    								  where alc.Employee_ID = '$Employee_ID' and
    								  Registration_ID = '$Registration_ID' LIMIT 1") or die(mysqli_error($conn));
    							      
    					  $no_of_items = mysqli_num_rows($select_bill_type);
    				          if($no_of_items > 0){
    					     while($data = mysqli_fetch_array($select_bill_type)){
    						$Billing_Type = $data['Billing_Type'];
    					     }
    					     echo "<option selected='selected'>".$Billing_Type."</option>";
    					  }else{
    					     if(strtolower($Guarantor_Name) == 'cash'){
    						echo "<option selected='selected'>Outpatient Cash</option>";
    					     }else{
    						echo "<option selected='selected'>Outpatient Credit</option> 
    						      <option>Outpatient Cash</option>";
    					     }
    					  }
    				       ?>
                                        </select>
                                    </td>
    				 <td style='text-align: right;'>Claim Form Number</td>
    				 <td>
    				    <input type='text' name='Claim_Form_Number' id='Claim_Form_Number' placeholder='Claim Form Number' autocomplete='off' value="<?php echo $Claim_Form_Number; ?>">
    				 </td>
                                    <td style='text-align: right;'>Folio Number</td>
                                    <td>
    				    <input type='text' name='Folio_Number' id='Folio_Number' autocomplete='off' placeholder='Folio Number' value="<?php echo $Folio_Number; ?>">
    				</td>
				</tr>
                <tr>
                    <td style='text-align: right;'>Type Of Check In</td>
                    <td>  
                    <select name='Type_Of_Check_In' id='Type_Of_Check_In' required='required' onchange='examType()' onclick='examType()'> 
                        <option selected='selected'>Laboratory</option> 
                    </select>
                    </td>
                    <td style='text-align: right;'>Patient Age</td>
                    <td><input type='text' name='Patient_Age' id='Patient_Age'  disabled='disabled' value='<?php echo $age; ?>'></td>
                    <td style='text-align: right;'>Registered Date</td>
                    <td><input type='text' name='Folio_Number' id='Folio_Number' disabled='disabled' value='<?php echo $Registration_Date_And_Time; ?>'></td>
                </tr>
                <tr> 
                    <td style='text-align: right;'>Consultant</td>
                    <td>
                        <select name='Consultant_ID' id='Consultant_ID'>
                            <option selected='selected'><?php echo $Employee_Name; ?></option>
                        </select>
                    </td>
                    <td style='text-align: right;'>Sponsor Name</td>
                    <td><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>
                    <td style='text-align: right;'>Phone Number</td>
                    <td><input type='text' name='Phone_Number' id='Phone_Number' disabled='disabled' value='<?php echo $Phone_Number; ?>'></td>
                </tr>
                <tr>
                    <td style='text-align: right;'>Registration Number</td>
                    <td><input type='text' name='Registration_Number' id='Registration_Number' disabled='disabled' value='<?php echo $Registration_ID; ?>'></td>    
                    <td style='text-align: right;'>Member Number</td>
                    <td><input type='text' name='Supervised_By' id='Supervised_By' disabled='disabled' value='<?php echo $Member_Number; ?>'></td> 
                </tr> 
            </table>
        </center>
</fieldset>

<fieldset>
   <table width=100% id="Process_Buttons_Area">
      <?php
	 $select_Transaction_Items = mysqli_query($conn,
				 "select Billing_Type
				    from tbl_laboratory_items_list_cache alc
				    where alc.Employee_ID = '$Employee_ID' and
				    Registration_ID = '$Registration_ID' LIMIT 1") or die(mysqli_error($conn));
			     
	 $no_of_items = mysqli_num_rows($select_Transaction_Items);
      ?>
      <td style='text-align: right;'>
	 <?php
	    if($no_of_items > 0){
	       while($data = mysqli_fetch_array($select_Transaction_Items)){
		  $Billing_Type = $data['Billing_Type'];
	       }
	       if(strtolower($Billing_Type) == 'outpatient cash' || strtolower($Billing_Type) == 'inpatient cash'){
	 ?>
	       <button class="art-button-green" type="button" onclick="Make_Payment_And_Send_To_Laboratory(); clearContent();">MAKE PAYMENT & SEND TO LABORATORY</button>
	 <?php
	       }else{
	 ?>
	       <button class="art-button-green" type="button" onclick="Send_Patient_To_Laboratory(); clearContent();">SEND TO LABORATORY</button>
	 <?php
	       }
	    }
	 ?>
	 <button class="art-button-green" type="button" onclick="openItemDialog(); clearContent();">ADD TEST</button>
      </td>
   </table>
   <img id="loader" style="float:left;display:none" src="images/22.gif"/>
</fieldset>

<fieldset>   
        <center>
            <table width=100%>
		<tr>
		    <td>
                        <form id="saveDiscount">
			<!-- get Sub_Department_ID from the URL -->
			<?php if(isset($_GET['Sub_Department_ID'])) { $Sub_Department_ID = $_GET['Sub_Department_ID']; }else{ $Sub_Department_ID = 0; } ?>
                        <fieldset id="Picked_Items_Fieldset"  style='overflow-y: scroll; height: 200px;'>
                           <center>
			      <table width =100% border=0>
				 <tr id="thead">
				 <td style="text-align: left;" width=5%><b>Sn</b></td>
                 <td><b>Test Name</b></td>
				 <td width="12%"><b>Location</b></td>
				 <td style="text-align: left;" width=25%><b>Comment</b></td>
				 <td style="text-align: right;" width=8%><b>Price</b></td>
				 <td style="text-align: right;" width=8%><b>Quantity</b></td>
				 <td style="text-align: right;" width=8%><b>Sub Total</b></td>
				 <td style="text-align: center;" width=6%><b>Action</b></td></tr>
			<?php
			      $temp = 0;
			      $total = 0;
			      $select_Transaction_Items = mysqli_query($conn,
				 "select Item_Cache_ID, Product_Name, Price, Quantity,Registration_ID,Comment,Sub_Department_ID
				     from tbl_items t, tbl_laboratory_items_list_cache alc
					 where alc.Item_ID = t.Item_ID and
					     alc.Employee_ID = '$Employee_ID' and
						     Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
			     
			     $no_of_items = mysqli_num_rows($select_Transaction_Items);
			     while($row = mysqli_fetch_array($select_Transaction_Items)){
                    $Temp_Sub_Department_ID = $row['Sub_Department_ID'];
                    //get sub department name
                    $select_sub_department = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Temp_Sub_Department_ID'") or die(mysqli_error($conn));
                    $my_num = mysqli_num_rows($select_sub_department);
                    if($my_num > 0){
                        while($rw = mysqli_fetch_array($select_sub_department)){
                            $Sub_Department_Name = $rw['Sub_Department_Name'];
                        }
                    }else{
                        $Sub_Department_Name = '';
                    }
				 echo "<tr><td>".++$temp."</td>";
                 echo "<td>".$row['Product_Name']."</td>";
				 echo "<td>".$Sub_Department_Name."</td>";
				 echo "<td>".$row['Comment']."</td>";
				 echo "<td style='text-align:right;'>".number_format($row['Price'])."</td>";
				 echo "<td style='text-align:right;'>".$row['Quantity']."</td>";
				 echo "<td style='text-align:right;'>".number_format($row['Price'] * $row['Quantity'])."</td>";
			     ?>
				 <td style="text-align: center;"> 
				    <input type='button' style='color: red; font-size: 10px;' value='X' onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Item_Cache_ID']; ?>,<?php echo $row['Registration_ID']; ?>)'>
				 </td>
			     <?php
				 $total = $total + ($row['Price'] * $row['Quantity']);
			     }echo "</tr></table>";
			     ?>
                        </fieldset>
		    </td>
		</tr>
	       <tr>
		  <td style='text-align: right; width: 70%;' id='Total_Area'>
		     <h4>Total : <?php echo number_format($total); ?></h4>
		  </td>
	       </tr>
           </table>
        </center>
</fieldset>

<div id="Add_Laboratory_Items" style="width:50%;" >
   <table width=100% style='border-style: none;'>
      <tr>
	 <td width=40%>
	    <table width=100% style='border-style: none;'>
	       <tr>
		  <td>
		     <b>Category : </b>
		     <select name='Item_Category_ID' id='Item_Category_ID' onchange='getItemsList(this.value)' onchange='Calculate_Amount()' onkeypress='Calculate_Amount()'>
			 <option selected='selected'></option>
			 <?php
			     $data = mysqli_query($conn,"
    				    select ic.Item_Category_Name, ic.Item_Category_ID
    				    from tbl_item_category ic, tbl_item_subcategory isu, tbl_items i where
                        ic.Item_category_ID = isu.Item_category_ID and
                        i.Item_Subcategory_ID = isu.Item_Subcategory_ID and
                        i.Consultation_Type = 'Laboratory' group by ic.Item_Category_ID order by ic.Item_Category_Name
				     ") or die(mysqli_error($conn));
			     while($row = mysqli_fetch_array($data)){
				 echo '<option value="'.$row['Item_Category_ID'].'">'.$row['Item_Category_Name'].'</option>';
			     }
			 ?>   
		     </select>
		  </td>
	       </tr>
	       <tr>
		  <td>
		     <input type='text' id='Search_Value' name='Search_Value' autocomplete='off' onkeyup='getItemsListFiltered(this.value)' placeholder='Enter Item Name'>
		  </td>
	       </tr>
	       <tr>
		  <td>
		     <fieldset style='overflow-y: scroll; height: 270px;' id='Items_Fieldset'>
			<table width=100%>
			<?php
			   $result = mysqli_query($conn,"SELECT * FROM tbl_items where Consultation_Type = 'Laboratory' order by Product_Name limit 200");
			   while($row = mysqli_fetch_array($result)){
			       echo "<tr>
				   <td style='color:black; border:2px solid #ccc;text-align: left;' width=5%>"; ?>
				       
				       <input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $row['Product_Name']; ?>' onclick="Get_Item_Name(this.value,<?php echo $row['Item_ID']; ?>); Get_Item_Price(<?php echo $row['Item_ID']; ?>,'<?php echo $Guarantor_Name; ?>');">
				  
			       <?php
				 echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'><label for='".$row['Item_ID']."'>".$row['Product_Name']."</label></td></tr>";
			   }
			?>
			</table>
		     </fieldset>
		  </td>
	       </tr>
	    </table>
	 </td>
	 <td>
	    <table width=100% border=0>
           <tr>
          <td style='text-align: right;' width=30%>Test Name</td>
          <td>
             <input type='text' name='Item_Name' id='Item_Name' readyonly='readyonly' placeholder='Item Name'>
             <input type='hidden' name='Item_ID' id='Item_ID' value=''>
          </td>
           </tr>
           <tr>
          <td style='text-align: right;' width=30%>Location</td>
          <td>
             <select name="Sub_Department_ID" id="Sub_Department_ID">
                <?php
                    $select = mysqli_query($conn,"select sd.Sub_Department_ID, sd.Sub_Department_Name from
                                            tbl_department d, tbl_sub_department sd where
                                            d.Department_ID = sd.Department_ID and
                                            d.Department_Location = 'Laboratory'") or die(mysqli_error($conn));
                    $num = mysqli_num_rows($select);
                    if($num > 0){
                        while ($data = mysqli_fetch_array($select)) {
                ?>
                            <option value='<?php echo $data['Sub_Department_ID']; ?>'><?php echo $data['Sub_Department_Name']; ?></option>
                <?php
                        }
                    }
                ?> 
             </select>
          </td>
           </tr>
	       <tr>
		  <td style='text-align: right;'>Price</td>
		  <td>
		     <input type='text' name='Price' id='Price' readonly='readonly' placeholder='Price'>
		  </td>
	       </tr>
	       <tr>
		  <td style='text-align: right;'>Quantiy</td>
		  <td>
		     <input type='text' name='Quantity' id='Quantity' value='1' placeholder='Quantity' autocomplete='off'>
		  </td>
	       </tr>
	       <tr>
		  <td colspan=2>
		     <textarea name='Comment' id='Comment' placeholder='Comment'></textarea>
		  </td>
	       </tr>
	       <tr>
		  <td colspan=2 style='text-align: center;'>
		     <input type='button' name='Submit' id='Submit' class='art-button-green' value='ADD TEST' onclick='Get_Selected_Item()'>
		  </td>
	       </tr>
	    </table>
	 </td>
      </tr>
   </table>
</div>

<script type='text/javascript'>
   function Make_Payment_And_Send_To_Laboratory() {
        var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
        var Claim_Form_Number = document.getElementById("Claim_Form_Number").value;
        var Folio_Number = document.getElementById("Folio_Number").value;
        var Consultant_ID = document.getElementById("Consultant_ID").value;
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Consultant_ID = document.getElementById("Consultant_ID").value;
      
        if (Guarantor_Name == 'NHIF' && (Claim_Form_Number == '' || Claim_Form_Number == null || Folio_Number == '' || Folio_Number == null)){
	 
            if(Claim_Form_Number=='' || Claim_Form_Number == null){
                document.getElementById("Claim_Form_Number").focus();
                document.getElementById("Claim_Form_Number").style = 'border: 3px solid red';
            }else{
                document.getElementById("Claim_Form_Number").style = 'border: 3px';
            }

            if(Folio_Number=='' || Folio_Number == null){
                document.getElementById("Folio_Number").focus();
                document.getElementById("Folio_Number").style = 'border: 3px solid red';
            }else{
                document.getElementById("Folio_Number").style = 'border: 3px';
            }

            if(Consultant_ID=='' || Consultant_ID == null){
                document.getElementById("Consultant_ID").focus();
                document.getElementById("Consultant_ID").style = 'border: 3px solid red';
            }else{
                document.getElementById("Consultant_ID").style = 'border: 3px';
            }
        }else{
            var r = confirm("Are you sure you want to make payment and send this patient to laboratory\nClick OK to proceed or Cancel to stop process?");
        if(r == true){
            document.location = 'Make_Payment_And_Send_To_Laboratory.php?Registration_ID='+Registration_ID+'&Folio_Number='+Folio_Number+'&Claim_Form_Number='+Claim_Form_Number+'&Consultant_ID='+Consultant_ID;
        }
    }
   }
</script>

<script>
    function Confirm_Remove_Item(Item_Name,Item_Cache_ID,Registration_ID){
      var Confirm_Message = confirm("Are you sure you want to remove \n"+Item_Name);
      var Registration_ID = '<?php echo $Registration_ID; ?>';
      if (Confirm_Message == true) {
	      if(window.XMLHttpRequest) {
		      My_Object_Remove_Item = new XMLHttpRequest();
	      }else if(window.ActiveXObject){
		      My_Object_Remove_Item = new ActiveXObject('Micrsoft.XMLHTTP');
		      My_Object_Remove_Item.overrideMimeType('text/xml');
	      }
	      My_Object_Remove_Item.onreadystatechange = function (){
		      data6 = My_Object_Remove_Item.responseText;
		      if (My_Object_Remove_Item.readyState == 4) {
			   document.getElementById('Picked_Items_Fieldset').innerHTML = data6;
                update_total(Registration_ID);
                update_Billing_Type();
			  //Update_Claim_Form_Number();
			  //update_total(Registration_ID);
		      }
	      }; //specify name of function that will handle server response........
		      
	      My_Object_Remove_Item.open('GET','Laboratory_Remove_Item_From_List.php?Item_Cache_ID='+Item_Cache_ID+'&Registration_ID='+Registration_ID,true);
	      My_Object_Remove_Item.send();
      }
   }
</script>

<script>
   function update_total() {
      var Registration_ID = '<?php echo $Registration_ID; ?>';
      if(window.XMLHttpRequest) {
	 My_Object_Update_Total = new XMLHttpRequest();
       }else if(window.ActiveXObject){
	       My_Object_Update_Total = new ActiveXObject('Micrsoft.XMLHTTP');
	       My_Object_Update_Total.overrideMimeType('text/xml');
       }
	       
       My_Object_Update_Total.onreadystatechange = function (){
	       data600 = My_Object_Update_Total.responseText;
	       if (My_Object_Update_Total.readyState == 4) {
		    document.getElementById('Total_Area').innerHTML = data600;
		     //update_total(Registration_ID);
		     //update_Billing_Type(Registration_ID);
		     //Update_Claim_Form_Number();
	       }
       }; //specify name of function that will handle server response........
	       
       My_Object_Update_Total.open('GET','Laboratory_Update_Total.php?Registration_ID='+Registration_ID,true);
       My_Object_Update_Total.send();
   }
</script>

<script>
   function update_Billing_Type() {
      var Registration_ID = '<?php echo $Registration_ID; ?>';
      if(window.XMLHttpRequest) {
	 My_Object_Update_Billing_Type = new XMLHttpRequest();
      }else if(window.ActiveXObject){
	 My_Object_Update_Billing_Type = new ActiveXObject('Micrsoft.XMLHTTP');
	 My_Object_Update_Billing_Type.overrideMimeType('text/xml');
      }

      My_Object_Update_Billing_Type.onreadystatechange = function (){
	 data6001 = My_Object_Update_Billing_Type.responseText;
	 if (My_Object_Update_Billing_Type.readyState == 4) {
	      document.getElementById('Billing_Type_Area').innerHTML = data6001;
	 }
      }; //specify name of function that will handle server response........
	       
      My_Object_Update_Billing_Type.open('GET','laboratory_update_Billing_Type.php?Registration_ID='+Registration_ID,true);
      My_Object_Update_Billing_Type.send();
   }
</script>

<script>
	function Get_Item_Name(Item_Name,Item_ID){
	    document.getElementById('Quantity').value = '';
	    document.getElementById('Comment').value = '';

	    var Temp = '';
	    if(window.XMLHttpRequest) {
            myObjectGetItemName = new XMLHttpRequest();
	    }else if(window.ActiveXObject){
    		myObjectGetItemName = new ActiveXObject('Micrsoft.XMLHTTP');
    		myObjectGetItemName.overrideMimeType('text/xml');
	    }
	    
	    document.getElementById("Item_Name").value = Item_Name;
	    document.getElementById("Item_ID").value = Item_ID;
        document.getElementById("Quantity").focus();
	}
</script>

<script>
   function vieweRemovedItem(){
          //alert("item");
         
     $.ajax({
         type: 'POST',
         url: "change_items_pharmacy_list.php",
         data: "viewRemovedItem=true",
         dataType:"json",
         success: function (data) {
            $('#patientItemsList').html(data['data']);
	    $('#totalAmount').html(data['total_amount']);	          
         },error: function (jqXHR, textStatus, errorThrown) {
           alert(errorThrown);               
         }
     });
 }
 
function addItem(item){
      $.ajax({
         type: 'POST',
         url: "change_items_pharmacy_list.php",
         data: "readdItem="+item,
         success: function (data) {
            showItem();
         },error: function (jqXHR, textStatus, errorThrown) {
           alert(errorThrown);               
         }
     });
 }
</script>


<script>
   function openItemDialog(){
      $("#Add_Laboratory_Items").dialog("open");
   }
</script>

<script>
   function clearContent() {
      document.getElementById("Quantity").value = '';
      document.getElementById("Item_Name").value = '';
      document.getElementById("Item_ID").value = '';
      document.getElementById("Price").value = '';
      document.getElementById("Comment").value = '';
      document.getElementById("Search_Value").value = '';
   }
</script>


<script>
   function Get_Item_Price(Item_ID,Guarantor_Name){
      var Billing_Type = document.getElementById("Billing_Type").value;
      //alert(Item_ID);
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
      
      myObject.open('GET','Get_Items_Price.php?Item_ID='+Item_ID+'&Guarantor_Name='+Guarantor_Name+'&Billing_Type='+Billing_Type,true);
      myObject.send();
  }
</script>




<script>
   function update_process_buttons(Registration_ID){
      
      if(window.XMLHttpRequest) {
	 my_Object_Update_Process = new XMLHttpRequest();
      }else if(window.ActiveXObject){ 
	 my_Object_Update_Process = new ActiveXObject('Micrsoft.XMLHTTP');
	 my_Object_Update_Process.overrideMimeType('text/xml');
      }
      
      my_Object_Update_Process.onreadystatechange = function (){
	  data = my_Object_Update_Process.responseText;
	  
	  if (my_Object_Update_Process.readyState == 4) { 
	    document.getElementById('Process_Buttons_Area').innerHTML = data;
	  }
      }; //specify name of function that will handle server response........
      
      my_Object_Update_Process.open('GET','Laboratory_Update_Process_Button.php?Registration_ID='+Registration_ID,true);
      my_Object_Update_Process.send();
  }
</script>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="script.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="script.responsive.js"></script>

<script>
   $(document).ready(function(){
      $("#Add_Laboratory_Items").dialog({ autoOpen: false, width:950,height:450, title:'ADD NEW TEST',modal: true});
   });
</script>





<script>
    function Get_Selected_Item(){
        var Billing_Type = document.getElementById("Billing_Type").value;
        var Item_ID = document.getElementById("Item_ID").value;
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        var Quantity = document.getElementById("Quantity").value;
        var Registration_ID = <?php echo $Registration_ID; ?>;
        var Comment =  document.getElementById("Comment").value;
        var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
        var Consultant_ID = document.getElementById("Consultant_ID").value;
        var Claim_Form_Number = document.getElementById("Claim_Form_Number").value;
        var Sub_Department_ID = document.getElementById("Sub_Department_ID").value;
        

	if (Registration_ID != '' && Registration_ID != null && Item_Name != '' && Item_Name != null && Item_ID != '' && Item_ID != null && Quantity != '' && Quantity != null && Billing_Type != '' && Billing_Type != null) {
	     if(window.XMLHttpRequest){
	       myObject2 = new XMLHttpRequest();
	     }else if(window.ActiveXObject){
	       myObject2 = new ActiveXObject('Micrsoft.XMLHTTP');
	       myObject2.overrideMimeType('text/xml');
	     }
	     myObject2.onreadystatechange = function (){
	       data = myObject2.responseText;
		 
	       if (myObject2.readyState == 4) {
		  //alert("One Item Added");
		  document.getElementById('Picked_Items_Fieldset').innerHTML = data;
		  //update_Billing_Type(Registration_ID);
		  //Update_Claim_Form_Number();
		  document.getElementById("Item_Name").value = '';
		  document.getElementById("Item_ID").value = '';
		  document.getElementById("Quantity").value = '';
		  document.getElementById("Price").value = '';
		  document.getElementById("Comment").value = '';
		  document.getElementById("Search_Value").focus();
		  alert("Item Added Successfully");
		  update_billing_type(Registration_ID);
		  update_total(Registration_ID);
		  update_process_buttons(Registration_ID);
	      }
	     }; //specify name of function that will handle server response........
	     
	     //myObject.open('GET','Perform_Reception_Transaction.php?Registration_ID='+Registration_ID+'&Item_ID='+Item_ID+'&Type_Of_Check_In='+Type_Of_Check_In+'&direction='+direction+'&Quantity='+Quantity'&Consultant='+Consultant,true);
	     myObject2.open('GET','Laboratory_Add_Selected_Item.php?Registration_ID='+Registration_ID+'&Item_ID='+Item_ID+'&Quantity='+Quantity+'&Consultant_ID='+Consultant_ID+'&Billing_Type='+Billing_Type+'&Guarantor_Name='+Guarantor_Name+'&Sponsor_ID='+Sponsor_ID+'&Claim_Form_Number='+Claim_Form_Number+'&Billing_Type='+Billing_Type+'&Comment='+Comment+'&Sub_Department_ID='+Sub_Department_ID,true);
	     myObject2.send();
	     
	 }else if (Registration_ID != '' && Registration_ID != null && (Item_Name == '' || Item_Name == null || Item_ID == '' || Item_ID == null) != '' && Quantity != '' && Quantity != null){
	     alertMessage();
	 }else{
	     if(Quantity=='' || Quantity == null){
	       document.getElementById("Quantity").focus();
	       document.getElementById("Quantity").style = 'border: 3px solid red';
	     }
        //if(Comment=='' || Comment == null){
	    //   document.getElementById("Comment").focus();
	    //   document.getElementById("Comment").style = 'border: 3px solid red';
	    // }
	 }
     }
</script>

<script type="text/javascript">
    function alertMessage(){
        alert("Select Test First");
        document.getElementById("Quantity").value = '';
    }
</script>

<script>
   function getItemsList(Item_Category_ID){
      document.getElementById("Search_Value").value = ''; 
      document.getElementById("Item_Name").value = '';
      document.getElementById("Item_ID").value = '';
      var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
      
      if(window.XMLHttpRequest) {
	  myObject = new XMLHttpRequest();
      }else if(window.ActiveXObject){ 
	  myObject = new ActiveXObject('Micrsoft.XMLHTTP');
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
      myObject.open('GET','Get_List_Of_Laboratory_Items_List.php?Item_Category_ID='+Item_Category_ID+'&Guarantor_Name='+Guarantor_Name,true);
      myObject.send();
   }
</script>




<script>
   function update_billing_type(Registration_ID){
      if(window.XMLHttpRequest) {
	  myObjectUpdateBilling = new XMLHttpRequest();
      }else if(window.ActiveXObject){ 
	  myObjectUpdateBilling = new ActiveXObject('Micrsoft.XMLHTTP');
	  myObjectUpdateBilling.overrideMimeType('text/xml');
      }

      myObjectUpdateBilling.onreadystatechange = function (){
	 data2605 = myObjectUpdateBilling.responseText;
	 if (myObjectUpdateBilling.readyState == 4) {
	     document.getElementById('Billing_Type').innerHTML = data2605;
	 }
      }; //specify name of function that will handle server response........
      myObjectUpdateBilling.open('GET','Laboratory_Update_Billing_Type.php?Registration_ID='+Registration_ID,true);
      myObjectUpdateBilling.send();
   }
</script>




<script>
   function getItemsListFiltered(Item_Name){
      document.getElementById("Item_Name").value = '';
      document.getElementById("Item_ID").value = '';
      document.getElementById("Comment").value = '';
      document.getElementById("Quantity").value = '';
      var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
      
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
      myObject.open('GET','Get_List_Of_Laboratory_Filtered_Items.php?Item_Category_ID='+Item_Category_ID+'&Item_Name='+Item_Name+'&Guarantor_Name='+Guarantor_Name,true);
      myObject.send();
   }
</script>


<script type='text/javascript'>
   function Send_Patient_To_Laboratory() {
        var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
        var Claim_Form_Number = document.getElementById("Claim_Form_Number").value;
        var Folio_Number = document.getElementById("Folio_Number").value;
        var Consultant_ID = document.getElementById("Consultant_ID").value;
        var Registration_ID = '<?php echo $Registration_ID; ?>';

        var Claim_Number_Status = '<?php echo $Claim_Number_Status; ?>';
        var Folio_Number_Status = '<?php echo $Folio_Number_Status; ?>';

        if((Folio_Number_Status == 'mandatory' && (Folio_Number == '' || Folio_Number == null)) || (Claim_Number_Status == 'mandatory' && (Claim_Form_Number == '' || Claim_Form_Number == null))){
        	if(Claim_Number_Status == 'mandatory' && (Claim_Form_Number =='' || Claim_Form_Number == null)){
        	    document.getElementById("Claim_Form_Number").focus();
        	    document.getElementById("Claim_Form_Number").style = 'border: 3px solid red';
        	 }else{
        	    document.getElementById("Claim_Form_Number").style = 'border: 3px';
        	 }
        	 
        	 if(Folio_Number_Status == 'mandatory' && (Folio_Number=='' || Folio_Number == null)){
        	    document.getElementById("Folio_Number").focus();
        	    document.getElementById("Folio_Number").style = 'border: 3px solid red';
        	 }else{
        	    document.getElementById("Folio_Number").style = 'border: 3px';
        	 }
        }else{
            var r = confirm("Are you sure you want to send tests to laboratory?\nClick OK to proceed or Cancel to stop process");
            if(r == true){
                document.location = 'Send_Patient_To_Laboratory.php?Registration_ID='+Registration_ID+'&Folio_Number='+Folio_Number+'&Claim_Form_Number='+Claim_Form_Number+'&Consultant_ID='+Consultant_ID;
            }
        }
   }
</script>

<?php
    include("./includes/footer.php");
?>