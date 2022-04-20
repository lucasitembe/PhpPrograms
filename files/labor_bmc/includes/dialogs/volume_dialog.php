<div id="show_dialogv">

    <center>

        <br>

        <table style="border:none;width:100%;">

            <tr style="height:35px; width:60%;">
                <td style="text-align:center">Volume</td>
                <td style="text-align:center">Select Hour</td>
            </tr>

            <tr style="height:35px; width:60%;">
                <td style="text-align:center">
                    <input type="text" name="volume_remark" id="volume_remark" value="">
                </td>
                <td style="text-align:center">
                    <select id="show-timev" name="timev" required>
                        <option value="">--Select Hour--</option>
                        <?php for ($i = 0; $i <= 24; $i++) { ?>
                            <option value="<?= ($i); ?>"><?= ($i); ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>

        </table>

        <br>

        <input id="savev" value="Save" type="submit" style="width:20%;" class="art-button-green">

    </center>

</div>