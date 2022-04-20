<?php
//ini_set('display_errors', true);
include('new_header.php');
include('../includes/connection.php');
$form_name = "INTENSIVE CARE UNIT";

include 'partials/get_patient_details.php';

?>

<a href="../nursecommunicationpage.php?consultation_ID=<?= $consultation_ID; ?>&Registration_ID=<?= $Registration_ID; ?>&Admision_ID=<?= $Admision_ID ?>&Check_In_ID=<?=$_GET['Check_In_ID']?>"
   class="btn btn-primary">BACK</a>

<?php include "partials/new_patient_info.php"; ?>


<div class="bg-white pt-5 px-2">
    <div class="d-flex justify-content-center">
        <div style="width: 50vw;">
            <table class="w-100 table table-bordered">
                <tr>
                    <td>
                        <a href="form_one.php?Registration_ID=<?= $_GET['Registration_ID'] ?>&Admision_ID=<?= $Admision_ID ?>&consultation_ID=<?= $consultation_ID ?>">
                            <button class="menu-button">VITAL SIGNS</button>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <a href="form_two.php?Registration_ID=<?= $_GET['Registration_ID'] ?>&Admision_ID=<?= $Admision_ID ?>&consultation_ID=<?= $consultation_ID ?>">
                            <button class="menu-button">GLASGOW COMA SCALE (GCS)</button>
                        </a>
                    </td>
                </tr>

                <tr>
                    <td>
                        <a href="form_three.php?Registration_ID=<?= $Registration_ID ?>&Admision_ID=<?= $Admision_ID ?>&consultation_ID=<?= $consultation_ID ?>&Check_In_ID=<?=$_GET['Check_In_ID']?>">
                            <button class="menu-button">MEDICATION ADMINISTRATION (eMAR)</button>
                        </a>
                    </td>
                </tr>

                <tr>
                    <td>
                        <a href="form_four.php?Registration_ID=<?= $_GET['Registration_ID'] ?>&Admision_ID=<?= $Admision_ID ?>&consultation_ID=<?= $consultation_ID ?>">
                            <button class="menu-button">LABORATORY REPORT</button>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <a href="form_five.php?Registration_ID=<?= $_GET['Registration_ID'] ?>&Admision_ID=<?= $Admision_ID ?>&consultation_ID=<?= $consultation_ID ?>">
                            <button class="menu-button">HANDOVER ISSUE</button>
                        </a>
                    </td>
                </tr>

                <tr>
                    <td>
                        <a href="form_six.php?Registration_ID=<?= $_GET['Registration_ID'] ?>&Admision_ID=<?= $Admision_ID ?>&consultation_ID=<?= $consultation_ID ?>">
                            <button class="menu-button">PATIENT ASSESSMENT</button>
                        </a>
                    </td>
                </tr>

                <tr>
                    <td>
                        <a href="form_seven.php?Registration_ID=<?= $_GET['Registration_ID'] ?>&Admision_ID=<?= $Admision_ID ?>&consultation_ID=<?= $consultation_ID ?>">
                            <button class="menu-button">ROUTINE CARE</button>
                        </a>
                    </td>
                </tr>

            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
