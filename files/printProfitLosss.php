<?php
//$fromDate=$_GET['fromDate'];
//$toDate=$_GET['toDate'];
//
if(isset($_GET['DispenseReport'])){
 
    session_start();
    include("./includes/connection.php");
    
    if(isset($_GET['fromDate'])){
        $Start_Date = mysqli_real_escape_string($conn,$_GET['fromDate']);
    }
    
    if(isset($_GET['toDate'])){
        $End_Date = mysqli_real_escape_string($conn,$_GET['toDate']);
    }
    
    
    if(isset($_GET['Search_Value'])){
        $Search_Value = mysqli_real_escape_string($conn,$_GET['Search_Value']);
    }else{
        $Search_Value = '';
    }

    if(isset($_SESSION['Pharmacy_ID'])){
	$Sub_Department_ID = $_SESSION['Pharmacy_ID'];
    }else{
	$Sub_Department_ID = 0;
    }
    
                $htm='<table align="center" width="80%" border="0" class="hv_table">
                <tr>
		    <td>
			<img src="./branchBanner/branchBanner.png" width=100%>
		    </td>
		</tr>
                <tr>
                 <td style="text-align:center"><b>PROFIT & LOSS REPORT</b></td>
                </tr>
                <tr>
                <td style="text-align:center"><b>FROM:  '.$Start_Date.'  TO: '.$End_Date.'</b></td>
                </tr>
                 <tr>
                    <td style="text-align: center;"><hr></td>
                </tr>
              </table>
              ';
    

                $productTotal=0;
                $totalDiscount=0;
                $Total_Buying_Price=0;
                $Grand_Total_Items=0;
                $Total_Balance=0;
                $Total_Profit=0;
                $Total_Amount_Obtained=0;
                $htm.='<table width=100%>';
                $htm.="<tr>
                <td width=3%><b>SN</b></td>
                <td width=50%><b>ITEM NAME</b></td>
                <td style='text-align:right'><b>QUANTITY DISPENSED</b></td>
                <td style='text-align:right'><b>BALANCE</b></td>
                <td style='text-align:right'><b>BUYING AMOUNT</b></td>
                <td style='text-align:right'><b>COLLECTED AMOUNT</b></td>
                <td style='text-align:right'><b>PROFIT AMOUNT</b></td>
                </tr>";
                $temp = 1; $total_items = 0;
                if(isset($_SESSION['Pharmacy'])){
                    $Sub_Department_Name = $_SESSION['Pharmacy'];
                }else{
                    $Sub_Department_Name = '';
                } 
                $result = mysqli_query($conn,"select i.Item_ID, i.Product_Name FROM tbl_items i,tbl_item_list_cache ilc
                                        where i.Item_ID = ilc.Item_ID and 
                                            ilc.Payment_Date_And_Time between '$Start_Date' and '$End_Date' and
                                                ilc.Check_In_Type = 'pharmacy' and (ilc.Status = 'dispensed' or ilc.Status = 'paid') and
                                                        i.Product_Name like '%$Search_Value%' and
                                                            ilc.Sub_Department_ID = '$Sub_Department_ID'
                                                                group by i.Item_ID order by i.Product_Name limit 500") or die(mysqli_error($conn));
                //$num = mysqli_num_rows($result); echo $num; 
                while($row = mysqli_fetch_array($result)){
                    $Item_ID = $row['Item_ID'];
                    $Product_Name = $row['Product_Name'];
                    $Individual_Details = mysqli_query($conn,"select i.Product_Name, ilc.Quantity,ilc.Price,sum(ilc.Discount*ilc.Quantity) as discount,sum(ilc.Price*ilc.Quantity) as productTotal,ilc.Discount,ilc.Edited_Quantity
                                                        FROM tbl_items i,tbl_item_list_cache ilc
                                                            where i.Item_ID = ilc.Item_ID and 
                                                                ilc.Payment_Date_And_Time between '$Start_Date' and '$End_Date' and
                                                                    ilc.Check_In_Type = 'pharmacy' and
                                                                            (ilc.Status = 'dispensed' or ilc.Status = 'paid') and ilc.Item_ID = '$Item_ID' GROUP BY Payment_Item_Cache_List_ID") or die(mysqli_error($conn));

                    while($row2 = mysqli_fetch_array($Individual_Details)){
                        $Quantity = $row2['Quantity'];
                        $Discount=$row2['Discount'];
                        $Price=$row2['Price'];
                        $productTotal=$productTotal+$row2['productTotal'];
                        $totalDiscount=$totalDiscount+$row2['discount'];
                        $TotalAmount=$productTotal-$totalDiscount;
                        $Edited_Quantity = $row2['Edited_Quantity'];
                        if($Edited_Quantity != 0){
                            $total_items = $total_items + $Edited_Quantity;
                            //$sum
                        }else{
                            $total_items = $total_items + $Quantity;
                        }

                        $sumAmount=$TotalAmount;
                    }
                    
                    $buying1=mysqli_query($conn,"SELECT AVG(Buying_Price) AS Buying_Price FROM tbl_grn_open_balance_items grn JOIN tbl_item_list_cache ilc ON grn.Item_ID=ilc.Item_ID WHERE ilc.Item_ID='$Item_ID' AND ilc.Payment_Date_And_Time between '$Start_Date' and '$End_Date'");
                    $result_buy1= mysqli_fetch_assoc($buying1);
                    $grn_price=$result_buy1['Buying_Price'];
                    
                    $buying2=mysqli_query($conn,"SELECT AVG(Buying_Price) AS Buying_Price FROM tbl_purchase_order_items po JOIN tbl_item_list_cache ilc ON po.Item_ID=ilc.Item_ID WHERE ilc.Item_ID='$Item_ID' AND ilc.Payment_Date_And_Time between '$Start_Date' and '$End_Date'");
                    $result_buy2=  mysqli_fetch_assoc($buying2);
                    $purchase_price=$result_buy2['Buying_Price'];
                     
                    if((($grn_price=='') || ($grn_price==0)) && (($purchase_price=='') || ($purchase_price==0))){
                        
                    $buying1=mysqli_query($conn,"SELECT Buying_Price FROM tbl_grn_open_balance_items WHERE Item_ID='$Item_ID' AND Buying_Price<>'' OR Buying_Price<>'0' LIMIT 1");
                    $result_buy1= mysqli_fetch_assoc($buying1);
                    $grn_price=$result_buy1['Buying_Price'];
//                    
                    $buying2=mysqli_query($conn,"SELECT Buying_Price FROM tbl_purchase_order_items WHERE Item_ID='$Item_ID' AND Buying_Price<>'' OR Buying_Price<>'0' LIMIT 1");
                    $result_buy2=  mysqli_fetch_assoc($buying2);
                    $purchase_price=$result_buy2['Buying_Price'];
    
                    $average_price=(($grn_price+$purchase_price)/2)*$total_items; 

                    }elseif ((($grn_price=='') || ($grn_price==0)) && (($purchase_price!='') || ($purchase_price!=0))){
                        
                       $average_price=$purchase_price*$total_items;
            
                    }elseif ((($grn_price!='') || ($grn_price!=0)) && (($purchase_price=='') || ($purchase_price==0))){
                        
                       $average_price=$grn_price*$total_items;
                        
                    }else{
                        
                        $average_price=(($grn_price+$purchase_price)/2)*$total_items;
                        
                    }
                    
                    $profit=$sumAmount-$average_price;
                    
                    $sql_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
                                                Item_ID = '$Item_ID' and
                                                    Sub_Department_ID = '$Sub_Department_ID'
                                                        ") or die(mysqli_error($conn));
                    $num_balance = mysqli_num_rows($sql_balance);
                    if($num_balance > 0){
                        while($sd = mysqli_fetch_array($sql_balance)){
                            $Item_Balance = $sd['Item_Balance'];
                        }
                    }else{
                        $Item_Balance = 0;
                    }
                                     
                  //echo "SELECT AVG(Buying_Price) AS Buying_Price FROM tbl_purchase_order_items po JOIN tbl_item_list_cache ilc ON po.Item_ID=ilc.Item_ID WHERE ilc.Item_ID='$Item_ID' ilc.Payment_Date_And_Time between '$Start_Date' and '$End_Date'".'<br /><br />'; 
                    
                    //echo 'Item'.$Item_ID.'   grnPrice'.$grn_price.'  purchase'.$purchase_price.'<br />';
                    $htm.="<tr><td style='text-align: left;' width=5%>".$temp."</td>";
                    $htm.="<td style='text-align: left;' width=50%>".$Product_Name."</td>";
                    $htm.="<td style='text-align: right;width=5%'>".$total_items."</td>";
                    $htm.="<td style='text-align: right;width=5%'>".$Item_Balance."</td>";
                    $htm.="<td style='text-align: right;width=5%'>".number_format($average_price,0)."</td>";
                    $htm.="<td style='text-align: right;width=5%'>".number_format($TotalAmount,0)."</td>";
                    $htm.="<td style='text-align: right;width=5%'>".number_format($profit,0)."</td>";       
                    $htm.="</tr>";
                    $temp++;
                    $Edited_Quantity = 0;
                    $Quantity = 0;
                    $total_items = 0;
                    
                    $Total_Buying_Price=$Total_Buying_Price+$average_price;
                    $Total_Amount_Obtained=$Total_Amount_Obtained+$TotalAmount;
                    $Grand_Total_Items=$Grand_Total_Items+$total_items;
                    $Total_Profit=$Total_Amount_Obtained-$Total_Buying_Price;
                    $Total_Balance=$Total_Balance+$Item_Balance;
                   
                }
                
                 $htm.= "<tr style='font-weight:bold'><td></td><td>Total</td><td style='text-align:right'>".number_format($Grand_Total_Items)."</td>  <td style='text-align:right'>".number_format($Total_Balance)."</td>  <td style='text-align:right'>".  number_format($Total_Buying_Price)."</td>  <td style='text-align:right'>".number_format($Total_Amount_Obtained)."</td> <td style='text-align:right'>".  number_format($Total_Profit)."</td></tr>";
                 $htm.= '</table>';
        
        


include("MPDF/mpdf.php");
$htm = utf8_encode($htm);
$mpdf=new mPDF(); 
$mpdf->WriteHTML($htm);
$mpdf->Output();
    
    
}
?>