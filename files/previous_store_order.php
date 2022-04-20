<?php 
    include './includes/header.php';
    include 'store/store.interface.php';

    $Sub_Department_ID = (isset($_SESSION['Storage_Info']['Sub_Department_ID'])) ? $_SESSION['Storage_Info']['Sub_Department_ID'] : 0 ;
    $Interface = new StoreInterface();
    $count = 1;
    $Order_List = $Interface->fetchApprovedStoreOrders_($Sub_Department_ID,null,'Approved');
?>
<a href="storeordering.php?status=new" class="art-button-green">BACK</a>

<br><br>
<fieldset>
    <legend style="margin: 0;font-weight:500">LIST OF PREVIOUS APPROVED STORE ORDERS</legend>
        <center>
            <table width='70%'>
                <tr>
                    <td width='30%' style="padding: 4px"><input type="text" style="text-align: center;padding:6px" id="Start_Date" placeholder="Start date"></td>
                    <td width='30%' style="padding: 4px"><input type="text" style="text-align: center;padding:6px" id="End_Date" placeholder="End date"></td>
                    <td width='30%' style="padding: 4px"><input type="text" onkeyup="filterOrder()" style="text-align: center;padding:6px" id="Order_Number" placeholder="Order Number"></td>
                    <td style="padding: 5px;text-align:center"><input type="submit" onclick="filterOrder()" class="art-button-green" value="FILTER"></td>
                </tr>
            </table>
        </center>
</fieldset>
<fieldset style="height: 550px;overflow-y:scroll">
    <table width='100%'>
        <tr style="background-color: #ddd;font-weight:500">
            <td style="padding: 8px;" width='5%'><center>SN</center></td>
            <td style="padding: 8px;" width='11.8%'><center>ORDER NO</center></td>
            <td style="padding: 8px;" width='11.8%'>PREPARED BY</td>
            <td style="padding: 8px;" width='11.8%'>APPROVED BY</td>
            <td style="padding: 8px;" width='11.8%'>SUB DEPARTMENT NAME</td>
            <td style="padding: 8px;" width='11.8%'>CREATED DATE</td>
            <td style="padding: 8px;" width='11.8%'>SUBMITTED DATE</td>
            <td style="padding: 8px;" width='11.8%'>APPROVED DATE</td>
            <td style="padding: 8px;" width='11.8%'><center>ACTION</center></td>
        </tr>

        <tbody id="Display_Orders">
            <?php foreach($Order_List as $Order) : ?>
                <tr style="background-color: #fff;">
                    <td style="padding: 8px;" width='5%'><center><?=$count++?></center></td>
                    <td style="padding: 8px;" width='11.8%'><center><?=$Order['Store_Order_ID']?></center></td>
                    <td style="padding: 8px;" width='11.8%'><?=$Order['Employee_Name']?></td>
                    <td style="padding: 8px;" width='11.8%'><?=$Order['Employee_Name']?></td>
                    <td style="padding: 8px;" width='11.8%'><?=$Order['Sub_Department_Name']?></td>
                    <td style="padding: 8px;" width='11.8%'><?=$Order['Created_Date_Time']?></td>
                    <td style="padding: 8px;" width='11.8%'><?=$Order['Sent_Date_Time']?></td>
                    <td style="padding: 8px;" width='11.8%'><?=$Order['Approval_Date_Time']?></td>
                    <td style="padding: 5px;" width='11.8%'><center><a href="previousstoreorderreport.php?Store_Order_ID=<?=$Order['Store_Order_ID']?>&PreviousStoreOrder=PreviousStoreOrderThisPage" target="_blank" class="art-button-green">PREVIEW</a></center></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</fieldset>

<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    function filterOrder(){
        var Start_Date = $('#Start_Date').val();
        var End_Date = $('#End_Date').val();
        var Order_Number = $('#Order_Number').val();

        if(Start_Date == ""){
            $('#Start_Date').css('border','1px solid red');
            exit();
        }
        $('#Start_Date').css('border','1px solid #eee');

        if(End_Date == ""){
            $('#End_Date').css('border','1px solid red');
            exit(); 
        }
        $('#End_Date').css('border','1px solid #eee');


        let Filter_Object = { Start_Date:Start_Date,End_Date:End_Date,Order_Number:Order_Number,Order_Status:'Approved'}
        $.ajax({
            type: "GET",
            url: "store/store.common.php",
            data: {filter_order_record:'filter_order_record',Filter_Object:Filter_Object,Sud_Department_ID:<?=$Sub_Department_ID?>},
            success: (response) => {
                $('#Display_Orders').html(response);
            }
        });
    }
</script>
<script>
    $('#Start_Date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
    });
    $('#Start_Date').datetimepicker({value: '', step: 01});

    $('#End_Date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
    });
    $('#End_Date').datetimepicker({value: '', step: 01});
</script>

<?php include './includes/footer.php'; ?>
