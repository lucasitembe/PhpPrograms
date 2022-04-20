<?php
    include("./includes/connection.php");
    include("./includes/header.php");
?>

<style>
    table tr:hover {
        background-color: #ddd;
    }
</style>

<a href="itemsconfiguration.php" class="art-button-green" style="font-family: Arial, Helvetica, sans-serif;">BACK</a>

</br></br>

<fieldset>
    <legend>Item Expire Date & Batch Setup</legend>
    <div>
        <input type="text" id="item_name" onkeyup="searchName()" placeholder="Search By Item Name" style="text-align: center;padding:8px">
    </div>

    <br>

    <table width='100%'>
        <thead>
            <tr>
                <td style="font-weight:600;padding: 8px;text-align:center" width='5%'>S/N</td>
                <td style="font-weight:600;padding: 8px;" width='20%'>PRODUCT CODE</td>
                <td style="font-weight:600;padding: 8px;" width='20%'>Consultation Type</td>
                <td style="font-weight:600;padding: 8px;" width='55'>PRODUCT NAME</td>
                <td style="font-weight:600;padding: 8px;text-align:center" width='15%'>ACTION</td>
            </tr>
        </thead>

        <tbody style="background-color: white;" id="display_items"></tbody>
    </table>
</fieldset>

<script>
    $(document).ready(() => {
        loadDefaultItems();
    });

    function loadDefaultItems(){
        var request = 'load_data';

        $.get('item_config_batch_require.php',{
            request:request
        },(data) => {
            $('#display_items').html(data);
        });
    }
</script>

<script>
    function searchName() {  
        var item_name = $('#item_name').val();
        var seach_items = 'seach_items';

        $.get('item_config_batch_require.php',{
            seach_items:seach_items,
            item_name:item_name
        },(data) => {
            $('#display_items').html(data);
        });
    }

    function check_uncheck(Item_Id) {  
        var check_uncheck = $('#'+Item_Id).val();
        var append_value = check_uncheck == 'yes' ? 'no' : 'yes';
        var btn_check_uncheck = "btn_check_uncheck";

        $.post('item_config_batch_require.php',{
            btn_check_uncheck:btn_check_uncheck,
            Item_Id:Item_Id,
            append_value:append_value
        },(response)=>{
            $('#'+Item_Id).val(append_value);
        })
    }
</script>

<?php include("./includes/footer.php"); ?>