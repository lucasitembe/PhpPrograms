<?php
include("./includes/connection.php");
include("./includes/header.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

//get today's date
$sql_date_time = mysqli_query($conn, "select now() as Date_Time ") or die(mysqli_error($conn));
while ($date = mysqli_fetch_array($sql_date_time)) {
    $Current_Date_Time = $date['Date_Time'];
}
$Filter_Value = substr($Current_Date_Time, 0, 11);
$Start_Date = $Filter_Value . ' 00:00';
$End_Date = $Current_Date_Time;

if (isset($_GET['dhis2_dataset_name'])) {
    $dhis2_dataset_name = $_GET['dhis2_dataset_name'];
} else {
    $dhis2_dataset_name = "";
}
if (isset($_GET['dataset_id'])) {
    $dataset_id = $_GET['dataset_id'];
} else {
    $dataset_id = "";
}
if (isset($_GET['dhis2_auto_dataset_id'])) {
    $dhis2_auto_dataset_id = $_GET['dhis2_auto_dataset_id'];
} else {
    $dhis2_auto_dataset_id = "";
}
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
if (isset($_GET['formupdate'])) {
    $row = $_GET['row'];
    $row_clinic_id = $_GET['row_clinic_id'];
    $clinic_uid = $_GET['clinic_uid' . $row];
    $les_than_5_male_new = $_GET['les_than_5_male_new' . $row];
    $les_than_5_female_new = $_GET['les_than_5_female_new' . $row];
    $btn_5_and_59_male_new = $_GET['btn_5_and_59_male_new' . $row];
    $btn_5_and_59_female_new = $_GET['btn_5_and_59_female_new' . $row];
    $plus_60_male_new = $_GET['plus_60_male_new' . $row];
    $plus_60_female_new = $_GET['plus_60_female_new' . $row];

    $query = mysqli_query($conn, "delete from tbl_dhis2_report_config where ward_id = '$row_clinic_id' and dataset_id = '$dataset_id'") or die(mysqli_error($conn));
    if($query){
    $result = mysqli_query($conn, "insert into tbl_dhis2_report_config (ward_id,uid,les_than_5_male_new,les_than_5_female_new,btn_5_and_59_male_new,btn_5_and_59_female_new,plus_60_male_new,plus_60_female_new,dataset_id,employee_id) values ('$row_clinic_id','$clinic_uid','$les_than_5_male_new','$les_than_5_female_new','$btn_5_and_59_male_new','$btn_5_and_59_female_new','$plus_60_male_new"
                    . "','$plus_60_female_new','$dataset_id','$Employee_ID')") or die(mysqli_error($conn));
    if ($result) {
        ?>
        <script>
            alert("Update Succesfully.");
            window.location = "dhis2_report_configuration_ipd.php?dhis2_auto_dataset_id=<?= $dhis2_auto_dataset_id ?>&&dhis2_dataset_name=<?= $dhis2_dataset_name ?>&&dataset_id=<?= $dataset_id ?>";
        </script>
        <?php
    } else {
        ?>
        <script>
            alert("Update Fail.");
            window.location = "dhis2_report_configuration_ipd.php?dhis2_auto_dataset_id=<?= $dhis2_auto_dataset_id ?>&&dhis2_dataset_name=<?= $dhis2_dataset_name ?>&&dataset_id=<?= $dataset_id ?>";
        </script>
        <?php
    }
    }
}
?>
<a href="dhis2_hmis_dataelements_new_ipd.php?dhis2_auto_dataset_id=<?= $dhis2_auto_dataset_id ?>&&dhis2_dataset_name=<?= $dhis2_dataset_name ?>&&dataset_id=<?= $dataset_id ?>" class="art-button-green">BACK</a>
<!--<a href="dhis2_api.php" class="art-button-green">BACK</a>-->
<style>
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
</style>
<fieldset>
    <legend align='center'><b><?= $dhis2_dataset_name; ?> Report Configuration</b></legend>
    <div class="box box-primary" style="height: 600px;overflow-y: scroll;overflow-x: scroll">
        <table class="table">
            <tr>
                <td style="width:50px"><b>S/N</b></td>
                <td><b>Ward Name</b></td>
                <td><b>Data element UID</b></td>
                <td><b>Number of Beds</b></td>
                <td><b>Admissions</b></td>
                <td><b>Discharges Lives</b></td>
                <td><b>Deaths</b></td>
                <td><b>Inpatients/occupied Beds Days (OBD)</b></td>
            </tr>
            <tbody>
                <?php
                @session_start();
                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
                $count_sn = 1;

                $get_all_clinics = mysqli_query($conn, "select Hospital_Ward_Name, Hospital_Ward_ID from tbl_hospital_ward order by Hospital_Ward_Name") or die(mysqli_error($conn));

                while ($clinic = mysqli_fetch_assoc($get_all_clinics)) {
                    $Hospital_Ward_Name = $clinic['Hospital_Ward_Name'];
                    $Hospital_Ward_ID = $clinic['Hospital_Ward_ID'];
                    $result = mysqli_query($conn, "select * from tbl_dhis2_report_config dh where dh.ward_id = '$Hospital_Ward_ID' and dh.dataset_id = '$dataset_id'") or die(mysqli_error($conn));
                    if (mysqli_num_rows($result) > 0) {
                        while ($rows = mysqli_fetch_assoc($result)) {
                            $clinic_uid = $rows['uid'];
                            $les_than_5_male_new = $rows['les_than_5_male_new'];
                            $les_than_5_female_new = $rows['les_than_5_female_new'];
                            $btn_5_and_59_male_new = $rows['btn_5_and_59_male_new'];
                            $btn_5_and_59_female_new = $rows['btn_5_and_59_female_new'];
                            $plus_60_male_new = $rows['plus_60_male_new'];
                            $plus_60_female_new = $rows['plus_60_female_new'];
                        }
                    } else {
                        $clinic_uid = '';
                        $les_than_5_male_new = '';
                        $les_than_5_female_new = '';
                        $btn_5_and_59_male_new = '';
                        $btn_5_and_59_female_new = '';
                        $plus_60_male_new = '';
                        $plus_60_female_new = '';
                    }


                    echo "
                <tr class='rows_list' $change_color_style><form action='#' method='get'>
                        <td>$count_sn.</td>
                        <td>$Hospital_Ward_Name</td>
                          <td style='text-align:center;'><input type='text' name='clinic_uid$count_sn' value='$clinic_uid' class='form-control'/></td>
                          <td style='text-align:center;'><input type='text' name='les_than_5_male_new$count_sn' value='$les_than_5_male_new' class='form-control'/></td>
                          <td style='text-align:center;'><input type='text' name='les_than_5_female_new$count_sn' value='$les_than_5_female_new' class='form-control'/></td>
                          <td style='text-align:center;'><input type='text' name='btn_5_and_59_male_new$count_sn' value='$btn_5_and_59_male_new' class='form-control'/></td>
                          <td style='text-align:center;'><input type='text' name='btn_5_and_59_female_new$count_sn' value='$btn_5_and_59_female_new' class='form-control'/></td>
                          <td style='text-align:center;'><input type='text' name='plus_60_male_new$count_sn' value='$plus_60_male_new' class='form-control'/></td>
                          
                       <td style='text-align:center;'>
                       <input type='text' name='dhis2_auto_dataset_id' value='$dhis2_auto_dataset_id' style='display:none;'/>
                       <input type='text' name='dhis2_dataset_name' value='$dhis2_dataset_name' style='display:none;'/>
                       <input type='text' name='dataset_id' value='$dataset_id' style='display:none;'/>
                       <input type='text' name='row' value='$count_sn' style='display:none;'/>
                       <input type='text' name='row_clinic_id' value='$Hospital_Ward_ID' style='display:none;'/>
                       <input type='submit' name='formupdate' value='UPDATE' class='art-button pull-right'/></td>
                    </form>
                </tr>
                ";
                    $count_sn++;
                }
                ?>

            </tbody>
        </table>
    </div>
</fieldset>
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<?php
include("./includes/footer.php");
?>
