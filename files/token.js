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

function authorizeNHIF(CardNo) {
    var VisitTypeID = $("#VisitTypeID").val();
    var referral_no = $("#referral_no_txt").val();
    // $("#referral_status").ch

    var referred_from_hospital_id = $("#referred_from_hospital_id option:selected").val();

    if (VisitTypeID == 3) {
        if (referred_from_hospital_id.length <= 0) {
            alert(referred_from_hospital_id);
            $("#referred_from_hospital_id").focus();
            //$("#referred_from_hospital_id").attr("style","border:1px solid red");  
            $('#referred_from_hospital_id').addClass("select2");
            $('#referred_from_hospital_id').attr("required", "required");
        }
        if (referral_no.length <= 0) {
            $("#referral_no_txt").focus();
            $("#referral_no_txt").css("border", "1px solid red");
        }



        //break;
    } else {
        if (CardNo != '') {
            $.ajax({
                type: "GET",
                url: 'nhif3/index.php',
                dataType: 'json',
                data: { CardNo: CardNo, action: "authenticateCard", VisitTypeID: VisitTypeID, referral_no: referral_no },
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
    }
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
            var dt = data;
            var AuthorizationStatus = dt.AuthorizationStatus;
            var Remarks = dt.Remarks;
            var AuthorizationNo = dt.AuthorizationNo;

            document.getElementById('Remarks').innerHTML = Remarks;
            document.getElementById('CardStatus').value = AuthorizationStatus;
            document.getElementById('AuthorizationNo').value = AuthorizationNo;
            //function to get the member photo	    
            memberphoto(CardNo);
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
function verifyNHIF3() {

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
            url: 'nhif3/index.php',
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
                    console.log(MembershipNo)
                    console.log("meeeeeeeeeeeeeeeeeeeeeee")
                    if (IsActive == 'true') {
                        document.getElementById("Member_Number").setAttribute('style', 'border-color:#0F0;width: 150px;text-align: left;');
                    } else {
                        document.getElementById("Member_Number").setAttribute('style', 'border-color:red;width: 150px;text-align: left;');
                    }

                    if (FullName != '' || FullName != '') {
                        //insert into form
                        document.getElementById("Patient_Name").value = FullName;
                        document.getElementById("Patient_Name").setAttribute('readonly', 'readonly');

                        document.getElementById("Employee_Vote_Number").value = MembershipNo;
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
