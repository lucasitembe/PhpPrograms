<?php
include("./includes/header.php");
include("./includes/connection.php");
include './includes/cleaninput.php';
session_start();

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if(isset($_GET['cancer_type_id'])){
    $cancer_ID = $_GET['cancer_type_id'];
}

$select_protocal = mysqli_query($conn, "SELECT cancer_type_id, date_time,Cancer_Name, Employee_Name FROM tbl_cancer_type ct, tbl_employee e WHERE added_by=Employee_ID AND cancer_type_id='$cancer_ID' ") or die(mysqli_error($conn));
?>
<a href='manage_cancer_protocal.php' class='art-button-green'> BACK </a>
<br>
<style>
    .remove{
        background:#ff8378; 
        width:60px;
         height:25px; 
         border-radius:10px;
    }
</style>
<?php
     $select_diagnosis=mysqli_query($conn,"SELECT Cancer_Name FROM tbl_cancer_type WHERE cancer_type_id='$cancer_ID'") or die(mysqli_error($conn));
     while($rowss=mysqli_fetch_assoc($select_diagnosis)){             
             $Cancer_Name =$rowss['Cancer_Name'];
     }
     echo   " <br>
            <div class='box box-primary' style='width:100%;'>
                        <div class='box-header'>
                            <div class='col-md-offset-3 col-md-12'>
                            <input class='form-control' value='$Cancer_Name' readonly='readonly' style='width:60%; text-align:center;' id='Protocal_name'>
                            </div>
                        </div>
                        
                        <div class='box-body'>
                            <table class='table' id='colum-addition'>
                           
                                   <tr width='50px;'>
                     
                        <td width='40%'>
                            <div class='title' style='text-align:center;'><b>Chemo Treatment</b></div>
                        </td>  
                        <td width='20%'>
                            <div class='title' style='text-align:center;'><b>Chemo Strength (mg/m²)</b></div>
                        </td>                     
                        <td width='30%'>
                            <div class='title' style='text-align:center;'><b>Standard Duration</b></div>
                        </td>  
                        <th>Status</th>                      
                       
                        <td width='5%'>
                             <input type='button'class='art-button-green' id='addrow1' value='Add'>
                        </td>
                    </tr>";
                    
                    
                        $adjNo=0;
                      $sql_data_cancer = mysqli_query($conn,"SELECT adjuvant,adjuvantstrenth, duration,date_and_time,adjuvant_ID, Status FROM  tbl_adjuvant_duration WHERE cancer_type_id='$cancer_ID'");
                                if(mysqli_num_rows($sql_data_cancer)>0){
                                    while($values = mysqli_fetch_assoc($sql_data_cancer)){                                      
                                      $adjuvant   =$values['adjuvant'];
                                      $duration   =$values['duration'];
                                      $adjuvant_ID=$values['adjuvant_ID'];
                                      $adjuvantstrenth = $values['adjuvantstrenth'];
                                      $Status = $values['Status'];
                                      $adjNo++;
                                      echo "     <tr>
                                        <input type='text' name='adjuvant_ID[]' id='adjuvant_ID[]'  style='width:100%;display:none;' value='$adjuvant_ID'/>  
                                        <td>
                                        <input type='text' name='adjuvantname[]' id='adjuvantname[]' autocomplete='off' style='width:100%;display:inline;height:30px; text-align:center' value='$adjuvant'/>  
                                        </td> 
                                        <td>
                                        <input type='text' name='adjuvantstrenth[]' id='adjuvantstrenth_$adjNo' autocomplete='off' style='width:100%;display:inline;height:30px; text-align:center'  onkeyup='dosestreangth($adjNo)' value='$adjuvantstrenth' />  
                                        </td>                       
                                        <td>
                                        <input type='text' name='duration[]' id='duration[]' autocomplete='off' style='width:100%;display:inline;height:30px; text-align:center' value='$duration'/> 
                                        </td>
                                        <td> 
                                            <select onchange='update_item_status($adjuvant_ID)' id='Status_$adjuvant_ID'>
                                                <option selected='selected'>$Status</option>
                                                <option value='Enabled'>Enabled</option>
                                                <option value='Disabled'>Disabled</option>
                                            </select>
                                        </td>
                                        <td>";
                                            $select_adjuvantID = mysqli_query($conn, "SELECT adjuvant_ID FROM tbl_patient_adjuvant_duration WHERE adjuvant_ID = '$adjuvant_ID'") or die(mysqli_error($conn));
                                            if(mysqli_num_rows($select_adjuvantID) ==0){
                                                echo "<input type='button' class='remove'  value='DELETE' onclick='delete_adjuvant($adjuvant_ID)'/>";
                                            }
                                         
                                        echo "</td>
                                    </tr> 
                                    <tr id='colum-addition'> 
                                    </tr> ";
                                    }
                              }else{
//                                  echo "empty data to diplay";
                              }
                    
               
                     echo "
                         
                  <tr>
                       <table class='table' style='background-color: white; width:100%' >
                      
                    <tr>                      
                        <td>
                            <div class='title' style='text-align:center;padding:15px;'><b>PRE HYDREATION  </b><br/>Physician to circle one in each column</div>
                        </td>
                    </tr>
                    <tr>
                       
                        <td width='50%'>
                            <table class='table' id='row-addition'>
                                <tr>
                                    <td>Name</td>
                                    <td>Volume</td>
                                    <td>Type</td>
                                    <td>Minutes</td>
                                    <th>Status</th>                                    
                                    <td width='5%'> <input type='button'class='art-button-green' id='addrow_one' style='' value='Add'></td>
                                </tr>";
                                
                                              
                    
                      $sql_data_cancer_duration = mysqli_query($conn,"SELECT physician_volume,Status,Physician_Item_name, physician_type,physician_minutes,date_and_time,physician_ID FROM tbl_physician WHERE cancer_type_id='$cancer_ID'") or die(mysqli_error($conn));
                              if(mysqli_num_rows($sql_data_cancer_duration)>0){
                                  while($values = mysqli_fetch_assoc($sql_data_cancer_duration)){
                                      
                                      $physician_volume=$values['physician_volume'];
                                      $physician_minutes=$values['physician_minutes'];
                                      $physician_type=$values['physician_type'];
                                      $physician_ID=$values['physician_ID'];
                                      $Status = $values['Status'];
                                      $Physician_Item_name =$values['Physician_Item_name'];
                                      echo "<tr>
                                            <td><input type='text' name='Physician_Item_name[]'  autocomplete='off'style='width:100%;display:inline;height:30px;text-align:center' readonly value='$Physician_Item_name'/> </td>
                                            <td>
                                            <input type='text' name='physician_ID[]' id='physician_ID[]' autocomplete='off' style='width:100%;display:none;' value='$physician_ID'/> 
                                            <input type='text' name='physician_volume[]' id='physician_volume[]' autocomplete='off' style='width:100%;display:inline;height:30px;text-align:center' value='$physician_volume'/>  
                                            </td>
                                            <td>
                                            <input type='text' name='physician_type[]' id='physician_type[]' autocomplete='off'style='width:100%;display:inline;height:30px;text-align:center' value='$physician_type'/> 
                                            </td>
                                            <td>
                                            <input type='text' name='physician_minutes[]' id='physician_minutes[]' autocomplete='off' style='width:100%;display:inline;height:30px;text-align:center' value='$physician_minutes'/> 
                                            </td>
                                            <td> 
                                                <select onchange='update_physician_status($physician_ID)' id='Status_$physician_ID'>
                                                    <option selected='selected'>$Status</option>
                                                    <option value='Enabled'>Enabled</option>
                                                    <option value='Disabled'>Disabled</option>
                                                </select>
                                            </td>
                                            <td>";
                                                echo "<input type='button' class='remove'  value='DELETE' onclick='delete_physician($physician_ID)'/>";
                                            
                                         
                                        echo "</td>
                                        </tr> ";
                                  }
                              }else{
//                                  echo "empty data to diplay";
                              }
                  
                              echo "";
                       echo "</table>
                           <input type='hidden' id='rowCount' value='1'>
                        </td>
                    </tr>
               
                    </table>
                  </tr>
                    </td>
                </tr>
                
                <tr style='margin-top:30px;'>
              
                    <td>
                    <div class='title' style='text-align:center;padding:15px;'><b>SUPPORTIVE TREATMENT</b></div>
                <table class='table' style='background-color: white;' id='colum-addition_supportive'>
                    <tr>
                    <th>SN</th>
                    <th width='30%'>Supportive treatment</th>
                    <th width='8%'>Dose(mg)</th>
                    <th width='6%'>Route</th>
                    <th  width='8%'>Administration Time</th>
                    <th width='8%'>Frequence</th>
                    <th  width='20%'>Medication Instructions and Indications</th>
                    <th>Status</th>
                    
                     <th width='5%'>
                          <input type='button'class='art-button-green' id='addrow2' value='Add'>
                        </th>
                    </tr>";
                    
                                                                
                     $count=0;
                      $sql_data_cancer_supportive = mysqli_query($conn,"SELECT Status, supportive_treatment,Dose,Route,Administration_Time,Frequence,Medication_Instructions,treatment_ID FROM  tbl_supportive_treatment WHERE cancer_type_id='$cancer_ID' ") or die(mysqli_error($conn));
                              if(mysqli_num_rows($sql_data_cancer_supportive)>0){
                                  while($valuesv = mysqli_fetch_assoc($sql_data_cancer_supportive)){
                                      $count++;
                                      $Status = $valuesv['Status'];
                                      $supportive_treatment=$valuesv['supportive_treatment'];
                                      $Dose=$valuesv['Dose'];
                                      $Medication_Instructions=$valuesv['Medication_Instructions'];
                                      $Route=$valuesv['Route'];
                                      $Administration_Time=$valuesv['Administration_Time'];
                                      $Frequence=$valuesv['Frequence'];
                                      $treatment_ID=$valuesv['treatment_ID'];
                                    //  $Product_Name = $valuesv['Product_Name'];
                                      echo "<tr><td>
                    <center>$count</center>  
                        </td>
                        <td>
                        <input type='text' name='treatment_ID[]' id='treatment_ID[]' autocomplete='off' style='display:none;height:30px;' value='$treatment_ID'/>
                           <input type='text' name='item[]' id='item[]' autocomplete='off' style='width:100%;display:inline;height:30px;' value='$supportive_treatment'/>
                        </td>
                        <td>
                            
                             <input type='text' name='treat_dose[]' id='treat_dose[]' autocomplete='off' style='width:100%;display:inline;height:30px;' value='$Dose'/>
                        </td>
                        <td>
                            <input type='text' name='treat_route[]' id='treat_route[]' autocomplete='off' style='width:100%;display:inline;height:30px;' value='$Route'/>
                        </td>
                          <td>
                           <input type='text' name='treat_admin[]' id='treat_admin[]' autocomplete='off' style='width:100%;display:inline;height:30px;' value='$Administration_Time'/>
                        </td>
                          <td>
                           <input type='text' name='treat_frequence[]' id='treat_frequence[]' autocomplete='off' style='width:100%;display:inline;height:30px;' value='$Frequence'/>
                        </td>
                          <td>
                           <input type='text'  name='treat_medication[]' id='treat_medication[]' autocomplete='off' style='width:100%;display:inline;height:30px;' value='$Medication_Instructions'/>
                        </td>
                        <td> 
                            <select onchange='update_treatment_status($treatment_ID)' id='Status_$treatment_ID'>
                                <option selected='selected'>$Status</option>
                                <option value='Enabled'>Enabled</option>
                                <option value='Disabled'>Disabled</option>
                            </select>
                        </td>
                           <td>";
                           $select_adjuvantID = mysqli_query($conn, "SELECT treatment_ID FROM tbl_patient_supportive_treatment WHERE treatment_ID = '$treatment_ID'") or die(mysqli_error($conn));
                           if(mysqli_num_rows($select_adjuvantID) ==0){
                               echo "<input type='button' class='remove'  value='DELETE' onclick='delete_treatment($treatment_ID)'/>";
                           }
                        
                       echo " 
                        </td>
                        </tr>
                        <tr id='colum-addition_supportive'> </tr>";
                                  }
                              }else{
//                                  echo "empty data to diplay";
                              }
                      
           
               echo "</table>
                    </td>
                </tr>


               
            <tr style='margin-top:30px;'>
                <td>
                
                <div class='title' style='text-align:center; padding:15px;'><b>CHEMOTHERAPY DRUG</b></div>
                <table class='table' style='background-color: white;' id='colum-addition_chemotherapy'>
                    <tr>
                    <th width='4%'>SN</th>
                    <th width='45%'>Chemotherapy Drug</th>
                    <th width='7%'>Volume(mls)</th>
                    <th  width='6%'>Route</th>
                    <th width='8%'>Admin Time</th>
                    <th  width='30%'>Frequency</th>
                    <th>Status</th>
                    <th  width='5%'><input type='button'  value='ADD' class='art-button-green' id='addrow3'  ></th>
                    </tr>";
                    //exclude as the streangth
                   // <th width='6%'>Volume(ml)</th>
                                                                
                     $count=0;
                   
                      $sql_data_cancer_drug = mysqli_query($conn,"SELECT Status,chemotherapy_ID,Chemotherapy_Drug,Dose,Volume,Route,Admin_Time,Frequency,chemotherapy_ID FROM tbl_chemotherapy_drug  WHERE cancer_type_id='$cancer_ID' ") or die(mysqli_error($conn));
                              if(mysqli_num_rows($sql_data_cancer_drug)>0){
                                  while($values = mysqli_fetch_assoc($sql_data_cancer_drug)){ 
                                      $count++;
                                        $Status = $values['Status'];
                                      $Chemotherapy_Drug=$values['Chemotherapy_Drug'];
                                      $Dose=$values['Dose'];
                                      $Volume=$values['Volume'];
                                      $Route=$values['Route'];
                                      $Admin_Time=$values['Admin_Time'];
                                      $Frequency=$values['Frequency'];
                                      $chemotherapy_ID=$values['chemotherapy_ID'];
                                    //  $Product_Name = $values['Product_Name'];
                                    //AND Chemotherapy_Drug=i.item_id
                                      echo "<tr><td>
                    <center>$count</center>  
                        </td>
                        <td>
                        <input type='text' name='chemotherapy_ID[]'  value='$chemotherapy_ID'  style='display:none;' />
                           <input type='text' name='item[]'  id='item_c[]' autocomplete='off' style='width:100%;display:inline;height:30px;' value='$Chemotherapy_Drug ($Volume)'/>
                        </td>
                        <td>
                             <input type='text' name='chemoVolume_c[]' class='dose_c[]' id='dose".$chemotherapy_ID."' autocomplete='off' style='width:100%;display:inline;height:30px;text-align:center;'  value='$Volume'/>
                        </td>
                       
                        <td>
                            <input type='text' name='chemoroute_c[]' id='route_c[]' autocomplete='off' style='width:100%;display:inline;height:30px;text-align:center;' value='$Route'/>
                        </td>
                          <td>
                           <input type='text' name='chemoadmin_c[]' id='admin_c[]' autocomplete='off' style='width:100%;display:inline;height:30px;text-align:center;' value='$Admin_Time'/>
                        </td>
                         
                          <td>
                           <input type='text' name='chemofrequence_c[]'  id='frequence_c[]' autocomplete='off' style='width:100%;display:inline;height:30px;text-align:center;' value='$Frequency'/>
                        </td>
                        <td> 
                            <select  id='Status_$chemotherapy_ID' onchange='update_chemotherapy_status($chemotherapy_ID)'>
                                <option selected='selected'>$Status</option>
                                <option value='Enabled'>Enabled</option>
                                <option value='Disabled'>Disabled</option>
                            </select>
                        </td>
                          <td>";
                          $select_chemo = mysqli_query($conn, "SELECT chemotherapy_ID FROM tbl_patient_chemotherapy_drug WHERE chemotherapy_ID='$chemotherapy_ID'") or die(mysqli_error($conn));
                          if(mysqli_num_rows($select_chemo)<1){
                           echo "<input type='button' class='remove'  value='Delete' onclick='delete_chemotherapy($chemotherapy_ID)'/>";
                          }
                      echo "  </td>
                </tr> 
                
                ";
                echo "<tr id='colum-addition_chemotherapy'></tr>";
                }
                
            }
                      
           
               echo "</table>
                    </td> 
                </tr>
                
                </table>
                </br>
                </br>
                <div align='right'> 
                    <a class='art-button-green' href='#'  onclick='update_protocal_details($cancer_ID)'>UPDATE  PROTOCAL</a>
                </div> 
            </div>
        </div>";
               
        ?>
<script>
            $('#addrow1').click(function () {                    
            $.ajax({
                    type: 'POST',
                    url: 'Cancer_pharmacy_items.php',
                    data: {addrow1:''},
                    success: function (result) {
                var rowCount = parseInt($('#rowCount').val()) + 1;
                var newRow = "<tr class='addnewrow tr" + rowCount + "'><td>"+result+"</td><td><input style='width:100%' type='text' name='adjuvantstreanthvl[]' placeholder='streangth'></td><td><input name='durationssss[]'  type='text' class='adjuvant' id='" + rowCount + " ' style='width:100%'><td colspan='2'><input type='button' class='remove' row_id='" + rowCount + "' value='x'></td> </tr>";
                $('#colum-addition').append(newRow);
                document.getElementById('rowCount').value = rowCount;
                $("select").select2();
                    }
                });        
            });
            $('#add_adjuvant').click(function () {
                var cancer_ID ='<?=$cancer_ID?>'; 
                var Item_ID =$("#Item_ID").val();
                alert(Item_ID)
            })

            $('#addrow2').click(function () {
            
                $.ajax({
                        type: 'POST',
                        url: 'Cancer_pharmacy_items.php',
                        data: {addrow2:''},
                        success: function (result) {
                    var rowCount = parseInt($('#rowCount').val()) + 1;
                    var newRow = "<tr class='addnewrow tr" + rowCount + "'><td></td><td>"+result+"</td><td><input type='text' name='dosetreat[]' id='" + rowCount + " ' autocomplete='off' style='width:100%;display:inline;height:30px;'/></td><td><input type='text' name='treatroute[]'  id='" + rowCount + " ' autocomplete='off' style='width:100%;display:inline;height:30px;'/></td><td><input type='text' name='treatadmin[]' id='admin[]' autocomplete='off' style='width:100%;display:inline;height:30px;'/></td><td>  <input type='text' name='treatfrequence[]' id='" + rowCount + " ' autocomplete='off' style='width:100%;display:inline;height:30px;'/> </td><td>  <input type='text'  name='treatmedication[]' id='medication[]' autocomplete='off' style='width:100%;display:inline;height:30px;'/>  </td><td><input type='button' class='remove' row_id='" + rowCount + "' value='x'></td> </tr>";
                    $('#colum-addition_supportive').append(newRow);
                    document.getElementById('rowCount').value = rowCount;
                    $("select").select2();
                        }
                    });
                
             });
             $('#addrow3').click(function () {
            
            $.ajax({
                    type: 'POST',
                    url: 'Cancer_pharmacy_items.php',
                    data: {addrow3:''},
                    success: function (result) {
                var rowCount = parseInt($('#rowCount').val()) + 1;
                var newRow = "<tr class='addnewrow tr" + rowCount + "'><td></td><td>"+result+"</td><td><input type='text' name='dvolume[]' placeholder='volume' id='" + rowCount + " ' autocomplete='off' style='width:100%;display:inline;height:30px;'/></td><td><input type='text' name='chemoroute[]'  id='" + rowCount + " ' autocomplete='off' style='width:100%;display:inline;height:30px;'/></td><td><input type='text' name='chemoadmin[]' id='admin[]' autocomplete='off' style='width:100%;display:inline;height:30px;'/></td><td>  <input type='text' name='chemofrequence[]' id='" + rowCount + " ' autocomplete='off' style='width:100%;display:inline;height:30px;'/> </td><td>  <input type='text'  name='adminmedication[]' id='medication[]' autocomplete='off' style='width:100%;display:inline;height:30px;'/>  </td><td><input type='button' class='remove' row_id='" + rowCount + "' value='x'></td> </tr>";
                $('#colum-addition_chemotherapy').append(newRow);
                document.getElementById('rowCount').value = rowCount;
                $("select").select2();
                    }
                });
            
         });


//addition_chemotherapy
    $('#addrow_one').click(function () {
        $.ajax({
            type: 'POST',
            url: 'Cancer_pharmacy_items.php',
            data: {addrow_one:''},
            success: function (result){
                var rowCount = parseInt($('#rowCount').val()) + 1;
                var newRow = "<tr class='addnewrow tr" + rowCount + "'><td>"+result+"</td><td><input name='hydrationvolume[]' class='txtbox' type='text' class='duration' id='" + rowCount + " ' style='width:100%'></td><td><input name='hydrationtype[]' class='txtbox' type='text' class='duration' id='" + rowCount + " ' style='width:100%'></td><td><input name='hydrationminute[]' class='txtbox' type='text' class='duration' id='" + rowCount + " ' style='width:100%'></td><td><input type='button' class='remove' row_id='" + rowCount + "' value='x'></td></tr>";
                $('#row-addition').append(newRow);
                document.getElementById('rowCount').value = rowCount;
                $("select").select2();
            }
        })
    });
             
                
    $(document).on('click', '.remove', function () {
        var id = $(this).attr('row_id');
        //alert(id);
        $('.tr' + id).remove().fadeOut();
    });

    function delete_adjuvant(adjuvant_ID){
        
        if(confirm("Are you sure you want to delete?")){
            $.ajax({ 
                type:'POST', 
                url:'Ajax_update_cancer_protocal.php',
                data:{adjuvant_ID:adjuvant_ID, deleteadjuvant:''},
                    success:function(responce){ 
                        if(responce=='Deleted successful'){
                            location.reload()
                        }else{
                            alert("Something is wrong Try again"); 
                        }
                }
            });
        }
    }
    function delete_treatment(treatment_ID){
        if(confirm("Are you sure you want to delete?")){
            $.ajax({ 
                type:'POST', 
                url:'Ajax_update_cancer_protocal.php',
                data:{treatment_ID:treatment_ID, deletetreatment:''},
                    success:function(responce){ 
                        if(responce=='Deleted successful'){
                            location.reload()
                        }else{
                            alert("Something is wrong Try again"); 
                        }
                }
            });
        } 
    }

    function delete_chemotherapy(chemotherapy_ID){
        if(confirm("Are you sure you want to delete?")){
            $.ajax({ 
                type:'POST', 
                url:'Ajax_update_cancer_protocal.php',
                data:{chemotherapy_ID:chemotherapy_ID, deletechemotherapy_ID:''},
                    success:function(responce){ 
                        if(responce=='Deleted successful'){
                            location.reload()
                        }else{
                            alert("Something is wrong Try again"); 
                        }
                }
            });
        } 
    }
    function update_protocal_details(cancer_ID){        
        var Protocal_name = $("#Protocal_name").val();
        var adjuvantstreanthvl=[];
        var doses = document.getElementsByName('adjuvantstreanthvl[]');
            for (var i = 0; i <doses.length; i++) {
            var inp=doses[i];
            adjuvantstreanthvl.push(inp.value);
            }
        var duration=[];
        var cyclenum = document.getElementsByName('durationssss[]');
            for (var i = 0; i <cyclenum.length; i++) {
            var inp=cyclenum[i];
            duration.push(inp.value); 
            }
        var Items=[];
        var itemname = document.getElementsByName('Item_IDs[]');
            for (var i = 0; i <itemname.length; i++) {
            var inp=itemname[i];
            Items.push(inp.value);
            }
        var Itemss=[];
        var itemname = document.getElementsByName('Item_IDss[]');
            for (var i = 0; i <itemname.length; i++) {
            var inp=itemname[i];
            Itemss.push(inp.value);
            }
            var Hydrationvolume=[];
        var volumes = document.getElementsByName('hydrationvolume[]');
            for (var i = 0; i <volumes.length; i++) {
            var inp=volumes[i];
            Hydrationvolume.push(inp.value);
            }
        var Hydrationtype=[];
        var types = document.getElementsByName('hydrationtype[]');
            for (var i = 0; i <types.length; i++) {
            var inp=types[i];
            Hydrationtype.push(inp.value);
            }
        var Hydrationminutes=[];
        var minutes = document.getElementsByName('hydrationminute[]');
            for (var i = 0; i <minutes.length; i++) {
            var inp=minutes[i];
            Hydrationminutes.push(inp.value);
            }

            //Volume update
            var physician_ID=[];
        var physicianID = document.getElementsByName('physician_ID[]');
            for (var i = 0; i <physicianID.length; i++) {
            var inp=physicianID[i];
            physician_ID.push(inp.value);
            }
            var physician_volume=[];
        var physicianvolume = document.getElementsByName('physician_volume[]');
            for (var i = 0; i <physicianvolume.length; i++) {
            var inp=physicianvolume[i];
            physician_volume.push(inp.value);
            }
        var physician_type=[];
        var types = document.getElementsByName('physician_type[]');
            for (var i = 0; i <types.length; i++) {
            var inp=types[i];
            physician_type.push(inp.value);
            }
        var physician_minutes=[];
        var minutes = document.getElementsByName('physician_minutes[]');
            for (var i = 0; i <minutes.length; i++) {
            var inp=minutes[i];
            physician_minutes.push(inp.value);
            }
        var Item_ID_physician=[];
        var itemnamesp = document.getElementsByName('Item_ID_physician[]');
            for (var i = 0; i <itemnamesp.length; i++) {
            var inp=itemnamesp[i];
            Item_ID_physician.push(inp.value);
            }
        var Itemsss=[];
        var itemnames = document.getElementsByName('Item_IDsss[]');
            for (var i = 0; i <itemnames.length; i++) {
            var inp=itemnames[i];
            Itemsss.push(inp.value);
            }
        var Treatment=[];
        var tratdose = document.getElementsByName('dosetreat[]');
            for (var i = 0; i <tratdose.length; i++) {
            var inp=tratdose[i];
            Treatment.push(inp.value);
            }
        var routTreatment=[];
        var tratroute = document.getElementsByName('treatroute[]');
            for (var i = 0; i <tratroute.length; i++) {
            var inp=tratroute[i];
            routTreatment.push(inp.value);
            }
       
        var admintTreatment=[];
        var tratadmin = document.getElementsByName('treatadmin[]');
            for (var i = 0; i <tratadmin.length; i++) {
            var inp=tratadmin[i];
            admintTreatment.push(inp.value);
            }
        var frequanceTreatment=[];
        var tratfrequence = document.getElementsByName('treatfrequence[]');
            for (var i = 0; i <tratfrequence.length; i++) {
            var inp=tratfrequence[i];
            frequanceTreatment.push(inp.value);
            }
        var medicationTreatment=[];
        var tratmedication = document.getElementsByName('treatmedication[]');
            for (var i = 0; i <tratmedication.length; i++) {
            var inp=tratmedication[i];
            medicationTreatment.push(inp.value);
            }
        //adjuvantstrenth
        var adjuvantstrenth=[];
        var adjuvant = document.getElementsByName('adjuvantstrenth[]');
            for (var i = 0; i <adjuvant.length; i++) {
            var inp=adjuvant[i];
            adjuvantstrenth.push(inp.value);
            }
        var adjuvant_ID=[];
        var adjuvantid = document.getElementsByName('adjuvant_ID[]');
            for (var i = 0; i <adjuvantid.length; i++) {
            var inp=adjuvantid[i];
            adjuvant_ID.push(inp.value);
            }
        var duration=[];
        var duratn = document.getElementsByName('duration[]');
            for (var i = 0; i <duratn.length; i++) {
            var inp=duratn[i];
            duration.push(inp.value);
            }

        var adjuvantname=[];
        var name = document.getElementsByName('adjuvantname[]');
            for (var i = 0; i <name.length; i++) {
            var inp=name[i];
            adjuvantname.push(inp.value);
            }
            
            
            //Treatment supportive 
            var treatment_ID=[];
        var tretID = document.getElementsByName('treatment_ID[]');
            for (var i = 0; i <tretID.length; i++) {
            var inp=tretID[i];
            treatment_ID.push(inp.value);
            }
        var treat_route=[];
        var tretroute = document.getElementsByName('treat_route[]');
            for (var i = 0; i <tretroute.length; i++) {
            var inp=tretroute[i];
            treat_route.push(inp.value);
            }
        var admint_Treatment=[];
        var tretadmin = document.getElementsByName('treat_admin[]');
            for (var i = 0; i <tretadmin.length; i++) {
            var inp=tretadmin[i];
            admint_Treatment.push(inp.value);
            }
        var frequance_Treatment=[];
        var tretfrequence = document.getElementsByName('treat_frequence[]');
            for (var i = 0; i <tretfrequence.length; i++) {
            var inp=tretfrequence[i];
            frequance_Treatment.push(inp.value);
            }
        
            
        var medication_Treatment=[];
        var tretmedication = document.getElementsByName('treat_medication[]');
            for (var i = 0; i <tretmedication.length; i++) {
            var inp=tretmedication[i];
            medication_Treatment.push(inp.value);
            }

        var treat_dose=[];
        var tretdose = document.getElementsByName('treat_dose[]');
            for (var i = 0; i <tretdose.length; i++) {
                var inp=tretdose[i];
                treat_dose.push(inp.value);
            }
            // alert(treat_dose);exit;
        //end of supportive
        var chemotherapy_ID=[];
        var chemoID = document.getElementsByName('chemotherapy_ID[]');
        for (var i = 0; i <chemoID.length; i++) {
        var inp=chemoID[i];
        chemotherapy_ID.push(inp.value); 
        }

        var Chemotherapydose=[];
        var chemodose = document.getElementsByName('dosechemo[]');
            for (var i = 0; i <chemodose.length; i++) {
            var inp=chemodose[i];
            Chemotherapydose.push(inp.value); 
            }
        // var chemodose_c=[];
        // var chemodoseC = document.getElementsByName('chemodose_c[]');
        //     for (var i = 0; i <chemodoseC.length; i++) {
        //     var inp=chemodoseC[i];
        //     chemodose_c.push(inp.value); 
        //     }

        var chemoroutes=[];
        var chemoroute = document.getElementsByName('chemoroute[]');
            for (var i = 0; i <chemoroute.length; i++) {
            var inp=chemoroute[i];
            chemoroutes.push(inp.value);
            }
        var chemoroute_c=[];
        var croute = document.getElementsByName('chemoroute_c[]');
            for (var i = 0; i <croute.length; i++) {
            var inp=croute[i];
            chemoroute_c.push(inp.value);
            }

        var Chemotherapyadmin=[];
        var chemoadmin = document.getElementsByName('chemoadmin[]');
            for (var i = 0; i <chemoadmin.length; i++) {
            var inp=chemoadmin[i];
            Chemotherapyadmin.push(inp.value);
            }

        var chemoadmin_c=[];
        var admin_c = document.getElementsByName('chemoadmin_c[]');
            for (var i = 0; i <admin_c.length; i++) {
            var inp=admin_c[i];
            chemoadmin_c.push(inp.value);
            }
        var Chemomedication=[];
        var chemomed = document.getElementsByName('adminmedication[]');
            for (var i = 0; i <chemomed.length; i++) {
            var inp=chemomed[i];
            Chemomedication.push(inp.value);
            }
        var chemofrequence=[];
        var chemofrq = document.getElementsByName('chemofrequence[]');
            for (var i = 0; i <chemofrq.length; i++) {
            var inp=chemofrq[i];
            chemofrequence.push(inp.value);
            }

        var chemofrequence_c=[];
        var chemoF = document.getElementsByName('chemofrequence_c[]');
            for (var i = 0; i <chemoF.length; i++) {
            var inp=chemoF[i];
            chemofrequence_c.push(inp.value);
            } 
        var dvolume=[];  
        var chemovol = document.getElementsByName('dvolume[]');
            for (var i = 0; i <chemovol.length; i++) {
            var inp=chemovol[i];
            dvolume.push(inp.value);
            } 

            var chemoVolume_c=[];  
        var chemovol_c = document.getElementsByName('chemoVolume_c[]');
            for (var i = 0; i <chemovol_c.length; i++) {
            var inp=chemovol_c[i];
            chemoVolume_c.push(inp.value);
            } 
            // alert(Item_ID_physician); exit;
        $.ajax({ 
           type:'POST', 
           url:'Ajax_update_cancer_protocal.php',
           data:{cancer_ID:cancer_ID,Hydrationvolume:Hydrationvolume,Hydrationtype:Hydrationtype,dvolume:dvolume,Hydrationminutes:Hydrationminutes,duration:duration,Items:Items,Itemss:Itemss,Itemsss:Itemsss,Item_ID_physician:Item_ID_physician, Treatment:Treatment,Protocal_name:Protocal_name, routTreatment:routTreatment, admintTreatment:admintTreatment,frequanceTreatment:frequanceTreatment,medicationTreatment:medicationTreatment,Chemotherapydose:Chemotherapydose,chemoroutes:chemoroutes, Chemotherapyadmin:Chemotherapyadmin,Chemomedication:Chemomedication,chemofrequence:chemofrequence,adjuvantstreanthvl:adjuvantstreanthvl,chemoVolume_c:chemoVolume_c,chemofrequence_c:chemofrequence_c, chemoadmin_c:chemoadmin_c,chemoroute_c:chemoroute_c,chemotherapy_ID:chemotherapy_ID,treat_route:treat_route,
            admint_Treatment:admint_Treatment,frequance_Treatment:frequance_Treatment, medication_Treatment:medication_Treatment,treatment_ID:treatment_ID,treat_dose:treat_dose,adjuvantname:adjuvantname,adjuvant_ID:adjuvant_ID,duration:duration,adjuvantstrenth:adjuvantstrenth,physician_ID:physician_ID,physician_type:physician_type,physician_minutes:physician_minutes,physician_volume:physician_volume,
            
            update_field_added:'' },
            success:function(data){              
                if(data=='Updated successful'){
                    
                    location.reload();
                }else{
                    alert("Something went");
                }
            
           
           }
       });
   
    }
    function update_item_status(adjuvant_ID){
        var Status = $("#Status_"+adjuvant_ID).val();
        $.ajax({ 
           type:'POST', 
           url:'Ajax_update_cancer_protocal.php',
           data:{adjuvant_ID:adjuvant_ID,Status:Status, adjuvant:''},
            success:function(data){              
          
            alert(data)
           
           }
       });
    }
    function update_physician_status(physician_ID){
        var Status = $("#Status_"+physician_ID).val();
        $.ajax({ 
           type:'POST', 
           url:'Ajax_update_cancer_protocal.php',
           data:{physician_ID:physician_ID,Status:Status, physician:''},
            success:function(data){  
                alert(data)
                location.reload();
           }
       });
    }

    function update_treatment_status(treatment_ID){
        var Status = $("#Status_"+treatment_ID).val();
        $.ajax({ 
           type:'POST', 
           url:'Ajax_update_cancer_protocal.php',
           data:{treatment_ID:treatment_ID,Status:Status, treatment:''},
            success:function(data){              
          
            alert(data)
           
           }
       });
    }

    function update_chemotherapy_status(chemotherapy_ID){
        var Status = $("#Status_"+chemotherapy_ID).val();
        $.ajax({ 
           type:'POST', 
           url:'Ajax_update_cancer_protocal.php',
           data:{chemotherapy_ID:chemotherapy_ID,Status:Status, chemotherapy:''},
            success:function(data){              
          
            alert(data)
           
           }
       });
    }

    function delete_physician(physician_ID){
        if(confirm("Are you sure you want to delete?")){
            $.ajax({ 
                type:'POST', 
                url:'Ajax_update_cancer_protocal.php',
                data:{physician_ID:physician_ID, deletephysician_ID:''},
                    success:function(responce){ 
                        if(responce=='Deleted successful'){
                            location.reload()
                        }else{
                            alert("Something is wrong Try again"); 
                        }
                }
            });
        } 
    }
</script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script> 