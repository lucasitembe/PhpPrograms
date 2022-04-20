<?php
    session_start();
    include("./includes/connection.php");

    echo "<link rel='stylesheet' href='fixHeader.css'>";
    
    //get start date, end date & sponsor id
    
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
    
    if(isset($_GET['Sponsor_ID'])){
        $Sponsor_ID = $_GET['Sponsor_ID'];
    }else{
        $Sponsor_ID = 0;
    }
    
    //get employee ID
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
    
    $temp = 1;
    if($Start_Date != '' && $Start_Date != null && $End_Date != '' && $End_Date != null && $Sponsor_ID != '' && $Sponsor_ID != null && $Employee_ID != 0 && $Employee_ID != null){
        echo '<center><table width = 100% border=0 class="fixTableHead">';
        echo '<thead style="background-color: #ccc;">
                <tr>
                    <td width=4% style="text-align: center;"><b>Sn</b></td>
                    <td><b>Patient Name</b></td>
                    <td width=10%><b>Patient#</b></td>
                    <td width 15% style="text-align: left;"><b>Sponsor Name</b></td>
                    <td width=10% style="text-align: center;"><b>Folio Number</b></td>
                    <td width=10% style="text-align: right;"><b>Amount</b></td>
                    <td width=13% style="text-align: right;"><b>First Served Date</b></td>
                    <td width=20% style="text-align: center;"><b>Approved By</b></td>
                </tr>
              </thead>';

                                                            
            $get_details = mysqli_query($conn,"select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount, min(pp.Payment_Date_And_Time) as First_Served_Date,
                    pr.Patient_Name, pp.Folio_Number,pp.Sponsor_ID, emp.Employee_Name
                    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp, tbl_employee emp
                    where pp.patient_payment_id = ppl.patient_payment_id and
                    pp.registration_id = pr.registration_id and
                    (pp.billing_type = 'Outpatient Credit' or pp.billing_type = 'Inpatient Credit')
                    and pp.Billing_Process_Date between '$Start_Date' and '$End_Date'
                    and sp.sponsor_id = pp.sponsor_id and
                    sp.sponsor_id = '$Sponsor_ID' and
                    emp.Employee_ID = pp.Billing_Process_Employee_ID
                    and pp.Billing_Process_Status = 'Approved'
                    GROUP BY  pr.Registration_ID, pp.Folio_Number  order by pp.Folio_Number, pr.Registration_ID
                    ") or die(mysqli_error($conn));

        $num = mysqli_num_rows($get_details);
        if($num > 0){
            while($row = mysqli_fetch_array($get_details)){
                echo '<tr><td width=4% style="text-align: center;">'.$temp.'</td>
                            <td>'.$row['Patient_Name'].'</td>
                            <td width=10%>'.$row['Registration_ID'].'</td>
                            <td width 15% style="text-align: left;">'.$row['Guarantor_Name'].'</td>
                            <td width=10% style="text-align: center;">'.$row['Folio_Number'].'</td>
                            <td width=10% style="text-align: right;">'.number_format($row['Amount']).'</td>
                            <td width=10% style="text-align: right;">'.$row['First_Served_Date'].'</td>
                            <td width=20% style="text-align: center;">'.$row['Employee_Name'].'</td>
                        </tr>';
                    $temp++;
            }
        }else{
?>
            <tr>
                <td colspan=8 style='text-align: center; vertical-align: middle;'>
                    <h3><br/><br/>No Any Approved Transactions Found</h3>
                </td>
            </tr>
<?php
        }
    }
?>