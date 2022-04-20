<?php
include("./includes/header.php");
include("./includes/connection.php");
$total = 0;
$num_of_items_selected = 0;
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Reception_Works'])) {
        if ($_SESSION['userinfo']['Reception_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        } else {
            @session_start();
            if (!isset($_SESSION['supervisor'])) {
                //get patient registration id for future use
                if (isset($_GET['Registration_ID'])) {
                    $Registration_ID = $_GET['Registration_ID'];
                } else {
                    $Registration_ID = '';
                }
                header("Location: ./receptionsupervisorauthentication.php?Registration_ID=$Registration_ID&InvalidSupervisorAuthentication=yes");
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
<style>
    table,tr,td{
        //border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>

<?php
if (isset($_SESSION['userinfo'])) {
    ?>
    <a href='visitorform.php?VisitorForm=VisitorFormThisPage' class='art-button-green'>
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
        $("#Consultant").select2().select2("val", '');
        $("#Consultant").css('width', '100%');
        var Type_Of_Check_In = document.getElementById('Type_Of_Check_In').value;
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }

        if (document.getElementById('direction').value == 'Direct To Doctor Via Nurse Station' || document.getElementById('direction').value == 'Direct To Doctor') {
            document.getElementById('Doctors_List').style.visibility = "";
            mm.onreadystatechange = AJAXP3; //specify name of function that will handle server response....
            mm.open('GET', 'Get_Guarantor_Name.php?Type_Of_Check_In=' + Type_Of_Check_In + '&direction=doctor', true);
            mm.send();
        }
        else {
            mm.onreadystatechange = AJAXP3; //specify name of function that will handle server response....
            mm.open('GET', 'Get_Guarantor_Name.php?direction=clinic', true);
            mm.send();
            document.getElementById('Doctors_List').style.visibility = "hidden";
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









<form action='' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    <!--<br/>-->
    <fieldset>  
        <legend align=right><b>REVENUE CENTER - RECEPTION</b></legend>
        <center> 

            <table width=100%>
                <tr>
                    <td style='text-align: right; width: 12%;'><b>Billing Type</b></td>

                    <td style='width: 15%;'>
                        <select style='width: 100%;' name='Billing_Type' id='Billing_Type' onchange='Get_Item_Price2()' required='required'>
                            <option selected='selected'>Outpatient Cash</option>
                        </select>
                    </td>
                    <td style='text-align: right; width: 10%'><b>Patient Name</b></td>
                    <td width='15%'><input type='text' name='Patient_Name' disabled='disabled' id='Patient_Name' value='<?php echo $Patient_Name; ?>'></td>
                    <td style='text-align: right;'><b>Registration Number</b></td>
                    <td><input type='text' name='Registration_Number' id='Registration_Number' disabled='disabled' value='<?php echo $Registration_ID; ?>'></td>
                </tr>
                <tr>
                    <td style='text-align: right;'><b>Type Of Check In</b></td>
                    <td>  
                        <select name='Type_Of_Check_In' id='Type_Of_Check_In' style='width: 100%;' required='required' onchange='clearFocus(this)' onclick='clearFocus(this)'>

                            <?php /* if(!isset($_GET['NR'])){ ?>
                              <option selected='selected'><?php echo $Check_In_Type; ?>hh</option>
                              <?php } else{ ?>
                              <option selected='selected'><?php $Check_In_Type; ?>gg</option>
                              <?php } */ ?>

                            <!--<option>Radiology</option>-->
                            <!-- <option>Dialysis</option>
                            <option>Physiotherapy</option>
                            <option>Optical</option>  -->
                            <option>Doctor Room</option>
                            <!--  <option>Dressing</option> -->
                            <!-- <option>Matenity</option>
                            <option>Cecap</option> -->
                            <!--<option>Laboratory</option>-->
                            <!--<option>Pharmacy</option>-->
                            <!-- <option>Theater</option>
                            <option>Dental</option>
                            <option>Ear</option> -->
                        </select>
                    </td>

                    <td style='text-align: right;'><b>Occupation</b></td>
                    <td>
                        <input type='text' name='Receipt_Date' disabled='disabled' id='date2' value='<?php echo $Occupation; ?>'>
                    </td>
                    <td style='text-align: right; width: 11%'><b>Gender</b></td>
                    <td width='12%'><input type='text' name='Receipt_Number' disabled='disabled' id='Receipt_Number' value='<?php echo $Gender; ?>'></td>
                </tr>
                <tr>

                    <td style='text-align: right;'><b>Patient Direction</b></td>
                    <td>
                        <select style='width: 100%;' id='direction' name='direction' onclick='getDoctor();
                                clearFocus(this)' onchange='getDoctor();
                                        clearFocus(this)' required='required'>
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


                    <td style='text-align: right;'><b>Patient Age</b></td>
                    <td><input type='text' name='Patient_Age' id='Patient_Age'  disabled='disabled' value='<?php echo $age; ?>'></td>
                    <td style='text-align: right;'><b>Registered Date</b></td>
                    <td><input type='text' name='Folio_Number' id='Folio_Number' disabled='disabled' value='<?php echo $Registration_Date_And_Time; ?>'></td>
                </tr>
                <tr>
                    <td style='text-align: right;'><b>Consultant</b></td>
                    <td>
                        <select name='Consultant' id='Consultant' value='<?php echo $Guarantor_Name; ?>' onchange='clearFocus(this)' onclick='clearFocus(this)' required='required'>

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
                        <input type="button" name="Doctors_List" id="Doctors_List" value="SELECT DOCTOR" onclick="Get_Doctor()" style="Visibility: hidden;">
                    </td>

                    <td style='text-align: right;'><b>Sponsor Name</b></td>
                    <td><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>
                    <td style='text-align: right;'><b>Phone Number</b></td>
                    <td><input type='text' name='Phone_Number' id='Phone_Number' disabled='disabled' value='<?php echo $Phone_Number; ?>'></td>
                </tr> 
            </table>
        </center>
    </fieldset>

    <div id="List_OF_Doctors">
        <center>
            <table width="100%">
                <tr>
                    <td>
                        <input type="text" name="Doc_Name" id="Doc_Name" placeholder="~~~ ~~~ Enter Doctor Name ~~~ ~~~" autocomplete="off" style="text-align: center;" onkeyup="Search_Doctors()" oninput="Search_Doctors()">
                    </td>
                </tr>
                <tr>
                    <td>
                        <fieldset style='overflow-y: scroll; height: 200px; background-color: white;' id='Doctors_Area'>
                            <table width="100%">
                                <?php
                                $counter = 0;
                                $get_doctors = mysqli_query($conn,"select Employee_ID, Employee_Name from tbl_employee where Employee_Type = 'Doctor' and Account_Status = 'active' order by Employee_Name limit 100") or die(mysqli_error($conn));
                                $doctors_num = mysqli_num_rows($get_doctors);
                                if ($doctors_num > 0) {
                                    while ($data = mysqli_fetch_array($get_doctors)) {
                                        ?>
                                        <tr>
                                            <td style='text-align: right;'>
                                                <label onclick="Get_Selected_Doctor('<?php echo $data['Employee_Name']; ?>')"><?php echo ++$counter; ?></label>
                                            </td>
                                            <td>
                                                <label onclick="Get_Selected_Doctor('<?php echo $data['Employee_Name']; ?>')">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strtoupper($data['Employee_Name']); ?></label>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </table>
                        </fieldset>
                    </td>
                </tr>
            </table>
        </center>
    </div>

    <script type="text/javascript">
        function Get_Doctor() {
            var Direction = document.getElementById("direction").value;
            if (Direction != null && Direction != '' && (Direction == 'Direct To Doctor Via Nurse Station' || Direction == 'Direct To Doctor')) {
                $("#List_OF_Doctors").dialog("open");
            }
        }
    </script>

    <script type="text/javascript">
        function Search_Doctors() {
            var Doctror_Name = document.getElementById("Doc_Name").value;
            if (window.XMLHttpRequest) {
                myObject_Search_Doctor = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject_Search_Doctor = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject_Search_Doctor.overrideMimeType('text/xml');
            }

            myObject_Search_Doctor.onreadystatechange = function () {
                data = myObject_Search_Doctor.responseText;
                if (myObject_Search_Doctor.readyState == 4) {
                    document.getElementById('Doctors_Area').innerHTML = data;
                }
            }; //specify name of function that will handle server response........
            myObject_Search_Doctor.open('GET', 'Search_Doctors.php?Doctror_Name=' + Doctror_Name, true);
            myObject_Search_Doctor.send();
        }
    </script>

    <script type="text/javascript">
        function Get_Selected_Doctor(Doctror_Name) {
            document.getElementById("Consultant").value = Doctror_Name;
            $("#List_OF_Doctors").dialog("close");
        }
    </script>
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
                                myObject.open('GET', 'Get_List_Of_Items.php?Item_Category_ID=' + Item_Category_ID + '&Guarantor_Name=' + Guarantor_Name, true);
                                myObject.send();
                            }

                            function getItemsListFiltered(Item_Name, Guarantor_Name) {
                                document.getElementById("Price").value = '';
                                document.getElementById("Item_Name").value = '';
                                document.getElementById("Quantity").value = '';
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
                                    data = myObject.responseText;
                                    if (myObject.readyState == 4) {
                                        //document.getElementById('Approval').disabled = 'disabled';
                                        document.getElementById('Items_Fieldset').innerHTML = data;
                                    }
                                }; //specify name of function that will handle server response........
                                myObject.open('GET', 'Get_List_Of_Items_Filtered.php?Item_Category_ID=' + Item_Category_ID + '&Item_Name=' + Item_Name + '&Guarantor_Name=' + Guarantor_Name, true);
                                myObject.send();
                            }

                            function Get_Item_Name(Item_Name, Item_ID) {
                                document.getElementById("Item_Name").value = Item_Name;
                                document.getElementById("Item_ID").value = Item_ID;
                                document.getElementById("Quantity").value = 1;
                                //document.getElementById("Quantity").focus();
                            }

                            function Get_Item_Price(Item_ID, Guarantor_Name) {
                                var Billing_Type = document.getElementById("Billing_Type").value;
                                //alert(Item_ID);
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


                            function Make_Payment() {
                                var Item_Name = document.getElementById("Item_Name").value;
                                var Discount = document.getElementById("Discount").value;
                                var Type_Of_Check_In = document.getElementById("Type_Of_Check_In").value;
                                var direction = document.getElementById("direction").value;
                                var Consultant = document.getElementById("Consultant").value;
                                var Item_ID = document.getElementById("Item_ID").value;
                                var Quantity = document.getElementById("Quantity").value;

                                var Registration_ID = <?php echo $Registration_ID; ?>;

                                if (Registration_ID != '' && Registration_ID != null && Item_Name != '' && Item_Name != null && Item_ID != '' && Item_ID != null && Type_Of_Check_In != '' && Type_Of_Check_In != null && direction != '' && direction != null && Quantity != '' && Quantity != null && Consultant != null && Consultant != '') {

                                    if (window.XMLHttpRequest) {
                                        myObject2 = new XMLHttpRequest();
                                    } else if (window.ActiveXObject) {
                                        myObject2 = new ActiveXObject('Micrsoft.XMLHTTP');
                                        myObject2.overrideMimeType('text/xml');
                                    }

                                    myObject2.onreadystatechange = function () {
                                        data = myObject2.responseText;

                                        if (myObject2.readyState == 4) {
                                            //alert("One Item Added");
                                            document.getElementById('Picked_Items_Fieldset').innerHTML = data;

                                            document.getElementById("Item_Name").value = '';
                                            document.getElementById("Item_ID").value = '';
                                            document.getElementById("Quantity").value = '';
                                            document.getElementById("Price").value = '';

                                            //update_fieldset(Registration_ID);
                                            update_total(Registration_ID);
                                        }
                                    }; //specify name of function that will handle server response........

                                    //myObject.open('GET','Perform_Reception_Transaction.php?Registration_ID='+Registration_ID+'&Item_ID='+Item_ID+'&Type_Of_Check_In='+Type_Of_Check_In+'&direction='+direction+'&Quantity='+Quantity'&Consultant='+Consultant,true);
                                    myObject2.open('GET', 'Perform_Reception_Transaction.php?Registration_ID=' + Registration_ID + '&Item_ID=' + Item_ID + '&Type_Of_Check_In=' + Type_Of_Check_In + '&direction=' + direction + '&Quantity=' + Quantity + '&Consultant=' + Consultant + '&Discount=' + Discount, true);
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
                                }
                            }

                            function alertMessage() {
                                alert("Please Select Item First");
                            }
                        </script>
                        <script>
                            function clearFocus(MyElement) {
                                MyElement.style = 'border-color: white';
                            }
                        </script>
                        <script type='text/javascript'>
                            function Confirm_Remove_Item(Item_Name, Patient_Payment_Item_List_ID, Registration_ID) {
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
                                            //update_fieldset(Registration_ID);
                                            update_total(Registration_ID);
                                        }
                                    }; //specify name of function that will handle server response........

                                    myObject.open('GET', 'Remove_Item_From_List.php?Patient_Payment_Item_List_ID=' + Patient_Payment_Item_List_ID + '&Registration_ID=' + Registration_ID, true);
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

                                myObject.open('GET', 'Update_Total.php?Registration_ID=' + Registration_ID, true);
                                myObject.send();
                            }


                            function update_fieldset(Registration_ID) {
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
                                    }
                                }; //specify name of function that will handle server response........

                                myObject.open('GET', 'Update_Fieldset.php?Registration_ID=' + Registration_ID, true);
                                myObject.send();
                            }

                            function Remove_Item() {
                                alert("This Item Removed");
                            }
                        </script>

                        <table width = 100%>
                            <tr>
                                <td style='text-align: center;'>
                                    <select name='Item_Category_ID' id='Item_Category_ID' onchange='getItemsList(this.value)' onchange='Calculate_Amount()' onkeypress='Calculate_Amount()'>
                                        <option selected='selected'></option>
                                        <?php
                                        $data = mysqli_query($conn,"select * from tbl_item_category");
                                        while ($row = mysqli_fetch_array($data)) {
                                            echo '<option value="' . $row['Item_Category_ID'] . '">' . $row['Item_Category_Name'] . '</option>';
                                        }
                                        ?>   
                                    </select>
                                </td>
                            </tr>
                            <tr><td><input type='text' id='Search_Value' name='Search_Value' onkeyup='getItemsListFiltered(this.value)' placeholder='Enter Item Name'></td></tr>

                            <tr>
                                <td>
                                    <fieldset style='overflow-y: scroll; height: 225px;' id='Items_Fieldset'>
                                        <table width=100%>
                                            <?php
                                            $result = mysqli_query($conn,"SELECT * FROM tbl_items order by Product_Name limit 100");
                                            while ($row = mysqli_fetch_array($result)) {
                                                echo "<tr>
							<td style='color:black; border:2px solid #ccc;text-align: left;'>";
                                                ?>
                                                <input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $row['Product_Name']; ?>' onclick="Get_Item_Name(this.value,<?php echo $row['Item_ID']; ?>);
                                                        Get_Item_Price(<?php echo $row['Item_ID']; ?>, '<?php echo $Guarantor_Name; ?>')">						       
                                                       <?php
                                                       echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'><label for='" . $row['Item_ID'] . "'>" . $row['Product_Name'] . "</label></td></tr>";
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
                                            <!--<select name='Item_ID' id='Item_ID' required='required'>-->
                                            <!--    <option selected='selected' style='width: 300px;'></option>-->
                                            <!--</select>-->
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
                                            <input type='text' name='Quantity' autocomplete='off' id='Quantity' required='required' placeholder='Quantity' onchange='Calculate_Amount()' onkeypress='Calculate_Amount()'>
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

                                            <?php
                                            if (isset($_SESSION['userinfo']['Employee_ID'])) {
                                                //get employee id
                                                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
                                                if ($Registration_ID != '') {
                                                    //check if there is any pending order but different from this patient
                                                    //select previous record
                                                    $Previous_Record = mysqli_query($conn,"select * from tbl_patient_payments_cache where
						    Transaction_status = 'pending' and
							Employee_ID = '$Employee_ID' and
							    Registration_ID <> '$Registration_ID'") or die(mysqli_error($conn));
                                                    $num_rows = mysqli_num_rows($Previous_Record);
                                                    if ($num_rows > 0) {
                                                        echo "<input type='button' name='button' id='button' value='ADD' class='art-button-green' onclick='Make_Payment_Warning()'>";
                                                    } else {
                                                        echo "<input type='button' name='submit' id='button' value='ADD' class='art-button-green' onclick='Make_Payment()'>";
                                                    }
                                                } else {
                                                    echo "<input type='button' name='button' id='button' value='ADD' class='art-button-green' onclick='Select_Patient_First()'>";
                                                }
                                            }
                                            ?> 
                                        </td>
                                    </tr>
                                </table>   
                            </center></form>
                            <!-- ITEM DESCRIPTION ENDS HERE -->
                    </td>
                </tr>
                <tr>
                    <td colspan=2 style='text-align: right;'>
                        <?php
//this part will be removed soon
//check if there is any pending order but different from selected patient
//select previous record
                        /* $Previous_Record = mysqli_query($conn,"select * from tbl_patient_payments_cache where
                          Transaction_status = 'pending' and
                          Employee_ID = '$Employee_ID' and
                          Registration_ID <> '$Registration_ID'") or die(mysqli_error($conn));
                          $num_rows = mysqli_num_rows($Previous_Record);
                          if($num_rows > 0){
                          //get some patient details
                          while($Patient_Details = mysqli_fetch_array($Previous_Record)){
                          //pending patient id
                          $Temp_Registration_ID = $Patient_Details['Registration_ID'];
                          }
                          echo "<iframe src='Patient_Billing_Prepare_Confirmation.php?Registration_ID=".$Registration_ID."&Temp_Registration_ID=".$Temp_Registration_ID."' width='100%' height=200px></iframe>";
                          }else{
                          echo "<iframe src='Patient_Billing_Iframe_Prepare.php?Registration_ID=".$Registration_ID."' width='100%' height=200px></iframe>";
                          } */
                        ?>



                        <fieldset style='overflow-y: scroll; height: 190px;' id='Picked_Items_Fieldset'>
                            <?php
//check if there is another patient before
                            $Previous_Record = mysqli_query($conn,
                                    "select * from tbl_patient_payments_cache where
						 Transaction_status = 'pending' and
						     Employee_ID = '$Employee_ID' and
							 Registration_ID <> '$Registration_ID'") or die(mysqli_error($conn));
                            $num_rows = mysqli_num_rows($Previous_Record);

                            if ($num_rows > 0) {
                                while ($Patient_Details = mysqli_fetch_array($Previous_Record)) {
                                    //pending patient id
                                    $Temp_Registration_ID = $Patient_Details['Registration_ID'];
                                }
                                //get previous patient details	
                                $select_details = mysqli_query($conn,"select pr.Patient_Name,ppc.Payment_Date_And_Time from
						tbl_patient_payments_cache ppc, tbl_patient_registration pr where
						    pr.registration_id = ppc.registration_id and
							pr.registration_id = '$Temp_Registration_ID'") or die(mysqli_error($conn));

                                while ($row = mysqli_fetch_array($select_details)) {
                                    $Temp_Patient_Name = $row['Patient_Name'];
                                    $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
                                }
                                ?>
                                <center>
                                    <span>
                                        <h3>System detects that, there is another pending Patient you were working on</h3>
                                    </span>
                                </center>
                                <center>
                                    <table width=60%>
                                        <tr><td style='text-align: right;'>Patient Name : </td><td style='text-align: left;'><?php echo $Temp_Patient_Name; ?></td></tr>
                                        <tr><td style='text-align: right;'>Registration Number : </td><td style='text-align: left;'><?php echo $Temp_Registration_ID; ?></td></tr>
                                        <tr><td style='text-align: right;'>Attempt Date & Time : </td><td style='text-align: left;'><?php echo $Payment_Date_And_Time; ?></td></tr>
                                    </table>
                                    <table width=80>
                                        <tr>
                                            <td style='text-align: center;'>
                                                <a href='patientbillingprepare.php?Registration_ID=<?php echo $Temp_Registration_ID; ?>&NR=True&PatientBillingPrepare=PatientBillingPrepareThisForm' class='art-button-green' target='_Parent'>Back to previous patient (<?php echo $Temp_Patient_Name; ?>)</a>
                                            </td>
                                            <td style='text-align: center;'>
                                                <a href='Patient_Billing_Prepare_Delete.php?Temp_Registration_ID=<?php echo $Temp_Registration_ID; ?>&Employee_ID=<?php echo $Employee_ID; ?>&Registration_ID=<?php echo $Registration_ID; ?>' target='_Parent' class='art-button-green'>Continue with selected patient (<?php echo $Patient_Name; ?>)</a>
                                            </td>
                                        </tr>
                                    </table>

                                </center>
                                <?php
                            } else {
                                //display items
                                echo '<table width =100%>';
                                echo "<tr id='thead'><td style='width: 3%;'>Sn</td><td style='width: 10%;'>Check in type</td>";
                                echo '<td style="width: 20%;">Location</td>
							    <td style="width: 28%;">Item description</td>
								<td style="text-align:right; width: 8%;">Price</td>
								    <td style="text-align:right; width: 8%;">Discount</td>
									<td style="text-align:right; width: 8%;">Quantity</td>
									    <td style="text-align:right; width: 8%;">Sub total</td><td width=4%>Remove</td></tr>';

                                $total = 0;
                                $temp = 1;
                                $select_Transaction_Items = mysqli_query($conn,
                                        "select Check_In_Type, Patient_Direction, Product_Name, Price, Discount, Quantity,Patient_Payment_Item_List_ID, ppc.Registration_ID
						    from tbl_items t, tbl_patient_payments_cache ppc, tbl_patient_payment_item_list_cache ppi
							where ppc.Patient_Payment_Cache_ID = ppi.Patient_Payment_Cache_ID and
							    t.item_id = ppi.item_id and
								Employee_ID = '$Employee_ID' and
								    Registration_ID = '$Registration_ID' and
									Transaction_status = 'pending'") or die(mysqli_error($conn));
                                $num_of_items_selected = mysqli_num_rows($select_Transaction_Items);
                                while ($row = mysqli_fetch_array($select_Transaction_Items)) {
                                    echo "<tr><td>" . $temp . "</td><td>" . $row['Check_In_Type'] . "</td>";
                                    echo "<td>" . $row['Patient_Direction'] . "</td>";
                                    echo "<td>" . $row['Product_Name'] . "</td>";
                                    echo "<td style='text-align:right;'>" . number_format($row['Price']) . "</td>";
                                    echo "<td style='text-align:right;'>" . number_format($row['Discount']) . "</td>";
                                    echo "<td style='text-align:right;'>" . $row['Quantity'] . "</td>";
                                    echo "<td style='text-align:right;'>" . number_format(($row['Price'] - $row['Discount']) * $row['Quantity']) . "</td>";
                                    ?>
                                    <td style='text-align: center;'> 
                                        <input type='button' style='color: red; font-size: 10px;' value='X'  onclick='Confirm_Remove_Item("<?php echo str_replace("'", "", $row['Product_Name']); ?>",<?php echo $row['Patient_Payment_Item_List_ID']; ?>,<?php echo $row['Registration_ID']; ?>)'>
                                    </td>
                                    <?php
                                    $total = $total + (($row['Price'] - $row['Discount']) * $row['Quantity']);
                                    $temp++;
                                } echo "</tr>";
                                echo "<tr><td colspan=8 style='text-align: right;'> <b>Total : " . number_format($total) . "</b></td></tr>";
                                ?></table><?php } ?> 
    </fieldset>


</td>
</tr>
<?php
$total = 0;
$num_rows = 0;
$get_Total = mysqli_query($conn,
        "select Price, Discount, Quantity, ppc.Patient_Payment_Cache_ID
					from tbl_items t, tbl_patient_payments_cache ppc, tbl_patient_payment_item_list_cache ppi
					    where ppc.Patient_Payment_Cache_ID = ppi.Patient_Payment_Cache_ID and
						t.item_id = ppi.item_id and
						    Employee_ID = '$Employee_ID' and
							Registration_ID = '$Registration_ID' and
							    Transaction_status = 'pending'") or die(mysqli_error($conn));

$num_rows = mysqli_num_rows($get_Total);
if ($num_rows > 0) {
    while ($row = mysqli_fetch_array($get_Total)) {
        $Patient_Payment_Cache_ID = $row['Patient_Payment_Cache_ID'];
        $total = $total + (($row['Price'] - $row['Discount']) * $row['Quantity']);
    }

    //get number of items
    $sql = mysqli_query($conn,"select Patient_Payment_Item_List_ID from tbl_patient_payment_item_list_cache
							    where Patient_Payment_Cache_ID = '$Patient_Payment_Cache_ID'") or die(mysqli_error($conn));
    $number_of_items = mysqli_num_rows($sql);
}
?>
<tr id='Total_Area'>
    <td style='text-align: right;'>
        <b><h4 id='Total_Area'>Total : <?php echo number_format($total); ?>&nbsp;&nbsp;&nbsp;</h4>
    </td>
    <?php
    if ($num_of_items_selected > 0) {
        ?>
        <td style='text-align: right;'>
            <input type='button' value='Create ePayment Bill' id='Pay_Via_Mobile' name='Pay_Via_Mobile' class='art-button-green' onclick='Pay_Via_Mobile_Function()'>
            <input type='button' name='Send_To_Cashier_Button' class='art-button-green' value='Send To Cashier' onclick=' return Confirm_Send_To_Cashier("<?php echo $Patient_Payment_Cache_ID; ?>")'>
        </td>
        <?php
    } else {
        echo "<td style='text-align: right;'>
							&nbsp;
						    </td>";
    }
    ?>
</tr>

<tr>				
<script type='text/javascript'>

    function Send_To_Cashier(Patient_Payment_Cache_ID) {
        document.location = 'Send_To_Cashier_Patient_Reception.php?Patient_Payment_Cache_ID=' + Patient_Payment_Cache_ID
    }

    function Confirm_Send_To_Cashier(Patient_Payment_Cache_ID) {
        var r = confirm("Are you sure you want to send this bill to cashier?");
        if (r == true) {
            Send_To_Cashier(Patient_Payment_Cache_ID);
        }
    }

</script>
</tr>
</table> 
</td>
</tr> 
</table>
</center>
</fieldset>

<!-- ePayment pop up windows -->
<div id="ePayment_Window" style="width:50%;" >
    <span id='ePayment_Area'>
        <table width=100% style='border-style: none;'>
            <tr>
                <td>

                </td>
            </tr>
        </table>
    </span>
</div>

<script>
    function Pay_Via_Mobile_Function() {
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Employee_ID = '<?php $Employee_ID; ?>';

        if (window.XMLHttpRequest) {
            myObjectGetDetails = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectGetDetails = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetDetails.overrideMimeType('text/xml');
        }

        myObjectGetDetails.onreadystatechange = function () {
            data29 = myObjectGetDetails.responseText;
            if (myObjectGetDetails.readyState == 4) {
                document.getElementById('ePayment_Area').innerHTML = data29;
                $("#ePayment_Window").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectGetDetails.open('GET', 'ePayment_Patient_Details_Prepare.php?Employee_ID=' + Employee_ID + '&Registration_ID=' + Registration_ID, true);
        myObjectGetDetails.send();
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

//      $('.ui-dialog-titlebar-close').click(function(){
//	 Get_Transaction_List();
//      });

    });
</script>
<script>
    $(document).ready(function () {
        $("#List_OF_Doctors").dialog({autoOpen: false, width: '30%', height: 350, title: 'DOCTORS LIST', modal: true});
    });
</script>
<script>
    $(document).ready(function () {
        $("#ePayment_Window").dialog({autoOpen: false, width: '45%', height: 230, title: 'Create ePayment Bill', modal: true});
    });
</script>
<!-- end of pop up window -->


<script>
    $(document).ready(function () {
        $("#ePayment_Window").dialog({autoOpen: false, width: '55%', height: 250, title: 'Create ePayment Bill', modal: true});
    });
</script>
<!-- end of pop up window -->

<!-- <script type="text/javascript">
    function Create_ePayment_Bill(){
        var Payment_Mode = document.getElementById("Payment_Mode").value;
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Amount = document.getElementById("Amount_Required").value;
        if(Amount <= 0 || Amount == null || Amount == '' || Amount == '0'){
            alert("Process Fail! You can not prepare ePayment bill with zero amount");
        }else{
            if(Payment_Mode != null && Payment_Mode != ''){
                if(Payment_Mode == 'CRDB Bank'){
                    var Confirm_Message = confirm("Are you sure you want to create CRDB eBILL?");
                    if (Confirm_Message == true) {
                        document.location = 'crdb_transaction_prepare.php?Registration_ID='+Registration_ID;
                    }
                }else if(Payment_Mode == 'Airtel Money'){
                    var Confirm_Message = confirm("Are you sure you want to create Airtel eBILL?");
                    if (Confirm_Message == true) {
                        document.location = "#";
                    }
                } 
            }else{
                document.getElementById("Payment_Mode").focus();
                document.getElementById("Payment_Mode").style = 'border: 3px solid red';
            }
        }
    }
</script>-->

<script type="text/javascript">
    function Verify_ePayment_Bill() {
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Employee_ID = '<?php echo $Employee_ID; ?>';

        if (window.XMLHttpRequest) {
            myObjectVerify = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectVerify = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectVerify.overrideMimeType('text/xml');
        }
        myObjectVerify.onreadystatechange = function () {
            data2912 = myObjectVerify.responseText;
            if (myObjectVerify.readyState == 4) {
                var feedback = data2912;
                if (feedback == 'yes') {
                    Create_ePayment_Bill();
                } else {
                    alert("You are not allowed to create transaction with zero price or zero quantity. Please remove those items to proceed");
                }
            }
        }; //specify name of function that will handle server response........

        myObjectVerify.open('GET', 'Verify_ePayment_Bill.php?Registration_ID=' + Registration_ID + '&Employee_ID=' + Employee_ID, true);
        myObjectVerify.send();
    }
</script>

<script type="text/javascript">
    function Create_ePayment_Bill() {
        var Payment_Mode = document.getElementById("Payment_Mode").value;
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Amount = document.getElementById("Amount_Required").value;
        if (Amount <= 0 || Amount == null || Amount == '' || Amount == '0') {
            alert("Process Fail! You can not prepare a bill with zero amount");
        } else {
            if (Payment_Mode != null && Payment_Mode != '') {
                if (Payment_Mode == 'Bank_Payment') {
                    var Confirm_Message = confirm("Are you sure you want to create Bank Payment BILL?");
                    if (Confirm_Message == true) {
                        //document.location = 'Prepare_Bank_Payment_Transaction.php?Registration_ID='+Registration_ID;
                        document.location = 'crdb_transaction_prepare.php?Section=Reception&Registration_ID=' + Registration_ID;
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
<script>
    $(document).ready(function () {
        $("select").select2();
    });
</script>

<?php
include("./includes/footer.php");
?>