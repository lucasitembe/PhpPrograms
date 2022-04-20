<?php
include("./includes/connection.php");
@session_start();
 if(isset($_GET['category_id'])){
	$Item_category_ID = $_GET['category_id'];
	
	
	$item=mysqli_query($conn,"SELECT * FROM tbl_items as tit INNER JOIN tbl_item_subcategory AS tis ON tit.Item_Subcategory_ID=tis.Item_Subcategory_ID
	JOIN tbl_item_category tic ON tic.Item_category_ID=tis.Item_category_ID
	WHERE tis.Item_category_ID='".$_GET['category_id']."' ORDER BY tit.Product_Name ASC") or die(mysqli_error($conn));
	$i=1;
	
	if(mysqli_num_rows($item)>0){
	
            echo '<tr style="background-color:#006400;color:white;"><th >S/N</th><th style="text-align:left">ITEM NAME</th><th style="text-align:left">SUB CATEGORY</th><th>VISIBILITY</th></tr>';    
            
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
		  <td id="'.$data['Item_ID'].'">';
		    
		
		   echo $data['Item_Subcategory_Name'];
		 
		echo '</td>'
                   . ''
                        . ' <td>
		    <select style="text-align:center;padding:3px;width:100%;font-size:19px" name="visibilitystatus" onchange="visibilitystatus('.$data['Item_ID'].',this.value,'.$_GET['category_id'].')">';
			   if($data['Status'] =='Not Available'){
			          echo "<option selected='selected' value='Not Available'>Not Available</option>";
				  echo "<option value='Available'>Available</option>";
			   }elseif($data['Status'] =='Available'){
			          echo "<option selected='selected' value='Available'>Available</option>";
				  echo "<option  value='Not Available'>Not Available</option>";
			   }else{
			          echo "<option></option>";
				  echo "<option value='Available'>Available</option>";
				  echo "<option value='Not Available'>Not Available</option>";
			   }
			  
		echo	'</select>'
                           . '</td></tr>';
	   $i++;
	}
	}else{
	   echo '<tr><td style="color:red;text-align:center;font-size:18px;font-weight:bold" colspan="5">No Item In This Category</td></tr>';
	}
	//echo $data;
   
 }elseif(isset($_GET['updateItemStatus'])){
   $change = mysqli_query($conn,"UPDATE tbl_items SET Status='".$_GET['status']."'  WHERE Item_ID='".$_GET['itmID']."'") or die(mysqli_error($conn));

       $item=mysqli_query($conn,"SELECT * FROM tbl_items as tit INNER JOIN tbl_item_subcategory AS tis ON tit.Item_Subcategory_ID=tis.Item_Subcategory_ID
	JOIN tbl_item_category tic ON tiC.Item_category_ID=tis.Item_category_ID
	WHERE tis.Item_category_ID='".$_GET['categoryID']."'") or die(mysqli_error($conn));
	$i=1;

	$dta='';
	
	
            $dta .= '<tr style="background-color:#006400;color:white;"><th >S/N</th><th style="text-align:left">ITEM NAME</th><th style="text-align:left">SUB CATEGORY</th><th>VISIBILITY</th></tr>';    
            
	$_SESSION['total_items']=mysqli_num_rows($item);
	while($data=mysqli_fetch_array($item)){
		  $dta .= '
	      <tr rowspan="1">
		  <td>
		    '.$i.'
		  </td>
		  <td>
		  <input type="hidden" value="'.$data['Item_ID'].'" name="Item_ID">
		    '.$data['Product_Name'].'
		  </td>
		  <td id="'.$data['Item_ID'].'">';
		    
		
		    $dta .= $data['Item_Subcategory_Name'];
		 
		 $dta .= '</td>'
                   . ''
                        . ' <td>
		    <select style="text-align:center;padding:3px;width:100%;font-size:19px" name="visibilitystatus" onchange="visibilitystatus('.$data['Item_ID'].',this.value,'.$_GET['categoryID'].')">';
			   if($data['Status'] =='Not Available'){
			           $dta .= "<option selected='selected' value='Not Available'>Not Available</option>";
				   $dta .= "<option value='Available'>Available</option>";
			   }elseif($data['Status'] =='Available'){
			           $dta .= "<option selected='selected' value='Available'>Available</option>";
				   $dta .= "<option  value='Not Available'>Not Available</option>";
			   }else{
			           $dta .= "<option></option>";
				   $dta .= "<option value='Available'>Available</option>";
				   $dta .= "<option value='Not Available'>Not Available</option>";
			   }
			  
		 $dta .=	'</select>'
                           . '</td></tr>';
                       
	   $i++;
	}
	 $disdta ='1tenganisha'.$dta;    
	
	//echo $data;
   
     echo $disdta;
 }elseif(isset($_GET['search_word'])){
 
   $search_word=$_GET['search_word'];
	
	
	$item=mysqli_query($conn,"SELECT * FROM tbl_items as tit INNER JOIN tbl_item_subcategory AS tis ON tit.Item_Subcategory_ID=tis.Item_Subcategory_ID WHERE tis.Item_Subcategory_ID='".$_GET['categoryID']."' AND tit.Product_Name LIKE '%$search_word%'") or die(mysqli_error($conn));
	$i=1;
	
	
	
	$_SESSION['total_items']=mysqli_num_rows($item);
	if(mysqli_num_rows($item)>0){
	     echo '<tr style="background-color:#006400;color:white;"><th >S/N</th><th style="text-align:left">ITEM NAME</th><th style="text-align:left">SUB CATEGORY</th><th>VISIBILITY</th></tr>';    
            
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
		  <td id="'.$data['Item_ID'].'">';
		    
		
		   echo $data['Item_Subcategory_Name'];
		 
		echo '</td>'
                   . ''
                        . ' <td>
		    <select style="text-align:center;padding:3px;width:100%;font-size:19px" name="visibilitystatus" onchange="visibilitystatus('.$data['Item_ID'].',this.value,'.$_GET['categoryID'].')">';
			   if($data['Status'] =='Not Available'){
			          echo "<option selected='selected' value='Not Available'>Not Available</option>";
				  echo "<option value='Available'>Available</option>";
			   }elseif($data['Status'] =='Available'){
			          echo "<option selected='selected' value='Available'>Available</option>";
				  echo "<option value='Not Available'>Not Available</option>";
			   }else{
			          echo "<option></option>";
				  echo "<option value='Available'>Available</option>";
				  echo "<option value='Not Available'>Not Available</option>";
			   }
			  
		echo	'</select>'
                           . '</td></tr>';
	   $i++;
	}
	}else{
	   echo '<tr><td colspan="5" style="color:red;font-size:20px;font-weight:strong;text-align:center">No Item Found</td></tr>';
	}
	
	//echo $data;
   
 }
?>