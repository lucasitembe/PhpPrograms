<?php
include("./includes/connection.php");
//ini_set('display_errors', true);
@session_start();
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
if (isset($_POST['period_year']) && isset($_POST['period_type']) && isset($_POST['complete_date']) && isset($_POST['dataset_id'])) {

    $period_year = $_POST['period_year'];
    $period_type = $_POST['period_type'];
    $complete_date = $_POST['complete_date'];
    $dataset_id = $_POST['dataset_id'];
    $orgUnit = $_POST['orgUnit'];
    $period = "$period_year$period_type";
    $data_element_array = array();
    $get_all_clinics = mysqli_query($conn, "select Hospital_Ward_Name, Hospital_Ward_ID from tbl_hospital_ward order by Hospital_Ward_Name") or die(mysqli_error($conn));

    while ($clinic = mysqli_fetch_assoc($get_all_clinics)) {
        $Hospital_Ward_Name = $clinic['Hospital_Ward_Name'];
        $Hospital_Ward_ID = $clinic['Hospital_Ward_ID'];

        $result = mysqli_query($conn, "select * from tbl_dhis2_report_config dh where dh.ward_id = '$Hospital_Ward_ID' and dh.dataset_id = '$dataset_id'") or die(mysqli_error($conn));
        if (mysqli_num_rows($result) > 0) {
            while ($rows = mysqli_fetch_assoc($result)) {
                $valuedata = mysqli_query($conn, "select * from tbl_dhis2_submission_query where ward_id = '$Hospital_Ward_ID' and employee_id='$Employee_ID' and status='not submited' ") or die(mysqli_error($conn));
                $valuesObj = mysqli_fetch_assoc($valuedata);
                if(empty($rows['uid']))                    continue;
                array_push($data_element_array, array(
                    "dataElement" => $rows['uid'],
                    "categoryOptionCombo" => $rows['les_than_5_male_new'],
                    "value" => (string) (int) $valuesObj['les_than_5_female_new']
                ));
                array_push($data_element_array, array(
                    "dataElement" => $rows['uid'],
                    "categoryOptionCombo" => $rows['les_than_5_female_new'],
                    "value" => (string) (int) $valuesObj['btn_5_and_59_male_new']
                ));
                array_push($data_element_array, array(
                    "dataElement" => $rows['uid'],
                    "categoryOptionCombo" => $rows['btn_5_and_59_male_new'],
                    "value" => (string) (int) $valuesObj['btn_5_and_59_female_new']
                ));
                array_push($data_element_array, array(
                    "dataElement" => $rows['uid'],
                    "categoryOptionCombo" => $rows['btn_5_and_59_female_new'],
                    "value" => (string) (int) $valuesObj['plus_60_male_new']
                ));
                array_push($data_element_array, array(
                    "dataElement" => $rows['uid'],
                    "categoryOptionCombo" => $rows['plus_60_male_new'],
                    "value" => (string) (int) $valuesObj['plus_60_female_new']
                ));

                mysqli_query($conn, "update tbl_dhis2_submission_query set status='submited' where ward_id = '$Hospital_Ward_ID' and employee_id='$Employee_ID'") or die(mysqli_error($conn));
            }
        }
    }

    $array = array("dataSet" => "$dataset_id",
        "completeDate" => "$complete_date",
        "period" => "$period",
        "orgUnit" => "$orgUnit",
        "dataValues" => $data_element_array);
    
//    die(json_encode($array));

    $ch = curl_init();

    // curl_setopt($ch, CURLOPT_URL, "https://vmi368782.contaboserver.net/dhis2testupgrade/api/dataValueSets.json");
    curl_setopt($ch, CURLOPT_URL, "https://dhis.moh.go.tz/api/dataValueSets.json");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($array));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_USERPWD, "tnyalu" . ":" . "Teotuma@10");

    $headers = array();
    $headers[] = "Content-Type: application/json";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo '<b style="color:red">Error:</b>' . curl_error($ch);
    }
    curl_close($ch);

    $feedback = json_decode($result, TRUE);

    $responseType = $feedback['responseType'];
    $status = $feedback['status'];
    $description = $feedback['description'];

    $importCount = $feedback['importCount'];
    $imported = $importCount['imported'];
    $updated = $importCount['updated'];
    $ignored = $importCount['ignored'];
    $deleted = $importCount['deleted'];
    $conflit_v = "";
    if (isset($feedback['conflicts'])) {
        $conflicts = $feedback['conflicts'];
        $conflicts_value = $conflicts[0];
        $conflit_v = $conflicts_value['value'];
    }

    $dataSetComplete = $feedback['dataSetComplete'];
    ?>
    <table class="table" style="background: #FFFFFF">
        <tr>
            <td colspan="2"><b>Response Type</b></td>
            <td><?= $responseType ?></td>
        </tr>
        <tr>
            <?php
            if ($status == "SUCCESS") {
                $color_style = "style='color:green'";
            } else {
                $color_style = "style='color:red'";
            }
            ?>
            <td colspan="2"><b>Status</b></td>
            <td><b <?= $color_style ?>><?= $status ?></b></td>
        </tr>
        <tr>
            <td colspan="2"><b>Description</b></td>
            <td><?= $description ?></td>
        </tr>
        <tr>
            <td colspan="2"><b>DataSet Complete</b></td>
            <td><?= $dataSetComplete ?></td>
        </tr>
        <tr>
            <td colspan="2"><b>Conflict</b></td>
            <td><?= $conflit_v ?></td>
        </tr>
        <tr>
            <td colspan="8" style="text-align: center"><b>Import Count</b></td>
        </tr>
        <tr><td colspan="8"><hr/></td></tr>
        <tr>
            <td><b>Imported</b></td>
            <td><?= $imported ?></td>
            <td><b>Updated</b></td>
            <td><?= $updated ?></td>
            <td><b>Ignored</b></td>
            <td><?= $ignored ?></td>
            <td><b>Deleted</b></td>
            <td><?= $deleted ?></td>
        </tr>
    </table>

    <?php
//    echo "<div>";
//    print_r(json_decode($result));
//    echo "</div>";
}
?>