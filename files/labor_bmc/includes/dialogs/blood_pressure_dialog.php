<div id="show_dialogbp">
    <center>
        <br />
        <table style="border:none;width:100%;">
            <tr style="height:35px; width:60%;">
                <td style="text-align:center">Blood Pressure</td>
                <td style="text-align:center">Select Hour</td>
            </tr>
            <tr style="height:35px; width:60%;">
                <td style="text-align:center">
                    <input type="text" name="bp_remark" id="bp_remark">
                </td>
                <td style="text-align:center">
                    <select id="show-timebp" name="timebp" required>
                        <option value="">--Select Hour--</option>
                        <?php for ($i = 0; $i <= 24; $i++) { ?>
                            <option value="<?= ($i); ?>"><?= ($i); ?></option>
                        <?php }?>
                    </select>
                </td>
            </tr>
        </table>
        <br />
        <input id="savebp" value="Save" type="submit" style="width:20%;" name="submit" class="art-button-green">
    </center>
</div>