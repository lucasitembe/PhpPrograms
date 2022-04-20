<div id="show_dialogt">

    <center>

        <br>

        <table style="border:none;width:100%;">

            <tr style="height:35px; width:60%;">
                <td style="text-align:center">Temperature</td>
                <td style="text-align:center">Select Hour</td>
            </tr>

            <tr style="height:35px; width:60%;">
                <td style="text-align:center" id="selected_liqour">
                    <input type="text" name="" id="temp_remark" value="">
                </td>
                <td style="text-align:center">
                    <select id="show-timet" name="timet" required>
                        <option value="">--Select Hour--</option>
                        <?php for ($i = 0; $i <= 24; $i++) { ?>
                            <option value="<?= ($i); ?>"><?= ($i); ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>

        </table>

        <br>

        <input id="savet" value="Save" type="submit" style="width:20%;" class="art-button-green">
        
    </center>
</div>