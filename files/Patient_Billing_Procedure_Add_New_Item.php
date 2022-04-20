<?php
	include("./includes/connection.php");
	$Type_Of_Check_In = 'Procedure';
	
	if(isset($_GET['Guarantor_Name'])){
		$Guarantor_Name = $_GET['Guarantor_Name'];
	}else{
		$Guarantor_Name = '';
	}

	if(strtolower($Type_Of_Check_In) == 'laboratory' || strtolower($Type_Of_Check_In) == 'radiology' || strtolower($Type_Of_Check_In) == 'procedure'){
		$Consultation_Condition = "d.Department_Location = '$Type_Of_Check_In'";
	}else{
		$Consultation_Condition = "((d.Department_Location='Dialysis') OR
	    (d.Department_Location='Physiotherapy') OR (d.Department_Location='Optical')OR
	    (d.Department_Location='Dressing')OR(d.Department_Location='Maternity')OR
	    (d.Department_Location='Cecap')OR(d.Department_Location='Dental')OR
	    (d.Department_Location='Ear') OR(d.Department_Location='Hiv') OR
	    (d.Department_Location='Eye') OR(d.Department_Location='Maternity') OR
	    (d.Department_Location='Rch') OR(d.Department_Location='Theater') OR
	    (d.Department_Location='Family Planning')OR(d.Department_Location='Surgery'))";
	}
?>

<style>
    table,tr,td{
        //border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>
<table width=100% style='border-style: none;'>
      <tr>
	 <td width=40%>
	    <table width=100% style='border-style: none;'>
	       <tr>
		  <td>
		     <b>Category : </b>
		     <select name='Item_Category_ID' id='Item_Category_ID' onchange='getItemsList(this.value)' onchange='Calculate_Amount()' onkeypress='Calculate_Amount()'>
			 <option selected='selected' value="">All</option>
			 <?php
			 	if(strtolower($Type_Of_Check_In) == 'laboratory' || strtolower($Type_Of_Check_In) == 'radiology' || strtolower($Type_Of_Check_In) == 'procedure'){
			 		$data = mysqli_query($conn,"
    				    select ic.Item_Category_Name, ic.Item_Category_ID
    				    from tbl_item_category ic, tbl_item_subcategory isu, tbl_items i where
                        ic.Item_category_ID = isu.Item_category_ID and
                        i.Item_Subcategory_ID = isu.Item_Subcategory_ID and
                        i.Consultation_Type = '$Type_Of_Check_In' group by ic.Item_Category_ID order by ic.Item_Category_Name
				    ") or die(mysqli_error($conn));
			 	}else{
			 		$data = mysqli_query($conn,"
    				    select ic.Item_Category_Name, ic.Item_Category_ID
    				    from tbl_item_category ic, tbl_item_subcategory isu, tbl_items i where
                        ic.Item_category_ID = isu.Item_category_ID and
                        i.Item_Subcategory_ID = isu.Item_Subcategory_ID and
                        (i.Consultation_Type <> 'radiology' and i.Consultation_Type <> 'procedure' and i.Consultation_Type <> 'radiology') group by ic.Item_Category_ID order by ic.Item_Category_Name
				    ") or die(mysqli_error($conn));
			 	}
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
		     <fieldset style='overflow-y: scroll; height: 270px;' id='New_Items_Fieldset'>
			<table width=100%>
			<?php
				if(strtolower($Type_Of_Check_In) == 'laboratory' || strtolower($Type_Of_Check_In) == 'radiology' || strtolower($Type_Of_Check_In) == 'procedure'){
			   		$result = mysqli_query($conn,"SELECT * FROM tbl_items where Consultation_Type = '$Type_Of_Check_In' order by Product_Name limit 100");
				}else{
					$result = mysqli_query($conn,"SELECT * FROM tbl_items where Consultation_Type <> 'laboratory' and  Consultation_Type <> 'radiology' and Consultation_Type <> 'procedure' order by Product_Name limit 100");
				}
			   

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
	 	<center><b><h3><?php echo strtoupper($Type_Of_Check_In); ?></h3></b></center><br/><br/>
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
                                            d.Department_Location = 'Procedure'") or die(mysqli_error($conn));
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
		     <input type='text' name='Quantity' id='Quantity' value='1' placeholder='Quantity'>
		  </td>
	       </tr>
	       <tr>
		  <td colspan=2>
		     <textarea name='Comment' id='Comment' placeholder='Comment'></textarea>
		  </td>
	       </tr>
	       <tr>
		  <td colspan=2 style='text-align: center;'>
		     <input type='button' name='Submit' id='Submit' class='art-button-green' value='ADD ITEM' onclick='Get_Selected_Item()'>
		  </td>
	       </tr>
	    </table>
	 </td>
      </tr>
   </table>