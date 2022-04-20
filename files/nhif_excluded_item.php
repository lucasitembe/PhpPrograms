<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
        //get today's date
  $sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
    while($date = mysqli_fetch_array($sql_date_time)){
        $Current_Date_Time = $date['Date_Time'];
    }
    $Filter_Value = substr($Current_Date_Time,0,11);
    $Start_Date = $Filter_Value.' 00:00';
    $End_Date = $Current_Date_Time;
?>
<a href="itemsconfiguration.php?Section=Reception&ReceptionReportThisPage" class="art-button-green">BACK</a>
<style>
    .rows_list{
        cursor: pointer;
    }
    .rows_list:active{
        color: #328CAF!important;
        font-weight:normal!important;
    }
    .rows_list:hover{
        color:#00416a;
        background: #dedede;
        font-weight:bold;
    }
    a{
        text-decoration: none;
    }
</style>
<fieldset>
    <legend align='center'><b> NHIF EXCLUDED ITEM LIST</b></legend>
    <center>
        <table>
            <tr>
                
                <td><input type="text" style="text-align:center" onkeyup='filter_list_of_patient_sent_to_cashier()' placeholder="Item Name" id='item_name'/></td>
                <td><input type="text" style="text-align:center" onkeyup='filter_list_of_patient_sent_to_cashier()' placeholder="Item Code" id='item_code'/></td>

                <td>   
                <select id="consultation_type" onchange="filter_list_of_patient_sent_to_cashier()">
                    <option value="">All Consultation Type</option>
                    <option value="Procedure">Procedure</option>
                    <option value="Laboratory">Laboratory</option>
                    <option value="Radiology">Radiology</option>
                    <option value="Surgery">Surgery</option>
                    <option value="Pharmacy">Pharmacy</option>
                    <option value="Optical">Optical</option>
                    <option value="Others">Others</option>
                </select></td>
                
                <td>   
                <select id="nhif_package" onchange="filter_list_of_patient_sent_to_cashier()">
                <?php
                    $select_Supplier = mysqli_query($conn,"SELECT * from tbl_nhif_scheme_package") or die(mysqli_error($conn));
                        echo "<option value=''>All Package</option>";
                        while($row = mysqli_fetch_array($select_Supplier)){
                        echo "<option value='".$row['package_id']."'>".ucfirst($row['package_name'])."</option>";
                    }
                ?>
                </select></td>
                
                <!-- <td><input type="button" value="FILTER" onclick="filter_list_of_patient_sent_to_cashier()" class="art-button-green"/></td> -->
                <!--<td><input type="button" value="Card/Mobile CONFIRM PAYMENT" onclick="confirm_mobile_payment()" class="art-button-green"/></td>-->
            </tr>
        </table>
    </center>
    <div class="box box-primary" style="height: 400px;overflow-y: scroll;overflow-x: hidden">
        <table class="table">
            <thead style="background-color:bdb5ac">
                <tr>
                    <td style="width:50px"><b>S/No</b></td>
                    <td><b>PACKAGE NAME</b></td>
                    <td><b>CONSULTATION TYPE</b></td>
                    <td><b>ITEM CODE</b></td>
                    <td><b>EXCLUDED ITEM</b></td>
                </tr>
            </thead>
        <tbody id='patient_sent_to_cashier_tbl'> 
                
            </tbody>
        </table>
    </div>
</fieldset>
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>

<script>
    function filter_list_of_patient_sent_to_cashier(){
        var item_name=$('#item_name').val();
        var item_code=$('#item_code').val();
        var nhif_package=$('#nhif_package').val();
        var consultation_type=$('#consultation_type').val();
        document.getElementById('patient_sent_to_cashier_tbl').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        $.ajax({
            type:'POST',
            url:'nhif_excluded_item_ajax.php',
            data:{item_name:item_name,item_code:item_code,nhif_package:nhif_package,consultation_type:consultation_type},
            success:function(data){
                $("#patient_sent_to_cashier_tbl").html(data);
            }
        });
    }
     $(document).ready(function () {
        filter_list_of_patient_sent_to_cashier();
    });
</script>
<?php
    include("./includes/footer.php");
?>
