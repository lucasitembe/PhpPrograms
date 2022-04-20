<?php
    include("./includes/header.php");
    include("./includes/connection.php");

Error_reporting(E_ERROR|E_PARSE);
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Radiology_Works'] == 'yes'){
	} 
}

    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Radiology_Works'] == 'yes'){

    //get all item details based on item id
    if(isset($_GET['RI'])){
		$Registration_ID = $_GET['RI'];
    }else{
		$Registration_ID = '';
    }

if(isset($_GET['PPILI'])){
	$Patient_Payment_Item_List_ID = $_GET['PPILI'];
}else{
	$Patient_Payment_Item_List_ID = '';
}

if(isset($_GET['PPI'])){
	$Patient_Payment_ID = $_GET['PPI'];
}else{
	$Patient_Payment_ID = '';
}
if(isset($_GET['II'])){
	$Item_ID = $_GET['II'];
}else{
	$Item_ID = '';
}

if(isset($_GET['Status_From'])){
	$Status_From = $_GET['Status_From'];
}else{
	$Status_From = '';
}

?>
<a href='radiologyviewimage.php?II=<?php echo $Item_ID ?>&RI=<?php echo $Registration_ID; ?>'class='art-button-green'>BACK</a>
<?php  } }
?>

<?php
    //get all item details based on item id
    if(isset($_GET['RI'])){
	$Registration_ID = $_GET['RI'];
    }else{
	$Registration_ID = '';
    }

if(isset($_GET['PPILI'])){
	$Patient_Payment_Item_List_ID = $_GET['PPILI'];
}else{
	$Patient_Payment_Item_List_ID = '';
}

if(isset($_GET['PPI'])){
	$Patient_Payment_ID = $_GET['PPI'];
}else{
	$Patient_Payment_ID = '';
}
if(isset($_GET['II'])){
	$Item_ID = $_GET['II'];
}else{
	$Item_ID = '';
}

if(isset($_GET['Status_From'])){
	$Status_From = $_GET['Status_From'];
}else{
	$Status_From = '';
}

$Results = mysqli_query($conn,"SELECT pr.Patient_Name,pp.Registration_ID,pr.Gender,pr.Date_Of_Birth,
		       pr.Registration_ID, pp.patient_payment_ID AS receipt, ppl.Check_In_Type,ppl.Item_ID,it.Product_Name
FROM tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr,tbl_items it
WHERE pr.Registration_ID = pp.Registration_ID
AND pp.Patient_Payment_ID = ppl.Patient_Payment_ID
AND ppl.Item_ID=it.Item_ID
AND pr.Registration_ID= '$Registration_ID'") or die (mysqli_error($conn));
    $no = mysqli_num_rows($Results);
	$Registration_ID=0;
    if($no > 0){
		while($row = mysqli_fetch_array($Results)){
			 $Registration_ID = $row['Registration_ID'];
			 $Patient_Name= $row['Patient_Name'];
			 $Gender= $row['Gender'];
			 $Date_Of_Birth = $row['Date_Of_Birth'];
			 $Product_Name=$row['Product_Name'];

			 //calculate age
			$date1 = new DateTime(date('Y-m-d'));
			$date2 = new DateTime($Date_Of_Birth);
			$diff = $date1 -> diff($date2);
			$age = $diff->y." Years, ";
			$age.= $diff->m." Months and ";
			$age.= $diff->d." Days ";
		}
    }
?>
<?php

    if(isset($_POST['submitRadilogyform'])){
	 if(isset($_SESSION['userinfo'])){
           if(isset($_SESSION['userinfo']['Employee_ID'])){
                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
           }else{
				$Employee_ID = 0;
           }
        }
	//$Registration_ID=mysqli_real_escape_string($conn,$_POST['Registration_ID']);

	$parameter=mysqli_real_escape_string($conn,$_POST['Parameter_ID']);
	$Registration_ID=mysqli_real_escape_string($conn,$_POST['Registration_ID']);
	$comments=mysqli_real_escape_string($conn,$_POST['comments']);
	$comments=strip_tags($comments,"<br>");
	//$Registration_ID = $_GET['Registration_ID'];

	$insert_parameterComments = "INSERT INTO tbl_radiology_discription(Registration_ID,Parameter_ID,Patient_Payment_Item_List_ID,comments,Radiology_Date) VALUES('$Registration_ID','$parameter','$Patient_Payment_Item_List_ID','$comments',(select now()))";

	$result=mysqli_query($conn,$insert_parameterComments) or die(mysqli_error($conn));
	 if($result){?>
		<script>
			alert("Radiology Result/Comment Successfully Added.");
			location.href="Radiologyverryfyimagepage.php?RI=<?php echo $Registration_ID?>&Status_From=<?php echo $Status_From;?>&II=<?php echo $Item_ID;?>&PPI=<?php echo $Patient_Payment_ID;?>&PPILI=<?php echo $Patient_Payment_Item_List_ID?>&RadiologyVeryfyImageThisPage=ThisPage";
		</script>
		   <?php }else{?>
	 	<script>
			alert("Failed To Add Radiology Result For This Item On This Patient.");
			location.href="Radiologyverryfyimagepage.php?RI=<?php echo $Registration_ID?>&Status_From=<?php echo $Status_From;?>&II=<?php echo $Item_ID;?>&PPI=<?php echo $Patient_Payment_ID;?>&PPILI=<?php echo $Patient_Payment_Item_List_ID?>&RadiologyVeryfyImageThisPage=ThisPage";
		</script>
	 	<?php }
		}
?>

<center>
<form action="#" method="POST"  name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
<fieldset style="width:85%;margin-top:10px;">
	
		<strong><?php  echo $Patient_Name ."</strong>  | <strong> ". $Gender."</strong>  | <strong> ".$age."</strong> "; ?> <br />
		<strong> Test For: </strong> <?php echo $Product_Name;?>

	    <hr>
	    <table width="95%">
			<tr>
				<th width="15%"> Parameter </th>
				<th >Results/Comments</th>
		    </tr>
		  <tr>
			<td width="20%">
				<select  name="Parameter_ID" id="Parameter_ID" width=20% style="border-radius:1px;box-shadow:0 0 1px 1px #123456;" required="required">
				   <option></option>
					<?php
					$data = mysqli_query($conn,"SELECT * FROM tbl_radiology_parameter");
					 while($row = mysqli_fetch_array($data)){
						   ?>
					 <option value='<?php echo $row['Parameter_ID'];?>'>
					<?php echo $row['Parameter_Name']; ?>
					</option>
					<?php
					}
				  ?>
				</select>
			</td>
			<td width="80%" >
				<textarea name="comments" id="write_comment" style="width:95%;border-radius:1px;box-shadow:0 0 1px 1px #123456;margin-top:10px;padding:5px;border:1px;"></textarea></td>
			<td>
				<div style="width:100%;text-align:right;padding-right:10px;">
					<input type="submit" value="ADD" class='art-button-green' name="submit" id="submit" onclick="return validateForm();">
					<input type='hidden' name='submitRadilogyform' value='true'/>
				</div>
			</td>
	</tr>

	</table>
	<br>
           <legend align="center"><b>COMMENTS AND DESCRIPTION</b></legend>
            <center>
            <table width="100%">
                <tr>
            <td id='Search_Iframe'>
		<iframe width='100%' height="180px" src=' Radiologyverryfyimagepage_iframe.php?RI=<?php echo $Registration_ID;?>'></iframe>
            </td>
				</tr>
				<tr>
					<td>
						<script>
							function Update_Data(Status_From,Patient_Payment_Item_List_ID,Item_ID){
								var update=confirm("Are You Sure You Are Done With This Patient?");

								if(update){
									if(window.XMLHttpRequest) {
										myObject = new XMLHttpRequest();
									}else if(window.ActiveXObject){
										myObject = new ActiveXObject('Micrsoft.XMLHTTP');
										myObject.overrideMimeType('text/xml');
									}
									myObject.onreadystatechange = function (){
										data = myObject.responseText;

										if (myObject.readyState == 4) {
											if (data=='success') {
											    alert('Radiology Data For This Patient Successfully Saved');
											    location.href="PatientRadiology.php?RadiologyPatientThisList=ThisPage";
											}else{
											    alert('Failed To Save Radiology Data For This Patient.');
											}

										}
									}; //specify name of function that will handle server response........

									myObject.open('GET','Update_Item.php?Status_From='+Status_From+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Item_ID='+Item_ID,true);
									myObject.send();
									return true;
								}else{
									location.href="#";
									return false;
								}
							}
						</script>
						<input type="button" name="done" value="DONE" class="art-button-green" onclick="Update_Data('<?php echo $Status_From?>','<?php echo $Patient_Payment_Item_List_ID?>','<?php echo $Item_ID?>')"/>
					</td>
					<!--<td>-->
					<!--    <input type='text' id='done'/>-->
					<!--</td>-->
				</tr>
            </table>
        </center>

</fieldset>
</form>
<script>
function validateForm(theForm) {
    var problem_desc = document.getElementById("write_comment");
    var Status_From="<?php echo $Status_From;?>";
    var Item_ID="<?php echo $Item_ID;?>";
    var Patient_Payment_ID="<?php echo $Patient_Payment_ID;?>";
    var Patient_Payment_Item_List_ID="<?php echo $Patient_Payment_Item_List_ID;?>";
    
    if ($.trim(problem_desc.value) == '') {
        alert("Please Write the Results/Comments");
		document.getElementById('write_comment').style.borderColor='red'
        return false;
    } else {
        return true;
    }
}

</script>
<br>

</center>

<?php
    include("./includes/footer.php");
?>