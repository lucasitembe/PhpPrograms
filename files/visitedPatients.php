<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Reception_Works'])) {
        if ($_SESSION['userinfo']['Reception_Works'] != 'yes') {

            if (isset($_SESSION['userinfo']['Management_Works']) && $_SESSION['userinfo']['Management_Works'] != 'yes') {
                header("Location: ./index.php?InvalidPrivilege=yes");
            }
        }
    } elseif (isset($_SESSION['userinfo']['Management_Works'])) {
        if ($_SESSION['userinfo']['Management_Works'] != 'yes') {

            if (isset($_SESSION['userinfo']['Reception_Works']) && $_SESSION['userinfo']['Reception_Works'] != 'yes') {
                header("Location: ./index.php?InvalidPrivilege=yes");
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
<script type="text/javascript" language="javascript">
    function getDistrictsList(Region_ID) {
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }
        var byname ='districtbyname';
        mm.onreadystatechange = AJAXP; //specify name of function that will handle server response....
        mm.open('GET', 'getDistrictsList.php?Region_IDs=' + Region_ID+'&districtname='+byname, true);
        mm.send();
    }
    function AJAXP() {
        var data = mm.responseText;
        document.getElementById('District_ID').innerHTML = data;
    }

//    function to verify NHIF STATUS
    function nhifVerify() {
        //code
    }
</script>
<?php
if (isset($_GET['Section'])) {
    $Section = $_GET['Section'];
} else {
    $Section = '';
}
$selectemployee = mysqli_query($conn, "SELECT Employee_Name, p.Employee_ID FROM tbl_employee e, tbl_privileges p WHERE Reception_Works='yes' AND e.Employee_ID = p.Employee_ID AND Account_Status='Active'") or die(mysqli_error($conn));

?>

<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Reception_Works'] == 'yes') {
        ?>
        <a href='./receptionReports.php?Section=<?php echo $Section;?>&Reception=ReceptionThisPage' class='art-button-green'>
            BACK
        </a>
    <?php }
} ?>

<br/><br/>
<center>
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
    <fieldset>
    <table width="100%">
            <!-- <tr>
                <td colspan="4">&nbsp;</td>                
                <td colspan="8" style="text-align: center;">VISIT RANGE</td>
                <td colspan="4" style="text-align: center;">AGE RANGE</td>
            </tr> -->
            <tr>
                
                <td width='10%'>
                <select name="" id="Employee_ID" style="text-align:center;">
                    <option value="">~~select Employee~~</option>
                    <?php while($row = mysqli_fetch_assoc($selectemployee)){
                        $Employee_ID = $row['Employee_ID'];
                        $Employee_Name = $row['Employee_Name'];
                        echo "<option value='$Employee_ID'>$Employee_Name</option>";
                    } 
                    ?>
                </select>
                    <?php 
                    
                    ?>
                </td>
                <td width="5%">Region</td>
                <td width="">
                    <select name='Region_ID' id='Region_ID' onchange='getDistrictsList(this.value)'>
                        <option selected='selected' value='All'>All</option>
                        <?php
                        $data = mysqli_query($conn,"select * from tbl_regions");
                        while ($row = mysqli_fetch_array($data)) {
                            ?>
                            <option value='<?php echo $row['Region_Name']; ?>'>
                            <?php echo $row['Region_Name']; ?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
                <td width="5%">District</td>
                <td width="">
                    <select name='District_ID'  id='District_ID'>
                        <option selected='selected' value='All'>All</option>		
                    </select>
                </td>
                
                <td width="14%">
                    <input type='text' name='Date_From' id='date_From'placeholder='Start Date'  style="text-align: center;" required='required' autocomplete='off' style="text-align: center;">
                </td>
                
                <td width="14%">
                    <input type='text' name='Date_To' id='date_To' placeholder='End Date'  style="text-align: center;" required='required' autocomplete='off'>
                </td>
                
                <td width='10%'>
                                               
                    <select style="width: 100%;"  id="Type_Of_Check_In" class="foropd">
                        <option value="All">Check in Type</option>
                        <option value="Afresh">New</option>
                        <option value="Continuous">Return</option>
                    </select>
                </td>
                <td width='5%'>
                    <select name="" id="Clinic_ID"  >
                        <option value="All"> ~~Select CLinic~~</option>
                        <?php
                            $select_clinic = mysqli_query($conn, "SELECT Clinic_name, Clinic_ID FROM tbl_clinic") or die(mysqli_error($conn));
                            while($rw = mysqli_fetch_assoc($select_clinic)){
                                $Clinic_Name = $rw['Clinic_name'];
                                $Clinic_ID = $rw['Clinic_ID'];
                                echo "<option value='$Clinic_ID'>$Clinic_Name</option>";
                            }
                        ?>
                    </select>
                </td>
                <td width='10%'>
                    
                    <select style="height:100%"   required="" name="visit_type" id="visit_type">
                        <option value="All">~~Visit Type~~</option>
                        <option value="All">All</option>
                        <option value="1">Routine</option>
                        <option value="2">Emergency</option>
                        <option value="4">Self Referral</option>
                        <option value="5">Start</option>
                        <option value="3">Referral</option>
                    </select>
                </td>
                
                <td width="7%">
                    <input type="text" name="ageFrom" id="ageFrom"  placeholder="Age From " style="text-align: center;" required="required" autocomplete='off'>
                </td>
                
                <td width="7%">
                    <input type="text" name="ageTo" id="ageTo"  placeholder="Age To" style="text-align: center;" required="required" autocomplete='off' style="text-align: center;">
                </td>
                <td style="text-align: right;" width='8%'>
                    <select id='agetype' style='text-align:center;padding:4px; width:100%;display:inline'>
                        <option value='YEAR'>Year</option>
                        <option value='MONTH'>Month</option>
                        <option value='DAY'>Days</option>
                    </select>
                </td>

                <td width="10%" style="text-align: center;">
                    <input type='button' name='Print_Filter' id='Print_Filter' class='art-button-green' value='FILTER' onclick="Filter_Patients()">
                </td>
            </tr>
        </table>
    </fieldset>
    <div id="progressbar"></div>
    <fieldset style='overflow-y: scroll; height: 550px; background-color:white' id='Patient_List'>
        <legend align="center" style="background-color:#006400;color:white;padding:5px;" ><b>DEMOGRAPHIC VISITS BY SPONSOR  REPORT </b></legend>
        <table width =100% style="border: 0">
            <tr>
                <td style='text-align:left; width:3%;border: 1px #ccc solid;'><b>SN</b></td>
                <td style='text-align:left; width:3%;border: 1px #ccc solid;'><b>SPONSOR NAME</b></td>
                <td style='text-align:right; width:3%;border: 1px #ccc solid;'><b>MALE</b></td>
                <td style='text-align:right; width:3%;border: 1px #ccc solid;'><b>FEMALE</b></td>
                <td style='text-align:right; width:3%;border: 1px #ccc solid;'><b>TOTAL</b></td>
            </tr>
            <tr><td colspan=5 style='border: 1px #ccc solid'><hr></td></tr>
            <?php
            $temp = 0;
            $select = mysqli_query($conn,"select Guarantor_Name from tbl_sponsor order by Guarantor_Name") or die(mysqli_error($conn));
            $num = mysqli_num_rows($select);
            if ($num > 0) {
                while ($data = mysqli_fetch_array($select)) {
                    ?>
                    <tr>
                        <td><?php echo ++$temp; ?></td>
                        <td><?php echo $data['Guarantor_Name']; ?></td>
                        <td style="text-align: right;">0</td>
                        <td style="text-align: right;">0</td>
                        <td style="text-align: right;">0</td>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>
    </fieldset>
    <table width="100%" style="twxt-align:right;">
        <tr>
            <td style="text-align: center;" width="78%"><b><span style='color: #037CB0;'><i>Click sponsor name to view details</i></span></b></td>
            <td style='text-align:right;' id="Graph_Button_Area">
                <!-- <input type="button" class="art-button-green" value="PREVIEW GRAPHS" onclick="Preview_Graphs()"> -->
                <!-- <a href="demographicFilterMultipleBarChart.php?Region_ID=<?php echo $regionID ?>&District_ID=<?php echo $districtID ?>&ageFrom=<?php echo $ageFrom ?>&ageTo=<?php echo $ageTo ?>&Date_From=<?php echo $Date_From ?>&Date_To=<?php echo $Date_To ?>">
                        <input type='submit' name='graph' id='praph' class='art-button-green' value='GRAPHS'>
                </a> -->
            </td>
            <td style='text-align:right;' id="Preview_Button_Area">
                <input type="button" class="art-button-green" value="PREVIEW REPORT" onclick="Preview_Report()">
                <!-- <a href="demographicpdfreport.php?Region_ID=<?php echo $regionID ?>&District_ID=<?php echo $districtID ?>&ageFrom=<?php echo $ageFrom ?>&ageTo=<?php echo $ageTo ?>&Date_From=<?php echo $Date_From ?>&Date_To=<?php echo $Date_To ?>" class="art-button-green" target="_blank">
                        PREVIEW ALL
                </a> -->
            </td>
        </tr>
    </table>

    <!--popup window -->
    <div id="D_Details" style="width:80%;" >
        <center id='Patients_Details'>
            <table width=100% style='border-style: none;'>
                <tr>
                    <td>

                    </td>
                </tr>
            </table>
        </center>
    </div>

    <script type="text/javascript">
        function Preview_Report() {
            var Region_ID = document.getElementById("Region_ID").value;
            var District_ID = document.getElementById("District_ID").value;
            var ageFrom = document.getElementById("ageFrom").value;
            var ageTo = document.getElementById("ageTo").value;
            var date_From = document.getElementById("date_From").value;
            var date_To = document.getElementById("date_To").value;
            var agetype = document.getElementById("agetype").value;
            var visit_type = document.getElementById("visit_type").value;
            var Type_Of_Check_In = document.getElementById("Type_Of_Check_In").value;
            var Employee_ID = document.getElementById("Employee_ID").value;
            var Clinic_ID = $("#Clinic_ID").val();
            if (ageTo == null || ageTo == '' || ageFrom == null || ageFrom == '' || date_From == null || date_From == '' || date_To == null || date_To == '') {
                if (ageTo == null || ageTo == '') {
                    document.getElementById("ageTo").focus();
                    document.getElementById("ageTo").style = 'border: 3px solid red; text-align: center;';
                } else {
                    document.getElementById("ageTo").style = 'border: 3px white; text-align: center;';
                }

                if (ageFrom == null || ageFrom == '') {
                    document.getElementById("ageFrom").focus();
                    document.getElementById("ageFrom").style = 'border: 3px solid red; text-align: center;';
                } else {
                    document.getElementById("ageFrom").style = 'border: 3px white; text-align: center;';
                }

                if (date_From == null || date_From == '') {
                    document.getElementById("date_From").focus();
                    document.getElementById("date_From").style = 'border: 3px solid red; text-align: center;';
                } else {
                    document.getElementById("date_From").style = 'border: 3px white; text-align: center;';
                }

                if (date_To == null || date_To == '') {
                    document.getElementById("date_To").focus();
                    document.getElementById("date_To").style = 'border: 3px solid red; text-align: center;';
                } else {
                    document.getElementById("date_To").style = 'border: 3px white; text-align: center;';
                }
            } else {
                document.getElementById("ageTo").style = 'border: 3px white';
                document.getElementById("ageFrom").style = 'border: 3px white';
                document.getElementById("date_From").style = 'border: 3px white';
                document.getElementById("date_To").style = 'border: 3px white';
                window.open('demographicallvisitsreport.php?Region_ID=' + Region_ID + '&District_ID=' + District_ID + '&ageFrom=' + ageFrom + '&ageTo=' + ageTo + '&Date_From=' + date_From + '&Date_To=' + date_To+'&agetype=' + agetype + '&visit_type=' + visit_type + '&Type_Of_Check_In=' + Type_Of_Check_In+'&Employee_ID'+Employee_ID+'&Clinic_ID='+Clinic_ID, '_blank');
            }
        }
    </script>

    <script type="text/javascript">
        function Preview_Graphs() {
            var Region_ID = document.getElementById("Region_ID").value;
            var District_ID = document.getElementById("District_ID").value;
            var Age_From = document.getElementById("ageFrom").value;
            var Age_To = document.getElementById("ageTo").value;
            var date_From = document.getElementById("date_From").value;
            var date_To = document.getElementById("date_To").value;
            var agetype = document.getElementById("agetype").value;
            var visit_type = document.getElementById("visit_type").value;
            var Type_Of_Check_In = document.getElementById("Type_Of_Check_In").value;
            var Employee_ID = document.getElementById("Employee_ID").value;
            var Clinic_ID = $("#Clinic_ID").val();
            if (ageTo == null || ageTo == '' || ageFrom == null || ageFrom == '' || date_From == null || date_From == '' || date_To == null || date_To == '') {
                if (ageTo == null || ageTo == '') {
                    document.getElementById("ageTo").focus();
                    document.getElementById("ageTo").style = 'border: 3px solid red; text-align: center;';
                } else {
                    document.getElementById("ageTo").style = 'border: 3px white; text-align: center;';
                }

                if (ageFrom == null || ageFrom == '') {
                    document.getElementById("ageFrom").focus();
                    document.getElementById("ageFrom").style = 'border: 3px solid red; text-align: center;';
                } else {
                    document.getElementById("ageFrom").style = 'border: 3px white; text-align: center;';
                }

                if (date_From == null || date_From == '') {
                    document.getElementById("date_From").focus();
                    document.getElementById("date_From").style = 'border: 3px solid red; text-align: center;';
                } else {
                    document.getElementById("date_From").style = 'border: 3px white; text-align: center;';
                }

                if (date_To == null || date_To == '') {
                    document.getElementById("date_To").focus();
                    document.getElementById("date_To").style = 'border: 3px solid red; text-align: center;';
                } else {
                    document.getElementById("date_To").style = 'border: 3px white; text-align: center;';
                }
            } else {
                document.getElementById("ageTo").style = 'border: 3px white';
                document.getElementById("ageFrom").style = 'border: 3px white';
                document.getElementById("date_From").style = 'border: 3px white';
                document.getElementById("date_To").style = 'border: 3px white';
                document.location = 'visitedPatientsFilterMultipleBarChart.php?branchID=' + Branch_ID + '&Region_ID=' + Region_ID + '&District_ID=' + District_ID + '&ageTo=' + ageTo + '&Date_From=' + date_From + '&Date_To=' + date_To+ '&Age_To=' + Age_To+ '&agetype=' + agetype + '&visit_type=' + visit_type + '&Type_Of_Check_In=' + Type_Of_Check_In+'&Employee_ID'+Employee_ID+'&Clinic_ID='+Clinic_ID;
            }
        }
    </script>
    <script type="text/javascript">
        function Filter_Patients() {
            var Region_ID = document.getElementById("Region_ID").value;
            var District_ID = document.getElementById("District_ID").value;
            var Date_From = document.getElementById("date_From").value;
            var Date_To = document.getElementById("date_To").value;
            var Age_From = document.getElementById("ageFrom").value;
            var Age_To = document.getElementById("ageTo").value;
            var agetype = document.getElementById("agetype").value;
            var visit_type = document.getElementById("visit_type").value;
            var Type_Of_Check_In = document.getElementById("Type_Of_Check_In").value;
            var Employee_ID = document.getElementById("Employee_ID").value;
            var Clinic_ID = $("#Clinic_ID").val();
            // alert(agetype+'-->'+visit_type+'=='+Type_Of_Check_In); exit();
           
            if (Age_To == null || Age_To == '' || Age_From == null || Age_From == '' || Date_From == null || Date_From == '' || Date_To == null || Date_To == '') {
                if (Age_To == null || Age_To == '') {
                    //document.getElementById("ageTo").focus();
                    document.getElementById("ageTo").style = 'border: 3px solid red; text-align: center;';
                } else {
                    document.getElementById("ageTo").style = 'border: 3px white; text-align: center;';
                }

                if (Age_From == null || Age_From == '') {
                    //document.getElementById("ageFrom").focus();
                    document.getElementById("ageFrom").style = 'border: 3px solid red; text-align: center;';
                } else {
                    document.getElementById("ageFrom").style = 'border: 3px white; text-align: center;';
                }

                if (Date_From == null || Date_From == '') {
                    //document.getElementById("date_From").focus();
                    document.getElementById("date_From").style = 'border: 3px solid red; text-align: center;';
                } else {
                    document.getElementById("date_From").style = 'border: 3px white; text-align: center;';
                }

                if (Date_To == null || Date_To == '') {
                    //document.getElementById("date_To").focus();
                    document.getElementById("date_To").style = 'border: 3px solid red; text-align: center;';
                } else {
                    document.getElementById("date_To").style = 'border: 3px white; text-align: center;';
                }
            } else {
                
                if (window.XMLHttpRequest) {
                    myObject = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObject.overrideMimeType('text/xml');
                }

                myObject.onreadystatechange = function () {
                    data = myObject.responseText;
                    if (myObject.readyState == 4) {
                        //alert(data);
                        document.getElementById('Patient_List').innerHTML = data;
                    }
                }; //specify name of function that will handle server response........
                // document.getElementById('progressbar').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
                $('#Patient_List').html('<div align="center" style="display:block;" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>');
                myObject.open('GET', 'Demographic_Report_All_Visits.php?Region_ID=' + Region_ID + '&District_ID=' + District_ID + '&Date_From=' + Date_From + '&Date_To=' + Date_To + '&Age_From=' + Age_From + '&Age_To=' + Age_To+ '&agetype=' + agetype + '&visit_type=' + visit_type + '&Type_Of_Check_In=' + Type_Of_Check_In+'&Employee_ID='+Employee_ID+'&Clinic_ID='+Clinic_ID, true);
                myObject.send();
                document.getElementById('progressbar').innerHTML = "";
            }
        }
    </script>

    <script src="css/jquery.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>


    <script>
        $('#date_From').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            startDate: 'now'
        });
        $('#date_From').datetimepicker({value: '', step: 01});
        $('#date_To').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            startDate: 'now'
        });
        $('#date_To').datetimepicker({value: '', step: 01});
    </script>
    <!--End datetimepicker-->

    <script type="text/javascript">
        function Display_Details(Region_ID, District_ID, Date_From, Date_To, Age_From, Age_To,visit_type,Type_Of_Check_In,Employee_ID, Sponsor_ID, Clinic_ID) {
            var agetype = document.getElementById("agetype").value;
            if (window.XMLHttpRequest) {
                myObjectDisplayDetails = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectDisplayDetails = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectDisplayDetails.overrideMimeType('text/xml');
            }

            myObjectDisplayDetails.onreadystatechange = function () {
                data779 = myObjectDisplayDetails.responseText;
                if (myObjectDisplayDetails.readyState == 4) {
                    document.getElementById('Patients_Details').innerHTML = data779;
                    $("#D_Details").dialog("open");
                }
            }; //specify name of function that will handle server response........
            myObjectDisplayDetails.open('GET', 'Visited_Patients_Display_Details.php?Sponsor_ID=' + Sponsor_ID + '&Date_From=' + Date_From + '&Date_To=' + Date_To + '&District_ID=' + District_ID + '&Region_ID='+Region_ID+ '&Age_From=' + Age_From + '&Age_To=' + Age_To+ '&agetype=' + agetype + '&visit_type=' + visit_type + '&Type_Of_Check_In=' + Type_Of_Check_In+'&Employee_ID'+Employee_ID+'&Clinic_ID='+Clinic_ID, true);
            myObjectDisplayDetails.send();
        }
    </script>   
    <script src="js/jquery-1.8.0.min.js"></script>
    <script src="js/jquery-ui-1.8.23.custom.min.js"></script>
    <link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">

    <script>
        $(document).ready(function () {
            $("#D_Details").dialog({autoOpen: false, width: '80%', height: 600, title: 'DEMOGRAPHIC REPORT DETAILS - BY SPONSOR', modal: true});
        });
    </script>
    <script type="text/javascript">
        function Preview_Details_Report(Region_ID, District_ID, Date_From, Date_To, Age_From, Age_To,visit_type,Type_Of_Check_In, Sponsor_ID, Clinic_ID) {
            var agetype = document.getElementById("agetype").value;
            var Employee_ID = document.getElementById("Employee_ID").value;
            if (Age_To == null || Age_To == '' || Age_From == null || Age_From == '' || Date_From == null || Date_From == '' || Date_To == null || Date_To == '') {
                if (Age_To == null || Age_To == '') {
                    document.getElementById("ageTo").focus();
                    document.getElementById("ageTo").style = 'border: 3px solid red; text-align: center;';
                } else {
                    document.getElementById("ageTo").style = 'border: 3px white; text-align: center;';
                }

                if (Age_From == null || Age_From == '') {
                    document.getElementById("ageFrom").focus();
                    document.getElementById("ageFrom").style = 'border: 3px solid red; text-align: center;';
                } else {
                    document.getElementById("ageFrom").style = 'border: 3px white; text-align: center;';
                }

                if (Date_From == null || Date_From == '') {
                    document.getElementById("date_From").focus();
                    document.getElementById("date_From").style = 'border: 3px solid red; text-align: center;';
                } else {
                    document.getElementById("date_From").style = 'border: 3px white; text-align: center;';
                }

                if (Date_To == null || Date_To == '') {
                    document.getElementById("date_To").focus();
                    document.getElementById("date_To").style = 'border: 3px solid red; text-align: center;';
                } else {
                    document.getElementById("date_To").style = 'border: 3px white; text-align: center;';
                }
            } else {
                document.getElementById("ageTo").style = 'border: 3px white';
                document.getElementById("ageFrom").style = 'border: 3px white';
                document.getElementById("date_From").style = 'border: 3px white';
                document.getElementById("date_To").style = 'border: 3px white';
                window.open('demographicreportdetailsall.php?Region_ID=' +Region_ID+ '&District_ID=' + District_ID + '&Age_From=' + Age_From + '&Age_To=' + Age_To + '&Date_From=' + Date_From + '&Date_To=' + Date_To + '&Sponsor_ID=' + Sponsor_ID+ '&agetype=' + agetype + '&visit_type=' + visit_type + '&Type_Of_Check_In=' + Type_Of_Check_In+'&Employee_ID'+Employee_ID+'&Clinic_ID='+Clinic_ID, '_blank');
            }
        }
    </script>
    <?php
    include("./includes/footer.php");
    ?>