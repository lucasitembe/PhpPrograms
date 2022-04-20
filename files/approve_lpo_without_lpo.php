<?php 
    include './includes/header.php';
    include 'procurement/procure.interface.php';

    $Procurement = new ProcureInterface();
    $count = 1; 
?>

<a href="purchaseorder.php?status=new&NPO=True&PurchaseOrder=PurchaseOrderThisPage" class="art-button-green">BACK</a>

<fieldset style="height: 550px;">
    <legend>APPROVE LIST OF LPO WITHOUT PR</legend>
    <table width='100%'>
        <tr style="background-color: #ddd;">
            <td style="padding: 6px;" width='5%'><center>S/N</center></td>
            <td width='15%' style="padding: 6px;"><center>DOCUMENT NUMBER</center></td>
            <td width='15%' style="padding: 6px;">SUPPLIER</td>
            <td width='15%' style="padding: 6px;">STORE</td>
            <td width='15%' style="padding: 6px;">CREATE DATE</td>
            <td width='15%' style="padding: 6px;">SUBMIT DATE</td>
            <td width='15%' style="padding: 6px;"><center>ACTION</center></td>
        </tr>

        <tbody>
            <?php foreach($Procurement->fetchAllSubmittedLPOWithoutPR_() as $Procure) : ?>
            <tr style="background-color: #fff;">
                <td style="padding:6px"><center><?=$count++?></center></td>
                <td style="padding:6px"><center><?=$Procure['ID']?></center></td>
                <td style="padding:6px"><?=ucwords($Procure['Supplier_Name'])?></td>
                <td style="padding:6px"><?=ucwords($Procure['Sub_Department_Name'])?></td>
                <td style="padding:6px"><?=$Procure['Created_AT']?></td>
                <td style="padding:6px"><?=$Procure['Submitted_At']?></td>
                <td style="padding:3px;text-align:center">
                    <?php if($Procure['Status'] == "submitted"){ ?>
                        <a href="approve_lpo_without_.php?ID=<?=$Procure['ID']?>" class="art-button-green">APPROVE</a>
                    <?php }else { ?>
                        <a href="approve_lpo_without_?ID=<?=$Procure['ID']?>" class="art-button-green">PREVIEW</a>
                    <?php } ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</fieldset>

<?php include './includes/footer.php'; ?>