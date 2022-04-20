<?php
@session_start();
include("./includes/connection.php");

$filter = '';
$filterIn = '';
$patient_type = '';
if (isset($_GET['Date_From']) && !empty($_GET['Date_From'])) {
    $fromDate = $_GET['Date_From'];
    $toDate = $_GET['Date_To'];
    $bill_type = $_GET['bill_type'];
    $start_age= $_GET['start_age'];
    $end_age = $_GET['end_age'];
    
    $Disease_Name = mysqli_real_escape_string($conn,$_GET['Disease_Name']);
    $filter = "  and dc.Disease_Consultation_Date_And_Time between '$fromDate' and '$toDate' and  TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) BETWEEN '$start_age' AND '$end_age'";
    $filterIn = "  and wd.Round_Disease_Date_And_Time between '$fromDate' and '$toDate' and  TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) BETWEEN '$start_age' AND '$end_age'";
    if (!empty($Disease_Name)) {
        $filter .=" and d.disease_name like '%$Disease_Name%'";
        $filterIn .=" and d.disease_name like '%$Disease_Name%'";
    }
    
    $patient_type = '<b>Patient Type:</b>' . $bill_type;

    if ($bill_type != 'All') {
        $patient_type = '<b>Patient Type:</b>' . $bill_type;
    }

}
//if (isset($_GET['Date_From']) && !empty($_GET['Date_From'])) {
//    $fromDate = $_GET['Date_From'];
//    $toDate = $_GET['Date_To'];
//    $bill_type = $_GET['bill_type'];
//    $Disease_Name = mysqli_real_escape_string($conn,$_GET['Disease_Name']);
//    $filter = "  and dc.Disease_Consultation_Date_And_Time between '$fromDate' and '$toDate'";
//    $filterIn = "  and wd.Round_Disease_Date_And_Time between '$fromDate' and '$toDate'";
//    if (!empty($Disease_Name)) {
//        $filter .=" and d.disease_name like '%$Disease_Name%'";
//        $filterIn .=" and d.disease_name like '%$Disease_Name%'";
//    }
//
//    $patient_type = '<b>Patient Type:</b>' . $bill_type;
//
//    if ($bill_type != 'All') {
//        $patient_type = '<b>Patient Type:</b>' . $bill_type;
//    }
//
////    if ($SubCategory != 'All') {
////        $filterSub .=" AND i.Item_Subcategory_ID='$SubCategory'";
////    }
//}

$htm = "<img src='branchBanner/default.png'>";
$htm.="";
$htm.="<p align='center'><b>PROVISIONAL & DIAGNOSED DISEASES <br/><br/>$patient_type<br/>"
        . "<br/><b>From</b>  " . date('j F,Y H:i:s', strtotime($fromDate)) . " <b>To</b>  " . date('j F,Y H:i:s', strtotime($toDate)) . ""
        . "<br/><br/><b>AGE From</b>  " . $start_age . " <b>To</b>  " . $end_age . ""
        . "</p>";
$htm.="<center>";

$htm.=' <table style="width:100%;border:0;background-color:rgb(255,255,255)" id="viewDiagnosisdd" >
        <thead>
			<!--<tr>
				<th width="5%">SN</th>
				<th >DISEASE NAME</th>
				<th width="15%"  style="text-align: left;">DISEASE CODE</th>
				<th width="15%" style="text-align: left;">QUANTITY</th>
			</tr>-->
			<tr>
				<th  width="5%">SN</th>
				<th style="">DISEASE NAME</th>
				<th width="20%"  style="text-align: left; ">DISEASE CODE</th>
                                <th width="15%" style="text-align: right; ">PROV QTY</th>
				<th width="15%" style="text-align: right; ">FIN QTY</th>
		   </tr>
                    <tr>
                       <td colspan="5"><hr width="100%" /></td>
                     </tr> 
            </thead>';

$temp = 1;

if ($bill_type == 'All') {

    //OUTPATIENT

    $sqloutpatient = "select d.disease_name, d.disease_ID, d.disease_code
                                    from tbl_disease_consultation dc, tbl_disease d,tbl_consultation c,tbl_patient_registration pr where
                                    d.disease_ID = dc.disease_ID and
                                    c.consultation_ID = dc.consultation_ID and
                                    c.Registration_ID = pr.Registration_ID  and
                                    diagnosis_type IN ('diagnosis','provisional_diagnosis') $filter 
                                    group by d.disease_ID order by d.disease_name";

        //echo $sqloutpatient;exit;
        $result = mysqli_query($conn,$sqloutpatient) or die(mysqli_error($conn));

        while ($row = mysqli_fetch_array($result)) {
            //echo $sqloutpatient;exit;
            $no_diagnosis = mysqli_fetch_assoc(mysqli_query($conn,"select count(dc.disease_ID) as amount
                                    from tbl_disease_consultation dc, tbl_disease d,tbl_consultation c,tbl_patient_registration pr where
                                    d.disease_ID = dc.disease_ID and
                                    c.consultation_ID = dc.consultation_ID and
                                    c.Registration_ID = pr.Registration_ID and 
                                    diagnosis_type = 'diagnosis' and
                                    d.disease_ID='" . $row['disease_ID'] . "' $filter
                                    group by d.disease_ID order by d.disease_name"))['amount'] +
                    mysqli_fetch_assoc(mysqli_query($conn,"select count(wd.disease_ID) as amount
                                    from tbl_ward_round_disease wd, tbl_disease d,tbl_ward_round wr,tbl_patient_registration pr where
                                    d.disease_ID = wd.disease_ID and
                                    wr.Round_ID = wd.Round_ID and
                                    wr.Registration_ID = pr.Registration_ID and
                                    diagnosis_type = 'diagnosis' and
                                    d.disease_ID='" . $row['disease_ID'] . "' $filterIn
                                    group by d.disease_ID order by d.disease_name"))['amount'];
            
            
            $no_provisional_diagnosis = mysqli_fetch_assoc(mysqli_query($conn,"select count(dc.disease_ID) as amount
                                    from tbl_disease_consultation dc, tbl_disease d,tbl_consultation c,tbl_patient_registration pr where
                                    d.disease_ID = dc.disease_ID and
                                    c.consultation_ID = dc.consultation_ID and
                                    c.Registration_ID = pr.Registration_ID and 
                                    diagnosis_type = 'provisional_diagnosis' and
                                    d.disease_ID='" . $row['disease_ID'] . "' $filter
                                    group by d.disease_ID order by d.disease_name"))['amount'] +
                    mysqli_fetch_assoc(mysqli_query($conn,"select count(wd.disease_ID) as amount
                                    from tbl_ward_round_disease wd, tbl_disease d,tbl_ward_round wr,tbl_patient_registration pr where
                                    d.disease_ID = wd.disease_ID and
                                    wr.Round_ID = wd.Round_ID and
                                    wr.Registration_ID = pr.Registration_ID and
                                    diagnosis_type = 'provisional_diagnosis' and
                                    d.disease_ID='" . $row['disease_ID'] . "' $filterIn
                                    group by d.disease_ID order by d.disease_name"))['amount'];

            if (empty($no_diagnosis)) {
                $no_diagnosis = 0;
            } if (empty($no_provisional_diagnosis)) {
                $no_provisional_diagnosis = 0;
            }



         $htm.="<tr><td >".$temp."</td>";
                $htm.="<td>
                    <label>".$row['disease_name']."</label>
                </td> 
                <td style='text-align: center;'>
                    <label>".$row['disease_code']."</label>
                </td> 
                <td style='text-align: right;'>
                    <label> ".number_format($no_provisional_diagnosis)."</label>
                </td>
                <td style='text-align: right;'>
                    <label>".number_format($no_diagnosis)."</label>
                </td>
                </tr> 
                <tr>
                   <td colspan='5'><hr width='100%' /></td>
                 </tr>";
                
                
        $temp++;
    }


//END OUTPATIENT
    //INPATIENT

     $sqlinpatient = "select d.disease_name, d.disease_ID, d.disease_code
                                    from tbl_ward_round_disease wd, tbl_disease d,tbl_ward_round wr,tbl_patient_registration pr where
                                    d.disease_ID = wd.disease_ID and
                                    wr.Round_ID = wd.Round_ID and
                                    wr.Registration_ID = pr.Registration_ID and
                                    d.disease_ID NOT IN (select  d.disease_ID from tbl_disease_consultation dc, tbl_disease d where
                                    d.disease_ID = dc.disease_ID and
                                    diagnosis_type IN ('diagnosis','provisional_diagnosis') $filter 
                                    group by d.disease_ID order by d.disease_name) and
                                    diagnosis_type IN ('diagnosis','provisional_diagnosis') $filterIn 
                                    group by d.disease_ID order by d.disease_name";

    //echo $sqloutpatient;exit;
    $result = mysqli_query($conn,$sqlinpatient) or die(mysqli_error($conn));

    while ($row = mysqli_fetch_array($result)) {

        $no_diagnosis = mysqli_fetch_assoc(mysqli_query($conn,"select count(dc.disease_ID) as amount
                                    from tbl_disease_consultation dc, tbl_disease d,tbl_consultation c,tbl_patient_registration pr where
                                    d.disease_ID = dc.disease_ID and
                                    c.consultation_ID = dc.consultation_ID and
                                    c.Registration_ID = pr.Registration_ID and 
                                    diagnosis_type = 'diagnosis' and
                                    d.disease_ID='" . $row['disease_ID'] . "' $filter
                                    group by d.disease_ID order by d.disease_name"))['amount'] +
                mysqli_fetch_assoc(mysqli_query($conn,"select count(wd.disease_ID) as amount
                                    from tbl_ward_round_disease wd, tbl_disease d ,tbl_ward_round wr,tbl_patient_registration pr where
                                    d.disease_ID = wd.disease_ID and
                                    wr.Round_ID = wd.Round_ID and
                                    wr.Registration_ID = pr.Registration_ID and
                                    diagnosis_type = 'diagnosis' and
                                    d.disease_ID='" . $row['disease_ID'] . "' $filterIn
                                    group by d.disease_ID order by d.disease_name"))['amount'];
        
        
        $no_provisional_diagnosis = mysqli_fetch_assoc(mysqli_query($conn,"select count(dc.disease_ID) as amount
                                    from tbl_disease_consultation dc, tbl_disease d,tbl_consultation c,tbl_patient_registration pr where
                                    d.disease_ID = dc.disease_ID and
                                    c.consultation_ID = dc.consultation_ID and
                                    c.Registration_ID = pr.Registration_ID and 
                                    diagnosis_type = 'provisional_diagnosis' and
                                    d.disease_ID='" . $row['disease_ID'] . "' $filter
                                    group by d.disease_ID order by d.disease_name"))['amount'] +
                mysqli_fetch_assoc(mysqli_query($conn,"select count(wd.disease_ID) as amount
                                    from tbl_ward_round_disease wd, tbl_disease d,tbl_ward_round wr,tbl_patient_registration pr where
                                    d.disease_ID = wd.disease_ID and
                                    wr.Round_ID = wd.Round_ID and
                                    wr.Registration_ID = pr.Registration_ID and
                                    diagnosis_type = 'provisional_diagnosis' and
                                    d.disease_ID='" . $row['disease_ID'] . "' $filterIn
                                    group by d.disease_ID order by d.disease_name"))['amount'];
        

        if (empty($no_diagnosis)) {
            $no_diagnosis = 0;
        } if (empty($no_provisional_diagnosis)) {
            $no_provisional_diagnosis = 0;
        }


         $htm.="<tr><td >".$temp."</td>";
                $htm.="<td>
                    <label>".$row['disease_name']."</label>
                </td> 
                <td style='text-align: center;'>
                    <label>".$row['disease_code']."</label>
                </td> 
                <td style='text-align: right;'>
                    <label> ".number_format($no_provisional_diagnosis)."</label>
                </td>
                <td style='text-align: right;'>
                    <label>".number_format($no_diagnosis)."</label>
                </td>
                </tr> 
                <tr>
                   <td colspan='5'><hr width='100%' /></td>
                 </tr>";
                
                
        $temp++;
    }

//END INPATIENT
} else if ($bill_type == 'Outpatient') {
    //OUTPATIENT

    $sqloutpatient = "select d.disease_name, d.disease_ID, d.disease_code
                                    from tbl_disease_consultation dc, tbl_disease d, tbl_consultation c,tbl_patient_registration pr where
                                    d.disease_ID = dc.disease_ID and
                                    c.consultation_ID = dc.consultation_ID and
                                    c.Registration_ID = pr.Registration_ID and 
                                    diagnosis_type IN ('diagnosis','provisional_diagnosis') $filter 
                                    group by d.disease_ID order by d.disease_name";

    //echo $sqloutpatient;exit;
    $result = mysqli_query($conn,$sqloutpatient) or die(mysqli_error($conn));

    while ($row = mysqli_fetch_array($result)) {

        $no_diagnosis = mysqli_fetch_assoc(mysqli_query($conn,"select count(dc.disease_ID) as amount
                                    from tbl_disease_consultation dc, tbl_disease d,tbl_consultation c,tbl_patient_registration pr where
                                    d.disease_ID = dc.disease_ID and
                                    c.consultation_ID = dc.consultation_ID and
                                    c.Registration_ID = pr.Registration_ID and 
                                    diagnosis_type = 'diagnosis' and
                                    d.disease_ID='" . $row['disease_ID'] . "' $filter
                                    group by d.disease_ID order by d.disease_name"))['amount'];
        $no_provisional_diagnosis = mysqli_fetch_assoc(mysqli_query($conn,"select count(dc.disease_ID) as amount
                                    from tbl_disease_consultation dc, tbl_disease d,tbl_consultation c,tbl_patient_registration pr where
                                    d.disease_ID = dc.disease_ID and
                                    c.consultation_ID = dc.consultation_ID and
                                    c.Registration_ID = pr.Registration_ID and 
                                    diagnosis_type = 'provisional_diagnosis' and
                                    d.disease_ID='" . $row['disease_ID'] . "' $filter
                                    group by d.disease_ID order by d.disease_name"))['amount'];

        if (empty($no_diagnosis)) {
            $no_diagnosis = 0;
        } if (empty($no_provisional_diagnosis)) {
            $no_provisional_diagnosis = 0;
        }



          $htm.="<tr><td >".$temp."</td>";
                $htm.="<td>
                    <label>".$row['disease_name']."</label>
                </td> 
                <td style='text-align: center;'>
                    <label>".$row['disease_code']."</label>
                </td> 
                <td style='text-align: right;'>
                    <label> ".number_format($no_provisional_diagnosis)."</label>
                </td>
                <td style='text-align: right;'>
                    <label>".number_format($no_diagnosis)."</label>
                </td>
                </tr> 
                <tr>
                   <td colspan='5'><hr width='100%' /></td>
                 </tr>";
                
                
        $temp++;
    }


//END OUTPATIENT
} else if ($bill_type == 'Inpatient') {
    //INPATIENT

   $sqlinpatient = "select d.disease_name, d.disease_ID, d.disease_code
                                    from tbl_ward_round_disease wd, tbl_disease d ,tbl_ward_round wr,tbl_patient_registration pr where
                                    d.disease_ID = wd.disease_ID and
                                    wr.Round_ID = wd.Round_ID and
                                    wr.Registration_ID = pr.Registration_ID and
                                    diagnosis_type IN ('diagnosis','provisional_diagnosis') $filterIn 
                                    group by d.disease_ID order by d.disease_name";

    //echo $sqloutpatient;exit;
    $result = mysqli_query($conn,$sqlinpatient) or die(mysqli_error($conn));

    while ($row = mysqli_fetch_array($result)) {

        $no_diagnosis = mysqli_fetch_assoc(mysqli_query($conn,"select count(wd.disease_ID) as amount
                                    from tbl_ward_round_disease wd, tbl_disease d,tbl_ward_round wr,tbl_patient_registration pr where
                                    d.disease_ID = wd.disease_ID and
                                    wr.Round_ID = wd.Round_ID and
                                    wr.Registration_ID = pr.Registration_ID and
                                    diagnosis_type = 'diagnosis' and
                                    d.disease_ID='" . $row['disease_ID'] . "' $filterIn
                                    group by d.disease_ID order by d.disease_name"))['amount'];
        $no_provisional_diagnosis = mysqli_fetch_assoc(mysqli_query($conn,"select count(wd.disease_ID) as amount
                                    from tbl_ward_round_disease wd, tbl_disease d,tbl_ward_round wr,tbl_patient_registration pr where
                                    d.disease_ID = wd.disease_ID and
                                    wr.Round_ID = wd.Round_ID and
                                    wr.Registration_ID = pr.Registration_ID and
                                    diagnosis_type = 'provisional_diagnosis' and
                                    d.disease_ID='" . $row['disease_ID'] . "' $filterIn
                                    group by d.disease_ID order by d.disease_name"))['amount'];

        if (empty($no_diagnosis)) {
            $no_diagnosis = 0;
        } if (empty($no_provisional_diagnosis)) {
            $no_provisional_diagnosis = 0;
        }
        
  $htm.="<tr><td >".$temp."</td>";
                $htm.="<td>
                    <label>".$row['disease_name']."</label>
                </td> 
                <td style='text-align: center;'>
                    <label>".$row['disease_code']."</label>
                </td> 
                <td style='text-align: right;'>
                    <label> ".number_format($no_provisional_diagnosis)."</label>
                </td>
                <td style='text-align: right;'>
                    <label>".number_format($no_diagnosis)."</label>
                </td>
                </tr> 
                <tr>
                   <td colspan='5'><hr width='100%' /></td>
                 </tr>";
                
                
        $temp++;
    }

//END INPATIENT
}
$htm.="</table>";


include("MPDF/mpdf.php");
$mpdf = new mPDF();
$mpdf->WriteHTML($htm);
$mpdf->Output();
exit;
