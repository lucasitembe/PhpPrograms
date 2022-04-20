<div id="show_dialog_temp_time">

    <center>

        <br>

        <table style="border:none;width:100%;">

            <tr style="height:35px; width:60%;">
                <td style="text-align:center">Time</td>
                <td style="text-align:center">Select hour</td>
            </tr>

            <tr style="height:35px; width:60%;">
                <td style="text-align:center" id="selected_liqour">
                    <input id="temp_time_remark" placeholder="Select time" type="text" autocomplete="off" class="form-control" style='text-align: center;width:30%;display:inline'>
                </td>
                <td style="text-align:center">
                    <select id="temp_time_hours" name="time_hour" required>
                        <option value="">--Select Hour--</option>
                        <?php for ($i = 0; $i <= 24; $i++) { ?>
                            <option value="<?= ($i); ?>"><?= ($i); ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>

        </table>

        <br>

        <input id="save_temp_time" value="Save" type="submit" style="width:20%;" class="art-button-green">

    </center>

</div>