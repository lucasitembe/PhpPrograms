<div id="show_dialogd">

    <center>

        <br>

        <table style="border:none;width:100%;">

            <tr style="height:35px; width:60%;">
                <td style="text-align:center">Drops</td>
                <td style="text-align:center">Select Hour</td>
            </tr>

            <tr style="height:35px; width:60%;">
                <td style="text-align:center">
                    <input type="text" name="" id="drop_remark" value="">
                </td>
                <td style="text-align:center">
                    <select id="show-timed" name="timed" required>
                        <option value="">--Select Hour--</option>
                        <?php for ($i = 0; $i <= 24; $i++) { ?>
                            <option value="<?= ($i); ?>"><?= ($i); ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>

        </table>

        <br>

        <input id="saved" value="Save" type="submit" style="width:20%;" name="submit" class="art-button-green">

    </center>
    
</div>