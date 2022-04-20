<div id="show_dialog_liqour_time">

    <center>

        <br>

        <table style="border:none;width:100%;">

            <tr style="height:35px; width:60%;">
                <td style="text-align:center">Time</td>
                <td style="text-align:center">Select Hour</td>
            </tr>

            <tr style="height:35px; width:60%;">
                <td style="text-align:center">
                    <input id="liquor_time_remark" placeholder="Select time" type="text" class="form-control" style='text-align: center;width:25%;display:inline'>
                </td>
                <td style="text-align:center">
                    <select id="liquor_time_hours" name="time_hour" required>
                        <option value="">--Select Hour--</option>
                        <?php for ($i = 0; $i <= 24; $i++) { ?>
                            <option value="<?= ($i); ?>"><?= ($i); ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>

        </table>

        <br>

        <input id="save_liquor_time" value="Save" type="submit" style="width:20%;" class="art-button-green">

    </center>

</div>