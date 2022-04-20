<?php
include("./includes/header.php");
include("./includes/connection.php");
$temp = 1;
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
                header("Location: ./supervisorauthentication.php?InvalidSupervisorAuthentication=yes");
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
if (isset($_SESSION['userinfo'])) {
    ?>
    <a href='searchlistofoutpatientbilling.php?SearchListOfOutpatientBilling=SearchListOfOutpatientBillingThisPage' class='art-button-green'>
        BACK
    </a>
<?php } ?>


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
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
    $select_Patient = mysqli_query($conn,"select
                            Old_Registration_Number,Title,Patient_Name,pr.Sponsor_ID,
                                Date_Of_Birth,
                                    Gender,pr.Region,pr.District,pr.Ward,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,
                                                        Registration_ID
                                      
                                      
                                      
                                      
                                      from tbl_patient_registration pr, tbl_sponsor sp 
                                        where pr.Sponsor_ID = sp.Sponsor_ID and 
                                              Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Patient);

    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Patient)) {
            $Registration_ID = $row['Registration_ID'];
            $Old_Registration_Number = $row['Old_Registration_Number'];
            $Title = $row['Title'];
            $Patient_Name = $row['Patient_Name'];
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
}
?>






<!--filtering services against categories-->
<script type="text/javascript" language="javascript">
    function getItemList(Item_Category_Name) {
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }
        //getPrice();
        var ItemListType = document.getElementById('Type').value;
        getItemListType(ItemListType);
        document.getElementById('Price').value = '';
        mm.onreadystatechange = AJAXP; //specify name of function that will handle server response....
        mm.open('GET', 'GetItemList.php?Item_Category_Name=' + Item_Category_Name, true);
        mm.send();
    }
    function AJAXP() {
        var data1 = mm.responseText;
        document.getElementById('Item_Name').innerHTML = data1;
    }
</script>

<script type='text/javascript'>
    function getItemListType() {
        var Item_Category_Name = document.getElementById("Item_Category").value;


        if (window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }

        alert(Item_Category_Name);
        //document.location = 'Approval_Bill.php?Registration_ID='+Registration_ID+'&Insurance='+Insurance+'&Folio_Number='+Folio_Number;

        myObject.onreadystatechange = function () {
            data = myObject.responseText;
            if (myObject.readyState == 4) {
                document.getElementById('Approval').disabled = 'disabled';
                document.getElementById('Approval_Comment').innerHTML = data;
            }
        }; //specify name of function that will handle server response........
        myObject.open('GET', 'Approval_Bill.php?Item_Category_Name=' + Item_Category_Name, true);
        myObject.send();
    }
</script>
<!-- end of filtering-->




<!-- clinic and doctor selection-->
<script type="text/javascript" language="javascript">
    function getDoctor() {
        var Type_Of_Check_In = document.getElementById('Type_Of_Check_In').value;
        $("#Consultant").select2().select2("val", '');
        $("#Consultant").css('width', '100%');
        
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }

        if (document.getElementById('direction').value == 'Direct To Doctor Via Nurse Station' || document.getElementById('direction').value == 'Direct To Doctor') {
            mm.onreadystatechange = AJAXP3; //specify name of function that will handle server response....
            mm.open('GET', 'Get_Guarantor_Name.php?Type_Of_Check_In=' + Type_Of_Check_In + '&direction=doctor', true);
            mm.send();
        }
        else {
            mm.onreadystatechange = AJAXP3; //specify name of function that will handle server response....
            mm.open('GET', 'Get_Guarantor_Name.php?direction=clinic', true);
            mm.send();
        }
    }
    function AJAXP3() {
        var data3 = mm.responseText;
        document.getElementById('Consultant').innerHTML = data3;
    }
</script>
<!-- end of selection-->



<!-- get employee id-->
<?php
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}
?>


<?php
if (isset($_POST['submittedPatientBillingForm'])) {
    //---get supervisor id 
    if (isset($_SESSION['supervisor'])) {
        if (isset($_SESSION['supervisor']['Session_Master_Priveleges'])) {
            if ($_SESSION['supervisor']['Session_Master_Priveleges'] = 'yes') {
                $Supervisor_ID = $_SESSION['supervisor']['Employee_ID'];
            } else {
                $Supervisor_ID = '';
            }
        } else {
            $Supervisor_ID = '';
        }
    } else {
        $Supervisor_ID = '';
    }
    //end of fetching supervisor id
}
?>
<!--end of process-->



<form action='' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    <!--<br/>-->
    <fieldset>  
        <legend align=right><b>ADHOC REVENUE CENTER</b></legend>
        <center> 
            <table width=100%> 
                <tr> 
                    <td>
                        <table width=100%>
                            <tr>
                                <td style='text-align: right; width: 10%;'>Billing Type</td>


                                <td style='width: 15%;' id='Billing_Type_Area'>

                                    <?php
                                    $select_billing_type = mysqli_query($conn,"select Billing_Type from tbl_adhoc_items_list_cache where
										Employee_ID = '$Employee_ID' and
										    Registration_ID = '$Registration_ID' order by Adhoc_Item_ID desc limit 1") or die(mysqli_error($conn));
                                    $num_of_rows = mysqli_num_rows($select_billing_type);
                                    if ($num_of_rows > 0) {
                                        while ($row = mysqli_fetch_array($select_billing_type)) {
                                            $Selected_Billing_Type = $row['Billing_Type'];
                                        }
                                        ?>
                                        <select style='width: 100%;' name='Billing_Type' id='Billing_Type' required='required' onchange='clearFocus(this);
                                                    Get_Item_Price2()' onclick='clearFocus(this)'>
                                            <option selected='selected'><?php echo $Selected_Billing_Type; ?></option>
                                        </select>			

                                        <?php
                                    } else {

                                        if (strtolower($Guarantor_Name) == 'cash') {
                                            ?>
                                            <select style='width: 100%;' name='Billing_Type' id='Billing_Type' required='required' onchange='clearFocus(this);
                                                            Get_Item_Price2()' onclick='clearFocus(this)'>
                                                <option>Outpatient Cash</option>
                                            </select>				    
                                            <?php
                                        } else {
                                            ?>
                                            <select style='width: 100%;' name='Billing_Type' id='Billing_Type' required='required' onchange='clearFocus(this);
                                                            Get_Item_Price2()' onclick='clearFocus(this)'>
                                                <option selected='selected'>Outpatient Credit</option>
                                                <option >Outpatient Cash</option>
                                                <!--<option selected='selected'>Outpatient Credit</option>-->
                                            </select>				    				    
                                            <?php
                                        }
                                    }
                                    ?>
                                </td>
                                <td style='text-align: right; width: 10%'>Patient Name</td>
                                <td width='15%'><input type='text' name='Patient_Name' disabled='disabled' id='Patient_Name' value='<?php echo ucwords(strtolower($Patient_Name)); ?>'></td>
                                <td style='text-align: right; width: 7%;'>Registration Number</td>
                                <td><input type='text' name='Registration_Number' id='Registration_Number' disabled='disabled' value='<?php echo $Registration_ID; ?>'></td>
                                <td style='text-align: right;'>Receipt Number</td>
                                <td><input type='text' name='Receipt_Number' id='Receipt_Number' readonly='readonly'></td>
                            </tr>
                            <tr>
                                <td style='text-align: right;'>Type Of Check In</td>
                                <td>  
                                    <select style='width: 100%;' name='Type_Of_Check_In' id='Type_Of_Check_In' required='required' onchange='type_Of_Check_In(this.value)' onclick='clearFocus(this);
                                            getDoctor()'>

                                        <?php /* if(!isset($_GET['NR'])){ ?>
                                          <option selected='selected'><?php echo $Check_In_Type; ?></option>
                                          <?php } else{ ?>
                                          <option selected='selected'><?php $Check_In_Type; ?></option>
                                          <?php } */ ?>

                                        <!--<option>Radiology</option>-->
                                        <!-- <option>Dialysis</option>
                                        <option>Physiotherapy</option>
                                        <option>Optical</option> -->
                                        <option>Doctor Room</option>
                                        <!-- <option>Dressing</option> -->
                                        <!-- <option>Matenity</option>
                                        <option>Cecap</option> -->
                                        <!--<option>Laboratory</option>-->
                                        <!--<option>Pharmacy</option>-->
                                        <!--<option>Theater</option>-->
                                        <!-- <option>Dental</option>
                                        <option>Ear</option> -->
                                    </select>
                                </td>

                                <td style='text-align: right;'>Occupation</td>
                                <td>
                                    <input type='text' name='Receipt_Date' disabled='disabled' id='date2' value='<?php echo $Occupation; ?>'>
                                </td>
                                <td style='text-align: right; width: 11%'>Gender</td>
                                <td width='12%'><input type='text' name='Gender' readonly='readonly' id='Gender' value='<?php echo $Gender; ?>'></td>
                                <td style='text-align: right;'>Receipt Date & Time</td>
                                <td width='12%'><input type='text' name='Receipt_Date_Time' readonly='readonly' id='Receipt_Date_Time' value='<?php echo ''; ?>'></td>
                            </tr>
                            <tr>

                                <td style='text-align: right;'>Patient Direction</td>
                                <td>
                                    <select style='width: 100%;' id='direction' name='direction' onclick='clearFocus(this);
                                            getDoctor()' onchange='clearFocus(this);
                                                    getDoctor()' required='required'>
                                                <?php if (!isset($_GET['NR']) && !isset($_GET['CP'])) { ?>
                                            <option selected='selected'><?php echo $Patient_Direction; ?></option>
                                        <?php } else { ?>
                                            <option selected='selected'></option>
                                        <?php } ?>
                                        <option>Direct To Doctor</option>
                                        <option>Direct To Doctor Via Nurse Station</option>
                                        <option>Direct To Clinic</option>
                                        <option>Direct To Clinic Via Nurse Station</option>
                                    </select>
                                </td>


                                <td style='text-align: right;'>Patient Age</td>
                                <td><input type='text' name='Patient_Age' id='Patient_Age'  disabled='disabled' value='<?php echo $age; ?>'></td>
                                <td style='text-align: right;'>Registered Date</td>
                                <td><input type='text' name='Registered_Date' id='Registered_Date' disabled='disabled' value='<?php echo $Registration_Date_And_Time; ?>'></td>

                                <?php
//find the last folio number based on selected patient
                                $get_folio = mysqli_query($conn,"select Folio_Number, Claim_Form_Number from tbl_patient_payments where
								Registration_ID = '$Registration_ID'
								    order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));

                                $fNumber = mysqli_num_rows($get_folio);

                                if ($fNumber > 0) {
                                    while ($Folio = mysqli_fetch_array($get_folio)) {
                                        $Folio_Number = $Folio['Folio_Number'];
                                        $Claim_Form_Number = $Folio['Claim_Form_Number'];
                                    }
                                } else {
                                    $Folio_Number = '';
                                    $Claim_Form_Number = '';
                                }
                                ?>
                                <td style='text-align: right;'>Folio Number</td>
                                <td><input type='text' name='Folio_Number' id='Folio_Number' readonly='readonly' value='<?php echo $Folio_Number; ?>'></td>
                            </tr>
                            <tr>
                                <td style='text-align: right;'>Consultant</td>
                                <td>
                                    <select name='Consultant' id='Consultant' value='<?php echo $Guarantor_Name; ?>' required='required' onchange='clearFocus(this)' onclick='clearFocus(this)'>

                                        <?php if (!isset($_GET['NR']) && !isset($_GET['CP'])) { ?>
                                            <option selected='selected'><?php echo $Consultant; ?></option>
                                        <?php } else { ?>
                                            <option selected='selected'></option>
                                        <?php } ?>

                                        <?php
                                        $Select_Consultant = "select * from tbl_clinic where Clinic_Status = 'Available'";
                                        $result = mysqli_query($conn,$Select_Consultant);
                                        ?> 
                                        <?php
                                        while ($row = mysqli_fetch_array($result)) {
                                            ?>
                                            <option><?php echo $row['Clinic_Name']; ?></option>
                                            <?php
                                        }

                                        $Select_Doctors = "select * from tbl_employee where employee_type = 'Doctor' and Account_Status = 'active'";
                                        $result = mysqli_query($conn,$Select_Doctors);
                                        ?> 
                                        <?php
                                        while ($row = mysqli_fetch_array($result)) {
                                            ?>
                                            <option><?php echo $row['Employee_Name']; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <input type="button" name="Doctors_Queue" id="Doctors_Queue" value="Dq" onclick="open_Dialog()"/>
                                </td>

                                <td style='text-align: right;'>Sponsor Name</td>
                                <td><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>
                                <td style='text-align: right;'>Claim Form Number</td>
                                <?php
                                //check claim form number status
                                $select_claim_status = mysqli_query($conn,"select Claim_Number_Status from tbl_sponsor
									where sponsor_id = '$Sponsor_ID'") or die(mysqli_error($conn));
                                $num_rows = mysqli_num_rows($select_claim_status);
                                if ($num_rows > 0) {
                                    while ($row = mysqli_fetch_array($select_claim_status)) {
                                        $Claim_Number_Status = $row['Claim_Number_Status'];
                                    }
                                } else {
                                    $Claim_Number_Status = '';
                                }

                                if (strtolower($Claim_Number_Status) == 'mandatory') {
                                    //check if there is any record in cache then capture claim form number
                                    $select_Claim_Number_Status = mysqli_query($conn,"select Claim_Form_Number from tbl_adhoc_items_list_cache where
										Employee_ID = '$Employee_ID' and
										    Registration_ID = '$Registration_ID' order by Adhoc_Item_ID desc limit 1") or die(mysqli_error($conn));
                                    $num_of_rows = mysqli_num_rows($select_Claim_Number_Status);
                                    if ($num_of_rows > 0) {
                                        while ($row = mysqli_fetch_array($select_Claim_Number_Status)) {
                                            $Claim_Form_Number = $row['Claim_Form_Number'];
                                        }
                                        echo "<td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number' readonly='readonly' value='" . $Claim_Form_Number . "'></td>";
                                    } else {
                                        echo "<td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number' required='required' value='" . $Claim_Form_Number . "' onclick='clearFocus(this)' onchange='clearFocus(this)'></td>";
                                    }
                                } elseif (strtolower($Claim_Number_Status) == 'not mandatory') {
                                    //check if there is any record in cache then capture claim form number
                                    $select_Claim_Number_Status = mysqli_query($conn,"select Claim_Form_Number from tbl_reception_items_list_cache where
										Employee_ID = '$Employee_ID' and
										    Registration_ID = '$Registration_ID' order by Reception_List_Item_ID desc limit 1") or die(mysqli_error($conn));
                                    $num_of_rows = mysqli_num_rows($select_Claim_Number_Status);
                                    if ($num_of_rows > 0) {
                                        while ($row = mysqli_fetch_array($select_Claim_Number_Status)) {
                                            $Claim_Form_Number = $row['Claim_Form_Number'];
                                        }
                                        echo "<td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number' readonly='readonly' value='" . $Claim_Form_Number . "'></td>";
                                    } else {
                                        echo "<td><input type='text' value='" . $Claim_Form_Number . "' name='Claim_Form_Number' id='Claim_Form_Number'></td>";
                                    }
                                }
                                ?>

                                <td style='text-align: right;'>Supervised By</td>

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
                    <td width=25%>
                        <script type='text/javascript'>
                            function getItemsList(Item_Category_ID) {
                                document.getElementById("Search_Value").value = '';
                                document.getElementById("Price").value = '';
                                document.getElementById("Item_Name").value = '';
                                document.getElementById("Quantity").value = '';
                                var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
                                if (window.XMLHttpRequest) {
                                    myObject = new XMLHttpRequest();
                                } else if (window.ActiveXObject) {
                                    myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                                    myObject.overrideMimeType('text/xml');
                                }
                                //alert(data);

                                myObject.onreadystatechange = function () {
                                    data = myObject.responseText;
                                    if (myObject.readyState == 4) {
                                        //document.getElementById('Approval').disabled = 'disabled';
                                        document.getElementById('Items_Fieldset').innerHTML = data;
                                    }
                                }; //specify name of function that will handle server response........
                                myObject.open('GET', 'Get_List_Of_Items_With_Consultation.php?Item_Category_ID=' + Item_Category_ID + '&Guarantor_Name=' + Guarantor_Name, true);
                                myObject.send();
                            }

                            function getItemsListFiltered(Item_Name) {
                                document.getElementById("Price").value = '';
                                document.getElementById("Item_Name").value = '';
                                document.getElementById("Quantity").value = '';

                                var Guarantor_Name = '<?php echo $Guarantor_Name ?>';
                                //alert(Guarantor_Name);

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
                                //alert(data);

                                myObject.onreadystatechange = function () {
                                    data = myObject.responseText;
                                    if (myObject.readyState == 4) {
                                        //document.getElementById('Approval').disabled = 'disabled';
                                        document.getElementById('Items_Fieldset').innerHTML = data;
                                    }
                                }; //specify name of function that will handle server response........
                                myObject.open('GET', 'Get_List_Of_Items_Filtered_With_Consultation.php?Item_Category_ID=' + Item_Category_ID + '&Item_Name=' + Item_Name + '&Guarantor_Name=' + Guarantor_Name, true);
                                myObject.send();
                            }

                            function Get_Item_Name(Item_Name, Item_ID) {
                                document.getElementById("Item_Name").value = Item_Name;
                                document.getElementById("Item_ID").value = Item_ID;
                                document.getElementById("Quantity").value = '';
                                document.getElementById("Quantity").focus();
                            }

                            function Get_Item_Price(Item_ID, Guarantor_Name) {
                                var Billing_Type = document.getElementById("Billing_Type").value;
                                //alert(Billing_Type);
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
                                        document.getElementById('Quantity').value = 1;
                                        //alert(data);
                                    }
                                }; //specify name of function that will handle server response........

                                myObject.open('GET', 'Get_Items_Price.php?Item_ID=' + Item_ID + '&Guarantor_Name=' + Guarantor_Name + '&Billing_Type=' + Billing_Type, true);
                                myObject.send();
                            }

                            //this will be called on billing type
                            function Get_Item_Price2() {
                                var Billing_Type = document.getElementById("Billing_Type").value;
                                var Item_ID = document.getElementById("Item_ID").value;
                                var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
                                if (Billing_Type != '' && Billing_Type != null && Item_ID != '' && Item_ID != null && Guarantor_Name != '' && Guarantor_Name != null) {
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
                                            //alert(data);
                                        }
                                    }; //specify name of function that will handle server response........

                                    myObject.open('GET', 'Get_Items_Price.php?Item_ID=' + Item_ID + '&Guarantor_Name=' + Guarantor_Name + '&Billing_Type=' + Billing_Type, true);
                                    myObject.send();
                                }
                            }

                            function alertMessage() {
                                alert("Please Select Item First");
                            }
                        </script>

                        <script type='text/javascript'>
                            function update_Billing_Type() {
                                var Registration_ID = <?php echo $Registration_ID; ?>;

                                if (window.XMLHttpRequest) {
                                    myObject123 = new XMLHttpRequest();
                                } else if (window.ActiveXObject) {
                                    myObject123 = new ActiveXObject('Micrsoft.XMLHTTP');
                                    myObject123.overrideMimeType('text/xml');
                                }

                                //alert('Update_Billing_Type.php?Registration_ID='+Registration_ID);
                                myObject123.onreadystatechange = function () {
                                    data123 = myObject123.responseText;
                                    if (myObject123.readyState == 4) {
                                        document.getElementById('Billing_Type_Area').innerHTML = data123;
                                    }
                                };//specify name of function that will handle server response........

                                myObject123.open('GET', 'Update_Billing_Type_Adhoc.php?Registration_ID=' + Registration_ID, true);
                                myObject123.send();
                            }
                        </script>

                        <script type='text/javascript'>
                            function Get_Selected_Item() {
                                var Item_Name = document.getElementById("Item_Name").value;
                                var Discount = document.getElementById("Discount").value;
                                var Type_Of_Check_In = document.getElementById("Type_Of_Check_In").value;
                                var direction, Consultant;

                                if (Type_Of_Check_In != 'Doctor Room') {
                                    direction = 'others';
                                    Consultant = 'others';

                                } else {
                                    direction = document.getElementById("direction").value;
                                    Consultant = document.getElementById("Consultant").value;
                                }
                                var Item_ID = document.getElementById("Item_ID").value;
                                var Quantity = document.getElementById("Quantity").value;
                                var Registration_ID = <?php echo $Registration_ID; ?>;
                                var Employee_ID = <?php echo $Employee_ID; ?>;
                                var Billing_Type = document.getElementById("Billing_Type").value;
                                var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
                                var Sponsor_ID = <?php echo $Sponsor_ID; ?>;
                                var Claim_Form_Number = document.getElementById("Claim_Form_Number").value;
                                //alert('Perform_Reception_Transaction.php?Registration_ID='+Registration_ID+'&Item_ID='+Item_ID+'&Type_Of_Check_In='+Type_Of_Check_In+'&direction='+direction+'&Quantity='+Quantity+'&Consultant='+Consultant+'&Discount='+Discount); 

                                //alert('Add_Selected_Item.php?Registration_ID='+Registration_ID+'&Item_ID='+Item_ID+'&Type_Of_Check_In='+Type_Of_Check_In+'&direction='+direction+'&Quantity='+Quantity+'&Consultant='+Consultant+'&Discount='+Discount);
                                if (Registration_ID != '' && Registration_ID != null && Item_Name != '' && Item_Name != null && Item_ID != '' && Item_ID != null && Type_Of_Check_In != '' && Type_Of_Check_In != null && direction != '' && direction != null && Quantity != '' && Quantity != null && Employee_ID != '' && Employee_ID != null && Billing_Type != '' && Billing_Type != null <?php if (strtolower($Claim_Number_Status) == 'mandatory') { ?> && Claim_Form_Number != '' && Claim_Form_Number != null<?php } ?>) {
                                    if (window.XMLHttpRequest) {
                                        myObject2 = new XMLHttpRequest();
                                    } else if (window.ActiveXObject) {
                                        myObject2 = new ActiveXObject('Micrsoft.XMLHTTP');
                                        myObject2.overrideMimeType('text/xml');
                                    }

                                    myObject2.onreadystatechange = function () {
                                        data2 = myObject2.responseText;

                                        if (myObject2.readyState == 4) {
                                            document.getElementById('Picked_Items_Fieldset').innerHTML = data2;

                                            document.getElementById("Item_Name").value = '';
                                            document.getElementById("Item_ID").value = '';
                                            document.getElementById("Quantity").value = '';
                                            document.getElementById("Price").value = '';
                                            update_Billing_Type();

                                        }
                                    }; //specify name of function that will handle server response........

                                    //myObject.open('GET','Perform_Reception_Transaction.php?Registration_ID='+Registration_ID+'&Item_ID='+Item_ID+'&Type_Of_Check_In='+Type_Of_Check_In+'&direction='+direction+'&Quantity='+Quantity'&Consultant='+Consultant,true);
                                    myObject2.open('GET', 'Add_Selected_Item.php?Registration_ID=' + Registration_ID + '&Item_ID=' + Item_ID + '&Type_Of_Check_In=' + Type_Of_Check_In + '&direction=' + direction + '&Quantity=' + Quantity + '&Consultant=' + Consultant + '&Discount=' + Discount + '&Billing_Type=' + Billing_Type + '&Guarantor_Name=' + Guarantor_Name + '&Sponsor_ID=' + Sponsor_ID + '&Claim_Form_Number=' + Claim_Form_Number, true);
                                    myObject2.send();

                                } else if (Registration_ID != '' && Registration_ID != null && (Item_Name == '' || Item_Name == null || Item_ID == '' || Item_ID == null) && Type_Of_Check_In != '' && Type_Of_Check_In != null && direction != '' && direction != null && Quantity != '' && Quantity != null) {
                                    alertMessage();
                                } else {
                                    if (Discount == '' || Discount == null) {
                                        document.getElementById("Discount").value = '0';
                                    }
                                    if (Quantity == '' || Quantity == null) {
                                        document.getElementById("Quantity").focus();
                                        document.getElementById("Quantity").style = 'border-color: red';
                                    }
                                    if (Consultant == '' || Consultant == null) {
                                        document.getElementById("Consultant").focus();
                                        document.getElementById("Consultant").style = 'border-color: red';
                                    }
                                    if (direction == '' || direction == null) {
                                        document.getElementById("direction").focus();
                                        document.getElementById("direction").style = 'border-color: red';
                                    }
                                    if (Type_Of_Check_In == '' || Type_Of_Check_In == null) {
                                        document.getElementById("Type_Of_Check_In").focus();
                                        document.getElementById("Type_Of_Check_In").style = 'border-color: red';
                                    }
<?php if (strtolower($Claim_Number_Status) == 'mandatory') { ?>
                                        if (Claim_Form_Number == '' || Claim_Form_Number == null) {
                                            document.getElementById("Claim_Form_Number").focus();
                                            document.getElementById("Claim_Form_Number").style = 'border-color: red';
                                        }
<?php } ?>
                                }
                            }
                        </script>
                        <script>
                            function clearFocus(MyElement) {
                                MyElement.style = 'border-color: white';
                            }
                        </script>

                        <table width = 100%>
                            <tr>
                                <td style='text-align: center;'>
                                    <select name='Item_Category_ID' id='Item_Category_ID' onchange='getItemsList(this.value)' onchange='Calculate_Amount()' onkeypress='Calculate_Amount()'>
                                        <option selected='selected'></option>
                                        <?php
                                        $data = mysqli_query($conn,"select * from tbl_item_category tic JOIN tbl_item_subcategory tis ON tic.Item_category_ID=tis.Item_category_ID JOIN tbl_items ti ON ti.Item_Subcategory_ID=tis.Item_Subcategory_ID where consultation_Item='yes' GROUP BY Item_Category_Name");
                                        while ($row = mysqli_fetch_array($data)) {
                                            echo '<option value="' . $row['Item_Category_ID'] . '">' . $row['Item_Category_Name'] . '</option>';
                                        }
                                        ?>   
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><input type='text' id='Search_Value' name='Search_Value' onkeyup='getItemsListFiltered(this.value)' placeholder='Enter Item Name' autocomplete='off'></td>
                            </tr>			    
                            <tr>
                                <td>
                                    <fieldset style='overflow-y: scroll; height: 225px;' id='Items_Fieldset'>
                                        <table width=100%>
                                            <?php
                                            $result = mysqli_query($conn,"SELECT * FROM tbl_items where consultation_Item='yes' and consultation_Item='yes' order by Product_Name limit 200");
                                            while ($row = mysqli_fetch_array($result)) {
                                                echo "<tr>
							<td style='color:black; border:2px solid #ccc;text-align: left;'>";
                                                ?>

                                                <input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $row['Product_Name']; ?>' onclick="Get_Item_Name(this.value,<?php echo $row['Item_ID']; ?>);
                                                            Get_Item_Price(<?php echo $row['Item_ID']; ?>, '<?php echo $Guarantor_Name; ?>')">

                                                <?php
                                                echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'><label for='" . $row['Item_ID'] . "'>" . $row['Product_Name'] . "<label></td></tr>";
                                            }
                                            ?> 
                                        </table>
                                    </fieldset>		
                                </td>
                            </tr>
                        </table> 
                    </td>
                    <td>
                        <table width=100%>
                            <tr>
                                <td style='text-align: center;' colspan=2>



                                    <!-- ITEM DESCRIPTION START HERE -->


                            <center>
                                <table width=100%>
                                    <tr>
                                        <td>Item Name</td>
                                        <td>Discount</td>
                                        <td>Price</td>
                                        <td>Balance</td>
                                        <td>Qty</td>
                                        <td>Amount</td>
                                        <td></td>
                                    </tr>
                                    <tr>  
                                        <td width=35%> 
                                            <input type='text' name='Item_Name' id='Item_Name' size=20 placeholder='Item Name' readonly='readonly' required='required'>
                                            <input type='hidden' name='Item_ID' id='Item_ID' value=''>
                                        </td> 
                                        <td>
                                            <input type='text' name='Discount' id='Discount' placeholder='Discount' onchange='Calculate_Amount()' onkeypress='Calculate_Amount()' value=0>
                                        </td>
                                        <td>
                                            <input type='text' name='Price' id='Price' placeholder='Price' readonly='readonly'>
                                        </td>
                                        <td>
                                            <input type='text' name='Balance' id='Balance' placeholder='Balance' value=1 readonly='readonly'>
                                        </td>
                                        <td>
                                            <input type='text' name='Quantity' id='Quantity' autocomplete='off' required='required' placeholder='Quantity' onchange='Calculate_Amount()' onkeypress='Calculate_Amount()'>
                                        </td>
                                        <td>
                                            <input type='text' name='Amount' id='Amount' placeholder='Amount' readonly='readonly'>
                                        </td>
                                        <td style='text-align: center;'>
                                            <script type='text/javascript'>
                                                function Make_Payment_Warning() {
                                                    alert("Please choose one of the options below");
                                                }
                                                function Select_Patient_First() {
                                                    alert("Please select patient first");
                                                }
                                            </script>
                                            <input type='button' name='submit' id='submit' value='ADD' class='art-button-green' onclick='Get_Selected_Item()'>
                                        </td>
                                    </tr>
                                </table>   
                            </center>
                            <!-- ITEM DESCRIPTION ENDS HERE -->
                    </td>
                </tr>
                <tr>
                    <td colspan=2 id='Picked_Items_Fieldset'>
                        <!--<iframe src='Adhoc_Patient_Billing_Iframe.php?Registration_ID=<?php echo $Registration_ID; ?>&Employee_ID=<?php echo $Employee_ID; ?>' width='100%' height=200px></iframe>-->
                        <fieldset style='overflow-y: scroll; height: 200px;'>
                            <?php
                            $total = 0;
                            echo '<table width =100%>';
                            echo "<tr id='thead'><td style='width: 3%;'>Sn</td><td style='width: 10%;'>Check in type</td>";
                            echo '<td style="width: 20%;">Location</td>
							<td style="width: 28%;">Item description</td>
							    <td style="text-align:right; width: 8%;">Price</td>
								<td style="text-align:right; width: 8%;">Discount</td>
								    <td style="text-align:right; width: 8%;">Quantity</td>
									<td style="text-align:right; width: 8%;">Sub total</td><td width=4%>Remove</td></tr>';

                            $select_Transaction_Items = mysqli_query($conn,
                                    "select Adhoc_Item_ID, Check_In_Type, Patient_Direction, Product_Name, Price, Discount, Quantity,Registration_ID
						    from tbl_items t, tbl_adhoc_items_list_cache alc
							where alc.Item_ID = t.Item_ID and
							    alc.Employee_ID = '$Employee_ID' and
								    Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
                            $no_of_items = mysqli_num_rows($select_Transaction_Items);
                            while ($row = mysqli_fetch_array($select_Transaction_Items)) {
                                echo "<tr><td>" . $temp . "</td><td>" . $row['Check_In_Type'] . "</td>";
                                echo "<td>" . $row['Patient_Direction'] . "</td>";
                                echo "<td>" . $row['Product_Name'] . "</td>";
                                echo "<td style='text-align:right;'>" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($row['Price'], 2) : number_format($row['Price'])) . "</td>";
                                echo "<td style='text-align:right;'>" . number_format($row['Discount']) . "</td>";
                                echo "<td style='text-align:right;'>" . $row['Quantity'] . "</td>";
                                echo "<td style='text-align:right;'>" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format(($row['Price'] - $row['Discount']) * $row['Quantity'], 2) : number_format(($row['Price'] - $row['Discount']) * $row['Quantity'])) . "</td>";
                                ?>
                                <td style='text-align: center;'> 
                                    <input type='button' style='color: red; font-size: 10px;' value='X' onclick='Confirm_Remove_Item("<?php echo str_replace("'", "", $row['Product_Name']); ?>",<?php echo $row['Adhoc_Item_ID']; ?>,<?php echo $row['Registration_ID']; ?>)'>
                                </td>
                                <?php
                                $temp++;
                                $total = $total + (($row['Price'] - $row['Discount']) * $row['Quantity']);
                            }echo "</tr>";
                            echo "<tr><td colspan=8 style='text-align: right;'> Total : " . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($total, 2) : number_format($total)) . '  ' . $_SESSION['hospcurrency']['currency_code'] . "</td></tr></table>";
                            ?>
                        </fieldset>
                        <table width='100%'>
                            <tr>
                                <?php
                                if ($no_of_items > 0) {
                                    ?>
                                    <td style='text-align: right; width: 70%;'><h4>Total : <?php echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($total, 2) : number_format($total)) . '  ' . $_SESSION['hospcurrency']['currency_code']; ?></h4></td>
                                    <td style='text-align: right; width: 30%;'>
                                        <input type='button' value='Save Information' class='art-button-green' onclick='Adhock_Generate_Receipt(<?php echo $Registration_ID; ?>)'>
                                    </td>
                                    <?php
                                } else {
                                    ?>
                                    <td style='text-align: right; width: 70%;'><h4>Total : 0</h4></td>
                                    <td style='text-align: right; width: 30%;'>
                                    </td>
                                    <?php
                                }
                                ?>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            </td>
            </tr> 
            </table>
        </center>
    </fieldset>
    <script type='text/javascript'>
        function Make_Payment() {
            var Patient_Payment_Cache_ID = <?php echo $Patient_Payment_Cache_ID; ?>;
            document.location = 'Patient_Billing_Prepared_Make_Payment.php?Patient_Payment_Cache_ID=' + Patient_Payment_Cache_ID
        }

        function Confirm_Make_Payment() {
            var r = confirm("You are about to make Transaction. Click OK to continue?");
            if (r == true) {
                Make_Payment();
            }
        }


        function Remove_Item() {
            alert("This Item Removed");
        }
    </script>



    <script type='text/javascript'>
        function Confirm_Remove_Item(Item_Name, Adhoc_Item_ID, Registration_ID) {
            var Confirm_Message = confirm("Are you sure you want to remove \n" + Item_Name);
            if (Confirm_Message == true) {
                if (window.XMLHttpRequest) {
                    myObject = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObject.overrideMimeType('text/xml');
                }

                myObject.onreadystatechange = function () {
                    data = myObject.responseText;
                    if (myObject.readyState == 4) {
                        document.getElementById('Picked_Items_Fieldset').innerHTML = data;
                        update_total(Registration_ID);
                        //update_Billing_Type();
                    }
                }; //specify name of function that will handle server response........

                myObject.open('GET', 'Adhoc_Remove_Item_From_List.php?Adhoc_Item_ID=' + Adhoc_Item_ID + '&Registration_ID=' + Registration_ID, true);
                myObject.send();
            }
        }

        function update_total(Registration_ID) {
            if (window.XMLHttpRequest) {
                myObject = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }
            myObject.onreadystatechange = function () {
                data = myObject.responseText;
                if (myObject.readyState == 4) {
                    document.getElementById('Total_Area').innerHTML = data;
                }
            }; //specify name of function that will handle server response........

            myObject.open('GET', 'Adhoc_Update_Total.php?Registration_ID=' + Registration_ID, true);
            myObject.send();
        }

        function Remove_Item() {
            alert("This Item Removed");
        }


        function Adhock_Generate_Receipt(Registration_ID) {
            var Confirm_Message = confirm("Are you sure you want to perform transaction?");
            if (Confirm_Message == true) {
                document.location = 'Confirmed_Adhoc_Payment.php?Registration_ID=' + Registration_ID;
            }
        }
    </script>
    <script>
        function type_Of_Check_In(type_of_check_in) {
            if (type_of_check_in !== 'Doctor Room') {
                //alert(type_of_check_in+' Not To Doctor Room');
                $("#direction,#Consultant").css("background", "#ccc");
                $("#direction,#Consultant").attr("disabled", "true");
                $("#direction,#Consultant").removeAttr('required');
                $("#direction,#Consultant").val("");
            } else {
                $("#direction,#Consultant").css("background", "white");
                $("#direction,#Consultant").attr("disabled", false);
                $("#direction,#Consultant").attr('required', 'required');
            }


            //MyElement.style = 'border-color: white';
            $(this).css("border-color", "white");
        }
    </script>

    <link rel="stylesheet" href="css/select2.min.css" media="screen">
    <script src="js/jquery-1.8.0.min.js"></script>
    <script src="js/select2.min.js"></script>
    <script src="js/jquery-ui-1.8.23.custom.min.js"></script> 
    <link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
    <!--<script src="js/jquery-ui-1.10.1.custom.min.js"></script>-->

    <!-- pop up window -->
    <div id="Display_Number_Of_Patients" style="width:50%;" >
        <center id='Details_Area'>
            <table width=100% style='border-style: none;'>
                <tr>
                    <td>

                    </td>
                </tr>
            </table>
        </center>
    </div>

    <script>
        function open_Dialog() {
            if (window.XMLHttpRequest) {
                myObjectGetDetails = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectGetDetails = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectGetDetails.overrideMimeType('text/xml');
            }
            myObjectGetDetails.onreadystatechange = function () {
                data29 = myObjectGetDetails.responseText;
                if (myObjectGetDetails.readyState == 4) {
                    document.getElementById('Details_Area').innerHTML = data29;
                    $("#Display_Number_Of_Patients").dialog("open");
                }
            }; //specify name of function that will handle server response........

            myObjectGetDetails.open('GET', 'patientqueue.php', true);
            myObjectGetDetails.send();
        }
    </script>

    <!--<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
    <script src="script.responsive.js"></script>-->

    <script>
        $(document).ready(function () {
            $("#Display_Number_Of_Patients").dialog({autoOpen: false, width: '30%', height: 500, title: 'DOCTORS QUEUE', modal: true});
            $("select").select2();
        });
    </script>


    <!-- end of pop up window -->


    <?php
    include("./includes/footer.php");
    ?>