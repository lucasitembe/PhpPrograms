<?php 

    include './includes/connection.php';
    include 'store/store.interface.php';
    $Store = new StoreInterface();

    $Current_Store_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
    $adjustment_details = $Store->getReadAdjustments($_GET['Sub_Department_ID'],$_GET['adjustment'],"all","","");
    $adjustment_items = $Store->getAdjustedItems($_GET['adjustment'],$_GET['Sub_Department_ID']);
    $counter = 1;

    $check_submission = ($adjustment_details[0]['Disposed_Date'] == "0000-00-00") ? "<span style='color:red'>Not Submitted</span>" : $adjustment_details[0]['Disposed_Date'];
    
    $htm .= "
        <style>
            body{ font-family:arial; }
            table{ border-collapse:collapse; }
        </style>";

    $htm .= "<table width ='100%' height = '30px' style='font-family:arial'>";
    $htm .= "<tr> <td> <img src='./branchBanner/branchBanner.png' width=100%> </td> </tr>";
    $htm .= "<tr><td>&nbsp;</td></tr>";
    $htm .= "<tr> <td style='text-align: center;'><h3>ADJUSTMENT</h3></td> </tr>";
    $htm .= "</table>";

    $htm .= "
        <br/>
        <table width='100%'>
            <tr>
                <td width='25%'>Adjustment Number : </td> <td width='25%'>{$adjustment_details[0]['Disposal_ID']}</td>
                <td width='25%'>Adjustment Date : </td> <td width='25%'>{$check_submission}</td>
            </tr>
            <tr>
                <td>Adjustment Location : </td> <td>".ucwords($adjustment_details[0]['Sub_Department_Name'])."</td>
                <td>Adjustment Officer : </td> <td>".ucwords($adjustment_details[0]['Employee_Name'])."</td>
            </tr>
            <tr>
                <td>Adjustment Description : </td> <td>{$adjustment_details[0]['Disposal_Description']}</td>
                <td>Adjustment Reason : </td> <td>{$adjustment_details[0]['name']}</td>
            </tr>
        </table>
        </br></br>
        <br/>
        <table width='100%' border='1'>
            <tr style='background-color:#ddd'>
                <td style='text-align:center' width='10%'>S/N</td> 
                <td>Item Name</td>
                <td width='20%' style='text-align:center'>Quantity</td> 
                <td width='20%'>Remark</td>
            </tr>";

    if(sizeof($adjustment_items) > 0){
        foreach($adjustment_items as $items){
            $check_remark = ($items['Item_Remark'] == "") ? "Not Provided" : $items['Item_Remark'];
            $htm .= "
                <tr>
                    <td style='text-align:center' width='10%'>".$counter++."</td> 
                    <td>{$items['Product_Name']}</td>
                    <td width='20%' style='text-align:center'>{$items['Quantity_Disposed']}</td> 
                    <td width='20%'>{$check_remark}</td>
                </tr>
            ";
        }
    }else{
        $htm .= "
            <tr>
                <td style='text-align:center' width='10%' colspan='4'>No Item Added</td> 
            </tr>
        ";
    }

    $htm .= "</table><br><br><br><table>";
    $htm .= "<tr>";
    $htm .= "<td style='text-align:left;width:30%'>Issue Officer Sign : </td>";
    $htm .= "<td style='text-align:left;width:15%'><b>________________________________</b></td>";
    $htm .= "<td style='text-align:right;width:10%'></td>";
    $htm .= "<td style='text-align:left;width:30%'></td>";
    $htm .= "<td style='text-align:left;width:15%'></td>";
    $htm .= "</tr>";
    $htm .= "<tr>";
    $htm .= "<td style='text-align:left;width:30%'>Issue Officer : </td>";
    $htm .= "<td style='text-align:left;width:15%'><b> ".ucwords($adjustment_details[0]['Employee_Name'])." </b></td>";
    $htm .= "<td style='text-align:right;width:10%'></td>";
    $htm .= "<td style='text-align:left;width:30%'></td>";
    $htm .= "<td style='text-align:left;width:15%'></td>";
    $htm .= "</tr>";
    $htm .= "</table>";


    include("./functions/makepdf.php");
?>