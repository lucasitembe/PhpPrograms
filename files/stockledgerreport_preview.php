<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include_once("./includes/connection.php");
    include_once("./functions/items.php");
    include_once("./functions/department.php");
    include_once("./functions/supplier.php");
    include_once("./functions/stockledger.php");
    include_once("./functions/patient.php");

    if(isset($_GET['Start_Date'])){
        $Start_Date = $_GET['Start_Date'];
    }else{
        $Start_Date = '';
    }

    if(isset($_GET['End_Date'])){
        $End_Date = $_GET['End_Date'];
    }else{
        $End_Date = '';
    }

    if(isset($_GET['Sub_Department_ID'])){
        $Sub_Department_ID = $_GET['Sub_Department_ID'];
        $Sub_Department_Name = Get_Sub_Department_Name($Sub_Department_ID);
    }else{
        $Sub_Department_ID = '';
        $Sub_Department_Name = '';
    }

    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
        $Item = Get_Item($Item_ID);
        if (!empty($Item)) {
            $Product_Name = $Item['Product_Name'];
        } else {
            $Product_Name = '';
        }
    }else{
        $Item_ID = '';
        $Product_Name = '';
    }
    if(isset($_GET['item_folio_number'])){
        $item_folio_number = $_GET['item_folio_number'];
    }else{
       $item_folio_number="";
    }

    $htm = "<table width ='100%' height = '30px'>";
    $htm .= "<tr> <td> <img src='./branchBanner/branchBanner.png' width=100%> </td> </tr>";
    $htm .= "<tr><td>&nbsp;</td></tr>";
    $htm .= "<tr>";
    $htm .= "<td style='text-align: center;'><h2>STOCK LEDGER REPORT</h2></td>";
    $htm .= "</tr>";
    $htm .= "</table><br/>";

    $htm .= "<table>";
    $htm .= "<tr>";
    $htm .= "<td><b>Start Date :</b> </td><td> {$Start_Date} </td>";
    $htm .= "<td><b>End Date :</b> </td><td> {$End_Date} </td>";
    $htm .= "</tr>";
    $htm .= "<tr>";
    $htm .= "<td><b>Item Name :</b> </td><td> {$Product_Name} </td></tr><tr><td><b>Item Folio No.</b> </td><td>$item_folio_number </td>";
    $htm .= "<tr><td><b>Location :</b> </td><td> {$Sub_Department_Name} </td>";
    $htm .= "</tr>";
    $htm .= "</table>";
    $htm .= "<br/>";

    $Stock_Ledger_Details = Get_Stock_Ledger_Details($Item_ID, $Sub_Department_ID, $Start_Date, $End_Date);
    if (!empty($Stock_Ledger_Details)) {

        $Total_Inward = 0;
        $Total_Outward = 0;
        $temp = 0;

        $htm .= "<table id='items' width='100%' >";
        //$title = "<tr><td colspan='7'><hr></td></tr>";
        $title = "<tr>";
        $title .= "<td width='3%'><b>SN</b></td>";
        $title .= "<td width='8%'><b>DOC NO</b></td>";
        $title .= "<td width='10%'><b>DOC DATE</b></td>";
        $title .= "<td><b>NARRATION</b></td>";
        $title .= "<td width='10%' style='text-align: right'><b>INWARD FLOW</b></td>";
        $title .= "<td width='10%' style='text-align: right'><b>OUTWARD FLOW</b></td>";
        $title .= "<td width='13%' style='text-align: right'><b>RUNNING BALANCE</b></td>";
        $title .= "</tr></thead>";
        //$title .= "<tr><td colspan='7'><hr></td></tr>";
        foreach($Stock_Ledger_Details as $Stock_Ledger_Detail) {
            $Movement_Type = $Stock_Ledger_Detail['Movement_Type'];

            $Internal_Destination = $Stock_Ledger_Detail['Internal_Destination'];
            $Internal_Destination_Name = Get_Sub_Department_Name($Internal_Destination);

            $External_Source = $Stock_Ledger_Detail['External_Source'];
            $Supplier = Get_Supplier($External_Source);
            if (!empty($Supplier)) {
                $External_Source_Name = $Supplier['Supplier_Name'];
            }

            $Registration_ID = $Stock_Ledger_Detail['Registration_ID'];
            $Patient = Get_Patient($Registration_ID);
            if(!empty($Patient)) {
                $Patient_Name = $Patient['Patient_Name'];
            }else{
                $Patient_Name = '';
            }

            $Pre_Balance = $Stock_Ledger_Detail['Pre_Balance'];
            $Post_Balance = $Stock_Ledger_Detail['Post_Balance'];
            $Grand_Balance = $Post_Balance;
            $Document_Number = $Stock_Ledger_Detail['Document_Number'];
            $Movement_Date = $Stock_Ledger_Detail['Movement_Date'];
            $Movement_Date_Time = $Stock_Ledger_Detail['Movement_Date_Time'];

            $Inward_Quantity = $Stock_Ledger_Detail['Post_Balance'] - $Stock_Ledger_Detail['Pre_Balance'];
            $Outward_Quantity = $Stock_Ledger_Detail['Pre_Balance'] - $Stock_Ledger_Detail['Post_Balance'];

            if ($Inward_Quantity > 0 ) {
                $Outward_Quantity = 0;
                $Total_Inward += $Inward_Quantity;
            } else {
                $Inward_Quantity = 0;
                $Total_Outward += $Outward_Quantity;
            }

            if ($temp == 0) {
                $htm .= "<thead><tr><td colspan='7' style='text-align: right;'><b>B/F : {$Pre_Balance}</b></td></tr>";
                $htm .= $title;
            }

            $htm .= "<tr><td>".++$temp."<b>.</b></td>";
            $htm .= "<td> {$Document_Number} </td>";
            $htm .= "<td> {$Movement_Date} </td>";

            if(in_array($Movement_Type, array('From External','Without Purchase'))){
                $htm .= "<td> {$External_Source_Name} </td>";
            } else if(in_array($Movement_Type, array('Open Balance'))){
                $htm .= "<td> OPEN BALANCE / STOCK TAKING </td>";
            } else if(in_array($Movement_Type, array('Issue Note'))){
                $htm .= "<td> Issue ({$Internal_Destination_Name}) </td>";
            } else if(in_array($Movement_Type, array('Issue Note Manual'))){
                $htm .= "<td> Issue Manual ({$Internal_Destination_Name}) </td>";
            } else if(in_array($Movement_Type, array('Dispensed'))){
                //Get payment mode
                $Payment_Mode = 'Cash';
                $slct = mysqli_query($conn,"select Billing_Type, sp.Exemption, pp.payment_type from tbl_patient_payments pp, tbl_sponsor sp where
                                        pp.Sponsor_ID = sp.Sponsor_ID and
                                        pp.Patient_Payment_ID = '$Document_Number'") or die(mysqli_error($conn));
                $nm = mysqli_num_rows($slct);
                if($nm > 0){
                    while($data = mysqli_fetch_array($slct)){
                        if(strtolower($data['Billing_Type']) == 'outpatient credit' || strtolower($data['Billing_Type']) == 'inpatient credit' || (strtolower($data['Billing_Type']) == 'inpatient cash' && $data['payment_type'] == 'post')){
                            if(strtolower($data['Exemption']) == 'yes'){
                                $Payment_Mode = 'Exemption';
                            }else{
                                $Payment_Mode = 'Credit';
                            }
                        }else{
                            $Payment_Mode = 'Cash';
                        }
                    }
                }
                $htm .= "<td> Dispensed to ".ucwords(strtolower($Patient_Name))." - ".$Payment_Mode."</td>";
            } else if(in_array($Movement_Type, array('GRN Agains Issue Note'))){
                $htm .= "<td> GRN ({$Internal_Destination_Name}) </td>";
            } else if(in_array($Movement_Type, array('Disposal'))){
                $htm .= "<td> Disposal </td>";
            } else if(in_array($Movement_Type, array('Return Outward'))){
                $htm .= "<td> Return TO ({$External_Source_Name}) </td>";
            } else if(in_array($Movement_Type, array('Return Inward'))){
                $htm .= "<td> Return From ({$Internal_Destination_Name}) </td>";
            } else if(in_array($Movement_Type, array('Return Inward Outward'))){
                $htm .= "<td> Return TO ({$Internal_Destination_Name}) </td>";
            } else if(in_array($Movement_Type, array('Stock Taking Under'))){
                $htm .= "<td> Stock Taking (Under) </td>";
            } else if(in_array($Movement_Type, array('Stock Taking Over'))){
                $htm .= "<td> Stock Taking (Over) </td>";
            }  else if(in_array($Movement_Type, array('Received From Issue Note Manual'))){
                $htm .= "<td> Issue Note Manual From <b>{$Internal_Destination_Name}</b> </td>";
            } else {
                $htm .= "<td> Unknown Movement </td>";
            }

            $htm .= "<td style='text-align: right;'> {$Inward_Quantity} </td>";
            $htm .= "<td style='text-align: right;'> $Outward_Quantity </td>";
            $htm .= "<td style='text-align: right;'> {$Post_Balance} </td>";
            $htm .= "</tr>";
        }

        //$htm .= "<tr><td colspan='7'><hr></td></tr>";
        $htm .= "<tr>";
        $htm .= "<td colspan='4'>";
        $htm .= "<td style='text-align: right;'> <b>{$Total_Inward}</b> </td>";
        $htm .= "<td style='text-align: right;'> <b>{$Total_Outward}</b> </td>";
        $htm .= "<td style='text-align: right;'> <b>{$Grand_Balance}</b> </td>";
        $htm .= "</tr>";
        //$htm .= "<tr><td colspan='7'><hr></td></tr>";
        $htm .= "</table>";
    } else {
        $htm .= "<br/><br/><br/><br/>";
        $htm .= "<center><h3><b>NO RECORDS FOUND</b></h3></center>";
    }

    $htm .= "<style>";
    $htm .= "body { font-size: 14px; }";
    $htm .= "table#items tr td { font-size: 10px; }";
    $htm .= "table#items { border-collapse: collapse; border: 1px solid black; }";
    $htm .= "table#items td { border: 1px solid black; padding:3px 5px; }";
    $htm .= "</style>";

    include("./functions/makepdf.php");
?>