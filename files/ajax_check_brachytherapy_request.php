<?php
include("includes/connection.php");
$Employee_ID = $_GET['Employee_ID'];
$consultation_ID = $_GET['consultation_ID'];
$Registration_ID = $_GET['Registration_ID'];
$Treatment_Phase = $_GET['Treatment_Phase'];
$Intent_of_Treatment = $_POST['Intent_of_Treatment'];

$display = '';
    $Brachytherapy_ID =mysqli_fetch_assoc(mysqli_query($conn, "SELECT Brachytherapy_ID FROM tbl_brachytherapy_requests WHERE consultation_ID = '$consultation_ID'"))['Brachytherapy_ID'];
    if($Brachytherapy_ID > 0){
        $select_details = mysqli_query($conn, "SELECT Number_of_Fraction, Dose_per_Fraction, Type_of_brachytherapy FROM tbl_brachytherapy_requests WHERE Brachytherapy_ID = '$Brachytherapy_ID'");
        if(mysqli_num_rows($select_details) > 0){
            while($row = mysqli_fetch_assoc($select_details)){
                $Number_of_Fraction = $row['Number_of_Fraction'];
                $Dose_per_Fraction = $row['Dose_per_Fraction'];
                $Type_of_brachytherapy = $row['Type_of_brachytherapy'];

                // $display .= "<caption><b>".$Treatment_Phase."</b></caption>";
                $display .= '<table  class="table table-collapse table-striped " style="border-collapse: collapse;border:1px solid black;">';
                $display .= '<tr>
                                <td>Total Number of Fraction</td>
                                <th>'.$Number_of_Fraction.'</th>

                                <td>Dose Per Fraction</td>
                                <th>'.$Dose_per_Fraction.' Grays</th>
                            </tr>';
                $display .= '<tr>
                                <td>Number of Fields</td>
                                <th>'.$Type_of_brachytherapy.'</th>
                                <td colspan="2"></td>
                            </tr>';
                $display .='</table>';
            }
            echo $display;
            echo "<tr>
                        <td style='text-align: right;'>
                            <input type='button' value='SAVE PRESCRIPTION' class='art-button-green' onclick='Submit_Brachytherapy($consultation_ID)' style='padding: 5px; border-radius: 5px;'>
                        </td>
                    </tr>";
        }else{
            
        }
    }

    mysqli_close($conn);
?>