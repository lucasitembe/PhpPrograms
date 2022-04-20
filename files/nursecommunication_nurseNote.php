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


<fieldset style="margin-top:15px;">
  <center>
    <table border="0" class="hiv_table" > 
        <tr>
        <td colspan="4"><span class="power_charts_status"><center><b>NURSES NOTES</b></center></span></td>
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
        <td>
                <fieldset style="margin-top:4px;">
                <center>
                <table border="0" class="hiv_table" style="width:100%">
                <tr>
                        <th colspan="1" width="25%">
        Date and Time
        </th>
        <th colspan="2" width="75%">
        Nurse Notes
        </th>
        </tr>
        </tr>
        <tr>
        <td colspan="1" width="25%">
        <input name="" type="text"  value="&nbsp;&nbsp;&nbsp;&nbsp;<?php echo date('d-m-y'); ?>">
        </td>
        <td colspan="2" width="75%">
        <textarea rows="20"></textarea>
        </td>
        </tr>
        </table>
        </center>
        </fieldset>
        </td>
        </tr>
    </table>               
</center>
</fieldset>
                
                
<?php
    include("./includes/footer.php");
?>