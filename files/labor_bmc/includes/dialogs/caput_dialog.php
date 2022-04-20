<div id="show_dialog_caput">

    <center>

        <br>

        <table style="border:none;width:100%;">

            <tr style="height:35px; width:60%;">
                <td style="text-align:center">Caput</td>
                <td style="text-align:center">Select Hour</td>
            </tr>

            <tr style="height:35px; width:60%;">
                <td style="text-align:center">
                    <select id="caput_remark" style="width:90px;">
                        <option value="0">0</option>
                        <option value="+">+</option>
                        <option value="++">++</option>
                        <option value="+++">+++</option>
                    </select>
                </td>
                <td style="text-align:center">
                    <select id="time_caput" name="time_caput" required>
                        <option value="">--Select Hour--</option>
                        <?php for ($i = 0; $i <= 24; $i++) { ?>
                            <option value="<?= ($i); ?>"><?= ($i); ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>

        </table>

        <br>

        <input id="save_caput" value="Save" type="submit" style="width:20%;" class="art-button-green">

    </center>

</div>