<?php 
  include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Pharmacy'])){
	    if($_SESSION['userinfo']['Pharmacy'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }else{
		@session_start();
		if(!isset($_SESSION['Pharmacy_Supervisor'])){ 
		    header("Location: ./pharmacysupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
		}
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
 
 
 <?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Pharmacy'] == 'yes'){ 
?>
    <a href='itemsreport.php?PhrmacyReports=PharmacyReportsThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
<br/><br/>
<?php
 $Start_Date=isset($_GET['Start_Date'])?$_GET['Start_Date']:'';
 $End_Date=isset($_GET['End_Date'])?$_GET['End_Date']:'';
 $Item_ID=isset($_GET['Item_ID'])?$_GET['Item_ID']:'';
 
 $filter='';
     $duration='<b>All Time Revenue</b>';
     $servedby='<b>Services By All employees</b>';
    
     if(isset($Start_Date) && !empty($Start_Date)){
        $filter="AND tgin.Created_Date >='".$Start_Date."'";
        $duration='From&nbsp;&nbsp; <b>'.$Start_Date.'</b>';
      }
        if(isset($End_Date) && !empty($End_Date)){
        $filter="AND tgin.Created_Date <='".$End_Date."'";
         $duration='To &nbsp;&nbsp;<b>'.$End_Date.'</b>';
     
        }
        if(isset($End_Date) && !empty($End_Date) && isset($Start_Date) && !empty($Start_Date)){
        $filter=" AND tgin.Created_Date BETWEEN '". $Start_Date."' AND '".$End_Date."'";
           $duration='From&nbsp;&nbsp; <b>'.$Start_Date.'</b>&nbsp;&nbsp;&nbsp;To &nbsp;&nbsp;<b>'.$End_Date.'</b>';
        }
        
    $rs=mysqli_query($conn,"SELECT Product_Name FROM tbl_items where Item_ID = '".$Item_ID."'")or die(mysqli_error($conn));
	
	$ItemRow=mysqli_fetch_assoc($rs);
	$itemName=$ItemRow['Product_Name'];
	
	$rsDetails=mysqli_query($conn,"SELECT tgin.Created_Date,td.Department_Name,tri.Quantity_Received FROM tbl_requisition_items  tri
	                 JOIN tbl_grn_issue_note tgin ON tgin.Issue_ID=tri.Issue_ID
					 JOIN tbl_requisition AS tr  ON tri.Requisition_ID=tr.Requisition_ID
					 JOIN tbl_sub_department AS tsb ON tsb.Sub_Department_ID=tr.Store_Issue
					 JOIN tbl_department AS td ON td.Department_ID= tsb.Department_ID
	                 WHERE tri.Item_ID = '".$Item_ID."' AND tri.Item_Status='received' $filter")or die(mysqli_error($conn));
					 
	// $rsBF=mysqli_query($conn,"SELECT tgin.Created_Date,td.Department_Name,tri.Quantity_Received FROM tbl_requisition_items  tri
	                 // JOIN tbl_grn_issue_note tgin ON tgin.Issue_ID=tri.Issue_ID
					 // JOIN tbl_requisition AS tr  ON tri.Requisition_ID=tr.Requisition_ID
					 // JOIN tbl_sub_department AS tsb ON tsb.Sub_Department_ID=tr.Store_Issue
					 // JOIN tbl_department AS td ON td.Department_ID= tsb.Department_ID
	                 // WHERE tri.Item_ID = '".$Item_ID."' AND tri.Item_Status='received' AND tgin.Created_Date < '$Start_Date'")or die(mysqli_error($conn));
					 
	// while($resultDetail=mysqli_fetch_array($rsDetails)){
				   // $Created_Date=$resultDetail['Created_Date'];
				   // $Department_Name=$resultDetail['Department_Name'];
				   // $Quantity_Received=$resultDetail['Quantity_Received'];
	// }
	
?>
<center>
<fieldset  style='background-color:white;height:400px;width:90%;oveflow-y:scroll;overflow-x:hidden'>
        <legend align='center' style='background-color:#2F8BAF;padding:10px;color:white;'><b>ITEM IN STOCK DETAILS SUMMARY ~ <?php if(isset($_SESSION['Pharmacy'])){ echo strtoupper($_SESSION['Pharmacy']);  }?></b></legend>
       
          <center>
		     <br/><br/>
		      <b>
				GPITG HOSPITAL<br/><br/>
			  </b>
			  <b>
				<?php echo $duration;?>	
			  </b><br/><br/>
		   </center>
			<hr/><br/>  
			<div style="text-align:left;padding-left:2px;width:100%;margin-bottom:4px;">Medical Name:<b><?php echo $itemName; ?></b><div style="float:right;padding-right:10px;"><!--<b>B/F</b><span style="padding-left:40px"><?php //echo 23;?></span>--></div></div>
			<center>
            <table width=100%>
              <tr style='font-weight:bold'>
				<td>#</td><td>Date</td><td>Narration</td><td>Inward Flow</td><td>Outward Flow</td><td>Running Balace</td>
			  </tr>
			  <?php
			    $i=1;
				$qtyInward=0;$qtyOutward=0;
				
			    while($resultDetail=mysqli_fetch_array($rsDetails)){
				   $Created_Date=$resultDetail['Created_Date'];
				   $Department_Name=$resultDetail['Department_Name'];
				   $Quantity_Received=$resultDetail['Quantity_Received'];
				    //die($Created_Date);
				   $newDate=date('y-M-d',strtotime($Created_Date));
				   
				   $selectDespensed=mysqli_query($conn,"SELECT Quantity FROM tbl_patient_payment_item_list WHERE Item_ID = '".$Item_ID."' AND DATE(Transaction_Date_And_Time)='$Created_Date' AND Process_Status='saved' AND Check_In_Type='Pharmacy'");
				   
				   $quantityOut=0;
				   
				   if(mysqli_num_rows($selectDespensed)>0){
				     while($roq=mysqli_fetch_array($selectDespensed)){
					   $quantityOut+=$roq['Quantity'];
					 }
				     
				   }
				   
				   $qtyInward+=$Quantity_Received;
				   $qtyOutward+=$quantityOut;
				 ?>  
				     <tr>
				           <td><?php echo $i?></td>
						   <td><?php echo $newDate?></td>
						   <td><?php echo $Department_Name?></td>
						   <td><?php echo $Quantity_Received?></td>
						   <td><?php echo $quantityOut?></td>
						   <td><?php echo ($qtyInward-$quantityOut)?></td>
						  </tr> 
						  
					<?php	  
				   $i++;		  
				}
				
			  
			  ?>
			  <tr style="font-weight:bold">
				           <td style="text-align:right" colspan="3">Total</td><td><?php echo $qtyInward?></td><td><?php echo $qtyOutward?></td><td><?php echo ($qtyInward-$qtyOutward)?></td>
			  </tr>			   
			</table>
		  </center>
</fieldset></center>	  