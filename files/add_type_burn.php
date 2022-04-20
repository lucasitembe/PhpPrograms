<?php 
include("middleware/burn_unit_function.php");
session_start();


if(isset($_POST['dailog'])){
?>

<br><br>

<div class="col-md-12">
<center>
    <div class="col-md-3">Type of burn</div>
    <div class="col-md-5">
        <input type="text" id="type_burn" class="form-control">
    </div>
    <div class="col-md-4">
        <button class="art-button-green" onclick="save_type_of_burn()">Save</button>
    </div>
</center>
</div>

<?php
}
$Employee_ID  = $_SESSION['userinfo']['Employee_ID'];
if(isset($_POST['type_burn'])){
    $type_burn = $_POST['type_burn'];
    $data =array(array(
        "type_burn" => $type_burn,
        "Employee_ID "=>$Employee_ID
    ));
   // die(print_r($data));
    if(save_type_of_burn(json_encode($data))>0){

        echo "ok";
    }else{
        echo mysqli_error($conn);
    }
}
if(isset($_POST['classfication'])){
    ?>
    
    <br><br>
    
    <div class="col-md-12">
    <center>
        <div class="col-md-3">Classification </div>
        <div class="col-md-6">
            <input type="text" id="Classfication_of_burn" class="form-control">
        </div>
        <div class="col-md-3">
            <button class="art-button-green" name="btn_classfc" onclick="save_burn_classfication()">Save</button>
        </div>
    </center>
    </div>
    
    <?php
    }

    if(isset($_POST['btn_classfc'])){        
        $Classfication_of_burn = $_POST['Classfication_of_burn'];

       
        $data =array(array(
            "Classfication_of_burn" => $Classfication_of_burn,
            "Employee_ID "=>$Employee_ID
        ));
         //die(print_r($data));
        if(save_classfication_of_burn(json_encode($data))>0){    
            echo "--ok -----";
        }else{
            echo mysqli_error($conn);
        }
        
    }

    if(isset($_POST['pre_request'])){
        ?>
        <div class="col-md-12"> 
             <div class="box box-primary" >
                <div class="box-body"> 
                    <table class="table" style="width: 79em;">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>ORDER DATE</th>
                                <th>ORDERED BY</th>
                                <th>ORDERED TO</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $Registration_ID = $_POST['Registration_ID'];
                                $name =$_SESSION['userinfo']['Employee_Name'];
                                $num=0;
                                $select_request = mysqli_query($conn, "SELECT Request_Consultation_ID, Request_to,Request_from,Date_of_request, Employee_Name FROM tbl_request_for_consultation rc, tbl_employee e WHERE e.Employee_ID=Request_to AND Registration_ID='$Registration_ID' ") or die(mysqli_error($conn));
                                if(mysqli_num_rows($select_request)>0){
                                    while($rq_rw = mysqli_fetch_assoc($select_request)){
                                        $Request_Consultation_ID = $rq_rw['Request_Consultation_ID'];
                                        $Request_to = $rq_rw['Request_to'];
                                        $Request_from = $rq_rw['Request_from'];
                                        $Date_of_request = $rq_rw['Date_of_request'];
                                        $Employee_Name =$rq_rw['Employee_Name'];
                                        $num++;
                                        echo "<tr>
                                            <td>$num</td>
                                            <td>$Date_of_request</td>
                                            <td>$Request_from</td>
                                            <td>$Employee_Name</td>
                                            <td>
                                            <div class='btn-group'>
                                                <span><a href='Preview_consultation_request_burn.php?Request_Consultation_ID=$Request_Consultation_ID&Registration_ID=$Registration_ID' class='btn btn-info btn-xs'>Preview</a>&nbsp;";
                                                if($name==$Request_from){
                                                    echo "<a href='Preview_consultation_request_burn.php?Request_Consultation_ID=$Request_Consultation_ID&Registration_ID=$Registration_ID' class='btn btn-primary btn-xs'>EDIT</a>";
                                                }
                                                echo "</span>
                                            </div>
                                            </td>
                                        </tr>";
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
    }

    if(isset($_POST['consultation_request_btn'])){
        ?>
    <div class="box box-primary" >
        <div class="box-body">
        <table class="table">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>REQUEST DATE</th>
                    <th>ORDERED BY</th>
                    <th>ORDERED TO</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $select_request = mysqli_query($conn, "SELECT Registration_ID,Request_Consultation_ID, Request_to,Request_from,Date_of_request, Employee_Name FROM tbl_request_for_consultation rc, tbl_employee e WHERE e.Employee_ID=Request_to") or die(mysqli_error($conn));
                    if(mysqli_num_rows($select_request)>0){
                        while($rq_rw = mysqli_fetch_assoc($select_request)){
                            $Request_Consultation_ID = $rq_rw['Request_Consultation_ID'];
                            $Registration_ID = $rq_rw['Registration_ID'];
                            $Request_to = $rq_rw['Request_to'];
                            $Request_from = $rq_rw['Request_from'];
                            $Date_of_request = $rq_rw['Date_of_request'];
                            $Employee_Name =$rq_rw['Employee_Name'];
                            $num++;
                            echo "<tr>
                                <td>$num</td>
                                <td>$Date_of_request</td>
                                <td>$Request_from</td>
                                <td>$Employee_Name</td>
                                <td>
                                <div class='btn-group'>
                                    <span><a href='Preview_consultation_request_burn.php?Request_Consultation_ID=$Request_Consultation_ID&Registration_ID=$Registration_ID' class='btn btn-info btn-xs'>Preview</a>&nbsp;";
                                    if($name==$Request_from){
                                        echo "<a href='Preview_consultation_request_burn.php?Request_Consultation_ID=$Request_Consultation_ID&Registration_ID=$Registration_ID' class='btn btn-primary btn-xs'>EDIT</a>";
                                    }
                                    echo "</span>
                                </div>
                                </td>
                            </tr>";
                        }
                    }
                ?>
            </tbody>
        </table>
        </div>
    </div>
        <?php
    }

    if(isset($_POST['displayAssessment'])){
        $Admision_ID = $_POST['Admision_ID'];
        $Registration_ID = $_POST['Registration_ID'];
        $select_assessment = mysqli_query($conn, "SELECT * FROM tbl_nurse_assessment WHERE Registration_ID='$Registration_ID' Order by Assessment_ID DESC LIMIT 1") or die(mysqli_error($conn));
        if((mysqli_num_rows($select_assessment))>0){
            while($assessm_rw = mysqli_fetch_assoc($select_assessment)){
                
                $Assessment_ID = $assessm_rw['Assessment_ID'];
               $significant_life_criss = $assessm_rw['significant_life_criss'];
               $current_health_status = $assessm_rw['current_health_status'];
               $status  = $assessm_rw['status'];
               $medication_information = $assessm_rw['medication_information'];
             
               $social_history =$assessm_rw['social_history'];
               $relatives = $assessm_rw['relatives'];
               $nursing_history = $assessm_rw['nursing_history'];
               ?>
   <div class="box box-primary">
           <div class="box-body">
           <div class="row">
                   <div class="col-md-6">

                   <label for="" >Significant of life criss</label>
                   <div >
                   <textarea name="" id="significant_life_criss" disabled rows="2" class="form-control"><?php echo $significant_life_criss; ?></textarea>
                   </div>
                   </div>
                   <div class="col-md-6">
                   <label for="" >Patient perception of current health status</label>
                   <div >
                   <textarea name="" id="current_health_status" disabled rows="2" class="form-control"><?php echo $current_health_status; ?></textarea>
                   </div>
                   </div>                   
               </div>

               <br>
               <div class="row">
                   <div class="col-md-6">
                        <label for="" >Status</label>
                        <div >
                            <textarea name="" id="status" rows="2" class="form-control" disabled value=""><?php echo $status; ?></textarea>
                        </div>
                   </div>
                   <div class="col-md-6">
                        <label for="" >Medication information</label>
                        <div >
                            <textarea name="" id="medication_information" rows="3"disabled class="form-control"><?php echo $medication_information; ?></textarea>
                        </div>

                   </div>
                     
                   
               </div> 
               <br>
               <div class="row">
                   <div class="col-md-6">
                        <label for="" >Social history</label>
                        <div >
                            <textarea name="" id="social_history" rows="2" disabled class="form-control"><?php echo $social_history; ?></textarea>
                        </div>
                   </div>
                   <div class="col-md-6">
                    <label for="" >Relatives</label>
                    <div >
                        <textarea name="" id="relatives" rows="2" disabled class="form-control"><?php echo $relatives;  ?></textarea>
                    </div>
                   </div>
               </div>
               <br>
               <div class="row">
                    <div class="col-md-2" style="padding: 2em; align-content:right;">
                       <button name="btn_assessment_info" type="button" onclick="add_assessment_information('<?php echo $Registration_ID;?>','<?php echo $Assessment_ID;?>')" class="art-button-green">+ ASSESSMENT INFO</button>
                   </div>
                   
                   <div class="col-md-10">
                       <label for="">Nursing history</label>
                       <textarea name="" id="nursing_history" disabled rows="2" class="form-control"><?php echo $nursing_history; ?></textarea>
                   </div>
                   
               </div>
           </div>
        </div>
   
           <?php }
        }else{
        ?>
        <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   <div class="col-md-6">

                   <label for="" >Significant of life criss</label>
                   <div >
                       <textarea name="" id="significant_life_criss" rows="2" class="form-control"></textarea>
                   </div>
                   </div>
                   <div class="col-md-6">
                   <label for="" >Patient perception of current health status</label>
                   <div >
                       <textarea name="" id="current_health_status" rows="2" class="form-control"></textarea>
                   </div>
                   </div>                   
               </div>
               <br>
               <div class="row">
                   <div class="col-md-6">
                        <label for="" >Status</label>
                        <div >
                            <textarea name="" id="status"  class="form-control"></textarea>
                        </div>
                   </div>
                   <div class="col-md-6">
                        <label for="">Medication information</label>
                        <div >
                            <textarea name="" id="medication_information" rows="2" class="form-control"></textarea>
                        </div>
                   </div>                  
                   
               </div>
               <br>
               <div class="row">
                   <div class="col-md-6">
                    <label for="" >Social history</label>
                    <div >
                        <textarea name="" id="social_history" rows="2" class="form-control"></textarea>
                    </div>
                   </div>
                   <div class="col-md-6">
                    <label for="" >Relatives</label>
                    <div class="">
                        <textarea name="" id="relatives" rows="2" class="form-control"></textarea>
                    </div>
                   </div>                   
               </div>
               <br>
               <div class="row">
                   <div class="col-md-8">
                        <label for="" >Nursing history</label>
                        <div >
                            <textarea name="" id="nursing_history" rows="2" class="form-control"></textarea>
                        </div>
                   </div>
                   
                   
                   <div class="col-md-2" style="padding: 2em; align-content:right;">
                       <label for=""></label>
                       <button name="btn_assessment" type="button" onclick="save_patient_assessment('<?php echo $Registration_ID;?>','<?php echo $Admision_ID; ?>')" class="art-button-green">Save assessment</button>
                   </div>
               </div>
           </div>
        </div>
        <?php } 
    }


    if(isset($_POST['btn_assessment_info'])){
        $Assessment_ID = $_POST['Assessment_ID'];
        $Registration_ID = $_POST['Registration_ID'];
        ?>
        <style>
        
        th.rotate {
            /* Something you can count on */
                height: 140px;
            }

            th.rotate {
            transform: 
                /* Magic Numbers */
                translate(0px, 5px)
                /* 45 is really 360 - 45 */
                rotate(145);
            }
            th.rotate {
            border-bottom:  1px solid #ccc;
            
            }
            
            </style>
            <style media="screen">
                table tr,td{
                    border:none !important;
                }

                .input{
                    width:30% !important;
                }

                .label-input{
                    width: 0% !important ;
                    text-align: right !important;

                }
                </style>
        <input type="text" style="display: none;" id="Registration_ID" value="<?php echo $Registration_ID;?>">
        <input type="text" style="display: none;" id="Assessment_ID" value="<?php echo $Assessment_ID;?>">
        <div class="boxs" style="border-bottom-color: blue">
            <div class="box-body">
                <!-- ASSESSMENT DATA KEY
                <div class="row">
                    Urgent/servere problem  &nbsp;&nbsp;<input type="checkbox" checked='checked'>&nbsp;&nbsp; Problem present
                </div>
                <div class="row" style="display: -webkit-box;">
                    ** Potential problem  &nbsp;&nbsp; <input type="checkbox" disabled class="checkbox"> &nbsp;&nbsp;No problem present
                </div> -->
                <table  width="100%">
                    <tr>
                        <th colspan="3" class="text-center">ASSESSMENT DATA KEY</th>
                    </tr>
                    <tr>
                        <td width="49%">Urgent/servere problem</td>
                        <td width="5%"><input type="checkbox" checked='checked'></td>
                        <td width="49%">Problem present</td>
                    </tr>
                    <tr>
                        <td width="49%">Potential problem</td>
                        <td width="2%"><input type="checkbox" disabled class="checkbox"></td>
                        <td width="49%">No problem present</td>
                    </tr>
                </table>
            </div>
            
        </div>  
        <div style="height: 400px;overflow: auto">
                <table class="table table-responsive table-bordered table-hover" >
                    <thead>
                        <tr>
                            <th>SAVED BY</th>
                            <th class="rotate">Date</th>
                            <th class="rotate" >Airway</th>
                            <th class="rotate" >Breathing</th>
                            <th class="rotate" >Circulation</th>
                            <th class="rotate" >Level of consciousness</th>
                            <th class="rotate" >Pain</th>
                            <th class="rotate" >Fluid intake</th>
                            <th class="rotate" >Fluid_output</th>
                            <th class="rotate" >Fluid balance</th>
                            <th class="rotate" >Body temperature</th>
                            <th class="rotate" >Food/nutrition/electrolytes</th>
                            <th class="rotate" >Elimination</th>
                            <th class="rotate" >Haygien-body</th>
                            <th class="rotate" >Haygiene - oral eyes</th>
                            <th class="rotate" >Wound(s)</th>
                            <th class="rotate" >Risk of pressure sore</th>
                            <th class="rotate" >Drains</th>
                            <th class="rotate" >Exercise</th>
                            <th class="rotate" >Social well being</th>
                            <th class="rotate" >Psychological well being</th>
                            <th class="rotate" >spiritual well being</th>
                            <th class="rotate" >Environment/ equipment/staff</th>
                            <th class="rotate" >Information /  education</th>
                            <th class="rotate" >Test</th>
                            <th class="rotate" >Treatment</th>
                            <th class="rotate" >Payment</th>
                            <th class="rotate" >rest / Sleep</th>
                            <th class="rotate" >Creative activities</th>
                            <th class="rotate" >Other Problems</th>
                            <th class="rotate" >Action</th>
                        </tr>
                    </thead>
                    <tbody id="infobody">
                        
                    </tbody>
                </table>
        </div>
            <!-- </div>
        </div> -->

        <?php
    }