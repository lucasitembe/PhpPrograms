<?php
        include("./includes/connection.php");


	if(isset($_GET['pgfrom'])){
		/*check where the page is from */
		switch($_GET['pgfrom']){
			case "issuenote":/*if the page is from issue note page */

	if(isset($_POST['submit'])){
		switch ($_POST['submit']) {
			case 'SUBMIT':  
					$issue_number =mysqli_real_escape_string($conn,$_POST['issue_number']);
					$supplier =mysqli_real_escape_string($conn,$_POST['supplier']);
					$receiver =mysqli_real_escape_string($conn,$_POST['receiver']);
					$supplier_ID =mysqli_real_escape_string($conn,$_POST['supplier_ID']);
					$receiver_ID =mysqli_real_escape_string($conn,$_POST['receiver_ID']);

					$sql = "INSERT INTO tbl_grnissue (issue_id, create_date, supplier, receiver) 
							VALUES ('$issue_number',(SELECT NOW()),'$supplier' ,'$receiver')";
					if($result=mysqli_query($conn,$sql)){
						$sql_setReceived=mysqli_query($conn,"UPDATE tbl_issued SET isReceived = '1' WHERE issue_ID = '$issue_number'");

					$i=0;
					foreach($_POST['Requizition_Item_ID'] as $Requizition_Item_ID){
								$requisition_id =$_POST['requisition_id'];
								$Quantity_Recieved =$_POST['Quantity_Recieved'];
						/*
						*select the id of items from table requision items
						*/
						$query=mysqli_query($conn,"SELECT Item_ID FROM tbl_requizition_items WHERE Requizition_Item_ID ='$Requizition_Item_ID' AND Requizition_ID='$requisition_id[$i]'");
						$item_number=mysqli_fetch_array($query);


//***************************************** deal with supplier **********************************************
						/*
						*check if the sub department with its item is alredy in table balance
						*/
						$sql1=mysqli_query($conn,"SELECT Quantity FROM tbl_balance WHERE Sub_Department_ID='$supplier_ID' AND Item_ID='".$item_number['Item_ID']."'");
						$disp =mysqli_fetch_array($sql1);
						/*
						*compare if the supplier can provide the need of receiver
						*/
				//if($disp['Quantity'] >= $Quantity_Recieved[$i])
				//	{
						/*if can fulfil the receiver need */
							$remaining = $disp['Quantity'] - $Quantity_Recieved[$i];
							$sql =mysqli_query($conn,"UPDATE tbl_balance SET Quantity= '$remaining'
												 WHERE Item_ID ='".$item_number['Item_ID']."' 
												 	AND Sub_Department_ID ='".$supplier_ID."'");

			//***********************deal with receiver now ****************************
						/*
						*check if the sub department with the item are in the table balance
						*/
							$sql1=mysqli_query($conn,"SELECT Quantity FROM tbl_balance WHERE Sub_Department_ID='$receiver_ID' AND Item_ID='".$item_number['Item_ID']."'");
							if(mysqli_num_rows($sql1) > 0)
							{
						/*
						*if its already in table balance then we update the value
						*/
								$disp1 =mysqli_fetch_array($sql1);
								$total =$disp1['Quantity'] + $Quantity_Recieved[$i];
								$sql2  =mysqli_query($conn,"UPDATE tbl_balance SET Quantity= '$total'
												 WHERE Item_ID ='".$item_number['Item_ID']."' 
												 	AND Sub_Department_ID ='$receiver_ID'");

							}else{
						/*
						*if its not in table balance we insert
						*/
								$sql3=mysqli_query($conn,"INSERT INTO tbl_balance(Item_ID,Sub_Department_ID,Quantity) VALUES ('".$item_number['Item_ID']."', '$receiver_ID', '$Quantity_Recieved[$i]')");

							}//end of if that check for items in tbl_balance
							

				//	}else{
								/*
								* if supplier can not fulfil the receiver need 
								*/
					//			echo 1;
					//			exit();

					//}//end of if that check if the supplierr can provide the need of the receiver

					/*
					*Now update the quantity_received in tbl_requizition_items for the given item
					*/

					 $sql_update=mysqli_query($conn,"UPDATE tbl_requizition_items SET quantity_received ='$Quantity_Recieved[$i]'
											WHERE Requizition_Item_ID ='$Requizition_Item_ID' AND Requizition_ID='$requisition_id[$i]'");
					$i++;		
					}//end of foreach
					
					/*
					*direct the page to the list of sent issue
					*/
				header('Location:grn_issuenote_list.php?lform=sentData');
				}
			}
		}

			break;

			case "openbalance":

				if(isset($_GET['grn_id']))
				  if($_GET['grn_id']=='new')
				  {
					$storeneed=mysqli_real_escape_string($conn,$_POST['storeneed']); 
					$requisit_officer=mysqli_real_escape_string($conn,$_POST['grn_number']);
					$description=mysqli_real_escape_string($conn,$_POST['grn_date']);
					$Employee_ID=mysqli_real_escape_string($conn,$_POST['Employee_ID']);
					$Branch_ID=mysqli_real_escape_string($conn,$_POST['Branch_ID']);
					
					    
					$item_id=mysqli_real_escape_string($conn,$_POST['Item_Name']);
					$_Quantity=mysqli_real_escape_string($conn,$_POST['Quantity']);
					$Quantity=preg_replace('/,/','',$_Quantity);
					$price=mysqli_real_escape_string($conn,$_POST['price']);
					
					/*find the name of item for
					* the item_id given
					 */
					$select1=mysqli_query($conn,"SELECT Product_Name  FROM tbl_items where Item_ID='$item_id'");
					$itemname=mysqli_fetch_array($select1);
					$item_name1=$itemname['Product_Name'];//name of the product


					/*
					*check the balance of that product of the given sub_department
					*if its not in the tbl_balance the product will be 
					*/
					$checkBalanceTable=mysqli_query($conn,"SELECT * FROM tbl_Balance WHERE (Item_ID='$item_id' AND Sub_Department_ID='$storeneed')");
					$tableValue=mysqli_fetch_array($checkBalanceTable);

					If(mysqli_num_rows($checkBalanceTable) >0)
					{
						/*
						*if the product is already in tbl_balance we only add the quantity received to quantity in tbl_balance
						*and then update the value in database to a new value found by addition
						*/ 
						$total=$tableValue['Quantity'] + $Quantity;
						$upDate=mysqli_query($conn,"UPDATE tbl_balance SET Quantity = '$total' 
												WHERE Item_ID='$item_id' AND Sub_Department_ID='$storeneed'");

					}else
					{
						//if product is not in tbl_balance
							$inSert=mysqli_query($conn,"INSERT INTO tbl_balance (Item_ID,Sub_Department_ID,Quantity) VALUES ('$item_id','$storeneed','$Quantity');");
					}
					
					$insert_openbalance="INSERT INTO tbl_grnopenbalance(create_date,receiver,Employee_ID,Branch_ID) 
										VALUES ((SELECT NOW()),'$storeneed','$Employee_ID','$Branch_ID')";

					/*
					*get the id inserted above and use it to insert items of grn of that id
					*/
					$select_ID=mysqli_query($conn,"SELECT * FROM tbl_grnopenbalance WHERE Employee_ID='$Employee_ID' AND Branch_ID='$Branch_ID' ORDER BY Employee_ID DESC LIMIT 1");
					$disp_id=mysqli_fetch_array($select_ID);
					$grn_id=$disp_id['grn_openbalace_id'];
							
					if($result=mysqli_query($conn,$insert_openbalance)){

						$insert_openbalance_items="INSERT INTO tbl_grnopenbalance_items(grn_openbalacne_id,item_id,item_name,quantity_received,Price)
						VALUES ('$grn_id','$item_id','$item_name1','$Quantity','$price')";

						if($result_req_item=mysqli_query($conn,$insert_openbalance_items)){
								header("location:grn_openbalance.php?grn_id={$grn_id}");
						 }



				}
				
				}else {
					$storeneed=mysqli_real_escape_string($conn,$_POST['storeneed']); 
					$item_id=mysqli_real_escape_string($conn,$_POST['Item_Name']);
					$_Quantity=mysqli_real_escape_string($conn,$_POST['Quantity']);
					$Quantity=preg_replace('/,/','',$_Quantity);
					$price=mysqli_real_escape_string($conn,$_POST['price']);
					
					$select1=mysqli_query($conn,"SELECT Product_Name  FROM tbl_items where Item_ID='$item_id'");
					$itemname=mysqli_fetch_array($select1);
					$item_name1=$itemname['Product_Name'];
					$grn_id=$_GET['grn_id'];
					
					$checkBalanceTable=mysqli_query($conn,"SELECT * FROM tbl_Balance WHERE (Item_ID='$item_id' AND Sub_Department_ID='$storeneed')");
					$tableValue=mysqli_fetch_array($checkBalanceTable);

					If(mysqli_num_rows($checkBalanceTable) >0){
					$total=$tableValue['Quantity'] +$Quantity;

					$upDate=mysqli_query($conn,"UPDATE tbl_balance SET Quantity = '$total' 
											WHERE Item_ID='$item_id' AND Sub_Department_ID='$storeneed'");
					}else{
						$inSert=mysqli_query($conn,"INSERT INTO tbl_balance (Item_ID,Sub_Department_ID,Quantity) VALUES ('$item_id','$storeneed','$Quantity');");
					}

						$insert_openbalance_items="INSERT INTO tbl_grnopenbalance_items(grn_openbalacne_id,item_id,item_name,quantity_received,Price)
						VALUES ('$grn_id','$item_id','$item_name1','$Quantity','$price')";

						if($result_req_item=mysqli_query($conn,$insert_openbalance_items)){
								header("location:grn_openbalance.php?grn_id={$_GET['grn_id']}");
						 }



		}


					break;
					case "purchase_order":

				$sIssued=$_POST['sIssued'];
				$sNeed_ID=$_POST['sIssued_ID'];
				$order_id=$_POST['order_id'];

					$sql_insert="INSERT INTO tbl_grnpurchaseorder (purchase_id, create_date,receiver,supplier_id) 
							 VALUES ('$order_id',(SELECT NOW()), '$sNeed_ID', '$sIssued')";

					if(mysqli_query($conn,$sql_insert)){

					$i=0;
					foreach($_POST['order_item_id'] AS $order_item_id){ 

						$supplied_quantity=$_POST['supplied_quantity'];
						$quantity_recieved=$_POST['quantity_recieved'];
						$buying_price=$_POST['buying_price'];

	//***********************deal with receiver now ****************************
						/*
						*check if the sub department with the item are in the table balance
						*/
                     $select_item_number=mysqli_query($conn,"SELECT item_id FROM tbl_purchase_order_items where order_item_id='$order_item_id'");
						$item_number=mysqli_fetch_array($select_item_number);

							$sql=mysqli_query($conn,"SELECT Quantity FROM tbl_balance WHERE Sub_Department_ID='$sNeed_ID' AND Item_ID='".$item_number['item_id']."'");
							if(mysqli_num_rows($sql) > 0)
							{
								
						/*
						*if its already in table balance then we update the value
						*/
								$disp1 =mysqli_fetch_array($sql);
								$total =$disp1['Quantity'] + $quantity_recieved[$i];
								//$sql_update_balance=mysqli_query($conn,
							$update_=mysqli_query($conn,"UPDATE tbl_balance SET Quantity= '$total'
												 WHERE Item_ID ='".$item_number['item_id']."' 
												 	AND Sub_Department_ID ='$sNeed_ID'");

							}else{
						/*
						*if its not in table balance we insert
						*/
							$sql_insert_balance=mysqli_query($conn,"INSERT INTO tbl_balance(Item_ID,Sub_Department_ID,Quantity) VALUES ('".$item_number['item_id']."', '$sNeed_ID', '$quantity_recieved[$i]')");

							}//end of if that check for items in tbl_balance


						mysqli_query($conn,"UPDATE tbl_purchase_order_items
								 SET quantity_supplied = '$supplied_quantity[$i]', quantity_received = '$quantity_recieved[$i]', buying_price = '$buying_price[$i]' WHERE order_item_id ='$order_item_id'");
					
						$i++;

                    }

                    		mysqli_query($conn,"UPDATE tbl_purchase_order SET isPurchaced= '1' WHERE  order_id= '$order_id' ");
							header("location:grn_purchaseorder.php");
                }

					break;
	}
}