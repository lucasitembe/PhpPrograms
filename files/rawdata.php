<?php 

include ("./includes/functions.php");
include ("./includes/header.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Mtuha_Reports'] != 'yes') {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
}
?>
<!-- SSMEmskmoscow2314/@ savana-->
 <!-- <a href="rwdata.php" class="art-button-green">RAW DATA2</a><br> -->
<style>
    * {
  padding: 0px;
  margin: 0px;
  outline: none;
  font: 16px "sans serif";
  font-weight: none;
  list-style-type: none;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
}
.table{
  border: none;
}
body {
  background: #e39900;
  overflow: hidden;
  overflow-y: auto;
  color: #bbb;
}
/* position: fixed; */
.controls {
  
  top: 30px;
  left: 0;
  right: 0;
  background: #ccc;
  z-index: 1;
  padding: 3px 5px;
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
}

button {
  border: 0px;
  color: #e13300;
  margin: 4px;
  padding: 4px 12px;
  cursor: pointer;
  background: transparent;
}

button.active,
button.active:hover {
  background: #e13300;
  color: #fff;
}

button:hover {
  background: #efefef;
}

input[type=checkbox] {
  vertical-align: middle !important;
}

h1 {
  font-size: 3em;
  font-weight: lighter;
  color: #fff;
  text-align: center;
  display: block;
  padding: 40px 0px;
  margin-top: 40px;
}

.tree {
  margin: 2% auto;
  width: 80%;
}

.tree ul {
  display: none;
  margin: 4px auto;
  margin-left: 6px;
  border-left: 1px dashed #dfdfdf;
}


.tree li {
  padding: 12px 18px;
  cursor: pointer;
  vertical-align: middle;
  background: #fff;
}

.tree li:first-child {
  border-radius: 3px 3px 0 0;
}

.tree li:last-child {
  border-radius: 0 0 3px 3px;
}

.tree .active,
.active li {
  background: #efefef;
}

.tree label {
  cursor: pointer;
}

.tree input[type=checkbox] {
  margin: -2px 6px 0 0px;
}

.has > label {
  color: #000;
}

.tree .total {
  color: #e13300;
}

</style>
<a href='governmentReports.php?GovernmentReports=GovernmentReportsThisPage' class='art-button-green'>
        BACK
    </a>
<br>
<br>
<fieldset>
<table width="100%">
      <tr>
          <td width="100%" style="text-align: center;">
          <b>OPD Dr. Consultation Date</b>
          <input type='radio' name='Date_To' class='filter_date' value="tc.Consultation_Date_And_Time" style='text-align: center; '>
          <b>IPD Admission Date</b>
          <input type='radio' name='Date_To' class='filter_date' value="Admission_Date_Time" style='text-align: center; '>
          
          <b>Start Date</b>
              <input type='text' name='Date_From' id='date' style='text-align: center; width:20%;' placeholder="~~~ ~~~ Enter Start Date ~~~ ~~~">
          <b>End Date</b>
          <input type='text' name='Date_To' id='date2' style='text-align: center; width:20%;' placeholder="~~~ ~~~ Enter End Date ~~~ ~~~">
          <!-- <b>Group</b>
          <select name="" id="GROUP_By" style='text-align: left; width:20%;'>

          <option value="none">None</option>
          <option value="Patient_name">Patient Name</option>
          <option value="Check_in_ID">Patient Visit</option>
          <option value=""></option>
          </select> -->
          <input type="button" value="EXPORT DATA" class="art-button-green pull-center" onclick="exportdata()">
      </td>
      </tr>      
  </table>
</fieldset>

  <br>
<fieldset>
    <legend>DATA ANALYTICS (REPORT BUILDER)</legend>
    
<div class="controls">
  <button>Collepsed</button>
  <button>Expanded</button>
  <!-- <button>Checked All</button> -->
  <button>Unchek All</button>
</div>
<table class="table">
    <tr>
        <td>
            <ul class="tree">
            <li class="has">
            <input type="checkbox" class="domain" value="tbl_patient_registration pr">
            <!-- <input type="checkbox" class="whereclaus" style="display: none;" value=" pr.Registration_ID=tc.Registration_ID "> -->
            <label>Demographics <span class="total">(12)</span></label>
            <ul>
            <li class="">
                <input type="checkbox" class="subdomain" value="Patient_Name">
                <label>Patient Name </label>
            </li>
            <li class="">
                <input type="checkbox" class="subdomain" value="pr.Registration_ID">
                <label>Registration Number </label>
            </li>
            <li class="">
                <input type="checkbox" class="subdomain" value="Date_of_birth">
                <label>DOB </label>
            </li>
            <li class="">
                <input type="checkbox" class="subdomain" value="Gender">
                <label>Gender </label>
            </li>
            <li class="">
                <input type="checkbox" class="subdomain" value="Country">
                <label>Country </label>
            </li>
            <li class="">
                <input type="checkbox" class="subdomain" value="pr.Region">
                <label>Region </label>
            </li>
            <li class="">
                <input type="checkbox" class="subdomain" value="pr.District">
                <label>District </label>
            </li>
            <li class="">
                <input type="checkbox" class="subdomain" value="pr.Ward">
                <label>Ward </label>
            </li>
            <li class="">
                <input type="checkbox" class="subdomain" value="Tribe">
                <label>Tribe </label>
            </li>
            <!-- <li class="">
                <input type="checkbox" class="subdomain" value="pr.Religion_ID=tr.Religion_ID">
                <label>Religion </label>
            </li>
            <li class="">
                <input type="checkbox" class="subdomain" value="Denomination">
                <label>Denomination </label>
            </li> -->
            <li class="">
              <input type="checkbox" style="display: none;" class="whereclaus" value=" sp.Sponsor_ID=pr.Sponsor_ID ">
              <input type="checkbox" style="display: none;" class="domain" value=" tbl_sponsor sp ">
                <input type="checkbox" class="subdomain" value="Guarantor_Name">
                <label>Sponsor </label>
            </li>
            
            </ul>
        </li>
            </ul>
        </td>
        <td>
            <ul class="tree">
            <li class="has">
            <!-- <input type="checkbox" style="display: none;" class="whereclaus" value="c.Registration_ID=pr.Registration_ID "> -->
    <input type="checkbox" class="domain" value="tbl_consultation tc">
    <label>Consultation OPD<span class="total">(8)</span></label>
    <ul>
      <li class="">
        <input type="checkbox" class="subdomain" value="tc.Consultation_Date_And_Time">
        <label>Consultation Date </label>
      </li>
      <li class="">
        <input type="checkbox" class="subdomain" value="tc.maincomplain">
        <label>Main Complain </label>
      </li>
      <li class="">
        <input type="checkbox" class="subdomain" value="tc.Remarks">
        <label>Remarks </label>
      </li>
      <li class="">
        <input type="checkbox" class="subdomain" value="tc.consultation_type">
        <label>Consultation case </label>
        <!-- <ul>
          <li>
            <input type="checkbox" class="subject" value=" c.consultation_type = 'new_consultation' ">
            <label>New consultation</label>
          </li>
          <li>
            <input type="checkbox" class="subject" value=" c.consultation_type = 'result_consultation' ">
            <label>Return consultation</label>
          </li>
        </ul> -->
      </li>
      <li class="">
        <input type="checkbox" class="subdomain" value=" tc.local_examination">
        <label>Local Examination </label>
      </li>
      <li class="">
        <input type="checkbox" class="subdomain" value="Clinic_Name">
        <label>Clinic </label>
        <ul>
          <li>
          <input type="checkbox" style="display: none;" class="domain" value=" tbl_clinic cl">
          <input type="checkbox" style="display: none;" class="whereclaus" value="  tc.Clinic_ID=cl.Clinic_ID ">
             
            <!-- <input type="checkbox" class="subdomain" value="Clinic_Name">
            <label>Clinic name</label> -->
          </li>
          
                    
        </ul>
      </li>
      <li class="has">
        <input type="checkbox" class="subdomain" value=" tc.general_observation ">
        <label>General Observation</label>
       
      </li>
      <li class="">
        <input type="checkbox" class="subdomain" value=" tc.doctor_plan_suggestion">
        <label>Doctor plan </label>
      </li>
      <li class="">
      <input type="checkbox" style="display: none;" class="domain" value="tbl_employee te">

      <input type="checkbox" style="display: none;" class="whereclaus" value=" tc.Employee_ID=te.Employee_ID ">

        <input type="checkbox" class="subdomain" value="Employee_name">
        <label>Consultant </label>
      </li>
  
            </ul>
        </td>
        <td>
      <ul class="tree">
          <li class="has">
            <input type="checkbox" class="domain" value="tbl_disease_consultation dc, tbl_disease td">
            <input type="checkbox" style="display: none;" class="whereclaus" value=" dc.disease_ID=td.disease_ID AND tc.consultation_ID=dc.consultation_ID ">
            <label>Disease consultation OPD<span class="total">(4)</span></label>
            <ul>
              <li>
                <input type="checkbox" class="subdomain" value="diagnosis_type">
                <label>Diagnosis type</label>
                <!-- <ul>
                  <li>
                    <input type="checkbox" class="subject" value=" diagnosis_type='provisional_diagnosis' ">
                    <label>Provisional Diagnosis</label>
                  </li>
                  <li>
                    <input type="checkbox" class="subject" value=" diagnosis_type='differantial_diagnosis' ">
                    <label>Differantial Diagnosis</label>
                  </li>
                  <li>
                    <input type="checkbox" class="subject" value=" diagnosis_type='diagnosis' ">
                    <label>Final Diagnosis</label>
                  </li>
                </ul> -->
              </li>
              <li>
                <input type="checkbox" class="subdomain" value="disease_name">
                <label>Disease name</label>
              </li>
              <li>
                <input type="checkbox" class="subdomain" value="disease_code">
                <label>Disease code</label>
              </li>
              <li>
                <input type="checkbox" class="subdomain" value="disease_code">
                <label>Disease date</label>
              </li>
            </ul>
          </li>
      
            </ul>
        </td>
        <td>
        <ul class="tree">
          <li class="has">
          <input type="checkbox" style="display: none;" class="whereclaus" value=" tl.Item_ID=ti.Item_ID AND pc.Payment_Cache_ID = tl.Payment_Cache_ID AND tc.consultation_ID=pc.consultation_ID ">
            <input type="checkbox" class="domain" value=" tbl_item_list_cache tl, tbl_items ti, tbl_payment_cache pc ">
            <label>Services <span class="total">(9)</span></label>
            <ul>
              <li>
                <input type="checkbox" class="subdomain" value="Product_name">
                <label>Service Name</label>
              </li>
              <li>
                <input type="checkbox" class="subdomain" value="Check_In_Type">
                <label>Service Type</label>
                <!-- <ul>
                  <li>
                    <input type="checkbox" class="subject" value=" Check_In_Type='Pharmacy' ">
                    <label>Pharmacy</label>
                  </li>
                  <li>
                    <input type="checkbox" class="subject" value=" Check_In_Type='Laboratory' ">
                    <label>Laboratory</label>
                  </li>
                  <li>
                    <input type="checkbox" class="subject" value=" Check_In_Type='Procedure' ">
                    <label>Procedure</label>
                  </li>
                  <li>
                    <input type="checkbox" class="subject" value=" Check_In_Type='Radiology' ">
                    <label>Radiology</label>
                  </li>
                  <li>
                    <input type="checkbox" class="subject" value=" Check_In_Type='Others' ">
                    <label>Others</label>
                  </li>
                  <li>
                    <input type="checkbox" class="subject" value=" Check_In_Type='Doctor room' ">
                    <label>Consultation</label>
                  </li>
                </ul> -->
              </li>
              <li>
                <input type="checkbox" class="subdomain" value="Quantity">
                <label>Quantity</label>
              </li>
              <li>
                <input type="checkbox" class="subdomain" value="Price">
                <label>Price</label>
              </li>
              <li>
                <input type="checkbox" class="subdomain" value="Consultant">
                <label>Consultant</label>
              </li>
              <li>
                <input type="checkbox" class="subdomain" value="tl.Status">
                <label>Service Status</label>
                <!-- <ul>
                  <li>
                    <input type="checkbox" class="subject" value=" tl.Status='paid' ">
                    <label>Service Paid</label>
                  </li>
                  <li>
                    <input type="checkbox" class="subject" value=" tl.Status='served' ">
                    <label>Service Done</label>
                  </li> 
                  <li>
                    <input type="checkbox" class="subject" value=" tl.Status='active' ">
                    <label>Service not done</label>
                  </li>                  
                </ul> -->
      
              </li>
              <li>
                <input type="checkbox" class="subdomain" value="Transaction_Date_And_Time">
                <label>Service order date</label>
              </li>
              <!-- <li>
              <input type="checkbox" style="display: none;" class="whereclaus" value="  tc.Clinic_ID=cl.Clinic_ID ">
                <input type="checkbox" class="subdomain" value="tc.Clinic_ID">
                <label>Clinic name</label>
              </li> -->
            </ul>
          </li>
  
    </ul>
                </ul>
        </td>
        
    </tr>
    <tr>
      <td>
      <ul class="tree">
          <li class="has">
            
            <input type="checkbox" class="domain" value=" tbl_admission ad ">
            <input type="checkbox" style="display: none;" class="whereclaus" value=" ad.Registration_ID=pr.Registration_ID ">

            <label>Admissions IPD<span class="total">(6)</span></label>
            <ul>
              <li>
              <input type="checkbox" style="display: none;" class="whereclaus" value=" hw.Hospital_Ward_ID=ad.Hospital_Ward_ID ">
              <input type="checkbox" style="display: none;" class="domain" value=" tbl_hospital_ward hw ">
                <input type="checkbox" class="subdomain" value="Hospital_Ward_Name">
                <label>Admission Ward</label>
                <!-- <ul>
                  <li>
                    <input type="checkbox" class="subject" value="ward_room_id">
                    <label>Room</label>
                  </li>
                  <li>
                    <input type="checkbox" class="subject" value="Bed_Name">
                    <label>Bed Number</label>
                  </li>
                </ul> -->
              </li>
              <li>
                <input type="checkbox" class="subdomain" value="Admission_Date_Time">
                <label>Admission date</label>
              </li>
              <li>
                <input type="checkbox" class="subdomain" value="Admission_Status">
                <label>Admission Status</label>
              </li>
              <li>
                <input type="checkbox" class="subdomain" value="Discharge_Date_Time">
                <label>Discharge Date</label>
              </li>
              <li>
                <input type="checkbox" class="subdomain" value="Discharge_Reason">
                <label>Discharge Reason</label>
                <input type="checkbox" style="display: none;" class="whereclaus" value=" dr.Discharge_Reason_ID=ad.Discharge_Reason_ID ">
              <input type="checkbox" style="display: none;" class="domain" value=" tbl_discharge_reason dr ">
                <!-- <ul>
                  <?php 
                    // $select = mysqli_query($conn, "SELECT * FROM tbl_discharge_reason") or die(mysqli_error($conn));
                    // if(mysqli_num_rows($select)>0){
                    //   while($rw = mysqli_fetch_assoc($select)){
                    //     $Discharge_Reason = $rw['Discharge_Reason'];
                    //    echo " <li>
                    //       <input type='checkbox' class='subject' value=' Discharge_Reason_ID="."'". $Discharge_Reason_ID."'>
                    //       <label>$Discharge_Reason</label>
                    //     </li>";
                    //   }
                    // }
                  ?>
                </ul> -->
              </li>

              <li>
                
                <input type="checkbox" class="subdomain" value="New_return_admission">
                <label>Admission Type</label>
                <!-- <ul>
                  <li>
                    <input type="checkbox" class="subject" value=" ad.New_return_admission = 'New_Admission' ">
                    <label>New Admission</label>
                  </li>
                  <li>
                    <input type="checkbox" class="subject" value=" ad.New_return_admission ='Return_Admission' ">
                    <label>Return Admission</label>
                  </li>
                </ul> -->
              </li>
            </ul>
          </li>
      </ul>
      </td>
      <td>
      <ul class="tree">
          <li class="has">
          <input type="checkbox" class="domain" value=" tbl_ward_round wr, tbl_consultation lc ">
            <input type="checkbox" style="display: none;" class="whereclaus" value=" wr.consultation_ID=lc.consultation_ID ">
            
            <label>Ward Round IPD<span class="total">(4)</span></label>
            <ul>
              <li>
                <input type="checkbox" class="subdomain" value="clinical_history">
                <label>Patient History</label>
              </li>
              <li>
                <input type="checkbox" class="subdomain" value="wr.remarks">
                <label>Doctor remarks</label>
              </li>
              <li>
                <input type="checkbox" class="subdomain" value="Findings">
                <label>Findings</label>
              </li>
              <li>
                <input type="checkbox" class="subdomain" value="Ward_Round_Date_And_Time">
                <label>Ward Round Date</label>
              </li>
            </ul>
          </li>
      
            </ul>
      </td>
      <td>
      <ul class="tree">
          <li class="has">     
            <input type="checkbox" class="domain" value=" tbl_discharge_diagnosis dd,tbl_consultation lc, tbl_disease td ">
            <input type="checkbox" style="display: none;" class="whereclaus" value=" dd.consultation_ID=lc.consultation_ID AND dd.disease_ID=td.disease_ID ">
            <label>Ward round Disease IPD<span class="total">(4)</span></label>
            <ul>
              <li>
                <input type="checkbox" class="subdomain" value="diagnosis_type">
                <label>Diagnosis type</label>
              </li>
              <li>
                <input type="checkbox" class="subdomain" value="disease_name">
                <label>Disease name</label>
              </li>
              <li>
                <input type="checkbox" class="subdomain" value="disease_code">
                <label>Disease code</label>
              </li>
              <li>
                <input type="checkbox" class="subdomain" value="Doctor_Discharge_date">
                <label>Disease date</label>
              </li>
            </ul>
          </li>
      
            </ul>
      </td>
    </tr>
</table>
</fieldset>

<script>
    $(document).on('click', '.tree label', function(e) {
        $(this).next('ul').fadeToggle();
        e.stopPropagation();
    });

$(document).on('change', '.tree input[type=checkbox]', function(e) {
  $(this).siblings('ul').find("input[type='checkbox']").prop('checked', this.checked);
  $(this).parentsUntil('.tree').children("input[type='checkbox']").prop('checked', this.checked);
  e.stopPropagation();
});

$(document).on('click', 'button', function(e) {
  switch ($(this).text()) {
    case 'Collepsed':
      $('.tree ul').fadeOut();
      break;
    case 'Expanded':
      $('.tree ul').fadeIn();
      break;
    case 'Checked All':
      $(".tree input[type='checkbox']").prop('checked', true);
      break;
    case 'Unchek All':
      $(".tree input[type='checkbox']").prop('checked', false);
      break;
    default:
  }
});

</script>
<script>
  function exportdata(){

    // test code
    // var ward_round_diseases = []; 
    // $("input:checkbox[class='subdomain']:checked").each(function(){
    //   ward_round_diseases.push($(this).val());
    // });

    // var disease_consulatation = []; 
    // $("input:checkbox[class='subdomain']:checked").each(function(){
    //   disease_consulatation.push($(this).val());
    // });

    // alert(ward_round_diseases);
    // exit();


    // =============================================================
    
    var dateFilter = []; 
    $(".filter_date:checked").each(function() {
      dateFilter.push($(this).val());
    });
    if(dateFilter ==''){
      alert("please Select date filter");
      $(".filter_date").css("color", "red")
      exit;
    }

    var tablecolumn = []; 
    $(".subdomain:checked").each(function() {
    tablecolumn.push($(this).val());
    });

    var tables = []; 
    $(".domain:checked").each(function() {
    tables.push($(this).val());
    });
    
    var whereclaus = []; 
    $(".whereclaus:checked").each(function() {
      whereclaus.push($(this).val());
    });
    var selectin = []; 
    $(".subject:checked").each(function() {
    selectin.push($(this).val());
    });
    var datastring= {
      selectin:selectin,
      tables:tables,
      tablecolumn:tablecolumn
    }
    var datastring = datastring.toString();
    var Start_Date=$("#date").val();
    var End_Date=$("#date2").val();
    if(Start_Date==''){
      $("#date").css("border", "2px solid red");
      exit();
    }else{
        $("#date").css("border","2px solid gray");
       }

    if(End_Date==''){
      $("#date2").css("border", "2px solid red");
      exit();
    }else{
        $("#date2").css("border","2px solid gray");
       }
    window.open('exportrwdata.php?Start_Date='+Start_Date+'&End_Date='+End_Date+'&selectin='+selectin+'&tables='+tables+'&tablecolumn='+tablecolumn+'&datastring='+datastring+'&whereclaus='+whereclaus+'&dateFilter='+dateFilter, '_blank');
    
        // alert(datastring);
  }
        
</script>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script>
</script>
<script>
    $(document).ready(function () {
        $('select').select2();
    })
</script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>


<?php
include("./includes/footer.php");
?>
