<?php
include("./includes/connection.php");
$year = $_POST['year'];
$month = $_POST['month'];

function getMonth($month){
        $newMonth = '';
        switch ($month) {
            case '1':{$newMonth = 'January';}break;
            case '2':{$newMonth = 'February';}break;
            case '3':{$newMonth = 'March';}break;
            case '4':{$newMonth = 'April';}break;
            case '5':{$newMonth = 'May';}break;
            case '6':{$newMonth = 'June';}break;
            case '7':{$newMonth = 'July';}break;
            case '8':{$newMonth = 'August';}break;
            case '9':{$newMonth = 'September';}break;
            case '10':{$newMonth = 'October';}break;
            case '11':{$newMonth = 'November';}break;
            case '12':{$newMonth = 'December';}break;

        }
        return $newMonth;
    }

function getMaxMinFolio($value,$year,$month){
    global $conn;
    $query = mysqli_fetch_assoc(mysqli_query($conn,  "SELECT MIN(Folio_No) MinFolio ,MAX(Folio_No) MaxFolio FROM tbl_claim_folio WHERE claim_month = '$month' AND claim_year = '$year'"));

    return $query[$value];
}
 ?>
<b>FOLIO STATISTICS</b>
<table width="50%" style="font-size: 18px;text-align: center;" class="statistic-table">
    <tr>
        <td style="text-align: center;"> <b>Year: &emsp;&emsp;<?=$year;?></b> </td>
    </tr>
    <tr>
        <td> <b>Month: &emsp;&emsp;<?=$month."&nbsp;(".getMonth($month).")";?></b> </td>
    </tr>
    <tr>
        <td> <b>First Folio No: &emsp;&emsp;<?=getMaxMinFolio('MinFolio',$year,$month);?></b> </td>
    </tr>
    <tr>
        <td> <b>Last Folio No: &emsp;&emsp;<?=getMaxMinFolio('MaxFolio',$year,$month);?></b> </td>
    </tr>
</table>
