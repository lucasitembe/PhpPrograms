<?php
	include("./includes/header.php");
	include("./includes/connection.php");
	$requisit_officer=$_SESSION['userinfo']['Employee_Name'];
	
    if(!isset($_SESSION['userinfo'])){ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");}
    if(isset($_SESSION['userinfo']))
	{
		if(isset($_SESSION['userinfo']['Storage_And_Supply_Work']))
		{
			if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){ header("Location: ./index.php?InvalidPrivilege=yes");} 
		}else
			{
				header("Location: ./index.php?InvalidPrivilege=yes");
			}
    }else
		{ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes"); }

    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes')
            { 
            echo "<a onclick='goBack()' class='art-button-green'>BACK</a>";
            }
    }
                ?>


<fieldset style="margin-top:5px;">
  <center>
    <table   border="0" class="hiv_table"> 
        <tr>
             <td colspan="4"><span class="power_charts_status"><center><b>Antnatal Observation Chart</b></center></span></td>
        </tr>
                <tr>
                    <td colspan="4" width="100%"><hr></td>
                </tr>
                <tr>
                    <td colspan="4"><center>Adriano Dominick, Male, 4 years of age,In-Patient Credit Bill, Cash</center>
                </tr>
                <tr>
                    <td colspan="4" width="100%"><hr></td>
                </tr>
        <tr>
        <td colspan="4">
        <center>
            <table class="hiv_table" style="width:100%">
                <tr>
<th>Date and Time</th><th>BP</th><th>Pulse</th><th>Temperature</th><th>Respiration</th><th>FHR</th><th>FH</th>
<th>BWT</th><th>Urine For Proten</th></tr>
<tr>
                    <td> <input name=""></td>
                    <td><input name=""></td>
                    <td> <input name=""> </td> 
                    <td><input name=""></td>
                    <td><input name=""></td>
                    <td><input name=""></td>
                    <td><input name=""></td>
                    <td><input name=""></td>
                    <td><input name=""></td>
                    <td style="text-align: center;"><input type="submit" name="submit" id="submit" value="ADD" class="art-button-green">
                    <input type="hidden" name="" value="true"/></td>
                </tr>
                 <tr>
                    <td colspan="10" width="100%" >
                    <iframe src="nursecommunication_antnatal_Iframe.php" width="100%" height="300px"></iframe>
                    </td>
                </tr>
            </table> 
            </center>
        </td>
        </tr>

    </table>               
</center>
</fieldset>
                
                
<?php
    include("./includes/footer.php");
?>