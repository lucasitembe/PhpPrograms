 <?php
    @session_start();
    include("./includes/connection.php");
    
    //get Purchase_Order_ID
    if(isset($_GET['Requisition_ID'])){
        $Requisition_ID = $_GET['Requisition_ID'];
    }else{
        $Requisition_ID = 0;
    }
    
     
    $htm = "<table width ='100%' height = '30px'>
		<tr><td>
			<img src='./branchBanner/branchBanner.png' width=100%>
		    </td>
                </tr>
                <tr>
                    <td style='text-align: center;'><b>REAGENTS REQUISITION REPORT</b></td>
                </tr>
                <tr>
                    <td style='text-align: center;'><hr></td>
                </tr>
                    </table>";
    $htm .= "<table width=100%>";
    //get all basic information
    $select_info = mysqli_query($conn,"select * from tbl_reagents_requisition rq, tbl_sub_department sd, tbl_employee emp where
                                    rq.Store_Issue = sd.Sub_Department_ID and
                                            rq.Employee_ID = emp.Employee_ID and
                                                rq.Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
    
    while($row = mysqli_fetch_array($select_info)){
        $htm .= "<tr><td width=20%><b>Requisition N<u>o</u>  </b></td><td width=20%>".$row['Requisition_ID']."</td>";
        $htm .= "<td width=20%><b>Requisition Date  </b></td><td>".$row['Created_Date_Time']."</td></tr>";
        
        //get store need
        $Sub_Department_ID2 = $row['Store_Need'];
        $select_store_need = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where sub_department_id = '$Sub_Department_ID2'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select_store_need);
        if($no > 0){
            while($row2 = mysqli_fetch_array($select_store_need)){
                $Store_Need = $row2['Sub_Department_Name'];
            }
        }else{
            $Store_Need = '';
        }
        
        
        
        
        $htm .= "<tr><td><b>Store Need  </b></td><td style='text-align: left;'>".$Store_Need."</td>";
        $htm .= "<td width=15%><b>Store Issue  </b></td><td>".$row['Sub_Department_Name']."</td></tr>";
        $htm .= "<tr><td><b>Description </b></td><td colspan=3>".$row['Requisition_Description']."</td></tr>";
	$Prepared_By = $row['Employee_Name'];
    }            
                    
    $htm .= "</table><br/><table width='100%'>
            <tr>
                <td width=3%><b>Sn</b></td>
                <td><b>Particular</b></td>
                <td width=12% style='text-align: left;'><b>Quantity</b></td>
                <td width=35% style='text-align: left;'><b>Remark</b></td>
                </tr>";
            $htm .= "<tr><td colspan=4><hr></td></tr>";
    //select data from the table tbl_purchase_order_items
    $temp = 1; $Amount = 0; $Grand_Total = 0;
    $select_data = mysqli_query($conn,"select * from tbl_reagents_requisition_items rqi, tbl_reagents_items it where
                                rqi.Item_ID = it.Item_ID and Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($select_data)){
        $htm .= "<tr><td>".$temp.".</td><td>".$row['Product_Name']."</td>";
        $htm .= "<td style='text-align: left;'>".$row['Quantity_Required']."</td>";
        $htm .= "<td style='text-align: left;'>".$row['Item_Remark']."</td>";
	$temp++;
    }
    $htm .= "<tr><td colspan=4><hr></td></tr>";
    $htm .= "<tr><td colspan=4 style='text-align: right;'><b>Prepared By : ".$Prepared_By."</b></td></tr>";
    $htm .= "</table>";
?>




<?php
    include("MPDF/mpdf.php");
    $mpdf=new mPDF(); 
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>