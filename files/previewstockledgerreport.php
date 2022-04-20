<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Start_Date'])){
		$Start_Date = $_GET['Start_Date'];
	}else{
		$Start_Date = '';
	}

	if(isset($_GET['End_Date'])){
		$End_Date = $_GET['End_Date'];
	}else{
		$End_Date = '';
	}

	if(isset($_GET['Item_ID'])){
		$Item_ID = $_GET['Item_ID'];
	}else{
		$Item_ID = '';
	}

    //get item name
    $select = mysqli_query($conn,"select Product_Name from tbl_items where Item_ID = '$Item_ID'") or die(mysqli_error($conn));
    $nums = mysqli_num_rows($select);
    if($nums > 0){
        while ($row = mysqli_fetch_array($select)) {
            $Product_Name = $row['Product_Name'];
        }
    }else{
        $Product_Name = '';
    }

	if(isset($_SESSION['Storage_Info']['Sub_Department_ID'])){
        $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
    }else{
        $Sub_Department_ID = '';
    }
    
    
    //get sub department name
    $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while($row = mysqli_fetch_array($select)){
            $Sub_Department_Name = $row['Sub_Department_Name'];
        }
    }else{
        $Sub_Department_Name = '';
    }

    $htm = "<table width ='100%' height = '30px'>
        <tr>
            <td>
            <img src='./branchBanner/branchBanner.png' width=100%>
            </td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr>
            <td style='text-align: center;'><b>STOCK LEDGER REPORT</b></td>
        </tr></table><br/>";
    $htm .= '<table>
                <tr><td>Start Date : </td><td>'.$Start_Date.'</td></tr>
                <tr><td>End Date : </td><td>'.$End_Date.'</td></tr>
                <tr><td>Item Name : </td><td>'.$Product_Name.'</td></tr>
            </table>';
    //get details
    $select = mysqli_query($conn,"select * from tbl_stock_ledger_controler where Movement_Date between '$Start_Date' and '$End_Date' and Item_ID = '$Item_ID' and Sub_Department_ID = '$Sub_Department_ID' order by Controler_ID") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select);
    if($no > 0){
        $controler = 'yes';
        $Total_inward = 0;
        $Total_outward = 0;
        $temp = 0;
        $htm .= "<table width='100%'>";
        $Title =  "<tr><td colspan='7'><hr></td></tr>
                    <tr><td width='3%'><b>SN</b></td>
                    <td width='8%'><b>DOC NO</b></td>
                    <td width='10%'><b>DOC DATE</b></td>
                    <td><b>NARRATION</b></td>
                    <td width='10%' style='text-align: right'><b>INWARD FLOW</b></td>
                    <td width='10%' style='text-align: right'><b>OUTWARD FLOW</b></td>
                    <td width='13%' style='text-align: right'><b>RUNNING BALANCE</b></td>
                </tr>
                <tr><td colspan='7'><hr></td></tr>";
    	while ($data = mysqli_fetch_array($select)) {
            $Movement_Type = $data['Movement_Type'];
            $Internal_Destination = $data['Internal_Destination'];
            $External_Source = $data['External_Source'];
            $Pre_Balance = $data['Pre_Balance'];
            $Movement_Date = $data['Movement_Date'];
            $Movement_Date_Time = $data['Movement_Date_Time'];
            $Registration_ID = $data['Registration_ID'];
            if($controler == 'yes'){
                $htm .= "<tr><td colspan='7' style='text-align: right;'><b>B/F : ".$Pre_Balance."&nbsp;&nbsp;&nbsp;&nbsp;</b></td></tr>";
                //$htm .= "<tr><td colspan='7'><hr></td></tr>";
                $controler = 'no';
                $htm .= $Title;
            }
            if($Movement_Type == 'From External'){
                //get supplier name
                $slct = mysqli_query($conn,"select Supplier_Name from tbl_supplier where Supplier_ID = '$External_Source'") or die(mysqli_error($conn));
                $numz = mysqli_num_rows($slct);
                if($numz > 0){
                    while ($rw = mysqli_fetch_array($slct)) {
                        $Supplier_Name = $rw['Supplier_Name'];
                    }
                }else{
                    $Supplier_Name = '';
                }
                $Total_inward += ($data['Post_Balance'] - $data['Pre_Balance']);
                $htm .= '<tr><td>'.++$temp.'<b>.</b></td>
                            <td>'.$data['Document_Number'].'</td>
                            <td>'.$data['Movement_Date'].'</td>
                            <td>'.strtoupper($Supplier_Name).'</td>
                            <td style="text-align: right">'.($data['Post_Balance'] - $data['Pre_Balance']).'</td>
                            <td style="text-align: right">0</td>
                            <td style="text-align: right">'.$data['Post_Balance'].'</td>
                        </tr>';

                $Grand_Balance = $data['Post_Balance'];
            }else if($Movement_Type == 'Without Purchase'){
                //get supplier name
                $slct = mysqli_query($conn,"select Supplier_Name from tbl_supplier where Supplier_ID = '$External_Source'") or die(mysqli_error($conn));
                $numz = mysqli_num_rows($slct);
                if($numz > 0){
                    while ($rw = mysqli_fetch_array($slct)) {
                        $Supplier_Name = $rw['Supplier_Name'];
                    }
                }else{
                    $Supplier_Name = '';
                }
                $Total_inward += ($data['Post_Balance'] - $data['Pre_Balance']);

                $htm .= '<tr><td>'.++$temp.'<b>.</b></td>
                            <td>'.$data['Document_Number'].'</td>
                            <td>'.$data['Movement_Date'].'</td>
                            <td>'.strtoupper($Supplier_Name).'</td>
                            <td style="text-align: right">'.($data['Post_Balance'] - $data['Pre_Balance']).'</td>
                            <td style="text-align: right">0</td>
                            <td style="text-align: right">'.$data['Post_Balance'].'</td>
                        </tr>';

                $Grand_Balance = $data['Post_Balance'];
            }else if($Movement_Type == 'Open Balance'){
                $Total_inward = $data['Post_Balance'];
                $Total_outward = 0;

                $htm .= '<tr><td>'.++$temp.'<b>.</b></td>
                    <td>'.$data['Document_Number'].'</td>
                    <td>'.$data['Movement_Date'].'</td>
                    <td>OPEN BALANCE / STOCK TAKING</td>
                    <td style="text-align: right">'.$data['Post_Balance'].'</td>
                    <td style="text-align: right">0</td>
                    <td style="text-align: right">'.$data['Post_Balance'].'</td>
                </tr>';

                $Grand_Balance = $data['Post_Balance'];
            }else if($Movement_Type == 'Issue Note'){
                //get department requested
                $sub_D = $data['Internal_Destination'];
                $slct = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$sub_D'") or die(mysqli_error($conn));
                $numz = mysqli_num_rows($slct);
                if($numz > 0){
                    while ($dts = mysqli_fetch_array($slct)) {
                        $Sub_Department_Name = $dts['Sub_Department_Name'];
                    }
                }else{
                    $Sub_Department_Name = '';
                }
                $Total_outward += ($data['Pre_Balance'] - $data['Post_Balance']);

                $htm .= '<tr><td>'.++$temp.'<b>.</b></td>
                    <td>'.$data['Document_Number'].'</td>
                    <td>'.$data['Movement_Date'].'</td>
                    <td>'.$Sub_Department_Name.'</td>
                    <td style="text-align: right;">0</td>
                    <td style="text-align: right;">'.($data['Pre_Balance'] - $data['Post_Balance']).'</td>
                    <td style="text-align: right">'.$data['Post_Balance'].'</td>
                </tr>';

                $Grand_Balance = $data['Post_Balance'];
            }else if($Movement_Type == 'Dispensed'){
                $slct_name = mysqli_query($conn,"select Patient_Name from tbl_patient_registration where Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
                $num_patient = mysqli_num_rows($slct_name);
                if($num_patient > 0){
                    while ($rwz = mysqli_fetch_array($slct_name)) {
                        $Patient_Name = $rwz['Patient_Name'];
                    }
                }else{
                    $Patient_Name = '';
                }
                $Total_outward += ($data['Pre_Balance'] - $data['Post_Balance']);
                $htm .= '<tr><td>'.++$temp.'<b>.</b></td>
                            <td>'.$data['Document_Number'].'</td>
                            <td>'.$data['Movement_Date'].'</td>
                            <td>'.ucwords(strtolower($Patient_Name)).'</td>
                            <td style="text-align: right;">0</td>
                            <td style="text-align: right;">'.($data['Pre_Balance'] - $data['Post_Balance']).'</td>
                            <td style="text-align: right">'.$data['Post_Balance'].'</td>
                        </tr>';
                 $Grand_Balance = $data['Post_Balance'];
            }
        }
        $htm .= "<tr><td colspan='7'><hr></td></tr>";
        $htm .= "<tr><td colspan='4'>
                    <td style='text-align: right;'>".$Total_inward."</td>
                    <td style='text-align: right;'>".$Total_outward."</td>
                    <td style='text-align: right;'>".$Grand_Balance."</td>
                    </tr>";
        $htm .= "<tr><td colspan='7'><hr></td></tr>";
        $htm .= "</table>";
    }else{
    	$htm .= "<br/><br/><br/><br/>";
    	$htm .= "<center><h3><b>NO RECORDS FOUND</b></h3></center>";
    }

    include("./functions/makepdf.php");
?>