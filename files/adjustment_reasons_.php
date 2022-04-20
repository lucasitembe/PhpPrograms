<?php 
    include("./includes/header.php");
    include "store/store.interface.php";

    if (!isset($_SESSION['userinfo'])) {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    $Store = new StoreInterface();
    $Reasons = $Store->getAdjustmentReasons("");
    $count = 1;
?>
<a href="itemsconfiguration.php" class="art-button-green">BACK</a>

<br><br>
<input type="hidden" id="Employee_ID" value="<?=$_SESSION['userinfo']['Employee_ID']?>">
<fieldset style="width: 40%;height:550px">
    <legend>ADJUSTMENT CONFIGURATION</legend>
    
    <table width='100%'>
        <tr>
            <td><input type="text" id="reason" placeholder="Enter New Reason"></td>
            <td width='18%'>
                <select id="nature" style="padding: 5px;width: 100%;">
                    <option value="">Select Reason</option>
                    <option value="adjustment_plus">Adjustment Plus</option>
                    <option value="adjustment_minus">Adjustment Minus</option>
                </select>
            </td>
            <td width='17%'><a href="#" onclick="addNewReason()" class="art-button-green">ADD</a></td>
        </tr>
    </table>

    <fieldset style="border: 1px solid #ccc !important;overflow-y:scroll;height:450px">
        <table width='100%'>
            <tr style="background-color:#ddd">
                <td style="padding: 6px;font-weight:500" width='15%'><center>S/N</center></td>
                <td style="padding: 6px;font-weight:500">REASON</td>
                <td style="padding: 6px;font-weight:500" width='18%'>NATURE</td>
                <td style="padding: 6px;font-weight:500" width='15%'>ACTION</td>
            </tr>

            <tbody id="display_reason"></tbody>
        </table>
    </fieldset>
</fieldset>

<script>
    $(document).ready(() => {
        loadReasons();
    })

    function loadReasons(){
        $.get('store/store.common.php',{
            load_to_display_adjustment_reason:'load_to_display_adjustment_reason'
        },(data)=> {
            $('#display_reason').html(data);
        })
    }

    function disableEnable(param){
        var status = $('#'+param).val();

        if (confirm('Are you sure, you want to ' + status)) {
            $.ajax({
                type: "POST",
                url: 'store/store.common.php',
                data: {
                    param:param,
                    status:status,
                    enable_disable_reasons:'enable_disable_reasons'
                },
                success: function (response) {
                    if(response != 100){
                        alert('something went contact administrator for support');
                    }
                }
            });
        }
    }

    function addNewReason(){
        var reason = $('#reason').val();
        var nature = $('#nature').val();
        var Employee_ID = $('#Employee_ID').val();

        if(reason == ""){
            $('#reason').css('border','1px solid red');exit;
        }
        $('#reason').css('border','1px solid #ccc');

        if(nature == ""){
            alert('Please select nature of the reason');exit;
        }

        $.ajax({
            type: "POST",
            url: "store/store.common.php",
            data: {
                reason:reason,
                nature:nature,
                Employee_ID:Employee_ID,
                add_new_reason:'add_new_reason'
            },
            success: (response) => {
                if(response == 100){
                    loadReasons();
                }else{
                    alert('Please contact administrator for support');
                }
            }
        });

    }
</script>

<?php include("./includes/footer.php"); ?>