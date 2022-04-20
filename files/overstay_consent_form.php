<?php
include("./includes/connection.php");
include("./includes/header.php");
// include("../signature/index.php");

session_start();
$consultation_ID = $_GET['consultation_ID'];
$Admision_ID = $_GET['Admision_ID'];
$Check_In_ID = $_GET['Check_In_ID'];
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
    $sql_select_patient_information_result = mysqli_query($conn,"SELECT Patient_Name, Sponsor_ID, Date_Of_Birth,Region,District,Ward,village,Gender,Member_Number,Phone_Number FROM tbl_patient_registration WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_patient_information_result)>0){
        while($pat_details_rows=mysqli_fetch_assoc($sql_select_patient_information_result)){
            $Patient_Name =$pat_details_rows['Patient_Name'];
            $Date_Of_Birth =$pat_details_rows['Date_Of_Birth'];
            $Region =$pat_details_rows['Region'];
            $District =$pat_details_rows['District'];
            $Ward =$pat_details_rows['Ward'];
            $village =$pat_details_rows['village'];
            $Gender =$pat_details_rows['Gender'];
            $Phone_Number = $pat_details_rows['Phone_Number'];
            $Member_Number = $pat_details_rows['Member_Number'];
            $Sponsor_ID = $pat_details_rows['Sponsor_ID'];

            $date1 = new DateTime($Today);
            $date2 = new DateTime($pat_details_rows['Date_Of_Birth']);
            $diff = $date1->diff($date2);
            $age = $diff->y . " Years, ";
            $age .= $diff->m . " Months, ";
            $age .= $diff->d . " Days";
        }
    }

    $sql_select_admission_ward_result=mysqli_query($conn,"SELECT hw.Hospital_Ward_Name, ci.Check_In_ID, ci.AuthorizationNo FROM tbl_hospital_ward hw, tbl_admission ad, tbl_check_in ci, tbl_check_in_details cid WHERE hw.Hospital_Ward_ID = ad.Hospital_Ward_ID AND ad.Registration_ID='$Registration_ID' AND ad.Admision_ID = '$Admision_ID' AND ad.Admission_Status<>'Discharged' AND cid.consultation_ID = '$consultation_ID' AND ad.Admision_ID = cid.Admission_ID AND ci.Check_In_ID = cid.Check_In_ID") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_admission_ward_result)>0){
        while($wodini = mysqli_fetch_assoc($sql_select_admission_ward_result)){
            $Hospital_Ward_Name = $wodini['Hospital_Ward_Name'];
            $AuthorizationNo = $wodini['AuthorizationNo'];
            $Check_In_ID = $wodini['Check_In_ID'];
        }
    }else{
        $Hospital_Ward_Name = '<b>NOT ADMITTED</b>';
    }


    

  $date2= date('d, D, M, Y');
  $time= date('h:m:s');

                $select_conset_detail = mysqli_query($conn,"SELECT cd.Overstay_Form_ID, cd.consent_by, cd.Signed_at, em.Employee_Name, cd.consent_amputation, cd.behalf FROM tbl_consert_blood_forms_details cd, tbl_employee em WHERE Registration_ID = '$Registration_ID' AND consultation_id = '$consultation_id' AND em.Employee_ID = cd.Employee_ID");
                $datazangu = ($select_conset_detail > 0);
                if($datazangu){
                    while ($row = mysqli_fetch_array($select_conset_detail)) {
                        // `PROCEDURES`, `REPRESENTATIVE`, `WITNESS_NAME`, `DOCTOR`, `DATE_SIGNED`,
                        $consent_by=$row['consent_by'];
                        $Signed_at=$row['Signed_at'];
                        $Employee_Name=$row['Employee_Name'];
                        $Overstay_Form_ID=$row['Overstay_Form_ID'];
                        $consent_amputation = $row['consent_amputation'];
                        $behalf = $row['behalf'];

                    }
                }    
     }

$Current_Employee_ID = $_SESSION['userinfo']['Employee_ID'];
$Current_Employee_Name= $_SESSION['userinfo']['Employee_Name'];

           $select_Filtered_Doctors = mysqli_query($conn,
              "SELECT * from tbl_employee where
                   Employee_Type = 'doctor' order by employee_name") or die(mysqli_error($conn)); 
                   while($row = mysqli_fetch_array($select_Filtered_Doctors)){
           $select.="
           <option value=".$row['Employee_ID']."> Dr. ".$row['Employee_Name']." </option>
           ";
         }

         $select_magonjwa = mysqli_query($conn, "SELECT disease_code FROM tbl_disease_consultation dc, tbl_disease d WHERE d.disease_ID = dc.disease_ID AND consultation_ID = '$consultation_ID'");
         $idadi = mysqli_num_rows($select_magonjwa);
           while($disease = mysqli_fetch_assoc($select_magonjwa)){
               $disease_code = $disease['disease_code'];
               // $magonjwa = $disease_code;
               $magonjwa = $disease_code.", ".$magonjwa;
           }
    

           $hospital_info = mysqli_query($conn, "SELECT Hospital_Name, Box_Address, facility_code FROM tbl_system_configuration");
           while($data = mysqli_fetch_assoc($hospital_info)){
               $Hospital_Name = $data['Hospital_Name'];
               $Box_Address = $data['Box_Address'];
               $facility_code = $data['facility_code'];
           }

           $select_Filtered_Donors = mysqli_query($conn, "SELECT inp.Overstay_Form_ID, inp.consultation_ID, inp.Reason_For_Overstaying, inp.Check_In_ID, em.Employee_Name, hw.Hospital_Ward_Name, inp.Signed_Date_Time, ad.Admission_Date_Time FROM tbl_inpatient_overstaying inp, tbl_hospital_ward hw, tbl_ward_rooms wr, tbl_employee em, tbl_admission ad WHERE em.Employee_ID = inp.Employee_ID AND hw.Hospital_Ward_ID = ad.Hospital_Ward_ID AND ad.Admision_ID = inp.Admision_ID AND wr.ward_room_id AND inp.ward_room_id AND em.Employee_ID = inp.Employee_ID AND inp.Registration_ID = '$Registration_ID' AND inp.consultation_ID = '$consultation_ID' AND inp.Check_In_ID = '$Check_In_ID' GROUP BY inp.Overstay_Form_ID ORDER BY inp.Overstay_Form_ID DESC LIMIT 1") or die(mysqli_error($conn));


            while ($row = mysqli_fetch_array($select_Filtered_Donors)) {

                $Room_Type = $row['Room_Type'];
                $Employee_Name = $row['Employee_Name'];
                $Hospital_Ward_Name = $row['Hospital_Ward_Name'];
                $Signed_Date_Time = $row['Signed_Date_Time'];
                $Admission_Date_Time = $row['Admission_Date_Time'];
                $Reason_For_Overstaying = $row['Reason_For_Overstaying'];
                $Overstay_Form_ID = $row['Overstay_Form_ID'];
            }

            $signature_check = mysqli_query($conn, "SELECT `signature` FROM tbl_check_in WHERE Check_In_ID = '$Check_In_ID'");
            $Signature = mysqli_fetch_assoc($signature_check)['signature'];

    $Clinic_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Clinic_Name FROM tbl_clinic c, tbl_consultation cn WHERE c.Clinic_ID = cn.Clinic_ID AND cn.consultation_ID = '$consultation_ID'"))['Clinic_Name'];
    $msg="
    <tr>
<td colspan='8' style='text-align:center;' >
<font color='blue;'> <b>Take Patient Signature</b></font>
</td>
</tr>
    ";

 
 $doctor_button="
 <a target='_blank' href='../esign/employee_signature.php?Employee_ID=".$Responsible_dr."&ChangeUserPassword=ChangeUserPasswordThisPage' class='art-button-green'>CHUKUA SAINI YA DAKTARI</a>
 ";
 $patient_button="
 <a target='_blank' href='../esign/signature.php?Registration_ID=".$Registration_ID."&Check_In_ID=".$Check_In_ID."'class='art-button-green'  style='border-radius: 5px; height: 35px;'>TAKE PATIENT SIGNATURE</a>

 ";

 $printbtn="
 <a target='_blank' href='preview_overstay_form.php?Registration_ID=".$Registration_ID."&Overstay_Form_ID=".$Overstay_Form_ID."&consultation_ID=".$consultation_ID."&Check_In_ID=".$Check_In_ID."&Admision_ID=".$Admision_ID."' class='art-button-green' style='border-radius: 5px; height: 35px;'>PREVIEW AND PRINT</a>

 ";
?>
<a href="patient_overstay_list.php" class='art-button-green'>BACK</a>

<!DOCTYPE html>
<html>
<head>
<style type="text/css">
    /* .labefor{display:block;width: 100% }
    .labefor:hover{background-color: #a8d1ff;cursor: pointer}
    label.labefor { width: 100%;  */             
                    .rows_list{ 
                        cursor: pointer;
                    }
                    .rows_list:active{
                        color: #328CAF!important;
                        font-weight:normal!important;
                    }
                    .rows_list:hover{
                        color:#00416a;
                        background: #dedede;
                        font-weight:bold;
                    }
                    a{
                        text-decoration: none;
                    }
                    
                input[type="checkbox"]{
                    width: 25px; 
                    height: 25px;
                    cursor: pointer;
                    margin: 5px;
                    margin-right: 5px;
                }

                input[type="radio"]{
                    width: 25px; 
                    height: 25px;
                    cursor: pointer;
                    margin: 5px;
                    margin-right: 5px;
                }
                #th{
                    background:#99cad1;
                }

                #spu_lgn_tbl{
                    width:100%;
                    border:none!important;
                }
                #spu_lgn_tbl tr, #spu_lgn_tbl tr td{
                    border:none!important;
                    padding: 5px;
                    font-size: 16PX;
                }
                #spu_lgn_tbl tr, #spu_lgn_tbl tr th{
                    border:none!important;
                    padding: 5px;
                    font-size: 18PX;
                }
                *{
                  font-size: 17px;
                }
                .button_container{
                  width: 90%;
                  border: 1px solid silver;
                  margin: 10px;
                }
                .button_alignment{
                  width: 33%;
                  border: 1px solid silver;
                  position: relative;
                  display: inline-block;
                  margin: auto;
                }

            #surgery_doctor{
                padding: 5px;
                border-top: none;
                font-weight: bold;
                font-size: 16px;
            }
            p{
                text-align: justify;
            }
</style>
</head>
<body>
<center>
                  
  <br/>
<table class="table"id="spu_lgn_tbl" width='60%'>
                <tbody>
                </tbody>
        </table>
<section style="width:79%; ">
<fieldset ><form action="" method="post">
<legend align="center"><b>PATIENT OVERSTAY NOTIFICATION FORM</b></legend>


<table style="font-size:20px; border: none;" width = 100%; border='0' id='spu_lgn_tbl'>
<?php if($Overstay_Form_ID > 0){ echo $msg; }?>
<tr>           <td colspan="8" style="text-align:center"><img src="./branchBanner/NHIF.png" width='200px'></td></tr>
                <tr>
                <td colspan="8" style="text-align:center"><b>PATIENT OVERSTAY NOTIFICATION FORM</b></td> 
                </tr>
                <tr>
                <td colspan="8" style="text-align:center"><b>This form should be utilized only when the Patient overstay form more than 5 days in ICU and 10 days in General Wards</b></td> 
                </tr>
                <tr>
                    <th colspan="8" style="text-align:left;">
                        A. Patient Information
                    </th>
                </tr>
                <tr>
                    <td style='width: 15%;text-align:right;'>Name of Patient:</td>
                    <td style='width: 22%;'><b> <?php echo $Patient_Name; ?></b></td>
                    <td style="text-align:right; width: 10%;">Hosp Reg. No:</td>
                    <td style="width:12%;"><b><?php echo $Registration_ID; ?></b></td>
                    <td style="text-align:right; width: 5%;">Age:</td>
                    <td style="width: 17%;"><b><?php echo $age; ?> </b></td>
                    <td style="width:7%;text-align:right;">Sex: </td>
                    <td><b><?php echo $Gender; ?></b>
                    </td>  
                </tr>
                <tr>
                    <td style="text-align:right;">Membership Number: </td>
                    <td><b> <?php echo $Member_Number; ?></b></td>
                    <td style="text-align:right; width: 8%;">Phone Number:</td>
                    <td style="width:7%;"><b><?php echo $Phone_Number; ?></b></td>
                </tr>
                <tr>
                    <td style="text-align:right;">Disease(s) Code Number: </td>
                    <td><b> <?php echo $magonjwa; ?></b></td>
                    <td style="text-align:right; width: 8%;">Authorization No.:</td>
                    <td style="width:7%;"><b><?php echo $AuthorizationNo; ?></b></td>
                </tr>
</table>
<br/>
            <table style="font-size:20px" width = 90%; border="0" id='spu_lgn_tbl'>
                            
            <tr>

            </tr>
            <tr>
                     <th colspan="8" style="text-align:left;">
                        B. Treating Health Facility (Hospital/Health Centre)
                    </th>
            </tr>
            <tr>
                    <td style='width: 15%;text-align:right;'>Name of Health Facility:</td>
                    <td style='width: 22%;'><b> <?php echo $Hospital_Name; ?></b></td>
                    <td style="text-align:right; width: 12%;">Accredential Number:</td>
                    <td style="width:8%;"><b><?php echo $facility_code; ?></b></td>
                    <td style="text-align:right; width: 15%;">Address of Facility:</td>
                    <td style="width: 17%;"><b><?php echo $Box_Address; ?> </b></td> 
            </tr>
            <tr>
                    <td style='width: 15%;text-align:right;'>Department:</td>
                    <td style='width: 22%;'><b> <?php echo $Clinic_Name; ?></b></td>
                    <td style="text-align:right; width: 12%;">Ward Number:</td>
                    <td style="width:6%;"><b><?php echo $Hospital_Ward_Name; ?></b></td>
            </tr>
            <tr>
                    <td style='width: 15%;text-align:right;'>Admission Date:</td>
                    <td style='width: 22%;'><b> <?php echo date('d, M Y', strtotime($Admission_Date_Time)); ?></b></td>
                    <td style="text-align:right; width: 12%;">Notification Date:</td>
                    <td style="width:12%;"><b><?php echo date('d, M Y', strtotime($Signed_Date_Time)); ?></b></td>
            </tr>
            <tr>
                <td>
                    <br>
                </td>
            </tr>
            <tr>
                <td colspan="8">Reason for Overstay more than ten (10) days</td>
            </tr>
            <tr>
                <td colspan="8"> 
                    <p><?php echo $Reason_For_Overstaying; ?>
                </td>
            </tr>

<?php if($Overstay_Form_ID > 0){ echo $msg; } ?> 
</tr>
<tr>
<td>
<tr>
                <td  colspan="2"style="text-align:left" ><b>Patient Name
                <input type="text" name="behalf" id='behalf' placeholder="Guardian/Proxy:..." value="<?php echo $Patient_Name; ?> ">
                </td>
                <td style="text-align:right" >Tarehe: 
                <td style="text-align:left" ><b>
                <?php echo $Signed_at ?>
                </td>
                </tr>
<tr>
<td><input type="button" name="" onclick="save_consent_data2()" width="100%" class="art-button-green" value="HAKIKI NA HIFADHI" style="border-radius: 5px; height: 35px; display: none;">
<?php if($Overstay_Form_ID > 0) { echo $patient_button; }?>
</td>
<td>

</td>
<td>
<?php
if(($Signature) != 0)  {
    echo $printbtn; 
}
?>
</td>
</tr>
</table>
<div id="procedure_list"></div>

</fieldset>
</form>
</section>
</center>
<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css" />
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="css/jquery-ui.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>





<script>
 
  var toolbarOptions = [
  ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
  //['blockquote', 'code-block'],

  //[{ 'header': 1 }, { 'header': 2 }],               // custom button values
  [{ 'list': 'ordered'}, { 'list': 'bullet' }],
  [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
  [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
  [{ 'direction': 'rtl' }],                         // text direction

 // [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
  [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

  [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
  [{ 'font': [] }],
  [{ 'align': [] }],

  ['clean']                                         // remove formatting button
];
  var quill = new Quill('.editorC', {
    modules: {
      toolbar: toolbarOptions
    },
    theme: 'snow'
  });
  function logHtmlContentC() {
    
    console.log(quill.root.innerHTML);
    var htmlcodeC=quill.root.innerHTML;
    var Registration_ID=<?php echo $Registration_ID;?>;
   
    //alert(htmlcodeC)
    // alert(Registration_ID)


    $.ajax({
        type:'POST',
        url:'save_param.php',
        data:{htmlcodeC:htmlcodeC,
        location:"to_update",
        Registration_ID:Registration_ID

        },
        success(response){
            alert(response);
        }
    });

  }
  
  function ajax_procedure_dialog_open(){
        $.ajax({
            type:'POST',
            url:'ajax_anasthesia_procedure_dialog1.php',
            data:{procedure_dialog:''},
            success:function(responce){                
                $("#procedure_list").dialog({
                    title: 'PROPOSSED PROCEDURE',
                    width: '85%',
                    height: 600,
                    modal: true,
                });
                $("#procedure_list").html(responce)
                ajax_search_procedure()
            }
        });
    }

    function ajax_search_procedure(){
        var Product_Name = $("#procedure_name").val();
        $.ajax({
            type:'POST',
            url:'ajax_anasthesia_procedure_dialog1.php',
            data:{Product_Name:Product_Name, search_procedure:''},
            success:function(responce){
                $("#list_of_all_procedure").html(responce);
            }
        });
    }
    function save_anasthesia_procedure(Item_ID){
        var Registration_ID='<?= $Registration_ID ?>';
        $.ajax({
            type: 'POST',
            url: 'ajax_anasthesia_procedure_dialog1.php',
            data:{Registration_ID:Registration_ID,Item_ID:Item_ID, save_procedure:'save_procedure'},
            success:function(responce){
                display_selected_procedure()
            }
        });
    }
    function  display_selected_procedure(){
        var Registration_ID='<?php echo $Registration_ID; ?>';
        $.ajax({
            type:'POST',
            url:'ajax_anasthesia_procedure_dialog1.php',
            data:{Registration_ID:Registration_ID, display_procedure:'display_procedure'  },
            success:function(responce){
                $("#list_of_selected_procedure").html(responce)
            }
        });
    }

    function remove_anasthesia_procedure(Procedure_ID){
        $.ajax({
            type:'POST',
            url:'ajax_anasthesia_procedure_dialog1.php',
            data:{Procedure_ID:Procedure_ID, remove_procedure:''},
            success:function(responce){
                display_selected_procedure()
            }
        });
    }
    function view_procedure_selected(){
        var Registration_ID='<?php echo $Registration_ID; ?>';
        
        $.ajax({
            type:'POST',
            url:'ajax_anasthesia_procedure_dialog1.php',
            data:{Registration_ID:Registration_ID,view_procedure:''},
            success:function(responce){
                $("#proposed_procedure").val(responce)
                $("#procedure_list").dialog("close")}
        });
    }
    window.setInterval('refresh()', 10000); 	
    // Call a function every 10000 milliseconds 
    // (OR 10 seconds).

    // Refresh or reload page.
    function refresh() {
        window .location.reload();
    }
</script>

<!-- <input type="text" name="Registration_ID" hidden value="<?php ;?>" > -->
<!-- <input type="text" id="Registration_ID" value="<?php ;?>" > -->


</html>

<?php
    include("./includes/footer.php");
?>