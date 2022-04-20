<?php
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Msamaha_Works'] = 'yes'){
            
        }else{
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    }else{
    @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    //get today date
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $End_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        $Start_Date = $new_Date.' 00:00:00';
    }
    echo "<a href='patientwith_imbalance_bills.php' class='art-button-green'>
    Debit Patient reports
                        </a> ";
    echo "<a href='patient_sent_from_reception.php?".$Section_Link."CreditTransactions=CreditTransactionsThisForm' class='art-button-green'>BACK</a>";
?>

<br/><br/>
<!--//border-collapse:collapse !important;-->
<style>
    
    table,tr,td{
        
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
    .approve_credit_trsns_out_p_bill_tbl table, .approve_credit_trsns_out_p_bill_tbl tr, .approve_credit_trsns_out_p_bill_tbl td{
       border:1px solid #CCCCCC!important; 
    }
</style>
<fieldset>
    <table width=100%>
        <tr>
            <td width="20%">
                <input type="text" name="Patient_Name" id="Patient_Name" autocomplete="off"  style="text-align: center;" placeholder="~~~ ~~~ Enter Patient Name ~~~ ~~~">
            </td>
            <td width="15%">
                <input type="text" name="Patient_Number" id="Patient_Number" autocomplete="off"  style="text-align: center;" placeholder="~~~ Enter Patient Number ~~~">
            </td>
            
        </tr>
       
    </table>
</fieldset>
<div id="progress_bar_area"></div>
<fieldset style='overflow-y: scroll; height: 500px; background-color: white;' id='Patient_List_Area'>
    <legend align=center>PATIENT WITH DEBT SENT FROM RECEPTION</legend>
    
    <table width="100%">
        <tr><td colspan="10"><hr></td></tr>
        <tr>
            <td width="5%"><b>SN</b></td>
            <td width="15%"><b>PATIENT NAME</b></td>
            <td width="5%"><b>PATIENT NUMBER</b></td>
            <td width="15%"><b>SPONSOR NAME</b></td>
            <td width="5%"><b>GENDER</b></td>
            <td width="5%"><b>AGE</b></td>
            <td width="10%" style="text-align: right;"><b>TOTAL AMOUNT</b></td>
            <td width="10%" style="text-align: right;"><b>AMOUNT PAID</b></td>
            <td width="10%" style="text-align: right;"><b>AMOUNT REMAINED</b></td>
            <td width="10%" style="text-align: center;"><b>DEBIT STATUS</b></td>
        </tr>
        <tr><td colspan="10"><hr></td></tr>
    <?php
        $temp = 0;
       
                    
                    $Total_amount = 0;
                    $Amount_remained =0;
                $select2=mysqli_query($conn,"SELECT cd.Debt_ID, pr.Phone_Number, pr.Gender,Grand_Total,Grand_Total_Direct_Cash,Debt_status, pr.Patient_Name, pr.Date_Of_Birth,Guarantor_Name, ds.Registration_ID FROM tbl_sponsor s, tbl_patient_registration pr, tbl_patient_debt ds, tbl_debt_cash_deposit cd WHERE  ds.Debt_ID=cd.Debt_ID AND s.Sponsor_ID=pr.Sponsor_ID AND pr.Registration_ID=ds.Registration_ID") or die(mysqli_error($conn));
                while($row2=mysqli_fetch_assoc($select2)){
                    $Grand_Total_Direct_Cash = $row2['Grand_Total_Direct_Cash'];
                    $Grand_Total = $row2['Grand_Total'];
                    $Registration_ID =$row2['Registration_ID'];
                    $Debt_ID = $row2['Debt_ID'];
                    $Total_amount = $Grand_Total -$Grand_Total_Direct_Cash;
                    $Debt_status = $row2['Debt_status'];
                    
                    $selectAmount_paid = mysqli_query($conn, "SELECT SUM(Patient_debt_amount) as Amount_paid, debit_status, created_at, Paid_at FROM tbl_debt_cash_deposit WHERE Debt_ID='$Debt_ID'");
                   $Amount_paid =0;
                    $Date_Of_Birth = $row2['Date_Of_Birth'];
                    $date1 = new DateTime($Today);
                    $date2 = new DateTime($Date_Of_Birth);
                    $diff = $date1 -> diff($date2);
                    $age = $diff->y." Years";
                    // $age .= $diff->m." Months, ";
                    // $age .= $diff->d." Days";
                    while($dtrw = mysqli_fetch_assoc($selectAmount_paid)){
                        $Amount_paid = $dtrw['Amount_paid'];
                        $debit_status =$dtrw['debit_status'];
                        $created_at = $dtrw['created_at'];
                        $Paid_at = $dtrw['Paid_at'];
                        
                        if($debit_status=='Active'){
                            $Amount_paid =0;
                            $Amount_remained = $Total_amount - $Amount_paid;
                        }

                        if($debit_status=='Paid'){
                            $Amount_remained = $Total_amount - $Amount_paid;
                        }
                        
                        $Today_Date = mysqli_fetch_array(mysqli_query($conn,"select now() as today"))['today'];
                        $time_elapsed = date_diff(date_create($Paid_at), date_create($Today_Date))->d;
                       
                        if($time_elapsed >7 && $Amount_remained >0 ){
                            $UpdateStatus = mysqli_query($conn, "UPDATE tbl_patient_debt SET Debt_status='Not Cleared' WHERE Registration_ID='$Registration_ID' AND Debt_ID ='$Debt_ID'") or die(mysqli_error($conn));
                            if($UpdateStatus){
                                //echo "Updated";
                            }else{
                               // echo "No";
                            }
                        }
                        ?>  
                        <tr>
                            <td><?php echo ++$temp; ?></td>
                            <td><?php echo strtoupper($row2['Patient_Name']); ?></td>
                            <td><?php echo $row2['Registration_ID']; ?></td>
                            <td><?php echo $row2['Guarantor_Name']; ?></td>
                            <td><?php echo strtoupper($row2['Gender']); ?></td>
                            <td><?php echo $age; ?></td>
                            <td style="text-align: right;"><?php echo $Total_amount; ?></td>
                            <td style="text-align: right;"><?php echo $Amount_paid; ?></td>
                            <td style="text-align: right;"><?php echo $Amount_remained; ?></td>
                            <td style="text-align: center;"><?php echo $Debt_status; ?></td>
                        </tr>
                    <?php
                    }
    
            }
       
    ?>
    </table>
</fieldset>


<?php
    include("./includes/footer.php");
?>