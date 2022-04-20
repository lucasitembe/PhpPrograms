<style type="text/css">
    /* .labefor{display:block;width: 100% }
    .labefor:hover{background-color: #a8d1ff;cursor: pointer}
    label.labefor { width: 100%;  */             
                    .rows_list{ 
                        cursor: pointer;
                    }
                    .rows_list:active{
                        color: #328CAF!important;
                        font-weight:normal!important;
                    }
                    .rows_list:hover{
                        color:#00416a;
                        background: #dedede;
                        font-weight:bold;
                    }
                    a{
                        text-decoration: none;
                    }
                    
                input[type="checkbox"]{
                    width: 25px; 
                    height: 25px;
                    cursor: pointer;
                    margin: 5px;
                    margin-right: 5px;
                }

                input[type="radio"]{
                    width: 25px; 
                    height: 25px;
                    cursor: pointer;
                    margin: 5px;
                    margin-right: 5px;
                }
                #th{
                    background:#99cad1;
                }

                #spu_lgn_tbl{
                    width:100%;
                    border:none!important;
                }
                #spu_lgn_tbl tr, #spu_lgn_tbl tr td{
                    border:none!important;
                    padding: 5px;
                    font-size: 14PX;
                }
                select{
        padding:5px;
    }

    .dates{
        color:#cccc00;
    }
</style>
<?php
    include("./includes/header.php");
    include("./includes/connection.php");

    include_once("./functions/department.php");
    include_once("./functions/employee.php");
    include_once("./functions/items.php");
    include_once("./functions/requisition.php");

    //get employee name
    
        if (isset($_GET['Requisition_ID'])) {
        $Requisition_ID = $_GET['Requisition_ID'];
    }
    
    if (isset($_SESSION['userinfo'])) {
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    } else {
        $Employee_Name = 'Unknown Officer';
        $Employee_ID = 0;
    }
    
//get sub department name
if (isset($_SESSION['Storage_Info']['Sub_Department_ID'])) {
    $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
    //exit($Sub_Department_ID);
    $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select);
    if ($no > 0) {
        $row = mysqli_fetch_assoc($select);
        $Sub_Department_Name = $row['Sub_Department_Name'];
    } else {
        $Sub_Department_Name = '';
    }
}


    

    if (!isset($_SESSION['userinfo'])) {
        session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    $Requisition = array();
    if (!empty($Requisition_ID)) {
        $Requisition = Get_Requisition($Requisition_ID);
    }

    if (isset($_SESSION['Storage_Info'])) {
        $Current_Store_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
        $Current_Store_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
    }

    $Today_Date = mysqli_query($conn,"select now() as today");
    while ($row = mysqli_fetch_array($Today_Date)) {
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d H:m", strtotime($original_Date));
        $Today = $new_Date;
    }

$engineering
?>
        <a href='project_menu.php?Engineering_Works=Engineering_WorksThisPage' class='art-button-green'>
            BACK
        </a>

        <style>
        
        
        .procure {
            display: none;
        }

        .spare {
            display: none;
            border: 1px white black;
        }

        .spare table tr th {
            background: gray;
            border: 1px solid #fff;
        }
        .spare table tr:nth-child(even){
            background-color: #eee;
        }
        .spare table tr:nth-child(odd){
            background-color: #fff;
        }
        </style>

<br/>
<center>
<table width='90%'>
    <tr>
      <td>
        <fieldset>
        <legend align=center><b>LABOUR SERVICE AGREEMENT</b></legend>
        <form action='' method='POST' name='' id='myForm' >
            <table   id="spu_lgn_tbl" width="100%">
                <tr>
                    <td style="text-align: right;">Date of Agreement:</td>
                    <td colspan='2'>
                        <?php
                            echo "<input type='text' name='date_of_agreement' value='{$Today}' readonly='readonly'>";
                        ?>
                    </td>
                    <td style="text-align: right;">Name of Labourer / Contractor:</td>
                    <td colspan='2'>
                        <input type='text' name='date_of_agreement'>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right;">Type of Service:</td>
                    <td colspan='2'>
                        <select name="service_type" id="service_type" style="width: 100%;">
                            <option>Biomedical</option>
                            <option>Electrical</option>
                            <option>Mechanical</option>
                            <option>Mason</option>
                            <option>Plumber</option>
                            <option>Carpentry</option>
                            <option>Welding</option>
                            <option>Painting</option>
                        </select>
                    </td>
                    <td><td>
                    <td><td>
                    <td><td>
                </tr>
                <tr>
                    <td style="text-align: right;">Labourer / Contractor<br> affiliation with BMC:</td>
                    <td colspan="2" style='text-align: center;'>
                        <input type='checkbox' name='external_contractor' id='external_contractor' value='yes'>
                        <label for='external_contractor'>External Contractor / Labourer</label>
                    </td>
                    <td colspan="2" style='text-align: center;'>
                        <input type='checkbox' name='bmc_staff' id='bmc_staff' value='yes'>
                        <label for='bmc_staff'>BMC Salaried Staff</label>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="6"><legend align=center style='text-align: center;'><b>DELIVERABLES</b></legend></td>
                </tr>
                <tr>
                    <td  style='text-align: right; font-weight: bold;' colspan='5'><i>Outline All Activities that are to be completed under this agreement*</i></td>
                    <td>
                        <button type="button" name="Add_Activities" class="btn btn-primary" onclick="Add_Activities()" >Add Activities </button>
                    </td>
                </tr>
                <tr>
                    <td  style='text-align: right;'>1.</td>
                    <td colspan='4'>
                        <input type='text' name='activities' width='100%' height='20%' placeholder='Name Specific Activity Done'>
                    </td>
                    <td>
                        <button type="button" name="remove_Activities" class="btn btn-primary" onclick="remove_Activities()" >X</button>
                    </td>
                </tr>
                <tr>
                    <td  style='text-align: right;'>Total Hours for the Job:</td>
                    <td colspan='2'>
                        <input type='text' name='activities' width='100%' height='20%' placeholder='Total Hours'>
                    </td>
                    <td  style='text-align: right;'>Rate / Hours:</td>
                    <td  colspan='2'>
                        <input type='text' name='activities' width='100%' height='20%' placeholder='Total Hours'>
                    </td>
                </tr>
                <tr>
                    <td  style='text-align: right;'>Agreed cost for the Job:</td>
                    <td colspan='2'>
                        <input type='text' name='activities' width='100%' height='20%' placeholder='Agreed cost for the Job:'>
                    </td>
                </tr>
                <tr>
                    <td  style='text-align: right;'>Start Date:</td>
                    <td colspan='2'>
                        <input type="text" autocomplete="off" style='text-align: center;width:100%;display:inline' id="start_date" placeholder="Start Date"/>
                    </td>
                    <td  style='text-align: right;'>Due Date and Time:</td>
                    <td  colspan='2'>
                        <input type="text" autocomplete="off" style='text-align: center;width:100%;display:inline' id="end_date" placeholder="End Date"/>
                    </td>
                </tr>
                <tr>
                    <td colspan='6' style='text-align: center;'>
                            <input type='submit' name='submit_form' id='submit_form' value='   SAVE INFORMATIONS   ' class='btn btn-primary'>
                    </td>
                </tr>
            </table>
            </table>



            <script type="text/javascript">
        $(document).ready(function () {
            $('#patients-list').DataTable({
                "bJQueryUI": true
            });

            $('#start_date').datetimepicker({
                dayOfWeekStart: 1,
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,
                lang: 'en',
                //startDate:    'now'
            });
            $('#start_date').datetimepicker({value: '', step: 1});
            $('#end_date').datetimepicker({
                dayOfWeekStart: 1,
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,
                lang: 'en',
                //startDate:'now'
            });
            $('#end_date').datetimepicker({value: '', step: 1});
        });
    </script>            

<?php
    include("./includes/footer.php");
?>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
    <link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
    <script src="media/js/jquery.js" type="text/javascript"></script>
    <script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
    <script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
    <script src="css/jquery-ui.js"></script>
