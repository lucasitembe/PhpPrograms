<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    include("./functions/items.php");
    include("./functions/itemcategory.php");

    if(!isset($_SESSION['userinfo'])){
		@session_destroy();
		header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='addnewcategory.php?AddNewCategory=AddNewCategoryThisPage' class='art-button'>
        NEW CATEGORY ITEM
    </a>
<?php  } } ?>


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='addnewsubitemcategory.php?AddNewSubategoryItem=AddNewSubategoryItemThisPage' class='art-button'>
        NEW SUBCATEGORY ITEM
    </a>
<?php  } } ?>


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='edititemlist.php?EditItem=EditItemThisPage' class='art-button'>
        BACK
    </a>
<?php  } } ?>

<script type="text/javascript" language="javascript">
   /*  function getSubcategory(Item_Category_ID) {
	    if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    }
	    
	
	    mm.onreadystatechange= AJAXP; //specify name of function that will handle server response....
	    mm.open('GET','GetSubcategory.php?Item_Category_ID='+Item_Category_ID,true);
	    mm.send();
	}
    function AJAXP() {
	var data = mm.responseText; 
	//alert(data);
	document.getElementById('Item_Subcategory').innerHTML = data;	
    } */
	
	function getSubcategory(Item_Category_ID){
	if (window.XMLHttpRequest) {
                myObject = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }
            //alert(data);

            myObject.onreadystatechange = function () {
                data265 = myObject.responseText;
                if (myObject.readyState == 4) {
				
                   document.getElementById('Item_Subcategory').innerHTML = data265;
                }
            }; //specify name of function that will handle server response........
            myObject.open('GET', 'GetSubcategory.php?Item_Category_ID='+Item_Category_ID, true);
            myObject.send();
	}
	
	
</script>

<br/><br/>

<center>

<?php
    //get all item details based on item id
    if(isset($_GET['Item_ID'])){
	$Item_ID = $_GET['Item_ID'];
    }else{
	$Item_ID = '';
    }
    
    $Results = mysqli_query($conn,"select * from tbl_items i,tbl_item_subcategory isc, tbl_item_category ic
				where i.Item_Subcategory_ID = isc.Item_Subcategory_ID and
				    isc.Item_category_ID = ic.Item_category_ID and
					i.Item_ID = '$Item_ID'");
    $no = mysqli_num_rows($Results);
    if($no > 0){
	while($row = mysqli_fetch_array($Results)){
	    $Item_Type = $row['Item_Type'];
	    $NHIFItem_Type = $row['NHIFItem_Type'];
	    $Product_Code = $row['Product_Code'];
	    $Unit_Of_Measure = $row['Unit_Of_Measure'];
	    $Product_Name = $row['Product_Name'];
            $Tax1 = $row['Tax'];
            $Statevalue = $row['Statevalue'];
            $Liquid_Item_Value = $row['Liquid_Item_Value'];
	    $Package_Type = $row['Package_Type'];
	    $Item_Category_Name = $row['Item_Category_Name'];
	    $Item_Subcategory_ID = $row['Item_Subcategory_ID'];
	    $Item_Subcategory_Name = $row['Item_Subcategory_Name'];
	    $Reoder_Level = $row['Reoder_Level'];
	    $Item_Status = $row['Status'];
	    $Consultation_Type = $row['Consultation_Type'];
	    $Can_Be_Substituted_In_Doctors_Page = $row['Can_Be_Substituted_In_Doctors_Page'];
	    $Ward_Round_Item = $row['Ward_Round_Item'];
	    $daily_add_automaticaly_in_inpatient_bill = $row['daily_add_automaticaly_in_inpatient_bill'];
            $Classification = $row['Classification'];
            $Can_Be_Sold = $row['Can_Be_Sold'];
            $Can_Be_Stocked = $row['Can_Be_Stocked'];
	    $Ct_Scan_Item = $row['Ct_Scan_Item'];
	    $consultation_Item = $row['consultation_Item'];
	    $Particular_Type = $row['Particular_Type'];
	    $Seen_On_Allpayments=$row['Seen_On_Allpayments'];
            $Free_Consultation_Item = $row['Free_Consultation_Item'];
            $route_type = $row['route_type'];
	    $Facility_Product_Code = $row['Facility_Product_Code'];
	}
    }else{
	    $Item_Type = 'Unknown Type';
	    $NHIFItem_Type = 'Unkown';
	    $Product_Name = 'Unknown Product Name';  
	    $Package_Type = 'Unkown Package Type';
		$Tax1 = 'Unknown Tax';
	    $Product_Code = "Unknown Product Code";  
	    $Item_Category_Name = 'Unknown Coategory';
	    $Item_Subcategory_Name = "Unknown SubCategory";
	    $Item_Subcategory_ID = 0;
	    $Item_Status = 'Unknown Status'; 
	    $Can_Be_Substituted_In_Doctors_Page = 'no';
	    $Unit_Of_Measure = "Unknown Unit Of Measure"; 
	    $Reoder_Level = "Unknown Reoder Level";
	    $Item_Status = "Unknown Status";
	    $Consultation_Type = 'Unknown Consultation Type';
	    $Ward_Round_Item = "no";
	    $daily_add_automaticaly_in_inpatient_bill= "no";
        $Classification = "";
        $Can_Be_Sold = "no";
        $Can_Be_Stocked = "no";
		$consultation_Item = 'no';
		$Ct_Scan_Item = "no";
        $Particular_Type = "";
		$Seen_On_Allpayments="no";
        $Free_Consultation_Item = '0';
        $Statevalue = '';
        $Liquid_Item_Value = '';
	    $Facility_Product_Code = '';
    }

    $existence=mysqli_query($conn,"SELECT Should_be_ordered_by_specialist FROM tbl_item_price WHERE Item_ID='$Item_ID' AND Sponsor_ID='$Sponsor_ID'") or die(mysqli_error($conn));
    $num_rows=  mysqli_num_rows($existence);
    if($num_rows>0){
        while($row = mysqli_fetch_assoc($existence)){
            $Should_be_ordered_by_specialist = $row['Should_be_ordered_by_specialist'];
        }
    }else{
        $Should_be_ordered_by_specialist='No';
    }
?>

 <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    
<table width=70%>
    <tr>
        <td>
            <fieldset>  
            <legend align=center><b>EDIT ITEM</b></legend>
        
            <table width=100%>
                <tr>
                    <td width=40% style="text-align:right;"><b>Item Type</b></td>
                    <td width=60%>
                        <select name='itemtype' id='itemtype' onchange="Update_Option()">
                            <?php
                                $Item_Type_List = Get_Item_Type();
                                foreach($Item_Type_List as $Item_Typ) {
                                    ($Item_Type == $Item_Typ['Name']) ? $selected="selected":$selected="";
                                    echo "<option value='{$Item_Typ['Name']}' {$selected}> {$Item_Typ['Description']} </option>";
                                }
                            ?>
                        </select>
                    </td> 
                </tr>
                <tr style="display: none">
                    <td width=25% style="text-align:right;"><b>NHIF Item Type</b></td>
                    <td width=75%>
                        <select name='NHIFItem_Type' id='NHIFItem_Type'>
			    <option value='<?php echo $NHIFItem_Type;?>' selected="selected"><?php if($NHIFItem_Type == '1'){
					echo "1 Registration/Consultation Charges";
					}else if($NHIFItem_Type == '2'){ echo "2 Inpatient Charges";
					}else if($NHIFItem_Type == '3'){ echo "3 Medicine and Consumables";
					}else if($NHIFItem_Type == '4'){ echo "4 Sergical Charges";
					}else if($NHIFItem_Type == '5'){ echo "5 Diagnostic Examinations";
					}else if($NHIFItem_Type == '6'){ echo "6 Procedural Charges";
					}else if($NHIFItem_Type == '7'){ echo "7 Other Charges";
					} ?>
			    </option>
			    <?php if($NHIFItem_Type != '1'){ echo "<option value'1'> 1 Registration/Consultation Charges</option>";} ?>
			    <?php if($NHIFItem_Type != '2'){ echo "<option value'2'> 2 Inpatient Charges</option>";} ?>
			    <?php if($NHIFItem_Type != '3'){ echo "<option value'3'> 3 Medicine and Consumables</option>";} ?>
			    <?php if($NHIFItem_Type != '4'){ echo "<option value'4'> 4 Sergical Charges</option>";} ?>
			    <?php if($NHIFItem_Type != '5'){ echo "<option value'5'> 5 Diagnostic Examinations</option>";} ?>
			    <?php if($NHIFItem_Type != '6'){ echo "<option value'6'> 6 Procedural Charges</option>";} ?>
			    <?php if($NHIFItem_Type != '7'){ echo "<option value'7'> 7 Other Charges</option>";} ?>
                        </select>
                    </td> 
                </tr>
                <tr>
                    <td style="text-align:right;"><b>Product Code</b></td>
                    <td>
                        <input type='text' name='Product_Code' id='Product_Code' value='<?php echo $Product_Code; ?>' placeholder='Enter Product Code'>
                    </td>
                </tr>
		<tr>
                    <td style="text-align:right;"><b>Item Product Code</b></td>
                    <td>
                        <input type='text' name='Facility_Product_Code' id='Facility_Product_Code' value='<?php echo $Facility_Product_Code; ?>' placeholder='Enter Item  Product Code'>
                    </td>
                </tr>

                <tr>
                    <td style="text-align:right;"><b>Unit Of Measure</b></td>
                    <td>
                        <select name='Unit_Of_Measure' id='Unit_Of_Measure' placeholder='Enter Unit Of Measure'>
                            <?php
                                $Unit_Of_Measure_List = Get_Unit_Of_Measure();
                                foreach($Unit_Of_Measure_List as $UOM) {
                                    ($Unit_Of_Measure == $UOM['Name']) ? $selected="selected" : $selected="";
                                    echo "<option value='{$UOM['Name']}' {$selected}> {$UOM['Description']} </option>";
                                }
                            ?>
			            </select>
                    </td>
                </tr>
				    <tr>
                                <td style="text-align:right;"><b>Package Type</b></td>
                                <td colspan="2">
                                    <input type='text' name='Package_Type' id='Package_Type' value='<?php echo $Package_Type; ?>'  placeholder='Enter Package type' autocomplete='off'>
                                </td>
                            </tr>
                <tr>
                    <td style="text-align:right;"><b>Product Name</b></td>
                    <td>
                        <input type='text' name='Product_Name' id='Product_Name' required='required' value='<?php echo $Product_Name; ?>' placeholder='Enter Product Name'>
                    </td>
                </tr>
                        <tr>
                                <td style="text-align:right;"><b>Taxable/Non Taxable</b></td>
                                <td colspan="2">
                                    <select name='Tax_type' id='Tax_type'>
									<option  value="<?php echo $Tax1; ?>"><?php if($Tax1=="non_taxable"){ echo "Non Taxable";} else if($Tax1=="taxable"){ echo "Taxable";}else echo $Tax1;?></option>
                                    <option value="non_taxable" >Non Taxable</option>
                                    <option  value="taxable">Taxable</option>
                                    </select>
                                </td>
                        </tr>
                <tr>
                    <td style="text-align:right;"><b>Classification</b></td>
                    <td colspan="2">
                        <select name='Item_Classification' id='Item_Classification'>
                            <option selected='selected'></option>
                            <?php
                            $Item_Classification_List = Get_Item_Classification();
                            foreach($Item_Classification_List as $Item_Classification) {
                                ($Item_Classification['Name'] == $Classification) ? $selected = "selected" : $selected = "";
                                echo "<option value='{$Item_Classification['Name']}' {$selected} > {$Item_Classification['Description']} </option>";
                            }
                            ?>
                        </select>&nbsp;&nbsp;&nbsp;<b>Select Classification</b>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:right;"><b>Item Category</b></td>
                    <td> 
			            <select name='Item_Category' id='Item_Category' onchange='getSubcategory(this.value)'>
                            <?php
                                $Item_Category_List = Get_Item_Category_All();
                                foreach($Item_Category_List as $Item_Category) {
                                    ($Item_Category['Item_Category_Name'] == $Item_Category_Name) ? $selected="selected" : $selected="";
                                    echo "<option value='{$Item_Category['Item_Category_ID']}' {$selected}>
                                            {$Item_Category['Item_Category_Name']} </option>";
                                }
                            ?>
			            </select>&nbsp;&nbsp;&nbsp;<b>Select Category</b>
		            </td>
                </tr>
                <tr>
                    <td style="text-align:right;"><b>Sub Category</b></td>
                    <td>
                        <select name='Item_Subcategory' id='Item_Subcategory'>
                            <option selected='selected' value='<?php echo $Item_Subcategory_ID; ?>'>
                                <?php echo $Item_Subcategory_Name; ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:right;"><b>Status</b></td>
                    <td>
                        <select name='Item_Status' id='Item_Status' required='required'>
                            <option selected='selected'><?php echo $Item_Status; ?></option>
			    <option>Available</option>
                            <option>Not Available</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:right;"><b>Particular Type</b></td>
                    <td colspan="2">
                        <select name='Particular_Type' id='Particular_Type'>
                            <option <?php if($Particular_Type == 'Cost Sharing'){ echo "selected='selected'"; } ?>>Cost Sharing</option>
                            <option <?php if($Particular_Type == 'DRF'){ echo "selected='selected'"; } ?>>DRF</option>
                            <option <?php if($Particular_Type == 'Fast Track'){ echo "selected='selected'"; } ?>>Fast Track</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:right;"><b>Reoder Level</b></td>
                    <td><input type='text' name='Reoder_Level' id='Reoder_Level' value='<?php echo $Reoder_Level; ?>' placeholder='Enter Reoder Level'></td>
                </tr>
               <!-- <tr>
                    <td><b>Nature Of The Item</b></td>
                    <td>
                        <select name='Consultation_Type' id='Consultation_Type' required='required'>
			    <option selected='selected'><?php echo $Consultation_Type; ?></option>
			    
			    <?php //if(strtolower($Consultation_Type) != 'pharmacy'){ echo '<option>Pharmacy</option>'; } ?>
			    <?php //if(strtolower($Consultation_Type) != 'laboratory'){ echo '<option>Laboratory</option>'; } ?>
			    <?php //if(strtolower($Consultation_Type) != 'radiology'){ echo '<option>Radiology</option>'; } ?>
			    <?php //if(strtolower($Consultation_Type) != 'procedure'){ echo '<option>Procedure</option>'; } ?>
			    <?php //if(strtolower($Consultation_Type) != 'surgery'){ echo '<option>Surgery</option>'; } ?>
			    <?php //if(strtolower($Consultation_Type) != 'others'){ echo '<option>Others</option>'; } ?>
			</select>
                    </td>
                </tr>-->
                <tr>
                    <td style="text-align:right;"><b>Consultation Type</b></td>
                    <td>
                        <select name='Consultation_Type' id='Consultation_Type'>
                            <option selected='selected'></option>
                            <?php
                                $Item_Consultation_Type = Get_Item_Consultation_Type();
                                foreach($Item_Consultation_Type as $Consultation_T) {
                                    ($Consultation_T['Name'] == $Consultation_Type) ? $selected="selected" : $selected="";
                                    echo "<option value='{$Consultation_T['Name']}' {$selected}> {$Consultation_T['Description']} </option>";
                                }
                                ?>       <option>Consultation</option>
			            </select>
                    </td>
                </tr>
				<tr>
					<td  style="text-align:right;"><b>Ward Round Item</b></td>
					<td>
						<input type='checkbox' name='Ward_Round_Item' id='Ward_Round_Item'
							 <?php if(strtolower($Ward_Round_Item) == 'yes') { echo 'checked="checked"'; } ?> 
						value='yes'>
					</td>
				</tr>
				<tr>
					<td  style="text-align:right;"><b>Daily Add Automatically In Inpatient bill</b></td>
					<td>
						<input type='checkbox' name='daily_add_automaticaly_in_inpatient_bill' id='Ward_Round_Item'
							 <?php if(strtolower($daily_add_automaticaly_in_inpatient_bill) == 'yes') { echo 'checked="checked"'; } ?> 
						value='yes'>
					</td>
				</tr>
                <tr>
                    <td style="text-align:right;">
					  <b>Can Be Substituted In Doctor's Page</b>
					 </td>
					 <td> 
                        <input type='checkbox' name='Can_Be_Substituted_In_Doctors_Page' id='Can_Be_Substituted_In_Doctors_Page' <?php if(strtolower($Can_Be_Substituted_In_Doctors_Page) == 'yes') { echo 'checked="checked"'; } ?> value='yes'>
                      
                    </td>
                </tr>
                <tr>
                    <td  style="text-align:right;"><b>Can Be Sold</b>
                    </td>
                    <td colspan="2">
                        <?php ($Can_Be_Sold == "yes") ? $checked="checked" : $checked=""; ?>
                        <input type='checkbox' name='Can_Be_Sold' id='Can_Be_Sold' <?php echo $checked; ?> />
                    </td>
                </tr>
                <tr>
                    <td  style="text-align:right;"><b>Can Be Stocked</b>
                    </td>
                    <td colspan="2">
                        <?php ($Can_Be_Stocked == "yes") ? $checked="checked" : $checked=""; ?>
                        <input type='checkbox' name='Can_Be_Stocked' id='Can_Be_Stocked' <?php echo $checked; ?> />
                    </td>
                </tr>
                <tr style="display: none">
                    <td style="text-align:right;">
					  <label for="Ct_Scan_Item"><b>CT SCAN Item</b></label>
					 </td>
					 <td> 
                        <input type='checkbox' name='Ct_Scan_Item' id='Ct_Scan_Item' <?php if(strtolower($Ct_Scan_Item) == 'yes') { echo 'checked="checked"'; } ?> value='no'>
                      
                    </td>
                </tr>
		 <tr>
                    <td  style="text-align:right;"><b>Consultation Item</b>
                    </td>
                    <td colspan="2">
                        <?php ($consultation_Item == "yes") ? $checked="checked" : $checked=""; ?>
                        <input type='checkbox' name='consultation' id='consultation' <?php echo $checked; ?> />
                    </td>
                </tr>
				
				
                 <tr>
                    <td  style="text-align:right;"><b>Can be seen on all payments list</b>
                    </td>
                    <td colspan="2">
                        <?php ($Seen_On_Allpayments == "yes") ? $checked="checked" : $checked=""; ?>
                        <input type='checkbox' name='Seen_On_Allpayments' id='Seen_On_Allpayments' <?php echo $checked; ?> />
                    </td>
                </tr>
                 <tr>
                    <td  style="text-align:right;"><b><label for="Free_Consultation_Item">Appear In Advance when check-in patient</label></b>
                    </td>
                    <td colspan="2">
                        <?php ($Free_Consultation_Item == "1") ? $checked="checked" : $checked=""; ?>
                        <input type='checkbox'  disabled="" name='Free_Consultation_Item' id='Free_Consultation_Item' <?php echo $checked; ?> />
                    </td>
                </tr>
                <tr>
                    <td  style="text-align:right;"><b>Can be ordered by Specialist / Super Specialist only</b>
                    </td>
                    <td colspan="2">
                        <?php ($Should_be_ordered_by_specialist == "Yes") ? $checked="checked" : $checked=""; ?>
                        <input type='checkbox' name='Should_be_ordered_by_specialist' id='Should_be_ordered_by_specialist' <?php echo $checked; ?> />
                    </td>
                </tr>
                <tr>
                    <td  style="text-align:right;"><b><label for="Statevalue">Solid/liquid Item</label></b>
                    </td>
                    <td colspan="2">
                        <select name="" id="Statevalue" onchange="changeliquidstatus(this.value)" required style="width:30%">
                            <?php 
                                if($Statevalue =='Liquid'){
                                    echo "<option value='Liquid' selected='selected'>Liquid</option>";
                                    echo "<option value='Solid' >Solid</option>";
                                }else if($Statevalue=='Solid'){
                                    echo "<option value='Solid' selected='selected'>Solid</option>";
                                    echo "<option value='Liquid' >Liquid</option>";
                                }else{
                                    echo '<option value=""></option>
                                    <option value="Solid">Solid</option>
                                    <option value="Liquid">Liquid</option>';
                                }
                            ?>
                        </select>
                        <select name='route_type' id='route_type' style="width:30%" onchange="changeliquidvalue()">
                            <option><?= $route_type ?></option>
                            <option value='Injection'>Injection</option>
                            <option value='Oral'>Oral</option>
                            <option value='Sublingual'>Sublingual</option>
                            <option value='Rectal'>Rectal</option>
                            <option value='Avaginal'>Avaginal</option>
                            <option value='Obular'>Obular</option>
                            <option value='Otic'>Otic</option>
                            <option value='Nasal'>Nasal</option>
                            <option value='Inhalation'>Inhalation</option>
                            <option value='Nebulazation'>Nebulazation</option>
                            <option value='Very_rarely_transdermal'>Very rarely transdermal</option>
                            <option value='Cutaneous'>Cutaneous</option>
                            <option value='Intramuscular'>Intramuscular</option>
                            <option value='Intravenous'>Intravenous</option>
                            <option value='Topical'>Topical</option>

                        </select>
                        <?php 
                            if($Statevalue =='Liquid'){
                                echo $Liquid_Item_Value. ' mls';
                            } 
                        ?>
                        <input type="text" id="Liquid_Item_Value" style="width:50%"  onkeyup="changeliquidvalue()" value="<?php echo $Liquid_Item_Value; ?>">
                    </td>
                    
                </tr>
				
                <tr>
                    <td colspan=2 style='text-align:right;'>
			<?php if($no > 0) { ?>
                        <input type='submit' name='submit' id='submit' value='   UPDATE  ' class='art-button'>
			<?php } ?>
                        <a href='edititemlist.php?EditItem=EditItemThisPage' class='art-button'>CANCEL</a> 
                        <input type='hidden' name='submittedEditItemForm' value='true'/> 
                    </td>
                </tr>
            </table>
</fieldset>
        </td>
    </tr>
</table>
 </form>
        </center>
        
<br/>
<script type="text/javascript">
    function Update_Option(){
        var itemtype = document.getElementById("itemtype").value;
        if(itemtype == 'Pharmacy' || itemtype == 'Others'){
            document.getElementById("Can_Be_Stocked").checked = true;
        }else{
            document.getElementById("Can_Be_Stocked").checked = false;
        }
    }
</script>
<script>
    $(document).ready(function(){
        $("#Liquid_Item_Value").hide();
        $("#Can_Be_Sold").on("change", function(){
        if($("#Can_Be_Sold").is(':checked')){
            $("#Item_Classification").attr("required", "required");
            } else {
                  $("#Item_Classification").removeAttr("required");
            }
        });
		
		var itemtype=$('#itemtype').val();
		if(itemtype=='Service'){
			$("#Item_Classification").removeAttr("required");
		}
		
		$('#itemtype').on('change',function(){
			var itemtype=$(this).val();
			if(itemtype=='Service'){
			$("#Item_Classification").removeAttr("required");
		   }else{
			  $("#Item_Classification").attr("required","required"); 
		   }
		});
		
		
    });

    function changeliquidstatus(Statevalue){
        var Liquid_Item_Value ='';
        if(Statevalue=='Liquid'){
            $("#Liquid_Item_Value").show();
            var Liquid_Item_Value = $("#Liquid_Item_Value").val();
            $("#Liquid_Item_Value").focus();
        }
        if(Statevalue=='Solid'){
            $("#Liquid_Item_Value").hide();
        }
        var Item_ID = '<?php echo  $Item_ID ?>';
        $.ajax({
            type:'POST',
            url:'ajax_change_item_state.php',
            data:{Statevalue:Statevalue,Item_ID:Item_ID,Liquid_Item_Value:Liquid_Item_Value, medicationChart:'' },
            success:function(data){
                console.log(data)
            }
        });
    }

    function changeliquidvalue(){
        var Statevalue = $("#Statevalue").val();
        var route_type = $("#route_type").val();
        if(Statevalue=='Solid'){
            $("#Liquid_Item_Value").hide();
        }
        var Liquid_Item_Value = $("#Liquid_Item_Value").val();
        var Item_ID = '<?php echo  $Item_ID ?>';
        $.ajax({
            type:'POST',
            url:'ajax_change_item_state.php',
            data:{Statevalue:Statevalue,Item_ID:Item_ID,Liquid_Item_Value:Liquid_Item_Value,route_type:route_type, medicationChart:'' },
            success:function(data){
                console.log(data)
            }
        });
    }
</script>


<?php
    if(isset($_POST['submittedEditItemForm'])){
        $itemtype = mysqli_real_escape_string($conn,$_POST['itemtype']);
        $NHIFItem_Type = mysqli_real_escape_string($conn,$_POST['NHIFItem_Type']);
        $Product_Code = mysqli_real_escape_string($conn,$_POST['Product_Code']);
        $Unit_Of_Measure = mysqli_real_escape_string($conn,$_POST['Unit_Of_Measure']);
        $Product_Name = mysqli_real_escape_string($conn,$_POST['Product_Name']);
        $Package_Type = mysqli_real_escape_string($conn,$_POST['Package_Type']);
        $Tax = mysqli_real_escape_string($conn,$_POST['Tax_type']);
        $Facility_Product_Code = mysqli_real_escape_string($conn,$_POST['Facility_Product_Code']);

        //this item_Subcategory will act as item_subcategory_id - we assign this value on option value
        $Item_Subcategory = mysqli_real_escape_string($conn,$_POST['Item_Subcategory']);
        $Reoder_Level = mysqli_real_escape_string($conn,$_POST['Reoder_Level']);
        $Item_Status = mysqli_real_escape_string($conn,$_POST['Item_Status']);
        $Consultation_Type = mysqli_real_escape_string($conn,$_POST['Consultation_Type']);
        $Item_Classification = mysqli_real_escape_string($conn,$_POST['Item_Classification']);
        $Particular_Type = mysqli_real_escape_string($conn,$_POST['Particular_Type']);

        if(isset($_POST['Can_Be_Substituted_In_Doctors_Page'])) {
            $Can_Be_Substituted_In_Doctors_Page = 'yes';
        }else{
            $Can_Be_Substituted_In_Doctors_Page = 'no';
        }

        if(isset($_POST['Ward_Round_Item'])) {
            $Ward_Round_Item = 'yes';
        }else{
            $Ward_Round_Item = 'no';
        }

        if(isset($_POST['Can_Be_Sold'])) {
            $Can_Be_Sold = 'yes';
        }else{
            $Can_Be_Sold = 'no';
        }

        if(isset($_POST['Can_Be_Stocked']) || strtolower($itemtype) == 'pharmacy' || strtolower($itemtype) == 'others') {
            $Can_Be_Stocked = 'yes';
        }else{
            $Can_Be_Stocked = 'no';
        }
	
    	if(isset($_POST['Ct_Scan_Item'])){
    		$Ct_Scan_Item = 'no';
    	}else{
    		$Ct_Scan_Item = 'no';
    	}
	
	 if(isset($_POST['consultation'])) {
            $consultation = 'yes';
        }else{
            $consultation = 'no';
        }
		
        if(isset($_POST['Seen_On_Allpayments'])) {
            $Seen_On_Allpayments = 'yes';
        }else{
            $Seen_On_Allpayments = 'no';
        }
        if(isset($_POST['daily_add_automaticaly_in_inpatient_bill'])) {
            $daily_add_automaticaly_in_inpatient_bill = 'yes';
        }else{
            $daily_add_automaticaly_in_inpatient_bill = 'no';
        }
        if(isset($_POST['Should_be_ordered_by_specialist'])){
            $Should_be_ordered_by_specialist='Yes';
            
        }else{
            $Should_be_ordered_by_specialist='No';
        }
       
        if(isset($_POST['Free_Consultation_Item'])) {
            $Free_Consultation_Item = '1';
        }else{
            $Free_Consultation_Item = '0';
        }
	
	    $Update_New_Item = "UPDATE tbl_items set
			    Item_Type = '$itemtype',NHIFItem_Type='$NHIFItem_Type', Product_Code = '$Product_Code', Unit_Of_Measure = '$Unit_Of_Measure',
				Product_Name = '$Product_Name', Item_Subcategory_ID = '$Item_Subcategory', Package_Type='$Package_Type',Tax='$Tax',
                Status = '$Item_Status', Reoder_Level = '$Reoder_Level', Classification = '$Item_Classification',
                Consultation_Type = '$Consultation_Type',Can_Be_Substituted_In_Doctors_Page = '$Can_Be_Substituted_In_Doctors_Page',
                Ward_Round_Item = '$Ward_Round_Item',daily_add_automaticaly_in_inpatient_bill='$daily_add_automaticaly_in_inpatient_bill',Can_Be_Sold = '$Can_Be_Sold',Can_Be_Stocked = '$Can_Be_Stocked', Ct_Scan_Item = '$Ct_Scan_Item',consultation_Item='$consultation',
                Particular_Type = '$Particular_Type',Seen_On_Allpayments='$Seen_On_Allpayments', Free_Consultation_Item = '$Free_Consultation_Item', Facility_Product_Code = '$Facility_Product_Code'
                WHERE item_id = '$Item_ID'";


        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        $existence=mysqli_query($conn,"SELECT Should_be_ordered_by_specialist FROM tbl_item_price WHERE Item_ID='$Item_ID' AND Sponsor_ID='$Sponsor_ID'") or die(mysqli_error($conn));
        $num_rows=  mysqli_num_rows($existence);
        if($num_rows>0){
            if(isset($_POST['Should_be_ordered_by_specialist'])){ 

                $updating=mysqli_query($conn,"UPDATE tbl_item_price SET Should_be_ordered_by_specialist='$Should_be_ordered_by_specialist', status_update='$Employee_ID'  WHERE Item_ID='$Item_ID' AND Sponsor_ID='$Sponsor_ID'") or die(mysqli_error($conn));
                if($updating){
                    echo 'success';                
                }else{
                    echo "Item has no Price";
                } 
            }
        }
	if(!mysqli_query($conn,$Update_New_Item)){ 
				$error = '1062yes';
				if(mysqli_errno($conn)."yes" == $error){
				    ?>
				    <script>
					alert("\nITEM NAME ALREADY EXISTS!\nPROCESS FAIL"); 
				    </script>
				    
				    <?php
				    
				}
		} else {
		    echo '<script>
			alert("ITEM UPDATED SUCCESSFUL");
			document.location="./edititemlist.php?StatusEditItemList=EditItemListThisPage";
		    </script>';	
		}
    }
?>
				    
				    
<?php
    include("./includes/footer.php");

    //ALTER TABLE `tbl_item_price` ADD `Should_be_ordered_by_specialist` VARCHAR(5) NOT NULL DEFAULT 'No' AFTER `last_updated_by`, ADD `status_update` INT NULL AFTER `Should_be_ordered_by_specialist`; 
?>
