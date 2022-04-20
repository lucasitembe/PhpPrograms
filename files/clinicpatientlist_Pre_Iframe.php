<?php
    @session_start();
    include("./includes/connection.php");
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];   
    }else{
        $Patient_Name = '';
    }
    $temp=1;
	
    //GET BRANCH ID
    $Folio_Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    
    //Find the current date to filter check in list
    
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date; 
    }
    ?>
    <script type='text/javascript'>
        function patientnoshow(Patient_Payment_Item_List_ID,Registration_ID,Patient_Name) {

            var Confirm_Noshow=confirm("Are You Sure You Want To No Show "+Patient_Name+" ?");

            if(Confirm_Noshow){
                if(window.XMLHttpRequest) {
                    mm = new XMLHttpRequest();
                }
                else if(window.ActiveXObject){
                    mm = new ActiveXObject('Micrsoft.XMLHTTP');
                    mm.overrideMimeType('text/xml');
                }
                mm.onreadystatechange= AJAXP; //specify name of function that will handle server response....
                mm.open('GET','patientnoshow.php?Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID,true);
                mm.send();
                return true;
            }else{
                return false;
            }

        }
        function AJAXP() {
	var data = mm.responseText;
            if(mm.readyState == 4){
                document.location.reload();
            }
        }
    </script>
    <?php
    
    $hospitalConsultType = $_SESSION['hospitalConsultaioninfo']['consultation_Type'];

    
echo '<center><table width ="100%" id="clinicpatients">';
    echo " <thead><tr ><th style='width:5%;'>SN</th><th><b>PATIENT NAME</b></th>
               <th><b>OLD PATIENT NUMBER</b></th>
                <th><b>PATIENT NUMBER</b></th>
                <th><b>SPONSOR</b></th>
                    <th><b>AGE</b></th>
                        <th><b>GENDER</b></th>
                            <th><b>PHONE NUMBER</b></th>
                                <th><b>MEMBER NUMBER</b></th>
                                <th><b>TRANS DATE</b></th>
				<th><b>ACTION</b></th>
				</tr>
                                </thead>";
    
$get_clinics_query = "
                SELECT * FROM tbl_clinic_employee ce WHERE ce.Employee_ID =" . $_SESSION['userinfo']['Employee_ID'] . "
            ";

//die($get_clinics_query);

$get_clinics_result=  mysqli_query($conn,$get_clinics_query) or die(mysqli_error($conn));



//while ($rowClinic = mysqli_fetch_array($get_clinics_result)) {
  //$Clinic_ID=$rowClinic['Clinic_ID'];
//    $myqr = "
//                SELECT pr.Registration_ID,pr.Old_Registration_Number,pr.Gender,pr.Patient_Name,pr.Phone_Number,pr.Member_Number,pl.Patient_Direction,pr.Date_Of_Birth,pl.Transaction_Date_And_Time,pl.Patient_Payment_Item_List_ID,pp.Patient_Payment_ID,sp.Guarantor_Name
//                FROM  tbl_patient_payment_item_list pl INNER JOIN tbl_patient_payments pp ON  pp.Patient_Payment_ID = pl.Patient_Payment_ID
//                JOIN tbl_patient_registration pr ON pp.Registration_ID = pr.Registration_ID
//                JOIN tbl_sponsor sp ON sp.Sponsor_ID = pr.Sponsor_ID
//                
//                WHERE pl.Process_Status= 'not served' AND 
//                      
//                      pp.Branch_ID = '$Folio_Branch_ID' AND 
//                      DATE(pl.Transaction_Date_And_Time)=CURDATE() AND
//                      pp.Transaction_status != 'cancelled'
//                 GROUP BY pl.Patient_Payment_ID,pp.Registration_ID ORDER BY pl.Transaction_Date_And_Time LIMIT 20
//            ";
    $myqr = " SELECT pr.Registration_ID,pr.Old_Registration_Number,pr.Gender,pr.Patient_Name,pr.Phone_Number,pr.Member_Number,pl.Patient_Direction,pr.Date_Of_Birth,pl.Transaction_Date_And_Time,pl.Patient_Payment_Item_List_ID,pp.Patient_Payment_ID,sp.Guarantor_Name
                FROM  tbl_patient_payment_item_list pl LEFT JOIN tbl_patient_payments pp ON  pp.Patient_Payment_ID = pl.Patient_Payment_ID
               INNER JOIN tbl_patient_registration pr ON pp.Registration_ID = pr.Registration_ID
              INNER  JOIN tbl_sponsor sp ON sp.Sponsor_ID = pr.Sponsor_ID
                
                WHERE pl.Process_Status= 'not served' AND 
                     
                      pp.Transaction_status != 'cancelled' AND
                      pp.Branch_ID = '$Folio_Branch_ID' AND
                      DATE(pl.Transaction_Date_And_Time)=CURDATE() AND
                      pp.Transaction_status != 'cancelled'
                  GROUP BY pl.Patient_Payment_ID,pp.Registration_ID ORDER BY pl.Transaction_Date_And_Time  LIMIT 20
            ";
    //die($myqr);
    
     $q="SELECT * FROM `tbl_patient_payment_item_list` LEFT JOIN tbl_patient_payments pp ON  pp.Patient_Payment_ID = pl.Patient_Payment_ID
            WHERE "
            . "   Process_Status ='not served' ";

//die($qr);



    $select_Filtered_Patients = mysqli_query($conn,$myqr) or die(mysqli_error($conn));

    while ($row = mysqli_fetch_array($select_Filtered_Patients)) {
    if($row['Patient_Direction']=='Direct To Clinic'||$row['Patient_Direction']=='Direct To Doctor'||$row['Patient_Direction']=='Direct To Doctor Via Nurse Station'||$row['Patient_Direction']=='Direct To Clinic Via Nurse Station'){
        $emeg = mysqli_query($conn,"SELECT emergency FROM tbl_nurse n WHERE  n.Patient_Payment_Item_List_ID='" . $row['Patient_Payment_Item_List_ID'] . "'") or die(mysqli_error($conn));
        $emergency = mysqli_fetch_assoc($emeg)['emergency'];
 
         $style = "";
            $startspan = "";
            $endspan = "";
        if ($emergency == 'yes') {
           $style="style='background-color:#ddfdd'";
            $startspan="<span style='color:red'>";
	    $endspan="</span>";
        }

        echo "<tr><td>$startspan" . $temp . "$endspan</td><td ><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>$startspan" . ucwords(strtolower($row['Patient_Name'])) . "$endspan</a></td>";
        echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>$startspan" . $row['Old_Registration_Number'] . "$endspan</a></td>";
        echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>$startspan" . $row['Registration_ID'] . "$endspan</a></td>";
        echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>$startspan" . $row['Guarantor_Name'] . "$endspan</a></td>";
        echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>$startspan" . $row['Date_Of_Birth'] . "$endspan</a></td>";
        echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>$startspan" . $row['Gender'] . "$endspan</a></td>";
        echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>$startspan" . $row['Phone_Number'] . "$endspan</a></td>";
        echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>$startspan" . $row['Member_Number'] . "$endspan</a></td>";
        echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>$startspan" . $row['Transaction_Date_And_Time'] . "$endspan</a></td>";
           ?>
        <td>
            <?php
            if ($hospitalConsultType == 'One patient to one doctor') {
                ?>
                <input type='button' value='NO SHOW' class='art-button-green'
                       onclick='patientnoshow("<?php echo $row['Patient_Payment_Item_List_ID']; ?>")'>

                <?php
            }
            ?>
        </td>
        </tr>
        <?php
        $temp++;
        }
    }
//}
?></table></center>