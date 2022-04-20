<?php
include("./includes/header.php");
include("./includes/connection.php");
if(!isset($_SESSION['userinfo'])){
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if(isset($_SESSION['userinfo'])){
    if(isset($_SESSION['userinfo']['Laboratory_Works'])){
        if($_SESSION['userinfo']['Laboratory_Works'] != 'yes'){
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    }else{
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
}else{
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if(isset($_GET['Patient_Payment_Item_List_ID'])){
    $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
}
?>
    <!--START HERE-->
<?php
//get the current date
$Today_Date = mysqli_query($conn,"select now() as today");
while($row = mysqli_fetch_array($Today_Date)){
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}
//    select patient information
if(isset($_GET['Registration_ID'])){
    $Registration_ID = $_GET['Registration_ID'];
    $select_Patient = mysqli_query($conn,"select
                            Old_Registration_Number,Title,Patient_Name,pr.Sponsor_ID,Date_Of_Birth,
                                    Gender,pr.Region,pr.District,pr.Ward,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID,
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,Claim_Number_Status,
                                                        Registration_ID
                                      from tbl_patient_registration pr, tbl_sponsor sp
                                        where pr.Sponsor_ID = sp.Sponsor_ID and
                                        Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Patient);
    if($no>0){
        while($row = mysqli_fetch_array($select_Patient)){
            $Registration_ID = $row['Registration_ID'];
            $Old_Registration_Number = $row['Old_Registration_Number'];
            $Title = $row['Title'];
            $Patient_Name = $row['Patient_Name'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $Gender = $row['Gender'];
            $Region = $row['Region'];
            $District = $row['District'];
            $Ward = $row['Ward'];
            $Guarantor_Name = $row['Guarantor_Name'];
            $Claim_Number_Status = $row['Claim_Number_Status'];
            $Member_Number = $row['Member_Number'];
            $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
            $Phone_Number = $row['Phone_Number'];
            $Email_Address = $row['Email_Address'];
            $Occupation = $row['Occupation'];
            $Employee_Vote_Number = $row['Employee_Vote_Number'];
            $Emergence_Contact_Name = $row['Emergence_Contact_Name'];
            $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
            $Company = $row['Company'];
            $Employee_ID = $row['Employee_ID'];
            $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
            // echo $Ward."  ".$District."  ".$Ward; exit;
        }

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1 -> diff($date2);
        //$age = $diff->y." Years, ".$diff->m." Months, ".$diff->d." Days, ".$diff->h." Hours";
        $age = $diff->y." Years, ".$diff->m." Months, ".$diff->d." Days";
    }else{
        $Registration_ID = '';
        $Old_Registration_Number = '';
        $Title = '';
        $Patient_Name = '';
        $Sponsor_ID = '';
        $Date_Of_Birth = '';
        $Gender = '';
        $Region = '';
        $District = '';
        $Ward = '';
        $Guarantor_Name = '';
        $Claim_Number_Status = '';
        $Member_Number = '';
        $Member_Card_Expire_Date = '';
        $Phone_Number = '';
        $Email_Address = '';
        $Occupation = '';
        $Employee_Vote_Number = '';
        $Emergence_Contact_Name = '';
        $Emergence_Contact_Number = '';
        $Company = '';
        $Employee_ID = '';
        $Registration_Date_And_Time = '';
        $age =0;
    }
}else{
    $Registration_ID = '';
    $Old_Registration_Number = '';
    $Title = '';
    $Sponsor_ID = '';
    $Date_Of_Birth = '';
    $Gender = '';
    $Region = '';
    $District = '';
    $Ward = '';
    $Guarantor_Name = '';
    $Claim_Number_Status = '';
    $Member_Number = '';
    $Member_Card_Expire_Date = '';
    $Phone_Number = '';
    $Email_Address = '';
    $Occupation = '';
    $Employee_Vote_Number = '';
    $Emergence_Contact_Name = '';
    $Emergence_Contact_Number = '';
    $Company = '';
    $Employee_ID = '';
    $Registration_Date_And_Time = '';
    $age =0;
}
?>
<?php
if(isset($_SESSION['userinfo'])){
    if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){
        ?>
        <!--Script to display patient optional photo-->
        <!--PATIENT PHOTO SCRIPT START-->

        <script>
            function displayPatientPhoto(){
                document.getElementById('photo').onclick = function(){
                    if(document.getElementById('photo').checked){
                        //use css style to display the photo
                        document.getElementById("PatientPhoto").style.display = "block";
                    }else{
                        document.getElementById("PatientPhoto").style.display = "none";
                    }
                };
                //hide on initial page load
                document.getElementById("PatientPhoto").style.display = "none";
            }

            window.onload = function(){
                displayPatientPhoto();
            };
        </script>


        <!--PATIENT PHOTO SCRIPT END-->
        <script type="text/javascript">
            function gotolink(){
                var url = "<?php
		if($Registration_ID!=''){
		    echo "Registration_ID=$Registration_ID&";
		}
		if(isset($_GET['Patient_Payment_ID'])){
		    echo "Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&";
		    }
		if(isset($_GET['Patient_Payment_Item_List_ID'])){
		    echo "Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&";
		    }
		?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage";
                var patientlist = document.getElementById('patientlist').value;

                if(patientlist=='MY PATIENT LIST'){
                    document.location = "doctorcurrentpatientlist.php?"+url;
                }else if (patientlist=='CLINIC PATIENT LIST') {
                    document.location = "clinicpatientlist.php?"+url;
                }else if (patientlist=='CONSULTED PATIENT LIST') {
                    document.location = "doctorconsultedpatientlist.php?"+url;
                }else{
                    alert("Choose Type Of Patients To View");
                }
            }
        </script>

        
      
        
    <?php  } } ?>
<?php
if(isset($_SESSION['userinfo'])){
    if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){
        ?>
        <a href='./Today_Lab_Patients.php?TodayLaboratoryResultsThisPage=ThisPage' class='art-button-green'>
            BACK
        </a>
    <?php  } } ?>
    <br/><br/>
    <!-- get employee id-->
<?php
if(isset($_SESSION['userinfo']['Employee_ID'])){
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}
?>

    <!-- get id, date, Billing Type,Folio number and type of chech in -->
<?php
if(isset($_GET['Registration_ID']) && isset($_GET['Patient_Payment_ID'])){
    //select the current Patient_Payment_ID to use as a foreign key

    $qr = "select * from tbl_patient_payments pp
					    where pp.Patient_Payment_ID = ".$_GET['Patient_Payment_ID']."
					    and pp.registration_id = '$Registration_ID'";
    $sql_Select_Current_Patient = mysqli_query($conn,$qr);
    $row = mysqli_fetch_array($sql_Select_Current_Patient);
    $Patient_Payment_ID = $row['Patient_Payment_ID'];
    $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
    //$Check_In_Type = $row['Check_In_Type'];
    $Folio_Number = $row['Folio_Number'];
    $Claim_Form_Number = $row['Claim_Form_Number'];
    $Billing_Type = $row['Billing_Type'];
    //$Patient_Direction = $row['Patient_Direction'];
    //$Consultant = $row['Consultant'];
}else{
    $Patient_Payment_ID = '';
    $Payment_Date_And_Time = '';
    //$Check_In_Type = $row['Check_In_Type'];
    $Folio_Number = '';
    $Claim_Form_Number = '';
    $Billing_Type = '';
    //$Patient_Direction = '';
    //$Consultant ='';
}
?>
    <!--Getting employee name -->
<?php
if(isset($_SESSION['userinfo']['Employee_Name'])){
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
}else{
    $Employee_Name = 'Unknown Employee';
}
?>

    <script type='text/javascript'>
        function access_Denied(){
            alert("Access Denied");
            document.location = "./index.php";
        }
    </script>
    <fieldset>
        <center style='background: #006400 !important;color: white;'>
            <b>LABORATORY DETAILED RESULTS<br>&nbsp;</b>
        </center>
        <br/>
        <center>
            <table width=100%>
                <tr>
                    <td>
                        <table width="100%">
                            <tr>
                                <td width='16%' style='text-align: right'><b>Patient Name</b></td>
                                <td width='26%'><input type='text' name='Patient_Name' disabled='disabled' id='Patient_Name' value='<?php if(isset($Patient_Name)){ echo $Patient_Name;} ?>'></td>
                            </tr>
                            <tr>
                                <td width='13%' style='text-align: right'><b>Card Id Expire Date</b></td>
                                <td width='16%'><input type='text' name='Card_ID_Expire_Date' disabled='disabled' id='Card_ID_Expire_Date' value='<?php echo $Member_Card_Expire_Date; ?>'></td>
                            </tr>
                            <tr>
                                <td width='13%' style='text-align: right'><b>D.O.B</b></td>
                                <td width='16%'><input type='text' name='Date_Of_Birth' id='Date_Of_Birth' value='<?php echo $Date_Of_Birth;?>' disabled='disabled'></td>
                            </tr>
                            <tr>
                                <td style='text-align: right'><b>Phone Number</b></td>
                                <td><input type='text' name='Phone_Number' id='Phone_Number' disabled='disabled' value='<?php echo $Phone_Number; ?>'></td>
                            </tr>
                            <tr>
                                <td style='text-align: right'><b>Region</b></td>
                                <td>
                                    <input type='text' name='Region' id='Region' disabled='disabled' value='<?php echo $Region; ?>'>
                                </td>
                            </tr>
                            <tr>
                                <td style='text-align: right'><b>Receipt No</b></td>
                                <td>
                                    <input type='text' name='Patient_Payment_ID' id='Patient_Payment_ID' disabled='disabled' value='<?php echo $Patient_Payment_ID; ?>'>
                                </td>
                            </tr>
                        </table>
                    </td>

                    <td>
                        <table width="100%">
                            <tr>
                                <td style='text-align: right'><b>Sponsor Name</b></td>
                                <td><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>
                            </tr>
                            <tr>
                                <td style='text-align: right'><b>Member Number</b></td>
                                <td><input type='text' name='Supervised_By' id='Supervised_By' disabled='disabled' value='<?php echo $Member_Number; ?>'></td>
                            </tr>
                            <tr>
                                <input type='hidden' id='Admission_Employee_ID' name='Admission_Employee_ID' value='<?php echo $Employee_ID;?>'>
                                <td style='text-align: right'><b>Folio Number</b></td>
                                <td><input type='text' disabled='disabled' value='<?php echo $Folio_Number; ?>'>
                                    <input type='hidden' name='Folio_Number' id='Folio_Number' value='<?php echo $Folio_Number; ?>'>
                                </td>
                            </tr>
                            <tr>
                                <td style='text-align: right'><b>Registration Number</b></td>
                                <td><input type='text' disabled='disabled' value='<?php echo $Registration_ID; ?>'>
                                    <input type='hidden' name='Registration_ID' id='Registration_ID'value='<?php echo $Registration_ID; ?>'>
                                </td>
                            </tr>
                            <tr>
                                <td style='text-align: right'><b>Consulting/Doctor</b></td>
                                <td><input type='text' name='Prepared_By' id='Prepared_By' disabled='disabled' value='<?php echo $Employee_Name; ?>'></td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table width="100%">
                            <tr>
                                <td style='text-align: right'><b>Gender</b></td>
                                <td><input type='text' name='Gender' disabled='disabled' id='Gender' value='<?php echo $Gender; ?>'></td>
                            </tr>
                            <tr>
                                <td style='text-align: right'><b>Claim Form Number</b></td>
                                <td><input type='text' name='Admission_Claim_Form_Number' disabled='disabled'  id='Admission_Claim_Form_Number'<?php if($Claim_Number_Status == "Mandatory"){?> required='required'<?php }?> value='<?php echo $Claim_Form_Number;?>'></td>
                            </tr>
                            <tr>
                                <input type='hidden' id='Admission_Employee_ID' name='Admission_Employee_ID' value='<?php echo $Employee_ID;?>'>
                                <td style='text-align: right'><b>Bill Type</b></td>
                                <td><input type='text' name='Billing_Type' disabled='disabled' id='Billing_Type' value='<?php echo $Billing_Type; ?>'>

                                </td>
                            </tr>
                            <tr>
                                <td style='text-align: right'><b>Patient Age</b></td>
                                <td><input type='text' name='Patient_Age' id='Patient_Age'  disabled='disabled' value='<?php echo $age; ?>'></td>
                            </tr>
                            <tr>
                                <td style='text-align: right'><b>Consulting/Doctor</b></td>
                                <td><input type='text' name='Prepared_By' id='Prepared_By' disabled='disabled' value='<?php echo $Employee_Name; ?>'></td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table width="100%">
                            <tr>
                                <td style='text-align: right;width: 100%;'>
                                    <fieldset id="PatientPhoto">
                                        <legend>Patient Photo</legend>
                                        <div>
                                            <img src="patientImages/default.PNG" alt="PatientPhoto" width="100%"/>
                                        </div>
                                    </fieldset>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </center>
    </fieldset>


    <script type='text/javascript'>
        function substitute(Item_ID_location){
            var Item_ID = document.getElementById('Item_ID_'+Item_ID_location+"").value;
            var Patient_Payment_Item_List_ID="<?php echo $_GET['Patient_Payment_Item_List_ID']; ?>";
            var quantity = document.getElementById('Quantity_'+Item_ID_location+"").value;
            var responce = confirm('Are You Sure You Want To Substitute This Item');
            var Billing_Type = '<?php echo $Billing_Type; ?>';
            var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
            if (responce){
                if(window.XMLHttpRequest) {
                    mm_sbst_object = new XMLHttpRequest();
                }
                else if(window.ActiveXObject){
                    mm_sbst_object = new ActiveXObject('Micrsoft.XMLHTTP');
                    mm_sbst_object.overrideMimeType('text/xml');
                }
                mm_sbst_object.onreadystatechange= function (){
                    if (mm_sbst_object.readyState == 4) {
                        var ajax_responce = mm_sbst_object.responseText;
                        if(ajax_responce=='sent'){
                            location.reload();
                        }
                    }
                }; //specify name of function that will handle server response....
                mm_sbst_object.open('GET','doctocr_ajax_substitute.php?Item_ID='+Item_ID+"&Patient_Payment_Item_List_ID="+Patient_Payment_Item_List_ID+"&quantity="+quantity+"&Billing_Type="+Billing_Type+"&Guarantor_Name="+Guarantor_Name,true);
                mm_sbst_object.send();
            }else{
            }
        }

        function changeItem(Item_ID,Item_ID_local) {
            if (Item_ID!=''){
                var Billing_Type = '<?php echo $Billing_Type; ?>';
                var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
                if(window.XMLHttpRequest) {
                    mm = new XMLHttpRequest();
                }
                else if(window.ActiveXObject){
                    mm = new ActiveXObject('Micrsoft.XMLHTTP');
                    mm.overrideMimeType('text/xml');
                }
                mm.onreadystatechange= function (){
                    if (mm.readyState == 4) {
                        var data4 = mm.responseText;

                        document.getElementById('price_'+Item_ID_local+"").value = data4;

                        var price = document.getElementById('price_'+Item_ID_local+"").value;

                        var quantity = document.getElementById('Quantity_'+Item_ID_local+"").value;
                        var ammount = 0;
                        ammount = price*quantity;
                        document.getElementById('amount_'+Item_ID_local+"").value = ammount;
                    }
                }; //specify name of function that will handle server response....
                mm.open('GET','Get_Item_price.php?Product_Name='+Item_ID+'&Billing_Type='+Billing_Type+'&Guarantor_Name='+Guarantor_Name,true);
                mm.send();
            }
        }
    </script>
<?php
    if(isset($_GET['Patient_Payment_ID'])){ ?>
        <fieldset style='overflow-y: scroll;height: 200px;'>
            <table  width=100%>
                <tr>
                    <td style='width: 3%;'><center><b>S/N</b></center></td>
                    <td style='width: 10%;'><b>Test ID</b></td>
                    <td style='width: 40%;'><b>Test Name</b></td>
                    <td style='width: 15%;'><b>Result Date And Time</b></td>
                    <td style="width: 10%;text-align: left"><b>Test Status</b></td>
                    <!--<td style='width: 8%;text-align: center'><b>Action</b></td>-->
                </tr>
                <?php
                if(isset($_GET['Patient_Payment_ID'])){
                    $Patient_Payment_ID= $_GET['Patient_Payment_ID'];
                    if($_GET['Status_From'] == 'payment'){
                        $qr = mysqli_query($conn,"SELECT * FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp,tbl_items it,tbl_patient_registration pr,tbl_sponsor sp
                                          WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID
                                          AND ppl.Item_ID=it.Item_ID
                                          AND pp.Registration_ID=pr.Registration_ID
                                          AND ppl.Item_ID=it.Item_ID
                                          AND sp.Sponsor_ID=pr.Sponsor_ID
                                          AND pp.Registration_ID='$Registration_ID'
                                          AND pp.Patient_Payment_ID='$Patient_Payment_ID'") or die(mysqli_error($conn));
                    }else{
                        $qr = mysqli_query($conn,"SELECT * FROM tbl_item_list_cache il,tbl_payment_cache pc,tbl_items it,tbl_patient_registration pr,tbl_sponsor sp
                                          WHERE il.Payment_Cache_ID=pc.Payment_Cache_ID
                                          AND il.Item_ID=it.Item_ID
                                          AND pc.Registration_ID=pr.Registration_ID
                                          AND il.Item_ID=it.Item_ID
                                          AND sp.Sponsor_ID=pr.Sponsor_ID
                                          AND pc.Registration_ID='$Registration_ID'
                                          AND pc.Payment_Cache_ID='$Patient_Payment_ID'") or die(mysqli_error($conn));
                    }
                }
                
                $i=1;
                while($row=mysqli_fetch_array($qr)){
                    $Item_ID=$row['Item_ID'];
                    ?>
                    <tr>
                        <td><input type='text' value='<?php echo $i;?>' style='width: 50px; text-align: center;' readonly="readonly"></td>
                        <td><input type='text' id='Product_Code_<?php echo $row['Item_ID']; ?>' name='Product_Code_<?php echo $row['Item_ID']; ?>' style='width: 200px;' value='<?php echo $row['Item_ID'];?>' readonly="readonly"></td>
                        <td><input type='text' id='Product_Name_<?php echo $row['Product_Name']; ?>' name='Product_Name_<?php echo $row['Product_Name']; ?>' ' value='<?php echo $row['Product_Name'];?>' readonly="readonly" style="width: 100%"></td>
                        <td>
                            <?php
                                if($_GET['Status_From'] == 'payment'){
                                    $query = mysqli_query($conn,"SELECT 'payment' AS Status_From,pr.Registration_ID,pr.Patient_Name,pr.Member_Number,pr.Gender,pr.Sponsor_ID,sp.Sponsor_ID,sp.Guarantor_Name,pr.Date_Of_Birth,pr.Phone_Number,ppr.Item_ID,ppr.Patient_Payment_ID,ppr.Item_ID,ppr.Result_Datetime,it.Product_Name
                                                      FROM  tbl_patient_payment_results ppr
                                                      JOIN tbl_patient_registration pr ON ppr.Patient_ID = pr.Registration_ID
                                                      JOIN tbl_patient_payments pp ON ppr.Patient_Payment_ID = pp.Patient_Payment_ID
                                                      JOIN tbl_sponsor sp ON sp.Sponsor_ID = pr.Sponsor_ID
                                                      JOIN tbl_items it ON it.Item_ID = ppr.Item_ID
                                                      WHERE ppr.Patient_ID = pr.Registration_ID
                                                      AND ppr.Patient_Payment_ID = pp.Patient_Payment_ID
                                                      AND pr.Sponsor_ID = sp.Sponsor_ID
                                                      AND ppr.Item_ID = it.Item_ID
                                                      AND ppr.Patient_ID = '$Registration_ID'
                                                       AND ppr.Patient_Payment_ID = '$Patient_Payment_ID'
                                                       AND ppr.Item_ID='$Item_ID'
                                                       GROUP BY ppr.Item_ID") or die(mysqli_error($conn));
                                }else{
                                    $query = mysqli_query($conn,"SELECT 'cache' AS Status_From,pr.Registration_ID,pr.Patient_Name,pr.Member_Number,pr.Gender,pr.Sponsor_ID,sp.Sponsor_ID,sp.Guarantor_Name,pr.Date_Of_Birth,pr.Phone_Number,pcr.Item_ID,pcr.Payment_Cache_ID,pcr.Item_ID,pcr.Result_Datetime,it.Product_Name
                                                      FROM  tbl_patient_cache_results pcr
                                                      JOIN tbl_patient_registration pr ON pcr.Patient_ID = pr.Registration_ID
                                                      JOIN tbl_payment_cache pc ON pcr.Payment_Cache_ID = pc.Payment_Cache_ID
                                                      JOIN tbl_sponsor sp ON sp.Sponsor_ID = pr.Sponsor_ID
                                                      JOIN tbl_items it ON it.Item_ID = pcr.Item_ID
                                                      WHERE pcr.Patient_ID = pr.Registration_ID
                                                      AND pcr.Payment_Cache_ID = pc.Payment_Cache_ID
                                                      AND pr.Sponsor_ID = sp.Sponsor_ID
                                                      AND pcr.Item_ID = it.Item_ID
                                                       AND pcr.Patient_ID = '$Registration_ID'
                                                       AND pcr.Payment_Cache_ID = '$Patient_Payment_ID'
                                                       AND pcr.Item_ID='$Item_ID'GROUP BY pcr.Item_ID") or die(mysqli_error($conn));
                                }
                                
                                $row2=mysqli_fetch_assoc($query);
                                if(mysqli_num_rows($query)== 0){?>
                                    <input type='text' id='Product_Result_Datetime_<?php echo date("j F,Y H:i:s",strtotime($row['Result_Datetime'])); ?>' name='Product_Name_<?php echo date("j F,Y H:i:s",strtotime($row['Result_Datetime'])); ?>' style='width: 100%;' value='Not Yet Published' readonly="readonly">
                                <?php }else{?>
                                    <input type='text' id='Product_Result_Datetime_<?php echo date("j F,Y H:i:s",strtotime($row2['Result_Datetime'])); ?>' name='Product_Name_<?php echo date("j F,Y H:i:s",strtotime($row2['Result_Datetime'])); ?>' style='width: 100%;' value='<?php echo date("jS F, Y H:i:s",strtotime($row2['Result_Datetime'])); ?>' readonly="readonly">
                                <?php }
                                
                                
                            ?>
                        </td>
                        <td>
                            <?php
                                    if($_GET['Status_From'] == 'payment'){
                                        $select_result = mysqli_query($conn,"SELECT * FROM tbl_patient_payment_results ppr,tbl_items it,
                                                                                    tbl_patient_payments pp,
                                                                                    tbl_patient_registration pr,
                                                                                    tbl_laboratory_parameters lp,
                                                                                    tbl_laboratory_results_submition lrs,
                                                                                    tbl_laboratory_results_validation lrv
                                                                              WHERE ppr.Patient_ID=pr.Registration_ID
                                                                              AND ppr.Item_ID=it.Item_ID
                                                                              AND ppr.Patient_Payment_ID=pp.Patient_Payment_ID
                                                                              AND ppr.Laboratory_Parameter_ID=lp.Laboratory_Parameter_ID
                                                                              AND ppr.Patient_Payment_Result_ID = lrs.Patient_Payment_Result_ID
                                                                              AND ppr.Patient_Payment_Result_ID = lrv.Patient_Payment_Result_ID
                                                                              AND ppr.Patient_Payment_ID = '$Patient_Payment_ID'
                                                                              AND ppr.Item_ID = '$Item_ID'
                                                                              AND ppr.Patient_ID = '$Registration_ID' GROUP BY ppr.Item_ID ");
                                            if(mysqli_num_rows($select_result) > 0){
                                                echo "<b style='text-align:center;color: blue;font-size: 14px;font-weight: bold;'>Done</b>";
                                            }else{
                                                echo "<b style='text-align:center;color: red;font-size: 14px;font-weight: bold;'>Provisional</b>";
                                            }
                                    }else{
                                        $select_result = mysqli_query($conn,"SELECT * FROM tbl_patient_cache_results pcr,tbl_items it,
                                                                                    tbl_payment_cache pc,
                                                                                    tbl_patient_registration pr,
                                                                                    tbl_laboratory_parameters lp,
                                                                                    tbl_laboratory_results_submition_cache lrsc,
                                                                                    tbl_laboratory_results_validation_cache lrvc
                                                                              WHERE pcr.Patient_ID=pr.Registration_ID
                                                                              AND pcr.Item_ID=it.Item_ID
                                                                              AND pcr.Payment_Cache_ID=pc.Payment_Cache_ID
                                                                              AND pcr.Laboratory_Parameter_ID=lp.Laboratory_Parameter_ID
                                                                              AND pcr.Patient_Cache_Results_ID = lrsc.Patient_Cache_Results_ID
                                                                              AND pcr.Patient_Cache_Results_ID = lrvc.Patient_Cache_Results_ID
                                                                              AND pcr.Payment_Cache_ID = '$Patient_Payment_ID'
                                                                              AND pcr.Item_ID = '$Item_ID'
                                                                              AND pcr.Patient_ID = '$Registration_ID' GROUP BY pcr.Item_ID ");
                                        if(mysqli_num_rows($select_result) > 0){
                                            echo "<b style='text-align:center;color: blue;font-size: 14px;font-weight: bold;'>Done</b>";
                                        }else{
                                            echo "<b style='text-align:center;color: red;font-size: 14px;font-weight: bold;'>Provisional</b>";
                                        }

                                    }
                                ?>
                        </td>
                        <!--<td><a href="detailed_laboratory_result.php?Status_From=<?php echo $_GET['Status_From']?>&patient_id=<?php echo $Registration_ID?>&payment_id=<?php echo $Patient_Payment_ID?>&Item_ID=<?php echo $Item_ID?>" class="art-button-green" style="width: 50%;text-align: center">View Result</a></td>-->
                    </tr>
                <?php
                $i++;
                }
                
                ?>
            </table>
        </fieldset>
    <?php }else{

    }
?>
    <!--END HERE-->
<?php
include("./includes/footer.php");
?>