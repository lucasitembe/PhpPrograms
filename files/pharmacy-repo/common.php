<?php 
    declare(strict_types=1);
    require 'interface.php';
    $Interface = new PharmacyInterface();

    if(isset($_GET['send_to_cashier'])){
        $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
        $Transaction_Type = $_GET['Transaction_Type'];
        $Check_In_Type = $_GET['Check_In_Type'];
        $newSub_Department_ID = $_GET['sub_department_id'];
        $selectedItem = $_GET['selectedItem'];
        echo $Interface->sendToCashier($Payment_Cache_ID,$Transaction_Type,$newSub_Department_ID,$Check_In_Type,$selectedItem);
    }

    if(isset($_GET['dispense_cash_medicaion'])){
        $Employee_ID = $_GET['Employee_ID'];
        $Registration_ID = $_GET['Registration_ID'];
        $selectedItem = $_GET['selectedItem'];
        $Sub_Department_ID = $_GET['sub_department_id'];
        echo $Interface->cashDispenseItems($Employee_ID,$Registration_ID,$selectedItem,$Sub_Department_ID);
    }

    if(isset($_GET['dispense_credit'])){
        $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
        $Transaction_Type = $_GET['Transaction_Type'];
        $Billing_Type = $_GET['Billing_Type'];
        $Registration_ID = $_GET['Registration_ID'];
        $Check_In_Type = $_GET['Check_In_Type'];
        $Sub_Department_ID = $_GET['Sub_Department_ID'];
        $selectedItem = $_GET['selectedItem'];
        $Folio_Branch_ID = $_GET['Folio_Branch_ID'];
        $Sponsor_ID = $_GET['Sponsor_ID'];
        $Employee_ID = $_GET['Employee_ID'];
        echo $Interface->billAndDispenseMedication($Payment_Cache_ID,$Transaction_Type,$Billing_Type,$Registration_ID,$Check_In_Type,$Sub_Department_ID,$selectedItem,$Folio_Branch_ID,$Sponsor_ID,$Employee_ID);
    }

    if(isset($_GET['filter_quantity_report'])){
        $Start_Date = $_GET['Start_Date'];
        $End_Date = $_GET['End_Date'];
        $Search_Patient = $_GET['Search_Patient'];
        $Sponsor_ID = $_GET['Sponsor_ID'];
        $Employee_ID = $_GET['Employee_ID'];
        $Bill_Type = $_GET['Bill_Type'];
        $Payment_Mode = $_GET['Payment_Mode'];
        $Sub_Department_ID = $_GET['Sub_Department_ID'];
        $output = "";
        $count = 1;
        $grand_total = 0;

        $Today = $Interface->getTodayDateTime();
        $report_details = $Interface->filterDispenseMedicationReport($Start_Date,$End_Date,$Search_Patient,$Sponsor_ID,$Employee_ID,$Bill_Type,$Payment_Mode,$Sub_Department_ID);

        if(sizeof($report_details) > 0){
            $output .= "<b><h5 style='margin-bottom:0.5em;color:#037CB0'>Number Of Patients : ".sizeof($report_details)."</h5></b>";
            foreach($report_details as $details){
                $date1 = new DateTime($Today);
                $date2 = new DateTime($details['Date_Of_Birth']);
                $diff = $date1->diff($date2);
                $age = $diff->y . " Years, ";
                $age .= $diff->m . " Months, ";
                $age .= $diff->d . " Days";

                $output .= "<table width='100%' style='border:none;border-bottom:1px solid #ccc'>
                                <thead>
                                    <tr style='background-color: #fff;'>
                                        <td style='padding: 6px;font-weight:bold' width='3%' ><center>".$count++."</center></td>
                                        <td style='padding: 6px;font-weight:bold'>PATIENT NAME : {$details['Patient_Name']}</td>
                                        <td style='padding: 6px;font-weight:bold' colspan='2'>GENDER : {$details['Gender']}</td>
                                        <td style='padding: 6px;font-weight:bold' colspan='2'>AGE : {$age}</td>
                                        <td style='padding: 6px;font-weight:bold' colspan='2'>SPONSOR : {$details['Guarantor_Name']}</td>
                                        <td style='padding: 6px;font-weight:bold' colspan='2'>BILL : {$details['Billing_Type']}</td>
                                    </tr>
                                    <tr style='background-color: #ddd;'>
                                        <td style='padding: 6px;font-weight:bold' width='3%'><center>SN</center></td>
                                        <td style='padding: 6px;font-weight:bold' width='35%'>PRODUCT NAME</td>
                                        <td style='padding: 6px;font-weight:bold' width='8%'>DOSAGE</td>
                                        <td style='padding: 6px;font-weight:bold;text-align:right' width='6%'>PRICE</td>
                                        <td style='padding: 6px;font-weight:bold;text-align:center' width='6%'>DISCOUNT</td>
                                        <td style='padding: 6px;font-weight:bold;text-align:center' width='6%'>QUANTITY</td>
                                        <td style='padding: 6px;font-weight:bold' width='6%'>ORDERED BY</td>
                                        <td style='padding: 6px;font-weight:bold' width='6%'>DISPENSED BY</td>
                                        <td style='padding: 6px;font-weight:bold' width='6%'>DISPENSED DATE</td>
                                        <td style='padding: 6px;font-weight:bold;text-align:right' width='6%'>TOTAL</td>
                                    </tr>
                                </thead>";
                        $in = 1;
                        $__sub_total = 0;
                        foreach($details[0] as $inner_details){
                                $Consultant_name = $Interface->getConsultantName($inner_details['Consultant_ID']);

                                if($inner_details['sub_total_from_dispense_qty'] > 0){
                                    $__sub_total = $__sub_total + $inner_details['sub_total_from_dispense_qty'];
                                    $qty = $inner_details['dispensed_quantity'];
                                    $formatted_total = (int)$inner_details['sub_total_from_dispense_qty'];
                                }else if($inner_details['sub_total_from_qty'] > 0){
                                    $qty = $inner_details['Quantity'];
                                    $__sub_total = $__sub_total + $inner_details['sub_total_from_qty'];
                                    $formatted_total = (int)$inner_details['sub_total_from_qty'];
                                }else{
                                    $qty = $inner_details['Edited_Quantity'];
                                    $__sub_total = $__sub_total + $inner_details['sub_total_from_edited_dispense_qty'];
                                    $formatted_total = (int)$inner_details['sub_total_from_edited_dispense_qty'];
                                }

                                $formatted_price = (int)$inner_details['Price'];

                                $output .= "<tbody>
                                    <tr style='background-color: #fff;'>
                                        <td style='padding: 6px;' width='3%'><center>".$in++."</center></td>
                                        <td style='padding: 6px;' width='35%'>{$inner_details['Product_Name']}</td>
                                        <td style='padding: 6px;' width='7%'>{$inner_details['Doctor_Comment']}</td>
                                        <td style='padding: 6px;;text-align:right' width='7%'>".number_format($formatted_price,2)."</td>
                                        <td style='padding: 6px;text-align:center' width='7%'>{$inner_details['Discount']}</td>
                                        <td style='padding: 6px;text-align:center' width='7%'>{$qty}</td>
                                        <td style='padding: 6px;' width='7%'>{$Consultant_name}</td>
                                        <td style='padding: 6px;' width='7%'>{$inner_details['Dispensor_Name']}</td>
                                        <td style='padding: 6px;' width='7%'>{$inner_details['Dispense_Date_Time']}</td>
                                        <td style='padding: 6px;text-align:right' width='7%'>".number_format($formatted_total,2)."</td>
                                    </tr>";
                        }
                                    $grand_total = $grand_total + $__sub_total;
                                    $output .= "<tr style='background-color: #fff;'>
                                        <td colspan='9' style='padding: 6px;text-align:end'></td>
                                        <td style='padding: 6px;text-align:right;font-weight:bold'>".number_format($__sub_total,2)."</td>
                                    </tr>
                                </tbody>
                            </table><br/>";
            }
            $output.="<h5 style='float:right;margin-bottom:.5em'>Grand Total : ".number_format($grand_total)."</b>";
        }else$output .= "<table width='100%' style='border:none;border-bottom:1px solid #ccc'>
                        <thead><tr style='background-color: #fff;'><td style='padding: 20px;font-weight:bold; text-align: center;' colspan='9' >NO DATA FOUND</td></tr></thead>
                    </table>";
        echo $output;
    }

    if(isset($_GET['filter_quantity_given'])){
        $dates_From = $_GET['dates_From'];
        $dates_To = $_GET['dates_To'];
        $Sponsor_ID = $_GET['Sponsor_ID'];
        $search_item = $_GET['search_item'];
        $sub_department_id = $_GET['sub_department_id'];
        $output = "";
        $__count = 1;
        $grand_total = 0;
        $result = $Interface->getQuantityDispensedReport($dates_From,$dates_To,$Sponsor_ID,$search_item,$sub_department_id);

        if(sizeof($result) > 0){
            foreach($result as $details){
                $sum_amount = $details['sum_amount'];
                $formatted_price = (int) $sum_amount;

                $grand_total = $grand_total + $formatted_price;
                $output .= "
                    <tr style='background-color: white;'>
                        <td style='padding: 8px; text-align: center;' width='5%'>" .$__count++. "</td>
                        <td style='padding: 8px;' width='12.1%'>{$details['Product_Code']}</td>
                        <td style='padding: 8px;'>{$details['Product_Name']}</td>
                        <td style='padding: 8px; text-align: center;' width='12.6%'>{$details['quantity_dispensed']}</td>
                        <td style='padding: 8px; text-align: center;' width='11%'>{$details['Item_Balance']}</td>
                        <td style='padding: 8px;text-align:right' width='12%'>".number_format($formatted_price,2)."<input type='hidden' id='{$details['Item_ID']}' value='{$details['Product_Name']}' /></td>
                        <td width='12%'><center><a href='#' onclick='open_details({$details['Item_ID']})' class='art-button'>VIEW DETAILS</a></center></td>
                    </tr>
                ";
            }
            $output .= "
                <tr style='background-color: white;'>
                    <td style='padding:8px' colspan='5'><b>Grand Total</b></td>
                    <td style='text-align:right;padding:8px'>".number_format($grand_total,2)."</td>
                    <td style='padding:8px' style='text-align:right'></td>
                </tr>
            ";
        }else{
            $output .= "<tr style='background-color: white;'><td style='padding: 10px;' colspan='8'><center>NO DATA FOUND FROM <b>".$dates_From." TO ".$dates_To."</b> FOR SELECTED Guarantor_Name</center></td></tr>";
        }
        echo $output;
    }

    if($_GET['load_patient_dispensed_details']){
        $Start_Date = $_GET['dates_From'];
        $End_Date = $_GET['dates_To'];
        $Sub_Department_ID = $_GET['sub_department_id'];
        $Sponsor_ID = $_GET['Sponsor_ID'];
        $Item_ID = $_GET['item_id'];
        $__count = 1;
        $output = "";

        $result = $Interface->getQuantityDispensedPatientDetailsReport($Start_Date,$End_Date,$Sub_Department_ID,$Item_ID,$Sponsor_ID);

        if(sizeof($result) > 0){
            foreach($result as $details){
                $qty = $details['dispensed_quantity'] > 0 ? $details['dispensed_quantity'] : $details['Quantity'];
                $output .= "
                    <tr>
                        <td style='padding:8px;text-align:center'>".$__count++."</td>
                        <td style='padding:8px;'>{$details['Patient_Name']}</td>
                        <td style='padding:8px;'>{$details['Registration_ID']}</td>
                        <td style='padding:8px;'>{$details['Guarantor_Name']}</td>
                        <td style='padding:8px;'>{$details['Dispense_Date_Time']}</td>
                        <td style='padding:8px;text-align:center'>{$details['dispensed_quantity']}</td>
                    </tr>
                ";
                $qty = 0;
            }
        }
        echo $output;
    }

    if($_GET['not_dispense_report']){
        $Start_Date = $_GET['Start_Date'];
        $End_Date = $_GET['End_Date'];
        $Search_Patient = $_GET['Search_Patient'];
        $Sponsor_ID = $_GET['Sponsor_ID'];
        $Bill_Type = $_GET['Bill_Type'];
        $Payment_Mode = $_GET['Payment_Mode'];
        $Sub_Department_ID = $_GET['Sub_Department_ID'];
        $output = "";
        $count = 1;

        $Today = $Interface->getTodayDateTime();
        $not_dispensed = $Interface->notDispensed($Start_Date,$End_Date,$Search_Patient,$Sponsor_ID,$Bill_Type,$Payment_Mode,$Sub_Department_ID);

        if(sizeof($not_dispensed) > 0){
            $output .= "<b><h5 style='margin-bottom:0.5em;color:#037CB0'>Number Of Patients : ".sizeof($not_dispensed)."</h5></b>";
            foreach($not_dispensed as $details){
                $date1 = new DateTime($Today);
                $date2 = new DateTime($details['Date_Of_Birth']);
                $diff = $date1->diff($date2);
                $age = $diff->y . " Years, ";
                $age .= $diff->m . " Months, ";
                $age .= $diff->d . " Days";

                $output .= "
                    <table width='100%'>
                        <thead>
                            <tr style='background-color: #fff;'>
                                <td style='padding: 6px;font-weight:bold' width='3%' ><center>".$count++."</center></td>
                                <td style='padding: 6px;font-weight:bold'>PATIENT NAME : {$details['Patient_Name']}</td>
                                <td style='padding: 6px;font-weight:bold' colspan='2'>GENDER : {$details['Gender']}</td>
                                <td style='padding: 6px;font-weight:bold'> AGE : {$age}</td>
                                <td style='padding: 6px;font-weight:bold'>SPONSOR : {$details['Guarantor_Name']}</td>
                                <td style='padding: 6px;font-weight:bold'>BILL : {$details['Billing_Type']}</td>
                            </tr>

                            <tr style='background-color: #ddd;'>
                                <td style='padding: 6px;font-weight:bold' width='3%'><center>SN</center></td>
                                <td style='padding: 6px;font-weight:bold' width='35%'>PRODUCT NAME</td>
                                <td style='padding: 6px;font-weight:bold' width='8%'>DOSAGE</td>
                                <td style='padding: 6px;font-weight:bold;text-align:right' width='6%'>PRICE</td>
                                <td style='padding: 6px;font-weight:bold;text-align:center' width='12%'>DISCOUNT</td>
                                <td style='padding: 6px;font-weight:bold' width='12%'>ORDERED BY</td>
                                <td style='padding: 6px;font-weight:bold;' width='12%'>STATUS</td>
                            </tr>
                        </thead>
                    ";
                    $in = 1;
                    foreach($details[0] as $inner_details){
                        $Consultant_name = $Interface->getConsultantName($inner_details['Consultant_ID']);
                        if($inner_details['Status'] == 'active' && (strtolower($details['Billing_Type']) == ('inpatient credit') || strtolower($details['Billing_Type']) == ('outpatient credit'))){
                            $medication_status = "<span style='color:white'>Not Billed</span>";
                            $style = '#e07878';
                        }else if($inner_details['Status'] == 'active' && (strtolower($details['Billing_Type']) == ('outpatient cash'))){
                            $medication_status = "<span style='color:white'>Not Paid</span>";
                            $style = '#6565c9';
                        }else if($inner_details['Status'] == 'Out Of Stock'){
                            $medication_status = "<span style='color:red'>Out Of Stock</span>";

                        }else if($inner_details['Status'] == 'approved'){
                            $medication_status = "<span style='color:#293782'>Approved</span>";
                        }else if($inner_details['Status'] == 'removed'){
                            $medication_status = "<span>Removed</span>";
                            $style = 'burlywood';
                        }else{
                            $medication_status = ucfirst($inner_details['Status']);
                        }
                        $formatted_price = (int)$inner_details['Price'];

                        $output.="<tbody>
                                <tr style='background-color: #fff;'>
                                    <td style='padding: 6px;' width='3%'><center>".$in++."</center></td>
                                    <td style='padding: 6px;'>{$inner_details['Product_Name']}</td>
                                    <td style='padding: 6px;'>{$inner_details['Doctor_Comment']}</td>
                                    <td style='padding:6px;text-align:end'>".number_format($formatted_price,2)."</td>
                                    <td style='padding:6px;text-align:center'>{$inner_details['Discount']}</td>
                                    <td style='padding:6px;'>{$Consultant_name}</td>
                                    <td style='padding:6px;font-weight:500;background-color:$style'>$medication_status</td>
                                </tr>
                            </tbody>";
                        $medication_status="";$style ="";
                    }
                    $output .="</table><br>";
                }
            }else{
                $output .= "<table width='100%'><tr style='background-color: white;'><td style='padding: 10px;' colspan='7'><center>NO DATA FOUND</b></center></td></tr></table>";
            }
        echo $output;
    }

    if($_GET['list_patients_to_return']){
        $Start_date = $_GET['Start_date'];
        $end_date = $_GET['end_date'];
        $Patient_Name = $_GET['Patient_Name'];
        $Patient_Number = $_GET['Patient_Number'];
        $Bill_Type = $_GET['billing_type'];
        $output = "";
        $__count = 1;

        $result = $Interface->getDispenseListReturn($Start_date,$end_date,$Patient_Name,$Patient_Number,$Bill_Type);
        
        if(sizeof($result) > 0){
            foreach($result as $patient){
                $output .="
                    <tr style='background-color: #fff;' onclick='return_medication({$patient['Registration_ID']},{$patient['Payment_Cache_ID']})'>
                        <td style='padding: 6px;text-align:center' width='5%'>".$__count++."</td>
                        <td style='padding: 6px;' >{$patient['Patient_Name']}</td>
                        <td style='padding: 6px;' width='20%'>{$patient['Registration_ID']}</td>
                        <td style='padding: 6px;' width='20%'>{$patient['Guarantor_Name']}</td>
                        <td style='padding: 6px;' width='20%'>{$patient['Billing_Type']}</td>
                        <td style='padding: 6px;' width='20%'>{$patient['Dispense_Date_Time']}</td>
                    </tr>
                ";
            }
        }else{
            $output .="<tr><td colspan='6' style='padding: 10px;text-align:center'>No Patient Found</td></tr>";
        }
        echo $output;
    }

    if($_GET['remove_items_reasons_configured']){
        $result = $Interface->checkIfReasonsForRemovalConfigured() ;
        echo ($result == "yes") ? 1 :0;
    }

    if($_GET['remove_item_using_reasons']){
        $Item_ID = $_GET['Item_ID'];
        $Patient_Payment_Item_List = $_GET['id_param'];
        $output = "";

        $Item_Name = $Interface->getItem($Item_ID);
        $result = $Interface->fetchRemovalReasons();
        $output .= "
            <span style='color:red;font-size:18px'>Reason for removing ~ </span>
            <h4>$Item_Name</h4><br>
            <table width='100%'><tr><td><select id='reason_for_remove' onchange='removeWithReasons($Patient_Payment_Item_List,$Item_ID)' style='width:100%;padding:5px'><option>SELECT REASONS</option>";
        foreach($result as $reason){ $output .= "<option value='{$reason['description']}'>{$reason['description']}</option>"; }
        $output .="</select></td></tr></table>";
        echo $output;
    }

    if($_POST['remove_pharmacy_item']){
        $reason_for_remove = $_POST['reason_for_remove'];
        $Patient_Payment_Item_List = $_POST['Patient_Payment_Item_List'];
        $Item_ID = $_POST['Item_ID'];
        $Interface->removePharmacyItem($reason_for_remove,$Patient_Payment_Item_List,$Item_ID);
    }

    if($_GET['return_medication_view']){
        $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
        $Reg_No = $_GET['Reg_No'];
        $output = "";
        $__count = 1;

        $result = $Interface->fetchItemPerStatus($Payment_Cache_ID);

        $output .= "
            <table width='100%'>
            <tr style='background-color:#eee'>
            <td style='padding:8px' width='15%'><center>S/No</center></td>
            <td style='padding:8px'>ITEM NAME</td>
            <td style='padding:8px' width='20%'><center>ACTION</center></td>
            </tr>";

        if(sizeof($result) > 0){
            foreach ($result as $details){
                $output .= "
                    <tr style='background-color:#fff'>
                    <td style='padding:6px' width='15%'><center>".$__count++."</center></td>
                    <td style='padding:6px'>{$details['Product_Name']}</td>
                    <td style='padding:6px' width='20%'><center><a href='#' onclick='returnMedication({$details['Payment_Item_Cache_List_ID']})' class='art-button'>Return</a></center></td>
                    </tr>
                ";
            }
        }else{
            $output .= "<tr><td colspan='3' style='padding:8px;color:red'><b><center>NO ITEM FOUND</center></b></td></tr>";
        }
        $output .= "</table>";
        echo $output;
    }

    if($_POST['update_item_status']){
        $Patient_Payment_Item_List_Cache_ID = $_POST['Patient_Payment_Item_List_Cache_ID'];
        echo $Interface->changeItemStatus($Patient_Payment_Item_List_Cache_ID);
    }

    if($_POST['view_reasons']){
        $output = "";
        $result = $Interface->fetchAllReasonsForRemoval();
        $___count = 1;

        foreach($result as $detail){
            if($detail['status'] == 'active'){
                $style = "style='background:#a63f3f !important;color:white'";
                $text = "Disable";
            }else{
                $style = "";
                $text = "Enable";
            }

            $output .= "<tr style='background-color: #fff;'>
                <td style='padding: 6px;'><center>".$___count++."</center></td>
                <td style='padding: 6px;'>{$detail['description']}</td>
                <td style='padding: 6px;'><a href='#' onclick='$text({$detail['id']})' class='art-button' $style>{$text}</a></td>
            </tr>";
            $style = '';$text = "";
        }
        echo $output;
    }

    if($_POST['add_reasons']){
        $reasons = $_POST['reasons'];
        echo $Interface->addReason($reasons);
    }

    if($_POST['enable_disable_reasons']){
        $Id = $_POST['Id'];
        $status = $_POST['status'];
        echo $Interface->disableEnable($Id,$status);
    }

    if($_GET['check_balance_for_duplicate_items']){
        $___count = 1;
        $count = 0;
        $output = "";
        $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
        $details = $Interface->balanceForDuplicate($Payment_Cache_ID);

        if(sizeof($details) > 0){
            $output .= "<h5 style='color:#9f3833;line-height:2'><b>BALANCE NOT ENOUGH FOR THESE DUPLICATE ITEMS<b></h5>";
            $output .= "<table width='100%'>";
            $output .= "<tr style='background-color:#eee'><td style='padding:6px;text-align:center'>S/No</td><td style='padding:6px'>Item Name</td></tr>";
            foreach($details as $data){
                $output .= "<tr><td style='padding:6px;text-align:center'>".$___count++."</td><td style='padding:6px;'>$data</td></tr>";
            }
            $count++;
            $output .= "</table>";
        }else{
            $output = 1;
        }
        echo $output;
    }

    if($_POST['return_medication_pharmacy']){
        $Payment_Cache_ID = $_POST['Payment_Cache_ID'];
        $return_medication = $_POST['return_medication'];
        $Employee_ID = $_POST['Employee_ID'];
        $Registration_ID = $_POST['Registration_ID'];
        $Sub_Department_ID = $_POST['Sub_Department_ID'];

        echo $Interface->returnMedicationToPharmacy($Payment_Cache_ID,$return_medication,$Employee_ID,$Sub_Department_ID,$Registration_ID);
    }

    if($_GET['load_items_to_return']){
        $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
        $__medication = $Interface->getMedicationForReturnPatient($Payment_Cache_ID);

        $output = "";
        $__count = 1;
        $__grand_total = 0;

        foreach($__medication as $medication){ 
            $__grand_total += ($medication['dispensed_quantity'] * $medication['Price']);
            $Price = (int)$medication['Price'];
            $_sub_total = (int)$medication['dispensed_quantity'] * (int)$medication['Price'];
            $output .= "<tr style='background-color: #fff;'>
                <td style='padding: 6px;' ><center>".$__count++."</center></td>
                <td style='padding: 6px;' >{$medication['Product_Name']}</td>
                <td style='padding: 6px;' width='11%'>{$medication['Doctor_Comment']}</td>
                <td style='padding: 6px;text-align:center'>{$medication['dispensed_quantity']}</td>
                <td style='padding: 6px;text-align:right'>".number_format($Price,2)."</td>
                <td style='padding: 6px;text-align:right'>".number_format($_sub_total,2)."</td>
                <td style='padding: 6px;'>{$medication['Dispense_Date_Time']}
                    <input type='text' id='Item{$medication['Payment_Item_Cache_List_ID']}' class='hide' value='{$medication['Item_ID']}'>
                    <input type='text' id='Patient_Payment_ID{$medication['Payment_Item_Cache_List_ID']}' class='hide' value='{$medication['Patient_Payment_ID']}'>
                    <input type='text' id='Previous_qty{$medication['Payment_Item_Cache_List_ID']}' class='hide' value='{$medication['dispensed_quantity']}'>
                    <input type='text' id='Payment_Item_Cache_List_ID{$medication['Payment_Item_Cache_List_ID']}' class='hide' value='{$medication['Payment_Item_Cache_List_ID']}'>
                    <input type='text' class='returned_quantity_cls_hd hide' value='{$medication['Payment_Item_Cache_List_ID']}'>
                </td>
                <td style='padding: 6px;text-align:center'>
                    <input type='text' style='text-align: center;' id='Return_Qty{$medication['Payment_Item_Cache_List_ID']}' onkeyup='validateReturnedQty({$medication['dispensed_quantity']},{$medication['Payment_Item_Cache_List_ID']})' placeholder='Returned Quantity'>
                </td>
            </tr>
            ";
        }

        $output .= "<tr style='background-color: #ddd;'>
            <td style='padding: 6px;' colspan='5'>Grand Total</td>
            <td style='text-align: right;padding:6px'>".number_format($__grand_total,2)."</td>
            <td colspan='2'></td>
        </tr>";

        echo $output;
    }

    if($_GET['item_approval']){
        $Item_ID = $_GET['Item_ID'];
        $Registration_ID = $_GET['Registration_ID'];
        $Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];

        $output = "<br/>";
        $output .= "<input type='text' name='treatment_authorization_no' class='treatment_authorization_no' id='treatment_authorization_no$Payment_Item_Cache_List_ID' placeholder='Verify Authorization Number'  style='text-align:center;width:100%;display:inline;'><br/></br>";
        $output .= "<input type='button' name='authorize_treatment' value='NHIF-VERIFY NO' onclick='verify_service(\"$Item_ID\", \"$Registration_ID\",\"$Payment_Item_Cache_List_ID\")' id='authorize_$Payment_Item_Cache_List_ID' class='btn btn-danger btn-xs' style='padding:5px;width:100%;display:inline;'>";
        echo $output;
    }

    if($_POST['store_verification_data']){
        $Item_ID = $_POST['Item_ID'];
        $Registration_ID = $_POST['Registration_ID'];
        $Payment_Item_Cache_List_ID = $_POST['Payment_Item_Cache_List_ID'];
        $treatment_authorization_no = $_POST['treatment_authorization_no'];
        $Employee_ID = $_POST['Employee_ID'];

        echo $Interface->storeVerificationDetails($Item_ID,$Registration_ID,$Payment_Item_Cache_List_ID,$treatment_authorization_no,$Employee_ID);
    }

    if($_GET['load_outpatient_customer']){
        $patient_name = $_GET['patient_name'];
        $patient_number = $_GET['patient_number'];
        $patient_patient_number = $_GET['patient_patient_number'];
        $count = 1;
        $output = "";

        $Patient_Details = $Interface->fetchOutpatientList_($patient_name,$patient_number,$patient_patient_number);

        if(sizeof($Patient_Details) > 0){
            foreach($Patient_Details as $Patient){
                if($Patient['Admission_Status'] == 'Discharged' || $Patient['Admission_Status'] == null){
                    $Age = $Interface->getCurrentPatientAge($Patient['Date_Of_Birth']);
                    $output .= "
                        <tr style='background-color: #fff;'>
                            <td width='5%' style='padding: 8px;text-align:center'><a href='new_pharmacy_othersworks_page.php?Registration_ID={$Patient['Registration_ID']}>'<center>". $count++ ."</center></a></td>
                            <td width='15.8%' style='padding: 8px;'><a href='new_pharmacy_othersworks_page.php?Registration_ID={$Patient['Registration_ID']}'>". ucwords($Patient['Patient_Name']) ."</a></td>
                            <td width='15.8%' style='padding: 8px;'><a href='new_pharmacy_othersworks_page.php?Registration_ID={$Patient['Registration_ID']}'>". $Patient['Registration_ID'] ."</a></td>
                            <td width='15.8%' style='padding: 8px;'><a href='new_pharmacy_othersworks_page.php?Registration_ID={$Patient['Registration_ID']}'>". $Patient['Guarantor_Name']."</a></td>
                            <td width='15.8%' style='padding: 8px;'><a href='new_pharmacy_othersworks_page.php?Registration_ID={$Patient['Registration_ID']}'>{$Age}</a></td>
                            <td width='15.8%' style='padding: 8px;'><a href='new_pharmacy_othersworks_page.php?Registration_ID={$Patient['Registration_ID']}'>". $Patient['Gender']."</a></td>
                            <td width='15.8%' style='padding: 8px;'><a href='new_pharmacy_othersworks_page.php?Registration_ID={$Patient['Registration_ID']}'>". $Patient['Phone_Number']."</a></td>
                        </tr>
                    ";
                }else{
                    $In_Patient_Details = $Interface->fetchSingleInpatientDetails_($patient_name,$patient_number);
                    if(sizeof($In_Patient_Details) > 0){
                        $output = "<tr style='background-color:#fff'><td colspan='7' style='padding:8px;font-weight:500'><center>PATIENT ~ <span style='color:red'>".strtoupper($In_Patient_Details[0]['Patient_Name'])."</span> ~ FOUND IS ADMITTED IN ~ <span style='color:red'>".strtoupper($In_Patient_Details[0]['Hospital_Ward_Name'])."</span></center></td></tr>";
                    }else{
                        $output = "<tr style='background-color:#fff'><td colspan='7' style='padding:8px;font-weight:500'><center>PATIENT NOT FOUND</span></center></td></tr>";
                    }
                }
            }
        }else{
            if($patient_name != "" || $patient_number != ""){
                $In_Patient_Details = $Interface->fetchSingleInpatientDetails_($patient_name,$patient_number);
                if(sizeof($In_Patient_Details) > 0){
                    $output = "<tr style='background-color:#fff'><td colspan='7' style='padding:8px;font-weight:500'><center>PATIENT ~ <span style='color:red'>".ucwords($In_Patient_Details[0]['Patient_Name'])."</span> ~ FOUND IS ADMITTED IN ~ <span style='color:red'>".strtoupper($In_Patient_Details[0]['Hospital_Ward_Name'])."</span></center></td></tr>";
                }else{
                    $output = "<tr style='background-color:#fff'><td colspan='7' style='padding:8px;font-weight:500'><center>PATIENT NOT FOUND</span></center></td></tr>";
                }
            }
        }
        echo $output;
    }

    if($_POST['auth_usr']){
        $UserDetails = $_POST['UserDetails'];
        echo $Interface->authenticateUser_($UserDetails);
    }

?>