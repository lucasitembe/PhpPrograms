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
            echo "<a href='seachpatientfromspeciemenlist.php' class='art-button-green'>BACK</a>";
            }
    }
    
    
 if($_GET['patient_id']!=''){
  $Registration_ID=$_GET['patient_id'];
 }else{
  $Registration_ID='';
 }
 if($_GET['Patient_Payment_Item_List_ID']){
  $Patient_Payment_Item_List_ID=$_GET['Patient_Payment_Item_List_ID'];
 }else{
  $Patient_Payment_Item_List_ID='';
 }
 
 if($_GET['payment_id']){
  $Patient_Payment_ID=$_GET['payment_id'];
 }else{
  $Patient_Payment_ID='';
 }
 if($_GET['Status_From']!=''){
  $Status_From=$_GET['Status_From'];
 }else{
  $Status_From='';
 }



if($_GET['Status_From'] == 'payment'){
    $Registration_ID = $_GET['patient_id'];
    $Patient_Payment_ID = $_GET['payment_id'];
    $sql = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_patient_payments pp,tbl_employee emp
                        WHERE pp.Registration_ID = pr.Registration_ID
                          AND emp.Employee_ID = pp.Employee_ID
                          AND pp.Patient_Payment_ID = '$Patient_Payment_ID' ");
}else{
    $Registration_ID = $_GET['patient_id'];
    $Patient_Payment_ID = $_GET['payment_id'];
    $sql = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_payment_cache pc,tbl_employee emp
                        WHERE  pc.Registration_ID = pr.Registration_ID
                          AND emp.Employee_ID = pc.Employee_ID
                           AND pc.Payment_Cache_ID = '$Patient_Payment_ID' ");
}


//if(isset($_GET['Status_From']))
//        if($_GET['Status_From'] == 'payment')
//        {
//            $Registration_ID = $_GET['patient_id'];
//            $sql =mysqli_query($conn,"SELECT * FROM tbl_patient_payments as pp
//            join tbl_patient_registration as pr ON pr.Registration_ID = pp.Registration_ID
//            JOIN tbl_Employee as e ON e.Employee_ID = pp.Employee_ID
//            where pr.Registration_ID = '$Registration_ID' AND pp.Patient_Payment_ID ='".filter_input(INPUT_GET, 'payment_id')."'
//            ");
//
//        }else if($_GET['Status_From'] == 'cache')
//        {
//          $sql =mysqli_query($conn,"SELECT * FROM tbl_payment_cache as pp
//                    join tbl_patient_registration as pr ON pr.Registration_ID = pp.Registration_ID
//                      JOIN tbl_Employee as e ON e.Employee_ID = pp.Employee_ID
//                        JOIN tbl_item_list_cache as il ON il.Payment_Cache_ID = pp.Payment_Cache_ID
//                            where il.Payment_Item_Cache_List_ID ='".filter_input(INPUT_GET, 'patient_id')."'
//                                ");
//        }



$disp =mysqli_fetch_assoc($sql);

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

<fieldset style="margin-top:5px;min-height:500px;">
  <center>
    <table border="0" class="hiv_table" style="width:90%" >

        <?php 
        if(filter_input(INPUT_GET, 'Status_From') =='payment'){
        $select_results =mysqli_query($conn,"SELECT * FROM tbl_patient_payment_results WHERE Patient_Payment_ID ='".filter_input(INPUT_GET, 'payment_id')."'");
      }else  if(filter_input(INPUT_GET, 'Status_From') =='cache'){
  $select_results =mysqli_query($conn,"SELECT * FROM tbl_patient_cache_results WHERE Payment_Cache_ID ='".filter_input(INPUT_GET, 'payment_id')."'");

      }

            if(mysqli_num_rows($select_results) == 0){
?>
              <tr> 
              <!--<td><a onclick="alert('No Results To Validate!')" class="art-button-green">Validation/Submition</a>-->
              <td colspan="1">&nbsp;</td>
              </tr>
              <?php
            }else{
              ?>
              <tr> 
              <!--<td><a href="laboratory_results_validation.php?Patient_Payment_Item_List_ID=<?php echo $_GET['Patient_Payment_Item_List_ID']?>&Status_From=<?php
                       echo filter_input(INPUT_GET, 'Status_From'); ?>&payment_id=<?php
                               echo filter_input(INPUT_GET, 'payment_id'); ?>&patient_id=<?php
                                       echo filter_input(INPUT_GET, 'patient_id'); ?>" class="art-button-green">Validation / Submition</a>
              <td colspan="1">&nbsp;</td>-->
              </tr>
              <?php
            }
            ?>
        <tr>
            <td colspan="4" width="100%"><hr></td>
        </tr>
        <tr>
        <td colspan="4">
        <center>
            <table style="width:100%" class="hiv_table"  >
                <tr>
                  <td style="color:black;font-weight:600;border:1px solid #ccc;text-align:right;">Patient Name</td>
                  <td style="color:black;border:1px solid #ccc;"><input readonly='readonly'name="Patient_Name" style="width:100%" id="Patient_Name" value="<?php echo $disp['Patient_Name']; ?>"></td>
                  <td style="color:black;font-weight:600;border:1px solid #ccc;text-align:right;">Submit Time</td>
                  <td style="color:black;border:1px solid #ccc;"><input readonly='readonly'name="Payment_Date_And_Time" style="width:100%" value="<?php echo $disp['Payment_Date_And_Time']; ?>"></td>
                    <td style="color:black;font-weight:600;border:1px solid #ccc;text-align:right;">Sponsor</td>
                    <td style="color:black;border:1px solid #ccc;"><input readonly='readonly'name="Sponsor_Name" style="width:100%" id="Sponsor" value="<?php echo $disp['Sponsor_Name']; ?>"></td>
                    <td style="color:black;font-weight:600;border:1px solid #ccc;text-align:right;">Visit Date</td>
                    <td style="color:black;border:1px solid #ccc;"><input readonly='readonly'name="Payment_Date_And_Time" style="width:100%" value="<?php echo $disp['Payment_Date_And_Time']; ?>"></td>
                </tr>
                <tr> 
                  <td style="color:black;font-weight:600;border:1px solid #ccc;text-align:right;">Gender</td>
                  <td style="color:black;border:1px solid #ccc;"><input readonly='readonly'name="Gender" style="width:100%" id="gender" value="<?php echo $disp['Gender']; ?>"></td>
                  <td style="color:black;font-weight:600;border:1px solid #ccc;text-align:right;">Age</td>
                  <td style="color:black;border:1px solid #ccc;"><input readonly='readonly'name="Age" style="width:100%" id="age" value="<?php echo $age; ?>"></td>
                  <td style="color:black;font-weight:600;border:1px solid #ccc;text-align:right;">File No.</td>
                  <td style="color:black;border:1px solid #ccc;"><input readonly='readonly'name="Old_Registration_Number" style="width:100%" id='File_Number'  value="<?php echo $disp['Old_Registration_Number']; ?>"></td>
                  <td style="color:black;font-weight:600;border:1px solid #ccc;text-align:right;">Requesting doctor</td>
                  <td style="color:black;border:1px solid #ccc;"><input readonly='readonly'name="Employee_Name" style="width:100%" id="Employee_Name" value="<?php echo $disp['Employee_Name']; ?>"></td>
                </tr>
                <tr>
                  <td colspan="8">
                    <hr>
                  </td>
                </tr>
                <tr>
                    <td colspan="9" width="100%" >
                                <div id="table" style="margin-top:5px;height:300px;widht:100%;background-color:white;overflow-x:hidden;overflow-y: scroll;">

<?php



if(isset($_GET['Status_From']))
        if($_GET['Status_From'] == 'payment')
        {
          $select_tests =mysqli_query($conn,"SELECT 'payment' as Status_From,i.Item_ID as Item_ID,i.Product_Name as Product_Name,il.Patient_Payment_Item_List_ID as Patient_Payment_Item_List_ID,Process_Status
                       FROM tbl_patient_payment_item_list as il
                        join tbl_items as i ON i.Item_ID =il.Item_ID 
                        where Patient_Payment_ID='".filter_input(INPUT_GET, 'payment_id')."' and Check_In_Type ='Laboratory' and i.Consultation_Type = 'Laboratory'");

          //find the specimen time
          $select_time =mysqli_query($conn,"SELECT Submited_Time,View_Time FROM tbl_patient_payment_test as ppt 
                                          JOIN tbl_patient_payment_item_list as ppil ON ppt.Patient_Payment_Item_List_ID =ppil.Patient_Payment_Item_List_ID 
                                          WHERE ppil.Patient_Payment_ID='".filter_input(INPUT_GET, 'payment_id')."'");

        }else if($_GET['Status_From'] == 'cache')
        {
          $select_tests =mysqli_query($conn,"SELECT 'cache' as Status_From,i.Item_ID as Item_ID,i.Product_Name as Product_Name,il.Doctor_Comment as Doctor_Comment
                          ,il.Payment_Item_Cache_List_ID as Patient_Payment_Item_List_ID,Process_Status
                       FROM tbl_item_list_cache as il
                        join tbl_items as i ON i.Item_ID =il.Item_ID 
                        where il.Payment_Cache_ID='".filter_input(INPUT_GET, 'payment_id')."' and Check_In_Type ='Laboratory' and i.Consultation_Type = 'Laboratory'");

          //find the specimen time
          $select_time =mysqli_query($conn,"SELECT Submited_Time,View_Time FROM tbl_patient_cache_test as ppt 
                                          JOIN tbl_item_list_cache as ppil ON ppt.Payment_Item_Cache_List_ID =ppil.Payment_Item_Cache_List_ID 
                                          WHERE ppil.Payment_Cache_ID='".filter_input(INPUT_GET, 'payment_id')."'");
               }


   echo "<center><table class='hiv_table' style='width:100%'>";       
   echo "<tr>
                <th>S/N</th>
                <th style='text-align:left;'>Test Name</th>
                <th>Doctor's Notes</th>
                <th>Time Specimen Taken</th>
                <th>Time Specimen Submitted</th>
                <th>Test Status</th>
                <th>Overall Remarks</th>
                <th>Attachment</th>
                <th>Results</th>
      </tr>";
    
    

$i=1;    
    while($row =mysqli_fetch_assoc($select_tests)){
if ($i%2==0){
            $color='#F8F8F8';
            }else{
            $color='white';
            }

        if($row['Status_From']=='payment'){
          $select_test1 =mysqli_query($conn,"SELECT * FROM tbl_patient_payment_test WHERE Patient_Payment_Item_List_ID='".$row['Patient_Payment_Item_List_ID']."'");  

        }else if($row['Status_From'] == 'cache'){
          $select_test1 =mysqli_query($conn,"SELECT * FROM tbl_patient_cache_test WHERE Payment_Item_Cache_List_ID='".$row['Patient_Payment_Item_List_ID']."'");  
        }
       echo "<tr bgcolor=".$color.">";
       echo  "<td>".$i."</td>";?>
                                   <!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
                                    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
                                    <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
									
                                    <link rel="stylesheet" href="/resources/demos/style.css">
                                   -->
								   <script>
                                        $(function() {
                                            $( document ).tooltip();
                                        });
                                    </script>
                                    <script>
                                        $(function() {
                                            $( "#tooltip" ).tooltip();
                                        });
                                    </script>
                                    <style>
                                        label {
                                            display: inline-block;
                                            width: 5em;
                                        }
                                    </style>
       <td><textarea name='' id="tooltip" disabled="disabled" title="<?php echo $row['Product_Name']?>"> <?php echo substr($row['Product_Name'],0,20);?>...</textarea></td>
       <?php echo "<td  colspan='1'><textarea rows='2' cols='10' readonly='readonly' name=''>";
            if(($_GET['Status_From'] == 'cache')){
                echo $row['Doctor_Comment'];
            }else{
                echo "Nill";
            }
       echo " </textarea></td>";
       echo "<td style='width:100px'><input name='' readonly='readonly' value='";
                //display the specimen time
                $row1 =mysqli_fetch_array($select_time);
                echo $row1['View_Time'];
       echo "'</td>";  

       echo "<td style='width:100px'><input name='' readonly='readonly' value='";
                        if($row1['Submited_Time'] == '0000-00-00 00:00:00'){
                                echo "Not submmited";
                        }else{
                            echo  $row1['Submited_Time'];
                        }
                          echo "'> </td>";


       echo "<td style='width:50px'>
                            <select  class='list_style'>
                                <option ";  if($row['Process_Status'] == 'Result')
                                                                      {
                                                                                 echo "selected='selected'"; 
                                                                      } 
                                                                       echo ">Done</option>
                                <option ";   if($row['Process_Status'] =='Inactive')
                                                                      { 
                                                                        echo "selected='selected'"; 
                                                                      } 
                                                                       echo ">Not Applicable</option>
                                <option ";   if($row['Process_Status'] =='Sample Collected')
                                                                      { 
                                                                        echo "selected='selected'"; 
                                                                      } 
                                                                      echo ">Pending</option>
                            </select>
        </td>";
       echo "<td><input name='Overall_Remarks' id='Overall_Remarks' value='' onchange='insertRemarks()' style='width:95%'></td>";
       echo "<td style='width:50px'><a href='#'><a href='Lab_Attachment.php?Registration_ID=$Registration_ID&Patient_Payment_ID=$Patient_Payment_ID&Patient_Payment_Item_List_ID=$Patient_Payment_Item_List_ID&Status_From=$Status_From&LaboratoryAttachmentThisPage=ThisPage' class='art-button-green'>Attachment</a></td>";


       if(mysqli_num_rows($select_test1) <= 0){
       echo "<td style='width:50px'>";
       ?>
       <a ><button onclick="alert('No Specimen Collected For This Test')">Results</button></a> </td>
          </tr>
      <?php
       }else{
       echo "<td style='width:50px'>";
       echo "<a href='laboratory_results_templates.php?Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&Status_From=".filter_input(INPUT_GET, 'Status_From')."&Item_ID=".$row['Item_ID']."&patient_id=".filter_input(INPUT_GET, 'patient_id')."&payment_id=".filter_input(INPUT_GET, 'payment_id')."'><button>Results</button></a> </td>";
      echo "</tr>";
       }



       $i++;
    }

    ?>





                                      </table></center>
                                </div>
                    </td>
                </tr>
                <tr>
                  <td><br></td>
                </tr>
                <tr>
                  <td colspan="5">Patient Clinical History:</td>
                  <td colspan="5">Lab Remarks</td>
                </tr>
                <tr>
                    <td colspan="5"  >
                                <div width="100%" height="120px" style="margin-top:0px">
                                    <textarea rows=""></textarea>

                                </div>
                    </td>
                    <td colspan="5">
                                <div width="100%" height="120px" style="margin-top:0px">
                                    <textarea rows="" onchange="insertLab_Remarks()"></textarea>

                                </div>
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
