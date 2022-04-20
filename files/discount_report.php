<?php
include("includes/header.php");
include("includes/connection.php");
$consultation = 0;
$investigation = 0;
$pharamcy = 0;
$total = 0;
$female = 0;
$male = 0;

$pharmacy_discount = 0;
$investigation_discount = 0;
if(!isset($_SESSION['userinfo']['Employee_ID'])){
    header("Location:index.php");
}
?>
<a href="msamahareports.php?MsamahaReports=MsamahaReportsThisForm" class="art-button-green">BACK</a>

<br/>
<br/>

<style>
    table{
        width:100%;
        text-align:center;
        border:none;
        border-collapse:collapse;
    }
    table,tr,td{
        border:none;
        border-collapse:collapse;
        text-align:center;
    }
    #select{
        width:100%;
    }
    input{
        height:35px;
        text-align:center;
    }

.art-button{
    width:100px;
    color:#fff !important;
    height:35px !important;
}
select{
    height:35px;
    width:100%;
    padding-left:10px;
}
#report{

}
#report td{
    text-align:center;
}
#report_cover{
    height:325px;
}
#cover_report{
    background:#fff;
    height:290px;
    overflow-y:scroll;
    overflow-x:hidden;
}
#report td{
}
#inside_report{
    width:100%;
    
}
#inside_report td{
    padding:6px;
    width:23%;
}
</style>
<center>

<table >
<td><input class="input" type="text" id="start_date" autocomplete="off" placeholder="start date"></td>
<td><input class="input" type="text" id="end_date" autocomplete="off" placeholder="end date"></td>
<td>

</td>
<td>
<select name="" id="select" class="clinic_id">
        <option value="">SELECT CLINIC</option>
        <?php 
        $result =mysqli_query($conn,"SELECT Clinic_Name,Clinic_ID FROM tbl_Clinic");
        while($row = mysqli_fetch_assoc($result)){
            $clinic_name = $row['Clinic_Name'];
            $clinic_id = $row['Clinic_ID'];
            echo "<option value='$clinic_id'>".$clinic_name."</option>";
        }
        ?>
       
    </select>
</td>
<td>
    <button class="art-button-green" id='filter_report'>
        FILTER
    </button>
</td>
    

</table> 
</center>
<br />
<style>

td{
    text-align:center;
}

</style>
<fieldset id="report_cover">
<legend>Discount report</legend>
<table id="report" style='width:100%;box-sizing:border-box;'>
        <td style='width:4.7%;box-sizing:border-box;'><b>SN</b></td>
        <td style='width:19%;box-sizing:border-box;'><b>Patient Name</b></td>
        <td style='width:6.5%;box-sizing:border-box;'><b>Gender</b></td>
        <td style='width:6.6%;box-sizing:border-box;'><b>Age</b></td>
        <td style='width:7%;box-sizing:border-box;'><b>Patient No#</b></td>
        <td style='width:6.7%;box-sizing:border-box;'><b>Total amount</b></td>
        <td style='width:6.7%;box-sizing:border-box;'><b>Discount</b></td>
        <td style='width:6.7%;box-sizing:border-box;'><b>amount paid</b></td>
        <td style='width:6.7%;box-sizing:border-box;'><b>Phone number</b></td>
</table>

<div id="cover_report">
<?php
$sql = "SELECT pr.Registration_ID,pr.Gender,pr.Patient_Name,pr.Gender,Date_Of_Birth,Phone_Number,count(pp.`Patient_Payment_ID`) as ppid FROM `tbl_patient_payments` pp JOIN tbl_patient_registration pr ON pp.`Registration_ID`=pr.Registration_ID JOIN tbl_patient_payment_item_list ppl ON pp.`Patient_Payment_ID`= ppl.Patient_Payment_ID WHERE ppl.Discount > 0 AND ppl.Clinic_ID=12 GROUP BY pr.Registration_ID";
$result = mysqli_query($conn,$sql);
echo "<table id='inside_report'>";
$i=0;
$number = mysqli_num_rows($result);
$total = 0;
$overal_total_discount = 0;
$overal_total_paid = 0;
while($row = mysqli_fetch_assoc($result)){
    $patient_name = $row['Patient_Name'];
    $gender = $row['Gender'];
    $ppid = $row['ppid'];
    $registration_id = $row['Registration_ID'];
    $phone_number = $row['Phone_Number'];
    $dob = $row['Date_Of_Birth'];

    $then = $dob;
    $then = new DateTime($dob);
    $now = new DateTime();
    $sinceThen = $then->diff($now);
    $dob = $sinceThen->y.' years '. $sinceThen->m.' months '. $sinceThen->d.' days';

   if($gender == "Male"){
       $male = $male+1;
   }else if($gender == "Female"){
       $female = $female + 1;
   }
    
    $sql2 = "SELECT Patient_Payment_ID,Payment_Date_And_Time FROM tbl_patient_payments WHERE Registration_ID='$registration_id' ";
    $result2 = mysqli_query($conn,$sql2) or die(mysqli_error($conn));
    
    $total_ivestigation_amount = 0;
    $total_ivestigation_discount = 0;
    $total_discount = 0;
    $amount_paid = 0;
    $total_per_patient = 0;
    while($row2 = mysqli_fetch_assoc($result2)){
        
        
        $payment_id = $row2['Patient_Payment_ID'];
        $date_time = $row2['Payment_Date_And_Time'];
    
        $sql3 = "SELECT Check_In_Type ,Discount, price ,Item_ID FROM tbl_patient_payment_item_list WHERE Patient_Payment_ID='$payment_id' AND Check_In_Type='Pharmacy' Transaction_Date_And_Time BETWEEN '$start_date' AND '$end_date'";
        
        
        $result3  = mysqli_query($conn,$sql3) or dor(mysqli_error($conn));
        
        while($row3 = mysqli_fetch_assoc($result3)){
            $check = $row3['Check_In_Type'];
            $total =  $total + $row3['price'];
            $price = $row3['price'];
            $discount = $row3['Discount'];
            $total_discount = $total_discount + $discount;
            $amount_paid += ($price - $discount);
            $overal_total_paid += ($price - $discount);
            $item_name = getItemName($row3['Item_ID']);
            $overal_total_discount += $discount;
            $total_per_patient += $row3['price'];
            // $total_ivestigation_amount += $price;


            // if($check == "Doctor Room"){
            //     $consultation = $consultation + 1;
                 
            // }else if($check == "Laboratory" || $check == "Radiology"){
            //     $investigation = $investigation + 1;
            //     $investigation_discount += $discount;
               
                
            // }else if($check == "Pharmacy"){
            //     $pharamcy = $pharamcy +1;
            //     $pharmacy_discount += $discount;       
            // }

        }
       

        
    }

    echo "<tr>
    <td style='width:5%;box-sizing:border-box;'>".++$i."</td>
    <td style='width:20%;box-sizing:border-box;'><h6><b>".$patient_name."</b></h6></td>
    <td style='text-align:center;width:7%;box-sizing:border-box;'>".$gender."</td>
    <td style='text-align:center;width:7%;box-sizing:border-box;'>".$dob."</td>
    <td style='text-align:center;width:7%;box-sizing:border-box;'>".$registration_id."</td>
    <td style='text-align:center;width:7%;box-sizing:border-box;'>".number_format($total_per_patient)."</td>
    <td style='text-align:center;width:7%;box-sizing:border-box;'>".number_format($total_discount)."</td>
    <td style='text-align:center;width:7%;box-sizing:border-box;'>".number_format($amount_paid)."</td>
    <td style='text-align:center;width:7%;box-sizing:border-box;'>".($phone_number)."</td>
    </tr>";
}

echo "<tr>
<td style='width:5%;'></td>
<td style='width:20%;box-sizing:border-box;text-align:center'><h5><b>Total</b></h5></td>
<td style='text-align:center;width:7%;box-sizing:border-box;'></td>
<td style='text-align:center;width:7%;box-sizing:border-box;'></td>
<td style='text-align:center;width:7%;box-sizing:border-box;'></td>
<td style='text-align:center;width:7%;box-sizing:border-box;'><h5><b>".number_format($total)."</b></h5></td>
<td style='text-align:center;width:7%;box-sizing:border-box;'><h5><b>".number_format($overal_total_discount)."</b></h5></td>
<td style='text-align:center;width:7%;box-sizing:border-box;'><h5><b>".number_format($overal_total_paid)."</b></h5></td>
<td style='text-align:center;width:7%;box-sizing:border-box;'><h5><b></b></h5></td>
</tr>";

echo "</table>";

function getItemName($item_id){
    $sql = "SELECT Product_Name FROM tbl_items WHERE 
    Item_ID = '$item_id'";
    $result = mysqli_query($conn,$sql);
    while($row = mysqli_fetch_assoc($result)){
        $product_name = $row['Product_Name'];
    }
    return $product_name;
}

?>
</div>
</fieldset>


<script src="css/jquery.datetimepicker.js"></script>
<script>

// $("#select").select2()
    $('#start_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en', //startDate:    'now'
    });
    $('#start_date').datetimepicker({value: '', step: 01});
    $('#end_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en', //startDate:'now'
    });
    $('#end_date').datetimepicker({value: '', step: 01});
</script>

<script>
    $("#select").change(function(){
        $("#cover_report").html("Report")
    })



    $("#filter_report").click(function(e){
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        var clinic_id = $(".clinic_id").val();
        var exemption = $("#exemption").val();


        var data = {
            start_date:start_date,
            end_date:end_date,
            clinic_id:clinic_id,
        }
       
            $.ajax({
            method:"GET",
            url:"discount_report_filter.php",
            data:data,
            success:function(data){
                console.log(data)
                $("#cover_report").html("");
                $("#cover_report").append(data);
            }
        })
        

        
    })
</script>