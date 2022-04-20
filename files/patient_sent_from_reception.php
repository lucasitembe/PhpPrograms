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
    //patientwith_imbalance_bills
    echo "<a href='patientwith_imbalance_bills.php' class='art-button-green'>PATIENT WITH DEBIT</a>";
    echo "<a href='credittransactions.php?".$Section_Link."CreditTransactions=CreditTransactionsThisForm' class='art-button-green'>BACK</a>";
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
            <td width="10%"><b>MEMBER NUMBER</b></td>
        </tr>
        <tr><td colspan="8"><hr></td></tr>
        <tbody id="responceData">

        
    <?php
        $temp = 0;
       
                    
                $select2=mysqli_query($conn,"SELECT pr.Phone_Number,Debt_social_ID, pr.Member_Number, pr.Gender, pr.Patient_Name, pr.Date_Of_Birth,sent_date,Guarantor_Name, ds.Registration_ID FROM tbl_sponsor s, tbl_patient_registration pr, tbl_patient_debt_to_socialwalfare ds WHERE s.Sponsor_ID=pr.Sponsor_ID AND pr.Registration_ID=ds.Registration_ID  AND Debt_social_ID NOT IN (SELECT Debt_social_ID FROM tbl_social_reduce_debt)") or die(mysqli_error($conn));
                while($row2=mysqli_fetch_assoc($select2)){
                    
                $Date_Of_Birth = $row2['Date_Of_Birth'];
                $date1 = new DateTime($Today);
                $date2 = new DateTime($Date_Of_Birth);
                $diff = $date1 -> diff($date2);
                $age = $diff->y." Years, ";
                $age .= $diff->m." Months, ";
                $age .= $diff->d." Days";
    ?>
        <tr>
            <td><a href="approve_debit_bills.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&Debt_social_ID=<?php echo $row2['Debt_social_ID']; ?>" style="text-decoration: none;"><?php echo ++$temp; ?></td>
            <td><a href="approve_debit_bills.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&Debt_social_ID=<?php echo $row2['Debt_social_ID']; ?>" style="text-decoration: none;"><?php echo strtoupper($row2['Patient_Name']); ?></a></td>
            <td><a href="approve_debit_bills.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&Debt_social_ID=<?php echo $row2['Debt_social_ID']; ?>" style="text-decoration: none;"><?php echo $row2['Registration_ID']; ?></a></td>
            <td><a href="approve_debit_bills.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&Debt_social_ID=<?php echo $row2['Debt_social_ID']; ?>" style="text-decoration: none;"><?php echo $row2['Guarantor_Name']; ?></a></td>
            <td><a href="approve_debit_bills.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&Debt_social_ID=<?php echo $row2['Debt_social_ID']; ?>" style="text-decoration: none;"><?php echo strtoupper($row2['Gender']); ?></a></td>
            <td><a href="approve_debit_bills.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&Debt_social_ID=<?php echo $row2['Debt_social_ID']; ?>" style="text-decoration: none;"><?php echo $age; ?></a></td>
            <td><a href="approve_debit_bills.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&Debt_social_ID=<?php echo $row2['Debt_social_ID']; ?>" style="text-decoration: none;"><?php echo $row2['sent_date']; ?></a></td>
            <td><a href="approve_debit_bills.php?Registration_ID=<?php echo $row2['Registration_ID']; ?>&Debt_social_ID=<?php echo $row2['Debt_social_ID']; ?>" style="text-decoration: none;"><?php echo $row2['Member_Number']; ?></a></td>
        </tr>
    <?php
            }
       
    ?></tbody>
    </table>
</fieldset>


<script type="text/javascript">
    function Search_Patient(){
     
        document.getElementById('progress_bar_area').innerHTML = '<div><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        var Patient_Number = document.getElementById("Patient_Number").value;
        var Patient_Name = document.getElementById("Patient_Name").value;
        $.ajax({
            type:'POST',
            url:'patient_sent_from_reception_search.php',
            data:{Patient_Number:Patient_Number,Patient_Name:Patient_Name,msamahasearch:''  },
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