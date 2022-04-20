<?php
    session_start();
    include("./includes/connection.php");

    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo'])){
        if(isset($_SESSION['userinfo']['Storage_And_Supply_Work'])){
                if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){
                        header("Location: ./index.php?InvalidPrivilege=yes");
                } 
        }else{
                header("Location: ./index.php?InvalidPrivilege=yes");
        }
    }else{
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_GET['Outward_ID'])){
        $Outward_ID = $_GET['Outward_ID'];
    }else{
        $Outward_ID = 0;
    }

    $htm = "<table width ='100%' height = '30px'>
            <tr>
                <td>
                <img src='./branchBanner/branchBanner.png' width='100%'>
                </td>
            </tr>
            <tr><td>&nbsp;</td></tr>
            <tr>
                <td style='text-align: center;'><b>RETURN OUTWARD</b></td>
            </tr></table><br/>";

    $select = mysqli_query($conn,"select Outward_Date, Sub_Department_Name, Supplier_Name, Employee_Name from 
                            tbl_return_outward ro, tbl_sub_department sd, tbl_supplier sp, tbl_employee emp where 
                            sd.Sub_Department_ID = ro.Sub_Department_ID and
                            sp.Supplier_ID = ro.Supplier_ID and
                            emp.Employee_ID = ro.Employee_ID and
                            Outward_ID = '$Outward_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while ($data = mysqli_fetch_array($select)) {
            $Outward_Date = $data['Outward_Date'];
            $Sub_Department_Name = $data['Sub_Department_Name'];
            $Supplier_Name = $data['Supplier_Name'];
            $Employee_Name = $data['Employee_Name'];
        }
    }else{
        $Outward_Date = '000/00/00';
        $Sub_Department_Name = '';
        $Supplier_Name = '';
        $Employee_Name = '';
    }
    
    $htm .= '<table width="100%">
                <tr>
                    <td style="text-align: left;" width="20%"><span style="font-size: small;">Transaction No&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;</td>
                    <td width="15%"><span style="font-size: small;">'.$Outward_ID.'</span></td>
                    <td style="text-align: right;"><span style="font-size: small;">Receiving Supplier&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;</span></td>
                    <td><span style="font-size: small;">'.$Supplier_Name.'</span></td>
                    <td style="text-align: right;" width="20%"><span style="font-size: small;">Store Issued&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;</span></td>
                    <td><span style="font-size: small;">'.$Sub_Department_Name.'</span></td>
                </tr>
                <tr>
                    <td style="text-align: left;"><span style="font-size: small;">Transaction Date</span>&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;</td>
                    <td><span style="font-size: small;">'.date('d-m-Y', strtotime($Outward_Date)).'</span></td>
                    <td style="text-align: right;"><span style="font-size: small;">Posted By</span>&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;</td>
                    <td><span style="font-size: small;">'.$Employee_Name.'</span></td>
                </tr>
            </table><br/>';

    $htm .= '<table width="100%" border=1 style="border-collapse: collapse;">
                <tr>
                    <td width="5%"><span style="font-size: x-small;"><b>SN</b></span></td>
                    <td width="12%" style="text-align: center;"><span style="font-size: x-small;"><b>ITEM CODE</b></span></td>
                    <td><span style="font-size: x-small;"><b>ITEM NAME</b></span></td>
                    <td width="10%" style="text-align: center;"><span style="font-size: x-small;"><b>UOM</b></span></td>
                    <td width="15%" style="text-align: right;"><span style="font-size: x-small;"><b>QTY RETURNED</b></span></td>
                </tr>';    
        $temp = 0;
        $select = mysqli_query($conn,"select i.Unit_Of_Measure, i.Product_Name, roi.Quantity_Returned, i.Product_Code, roi.Item_Remark
                                from tbl_return_outward_items roi, tbl_items i where 
                                i.Item_ID = roi.Item_ID and Outward_ID = '$Outward_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select);
        if($num > 0){
            while ($row = mysqli_fetch_array($select)) {
                $htm .='<tr>
                            <td><span style="font-size: x-small;">'.++$temp.'</span></td>
                            <td style="text-align: center;"><span style="font-size: x-small;">'.$row['Product_Code'].'</span></td>
                            <td><span style="font-size: x-small;">'.$row['Product_Name'].'</span></td>
                            <td style="text-align: center;"><span style="font-size: x-small;">'.$row['Unit_Of_Measure'].'</span></td>
                            <td style="text-align: right;"><span style="font-size: x-small;">'.$row['Quantity_Returned'].'</span></td>
                        </tr>';
            }
        }

        $htm .= '</table><br/>';

    $htm .= '<table width="100%">
                <tr>
                    <td width="14%"><span style="font-size: small;">Returned By :&nbsp;&nbsp;&nbsp;</span></td>
                    <td><span style="font-size: small;">'.$Employee_Name.'</span></td>
                    <td style="text-align: right;" width="16%"><span style="font-size: small;">Received By :&nbsp;&nbsp;&nbsp;</span></td>
                    <td><span style="font-size: small;">'.$Supplier_Name.'</span></td>
                </tr>
                <tr>
                    <td><span style="font-size: small;">Signature&nbsp;&nbsp;&nbsp;</span></td>
                    <td>_____________________________________</td>
                    <td style="text-align: right;"><span style="font-size: small;">Signature&nbsp;&nbsp;&nbsp;</span></td>
                    <td>_____________________________________</td>
                </tr>
            </table>';
    
    include("./MPDF/mpdf.php");
    $mpdf=new mPDF('','Letter',0,'',12.7,12.7,14,12.7,8,8);
    $mpdf->SetFooter('Printed By '.strtoupper($Employee_Name).'|Page {PAGENO} of {nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
    exit;
?>
