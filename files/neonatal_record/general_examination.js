var app = angular.module('myApp',[]);

app.controller('myCtl',function($scope,$http,$timeout){
  $scope.success = "";
  $scope.error = "";
  $scope.title = "Mataka";

  $scope.generals = {};

  $scope.formDiv = true;
  $scope.tableDiv = false;
  $scope.tableLastYearDiv = false;

  // show last year table div
  $scope.showLastYearTable = function()
  {
    $scope.formDiv = false;
    $scope.tableDiv = false;
    $scope.tableLastYearDiv = true;
  }

  // show table div
  $scope.showTable = function()
  {
    $scope.formDiv = false;
    $scope.tableDiv = true;
    $scope.tableLastYearDiv = false;
  }

  // show form div
  $scope.showForm = function()
  {
    $scope.formDiv = true;
    $scope.tableDiv = false;
    $scope.tableLastYearDiv = false;
  }


  $scope.refresh = function()
  {
    var refreshTb = document.querySelector('#refreshTb').innerHTML;
  }



// get year of all records
  $scope.getYear = function()
  {
    var y = document.getElementById('year').value;

    $scope.getGeneralExamination(y);
  }

// retrieve data
  $scope.getGeneralExamination = function(y)
  {
    var Registration_ID = document.getElementById('Registration_ID').value;

    var url = "http://172.19.3.3/ehmsbmc/files/save_general_examination.php?action=get_exemination&Registration_ID="+Registration_ID+"&year="+y+"";
    $http.get(url).then(function(res){
      $scope.generals =  res.data;
      console.log($scope.generals);
    });
  }


// save the form data
$scope.saveGeneralExamination = function()
{

  var url = "http://172.19.3.3/ehmsbmc/files/save_general_examination.php";

  if (confirm('Are you sure want to save this?')) {
    $http.post(url,
      {
        "Registration_ID":document.querySelector('#Registration_ID').value,
        "Employee_ID":document.querySelector('#Employee_ID').value,
        "consultation_id":document.querySelector('#consultation_id').value,
        "admission_id":document.querySelector('#admission_id').value,
        "Pale":$scope.Pale,
        "Jaundice":$scope.Jaundice,
        "Oedema":$scope.Oedema,
        "Dyspnocic":$scope.Dyspnocic,
        "Shape_of_abdomen":$scope.Shape_of_abdomen,
        "Previous_scar":$scope.Previous_scar,
        "Previous_scar_yes_times":$scope.Previous_scar_yes_times,
        "Bundles_ring":$scope.Bundles_ring,
        "Fetal_movement":$scope.Fetal_movement,
        "Skin_hanges":$scope.Skin_hanges,
        "FH":$scope.FH,
        "Number_of_fetus":$scope.Number_of_fetus,
        "Lie":$scope.Lie,
        "Presentation":$scope.Presentation,
        "Position":$scope.Position,
        "Head_level":$scope.Head_level,
        "Contraction":$scope.Contraction,
        "FHR":$scope.FHR,
        "Auscultation_strength":$scope.Auscultation_strength,
        "Auscultation_shape":$scope.Auscultation_shape,
        "State_of_vulva":$scope.State_of_vulva,
        "Pv_discharge":$scope.Pv_discharge,
        "Pv_discharge_yes_colour":$scope.Pv_discharge_yes_colour,
        "Pv_discharge_yes_smell":$scope.Pv_discharge_yes_smell,
        "State_of_vagina":$scope.State_of_vagina,
        "State_of_Cx_Soft":$scope.State_of_Cx_Soft,
        "State_of_Cx_Rigid":$scope.State_of_Cx_Rigid,
        "State_of_Cx_Thin":$scope.State_of_Cx_Thin,
        "State_of_Cx_Thick":$scope.State_of_Cx_Thick,
        "State_of_Cx_Swollen":$scope.State_of_Cx_Swollen,
        "Cervical_dilatation":$scope.Cervical_dilatation,
        "Affacement":$scope.Affacement,
        "Membranes":$scope.Membranes,
        "Membranes_Date_and_Time":$scope.Membranes_Date_and_Time,
        "liquor_colour":$scope.liquor_colour,
        "liquor_smell":$scope.liquor_smell,
        "Presenting_part":$scope.Presenting_part,
        "Breech_type":$scope.Breech_type,
        "Sacro_promontory":$scope.Sacro_promontory,
        "Sacral_curve":$scope.Sacral_curve,
        "Ischial_spine":$scope.Ischial_spine,
        "Pubic_angle":$scope.Pubic_angle,
        "Knuckles":$scope.Knuckles,
        "Remark":$scope.Remark,
        "Midwifery_Opinions":$scope.Midwifery_Opinions,
        "Plans":$scope.Plans,
        "action":"save_general"
      }
    ).then(function(res){
        $scope.success = res.data;
        $scope.refresh();
        $scope.clear();
        console.log($scope.success);

        $timeout(function(){
          $scope.success = "";
        },5000);
    });
  }//end if

}//end main function



// clear inputs
$scope.clear = function()
{
  $scope.Pale = "";
  $scope.Jaundice = "";
  $scope.Oedema = "";
  $scope.Dyspnocic = "";
  $scope.Shape_of_abdomen = "";
  $scope.Previous_scar = "";
  $scope.Previous_scar_yes_times = "";
  $scope.Bundles_ring = "";
  $scope.Fetal_movement = "";
  $scope.Skin_hanges = "";
  $scope.FH = "";
  $scope.Number_of_fetus = "";
  $scope.Lie = "";
  $scope.Presentation = "";
  $scope.Position = "";
  $scope.Head_level = "";
  $scope.Contraction = "";
  $scope.FHR = "";
  $scope.Auscultation_strength = "";
  $scope.Auscultation_shape = "";
  $scope.State_of_vulva = "";
  $scope.Pv_discharge = "";
  $scope.Pv_discharge_yes_colour = "";
  $scope.Pv_discharge_yes_smell = "";
  $scope.State_of_vagina = "";
  $scope.State_of_Cx_Soft = "";
  $scope.State_of_Cx_Rigid = "";
  $scope.State_of_Cx_Thin = "";
  $scope.State_of_Cx_Thick = "";
  $scope.State_of_Cx_Swollen = "";
  $scope.Cervical_dilatation = "";
  $scope.Affacement = "";
  $scope.Membranes = "";
  $scope.Membranes_Date_and_Time = "";
  $scope.liquor_colour= "";
  $scope.liquor_smell = "";
  $scope.Presenting_part = "";
  $scope.Breech_type = "";
  $scope.Sacro_promontory = "";
  $scope.Sacral_curve = "";
  $scope.Ischial_spine = "";
  $scope.Pubic_angle = "";
  $scope.Knuckles = "";
  $scope.Remark = "";
  $scope.Midwifery_Opinions = "";
  $scope.Plans = "";
}
});
