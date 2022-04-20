<?php
include("./includes/connection.php");
include("./includes/header.php");
// include("../signature/index.php");
echo "<a href='procedureconcertform.php' class='art-button-green'>BACK</a>";

session_start();
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
    $select_Patient = mysqli_query($conn, "SELECT * FROM tbl_patient_registration WHERE   Registration_ID = '$Registration_ID'");
    while ($row = mysqli_fetch_array($select_Patient)) {
        $Patient_Name = ucwords(strtolower($row['Patient_Name']));
        //     $Date_Of_Birth = $row['Date_Of_Birth'];
        $Gender = $row['Gender'];
        $date1 = new DateTime($Today);
        $date2 = new DateTime($row['Date_Of_Birth']);
        $diff = $date1->diff($date2);
        $age = " Miaka " . $diff->y;
        $age .= ", Miezi " . $diff->m;
        $age .= ", Na Siku " . $diff->d;

        if ($Gender == 'Male') {
            $Gender = 'Mwanaume';
        } elseif ($Gender == 'Female') {
            $Gender = 'Mwanamke';
        }
    }

    $date2 = date('d, D, M, Y');
    $time = date('h:m:s');

    // die("SELECT * FROM tbl_consert_forms_details WHERE Registration_ID = '$Registration_ID'");
    $select_conset_detail = mysqli_query($conn, "SELECT * FROM tbl_consert_forms_details WHERE Registration_ID = '$Registration_ID'");
    $datazangu = ($select_conset_detail > 0);
    if ($datazangu) {
        while ($row = mysqli_fetch_array($select_conset_detail)) {
            // `PROCEDURES`, `REPRESENTATIVE`, `WITNESS_NAME`, `DOCTOR`, `DATE_SIGNED`,
            $doctorname = $row['doctor_ID'];
            $next_of_kin = $row['next_of_kin'];
            $phone = $row['phone'];
            $designation = $row['designation'];
            $on_behalf_name = $row['on_behalf_name'];
            $Amptutation_of = $row['Amptutation_of'];
            $Language = $row['Language'];
            $Responsible_dr = $row['Responsible_dr'];
            $patient_witness = $row['patient_witness'];
            $photography_on_surgery = $row['photography_on_surgery'];
            $presense_of_students = $row['presense_of_students'];
            $consent_amputation = $row['consent_amputation'];
            $Consent_ID = $row['Consent_ID'];
            $procedure_taken = $row['procedure_taken'];
            $Consent_ID = $row['Consent_ID'];
            $relation = $row['relation'];
            $maelekezo = $row['maelekezo'];

            $surgeon = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$doctorname'"))['Employee_Name'];
            $surgeon2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Responsible_dr'"))['Employee_Name'];
        }
    }
}


if(isset($_GET['Product_Name'])) {
$Product_Name = $_GET['Product_Name'];
}
if (isset($_GET['consultation_id'])) {
$consultation_id = $_GET['consultation_id'];
}
if (isset($_GET['Payment_Item_Cache_List_ID'])) {
$Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
}

$Current_Employee_ID = $_SESSION['userinfo']['Employee_ID'];
$Current_Employee_Name = $_SESSION['userinfo']['Employee_Name'];

$select_Filtered_Doctors = mysqli_query(
$conn,
"SELECT * from tbl_employee where
Employee_Type = 'doctor' order by employee_name"
) or die(mysqli_error($conn));
while ($row = mysqli_fetch_array($select_Filtered_Doctors)) {
$select .= "
<option value=" . $row['Employee_ID'] . "> Dr. " . $row['Employee_Name'] . " </option>
";
}






// else{
// echo "<input type='text' style='width: 700px; border-top: none; border-left: none; border-right: none; border-bottom: 2px solid #000;' value=".$Registration_ID.">";
// }

$msg = "
<tr>
    <td colspan='4' style='text-align:center;'>
        <font color='blue;'> <b> Sasa unaweza Kuchukua Saini wa Wahusika kwa ajili ya Ridhaa</b></font>
    </td>
</tr>
";

// header('location:print_dailysis_formpdf.php');
// $_SESSION['patient_id'] = $Registration_ID;


$doctor_button = "
<a target='_blank' href='../esign/employee_signature.php?Employee_ID=" . $doctorname . "&ChangeUserPassword=ChangeUserPasswordThisPage' class='art-button-green'>TAKE DOCTOR SIGNATURE</a>
";
$patient_button = "
<a target='_blank' href='../esign/signature.php?Registration_ID=" . $Registration_ID . " ' class='art-button-green'>TAKE PATIENT/GUARDIAN/PROXY SIGNATURE</a>

";
$witnes_button = "
<a target='_blank' href='../signaturesignatur/index.php?Registration_ID= " . $Registration_ID . " ' class='art-button-green'> TAKE WITNESS SIGNATURE</a>
";
$printbtn = "
<a target='_blank' href='printrtheater.php?Registration_ID=" . $Registration_ID . "' class='art-button-green'>PREVIEW AND PRINT</a>

";
?>
<!DOCTYPE html>
<html>

<head>
    <style type="text/css">
        /* .labefor{display:block;width: 100% }
    .labefor:hover{background-color: #a8d1ff;cursor: pointer}
    label.labefor { width: 100%;  */
        .rows_list {
            cursor: pointer;
        }

        .rows_list:active {
            color: #328CAF !important;
            font-weight: normal !important;
        }

        .rows_list:hover {
            color: #00416a;
            background: #dedede;
            font-weight: bold;
        }

        a {
            text-decoration: none;
        }

        input[type="checkbox"] {
            width: 25px;
            height: 25px;
            cursor: pointer;
            margin: 5px;
            margin-right: 5px;
        }

        input[type="radio"] {
            width: 25px;
            height: 25px;
            cursor: pointer;
            margin: 5px;
            margin-right: 5px;
        }

        #th {
            background: #99cad1;
        }

        #spu_lgn_tbl {
            width: 100%;
            border: none !important;
        }

        #spu_lgn_tbl tr,
        #spu_lgn_tbl tr td {
            border: none !important;
            padding: 5px;
            font-size: 16PX;
        }

        #spu_lgn_tbl tr,
        #spu_lgn_tbl tr th {
            border: none !important;
            padding: 5px;
            font-size: 18PX;
        }

        #spu_lgn_tbl {}

        * {
            font-size: 17px;
        }

        .button_container {
            width: 90%;
            border: 1px solid silver;
            margin: 10px;
        }

        .button_alignment {
            width: 33%;
            border: 1px solid silver;
            position: relative;
            display: inline-block;
            margin: auto;
        }

        #surgery_doctor {
            padding: 5px;
            border-top: none;
            font-weight: bold;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <center>
        <div class="button_container">
            <div class="button_alignment">
                <a href='procedure_concert_form.php?Registration_ID=<?= $Registration_ID; ?>&Product_Name=<?= $Product_Name; ?>&consultation_id=<?= $consultation_id; ?>&Payment_Item_Cache_List_ID=<?= $Payment_Item_Cache_List_ID ?>&PatientBilling=PatientBillingThisForm'>
                    <button style='width: 100%; height: 100%'>
                        ENGLISH VERSION
                    </button>
                </a>
            </div>
            <div class="button_alignment">
                <a href='#'>
                    <button style='width: 100%; height: 100%'>
                        TOLEO LA KISWAHILI
                    </button>
                </a>
            </div>
        </div>
        <br />
        <table class="table" id="spu_lgn_tbl" width='60%'>
            <tbody>
            </tbody>
        </table>
        <section style="width:79%; ">
            <fieldset>
                <form action="" method="post">
                    <legend align="center"><b>FOMU YA RIDHAA YA UPASUAJI/TIBA (CONSENT FORM)</b></legend>

                    <!-- <table   style="color:black;text-align:center; text; font-family:Times New Roman, Times, serif;font-size: 20px; border='0';"> -->

                    <table style="font-size:20px; border: none;" width=100%; border='0' id='spu_lgn_tbl'>
                        <?php if ($Consent_ID > 0) {
                            echo $msg;
                        } ?>
                        <tr>
                            <td colspan="4" style="text-align:center"><img src="./branchBanner/branchBanner.png"></td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align:center"><b>FOMU YA RIDHAA YA UPASUAJI/TIBA (CONSENT FORM)</b></td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align:center">Jina la Mgonjwa: <b> <?php echo $Patient_Name; ?></b>&nbsp;&nbsp;&nbsp;Umri: <b><?php echo $age; ?> </b> &nbsp;&nbsp;&nbsp; Jinsia: <b><?php echo $Gender; ?></b></td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align:center">Namba ya Faili: <b><?php echo $Registration_ID; ?></b></td>
                        </tr>
                    </table>

                    <table style="font-size:20px" width=90%; border="0" id='spu_lgn_tbl'>

                        <tr>
                            <td colspan="4">
                                <p>
                                    Ninatoa ridhaa/ruhusa kwa madaktari na/au kwa kushirikiana na wahudumu wengine ili kutoa tiba au kufanya upasuaji ufuatao amabo unahusu: <input type="text" class="text" name='Procedure_named' id='Procedure_named' value="<?php if ($Consent_ID > 0) {
                                                                                                                                                                                                                                                                    echo $procedure_taken;
                                                                                                                                                                                                                                                                } ?>" style='width: 700px; border-top: none; border-left: none; border-right: none; border-bottom: 2px solid #000;'>
                                <p>
                                    Namna na lengo la tiba/upasuaji limeelezwa vyema. Na zaidi faida, matokeo na madhara ya huo upasuaji, yameelezwa vyema kwangu na nimeelewa na kuridhia binafsi bila shuruti: <input type="text" class="text" name='maelekezo' id='maelekezo' value="<?php if ($Consent_ID > 0) {
                                                                                                                                                                                                                                                                                            echo $maelekezo;
                                                                                                                                                                                                                                                                                        } ?>" style='width: 700px; border-top: none; border-left: none; border-right: none; border-bottom: 2px solid #000;'>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <ol>
                                    <li>
                                        Ninafahamu katika upasuaji huu, halu inaweza kutokea inayolazimu upasuaji/tiba tofauti na ile iliyopendekezwa awali ikafanyika. Ninaruhusu tiba/upasuaji huo kufanyika kama itaonekana kuwa inafaa baada ya kuridhiwa na wataalamu wa upasuaji
                                    </li>
                                    <li>
                                        Ninaruhusu kupewa dawa ya usingizi (Anaesthesia) yaani nusu kaputi na timu husika ya wataalamu wa dawa za usingizi.
                                    </li>
                                    <li>
                                        Pia, nakiri kuwa faida na madhara ya dawa hizo za usingizi nimeelezwa kwa kina na nimeridhia binafsi baada ya kuelewa.
                                    </li>
                                    <li>
                                        Ninaruhusu kupewa damu ikiwa itaonekana ni muhimu na lazima kuongezewa.
                                    </li>
                                    <li>
                                        Ninakubaliana na matokeo yoyote ya tiba/upasuaji huu.
                                    </li>
                                    <li>
                                        Mgonjwa aelezwe kuwa BMC inatumika kwa kufundishia Wanafunzi wa fani ya afya.
                                    </li>
                                </ol>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan='4'>
                                <hr>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="4">
                                MAKUBALIANO YA RIDHAA
                            </th>
                        </tr>
                        <tr>
                            <td colspan='2' style='text-align: left;'><b>Uwepo wa wanafunzi wakati wa tiba/upasuaji:</b><br>
                                <input class="form_group" name="presense_of_students" value="Agree" type="radio" id="presense_of_students" <?php if ($presense_of_students == "Agree") {
                                                                                                                                                echo "checked";
                                                                                                                                            } ?>> Ninaruhusu
                                <input class="form_group" name="presense_of_students" value="Disagree" type="radio" id="presense_of_students" <?php if ($presense_of_students == "Disagree") {
                                                                                                                                                    echo "checked";
                                                                                                                                                } ?>> Siruhusu
                            </td>
                            <td colspan='2' style='text-align: left;'><b>Picha/Taarifa inayohusiana na Operesheni/Upasuaji/matibabu haya kutumika kwa tafiti zaidi na/au kufundishia ikiwa wataalam wataona inafaa kufanya hivyo bila shuruti na kwa usiri(Confidentiality) wa mgonjwa:</b><br>
                                <input class="form_group" name="photography_on_surgery" value="Agree" type="radio" id="photography_on_surgery" <?php if ($photography_on_surgery == "Agree") {
                                                                                                                                                    echo "checked";
                                                                                                                                                } ?>> Ninaruhusu
                                <input class="form_group" name="photography_on_surgery" value="Disagree" type="radio" id="photography_on_surgery" <?php if ($photography_on_surgery == "Disagree") {
                                                                                                                                                        echo "checked";
                                                                                                                                                    } ?>> Siruhusu
                            </td>
                        </tr>
                        <tr>
                            <td colspan='4'>
                                <hr>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="4">
                                AMPUTATION
                            </th>
                        </tr>
                        <tr>
                            <td colspan='4'>
                                <p><input class="form_group" name="consent_amputation" value="Agree" type="radio" id="consent_amputation" <?php if ($consent_amputation == "Agree") {
                                                                                                                                                echo "checked";
                                                                                                                                            } ?>> <b>Ninakubali</b>
                                    <input class="form_group" name="consent_amputation" value="Disagree" type="radio" id="consent_amputation" <?php if ($consent_amputation == "Disagree") {
                                                                                                                                                    echo "checked";
                                                                                                                                                } ?>> <b>Sikubali</b>: Kuondolewa/kukatwa kiungo changu cha mwili ambacho ni: <input type="text" class="text" name='Amptutation_of' id='Amptutation_of' value="<?php if ($datazangu) {
                                                                                                                                                                                                                                                                                                                    echo $Amptutation_of;
                                                                                                                                                                                                                                                                                                                } ?>" style='width: 700px; border-top: none; border-left: none; border-right: none; border-bottom: 2px solid #000;'>
                            </td>
                        </tr>
                        <tr>
                            <td colspan='4'>
                                <hr>
                            </td>
                        </tr>
                        <tr>
                            <td colspan='4' style='text-align: center; padding:4px;'>
                                <label>Imeridhiwa na: </label><input type="radio" id="vehicle" value="Mgonjwa">
                                <label> Mgonjwa</label><input type="radio" id="vehicle" value="Mlezi">
                                <label>Mlezi</label><input type="radio" id="vehicle" value="Director">
                                <label> Director</label>
                            </td>
                        </tr>
                        <tr>

                            <?php if ($Consent_ID > 0) {
                                echo $msg;
                            } ?>
                        </tr>
                        <tr>
                            <td>
                        <tr>
                            <td colspan="2" style="text-align:left"><b>
                                    Daktari Bingwa/Daktari wa Zamu: </b>
                                <?php

                                if ($Consent_ID > 0) {
                                    echo "<input type='text'  style='width: 370px; border-top: none; border-left: none; border-right: none; border-bottom: 2px solid #000; text-align: center; font-weight:bold;' value=" . $surgeon2 . ">";
                                } else { ?>
                                    <b><select id='surgery_doctor2'>
                                            <option value="<?php echo $Current_Employee_ID ?>" selected><?php echo $Current_Employee_Name ?></option><?php echo $select; ?>
                                        </select></b>
                                <?php } ?>
                            </td>
                            <td colspan="2" style="text-align:left"><b>Jina la Mlezi: </b>
                                <input type="text" name="behalf" id='behalf' placeholder="Guardian/Proxy:..." value="<?php echo $on_behalf_name; ?> ">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align:left"><b>Signature: </b>
                                <?php if ($Consent_ID > 0) {
                                    echo $doctor_button;
                                } ?>
                            </td>
                            <td colspan="2" style="text-align:left"><b>Uhusiano (kama sio mgonjwa mwenyewe):</b>
                                <input type="text" name="relation" id='relation' placeholder="Relationship of Guardian/Proxy..." value="<?php if ($Consent_ID > 0) {
                                                                                                                                            echo $relation;
                                                                                                                                        } ?> ">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align:left"><b>Cheo: </b>
                                <input type="text" name="designation" id='designation' placeholder="Designation..." value="<?php echo $designation; ?> ">
                            </td>
                            <td colspan="2" style="text-align:left"><b>Saini ya Daktari: </b>
                                <?php if ($Consent_ID > 0) {
                                    echo $patient_button;
                                } ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align:left">
                            </td>
                            <td colspan="2" style="text-align:left"><b>Jina la Shahidi wa Mgonjwa: </b>
                                <input type="text" name="patient_witness" id='patient_witness' placeholder="patient_witness..." value="<?php echo $patient_witness; ?> ">
                            </td>
                        </tr>

                        <tr>
                            <td colspan='2'></td>
                            <td colspan="2" style="text-align:left"><b>Saini: </b>
                                <?php if ($Consent_ID > 0) {
                                    echo $witnes_button;
                                } ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align:left"><b>Tarehe: </b>
                                <?php echo $date2; ?>
                            </td>
                            <td colspan="2" style="text-align:left"><b>Tarehe: </b>
                                <?php echo $date2; ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align:left">
                            </td>
                            <td colspan="2" style="text-align:left"><b>Muda: </b>
                                <?php echo $time; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><input type="button" name="" onclick="save_consent_data2()" width="100%" class="art-button-green" value="HAKIKI NA HIFADHI" style="border-radius: 10px; height: 35px;">
                            </td>
                            <td>
                                <!-- <a target="_blank" href="printrtheater.php?Registration_ID=<?php echo $Registration_ID; ?>" class="art-button-green">PREVIEW AND PRINT</a> -->
                            </td>
                            <td>
                                <?php
                                if (mysqli_num_rows($select_conset_detail) > 0) {
                                    echo $printbtn;
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                    <div id="procedure_list"></div>

            </fieldset>
            </form>
        </section>
    </center>
    <link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css" />
    <link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
    <link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
    <script src="media/js/jquery.js" type="text/javascript"></script>
    <script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
    <script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
    <script src="js/jquery-1.8.0.min.js"></script>
    <script src="js/jquery-ui-1.8.23.custom.min.js"></script>
    <script src="css/jquery-ui.js"></script>
    <link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
    <script src="js/jquery-1.8.0.min.js"></script>
    <script src="js/jquery-ui-1.8.23.custom.min.js"></script>





    <script>
        var toolbarOptions = [
            ['bold', 'italic', 'underline', 'strike'], // toggled buttons
            //['blockquote', 'code-block'],

            //[{ 'header': 1 }, { 'header': 2 }],               // custom button values
            [{
                'list': 'ordered'
            }, {
                'list': 'bullet'
            }],
            [{
                'script': 'sub'
            }, {
                'script': 'super'
            }], // superscript/subscript
            [{
                'indent': '-1'
            }, {
                'indent': '+1'
            }], // outdent/indent
            [{
                'direction': 'rtl'
            }], // text direction

            // [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
            [{
                'header': [1, 2, 3, 4, 5, 6, false]
            }],

            [{
                'color': []
            }, {
                'background': []
            }], // dropdown with defaults from theme
            [{
                'font': []
            }],
            [{
                'align': []
            }],

            ['clean'] // remove formatting button
        ];
        var quill = new Quill('.editorC', {
            modules: {
                toolbar: toolbarOptions
            },
            theme: 'snow'
        });

        function logHtmlContentC() {

            console.log(quill.root.innerHTML);
            var htmlcodeC = quill.root.innerHTML;
            var Registration_ID = <?php echo $Registration_ID; ?>;

            //alert(htmlcodeC)
            // alert(Registration_ID)


            $.ajax({
                type: 'POST',
                url: 'save_param.php',
                data: {
                    htmlcodeC: htmlcodeC,
                    location: "to_update",
                    Registration_ID: Registration_ID

                },
                success(response) {
                    alert(response);
                }
            });

        }

        function ajax_procedure_dialog_open() {
            $.ajax({
                type: 'POST',
                url: 'ajax_anasthesia_procedure_dialog1.php',
                data: {
                    procedure_dialog: ''
                },
                success: function(responce) {
                    $("#procedure_list").dialog({
                        title: 'PROPOSSED PROCEDURE',
                        width: '85%',
                        height: 600,
                        modal: true,
                    });
                    $("#procedure_list").html(responce)
                    ajax_search_procedure()
                }
            });
        }

        function ajax_search_procedure() {
            var Product_Name = $("#procedure_name").val();
            $.ajax({
                type: 'POST',
                url: 'ajax_anasthesia_procedure_dialog1.php',
                data: {
                    Product_Name: Product_Name,
                    search_procedure: ''
                },
                success: function(responce) {
                    $("#list_of_all_procedure").html(responce);
                }
            });
        }

        function save_anasthesia_procedure(Item_ID) {
            var Registration_ID = '<?= $Registration_ID ?>';
            $.ajax({
                type: 'POST',
                url: 'ajax_anasthesia_procedure_dialog1.php',
                data: {
                    Registration_ID: Registration_ID,
                    Item_ID: Item_ID,
                    save_procedure: 'save_procedure'
                },
                success: function(responce) {
                    display_selected_procedure()
                }
            });
        }

        function display_selected_procedure() {
            var Registration_ID = '<?php echo $Registration_ID; ?>';
            $.ajax({
                type: 'POST',
                url: 'ajax_anasthesia_procedure_dialog1.php',
                data: {
                    Registration_ID: Registration_ID,
                    display_procedure: 'display_procedure'
                },
                success: function(responce) {
                    $("#list_of_selected_procedure").html(responce)
                }
            });
        }

        function remove_anasthesia_procedure(Procedure_ID) {
            $.ajax({
                type: 'POST',
                url: 'ajax_anasthesia_procedure_dialog1.php',
                data: {
                    Procedure_ID: Procedure_ID,
                    remove_procedure: ''
                },
                success: function(responce) {
                    display_selected_procedure()
                }
            });
        }

        function view_procedure_selected() {
            var Registration_ID = '<?php echo $Registration_ID; ?>';

            $.ajax({
                type: 'POST',
                url: 'ajax_anasthesia_procedure_dialog1.php',
                data: {
                    Registration_ID: Registration_ID,
                    view_procedure: ''
                },
                success: function(responce) {
                    $("#proposed_procedure").val(responce)
                    $("#procedure_list").dialog("close")
                }
            });
        }
    </script>
    <script>
        $(document).ready(function(e) {
            $("#surgery_doctor").select2();
            $("#surgery_doctor2").select2();
        });
    </script>
    <!-- <input type="text" name="Registration_ID" hidden value="<? php; ?>" > -->
    <!-- <input type="text" id="Registration_ID" value="<? php; ?>" > -->

    <script>
        function save_consent_data2() {
            var Procedure_named = $("input[name = 'Procedure_named']").val();
            // var surgery_doctor = document.getElementById("surgery_doctor").value;
            var behalf = $("input[name = 'behalf']").val();
            var relation = $("input[name = 'relation']").val();
            var maelekezo = $("input[name = 'maelekezo']").val();
            var designation = $("input[name = 'designation']").val();
            var patient_witness = $("input[name = 'patient_witness']").val();
            // var Registration_ID= $("input[name = 'Registration_ID']").val();
            var presense_of_students = $("input[name = 'presense_of_students']:checked").val();
            var Amptutation_of = $("input[name = 'Amptutation_of']").val();
            var surgery_doctor2 = document.getElementById("surgery_doctor2").value;
            var photography_on_surgery = $("input[name = 'photography_on_surgery']:checked").val();
            var consent_amputation = $("input[name = 'consent_amputation']:checked").val();
            var vehicle = $("input[name = 'vehicle']:checked").val();
            var Registration_ID = '<?php echo $_GET['Registration_ID']; ?>';
            var Language = "Sw";

            if (Registration_ID != '0') {
                if (confirm("Una hakika taarifa ulizojaza ni sahihi na unataka kuendelea?")) {
                    $.ajax({
                        url: "ajax_save_consert_form.php",
                        type: "post",
                        data: {
                            Procedure_named: Procedure_named,
                            behalf: behalf,
                            relation: relation,
                            maelekezo: maelekezo,
                            designation: designation,
                            patient_witness: patient_witness,
                            Registration_ID: Registration_ID,
                            presense_of_students: presense_of_students,
                            Amptutation_of: Amptutation_of,
                            surgery_doctor2: surgery_doctor2,
                            photography_on_surgery: photography_on_surgery,
                            consent_amputation: consent_amputation,
                            vehicle: vehicle,
                            Language: Language
                        },
                        cache: false,
                        success: function(responce) {
                            alert(responce);
                            location.reload();
                        }
                    });
                }
            }
        }
    </script>

</html>

<?php
include("./includes/footer.php");
?>