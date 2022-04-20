<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = 0;
	}

	if(isset($_GET['Patient_Name'])){
		$Patient_Name = $_GET['Patient_Name'];
	}else{
		$Patient_Name = 0;
	}

	if(isset($_GET['age'])){
		$age = $_GET['age'];
	}else{
		$age = 0;
	}

	if(isset($_GET['Gender'])){
		$Gender = $_GET['Gender'];
	}else{
		$Gender = 0;
	}
	$Employee_ID = $_SESSION['Userinfo']['Employee_ID'];
?>

	<div style="width:48%;float:left">              
      	<fieldset style="width:92%;">
          	<legend>&nbsp;A: UTAMBULISHO</legend>
          	<table width=100%>
               	<tr>
					<td style="text-align:right;">Namba ya Wodi/Kliniki ya Wagonjwa wa Nje</td>
					<td width="40%"><input type='text' name='Na_ya_Wodi' id='Na_ya_Wodi' ></td>
              	</tr>
              	<tr>
                   	<td style="text-align:right;">Jina la Balozi</td>
                   	<td width="40%"><input type='text' name='Name_Balozi' id='Name_Balozi' ></td>
              	</tr>
          		<tr>
               		<td style="text-align:right;"><b style='color: red'>Aina ya msamaha</b></td>
                   	<td width="40%">
                       	<div id="displayMsamaha">
                            <select name="Aina_ya_msamaha" id="Aina_ya_msamaha" style="padding:4px;width:99%" required>
                                <option></option>
                                <?php
                                	$Query= mysqli_query($conn,"SELECT msamaha_aina FROM tbl_msamaha_items");
									while ($row=  mysqli_fetch_assoc($Query)){
                                    	echo '<option>'.$row['msamaha_aina'].'</option>'; 
									}
                                ?>
                            </select>
                        </div>
                   </td>
          		</tr>
      		</table>
      	</fieldset>
      	<fieldset style="width:92%;">
          	<legend>&nbsp;B: MAELEZO YA ZIADA</legend>
          	<table width=100%>
                <tr>
                    <td style="text-align:right;">Kiwango cha elimu</td>
                    <td width="40%"><input type='text' name='Education_Level' id='Education_Level' ></td>
                </tr>
                <tr>
                    <td style="text-align:right;">Kazi ya mke/mlezi</td>
                    <td width="40%"><input type='text' name='Work_Wife' id='Work_Wife' ></td>
                </tr>
                <tr>
                    <td style="text-align:right;">Idadi ya mahudhurio(wagojwa wa nje kwa miezi 6 iliyopita)</td>
                    <td width="40%"><input type='text' name='Mahudhulio' id='Mahudhulio' ></td>
                </tr>
                <tr>
                    <td style="text-align:right;">Idadi ya kulazwa hospitali kwa miezi 6 iliyopita</td>
                    <td width="40%"><input type='text' name='Idadi_Mahudhulio' id='Idadi_Mahudhulio' ></td>
                </tr>
                <tr>
                    <td style="text-align:right;">Ni ndugu yako yupi anayeweze kukulipia malipo ya matibabu</td>
                    <td width="40%"><input type='text' name='Ni_ndugu_yako_yupi' id='Ni_ndugu_yako_yupi' ></td>
                </tr>
          </table>
      	</fieldset>
      	<fieldset style="width:92%;">
          	<legend>&nbsp;C: UCHUNGUZI</legend>
          	<table width=100%>
               	<tr>
                   	<td style="text-align:right;">Je mgonjwa amewahi kutibiwa mahali pengine?</td>
                   	<td width="40%">
                       	<select name='amewahi_kutibiwa_mahali' id='amewahi_kutibiwa_mahali' style="padding:4px;width:99%">
                           	<option>Ndio</option>
                           	<option>Hapana</option>
                       	</select>
                   </td>
              	</tr>
              	<tr>
                   	<td style="text-align:right;">Je mgonjwa amevaa nguo za thamani?</td>
                   	<td width="40%">
                       	<select name='amevaa_nguo_za_thamani' id='amevaa_nguo_za_thamani' style="padding:4px;width:99%">
                           	<option>Ndio</option>
                           	<option>Hapana</option>
                       	</select>
                   	</td>
              	</tr>
              	<tr>
	              	<td style="text-align:right;">Kama kuna mengineyo yanayoonyesha uwezo wa kuchangia:Eleza</td>
	                <td width="40%">
	                    <textarea rows="2" cols="10" name='mengineyo_yanayoonyesha_uwezo_wa_kuchangia' id='mengineyo_yanayoonyesha_uwezo_wa_kuchangia'></textarea>
	                </td>
              	</tr>
          	</table>
      	</fieldset>
  	</div>
	<div style="float:right;width:48%; ">
      	<fieldset style="width:92%;">
          	<legend>&nbsp;D: MAPENDEKEZO YA MSAMAHA</legend>
          	<table width=100%>
                <tr>
                   <td style="text-align:right;">Mapendekezo ya msamaha</td>
                   <td width="40%"><input type='text' name='Mapendekezo_ya_msamaha' id='Mapendekezo_ya_msamaha' ></td>
              	</tr>
               	<tr>
                   	<td style="text-align:right;"><b style='color: red'>Je anastahili kupata msamaha huo?</b></td>
                   	<td width="40%">
                   		<select name='anastahili_kupata_msamaha' id='anastahili_kupata_msamaha' style="padding:4px;width:99%" required>
                           <option>Ndio</option>
                           <option>Hapana</option>
                       	</select>
                    </td>
              	</tr>
              	<tr>
                   	<td style="text-align:right;">Jina la anayependekeza msamaha</td>
                   	<td width="40%">
                   		<input type='hidden' name='anayependekeza_msamaha_id' id='anayependekeza_msamaha_id' readonly value="<?php echo $_SESSION['userinfo']['Employee_ID'];?>">
						<input type='text' name='anayependekeza_msamaha' id='anayependekeza_msamaha' readonly value="<?php echo $_SESSION['userinfo']['Employee_Name'];?>">
                   	</td>
              	</tr>
               	<tr>
                   	<td style="text-align:right;">Cheo</td>
                   	<td width="40%">
                       	<input type='text' name='cheo_anayependekeza_msamaha' id='cheo_anayependekeza_msamaha' readonly value="<?php echo $_SESSION['userinfo']['Employee_Title'];?>">
                   	</td>
              	</tr>
              	<tr>
                   	<td style="text-align:right;"><b style='color: red'>Sahihi</b></td>
                   	<td width="40%">
                   		<select name='sahihi_anayependekeza_msamaha' required id='sahihi_anayependekeza_msamaha' style="padding:4px;width:99%">
                           <option>Nimekubali</option>
                           <option>Sijakubali</option>
                       </select>
                   </td>
              	</tr>
          	</table>
      	</fieldset>
      	<fieldset style="width:92%;">
          	<legend>&nbsp;E: IMEHADHIMISHWA</legend>
          	<table width=100%>
                <tr>
                   	<td style="text-align:right;"><b style='color: red'>Imehadhimishwa?</b></td>
                   	<td width="40%">
                   		<select name='Imehadhimishwa' id='Imehadhimishwa' style="padding:4px;width:99%" required>
                           <option>Ndio</option>
                           <option>Hapana</option>
                       </select>
                   	</td>
              	</tr>
               	<tr>
                   	<td style="text-align:right;"><b style='color: red'>Jina la anayehadhimisha</b></td>
                   	<td width="40%"> <input type='text' name='Jina_la_anayehadhimisha' required id='Jina_la_anayehadhimisha' value="<?php echo $_SESSION['userinfo']['Employee_Name'];?>"></td>
              	</tr>
               	<tr>
                   	<td style="text-align:right;"><b style='color: red'>Cheo</b></td>
                   	<td width="40%"><input type='text' name='cheo_anayehadhimisha' id='cheo_anayehadhimisha' required value="<?php echo $_SESSION['userinfo']['Employee_Title'];?>" ></td>
              	</tr>
              	<tr>
                   	<td style="text-align:right;"><b style='color: red'>Sahihi</b></td>
                   	<td width="40%">
                   		<select name='sahihi_anayehadhimisha' required id='sahihi_anayehadhimisha' style="padding:4px;width:99%">
                           <option>Nimekubali</option>
                           <option>Sijakubali</option>
                       	</select>
                   	</td>
              	</tr>
              	<tr>
                   	<td style="text-align:right;">Namba katika Rejista ya kupatiwa msamaha wa muda(Kama amesamehewa)</td>
                   	<td width="40%"> <input type='text' name='Namba_katika_Rejista_ya_kupatiwa_msamaha' id='Namba_katika_Rejista_ya_kupatiwa_msamaha' ></td>
              	</tr>
          	</table>
  		</fieldset>
        <fieldset style="width:92%;">
          	<table width=100%>
               	<tr>
                	<td colspan=2 style='text-align: right; ' width="40%">
	                    <input type="button" name="Update" id="Update" value="BONYEZA KUHIFADHI MAELEZO" class="art-button-green" onclick="Update_Msamaha_Details()">
	                    <input type="button" class="art-button-green" name="Cancel" id="Cancel" value="FUNGA" onclick="Close_Exemption_Details_Dialog()">
	                </td>
                </tr>
            </table>
      	</fieldset>
        <table width=100%>
           	<tr>
            	<td colspan=2 style='text-align: center;' id="Error_Message">
                    &nbsp;
                </td>
            </tr>
        </table>
	</div>

