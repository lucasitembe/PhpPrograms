<?php
include("./includes/header.php");
include("./includes/connection.php");
//    if(isset($_GET['removeItemFromCache'])){
//        $id=filter_input(INPUT_GET, 'removeItemFromCache');
//        $sql="UPDATE tbl_item_list_cache SET status='remove' WHERE Payment_Item_Cache_List_ID='".$id."'";
//        mysqli_query($conn,$sql) or die(mysqli_error($conn));
//        
//    }
//    echo $_GET['removeItemFromCache'];
//    exit;
///////////////////////check for system configuration//////////////////

$configResult = mysqli_query($conn,"SELECT * FROM tbl_config") or die(mysqli_error($conn));

				while($data = mysqli_fetch_assoc($configResult)){
					$configname = $data['configname'];
					$configvalue = $data['configvalue'];
					$_SESSION['configData'][$configname] = strtolower($configvalue);
				}
///////////////////////////////////////////////////////////////////////////////////////
$query_string = "section=" . $_GET['section'] . "&Registration_ID=" . $_GET['Registration_ID'] . "&Transaction_Type=" . $_GET['Transaction_Type'] . "&Payment_Cache_ID=" . $_GET['Payment_Cache_ID'] . "&NR=" . $_GET['NR'] . "&Billing_Type=" . $_GET['Billing_Type'] . "&Sub_Department_ID=" . $_GET['Sub_Department_ID'] . "&LaboratoryWorks=" . $_GET['LaboratoryWorks'] . "";
if (isset($_GET['Transaction_Type'])) {
    $_SESSION['Transaction_Type'] = $_GET['Transaction_Type'];
} else {
    $_SESSION['Transaction_Type'] = '';
}

if (isset($_GET['Payment_Cache_ID'])) {
    $_SESSION['Payment_Cache_ID'] = $_GET['Payment_Cache_ID'];
} else {
    $_SESSION['Payment_Cache_ID'] = '';
}

if (isset($_GET['Sub_Department_ID'])) {
    $_SESSION['Sub_Department_ID'] = $_GET['Sub_Department_ID'];
} else {
    $_SESSION['Sub_Department_ID'] = '';
}


//echo $query_string;
//exit;

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Revenue_Center_Works'])) {
        if ($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        } else {
            @session_start();
            if (!isset($_SESSION['supervisor'])) {
                header("Location: ./departmentalsupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
            }
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>




<?php
if (isset($_GET['Transaction_Type'])) {
    $Transaction_Type = $_GET['Transaction_Type'];
} else {
    $Transaction_Type = '';
}
if (isset($_GET['Payment_Cache_ID'])) {
    $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
} else {
    $Payment_Cache_ID = '';
}
if (isset($_GET['Billing_Type'])) {
    $Temp_Billing_Type2 = $_GET['Billing_Type'];
} else {
    $Temp_Billing_Type2 = '';
}
?>


<!-- link menu -->
<script type="text/javascript">
    function gotolink() {
        var patientlist = document.getElementById('patientlist').value;
        if (patientlist == 'OUTPATIENT CASH') {
            document.location = "revenuecenterlaboratorylist.php?Billing_Type=OutpatientCash&LaboratoryList=LaboratoryListThisForm";
        } else if (patientlist == 'OUTPATIENT CREDIT') {
            document.location = "revenuecenterlaboratorylist.php?Billing_Type=OutpatientCredit&LaboratoryList=LaboratoryListThisForm";
        } else if (patientlist == 'INPATIENT CASH') {
            document.location = "revenuecenterlaboratorylist.php?Billing_Type=InpatientCash&LaboratoryList=LaboratoryListThisForm";
        } else if (patientlist == 'INPATIENT CREDIT') {
            document.location = "revenuecenterlaboratorylist.php?Billing_Type=InpatientCredit&LaboratoryList=LaboratoryListThisForm";
        } else if (patientlist == 'PATIENT FROM OUTSIDE') {
            document.location = "revenuecenterlaboratorylist.php?Billing_Type=PatientFromOutside&LaboratoryList=LaboratoryListThisForm";
        } else {
            alert("Choose Type Of Patients To View");
        }
    }
</script>

<!--<label style='border: 1px ;padding: 8px;margin-right: 7px;' class='art-button-green'>
    <select id='patientlist' name='patientlist' onchange='gotolink()'>
        <option>Select List To View</option>
        <option>
            OUTPATIENT CASH
        </option>
            <option>
        	Outpatient credit
            </option>
         <option>
            INPATIENT CASH
        </option> 
            <option>
        	Inpatient Credit
            </option>
        <option>
            PATIENT FROM OUTSIDE
        </option>
    </select>
    
    <input type='button' value='VIEW' onclick='gotolink()'>
    
</label> -->


<?php
//if(isset($_SESSION['userinfo'])){
//    if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
<!--<a href='#' class='art-button-green'>-->
<!--    VIEW - EDIT-->
<!--</a>-->
<?php //} }  ?>


<?php
//if(isset($_SESSION['userinfo'])){
//    if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
<!--<a href='#' class='art-button-green'>-->
<!--    VIEW MY DATA-->
<!--</a>-->
<?php //} }  ?>
<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes') {
        ?>
        <a href='otherspaymentlist.php?OthersPaymentList=OthersPaymentListThisPage' class='art-button-green'>
            BACK
        </a>
    <?php }
}
?>

<!-- old date function -->
<?php
/* $Today_Date = mysqli_query($conn,"select now() as today");
  while($row = mysqli_fetch_array($Today_Date)){
  $original_Date = $row['today'];
  $new_Date = date("Y-m-d", strtotime($original_Date));
  $Today = $new_Date;

  $age = $Today - $original_Date;
  } */
?>
<!-- end of old date function -->


<!-- new date function (Contain years, Months and days)--> 
<?php
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}
?>
<!-- end of the function -->


<!--popup window-->
<!-- not used-->
<!-- not used-->
<!-- not used-->

<script type='text/javascript'>
    function di() {
        alert("All");
        $("#d").attr("hidden", "false").dialog();
    }
    function b(val) {
        alert(val);
    }
</script>
<div id='d' title='CATEGORIES' hidden='hidden'>
    <a href='#' id='s' onclick="b('s')">ALL IS WELL</a><BR/>
    <a href='#'>ALL IS WELL</a><BR/>
    <a href='#'>ALL IS WELL</a><BR/>
    <a href='#'>ALL IS WELL</a><BR/>
    <a href='#'>ALL IS WELL</a><BR/>
    <a href='#'>ALL IS WELL</a><BR/>
</div>
<!-- not used-->
<!-- not used-->
<!-- end of popup window-->






<!--Getting employee name -->
<?php
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = 'Unknown Employee';
}
?>




<?php
//    select patient information
if (isset($_GET['Payment_Cache_ID'])) {
    $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
    $select_Patient = mysqli_query($conn,"select pr.Phone_Number, pr.Registration_ID, pr.Old_Registration_Number, pr.Title, pr.Patient_Name, sp.Sponsor_ID, pr.Date_Of_Birth, pr.Member_Card_Expire_Date,
                                        pr.Gender, pr.Region, pr.District, pr.Ward, sp.Guarantor_Name, pr.Member_Number, pr.Email_Address, pr.Occupation, pr.Employee_Vote_Number,
                                        pr.Emergence_Contact_Name, pr.Emergence_Contact_Number, pr.Company, emp.Employee_ID, pr.Registration_Date_And_Time, pc.Billing_Type, emp.Employee_Name, pc.Folio_Number
                                        from tbl_payment_cache pc, tbl_patient_registration pr, tbl_employee emp, tbl_sponsor sp where
                                        pc.Registration_ID = pr.Registration_ID and
                                        pc.Employee_ID = emp.Employee_ID and
                                        pc.Sponsor_ID = sp.Sponsor_ID and
                                        pc.Payment_Cache_ID = '$Payment_Cache_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Patient);

    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Patient)) {
            $Registration_ID = $row['Registration_ID'];
            $Old_Registration_Number = $row['Old_Registration_Number'];
            $Title = $row['Title'];
            $Patient_Name = ucwords(strtolower($row['Patient_Name']));
            $Sponsor_ID = $row['Sponsor_ID'];
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $Gender = $row['Gender'];
            $Region = $row['Region'];
            $District = $row['District'];
            $Ward = $row['Ward'];
            $Guarantor_Name = $row['Guarantor_Name'];
            $Member_Number = $row['Member_Number'];
            $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
            $Phone_Number = $row['Phone_Number'];
            $Email_Address = $row['Email_Address'];
            $Occupation = $row['Occupation'];
            $Employee_Vote_Number = $row['Employee_Vote_Number'];
            $Emergence_Contact_Name = $row['Emergence_Contact_Name'];
            $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
            $Company = $row['Company'];
            $Employee_ID = $row['Employee_ID'];
            $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
            $Temp_Billing_Type = $row['Billing_Type'];
            $Consultant = $row['Employee_Name'];
            $Folio_Number = $row['Folio_Number'];


            if (strtolower($Temp_Billing_Type) == 'outpatient cash' || strtolower($Temp_Billing_Type) == 'outpatient credit') {
                $Billing_Type = 'Outpatient ' . $Transaction_Type;
            } elseif (strtolower($Temp_Billing_Type) == 'inpatient cash' || strtolower($Temp_Billing_Type) == 'inpatient credit') {
                $Billing_Type = 'Inpatient ' . $Transaction_Type;
            }


            // echo $Ward."  ".$District."  ".$Ward; exit;
        }

        $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";
        // if($age == 0){

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age .= $diff->m . " Months, ";
        $age .= $diff->d . " Days";

        /* }
          if($age == 0){
          $date1 = new DateTime($Today);
          $date2 = new DateTime($Date_Of_Birth);
          $diff = $date1 -> diff($date2);
          $age = $diff->d." Days";
          } */
    } else {
        $Registration_ID = '';
        $Old_Registration_Number = '';
        $Title = '';
        $Patient_Name = '';
        $Sponsor_ID = '';
        $Date_Of_Birth = '';
        $Gender = '';
        $Region = '';
        $District = '';
        $Ward = '';
        $Guarantor_Name = '';
        $Member_Number = '';
        $Member_Card_Expire_Date = '';
        $Phone_Number = '';
        $Email_Address = '';
        $Occupation = '';
        $Employee_Vote_Number = '';
        $Emergence_Contact_Name = '';
        $Emergence_Contact_Number = '';
        $Company = '';
        $Employee_ID = '';
        $Registration_Date_And_Time = '';
        $Consultant = '';
        $Folio_Number = '';
        $Billing_Type = '';
    }
} else {
    $Registration_ID = '';
    $Old_Registration_Number = '';
    $Title = '';
    $Patient_Name = '';
    $Sponsor_ID = '';
    $Date_Of_Birth = '';
    $Gender = '';
    $Region = '';
    $District = '';
    $Ward = '';
    $Guarantor_Name = '';
    $Member_Number = '';
    $Member_Card_Expire_Date = '';
    $Phone_Number = '';
    $Email_Address = '';
    $Occupation = '';
    $Employee_Vote_Number = '';
    $Emergence_Contact_Name = '';
    $Emergence_Contact_Number = '';
    $Company = '';
    $Employee_ID = '';
    $Registration_Date_And_Time = '';
    $Consultant = '';
    $Folio_Number = '';
    $Billing_Type = '';
}
?>

<!-- get employee id-->
<?php
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}
?>





<!-- get receipt number and receipt date-->
<?php
if (isset($_GET['Patient_Payment_ID'])) {
    $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
} else {
    $Patient_Payment_ID = '';
}
if (isset($_GET['Payment_Date_And_Time'])) {
    $Payment_Date_And_Time = $_GET['Payment_Date_And_Time'];
} else {
    $Payment_Date_And_Time = '';
}
?>
<!-- end of getting receipt number and receipt date-->


<script language="javascript" type="text/javascript">
    function searchPatient(Patient_Name) {
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=100% src='Patient_Billing_Laboratory_Iframe.php?Patient_Name=" + Patient_Name + "'></iframe>";
    }
</script>
<form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    <br/>

    <fieldset>  
        <legend align=right><b>PATIENT PAYMENTS ~ OTHERS</b></legend>
        <center>
            <table width=100%> 
                <tr> 
                    <td>
                        <table width=100%>
                            <tr>
                                <td width='10%' style="text-align:right;">Patient Name</td>
                                <td width='15%'><input type='text' name='Patient_Name' disabled='disabled' id='Patient_Name' value='<?php echo $Patient_Name; ?>'></td>
                                <td width='12%' style="text-align:right;">Card Expire Date</td>
                                <td width='15%'><input type='text' name='Card_ID_Expire_Date' disabled='disabled' id='Card_ID_Expire_Date' value='<?php echo $Member_Card_Expire_Date; ?>'></td> 
                                <td width='11%' style="text-align:right;">Gender</td>
                                <td width='12%'><input type='text' name='Receipt_Number' disabled='disabled' id='Receipt_Number' value='<?php echo $Gender; ?>'></td>
                                <td style="text-align:right;">Receipt Number</td>
                                <td><input type='text' name='Receipt_Number' disabled='disabled' id='Receipt_Number' value='<?php echo $Patient_Payment_ID; ?>'></td>
                            </tr> 
                            <tr>
                                <td style="text-align:right;">Billing Type</td> 
                                <td>
                                    <select name='Billing_Type' id='Billing_Type'>
                                        <option selected='selected'><?php echo $Billing_Type; ?></option> 
                                    </select>
                                </td>
                                <td style="text-align:right;" >Claim Form Number</td>
                                <!--<td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number'></td>-->
                                <?php
                                $select_claim_status = mysqli_query($conn,"select Claim_Number_Status from tbl_sponsor where Guarantor_Name = '$Guarantor_Name'");
                                $no = mysqli_num_rows($select_claim_status);
                                if ($no > 0) {
                                    while ($row = mysqli_fetch_array($select_claim_status)) {
                                        $Claim_Number_Status = $row['Claim_Number_Status'];
                                    }
                                } else {
                                    $Claim_Number_Status = '';
                                }
                                ?>
                                <?php if (strtolower($Claim_Number_Status) == 'mandatory') { ?>
                                    <td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number' required='required' placeholder='Claim Form Number'></td>
                                <?php } else { ?>
                                    <td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number' placeholder='Claim Form Number'></td>
<?php } ?>
                                <td style="text-align:right;">Occupation</td>
                                <td>
                                    <input type='text' name='Receipt_Date' disabled='disabled' id='date2' value='<?php echo $Occupation; ?>'>
                                </td>

                                <td style="text-align:right;">Receipt Date & Time</td>
                                <td>
                                    <input type='text' name='Receipt_Date' disabled='disabled' id='date2' value='<?php echo $Payment_Date_And_Time; ?>'>
                                    <input type='hidden' name='Receipt_Date_Hidden' id='Receipt_Date_Hidden' value='<?php echo $Payment_Date_And_Time; ?>'>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Type Of Check In</td>
                                <td>  
                                    <select name='Type_Of_Check_In' id='Type_Of_Check_In' required='required' onchange='examType()' onclick='examType()'> 
                                        <option selected='selected'>Others</option> 
                                    </select>
                                </td>
                                <td style="text-align:right;">Patient Age</td>
                                <td><input type='text' name='Patient_Age' id='Patient_Age'  disabled='disabled' value='<?php echo $age; ?>'></td>
                                <td style="text-align:right;">Registered Date</td>
                                <td><input type='text' name='Folio_Number' id='Folio_Number' disabled='disabled' value='<?php echo $Registration_Date_And_Time; ?>'></td>

                                <td style="text-align:right;">Folio Number</td>
                                <td><input type='text' name='Folio_Number' id='Folio_Number' disabled='disabled' value='<?php echo $Folio_Number; ?>'></td>
                            </tr>
                            <tr> 
                                <td style="text-align:right;">Patient Direction</td>
                                <td>
                                    <select id='direction' name='direction' required='required'> 
                                        <option selected='selected'>Others</option>
                                    </select>
                                </td>
                                <td style="text-align:right;">Sponsor Name</td>
                                <td><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>
                                <td style="text-align:right;">Phone Number</td>
                                <td><input type='text' name='Phone_Number' id='Phone_Number' disabled='disabled' value='<?php echo $Phone_Number; ?>'></td>

                                <td style="text-align:right;">Prepared By</td>
                                <td><input type='text' name='Prepared_By' id='Prepared_By' disabled='disabled' value='<?php echo $Employee_Name; ?>'></td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Consultant</td>
                                <td>
                                    <select name='Consultant' id='Consultant'>
                                        <option selected='selected'><?php echo $Consultant; ?></option>
                                    </select>
                                </td>
                                <td style="text-align:right;">Registration Number</td>
                                <td><input type='text' name='Registration_Number' id='Registration_Number' disabled='disabled' value='<?php echo $Registration_ID; ?>'></td>    
                                <td style="text-align:right;">Member Number</td>
                                <td><input type='text' name='Supervised_By' id='Supervised_By' disabled='disabled' value='<?php echo $Member_Number; ?>'></td> 

                                <td style="text-align:right;">Supervised By</td>

                                <?php
                                if (isset($_SESSION['supervisor'])) {
                                    if (isset($_SESSION['supervisor']['Session_Master_Priveleges'])) {
                                        if ($_SESSION['supervisor']['Session_Master_Priveleges'] = 'yes') {
                                            $Supervisor = $_SESSION['supervisor']['Employee_Name'];
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
                                <td><input type='text' name='Member_Number' id='Member_Number' disabled='disabled' value='<?php echo $Supervisor; ?>'></td>
                            </tr> 
                        </table>
                    </td> 
                </tr>
            </table>
        </center>
    </fieldset>

    <fieldset>   
        <center>
            <table width=100%>
                <tr>
                    <td style='text-align: center;'>
                        <?php
                        if (isset($_GET['Payment_Cache_ID'])) {
                            $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
                        } else {
                            $Payment_Cache_ID = '';
                        }

                        /*
                          if(isset($_SESSION['Laboratory'])){
                          $Sub_Department_Name = $_SESSION['Laboratory'];
                          }else{
                          $Sub_Department_Name = '';
                          }
                         */
                        if (isset($_GET['Sub_Department_ID'])) {
                            $Sub_Department_ID = $_GET['Sub_Department_ID'];
                        } else {
                            $Sub_Department_ID = 0;
                        }


                        $Transaction_Status_Title = '';
//create sql
                        $Check_Status = "select Status, Transaction_Type from tbl_item_list_cache where
						Transaction_Type = '$Transaction_Type' and
						    Payment_Cache_ID = '$Payment_Cache_ID' and
							    status = ";
                        $sqlSt = $Check_Status . "'dispensed'";
//check for dispensed
                        $select_Status = mysqli_query($conn,$sqlSt);
                        $no = mysqli_num_rows($select_Status);

//check for paid
                        $sqlSt = $Check_Status . "'paid'";
                        $select_Status = mysqli_query($conn,$sqlSt);
                        $no = mysqli_num_rows($select_Status);
                        if ($no > 0) {



                            $sqlSt = $Check_Status . "'active' OR status = 'approved'";
                            //check for active
                            $select_Status = mysqli_query($conn,$sqlSt);
                            $no = mysqli_num_rows($select_Status);

                            if ($no > 0) {
                                $Transaction_Status_Title = 'NOT PAID';
                            } else {
                                $Transaction_Status_Title = 'PAID';
                            }
                        } else {
                            //check for active
                            $sqlSt = $Check_Status . "'active' OR status = 'approved'";
                            $select_Status = mysqli_query($conn,$sqlSt);
                            $no = mysqli_num_rows($select_Status);
                            if ($no > 0) {
                                $Transaction_Status_Title = 'NOT PAID';
                            }
                        }

                        if (!isset($_GET['Payment_Cache_ID'])) {
                            $Transaction_Status_Title = 'NO PATIENT SELECTED';
                        }


                        $_SESSION['Transaction_Status_Title'] = $Transaction_Status_Title;
                        echo '<b>STATUS : ' . $Transaction_Status_Title . '</b>';

                        if ($Transaction_Status_Title == 'NOT PAID') {
                            echo '<button class="art-button-green" type="button" style="float:left;display:none" onclick="Add_New_Items()">ADD ITEM</button><img id="loader" style="float:left;display:none" src="images/22.gif"/>';
                        }
                        ?>

                    </td>
                    <?php
                    if ($Transaction_Status_Title == 'NOT PAID') {
                            ?>
                            <td style='text-align: right;' width=30%><input type="button"  value="Payment Via GePG" class="art-button-green" onclick="open_gpg_dialog()">&nbsp;&nbsp;
                              <?php 
                                    if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='epayment'){
                              ?>
                            <input type="button" name="Pay_Via_Mobile" id="Pay_Via_Mobile" value="Create ePayment Bill" class="art-button-green" onclick="Pay_Via_Mobile_Function('<?php echo $_SESSION['Payment_Cache_ID']; ?>')">&nbsp;&nbsp;
                            <?php } else { ?>
                            <td style='text-align: right;' width=30%>
                            <?php }  if(isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='makepayment'){ ?>
                            <input type='button' name='Make_Payment' id='Make_Payment' value='Make Payment' onclick='Make_Payment_Laboratory()' class='art-button-green'>&nbsp;&nbsp;
                            <?php } 
                        }
//	echo "<a href='Patient_Billing_Laboratory_Page.php?Payment_Cache_ID=".$Payment_Cache_ID."&Transaction_Type=".$Transaction_Type."&Sub_Department_ID=".$Sub_Department_ID."&Registration_ID=".$Registration_ID."&Billing_Type=".$Temp_Billing_Type2."' class='art-button-green'>
//		    Make Payment
//		</a>";
//    }
                        ?>

                        <?php
                        if ($Patient_Payment_ID != '' && $Transaction_Status_Title == 'PAID') {
                            echo "<a href='individualpaymentreportindirect.php?Patient_Payment_ID=" . $Patient_Payment_ID . "&IndividualPaymentReport=IndividualPaymentReportThisPage' class='art-button-green' target='_blank'>
				Print Receipt
			    </a>";
                        }
                        ?>
                    </td>
                </tr> 
            </table>
        </center>
    </fieldset>

</form>
<?php
$Control = 'yes';
if (isset($_GET['Sub_Department_ID'])) {
    $Sub_Department_ID = $_GET['Sub_Department_ID'];
} else {
    $Sub_Department_ID = 0;
}

if (isset($_SESSION['Payment_Cache_ID'])) {
    $Payment_Cache_ID = $_SESSION['Payment_Cache_ID'];
} else {
    $Payment_Cache_ID = '';
}

if (isset($_SESSION['Transaction_Type'])) {
    $Transaction_Type = $_SESSION['Transaction_Type'];
} else {
    $Transaction_Type = '';
}

if (isset($_SESSION['Sub_Department_ID'])) {
    $Sub_Department_ID = $_SESSION['Sub_Department_ID'];
} else {
    $Sub_Department_ID = 0;
}
$total = 0;
$temp = 1;
$data = '';
$dataAmount = '';
?>

<fieldset style='overflow-y: scroll; height: 200px;' id='Items_Fieldset'>
    <table width="100%">
        <tr>
            <td style="text-align: center;" width="5%"><b>SN</b></td>
            <td><b>ITEM NAME</b></td>
            <td style="text-align:right;" width=8%><b>PRICE</b></td>
            <td style="text-align: center;" width=8%><b>QUANTITY</b></td>
            <td style="text-align: right  ;" width=8%><b>SUB TOTAL</b></td>
        </tr>
        <?php
        $select_Transaction_Items_Active = mysqli_query($conn,"select ilc.status, ilc.Price, ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, 
                                                    ilc.Payment_Cache_ID, ilc.Edited_Quantity
                                                    from tbl_item_list_cache ilc, tbl_items its
                                                    where ilc.item_id = its.item_id and
                                                    (ilc.status = 'active' OR ilc.status = 'approved') and
                                                    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
                                                    ilc.Transaction_Type = '$Transaction_Type' and
                                                    ilc.Check_In_Type = 'Others'") or die(mysqli_error($conn));

        $no = mysqli_num_rows($select_Transaction_Items_Active);

        if ($no > 0) {
            while ($row = mysqli_fetch_array($select_Transaction_Items_Active)) {
                if ($row['Edited_Quantity'] == 0) {
                    $Quantity = $row['Quantity'];
                } else {
                    $Quantity = $row['Edited_Quantity'];
                }
                if ($Quantity < 1) {
                    $Control = 'no';
                }
                if ($row['Price'] <= 0) {
                    $Control = 'no';
                }

                $status = $row['status'];
                echo "<tr><td width='5%'><input type='text' size=3 value = '" . $temp . "' style='text-align: center;' readonly='readonly'></td>";
                echo "<td><input type='text' value='" . $row['Product_Name'] . "' readonly='readonly'></td>";
                echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='" . number_format($row['Price']) . "' style='text-align:right;'></td>";
                //echo "<td><input type='hidden' name='item_list_id[]' value='".$row['Payment_Item_Cache_List_ID']."' /><input type='text' name='discount[]' style='text-align:right;' value='".number_format($row['Discount'])."'></td>";

                echo "<td style='text-align:right;'>";
                echo "<input type='text' readonly='readonly' value='$Quantity' onkeyup='pharmacyQuantityUpdate2(" . $row['Payment_Item_Cache_List_ID'] . ",this.value)' style='text-align: center;' size=13>";
                echo'</td>';
                echo "<td><input type='text' name='Sub_Total' id='Sub_Total' readonly='readonly' value='" . number_format(($row['Price'] - $row['Discount']) * $Quantity) . "' style='text-align:right;'></td>";
                if ($no > 1) {
                    ?>
                    <td style="text-align: center;" width="4%"><input type="button" value="X" type="button" onclick="removeitem('<?php echo $row['Product_Name']; ?>',<?php echo $row["Payment_Item_Cache_List_ID"]; ?>)"></td>
                    <?php
                }
                $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
                $temp++;
                echo "</tr>";
            }
        }
        ?>
        <tr>
            <td colspan="4" style="text-align: right;"><b>GRAND TOTAL</b></td>
            <td><input type="text" readonly="readonly" style="text-align: right;" value="<?php echo number_format($total); ?>"></td>
        </tr>
    </table>
</fieldset>
<table width="100%">
    <tr>
        <td style="text-align: right;" id="Removed_Button_Area" width="80%">
            <?php
            $select = mysqli_query($conn,"select ilc.status from tbl_item_list_cache ilc
                                        where ilc.status = 'removed' and
                                        ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
                                        ilc.Transaction_Type = '$Transaction_Type' and
                                        ilc.Check_In_Type = 'Others'") or die(mysqli_error($conn));
            $no = mysqli_num_rows($select);
            if ($no > 0) {
                ?>
                <input type="button" name="Removed" id="Removed" value="VIEW REMOVED ITEMS" class="art-button-green" onclick="Preview_Removed_Items(<?php echo $Payment_Cache_ID; ?>,<?php echo $Sub_Department_ID; ?>, '<?php echo $Transaction_Type; ?>')">
    <?php
}
?>
        </td>
   
        <td style="text-align: right;" id="Grand_Total"> <input type="text" hidden="hidden" id="total_txt" value="<?php echo $total; ?>"/><b>GRAND TOTAL : <?php echo number_format($total); ?></b>&nbsp;&nbsp;&nbsp;</td>
    </tr>
</table>
<!-- <fieldset>   
    <center>
        <table width=100%>
            <tr>
                <td>
                    <form id="saveDiscount"> -->
<!-- get Sub_Department_ID from the URL -->
<!-- <div id="patientItemsList" style='height:200px;overflow-y:scroll;overflow-x:hidden'>
    <center><b>List of Items </b></center> -->
<?php //include "Patient_Billing_Laboratory_Iframe.php";  ?>
<!-- </div> -->
</form>
<!--			<iframe src='Patient_Billing_Laboratory_Iframe.php?Transaction_Type=<?php echo $Transaction_Type; ?>&Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>&Sub_Department_ID=<?php echo $Sub_Department_ID; ?>' width='100%' height=270px></iframe>-->
<!-- </td>
</tr>
<tr id="totalAmount"> -->
<?php //echo $dataAmount;  ?>
<!-- </tr>
</table>
</center>
</fieldset> -->
<!--Dialog div-->
<div id="addTests" style="width:100%;overflow:hidden;display: none;" >

    <fieldset>
        <!--<legend align='right'><b><a href='#' class='art-button-green'>LOGOUT</a></b></legend>-->
        <center>
            <table width = "100%" style="border:0 " border="1">
                <tr>
                    <td width="40%" style="text-align: center"><input  type="text" name="search" id="search_medicene" placeholder="-----------------------------------------Search Item-------------------------------------------" oninput="searchMedicene(this.value)"></td><td width="50%" style="text-align: center"><button style="width:90%;font-size:20px; " name="submitadded" class="art-button-green" type="button" onclick="submitAddedItems()">Add Item(s)</button></td>
                </tr>   
                <tr>
                    <td width="40%" style="text-align: center"><b>Items</b></td><td width="50%" style="text-align: center"><b>Chosen Tests</b></td>
                </tr>
                <tr>
                    <td width="40%">
                        <!--Show tests for the section--> 
                        <div id="items_to_choose" style="height:400px;">
                            <table id="loadDataFromItems">
                            </table>
                        </div>
                    </td>
                    <td width="50%">
                        <!--Display selected tests for the section--> 
                        <div id="displaySelectedTests"  style="height:400px;width:100% ">
                            <form id="addedItemForm" action="" method="post"> 
                                <table width="100%" id="getSelectedTests">

                                </table>
                            </form>
                        </div>
                    </td>
                </tr>
            </table>
        </center>
    </fieldset><br/>

</div>

<div id="ePayment_Window" style="width:50%;">
    <span id='ePayment_Area'>
        <table width=100% style='border-style: none;'>
            <tr>
                <td>

                </td>
            </tr>
        </table>
    </span>
</div>

<div id="Preview_Removed" style="width:50%;">
    <span id='Removed_Items'>

    </span>
</div>

<div id="Verification_Error" style="width:50%;">
    <center>Some items missing price or quantity Please remove those items before payment process</center>
</div>

<div id="Add_New_Item">
    <span id='Add_New_Items_Area'>

    </span>
</div>

<script>
    $(document).ready(function () {
        $("#ePayment_Window").dialog({autoOpen: false, width: '55%', height: 250, title: 'Create ePayment Bill', modal: true});
    });
</script>

<script>
    $(document).ready(function () {
        $("#Verification_Error").dialog({autoOpen: false, width: '45%', height: 150, title: 'eHMS 2.0 Alert Message', modal: true});
    });
</script>

<script>
    $(document).ready(function () {
        $("#Preview_Removed").dialog({autoOpen: false, width: '80%', height: 350, title: 'REMOVED LABORATORY ITEMS', modal: true});
    });
</script>

<script>
    $(document).ready(function () {
        $("#Add_New_Item").dialog({autoOpen: false, width: '80%', height: 450, title: 'ADD LABORATORY ITEMS', modal: true});
    });
</script>

<div id="myDiaglog" style="display:none;">
    
    
</div>
<script type="text/javascript">
    
           
        function get_terminal_id(terminalid){
        if(terminalid.value!=''){
            $('#terminal_id').val(terminalid.value);
        } else {
            $('#terminal_id').val('');
        }
        
    }
    function get_terminals(trans_type){
        var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
        var Sub_Department_ID = '<?php echo $Sub_Department_ID; ?>';
        var Transaction_Type = '<?php echo $Transaction_Type; ?>';
        var Billing_Type = '<?php echo $Temp_Billing_Type2; ?>';
         $('#terminal_id').val('');
        var uri = '../epay/get_terminals.php';
        //alert(trans_type.value);
        if(trans_type.value=="Manual"){
            var result=confirm("Are you sure you want to make manual payment?");
            if(result){
                    document.location = 'Patient_Billing_Others_Page.php?Transaction_Type=' + Transaction_Type + '&Payment_Cache_ID=' + Payment_Cache_ID + '&Sub_Department_ID=' + Sub_Department_ID + '&Billing_Type=' + Billing_Type+'&manual_offline=manual';
                    
                //document.location = 'Inpatient_Departmental_Make_Payment.php?Registration_ID=' + Registration_ID + '&Folio_Number=' + Folio_Number + '&Claim_Form_Number=' + Claim_Form_Number + '&Consultant_ID=' + Consultant_ID+'&manual_offline=manual';
           }
        }else{
                $.ajax({
                type: 'GET',
                url: uri,
                data: {trans_type : trans_type.value},
                success: function(data){
                    $("#terminal_name").html(data);
                },
                error: function(){

                }
            });
        }
    }
    
    
      function offline_transaction(amount_required,reg_id){
               
        var uri = '../epay/patientbillingothersOfflinePayment.php';
        
        var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
        var Sub_Department_ID = '<?php echo $Sub_Department_ID; ?>';
        var Transaction_Type = '<?php echo $Transaction_Type; ?>';
        var Billing_Type = '<?php echo $Temp_Billing_Type2; ?>';
        
                                
        //alert(trans_type.value);
        var comf = confirm("Are you sure you want to make MANUAL / OFFLINE Payments?");
        if(comf){
            
            $.ajax({
                type: 'GET',
                url: uri,
                data: {amount_required:amount_required,registration_id:reg_id,Payment_Cache_ID:Payment_Cache_ID,Sub_Department_ID:Sub_Department_ID,Transaction_Type:Transaction_Type,Billing_Type:Billing_Type},
                beforeSend: function (xhr) {
                    $('#offlineProgressStatus').show();
                },
                success: function(data){
                    //alert("dtat");
                    $("#myDiaglog").dialog({
                        title: 'Manual / Offline Transaction Form',
                        width: '35%',
                        height: 380,
                        modal: true,
                    }).html(data);
                },
                complete: function(){
                    $('#offlineProgressStatus').hide();
                },
                error: function(){
                     $('#offlineProgressStatus').hide();
                }
            });
        } 
    }
</script>
<script type="text/javascript">
    function Make_Payment_Laboratory() {
        var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
        var Sub_Department_ID = '<?php echo $Sub_Department_ID; ?>';
        var Transaction_Type = '<?php echo $Transaction_Type; ?>';
        var Billing_Type = '<?php echo $Temp_Billing_Type2; ?>';
        var Registration_ID = <?php echo $Registration_ID; ?>;
        if (window.XMLHttpRequest) {
            myObjectVerify = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectVerify = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectVerify.overrideMimeType('text/xml');
        }

        myObjectVerify.onreadystatechange = function () {
            data200 = myObjectVerify.responseText;
            if (myObjectVerify.readyState == 4) {
                var feedback = data200;
                if (feedback == 'yes') {
                    //var r = confirm("Are you sure you want to perform transaction");
                    //if (r == true) {
                   var amount_required= document.getElementById("total_txt").value;
                    offline_transaction(amount_required,Registration_ID)
                       // document.location = 'Patient_Billing_Others_Page.php?Transaction_Type=' + Transaction_Type + '&Payment_Cache_ID=' + Payment_Cache_ID + '&Sub_Department_ID=' + Sub_Department_ID + '&Billing_Type=' + Billing_Type;
                    //}
                } else {
                    $("#Verification_Error").dialog("open");
                }
            }
        }; //specify name of function that will handle server response........

        myObjectVerify.open('GET', 'Patient_Billing_Others_Verify.php?Payment_Cache_ID=' + Payment_Cache_ID + '&Sub_Department_ID=' + Sub_Department_ID + '&Transaction_Type=' + Transaction_Type, true);
        myObjectVerify.send();
    }
</script>

<div id="gpg_dialog">

</div>

<script>
        function open_gpg_dialog(){
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
        var Sub_Department_ID = '<?php echo $Sub_Department_ID; ?>';
        var Transaction_Type = '<?php echo $Transaction_Type; ?>';
        var Patient_Name = '<?php echo $Patient_Name; ?>';
      $.ajax({
          type:'POST',
          url:'ajax_open_gpg_dialog.php',
          data:{Registration_ID:Registration_ID,Payment_Cache_ID:Payment_Cache_ID,Sub_Department_ID:Sub_Department_ID,Transaction_Type:Transaction_Type,Patient_Name:Patient_Name,Check_In_Type:'Others'},
          success:function(data){
              //console.log(data);
            $("#gpg_dialog").html(data);  
            $("#gpg_dialog").dialog('open');  
          }
      });  
    }
   $(document).ready(function(){
      $("#gpg_dialog").dialog({ autoOpen: false, width:'55%',height:250, title:'Make Payment Via GePG',modal: true});
   });
    function removeitem(Item_Name, Item_Location) {
        var check = confirm("Are you sure you want to remove " + Item_Name);
        var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
        var Sub_Department_ID = '<?php echo $Sub_Department_ID; ?>';
        var Transaction_Type = '<?php echo $Transaction_Type; ?>';
        var Billing_Type = '<?php echo $Temp_Billing_Type2; ?>';

        if (check == true) {
            if (window.XMLHttpRequest) {
                myObjectRemove = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectRemove = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectRemove.overrideMimeType('text/xml');
            }

            myObjectRemove.onreadystatechange = function () {
                data2009 = myObjectRemove.responseText;
                if (myObjectRemove.readyState == 4) {
                    document.getElementById("Items_Fieldset").innerHTML = data2009;
                    Refresh_Total(Payment_Cache_ID, Sub_Department_ID, Transaction_Type);
                }
            }; //specify name of function that will handle server response........

            myObjectRemove.open('GET', 'Patient_Billing_Others_Remove.php?Item_Location=' + Item_Location + '&Payment_Cache_ID=' + Payment_Cache_ID + '&Sub_Department_ID=' + Sub_Department_ID + '&Transaction_Type=' + Transaction_Type, true);
            myObjectRemove.send();
        }
    }
</script>

<script type="text/javascript">
    function Refresh_Total(Payment_Cache_ID, Sub_Department_ID, Transaction_Type) {
        if (window.XMLHttpRequest) {
            myObjectRefresh = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectRefresh = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRefresh.overrideMimeType('text/xml');
        }

        myObjectRefresh.onreadystatechange = function () {
            dataRefresh = myObjectRefresh.responseText;
            if (myObjectRefresh.readyState == 4) {
                document.getElementById("Grand_Total").innerHTML = dataRefresh;
                Refresh_Removed_Button(Payment_Cache_ID, Sub_Department_ID, Transaction_Type);
            }
        }; //specify name of function that will handle server response........

        myObjectRefresh.open('GET', 'Patient_Billing_Others_Refresh_Total.php?Payment_Cache_ID=' + Payment_Cache_ID + '&Sub_Department_ID=' + Sub_Department_ID + '&Transaction_Type=' + Transaction_Type, true);
        myObjectRefresh.send();
    }
</script>

<script type="text/javascript">
    function Refresh_Removed_Button(Payment_Cache_ID, Sub_Department_ID, Transaction_Type) {
        if (window.XMLHttpRequest) {
            myObjectRefresh = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectRefresh = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRefresh.overrideMimeType('text/xml');
        }

        myObjectRefresh.onreadystatechange = function () {
            dataRefreshRemoved = myObjectRefresh.responseText;
            if (myObjectRefresh.readyState == 4) {
                document.getElementById("Removed_Button_Area").innerHTML = dataRefreshRemoved;
            }
        }; //specify name of function that will handle server response........

        myObjectRefresh.open('GET', 'Patient_Billing_Laboratory_Refresh_Removed_Button.php?Payment_Cache_ID=' + Payment_Cache_ID + '&Sub_Department_ID=' + Sub_Department_ID + '&Transaction_Type=' + Transaction_Type, true);
        myObjectRefresh.send();
    }
</script>


<script type="text/javascript">
    function Preview_Removed_Items(Payment_Cache_ID, Sub_Department_ID, Transaction_Type) {
        if (window.XMLHttpRequest) {
            myObjectPreview = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPreview = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPreview.overrideMimeType('text/xml');
        }

        myObjectPreview.onreadystatechange = function () {
            dataPreviewRemoved = myObjectPreview.responseText;
            if (myObjectPreview.readyState == 4) {
                document.getElementById("Removed_Items").innerHTML = dataPreviewRemoved;
                $("#Preview_Removed").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectPreview.open('GET', 'Patient_Billing_Laboratory_Preview_Removed_Items.php?Payment_Cache_ID=' + Payment_Cache_ID + '&Sub_Department_ID=' + Sub_Department_ID + '&Transaction_Type=' + Transaction_Type, true);
        myObjectPreview.send();
    }
</script>

<script type="text/javascript">
    function Re_Add_Item(Product_Name, Payment_Item_Cache_List_ID) {
        var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
        var Sub_Department_ID = '<?php echo $Sub_Department_ID; ?>';
        var Transaction_Type = '<?php echo $Transaction_Type; ?>';

        var Confirm_Message = confirm("Are you sure you want re-add " + Product_Name + "?");
        if (Confirm_Message == true) {
            if (window.XMLHttpRequest) {
                myObjectReAdd = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectReAdd = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectReAdd.overrideMimeType('text/xml');
            }

            myObjectReAdd.onreadystatechange = function () {
                dataPreviewReadd = myObjectReAdd.responseText;
                if (myObjectReAdd.readyState == 4) {
                    document.getElementById("Items_Fieldset").innerHTML = dataPreviewReadd;
                    Refresh_Total(Payment_Cache_ID, Sub_Department_ID, Transaction_Type);
                    $("#Preview_Removed").dialog("close");
                }
            }; //specify name of function that will handle server response........

            myObjectReAdd.open('GET', 'Patient_Billing_Laboratory_Re_Add_Item.php?Payment_Cache_ID=' + Payment_Cache_ID + '&Sub_Department_ID=' + Sub_Department_ID + '&Transaction_Type=' + Transaction_Type + '&Payment_Item_Cache_List_ID=' + Payment_Item_Cache_List_ID, true);
            myObjectReAdd.send();
        }
    }
</script>


<script type="text/javascript">
    function Add_New_Items() {
        var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
        var Sub_Department_ID = '<?php echo $Sub_Department_ID; ?>';
        var Transaction_Type = '<?php echo $Transaction_Type; ?>';
        var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
        if (window.XMLHttpRequest) {
            myObjectAddItem = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectAddItem = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectAddItem.overrideMimeType('text/xml');
        }

        myObjectAddItem.onreadystatechange = function () {
            data_Add_New = myObjectAddItem.responseText;
            if (myObjectAddItem.readyState == 4) {
                document.getElementById("Add_New_Items_Area").innerHTML = data_Add_New;
                $("#Add_New_Item").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectAddItem.open('GET', 'Patient_Billing_Others_Add_New_Item.php?Payment_Cache_ID=' + Payment_Cache_ID + '&Sub_Department_ID=' + Sub_Department_ID + '&Transaction_Type=' + Transaction_Type + '&Guarantor_Name=' + Guarantor_Name, true);
        myObjectAddItem.send();
    }
</script>

<script>
    function Get_Item_Name(Item_Name, Item_ID) {
        document.getElementById('Quantity').value = '';
        document.getElementById('Comment').value = '';

        var Temp = '';
        if (window.XMLHttpRequest) {
            myObjectGetItemName = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectGetItemName = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetItemName.overrideMimeType('text/xml');
        }

        document.getElementById("Item_Name").value = Item_Name;
        document.getElementById("Item_ID").value = Item_ID;
        document.getElementById("Quantity").value = 1;
        //document.getElementById("Quantity").focus();
    }
</script>


<script>
    function Get_Item_Price(Item_ID, Guarantor_Name) {
        var Billing_Type = document.getElementById("Billing_Type").value;
        if (window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }
        //document.location = "./Get_Items_Price.php?Item_Name="+Item_Name;
        myObject.onreadystatechange = function () {
            data = myObject.responseText;

            if (myObject.readyState == 4) {
                document.getElementById('Price').value = data;
            }
        }; //specify name of function that will handle server response........

        myObject.open('GET', 'Patient_Billing_Get_Items_Price.php?Item_ID=' + Item_ID + '&Guarantor_Name=' + Guarantor_Name + '&Billing_Type=' + Billing_Type, true);
        myObject.send();
    }
</script>

<script>
    function getItemsListFiltered(Item_Name) {
        document.getElementById("Item_Name").value = '';
        document.getElementById("Item_ID").value = '';
        document.getElementById("Comment").value = '';
        document.getElementById("Quantity").value = '';
        var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
        var Type_Of_Check_In = 'Others';
        var Item_Category_ID = document.getElementById("Item_Category_ID").value;
        if (Item_Category_ID == '' || Item_Category_ID == null) {
            Item_Category_ID = 'All';
        }

        if (window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }

        myObject.onreadystatechange = function () {
            data135 = myObject.responseText;
            if (myObject.readyState == 4) {
                //document.getElementById('Approval').readonly = 'readonly';
                document.getElementById('New_Items_Fieldset').innerHTML = data135;
            }
        }; //specify name of function that will handle server response........
        myObject.open('GET', 'Patient_Billing_Get_List_Of_Othersl_Filtered_Items.php?Item_Category_ID=' + Item_Category_ID + '&Item_Name=' + Item_Name + '&Guarantor_Name=' + Guarantor_Name + '&Type_Of_Check_In=' + Type_Of_Check_In, true);
        myObject.send();
    }
</script>


<script>
    function getItemsList(Item_Category_ID) {
        document.getElementById("Search_Value").value = '';
        document.getElementById("Item_Name").value = '';
        document.getElementById("Item_ID").value = '';
        var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
        var Type_Of_Check_In = 'Others';

        if (window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }

        myObject.onreadystatechange = function () {
            data265 = myObject.responseText;
            if (myObject.readyState == 4) {
                document.getElementById('New_Items_Fieldset').innerHTML = data265;
            }
        }; //specify name of function that will handle server response........
        myObject.open('GET', 'Patient_Billing_Get_List_Of_Departmental_Items_List.php?Item_Category_ID=' + Item_Category_ID + '&Guarantor_Name=' + Guarantor_Name + '&Type_Of_Check_In=' + Type_Of_Check_In, true);
        myObject.send();
    }
</script>


<script>
    function Get_Selected_Item() {
        var Billing_Type = document.getElementById("Billing_Type").value;
        var Item_ID = document.getElementById("Item_ID").value;
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        var Quantity = document.getElementById("Quantity").value;
        var Registration_ID = <?php echo $Registration_ID; ?>;
        var Comment = document.getElementById("Comment").value;
        var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
        var Claim_Form_Number = document.getElementById("Claim_Form_Number").value;
        var Type_Of_Check_In = 'Others';
        var Price = document.getElementById("Price").value;
        var Sub_Department_ID = 'NULL';
        var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
        var Transaction_Type = '<?php echo $Transaction_Type; ?>';

        if (Registration_ID != '' && Registration_ID != null && Item_Name != '' && Item_Name != null && Item_ID != '' && Item_ID != null && Quantity != '' && Quantity != null && Billing_Type != '' && Billing_Type != null && Type_Of_Check_In != null && Type_Of_Check_In != '') {

           
            if (parseInt(Price) == 0) {
                alert('Process fail!. Item missing price.');
                return false;
            }

            if (window.XMLHttpRequest) {
                myObject2 = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject2 = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject2.overrideMimeType('text/xml');
            }
            myObject2.onreadystatechange = function () {
                dataadd = myObject2.responseText;

                if (myObject2.readyState == 4) {
                    document.getElementById('Items_Fieldset').innerHTML = dataadd;
                    document.getElementById("Item_Name").value = '';
                    document.getElementById("Item_ID").value = '';
                    document.getElementById("Quantity").value = '';
                    document.getElementById("Price").value = '';
                    document.getElementById("Comment").value = '';
                    document.getElementById("Search_Value").focus();
                    alert("Item Added Successfully");
                    Refresh_Total(Payment_Cache_ID, Sub_Department_ID, Transaction_Type);
                    $("#Add_New_Item").dialog("close");
                }
            }; //specify name of function that will handle server response........

            //myObject.open('GET','Perform_Reception_Transaction.php?Registration_ID='+Registration_ID+'&Item_ID='+Item_ID+'&Type_Of_Check_In='+Type_Of_Check_In+'&direction='+direction+'&Quantity='+Quantity'&Consultant='+Consultant,true);
            myObject2.open('GET', 'Patient_Billing_Departmental_Other_Add_Selected_Item.php?Registration_ID=' + Registration_ID + '&Item_ID=' + Item_ID + '&Quantity=' + Quantity + '&Billing_Type=' + Billing_Type + '&Guarantor_Name=' + Guarantor_Name + '&Sponsor_ID=' + Sponsor_ID + '&Claim_Form_Number=' + Claim_Form_Number + '&Comment=' + Comment + '&Sub_Department_ID=' + Sub_Department_ID + '&Payment_Cache_ID=' + Payment_Cache_ID, true);
            myObject2.send();

        } else if (Registration_ID != '' && Registration_ID != null && (Item_Name == '' || Item_Name == null || Item_ID == '' || Item_ID == null) != '' && Quantity != '' && Quantity != null) {
            alertMessage();
        } else {
            if (Quantity == '' || Quantity == null) {
                document.getElementById("Quantity").focus();
                document.getElementById("Quantity").style = 'border: 3px solid red';
            }
        }
    }
</script>

<script type="text/javascript">
    function alertMessage() {
        alert("Select Item First");
        document.getElementById("Quantity").value = '';
    }
</script>

<script>

    function openItemDialog() {
        //Load data to the div
        // $('#loadDataFromItems').html('');
        $("#loader").show();
        $('#getSelectedTests').html('<tr><td style="" ><b>Description</b></td><td style=""><b>Price</b></td></tr>');
        $.ajax({
            type: 'GET',
            url: "search_item_for_test.php",
            data: "loadData=true&section=<?php echo $_GET['section'] ?>&Payment_Cache_ID=<?php echo $Payment_Cache_ID; ?>",
            success: function (data) {
                // alert(data['data']);
                $('#loadDataFromItems').html(data);
            }, error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
        $("#addTests").dialog("open");
    }
</script>

<script>
    function vieweRemovedItem() {
        // alert(item);

        $.ajax({
            type: 'POST',
            url: "change_items_info.php",
            data: "viewRemovedItem=true",
            dataType: "json",
            success: function (data) {
                $('#patientItemsList').html(data['data']);
                $('#totalAmount').html(data['total_amount']);
            }, error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    }

    function addItem(item) {
        $.ajax({
            type: 'POST',
            url: "change_items_info.php",
            data: "readdItem=" + item,
            dataType: "json",
            success: function (data) {
                $('#patientItemsList').html(data['data']);
                $('#totalAmount').html(data['total_amount']);
            }, error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    }

    function showItem() {
        $.ajax({
            type: 'POST',
            url: "change_items_info.php",
            data: "show_all_items=true",
            dataType: "json",
            success: function (data) {
                $('#patientItemsList').html(data['data']);
                $('#totalAmount').html(data['total_amount']);
            }, error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    }

    function submitAddedItems() {

        var datastring = $("form#addedItemForm").serialize();
        if (datastring !== '') {
            //alert('Alert')
            $.ajax({
                type: 'POST',
                url: "search_item_for_test.php",
                data: "addMoreItems=true&" + datastring + '&section=<?php echo $_GET['section'] ?>',
                success: function (data) {
                    alert(data);
                    if (data == 'saved') {
                        showItem();
                        $("#addTests").dialog("close");
                    }//alert(data);
//              $('#patientItemsList').html(data);          
                }, error: function (jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        } else {
            alert("No data set");
        }
        $("#loader").hide();
    }
    function searchMedicene(search) {
        if (search !== '') {
            $.ajax({
                type: 'GET',
                url: "search_item_for_test.php",
                data: "section=<?php echo $_GET['section'] ?>&search_word=" + search,
                success: function (data) {

                    if (data != '') {
                        // alert('done');
                        $('#items_to_choose').html(data);
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        }
    }

    function number_format(number, decimals, dec_point, thousands_sep) {
        //  discuss at: http://phpjs.org/functions/number_format/
        // original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
        // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
        // improved by: davook
        // improved by: Brett Zamir (http://brett-zamir.me)
        // improved by: Brett Zamir (http://brett-zamir.me)
        // improved by: Theriault
        // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
        // bugfixed by: Michael White (http://getsprink.com)
        // bugfixed by: Benjamin Lupton
        // bugfixed by: Allan Jensen (http://www.winternet.no)
        // bugfixed by: Howard Yeend
        // bugfixed by: Diogo Resende
        // bugfixed by: Rival
        // bugfixed by: Brett Zamir (http://brett-zamir.me)
        //  revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
        //  revised by: Luke Smith (http://lucassmith.name)
        //    input by: Kheang Hok Chin (http://www.distantia.ca/)
        //    input by: Jay Klehr
        //    input by: Amir Habibi (http://www.residence-mixte.com/)
        //    input by: Amirouche
        //   example 1: number_format(1234.56);
        //   returns 1: '1,235'
        //   example 2: number_format(1234.56, 2, ',', ' ');
        //   returns 2: '1 234,56'
        //   example 3: number_format(1234.5678, 2, '.', '');
        //   returns 3: '1234.57'
        //   example 4: number_format(67, 2, ',', '.');
        //   returns 4: '67,00'
        //   example 5: number_format(1000);
        //   returns 5: '1,000'
        //   example 6: number_format(67.311, 2);
        //   returns 6: '67.31'
        //   example 7: number_format(1000.55, 1);
        //   returns 7: '1,000.6'
        //   example 8: number_format(67000, 5, ',', '.');
        //   returns 8: '67.000,00000'
        //   example 9: number_format(0.9, 0);
        //   returns 9: '1'
        //  example 10: number_format('1.20', 2);
        //  returns 10: '1.20'
        //  example 11: number_format('1.20', 4);
        //  returns 11: '1.2000'
        //  example 12: number_format('1.2000', 3);
        //  returns 12: '1.200'
        //  example 13: number_format('1 000,50', 2, '.', ' ');
        //  returns 13: '100 050.00'
        //  example 14: number_format(1e-8, 8, '.', '');
        //  returns 14: '0.00000001'

        number = (number + '')
                .replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function (n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + (Math.round(n * k) / k)
                            .toFixed(prec);
                };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
                .split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '')
                .length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1)
                    .join('0');
        }
        return s.join(dec);
    }

</script>



<script>
    function Pay_Via_Mobile_Function(Payment_Cache_ID) {
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Employee_ID = '<?php $Employee_ID; ?>';
        var Sub_Department_ID = '<?php echo $Sub_Department_ID; ?>';

        if (window.XMLHttpRequest) {
            myObjectGetDetails = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectGetDetails = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetDetails.overrideMimeType('text/xml');
        }
//alert(Sub_Department_ID)
        myObjectGetDetails.onreadystatechange = function () {
            data29 = myObjectGetDetails.responseText;
            if (myObjectGetDetails.readyState == 4) {
                document.getElementById('ePayment_Area').innerHTML = data29;
                $("#ePayment_Window").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectGetDetails.open('GET', 'ePayment_Patient_Details_Departmental.php?Section=Others&Employee_ID=' + Employee_ID + '&Registration_ID=' + Registration_ID + '&Payment_Cache_ID=' + Payment_Cache_ID + '&Sub_Department_ID=' + Sub_Department_ID, true);
        myObjectGetDetails.send();
    }
</script>

<script type="text/javascript">
    function Confirm_Create_ePayment_Bill() {
        var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
        var Sub_Department_ID = '<?php echo $Sub_Department_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
//alert(Sub_Department_ID)
        if (window.XMLHttpRequest) {
            myObjectConfirm = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectConfirm = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectConfirm.overrideMimeType('text/xml');
        }

        myObjectConfirm.onreadystatechange = function () {
            data2933 = myObjectConfirm.responseText;
            if (myObjectConfirm.readyState == 4) {
                var feedback = data2933;
                if (feedback == 'yes') {
                    Create_ePayment_Bill();
                } else if (feedback == 'not') {
                    alert("No Item Found!");
                } else {
                   alert(feedback)
                    alert("You are not allowed to create transaction with zero price or zero quantity. Please remove those items to proceed");
                }
            }
        }; //specify name of function that will handle server response........
        myObjectConfirm.open('GET', 'Confirm_ePayment_Patient_Details_Departmental.php?Section=Others&Payment_Cache_ID=' + Payment_Cache_ID + '&Sub_Department_ID=' + Sub_Department_ID+'&Registration_ID='+Registration_ID, true);
        myObjectConfirm.send();
    }
</script>

<script type="text/javascript">
    function Create_ePayment_Bill() {
        var Payment_Cache_ID = '<?php echo $Payment_Cache_ID; ?>';
        var Sub_Department_ID = '<?php echo $Sub_Department_ID; ?>';
        var Payment_Mode = document.getElementById("Payment_Mode").value;
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Amount = document.getElementById("Amount_Required").value;
        var Billing_Type = document.getElementById("Billing_Type").value;
        if (Amount <= 0 || Amount == null || Amount == '' || Amount == '0') {
            alert("Process Fail! You can not prepare a bill with zero amount");
        } else {
            if (Payment_Mode != null && Payment_Mode != '') {
                if (Payment_Mode == 'Bank_Payment') {
                    var Confirm_Message = confirm("Are you sure you want to create Bank Payment BILL?");
                    if (Confirm_Message == true) {
                        document.location = 'Departmental_Bank_Payment_Transaction.php?Section=Others&Registration_ID=' + Registration_ID + '&Payment_Cache_ID=' + Payment_Cache_ID + '&Sub_Department_ID=' + Sub_Department_ID + '&Billing_Type=' + Billing_Type;
                    }
                } else if (Payment_Mode == 'Mobile_Payemnt') {
                    var Confirm_Message = confirm("Are you sure you want to create Mobile eBILL?");
                    if (Confirm_Message == true) {
                        document.location = "#";
                    }
                }
            } else {
                document.getElementById("Payment_Mode").focus();
                document.getElementById("Payment_Mode").style = 'border: 3px solid red';
            }
        }
    }
</script>

<script type="text/javascript">
    function Print_Payment_Code(Payment_Code) {
        var winClose = popupwindow('paymentcodepreview.php?Payment_Code=' + Payment_Code + '&PaymentCodePreview=PaymentCodePreviewThisPage', 'PAYMENT CODE', 530, 400);
    }

    function popupwindow(url, title, w, h) {
        var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);
        return mypopupWindow;
    }
</script>

<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="style.css" media="screen">
<link rel="stylesheet" href="style.responsive.css" media="all">
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script> <!--<script src="js/jquery-ui-1.10.1.custom.min.js"></script>-->
<script src="script.js"></script>
<script src="script.responsive.js"></script>


<style>.art-content .art-postcontent-0 .layout-item-0 { margin-bottom: 10px;  }
    .art-content .art-postcontent-0 .layout-item-1 { padding-right: 10px;padding-left: 10px;  }
    .ie7 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
    .ie6 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
    #displaySelectedTests,#items_to_choose{
        overflow-y:scroll;
        overflow-x:hidden; 
    }
</style>

<script type='text/javascript'>
    function LaboratoryQuantityUpdate(Payment_Item_Cache_List_ID, Quantity) {
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }
        mm.onreadystatechange = AJAXP; //specify name of function that will handle server response....
        mm.open('GET', 'LaboratoryQuantityUpdate.php?Payment_Item_Cache_List_ID=' + Payment_Item_Cache_List_ID + '&Quantity=' + Quantity, true);
        mm.send();
    }
    function AJAXP() {
        var data = mm.responseText;
        if (mm.readyState == 4) {
        }
    }

    $(document).ready(function () {
        $("#addTests").dialog({autoOpen: false, width: 900, height: 560, title: 'Choose an Item', modal: true});
//       $(".ui-widget-header").css("background-color","blue");  

        $(".chosenTests").live("click", function () {
//alert("chosen");
            var id = $(this).attr("id");
            if ($(this).is(':checked')) {


                $.ajax({
                    type: 'GET',
                    url: "search_item_for_test.php",
                    data: "section=<?php echo $_GET['section'] ?>&adthisItem=" + id,
                    success: function (data) {
                        if (data !== '') {
                            $('#getSelectedTests').append(data);
                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        alert(errorThrown);
                    }
                });
            } else {
                $("#itm_id_" + id).remove();
            }
        });
        $(".ui-icon-closethick").click(function () {
//         $(this).hide();
            $("#loader").hide();
        });
    });
</script>
<?php
include("./includes/footer.php");
?>