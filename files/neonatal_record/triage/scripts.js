

function saveNewbornTriage(data)
{
  if(data =='Save')
  {
    var Registration_ID=$("#Registration_ID").val();
    var Employee_ID=$("#Employee_ID").val();
    var Admision_ID=$("#Admision_ID").val();
    var consultation_id=$("#consultation_id").val();
    var temp=$("#temp").val();
    var respirationNo=$("#respirationNo").val();
    var respiration = $("input[name='respiration']:checked").val();
    var difficultInBreathingType = $("input[name='difficultInBreathingType']:checked").val();
    var comment=$("#comment").val();
    var skinCirculation = $("input[name='skinCirculation']:checked").val();
    var pmtct = $("input[name='pmtct']:checked").val();
    var deliveryDateAndTime=$("#deliveryDateAndTime").val();
    var movements = $("input[name='movements']:checked").val();
    var others = $("input[name='others']:checked").val();
    var evaluationStage=$("#evaluationStage").val();
    var maternalFactors = $("input[name='maternalFactors']:checked").val();
    var apgar=$("#apgar").val();
    var birthWeight=$("#birthWeight").val();
    var maternalFactors2 = $("input[name='maternalFactors2']:checked").val();
    var feeding = $("input[name='feeding']:checked").val();
    var umbilicus = $("input[name='umbilicus']:checked").val();
    var feeding2 = $("input[name='feeding2']:checked").val();
    var umbilicus2 = $("input[name='umbilicus2']:checked").val();
    var currentWeight=$("#currentWeight").val();

    //alert('Clicked');

      if(confirm("Are you Sure you want to Save This?")){
          $.ajax({
          url:"save_newborn_triage_checklist_records.php",
          type:"POST",
          data:{
            Registration_ID:Registration_ID,
            Employee_ID:Employee_ID,
            Admision_ID:Admision_ID,
            consultation_id:consultation_id,
            temp:temp,
            respiration:respiration,
            respirationNo:respirationNo,
            difficultInBreathingType:difficultInBreathingType,
            comment:comment,
            skinCirculation:skinCirculation,
            pmtct:pmtct,
            deliveryDateAndTime:deliveryDateAndTime,
            movements:movements,
            others:others,
            evaluationStage:evaluationStage,
            maternalFactors:maternalFactors,
            apgar:apgar,
            birthWeight:birthWeight,
            maternalFactors2:maternalFactors2,
            feeding:feeding,
            umbilicus:umbilicus,
            feeding2:feeding2,
            umbilicus2:umbilicus2,
            currentWeight:currentWeight,
            action:"save_newborn"


          },
    	    success:function(data){
            alert(data);
            $("#Registration_ID").val("");
            $("#Employee_ID").val("");
            $("#temp").val("");
            $("#respirationNo").val("");
            $("input[name='respiration']:checked").val("");
            $("input[name='difficultInBreathingType']:checked").val("");
            $("#comment").val("");
            $("input[name='skinCirculation']:checked").val("");
            $("input[name='pmtct']:checked").val("");
            $("#deliveryDateAndTime").val("");
            $("input[name='movements']:checked").val("");
            $("input[name='others']:checked").val("");
            $("#evaluationStage").val("");
            $("input[name='maternalFactors']:checked").val("");
            $("#apgar").val("");
            $("#birthWeight").val("");
            $("input[name='maternalFactors2']:checked").val("");
            $("input[name='feeding']:checked").val("");
            $("input[name='umbilicus']:checked").val("");
            $("input[name='feeding2']:checked").val("");
            $("input[name='umbilicus2']:checked").val("");
            $("#currentWeight").val("");

            location.reload(true);
          }
        })

    }//end confirmation

  }

}



function selectEvaluation(data)
{
  if(data =="firstEvaluation")
  {
    //alert('1st Evaluation Selected');
    $("#first1").css("display", "block");
    $("#firstIn1").css("display", "block");
    $("#first2").css("display", "block");
    $("#firstIn2").css("display", "block");
    $("#first3").css("display", "block");
    $("#firstIn3").css("display", "block");

    // hide 2nd evaluation
    $("#second1").css("display", "none");
    $("#secondIn1").css("display", "none");
    $("#secon2").css("display", "none");
    $("#secondIn2").css("display", "none");
    $("#second3").css("display", "none");
    $("#secondIn3").css("display", "none");
    // hide 3rd evaluation
    $("#third1").css("display", "none");
    $("#thirdIn1").css("display", "none");
    $("#third2").css("display", "none");
    $("#thirdIn2").css("display", "none");
    $("#third3").css("display", "none");
    $("#thirdIn3").css("display", "none");
  }

  if(data =="secondEvaluation")
  {
    $("#second1").css("display", "block");
    $("#secondIn1").css("display", "block");
    $("#second2").css("display", "block");
    $("#secondIn2").css("display", "block");
    $("#second3").css("display", "block");
    $("#secondIn3").css("display", "block");
    // hide 1st evaluation
    $("#first1").css("display", "none");
    $("#firstIn1").css("display", "none");
    $("#first2").css("display", "none");
    $("#firstIn2").css("display", "none");
    $("#first3").css("display", "none");
    $("#firstIn3").css("display", "none");
    // hide 3rd evaluation
    $("#third1").css("display", "none");
    $("#thirdIn1").css("display", "none");
    $("#third2").css("display", "none");
    $("#thirdIn2").css("display", "none");
    $("#third3").css("display", "none");
    $("#thirdIn3").css("display", "none");

  }

  if(data =="thirdEvaluation")
  {
    $("#third1").css("display", "block");
    $("#thirdIn1").css("display", "block");
    $("#third2").css("display", "block");
    $("#thirdIn2").css("display", "block");
    $("#third3").css("display", "block");
    $("#thirdIn3").css("display", "block");

    // hide 1st evaluation
    $("#first1").css("display", "none");
    $("#firstIn1").css("display", "none");
    $("#first2").css("display", "none");
    $("#firstIn2").css("display", "none");
    $("#first3").css("display", "none");
    $("#firstIn3").css("display", "none");

    // hide 2nd evaluation
    $("#second1").css("display", "none");
    $("#secondIn1").css("display", "none");
    $("#second2").css("display", "none");
    $("#secondIn2").css("display", "none");
    $("#second3").css("display", "none");
    $("#secondIn3").css("display", "none");
  }

  if(data =="select")
  {
    // hide 1st evaluation
    $("#first1").css("display", "none");
    $("#firstIn1").css("display", "none");
    $("#first2").css("display", "none");
    $("#firstIn2").css("display", "none");
    $("#first3").css("display", "none");
    $("#firstIn3").css("display", "none");
    // hide 2nd evaluation
    $("#second1").css("display", "none");
    $("#secondIn1").css("display", "none");
    $("#second2").css("display", "none");
    $("#secondIn2").css("display", "none");
    $("#second3").css("display", "none");
    $("#secondIn3").css("display", "none");
    // hide 3rd evaluation
    $("#third1").css("display", "none");
    $("#thirdIn1").css("display", "none");
    $("#third2").css("display", "none");
    $("#thirdIn2").css("display", "none");
    $("#third3").css("display", "none");
    $("#thirdIn3").css("display", "none");
  }
}
//end


function saveObservationDetails(data)
{
  if(data =='Save')
  {
    var Registration_ID=$("#Registration_ID").val();
    var Employee_ID=$("#Employee_ID").val();
    var temp=$("#temp").val();
    var delivery_year=$("#delivery_year").val();
    var respiration = $("#respiration").val();
    var weight=$("#weight").val();
    var feeding = $("input[name='feeding']:checked").val();
    var movements = $("input[name='movements']:checked").val();
    var comment=$("#comment").val();

    //alert('Clicked');

      if(confirm("Are you Sure you want to Save This?")){
          $.ajax({
          url:"save_newborn_triage_checklist_records.php",
          type:"POST",
          data:{
            Registration_ID:Registration_ID,
            Employee_ID:Employee_ID,
            temp:temp,
            respiration:respiration,
            delivery_year:delivery_year,
            weight:weight,
            feeding:feeding,
            comment:comment,
            movements:movements,
            action1:"save_observation"


          },
    	    success:function(data){
            alert(data);
            $("#Registration_ID").val("");
            $("#Employee_ID").val("");
            $("#temp").val("");
            $("input[name='respiration']:checked").val("");
            $("#comment").val("");
            $("#delivery_year").val("");
            $("#weight").val("");

            location.reload(true);
          }
        })

    }//end confirmation

  }

}


$(document).ready(function(e){

  $('.date').datetimepicker({value: '', step: 2});
})
