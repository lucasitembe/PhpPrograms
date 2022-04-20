<?php 
    include("./includes/connection.php");
    $Payment_Cache_ID = $_POST['Payment_Cache_ID'];
    $Reg_No = $_POST['Reg_No'];

    $get_patient_details = mysqli_query($conn,"SELECT * FROM tbl_patient_registration WHERE Registration_ID = $Reg_No LIMIT 1 ");
    while($patient_details = mysqli_fetch_assoc($get_patient_details)){
        $name = $patient_details['Patient_Name'];
        $Gender = $patient_details['Gender'];
    }
?>

<br>
<h4>Removed Items For : <b><?=$name?></b></h4>
<br>

<style>
    .tabl thead td{
        padding: .8em;
        border: 1px solid black;
        font-size: 16px;
    }
    .tabl tbody td{
        padding: .5em;
        border: 1px solid #ccc;
        font-size: 16px;
    }
    table,
    tr,
    td {
        border-collapse: collapse !important;
        border: 1px solid #ccc !important;

    }
    table tr td{
        border-bottom:1px solid #ccc;
    }
</style>

<table class='tabl' style='background-color:#fff;border:1px solid black' width='100%'>
    <thead>
        <tr style='background-color:#eee;padding:2em;border:1px solid black'>
            <td width='20%' style='text-align:center'><b>S/N</b></td>
            <td><b>Item Name</b></td>
            <td width='20%'><b>Actions</b></td>
        </tr>
    </thead>
    <tbody>

    <?php 
        $count = 1;
        $select_removed_items = mysqli_query($conn,"SELECT * FROM tbl_item_list_cache WHERE Check_In_Type = 'Pharmacy' AND Status = 'removed' AND Payment_Cache_ID = $Payment_Cache_ID ");
        if(mysqli_num_rows($select_removed_items) > 0):
            while($item_row = mysqli_fetch_assoc($select_removed_items)):
                    $get_item_id = $item_row['Item_ID'];
                    $get_item_name = mysqli_query($conn,"SELECT * FROM tbl_items WHERE Item_ID = $get_item_id");
                    while($product_name = mysqli_fetch_assoc($get_item_name)) : 
                        $get_product_name = $product_name['Product_Name'];
                    endwhile;?>
                        <tr>
                            <td style='text-align:center'><?=$count++?></td>
                            <td><?=$get_product_name?></td>
                            <td>
                                <input class='art-button-green' type='button' onclick="return_item(<?=$Payment_Cache_ID?>,<?=$get_item_id?>)" style='float:left;font-family:arial;font-weight:normal;border-radius:3px' value='Return'>
                            </td>
                        </tr>
    <?php 
            endwhile;
        else:
            echo '
                <tr>
                    <td style="color:red;text-align:center;font-size:16px;padding:3em" colspan="3"><b>No Removed Items Found</b></td>
                </tr>
            ';
        endif; ?>

    </tbody>
</table>


<script>
    // return pharmacy handler
    function return_item(Payment_Cache_ID,Item_ID){
        if(confirm('Are you sure ?')){
            $.post(
                'return_item_pharmarcy.php',{
                    Payment_Cache_ID: Payment_Cache_ID,
                    Item_ID: Item_ID
                },(response) =>{
                    location.reload(true);
                }
            );
        }
    }
</script>