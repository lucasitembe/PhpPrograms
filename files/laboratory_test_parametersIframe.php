<?php
  include("./includes/header_Iframe.php");
  include("./includes/connection.php");
  ?>
        <center>
        <div style="background-color:white;">
            <table  border="0"  style="width:100%;margin-left:0px;margin-top:0px;margin-bottom:0px;border:1px solid #ccc;" class="hiv_table">
						<t  bgcolor="#eee">
								<th style="font-size:13px;">SN</th>
								<th style="font-size:13px;">Parameter Name</th>
								<th style="font-size:13px;">Results</th>
								<th style="font-size:13px;">UoM</th>
								<th style="font-size:13px;">T</th>
								<th style="font-size:13px;">M</th>
								<th style="font-size:13px;">V</th>
								<th style="font-size:13px;">S</th>
								<th style="font-size:13px;">Normal Value</th>
								<th style="font-size:13px;">H</th>
								<th style="font-size:13px;">Previous Result</th>
						</tr>
		<?php

		$sql =mysqli_query($conn,"SELECT p.Laboratory_Parameter_Name,tp.Unit_Of_Measure as Unit_Of_Measure,tp.Lower_Value,tp.Operator,tp.Higher_Value FROM tbl_laboratory_test_parameters as tp
									join tbl_laboratory_parameters as p ON p.Laboratory_Parameter_ID = tp.Laboratory_Parameter_ID
									JOIN tbl_items as i ON i.Item_ID = tp.Item_ID
										 where tp.Item_ID ='".filter_input(INPUT_GET, 'Item_ID')."'");
		$i=1;
		while($disp =mysqli_fetch_array($sql)){
			extract($disp);
					?>	 	 	 	 	 	 	 	 	 	 	 	 	 	 
						<tr bgcolor="#eee">
							<td style="color:blue;border:1px solid #ccc;"><?php echo $i; ?></td>
							<td style="color:blue;border:1px solid #ccc;"><?php echo $Laboratory_Parameter_Name; ?></td>
							<td style="color:blue;border:1px solid #ccc;"><input name="Laboratory_Results" type="text" value=""></td>
							<td style="color:blue;border:1px solid #ccc;"><?php echo $Unit_Of_Measure; ?></td>
							<td style="color:blue;border:1px solid #ccc;"><?php echo ''; ?></td>
							<td style="color:blue;border:1px solid #ccc;"><?php echo ''; ?></td>
							<td style="color:blue;border:1px solid #ccc;"><?php echo ''; ?></td>
							<td style="color:blue;border:1px solid #ccc;"><?php echo ''; ?></td>
							<td style="color:blue;border:1px solid #ccc;"><?php echo $Lower_Value." ".$Operator." ".$Higher_Value; ?></td>
							<td style="color:blue;border:1px solid #ccc;"><?php echo ''; ?></td>
							<td style="color:blue;border:1px solid #ccc;"><?php echo ''; ?></td>
						</tr>

<?php
$i++;
}

?>
            </table></div>
        </center>