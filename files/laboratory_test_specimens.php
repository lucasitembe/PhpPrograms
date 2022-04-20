
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
                                                     echo "<a href='laboratory_sample_collection_details.php?Status_From=".filter_input(INPUT_GET, 'Status_From')."&patient_id=".filter_input(INPUT_GET, 'patient_id')."&payment_id=".filter_input(INPUT_GET, 'payment_id')."&Required_Date=".filter_input(INPUT_GET, 'Required_Date')."' class='art-button-green'>BACK</a>";
                                                         }
                                                             }


if($_GET['Status_From']!=''){
    $Status_From=$_GET['Status_From'];
}else{
    $Status_From='';
}
if($_GET['patient_id']!=''){
    $Registration_ID=$_GET['patient_id'];
}else{
    $Status_From='';
}
if($_GET['payment_id']!=''){
    $Patient_Payment_ID=$_GET['payment_id'];
}else{
    $Patient_Payment_ID='';
}
if($_GET['Patient_Payment_Item_List_ID']!=''){
    $Patient_Payment_Item_List_ID=$_GET['Patient_Payment_Item_List_ID'];
}else{
    $Patient_Payment_Item_List_ID='';
}

if($_GET['Required_Date']!=''){
    $Required_Date=$_GET['Required_Date'];
}else{
    $Required_Date='';
}
if($_GET['item_id']!=''){
    $item_id=$_GET['item_id'];
}else{
    $item_id='';
}

if(isset($_GET['patient_id'])){
$Employee_ID=$_SESSION['userinfo']['Employee_ID'];
$patient_id=$_GET['patient_id'];
$payment_id=$_GET['payment_id'];
$Patient_Payment_Item_List_ID=$_GET['Patient_Payment_Item_List_ID'];
$item_id=$_GET['item_id'];


if(filter_input(INPUT_GET, 'Status_From') == 'payment'){
//check if the item of this payment id is already in registered for specimen teken
$check_first =mysqli_query($conn,"select * from tbl_patient_payment_test where Patient_Payment_Item_List_ID ='$Patient_Payment_Item_List_ID'");
if(mysqli_num_rows($check_first) > 0 )
{

//chek the number of specimen to be teken for this test
$Specimen_Number_query =mysqli_query($conn,"SELECT count(Laboratory_Test_specimen_ID) as Specimen_Number
                                            FROM tbl_laboratory_test_specimen as l \n"
                                                ." where Item_ID ='".$item_id."'") or die(mysqli_error($conn));
                                                    $Specimen_Number_result =mysqli_fetch_array($Specimen_Number_query);
                                                    extract($Specimen_Number_result);
      //if no specimen defined for this test
          if($Specimen_Number <= 0){
              echo "
              <script>
                    alert('No Specimen Define For This Test');
                    document.location ='laboratory_sample_collection_details.php?Status_From=payment&patient_id=".filter_input(INPUT_GET, 'patient_id')."&payment_id=".filter_input(INPUT_GET, 'payment_id')."&Required_Date=".filter_input(INPUT_GET, 'Required_Date')."';
              </script>
              ";
          }else{
    //register the new item for specimen taken
    $sql =mysqli_query($conn,"INSERT INTO tbl_patient_payment_test
                                        ( Submited_Time,View_Time,Specimen_Taken, Specimen_Number, Patient_Payment_Item_List_ID, Employee_ID)
                                         VALUES ( (SELECT NOW()),(SELECT NOW()), '', '$Specimen_Number', '$Patient_Payment_Item_List_ID', '$Employee_ID');");
                                             $Patient_Payment_Test_ID =mysql_insert_id();
          }
}else
{
//if the test is already registered
$value =mysqli_fetch_assoc($check_first);
$Patient_Payment_Test_ID =$value['Patient_Payment_Test_ID'];
//$sql =mysqli_query($conn,"INSERT INTO tbl_patient_payment_test
//                                        ( Submited_Time,View_Time,Specimen_Taken, Specimen_Number, Patient_Payment_Item_List_ID, Employee_ID)
//                                         VALUES ( (SELECT NOW()),(SELECT NOW()), '', '$Specimen_Number', '$Patient_Payment_Item_List_ID', '$Employee_ID');");
//                                             $Patient_Payment_Test_ID =mysql_insert_id();
}

}elseif (filter_input(INPUT_GET, 'Status_From') == 'cache') {

$check_first =mysqli_query($conn,"select * from tbl_patient_cache_test where Payment_Item_Cache_List_ID ='$Patient_Payment_Item_List_ID'");
if(mysqli_num_rows($check_first) == 0 ){

        //chek the number of specimen to be teken for this test
$Specimen_Number_query =mysqli_query($conn,"SELECT count(Laboratory_Test_specimen_ID) as Specimen_Number
                                            FROM tbl_laboratory_test_specimen as l \n"
                                                ." where Item_ID ='".filter_input(INPUT_GET, 'item_id')."'");
                                                    $Specimen_Number_result =mysqli_fetch_array($Specimen_Number_query);
                                                    extract($Specimen_Number_result);



//if the no specimen defined for this test
if($Specimen_Number <= 0){
    echo "
    <script>
          alert('No Specimen Define For This Test');
          document.location ='laboratory_sample_collection_details.php?Status_From=cache&patient_id=".filter_input(INPUT_GET, 'patient_id')."&payment_id=".filter_input(INPUT_GET, 'payment_id')."&Required_Date=".filter_input(INPUT_GET, 'Required_Date')."';
    </script>
    ";
          }else{
            $sql =mysqli_query($conn,"INSERT INTO tbl_patient_cache_test( Submited_Time, View_Time, Specimen_Taken, Specimen_Number, Payment_Item_Cache_List_ID, Employee_ID)
VALUES ( (SELECT NOW()), (SELECT NOW()), '', '$Specimen_Number', '$Patient_Payment_Item_List_ID', '$Employee_ID')");
$Patient_Payment_Test_ID =mysql_insert_id();
          }

}else{
$value =mysqli_fetch_assoc($check_first);
$Patient_Payment_Test_ID =$value['Patient_Cache_Test_ID'];
//$sql =mysqli_query($conn,"INSERT INTO tbl_patient_cache_test( Submited_Time, View_Time, Specimen_Taken, Specimen_Number, Payment_Item_Cache_List_ID, Employee_ID)
//VALUES ( (SELECT NOW()), (SELECT NOW()), '', '$Specimen_Number', '$Patient_Payment_Item_List_ID', '$Employee_ID')");
//$Patient_Payment_Test_ID =mysql_insert_id();
}

}


}else{
$patient_id=0;
$payment_id=0;
$item_id=0;
}

if(isset($_GET['Status_From']))
    if(filter_input(INPUT_GET, 'Status_From') == 'payment'){

$sql=mysqli_query($conn,"select 'payment' as Status_From,i.Product_Name as item_name,i.Item_ID as item_id,
'Direct From Payment' as Employee,pr.* \n"
. "from tbl_patient_payment_item_list as il \n"
. "join  tbl_patient_payments as pc on pc.Patient_Payment_ID = il.Patient_Payment_ID "
. "join tbl_patient_registration as pr on pr.Registration_ID =pc.Registration_ID \n"
. "join tbl_items as i on i.Item_ID =il.Item_ID \n"
. "where il.Check_In_Type='Laboratory' and il.Patient_Payment_ID ='$payment_id'
	 and i.item_id='$item_id' and pc.Receipt_Date='".filter_input(INPUT_GET, 'Required_Date')."' ");

    }else if(filter_input(INPUT_GET, 'Status_From') == 'cache'){

                        $sql=mysqli_query($conn,"select i.Product_Name as item_name,i.Item_ID as item_id,e.Employee_Name as Employee,pr.* \n"
                                        . "from tbl_payment_cache as pc\n"
                                                . "join tbl_item_list_cache as pl on pc.Payment_Cache_ID =pl.Payment_Cache_ID\n"
                                                    . "Join tbl_employee as e on e.Employee_ID =pc.Employee_ID\n"
                                                        . "join tbl_patient_registration as pr on pr.Registration_ID =pc.Registration_ID\n"
                                                            . "join tbl_items as i on i.Item_ID =pl.Item_ID\n"
                                                                     . "where pl.Check_In_Type='Laboratory' and pr.Registration_ID ='$patient_id' and i.Item_ID ='".$_GET['item_id']."'");

                                                             }

                        //select for display the name of the patient
                            $sql1 =mysqli_query($conn,"SELECT * FROM tbl_patient_registration where Registration_ID='$patient_id'");
                                            $disp =mysqli_fetch_array($sql1);

                                                            ?>

<fieldset style="margin-top:5px;min-height:500px;">
     <center>
            <table border="1"  style="width:90%;" class="hiv_table" bgcolor="white">
                    <tr>
                            <td colspan="4" style="width:100%;padding-bottom:10px;padding-top:5px;">
                                  <center><?php echo $disp['Patient_Name']." ,".$disp['Gender'].",".$disp['Date_Of_Birth']." years of age,"; ?>
                                            </center>
                                                    </td>
                                                        </tr>
                                                             </table>


            <?php

            $i=1;
                while($rows=mysqli_fetch_array($sql)){
                                     ?>
                                         <table border="1"  style="width:90%;" class="hiv_table" bgcolor="white">
                                                  <tr>
                                                         <th colspan="4" style="width:70%;text-align:left;">TEST:<?php echo $rows['item_name']; ?> </th>
                                                            </tr>
                                                                <?php
                                                                $sql1 =mysqli_query($conn,"SELECT * FROM tbl_laboratory_test_specimen as l \n"
                                                                    . "join tbl_items as i on i.Item_ID = l.Item_ID \n"
                                                                    . "JOIN tbl_laboratory_Specimen as ls on ls.Specimen_ID = l.Specimen_ID
                                                                            where i.Item_ID ='".$rows['item_id']."'");
                                                                                                      ?>

                                                    <tr>
                                                        <td>
                                                            <table  border="0"  style="width:90%;margin-left:100px;margin-top:2px;margin-bottom:20px;border:1px solid #ccc;" class="hiv_table">
                                                                <tr bgcolor="#eee">
                                                                        <td style="color:blue;border:1px solid #ccc;">Print Barcode</td>
                                                                                <td style="color:blue;border:1px solid #ccc;">Specimen ID</td>
                                                                                    <td style="color:blue;border:1px solid #ccc;">Specimen Name</td>
                                                                                        <td style="color:blue;border:1px solid #ccc;">Done</td>
                                                                                        <td></td>
                                                                                                 </tr>

                       <?php
                              while($rows1 = mysqli_fetch_array($sql1)){

                                         if(isset($_GET['Status_From']))
                                                 if(filter_input(INPUT_GET, 'Status_From') == 'payment'){
                                                                    $status_query =mysqli_query($conn,"SELECT Specimen_Status,Patient_Payment_Test_Specimen_ID FROM tbl_patient_payment_test_specimen as p
                                                                                                join tbl_laboratory_test_specimen as l on  l.Laboratory_Test_specimen_ID = p.Laboratory_Test_specimen_ID
                                                                                                    where l.Laboratory_Test_specimen_ID ='".$rows1['Laboratory_Test_specimen_ID']."' and p.Payment_ID ='".filter_input(INPUT_GET, 'payment_id')."'");
                                                                                                          $status= mysqli_fetch_array($status_query);

}  elseif (filter_input(INPUT_GET, 'Status_From') == 'cache') {
$status_query =mysqli_query($conn,"SELECT Specimen_Status,Patient_Cache_Test_Specimen_ID FROM tbl_patient_cache_test_specimen as p
join tbl_laboratory_test_specimen as l on  l.Laboratory_Test_specimen_ID = p.Laboratory_Test_specimen_ID
where l.Laboratory_Test_specimen_ID ='".$rows1['Laboratory_Test_specimen_ID']."' and p.Payment_Item_Cache_List_ID ='".filter_input(INPUT_GET, 'Patient_Payment_Item_List_ID')."'");

  $status= mysqli_fetch_array($status_query);
                                                                                                                     }
                                                                                                                            ?>

<tr>
	<?php
		if($_GET['patient_id'] !=''){
			$patient_id=$_GET['patient_id'];
		}else{
			$patient_id='';
		}
	?>
	<td width="90px;"><a href="barcode_specimen/BCGcode39.php?Registration_ID=<?php echo $patient_id; ?>&Patient_Payment_Test_Specimen_ID=<?php if(isset($status['Patient_Payment_Test_Specimen_ID'])){ echo $status['Patient_Payment_Test_Specimen_ID']; }elseif(isset($status['Patient_Cache_Test_Specimen_ID'])){ echo $status['Patient_Cache_Test_Specimen_ID']; }else{ echo $Patient_Payment_Test_Specimen_ID=0; }?>&LaboratoryTestSpecimenBacodedThisPage=ThisPage"  target='_blank' class=""><button>
<!--                                                        <td width="90px;"><a href="barcode_specimen/BCGcode39.php?Registration_ID=--><?php //echo "PDPSN-".$status['Patient_Payment_Test_Specimen_ID']; ?><!--"  target='_blank'><button>-->
BARCODE
</button></a>
</td>
<td style="border:1px solid #ccc;" >
<?php
if(isset($status['Patient_Payment_Test_Specimen_ID'])){
echo "PDPSN-".$status['Patient_Payment_Test_Specimen_ID'];
}else if(isset($status['Patient_Cache_Test_Specimen_ID'])){
echo "PDSN-".$status['Patient_Cache_Test_Specimen_ID'];
		}
                                                                                                                    ?>
                                                                                                                        </td>

                                                                                                                            <td style="border:1px solid #ccc;">
                                                                                                                                    <?php echo $rows1['Specimen_Name']; ?></td>
                                                                                                                                         <?php $Laboratory_Test_specimen_ID=$rows1['Laboratory_Test_specimen_ID']; ?>
                                                                                                                                                <td style="border:1px solid #ccc;">
                                                                                                                                                        <input name="isTekken" id='Sample_Taken<?php echo $i; ?>'
																																						type="checkbox" <?php if(strtolower($_GET['Status'])=='done'){?> checked="checked" <?php }?> onclick="check_payment('<?php echo $patient_id?>','<?php echo $payment_id?>','<?php echo $_GET["Patient_Payment_Item_List_ID"]?>','<?php echo $Patient_Payment_Test_ID?>','<?php echo $Laboratory_Test_specimen_ID?>', '<?php echo $i; ?>')"   <?php if(strtolower($_GET['Status'])=='done'){?> disabled="disabled" <?php }?>   
                                                                                                                                                                <?php

                                                                                                                                                                        if($status['Specimen_Status'] =='Collected'){
                                                                                                                                                                                 echo "checked";
                                                                                                                                                                                     }?>></td>
                                                                                                                                                                                             </tr>
                                        <?php
                                                 $i++;
                                                        }
                                                            ?>
                                                                <tr>
                                                                    <td colspan="3">
                                                                         <center>
                                                                                <span style="color:red;font-size:10px;">
                                                                                    <?php
                                                                                        if(isset($_GET['Status_From']))
                                                                                                     if(filter_input(INPUT_GET, 'Status_From') == 'payment'){
                                                                                                            echo"KEY:PDPSN Stand For Patient from Direct to Payment Specimen Number";
                                                                                                                }  elseif (filter_input(INPUT_GET, 'Status_From') == 'cache') {
                                                                                                                        echo"KEY:PDSN Stand For Patient from Doctor  Specimen Number";
                                                                                                                                 }
                                                                                                                                     ?>

                                                                                                                                        </span>
                                                                                                                                                </center>
                                                                                                                                                         </td>
                                                                                                                                                                 </tr>

                                                                                                                                                                        </table>
                                                                                                                                                                                </td>
                                                                                                                                                                                     </tr>
                                                    <table>
                                                        <tr>
                                                            <?php
                                                                echo "<a href='laboratory_sample_collection_details.php?Status_From=".filter_input(INPUT_GET, 'Status_From')."&patient_id=".filter_input(INPUT_GET, 'patient_id')."&payment_id=".filter_input(INPUT_GET, 'payment_id')."&Required_Date=".filter_input(INPUT_GET, 'Required_Date')."' style='width:20%;' class='art-button-green'>DONE</a>";
                                                            ?>
                                                        </tr>
<!--                                                        <tr>-->
<!--                                                            <td>-->
<!--                                                                <input type="text" value="" id="Transaction_Type"/>-->
<!--                                                            </td>-->
<!--                                                        </tr>-->
                                                    </table>
                                            <?php
                                                $i++;
                                                     echo "</table>";
                                                            }
                                                                  ?>
                                                                        </center>
                                                                                </fieldset>


                                                                                <?php
                                                                                    include("./includes/footer.php");
                                                                                         ?>
            <script>
                    function insertSpecimen(Patient_Payment_Test_ID , Laboratory_Test_specimen_ID, i){
							 var sample = 'Sample_Taken'+i;
                             if(document.getElementById(sample).checked){
                                 var Status_From = '<?php  echo filter_input(INPUT_GET, 'Status_From'); ?>';
                                 var patient_id = '<?php  echo filter_input(INPUT_GET, 'patient_id'); ?>';
                                 var item_id = '<?php  echo filter_input(INPUT_GET, 'item_id'); ?>';
                                 var payment_id = '<?php  echo filter_input(INPUT_GET, 'payment_id'); ?>';
                                 var Patient_Payment_Item_List_ID = '<?php  echo filter_input(INPUT_GET, 'Patient_Payment_Item_List_ID'); ?>';

                                 if(window.XMLHttpRequest) {
                                     mm = new XMLHttpRequest();
                                 }
                                 else if(window.ActiveXObject){
                                     mm = new ActiveXObject('Micrsoft.XMLHTTP');
                                     mm.overrideMimeType('text/xml');
                                 }

                                 // document.location ='insert_value.php?Status_From='+Status_From+'&patient_id='+patient_id+'&item_id='+item_id+'&payment_id='+payment_id+'&Patient_Payment_Test_ID='+Patient_Payment_Test_ID+'&Laboratory_Test_specimen_ID='+Laboratory_Test_specimen_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID;
                                 mm.onreadystatechange= function(){
                                     var data = mm.responseText;
                                     if (mm.readyState==4 && mm.status==200)
                                     {
                                         document.getElementById(sample).setAttribute("disabled","disabled");
                                     }
                                 };

                                 mm.open('GET','insert_value.php?Status_From='+Status_From+'&patient_id='+patient_id+'&item_id='+item_id+'&payment_id='+payment_id+'&Patient_Payment_Test_ID='+Patient_Payment_Test_ID+'&Laboratory_Test_specimen_ID='+Laboratory_Test_specimen_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID,true);
                                 mm.send();
                             }else{
                                     var Status_From = '<?php  echo filter_input(INPUT_GET, 'Status_From'); ?>';
                                     var patient_id = '<?php  echo filter_input(INPUT_GET, 'patient_id'); ?>';
                                     var item_id = '<?php  echo filter_input(INPUT_GET, 'item_id'); ?>';
                                     var payment_id = '<?php  echo filter_input(INPUT_GET, 'payment_id'); ?>';
                                     var Patient_Payment_Item_List_ID = '<?php  echo filter_input(INPUT_GET, 'Patient_Payment_Item_List_ID'); ?>';

                                     if(window.XMLHttpRequest) {
                                         mm = new XMLHttpRequest();
                                     }
                                     else if(window.ActiveXObject){
                                         mm = new ActiveXObject('Micrsoft.XMLHTTP');
                                         mm.overrideMimeType('text/xml');
                                     }

                                     // document.location ='insert_value.php?Status_From='+Status_From+'&patient_id='+patient_id+'&item_id='+item_id+'&payment_id='+payment_id+'&Patient_Payment_Test_ID='+Patient_Payment_Test_ID+'&Laboratory_Test_specimen_ID='+Laboratory_Test_specimen_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID;
                                     mm.onreadystatechange= function(){
                                         var data = mm.responseText;
                                         if (mm.readyState==4 && mm.status==200)
                                         {
                                             document.getElementById(sample).setAttribute("disabled","disabled");
                                         }
                                     };

                                     mm.open('GET','delete_value.php?Status_From='+Status_From+'&patient_id='+patient_id+'&item_id='+item_id+'&payment_id='+payment_id+'&Patient_Payment_Test_ID='+Patient_Payment_Test_ID+'&Laboratory_Test_specimen_ID='+Laboratory_Test_specimen_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID,true);
                                     mm.send();
                             }
                    }


                                function check_payment(patient_id,payment_id,Patient_Payment_Item_List_ID,Patient_Payment_Test_ID,Laboratory_Test_specimen_ID, i){
									var sample = 'Sample_Taken'+i;
                                    if(document.getElementById(sample).checked){
                                        var item_id = '<?php  echo filter_input(INPUT_GET, 'item_id'); ?>';
                                        if(window.XMLHttpRequest) {
                                            mm = new XMLHttpRequest();
                                        }
                                        else if(window.ActiveXObject){
                                            mm = new ActiveXObject('Micrsoft.XMLHTTP');
                                            mm.overrideMimeType('text/xml');
                                        }
                                        // document.location ='insert_value.php?Status_From='+Status_From+'&patient_id='+patient_id+'&item_id='+item_id+'&payment_id='+payment_id+'&Patient_Payment_Test_ID='+Patient_Payment_Test_ID+'&Laboratory_Test_specimen_ID='+Laboratory_Test_specimen_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID;
                                        mm.onreadystatechange= function(){
                                            var data = mm.responseText;
                                            if (mm.readyState==4 && mm.status==200)
                                            {
                                                insertSpecimen(Patient_Payment_Test_ID , Laboratory_Test_specimen_ID, i);
                                                //document.getElementById("Transaction_Type").value=data;
                                                //document.getElementById("Sample_Taken").setAttribute("disabled","disabled");
                                                

                                            }
                                        };
                                        //alert("Here")
                                        mm.open('GET','check_payment_type.php?patient_id='+patient_id+'&item_id='+item_id+'&payment_id='+payment_id+'&Patient_Payment_Test_ID='+Patient_Payment_Test_ID+'&Laboratory_Test_specimen_ID='+Laboratory_Test_specimen_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID,true);
                                        mm.send();
                                    }
                                }





                                                </script>