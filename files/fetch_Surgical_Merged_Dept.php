<?php
include("includes/connection.php");

$Action = $_GET['Action'];
$num = 1;
$output = '';
if($Action == 'request'){
    $Select_Datas = mysqli_query($conn, "SELECT Attachement_ID, Sub_Department_Name, at.Theater_ID, at.Store_ID FROM tbl_attached_theater at, tbl_sub_department sd WHERE sd.Sub_Department_ID = at.Theater_ID") or die(mysqli_error($conn));
        if(mysqli_num_rows($Select_Datas)>0){
            while($details = mysqli_fetch_assoc($Select_Datas)){
                $Sub_Department_Name = $details['Sub_Department_Name'];
                $Theater_ID = $details['Theater_ID'];
                $Store_ID = $details['Store_ID'];
                $Attachement_ID = $details['Attachement_ID'];

                $Store_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Sub_Department_Name FROM tbl_sub_department WHERE Sub_Department_ID = '$Store_ID'"))['Sub_Department_Name'];
                $output .= "<tr><td>".$num."</td>";
                $output .= "<td>".$Sub_Department_Name."</td>"; 
                $output .= "<td>".$Store_Name."</td>"; 
                $output .= "<td><input type='button' value='X' onclick='Remove_Dept(".$Attachement_ID.")' class='art-button-green'></td></tr>";
            $num++; 
            }
        }else{
            $output .="<tr><td style='font-size: 17px; color: #bd0d0d; font-weight: bold; text-align: center;' colspan='4'>NO DEPARTMENT MERGED</td></tr>";
        }

        echo $output;
}

?>