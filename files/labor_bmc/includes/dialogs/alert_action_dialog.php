<div id="show_dialog_alert_action">

    <center>

        <br>

        <table style="border:none;width:100%;">

            <tr style="height:35px; width:60%;">
                <td style="text-align:center">ALERT</td>
                <td style="text-align:center">ACTION</td>
            </tr>

            <tr style="height:35px; width:60%;">
                <td style="text-align:center">
                    First alert point :
                    <select id="alert_x1" required>
                        <option value="">--X1--</option>
                        <?php for ($i = 0; $i <= 24; $i++) { ?>
                            <option value="<?= ($i); ?>"><?= ($i); ?></option>
                        <?php } ?>
                    </select>
                    <select id="alert_y1" required>
                        <option value="">--Y1--</option>
                        <?php for ($i = 0; $i <= 10; $i++) { ?>
                            <option value="<?= ($i); ?>"><?= ($i); ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td style="text-align:center">
                    First action point:
                    <select id="action_x1" required>
                        <option value="">--X1--</option>
                        <?php for ($i = 0; $i <= 24; $i++) { ?>
                            <option value="<?= ($i); ?>"><?= ($i); ?></option>
                        <?php } ?>
                    </select>
                    <select id="action_y1" required>
                        <option value="">--Y1--</option>
                        <?php for ($i = 0; $i <= 10; $i++) { ?>
                            <option value="<?= ($i); ?>"><?= ($i); ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>

            <tr style="height:35px; width:60%;">
                <td style="text-align:center">
                    Second alert point :
                    <select id="alert_x2" required>
                        <option value="">--X2--</option>
                        <?php for ($i = 0; $i <= 24; $i++) { ?>
                            <option value="<?= ($i); ?>"><?= ($i); ?></option>
                        <?php } ?>
                    </select>
                    <select id="alert_y2" required>
                        <option value="">--Y2--</option>
                        <?php for ($i = 0; $i <= 10; $i++) { ?>
                            <option value="<?= ($i); ?>"><?= ($i); ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td style="text-align:center">
                    Second action point:
                    <select id="action_x2" required>
                        <option value="">--X2--</option>
                        <?php for ($i = 0; $i <= 24; $i++) { ?>
                            <option value="<?= ($i); ?>"><?= ($i); ?></option>
                        <?php } ?>
                    </select>
                    <select id="action_y2" required>
                        <option value="">--Y2--</option>
                        <?php for ($i = 0; $i <= 10; $i++) { ?>
                            <option value="<?= ($i); ?>"><?= ($i); ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>

        </table>

        <br>

        <input id="save_alert_action" value="Save" type="submit" style="width:20%;" class="art-button-green">

    </center>

</div>