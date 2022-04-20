<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    include("./functions/items.php");
    include("./functions/itemcategory.php");
//Get_Item_Consultation_Type
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	    if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes' && $_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes' && $_SESSION['userinfo']['Reception_Works'] != 'yes' && $_SESSION['userinfo']['Revenue_Center_Works'] != 'yes' && $_SESSION['userinfo']['Pharmacy'] != 'yes'){
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

<a href='addnewcategory.php?AddNewCategory=AddNewCategoryThisPage' class='art-button-green'>NEW CATEGORY ITEM</a>
<a href='addnewsubitemcategory.php?AddNewSubategoryItem=AddNewSubategoryItemThisPage' class='art-button-green'>NEW SUBCATEGORY ITEM</a>
<a href='edititems.php?EditItem=EditItemThisForm' class='art-button-green'>EDIT ITEM</a>

<?php
    if(isset($_GET['from']) && $_GET['from'] == "itemsConf") {
?>
<a href='edititemlist.php?EditItemList=EditItemListThisPage&from=itemsConf' class='art-button-green'>BACK</a>
<?php
    } else {
?>
<a href='newitem.php?AddNewItemCategory=AddNewItemCategoryThisPage' class='art-button-green'>BACK</a>
<?php
    }
?>




<script type="text/javascript" language="javascript">
    function getSubcategory(Item_Category_ID) {
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
        document.getElementById('Item_Subcategory').innerHTML = data;
    }
</script>

<br/><br/>

<center>
    <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
        <table width=70%>
            <tr>
                <td>
                    <fieldset>
                        <legend align=center><b>NEW ITEM</b></legend>
                        <table width=100%>
                            <tr>
                                <td width=40% style="text-align:right;"><b>Item Type</b></td>
                                <td colspan="2" width=60%>
                                    <select name='itemtype' id='itemtype' required='required' onchange="Update_Option()">
                                        <?php
                                            $Item_Type_List = Get_Item_Type();
											echo "<option value=''></option>";
                                            foreach($Item_Type_List as $Item_Type) {
                                                echo "<option value='{$Item_Type['Name']}'> {$Item_Type['Description']} </option>";
                                            }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:right;"><b>Product Code</b></td>
                                <td width=10%>
                                    <input type='text' name='Product_Code_Prefix' id='Product_Code_Prefix'
                                           autocomplete='off' placeholder='Prefix'
                                           onchange='Product_Code_Prefix_Change()' onkeyup='Product_Code_Prefix_Change()'
                                           onkeypress='Product_Code_Prefix_Change()' >
                                </td>
                                <td>
                                    <input type='text' name='Product_Code' id='Product_Code' placeholder='Enter Product Code'
                                           required='required' autocomplete='off'>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:right;"><b>Unit Of Measure</b></td>
                                <td colspan="2">
                                    <select name='Unit_Of_Measure' id='Unit_Of_Measure' placeholder='Enter Unit Of Measure' required='required'>
                                        <?php
                                            $Unit_Of_Measure_List = Get_Unit_Of_Measure();
                                            foreach($Unit_Of_Measure_List as $Unit_Of_Measure) {
                                                echo "<option value='{$Unit_Of_Measure['Name']}'> {$Unit_Of_Measure['Description']} </option>";
                                            }
                                        ?>
                                    </select>
                                </td>
                            </tr>
						    <tr>
                                <td style="text-align:right;"><b>'Package Type</b></td>
                                <td colspan="2">
                                    <input type='text' name='Package_Type' id='Package_Type'  placeholder='Enter Package type' autocomplete='off'>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:right;"><b>Product Name</b></td>
                                <td colspan="2">
                                    <input type='text' name='Product_Name' id='Product_Name' required='required' placeholder='Enter Product Name' autocomplete='off'>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:right;"><b>Taxable/Non Taxable</b></td>
                                <td colspan="2">
                                    <select name='Tax_type' id='Tax_type'>
                                        <option value="non_taxable" selected='selected'>Non Taxable</option>
                                        <option value="taxable">Taxable</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:right;"><b>Classification</b></td>
                                <td colspan="2">
                                    <select name='Item_Classification' id='Item_Classification' required='required'>
                                        <option selected='selected'></option>
                                        <?php
                                            $Item_Classification = Get_Item_Classification();
                                            foreach($Item_Classification as $Classification) {
                                                echo "<option value='{$Classification['Name']}'> {$Classification['Description']} </option>";
                                            }
                                        ?>
                                    </select>&nbsp;&nbsp;&nbsp;<b>Select Classification</b>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:right;"><b>Item Category</b></td>
                                <td colspan="2">
                                    <select name='Item_Category' id='Item_Category' onchange='getSubcategory(this.value)' required='required'>
                                        <option selected='selected'></option>
                                        <?php
                                            $Item_Category_List = Get_Item_Category_All();
                                            foreach($Item_Category_List as $Item_Category) {
                                                echo "<option value='{$Item_Category['Item_Category_ID']}'> {$Item_Category['Item_Category_Name']} </option>";
                                            }
                                        ?>
                                    </select>&nbsp;&nbsp;&nbsp;<b>Select Category</b>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:right;"><b>Sub Category</b></td>
                                <td colspan="2">
                                    <select name='Item_Subcategory' id='Item_Subcategory' required='required'>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:right;"><b>Status</b></td>
                                <td colspan="2">
                                    <select name='Item_Status' id='Item_Status'>
                                        <option selected='selected'>Available</option>
                                        <option>Not Available</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:right;"><b>Particular Type</b></td>
                                <td colspan="2">
                                    <select name='Particular_Type' id='Particular_Type'>
                                        <option selected='selected'>Cost Sharing</option>
                                        <option>DRF</option>
                                        <option>Fast Track</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:right;"><b>Reoder Level</b></td>
                                <td colspan="2"><input type='text' name='Reoder_Level' id='Reoder_Level' placeholder='Enter Reoder Level' autocomplete='off'></td>
                            </tr>
                            <tr>
                                <td style="text-align:right;"><b>Consultation Type
                      
                                    </b></td>
                                <td colspan="2">
                                    <select name='Consultation_Type' id='Consultation_Type' required='required'>
                                        <option selected='selected'></option>
                                        <?php
                                            $Item_Consultation_Type = Get_Item_Consultation_Type();
                                            foreach($Item_Consultation_Type as $Consultation_Type) {
                                                echo "<option value='{$Consultation_Type['Name']}'> {$Consultation_Type['Description']} </option>";
                                            }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td  style="text-align:right;"><b>Ward Round Item</b></td>
                                <td colspan="2">
                                    <input type='checkbox' name='Ward_Round_Item' id='Ward_Round_Item' value='yes'>
                                </td>
                            </tr>
                            <tr>
                                <td  style="text-align:right;"><b>Can Be Substituted In Doctor's Page</b>
                                </td>
                                <td colspan="2">
                                    <input type='checkbox' name='Can_Be_Substituted_In_Doctors_Page' id='Can_Be_Substituted_In_Doctors_Page' value='yes'>
                                </td>
                            </tr>
                            <tr>
                                <td  style="text-align:right;"><b>Can Be Sold</b>
                                </td>
                                <td colspan="2">
                                    <input type='checkbox' name='Can_Be_Sold' id='Can_Be_Sold' value='yes'>
                                </td>
                            </tr>
                            <tr>
                                <td  style="text-align:right;"><b>Can Be Stocked</b>
                                </td>
                                <td colspan="2">
                                    <input type='checkbox' name='Can_Be_Stocked' id='Can_Be_Stocked' value='yes'>
                                </td>
                            </tr>
			    <tr style="display: none">
                    <td style="text-align:right;">
						<label for="Ct_Scan_Item"><b>CT SCAN Item</b></label>
					</td>
					<td>
						<input type='checkbox' name='Ct_Scan_Item' id='Ct_Scan_Item' value='no'>
					</td>
                </tr>
		
		      <tr>
                                <td  style="text-align:right;"><b>Consultation Item</b>
                                </td>
                                <td colspan="2">
                                    <input type='checkbox' name='consultation' id='consultation' value='yes'>
                                </td>
                            </tr>
							
							<tr>
                                <td  style="text-align:right;"><b>Can be seen on all payments list</b>
                                </td>
                                <td colspan="2">
                                    <input type='checkbox' name='Seen_On_Allpayments' id='Seen_On_Allpayments' value='yes'>
                                </td>
                            </tr>
                     <tr>
                        <td  style="text-align:right;"><b><label for="Free_Consultation_Item"> Appear In Advance when check-in patient</label></b>
                        </td>
                        <td colspan="2">
                            <?php ($Free_Consultation_Item == "1") ? $checked="checked" : $checked=""; ?>
                            <input type='checkbox' name='Free_Consultation_Item' id='Free_Consultation_Item' <?php echo $checked; ?> />
                        </td>
                    </tr>   
                            <tr>
                                <td colspan=3 style='text-align: right;'>
                                    <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                                    <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                                    <input type='hidden' name='submittedAddNewItemForm' value='true'/>
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

    function Product_Code_Prefix_Change() {
        Product_Code_Prefix = document.getElementById("Product_Code_Prefix").value;
        if (Product_Code_Prefix != '') {
            $("#Product_Code").removeAttr("required");
        } else {
            $("#Product_Code").attr("required", "required");
        }
    }
</script>




<?php
    if(isset($_POST['submittedAddNewItemForm'])){

        $itemtype = mysqli_real_escape_string($conn,$_POST['itemtype']);
        $Product_Code = mysqli_real_escape_string($conn,$_POST['Product_Code']);
        $Product_Code_Prefix = mysqli_real_escape_string($conn,$_POST['Product_Code_Prefix']);
        $Unit_Of_Measure = mysqli_real_escape_string($conn,$_POST['Unit_Of_Measure']);
        $Product_Name = mysqli_real_escape_string($conn,$_POST['Product_Name']);
        $Item_Subcategory = mysqli_real_escape_string($conn,$_POST['Item_Subcategory']);
        $Reoder_Level = mysqli_real_escape_string($conn,$_POST['Reoder_Level']);
        $Item_Status = mysqli_real_escape_string($conn,$_POST['Item_Status']);
        $Consultation_Type = mysqli_real_escape_string($conn,$_POST['Consultation_Type']);
        $Item_Classification = mysqli_real_escape_string($conn,$_POST['Item_Classification']);
        $Particular_Type = mysqli_real_escape_string($conn,$_POST['Particular_Type']);
		$Package_Type = mysqli_real_escape_string($conn,$_POST['Package_Type']);
		$Tax_type = mysqli_real_escape_string($conn,$_POST['Tax_type']);


        if(isset($_POST['Can_Be_Substituted_In_Doctors_Page'])) {
            $Can_Be_Substituted_In_Doctors_Page = 'yes';
        }else{
            $Can_Be_Substituted_In_Doctors_Page = 'no';
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

        if(isset($_POST['Ward_Round_Item'])) {
            $Ward_Round_Item = 'yes';
        }else{
            $Ward_Round_Item = 'no';
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
		
	if(isset($_POST['Free_Consultation_Item'])) {
            $Free_Consultation_Item = '1';
        }else{
            $Free_Consultation_Item = '0';
        }
        //Auto generation of Product Code if Product Code Prefix is given
        $Product_Code_Prefix = strtoupper($Product_Code_Prefix);
        if (!empty($Product_Code_Prefix)) {
            $Product_Code_Query = mysqli_query($conn,"SELECT Product_Code
                                               FROM tbl_items
                                               WHERE Product_Code like '$Product_Code_Prefix%'
                                               ORDER BY Product_Code DESC
                                               LIMIT 1");
            $Product_Code_Rows = mysqli_num_rows($Product_Code_Query);
            if ($Product_Code_Rows > 0) {
                while($data = mysqli_fetch_array($Product_Code_Query)){
                    $Last_Product_Code = $data['Product_Code'];
                }

                $Last_Product_Code_Number = str_replace($Product_Code_Prefix, "", $Last_Product_Code);
                $Last_Product_Code_Number = intval($Last_Product_Code_Number) + 1;
                $Product_Code = $Product_Code_Prefix . str_pad($Last_Product_Code_Number, 3, '0', STR_PAD_LEFT);
            } else {
                $Last_Product_Code_Number = 1;
                $Product_Code = $Product_Code_Prefix . str_pad($Last_Product_Code_Number, 3, '0', STR_PAD_LEFT);
            }
        }

        //item_subcategory act as item_subcategory_id - this is from the option value
        $Insert_New_Item = "INSERT INTO
                                tbl_items
                                (Item_Type, Product_Code, Unit_Of_Measure,
                                Product_Name,Item_Subcategory_ID, Status, Classification,
                                Reoder_Level, Consultation_Type, Can_Be_Substituted_In_Doctors_Page,
                                Ward_Round_Item, Can_Be_Sold, Can_Be_Stocked,Ct_Scan_Item,consultation_Item,Particular_Type,Seen_On_Allpayments,Free_Consultation_Item,Package_Type,Tax)
			                VALUES(
                                '$itemtype','$Product_Code','$Unit_Of_Measure',
                                '$Product_Name','$Item_Subcategory','$Item_Status','$Item_Classification',
					            '$Reoder_Level','$Consultation_Type','$Can_Be_Substituted_In_Doctors_Page',
					            '$Ward_Round_Item', '$Can_Be_Sold', '$Can_Be_Stocked','$Ct_Scan_Item','$consultation','$Particular_Type','$Seen_On_Allpayments','$Free_Consultation_Item','$Package_Type','$Tax_type' )";
            $rs=mysqli_query($conn,$Insert_New_Item) or die(mysqli_error($conn));
	    if(!$rs){
            $error = '1062yes';
            if(mysql_errno()."yes" == $error){
                ?>
                <script>
                alert("\nITEM NAME ALREADY EXISTS!\nTRY ANOTHER NAME");
                document.location="./addnewitemcategory.php?AddNewItemCategory=AddNewItemCategoryThisPage";
                </script>

                <?php

            }
		}
		else {
		    echo '<script>';
			echo 'alert("ITEM WITH PRODUCT CODE  '. $Product_Code . ' HAS BEEN ADDED SUCCESSFUL");';
		    echo '</script>';
		}

    }


?>



<?php
    include("./includes/footer.php");
?>