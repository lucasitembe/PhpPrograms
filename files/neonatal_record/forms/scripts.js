
function savePostnatal(act)
{
	if(act == "Save")
	{
    	var Admision_ID=$("#Admision_ID").val();
    	var consultation_id=$("#consultation_id").val();
			var employeeId=$("#employeeId").val();
    	var registrationId=$("#registrationId").val();
    	var parity=$("#parity").val();
    	var living=$("#living").val();
      var pmtct = $("input[name='pmtct']:checked").val();
    	var deliveryDateAndTime=$("#deliveryDateAndTime").val();
    	var niverapine=$("#niverapine").val();
    	var niverapineTime=$("#niverapineTime").val();
    	var babyCondition=$("#babyCondition").val();
    	var anyAbnormalities=$("#anyAbnormalities").val();
    	var apgarScore=$("#apgarScore").val();
    	var bwt=$("#bwt").val();
    	var bp=$("#bp").val();
    	var temp=$("#temp").val();
    	var pulse=$("#pulse").val();
    	var restRate=$("#restRate").val();
      var fh=$("#fh").val();
    	var physicalAppearance=$("input[name='physicalAppearance']:checked").val();
    	var hxOfFever=$("input[name='hxOfFever']:checked").val();
      var perinealTearType = $("input[name='perinealTearType']:checked").val();
    	var pallor=$("input[name='pallor']:checked").val();
    	var pain=$("input[name='pain']:checked").val();
    	var complains=$("#complains").val();
    	var wound=$("input[name='wound']:checked").val();
    	var nipples=$("input[name='nipples']:checked").val();
    	var BreastSecreteMilk=$("input[name='BreastSecreteMilk']:checked").val();
    	var uteras=$("input[name='uteras']:checked").val();
    	var perinealPad=$("input[name='perinealPad']:checked").val();
    	var pvBleeding=$("input[name='pvBleeding']:checked").val();
    	var estimatedBloodLoss=$("#estimatedBloodLoss").val();
    	var interpretation=$("#interpretation").val();
    	var complications=$("#complications").val();
    	var plan=$("#plan").val();
    	var aditionalFindings=$("#aditionalFindings").val();
			var wound=$("input[name='wound']:checked").val();
			var modeOfDelivery=$("input[name='modeOfDelivery']:checked").val();
			var ifcs=$("input[name='ifcs']:checked").val();

	if(confirm("Are you Sure you want to Save This?")){

		 $.ajax({
	    url:"save_postnatal_record.php",
	    type:"POST",
	    data:{

    	    Admision_ID:Admision_ID,
    			consultation_id:consultation_id,
					employeeId:employeeId,
    			registrationId:registrationId,
    			parity:parity,
    			living:living,
    			pmtct:pmtct,
    			deliveryDateAndTime:deliveryDateAndTime,
    			niverapine:niverapine,
    			niverapineTime:niverapineTime,
    			babyCondition:babyCondition,
    			anyAbnormalities:anyAbnormalities,
    			apgarScore:apgarScore,
    			bwt:bwt,
    			bp:bp,
    			temp:temp,
    			pulse:pulse,
    			restRate:restRate,
    			physicalAppearance:physicalAppearance,
    			hxOfFever:hxOfFever,
    			pallor:pallor,
    			pain:pain,
    			complains:complains,
          fh:fh,
    			wound:wound,
    			nipples:nipples,
    			BreastSecreteMilk:BreastSecreteMilk,
    			uteras:uteras,
    			perinealPad:perinealPad,
    			pvBleeding:pvBleeding,
    			perinealTearType:perinealTearType,
    			estimatedBloodLoss:estimatedBloodLoss,
    			interpretation:interpretation,
    			complications:complications,
    			plan:plan,
    			aditionalFindings:aditionalFindings,
					modeOfDelivery:modeOfDelivery,
					ifcs:ifcs,
    			action:"save_postnatal_data"
	    },
	    success:function(data){
          alert(data);
          $("#employeeId").val("");
        	$("#registrationId").val("");
        	$("#parity").val("");
          $("#living").val("");
          $("input[name='pmtct']:checked").val("");
        	$("#deliveryDateAndTime").val("");
        	$("#niverapine").val("");
        	$("#niverapineTime").val("");
        	$("#babyCondition").val("");
        	$("#anyAbnormalities").val("");
        	$("#apgarScore").val("");
        	$("#bwt").val("");
        	$("#bp").val("");
        	$("#temp").val("");
        	$("#pulse").val("");
        	$("#restRate").val("");
          $("#fh").val("");
        	$("input[name='physicalAppearance']:checked").val("");
        	$("input[name='hxOfFever']:checked").val("");
        	$("input[name='pallor']:checked").val("");
          $("input[name='pain']:checked").val("");
          $("#complains").val("");
        	$("input[name='wound']:checked").val("");
        	$("input[name='nipples']:checked").val("");
        	$("input[name='BreastSecreteMilk']:checked").val("");
        	$("input[name='uterus']:checked").val("");
        	$("input[name='perinealPad']:checked").val("");
        	$("input[name='pvBleeding']:checked").val("");
        	$("#sourceOfBleedingPerineal").val("");
        	$("input[name='perinealTearType']:checked").val("");
        	$("#estimatedBloodLoss").val("");
        	$("#interpretation").val("");
        	$("#complications").val("");
        	$("#plan").val("");
        	$("#aditionalFindings").val("");
					$("input[name='modeOfDelivery']:checked").val("");
					$("input[name='ifcs']:checked").val("");

	    }
	  })


	}

	} //end act if
}





function saveFluids(act)
{
	if(act == "Save")
	{
		var Admision_ID=$("#Admision_ID").val();
		var consultation_id=$("#consultation_id").val();
		var bfuildGiven=$("#bfuildGiven").val();
		var employeeId=$("#employeeId").val();
		var registrationId=$("#registrationId").val();
		var postnatalId=$("#postnatalId").val();
		var mils=$("#mils").val();
		var dateAndTime=$("#dateAndTime").val();
		var plan=$("#plan").val();

    if(confirm("Are you Sure you want to Save This?")){

        $.ajax({
          url:"save_postnatal_record.php",
          type:"POST",
          data:{
							Admision_ID:Admision_ID,
							consultation_id:consultation_id,
          		bfuildGiven:bfuildGiven,
          		employeeId:employeeId,
          		registrationId:registrationId,
          		postnatalId:postnatalId,
          		mils:mils,
          		plan:plan,
          		action2:"save_fluids",
              dateAndTime:dateAndTime

          },
          success:function(data){
            alert(data);
            bfuildGiven=$("#bfuildGiven").val("");
    		    employeeId=$("#employeeId").val("");
    		    registrationId=$("#registrationId").val("");
    		    postnatalId=$("#postnatalId").val("");
    		    mils=$("#mils").val("");
    		    dateAndTime=$("#dateAndTime").val("");
    		    plan=$("#plan").val("");
            location.reload(true);

          }
        })
      } //end confirm
  } //end act

}






function saveUrine(act)
{
	if(act == "Save")
	{
		var Admision_ID=$("#Admision_ID").val();
		var consultation_id=$("#consultation_id").val();
		var amount=$("#amount").val();
		var employeeId=$("#employeeId").val();
		var registrationId=$("#registrationId").val();
		var postnatalId=$("#postnatalId").val();
		var colord=$("#colord").val();
		var dateAndTime=$("#dateAndTime").val();
		var plan=$("#plan").val();

      if(confirm("Are you Sure you want to Save This?")){

        $.ajax({
          url:"save_postnatal_record.php",
          type:"POST",
          data:{
							Admision_ID:Admision_ID,
							consultation_id:consultation_id,
          		amount:amount,
          		employeeId:employeeId,
          		registrationId:registrationId,
          		postnatalId:postnatalId,
              dateAndTime:dateAndTime,
          		colord:colord,
          		plan:plan,
          		action3:"save_urine"

          },
          success:function(data){
            alert(data);
            $("#amount").val("");
    		    $("#employeeId").val("");
    		    $("#registrationId").val("");
    		    $("#postnatalId").val("");
    		    $("#colord").val("");
    		    $("#dateAndTime").val("");
    		    $("#plan").val("");
            location.reload(true);

          }
        })
      } //end confirm
  } //end act

}



//
// $("#urineOutputMonitor").click(function(e){
//   e.preventDefault()
//   var form_data=$("#urineOutputMonitorForm").serialize();
//   // confirm("Are you want to save the data?");
//   $.ajax({
//     url:"save_postnatal_record.php",
//     type:"POST",
//     data:form_data,
//     success:function(data){
//       //alert("Saved Succefully"+data);
//     }
//   })
//
// });
//

//
// $("#fluidsBlood").click(function(e){
//   e.preventDefault()
//   var form_data=$("#fluidsBloodForm").serialize();
//   // confirm("Are you want to save the data?");
//   $.ajax({
//     url:"save_postnatal_record.php",
//     type:"POST",
//     data:form_data,
//     success:function(data){
//       //alert("Saved Succefully"+data);
//     }
//   })
//
// });

// $(\'.date\').datetimepicker({value: '', step: 2});
$(document).ready(function(e){

  $('.date').datetimepicker({value: '', step: 2});
})
