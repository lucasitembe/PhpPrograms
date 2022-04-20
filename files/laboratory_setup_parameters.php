
<?php
	include("./includes/header.php");
	include("./includes/connection.php");
	$requisit_officer=$_SESSION['userinfo']['Employee_Name'];
	
    if(!isset($_SESSION['userinfo'])){ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");}
    if(isset($_SESSION['userinfo']))
	{
		if(isset($_SESSION['userinfo']['Laboratory_Works']))
		{
			if($_SESSION['userinfo']['Laboratory_Works'] != 'yes'){ header("Location: ./index.php?InvalidPrivilege=yes");} 
		}else
			{
				header("Location: ./index.php?InvalidPrivilege=yes");
			}
    }else
		{ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes"); }

    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Laboratory_Works'] == 'yes')
            { 
            //echo "<a href='laboratory_parameter_list.php' class='art-button-green'>PARAMETER LIST</a>";
            echo "<a href='' class='art-button addParameter'>ADD PARAMETER</a>";
            }
    }

    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Laboratory_Works'] == 'yes')
            { 
            echo "<a href='laboratory_setup.php' class='art-button-green'>BACK</a>";
            }
    }
	echo'<br />';  
	?>
	<fieldset>
        <legend align=center><b>LABORATORY WORKS</b></legend>
	<?php
    echo '<div id="showParameters">';
    include 'requests/getParameters.php';
    echo '</div>';
 
   ?>
    </fieldset> 
<div id="newparameter" style="display: none">
<table style="margin_top:20px;width: 100%" class="hiv_table" >
<tr>
<td>
<br><br>
<br><br>
<fieldset>  
	<p id="parameterstatus" style="font-weight: bold"></p>
			<table class="hiv_table" style="width:100%">
				<tr>
					<td width=25%  style="color:black;border:1px solid #ccc;text-align:right;">Parameter Name</td>
						<td width=75%  style="color:blue;border:1px solid #ccc;">
						<input type='text' name='Parameter_Name' id='ParameterName' required='required' placeholder='' autocomplete='off'>
					</td> 
				</tr>
				<tr>
					<td width=25%  style="color:black;border:1px solid #ccc;text-align:right;">Unit of measure</td>
						<td width=75%  style="color:blue;border:1px solid #ccc;">
						<input type='text' name='Parameter_Name' id='unitofmeasure' required='required' placeholder='' autocomplete='off'>
					</td> 
				</tr>
				<tr>
					<td width=25%  style="color:black;border:1px solid #ccc;text-align:right;">Lower Value</td>
						<td width=75%  style="color:blue;border:1px solid #ccc;">
						<input type='text' name='Parameter_Name' id='lowervalue' required='required' placeholder='' autocomplete='off'>
					</td> 
				</tr>
				<tr>
					<td width=25%  style="color:black;border:1px solid #ccc;text-align:right;">Higher Value</td>
						<td width=75%  style="color:blue;border:1px solid #ccc;">
						<input type='text' name='Parameter_Name' id='highervalue' required='required' placeholder=''  autocomplete='off'>
					</td> 
				</tr>
				<tr>
					<td width=25%  style="color:black;border:1px solid #ccc;text-align:right;">Operator</td>
						<td width=75%  style="color:blue;border:1px solid #ccc;">
						<input type='text' name='Parameter_Name' id='Operator' required='required' placeholder=''  autocomplete='off'>
					</td> 
				</tr>
				<tr>
					<td width=25%  style="color:black;border:1px solid #ccc;text-align:right;">Result type</td>
						<td width=75%  style="color:blue;border:1px solid #ccc;">
						<select id='results' required='required'>
							<option></option>
							<option>Quantitative</option>
							<option>Qualitative</option>
						</select>
						
						</td> 
				</tr>
				
				<tr>
					<td colspan=2 style='text-align: right;'>
					<input type='button' id="saveParameterSave" name='submit'value='   SAVE   ' class='art-button-green'>
					<input type='reset' name='clear' value=' CLEAR ' class='art-button-green' >
					
					</td>

			</tr>
		</table>
			</fieldset>
		</td>
	</tr>
</table>      

</div>


<div id="editthisParameter" style="display: none">
    <div id="showParameterlist"> 
    
    </div> 
  
   
</div>

<link rel="stylesheet" href="table.css" media="screen">
<link rel="stylesheet" href="../css/jquery-ui.css" media="screen">
<script src="css/jquery.js" type="text/javascript"></script>
<script src="css/jquery-ui.js" type="text/javascript"></script>
<script src="css/scripts.js" type="text/javascript"></script>

   
   <?php
     
    include("./includes/footer.php");
    
    ?>

