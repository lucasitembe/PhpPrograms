var serviceurl = "http://41.59.13.149/NHIFService";
function verifyNHIF(CardNo){
	nhif(CardNo);
}
 
 //function to get token for authentication
 function nhif(CardNo){
		var credentials= {
		"grant_type":"password",
		"username":"kinondonihospital",
		"password":"kinondonihospitalU$r@2014"
		 }

	    $.ajax({
	        url: serviceurl+'/Token',
			        type: "POST",
			        data: credentials
		 }).done(function (data) {

		     var accToken = data.access_token;
		     window.localStorage.setItem("AccessToken", accToken);

		     var token = window.localStorage.getItem("AccessToken");
		 })
	    FacilityCode = '03995';
	    UserName = 'kinondonihospital';
	    authorization(CardNo,FacilityCode,UserName);
	}
 




//function to authenticate
function authorization(CardNo,FacilityCode,UserName) {

	    var token = window.localStorage.getItem("AccessToken");

	    $.ajax({
	        url: serviceurl+'/breeze/Verification/AuthorizeCard?CardNo='+CardNo+' & FacilityCode='+FacilityCode+' & UserName='+UserName,
	        type: "GET",
	        headers: { "Authorization": "Bearer " + token },
	        xhrFields: {
	            withCredentials: true
	        }
	    }).done(function (data) {

	        var dt = data;
		var AuthorizationStatus=dt.AuthorizationStatus;
		var Remarks=dt.Remarks;
		var AuthorizationNo = dt.AuthorizationNo;
		
		document.getElementById('Remarks').innerHTML=Remarks;
		document.getElementById('CardStatus').value=AuthorizationStatus;
		document.getElementById('AuthorizationNo').value=AuthorizationNo;
	    })
	//function to get the member photo	    
	memberphoto(CardNo);
	}






//Verification Process

function verifyNHIF2(){
	CardNo = document.getElementById("Member_Number").value;
	nhif2(CardNo);
}

//nhif process for verification
function nhif2(CardNo){
		var credentials= {
		"grant_type":"password",
		"username":"kinondonihospital",
		"password":"kinondonihospitalU$r@2014"
		 }

	    $.ajax({
	        url: serviceurl+'/Token',
			        type: "POST",
			        data: credentials
		 }).done(function (data) {

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
		document.getElementById("Member_Number").setAttribute('style','border-color:red;width: 150px;text-align: left;');
		
		var token = window.localStorage.getItem("AccessToken");
	
	    $.ajax({
	        url: serviceurl+'/breeze/Verification/GetCardDetails?CardNo='+CardNo,
	        type: "GET",
	        headers: { "Authorization": "Bearer " + token },
	        xhrFields: {
	            withCredentials: true
	        }
	    }).done(function (data) {

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
		
			if (IsActive=='true') {
			document.getElementById("Member_Number").setAttribute('style','border-color:#0F0;width: 150px;text-align: left;');
			}else{
			document.getElementById("Member_Number").setAttribute('style','border-color:red;width: 150px;text-align: left;');
			}
			
			//insert into form
			document.getElementById("Patient_Name").value = FullName;
			document.getElementById("Patient_Name").setAttribute('readonly','readonly');
			
			document.getElementById("Employee_Vote_Number").value = CHNationalID;
			document.getElementById("Employee_Vote_Number").setAttribute('readonly','readonly');
			
			document.getElementById("date").value = ExpiryDate;
			document.getElementById("date").setAttribute('readonly','readonly');
			
			document.getElementById("date2").value = DateOfBirth;
			document.getElementById("date2").setAttribute('readonly','readonly');
			
			if (Gender == 'Male') {
			    document.getElementById("Gender").innerHTML = "<option></option><option>Male</option>";
			    document.getElementById("Gender").setAttribute('readonly','readonly');
			}else{
			    document.getElementById("Gender").innerHTML = "<option></option><option>Female</option>";
			    document.getElementById("Gender").setAttribute('readonly','readonly');
			}
	       
	    })
	
	//function to get the member photo	    
	memberphoto(CardNo);
}

//function to get the membe photo
	function memberphoto(CardNo) {

	    var token = window.localStorage.getItem("AccessToken");

	    $.ajax({
	        url: serviceurl+'/breeze/Verification/GetWebPhoto?CardNo='+CardNo,
	        type: "GET",
	        headers: { "Authorization": "Bearer " + token },
	        xhrFields: {
	            withCredentials: true
	        }
	    }).done(function (data) {

	        var dt = data;
		document.getElementById('Patient_Picture').src = data;
	    })

	}