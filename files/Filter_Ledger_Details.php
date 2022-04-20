<?php  
    include 'store/store.interface.php'; 
    $Interface = new StoreInterface();
    $Start_Date = (isset($_GET['Start_Date'])) ? $_GET['Start_Date'] : 0;
    $End_Date = (isset($_GET['End_Date'])) ? $_GET['End_Date'] : 0;
    $Item_ID = (isset($_GET['Item_ID'])) ? $_GET['Item_ID'] : 0;
    $Sub_Department_ID = (isset($_GET['Sub_Department_ID'])) ? $_GET['Sub_Department_ID'] : 0;
    $count = 1;
    $Total_Received = 0;
    $Total_Issued = 0;
    $Total_Adjusted = 0;
    $Total_Stock_tacking = 0;
    $Stock_Details = $Interface->fetchStockDetails_($Start_Date,$End_Date,$Sub_Department_ID,$Item_ID);
?>

<table width='100%'>
    <tr style="font-weight: 500;background-color:#eee">
        <td style="padding: 6px;" width='5%'><center>S/N</center></td>
        <td style="padding: 6px;" width='7%'>DOC NO</td>
        <td style="padding: 6px;" width='8%'>DOC DATE</td>
        <td style="padding: 6px;">NARRATION</td>
        <td style="padding: 6px;" width='9.5%'><center>OPEN BALANCE</center></td>
        <td style="padding: 6px;" width='8%'><center>RECEIVED</center></td>
        <td style="padding: 6px;" width='8%'><center>ISSUED</center></td>
        <td style="padding: 6px;" width='8%'><center>ADJUSTMENT</center></td>
        <td style="padding: 6px;" width='8%'><center>BALANCE</center></td>
        <td style="padding: 6px;" width='8%'>REASON</td>
        <td style="padding: 6px;" width='8%'>EMPLOYEE</td>
    </tr>

    <tbody>
        <?php if(sizeof($Stock_Details) > 0){ ?>
	<tr><td colspan="8"></td><td style="padding: 5px;text-align:center"><b>B/F : <?=$Stock_Details[0]['Pre_Balance']?></b></td></tr>
            <?php foreach ($Stock_Details as $Details) : ?>
                <?php if($Details["Movement_Type"] == "Dispensed") {  ?>
                    <?php 
                        $Total_Issued += ($Details['Pre_Balance'] - $Details['Post_Balance']);
                        $Grand_Balance = $Details['Post_Balance'];
                        $Transaction_Details = $Interface->getLedgerDetailsForDispensedMedication_($Details['Document_Number'],$Item_ID);
                    ?>
                    <tr style="background-color: #fff;">
                        <td style="padding: 6px;"><center><?=$count++?></center></td>
                        <td style="padding: 6px;"><?=$Details['Document_Number']?></td>
                        <td style="padding: 6px;"><?=$Details['Movement_Date']?></td>
                        <td style="padding: 6px;"><?=$Details['Movement_Type']?> to ~ <?=ucwords($Transaction_Details[0]['Patient_Name'])?></td>
                        <td style="padding: 6px;"><center>0</center></td>
                        <td style="padding: 6px;"><center>0</center></td>
                        <td style="padding: 6px;"><center><?=($Details['Pre_Balance'] - $Details['Post_Balance'])?></center></td>
                        <td style="padding: 6px;"><center>0</center></td>
                        <td style="padding: 6px;"><center><?=$Details['Post_Balance']?></center></td>
                        <td style="padding: 6px;">Dispensed</td>
                        <td style="padding: 6px;"><?=$Transaction_Details[0]['Employee_Name']?></td>
                    </tr>
                <?php } else if($Details["Movement_Type"] == "Open Balance") {  ?>
                    <?php 
                        $Total_Stock_tacking = $Total_Stock_tacking + $Details['Post_Balance'];
                        $Grand_Balance = $Details['Post_Balance'];
                        $Transaction_Details_For_Stock_Details = $Interface->getLedgerDetailsForStockTacking_($Details['Document_Number'],$Item_ID);
                    ?>
                    <tr style="background-color: #fff;">
                        <td style="padding: 6px;"><center><?=$count++?></center></td>
                        <td style="padding: 6px;"><?=$Details['Document_Number']?></td>
                        <td style="padding: 6px;"><?=$Details['Movement_Date']?></td>
                        <td style="padding: 6px;">Stock Taking</td>
                        <td style="padding: 6px;"><center><?=$Details['Post_Balance']?></center></td>
                        <td style="padding: 6px;"><center>0</center></td>
                        <td style="padding: 6px;"><center>0</center></td>
                        <td style="padding: 6px;"><center>0</center></td>
                        <td style="padding: 6px;"><center><?=$Details['Post_Balance']?></center></td>
                        <td style="padding: 6px;"><?=$Transaction_Details_For_Stock_Details[0]['reasons']?></td>
                        <td style="padding: 6px;"><?=ucwords($Transaction_Details_For_Stock_Details[0]['Employee_Name'])?></td>
                    </tr>
                <?php } else if($Details["Movement_Type"] == "Without Purchase") { ?>
                    <?php 
                        $Total_Received += ($Details['Post_Balance']-$Details['Pre_Balance']);
                        $Grand_Balance = $Details['Post_Balance'];
                        $Transaction_DetailsForGRNWithoutPR = $Interface->getLedgerDetailsForGRNWithoutPR_($Details['Document_Number']);
                    ?>
                    <tr style="background-color: #fff;">
                        <td style="padding: 6px;"><center><?=$count++?></center></td>
                        <td style="padding: 6px;"><?=$Details['Document_Number']?></td>
                        <td style="padding: 6px;"><?=$Details['Movement_Date']?></td>
                        <td style="padding: 6px;"><a href='previewgrnwithoutpurchaseorderreport.php?Grn_ID=<?=$Details['Document_Number']?>' target='_blank'><?=ucwords($Transaction_DetailsForGRNWithoutPR[0]['Supplier_Name'])?></a></td>
                        <td style="padding: 6px;"><center>0</center></td>
                        <td style="padding: 6px;"><center><?=($Details['Post_Balance'] - $Details['Pre_Balance'])?></center></td>
                        <td style="padding: 6px;"><center>0</center></td>
                        <td style="padding: 6px;"><center>0</center></td>
                        <td style="padding: 6px;"><center><?=$Details['Post_Balance']?></center></td>
                        <td style="padding: 6px;">Without Purchase</td>
                        <td style="padding: 6px;"><?=$Transaction_DetailsForGRNWithoutPR[0]['Employee_Name'];?></td>
                    </tr>
                <?php } else if($Details["Movement_Type"] == "Issue Note") { ?>
                    <?php 
                        $getLedgerDetailsForIssueDetails = $Interface->getLedgerDetailsForIssueDetails_($Details['Document_Number']);
                        $Total_Issued += ($Details['Pre_Balance'] - $Details['Post_Balance']);
                        $Grand_Balance = $Details['Post_Balance'];
			$Display__ = ($getLedgerDetailsForIssueDetails[0]['Issue_Description'] == "") ? "" : " ~ ".$getLedgerDetailsForIssueDetails[0]['Issue_Description'];
                    ?>
                    <tr style="background-color: #fff;">
                        <td style="padding: 6px;"><center><?=$count++?></center></td>
                        <td style="padding: 6px;"><?=$Details['Document_Number']?></td>
                        <td style="padding: 6px;"><?=$Details['Movement_Date']?></td>
                        <td style="padding: 6px;"><a target='_blank' href='previousissuenotereport.php?Issue_ID=<?=$Details['Document_Number']?>'>Issue ~ ( <?=ucwords($getLedgerDetailsForIssueDetails[0]['Sub_Department_Name'])?>)<?=$Display__?></a></td>
                        <td style="padding: 6px;"><center>0</center></td>
                        <td style="padding: 6px;"><center>0</center></td>
                        <td style="padding: 6px;"><center><?=($Details['Pre_Balance'] - $Details['Post_Balance'])?></center></td>
                        <td style="padding: 6px;"><center>0</center></td>
                        <td style="padding: 6px;"><center><?=$Details['Post_Balance']?></center></td>
                        <td style="padding: 6px;">Issue</td>
                        <td style="padding: 6px;"><?=$getLedgerDetailsForIssueDetails[0]['Employee_Name']?></td>
                    </tr>
                <?php } else if ($Details["Movement_Type"] == 'GRN Agains Issue Note') { ?>
                    <?php 
                        $getLedgerDetailsForIssueDetails = $Interface->getLedgerDetailsForGRNAgainstIssueNote_($Details['Document_Number']);
                        $Total_Received += ($Details['Post_Balance'] - $Details['Pre_Balance']);
                        $Grand_Balance = $Details['Post_Balance'];
                    ?>
                    <tr style="background-color: #fff;">
                        <td style="padding: 6px;"><center><?=$count++?></center></td>
                        <td style="padding: 6px;"><?=$Details['Document_Number']?></td>
                        <td style="padding: 6px;"><?=$Details['Movement_Date']?></td>
                        <td style="padding: 6px;"><a target='_blank' href='requisition_preview.php?Requisition_ID=<?=$Details['Document_Number']?>'>Requested From ~ <?=$getLedgerDetailsForIssueDetails[0]['Sub_Department_Name']?></a></td>
                        <td style="padding: 6px;"><center>0</center></td>
                        <td style="padding: 6px;"><center><?=($Details['Post_Balance'] - $Details['Pre_Balance'])?></center></td>
                        <td style="padding: 6px;"><center>0</center></td>
                        <td style="padding: 6px;"><center>0</center></td>
                        <td style="padding: 6px;"><center><?=$Details['Post_Balance']?></center></td>
                        <td style="padding: 6px;">GRN Against Issue Note</td>
                        <td style="padding: 6px;"><?=$getLedgerDetailsForIssueDetails[0]['Employee_Name']?></td>
                    </tr>
                <?php } else if ($Details["Movement_Type"] == 'ADJUSTMENT'){ ?>
                    <?php 
                        $Grand_Balance = $Details['Post_Balance'];
                        $getLedgerDetailsForAdjustment = $Interface->getLedgerDetailsForAdjustment_($Details['Document_Number']);
                    ?>
                    <tr style="background-color: #fff;">
                        <td style="padding: 6px;"><center><?=$count++?></center></td>
                        <td style="padding: 6px;"><?=$Details['Document_Number']?></td>
                        <td style="padding: 6px;"><?=$Details['Movement_Date']?></td>
                        <td style="padding: 6px;"><a target='_blank' href='itemsdisposal_preview.php?Disposal_ID=<?=$Details['Document_Number']?>'>Adjustment ~ <?=$getLedgerDetailsForAdjustment[0]['name']?></a></td>
                        <td style="padding: 6px;"><center>0</center></td>
                        <td style="padding: 6px;"><center>0</center></td>
                        <td style="padding: 6px;"><center>0</center></td>
                        <td style="padding: 6px;"><center><?=($Details['Post_Balance'] - $Details['Pre_Balance'])?></center></td>
                        <td style="padding: 6px;"><center><?=($Details['Post_Balance'])?></center></td>
                        <td style="padding: 6px;"><?=$getLedgerDetailsForAdjustment[0]['Disposal_Description']?></td>
                        <td style="padding: 6px;"><?=$getLedgerDetailsForAdjustment[0]['Employee_Name']?></td>
                    </tr>
                <?php } ?>
            <?php endforeach; ?>
            <tr>
                <tr style="font-weight: 500;background-color:#eee">
                    <td style="padding: 6px;" colspan="4">GRAND TOTAL</td>
                    <td style="padding: 6px;"><center><?=$Total_Stock_tacking?></center></td>
                    <td style="padding: 6px;"><center><?=$Total_Received?></center></td>
                    <td style="padding: 6px;"><center><?=$Total_Issued?></center></td>
                    <td style="padding: 6px;"><center></center></td>
                    <td style="padding: 6px;"><center><?=$Grand_Balance?></center></td>
                    <td colspan="2"></td>
                </tr>
            </tr>
        <?php }else {?>
            <tr>
                <td style="padding: 8px;color:#b14c4c;font-weight:500" colspan="11"><center>NO MOVEMENT FOUND THE SELECTED ITEM BETWEEN <?=$Start_Date?> TO <?=$End_Date?></center></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
