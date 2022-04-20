<?php
    	session_start();
        include("./includes/connection.php");
        $filter = '';
        
        if(isset($_GET['Date_From'])){
            $Start_Date = $_GET['Date_From'];
        }
        
        if(isset($_GET['Date_To'])){
            $End_Date = $_GET['Date_To'];
        }
        
        if(isset($_GET['working_department_ipd'])){
            $working_department_ipd = $_GET['working_department_ipd'];        
        }
       
        
        if(isset($_GET['Check_In_Type'])){
            $Check_In_Type = $_GET['Check_In_Type'];
        }
        if(isset($_GET['Billing_Type'])){
            $Billing_Type = $_GET['Billing_Type'];
        }
    
        if(isset($_GET['Sponsor_ID'])){
            $Sponsor_ID = $_GET['Sponsor_ID'];        
        }
        if(isset($_GET['Item_ID'])){
            $Item_ID = $_GET['Item_ID'];
        }
        if(isset($_GET['Status'])){
            $Status = $_GET['Status'];
        }
        if($working_department_ipd != 'All'){
            $filter = " AND ilc.finance_department_id = '$working_department_ipd' ";
        }
        if($Sponsor_ID !='All'){
            $filter .= " AND pc.Sponsor_ID = '$Sponsor_ID'";
        } 
        if($Check_In_Type != "All"){        
            $filter .= " AND Check_in_type = '$Check_In_Type'";
        }
        if($Item_ID !='All'){
            $filter .= " AND ilc.Item_ID='$Item_ID'";
        }
        if($Status !=''){
            if($Status=='Done'){
                $filter .=" AND ilc.Status NOT IN ('active', 'paid') ";
            }else if($Status=='Active'){
                $filter .=" AND ilc.Status = 'active' ";
            }else if($Status=='paid'){
                $filter .=" AND ilc.Status <> 'active' ";
            }
        }
        if($Billing_Type != 'All'){
            if($Billing_Type=='Credit'){
                $filter.=" AND pc.Billing_Type IN ('Outpatient Credit', 'Inpatient Credit')";
            }else{
                $filter.=" AND pc.Billing_Type IN ('Outpatient Cash', 'Inpatient Cash')";
            }
        }

    $htm = "<table width ='100%' height = '30px'>
    <tr><td><img src='./branchBanner/branchBanner.png' width=100%></td></tr>
    <tr><td style='text-align: center;'><b><span style='font-size: x-small;'><u>SERVICE DONE REPORT.</u></span></b></td></tr></br>
    <tr><td>$Product_name</td></tr>
    <tr><td style='text-align: center;'><b><span style='font-size: x-small;'>FROM ".@date("d F Y H:i:s",strtotime($Start_Date))." TO ".@date("d F Y H:i:s",strtotime($End_Date))."</span></b></td></tr>
    </table>
    <style>
            td{
                font-size:11px;
            }
            #tdalign{
                background-color:#ccc;
            }
        </style>";
    $htm.= "<table width='100%'>
        <thead>
            <tr id='headerstyle'>
                <td id='header' width='5%'>SN </td>
                <td id='header' width='35%'><b>ITEM NAME </b></td>
                <td id='header' width='15%'><b>NUMBER OF PATIENT </b></td>
                <td id='header' width='15%'><b>NUMBER OF SERVICE </b></td>
                <td id='header' width='15%'><b> TOTAL AMOUNT </b></td>
            </tr>
        </thead>
        <tbody>";
        $select_services = mysqli_query($conn, "SELECT Product_Name,Check_In_Type, ilc.Item_ID  FROM tbl_item_list_cache ilc, tbl_items i,   tbl_payment_cache pc WHERE pc.Payment_Cache_ID = ilc.Payment_Cache_ID AND ilc.Item_ID=i.Item_ID   AND Transaction_Date_And_Time BETWEEN '$Start_Date' AND '$End_Date' $filter GROUP BY ilc.Item_ID ") or die(mysqli_error($conn));
        $num=0;
        
        $Total_amount = 0;
        $Total_Patients=0;
        $Total_Quantitys =0;
        $Quantitys =0;
        if(mysqli_num_rows($select_services)>0){
            while($service_rw = mysqli_fetch_assoc($select_services)){
                $Product_name = $service_rw['Product_Name'];
                $Item_ID = $service_rw['Item_ID'];
                
                $Check_In_Type = $service_rw['Check_In_Type'];
                        $num++;
                        $htm .= "<tr>  
                        <td >$num</td>
                        <td>$Product_name</td>";
                        $select_item = mysqli_query($conn, "SELECT SUM((Price - Discount) * Edited_Quantity) as Edited_QuantityAMount,   sum(Quantity) as Quantity, sum((Price - Discount) * Quantity) as Amount, SUM(Edited_Quantity) AS PharmacyQuantity FROM tbl_payment_cache pc,  tbl_item_list_cache ilc   WHERE  Item_ID='$Item_ID'  AND pc.Payment_Cache_ID= ilc.Payment_Cache_ID $filter AND Transaction_Date_And_Time BETWEEN '$Start_Date' AND '$End_Date' GROUP BY pc.Registration_ID ") or die(mysqli_error($conn));
                        $Patients= mysqli_num_rows($select_item);
                      $htm.="  <td  class='rowlist'>$Patients</td>";
                        $Quantitys =0;
                        $Amount=0;
                        while($row = mysqli_fetch_assoc($select_item)){
                            $Amount +=$row['Amount'];
                            $Quantitys +=$row['Quantity'];
                            if($Check_In_Type=="Pharmacy"){                           
                                $Quantitys += $row['PharmacyQuantity']; 
                                $Amount += $row['Edited_QuantityAMount'];
                            }
                        }
                    
                    $htm.= "<td > ".number_format($Quantitys)."</td>";

                    $htm.= "<td style='text-align:right;'>".number_format($Amount)." /=</td>
                </tr>";
                $Total_Patients +=$Patients; 
                $Total_Quantitys +=$Quantitys;
                $Total_amount +=$Amount;
                    }
                }else{
                    $htm.= "<tr>
                            <td colspan='6' style='color:red; text-align:center;'><b>No result found</b></td>                            
                    </tr>";
                }

                $htm.= "<tr >
                        <th colspan='2' id='tdalign'>Total</th>
                        <th id='tdalign'>$Total_Patients</th>
                        <th id='tdalign'> ".number_format($Total_Quantitys)."</th>
                        <th colspan='2' id='tdalign' >".number_format($Total_amount)." /=</th>
                    </tr>";
                    $htm.="
        </tbody>
    </table>";

    

    include("./MPDF/mpdf.php");
    $mpdf=new mPDF('','A3', 0, '', 15,15,20,40,15,35, 'P');
    $mpdf->SetFooter('|{PAGENO}/{nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>
