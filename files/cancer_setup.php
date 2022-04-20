<style>
    .otherdoclinks:hover{
        text-decoration:underline;
        color: #000000; 
        cursor:pointer; 
    }
</style> 
<?php
include("./includes/header.php");
include("./includes/connection.php");
include './includes/cleaninput.php';
// session_start();

//        echo '<pre>';
//        print_r($_SESSION['hospitalConsultaioninfo']);exit;
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
    $Patient_Payment_Item_List_ID = 0;
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])) {
        if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
//get the current date
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}
?>
<?php

    if(isset($_GET['Registration_ID'])){
    $Registration_ID=$_GET['Registration_ID'];
}
    if(isset($_GET['from_consulted'])){
    $from_consulted=$_GET['from_consulted']; 
}
    if(isset($_GET['Patient_Payment_ID'])){
  $Patient_Payment_ID=$_GET['Patient_Payment_ID'];
}
    if(isset($_GET['Patient_Payment_Item_List_ID'])){
     $Patient_Payment_Item_List_ID=$_GET['Patient_Payment_Item_List_ID'];
    
}

?> 
<a href="manage_cancer_protocal.php?Fromsetup=Fromsetup" class="art-button-green">MANAGE PROTOCAL TEMPLATE</a>
<a href='chemotherapytreatment.php?Registration_ID=<?= $Registration_ID ?>&Patient_Payment_ID=<?= $Patient_Payment_ID ?>&Patient_Payment_Item_List_ID=<?= $Patient_Payment_Item_List_ID ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&from_consulted=<?= $from_consulted ?>' class='art-button-green'>
       BACK
    </a>
                    <div id="Add_Pharmacy_Items" style="width:50%;display: none;">
                        <table width=100% style='border-style: none;'>
                            <tr>
                                <td width=40%>
                                    <table width=100% style='border-style: none;'>
                                        <tr>
                                            <td>
                                                <input type='text' id='Search_Value' name='Search_Value' autocomplete='off' onkeyup='getItemsListFiltered(this.value)' placeholder='Enter Item Name'>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <fieldset style='overflow-y: scroll; height: 270px;' id='Items_Fieldset'>
                                                    <table width=100%>
                                                               <?php
                                                               $item_id=0;
                                                               $result = mysqli_query($conn,"SELECT * FROM tbl_items WHERE Consultation_Type='Pharmacy' limit 100");
                                                               while ($row = mysqli_fetch_array($result)) {
                                                                      $item_id = $row['Item_ID'];
                                                                   echo "<tr>
				                                    <td style='color:black; border:2px solid #ccc;text-align: left;' width=5%>";
                                                                   ?>

                                                            <input type='checkbox' name='selection' id='item_ID_cancer'  class='item_ID_cancer' value='<?php echo $row['Item_ID']; ?>' onclick="take_id(<?php echo $row['Item_ID']; ?>)">

                                                            <?php
                                                            echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'><label for='" . $row['Item_ID'] . "'>" . $row['Product_Name'] . "</label>f</td></tr>";
                                                        }
                                                        ?>
                                                    </table>
                                                </fieldset>
                                            </td>
                                        </tr>
                                    </table>
                                    <input type='button' name='Submit' id='Submit' class='art-button-green' value='ADD ITEMS' onclick="Add_item()">
                                </td>

                                                
                                          
                                  
                            </tr>
                        </table>
                    </div>
                    <div id="Add_Pharmacy_Items_other" style="width:50%;display: none;">
                        <table width=100% style='border-style: none;'>
                            <tr>
                                <td width=40%>
                                    <table width=100% style='border-style: none;'>
                                        <tr>
                                            <td>
                                                <input type='text' id='Search_Value' name='Search_Value' autocomplete='off' onkeyup='getItemsListFiltereds(this.value)' placeholder='Enter Item Name'>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <fieldset style='overflow-y: scroll; height: 270px;' >
                                                    <table width=100% id='Items_Fieldsets'>
                                                               <?php
                                                               $result = mysqli_query($conn,"SELECT * FROM tbl_items WHERE Consultation_Type='Pharmacy'  limit 100");
                                                               while ($row = mysqli_fetch_array($result)) {
                                                                   echo "<tr>
				    <td style='color:black; border:2px solid #ccc;text-align: left;' width=5%>";
                                                                   ?>

                                                            <input type='checkbox' name='selection' id='item_ID_cancer_id'  class='item_ID_cancer_id' value='<?php echo $row['Item_ID']; ?>'>

    <?php
    echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'><label for='" . $row['Item_ID'] . "'>" . $row['Product_Name'] . "</label>g</td></tr>";
}
?>
                                                    </table>
                                                </fieldset>
                                            </td>
                                        </tr>
                                    </table>
                                    <input type='button' name='Submit' id='Submit' class='art-button-green' value='ADD ITEMS f' onclick='Add_item_data();'>
                                </td>

                                                
                                          
                                  
                            </tr>
                        </table>
                    </div>
<fieldset>
    <legend align=center><b>CANCER PROTOCAL CONFIGURATION</b></legend>
        <table  class="hiv_table" style="width:100%;margin-top:5px;">
                <tr style="margin-top:30px;">              
                    <td style='text-align:center;'>                         
                        <div id='Cached2'>             
                            <div id='Cached'>
                                <b>Select Pathorogy:</b>
                                <select  name='pathorogy' id='Pathorogy_ID' style='width:50%; padding-top:4px; padding-bottom:4px;'>";
                            
                                <?php $query_cancer_Type = mysqli_query($conn,"SELECT Pathorogy_ID,Pathorogy_name FROM tbl_cancer_pathorogy") or die(mysqli_error($conn));
                                    echo '<option value="">~~~~~Select Pathorogy~~~~~</option>';
                                    while ($row_type_of_cancer= mysqli_fetch_assoc($query_cancer_Type)) {
                                    echo '<option value="' . $row_type_of_cancer['Pathorogy_ID'] . '">' . $row_type_of_cancer['Pathorogy_name'] . '</option>';                                    
                                    }
                                    ?>
                                
                                </select>  
                                <span><a class='art-button-green' href='#' type='button' onclick='pathorogy_dialog()'>ADD PATHOROGY</a></span>  
                            </div>
                        </div> 
                    </td>
                </tr>
                <tr style="margin-top:30px;">              
                    <td style='text-align:center;'>                         
                        <div id='Cached2'>             
                            <div id='Cached'>
                                <b>Select  Stage:</b>
                                <select  name='Stage_ID' id='Stage_ID' style='width:50%; padding-top:4px; padding-bottom:4px;'>";
                            
                                <?php $query_cancer_stage = mysqli_query($conn,"SELECT Stage_ID,stage_name FROM tbl_cancer_stages") or die(mysqli_error($conn));
                                    echo '<option value="">~~~~~Select Stage of Cancer~~~~~</option>';
                                    while ($row_stage_of_cancer= mysqli_fetch_assoc($query_cancer_stage)) {
                                    echo '<option value="' . $row_stage_of_cancer['Stage_ID'] . '">' . $row_stage_of_cancer['stage_name'] . '</option>';                                    
                                    }
                                    ?>
                                
                                </select> 
                                <span><a class='art-button-green' href='#' type='button' onclick='stage_dialog()'>ADD STAGE</a></span>  

                            </div>
                        </div> 
                    </td>
                </tr>
                
                <tr style="margin-top:30px;">              
                    <td style='text-align:center;'>                         
                        <div id='Cached_data'>    
                                <b>Type of Protocal:</b>
                                <select  name='type_of_cancer_id' id='type_of_cancer_id' style='width:50%; padding-top:4px; padding-bottom:4px;'>";
                            
                                <?php $query_cancer_Type = mysqli_query($conn,"SELECT cancer_type_id,Cancer_Name FROM tbl_cancer_type") or die(mysqli_error($conn));
                                    echo '<option value="">~~~~~Select Type of Cancer~~~~~</option>';
                                    while ($row_type_of_cancer= mysqli_fetch_assoc($query_cancer_Type)) {
                                    echo '<option value="' . $row_type_of_cancer['cancer_type_id'] . '">' . $row_type_of_cancer['Cancer_Name'] . '</option>';
                                    
                                    }?>
                                
                                </select>
                                <span><a class='art-button-green' href='#' type='button' onclick='cancer_protocal_dialog()'>ADD PROTOCAL</a></span> 
                        </div> 
                    </td>
                </tr>
                
                <tr style="margin-top:30px;">
                    <td>
                         </br>
                
                <table class="table" style="background-color: white; margin:0% 0% 0% 5%;width:90%" id='colum-addition'> 
                    <input type='button'class='art-button-green' id='addrow1' style='margin-left:95% !important;' value='Add'>
                    <tr width='50px;'>
                     
                        <td width="50%">
                            <div class="title" style='text-align:center;'><b>Therapy/Treatment</b></div>
                        </td>
                        <td>
                            <div class="title" style='text-align:center;'><b>Streangth</b></div>
                        </td>
                        <td>
                            <div class="title" style='text-align:center;'><b>Duration</b></div>
                        </td>
                        <td>
                            <div class="title" style='text-align:center;'><b>Action</b></div>
                        </td>
                    </tr>
                    <tr>
                    
                        <td>
                          <input type="text" name="adjuvant[]" autocomplete="off" style='width:100%;display:inline;height:30px;'/>  
                        </td>
                        <td>
                           <input type="text" name="adjuvantstrenth[]" autocomplete="off" style='width:100%;display:inline;height:30px;'/> 
                        </td>
                        <td>
                           <input type="text" name="duration[]" autocomplete="off" style='width:100%;display:inline;height:30px;'/> 
                        </td>
                    </tr>
                    <input type='hidden' id='rowCount' value='1'>
                    </table>
                    </td>
                </tr>
                <tr style="margin-top:30px;">
                    <td>
                         </br>
                
                <table class="table" style="background-color: white;margin:0% 0% 0% 5%;width:90%" > 
                    <input type='button'class='art-button-green' id='addrow_one' style='margin-left:95% !important;' value='Add'>
                    <tr>
                      
                        <td width="50%">
                            <div class="title" style='text-align:center;'><b>Pre Hydration</b></div>
                        </td>
                    </tr>
                   
                    <tr>
                    
                        <td width="50%">Physician to circle one in each column
                            <table class="table" id='row-addition'>
                                <tr>
                                    <td>Volume (ml)</td>
                                    <td>Type</td>
                                    <td>Minutes</td>
                                    <td>
                                        <div class="title" style='text-align:center;'><b>Action</b></div>
                                    </td>
                                </tr>
                                     <tr>
                    
                        <td>
                          <input type="text" name="volume[]" autocomplete="off" style='width:100%;display:inline;height:30px;'/>  
                        </td>
                        <td>
                           <input type="text" name="type[]" autocomplete="off" style='width:100%;display:inline;height:30px;'/> 
                        </td>
                        <td>
                           <input type="text" name="minutes[]" autocomplete="off" style='width:100%;display:inline;height:30px;'/> 
                        </td>
                    </tr>
                            </table>
                        </td>
                    </tr>
               
                    </table>
                    </td>
                </tr>
                
                
                 <tr style="margin-top:30px;">
              
                    <td>
                </br>
                <input type="button" name="SAVE" value="ADD ITEMS" class="art-button-green" onclick="showdialog()" style='margin-left:90% !important;' ></br>
                <div class="title" style='text-align:center;'><b>Supportive Treatment</b></div>
                <table class="table" style="background-color: white;">
                    <tr>
                    <th>SN</th>
                    <th>Supportive treatment</th>
                    <th width="6%">Dose(mg)</th>
                    <th width="6%">Route</th>
                    <th  width="8%">Administration Time(minutes)</th>
                    <th width="8%">Frequence</th>
                    <th  width="30%">Medication Instructions and Indications</th>
                    </tr>
                    
                    <tbody id="selection_items">
                        
                    </tbody>
                    
                    
                        <?php
                         $sql_select = mysqli_query($conn,"SELECT it.Product_Name,nt.supportive_treatment_ID FROM tbl_supportive_treatment nt,tbl_items it WHERE  it.Item_ID=nt.supportive_treatment_ID AND nt.status='pending'");
                           $count =0;
                          while($row=mysqli_fetch_assoc($sql_select)){
                              $count++;
                              $item_name = $row['Product_Name'];
                          }
                       ?> 

                </table>
                    </td>
                </tr>
                 <tr style="margin-top:30px;">
              
                    <td>
                </br>
                <input type="button" name="SAVE" value="ADD ITEMS" class="art-button-green" onclick="showdialogother()" style='margin-left:90% !important;' ></br>
                <div class="title" style='text-align:center;'><b>Chemotherapy Drug</b></div>
                <table class="table" style="background-color: white;">
                    <tr>
                    <th>SN</th>
                    <th>Chemotherapy Drug</th>
                     <th width="15%">Strength(ml)</th>
                    <th width="6%">Dose(mg)</th>                   
                    <th  width="8%">Route</th>
                    <th width="13%">Admin Time</th>
                    <th  width="30%">Frequency</th>
                    </tr>
                    
                      <tbody id="selection_items_Drug">
                        
                    </tbody>
                       
                     

                </table>
                    </td>
                </tr>
                
                </table>
    <center> <input type="button" name="SAVE" value="SAVE" class="art-button-green" onclick="SAVE_CANCER_TYPE()"></br></center>  

</fieldset>
 <div id="addpathorogy"></div>
 <div id="addstage"></div>
 <div id="addprotocal"></div>
<?php
    include("./includes/footer.php");
?>
<script type="text/javascript">
    function pathorogy_dialog(){
        $.ajax({
            type:'POST',
            url:'ajax_cancer_pathorogy_stage.php',           
            data:{addpatholoy:''},
            success:function(responce){
                $("#addpathorogy").dialog({
                    title: 'ADD PATHOROGY',
                    width: 800, 
                    height: 150, 
                    modal: true
                });
                $("#addpathorogy").html(responce);                
            }
        })
    }

    function save_pathorogy(){
        var Defined_pathorogy = $("#Defined_pathorogy").val();
        $.ajax({
            type:'POST',
            url:'ajax_cancer_pathorogy_stage.php',           
            data:{Defined_pathorogy:Defined_pathorogy, savepatholoy:''},
            success:function(responce){
                alert(responce);                
            }
        })
    }

    function stage_dialog(){
        $.ajax({
            type:'POST',
            url:'ajax_cancer_pathorogy_stage.php',           
            data:{addstagedialog:''},
            success:function(responce){
                $("#addstage").dialog({
                    title: 'ADD STAGE',
                    width: 800, 
                    height: 150, 
                    modal: true
                });
                $("#addstage").html(responce);                
            }
        })
    }
    
    
    function save_stage(){
        var Pathorogy_ID = $("#Pathorogy_ID").val();
        var Defined_stage = $("#Defined_stage").val();
        
        $.ajax({
            type:'POST',
            url:'ajax_cancer_pathorogy_stage.php',           
            data:{Defined_stage:Defined_stage,Pathorogy_ID:Pathorogy_ID, savestage:''},
            success:function(responce){
                alert(responce);                
            }
        })
    }
    function cancer_protocal_dialog(){
        var Stage_ID = $("#Stage_ID").val();
        if(Stage_ID ==''){
            alert('select stage')
            exit();
        }
        $("#Stage_ID").css('border', '2px solid red')
        $.ajax({
            type:'POST',
            url:'ajax_cancer_pathorogy_stage.php',           
            data:{Stage_ID:Stage_ID,addprotocal:''},
            success:function(responce){
                $("#addprotocal").dialog({
                    title: 'ADD PROTOCAL',
                    width: 800, 
                    height: 150, 
                    modal: true
                });
                $("#addprotocal").html(responce);                
            }
        })
    }
    function  add_type_of_cancer(Stage_ID){
        var type = $('#type_of_cancer').val();
        var Stage_ID = $("#Stage_ID").val();
        if (type == '') {
            alert("Enter Type of Cancer First");
            exit();
        }
        $.ajax({
            type: 'POST',
            url: 'add_type_of_cancer.php',
            data: {type:type, Stage_ID:Stage_ID},
            success: function (result) {
                $('#Cached_data').html(result);
                $('#type_of_cancer').val("");
                $("#type_of_cancer_id").select2();
                $("#addprotocal").dialog("close");
            }
        });
    }
    function remove_item_drug(cancer_drug_ID, cancer_id){
        //alert(cancer_id +"---"+cancer_drug_ID)
        $.ajax({
            type: 'POST',
            url: 'Ajax_delete_cancer_drug.php',
            data: {cancer_id:cancer_id,cancer_drug_ID:cancer_drug_ID,remove_items:''},
            cache: false,
            success: function (html) {
                Item_selection2();
              
            }
        });
    }

    function remove_item(item_cancer_ID, cancer_id){
       // alert(cancer_id +"---"+item_cancer_ID)
        $.ajax({
            type: 'POST',
            url: 'Ajax_delete_cancer_drug.php',
            data: {cancer_id:cancer_id,item_cancer_ID:item_cancer_ID, remove_item:''},
            cache: false,
            success: function (html) {
                Item_selection()
              
            }
        });
    }
     function SAVE_CANCER_TYPE(){

          var cancer_id = $('#type_of_cancer_id').val();
         
          var adjuvant=[];
          var adjuvants = document.getElementsByName('adjuvant[]');
          for (var i = 0; i <adjuvants.length; i++) {
            var inp=adjuvants[i];
                adjuvant.push(inp.value);
            }
            var adjuvantstrenth=[];
            var adjuvantss = document.getElementsByName('adjuvantstrenth[]');
            for (var i = 0; i <adjuvantss.length; i++) {
            var inp=adjuvantss[i];
            adjuvantstrenth.push(inp.value);
            }
          var duration=[];
          var durations = document.getElementsByName('duration[]');
          for (var i = 0; i <durations.length; i++) {
            var inp=durations[i];
                duration.push(inp.value);
            }
          var volume=[];
          var volumes = document.getElementsByName('volume[]');
          for (var i = 0; i <volumes.length; i++) {
            var inp=volumes[i];
                volume.push(inp.value);
            }
          var type=[];
          var types = document.getElementsByName('type[]');
          for (var i = 0; i <types.length; i++) {
            var inp=types[i];
                type.push(inp.value);
            }
          var minutes=[];
          var minutess = document.getElementsByName('minutes[]');
          for (var i = 0; i <minutess.length; i++) {
            var inp=minutess[i];
                minutes.push(inp.value);
            }
          var item=[];
          var items = document.getElementsByName('item[]');
          for (var i = 0; i <items.length; i++) {
            var inp=items[i];
                item.push(inp.value);
            }
            
          //  alert(item);
          var dose=[];
          var doses = document.getElementsByName('dose[]');
          for (var i = 0; i <doses.length; i++) {
            var inp=doses[i];
                dose.push(inp.value);
            }
          var route=[];
          var routes = document.getElementsByName('route[]');
          for (var i = 0; i <routes.length; i++) {
            var inp=routes[i];
                route.push(inp.value);
            }
          var admin=[];
          var admins = document.getElementsByName('admin[]');
          for (var i = 0; i <admins.length; i++) {
            var inp=admins[i];
                admin.push(inp.value);
            }
          var frequence=[];
          var frequences = document.getElementsByName('frequence[]');
          for (var i = 0; i <frequences.length; i++) {
            var inp=frequences[i];
                frequence.push(inp.value);
            }
          var medication=[];
          var medications = document.getElementsByName('medication[]');
          for (var i = 0; i <medications.length; i++) {
            var inp=medications[i];
                medication.push(inp.value);
            }
          var drug=[];
          var drugs = document.getElementsByName('drug[]');
          for (var i = 0; i <drugs.length; i++) {
            var inp=drugs[i];
                drug.push(inp.value);
            }
        
          var ddose=[];
          var ddoses = document.getElementsByName('ddose[]');
          for (var i = 0; i <ddoses.length; i++) {
            var inp=ddoses[i];
                ddose.push(inp.value);
            }
          var dvolume=[];
          var dvolumes = document.getElementsByName('dvolume[]');
          for (var i = 0; i <dvolumes.length; i++) {
            var inp=dvolumes[i];
                dvolume.push(inp.value);
            }
            
            var item_ID =[];
            var item_IDS = document.getElementsByName('item_ID[]');
            for (var i=0; i<item_IDS.length; i++){
                var inp=item_IDS[i];
                item_ID.push(inp.value);
            }

        var ditem_id =[];
        var ditem_id_s = document.getElementsByName('ditem_id[]');
        for (var i=0; i<ditem_id_s.length; i++){
            var inp=ditem_id_s[i];
            ditem_id.push(inp.value);
        }

          var droute=[];
          var droutes = document.getElementsByName('droute[]');
          for (var i = 0; i <droutes.length; i++) {
            var inp=droutes[i];
                droute.push(inp.value);
            }
          var dadmin=[];
          var dadmins = document.getElementsByName('dadmin[]');
          for (var i = 0; i <dadmins.length; i++) {
            var inp=dadmins[i];
                dadmin.push(inp.value);
            }
          var dfrequence=[];
          var dfrequences = document.getElementsByName('dfrequence[]');
          for (var i = 0; i <dfrequences.length; i++) {
            var inp=dfrequences[i];
                dfrequence.push(inp.value);
            }

            if (cancer_id  == '') {
                alert("Enter Type of Cancer First");
                exit();
            }
        if(adjuvant.length==0){
            alert("Please select adjuvant");
         }else  if(duration.length==0){
             alert("Please fill duration");
         }else  if(drug.length==0){
             alert("Please select  Drug");
         }else   if(item_ID.length==0){
             alert("Please select chemo drug item");
         }else  if(ditem_id.length==0){
             alert("Please check treatment Drug");
         }else {
            $('#weight').css("border", "");
            $('#height').css("border", "");
        $.ajax({
            type: 'POST', 
            url: 'save_all_data_cancer_type.php',
            data: {type:type,cancer_id:cancer_id,adjuvantstrenth:adjuvantstrenth, ditem_id:ditem_id,item_ID:item_ID,adjuvant:adjuvant,duration:duration,volume:volume,minutes:minutes,cancer_id:cancer_id,item:item,dose:dose,route:route,admin:admin,frequence:frequence,medication:medication,drug:drug,ddose:ddose,dvolume:dvolume,droute:droute,dadmin:dadmin,dfrequence:dfrequence},
            success: function (result) {
                  console.log(result);
                 alert("Protocal created successful");
                 $('#type_of_cancer_id').val("");
                 location.reload(true);
            }, error:function(x,y,z){
                console.log(x+y+z);
            }
        });
         }
     }
    
          
    
    function Add_item(){
        
          var cancer_id = $('#type_of_cancer_id').val();
          
            if (cancer_id  == '') {
                alert("Enter Cancer Type First");
                exit();
            }
        
            var selected_item = []; 
        $(".item_ID_cancer:checked").each(function() {
		selected_item.push($(this).val());
	});
        
 
            if (selected_item  == '') {
                alert("Select Item First");
                exit();
            }
        
//         alert(selected_item);

           $.ajax({
            type: 'POST',
            url: 'Add_item_to_form_list.php',
            data: {selected_item:selected_item,cancer_id:cancer_id},
            cache: false,
            success: function (html) {
                 
                console.log(html);
                alert("Item added successfully");
                 Item_selection();
              
            }, error:function(x,y,z){
                console.log(x+y+z);
            }
        });
    }
    function Add_item_data(){
            var cancer_id = $('#type_of_cancer_id').val();          
            if (cancer_id  == '') {
                alert("Enter Cancer Type First");
                exit();
            }            
            var selected_item_cancer = []; 
                $(".item_ID_cancer_id:checked").each(function() {
                selected_item_cancer.push($(this).val());
            });
            if (selected_item_cancer  == '') {
                alert("Select Item First !!!");
                exit();
            }
            $.ajax({
                type: 'POST',
                url: 'Add_item_form_lists.php',
                data: {selected_item_cancer:selected_item_cancer,cancer_id:cancer_id},
                cache: false,
                success: function (html){
                    console.log(html);
                    alert("Item added successful");
                    Item_selection2();                
                }
            });
    }
    function Item_selection(){
           $.ajax({
            type: 'POST',
            url: 'cancer_selection_items.php',
            data: {},
            cache: false,
            success: function (html) {
                $('#selection_items').html(html);              
            }
        });
    }
    function Item_selection2(){
           $.ajax({
            type: 'POST',
            url: 'cancer_items.php',
            data: {},
            cache: false,
            success: function (html) {
                $('#selection_items_Drug').html(html);
              
            }
        });
    }
    
  
  
    
    function getItemsListFiltered(items){
       // alert(items) 
           $.ajax({
            type: 'POST',
            url: 'filteritems_cancer.php',
            data: {items:items, cancer_search:''},
            cache: false,
            success: function (html) {
                console.log(html);
                $('#Items_Fieldset').html(html);
            }
        });
    }
    
    function getItemsListFiltereds(items){
        //alert(items)
           $.ajax({
            type: 'POST',
            url: 'filteritems_cancer.php',
            data: {items:items, listfilted:''},
            cache: false,
            success: function (html) {
                console.log(html);
                $('#Items_Fieldsets').html(html);
            }
        });
    }
    
    function showdialog(){
         $("#Add_Pharmacy_Items").dialog("open");
    }
    function showdialogother(){
         $("#Add_Pharmacy_Items_other").dialog("open");
    }
    
    $(document).ready(function () {
         $("#Add_Pharmacy_Items").dialog({autoOpen: false, width: 600, height: 450, title: 'ADD PHARMACY ITEMS', modal: true});
         $("#Add_Pharmacy_Items_other").dialog({autoOpen: false, width: 600, height: 450, title: 'ADD PHARMACY ITEMS', modal: true});
        $('select').select2();
        Item_selection();
        Item_selection2();
    });
    
    
          $('#addrow1').click(function (){
                        var rowCount = parseInt($('#rowCount').val()) + 1;
                        var newRow = "<tr class='addnewrow tr" + rowCount + "'><td><input name='adjuvant[]' class='txtbox' type='text' class='adjuvant' id='" + rowCount + " ' style='width:100%'></td><td><input name='adjuvantstrenth[]' class='txtbox' type='text' class='adjuvantstrenth' id='" + rowCount + " ' style='width:100%'></td><td><input name='duration[]' class='txtbox' type='text' class='duration' id='" + rowCount + " ' style='width:100%'></td><td><input type='button' class='remove' row_id='" + rowCount + "' value='x'></td></tr>";
                        $('#colum-addition').append(newRow);
                        document.getElementById('rowCount').value = rowCount;
                    });
          $('#addrow_one').click(function () {
                        var rowCount = parseInt($('#rowCount').val()) + 1;
                        var newRow = "<tr class='addnewrow tr" + rowCount + "'><td><input name='volume[]' class='txtbox' type='text' class='duration' id='" + rowCount + " ' style='width:100%'></td><td><input name='type[]' class='txtbox' type='text' class='duration' id='" + rowCount + " ' style='width:100%'></td><td><input name='minutes[]' class='txtbox' type='text' class='duration' id='" + rowCount + " ' style='width:100%'></td><td><input type='button' class='remove' row_id='" + rowCount + "' value='x'></td></tr>";
                        $('#row-addition').append(newRow);
                        document.getElementById('rowCount').value = rowCount;
                    });
                    
                        $(document).on('click', '.remove', function () {

                        var id = $(this).attr('row_id');
                        //alert(id);
                        $('.tr' + id).remove().fadeOut();
                    });
</script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
    <link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script> 

