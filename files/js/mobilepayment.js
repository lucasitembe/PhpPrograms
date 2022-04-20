//variables
var Item_ID = 0;
var code = '';
var mode = 'item';

function selectItem(ID,Item_Name){
	Item_ID = ID;
	document.getElementById('item_name').value = Item_Name;
	getPrice();
}

function getPrice() {
	var Product_Name = Item_ID;
	if (Product_Name!='') {
	    var Billing_Type = 'Outpatient Cash';
	    var Guarantor_Name = document.getElementById('Guarantor_Name').value;
	    if(window.XMLHttpRequest) {
		priceObj = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		priceObj = new ActiveXObject('Micrsoft.XMLHTTP');
		priceObj.overrideMimeType('text/xml');
	    }

		priceObj.onreadystatechange= function(){
									var data4 = priceObj.responseText;
									document.getElementById('Price').value = data4;
									var price = document.getElementById('Price').value;
									var discount = document.getElementById('Discount').value;
									var quantity = document.getElementById('Quantity').value
									var ammount = 0;
								
									ammount = (price-discount)*quantity;
									document.getElementById('Amount').value = ammount;
						    	};
		priceObj.open('GET','Get_Item_price.php?Product_Name='+Product_Name+'&Billing_Type='+Billing_Type+'&Guarantor_Name='+Guarantor_Name,true);
		priceObj.send();
	}
}


function getDoctor() {
var Type_Of_Check_In = document.getElementById('Type_Of_Check_In').value;
    if(window.XMLHttpRequest) {
	mm = new XMLHttpRequest();
    }else if(window.ActiveXObject){
	mm = new ActiveXObject('Micrsoft.XMLHTTP');
	mm.overrideMimeType('text/xml');
    }
    
    if (document.getElementById('direction').value =='Direct To Doctor Via Nurse Station' || document.getElementById('direction').value =='Direct To Doctor') {
	mm.onreadystatechange= function(){
							var data3 = mm.responseText;
							document.getElementById('Consultant_ID').innerHTML = data3;
							}; //specify name of function that will handle server response....
	mm.open('GET','Get_Guarantor_ID.php?Type_Of_Check_In='+Type_Of_Check_In+'&direction=doctor',true);
	mm.send();
    }
    else{
	mm.onreadystatechange= function(){
							var data3 = mm.responseText;
							document.getElementById('Consultant_ID').innerHTML = data3;
							}; //specify name of function that will handle server response....
	mm.open('GET','Get_Guarantor_ID.php?direction=clinic',true);
	mm.send();
    }
}

function add(Registration_ID, visit_status = 'new'){
	if(code == '' && validateFields() ){
		if(window.XMLHttpRequest) {
			codeObj = new XMLHttpRequest();
		}
		else if(window.ActiveXObject){ 
			codeObj = new ActiveXObject('Micrsoft.XMLHTTP');
			codeObj.overrideMimeType('text/xml');
		}

		codeObj.onreadystatechange= function(){
								if(codeObj.readyState==4){
									code = codeObj.responseText;
									document.getElementById('payment_code').value = code;
									document.getElementById('btnSend').removeAttribute('disabled');
									document.getElementById('btnCancel').removeAttribute('disabled');
									if(mode == 'item'){
										addMobilePaymentItem(Registration_ID,visit_status);
									}else if(mode == 'cash'){
										addMobilePaymentCash(Registration_ID, visit_status);
									}
								}
							}; //specify name of function that will handle server response....
		codeObj.open('GET','./mobile/getCode.php',true);
		codeObj.send();
	}else if( validateFields() ){
		//code already exists
		if(mode == 'item'){
			addMobilePaymentItem(Registration_ID,visit_status);
		}else if(mode == 'cash'){
			addMobilePaymentCash(Registration_ID, visit_status);
		}
	}
}

function addMobilePaymentItem(Registration_ID, visit_status){
	var Check_In_Type = document.getElementById('Type_Of_Check_In').value;
	var Patient_Direction = document.getElementById('direction').value;
	var Consultant_ID = document.getElementById('Consultant_ID').value;
	var Quantity = document.getElementById('Quantity').value;
	var Discount = document.getElementById('Discount').value;
	var bill_type = document.getElementById('Billing_Type').value;

	if(window.XMLHttpRequest) {
			addObj = new XMLHttpRequest();
		}
		else if(window.ActiveXObject){ 
			addObj = new ActiveXObject('Micrsoft.XMLHTTP');
			addObj.overrideMimeType('text/xml');
		};
		addObj.onreadystatechange= function(){
								if(addObj.readyState==4){
									addObjResult = addObj.responseText;
									reloadItemList();
								}
							}; //specify name of function that will handle server response....
		addObj.open('GET','./mobile/addMobilePaymentItem.php?Registration_ID='+Registration_ID+'&item_ID='+Item_ID+'&Check_In_Type='+Check_In_Type+'&Patient_Direction='+Patient_Direction+'&Consultant_ID='+Consultant_ID+'&Quantity='+Quantity+'&Discount='+Discount+'&bill_type='+bill_type+'&payment_code='+code+'&visit_status='+visit_status,true);
		addObj.send();
}

function addMobilePaymentCash(Registration_ID, visit_status){
	var Check_In_Type = document.getElementById('Type_Of_Check_In').value;
	var Patient_Direction = document.getElementById('direction').value;
	var Consultant_ID = document.getElementById('Consultant_ID').value;
	var Quantity = document.getElementById('Quantity').value;
	var Discount = document.getElementById('Discount').value;
	var bill_type = document.getElementById('Billing_Type').value;
	var Price = document.getElementById('Price').value;
	var Item_Name = document.getElementById('item_name').value;

	if(window.XMLHttpRequest) {
			addCashObj = new XMLHttpRequest();
		}
		else if(window.ActiveXObject){ 
			addCashObj = new ActiveXObject('Micrsoft.XMLHTTP');
			addCashObj.overrideMimeType('text/xml');
		};
		addCashObj.onreadystatechange= function(){
								if(addCashObj.readyState==4){
									addCashObjResult = addCashObj.responseText;
									//alert(addCashObjResult);
									reloadItemList();
								}
							}; //specify name of function that will handle server response....
		addCashObj.open('GET','./mobile/addMobileCashPaymentItem.php?Registration_ID='+Registration_ID+'&Check_In_Type='+Check_In_Type+'&Patient_Direction='+Patient_Direction+'&Consultant_ID='+Consultant_ID+'&Quantity='+Quantity+'&Discount='+Discount+'&bill_type='+bill_type+'&payment_code='+code+'&visit_status='+visit_status+'&Price='+Price+'&Item_Name='+Item_Name,true);
		addCashObj.send();
}

function reloadItemList(){
	if(window.XMLHttpRequest) {
			getItemObj = new XMLHttpRequest();
		}
		else if(window.ActiveXObject){ 
			getItemObj = new ActiveXObject('Micrsoft.XMLHTTP');
			getItemObj.overrideMimeType('text/xml');
		};
		getItemObj.onreadystatechange= function(){
								if(getItemObj.readyState==4){
									getItemObjResult = getItemObj.responseText;
									document.getElementById('itemlist').innerHTML = getItemObjResult;
								}
							}; //specify name of function that will handle server response....
		getItemObj.open('GET','./mobile/getMobilePaymentItem.php?payment_code='+code,true);
		getItemObj.send();	
}

function loadCode(cd){
	code = cd;
	document.getElementById('payment_code').value = code;
	document.getElementById('btnSend').removeAttribute('disabled');
	document.getElementById('btnCancel').removeAttribute('disabled');
	document.getElementById('submit').removeAttribute('disabled');
	reloadItemList();
}

function sendToCloud(cd = ''){
	if(cd == ''){
		cd = code;
	}

	if(window.XMLHttpRequest) {
			cloudObj = new XMLHttpRequest();
		}
		else if(window.ActiveXObject){ 
			cloudObj = new ActiveXObject('Micrsoft.XMLHTTP');
			cloudObj.overrideMimeType('text/xml');
		};
		cloudObj.onreadystatechange= function(){
								if(cloudObj.readyState==4){
									cloudObjResult = cloudObj.responseText;
									
									location.reload();
									//reloadpage
								}
							}; //specify name of function that will handle server response....
		cloudObj.open('GET','./mobile/sendToCloudMobilePayment.php?payment_code='+cd,true);
		cloudObj.send();
}

function cancelPayment(cd = code){
	decide = confirm('Are You Sure You Want To Cancel This Process?');
	if(decide){
	if(window.XMLHttpRequest) {
			cancelObj = new XMLHttpRequest();
		}
		else if(window.ActiveXObject){ 
			cancelObj = new ActiveXObject('Micrsoft.XMLHTTP');
			cancelObj.overrideMimeType('text/xml');
		};
		cancelObj.onreadystatechange= function(){
								if(cancelObj.readyState==4){
									cancelObjResult = cancelObj.responseText;
									location.reload();
								}
							}; //specify name of function that will handle server response....
		cancelObj.open('GET','./mobile/cancelMobilePayment.php?payment_code='+cd,true);
		cancelObj.send();
	}else{
		return false;
	}
}

function removeItem(mobile_payment_id){

	if(window.XMLHttpRequest) {
			removeItemObj = new XMLHttpRequest();
		}
		else if(window.ActiveXObject){
			removeItemObj = new ActiveXObject('Micrsoft.XMLHTTP');
			removeItemObj.overrideMimeType('text/xml');
		};
		removeItemObj.onreadystatechange= function(){
								if(removeItemObj.readyState==4){
									reloadItemList();
								}
							}; //specify name of function that will handle server response....
		removeItemObj.open('GET','./mobile/removeMobileItem.php?mobile_payment_id='+mobile_payment_id,true);
		removeItemObj.send();
}

function itemSearch(item_searchName = ''){
	if(item_searchName ==''){
		item_searchName = document.getElementById('searchName').value;
	}

	var Item_Type = document.getElementById('Item_Type').value;
	var Item_Category_ID = document.getElementById('Item_Category_ID').value;

	if(window.XMLHttpRequest) {
			itemSearchObj = new XMLHttpRequest();
		}
		
		else if(window.ActiveXObject){
			itemSearchObj = new ActiveXObject('Micrsoft.XMLHTTP');
			itemSearchObj.overrideMimeType('text/xml');
		};

		itemSearchObj.onreadystatechange= function(){
								itemSearchObjResponce = itemSearchObj.responseText

								if(itemSearchObj.readyState==4){
									document.getElementById('item_search').innerHTML = itemSearchObjResponce;
								}
							}; //specify name of function that will handle server response....

		itemSearchObj.open('GET','./mobile/mobileItemSearch.php?item_searchName='+item_searchName+'&Item_Type='+Item_Type+'&Item_Category_ID='+Item_Category_ID,true);
		itemSearchObj.send();	
}

function validateFields(){
	Type_Of_Check_In = document.getElementById('Type_Of_Check_In');
	if(Type_Of_Check_In.value == '' || Type_Of_Check_In.value == null ){
		Type_Of_Check_In.style.borderColor = 'red';
		Type_Of_Check_In.focus();
		return false;
	}else{
		Type_Of_Check_In.style.borderColor = '';
	}

	if(Item_ID == 0){
		alert('Choose An Item To Add');
		return false;
	}
	
	Quantity = document.getElementById('Quantity');
	if(Quantity.value == '' || Quantity.value == null ){
		Quantity.style.borderColor = 'red';
		Quantity.focus();
		return false;
	}else{
		Quantity.style.borderColor = '';
	}

	return true;
}

function getPatientAndMobileItems(payment_code,pName){
	document.getElementById('Name').value = pName
	document.getElementById('PaymentCode').value = payment_code
	if(window.XMLHttpRequest) {
			patientItemsObj = new XMLHttpRequest();
		}
		else if(window.ActiveXObject){ 
			patientItemsObj = new ActiveXObject('Micrsoft.XMLHTTP');
			patientItemsObj.overrideMimeType('text/xml');
		};
		patientItemsObj.onreadystatechange= function(){
								if(patientItemsObj.readyState==4){
									patientItemsObjResult = patientItemsObj.responseText;
									document.getElementById('patientItems').innerHTML = patientItemsObjResult;
								}
							}; //specify name of function that will handle server response....
		patientItemsObj.open('GET','./mobile/getPatientAndMobileItems.php?payment_code='+payment_code,true);
		patientItemsObj.send();
}

function updatePaymentStatus(payment_code){
	if(window.XMLHttpRequest) {
			updateObj = new XMLHttpRequest();
		}
		else if(window.ActiveXObject){
			updateObj = new ActiveXObject('Micrsoft.XMLHTTP');
			updateObj.overrideMimeType('text/xml');
		};
		updateObj.onreadystatechange= function(){
								if(updateObj.readyState==4){
									updateObjResult = updateObj.responseText;
									alert(updateObjResult);
									location.reload();
								}
							}; //specify name of function that will handle server response....
		updateObj.open('GET','./mobile/updateMobilepayment.php?payment_code='+payment_code,true);
		updateObj.send();
}

function seen(payment_code){
	if(window.XMLHttpRequest) {
			updateStatusObj = new XMLHttpRequest();
		}
		else if(window.ActiveXObject){
			updateStatusObj = new ActiveXObject('Micrsoft.XMLHTTP');
			updateStatusObj.overrideMimeType('text/xml');
		};
		updateStatusObj.onreadystatechange= function(){
								if(updateStatusObj.readyState==4){
									updateStatusObjResult = updateStatusObj.responseText;
									location.reload();
								}
							}; //specify name of function that will handle server response....
		updateStatusObj.open('GET','./mobile/updateStatusMobilepayment.php?payment_code='+payment_code,true);
		updateStatusObj.send();
}

function CancelOnline(payment_code){
	decide = confirm('Are You Sure You Want To Cancel This Process?');
	if(decide){
	if(window.XMLHttpRequest) {
			cancelOnlineObj = new XMLHttpRequest();
		}
		else if(window.ActiveXObject){
			cancelOnlineObj = new ActiveXObject('Micrsoft.XMLHTTP');
			cancelOnlineObj.overrideMimeType('text/xml');
		};
		cancelOnlineObj.onreadystatechange= function(){
								if(cancelOnlineObj.readyState==4){
									cancelOnlineObjResult = cancelOnlineObj.responseText;
									alert(cancelOnlineObjResult);
									location.reload();
								}
							}; //specify name of function that will handle server response....
		cancelOnlineObj.open('GET','./mobile/cancelOnlineMobilepayment.php?payment_code='+payment_code,true);
		cancelOnlineObj.send();
	}else{
		return false;
	}
}

function searchPatientListMobile(){
	Patient_Name = document.getElementById('searchName').value;
	payment_code = document.getElementById('searchPaymentCode').value;
	if(window.XMLHttpRequest) {
		paymentSearchObj = new XMLHttpRequest();
	}
	else if(window.ActiveXObject){
		paymentSearchObj = new ActiveXObject('Micrsoft.XMLHTTP');
		paymentSearchObj.overrideMimeType('text/xml');
	};

	paymentSearchObj.onreadystatechange= function(){
							if(paymentSearchObj.readyState==4){
								paymentSearchObjResult = paymentSearchObj.responseText;
								document.getElementById('MobilePatientList').innerHTML = paymentSearchObjResult;
							}
						}; //specify name of function that will handle server response....
	paymentSearchObj.open('GET','./mobile/searchPatientListMobile.php?Patient_Name='+Patient_Name+'&payment_code='+payment_code,true);
	paymentSearchObj.send();
}

function toglePaymentMode(controlElement){
	if(mode == 'item'){
		mode = 'cash';
		Item_ID = 'none';
		controlElement.value = 'Item Payment';
		document.getElementById('item_name').value = '';

		document.getElementById('Discount').value = '0';
		document.getElementById('Discount').setAttribute('readonly','readonly');

		document.getElementById('Quantity').value = '1';
		document.getElementById('Quantity').setAttribute('readonly','readonly');

		document.getElementById('searchName').setAttribute('readonly','readonly');

		document.getElementById('Item_Type').setAttribute('disabled','disabled');
		document.getElementById('Item_Category_ID').setAttribute('disabled','disabled');

		var els = document.getElementsByTagName('input');

		document.getElementById('Price').value = '';
		document.getElementById('Price').removeAttribute('readonly');

		for(var i=0; i<els.length;i++){
			if(els[i].id == 'choose'){
				els[i].setAttribute('disabled','disabled');
			}
		}
		document.getElementById('item_name').removeAttribute('readonly');
	}else{
		mode = 'item';
		Item_ID = 0;
		controlElement.value = 'Direct Cash';
		document.getElementById('item_name').value = '';
		
		document.getElementById('Discount').value = '0';
		document.getElementById('Discount').removeAttribute('readonly');

		document.getElementById('searchName').removeAttribute('readonly');

		document.getElementById('Item_Type').removeAttribute('disabled');
		document.getElementById('Item_Category_ID').removeAttribute('disabled');

		document.getElementById('Quantity').value = '';
		document.getElementById('Quantity').removeAttribute('readonly');
		
		var els = document.getElementsByTagName('input');
		for(var i=0; i<els.length;i++){
			if(els[i].id == 'choose'){
				els[i].removeAttribute('disabled');
			}
		}
		document.getElementById('item_name').setAttribute('readonly','readonly');

		document.getElementById('Price').value = '';
		document.getElementById('Price').setAttribute('readonly','readonly');
	}
}