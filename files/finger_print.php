<div id="finger_print_dialog">
<table width='100%'>
  <tr>
    <!--th style="text-align:center;">Left Thumb</th-->
    <th style="text-align:center;"> Thumb</th>
    <th style="text-align:center;"> Thumb</th>
    <th style="text-align:center;"> Thumb</th>
  </tr>
  <tr>
    <td width='33%' style="text-align:center;padding:15px;">
      <center>
      <div class="finger_print" style="border:1px dashed gray;width:70%;height:180px;background-color:#fff;cursor:pointer;"  title="click here to take finger print" onclick="load_finger_print_module('capture','step1',this);" >

      </div>
    </center>
    </td>
	<td width='33%' style="text-align:center;">
      <center>
      <div class="finger_print" style="border:1px dashed gray;width:70%;height:180px;background-color:#fff;cursor:pointer;"  title="click here to take finger print" onclick="load_finger_print_module('capture','step2',this);" >

      </div>
    </center>
    </td>
	<td width='33%' style="text-align:center;">
      <center>
      <div class="finger_print" style="border:1px dashed gray;width:70%;height:180px;background-color:#fff;cursor:pointer;"  title="click here to take finger print" onclick="load_finger_print_module('capture','step3',this);" >

      </div>
    </center>
    </td>
  </tr>
  <tr>
    <!--td width='50%'>&emsp;</td-->
    <td width='50%' colspan='3' style='padding:15px;'> 
		<center>
			<input type="submit" name="reset_finger_print" value="Reset" class="art-button-green" onclick="reset_finger_print();">
			<input type="submit" name="save_finger_print" value="Save" class="art-button-green" onclick="save_finger_print();">
		</center> 
	  </td>
  </tr>
</table>
</div>
<div style="display:none;">
	<img src="images/fingerprint.gif">
</div>
<script type="text/javascript">
$(document).ready(function () {
    $("#finger_print_dialog").dialog({autoOpen: false, width: '60%', height: 350, title: 'TAKE FINGER PRINT', modal: true, position: 'middle'});
});
</script>
