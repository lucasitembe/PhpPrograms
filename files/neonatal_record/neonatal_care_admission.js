var app =  angular.module('neonatalCare',[]);

app.controller('neonatalCtl',function($scope,$http,$timeout){

$scope.divForm = false;
$scope.tableDiv = true;
$scope.lastYears = true;
$scope.success = "";
$scope.error = "";

$scope.datas = {};
$scope.previous = {};
$scope.antenatals = {};
$scope.delivery = {};
$scope.postnatals = {};
$scope.babies = {};
$scope.examination1 = {};
$scope.examination2 = {};
$scope.management = {};

$scope.datas1 = {};
$scope.previous1 = {};
$scope.antenatals1 = {};
$scope.delivery1 = {};
$scope.postnatals1 = {};
$scope.babies1 = {};
$scope.examination11 = {};
$scope.examination21 = {};
$scope.management1 = {};

//show table
  $scope.showLastYearTable = function()
  {
    $scope.lastYears = false;
    $scope.divForm = true;
    $scope.tableDiv = true;

  }


//show table
  $scope.showTable = function()
  {
    $scope.lastYears = true;
    $scope.divForm = true;
    $scope.tableDiv = false;

  }


  // show form
  $scope.showForm = function()
    {
      $scope.lastYears = true;
      $scope.divForm = false;
      $scope.tableDiv = true;
    }

//get all functions Which called by year
$scope.getAll = function()
{
  var y = document.getElementById('year').value;
  // alert(y);
    $scope.getDataByYear(y);
    $scope.getPreviousByYear(y);
    $scope.getAntenatalByYear(y);
    $scope.getDeliveryByYear(y);
    $scope.getPostnatalByYear(y);
    $scope.getBabyByYear(y);
    $scope.getPhysical1ByYear(y);
    $scope.getPhysical2ByYear(y);
    $scope.getManagementByYear(y);
}


$scope.initializer = function()
{
  $scope.getData();
  $scope.getPrevious();
  $scope.getAntenatal();
  $scope.getDelivery();
  $scope.getPostnatal();
  $scope.getBaby();
  $scope.getPhysical1();
  $scope.getPhysical2();
  $scope.getManagement();
}


//get data
$scope.getData = function()
{
var Registration_ID = document.getElementById('Registration_ID').value;
var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_neonatal_care_admission.php?action=get_data&Registration_ID="+Registration_ID+"";
$http.get(url).then(function(res){
  $scope.datas =  res.data;
  console.log($scope.datas);
});
}

//get data by year
$scope.getDataByYear = function(y)
{
var Registration_ID = document.getElementById('Registration_ID').value;
var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_neonatal_care_admission.php?action=get_data1&Registration_ID="+Registration_ID+"&year="+y+"";
$http.get(url).then(function(res){
  $scope.datas1 =  res.data;
  console.log($scope.datas1);
});
}


//get HISTORY-PREVIOUS
$scope.getPrevious = function()
{
var Registration_ID = document.getElementById('Registration_ID').value;
var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_neonatal_care_admission.php?action=get_previous&Registration_ID="+Registration_ID+"";
$http.get(url).then(function(res){
  $scope.previous =  res.data;
  console.log($scope.previous);
});
}


//get HISTORY-PREVIOUS by year
$scope.getPreviousByYear = function(y)
{
var Registration_ID = document.getElementById('Registration_ID').value;
var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_neonatal_care_admission.php?action=get_previous1&Registration_ID="+Registration_ID+"&year="+y+"";
$http.get(url).then(function(res){
  $scope.previous1 =  res.data;
  console.log($scope.previous1);
});
}



//get Antenatal history
$scope.getAntenatal = function()
{
var Registration_ID = document.getElementById('Registration_ID').value;
var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_neonatal_care_admission.php?action=get_antenatal&Registration_ID="+Registration_ID+"";
$http.get(url).then(function(res){
  $scope.antenatals =  res.data;
  console.log($scope.antenatals);
});
}

//get Antenatal history by year
$scope.getAntenatalByYear = function(y)
{
var Registration_ID = document.getElementById('Registration_ID').value;
var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_neonatal_care_admission.php?action=get_antenatal1&Registration_ID="+Registration_ID+"&year="+y+"";
$http.get(url).then(function(res){
  $scope.antenatals1 =  res.data;
  console.log($scope.antenatals1);
});
}


//get Delivery history
$scope.getDelivery = function()
{
var Registration_ID = document.getElementById('Registration_ID').value;
var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_neonatal_care_admission.php?action=get_delivery&Registration_ID="+Registration_ID+"";
$http.get(url).then(function(res){
  $scope.delivery =  res.data;
  console.log($scope.delivery);
});
}


//get Delivery history year
$scope.getDeliveryByYear = function(y)
{
var Registration_ID = document.getElementById('Registration_ID').value;
var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_neonatal_care_admission.php?action=get_delivery1&Registration_ID="+Registration_ID+"&year="+y+"";
$http.get(url).then(function(res){
  $scope.delivery1 =  res.data;
  console.log($scope.delivery1);
});
}


//get Postnatal history
$scope.getPostnatal = function()
{
var Registration_ID = document.getElementById('Registration_ID').value;
var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_neonatal_care_admission.php?action=get_postnatal&Registration_ID="+Registration_ID+"";
$http.get(url).then(function(res){
  $scope.postnatals =  res.data;
  console.log($scope.postnatals);
});
}

//get Postnatal history by year
$scope.getPostnatalByYear = function(y)
{
var Registration_ID = document.getElementById('Registration_ID').value;
var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_neonatal_care_admission.php?action=get_postnatal1&Registration_ID="+Registration_ID+"&year="+y+"";
$http.get(url).then(function(res){
  $scope.postnatals1 =  res.data;
  console.log($scope.postnatals1);
});
}



//get History of the baby
$scope.getBaby = function()
{
var Registration_ID = document.getElementById('Registration_ID').value;
var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_neonatal_care_admission.php?action=get_baby&Registration_ID="+Registration_ID+"";
$http.get(url).then(function(res){
  $scope.babies =  res.data;
  console.log($scope.babies);
});
}


//get History of the baby by year
$scope.getBabyByYear = function(y)
{
var Registration_ID = document.getElementById('Registration_ID').value;
var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_neonatal_care_admission.php?action=get_baby1&Registration_ID="+Registration_ID+"&year="+y+"";
$http.get(url).then(function(res){
  $scope.babies1 =  res.data;
  console.log($scope.babies1);
});
}


//get PHYSICAL EXAMINATION
$scope.getPhysical1 = function()
{
var Registration_ID = document.getElementById('Registration_ID').value;
var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_neonatal_care_admission.php?action=get_physical1&Registration_ID="+Registration_ID+"";
$http.get(url).then(function(res){
  $scope.examination1 =  res.data;
  console.log($scope.examination1);
});
}


//get PHYSICAL EXAMINATION by year
$scope.getPhysical1ByYear = function(y)
{
var Registration_ID = document.getElementById('Registration_ID').value;
var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_neonatal_care_admission.php?action=get_physical11&Registration_ID="+Registration_ID+"&year="+y+"";
$http.get(url).then(function(res){
  $scope.examination11 =  res.data;
  console.log($scope.examination11);
});
}



//get physical_examination2
$scope.getPhysical2 = function()
{
var Registration_ID = document.getElementById('Registration_ID').value;
var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_neonatal_care_admission.php?action=get_physical2&Registration_ID="+Registration_ID+"";
$http.get(url).then(function(res){
  $scope.examination2 =  res.data;
  console.log($scope.examination2);
});
}


//get physical_examination2 by year
$scope.getPhysical2ByYear = function(y)
{
var Registration_ID = document.getElementById('Registration_ID').value;
var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_neonatal_care_admission.php?action=get_physical21&Registration_ID="+Registration_ID+"&year="+y+"";
$http.get(url).then(function(res){
  $scope.examination21 =  res.data;
  console.log($scope.examination21);
});
}




//get MANAGEMENT
$scope.getManagement = function()
{
var Registration_ID = document.getElementById('Registration_ID').value;
var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_neonatal_care_admission.php?action=get_management&Registration_ID="+Registration_ID+"";
$http.get(url).then(function(res){
  $scope.management =  res.data;
  console.log($scope.management);
});
}


//get MANAGEMENT by year
$scope.getManagementByYear = function(y)
{
var Registration_ID = document.getElementById('Registration_ID').value;
var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_neonatal_care_admission.php?action=get_management1&Registration_ID="+Registration_ID+"&year="+y+"";
$http.get(url).then(function(res){
  $scope.management1 =  res.data;
  console.log($scope.management1);
});
}


//save neonatal care admmission data
$scope.saveNonatal = function()
{
var url = "http://192.168.112.1/ehmsbmc/files/neonatal_record/save_neonatal_care_admission.php";

if (confirm('Are you sure want to save this?')) {

    $http.post(url,{
        "name_of_baby":$scope.name_of_baby,
        "referral_from":$scope.referral_from,
        "Admision_ID":document.getElementById('Admision_ID').value,
        "consultation_id":document.getElementById('consultation_id').value,
        "Employee_ID":document.getElementById('Employee_ID').value,
        "Registration_ID":document.getElementById('Registration_ID').value,
        "transer_from_maternity":$scope.transer_from_maternity,
        "date_birth":$scope.date_birth,
        "admission_date":$scope.admission_date,
        "length_cm":$scope.length_cm,
        "head_circumference_cm":$scope.head_circumference_cm,
        "pmtct":$scope.pmtct,
        "gender":$scope.gender,
        "apgar_score":$scope.apgar_score,
        "ga":$scope.ga,
        "birth_weight":$scope.birth_weight,
        "chronical_maternal_illiness":$scope.chronical_maternal_illiness,
        "family_illnesses":$scope.family_illnesses,
        "gravida":$scope.gravida,
        "para":$scope.para,
        "number_of_living_children":$scope.number_of_living_children,
        "known_problem_of_living_children":$scope.known_problem_of_living_children,
        "complication_during_previous_pregnancies":$scope.complication_during_previous_pregnancies,
        "marital_status":$scope.marital_status,
        "lnmp":$scope.lnmp,
        "edd":$scope.edd,
        "vdrl":$scope.vdrl,
        "malaria":$scope.malaria,
        "hep_b":$scope.hep_b,
        "hb_level":$scope.hb_level,
        "hypertension":$scope.hypertension,
        "blood_pressure":$scope.blood_pressure,
        "drug_abuse":$scope.drug_abuse,
        "blood_group_rh":$scope.blood_group_rh,
        "anc_attended":$scope.anc_attended,
        "where_anc_done":$scope.where_anc_done,
        "number_of_visits":$scope.number_of_visits,
        "ga_at_1st_visit":$scope.ga_at_1st_visit,
        "maternal_fever":$scope.maternal_fever,
        "ab_treatment":$scope.ab_treatment,
        "ab_treatment_yes_drug":$scope.ab_treatment_yes_drug,
        "prom":$scope.prom,
        "prom_yes_hrs":$scope.prom_yes_hrs,
        "amniotic_fluid":$scope.amniotic_fluid,
        "abnormalities_of_placenta":$scope.abnormalities_of_placenta,
        "abnormalities_of_placenta_yes":$scope.abnormalities_of_placenta_yes,
        "abnormal_presentation":$scope.abnormal_presentation,
        "abnormal_presentation_yes":$scope.abnormal_presentation_yes,
        "mode_of_delivery":$scope.mode_of_delivery,
        "cs":$scope.cs,
        "indication":$scope.indication,
        "duration_of_cs":$scope.duration_of_cs,
        "duration_of_labour_stage1":$scope.duration_of_labour_stage1,
        "duration_of_labour_stage2":$scope.duration_of_labour_stage2,
        "duration_of_labour_stage3":$scope.duration_of_labour_stage3,
        "obstructed_labour":$scope.obstructed_labour,
        "place_of_delivery":$scope.place_of_delivery,
        "delivery_attendant":$scope.delivery_attendant,
        "if_assisted_delivery_why":$scope.if_assisted_delivery_why,
        "problems_of_baby_after_birth":$scope.problems_of_baby_after_birth,
        "resuscitation":$scope.resuscitation,
        "resuscitation_yes":$scope.resuscitation_yes,
        "eye_prophylaxis":$scope.eye_prophylaxis,
        "vitamin_K_given":$scope.vitamin_K_given,
        "drugs_given":$scope.drugs_given,
        "drugs_given_yes_which":$scope.drugs_given_yes_which,
        "feeding_started_within_1_hour":$scope.feeding_started_within_1_hour,
        "chief_complaints":$scope.chief_complaints,
        "fever":$scope.fever,
        "vomiting":$scope.vomiting,
        "feeding":$scope.feeding,
        "enough_breast_milk":$scope.enough_breast_milk,
        "feeding_interval":$scope.feeding_interval,
        "passage_of_urine":$scope.passage_of_urine,
        "passage_of_stool":$scope.passage_of_stool,
        "quality":$scope.quality,
        "other_complaints":$scope.other_complaints,
        "baby_recieve_any_vaccines":$scope.baby_recieve_any_vaccines,
        "weight":$scope.weight,
        "temp":$scope.temp,
        "pulse":$scope.pulse,
        "resp_rate":$scope.resp_rate,
        "SpO2":$scope.SpO2,
        "rbg":$scope.rbg,
        "appearance_condition":$scope.appearance_condition,
        "appearance_activeness":$scope.appearance_activeness,
        "appearance_nourished":$scope.appearance_nourished,
        "appearance_Pathol":$scope.appearance_Pathol,
        "appearance_comment":$scope.appearance_comment,
        "skin_temperature":$scope.skin_temperature,
        "skin_color":$scope.skin_color,
        "skin_turgor":$scope.skin_turgor,
        "skin_cyanosed":$scope.skin_cyanosed,
        "skin_cyanosed_yes":$scope.skin_cyanosed_yes,
        "skin_rashes":$scope.skin_rashes,
        "skin_ctr":$scope.skin_ctr,
        "head1":$scope.head1,
        "head1_shape":$scope.head1_shape,
        "head1_fontanelle":$scope.head1_fontanelle,
        "head1_sutures":$scope.head1_sutures,
        "head1_swelling_trauma":$scope.head1_swelling_trauma,
        "head1_size":$scope.head1_size,
        "head2_other_malformation":$scope.head2_other_malformation,
        "head2_eye_discharge":$scope.head2_eye_discharge,
        "neck_lymphadenopathy":$scope.neck_lymphadenopathy,
        "neck_lymphadenopathy_yes":$scope.neck_lymphadenopathy_yes,
        "neck_clavicle_fractured":$scope.neck_clavicle_fractured,
        "breathing_chest_movement":$scope.breathing_chest_movement,
        "breathing_indrawing":$scope.breathing_indrawing,
        "breathing_sounds":$scope.breathing_sounds,
        "breathing_preterm":$scope.breathing_preterm,
        "heart_rhythm":$scope.heart_rhythm,
        "heart_murmurs":$scope.heart_murmurs,
        "heart_describe":$scope.heart_describe,
        "abdomen":$scope.abdomen,
        "umbillical_cord":$scope.umbillical_cord,
        "genitalia_male":$scope.genitalia_male,
        "genitalia_testis":$scope.genitalia_testis,
        "genitalia_ambiguous":$scope.genitalia_ambiguous,
        "genitalia_female":$scope.genitalia_female,
        "genitalia_female_describe":$scope.genitalia_female_describe,
        "anus_patent":$scope.anus_patent,
        "anus_patent_no_describe":$scope.anus_patent_no_describe,
        "anus_abdnormality":$scope.anus_abdnormality,
        "back_posture":$scope.back_posture,
        "back_malformation":$scope.back_malformation,
        "back_malformation_hints":$scope.back_malformation_hints,
        "neurology_spotaneous_movement":$scope.neurology_spotaneous_movement,
        "neurology_musde_tone":$scope.neurology_musde_tone,
        "neurology_flexes_glasping":$scope.neurology_flexes_glasping,
        "neurology_flexes_sucking":$scope.neurology_flexes_sucking,
        "neurology_flexes_traction":$scope.neurology_flexes_traction,
        "neurology_flexes_moro":$scope.neurology_flexes_moro,
        "finnstroem_score":$scope.finnstroem_score,
        "additional_findings":$scope.additional_findings,
        "key_findings":$scope.key_findings,
        "provisional_diagnoses":$scope.provisional_diagnoses,
        "differential_diagnoses":$scope.differential_diagnoses,
        "investigation":$scope.investigation,
        "treatment":$scope.treatment,
        "supportive_care":$scope.supportive_care,
        "preventions":$scope.preventions,
        "action":"save_neonatal"

    }).then(function(res){

      $scope.success = res.data;
      $scope.initializer();

      //success msg timeout
      $timeout(function(){
        $scope.success = "";
      },5000);

      //clear inputs
      $scope.weight = "";
      $scope.temp = "";
      $scope.pulse = "";
      $scope.resp_rate = "";
      $scope.SpO2 = "";
      $scope.rbg = "";



    });
} //end confirm

}




$scope.clearInputs = function()
{
      $scope.name_of_baby = "";
      $scope.referral_from = "";
      $scope.transer_from_maternity = "";
      $scope.date_birth = "";
      $scope.admission_date = "";
      $scope.length_cm = "";
      $scope.head_circumference_cm = "";
      $scope.pmtct = "";
      $scope.apgar_score = "";
      $scope.ga = "";
      $scope.birth_weight = "";
      $scope.chronical_maternal_illiness = "";
      $scope.family_illnesses = "";
      $scope.gravida = "";
      $scope.para = "";
      $scope.number_of_living_children = "";
      $scope.known_problem_of_living_children = "";
      $scope.complication_during_previous_pregnancies = "";
      $scope.marital_status = "";
      $scope.lnmp = "";
      $scope.edd = "";
      $scope.vdrl = "";
      $scope.malaria = "";
      $scope.hep_b = "";
      $scope.hb_level = "";
      $scope.hypertension = "";
      $scope.blood_pressure = "";
      $scope.drug_abuse = "";
      $scope.blood_group_rh = "";
      $scope.anc_attended = "";
      $scope.where_anc_done = "";
      $scope.number_of_visits = "";
      $scope.ga_at_1st_visit = "";
      $scope.maternal_fever = "";
      $scope.ab_treatment = "";
      $scope.ab_treatment_yes_drug = "";
      $scope.prom = "";
      $scope.prom_yes_hrs = "";
      $scope.amniotic_fluid = "";
      $scope.abnormalities_of_placenta = "";
      $scope.abnormalities_of_placenta_yes = "";
      $scope.abnormal_presentation = "";
      $scope.abnormal_presentation_yes = "";
      $scope.mode_of_delivery = "";
      $scope.cs = "";
      $scope.indication = "";
      $scope.duration_of_cs = "";
      $scope.duration_of_labour_stage1 = "";
      $scope.duration_of_labour_stage2 = "";
      $scope.duration_of_labour_stage3 = "";
      $scope.obstructed_labour = "";
      $scope.place_of_delivery = "";
      $scope.delivery_attendant = "";
      $scope.if_assisted_delivery_why = "";
      $scope.problems_of_baby_after_birth = "";
      $scope.resuscitation = "";
      $scope.resuscitation_yes = "";
      $scope.eye_prophylaxis = "";
      scope.vitamin_K_given = "";
      $scope.drugs_given = "";
      $scope.drugs_given_yes_which = "";
      $scope.feeding_started_within_1_hour = "";
      $scope.chief_complaints = "";
      $scope.fever = "";
      $scope.vomiting = "";
      $scope.feeding = "";
      $scope.enough_breast_milk = "";
      $scope.feeding_interval = "";
      $scope.passage_of_urine = "";
      $scope.passage_of_stool = "";
      $scope.quality = "";
      $scope.other_complaints = "";
      $scope.baby_recieve_any_vaccines = "";
      $scope.weight = "";
      $scope.temp = "";
      $scope.pulse = "";
      $scope.resp_rate = "";
      $scope.SpO2 = "";
      $scope.rbg = "";
      $scope.appearance_condition = "";
      $scope.appearance_activeness = "";
      $scope.appearance_nourished = "";
      $scope.appearance_Pathol = "";
      $scope.appearance_comment = "";
      $scope.skin_temperature = "";
      $scope.skin_color = "";
      $scope.skin_turgor = "";
      $scope.skin_cyanosed = "";
      $scope.skin_cyanosed_yes = "";
      $scope.skin_rashes = "";
      $scope.skin_ctr = "";
      $scope.head1 = "";
      $scope.head1_shape = "";
      $scope.head1_fontanelle = "";
      $scope.head1_sutures = "";
      $scope.head1_swelling_trauma = "";
      $scope.head1_size = "";
      $scope.head2_other_malformation = "";
      $scope.head2_eye_discharge = "";
      $scope.neck_lymphadenopathy = "";
      $scope.neck_lymphadenopathy_yes = "";
      $scope.neck_clavicle_fractured = "";
      $scope.breathing_chest_movement = "";
      $scope.breathing_indrawing = "";
      $scope.breathing_sounds = "";
      $scope.breathing_preterm = "";
      $scope.heart_rhythm = "";
      $scope.heart_murmurs = "";
      $scope.heart_describe = "";
      $scope.abdomen = "";
      $scope.umbillical_cord = "";
      $scope.genitalia_male = "";
      $scope.genitalia_testis = "";
      $scope.genitalia_ambiguous = "";
      $scope.genitalia_female = "";
      $scope.genitalia_female_describe = "";
      $scope.anus_patent = "";
      $scope.anus_patent_no_describe = "";
      $scope.anus_abdnormality = "";
      $scope.back_posture = "";
      $scope.back_malformation = "";
      $scope.back_malformation_hints = "";
      $scope.neurology_spotaneous_movement = "";
      $scope.neurology_musde_tone = "";
      $scope.neurology_flexes_glasping = "";
      $scope.neurology_flexes_sucking = "";
      $scope.neurology_flexes_traction = "";
      $scope.neurology_flexes_moro = "";
      $scope.finnstroem_score = "";
      $scope.additional_findings = "";
      $scope.key_findings = "";
      $scope.provisional_diagnoses = "";
      $scope.differential_diagnoses = "";
      $scope.investigation = "";
      $scope.treatment = "";
      $scope.supportive_care = "";
      $scope.preventions = "";

}


});
