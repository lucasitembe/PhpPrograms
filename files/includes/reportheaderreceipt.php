<?php

$retrieve = mysqli_query($conn,"SELECT Hospital_Name,Box_Address,Telephone,Cell_Phone,Fax,Tin FROM tbl_system_configuration WHERE Branch_ID='" . $_SESSION['userinfo']['Branch_ID'] . "'");
$data = mysqli_fetch_assoc($retrieve);

$hospital_Name = strtoupper($data['Hospital_Name']);
$box_Address = $data['Box_Address'];
$tel_phone = $data['Telephone'];
$cell_phone = $data['Cell_Phone'];
$fax = $data['Fax'];
$tin = $data['Tin'];

$htm = '';

$isReceipt = false;
$q = mysqli_query($conn,"SELECT Paper_Type FROM tbl_printer_settings") or die(mysqli_error($conn));
$row = mysqli_fetch_assoc($q);
$exist = mysqli_num_rows($q);
if ($exist > 0) {
    $Paper_Type = $row['Paper_Type'];
    if ($Paper_Type == 'Receipt') {
        $isReceipt = true;
    }
}


$htm .= "<table width='100%' border='0' cellpadding='0' cellspacing='0'>
          <tr>";
if (!$isReceipt) {
    $htm .= "<td style='text-align:center' colspan='2'>
			<img src='./branchBanner/branchBanner.png' width='100%'>
		    </td>";
} else {
    $htm .= "<td colspan='2'>
	    <center>
		<b>
		    $hospital_Name<br/> 
		
		$box_Address $cell_phone<br/> 
	    TIN: $tin  </center>
	</td>";
}
$htm .= "</tr></table>";
?>