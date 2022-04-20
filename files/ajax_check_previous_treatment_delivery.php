<?php
include("includes/connection.php");

$number_phases = $_GET['number_phases'];
$Radiotherapy_ID = $_GET['Radiotherapy_ID'];

$Select_Dates = mysqli_query($conn, "SELECT Date_field FROM tbl_treatment_delivery WHERE Radiotherapy_ID = '$Radiotherapy_ID' AND number_phases = '$number_phases' GROUP BY Date_field ORDER BY Date_field DESC") or die(mysqli_error($conn));
?>
        <div class="box-body" >
            <table class="table" style="background-color: white; margin:0% 0% 0% 5%;width:90%">

<?php


$numbers = mysqli_num_rows($Select_Dates);

$fraction = $numbers;

$select_field = mysqli_query($conn, "SELECT field_name FROM tbl_treatment_delivery td, tbl_fields_position ps WHERE Treatment_Status = 'delivered' AND Radiotherapy_ID = '$Radiotherapy_ID' AND number_phases = '$number_phases' AND field_ID = setup_devery_ID GROUP BY field_name ORDER BY setup_devery_ID ASC") or die(mysqli_error($conn));
    if(mysqli_num_rows($select_field) > 0){
        $display1 = '<td colspan="2">
        <b>Field</b>
    </td>';
    $Tittle = '<tr style="background: #dedede;">
    <td style="width:3% !important;">
        <b>FRACTION #</b>
    </td>
    <td width="8%">
        <b>Date</b>
    </td>';

    $taarifa1 = '</tr><tr style="background: #dedede; font-weight: bold;"><td>Date</td>';
    $display = '';

        while($dates = mysqli_fetch_assoc($select_field)){
            $field_name = $dates['field_name'];
            $field = "<td style='width: 20%' colspan='3'><h4>".ucwords($field_name)."</h4></td>";
            $data .= $field;

            $Tittle .='<td><b>Dose per Field</b></td>
            <td><b>Time</b></td>
            <td><b>Commulative Dose</b></td>';
        $select_data_simulation = mysqli_query($conn, "SELECT field_name FROM tbl_treatment_delivery td, tbl_fields_position ps WHERE Treatment_Status = 'delivered' AND Radiotherapy_ID = '$Radiotherapy_ID' AND number_phases = '$number_phases' AND field_ID = setup_devery_ID GROUP BY field_name ORDER BY setup_devery_ID ASC");
        }
        $Data_given = '';
        $Select_Date = mysqli_query($conn, "SELECT Date_field, em.Employee_Name FROM tbl_treatment_delivery td, tbl_employee em WHERE Radiotherapy_ID = '$Radiotherapy_ID' AND number_phases = '$number_phases' AND Treatment_Status = 'delivered' AND em.Employee_ID = td.Employee_ID GROUP BY Date_field ORDER BY Date_field DESC") or die(mysqli_error($conn));
        if(mysqli_num_rows($Select_Date) > 0){
            while($Dates = mysqli_fetch_assoc($Select_Date)){
                $Date_field = $Dates['Date_field'];
                $Employee_Name = $Dates['Employee_Name'];
                // $thisDate = date('d, M Y', strtotime($Date_field)) . '';
                $thisDate = date('jS, F Y (l)', strtotime($Date_field)) . '';



                $Data_given = "<tr><td style='background: #f2f4f4;'>".$fraction."</td>
                <td style='background: #f2f4f4; width: 10%'>".$thisDate."<br/>Treated By: <b>".ucwords($Employee_Name)."</b></td>";

                $select_previous = mysqli_query($conn, "SELECT Dose_per_Fraction1, Time1, Cummutive_Dose1 FROM tbl_treatment_delivery WHERE Radiotherapy_ID = '$Radiotherapy_ID' AND number_phases = '$number_phases' AND Treatment_Status = 'delivered' AND Date_field = '$Date_field' ORDER BY setup_devery_ID ASC") or die(mysqli_error($conn));
                    while($detials = mysqli_fetch_array($select_previous)){
                        $Dose_per_Fraction1 = $detials['Dose_per_Fraction1'];
                        $Time1 = $detials['Time1'];
                        $Cummutive_Dose1 = $detials['Cummutive_Dose1'];
                        // $Dose = $Dose_per_Fraction1
                        $Data_given .="<td>".$Dose_per_Fraction1."</td><td>".$Time1."</td><td style='background: #ebf5fb;'>".$Cummutive_Dose1."</td>";
                    }
            $Data_given_all .= $Data_given;

                $fraction--;

            }
        }
        $previous_datas = $Data_given_all;
        $data .='</tr>';
        $display1 .=$data;
    }else{
        $data = "<center><span style='font-size: 20px; text-align: center; color: red;'>NO ANY RECEIVED TREATMENT FOR THIS PATIENT FOR <b>".strtoupper($number_phases)."</b></span></center>";

    }

        echo $display1;
        echo $Tittle;
        echo $previous_datas;

mysqli_close($conn);
?>
</div>
</div>