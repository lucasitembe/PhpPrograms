<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Revenue_Center_Works'])){
	    if($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }else{
		@session_start();
		if(!isset($_SESSION['supervisor'])){ 
		    header("Location: ./supervisorauthentication.php?InvalidSupervisorAuthentication=yes");
		}
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./revenuecenterworkpage.php";
    }
</script>


<!-- link menu -->
<script type="text/javascript">
    function gotolink(){
	var patientlist = document.getElementById('patientlist').value;
	if(patientlist=='Checked In - Outpatient List'){
	    document.location = "searchlistofoutpatientbilling.php?SearchListOfOutpatientBilling=SearchListOfOutpatientBillingThisPage";
	}else if (patientlist=='Checked In - Inpatient List') {
	    document.location = "searchlistofinpatientbilling.php?SearchListPatientBilling=SearchListPatientBillingThisPage";
	}else if (patientlist=='Direct Cash - Outpatient') {
	    document.location = "DirectCashsearchlistofoutpatientbilling.php?SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage";
	}else if (patientlist=='Direct Cash - Inpatient') {
	    document.location = "DirectCashsearchlistinpatientbilling.php?SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage";
	}else if (patientlist=='AdHOC Payments - Outpatient') {
        document.location = "continueoutpatientbilling.php?ContinuePatientBilling=ContinuePatientBillingThisPage";
    }else if (patientlist=='Patient from outside') {
        document.location = "tempregisterpatient.php?RegistrationNewPatient=RegistrationNewPatientThisPage";
    }else{
	    alert("Choose Type Of Patients To View");
	}
	
    }
</script>

<label style='border: 1px ;padding: 8px;margin-right: 7px;' class='art-button hide'>
<select id='patientlist' name='patientlist'>
    <option selected='selected'>Chagua Orodha Ya Wagonjwa</option>
    <option>
	Checked In - Outpatient List
    </option>
    <option>
	Checked In - Inpatient List
    </option>
    <option>
	Direct Cash - Outpatient
    </option>
    <option>
	Direct Cash - Inpatient
    </option>
    <option>
	AdHOC Payments - Outpatient
    </option>
<?php if(isset($_SESSION['systeminfo']['Direct_departmental_payments']) && strtolower($_SESSION['systeminfo']['Direct_departmental_payments']) == 'yes'){ ?>
    <option>
	   Patient from outside
    </option>
<?php } ?>
    <!--<option>
	Other Payments
    </option>-->
</select>
<input type='button' value='VIEW' onclick='gotolink()'>
</label>

<br/>
<br/>


<!-- new date function (Contain years, Months and days)--> 
<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	$age ='';
    }
?>
<!-- end of the function -->
<br/>
<br/>
    <br/>
    <br/>
<fieldset>  
    <legend align=center><b>REVENUE CENTER WORKS</b></legend>
        <center><table width = 60%>
            <tr>
                <td style='text-align: center; height: 40px;'>
                    <!--<a href='patientbillingpharmacy.php?PatientBillingPharmacy=PatientBillingPharmacyThisPage'>-->
                    <a title='DEPARTMENT PAYMENTS' href='departmentpatientbillingpage.php?DepartmentPatientBilling=DepartmentPatientBillingThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Departmental Payments
                        </button>
                    </a>
                </td>
                <td style='text-align: center; height: 40px;'>
                    <a href="new_payment_method.php">
                        <button style='width: 100%; height: 100%'>
                            New Payments Methods
                        </button>
                    </a>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px;' class='hide'>
                    <!--<a href='patientbillingpharmacy.php?PatientBillingPharmacy=PatientBillingPharmacyThisPage'>-->
                    <a title='REVENUE FROM OTHER SOURCES' href='revenue_from_other_sources.php?SearchListRevenueFromOtherSources=SearchListRevenueFromOtherSourcesThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Revenue From Other Sources
                        </button>
                    </a>
                </td>
                <td style='text-align: center; height: 40px;' class='hide'>
                    <!--<a href='patientbillingpharmacy.php?PatientBillingPharmacy=PatientBillingPharmacyThisPage'>-->
                    <a title='SUPPLIER PAYMETS' href='supplier_payments_list.php?SearchListRevenueFromOtherSources=SearchListRevenueFromOtherSourcesThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Supplier Payments
                        </button>
                    </a>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 50%;'>
                    <a href='./adhocsearch.php?AdhocSearch=AdhocSearchThisPage' style="width:150px;" >
                        <button style='width: 100%; height: 100%'>
                            AdHOC SEARCH
                        </button>
                    </a>
                </td>
                <td style='text-align: center; height: 40px;'>
                        <a href='./edititemlist.php?EditItemList=EditItemListThisPage&FromRevenue=Revenues' style="width:150px;" >
                        <button style='width: 100%; height: 100%'>
                            Edit Price
                        </button>
                    </a>
                </td>
                
                <td style='text-align: center; height: 40px;' class='hide'>
                    <a href='./epaymentadhocsearch.php?AdhocSearch=AdhocSearchThisPage' style="width:150px;" >
                        <button style='width: 100%; height: 100%'>
                            ePayment AdHOC SEARCH
                        </button>
                    </a>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px;'>
                        <a href='./receptionpricelist.php?Section=Revenue&PriceList=PriceListThisPage&FromRevenue=Revenues' style="width:150px;" >
                        <button style='width: 100%; height: 100%'>
                            View Price List
                        </button>
                    </a>
                </td>
                <td style='text-align: center; height: 40px;' colspan="2">
                        <a href='performancereports.php?Section=RevenueCenter&PerformanceReport=PerformanceReportThisPage' style="width:150px;">
                            <button style='width: 100%; height: 100%'>
                                Reports
                            </button>
                        </a>
                    </td>
            </tr>
            <tr class="hide">
                <td style='text-align: center; height: 40px;' colspan="2">
                    <a href='./pricelist.php?PriceList=PriceListThisPage' style="width:150px;" >
                        <button style='width: 100%; height: 100%'>
                            Multi-Sponsors Price List
                        </button>
                    </a>
                </td>
            </tr>

                <tr class="hide">
                    <td style='text-align: center; height: 40px;' colspan="2">
                        <?php if ($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes' && $_SESSION['userinfo']['Patient_Transfer'] == 'yes') { ?>
                            <a href='transferdoctor.php?section=revenuecenter' style="width:150px;">
                                <button style='width: 100%; height: 100%'>
                                    Patient Transfer
                                </button>
                            </a>
                        <?php } else { ?>

                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Patient Transfer
                            </button>

                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: center; height: 40px;'>
                        <!--<a href='patientbillingpharmacy.php?PatientBillingPharmacy=PatientBillingPharmacyThisPage'>-->
                        <a title='REVENUE FROM OTHER SOURCES' href='revenue_from_other_sources.php?SearchListRevenueFromOtherSources=SearchListRevenueFromOtherSourcesThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Revenue From Other Sources
                            </button>
                        </a>
                    </td>
                        </tr>


            </table>
        </center>
    </fieldset>
    <br/>
    <br/>
    <br/>
<?php
    include("./includes/footer.php");
?>