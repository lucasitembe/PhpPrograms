<?php
  include("./includes/header.php");
  include("./includes/connection.php");
    //include the library
  include "libchart/libchart/classes/libchart.php";
  $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
  
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
                                                                                 echo "<a onclick='goBack()' class='art-button-green'>BACK</a>";
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

                                                                                //get parameter name
                                                                                $sql_select_parameterName =mysqli_query($conn,"SELECT Laboratory_Parameter_Name from tbl_laboratory_parameters where Laboratory_Parameter_ID ='".filter_input(INPUT_GET, 'Laboratory_Parameter_ID')."'");
                                                                                $getParameterName =mysqli_fetch_array($sql_select_parameterName);
                                                                                extract($getParameterName);
                                                                                //date manipulation to get the patient age


                                                                                                           ?>


<fieldset  style="margin-top:5px;min-height:500px;">
            <!--  table to show the header of the table-->
              <center>
                  <table style="width:90%;" class="hiv_table" border="0">
                    <tr>
                      <td colspan="4" style="width:100%;padding-bottom:10px;padding-top:5px;font-weight:bold;font-size:16px;color:blue;">
                        <center>Result History For <?php echo $disp['Patient_Name']; ?> For <?php echo $disp2['Product_Name']."(".$Laboratory_Parameter_Name.")"; ?></center>
                          </td>
                            </tr>
                            <tr>
                          <td colspan="4"><hr hegth='4px'></td>
                        </tr>
                      </table>
            </center><!--  end of the header of the table -->
             <table><tr><td>
			 <table style="width:50%;height:100%;float:left;">
               <tr>
                 <td width="100%">
                 <?php

                 if(filter_input(INPUT_GET, 'details_for')=='modification'){ 
                   $select_results =mysqli_query($conn,"SELECT e.Employee_Name as Employee_Name,ppr.Laboratory_Result as Result,ppr.Result_Datetime as Datetime 
                                                  FROM tbl_laboratory_results_modification as ppr
                                                  join tbl_employee as e ON e.Employee_ID = ppr.Employee_ID
                                                 WHERE Item_ID ='".filter_input(INPUT_GET, 'Item_ID')."'
                                                         and Laboratory_Parameter_ID ='".filter_input(INPUT_GET, 'Laboratory_Parameter_ID')."'
                                                                and Patient_ID ='".filter_input(INPUT_GET, 'patient_id')."'");

                 }else{
                   $select_results =mysqli_query($conn,"SELECT e.Employee_Name as Employee_Name,ppr.Laboratory_Result as Result,ppr.Result_Datetime as Datetime FROM tbl_patient_payment_results as ppr
                    join tbl_employee as e ON e.Employee_ID = ppr.Employee_ID
                                                 WHERE Item_ID ='".filter_input(INPUT_GET, 'Item_ID')."'
                                                         and Laboratory_Parameter_ID ='".filter_input(INPUT_GET, 'Laboratory_Parameter_ID')."'
                                                                and Patient_ID ='".filter_input(INPUT_GET, 'patient_id')."'");
                     }
                                        ?>
                  <div style="overflow-y:scroll;overflow-x:hidden;height:450px;">
                   <table  border="0"  style="width:100%;margin-left:0px;margin-top:0px;margin-bottom:0px;border:1px solid #ccc;" class="hiv_table">
                                <tr  bgcolor="#eee">
                                  <th style="font-size:13px;">SN</th>
                                  <th style="font-size:13px;">Result Date</th>
                                  <th style="font-size:13px;">Results</th>
                                  <th style="font-size:13px;">Employee</th>
                                  <th style="font-size:13px;">Status</th>
                                </tr>  
                            <?php
                            $i=1;
                            while(list($Employee_Name,$Result,$Datetime) =mysqli_fetch_array($select_results)){
                             ?>
                                <tr bgcolor="#eee">
                                    <td style="color:blue;border:1px solid #ccc;width:40px;"><?php echo $i; ?></td>
                                    <td style="color:blue;border:1px solid #ccc;"><?php echo $Datetime; ?></td>
                                    <td style="color:blue;border:1px solid #ccc;text-align:center;"><?php echo $Result; ?></td>
                                    <td style="color:blue;border:1px solid #ccc;text-align:center;"><?php echo $Employee_Name; ?></td>
                                    <td style="color:blue;border:1px solid #ccc;"><?php echo "1"; ?></td>
                                </tr>
                                  <?php
                                  $i++;
                                }
                                ?>
                                </table>
                                </div>

                 </td>
               </tr>
             </table></td><td>
             <table style="width:45%;" class="hiv_table">
               <tr>
                 <td>
                 <?php
                 if(filter_input(INPUT_GET, 'details_for')=='modification'){ 
                   $select_results1 =mysqli_query($conn,"SELECT e.Employee_Name as Employee_Name,ppr.Laboratory_Result as Result,ppr.Result_Datetime as Datetime
                                                  FROM tbl_laboratory_results_modification as ppr
                                                  join tbl_employee as e ON e.Employee_ID = ppr.Employee_ID
                                                 WHERE Item_ID ='".filter_input(INPUT_GET, 'Item_ID')."'
                                                         and Laboratory_Parameter_ID ='".filter_input(INPUT_GET, 'Laboratory_Parameter_ID')."'
                                                                and Patient_ID ='".filter_input(INPUT_GET, 'patient_id')."'");

                 }else{
                   $select_results1 =mysqli_query($conn,"SELECT e.Employee_Name as Employee_Name,ppr.Laboratory_Result as Result,ppr.Result_Datetime as DatetimeFROM tbl_patient_payment_results as ppr
                    join tbl_employee as e ON e.Employee_ID = ppr.Employee_ID
                                                 WHERE Item_ID ='".filter_input(INPUT_GET, 'Item_ID')."'
                                                         and Laboratory_Parameter_ID ='".filter_input(INPUT_GET, 'Laboratory_Parameter_ID')."'
                                                                and Patient_ID ='".filter_input(INPUT_GET, 'patient_id')."'");
                     }
                          //new pie chart instance or PieChart,HorizantalBarchart,VerticalBarChart
                          $chart = new LineChart( 650, 450 );
                       
                          //data set instance
                          $dataSet = new XYDataSet();

          while(list($Employee_Name,$Result,$Datetime) =mysqli_fetch_array($select_results1)){

    //       extract($row);
            $dataSet->addPoint(new Point($Datetime,$Result));
        }
    
        //finalize dataset
        $chart->setDataSet($dataSet);
 
        //set chart title
      if(filter_input(INPUT_GET, 'details_for')=='modification'){ 
        $chart->setTitle("Modification Record For ".$disp['Patient_Name']." For ".$disp2['Product_Name']."(".$Laboratory_Parameter_Name.")");
        }else{
        $chart->setTitle("Result History For ".$disp['Patient_Name']." For ".$Laboratory_Parameter_Name."");
        }
        //render as an image and store under "generated" folder
    $image_name = "generated/chart_".$_SESSION['userinfo']['Employee_ID'].".png";
        $chart->render($image_name);
    
        //pull the generated chart where it was stored
        echo "<img alt='Pie chart'  src='$image_name' style='border: 1px solid gray;margin-left:0;'/>";
          ?>


                 </td>
               </tr>
             </table></td></tr></table>

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
