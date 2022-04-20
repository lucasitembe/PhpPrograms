<?php 
    include './includes/header.php';
    include './includes/connection.php';
    $Clinic_ = $_GET['clinic'];
?>

<a href="index.php?Bashboard=BashboardThisPage" class="art-button-green">BACK</a>

<br><br>

<center>
    <fieldset style="padding-bottom: 15px;">
        <legend align='center' style="font-weight: 500;">PHYSIOTHERAPY WORKS</legend>
        <table width='40%'>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;' >
                    <a href='physiotherapy_ipd_patient_list.php?clinic=<?=$Clinic_?>'>
                        <button style="width: 100%; height: 100%;">
                            IPD PATIENT LIST 
                        </button>
                    </a>
                </td>
            </tr>
                <td style='text-align: center; height: 40px; width: 33%;' >
                    <a href='physiotherapy_opd_patient_list.php?clinic=<?=$Clinic_?>'>
                        <button style="width: 100%; height: 100%;">OPD PATIENT LIST </button>
                    </a>
                </td>
            </tr>
                <td style='text-align: center; height: 40px; width: 33%;' >
                    <a href='pharmacyworkspage_new.php'>
                        <button style="width: 100%; height: 100%;">
                            REPORT 
                        </button>
                    </a>
                </td>
            </tr>
            </tr>
        </table>
    </fieldset>
</center>

<?php include './includes/footer.php' ?>
