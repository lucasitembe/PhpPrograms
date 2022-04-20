<?php
include("./includes/connection.php");
@session_start();
 if(isset($_GET['category_id'])){
 
   
	
	
	$item=mysqli_query($conn,"SELECT * FROM tbl_items as tit INNER JOIN tbl_item_subcategory AS tis ON tit.Item_Subcategory_ID=tis.Item_Subcategory_ID
	JOIN tbl_item_category tic ON tic.Item_category_ID=tis.Item_category_ID
	WHERE tis.Item_category_ID='".$_GET['category_id']."'") or die(mysqli_error($conn));
	$i=1;
	
	if(mysqli_num_rows($item)>0){
	$result=mysqli_query($conn,"SELECT * FROM tbl_item_category") or die(mysqli_error($conn));
	$category='<select name="categorysub" onchange="categorysub(this.value)"><option ></option>';
	
	while($row=mysqli_fetch_array($result)){
	   $category.="<option value='".$row['Item_Category_ID']."'>".$row['Item_Category_Name']."</option>";
	}
	$category.='</select>';
	
	
	$_SESSION['total_items']=mysqli_num_rows($item);
	while($data=mysqli_fetch_array($item)){
		 echo '
	      <tr rowspan="1">
		  <td>
		    '.$i.'
		  </td>
		  <td>
		  <input type="hidden" value="'.$data['Item_ID'].'" name="Item_ID">
		    '.$data['Product_Name'].'
		  </td>
                  <td>
                   <select name="Consultation_Type" id="Consultation_Type'.$data['Item_ID'].'" onchange="changeItemConsultationType(this.value,'.$data['Item_ID'].')">
                            <option selected="selected"></option>
                            <option value="Pharmacy" '.((strtolower($data['Consultation_Type'])=='pharmacy')?'selected':'').'> Pharmacy </option>
                            <option value="Laboratory"  '.((strtolower($data['Consultation_Type'])=='laboratory')?'selected':'').'> Laboratory </option>
                            <option value="Radiology" '.((strtolower($data['Consultation_Type'])=='radiology')?'selected':'').'> Radiology </option>
                            <option value="Surgery" '.((strtolower($data['Consultation_Type'])=='surgery')?'selected':'').'> Surgery </option>
                            <option value="Procedure" '.((strtolower($data['Consultation_Type'])=='procedure')?'selected':'').'> Procedure </option>
                            <option value="Optical" '.((strtolower($data['Consultation_Type'])=='optical')?'selected':'').'> Admission </option>
                            <option value="Others" '.((strtolower($data['Consultation_Type'])=='others')?'selected':'').'> Others </option>			           
                    </select>
                  </td>
		   <td>item_kind
		    <select name="brandgeneric" id="brandgeneric'.$data['Item_ID'].'">';
			   if($data['item_kind'] =='Brand'){
			      echo "<option></option>";
				  echo "<option selected='selected'>Brand</option>";
				  echo "<option >Generic</option>";
			   }elseif($data['item_kind'] =='Generic'){
			      echo "<option></option>";
				  echo "<option>Brand</option>";
				  echo "<option selected='selected'>Generic</option>";
			   }else{
			      echo "<option></option>";
				  echo "<option>Brand</option>";
				  echo "<option>Generic</option>";
			   }
			  
		echo	'</select>  
		  </td>
		  <td>';
		  $result=mysqli_query($conn,"SELECT * FROM tbl_item_category") or die(mysqli_error($conn));
		echo '<select name="categorysub" onchange="categorysub(this.value,'.$data['Item_ID'].')">';
		echo '<option ></option>';
		while($row=mysqli_fetch_array($result)){
		 if($data['Item_Category_ID'] ==$row['Item_Category_ID']){
		  echo "<option value='".$row['Item_Category_ID']."' selected='selected'>".$row['Item_Category_Name']."</option>";
		 }else{
		     echo "<option value='".$row['Item_Category_ID']."'>".$row['Item_Category_Name']."</option>";
		 }
		}
		echo '</select>';
		echo '  
		  </td>
		  <td id="'.$data['Item_ID'].'">';
		    
		$sucategoryRs = mysqli_query($conn,"SELECT * FROM tbl_item_subcategory WHERE Item_category_ID='".$_GET['category_id']."'") or die(mysqli_error($conn));
		echo "<select name='subcategory' onchange='changeItemSubcategory(this.value,".$data['Item_ID'].")' ><option></option>";
		while($row1=mysqli_fetch_array($sucategoryRs)){
		   
		 if($data['Item_Subcategory_ID'] ==$row1['Item_Subcategory_ID']){
		   echo "<option value='".$row1['Item_Subcategory_ID']."' selected='selected'>".$row1['Item_Subcategory_Name']."</option>";
		 }else{
			 echo "<option value='".$row1['Item_Subcategory_ID']."'>".$row1['Item_Subcategory_Name']."</option>";
		 }
		}
		echo '</select>';
		echo '</td></tr>';
	   $i++;
	}
	}else{
	   echo '<tr><td style="color:red;text-align:center;font-size:18px;font-weight:bold" colspan="5">No Item In This Category</td></tr>';
	}
	//echo $data;
   
 }elseif(isset($_GET['updatesubcategory'])){
   $sucategoryRs = mysqli_query($conn,"SELECT * FROM tbl_item_subcategory WHERE Item_category_ID='".$_GET['categoryID']."'") or die(mysqli_error($conn));
   $data="<select name='subcategory' 
   onchange='changeItemSubcategory(this.value,".$_GET['itemID'].")' ><option></option>";
		//echo "<select name='subcategory' id='".$data['Item_ID']."'><option></option>";
		while($row1=mysqli_fetch_array($sucategoryRs)){
		  $data.= "<option value='".$row1['Item_Subcategory_ID']."'>".$row1['Item_Subcategory_Name']."</option>";
		}
		
		$data.="</select>";
		
		echo $data;
		//echo '</select>';
 }elseif(isset($_GET['changesubCategory'])){
   $change = mysqli_query($conn,"UPDATE tbl_items SET Item_Subcategory_ID='".$_GET['subcategoryID']."', item_kind='".$_GET['item_kind']."' WHERE Item_ID='".$_GET['itemID']."'") or die(mysqli_error($conn));
   
     echo 1;
 }elseif(isset($_GET['changesubConsultationType'])){
   $change = mysqli_query($conn,"UPDATE tbl_items SET Consultation_Type='".$_GET['consultation_type']."' WHERE Item_ID='".$_GET['itemID']."'") or die(mysqli_error($conn));
   
     echo 1;
 }elseif(isset($_GET['search_word'])){
 
   $search_word=$_GET['search_word'];
	
	
	$item=mysqli_query($conn,"SELECT * FROM tbl_items as tit INNER JOIN tbl_item_subcategory AS tis ON tit.Item_Subcategory_ID=tis.Item_Subcategory_ID WHERE tis.Item_Subcategory_ID='".$_GET['categoryID']."' AND tit.Product_Name LIKE '%$search_word%'") or die(mysqli_error($conn));
	$i=1;
	
	$result=mysqli_query($conn,"SELECT * FROM tbl_item_category") or die(mysqli_error($conn));
	$category='<select name="categorysub" onchange="categorysub(this.value)"><option ></option>';
	
	while($row=mysqli_fetch_array($result)){
	   $category.="<option value='".$row['Item_Category_ID']."'>".$row['Item_Category_Name']."</option>";
	}
	$category.='</select>';
	
	
	$_SESSION['total_items']=mysqli_num_rows($item);
	if(mysqli_num_rows($item)>0){
	while($data=mysqli_fetch_array($item)){
		 echo '
	      <tr rowspan="1">
		  <td>
		    '.$i.'
		  </td>
		  <td>
		  <input type="hidden" value="'.$data['Item_ID'].'" name="Item_ID">
		    '.$data['Product_Name'].'
		  </td>
                  <td>
                   <select name="Consultation_Type" id="Consultation_Type'.$data['Item_ID'].'" onchange="changeItemConsultationType(this.value,'.$data['Item_ID'].')">
                            <option selected="selected"></option>
                            <option value="Pharmacy" '.((strtolower($data['Consultation_Type'])=='pharmacy')?'selected':'').'> Pharmacy </option>
                            <option value="Laboratory"  '.((strtolower($data['Consultation_Type'])=='laboratory')?'selected':'').'> Laboratory </option>
                            <option value="Radiology" '.((strtolower($data['Consultation_Type'])=='radiology')?'selected':'').'> Radiology </option>
                            <option value="Surgery" '.((strtolower($data['Consultation_Type'])=='surgery')?'selected':'').'> Surgery </option>
                            <option value="Procedure" '.((strtolower($data['Consultation_Type'])=='procedure')?'selected':'').'> Procedure </option>
                            <option value="Optical" '.((strtolower($data['Consultation_Type'])=='optical')?'selected':'').'> Admission </option>
                            <option value="Others" '.((strtolower($data['Consultation_Type'])=='others')?'selected':'').'> Others </option>			           
                    </select>
                  </td>
		   <td>
		    <select name="brandgeneric" id="brandgeneric'.$data['Item_ID'].'">
			  <option></option>
			  <option>brand</option>
			  <option>generic</option>
			</select>  
		  </td>
		  <td>';
		  $result=mysqli_query($conn,"SELECT * FROM tbl_item_category") or die(mysqli_error($conn));
		echo '<select name="categorysub" onchange="categorysub(this.value,'.$data['Item_ID'].')">';
		echo '<option ></option>';
		while($row=mysqli_fetch_array($result)){
			echo "<option value='".$row['Item_Category_ID']."'>".$row['Item_Category_Name']."</option>";
		}
		echo '</select>';
		echo '  
		  </td>
		  <td id="'.$data['Item_ID'].'">';
		    
		$sucategoryRs = mysqli_query($conn,"SELECT * FROM tbl_item_subcategory WHERE Item_category_ID='".$_GET['categoryID']."'") or die(mysqli_error($conn));
		echo "<select name='subcategory' onchange='changeItemSubcategory(this.value,".$data['Item_ID'].")' ><option></option>";
		while($row1=mysqli_fetch_array($sucategoryRs)){
		   echo "<option value='".$row1['Item_Subcategory_ID']."'>".$row1['Item_Subcategory_Name']."</option>";
		}
		echo '</select>';
		echo '</td></tr>';
	   $i++;
	}
	}else{
	   echo '<tr><td colspan="5" style="color:red;font-size:20px;font-weight:strong;text-align:center">No Item Found</td></tr>';
	}
	
	//echo $data;
   
 }
?>