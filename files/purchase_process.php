<?php
session_start();
	    include("./includes/connection.php");
		$requisit_officer=$_SESSION['userinfo']['Employee_Name'];
	


if(isset($_GET['purchase_id']))
  if($_GET['purchase_id']=='new' & !isset($_GET['action'])){
	$storeneed=mysqli_real_escape_string($conn,$_POST['storeneed']);
	$supplier=mysqli_real_escape_string($conn,$_POST['store_issued']);
	$requisit_officer=mysqli_real_escape_string($conn,$_POST['requisit_officer']);
	$description=mysqli_real_escape_string($conn,$_POST['description']);
	
	    
	$item_id=mysqli_real_escape_string($conn,$_POST['Item_Name']);
	$Quantity=mysqli_real_escape_string($conn,$_POST['Quantity']);
	$unit_price=mysqli_real_escape_string($conn,$_POST['unit_price']);
	$BalanceNeeded=mysqli_real_escape_string($conn,$_POST['BalanceNeeded']);
	$Remark=mysqli_real_escape_string($conn,$_POST['Remark']);
	
	$select1=mysqli_query($conn,"SELECT Product_Name  FROM tbl_items where Item_ID='$item_id'");
	$itemname=mysqli_fetch_array($select1);
	$item_name1=$itemname['Product_Name'];
	
	$query="INSERT INTO tbl_purchase_order(order_description,created_date,send_date,store_need,supplier_id,employee_id,order_status)
				VALUES('$description',(SELECT NOW()),'','$storeneed','$supplier','$requisit_officer','Saved')";


	if($result=mysqli_query($conn,$query)){
		$select=mysqli_query($conn,"SELECT order_id FROM tbl_purchase_order WHERE employee_id = '$requisit_officer' ORDER BY order_id DESC LIMIT 1");
		$req_id=mysqli_fetch_array($select);
	$order_id=$req_id['order_id'];
		
		$query_req_item="INSERT INTO tbl_purchase_order_items(order_id,item_name,quantity_required,balance_needed,unit_price,remark,item_id)
						VALUES ('$order_id','$item_name1','$Quantity','$BalanceNeeded','$unit_price','$Remark','$item_id')";

		if($result_req_item=mysqli_query($conn,$query_req_item)){
		 header("location:purchaseorder.php?purchase_id=$order_id&page=purchaseorder");
		 }

}
	
	}else if($_GET['purchase_id'] !='new' & !isset($_GET['action'])){
	
	$item_id=mysqli_real_escape_string($conn,$_POST['Item_Name']);
	$Quantity=mysqli_real_escape_string($conn,$_POST['Quantity']);
	$unit_price=mysqli_real_escape_string($conn,$_POST['unit_price']);
	$BalanceNeeded=mysqli_real_escape_string($conn,$_POST['BalanceNeeded']);
	$Remark=mysqli_real_escape_string($conn,$_POST['Remark']);
	
	$select1=mysqli_query($conn,"SELECT Product_Name  FROM tbl_items where Item_ID='$item_id'");
	$itemname=mysqli_fetch_array($select1);
	$item_name1=$itemname['Product_Name'];
	
		$order_id=$_GET['purchase_id'];
		
		$query_req_item="INSERT INTO tbl_purchase_order_items(order_id,item_name,quantity_required,balance_needed,unit_price,remark,item_id)
						VALUES ('$order_id','$item_name1','$Quantity','$BalanceNeeded','$unit_price','$Remark','$item_id')";

		if($result_req_item=mysqli_query($conn,$query_req_item)){
		 header("location:purchaseorder.php?purchase_id=$order_id&page=purchaseorder");
		 }
	

}

if(isset($_GET['action']))
        if($_GET['action']=='send'){
                $requisit_officer=$_GET['ofcer'];
                 $purchase_id=$_GET['purchase_id'];

                $update="UPDATE tbl_purchase_order SET send_date=(SELECT NOW()),order_status ='Sent' WHERE order_id ='$purchase_id';";  	 
                if($update_query=mysqli_query($conn,$update)){
                    header("location:purchaseorder.php?status=new&sent=true&page=purchaseorder");
                }else{

                          header("location:purchaseorder.php?status=new&sent=false&page=purchaseorder"); 
                }
                
                
         }else if($_GET['action']=='edit'){
                       
            $order_id=$_POST['order_id'];
            $description=$_POST['description'];
            $purchase_date=$_POST['created_date'];
            $Store_Need=$_POST['Store_Need'];
            $Store_Issue=$_POST['Store_Issue'];
            $Employee_ID=$_POST['Employee_ID']; 

  $uReq="UPDATE tbl_purchase_order SET order_description= '$description', created_date= (SELECT NOW())  WHERE order_id = '$order_id'";
   
  if(mysqli_query($conn,$uReq)){
        $i=0;
        foreach($_POST['Requizition_Item_ID'] as $order_item_id){
            $Quantity_Required=$_POST['Quantity_Required'];
             $Balance_Needed=$_POST['Balance_Needed'];
             $unit_price=$_POST['unit_price'];
            $Item_Remark=$_POST['Item_Remark'];
            
    $uReqItems="UPDATE tbl_purchase_order_items SET quantity_required = '$Quantity_Required[$i]', balance_needed= '$Balance_Needed[$i]',
                    unit_price = '$unit_price[$i]]', remark = '$Item_Remark[$i]' WHERE order_item_id = '$order_item_id'";
         if(mysqli_query($conn,$uReqItems)){					
                     header("location:purchase_list.php?purchase=new&action=edited&requision_id=$order_item_id");
                                        
		}else{
		  header("location:purchase_list.php?purchase=new&edited=false&action=edit&requision_id=$order_item_id");
		}


          $i++;   
        }
         }
         }
         
	 
  if(isset($_GET['issue']))
         switch($_POST['submit']){
             case 'SUBMIT':
		$req_id=$_POST['Requizition_id'];
		
	$issue_ins="INSERT INTO tbl_issued(req_id,employeeIssue,issue_status) VALUES ('$req_id','$requisit_officer','Sent')";
	if(mysqli_query($conn,$issue_ins)){
	
		$i=0;
		
	foreach($_POST['Requizition_Item_ID'] as $req_item_id){
                $quantity=$_POST['quantityissued'];
             
		$set_issued="UPDATE tbl_requizition_items SET isISSUED= '1', Quantity_Issued= '$quantity[$i]' WHERE Requizition_Item_ID ='$req_item_id'";
			if(mysqli_query($conn,$set_issued)){
					
                                header("location:issue_list.php?lform=sentData&page=issue_lis&action=sent");
                                        
			}else{
				echo  $edited=false; 
			}
         
                $i++;
      
		}
                

	 }
	 break;
             case 'SAVE':
                 $req_id=$_POST['Requizition_id'];
		
	$issue_ins="INSERT INTO tbl_issued(req_id,employeeIssue,issue_status) VALUES ('$req_id','$requisit_officer','Save')";
	if(mysqli_query($conn,$issue_ins)){
	
		$i=0;
		
	foreach($_POST['Requizition_Item_ID'] as $req_item_id){
                $quantity=$_POST['quantityissued'];
             
		$set_issued="UPDATE tbl_requizition_items SET isISSUED= '1', Quantity_Issued= '$quantity[$i]' WHERE Requizition_Item_ID ='$req_item_id'";
			if(mysqli_query($conn,$set_issued)){
					
                              header("location:issue_list.php?lform=saveData&page=issue_list&action=saved");
                                        
			}else{
			 echo  $edited=false;
			}
         
                $i++;
      
		}
                

	 }
                 
            break;
       }


