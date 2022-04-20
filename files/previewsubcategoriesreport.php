<?php
	session_start();
	include("./includes/connection.php");
	
    if(isset($_SESSION['userinfo']['Employee_Name'])){
        $E_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
        $E_Name = '';
    }

    if(isset($_GET['Start_Date'])){
        $Start_Date = mysqli_real_escape_string($conn,$_GET['Start_Date']);
    }else{
        $Start_Date = '0000/00/00 00:00:00';
    }

    if(isset($_GET['End_Date'])){
        $End_Date = mysqli_real_escape_string($conn,$_GET['End_Date']);
    }else{
        $End_Date = '0000/00/00 00:00:00';
    }

    if(isset($_GET['Item_Category_ID'])){
        $Item_Category_ID = $_GET['Item_Category_ID'];
    }else{
        $Item_Category_ID = 0;
    }

    if(isset($_GET['Hospital_Ward_ID'])){
        $Hospital_Ward_ID = $_GET['Hospital_Ward_ID'];
    }else{
        $Hospital_Ward_ID = 'none';
    }

    if($Hospital_Ward_ID != 'none' && $Hospital_Ward_ID != '0'){
        //SELECT WARD
        $slct_ward = mysqli_query($conn,"select Hospital_Ward_Name from tbl_hospital_ward where Hospital_Ward_ID = '$Hospital_Ward_ID'") or die(mysqli_error($conn));
        $n_ward = mysqli_num_rows($slct_ward);
        if($n_ward > 0){
            while ($dzt = mysqli_fetch_array($slct_ward)) {
                $Ward = $dzt['Hospital_Ward_Name'];
                $Ward_Title = $dzt['Hospital_Ward_Name']." ";
            }
        }else{
            $Ward = '';
            $Ward_Title = '';
        }
    }else if($Hospital_Ward_ID == '0'){
        $Ward = 'ALL WARDS';
        $Ward_Title = 'ALL WARDS ';
    }else{
        $Ward = '';
        $Ward_Title = '';
    }
    
    
if (isset($_GET['finance_department_id'])) {
    $finance_department_id = $_GET['finance_department_id'];
} else {
    $finance_department_id = 'none';
}
//get finance department name
$finance_department_name="";
$sql_select_finance_department_name_result=mysqli_query($conn,"SELECT finance_department_name FROM tbl_finance_department WHERE finance_department_id='$finance_department_id'") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_finance_department_name_result)>0){
   $finance_department_name=mysqli_fetch_assoc($sql_select_finance_department_name_result)['finance_department_name']; 
}
    //get Item_Category_Name
    $select = mysqli_query($conn,"select Item_Category_Name from tbl_item_category where Item_Category_ID = '$Item_Category_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while($data = mysqli_fetch_array($select)){
            $Item_Category_Name = $data['Item_Category_Name'];
        }
    }else{
        $Item_Category_Name = '';
    }

    $filter = "pp.Payment_Date_And_Time between '$Start_Date' and '$End_Date'  and ic.Item_Category_ID = '$Item_Category_ID' and  ";
   if (isset($_GET['clinic']) && $_GET['clinic'] != "ALL") {
    $Clinic_ID = mysqli_real_escape_string($conn,$_GET['clinic']);
    //$filter .= "('$Clinic_ID' IN (SELECT Clinic_ID FROM tbl_clinic_employee WHERE Employee_ID=ppl.Consultant_ID) OR ppl.Clinic_ID='$Clinic_ID') and ";
     $filter .= "ppl.Clinic_ID='$Clinic_ID' and ";
    $clinics = "select * from tbl_clinic where Clinic_ID = '$Clinic_ID'";
    $clinic = mysqli_query($conn,$clinics);
    $num = mysqli_num_rows($clinic);
    if ($num > 0) {
        while ($row = mysqli_fetch_array($clinic)) {
            $Clinic_Name = $row['Clinic_Name'];
        }
    }
}else {
   $Clinic_ID = mysqli_real_escape_string($conn,$_GET['clinic']);
    $Clinic_Name = "ALL";
} 
    if(isset($_GET['Sponsor_ID'])){
        $Sponsor_ID = mysqli_real_escape_string($conn,$_GET['Sponsor_ID']);
    }else{
        $Sponsor_ID = '';
    }

    //get Sponsor
    if($Sponsor_ID == 0){
        $Sponsor = 'All';
    }else{
        $select = mysqli_query($conn,"select Guarantor_Name from tbl_sponsor where Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if($no > 0){
            while($row = mysqli_fetch_array($select)){
                $Sponsor = $row['Guarantor_Name'];
            }
        }else{
            $Sponsor = '0';
        }
    }

    if(isset($_GET['Employee_ID'])){
        $Employee_ID = mysqli_real_escape_string($conn,$_GET['Employee_ID']);
    }else{
        $Employee_ID = '';
    }

    //get employee
    if($Employee_ID == '0'){
        $Emp_Name = 'All';
    }else{
        $select = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select);
        if($num > 0){
            while($row = mysqli_fetch_array($select)){
                $Emp_Name = $row['Employee_Name'];
            }
        }
    }

    if(isset($_GET['Section'])){
        $Section = mysqli_real_escape_string($conn,$_GET['Section']);
    }else{
        $Section = '';
    }

    //create Report_Type
    if(strtolower($Section) == 'cashcredit'){
        $Report_Type = 'Cash And Credit';
    }else if(strtolower($Section) == 'cash'){
        $Report_Type = 'Cash';
    }else if(strtolower($Section) == 'credit'){
        $Report_Type = 'Credit';
    }else{
        $Report_Type = 'Cancelled';
    }

    if(isset($_GET['Patient_Type'])){
        $Patient_Type = mysqli_real_escape_string($conn,$_GET['Patient_Type']);
    }else{
        $Patient_Type = '';
    }

    if(isset($_GET['Section'])){
    	$Section = $_GET['Section'];
    }else{
    	$Section = '';
    }

    //dont complicate, understand slowly prog
    if($Patient_Type == 'Inpatient'){
        if(strtolower($Section) == 'cash'){
            $filter .= "Billing_Type = 'Inpatient Cash' and pp.payment_type = 'pre' and Transaction_status <> 'cancelled' and ";
        }elseif(strtolower($Section) == 'credit'){
            $filter .= "(Billing_Type = 'Inpatient Credit') and Transaction_status <> 'cancelled' and ";
        }elseif(strtolower($Section) == 'cancelled'){
            $filter .= "(Billing_Type = 'Inpatient Cash' or Billing_Type = 'Inpatient Credit') and Transaction_status = 'cancelled' and ";
        }else{
            $filter .= "((Billing_Type = 'Inpatient Cash'and pp.payment_type='pre') or Billing_Type = 'Inpatient Credit') and Transaction_status <> 'cancelled' and ";
        }

        //filter according to ward selected
        if($Hospital_Ward_ID != 'none' && $Hospital_Ward_ID != '0'){
            $filter .= " pp.Hospital_Ward_ID = '$Hospital_Ward_ID' and ";
        }
    }else if($Patient_Type == 'Outpatient'){
        if(strtolower($Section) == 'cash'){
            $filter .= "Billing_Type = 'Outpatient Cash' and Pre_Paid = '0' and Transaction_status <> 'cancelled' and ";
        }elseif(strtolower($Section) == 'credit'){
            $filter .= "(Billing_Type = 'Outpatient Credit') and Transaction_status <> 'cancelled' and ";
        }elseif(strtolower($Section) == 'cancelled'){
            $filter .= "(Billing_Type = 'Outpatient Cash' or Billing_Type = 'Outpatient Credit') and Transaction_status = 'cancelled' and ";
        }else{
            $filter .= "((Billing_Type = 'Outpatient Cash'and Pre_Paid = '0') or Billing_Type = 'Outpatient Credit') and Transaction_status <> 'cancelled' and ";
        }
    }else{
        if(strtolower($Section) == 'cash'){
            $filter .= "((Billing_Type = 'Outpatient Cash' and Pre_Paid = '0') or (Billing_Type = 'Inpatient Cash' and pp.payment_type = 'pre')) and Transaction_status <> 'cancelled' and ";
        }elseif(strtolower($Section) == 'credit'){
            $filter .= "(Billing_Type = 'Outpatient Credit' or Billing_Type = 'Inpatient Credit' or (Billing_Type = 'Inpatient Cash' and pp.payment_type = 'post') or (Billing_Type = 'Outpatient Cash' and Pre_Paid = '1')) and Transaction_status <> 'cancelled' and ";
        }elseif(strtolower($Section) == 'cancelled'){
            $filter .= "Transaction_status = 'cancelled' and ";
        }else{
//            $filter .= "Transaction_status <> 'cancelled' and ";
              $filter .= "((Billing_Type = 'Inpatient Cash' and pp.payment_type='pre') or Billing_Type = 'Inpatient Credit' or (Billing_Type = 'Outpatient Cash' and Pre_Paid = '0') or Billing_Type = 'Outpatient Credit') and Transaction_status <> 'cancelled' and ";
        }
    }

    if($Sponsor_ID != 0){
        $filter .= "pp.Sponsor_ID = '$Sponsor_ID' and ";
    }

    if($Employee_ID != 0){
        $filter .= "pp.Employee_ID = '$Employee_ID' and ";
    }

    $htm = "<table width ='100%' height = '30px'>
		<tr><td><img src='./branchBanner/branchBanner.png' width=100%></td></tr>
	    <tr><td>&nbsp;</td></tr></table>";
		
	$htm .='<center><table width="100%">
	            <tr><td style="text-align: center;"><span style="font-size: x-small;">REVENUE COLLECTION SUMMARY REPORT</span></td></tr>
	            <tr><td style="text-align: center;"><span style="font-size: x-small;">FROM <b>'.@date("d F Y H:i:s",strtotime($Start_Date)).'</b> TO <b>'.@date("d F Y H:i:s",strtotime($End_Date)).'</b></span></td></tr>
	            <tr><td style="text-align: center;"><span style="font-size: x-small;">'.strtoupper($Report_Type).'&nbsp;&nbsp;TRANSACTIONS REPORT,&nbsp;&nbsp;&nbsp;SPONSOR : '.strtoupper($Sponsor).'</span></td></tr>
                <tr><td style="text-align: center;"><span style="font-size: x-small;">CATEGORY NAME : '.strtoupper($Item_Category_Name).'</span></td></tr>
	            <tr><td style="text-align: center;"><span style="font-size: x-small;">EMPLOYEE NAME : '.strtoupper($Emp_Name).'</span>&nbsp;&nbsp;&nbsp;';
    if($Ward != ''){ $htm .= '<span style="font-size: x-small;">WARD NAME : '.$Ward.'</span>'; }
    $htm .= '</td></tr> '
   . '     <tr><td style="text-align: center;"><span style="font-size: x-small;">CLINIC NAME : '.strtoupper($Clinic_Name).'</span></td></tr>'
   . '     <tr><td style="text-align: center;"><span style="font-size: x-small;">DEPARTMENT NAME : '.strtoupper($finance_department_name).'</span></td></tr></table></center>';


    if(strtolower($Section) == 'cashcredit'){
        include("./Category_Cash_Credit_Report.php");
    }else if(strtolower($Section) == 'cash'){
        include("./Category_Cash_Report.php");
    }else if(strtolower($Section) == 'credit'){
        include("./Category_Credit_Report.php");
    }else if(strtolower($Section) == 'cancelled'){
        include("./Category_Cancelled_Report.php");
    }else{
        include("./Category_Credit_Report.php");
    }

    include("./MPDF/mpdf.php");
    $mpdf=new mPDF('','A4', 0, '', 15,15,20,35,15,30, 'P');
    $mpdf->SetFooter('Printed By '.strtoupper($E_Name).'|{PAGENO}/{nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>