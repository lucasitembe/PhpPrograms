<style>
    .otherdoclinks:hover{
        text-decoration:underline;
        color: #000000; 
        cursor:pointer; 
    }
    #attach_cat_icon{
        transform: rotate(30deg); 
       
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
$select_patien_details = mysqli_query($conn, "
		SELECT pr.Sponsor_ID,Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
			FROM
				tbl_patient_registration pr,
				tbl_sponsor sp
			WHERE
				pr.Registration_ID = '" . $Registration_ID . "' AND
				sp.Sponsor_ID = pr.Sponsor_ID
				") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_patien_details);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_patien_details)) {
            $Member_Number = $row['Member_Number'];
            $Patient_Name = $row['Patient_Name'];
            $Registration_ID = $row['Registration_ID'];
            $Gender = $row['Gender'];
            $Guarantor_Name  = $row['Guarantor_Name'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $DOB = $row['Date_Of_Birth'];
        }
    } else {
        $Guarantor_Name  = '';
        $Member_Number = '';
        $Patient_Name = '';
        $Gender = '';
        $Registration_ID = 0;
    }
    $age = date_diff(date_create($DOB), date_create('today'))->y;
?>
 <a href='Cancer_Patient.php?Registration_ID=<?= $Registration_ID ?>&Patient_Payment_ID=<?= $Patient_Payment_ID ?>&Patient_Payment_Item_List_ID=<?= $Patient_Payment_Item_List_ID ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&from_consulted=<?= $from_consulted ?>' class='art-button-green'>
       BACK
    </a>

<style>
    .rows_list{
        cursor: pointer;
    }
    .rows_list:active{
        color: #328CAF!important;
        font-weight:normal!important;
    }
    .rows_list:hover{
        color:#00416a;
        background: #CCCCCC;
        font-weight:bold;
    }
</style>

<input id="Employee_ID" value="<?php echo $_SESSION['userinfo']['Employee_ID']; ?>" type="text" style="display: none ">

  <br> <br>
  <fieldset>
    <!--<legend align="center"><b>NURSE COMMUNICATION</b></legend>-->
    <legend align="center" style='padding:10px; color:white; background-color:#2D8AAF; text-align:center'><b>
            <b>CHEMOTHERAPY PROTOCAL</b><br />
            <span style='color:yellow;'><?php echo " ".$Registration_ID. " | " . $Patient_Name . "  | " . $Gender . " | " . $age . " years | " . $Guarantor_Name  . ""; ?></span></b>
    </legend>
    <center>
  <div class="col-md-offset-3 col-md-6">
    <div class="box box-primary">
        <div class="box-body">
            <table class="table">
                <tr>
                    <td width="70%">
                        <select name="" id="cancer_ID" class="form-control" style="text-align: center;">
                            <option value=""  >~~~~~Select Protocal to assign to patient~~~~~</option>
                            <?php
                                    
                                    $finance_department_id = $_SESSION['finance_department_id'];
                                    
                                    $select_diagnosis=mysqli_query($conn,"SELECT * FROM tbl_cancer_type WHERE Protocal_status='Enabled'");
                                    while($row=mysqli_fetch_assoc($select_diagnosis)){
                                            $cancer_ID=$row['cancer_type_id'];
                                            $disease_name=$row['Cancer_Name'];
                                            $num_count++;
                                            echo "<option value='$cancer_ID'> $disease_name</option>";
                                     }
                                 
                                
                                ?>
                        </select>
                      
                    </td>
                    <td  width="30%">
                    <button class='btn btn-primary btn-block '  type='button' onclick='cancer_type_details(<?php echo $Registration_ID;?>)' >ASSIGN PROTOCAL<i id='attach_cat_icon' style='color:#328CAF' class='fa fa-send fa-2x'></i><span></button>
                    </td>
                </tr>
            </table>
    </fieldset>
    <fieldset>
    <div class="box box-primary">
        <div class="box-body">
           <table class="table"  style='height:10vh; overflow:scroll;'>
           <!-- style="overflow:auto" -->
               <thead>
                   <tr>
                       <th align="center" colspan="7"  style="background: #a3c9cc; text-align:center;">
                            LIST OF PROTOCAL ASSIGNED TO THIS PATIENT
                       </th>
                   </tr>
                   <tr>
                       <th width="3">SN</th>
                       <th width="45">NAME OF PROTOCAL</th>                      
                       <th width="10">EMPLOYEE ASSIGNED</th>
                       <th width="10">DATE ASSIGNED</th>
                       <th width="7">PROTOCAL STATUS</th>
                        <th width="10">REMARKS</th>
                       <th width="15">ACTION</th>
                   </tr>
               </thead>
               <tbody id="display_protocal">

               </tbody>
           </table>
        </div>
    </div>
    </fieldset>
                    
  <div id="cancer_type_dialog" style="width:50%;display: none;">
      
  </div>
  <div id="patient_protocal_detail"></div>
  <div id="administer_protocal"></div>
   
  <script>
      $(document).ready(function(){
        display_protocal_assigned();
      })
  
     function search_item(){
        var search_value=$("#search_value").val();
        var Registration_ID= '<?= $Registration_ID ?>';
          $.ajax({
          type:'POST',
          url:'cancer_type_search.php',
          data:{search_value:search_value,Registration_ID:Registration_ID},
          success:function(data){
             $("#table_search").html(data); 
          },
          error:function(x,y,z){
              console.log(z);
          } 
      });
    }

    function display_protocal_assigned(){
        var  Registration_ID = '<?php echo $Registration_ID; ?>';
        $.ajax({
          type:'POST',
          url:'Ajax_get_previous_cancer_data.php',
          data:{Registration_ID:Registration_ID,view_assigned_protocal:""},
          success:function(responce){
             $("#display_protocal").html(responce); 
          },
         
      });
    }
    
    function protocal_type_details(Patient_protocal_details_ID, disease_name,Registration_ID){

        $.ajax({
            url:'Ajax_cancer_protocal_details.php', 
            type:'POST',
            data:{Patient_protocal_details_ID:Patient_protocal_details_ID,Registration_ID:Registration_ID,disease_name:disease_name,protocal_type_details:''},
            success:function(responce){
                // console.log(responce);
                $("#patient_protocal_detail").dialog({
                    title: 'PATIENT PROTOCAL DETAILS',
                    width: '70%',
                    height: 700,
                    modal: true,
                });
                $("#patient_protocal_detail").html(responce);
                
            }
        });
        
    }
    function administer_protocal(Patient_protocal_details_ID, disease_name,Registration_ID){
        $.ajax({
            url:'Ajax_administer_cancer_protocal.php', 
            type:'POST',
            data:{Patient_protocal_details_ID:Patient_protocal_details_ID,Registration_ID:Registration_ID,disease_name:disease_name,protocal_type_details:''},
            success:function(responce){
                // console.log(responce);
                $("#administer_protocal").dialog({
                    title: 'ADMINISTER PATIENT PROTOCAL DETAILS',
                    width: '70%',
                    height: 700,
                    modal: true,
                });
                $("#administer_protocal").html(responce); 
                
            }
        });
    }

        function get_pharmacy_item_balance(patient_supportive_ID){

        var item_name = $(".item_name_"+patient_supportive_ID).val();
        var  Sub_Department_ID = $("#Sub_Department_ID_"+patient_supportive_ID).val();
        var  selected_pharmacy = $("#Sub_Department_ID_"+patient_supportive_ID).val();
        if(selected_pharmacy==""){
            alert("Please select pharmacy")
            $("#Sub_Department_ID_"+patient_supportive_ID).css("border", "2px solid red");
            $("#selvalue_"+patient_supportive_ID).prop("checked", false);
        }else{
            $("#Sub_Department_ID_"+patient_supportive_ID).css("border", "2px solid green");       
            $("#balance_"+patient_supportive_ID).css("border", "2px solid green");  
            $.ajax({
                type:'POST',
                url:'cancer_get_item_balance.php',
                data:{item_name:item_name, Sub_Department_ID:Sub_Department_ID},
                success:function(responce){
                    $("#balance_"+patient_supportive_ID).val(responce);
                    
                    if(responce ==0){
                        $("#balance_"+patient_supportive_ID).css("border", "2px solid red");
                        //alert("This item not available in selected pharmacy, Continue...");
                        $("#Sub_Department_ID_"+patient_supportive_ID).css("border", "2px solid red");
                        // $("#selvalue_"+patient_supportive_ID).prop("checked", false);
                    }else{
                        $("#balance_"+patient_supportive_ID).css("border", " ");
                    }
                }
            });
        }
    } 



    function get_item_balance(patient_chemotherapy_ID){
        var item_name = $(".chemo_item_name_"+patient_chemotherapy_ID).val()
        var Sub_Department_ID = $("#chemo_Sub_Department_ID_"+patient_chemotherapy_ID).val();
        var  selected_pharmacy = $("#chemo_Sub_Department_ID_"+patient_chemotherapy_ID).val();
        if(selected_pharmacy==""){
            alert("Please select pharmacy")
            $("#chemo_Sub_Department_ID_"+patient_chemotherapy_ID).css("border", "2px solid red");
            $("#chemo_selvalue_"+patient_supportive_ID).prop("checked", false);
        }else{
            $("#chemo_Sub_Department_ID_"+patient_chemotherapy_ID).css("border", "2px solid green");
            $("#chemo_balance_"+patient_chemotherapy_ID).css("border", "2px solid green");  
        $.ajax({
            type:'POST',
            url:'cancer_get_item_balance.php',
            data:{item_name:item_name, Sub_Department_ID:Sub_Department_ID},
            success:function(responce){
                $("#chemo_balance_"+patient_chemotherapy_ID).val(responce);
                if(responce ==0){
                    $("#chemo_balance_"+patient_chemotherapy_ID).css("border", "2px solid red");
                 //   alert("This item not available in selected pharmacy, Continue..");
                    $("#chemo_Sub_Department_ID_"+patient_chemotherapy_ID).css("border", "2px solid red");
                    // $("#chemo_selvalue_"+patient_chemotherapy_ID).prop("checked", false);
                }else{
                    $("#chemo_balance_"+patient_chemotherapy_ID).css("border", " ");
                }

            }
        });
        }

    }


    function save_administer_protocal(Patient_protocal_details_ID){
        var selected_drug = []; 
        $(".drug:checked").each(function() {
            selected_drug.push($(this).val());
        });

        var selected_treatment = []; 
        $(".treatment:checked").each(function() {
		    selected_treatment.push($(this).val());
        }); 
        var selected_physician_ID=[];
        $(".physician_ID:checked").each(function() {
		    selected_physician_ID.push($(this).val());
        }); 
        var itemdrug=[];
        var doses = document.getElementsByName('itemdrug[]');
        for (var i = 0; i <doses.length; i++) {
            var inp=doses[i];
            itemdrug.push(inp.value);
        }
        
        var treatmentdose=[];
        var doses = document.getElementsByName('treatmentdose[]');
        for (var i = 0; i <doses.length; i++) {
        var inp=doses[i];
        treatmentdose.push(inp.value); 
        }

        
        var treat_Pharmacy_ID=[];
        var treat_PharmacyIDs = document.getElementsByName('treatselectedPharmacy[]');
        for (var i = 0; i <treat_PharmacyIDs.length; i++) {
        var inp=treat_PharmacyIDs[i];
            treat_Pharmacy_ID.push(inp.value);
        }
        var phy_Pharmacy_ID=[];
        var treat_Pharmacy_IDs = document.getElementsByName('physselectedPharmacy[]');
        for (var i = 0; i <treat_Pharmacy_IDs.length; i++) {
        var inp=treat_Pharmacy_IDs[i];
            phy_Pharmacy_ID.push(inp.value);
        }
        
        var drug_selectedPharmacy=[];
        var Pharmacy_IDs = document.getElementsByName('drugselectedPharmacy[]');
        for (var i = 0; i <Pharmacy_IDs.length; i++) {
        var inp=Pharmacy_IDs[i];
            drug_selectedPharmacy.push(inp.value);
        }
        // alert(selected_physician_ID+'==='+selected_treatment+"-===-"+selected_drug);exit;
        var Cycle_details=[];
        var Cycle_detailss = document.getElementsByName('Cycle_details[]');
        for (var i = 0; i <Cycle_detailss.length; i++) {
        var inp=Cycle_detailss[i];
            Cycle_details.push(inp.value);
        }
        var Cycle_details = Cycle_details.toString();
        var  Registration_ID = '<?php echo $Registration_ID; ?>';      
        var cyclenumber = $("#cyclenumber").val();
        var administer_comment = $("#administer_comment").val();
        
        if(administer_comment ==''){
           $("#administer_comment").css("border","1px solid red");
        }else if(cyclenumber  ==''){
            $("#cyclenumber").css("border","1px solid red");
        }else{
            $.ajax({ 
            type:'POST', 
            url:'save_administer_cancer_drugs.php',
            data:{Registration_ID:Registration_ID,Cycle_details:Cycle_details, treat_Pharmacy_ID:treat_Pharmacy_ID,Patient_protocal_details_ID:Patient_protocal_details_ID,administer_comment:administer_comment,cyclenumber:cyclenumber, selected_treatment:selected_treatment,selected_drug:selected_drug, selected_physician_ID:selected_physician_ID, drug_selectedPharmacy:drug_selectedPharmacy, phy_Pharmacy_ID:phy_Pharmacy_ID },
                success:function(data){              
            // alert(data);
            // $('#administer_protocal').dialog('close');
            display_protocal_assigned();            
            // location.reload();
            
           }
       });
        }
       
       
    }
    function change_protocal_status(Patient_protocal_details_ID){
        var Protocal_status=$("#Protocal_status").val();
        var Reason = $("#Reason").val();
        var Employee_ID = $("#Employee_ID").val();
        if(Reason==""){
            $("#Reason").css("border", "2px solid red");
        }else if(Protocal_status==""){
            $("Protocal_stusus").css("#Protocal_status");
        }else{
            $("#Reason").css("border", "");
            $("Protocal_stusus").css("border", "");
            $.ajax({
            type:'POST',
            url:'cancer_type_search.php',
            data:{Protocal_status:Protocal_status,Patient_protocal_details_ID:Patient_protocal_details_ID, Reason:Reason,Employee_ID:Employee_ID,  btn_update_status:''},
            success:function(responce){
                alert(responce);
                display_protocal_assigned();
            },        
        });
    }
    }
      function cancer_type_details(Registration_ID){
          var cancer_id = $("#cancer_ID").val();          
          if(cancer_id==""){
              $("#cancer_ID").css("border", "2px solid red");
          }else{
              var Patient_Payment_Item_List_ID = '<?php echo $Patient_Payment_Item_List_ID; ?>';
            $.ajax({
            url:'fetch_dialog_cancer_details.php', 
            type:'POST',
            data:{cancer_id:cancer_id,Registration_ID:Registration_ID, Patient_Payment_Item_List_ID:Patient_Payment_Item_List_ID},
            success:function(result){
                console.log(result);
              $("#cancer_type_dialog").html(result);  
            }
        });
         $("#cancer_type_dialog").dialog("open");
        }
    }
    
    $(document).ready(function () {
         $("#cancer_type_dialog").dialog({autoOpen: false, width: 1200, height: 700, title: 'CHEMO REQUEST FORM', modal: true});
         
    });

    function changedose(obj){
        alert(obj.id+"<=>"+obj.value);
    }

    function save_patient_detals(cancer_ID){
        
        var selected_items = []; 
            $(".valuone:checked").each(function() {
            selected_items.push($(this).val());
	    });
        var selected_physician = []; 
            $(".physician:checked").each(function() {
            selected_physician.push($(this).val());
        });
        var selected_treatment = []; 
            $(".treatment:checked").each(function() {
            selected_treatment.push($(this).val());
        });

        //var dosedrug_ID = [];
        var dose=[];
        var doses = document.getElementsByName('drugdose[]');
            for (var i = 0; i <doses.length; i++) {
            var inp=doses[i];
                dose.push(inp.value);
            }

        var selected_drug = []; 
        $(".drug:checked").each(function() {
            selected_drug.push($(this).val());
        });
        
        var  Registration_ID = '<?php echo $Registration_ID; ?>';       
        var  weight=$('#Weightboo').val();
        var  height=$('#heightboo').val();
        var  surface=$('#bodysurface').val();
        var  stage=$('#stage').val();
        var  diagnosis=$('#diagnosis').val();
        var  dosead=$('#adjustmentdose').val();
        var  allergies=$('#allergies').val();
        
        var checkedvalue;
        
        if($('#Yes').is(':checked')){
            checkedvalue=$('#Yes').val();
        }else{
            checkedvalue=$('#No').val();
        }
        if(confirm("Are you sure you want to assign this Protocal?")){
            $.ajax({ 
            type:'POST', 
            url:'save_patient_cancer_details.php',
            data:{cancer_ID:cancer_ID,checkedvalue:checkedvalue,weight:weight,height:height,surface:surface,stage:stage,diagnosis:diagnosis,checkedvalue:checkedvalue,dosead:dosead,allergies:allergies,Registration_ID:Registration_ID,selected_items:selected_items,selected_physician:selected_physician,selected_treatment:selected_treatment,selected_drug:selected_drug,dose:dose},
                success:function(data){                    
                    display_protocal_assigned();
                    alert(data)
                    $('#patient_protocal_detail').dialog('close');
                }
            });
        }
   
    }
    function administer_protocals(Patient_protocal_details_ID, disease_name,Registration_ID){
        alert(disease_name +"  is " +Patient_protocal_details_ID+ "  please change Status to continue" );
    }
    </script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script>
    $(document).ready(function (e){
        $("select").select2();
       
    });
</script>
<?php
    include("./includes/footer.php");
?>