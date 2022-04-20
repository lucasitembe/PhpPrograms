<div id="show_dialogp">

    <center>

        <br>

        <table style="border:none;width:100%;">

            <tr style="height:35px; width:60%;">
                <td style="text-align:center">Protein</td>
                <td style="text-align:center">Select Hour</td>
            </tr>

            <tr style="height:35px; width:60%;">
                <td style="text-align:center">
                    <input type="text" name="protein_remark" value="" id="protein_remark">
                </td>
                <td style="text-align:center">
                    <select class="show-time" id="show-timepro" name="time" required>
                        <option value="">--Select Hour--</option>
                        <?php for ($i = 0; $i <= 24; $i++) { ?>
                            <option value="<?= ($i); ?>"><?= ($i); ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>

        </table>

        <br>

        <input id="savep" value="Save" type="submit" style="width:20%;" class="art-button-green">

    </center>

</div>