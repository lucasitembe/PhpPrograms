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
    
	// if(isset($_POST['working_department_ipd'])){
    //     $working_department_ipd = $_POST['working_department_ipd'];
    //     $filter = " AND ilc.finance_department_id = '$working_department_ipd' ";
	// }

	if(isset($_POST['Sponsor_ID']) != "All"){
        $Sponsor_ID = $_POST['Sponsor_ID'];

        $filter .= " AND pp.Sponsor_ID = '$Sponsor_ID'";
    }
    
    $Sponsor_ID = $_POST['Sponsor_ID'];
    $Check_In_Type = $_POST['Check_In_Type'];
    if($Check_In_Type != 'All'){
     $filter .= " AND Check_in_type = '$Check_In_Type'";
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
                
                <?php 
                    if($Check_In_Type=="Doctor Room" || $Check_In_Type=="Laboratory" || $Check_In_Type=="Radiology"){
                        echo "<th width='10%'>NUMBER OF SERVICE</th>
                        ";
                    }else if($Check_In_Type=="Pharmacy" || $Check_In_Type =='All'){
                        echo "<th width='10%'>QUANTITY</th>";
                    }
                ?>
                <th width='10%'>PRICE</th>
                <th width="15%">TOTAL AMOUNT</th>
            </tr>
        </thead>
        <tbody>
            <?php

                $select_services = mysqli_query($conn, "SELECT Product_Name,Check_In_Type,ilc.Patient_Payment_ID, ilc.Item_ID, ilc.Price, Quantity, pc.Sponsor_ID FROM tbl_patient_payment_item_list ilc, tbl_items i, tbl_sponsor s, tbl_patient_registration pr, tbl_patient_payments pc WHERE pc.Sponsor_ID='$Sponsor_ID' AND pc.Patient_Payment_ID = ilc.Patient_Payment_ID AND ilc.Item_ID=i.Item_ID AND pr.Registration_ID=pc.Registration_ID AND pc.Billing_Type='free service' AND Transaction_Date_And_Time BETWEEN '$Start_Date' AND '$End_Date' $filter GROUP BY ilc.Item_ID ") or die(mysqli_error($conn));
                $num=0;
                $Total_amount = 0;
                $Total_Patients=0;
                $Total_Quantitys =0;
                if(mysqli_num_rows($select_services)>0){
                    while($service_rw = mysqli_fetch_assoc($select_services)){
                        $Product_name = $service_rw['Product_Name'];
                        $Price = $service_rw['Price'];
                        $Edited_Quantity = $service_rw['Edited_Quantity'];
                        $Quantity = $service_rw['Quantity'];
                        $Item_ID = $service_rw['Item_ID'];
                        $Check_In_Type = $service_rw['Check_In_Type'];
                        $Patient_Payment_ID = $service_rw['Patient_Payment_ID'];
                        $Sponsor_ID = $service_rw['Sponsor_ID'];
                        $Patients=0;
                        $num++;
                        echo "<tr>  
                        <td id='tdalign'>$num</td>
                        <td>$Product_name</td>";

                        $select_item = mysqli_query($conn, "SELECT Patient_Payment_Item_List_ID, pp.Registration_ID, Price, Quantity  FROM tbl_patient_payments pp,  tbl_patient_payment_item_list pil   WHERE pp.Sponsor_ID='$Sponsor_ID' AND Item_ID='$Item_ID' AND pp.Billing_Type='free service'  AND pp.Patient_Payment_ID= pil.Patient_Payment_ID AND Transaction_Date_And_Time BETWEEN '$Start_Date' AND '$End_Date' GROUP BY pp.Registration_ID ") or die(mysqli_error($conn));
                        if(mysqli_num_rows($select_item)>0){
                            $Patients= mysqli_num_rows($select_item);
                            echo " <td id='tdalign' class='rowlist' onclick='view_patent_dialog($Item_ID,\"$Sponsor_ID\", \"$Product_name\")'>$Patients</td>";
                            $Quantitys =0;
                            while($row = mysqli_fetch_assoc($select_item)){
                                $Price = ($row['Price']);
                                $Quantitys += $row['Quantity'];
                                $Patient_Payment_Item_List_ID = $row['Patient_Payment_Item_List_ID'];
                                
                            }
                        }
                        echo "<td id='tdalign'> ".number_format($Quantitys)."</td>";

                        
                        if($Check_In_Type=="Doctor Room" || $Check_In_Type=="Laboratory" || $Check_In_Type=="Radiology"){
                            echo "<td id='tdalign'>".number_format($Price)." /=</td>"; 
                            
                        }else if($Check_In_Type=="Pharmacy"){
                            echo "<td id='tdalign'>".number_format($Price)." /=</td>";
                        }
                        echo "<td id='tdalign'>".number_format($Price * $Quantitys)." /=</td>
                    </tr>";
                    $Total_Patients +=$Patients; 
                    $Total_Quantitys +=$Quantitys;
                    $Total_amount += ($Price * $Quantitys);
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

    +=============   COMMENTS ON THE MEETING 31/08/2020  ================+
    Upgrade ya mfumo. 
    From mysql to mysqli 
    
 -->