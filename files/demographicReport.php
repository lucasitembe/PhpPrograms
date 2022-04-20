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

        mm.onreadystatechange = AJAXP; //specify name of function that will handle server response....
        mm.open('GET', 'getDistrictsList.php?Region_ID=' + Region_ID, true);
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
            <tr>
                <td colspan="4">&nbsp;</td>
                <td colspan="4" style="text-align: center;">AGE RANGE</td>
                <td colspan="4" style="text-align: center;">VISIT RANGE</td>
            </tr>
            <tr>
                <td width="5%">Region</td>
                <td width="">
                    <select name='Region_ID' id='Region_ID' onchange='getDistrictsList(this.value)'>
                        <option selected='selected' value='0'>All</option>
                        <?php
                        $data = mysqli_query($conn,"select * from tbl_regions");
                        while ($row = mysqli_fetch_array($data)) {
                            ?>
                            <option value='<?php echo $row['Region_ID']; ?>'>
                            <?php echo $row['Region_Name']; ?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
                <td width="5%">District</td>
                <td width="">
                    <select name='District_ID' id='District_ID'>
                        <option selected='selected' value='0'>All</option>		
                    </select>
                </td>
                <td width="5%" style="text-align: right;">From</td>
                <td width="15%">
                    <input type="text" name="ageFrom" id="ageFrom" style="text-align: center;" required="required" autocomplete='off'>
                </td>
                <td width="" style="text-align: right;">To</td>
                <td width="15%">
                    <input type="text" name="ageTo" id="ageTo" style="text-align: center;" required="required" autocomplete='off' style="text-align: center;">
                </td>
                <td width="" style="text-align: right;">From</td>
                <td width="15%">
                    <input type='text' name='Date_From' id='date_From' style="text-align: center;" required='required' autocomplete='off' style="text-align: center;">
                </td>
                <td width="" style="text-align: right;">To</td>
                <td width="15%">
                    <input type='text' name='Date_To' id='date_To' style="text-align: center;" required='required' autocomplete='off'>
                </td>
                <td width="10%" style="text-align: center;">
                    <input type='button' name='Print_Filter' id='Print_Filter' class='art-button-green' value='FILTER' onclick="Filter_Patients()">
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset style='overflow-y: scroll; height: 305px; background-color:white' id='Patient_List'>
        <legend align="right" style="background-color:#006400;color:white;padding:5px;" ><b>DEMOGRAPHIC REPORT (NEW VISITS)</b></legend>
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
            <td style='text-align:right;width:100%;' id="Graph_Button_Area">
                <input type="button" class="art-button-green" value="PREVIEW GRAPHS" onclick="Preview_Graphs()">
                <!-- <a href="demographicFilterMultipleBarChart.php?Region_ID=<?php echo $regionID ?>&District_ID=<?php echo $districtID ?>&ageFrom=<?php echo $ageFrom ?>&ageTo=<?php echo $ageTo ?>&Date_From=<?php echo $Date_From ?>&Date_To=<?php echo $Date_To ?>">
                        <input type='submit' name='graph' id='praph' class='art-button-green' value='GRAPHS'>
                </a> -->
            </td>
            <td style='text-align:right;width:100%;' id="Preview_Button_Area">
                <input type="button" class="art-button-green" value="PREVIEW REPORT" onclick="Preview_Report()">
                <!-- <a href="demographicpdfreport.php?Region_ID=<?php echo $regionID ?>&District_ID=<?php echo $districtID ?>&ageFrom=<?php echo $ageFrom ?>&ageTo=<?php echo $ageTo ?>&Date_From=<?php echo $Date_From ?>&Date_To=<?php echo $Date_To ?>" class="art-button-green" target="_blank">
                        PREVIEW ALL
                </a> -->
            </td>
        </tr>
    </table>
    <script type="text/javascript">
        function Preview_Report() {
            var Region_ID = document.getElementById("Region_ID").value;
            var District_ID = document.getElementById("District_ID").value;
            var ageFrom = document.getElementById("ageFrom").value;
            var ageTo = document.getElementById("ageTo").value;
            var date_From = document.getElementById("date_From").value;
            var date_To = document.getElementById("date_To").value;

            if (ageTo == null || ageTo == '' || ageFrom == null || ageFrom == '' || date_From == null || date_From == '' || date_To == null || date_To == '') {
                if (ageTo == null || ageTo == '') {
                    document.getElementById("ageTo").focus();
                    document.getElementById("ageTo").style = 'border: 3px solid red';
                } else {
                    document.getElementById("ageTo").style = 'border: 3px white';
                }

                if (ageFrom == null || ageFrom == '') {
                    document.getElementById("ageFrom").focus();
                    document.getElementById("ageFrom").style = 'border: 3px solid red';
                } else {
                    document.getElementById("ageFrom").style = 'border: 3px white';
                }

                if (date_From == null || date_From == '') {
                    document.getElementById("date_From").focus();
                    document.getElementById("date_From").style = 'border: 3px solid red';
                } else {
                    document.getElementById("date_From").style = 'border: 3px white';
                }

                if (date_To == null || date_To == '') {
                    document.getElementById("date_To").focus();
                    document.getElementById("date_To").style = 'border: 3px solid red';
                } else {
                    document.getElementById("date_To").style = 'border: 3px white';
                }
            } else {
                document.getElementById("ageTo").style = 'border: 3px white';
                document.getElementById("ageFrom").style = 'border: 3px white';
                document.getElementById("date_From").style = 'border: 3px white';
                document.getElementById("date_To").style = 'border: 3px white';
                window.open('demographicpdfreport.php?Region_ID=' + Region_ID + '&District_ID=' + District_ID + '&ageFrom=' + ageFrom + '&ageTo=' + ageTo + '&Date_From=' + date_From + '&Date_To=' + date_To, '_blank');
            }
        }
    </script>

    <script type="text/javascript">
        function Preview_Graphs() {
            var Region_ID = document.getElementById("Region_ID").value;
            var District_ID = document.getElementById("District_ID").value;
            var ageFrom = document.getElementById("ageFrom").value;
            var ageTo = document.getElementById("ageTo").value;
            var date_From = document.getElementById("date_From").value;
            var date_To = document.getElementById("date_To").value;

            if (ageTo == null || ageTo == '' || ageFrom == null || ageFrom == '' || date_From == null || date_From == '' || date_To == null || date_To == '') {
                if (ageTo == null || ageTo == '') {
                    document.getElementById("ageTo").focus();
                    document.getElementById("ageTo").style = 'border: 3px solid red';
                } else {
                    document.getElementById("ageTo").style = 'border: 3px white';
                }

                if (ageFrom == null || ageFrom == '') {
                    document.getElementById("ageFrom").focus();
                    document.getElementById("ageFrom").style = 'border: 3px solid red';
                } else {
                    document.getElementById("ageFrom").style = 'border: 3px white';
                }

                if (date_From == null || date_From == '') {
                    document.getElementById("date_From").focus();
                    document.getElementById("date_From").style = 'border: 3px solid red';
                } else {
                    document.getElementById("date_From").style = 'border: 3px white';
                }

                if (date_To == null || date_To == '') {
                    document.getElementById("date_To").focus();
                    document.getElementById("date_To").style = 'border: 3px solid red';
                } else {
                    document.getElementById("date_To").style = 'border: 3px white';
                }
            } else {
                document.getElementById("ageTo").style = 'border: 3px white';
                document.getElementById("ageFrom").style = 'border: 3px white';
                document.getElementById("date_From").style = 'border: 3px white';
                document.getElementById("date_To").style = 'border: 3px white';
                document.location = 'demographicFilterMultipleBarChart.php?Region_ID=' + Region_ID + '&District_ID=' + District_ID + '&ageFrom=' + ageFrom + '&ageTo=' + ageTo + '&Date_From=' + date_From + '&Date_To=' + date_To;
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

            //alert("here");
            //myObject.open('GET','Demographic_Report_New_Visits.php?Region_ID='+Region_ID+'&District_ID='+District_ID+'&Date_From='+Date_From+'&Date_To='+Date_To+'&Age_From='+Age_From+'&Age_To='+Age_To,true);
            myObject.open('GET', 'Demographic_Report_New_Visits.php?Region_ID=' + Region_ID + '&District_ID=' + District_ID + '&Date_From=' + Date_From + '&Date_To=' + Date_To + '&Age_From=' + Age_From + '&Age_To=' + Age_To, true);
            myObject.send();
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
        $('#date_From').datetimepicker({value: '', step: 30});
        $('#date_To').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            startDate: 'now'
        });
        $('#date_To').datetimepicker({value: '', step: 30});
    </script>
    <!--End datetimepicker-->

    <?php
    include("./includes/footer.php");
    ?>