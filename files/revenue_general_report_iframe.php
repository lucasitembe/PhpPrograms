<?php
	session_start();
	include("./includes/connection.php");
	$filter = '';
	
	if(isset($_POST['Date_From'])){
		$Start_Date = $_POST['Date_From'];
	}
	
	if(isset($_POST['Date_To'])){
		$End_Date = $_POST['Date_To'];
	}
    
	if(isset($_POST['working_department_ipd'])){
        $working_department_ipd = $_POST['working_department_ipd'];        
	}
   
    
    if(isset($_POST['Check_In_Type'])){
        $Check_In_Type = $_POST['Check_In_Type'];
    }
    if(isset($_POST['Billing_Type'])){
        $Billing_Type = $_POST['Billing_Type'];
    }

	if(isset($_POST['Sponsor_ID'])){
        $Sponsor_ID = $_POST['Sponsor_ID'];        
    }
    if(isset($_POST['Item_ID'])){
        $Item_ID = $_POST['Item_ID'];
    }
    if(isset($_POST['Status'])){
        $Status = $_POST['Status'];
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
    ?>
    <style>
    #headerstyle {
        color:#00416a;
        background: #dedede;
        font-weight:bold;
    }
    #tdalign{
        text-align:right;
    }

    .rowlist{
        cursor: pointer; 
    }
    .rowlist:active {
        color: #328CAF!important;
        font-weight:normal!important;
    }

    .rowlist:hover{
        color:#00416a;
        background: #88c9ff;
        font-weight:bold;
    }
    </style>
    <table class="table">
        <thead>
            <tr id='headerstyle'>
                <th width="5%">SN</th>
                <th width="35%">ITEM NAME</th>
                <th width="15%">NUMBER OF PATIENT</th>
                <th width='10%'>NUMBER OF SERVICE</th>
               
                <th width="15%">TOTAL AMOUNT</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $Patients=0;
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
                        echo "<tr>  
                        <td id='tdalign'>$num</td>
                        <td>$Product_name</td>";
                        $select_item = mysqli_query($conn, "SELECT SUM((Price - Discount) * Edited_Quantity) as Edited_QuantityAMount,   sum(Quantity) as Quantity, sum((Price - Discount) * Quantity) as Amount, SUM(Edited_Quantity) AS PharmacyQuantity FROM tbl_payment_cache pc,  tbl_item_list_cache ilc   WHERE  Item_ID='$Item_ID'  AND pc.Payment_Cache_ID= ilc.Payment_Cache_ID $filter AND Transaction_Date_And_Time BETWEEN '$Start_Date' AND '$End_Date' GROUP BY pc.Registration_ID ") or die(mysqli_error($conn));
                            $Patients= mysqli_num_rows($select_item);?>
                            <td id='tdalign' class='rowlist' onclick="view_patent_dialog('<?=$Item_ID?>', '<?=$Product_name?>')"><?=$Patients?></td>
                           <?php $Quantitys =0;
                            $Amount=0;
                            while($row = mysqli_fetch_assoc($select_item)){
                                $Amount +=$row['Amount'];
                                $Quantitys +=$row['Quantity'];
                                if($Check_In_Type=="Pharmacy"){                           
                                    $Quantitys += $row['PharmacyQuantity']; 
                                    $Amount += $row['Edited_QuantityAMount'];
                                }
                            }
                        
                        echo "<td id='tdalign'> ".number_format($Quantitys)."</td>";

                        echo "<td id='tdalign'>".number_format($Amount)." /=</td>
                    </tr>";
                    $Total_Patients +=$Patients; 
                    $Total_Quantitys +=$Quantitys;
                    $Total_amount +=$Amount;
                    }
                }else{
                    echo "<tr>
                            <td colspan='6' style='color:red; text-align:center;'><b>No result found</b></td>                            
                    </tr>";
                }

                echo "<tr id='headerstyle'>
                        <th colspan='2'>Total</th>
                        <th id='tdalign'>$Total_Patients</th>
                        <th id='tdalign'> ".number_format($Total_Quantitys)."</th>
                        <th colspan='2' id='tdalign' >".number_format($Total_amount)." /=</th>
                    </tr>"
            ?>
        </tbody>
    </table>
<!-- 

    +=============   COMMENTS ON THE MEETING 31/08/2020  muga================+
   
 -->