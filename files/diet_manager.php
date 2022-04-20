<!-- value="' . $_POST['val'] . '" id="' . $Laboratory_Test_specimen_ID . '" -->
<?php
include("./includes/connection.php");
$action=$_GET['action'];
switch ($action) {
    case 'getSessionData':
        $dietSession=$_GET['dietSession'];
        if ($dietSession=="breakfast") {
            $sessionFilter="AND breakFast=1";
        }
        elseif ($dietSession=="lunch") {
            $sessionFilter="AND lunch=1";
        }
        else {
            $sessionFilter="AND dinner=1";
        }
        $query="SELECT hw.Hospital_Ward_ID AS wardId, hw.Hospital_Ward_Name AS wardName,
        SUM(CASE WHEN ds.sponsor = 'Sold' $sessionFilter THEN 1 ELSE 0 END) AS soldSponsor,
        SUM(CASE WHEN ds.sponsor = 'GED' $sessionFilter THEN 1 ELSE 0 END) AS gedSponsor,SUM(CASE WHEN ds.sponsor = 'Guardian' $sessionFilter THEN 1 ELSE 0 END) AS guardianSponsor,SUM(CASE WHEN ds.highProtein =1 $sessionFilter THEN 1 ELSE 0 END) AS highProtein,SUM(CASE WHEN ds.lightDiet =1 $sessionFilter THEN 1 ELSE 0 END) AS lightDiet,SUM(CASE WHEN ds.saltFree =1 $sessionFilter THEN 1 ELSE 0 END) AS saltFree,SUM(CASE WHEN ds.fatFree =1 $sessionFilter THEN 1 ELSE 0 END) AS fatFree,SUM(CASE WHEN ds.diabeticDiet =1 $sessionFilter THEN 1 ELSE 0 END) AS diabeticDiet,SUM(CASE WHEN ds.specialDiet =1 $sessionFilter THEN 1 ELSE 0 END) AS specialDiet,SUM(CASE WHEN ds.normalDiet =1 $sessionFilter THEN 1 ELSE 0 END) AS normalDiet,SUM(CASE WHEN ds.milk =1 $sessionFilter THEN 1 ELSE 0 END) AS milk,SUM(CASE WHEN ds.breakFast =1 $sessionFilter THEN 1 ELSE 0 END) AS breakFast,SUM(CASE WHEN ds.lunch =1 $sessionFilter THEN 1 ELSE 0 END) AS lunch,SUM(CASE WHEN ds.dinner =1 $sessionFilter THEN 1 ELSE 0 END ) AS dinner FROM tbl_hospital_ward hw LEFT JOIN tbl_admission a ON a.Hospital_Ward_ID = hw.Hospital_Ward_ID LEFT JOIN tbl_diet_specification ds ON a.Admision_ID = ds.admission_id WHERE a.Admission_Status <> 'Discharged' GROUP BY hw.Hospital_Ward_ID";
        
        $select=mysqli_query($conn,$query);
        $tbody="";
        if (mysqli_num_rows($select)) {
            while ($row=mysqli_fetch_assoc($select)) {
                $wardId=$row['wardId'];
                $wardName=$row['wardName'];
                $gedSponsor=$row['gedSponsor'];
                $soldSponsor=$row['soldSponsor'];
                $guardianSponsor=$row['guardianSponsor'];
                $highProtein=$row['highProtein'];
                $lightDiet=$row['lightDiet'];
                $saltFree=$row['saltFree'];
                $fatFree=$row['fatFree'];
                $diabeticDiet=$row['diabeticDiet'];
                $specialDiet=$row['specialDiet'];
                $normalDiet=$row['normalDiet'];
                $milk=$row['milk'];


                $totalSoldSponsor+=$soldSponsor;
                $totalGedSponsor+=$gedSponsor;
                $totalGuardianSponsor+=$guardianSponsor;
                $totalHighProtein+=$highProtein;
                $totalLightDiet+=$lightDiet;
                $totalSaltFree+=$saltFree;
                $totalFatFree+=$fatFree;
                $totalDiabeticDiet+=$diabeticDiet;
                $totalSpecialDiet+=$specialDiet;
                $totalNormalDiet+=$normalDiet;
                $totalmilk+=$milk;


                $tbody .="
                <tr>
                    <td>$wardName</td>
                    <td class='text-center'>$soldSponsor</td>
                    <td class='text-center'>$guardianSponsor</td>
                    <td class='text-center'>$gedSponsor</td>
                    <td class='text-center'>$highProtein</td>
                    <td class='text-center'>$lightDiet</td>
                    <td class='text-center'>$saltFree</td>
                    <td class='text-center'>$fatFree</td>
                    <td class='text-center'>$diabeticDiet</td>
                    <td class='text-center'>$specialDiet</td>
                    <td class='text-center'>$normalDiet</td>
                    <td class='text-center'>$milk</td>
                    <td class='text-center'>
                    <button type='button' class='view-record btn btn-outline-success btn-sm' data-id='$wardId' data-ward='$wardName'>view</button>
                    </td>
                </tr>
                ";
            }
            $tbody.="<tr>
            <td class='fw-bold fs-5'>Total</td>
            <td class='text-center'>$totalSoldSponsor</td>
            <td class='text-center'>$totalGedSponsor</td>
            <td class='text-center'>$totalGuardianSponsor</td>
            <td class='text-center'>$totalHighProtein</td>
            <td class='text-center'>$totalLightDiet</td>
            <td class='text-center'>$totalSaltFree</td>
            <td class='text-center'>$totalFatFree</td>
            <td class='text-center'>$totalDiabeticDiet</td>
            <td class='text-center'>$totalSpecialDiet</td>
            <td class='text-center'>$totalNormalDiet</td>
            <td class='text-center'>$totalmilk</td>
            <td class='text-center'></td>
            </tr>";
        } else {
            $tbody.="
            <tr>
            <td colspan='12' class='text-center'>
            No data available
            </td>
            </tr>
            ";
        }
        echo $tbody;
        break;
        case 'getWardData':
            $ward=$_GET['ward'];
            $dietSession=$_GET['dietSession'];
            if ($dietSession=="breakfast") {
                $sessionFilter="AND breakFast=1";
            }
            elseif ($dietSession=="lunch") {
                $sessionFilter="AND lunch=1";
            }
            else {
                $sessionFilter="AND dinner=1";
            }
            
            $query="SELECT DISTINCT(hw.Hospital_Ward_ID) AS wardId,pr.rank,pr.military_unit,pr.service_no,hw.Hospital_Ward_Name AS wardName,pr.Patient_Name,ds.*,TIMESTAMPDIFF(year,pr.Date_Of_Birth, now() ) AS age FROM tbl_hospital_ward hw LEFT JOIN tbl_admission a ON a.Hospital_Ward_ID = hw.Hospital_Ward_ID LEFT JOIN tbl_diet_specification ds ON a.Admision_ID = ds.admission_id LEFT JOIN tbl_patient_registration pr ON pr.Registration_ID=a.Registration_ID WHERE a.Admission_Status <> 'Discharged' AND hw.Hospital_Ward_ID=$ward $sessionFilter";

            $select=mysqli_query($conn,$query);
            $tbody="";
            $i=1;
            if (mysqli_num_rows($select)) {
                while ($row=mysqli_fetch_assoc($select)) {
                    $wardId=$row['wardId'];
                    $Patient_Name=$row['Patient_Name'];
                    $rank=$row['rank'];
                    $military_unit=$row['military_unit'];
                    $service_no=$row['service_no'];
                    $age=$row['age'];
                    $id=$row['id'];
                    if ($row['signature']!="not signed") {
                        $signBtn="<span class='text-success fw-bold'>Signed</span>";
                    }
                    else {
                        $signBtn="<button type='button' class='view-record btn btn-outline-success btn-sm' data-id='$id'>Sign</button>";
                    }
                    $diet="";

                    if ($row['highProtein']==1) {
                        $diet.="High protein diet, ";
                    }

                    if ($row['lightDiet']==1) {
                        $diet.="Light diet, ";
                    }

                    if ($row['saltFree']==1) {
                        $diet.="Salt free diet, ";
                    }

                    if ($row['fatFree']==1) {
                        $diet.="Fat free diet, ";
                    }

                    if ($row['diabeticDiet']==1) {
                        $diet.="Diabetic diet, ";
                    }

                    if ($row['specialDiet']==1) {
                        $diet.="Special diet, ";
                    }

                    if ($row['normalDiet']==1) {
                        $diet.="Normal diet, ";
                    }
                    $milk="<td class='text-center text-warning fw-bold'>no recommendation</td>";
                    if ($row['milk']==1) {
                        $milk="<td class='text-center text-success fw-bold'>recomended</td>";
                    }
                    $diet =rtrim($diet,", ");
                    $tbody .="
                    <tr>
                        <td>$i</td>
                        <td class='text-center'>$service_no</td>
                        <td class='text-center'>$rank</td>
                        <td>$Patient_Name</td>
                        <td class='text-center'>$military_unit</td>
                        <td class='text-center'>$age</td>
                        <td class='text-center'>$diet</td>
                        $milk
                        <td class='text-center'>
                        $signBtn
                        </td>
                    </tr>
                    ";
                    $i++;
                }
            } else {
                $tbody.="
                <tr>
                <td colspan='12' class='text-center'>
                No available data
                </td>
                </tr>
                ";
            }
            echo $tbody;
            break;
}

function getValue($value)
{
    if ($value=1) {
        return 1;
    } else {
        return 0;
    }
    
}