<div id="show_dialogc">

    <center>

        <br>

        <table style="border:none;width:100%;">

            <tr style="height:35px; width:60%;">
                <td style="text-align:center">Contraction Per 10 Minutes</td>
            </tr>

            <tr style="height:35px; width:60%;">
                <td style="text-align:center">
                    <label>
                        <span>
                            <img src="img/dot.png" alt="" width="34px" height="34px">
                        </span>
                        <span style="margin-right:9px;">0-20 sec</span>
                        <span>
                            <input type="radio" class="contraction_per_min" name="contraction_per_min" value="1"> </span>
                    </label>

                    <label>
                        <span>
                            <img src="img/slash.png" alt="" width="34px" height="34px">
                        </span>
                        <span style="margin-right:9px;">20-40 sec</span>
                        <span>
                            <input type="radio" class="contraction_per_min" name="contraction_per_min" value="2">
                        </span>
                    </label>

                    <label>
                        <span>
                            <img src="img/full_black.png" alt="" width="34px" height="34px">
                        </span>
                        <span>More Than 40 sec</span>
                        <span>
                            <input type="radio" class="contraction_per_min" name="contraction_per_min" value="3">
                        </span>
                    </label>
                </td>
            </tr>

            <tr style="height:50px;">
                <td style="text-align:center">
                    <select class="show-timec" name="time" required>
                        <option value="">--Select Hour--</option>
                        <?php for ($i = 0; $i <= 24; $i++) { ?>
                            <option value="<?= ($i); ?>"><?= ($i); ?></option>
                        <?php } ?>
                    </select>

                    <select class="contraction_loop" name="time" required>
                        <option value="">--Select Contraction--</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>

                    <select class="contraction_no" name="time" required>
                        <option value="">--Start Point--</option>
                        <option value="one">1</option>
                        <option value="two">2</option>
                        <option value="three">3</option>
                        <option value="four">4</option>
                        <option value="five">5</option>
                    </select>

                    <!-- <input id="contraction_time_remark" placeholder="Select time" type="text" style='text-align: center;width:15%;display:inline'> -->

                </td>
            </tr>

        </table>

        <br>

        <input id="savec" value="Save" type="submit" style="width:20%;" class="art-button-green">

    </center>
    
</div>