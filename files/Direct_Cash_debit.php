<?php
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
    if(isset($_SESSION['userinfo']['Revenue_Center_Works'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes' && $_SESSION['userinfo']['Msamaha_Works'] != 'yes'){
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
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
    
    echo "<a href='cash_deposit.php' class='art-button-green'>BACK</a>";
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
                <input type="text" name="Patient_Name" id="Patient_Name" autocomplete="off"  style="text-align: center;" placeholder="~~~ ~~~ Enter Patient Name ~~~ ~~~" onkeyup="Search_Patient()">
            </td>
            <td width="15%">
                <input type="text" name="Patient_Number" id="Patient_Number" autocomplete="off"  style="text-align: center;" placeholder="~~~ Enter Patient Number ~~~" onkeyup="Search_Patient()">
            </td>
            
        </tr>
       
    </table>
</fieldset>
<div id="progress_bar_area"></div>
<fieldset style='overflow-y: scroll; height: 380px; background-color: white;' id='Patient_List_Area'>
    <legend align=center>PATIENT WITH DEBT SENT FROM RECEPTION</legend>
    
    <table width="100%">
        <tr><td colspan="8"><hr></td></tr>
        <tr>
            <td width="5%"><b>SN</b></td>
            <td><b>PATIENT NAME</b></td>
            <td width="10%"><b>PATIENT NUMBER</b></td>
            <td width="15%"><b>SPONSOR NAME</b></td>
            <td width="7%"><b>GENDER</b></td>
            <td width="14%"><b>AGE</b></td>
            <td width="10%"><b>CHECK IN DATE</b></td>
            <td width="10%"><b>DEBIT STATUS</b></td>
        </tr>
        <tr><td colspan="8"><hr></td></tr>
        <tbody id="responceData">

       
    <?php
        $temp = 0;
                   
        $select2 = mysqli_query($conn,"SELECT pr.Phone_Number,ds.Debt_ID,Patient_debt_amount, pr.Member_Number, pr.Gender, pr.Patient_Name, pr.Date_Of_Birth,ds.created_at, Guarantor_Name, pd.Registration_ID FROM tbl_sponsor s, tbl_patient_registration pr, tbl_debt_cash_deposit ds, tbl_patient_debt pd WHERE s.Sponsor_ID=pr.Sponsor_ID AND pr.Registration_ID=pd.Registration_ID AND ds.Debt_ID= pd.Debt_ID AND debit_status='Active' ") or die(mysqli_error($conn));
        while($row2 = mysqli_fetch_assoc($select2)){
            $Pqfl = $row2['Patient_debt_amount'];
            $Date_Of_Birth = $row2['Date_Of_Birth'];
            $Registration_ID = $row2['Registration_ID'];
            $date1 = new DateTime($Today);
            $date2 = new DateTime($Date_Of_Birth);
            $diff = $date1 -> diff($date2);
            $age = $diff->y." Years, ";
            $age .= $diff->m." Months, ";
            $age .= $diff->d." Days";

            $clear_patient_debit = mysqli_query($conn, "SELECT Patient_Bill_ID,Registration_ID, Grand_Total, pd.Debt_ID, Patient_debt_amount FROM tbl_debt_cash_deposit cd,  tbl_patient_debt pd WHERE Debt_status='Not cleared' AND pd.Registration_ID='$Registration_ID' AND pd.Debt_ID=cd.Debt_ID") or die(mysqli_error($conn));
            if(mysqli_num_rows($clear_patient_debit)>0){
                while($rows = mysqli_fetch_assoc($clear_patient_debit)){
                    $Patient_Bill_ID = $rows['Patient_Bill_ID'];
                    $Debt_ID = $rows['Debt_ID'];
                    $Patient_debt_amount = $rows['Patient_debt_amount'];
                    $Registration_ID = $rows['Registration_ID'];
                    $Grand_Total = $rows['Grand_Total'];

                   
                    $Today = date('Y-m-d');
                    $select_if_payment_done = mysqli_query($conn, "SELECT Price, Consultant_ID,Transaction_Date_And_Time  FROM tbl_items i, tbl_patient_payment_item_list pil,tbl_patient_payments pp WHERE pil.Item_ID=i.Item_ID AND Receipt_Date=CURDATE() AND Visible_Status='Others'  AND pil.Patient_Payment_ID=pp.Patient_Payment_ID AND Registration_ID='$Registration_ID' ORDER BY  pp.Patient_Payment_ID DESC ") or die(mysqli_error($conn));
                    if(mysqli_num_rows($select_if_payment_done)>0){
                        while($rwdata = mysqli_fetch_assoc($select_if_payment_done)){                       
    
                            $Price = $rwdata['Price']; 
                            $Transaction_Date_And_Time = $rwdata['Transaction_Date_And_Time'];
                            $Consultant_ID = $rwdata['Consultant_ID'];
    
                            if($Price == $Patient_debt_amount){
                                $clear_debit = mysqli_query($conn, "UPDATE tbl_patient_debt SET Debt_status='cleared', Debt_creared_by='$Consultant_ID', Clearance_date='$Transaction_Date_And_Time' WHERE Registration_ID='$Registration_ID' AND  Debt_ID='$Debt_ID' ") or die(mysqli_error($conn));
                                if($clear_debit){
                                    $clear_debit_tblcdd = mysqli_query($conn, "UPDATE tbl_debt_cash_deposit SET debit_status='Paid', Paid_at='$Transaction_Date_And_Time' WHERE Debt_ID='$Debt_ID'") or die(mysqli_error($conn));
                                        $debit_progress="Updated successful";
                                }else{
                                   $debit_progress= "No updates";
                                }
                            }else{
                                $amont_paid_toreduce_debit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Amount_deposited FROM tbl_social_reduce_debt srd, tbl_patient_debt_to_socialwalfare pds WHERE Patient_Bill_ID='$Patient_Bill_ID' AND Registration_ID='$Registration_ID' AND srd.Debt_social_ID=pds.Debt_social_ID"))['Amount_deposited'];
                               
                                if($amont_paid_toreduce_debit == $Price){
                                    $clear_debit_tblcdd = mysqli_query($conn, "UPDATE tbl_debt_cash_deposit SET debit_status='Paid', Paid_at='$Transaction_Date_And_Time' WHERE Debt_ID='$Debt_ID'") or die(mysqli_error($conn));
                                    if($clear_debit_tblcdd){
                                        $update_social_walfere = mysqli_error($conn, "UPDATE tbl_social_reduce_debt SET debit_status='Paid'  ") or mysqli_error($conn);
                                        $extend__debit = mysqli_query($conn, "UPDATE tbl_patient_debt SET Debt_status='Debt Extended' WHERE  Debt_ID='$Debt_ID'") or mysqli_error($conn);

                                        // hapa update tbl_patient_payments bill_ID Weka ile ya bill ya nyuma 
                                    }
                                }else{
                                    $debit_progress= "Transaction not the same";
                                }
                            }
                        }
                    }else{
                        $debit_progress= "No any transaction done";
                    }
                }            
            }else{
                $debit_progress= "No result";
            }
            
    ?>
        <tr>
            <td><a href="Cash_deposit_debit.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&robsq=<?php echo $row2['Debt_ID']; ?>&Pqfl=<?php echo $Pqfl; ?>" style="text-decoration: none;"><?php echo ++$temp; ?></td>
            <td><a href="Cash_deposit_debit.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&robsq=<?php echo $row2['Debt_ID']; ?>&Pqfl=<?php echo $Pqfl; ?>" style="text-decoration: none;"><?php echo strtoupper($row2['Patient_Name']); ?></a></td>
            <td><a href="Cash_deposit_debit.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&robsq=<?php echo $row2['Debt_ID']; ?>&Pqfl=<?php echo $Pqfl; ?>" style="text-decoration: none;"><?php echo $row2['Registration_ID']; ?></a></td>
            <td><a href="Cash_deposit_debit.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&robsq=<?php echo $row2['Debt_ID']; ?>&Pqfl=<?php echo $Pqfl; ?>" style="text-decoration: none;"><?php echo $row2['Guarantor_Name']; ?></a></td>
            <td><a href="Cash_deposit_debit.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&robsq=<?php echo $row2['Debt_ID']; ?>&Pqfl=<?php echo $Pqfl; ?>" style="text-decoration: none;"><?php echo strtoupper($row2['Gender']); ?></a></td>
            <td><a href="Cash_deposit_debit.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&robsq=<?php echo $row2['Debt_ID']; ?>&Pqfl=<?php echo $Pqfl; ?>" style="text-decoration: none;"><?php echo $age; ?></a></td>
            <td><a href="Cash_deposit_debit.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&robsq=<?php echo $row2['Debt_ID']; ?>&Pqfl=<?php echo $Pqfl; ?>" style="text-decoration: none;"><?php echo $row2['sent_date']; ?></a></td>
            <td><a href="Cash_deposit_debit.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&robsq=<?php echo $row2['Debt_ID']; ?>&Pqfl=<?php echo $Pqfl; ?>" style="text-decoration: none;"><?php echo $debit_progress; ?></a></td>
        </tr>
    <?php
        }
       
    ?> </tbody>
    </table>
</fieldset>
<script>
    function Search_Patient(){
     
     document.getElementById('progress_bar_area').innerHTML = '<div><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
     var Patient_Number = document.getElementById("Patient_Number").value;
     var Patient_Name = document.getElementById("Patient_Name").value;
     $.ajax({
         type:'POST',
         url:'patient_sent_from_reception_search.php',
         data:{Patient_Number:Patient_Number,Patient_Name:Patient_Name,cashdepositsearch:''  },
         success:function(responce){
             $("#responceData").html(responce);
             document.getElementById('progress_bar_area').innerHTML='';
         }
     })
   
 }
</script>
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $('#Start_Date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en', //startDate:    'now'
    });
    $('#Start_Date').datetimepicker({value: '', step: 01});
    $('#End_Date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en', //startDate:'now'
    });
    $('#End_Date').datetimepicker({value: '', step: 01});
</script>
<!--End datetimepicker-->

<?php
    include("./includes/footer.php");
?>