<?php
    include("./includes/connection.php");
    session_start();
if(isset($_POST['request_type'])){
    $request_type=$_POST['request_type'];
    $Registration_ID =$_POST['Registration_ID'];
    $finance_department_id = $_SESSION['finance_department_id'];
   // if($request_type=="write_request"){
        ?>
        <style>
            label{
                font-size: large;

            }
        </style>
<table class="table text-center">
       <caption>
           <b> 
               REQUEST FOR CONSULTATION
           </b>
       </caption>
    <tr>
        <td style='width:33%'>
            <label>Emergency <input type="checkbox" id="Emergency" class='request_priority'  value='emergency'></label>
        </td>
        <td style='width:33%'>
            <label>Urgent <input type="checkbox" id="Urgent" class='request_priority' value='urgent'></label>
        </td>
        <td style='width:33%'>
            <label>Routine <input type="checkbox" id="Routine" class='request_priority' value='routine'></label>
        </td>
    </tr>
    <tr>
        <td>
            <label>REQUEST FROM: <?php 
                $department_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT finance_department_name FROM tbl_finance_department WHERE finance_department_id='$finance_department_id'"))['finance_department_name'];
                    echo $department_name;
                
                echo " <br/>REQUESTED BY  ".$_SESSION['userinfo']['Employee_Name'];
            ?>
            </label>
            <input type="text" id="Request_from" style="display:none;" value="<?php echo  $department_name;?>">
        </td>
        <td><label for=""> REQUEST TO:</label>
            <select id="Request_to" class="form-controll">
                <option value="">Select department</option>
                <?php 
              
                        $sql_select_working_department_result=mysqli_query($conn,"SELECT finance_department_code,finance_department_id,finance_department_name FROM tbl_finance_department WHERE enabled_disabled='enabled'") or die(mysqli_error($conn));
                        if(mysqli_num_rows($sql_select_working_department_result)>0){
                            while($finance_dep_rows=mysqli_fetch_assoc($sql_select_working_department_result)){
                                $finance_department_id=$finance_dep_rows['finance_department_id'];
                                $finance_department_name=$finance_dep_rows['finance_department_name'];
                                $finance_department_code=$finance_dep_rows['finance_department_code'];
                                echo "<option value='$finance_department_id'>$finance_department_name-->$finance_department_code</option>";
                            }
                        }else{
                                echo "No result found";
                            }
                ?>
            </select>
        </td>
        <td>
            <label>Date of Request</label>
            
            <?php 
                   $Today_Date = mysqli_query($conn,"select now() as today");
                    while($row = mysqli_fetch_array($Today_Date)){
                        $original_Date = $row['today'];
                        $new_Date = date("Y-m-d", strtotime($original_Date));
                        $Today = $new_Date;
                        $age ='';
                    }
                    echo $original_Date;
            ?>
        </td>
    </tr>
    <tr>
        <td colspan="3" width="90%">
            <label>Diagnosis:</label>
            <textarea placeholder='Enter Diagnosis'  id="Diagnosis"></textarea>
        </td>
        <!-- <td  style='width:10%; padding-top: 3em;'><input type="button" class="btn btn-primary btn-md" onclick="select_diagnosis_for_consultation(Registration_ID)"  style="width: 23em;" value="SELECT DIAGNOSIS"></td> -->
    </tr>
    <tr>
        <td colspan="3">
            <label>Brief Case Summary:</label>
            <textarea placeholder='Enter Brief Case Summary' id="Brief_case_summary"></textarea>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <label>Question:</label>
            <textarea placeholder='Enter Question' id="Question"></textarea>
        </td> 
    </tr>
    <tr>
        <td colspan="3">
            <a class="btn btn-primary btn-sm pull-right"  style="width: 23em;" onclick="save_request_form_data(<?php echo  $Registration_ID; ?>)">SAVE</a> 
        </td>
    </tr>
</table>    
            
        <?php
   // }
}
if(isset($_POST['pre_request'])){
    $Registration_ID = $_POST['Registration_ID'];
    $name =$_SESSION['userinfo']['Employee_Name'];
    ?>
    <div class="col-md-12"> 
         <div class="box box-primary" >
            <div class="box-body"> 
                <table class="table" >
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>REQUEST DATE</th>
                            <th>REQUESTED BY</th>
                            <th>REQUESTED TO</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                           
                            $num=0;
                           
                            $select_request = mysqli_query($conn, "SELECT Request_Consultation_ID, Request_to,Request_from,finance_department_name,Date_of_request FROM tbl_request_for_consultation rc, tbl_finance_department fd WHERE fd.finance_department_id=Request_to AND Registration_ID='$Registration_ID' ORDER BY Request_Consultation_ID DESC") or die(mysqli_error($conn));
                            if(mysqli_num_rows($select_request)>0){
                                while($rq_rw = mysqli_fetch_assoc($select_request)){
                                    $Request_Consultation_ID = $rq_rw['Request_Consultation_ID'];
                                    $Request_to = $rq_rw['finance_department_name'];
                                    $Request_from = $rq_rw['Request_from'];
                                    $Date_of_request = $rq_rw['Date_of_request'];
                                  
                                    $num++;
                                    echo "<tr>
                                        <td>$num</td>
                                        <td>$Date_of_request</td>
                                        <td>$Request_from</td>
                                        <td>$Request_to</td>
                                        <td>
                                        <div class='btn-group'>                                            
                                            <a class='btn btn-primary btn-sm' style='width: 15em; text-align: center;' name='preview_request' onclick='reply_request_consultation($Request_Consultation_ID, $Registration_ID)'> PREVIEW</a>
                                        </div>
                                        </td>
                                    </tr>";
                                }
                            }else{
                                echo "<tr><td colspan='5'>No result found</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
}
if(isset($_POST['preview_request'])){
    $Registration_ID = $_POST['Registration_ID'];
    $Request_Consultation_ID = $_POST['Request_Consultation_ID'];
   // $Patient_name = $_POST['Patient_name'];
    ?>
    <style>
            label{
                font-size: large;

            }
        </style>
    <div class="col-md-12">
    <div class="box box-primary"  >
        <div class="box-body">
        <?php 
        //if(isset($_POST['edit_form'||'Preview'])){
        $name =$_SESSION['userinfo']['Employee_Name'];
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        $num=0;
       //echo $name;
      
        $select_requests = mysqli_query($conn, "SELECT Request_type,Diagnosis,finance_department_name,Brief_case_summary,Question, Request_from,Date_of_request, Request_Consultation_ID,Request_to, rc.Employee_ID FROM tbl_request_for_consultation rc,  tbl_finance_department fd WHERE fd.finance_department_id=Request_to AND Registration_ID='$Registration_ID' AND Request_Consultation_ID='$Request_Consultation_ID' ") or die(mysqli_error($conn));
        
        $Urgent="";
        $Routine ="";
        $Emergency ="";
        if(mysqli_num_rows($select_requests)>0){
            while($rq_rw = mysqli_fetch_assoc($select_requests)){
                $Request_Consultation_ID = $rq_rw['Request_Consultation_ID'];
                $Request_to_department_name = $rq_rw['finance_department_name'];
                $requested_by_Employee_ID = $rq_rw['Employee_ID'];
                $Request_from = $rq_rw['Request_from'];
                $Date_of_request = $rq_rw['Date_of_request'];
//$Employee_Name =$rq_rw['Employee_Name'];
                $Diagnosis = $rq_rw['Diagnosis'];
                $Question = $rq_rw['Question'];
                $Brief_case_summary = $rq_rw['Brief_case_summary'];
                $Date_of_request = $rq_rw['Date_of_request'];
                $request_to_id = $rq_rw['Request_to'];
                $num++;
                $Request_type = explode(',', $rq_rw['Request_type']);
                foreach($Request_type as $type){
                    if($type == "Emergency"){
                        $Emergency ="checked='checked'";
                    }
                    if($type == "Urgent"){
                        $Urgent ="checked='checked'";
                    }
                     if($type == "Routine"){
                        $Routine ="checked='checked'";
                    }
                }
                ?>
                <input type="text" value="<?php echo $Request_Consultation_ID; ?>" style="display: none;" id="Request_Consultation_ID">
                <input type="text" value="<?php echo $Registration_ID; ?>" id="Registration_ID" style="display: none;">
                <!-- Start of table -->
                <table width="100%" class="table">
                    <tr>
                        <th colspan="3" style="text-align: center;">TYPE OF REQUEST</th>
                    </tr>
                <tr>
                    <td style='width:33%'>
                        <label >Emergency <input type="checkbox" id="Emergency" name="Request_type" <?php echo $Emergency;?>></label>
                    </td>
                    <td style='width:33%'>
                        <label>Urgent <input type="checkbox" id="Urgent" <?php echo $Urgent;?>></label>
                    </td>
                    <td style='width:33%'>
                        <label>Routine  <input type="checkbox" id="Routine" <?php echo $Routine; ?>></label>
                    </td>
                </tr>
                <tr>
                    <?php
                     $select_replay = mysqli_query($conn, "SELECT consultation_request_replay,Employee_Name,date_of_replay FROM tbl_employee e, tbl_consultation_request_replay cr WHERE Registration_ID='$Registration_ID' AND Request_Consultation_ID='$Request_Consultation_ID' ") or die(mysqli_error($conn));
                     if(($requested_by_Employee_ID ==$Employee_ID) && (mysqli_num_rows($select_replay)==0) ){
                         // ($requested_by_Employee_ID ==$Employee_ID) && (mysqli_num_rows($select_replay)>0)
                      //   echo mysqli_num_rows($select_replay) ."--".$Employee_ID ."++".$requested_by_Employee_ID    ;
                        ?>
                    <td><input type="text" id="Request_from" style="display:none;" value="<?php echo $finance_department_id;?>">
                        <label>FROM: <?php echo strtoupper($Request_from)?></label></td>
                    <td>TO:
                        <select id="Request_to">
                            <option value="">Select department</option>
                            <?php                             
                            $sql_select_working_department_result=mysqli_query($conn,"SELECT finance_department_code,finance_department_id,finance_department_name FROM tbl_finance_department WHERE enabled_disabled='enabled'") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_working_department_result)>0){
                                while($finance_dep_rows=mysqli_fetch_assoc($sql_select_working_department_result)){
                                    $finance_department_id=$finance_dep_rows['finance_department_id'];
                                    $finance_department_name=$finance_dep_rows['finance_department_name'];
                                    $finance_department_code=$finance_dep_rows['finance_department_code'];
                                    echo "<option value='$finance_department_id'>$finance_department_name-->$finance_department_code</option>";
                                }
                            }else{
                                    echo "No result found";
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <label>Date of Request</label>
                            
                            <?php 
                                $Today_Date = mysqli_query($conn,"select now() as today");
                                    while($row = mysqli_fetch_array($Today_Date)){
                                        $original_Date = $row['today'];
                                        $new_Date = date("Y-m-d", strtotime($original_Date));
                                        $Today = $new_Date;
                                        $age ='';
                                    }
                                    echo $Date_of_request;
                            ?>
                        </td>
                        <?php
                    }else{
                        echo "<td colspan='3' style='text-align:center'>Request from  <b>".strtoupper($Request_from)."</b> FOR $Patient_name</td>";
                    }
                ?>
                    
                    </tr>
                
               
                   <tr>
                        <td colspan="3">
                            <label style="text-align: center;"><h4 style="text-align: center;"> Diagnosis:</h4></label>
                                <textarea name="" width="90%" id="Diagnosis"  rows="2" class="form-control"><?php echo $Diagnosis;?></textarea>
                            
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <label>Brief Case Summary:</label>
                        <textarea type="text" id="Brief_case_summary" rows="2" class="form-control"><?php echo $Brief_case_summary;?></textarea>
                            
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <label>Question:</label>
                            <textarea type="text" id="Question" rows="2" class="form-control"><?php echo $Question;?></textarea>
                        </td> 
                    </tr>
                    <tr>
                        <td colspan="3">                            
                        <?php
                        
                        
                         if((mysqli_num_rows($select_replay))==0 ){
                       ?> 
                            <button class="btn btn-primary btn-sm" style='width:10em;' class="art-button-green btn-xs" style="align-content: right;" name="request_btn_update" type="button" onclick="update_consultation_request(<?php echo $Request_Consultation_ID; ?>)">Update</button>
                        
                        <?php
                        } 
            ?>     </td>
                    </tr>
                    </table>
            
            <table class='table' style='border:1px solid white important;'>
            <thead>
                <tr>
                    <th width='15%' class='text-center'>DATE</th>
                    <th width='65%' class='text-center'>REPLY</th>
                    <th width='20' class='text-center'>DOCTOR REPLIED</th>
                </tr>
            </thead>
            <tbody id='reply_body'>
              <?php 
            echo "</tbody>
            </table>";
            $finance_department_id = $_SESSION['finance_department_id'];
            
            $Request_from2 =mysqli_fetch_assoc(mysqli_query($conn,"SELECT finance_department_code,finance_department_id,finance_department_name FROM tbl_finance_department WHERE finance_department_id='$finance_department_id' AND enabled_disabled='enabled'"))['finance_department_name'];
            
            if(($finance_department_id == $request_to_id)  || ($Request_from == $Request_from2)){
            ?><br>
            <h5 align='center' ><b>Reply:</b></h5>
            <br>
            
            <div class="row">
                <div class="col-md-12">                    
                        <textarea type="text" id="consultation_request_replay" rows="3" class="form-control"></textarea>                   
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 text-center">
                    <button class="btn btn-primary btn-sm" style='width:10em;' name="replay_btn" type="button" onclick="Replay_consultation_request(<?php echo $Registration_ID;?>,<?php echo $Request_Consultation_ID; ?>)">REPLY</button>
                </div>
            </div>
<?php      }
            ?>
            
        <?php }
        }else{
            echo "No result found 99999";
        }
    //}
    ?>
        </div>
    </div>
</div>
<?php
}
if(isset($_POST['reply_body'])){
    $Registration_ID = $_POST['Registration_ID'];
    $Request_Consultation_ID = $_POST['Request_Consultation_ID'];
    $select_replay = mysqli_query($conn, "SELECT consultation_request_replay,Employee_Name,date_of_replay FROM tbl_employee e, tbl_consultation_request_replay cr WHERE Registration_ID='$Registration_ID' AND Request_Consultation_ID='$Request_Consultation_ID' AND e.Employee_ID=cr.Employee_ID") or die(mysqli_error($conn));
        $num = 0;
        if(mysqli_num_rows($select_replay)>0 ){
            while($replay_rw = mysqli_fetch_assoc($select_replay)){

                $consultation_request_replay = $replay_rw['consultation_request_replay'];
                $date_of_replay = $replay_rw['date_of_replay'];
                $Employee_Name = $replay_rw['Employee_Name'];
                $num++;
                echo "<tr>
                        <td><p>$date_of_replay<p></td>
                        <td><p>$consultation_request_replay</p></td>
                        <td>$Employee_Name</td>
                        </tr>
                        ";
            }
        }else{
            echo "<tr><td colspan='2'class='text-center text-danger' >No any replay for this request</td></tr>";
        }
}