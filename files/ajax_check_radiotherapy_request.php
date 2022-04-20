<?php
include("includes/connection.php");
$Employee_ID = $_GET['Employee_ID'];
$consultation_ID = $_GET['consultation_ID'];
$Registration_ID = $_GET['Registration_ID'];
$Treatment_Phase = $_GET['Treatment_Phase'];
$Intent_of_Treatment = $_POST['Intent_of_Treatment'];

$display = '';
    $Radiotherapy_ID =mysqli_fetch_assoc(mysqli_query($conn, "SELECT Radiotherapy_ID FROM tbl_radiotherapy_requests WHERE consultation_ID = '$consultation_ID'"))['Radiotherapy_ID'];
    if($Radiotherapy_ID > 0){
        $select_details = mysqli_query($conn, "SELECT Treatment_Phase, Tumor_Dose, Number_of_Fraction, Dose_per_Fraction, name_of_site, Number_of_Fields FROM tbl_radiotherapy_phases WHERE Radiotherapy_ID = '$Radiotherapy_ID' ORDER BY Ordered_No ASC");
        if(mysqli_num_rows($select_details) > 0){
            while($row = mysqli_fetch_assoc($select_details)){
                $Treatment_Phase = $row['Treatment_Phase'];
                $Tumor_Dose = $row['Tumor_Dose'];
                $Number_of_Fraction = $row['Number_of_Fraction'];
                $Dose_per_Fraction = $row['Dose_per_Fraction'];
                $name_of_site = $row['name_of_site'];
                $Number_of_Fields = $row['Number_of_Fields'];

                $display .= "<caption><b>".$Treatment_Phase."</b></caption>";
                $display .= '<table  class="table table-collapse table-striped " style="border-collapse: collapse;border:1px solid black;">';
                $display .= '<tr>
                                <td>Total Tumor Dosage</td>
                                <th>'.$Tumor_Dose.' Grays</th>
                                <td>Total Number of Fraction</td>
                                <th>'.$Number_of_Fraction.'</th>
                            </tr>';
                $display .= '<tr>
                                <td>Dose Per Fraction</td>
                                <th>'.$Dose_per_Fraction.' Grays</th>

                                <td>Name of Site</td>
                                <th>'.$name_of_site.'</th>
                            </tr>
                            <tr>
                                <td>Number of Fields</td>
                                <th>'.$Number_of_Fields.'</th>
                            </tr>';
                $display .='</table>';
            }
            echo $display;
            echo "<tr>
                        <td style='text-align: right;'>
                            <input type='button' value='SAVE PRESCRIPTION' class='art-button-green' onclick='Submit_Radiotherapy($consultation_ID)' style='padding: 5px; border-radius: 5px;'>
                        </td>
                    </tr>";
        }else{
            
        }
    }else{
        
    }

    mysqli_close($conn);
?>