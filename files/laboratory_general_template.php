<?php
include("./includes/header.php");
include("./includes/connection.php");
$Employee_ID=$_SESSION['userinfo']['Employee_ID'];
if($_GET['Patient_Payment_Item_List_ID'] !=''){
    $Patient_Payment_Item_List_ID=$_GET['Patient_Payment_Item_List_ID'];
}else{
    $Patient_Payment_Item_List_ID='';
}

if(!isset($_SESSION['userinfo'])){ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");}
    if(isset($_SESSION['userinfo'])) {
        if(isset($_SESSION['userinfo']['Laboratory_Works'])){
            if($_SESSION['userinfo']['Laboratory_Works'] != 'yes'){ header("Location: ./index.php?InvalidPrivilege=yes");}
            }else {
                header("Location: ./index.php?InvalidPrivilege=yes");
        }
        }else{ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes"); }
if(isset($_SESSION['userinfo'])){
   if($_SESSION['userinfo']['Laboratory_Works'] == 'yes')
            {
                 echo "<a href='laboratory_results_templates.php?Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&Status_From=".filter_input(INPUT_GET, 'Status_From')."&Item_ID=".filter_input(INPUT_GET,'Item_ID')."&patient_id=".filter_input(INPUT_GET,'patient_id')."&payment_id=".filter_input(INPUT_GET,'payment_id')."' class='art-button-green'>BACK</a>";
                         }
  $already = false;                                                                                     }

$Today_Date = mysqli_query($conn,"select now() as today");
while($row = mysqli_fetch_array($Today_Date)){
  $original_Date = $row['today'];
  $new_Date = date("Y-m-d", strtotime($original_Date));
  $Today = $new_Date;
$age ='';
              }

              //select to view the details of the patient
                $sql7 =mysqli_query($conn,"SELECT * FROM tbl_patient_registration where Registration_ID='".filter_input(INPUT_GET, 'patient_id')."'");
                $disp =mysqli_fetch_array($sql7);

                //select to get the name of the test provided for patient
                $sql2 =mysqli_query($conn,"SELECT * FROM tbl_items where Item_ID='".filter_input(INPUT_GET, 'Item_ID')."'");
                $disp2 =mysqli_fetch_array($sql2);

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


<fieldset  style="margin-top:5px;min-height:500px;">
<!--  table to show the header of the table-->
<center>
<table style="width:90%;" class="hiv_table" border="0">
<tr>
<td colspan="4" style="width:100%;padding-bottom:10px;padding-top:5px;font-weight:bold;font-size:16px;color:blue;">
<center><?php echo $disp2['Product_Name']; ?></center>
</td>
</tr>
<tr>
<td colspan="4"><hr></td>
</tr>
<tr>
<td colspan="4" style="width:100%;padding-bottom:10px;padding-top:5px;font-weight:bold;font-size:14px;">
<center>
<?php echo $disp['Patient_Name'].",  ".$disp['Gender'].",  ".$age ; ?>
</center>
</td>
</tr>
<tr>
<td colspan="4"><hr hegth='4px'></td>
</tr>
</table>
</center><!--  end of the header of the table -->
<center>
<table    style="width:90%" class="hiv_table" border="0">
<tr>
<td style="color:black;font-weight:40;border:1px solid #ccc;text-align:right;">Specimen Name</td>
<td style="color:blue;border:1px solid #ccc;"><input name="Speciemen_Name" readonly="readonly"
value=" <?php
//select to find the  names for a given item
$sql3 =mysqli_query($conn,"SELECT s.Specimen_ID as Specimen_ID,s.Specimen_Name FROM tbl_laboratory_test_specimen as ts\n"
    . "JOIN tbl_items as i ON i.Item_ID = ts.Item_ID \n"
    . "JOIN tbl_laboratory_specimen as s ON s.Specimen_ID = ts.Specimen_ID\n"
    . "WHERE ts.Item_ID ='".filter_input(INPUT_GET, 'Item_ID')."'");

while($row =mysqli_fetch_array($sql3)){
echo $row['Specimen_Name'].",";
}
mysql_free_result($sql3);
?>
" type="text"></td>
<td style="color:black;font-weight:40;border:1px solid #ccc;text-align:right;">Specimen Number</td>
<td style="color:blue;border:1px solid #ccc;"><input name="Specimen_ID" readonly="readonly" type="text"
value=" <?php

//select to find the specimen id and name for a given item
$sql6 =mysqli_query($conn,"SELECT s.Specimen_ID as Specimen_ID,s.Specimen_Name FROM tbl_laboratory_test_specimen as ts\n"
    . "JOIN tbl_items as i ON i.Item_ID = ts.Item_ID \n"
    . "JOIN tbl_laboratory_specimen as s ON s.Specimen_ID = ts.Specimen_ID\n"
    . "WHERE ts.Item_ID ='".filter_input(INPUT_GET, 'Item_ID')."'");

while($row =mysqli_fetch_array($sql6)){
echo $row['Specimen_ID'].",";
}
?>
"></td>
</tr>
<tr>
<td style="color:black;font-weight:40;border:1px solid #ccc;text-align:right;" readonly="readonly">Department</td>
<td style="color:blue;border:1px solid #ccc;"><input name="" type="text" readonly="readonly" value="<?php echo $_SESSION['Laboratory'];?>"></td>
<td style="color:black;font-weight:40;border:1px solid #ccc;text-align:right;" readonly="readonly">Validated</td>
<td style="color:blue;border:1px solid #ccc;" readonly="readonly"><input name="" type="text"></td>
</tr>

<tr>
<td colspan="4"><hr></td>
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
<td colspan="4" width="100%">


<div   style="overflow-y:scroll;overflow-x:hidden;width:100%;height:350px;background-color:white;border:1px solid black;">
<table  border="0"  style="width:100%;margin-left:0px;margin-top:0px;margin-bottom:0px;border:1px solid #ccc;" class="hiv_table">
<tr  bgcolor="#eee">
<th style="font-size:13px;">SN</th>
<th style="font-size:13px;">Parameter Name</th>
<th style="font-size:13px;">Results</th>
<th style="font-size:13px;">UoM</th>
<th style="font-size:13px;">M</th>
<th style="font-size:13px;">V</th>
<th style="font-size:13px;">T</th>
<th style="font-size:13px;">S</th>
<?php
if(strtolower($Result_Type)=='qualitative'){
  //echo $Result_Type;
}else{ ?>
  <th style="font-size:13px;">Normal Value</th>
<th style="font-size:13px;">Status</th>
<?php }
?>
<th style="font-size:13px;">Previous Result</th>
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
  //    alert("This Is A Qualitative Result Type\nPlease,No Numbers Alloweddddd.")
  //    //location.reload();
  //    return false;
  //  }else{
  //    return true;
  //  }
  //}
  
  
}
</script>
<?php


$sql =mysqli_query($conn,"SELECT p.Laboratory_Parameter_ID as Laboratory_Parameter_ID,p.Laboratory_Parameter_Name,tp.Unit_Of_Measure as Unit_Of_Measure,
tp.Lower_Value,tp.Operator,tp.Higher_Value "
. "FROM tbl_laboratory_test_parameters as tp "
. "join tbl_laboratory_parameters as p ON p.Laboratory_Parameter_ID = tp.Laboratory_Parameter_ID "
. "JOIN tbl_items as i ON i.Item_ID = tp.Item_ID "
. "where tp.Item_ID ='".filter_input(INPUT_GET, 'Item_ID')."'");
$i=1;
while($disp =mysqli_fetch_array($sql)){

//check if the parameter is  already in database
//if its in database show results provided b4
if(filter_input(INPUT_GET, 'Status_From') =='payment'){
$select_results= mysqli_query($conn,"SELECT * FROM tbl_patient_payment_results
                 where Laboratory_Parameter_ID='".$disp['Laboratory_Parameter_ID']."' and Patient_Payment_ID='".filter_input(INPUT_GET, 'payment_id')."' and Item_ID='".filter_input(INPUT_GET, 'Item_ID')."'");

if(mysqli_num_rows($select_results) > 0){
            $sql4 =mysqli_query($conn,"SELECT ppr.Patient_Payment_Result_ID,ppr.Item_ID,ppr.Laboratory_Parameter_ID,ppr.Laboratory_Result as Laboratory_Result,ppr.Result_Datetime,\n"
                                        . "ppr.Patient_Payment_ID,p.Laboratory_Parameter_Name,tp.Unit_Of_Measure,tp.Lower_Value,tp.Operator,tp.Higher_Value\n"
                                        . "FROM tbl_patient_payment_results as ppr\n"
                                        . "join tbl_laboratory_parameters as p on p.Laboratory_Parameter_ID = ppr.Laboratory_Parameter_ID\n"
                                        . "JOIn tbl_Laboratory_test_parameters as tp on tp.Laboratory_Parameter_ID =p.Laboratory_Parameter_ID\n"
                                          . "WHERE ppr.Item_ID ='".filter_input(INPUT_GET, 'Item_ID')."' and ppr.Patient_Payment_ID ='".filter_input(INPUT_GET,'payment_id')."'
                                                and tp.Laboratory_Parameter_ID='".$disp['Laboratory_Parameter_ID']."' group by Laboratory_Parameter_ID");

                                                       $disp1=mysqli_fetch_array($sql4);
                                                                      extract($disp1);
                                                   }else{

                                                            extract($disp);


                                                   }
}else if(filter_input(INPUT_GET, 'Status_From') =='cache'){
$select_results= mysqli_query($conn,"SELECT * FROM tbl_patient_cache_results
                 where Laboratory_Parameter_ID='".$disp['Laboratory_Parameter_ID']."' and Payment_Cache_ID='".filter_input(INPUT_GET, 'payment_id')."' and Item_ID='".filter_input(INPUT_GET, 'Item_ID')."'");

if(mysqli_num_rows($select_results) > 0){
                  $sql4 =mysqli_query($conn,"SELECT cr.Patient_Cache_Results_ID as Patient_Payment_Result_ID,cr.Item_ID,cr.Laboratory_Parameter_ID,cr.Laboratory_Result as Laboratory_Result,cr.Result_Datetime,\n"
                                        . "cr.Payment_Cache_ID,p.Laboratory_Parameter_Name,tp.Unit_Of_Measure,tp.Lower_Value,tp.Operator,tp.Higher_Value\n"
                                        . "FROM tbl_patient_cache_results as cr\n"
                                        . "join tbl_laboratory_parameters as p on p.Laboratory_Parameter_ID = cr.Laboratory_Parameter_ID\n"
                                        . "JOIn tbl_Laboratory_test_parameters as tp on tp.Laboratory_Parameter_ID =p.Laboratory_Parameter_ID\n"
                                          . "WHERE cr.Item_ID ='".filter_input(INPUT_GET, 'Item_ID')."' and cr.Payment_Cache_ID ='".filter_input(INPUT_GET,'payment_id')."'
                                                and tp.Laboratory_Parameter_ID='".$disp['Laboratory_Parameter_ID']."' group by Laboratory_Parameter_ID");

                                                       $disp1=mysqli_fetch_array($sql4);
                                                                      extract($disp1);
                                                   }else{

                                                            extract($disp);


                                                   }
}


?>

<form name="" action="laboratory_result_process.php?pagename=results&Patient_Payment_Item_List_ID=<?php echo $_GET['Patient_Payment_Item_List_ID']?>&Status_From=<?php echo filter_input(INPUT_GET, 'Status_From'); ?>&submited=submited&paymet_id=<?php echo filter_input(INPUT_GET, 'payment_id');
  ?>&patient_id=<?php echo filter_input(INPUT_GET, 'patient_id');
                                ?>&Item_ID=<?php echo filter_input(INPUT_GET, 'Item_ID'); ?>" method="POST">
<tr bgcolor="#eee">
<td style="color:blue;border:1px solid #ccc;width:40px;"><?php echo $i; ?></td>
<td style="color:blue;border:1px solid #ccc;"><?php echo $Laboratory_Parameter_Name; ?>
<input name="Laboratory_Parameter_ID[]" value="<?php echo $Laboratory_Parameter_ID; ?>" type="hidden"></td>
<td style="color:blue;border:1px solid #ccc;text-align:center;"><input name="Laboratory_Result[]" type="text" value="<?php if(isset($disp1)){ echo $Laboratory_Result;} ?>" id="Laboratory_Result" onkeyup="check_result_type(this.value)"></td>
<td style="color:blue;border:1px solid #ccc;"><?php echo $Unit_Of_Measure; ?></td>
<td style="color:blue;border:1px solid #ccc;"><center><?php if(isset($disp1)){

$select_madification_status =mysqli_query($conn,"SELECT *
              FROM tbl_laboratory_results_modification
              WHERE Patient_Payment_Result_ID = '".$Patient_Payment_Result_ID."' and Laboratory_Parameter_ID ='".$Laboratory_Parameter_ID."'");
$num = mysqli_num_rows($select_madification_status);
if($num> 0){
    echo "<span style='color:red;''>
            <a href='laboratory_result_indetails.php?details_for=modification&Laboratory_Parameter_ID=".$Laboratory_Parameter_ID."&Item_ID=".$_GET['Item_ID']."&patient_id=".filter_input(INPUT_GET, 'patient_id')."'  style='color:red;'>M</a></span>";
            //echo '<span style="color:red;">M</span>';
}

} ?></center></td>
<td style="color:blue;border:1px solid #ccc;"><center><?php if(isset($disp1)){

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
} ?></center></td>
<td style="color:blue;border:1px solid #ccc;"><?php if(isset($disp1)){} ?></td>
<td style="color:blue;border:1px solid #ccc;"><center><?php if(isset($disp1)){

if(filter_input(INPUT_GET, 'Status_From') == 'payment'){
$sql8 =mysqli_query($conn,"SELECT * FROM tbl_laboratory_results_submition as s
        JOIN tbl_employee as e ON e.Employee_ID =s.Employee_ID
         where Patient_Payment_Result_ID='".$Patient_Payment_Result_ID."'");
}else{
$sql8 =mysqli_query($conn,"SELECT * FROM tbl_laboratory_results_submition_cache as s
        JOIN tbl_employee as e ON e.Employee_ID =s.Employee_ID
         where Patient_Cache_Results_ID='".$Patient_Payment_Result_ID."'");
}
if(mysqli_num_rows($sql8) > 0){
              echo '<span style="color:red;">S</span>';
            }

} ?></center></td>
<?php
if(strtolower($Result_Type)=='qualitative'){
  
}else{?>
  <td style="color:blue;border:1px solid #ccc;width:100px;"><center><?php echo $Lower_Value." ".$Operator." ".$Higher_Value; ?></center></td>
<td style="color:blue;border:1px solid #ccc;width:60px;"><center><?php if(isset($disp1) & isset($Laboratory_Result)){
if( ((int)$Lower_Value <= (int)$Laboratory_Result) & ((int)$Laboratory_Result <= (int)$Higher_Value)){
echo "N";
}else if((int)$Laboratory_Result > (int)$Higher_Value){
echo "H";
}else if((int)$Laboratory_Result < (int)$Lower_Value){
echo "L";
}
} ?></center></td>
<?php }
?>

<td style="color:blue;border:1px solid #ccc;width:200px;"
><?php
$sql5 =mysqli_query($conn,"SELECT * FROM tbl_patient_payment_results
            WHERE Patient_ID ='".filter_input(INPUT_GET, 'patient_id')."'
                   and Laboratory_Parameter_ID ='".$Laboratory_Parameter_ID."'
                            and Item_ID ='".filter_input(INPUT_GET, 'Item_ID')."'
                                    ORDER BY Patient_Payment_Result_ID DESC LIMIT 1");
               if(mysqli_num_rows($sql5) > 0){
                      $row5 =mysqli_fetch_array($sql5);
echo"<a href='laboratory_result_indetails.php?Laboratory_Parameter_ID=".$Laboratory_Parameter_ID."&Item_ID=".filter_input(INPUT_GET, 'Item_ID')."&patient_id=".filter_input(INPUT_GET, 'patient_id')."'>".$row5['Result_Datetime']."(".$row5['Laboratory_Result'].")</a>";
                  }else{
                         echo "No Previous Results";
                  }

?>
</td>
</tr>
<?php
unset($Laboratory_Result); unset($Lower_Value); unset($Operator); unset($Laboratory_Result); unset($Higher_Value);
$i++;
}

?>
</table>
</div>
<tr>
  <td>
        <?php
$select_madification_status =@mysqli_query($conn,"SELECT *
                                    FROM tbl_laboratory_results_modification
                                    WHERE Patient_Payment_Result_ID = '".$Patient_Payment_Result_ID."' and Laboratory_Parameter_ID ='".$Laboratory_Parameter_ID."'");
$num = mysqli_num_rows($select_madification_status);
if($num > 0){?>
<b style="color: red">Note:Click Later M Above To View Modification History</b>
<?php }
        ?>
        
    </td>
<td colspan="1"></td>
        <td style="text-align: right" colspan="0">
            <table align="right" border="0">
                <tr>
                    <td style="text-align: right">
                        <?php
                        if(filter_input(INPUT_GET, 'Status_From') =='payment'){
                            $select_results =mysqli_query($conn,"SELECT * FROM tbl_patient_payment_results WHERE Patient_Payment_ID ='".filter_input(INPUT_GET, 'payment_id')."'");
                        }else  if(filter_input(INPUT_GET, 'Status_From') =='cache'){
                            $select_results =mysqli_query($conn,"SELECT * FROM tbl_patient_cache_results WHERE Payment_Cache_ID ='".filter_input(INPUT_GET, 'payment_id')."'");

                        }

                        if(mysqli_num_rows($select_results) == 0){
                            ?><a onclick="alert('No Results To Validate!')" class="art-button-green">Validation/Submition</a>
                        <?php
                        }else{
                          if($_GET['Item_ID']){
                            $Item_ID=$_GET['Item_ID'];
                          }else{
                            $Item_ID='';
                          }
                            ?><a href="laboratory_results_validation.php?Item_ID=<?php echo $Item_ID?>&Patient_Payment_Item_List_ID=<?php echo $_GET['Patient_Payment_Item_List_ID']?>&Status_From=<?php
                            echo filter_input(INPUT_GET, 'Status_From'); ?>&payment_id=<?php
                            echo filter_input(INPUT_GET, 'payment_id'); ?>&patient_id=<?php
                            echo filter_input(INPUT_GET, 'patient_id'); ?>" class="art-button-green">Validation / Submition</a>
                        <?php
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        // if(isset($Results_values)){
                        //  echo '<input name="Submit" value="Update Results" type="submit">';
                        // }else{
                        echo '<input  type="submit" name="Submit" value="Save Results" class="art-button-green">';
                        // }
                        ?>
                    </td>
                </tr>
            </table>
        </td>
</tr>
</tr>
</table>
</form>
</center>
<br>
</fieldset>

<?php
include("./includes/footer.php");

if($already){
?>
<script>
alert("Some Results Were Already Inserted");
</script>
<?php
}
?>
