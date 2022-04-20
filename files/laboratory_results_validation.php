<?php
include("./includes/header.php");
include("./includes/connection.php");
$Employee_ID=$_SESSION['userinfo']['Employee_ID'];

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

//if(isset($_SESSION['userinfo'])){
//if($_SESSION['userinfo']['Laboratory_Works'] == 'yes')
//{
//echo "<a onclick='goBack()' class='art-button-green'>BACK</a>";
//}
//}



if(isset($_GET['patient_id'])){
	$Registration_ID = $_GET['patient_id'];
	//get phone number
	$select_Phone_Number = mysqli_query($conn,"select Phone_Number from tbl_patient_registration where Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
	$num2 = mysqli_num_rows($select_Phone_Number);
	if($num2 > 0){
		while($data = mysqli_fetch_array($select_Phone_Number)){
			$Phone_Number = $data['Phone_Number'];
		}
	}
	
}

$sql1 =mysqli_query($conn,"SELECT * FROM tbl_patient_registration where Registration_ID='".filter_input(INPUT_GET, 'patient_id')."'");
$disp =mysqli_fetch_array($sql1);


?>
<?php
if($_GET['Item_ID']!=''){
    $Item_ID=$_GET['Item_ID'];
}else{
    $Item_ID='';
}
if($_GET['Patient_Payment_Item_List_ID']!=''){
    $Patient_Payment_Item_List_ID=$_GET['Patient_Payment_Item_List_ID'];
}else{
    $Patient_Payment_Item_List_ID='';
}

if($_GET['Status_From']!=''){
    $Status_From=$_GET['Status_From'];
}else{
    $Status_From='';
}

if($_GET['payment_id']!=''){
    $payment_id=$_GET['payment_id'];
}else{
    $payment_id='';
}

if($_GET['patient_id']!=''){
    $patient_id=$_GET['patient_id'];
}else{
    $patient_id='';
}
?>
<a href="laboratory_general_template.php?Item_ID=<?php echo $Item_ID?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID?>&Status_From=<?php echo $Status_From?>&payment_id=<?php echo $payment_id?>&patient_id=<?php echo $patient_id?>" class="art-button-green">BACK</a>

<fieldset  style="margin-top:5px;min-height:500px;">
<center>
<table    style="width:90%" class="hiv_table">
<tr>
<td colspan="4" width="100%">
<center>
<b>LAB RESULT VALIDATION</b>
</center>
</td>
</tr>
<tr>
<td colspan="4" width="100%">
<hr>
</td>
</tr>
<tr>
<td colspan="4" width="100%">
<center>
    <?php
    //date manipulation to get the patient age
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        $age ='';
    }

    $Date_Of_Birth = $disp['Date_Of_Birth'];
    $age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
    // if($age == 0){

    $date1 = new DateTime($Today);
    $date2 = new DateTime($Date_Of_Birth);
    $diff = $date1 -> diff($date2);
    $age = $diff->y." Years, ";
    $age .= $diff->m." Months, ";
    $age .= $diff->d." Days";
    ?>
        <b><?php $Phone_Number = $disp['Phone_Number']; echo $disp['Patient_Name'].",".$disp['Gender'].",".$age." "; ?></b>
</center>
</td>
</tr>
<tr>
<td colspan="4" width="100%">
<hr>
</td>
</tr>
<?php
if($_GET['Item_ID']!=''){
  $Item_ID=$_GET['Item_ID'];
}else{
  $Item_ID='';
}
$select_param_result_type=mysqli_query($conn,"SELECT * FROM tbl_laboratory_test_parameters ltp,tbl_items it,tbl_laboratory_parameters lp
                                      WHERE ltp.Laboratory_Parameter_ID=lp.Laboratory_Parameter_ID
                                      AND ltp.Item_ID=it.Item_ID
                                      AND ltp.Item_ID='$Item_ID' ");
while($select_param_result_type_row=mysqli_fetch_array($select_param_result_type)){
  $Result_Type=$select_param_result_type_row['Result_Type'];
}

?>
<tr>

<?php
if($_GET['Item_ID']!=''){
    $Item_ID=$_GET['Item_ID'];
}else{
    $Item_ID='';
}
if($_GET['Patient_Payment_Item_List_ID']!=''){
    $Patient_Payment_Item_List_ID=$_GET['Patient_Payment_Item_List_ID'];
}else{
    $Patient_Payment_Item_List_ID='';
}
if(filter_input(INPUT_GET, 'Status_From') == 'payment'){
$sql = mysqli_query($conn,"SELECT * FROM tbl_patient_payment_item_list as il
        join tbl_items as i on il.Item_ID = i.Item_ID
        where  Patient_Payment_ID ='".filter_input(INPUT_GET, 'payment_id')."' AND il.Item_ID='$Item_ID' and Check_IN_Type='Laboratory'");

}else if(filter_input(INPUT_GET, 'Status_From') == 'cache'){
$sql = mysqli_query($conn,"SELECT * FROM tbl_item_list_cache as il
        join tbl_items as i on il.Item_ID = i.Item_ID
        where   Payment_Cache_ID ='".filter_input(INPUT_GET, 'payment_id')."' AND il.Item_ID='$Item_ID' and Check_IN_Type='Laboratory'");
                                        }

             ?>
<td colspan="4" width="100%">
<div   style="overflow-y:scroll;overflow-x:hidden;width:100%;height:450px;background-color:white;border:1px solid black;">
<table style="width:100%;border-collapse: collapse;border-spacing:0px;">
<?php
while($disp =mysqli_fetch_array($sql)){
echo "<tr bgcolor='#037cb0'>
<td colspan='4' style='font-size:14px;color:white;'>Test Name: ".$disp['Product_Name']."</td>
</tr><tr><td>";

           ?>
<table  border="0"  style="width:95%;margin-left:5%;margin-top:0px;margin-bottom:0px;margin-right:0px;">
<tr  bgcolor="#eee">
<th style="font-size:13px;width:20px">SN</th>
<th style="font-size:13px;">Parameter Name</th>
<th style="font-size:13px;">Results</th>
<th style="font-size:13px;">UoM</th>
<th style="font-size:13px;">V</th>
<th style="font-size:13px;">M</th>
<th style="font-size:13px;">S</th>
<?php
if(strtolower($Result_Type)=='qualitative'){
    
}else{ ?>
    <th style="font-size:13px;">Normal Value</th>
<th style="font-size:13px;">Status</th>
<?php }
?>
<th style="font-size:13px;">Previous Result</th>
<th style="font-size:13px;width:50px;">Action</th>
</tr>

<script>
//function to check Laboratory_Type Data
function check_result_type() {
  var Laboratory_Result=document.getElementById("Laboratory_Result").value;
  var Result_Type="<?php echo $Result_Type?>";
  if (Result_Type.toLowerCase()=="quantitative") {
    if (isNaN(Laboratory_Result)) {
      alert("This Is A Quantitative Result Type\nPlease,Put Only Numbers.")
      location.reload();
      return false;
    }else{
      return true;
    }
  }
  
  //if (Result_Type.toLowerCase()=="qualitative") {
  //  if (!isNaN(Laboratory_Result)) {
  //    alert("This Is A Qualitative Result Type\nPlease,No Numbers Allowed.")
  //    location.reload();
  //    return false;
  //  }else{
  //    return true;
  //  }
  //}
  
  
}
</script>




<?php
if(filter_input(INPUT_GET, 'Status_From') == 'payment'){

$select_results = mysqli_query($conn,"SELECT ppr.Patient_Payment_Result_ID as Patient_Payment_Result_ID,ppr.Item_ID,ppr.Laboratory_Parameter_ID as Laboratory_Parameter_ID,ppr.Laboratory_Result as Laboratory_Result,ppr.Result_Datetime,\n"
                                . "ppr.Patient_Payment_ID,p.Laboratory_Parameter_Name,tp.Unit_Of_Measure,tp.Lower_Value,tp.Operator,tp.Higher_Value\n"
                                . "FROM tbl_patient_payment_results as ppr\n"
                                  ."join tbl_laboratory_parameters as p ON p.Laboratory_Parameter_ID = ppr.Laboratory_Parameter_ID "
                                  ."join tbl_laboratory_test_parameters as tp ON tp.Laboratory_Parameter_ID = p.Laboratory_Parameter_ID"
                                ." where ppr.Item_ID = '".$disp['Item_ID']."' and ppr.Patient_Payment_ID='".filter_input(INPUT_GET, 'payment_id')."' group by Patient_Payment_Result_ID");

}else if(filter_input(INPUT_GET, 'Status_From') == 'cache'){

$select_results = mysqli_query($conn,"SELECT cr.Patient_Cache_Results_ID as Patient_Payment_Result_ID,cr.Item_ID,cr.Laboratory_Parameter_ID as Laboratory_Parameter_ID,cr.Laboratory_Result as Laboratory_Result,cr.Result_Datetime,\n"
                                 . "cr.Payment_Cache_ID,p.Laboratory_Parameter_Name,tp.Unit_Of_Measure,tp.Lower_Value,tp.Operator,tp.Higher_Value\n"
                                . "FROM tbl_patient_cache_results as cr\n"
                                  ."join tbl_laboratory_parameters as p ON p.Laboratory_Parameter_ID = cr.Laboratory_Parameter_ID "
                                  ."join tbl_laboratory_test_parameters as tp ON tp.Laboratory_Parameter_ID = p.Laboratory_Parameter_ID"
                                ." where cr.Item_ID = '".$disp['Item_ID']."' and cr.Payment_Cache_ID='".filter_input(INPUT_GET, 'payment_id')."' group by Patient_Payment_Result_ID");
}

$i =1;
while ( $row = mysqli_fetch_array($select_results)) {
extract($row);
                  ?>



                <form name="" action="laboratory_result_process.php?pagename=validation&Status_From=<?php echo filter_input(INPUT_GET, 'Status_From'); ?>&submited=submited&paymet_id=<?php echo filter_input(INPUT_GET, 'payment_id');
                          ?>&patient_id=<?php echo filter_input(INPUT_GET, 'patient_id');
                                    ?>&Item_ID=<?php echo $disp['Item_ID']; ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID?>" method="POST">
            <tr bgcolor="#eee">
            <td><?php echo $i; ?></td>
            <td style="color:blue;border:1px solid #ccc;"><?php echo $Laboratory_Parameter_Name; ?>
            <input name="Laboratory_Parameter_ID[]" value="<?php echo $Laboratory_Parameter_ID; ?>" type="hidden">
            <input type="hidden" name="Item_ID" value="<?php echo $Item_ID; ?>" type="hidden">
            <input type="hidden" name="Patient_Payment_Item_List_ID" value="<?php echo $Patient_Payment_Item_List_ID; ?>" type="hidden">
            </td>
            
            <td style="color:blue;border:1px solid #ccc;"><input id="Laboratory_Result" onkeyup="check_result_type(this.value)" name="Laboratory_Result[]" type="text" value="<?php echo $Laboratory_Result; ?>"
                <?php if(isset($_SESSION['userinfo']['Laboratory_Works'])){ ?>
                    <?php if($_SESSION['userinfo']['Laboratory_Result_Modification'] == 'yes'){ ?>
                    <?php }else{ ?>
                             disabled="disabled"
                    <?php }}else{ ?>
                    disabled="disabled"
                <?php } ?>
                 ></td>
            <td style="color:blue;border:1px solid #ccc;"><?php echo $Unit_Of_Measure; ?></td>
            <input name="Patient_Payment_Result_ID[]" value="<?php echo $Patient_Payment_Result_ID; ?>" id="Patient_Payment_Result_ID[]" type="hidden">
            <td style="color:blue;border:1px solid #ccc;"><center><?php if(isset($row)){
                                                        if(filter_input(INPUT_GET, 'Status_From') == 'payment'){
                                                        $select_validation_status =mysqli_query($conn,"SELECT *
                                                                                                  FROM tbl_laboratory_results_validation
                                                                                                  WHERE Patient_Payment_Result_ID = '".$Patient_Payment_Result_ID."' and Laboratory_Parameter_ID ='".$Laboratory_Parameter_ID."'");
                                                          }else{
                                                             $select_validation_status =mysqli_query($conn,"SELECT *
                                                                                                  FROM tbl_laboratory_results_validation_cache
                                                                                                  WHERE Patient_Cache_Results_ID = '".$Patient_Payment_Result_ID."' and Laboratory_Parameter_ID ='".$Laboratory_Parameter_ID."'");
                                                          }
                                                        if(mysqli_num_rows($select_validation_status) > 0){
                                                          echo '<span style="color:red;">V</span>';
                                                        }
                                                      } ?></center>
                                                      </td>
            <td style="color:blue;border:1px solid #ccc;"><center><?php if(isset($row)){

                                        $select_madification_status =mysqli_query($conn,"SELECT *
                                                                                  FROM tbl_laboratory_results_modification
                                                                                  WHERE Patient_Payment_Result_ID = '".$Patient_Payment_Result_ID."' and Laboratory_Parameter_ID ='".$Laboratory_Parameter_ID."'");
                                        $num = mysqli_num_rows($select_madification_status);
                                        if($num > 0){
                                          echo "<span style='color:red;''>
                                          <a href='laboratory_result_indetails.php?details_for=modification&Laboratory_Parameter_ID=".$Laboratory_Parameter_ID."&Item_ID=".$disp['Item_ID']."&patient_id=".filter_input(INPUT_GET, 'patient_id')."'  style='color:red;'>M</a></span>";
                                        }

                                      } ?>
                                      </center></td>
            <td style="color:blue;border:1px solid #ccc;"><center><?php if(isset($row)){

                           if(filter_input(INPUT_GET, 'Status_From') == 'payment'){
                                      $sql9 =mysqli_query($conn,"SELECT * FROM tbl_laboratory_results_submition as s
                                                            JOIN tbl_employee as e ON e.Employee_ID =s.Employee_ID
                                                             where Patient_Payment_Result_ID='".$Patient_Payment_Result_ID."'");
                                                }else{
                                      $sql9 =mysqli_query($conn,"SELECT * FROM tbl_laboratory_results_submition_cache as s
                                                            JOIN tbl_employee as e ON e.Employee_ID =s.Employee_ID
                                                             where Patient_Cache_Results_ID='".$Patient_Payment_Result_ID."'");
                                                }
                                                if(mysqli_num_rows($sql9) > 0){
                                                                  echo '<span style="color:red;">S</span>';
                                                                }
                                                        } ?></center>
                                                        </td>
            
            <?php
                //changed here
                if(strtolower($Result_Type)=='qualitative'){
                    
                }else{ ?>
                    <td style="color:blue;border:1px solid #ccc;"><?php echo $Lower_Value." ".$Operator." ".$Higher_Value; ?></td>
                    <td style="color:blue;border:1px solid #ccc;">  <?php
                      if( ($Lower_Value < $Laboratory_Result) & ($Laboratory_Result< $Higher_Value)){
                              echo "N";
                      }else if($Laboratory_Result > $Higher_Value){
                              echo "H";
                      }else if($Laboratory_Result < $Lower_Value){
                              echo "L";
                      }
                      ?></td>
                <?php }
            ?>
           <td style="color:blue;border:1px solid #ccc;"><?php
                                              $sql5 =mysqli_query($conn,"SELECT * FROM tbl_patient_payment_results
                                                                      WHERE Patient_ID ='".filter_input(INPUT_GET, 'patient_id')."'
                                                                             and Laboratory_Parameter_ID ='".$Laboratory_Parameter_ID."'
                                                                                      and Item_ID ='".filter_input(INPUT_GET, 'Item_ID')."'
                                                                                     and Result_Datetime !='$Result_Datetime'
                                                                                              ORDER BY Patient_Payment_Result_ID DESC LIMIT 1");
                                                                         if(mysqli_num_rows($sql5) > 0){
                                                                                $row5 =mysqli_fetch_array($sql5);
                          echo"<a href='laboratory_result_indetails.php?Laboratory_Parameter_ID=".$Laboratory_Parameter_ID."&Item_ID=".filter_input(INPUT_GET, 'Item_ID')."&patient_id=".filter_input(INPUT_GET, 'patient_id')."'>".$row5['Result_Datetime']."(".$row5['Laboratory_Result'].")</a>";
                                                                            }else{
                                                                                   echo "No Previous Results";
                                                                            }

                                          ?>
                                          </td>
            <td style="width:150px;font-size:10px;">

                <?php
                if(filter_input(INPUT_GET, 'Status_From') == 'payment'){
                $sql8 =mysqli_query($conn,"SELECT * FROM tbl_laboratory_results_validation as v
                                      JOIN tbl_employee as e ON e.Employee_ID =v.Employee_ID
                                       where Patient_Payment_Result_ID='".$Patient_Payment_Result_ID."'");
                      }else {
                $sql8 =mysqli_query($conn,"SELECT * FROM tbl_laboratory_results_validation_cache as v
                                      JOIN tbl_employee as e ON e.Employee_ID =v.Employee_ID
                                       where Patient_Cache_Results_ID='".$Patient_Payment_Result_ID."'");
                      }
                      if(mysqli_num_rows($sql8) > 0){

                        $row =mysqli_fetch_array($sql8);
                        if(mysqli_num_rows($sql9) > 0){
                                $row =mysqli_fetch_array($sql8);
                                 echo "<span style='color:#037cb0;'>Validated & Submitted</span>";
                              }else{
                                ?>
                            <input name="" type="checkbox" onchange="submitResults('<?php echo $Patient_Payment_Result_ID; ?>','<?php echo $Employee_ID; ?>','<?php echo $Laboratory_Parameter_ID; ?>','<?php echo $disp['Item_ID']; ?>')">Submit
                            <?php
                              }
                      }else{
                        ?>
                    <input name="" type="checkbox" onchange="validateResult('<?php echo $Patient_Payment_Result_ID; ?>','<?php echo $Employee_ID; ?>','<?php echo $Laboratory_Parameter_ID; ?>')">Validate
                    <?php
                      }
                      ?>
                      </td>
                      </tr>

                      <?php
                      $i++;
}
echo                                 "</table><br>";
}


?>
</table>
</div>
</tr>
<tr>
    <td>
        <?php
$select_madification_status =mysqli_query($conn,"SELECT *
                                    FROM tbl_laboratory_results_modification
                                    WHERE Patient_Payment_Result_ID = '".$Patient_Payment_Result_ID."' and Laboratory_Parameter_ID ='".$Laboratory_Parameter_ID."'");
$num = mysqli_num_rows($select_madification_status);
if($num > 0){?>
<b style="color: red">Note:Click Later M Above To View Modification History</b>
<?php }
        ?>
        
    </td>
<td colspan="4" width="100%" style="text-align:right;border-bottom:1px solid black;padding-bottom:4px;font-size:14px;margin-top:2px;">
    <script type='text/javascript'>
        function access_Denied(){
            alert("Access Denied");
            document.location = "#";
        }
    </script>
    <?php if(isset($_SESSION['userinfo']['Laboratory_Works'])){ ?>
        <?php if($_SESSION['userinfo']['Laboratory_Result_Modification'] == 'yes'){ ?>
            <input name="submit" class="art-button-green" value='Update Results' type="submit">
        <?php }else{ ?>
            <input name="submit" class="art-button-green" value='No Permission To Modify Result' type="submit" onclick="access_Denied()" disabled="disabled">
        <?php }}else{ ?>
        <input name="submit" class="art-button-green" value='No Permission To Modify Result' type="submit" onclick="access_Denied()" disabled="disabled">
    <?php } ?>

</form>
<br/><br/>

			<form method="POST" >
				<?php 
					$thePhone1 = $Phone_Number;
					//$thePhone1 = '255754894777';
					$Ourmsg = "Majibu yako ya Maabara yapo tayari. Asante!";
					
				?>
				<input type="hidden" name="Ourmessage" value="<?php echo $Ourmsg; ?>" />
				<input type="hidden" name="thePhone" value="<?php echo $thePhone1; ?>" />
				<input type='submit' name="sendMsg" value='SEND SMS ALERT' class='art-button-green' />
			</form>
			<?php 
				if(isset($_POST['sendMsg'])){
					require_once('sms/sms.php');
					$theMsg = $_POST['Ourmessage'];
					$receiver = $_POST['thePhone'];
					//$receiver = '255753119526';
					
					$send = SendSMS($receiver, $theMsg);
					
					//$respond = substr($response, 0, 6);
					
					if($send){
						echo "<center> SMS not sent, try again later. </center>";
					} else {
						echo "<center>The SMS was Successfully sent.</center>";
					}
				}
				//echo $thePhone1;
			?>			
</td>


</tr>
</table>
</center>
<br>
</fieldset>

<?php
include("./includes/footer.php");
?>
<script>
    function validateResult(Patient_Payment_Result_ID , Employee_ID,Laboratory_Parameter_ID){

        //function to confirm result validation
        var validate=confirm("Your about to validate this results.\nOnce validated,modification will require special permission.\n" +
                                "Are you  sure you want to validate this result?");
        if (validate) {
            var Status_From = '<?php echo filter_input(INPUT_GET, 'Status_From'); ?>';

            if(window.XMLHttpRequest) {
                mm = new XMLHttpRequest();
            }
            else if(window.ActiveXObject){
                mm = new ActiveXObject('Micrsoft.XMLHTTP');
                mm.overrideMimeType('text/xml');
            }

            // document.location ='laboratory_result_validated.php?Status_From='+Status_From+'&Patient_Payment_Result_ID='+Patient_Payment_Result_ID+'&Employee_ID='+Employee_ID+'&Laboratory_Parameter_ID='+Laboratory_Parameter_ID;
            mm.onreadystatechange= function(){
                var data = mm.responseText;
                if (mm.readyState==4 && mm.status==200)
                {
                    location.reload(true);
                }
            };

            mm.open('GET','laboratory_result_validated.php?Status_From='+Status_From+'&Patient_Payment_Result_ID='+Patient_Payment_Result_ID+'&Employee_ID='+Employee_ID+'&Laboratory_Parameter_ID='+Laboratory_Parameter_ID,true);
            mm.send();
            return true;
        }else{
            return false;
        }

    }

</script>


<script>
function submitResults(Patient_Payment_Result_ID , Employee_ID,Laboratory_Parameter_ID,Item_ID){

    //function to confirm result validation
    var submit=confirm("Your about to submit this results.\n Are you  sure you want to submit this result?");
    var Patient_Payment_ID="<?php echo $_GET['payment_id'];?>";
    var Registration_ID="<?php echo $_GET['patient_id'];?>";
    var Patient_Payment_Item_List_ID="<?php echo $_GET['Patient_Payment_Item_List_ID']?>";
    if(submit){
        var Status_From = '<?php echo filter_input(INPUT_GET, 'Status_From'); ?>';
        var payment_id = '<?php echo filter_input(INPUT_GET, 'payment_id'); ?>';
        if(window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        }
        else if(window.ActiveXObject){
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }

//document.location ='laboratory_result_submited.php?Status_From='+Status_From+'&Patient_Payment_Result_ID='+Patient_Payment_Result_ID+'&Employee_ID='+Employee_ID+'&Laboratory_Parameter_ID='+Laboratory_Parameter_ID+'&payment_id='+payment_id+'&Item_ID='+Item_ID;
        mm.onreadystatechange= function(){
            var data = mm.responseText;
            if (mm.readyState==4 && mm.status==200)
            {
                location.reload(true);
            }
        };

        mm.open('GET','laboratory_result_submited.php?Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Patient_Payment_ID='+Patient_Payment_ID+'&Registration_ID='+Registration_ID+'&Status_From='+Status_From+'&Patient_Payment_Result_ID='+Patient_Payment_Result_ID+'&Employee_ID='+Employee_ID+'&Laboratory_Parameter_ID='+Laboratory_Parameter_ID+'&payment_id='+payment_id+'&Item_ID='+Item_ID,true);
        mm.send();
        return true;
    }else{
        return false;
    }

}

</script>