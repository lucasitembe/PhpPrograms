<?php
	include("./includes/connection.php");

	if(isset($_GET['Type_Of_Check_In'])){
		$Type_Of_Check_In = $_GET['Type_Of_Check_In'];
	}else{
		$Type_Of_Check_In = 'Laboratory';
	}

	if(isset($_GET['Guarantor_Name'])){
		$Guarantor_Name = $_GET['Guarantor_Name'];
	}else{
		$Guarantor_Name = '';
	}
	if(strtolower($Type_Of_Check_In)=='procedure'){
	    $Consultation_Condition = "((d.Department_Location='Dialysis') OR
	    (d.Department_Location='Physiotherapy') OR (d.Department_Location='Optical')OR
	    (d.Department_Location='Dressing')OR(d.Department_Location='Maternity')OR
	    (d.Department_Location='Cecap')OR(d.Department_Location='Dental')OR
	    (d.Department_Location='Ear') OR(d.Department_Location='Hiv') OR
	    (d.Department_Location='Eye') OR(d.Department_Location='Maternity') OR
	    (d.Department_Location='Rch') OR(d.Department_Location='Theater') OR
	    (d.Department_Location='Family Planning')OR(d.Department_Location='Surgery')
	    OR(d.Department_Location='Procedure'))";
	}else{
	    $Consultation_Condition = "d.Department_Location = '$Type_Of_Check_In'";
	}

?>


<table width=100% style='border-style: none;'>
      <tr>
	 <td width=40%>
	    <table width=100% style='border-style: none;'>
	       <tr>
		  <td>
		     <b>Category : </b>
		     <select name='Item_Category_ID' id='Item_Category_ID' onchange='getItemsList(this.value)' >
			 <option selected='selected' value="All">All</option>
			 <?php
			     $data = mysqli_query($conn,"
    				    select ic.Item_Category_Name, ic.Item_Category_ID
    				    from tbl_item_category ic, tbl_item_subcategory isu, tbl_items i where
                        ic.Item_category_ID = isu.Item_category_ID and
                        i.Item_Subcategory_ID = isu.Item_Subcategory_ID 
                        group by ic.Item_Category_ID order by ic.Item_Category_Name
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
		     <input type='text' id='Search_Value' name='Search_Value' autocomplete='off' oninput='getItemsListFiltered(this.value)' placeholder='Enter Item Name'>
		  </td>
	       </tr>
	       <tr>
		  <td>
		     <fieldset style='overflow-y: scroll; height: 270px;' id='Items_Fieldset'>
			<table width=100%>
			<?php
			   $result = mysqli_query($conn,"SELECT * FROM tbl_items WHERE Status='Available'  order by Product_Name limit 100");
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
                    <td colspan="2" style="text-align:center" id="statusmsg">
                       
                    </td>
                </tr>    
           <tr>
          <td style='text-align: right;' width=30%>Test Name</td>
          <td>
             <input type='text' name='Item_Name' id='Item_Name' readyonly='readyonly' placeholder='Item Name'>
             <input type='hidden' name='Item_ID' id='Item_ID' value=''>
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
		  <td colspan=2 style='text-align: center;'>
		     <input type='button' name='Submit' id='Submit' class='art-button-green' value='ADD TEST' onclick='Get_Selected_Item()'>
		  </td>
	       </tr>
	    </table>
	 </td>
      </tr>
      <tr>
          <td colspan="2" style="text-align:right;padding-right: 5px "><button type="button" onclick="refreshPage()" class="art-button-green" >Done</button></td>
      </tr>
   </table>