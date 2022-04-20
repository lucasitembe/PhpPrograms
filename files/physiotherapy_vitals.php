<?php 
    include 'common/common.interface.php'; 
    $Result = new CommonInterface();
    $count = 1;
?>

<table width='100%'>
    <tr style="background-color: #eee;">
        <td width='15%' style="padding: 8px;font-weight:500"><center>S/N</center></td>
        <td style="padding: 8px;font-weight:500">VITAL SIGN</td>
        <td width='25%' style="padding: 8px;font-weight:500">INPUT</td>
    </tr>

    <tbody>
        <?php foreach($Result->getVitals() as $vital) :  ?>
            <tr>
                <td style="padding: 8px;"><center><?=$count++?></center></td>
                <td style="padding: 8px;"><?=$vital['Vital']?></td>
                <td style="padding: 5px;"><input type="text"></td>
            </tr>
        <?php endforeach; ?>
    </tbody>

    <tr>
        <td colspan="3" style="padding: 4px;text-align:end"><a href="#" class="art-button-green">SAVE VITALS</a></td>
    </tr>
</table>