<div id="show_dialogo">

    <center>

        <br>

        <table style="border:none;width:100%;">

            <tr style="height:35px; width:60%;">
                <td style="text-align:center">Oxyticine</td>
                <td style="text-align:center">Select Hour</td>
            </tr>

            <tr style="height:35px; width:60%;">
                <td style="text-align:center">
                    <input type="text" id="oxytocine_remark">
                </td>
                <td style="text-align:center">
                    <select id="show-timeo" name="timeo" required>
                        <option value="">--Select Hour--</option>
                        <?php for ($i = 0; $i <= 24; $i++) { ?>
                            <option value="<?= ($i); ?>"><?= ($i); ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>

        </table>

        <br>

        <input id="saveo" value="Save" type="submit" style="width:20%;" class="art-button-green">

    </center>
    
</div>