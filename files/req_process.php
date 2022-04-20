<?php
session_start();
	    include("./includes/connection.php");
		$requisit_officer=$_SESSION['userinfo']['Employee_Name'];
	


if(isset($_GET['requision_id']) & !isset($_GET['action']))
  if($_GET['requision_id']=='new'){
	$storeneed=mysqli_real_escape_string($conn,$_POST['storeneed']);
	$store_issued=mysqli_real_escape_string($conn,$_POST['store_issued']);
	$requisit_officer=mysqli_real_escape_string($conn,$_POST['requisit_officer']);
	$description=mysqli_real_escape_string($conn,$_POST['description']);
	$requision_date=mysqli_real_escape_string($conn,$_POST['requision_date']);
	
	    
	$item_id=mysqli_real_escape_string($conn,$_POST['Item_Name']);
	$Quantity=mysqli_real_escape_string($conn,$_POST['Quantity']);
	$BalanceStoreIssued=mysqli_real_escape_string($conn,$_POST['BalanceStoreIssued']);
	$BalanceNeeded=mysqli_real_escape_string($conn,$_POST['BalanceNeeded']);
	$Remark=mysqli_real_escape_string($conn,$_POST['Remark']);
	
	$select1=mysqli_query($conn,"SELECT Product_Name  FROM tbl_items where Item_ID='$item_id'");
	$itemname=mysqli_fetch_array($select1);
	$item_name1=$itemname['Product_Name'];
	
	$query="INSERT INTO tbl_requizition (Requizition_Descroption,Created_Date_Time,Sent_Date_Time,Store_Need,Store_Issue,Employee_ID)
				VALUES('$description',(SELECT NOW()),'','$storeneed','$store_issued','$requisit_officer')";


			
	if($result=mysqli_query($conn,$query)){
		$select=mysqli_query($conn,"SELECT Requizition_ID FROM tbl_requizition WHERE Employee_ID = '$requisit_officer' ORDER BY Requizition_ID DESC LIMIT 1");
		$req_id=mysqli_fetch_array($select);
		$requzition_id=$req_id['Requizition_ID'];
		
		$query_req_item="INSERT INTO tbl_requizition_items(Requizition_ID,Item_Name,Quantity_Required,Balance_Needed,Balance_Issued,Item_Remark,item_id)
						VALUES ('$requzition_id','$item_name1','$Quantity','$BalanceNeeded','$BalanceStoreIssued','$Remark','$item_id')";
		if($result_req_item=mysqli_query($conn,$query_req_item)){
		 header("location:requizition.php?requision_id=$requzition_id&page=requizition");
		 }

}
	
	}else {
	
	$item_id=mysqli_real_escape_string($conn,$_POST['Item_Name']);
	$Quantity=mysqli_real_escape_string($conn,$_POST['Quantity']);
	$BalanceStoreIssued=mysqli_real_escape_string($conn,$_POST['BalanceStoreIssued']);
	$BalanceNeeded=mysqli_real_escape_string($conn,$_POST['BalanceNeeded']);
	$Remark=mysqli_real_escape_string($conn,$_POST['Remark']);
	
	$select1=mysqli_query($conn,"SELECT Product_Name  FROM tbl_items where Item_ID='$item_id'");
	$itemname=mysqli_fetch_array($select1);
	$item_name1=$itemname['Product_Name'];
	
		$requzition_id=$_GET['requision_id'];
		
		$query_req_item="INSERT INTO tbl_requizition_items(Requizition_ID,Item_Name,Quantity_Required,Balance_Needed,Balance_Issued,Item_Remark,item_id)
						VALUES ('$requzition_id','$item_name1','$Quantity','$BalanceNeeded','$BalanceStoreIssued','$Remark','$item_id')";
		If($result_req_item=mysqli_query($conn,$query_req_item)){
		 header("location:requizition.php?requision_id=$requzition_id&page=requizition");
		}
	

}

if(isset($_GET['action']))
        if($_GET['action']=='send'){
                $requisit_officer=$_GET['ofcer'];
                 $requision_id=$_GET['requision_id'];

                $update="UPDATE tbl_requizition SET Sent_Date_Time=(SELECT NOW()),Requizition_Status ='Sent' WHERE Requizition_ID ='$requision_id';";
                if($update_query=mysqli_query($conn,$update)){
                     //header("location:requisition_report.php?requision_id=$requision_id");
                    header("location:requisition_list.php?requisition=list&lForm=saveData&action=sent&page=requisition_list&requision_id=$requision_id");
                }else{
                    
                           header("location:requizition.php?requision_id=$requzition_id&sent=false&page=requizition"); 
                }
                
                
         }else if($_GET['action']=='edit'){
                       
            $requision_id=$_POST['requision_id'];
            $description=$_POST['description'];
            $requision_date=$_POST['requision_date'];
            $Store_Need=$_POST['Store_Need'];
            $Store_Issue=$_POST['Store_Issue'];
            $Employee_ID=$_POST['Employee_ID'];

  $uReq="UPDATE tbl_requizition SET Requizition_Descroption = '$description', Created_Date_Time= (SELECT NOW())  WHERE Requizition_ID = '$requision_id'";
   
  if(mysqli_query($conn,$uReq)){
        $i=0;
        foreach($_POST['Requizition_Item_ID'] as $Requizition_Item_ID){
            $Quantity_Required=$_POST['Quantity_Required'];
             $Balance_Needed=$_POST['Balance_Needed'];
             $Balance_Issued=$_POST['Balance_Issued'];
            $Item_Remark=$_POST['Item_Remark'];
            
    $uReqItems="UPDATE tbl_requizition_items SET Quantity_Required = '$Quantity_Required[$i]', Balance_Needed= '$Balance_Needed[$i]',
                    Balance_Issued = '$Balance_Issued[$i]]', Item_Remark = '$Item_Remark[$i]' WHERE Requizition_Item_ID = '$Requizition_Item_ID'";
         if(mysqli_query($conn,$uReqItems)){					
                     header("location:requisition_list.php?requisition=list&lForm=saveData&action=edited&page=requisition_list&requision_id=$requision_id");
                                        
		}else{
		  header("location:requizition_edit_list.php?requisition=new&edited=false&action=edit&requision_id=$requision_id");
		}


          $i++;   
        }
         }
         }
         
	 
  if(isset($_GET['issue']))
         switch($_POST['submit']){
             case 'SUBMIT':
                        $req_id=$_POST['Requizition_id'];

                        $issue_ins="UPDATE tbl_requizition SET Requizition_Status = 'Sent', isISSUED='1' WHERE Requizition_ID = '$req_id'";
                        if(mysqli_query($conn,$issue_ins)){

                        $check_if_id_isset=mysqli_query($conn,"SELECT * FROM tbl_issued where req_id='$req_id'");
                        if(mysqli_num_rows($check_if_id_isset)>0){
                            $issue_ins=mysqli_query($conn,"UPDATE tbl_issued SET issue_status = 'Sent' WHERE req_id='$req_id'");
                        }else{
                         $issue_ins=mysqli_query($conn,"INSERT INTO tbl_issued(req_id,employeeIssue,issue_status) VALUES ('$req_id','$requisit_officer','Sent')");
                        }
          
	
                    $i=0;
                    foreach($_POST['Requizition_Item_ID'] as $req_item_id){
                            $quantity=$_POST['quantityissued'];

                            $set_issued="UPDATE tbl_requizition_items SET Quantity_Issued= '$quantity[$i]' WHERE Requizition_Item_ID ='$req_item_id'";
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
                    
                    $issue_ins="UPDATE tbl_requizition SET Requizition_Status = 'Sent', isISSUED='1' WHERE Requizition_ID = '$req_id'";
                    if(mysqli_query($conn,$issue_ins)){
                        
                        $check_if_id_isset=mysqli_query($conn,"SELECT * FROM tbl_issued where req_id='$req_id'");
                        if(mysqli_num_rows($check_if_id_isset)>0){
                            $issue_ins=mysqli_query($conn,"UPDATE tbl_issued SET issue_status = 'Sent' WHERE req_id='$req_id'");
                        }else{
                         $issue_ins=mysqli_query($conn,"INSERT INTO tbl_issued(req_id,employeeIssue,issue_status) VALUES ('$req_id','$requisit_officer','Save')");
                        }   

                        $i=0;
                        foreach($_POST['Requizition_Item_ID'] as $req_item_id){
                            $quantity=$_POST['quantityissued'];

                            $set_issued="UPDATE tbl_requizition_items SET Quantity_Issued= '$quantity[$i]' WHERE Requizition_Item_ID ='$req_item_id'";
                                   if(mysqli_query($conn,$set_issued)){
                                         header("location:issue_list.php?requisition=new&page=issue_list&action=saved");

                                   }else{
                                    echo  $edited=false;
                                   }

                            $i++;

                        }

                        header("location:issue_list.php?requisition=new&page=issue_list&action=saved");
                        }
                    
                 
            break;
       }

