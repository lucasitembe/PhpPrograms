<script src='js/functions.js'></script><!--<script src="jquery.js"></script>-->
<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Eye_Works'])) {
        if ($_SESSION['userinfo']['Eye_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        } else {
            @session_start();
            if (!isset($_SESSION['Optical_Supervisor'])) {
                header("Location: ./deptsupervisorauthentication.php?SessionCategory=Optical&InvalidSupervisorAuthentication=yes");
            }
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = '';
}

if (isset($_GET['Payment_Cache_ID'])) {
    $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
} else {
    $Payment_Cache_ID = '';
}

//get employee name
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = 'Unknown Employee';
}

//employee id
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 'Unknown Employee';
}
?>

<?php
if (isset($_SESSION['userinfo'])) {
    echo "<a href='glassprocessing.php?GlassProcessing=GlassProcessingThisPage' class='art-button-green'>BACK</a>";
}
?>

<?php
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}
?>

<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style> 


<?php
//get sub department id & name
if (isset($_SESSION['Optical_info'])) {
    $Sub_Department_ID5=$_SESSION['Sub_Department_ID'];
    $Sub_Department_ID = $_SESSION['Optical_info'];
}
$select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
$nu = mysqli_num_rows($select);
if ($nu > 0) {
    while ($data = mysqli_fetch_array($select)) {
        $Sub_Department_Name = $data['Sub_Department_Name'];
    }
} else {
    $Sub_Department_Name = '';
}

//    select patient information
if (isset($_GET['Payment_Cache_ID'])) {
    $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
    $select_Patient = mysqli_query($conn,"SELECT pr.Registration_ID, pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Phone_Number, pr.Member_Card_Expire_Date, sp.Sponsor_ID, pr.Registration_Date_And_Time,
                                        pr.Occupation, pr.Member_Number, pr.Employee_ID, emp.Employee_Name, pc.Folio_Number, ilc.Transaction_Type, sp.Guarantor_Name, pc.Billing_Type
                                        from tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration pr, tbl_employee emp, tbl_sponsor sp where
                                        pc.Registration_ID = pr.Registration_ID and
                                        pc.Employee_ID = emp.Employee_ID and
                                        pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
                                        pr.Sponsor_ID = sp.Sponsor_ID and
                                        pc.Payment_Cache_ID = '$Payment_Cache_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Patient);

    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Patient)) {
            $Registration_ID = $row['Registration_ID'];
            $Patient_Name = $row['Patient_Name'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $Gender = $row['Gender'];
            $Transaction_Type = $row['Transaction_Type'];
            $Guarantor_Name = $row['Guarantor_Name'];
            $Member_Number = $row['Member_Number'];
            $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
            $Phone_Number = $row['Phone_Number'];
            $Occupation = $row['Occupation'];
            $Employee_ID = $row['Employee_ID'];
            $Temp_Billing_Type = $row['Billing_Type'];
            $Consultant = $row['Employee_Name'];
            $Folio_Number = $row['Folio_Number'];
            $Registration_Date_And_Time = $row['Registration_Date_And_Time'];

            if (strtolower($Temp_Billing_Type) == 'outpatient cash' || strtolower($Temp_Billing_Type) == 'outpatient credit') {
                $Billing_Type = 'Outpatient ' . $Transaction_Type;
            } elseif (strtolower($Temp_Billing_Type) == 'inpatient cash' || strtolower($Temp_Billing_Type) == 'inpatient credit') {
                $Billing_Type = 'Inpatient ' . $Transaction_Type;
            }
        }

        $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";
        // if($age == 0){
        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age .= $diff->m . " Months, ";
        $age .= $diff->d . " Days";
    } else {
        $Registration_ID = '';
        $Patient_Name = '';
        $Sponsor_ID = '';
        $Date_Of_Birth = '';
        $Gender = '';
        $Transaction_Type = '';
        $Guarantor_Name = '';
        $Member_Number = '';
        $Member_Card_Expire_Date = '';
        $Phone_Number = '';
        $Occupation = '';
        $Employee_ID = '';
        $Temp_Billing_Type = '';
        $Consultant = '';
        $Folio_Number = '';
        $Registration_Date_And_Time = '';
    }
} else {
    $Registration_ID = '';
    $Patient_Name = '';
    $Sponsor_ID = '';
    $Date_Of_Birth = '';
    $Gender = '';
    $Transaction_Type = '';
    $Guarantor_Name = '';
    $Member_Number = '';
    $Member_Card_Expire_Date = '';
    $Phone_Number = '';
    $Occupation = '';
    $Employee_ID = '';
    $Temp_Billing_Type = '';
    $Consultant = '';
    $Folio_Number = '';
    $Registration_Date_And_Time = '';
}
?>


<!-- get employee id-->
<?php
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}
?>


<!-- get receipt number and date -->
<?php
if (isset($_GET['Payment_Cache_ID'])) {
    $Get_Receipt = mysqli_query($conn,"select Patient_Payment_ID, Payment_Date_And_Time from tbl_item_list_cache where status = 'dispensed' and 
                                    Payment_Cache_ID = '$Payment_Cache_ID' and
                                    Transaction_Type = '$Transaction_Type' group by Patient_Payment_ID limit 1");
    $no_of_rows = mysqli_num_rows($Get_Receipt);
    if ($no_of_rows > 0) {
        while ($row = mysqli_fetch_array($Get_Receipt)) {
            $Patient_Payment_ID = $row['Patient_Payment_ID'];
            $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
        }
    } else {
        $Get_Receipt = mysqli_query($conn,"select Patient_Payment_ID, Payment_Date_And_Time from tbl_item_list_cache where status = 'paid' and 
                                        Payment_Cache_ID = '$Payment_Cache_ID' and
                                        Transaction_Type = '$Transaction_Type' group by Patient_Payment_ID limit 1");
        $no_of_rows = mysqli_num_rows($Get_Receipt);
        if ($no_of_rows > 0) {
            while ($row = mysqli_fetch_array($Get_Receipt)) {
                $Patient_Payment_ID = $row['Patient_Payment_ID'];
                $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
            }
        } else {
            $Patient_Payment_ID = '';
            $Payment_Date_And_Time = '';
        }
    }
} else {
    $Patient_Payment_ID = '';
    $Payment_Date_And_Time = '';
}
?>

<fieldset>  
    <legend style='background-color:#006400;color:white;padding:8px;' align=right><b><?php if (isset($_SESSION['Optical_info'])) {
    echo 'GLASS PROCESSING ' . strtoupper($Sub_Department_Name);
} ?></b></legend>
    <center>
        <table  width="100%">
            <tr>
                <td width='10%' style='text-align: right;'>Patient Name</td>
                <td width='15%'><input type='text' name='Patient_Name' readonly='readonly' id='Patient_Name' value='<?php echo $Patient_Name; ?>'></td>
                <td width='12%' style='text-align: right;'>Card Expire Date</td>
                <td width='15%'><input type='text' name='Card_ID_Expire_Date' readonly='readonly' id='Card_ID_Expire_Date' value='<?php echo $Member_Card_Expire_Date; ?>'></td> 
                <td width='11%' style='text-align: right;'>Gender</td>
                <td width='12%'><input type='text' name='Receipt_Number' readonly='readonly' id='Receipt_Number' value='<?php echo $Gender; ?>'></td>
                <td style='text-align: right;'>Receipt Number</td>
                <td><input type='text' name='Receipt_Number' readonly='readonly' id='Receipt_Number' value='<?php echo $Patient_Payment_ID; ?>'></td>
            </tr> 
            <tr>
                <td style='text-align: right;'>Billing Type</td> 
                <td>
                    <select name='Billing_Type' id='Billing_Type'>
                        <option selected='selected'><?php echo $Billing_Type; ?></option> 
                    </select>
                </td>
                <td style='text-align: right;'>Claim Form Number</td>
                <?php
                //select the last claim form number
                $select_claim_form = mysqli_query($conn,"select Claim_Form_Number from tbl_patient_payments where
                                                        Folio_number = '$Folio_Number' and
                                                        Registration_ID = '$Registration_ID' and
                                                        Sponsor_ID = '$Sponsor_ID'
                                                        order by patient_payment_id desc limit 1");
                $nm = mysqli_num_rows($select_claim_form);
                if ($nm > 0) {
                    while ($row = mysqli_fetch_array($select_claim_form)) {
                        $Claim_Form_Number = $row['Claim_Form_Number'];
                    }
                } else {
                    $Claim_Form_Number = '';
                }
                ?>
                <td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number' readonly='readonly' value='<?php echo $Claim_Form_Number; ?>'></td>
                <td style='text-align: right;'>Occupation</td>
                <td>
                    <input type='text' name='Receipt_Date' readonly='readonly' id='date2' value='<?php echo $Occupation; ?>'>
                </td>
                <td style='text-align: right;'>Receipt Date & Time</td>
                <td>
                    <input type='text' name='Receipt_Date' readonly='readonly' id='date2' value='<?php echo $Payment_Date_And_Time; ?>'>
                    <input type='hidden' name='Receipt_Date_Hidden' id='Receipt_Date_Hidden' value='<?php echo $Payment_Date_And_Time; ?>'>
                </td>
            </tr>
            <tr>
                <td style='text-align: right;'>Type Of Check In</td>
                <td>  
                    <select name='Type_Of_Check_In' id='Type_Of_Check_In' required='required' onchange='examType()' onclick='examType()'> 
                        <option selected='selected'>Optical</option> 
                    </select>
                </td>
                <td style='text-align: right;'>Patient Age</td>
                <td><input type='text' name='Patient_Age' id='Patient_Age'  readonly='readonly' value='<?php echo $age; ?>'></td>
                <td style='text-align: right;'>Registered Date</td>
                <td><input type='text' name='Folio_Number' id='Folio_Number' readonly='readonly' value='<?php echo $Registration_Date_And_Time; ?>'></td>
                <td style='text-align: right;'>Folio Number</td>
                <td><input type='text' name='Folio_Number' id='Folio_Number' readonly='readonly' value='<?php echo $Folio_Number; ?>'></td>
            </tr>
            <tr> 
                <td style='text-align: right;'>Patient Direction</td>
                <td>
                    <select id='direction' name='direction' required='required'> 
                        <option selected='selected'>Others</option>
                    </select>
                </td>
                <td style='text-align: right;'>Sponsor Name</td>
                <td><input type='text' name='Guarantor_Name' readonly='readonly' id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>
                <td style='text-align: right;'>Phone Number</td>
                <td><input type='text' name='Phone_Number' id='Phone_Number' readonly='readonly' value='<?php echo $Phone_Number; ?>'></td>
                <td style='text-align: right;'>Prepared By</td>
                <td><input type='text' name='Prepared_By' id='Prepared_By' readonly='readonly' value='<?php echo $Employee_Name; ?>'></td>
            </tr>
            <tr>
                <td style='text-align: right;'>Consultant</td>
                <td>
                    <select name='Consultant' id='Consultant'>
                        <option selected='selected'><?php echo $Consultant; ?></option>
                    </select>
                </td>
                <td style='text-align: right;'>Registration Number</td>
                <td><input type='text' name='Registration_Number' id='Registration_Number' readonly='readonly' value='<?php echo $Registration_ID; ?>'></td>    
                <td style='text-align: right;'>Member Number</td>
                <td><input type='text' name='Supervised_By' id='Supervised_By' readonly='readonly' value='<?php echo $Member_Number; ?>'></td> 
                <td style='text-align: right;'>Supervised By</td>
                <?php
                if (isset($_SESSION['Optical_Supervisor'])) {
                    if (isset($_SESSION['Optical_Supervisor']['Session_Master_Priveleges'])) {
                        if ($_SESSION['Optical_Supervisor']['Session_Master_Priveleges'] = 'yes') {
                            $Supervisor = $_SESSION['Optical_Supervisor']['Employee_Name'];
                        } else {
                            $Supervisor = "Unknown Supervisor";
                        }
                    } else {
                        $Supervisor = "Unknown Supervisor";
                    }
                } else {
                    $Supervisor = "Unknown Supervisor";
                }
                ?> 
                <td><input type='text' name='Member_Number' id='Member_Number' readonly='readonly' value='<?php echo $Supervisor; ?>'></td>
            </tr> 
        </table>
    </center>
</fieldset>

<fieldset class="nshwbdr">   
    <center>
        <table  width=100%>
            <tr>
            
                <td style='text-align: center;'>
                    <?php
                    if (isset($_GET['Payment_Cache_ID'])) {
                        $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
                    } else {
                        $Payment_Cache_ID = '';
                    }
                    $Transaction_Status_Title = '';
                    if (isset($_GET['Registration_ID'])) {    //check if patient selected
                        //create sql
                        $Check_Status = "select Status, Transaction_Type from tbl_item_list_cache where
                                    Transaction_Type = '$Transaction_Type' and
                                    Payment_Cache_ID = '$Payment_Cache_ID' and
                                    Sub_Department_ID = '$Sub_Department_ID' and
                                    status = ";

                        $sql_Dispensed = $Check_Status . "'dispensed'";
                        $select_Status = mysqli_query($conn,$sql_Dispensed);
                        $no = mysqli_num_rows($select_Status);
                        if ($no > 0) {
                            $sql_Active = $Check_Status . "'active'";
                            //check for active items
                            $select_Status = mysqli_query($conn,$sql_Active);
                            $no = mysqli_num_rows($select_Status);

                            if ($no > 0) {
                                $Transaction_Status_Title = 'NOT PAID';
                            } else {
                                //check if there is no any paid items
                                $sql_Paid = $Check_Status . "'paid'";
                                $select_Status = mysqli_query($conn,$sql_Paid);
                                $no = mysqli_num_rows($select_Status);
                                if ($no > 0) {
                                    $Transaction_Status_Title = 'PAID';
                                } else {
                                    $Transaction_Status_Title = 'DISPENSED';
                                }
                            }
                        } else {
                            $sql_Active = $Check_Status . "'active'";
                            //check for active items
                            $select_Status = mysqli_query($conn,$sql_Active);
                            $no = mysqli_num_rows($select_Status);

                            if ($no > 0) {
                                $Transaction_Status_Title = 'NOT PAID';
                            } else {
                                //check for removed but no approved items
                                $sql_Removed = $Check_Status . "'removed'";
                                $select_Status = mysqli_query($conn,$sql_Removed);
                                $no = mysqli_num_rows($select_Status);

                                if ($no > 0) {
                                    //check if there is no any approved
                                    $sql_Approved = $Check_Status . "'approved'"; //not in use but no need to remove
                                    $select_Status = mysqli_query($conn,$sql_Approved);
                                    $no = mysqli_num_rows($select_Status);

                                    if ($no > 0) {
                                        //check if there is no any paid items
                                        $sql_Paid = $Check_Status . "'paid'";
                                        $select_Status = mysqli_query($conn,$sql_Paid);
                                        $no = mysqli_num_rows($select_Status);
                                        if ($no > 0) {
                                            $Transaction_Status_Title = 'PAID';
                                        } else {
                                            $Transaction_Status_Title = 'APPROVED'; //not in use but no need to remove
                                        }
                                    } else {
                                        //check if there is no paid items
                                        $sql_Paid = $Check_Status . "'paid'";
                                        $select_Status = mysqli_query($conn,$sql_Paid);
                                        $no = mysqli_num_rows($select_Status);
                                        if ($no > 0) {
                                            $Transaction_Status_Title = 'PAID';
                                        } else {
                                            $Transaction_Status_Title = 'ALL ITEMS REMOVED';
                                        }
                                    }
                                } else {
                                    //check for approved
                                    $sql_Approved = $Check_Status . "'approved'";
                                    $select_Status = mysqli_query($conn,$sql_Approved);
                                    $no = mysqli_num_rows($select_Status);
                                    if ($no > 0) {
                                        //check if there is no paid items
                                        $sql_Paid = $Check_Status . "'paid'";
                                        $select_Status = mysqli_query($conn,$sql_Paid);
                                        $no = mysqli_num_rows($select_Status);
                                        if ($no > 0) {
                                            $Transaction_Status_Title = 'PAID';
                                        } else {
                                            $Transaction_Status_Title = 'APPROVED'; //not in use but no need to remove
                                        }
                                    } else {
                                        // $Transaction_Status_Title = 'PAID';
                                        if (strtolower($Billing_Type) == 'outpatient cash' || strtolower($Billing_Type) == 'inpatient cash') {
                                            $Transaction_Status_Title = 'PAID';
                                        }else{
                                            $Transaction_Status_Title = 'BILLED';
                                        }
                                        
                                    }
                                }
                            }
                        }
                        
                    } else {
                        $Transaction_Status_Title = 'NO PATIENT SELECTED';
                    }
                    //echo 'STATUS : ' . $Transaction_Status_Title . '';
                    // if($Transaction_Status_Title = 'NOT PAID'){
                    //     echo "
                    // <label style='color:red; font-size:20px;'>STATUS : $Transaction_Status_Title</label>
                    // ";
                    // }else{
                    //     echo "
                    // <label style='color:blue; font-size:20px;'>STATUS : $Transaction_Status_Title</label>
                    // ";
                    // }
                        echo "
                    <label style='color:blue; font-size:20px;'>STATUS : $Transaction_Status_Title</label>
                    ";
                    ?>
                </td>
                <td style='text-align: right;' width=40%>
                    <?php
                    $Check_Status = mysqli_query($conn,"select status from tbl_item_list_cache where status = 'approved' and 
                                    Payment_Cache_ID = '$Payment_Cache_ID' and
                                    Transaction_Type = '$Transaction_Type' and
                                    Sub_Department_ID = '$Sub_Department_ID'");

                    $no = mysqli_num_rows($Check_Status);

                    //check if system setting is centralized and/or departmental
                    //get branch id
                    if (isset($_SESSION['userinfo']['Branch_ID'])) {
                        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
                    } else {
                        $Branch_ID = 0;
                    }

                    if (isset($_SESSION['systeminfo'])) {
                        $Centralized_Collection = $_SESSION['systeminfo']['Centralized_Collection'];
                        $Departmental_Collection = $_SESSION['systeminfo']['Departmental_Collection'];
                    } else {
                        $Centralized_Collection = 'yes1';
                        $Departmental_Collection = 'no1';
                    }
                    //BUTTON SEND TO CASHIER HERE//BUTTON SEND TO CASHIER HERE//BUTTON SEND TO CASHIER HERE//BUTTON SEND TO CASHIER HERE

                     

                    if (strtolower($Billing_Type) == 'outpatient cash' || strtolower($Billing_Type) == 'inpatient cash') {
                        $disp = 'DISPENSE ITEMS';
                        
                    } else {
                        $disp = 'DISPENSE & BILL ITEMS';
                    }

                    if ($Transaction_Status_Title != 'DISPENSED') {
                        if (strtolower($Billing_Type) == 'outpatient cash' || strtolower($Billing_Type) == 'inpatient cash' || strtoupper($Transaction_Status_Title) == 'PAID' || strtoupper($Transaction_Status_Title) == 'BILLED') {
                            if (strtoupper($Transaction_Status_Title) == 'PAID' && $Transaction_Status_Title != 'NO PATIENT SELECTED' || strtoupper($Transaction_Status_Title) == 'BILLED') {
                                ?>
                                <input type="button" name="Dispense_Item" id="Dispense_Item" class="art-button-green" value="<?php echo $disp; ?>" onclick="Dispense_Paid_Items(<?php echo $Payment_Cache_ID; ?>)">
                                <!-- <a href='Dispense_Optical_Items.php?Payment_Cache_ID=< ?php echo $Payment_Cache_ID; ?>&Transaction_Type=< ?php echo $Transaction_Type; ?>' class='art-button-green'>< ?php echo $disp; ?></a> -->
                            <?php } else { ?>
                                <button class='art-button-green' onclick='alert_dispense()'><?php echo $disp; ?></button>		    
                            <?php
                            }
                        } else {
                            if ($Transaction_Status_Title != 'ALL ITEMS REMOVED' && $Transaction_Status_Title != 'NO PATIENT SELECTED') {
                                ?>
                                <!-- <a href='Dispense_Credits_Optical_Items.php?Payment_Cache_ID=< ?php echo $Payment_Cache_ID; ?>&Transaction_Type=< ?php echo $Transaction_Type; ?>&Registration_ID=< ?php echo $Registration_ID; ?>&Billing_Type=< ?php echo $Billing_Type; ?>' class='art-button-green'>< ?php echo $disp; ?></a> -->
                                <button class='art-button-green' onclick='alert_dispense_bill()'><?php echo $disp; ?></button>		    

                            <?php
                            }
                        }
                    }
                    if ($Patient_Payment_ID != '' && ($Transaction_Status_Title == 'PAID' || $Transaction_Status_Title == 'DISPENSED')) {
                        if (isset($_GET['Transaction_Type'])) {
                            $Type = $_GET['Transaction_Type'];
                        } else {
                            $Type = 'Cash';
                        }

                        // if ($Type != 'Cash') {
                        //     echo "<input type='button' name='Print_Receipt' id='Print_Receipt' value='Print Debit Note' onclick='Print_Receipt_Payment()' class='art-button-green'>";
                        // } else {
                        //     echo "<input type='button' name='Print_Receipt' id='Print_Receipt' value='PRINT RECEIPT' onclick='Print_Receipt_Payment()' class='art-button-green'>";
                        // }
                    }
                    ?>
                </td>
            </tr> 
        </table>
    </center>
</fieldset>

<fieldset style='overflow-y: scroll; height: 200px; background-color: white;' id='Items_Fieldset'>
    <table width="100%">
        <tr><td colspan="8"><hr></td></tr>
        <tr>
            <td width="5%"><b>SN</b></td>
            <td><b>ITEM NAME</b></td>
            <td width="10%" style="text-align: right;"><b>PRICE</b></td>
            <td width="10%" style="text-align: right;"><b>DISCOUNT</b></td>
            <td width="10%" style="text-align: center;"><b>QUANTITY</b></td>
            <td width="10%" style="text-align: right;"><b>BALANCE</b></td>
            <td width="10%" style="text-align: right;"><b>SUB TOTAL</b></td>
        <?php
        if ($Transaction_Status_Title != 'PAID' && $Transaction_Status_Title != 'DISPENSED') {
            echo '<td width="7%"><b>ACTION</b></td>';
        }
        ?>
        </tr>
        <tr><td colspan="8"><hr></td></tr>
        <?php
        //get items
        $Grand_Total = 0;
        $select = mysqli_query($conn,"SELECT ilc.Item_ID, ilc.Price, ilc.Discount, ilc.Quantity, its.Product_Name, 
                                ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
                                from tbl_item_list_cache ilc, tbl_items its
                                where ilc.item_id = its.item_id and
                                ilc.Payment_Cache_ID = '$Payment_Cache_ID' AND ilc.Check_In_Type = 'Optical' and
                                ilc.Transaction_Type = '$Transaction_Type' and ilc.Status <> 'dispensed' AND
                                ilc.Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
        //exit($select);
        $nmz = mysqli_num_rows($select);
        if ($nmz > 0) {
            $count = 0;
            while ($data = mysqli_fetch_array($select)) {
                $Item_ID = $data['Item_ID'];
                //get balance
                $get_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where Item_ID = '$Item_ID' and Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
                $num_balance = mysqli_num_rows($get_balance);
                if ($num_balance > 0) {
                    while ($rw = mysqli_fetch_array($get_balance)) {
                        $Item_Balance = $rw['Item_Balance'];
                    }
                } else {
                    mysqli_query($conn,"insert into tbl_items_balance(Item_ID,Sub_Department_ID) values('$Item_ID','$Sub_Department_ID')") or die(mysqli_error($conn));
                    $Item_Balance = 0;
                }
                if ($Item_Balance < 0) {
                    $Item_Balance = 0;
                }
                ?>
                <tr>
                    <td><?php echo ++$count; ?></td>
                    <td><?php echo $data['Product_Name']; ?></td>
                    <td style="text-align: right;"><?php echo number_format($data['Price']); ?></td>
                    <td style="text-align: right;"><?php echo number_format($data['Discount']); ?></td>
                    <td style="text-align: center;"><?php echo $data['Quantity']; ?></td>
                    <td style="text-align: right;"><?php echo $Item_Balance; ?></td>
                    <td style="text-align: right;"><?php echo number_format(($data['Price'] - $data['Discount']) * $data['Quantity']); ?></td>
                <?php
                if ($Transaction_Status_Title != 'PAID' && $Transaction_Status_Title != 'DISPENSED') {
                    echo '<td width="7%">ACTION</td>';
                }
                ?>
                </tr>
                    <?php
                    //calculate grand total
                    $Grand_Total += (($data['Price'] - $data['Discount']) * $data['Quantity']);
                }
            }
            ?>
    </table>
</fieldset>
<table width="100%">
    <tr>
        <td style="text-align: right; background-color: white;">
<?php
echo "<b>GRAND TOTAL : " . number_format($Grand_Total) . "</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
?>
        </td>
    </tr>
</table>

<script type="text/javascript">
    function Dispense_Paid_Items(Payment_Cache_ID) {
        if (Payment_Cache_ID != null && Payment_Cache_ID != '') {
            var Registration_ID = '<?php echo $Registration_ID; ?>';
            var Transaction_Type = '<?php echo $Transaction_Type; ?>';
            var sms = confirm("Are you sure you want to dispense selected items?");
            if (sms == true) {
                document.location = "Dispense_Optical_Items.php?Payment_Cache_ID=" + Payment_Cache_ID + "&Registration_ID=" + Registration_ID + "&Transaction_Type=" + Transaction_Type;
            }
        }
    }
</script>

<script>
    function Print_Receipt_Payment() {
        var winClose = popupwindow('invidualsummaryreceiptprint.php?Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&IndividualSummaryReport=IndividualSummaryReportThisForm', 'Receipt Patient', 530, 400);
    }

    function popupwindow(url, title, w, h) {
        var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);
        return mypopupWindow;
    }
    function alert_dispense(){
        alert("ITEM NOT PAID YOU CANT DISPENSE")
        return false;
    }
    function alert_dispense_bill(){
        alert("ITEM NOT APPROVED YOU CANT DISPENSE")
        return false;
    }


</script>
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="script.js"></script>
<script src="script.responsive.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">

<script>
    $(document).ready(function () {
        $("#Previous_History").dialog({autoOpen: false, width: '80%', height: 550, title: 'PATIENT MEDICATION HISTORY', modal: true});
    });
</script>


<?php
include("./includes/footer.php");
?>