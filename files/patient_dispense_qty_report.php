<?php
    include 'pharmacy-repo/interface.php';
    $Interface = new PharmacyInterface();

    $dates_From = $_GET['dates_From'];
    $dates_To = $_GET['dates_To'];
    $sub_department_id = $_GET['sub_department_id'];
    $Sponsor_ID = $_GET['Sponsor_ID'];
    $item_id = $_GET['item_id'];

    $Product_Name = $Interface->getItem($item_id);
?>
<table width='100%'>
    <thead>
        <tr style="background-color: #eee;">
            <td colspan="2" style="padding: 8px;font-weight:600">ITEM NAME ~ <?=$Product_Name?></td>
            <td colspan="2" style="padding: 8px;font-weight:600">START DATE ~ <?=$dates_To?></td>
            <td colspan="2" style="padding: 8px;font-weight:600">END DATE ~ <?=$dates_From?></td>
        </tr>
        <tr style="background-color: #eee;">
            <td style="padding: 8px;font-weight:600" width='5%'><center>S/N</center></td>
            <td style="padding: 8px;font-weight:600">PATIENT NAME</td>
            <td style="padding: 8px;font-weight:600" width='15%'>PATIENT NUMBER</td>
            <td style="padding: 8px;font-weight:600" width='15%'>SPONSOR</td>
            <td style="padding: 8px;font-weight:600" width='15%'>DISPENSED DATE & TIME</td>
            <td style="padding: 8px;font-weight:600" width='15%'><center>QUANTITY</center></td>
        </tr>
    </thead>

    <tbody id='show_details'></tbody>
</table>

<script>
    $(document).ready(() => { loadData(); });

    function loadData(){
        $.get('pharmacy-repo/common.php',{
            load_patient_dispensed_details:'load_patient_dispensed_details',
            dates_From:'<?=$dates_From?>',
            dates_To:'<?=$dates_To?>',
            sub_department_id:'<?=$sub_department_id?>',
            Sponsor_ID:'<?=$Sponsor_ID?>',
            item_id:'<?=$item_id?>'
        },(response) => { $('#show_details').html(response); });
    }
</script>