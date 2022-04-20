<?php
	include("./includes/header.php");
	include("./includes/connection.php");
	$requisit_officer=$_SESSION['userinfo']['Employee_Name'];
	
    if(!isset($_SESSION['userinfo'])){ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");}
   /*
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
	*/
                ?>


<fieldset style="margin-top:5px;">
    <center>
         <table width="95%" class="hiv_table" border="0" >
                         <tr>
                             <td><a href="" class="art-button-green" >Save and Go Back</a></td>
                             <td colspan="3"><span class="power_charts_status"><b>Power Charts:</b> Family Planning</td></span>
                        </tr>
                         <tr>
                             <td width="100%" colspan="4"><hr></td>
                        </tr>
                        <tr>
                            <td width="30%" class="powercharts_td_left" style="text-align:right;">ID Number</td><td><input name="" type="text"></td>
                            <td class="powercharts_td_left"  style="text-align:right;">Husband Name/Partner Name</td><td><input name="" type="text"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left" style="text-align:right;">Is This Family Planning Consultation?</td><td>
                                <Select class="select_contents">
                                    <option> Select From List</option>
                                         <option class="select_contents">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                        Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                    <option class="select_contents">No</option>
                                </Select>
                                                                                                     </td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left" style="text-align:right;">How Many Previous Pregnancies?</td><td><input name="" type="text"></td></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left" style="text-align:right;">How Many Births?</td><td><input name="" type="text"></td>
                            <td class="powercharts_td_left" style="text-align:right;">How Many Still Births/Aborted Pregnancies?</td><td><input name="" type="text"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left" style="text-align:right;">How Many Children Who Died Within 7 Days Of Birth?</td><td><input name="" type="text"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left" style="text-align:right;">How Many Current Children?</td><td><input name="" type="text"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left" style="text-align:right;">Age Of Last Child</td><td><input name="" type="text"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left" style="text-align:right;">Method Of Contraception Being Chosen</td><td><input name="" type="text"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left" style="text-align:right;">Recommended Date For Review Consultation</td><td><input name="" type="text"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left" style="text-align:right;">Other Comments</td><td rowspan="3" colspan="3"><textarea></textarea>
                        <tr>
                            <td class="powercharts_td_left"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left"></td>
                        </tr>
                         <tr>
                             <td colspan="4" rowspan="2" class="powercharts_footer">&nbsp;<br><br></td></td>
                        </tr>
                    </table>
    </center>
</fieldset>
                
                
                
                

                
                
<?php
    include("./includes/footer.php");
?>