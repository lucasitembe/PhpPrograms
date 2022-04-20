<?php
  include("./includes/header.php");
  include("./includes/connection.php");
  $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
  
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


                        $sql1 =mysqli_query($conn,"SELECT * FROM tbl_patient_registration where Registration_ID='".filter_input(INPUT_GET, 'patient_id')."'");
                        $disp =mysqli_fetch_array($sql1);


                      ?>


                                    <fieldset  style="margin-top:5px;min-height:500px;">
                                            <center>
                                              <table    style="width:90%" class="hiv_table">
                                                  <tr>
                                                      <td colspan="4" width="100%">
                                                          <center>
                                                               LAB RESULT SUBMITION
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
                                                               <?php echo $disp['Patient_Name'].",".$disp['Gender'].",".$disp['Date_Of_Birth']." years of age,"; ?>
                                                                    </center>
                                                                           </td>
                                                                                 </tr>
                                                <tr>
                                                     <td colspan="4" width="100%">
                                                        <hr>
                                                            </td>
                                                              </tr>
                                                <tr>

            <?php
                                                                if(filter_input(INPUT_GET, 'Status_From') == 'payment'){
                  $sql = mysqli_query($conn,"SELECT * FROM tbl_patient_payment_item_list as il 
                                              join tbl_items as i on il.Item_ID = i.Item_ID 
                                              where  Patient_Payment_ID ='".filter_input(INPUT_GET, 'payment_id')."' and Check_IN_Type='Laboratory' ");    

                                                                              }else if(filter_input(INPUT_GET, 'Status_From') == 'cache'){
                  $sql = mysqli_query($conn,"SELECT * FROM tbl_item_list_cache as il 
                                              join tbl_items as i on il.Item_ID = i.Item_ID 
                                              where   Payment_Cache_ID ='".filter_input(INPUT_GET, 'payment_id')."' and Check_IN_Type='Laboratory' "); 
                                                                              } 
           
                                                   ?>
                                    
                                  <td colspan="4" width="100%">
                                     <div   style="overflow-y:scroll;overflow-x:hidden;width:100%;height:450px;background-color:white;border:1px solid black;">
                                     <table style="width:100%;border-collapse: collapse;border-spacing:0px;">
                                     <?php
                                       while($disp =mysqli_fetch_array($sql)){
                                                echo "
                                                  <tr bgcolor='#037cb0'>
                                                    <td colspan='4' style='font-size:14px;'>Test Name: ".$disp['Product_Name']."</td>
                                                  </tr><tr><td>";

                                                                           ?>
                                                        <table  border="0"  style="width:95%;margin-left:5%;margin-top:0px;margin-bottom:0px;margin-right:0px;">
                                                        <t  bgcolor="#eee">
                                                        <th style="font-size:13px;width:20px">SN</th>
                                                        <th style="font-size:13px;">Parameter Name</th>
                                                        <th style="font-size:13px;">Results</th>
                                                        <th style="font-size:13px;">UoM</th>
                                                        <th style="font-size:13px;">T</th>
                                                        <th style="font-size:13px;">M</th>
                                                        <th style="font-size:13px;">V</th>
                                                        <th style="font-size:13px;">S</th>
                                                        <th style="font-size:13px;">Normal Value</th>
                                                        <th style="font-size:13px;">Status</th>
                                                        <th style="font-size:13px;">Previous Result</th>
                                                        <th style="font-size:13px;width:50px;">Submit</th>
                                                        </tr>  
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



                                                                            <form name="" action="#" method="POST">                  
                                                                            <tr bgcolor="#eee">
                                                                            <td><?php echo $i; ?></td>
                                                                            <td style="color:blue;border:1px solid #ccc;"><?php echo $Laboratory_Parameter_Name; ?></td>
                                                                            <td style="color:blue;border:1px solid #ccc;"><input name="Laboratory_Result[]" type="text" value="  <?php echo $Laboratory_Result; ?>"></td>
                                                                            <td style="color:blue;border:1px solid #ccc;"><?php echo $Unit_Of_Measure; ?></td>
                                                                            <input name="Patient_Payment_Result_ID[]" value="<?php echo $Patient_Payment_Result_ID; ?>" id="Patient_Payment_Result_ID[]" type="hidden">
                                                                            <td style="color:blue;border:1px solid #ccc;"></td>
                                                                            <td style="color:blue;border:1px solid #ccc;"><center><?php if(isset($row)){

                                                                                                                            $select_madification_status =mysqli_query($conn,"SELECT *
                                                                                                                                                                      FROM tbl_laboratory_results_modification
                                                                                                                                                                      WHERE Patient_Payment_Result_ID = '".$Patient_Payment_Result_ID."' and Laboratory_Parameter_ID ='".$Laboratory_Parameter_ID."'");
                                                                                                                            $num = mysqli_num_rows($select_madification_status);
                                                                                                                            if($num> 0){
                                                                                                                              echo "<span style='color:red;'>
                                                                                                                              <a href='laboratory_result_indetails.php?details_for=modification&Laboratory_Parameter_ID=".$Laboratory_Parameter_ID."&Item_ID=".$disp['Item_ID']."&patient_id=".filter_input(INPUT_GET, 'patient_id')."'>M</a></span>";
                                                                                                                            }

                                                                                                                          } ?>
                                                                                                                          </center>
                                                                                                                          </td>
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
                                                                                                                                          } ?></center></td>
                                                                            <td style="color:blue;border:1px solid #ccc;"><center><?php if(isset($row)){

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
                                                                                                                                            } ?></center>
                                                                                                                                            </td>
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
                                                                           <td style="color:blue;border:1px solid #ccc;">
                                                                             <?php 
                                                                                $sql5 =mysqli_query($conn,"SELECT * FROM tbl_patient_payment_results 
                                                                                                        WHERE Patient_ID ='".filter_input(INPUT_GET, 'patient_id')."'
                                                                                                               and Laboratory_Parameter_ID ='".$Laboratory_Parameter_ID."' 
                                                                                                                        and Item_ID ='".filter_input(INPUT_GET, 'Item_ID')."' 
                                                                                                                       and Result_Datetime !='$Result_Datetime' 
                                                                                                                                ORDER BY Patient_Payment_Result_ID DESC LIMIT 1");
                                                                                                           if(mysqli_num_rows($sql5) > 0){
                                                                                                                  $row5 =mysqli_fetch_array($sql5);
                                                            echo"<a href='laboratory_result_indetails.php?details_for=priviousResult&Laboratory_Parameter_ID=".$Laboratory_Parameter_ID."&Item_ID=".filter_input(INPUT_GET, 'Item_ID')."&patient_id=".filter_input(INPUT_GET, 'patient_id')."' style='text-decolation:none;'>".$row5['Result_Datetime']."(".$row5['Laboratory_Result'].")</a>";
                                                                                                              }else{
                                                                                                                     echo "No Previous Results";
                                                                                                              }
                                                                                
                                                                            ?>
                                                                           </td>
                                                                            <td style="width:150px;font-size:10px;">

                                                                                <?php

                                                                                      if(mysqli_num_rows($sql8) > 0){
                                                                                        $row =mysqli_fetch_array($sql8);
                                                                                         echo "<span style='color:#037cb0;'>Submited By ". $row['Employee_Name'];
                                                                                      }else{
                                                                                        ?>
                                                                                    <input name="" type="checkbox" onchange="submitResults('<?php echo $Patient_Payment_Result_ID; ?>','<?php echo $Employee_ID; ?>','<?php echo $Laboratory_Parameter_ID; ?>')">
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
                  <td colspan="4" width="100%" style="text-align:right;border-bottom:1px solid black;padding-bottom:4px;font-size:14px;margin-top:2px;">
                  <button onclick="validateResultAll('<?php echo $Employee_ID; ?>')" class="art-button-green">Submit All</button>
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
                        function submitResults(Patient_Payment_Result_ID , Employee_ID,Laboratory_Parameter_ID){
                          var Status_From = '<?php echo filter_input(INPUT_GET, 'Status_From'); ?>';
                      if(window.XMLHttpRequest) {
                                    mm = new XMLHttpRequest();
                                                     }
                                     else if(window.ActiveXObject){ 
                                             mm = new ActiveXObject('Micrsoft.XMLHTTP');  
                                                     mm.overrideMimeType('text/xml');
                                                             }

                 //document.location ='laboratory_result_submited.php?Status_From='+Status_From+'&Patient_Payment_Result_ID='+Patient_Payment_Result_ID+'&Employee_ID='+Employee_ID+'&Laboratory_Parameter_ID='+Laboratory_Parameter_ID; 
                        mm.onreadystatechange= function(){
                                 var data = mm.responseText;
                                    if (mm.readyState==4 && mm.status==200)
                                            {
                                                location.reload(true);
                                                        }
                                                            };

                         mm.open('GET','laboratory_result_submited.php?Status_From='+Status_From+'&Patient_Payment_Result_ID='+Patient_Payment_Result_ID+'&Employee_ID='+Employee_ID+'&Laboratory_Parameter_ID='+Laboratory_Parameter_ID,true);
                                mm.send();
                                                 }

                                                    </script>   
              