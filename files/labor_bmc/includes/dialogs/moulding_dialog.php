<div id="show_dialogm">

    <center>

        <br>

        <table style="border:none;width:100%;">

            <tr style="height:35px; width:60%;">
                <td style="text-align:center">Moulding</td>
                <td style="text-align:center">Select Hour</td>
            </tr>

            <tr style="height:35px; width:60%;">
                <td style="text-align:center" id="selected_moulding"></td>
                <td style="text-align:center">
                    <select class="show-timem" name="time" required>
                        <option value="">--Select Hour--</option>
                        <?php for ($i = 0; $i <= 24; $i++) { ?>
                            <option value="<?= ($i); ?>"><?= ($i); ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>

        </table>

        <br>

        <input id="savem" value="Save" type="submit" style="width:20%;" class="art-button-green">

    </center>
</div>