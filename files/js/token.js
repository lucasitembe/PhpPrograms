var serviceurl = "https://verification.nhif.or.tz/NHIFService";
var credentials = {
    "grant_type": "password",
    "username": "kairuki",
    "password": "kairuki@2014"
};

var FacilityCode = '01146';
var UserName = 'kairuki';

function verifyNHIF(CardNo) {
    nhif(CardNo);
}

//function to get token for authentication
function nhif(CardNo) {
    $.ajax({
        type: 'POST',
        url: serviceurl + '/Token',
        data: credentials,
        beforeSend: function(xhr) {
            $('#verifyprogress').show();
        },
        success: function(data) {
            var accToken = data.access_token;
            window.localStorage.setItem("AccessToken", accToken);
            //UserName = 'kinondonihospital';
            authorization(CardNo, FacilityCode, UserName);
        },
        complete: function(jqXHR, textStatus) {

        },
        error: function(jqXHR, textStatus, errorThrown) {
            $('#verifyprogress').hide();
        }
    });

}
function verify_user_card_number(CardNo){
var hidden_card_number = document.getElementById('hidden_card_number').value.trim();
if(CardNo.trim() != hidden_card_number){
	alert("THE PATIENT CARD NUMBER DOES NOT MATCH WITH THE SYSTEM CARD NUMBER!");
	exit;
}

}

function authorizeNHIF(CardNo,extenal_nhif_server_url) {
    var VisitTypeID = $("#VisitTypeID").val();
    var referral_no = $("#referral_no_txt").val();
    var emergence_no_txt = $("#emergence_no_txt").val();
    var Member_Number=$("#Member_Number").val(); 
	verify_user_card_number(Member_Number);
    if(Member_Number==""){
        alert("Enter NHIF Membership Number");
        $("#Member_Number").css("border","2px solid red")
        $("#Member_Number").focus()
        exit;
    }
	if(VisitTypeID == ''){
		alert('PLEASE, SELECT VISIT TYPE!');
		return false;
	}
    CardNo=Member_Number;
    var referred_from_hospital_id = $("#referred_from_hospital_id option:selected").val();

    if (VisitTypeID == 3) {
        if (referred_from_hospital_id.length <= 0) {
//            alert(referred_from_hospital_id);
            $("#referred_from_hospital_id").focus();
            //$("#referred_from_hospital_id").attr("style","border:1px solid red");  
            $('#referred_from_hospital_id').addClass("select2");
            $('#referred_from_hospital_id').attr("required", "required");
        }
        if (referral_no.length <= 0) {
            $("#referral_no_txt").focus();
            $("#referral_no_txt").css("border", "1px solid red");
        }

        }
        if(VisitTypeID==2){
            $("#emergence_row").show();
            $("#emergence_no_txt").focus();
            $("#emergence_no_txt").css("border","2px solid red");
        }else{
            $("#emergence_row").hide();
        }
  //  } else {
        if (CardNo != '') {
            $.ajax({
                type: "GET",
                url: extenal_nhif_server_url+'nhif3/index.php',
                dataType: 'json',
                data: { CardNo: CardNo, action: "authenticateCard", VisitTypeID: VisitTypeID,Remarks:emergence_no_txt, referral_no: referral_no },
                beforeSend: function(xhr) {
                    $('#verifyprogress').show();
                },
                success: function(data) {
                    console.log(data);
                        //authorization(CardNo, FacilityCode, UserName);
                        var dt = data;
                        var AuthorizationStatus = dt.AuthorizationStatus;
                        var Remarks = dt.Remarks;
                        var AuthorizationNo = dt.AuthorizationNo;
			            console.log("remarks");
                        document.getElementById('Remarks').innerHTML = Remarks;
                        document.getElementById('CardStatus').value = AuthorizationStatus;
                        document.getElementById('AuthorizationNo').value = AuthorizationNo;
                        if (AuthorizationStatus == "REJECTED") {
                            document.getElementById('CardStatus').style.backgroundColor = 'red';
                            document.getElementById('Remarks').style.backgroundColor = 'red';
                            document.getElementById('AuthorizationNo').style.backgroundColor = 'red';
                            document.getElementById('CardStatus').style.color = 'white';
                            document.getElementById('Remarks').style.color = 'white';
                            document.getElementById('AuthorizationNo').style.color = 'white';
                        }else{
			//display authorization details to receptionist
			document.getElementById('ver_patient_name').innerHTML =  dt.FullName;
		
			document.getElementById('ver_card_no').innerHTML = dt.CardNo;
			document.getElementById('ver_status').innerHTML = dt.AuthorizationStatus;
			document.getElementById('ver_expire_date').innerHTML = dt.ExpiryDate;
			document.getElementById('ver_authorization_no').innerHTML = dt.AuthorizationNo;
			document.getElementById('ver_package').innerHTML = dt.ProductName;
			document.getElementById('ver_scheme_name').innerHTML = dt.SchemeName;

			$("#verification_dialog").dialog('open');
            var auto_package = '';
			if(dt.ProductName == 'STANDARD' || dt.ProductCode == 'NH001'){
				auto_package = "<option value='NH001'>STANDARD</option>";
			}else if(dt.ProductName == 'BUNGE' || dt.ProductCode == 'NH002'){
				auto_package = "<option value='NH002'>BUNGE</option>";
			}else if(dt.ProductName == 'NAJALI AFYA' || dt.ProductCode == 'NH003'){
				auto_package = "<option value='NH003'>NAJALI AFYA</option>";
			}else if(dt.ProductName == 'WEKEZA AFYA' || dt.ProductCode == 'NH004'){
				auto_package = "<option value='NH004'>WEKEZA AFYA</option>";
			}else if(dt.ProductName == 'TIMIZA AFYA' || dt.ProductCode == 'NH005'){
				auto_package = "<option value='NH005'>TIMIZA AFYA</option>";
			}else if(dt.ProductName == 'MACHINGA AFYA' || dt.ProductCode == 'NH006'){
				auto_package = "<option value='NH006'>MACHINGA AFYA</option>";
			}else if(dt.ProductName == 'USHIRIKA AFYA' || dt.ProductCode == 'NH007'){
				auto_package = "<option value='NH007'>USHIRIKA AFYA</option>";
			}else if(dt.ProductName == 'MADEREVA AFYA' || dt.ProductCode == 'NH008'){
				auto_package = "<option value='NH008'>MADEREVA AFYA</option>";
			}else if(dt.ProductName == 'UMOJA AFYA' || dt.ProductCode == 'NH009'){
				auto_package = "<option value='NH009'>UMOJA AFYA</option>";
			}else if(dt.ProductName == 'BODABODA AFYA' || dt.ProductCode == 'NH010'){
				auto_package = "<option value='NH010'>BODABODA AFYA</option>";
			}else if(dt.ProductName == 'TOTO AFYA' || dt.ProductCode == 'NH011'){
				auto_package = "<option value='NH011'>TOTO AFYA</option>";
			}else if(dt.ProductName == 'PRIVATE' || dt.ProductCode == 'NH012'){
				auto_package = "<option value='NH012'>PRIVATE</option>";
			}else if(dt.ProductName == 'STUDENT' || dt.ProductCode == 'NH013'){
				auto_package = "<option value='NH013'>STUDENT</option>";
			}else if(dt.ProductName == 'INTERN' || dt.ProductCode == 'NH014'){
				auto_package = "<option value='NH014'>INTERN</option>";
			}

			if(auto_package != ''){
				document.getElementById('select_package').innerHTML = auto_package;
			}
			}

                },
                complete: function(jqXHR, textStatus) {
                    $('#verifyprogress').hide();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#verifyprogress').hide();
                    // alert(textStatus);
                    console.log(jqXHR + textStatus + errorThrown);
                    alert("There Was A Problem While Connecting. Contact The Person Incharge!");
                }
            });
        } else {
            alert("Membership Number Must be Provided to Continue with Authirization");
        }
  //  }
}





//function to authenticate
function authorization(CardNo, FacilityCode, UserName) {

    var token = window.localStorage.getItem("AccessToken");

    $.ajax({
        type: 'GET',
        url: serviceurl + '/breeze/Verification/AuthorizeCard?CardNo=' + CardNo + ' & FacilityCode=' + FacilityCode + ' & UserName=' + UserName,
        headers: { "Authorization": "Bearer " + token },
        xhrFields: {
            withCredentials: true
        },
        beforeSend: function(xhr) {
            $('#verifyprogress').show();
        },
        success: function(data) {
            console.log(data);
                    //authorization(CardNo, FacilityCode, UserName);
                    var dt = data;
                    var AuthorizationStatus = dt.AuthorizationStatus;
                    var Remarks = dt.Remarks;
                    var AuthorizationNo = dt.AuthorizationNo;

                    document.getElementById('Remarks').innerHTML = Remarks;
                    document.getElementById('CardStatus').value = AuthorizationStatus;
                    document.getElementById('AuthorizationNo').value = AuthorizationNo;
                    if (AuthorizationStatus == "REJECTED") {
                        document.getElementById('CardStatus').style.backgroundColor = 'red';
                        document.getElementById('Remarks').style.backgroundColor = 'red';
                        document.getElementById('AuthorizationNo').style.backgroundColor = 'red';
                        document.getElementById('CardStatus').style.color = 'white';
                        document.getElementById('Remarks').style.color = 'white';
                        document.getElementById('AuthorizationNo').style.color = 'white';
                    } else {
                        //display authorization details to receptionist

                        document.getElementById('ver_patient_name').innerHTML =  dt.FullName;
                        document.getElementById('ver_card_no').innerHTML = dt.CardNo;
                        document.getElementById('ver_status').innerHTML = dt.AuthorizationStatus;
                        document.getElementById('ver_expire_date').innerHTML = dt.ExpiryDate;
                        document.getElementById('ver_authorization_no').innerHTML = dt.AuthorizationNo;
                        document.getElementById('ver_package').innerHTML = dt.ProductName;
                        document.getElementById('ver_scheme_name').innerHTML = dt.SchemeName;
                        $("#verification_dialog").dialog('open');
                        var auto_package = '';
                        if(dt.ProductName == 'STANDARD' || dt.ProductCode == 'NH001'){
                            auto_package = "<option value='NH001'>STANDARD</option>";
                        }else if(dt.ProductName == 'BUNGE' || dt.ProductCode == 'NH002'){
                            auto_package = "<option value='NH002'>BUNGE</option>";
                        }else if(dt.ProductName == 'NAJALI AFYA' || dt.ProductCode == 'NH003'){
                            auto_package = "<option value='NH003'>NAJALI AFYA</option>";
                        }else if(dt.ProductName == 'WEKEZA AFYA' || dt.ProductCode == 'NH004'){
                            auto_package = "<option value='NH004'>WEKEZA AFYA</option>";
                        }else if(dt.ProductName == 'TIMIZA AFYA' || dt.ProductCode == 'NH005'){
                            auto_package = "<option value='NH005'>TIMIZA AFYA</option>";
                        }else if(dt.ProductName == 'MACHINGA AFYA' || dt.ProductCode == 'NH006'){
                            auto_package = "<option value='NH006'>MACHINGA AFYA</option>";
                        }else if(dt.ProductName == 'USHIRIKA AFYA' || dt.ProductCode == 'NH007'){
                            auto_package = "<option value='NH007'>USHIRIKA AFYA</option>";
                        }else if(dt.ProductName == 'MADEREVA AFYA' || dt.ProductCode == 'NH008'){
                            auto_package = "<option value='NH008'>MADEREVA AFYA</option>";
                        }else if(dt.ProductName == 'UMOJA AFYA' || dt.ProductCode == 'NH009'){
                            auto_package = "<option value='NH009'>UMOJA AFYA</option>";
                        }else if(dt.ProductName == 'BODABODA AFYA' || dt.ProductCode == 'NH010'){
                            auto_package = "<option value='NH010'>BODABODA AFYA</option>";
                        }else if(dt.ProductName == 'TOTO AFYA' || dt.ProductCode == 'NH011'){
                            auto_package = "<option value='NH011'>TOTO AFYA</option>";
                        }else if(dt.ProductName == 'PRIVATE' || dt.ProductCode == 'NH012'){
                            auto_package = "<option value='NH012'>PRIVATE</option>";
                        }else if(dt.ProductName == 'STUDENT' || dt.ProductCode == 'NH013'){
                            auto_package = "<option value='NH013'>STUDENT</option>";
                        }else if(dt.ProductName == 'INTERN' || dt.ProductCode == 'NH014'){
                            auto_package = "<option value='NH014'>INTERN</option>";
                        }

                        if(auto_package != ''){
                            document.getElementById('select_package').innerHTML = auto_package;
                        }
                }
        },
        complete: function(jqXHR, textStatus) {

        },
        error: function(jqXHR, textStatus, errorThrown) {
            $('#verifyprogress').hide();
        }
    });

}






/*
 * verification of nhif membership using new API
 * done by NASSOR NASSOR 14 FEB 2017
 */
function verifyNHIF3(extenal_nhif_server_url) {

    CardNo = document.getElementById("Member_Number").value;
    //clear patient nhif details
    document.getElementById("Patient_Name").value = '';
    document.getElementById("Employee_Vote_Number").value = '';
    document.getElementById("date").value = '';
    document.getElementById("date2").value = '';
    document.getElementById("Gender").innerHTML = "<option></option><option>Male</option><option>Female</option>";
    document.getElementById("Member_Number").setAttribute('style', 'border-color:red;width: 150px;text-align: left;');

    if (CardNo != "") {
        $.ajax({
            type: "GET",
            url: extenal_nhif_server_url+'nhif3/index.php',
            dataType: 'json',
            data: { CardNo: CardNo, action: "getCardDetails" },
            success: function(data) {
                console.log(data);
                if (data.StatusCode == "200") {
                    //card found 
                    //$('#Patient_Name').val(data.FullName);
                    var dt = data;
                    var CardNo = dt.CardNo;
                    var MembershipNo = data.MembershipNo;
                    var FullName = data.FullName;
                    var PFNumber = data.PFNumber
                    var Gender = data.Gender
                    var DateOfBirth = data.DateOfBirth
                    var Age = data.Age
                    var CHNationalID = data.CHNationalID
                    var ExpiryDate = data.ExpiryDate
                    var CardStatusID = data.CardStatusID
                    var CardStatus = data.CardStatus
                    var StatusDescription = data.StatusDescription
                    var LatestContribution = data.LatestContribution
                    var AuthorizationStatus = data.AuthorizationStatus
                    var AuthorizationNo = data.AuthorizationNo
                    var Remarks = data.Remarks
                    var IsActive = data.IsActive
                    var EmployerNo = data.EmployerNo
                    console.log(MembershipNo)
                    console.log("error reached here")
                    if (IsActive == 'true') {
                        document.getElementById("Member_Number").setAttribute('style', 'border-color:#0F0;width: 150px;text-align: left;');
                    } else {
                        document.getElementById("Member_Number").setAttribute('style', 'border-color:red;width: 150px;text-align: left;');
                    }

                    if (FullName != '' || FullName != '') {
                        //insert into form
                        document.getElementById("Patient_Name").value = FullName;
                        document.getElementById("Patient_Name").setAttribute('readonly', 'readonly');

                        document.getElementById("Employee_Vote_Number").value = EmployerNo;
                        document.getElementById("Employee_Vote_Number").setAttribute('readonly', 'readonly');

                        document.getElementById("date").value = ExpiryDate;
                        document.getElementById("date").setAttribute('readonly', 'readonly');

                        document.getElementById("date2").value = DateOfBirth;
                        document.getElementById("date2").setAttribute('readonly', 'readonly');
                    } else {
                        document.getElementById("Patient_Name").value = null;
                        //document.getElementById("Patient_Name").removeAttribute('readonly');
                        document.getElementById("Patient_Name").setAttribute('required', 'required');

                        document.getElementById("Employee_Vote_Number").value = null;
                        //document.getElementById("Employee_Vote_Number").removeAttribute('readonly');
                        document.getElementById("Employee_Vote_Number").setAttribute('required', 'required');

                        document.getElementById("date").value = null;
                        //document.getElementById("date").removeAttribute('readonly');
                        document.getElementById("date").setAttribute('required', 'required');

                        document.getElementById("date2").value = null;
                        // document.getElementById("date2").removeAttribute('readonly');
                        document.getElementById("date2").setAttribute('required', 'required');
                    }

                    if (Gender == 'Male') {
                        document.getElementById("Gender").innerHTML = "<option selected='selected' value='Male'>Male</option>";
                        // document.getElementById("Gender").setAttribute('readonly', 'readonly');
                    } else {
                        document.getElementById("Gender").innerHTML = "<option></option><option selected='selected' value='Female'>Female</option>";
                        //document.getElementById("Gender").setAttribute('readonly', 'readonly');
                    }

                } else if (data.StatusCode == "404") {
                    //card not found
                    alert(data.Message);
                } else {
                    //alert("There is something with the card");
                }


            },
            error: function(jqXHR, textStatus, errorThrown) {
                document.getElementById("Patient_Name").value = null;
                document.getElementById("Patient_Name").removeAttribute('readonly');
                document.getElementById("Patient_Name").setAttribute('required', 'required');

                document.getElementById("Employee_Vote_Number").value = null;
                document.getElementById("Employee_Vote_Number").removeAttribute('readonly');
                document.getElementById("Employee_Vote_Number").setAttribute('required', 'required');

                document.getElementById("date").value = null;
                document.getElementById("date").removeAttribute('readonly');
                document.getElementById("date").setAttribute('required', 'required');

                document.getElementById("date2").value = null;
                document.getElementById("date2").removeAttribute('readonly');
                document.getElementById("date2").setAttribute('required', 'required');

                alert("There Was A Problem While Connecting. Contact The Person Incharge!");
            }
        });
    } else {
        alert("Please enter the NHIF Membership Number");
    }


}


//Verification Process

function verifyNHIF2() {
    CardNo = document.getElementById("Member_Number").value;
    nhif2(CardNo);
}



//nhif process for verification
function nhif2(CardNo) {
    var credentials = {
        "grant_type": "password",
        "username": "amanahospital",
        "password": "amanahospitalU$r@2014"
    }

    $.ajax({
        url: serviceurl + '/Token',
        type: "POST",
        data: credentials
    }).done(function(data) {

        var accToken = data.access_token;
        window.localStorage.setItem("AccessToken", accToken);

        var token = window.localStorage.getItem("AccessToken");
    })

    getCardDetails2(CardNo);
}


//getCardDetails2
function getCardDetails2(CardNo) {
    document.getElementById("Patient_Name").value = '';
    document.getElementById("Employee_Vote_Number").value = '';
    document.getElementById("date").value = '';
    document.getElementById("date2").value = '';
    document.getElementById("Gender").innerHTML = "<option></option><option>Male</option><option>Female</option>";
    document.getElementById("Member_Number").setAttribute('style', 'border-color:red;width: 150px;text-align: left;');

    var token = window.localStorage.getItem("AccessToken");

    $.ajax({
        url: serviceurl + '/breeze/Verification/GetCardDetails?CardNo=' + CardNo,
        type: "GET",
        headers: { "Authorization": "Bearer " + token },
        xhrFields: {
            withCredentials: true
        },
        error: function() {
            document.getElementById("Patient_Name").value = null;
            document.getElementById("Patient_Name").removeAttribute('readonly');
            document.getElementById("Patient_Name").setAttribute('required', 'required');

            document.getElementById("Employee_Vote_Number").value = null;
            document.getElementById("Employee_Vote_Number").removeAttribute('readonly');
            document.getElementById("Employee_Vote_Number").setAttribute('required', 'required');

            document.getElementById("date").value = null;
            document.getElementById("date").removeAttribute('readonly');
            document.getElementById("date").setAttribute('required', 'required');

            document.getElementById("date2").value = null;
            document.getElementById("date2").removeAttribute('readonly');
            document.getElementById("date2").setAttribute('required', 'required');

            alert("There Was A Problem While Connecting. Contact The Person Incharge!");
        }
    }).done(function(data) {

        var dt = data;
        var CardNo = dt.CardNo;
        var MembershipNo = data.MembershipNo;
        var FullName = data.FullName;
        var PFNumber = data.PFNumber
        var Gender = data.Gender
        var DateOfBirth = data.DateOfBirth
        var Age = data.Age
        var CHNationalID = data.MembershipNo
        var ExpiryDate = data.ExpiryDate
        var CardStatusID = data.CardStatusID
        var CardStatus = data.CardStatus
        var StatusDescription = data.StatusDescription
        var LatestContribution = data.LatestContribution
        var AuthorizationStatus = data.AuthorizationStatus
        var AuthorizationNo = data.AuthorizationNo
        var Remarks = data.Remarks
        var IsActive = data.IsActive


        if (IsActive == 'true') {
            document.getElementById("Member_Number").setAttribute('style', 'border-color:#0F0;width: 150px;text-align: left;');
        } else {
            document.getElementById("Member_Number").setAttribute('style', 'border-color:red;width: 150px;text-align: left;');
        }

        if (FullName != '' || FullName != '') {
            //insert into form
            document.getElementById("Patient_Name").value = FullName;
            document.getElementById("Patient_Name").setAttribute('readonly', 'readonly');

            document.getElementById("Employee_Vote_Number").value = CHNationalID;
            //document.getElementById("Employee_Vote_Number").setAttribute('readonly','readonly');

            document.getElementById("date").value = ExpiryDate;
            document.getElementById("date").setAttribute('readonly', 'readonly');

            document.getElementById("date2").value = DateOfBirth;
            document.getElementById("date2").setAttribute('readonly', 'readonly');
        } else {
            document.getElementById("Patient_Name").value = null;
            document.getElementById("Patient_Name").removeAttribute('readonly');
            document.getElementById("Patient_Name").setAttribute('required', 'required');

            document.getElementById("Employee_Vote_Number").value = null;
            document.getElementById("Employee_Vote_Number").removeAttribute('readonly');
            document.getElementById("Employee_Vote_Number").setAttribute('required', 'required');

            document.getElementById("date").value = null;
            document.getElementById("date").removeAttribute('readonly');
            document.getElementById("date").setAttribute('required', 'required');

            document.getElementById("date2").value = null;
            document.getElementById("date2").removeAttribute('readonly');
            document.getElementById("date2").setAttribute('required', 'required');
        }

        if (Gender == 'Male') {
            document.getElementById("Gender").innerHTML = "<option></option><option>Male</option>";
            document.getElementById("Gender").setAttribute('readonly', 'readonly');
        } else {
            document.getElementById("Gender").innerHTML = "<option></option><option>Female</option>";
            document.getElementById("Gender").setAttribute('readonly', 'readonly');
        }

    })

    //function to get the member photo	    
    memberphoto(CardNo);
}

//function to get the membe photo
function memberphoto(CardNo) {
    var token = window.localStorage.getItem("AccessToken");
    $('#verifyprogress').hide();
    /* $.ajax({
        url: serviceurl + '/breeze/Verification/GetWebPhoto?CardNo=' + CardNo,
        type: "GET",
        headers: {"Authorization": "Bearer " + token},
        xhrFields: {
            withCredentials: true
        },
        success: function (data) {
            var dt = data;
            document.getElementById('Patient_Picture').src = data;
        }, complete: function (jqXHR, textStatus) {
            $('#verifyprogress').hide();
        }, error: function (jqXHR, textStatus, errorThrown) {
            $('#verifyprogress').hide();
        }
    }); */

}
