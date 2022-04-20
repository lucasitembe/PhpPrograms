<style>
    .table{
        border: 1px solid #ccc !important;
    }

    table tr td{
        border: 1px solid #cccd !important;
    }
</style>
<?php 
    include './includes/connection.php';
    include 'pharmacy-repo/interface.php';
    $Interface = new PharmacyInterface();
    
    $Registration_ID = $_GET['Registration_ID'];
    $payment_cache_array = array();
    $output = "";

    $getPatientMedication = mysqli_query($conn,"SELECT tpc.Payment_Cache_ID,ti.Product_Name,tpc.consultation_id,tilc.Status,c.Consultation_Date_And_Time,Employee_Name,tilc.Doctor_Comment,maincomplain,systemic_observation,disease_name,general_observation
    FROM tbl_payment_cache AS tpc,tbl_item_list_cache AS tilc, tbl_items AS ti, tbl_consultation AS c,tbl_employee AS e,tbl_disease_consultation AS tdc,tbl_disease AS td
    WHERE tpc.Registration_ID = $Registration_ID AND tpc.Payment_Cache_ID = tilc.Payment_Cache_ID AND td.disease_ID = tdc.disease_ID AND c.employee_ID = e.Employee_ID AND tdc.consultation_ID = c.consultation_ID AND tpc.consultation_id = c.consultation_ID AND ti.Item_ID = tilc.Item_ID GROUP BY tpc.consultation_ID ORDER BY tpc.Payment_Cache_ID DESC LIMIT 5");


    while($data = mysqli_fetch_assoc($getPatientMedication)){
        array_push($payment_cache_array,$data);
    }

    for($i=0;$i < sizeof($payment_cache_array);$i++){

        $get_current_consultation_id = mysqli_fetch_assoc(mysqli_query($conn,"SELECT consultation_id FROM tbl_payment_cache WHERE Payment_Cache_ID = ".$payment_cache_array[$i]['Payment_Cache_ID']." LIMIT 1"))['consultation_id'];
        $diagnosis_qr = mysqli_query($conn,"SELECT diagnosis_type,disease_name,Disease_Consultation_Date_And_Time,disease_code FROM tbl_disease_consultation dc,tbl_disease d
        WHERE dc.consultation_ID = ".$payment_cache_array[$i]['consultation_id']." AND dc.disease_ID = d.disease_ID");

        $final_diagnosis = "";
        $provisional_diagnosis = "";
        $diff_diagnosis = "";
        while($rowsx = mysqli_fetch_assoc($diagnosis_qr)){
            if($rowsx['diagnosis_type'] == 'provisional_diagnosis'){
                $provisional_diagnosis.= $rowsx['disease_name']."<b>(".$rowsx['disease_code'].")</b>"."; ";
            }else if($rowsx['diagnosis_type'] == 'diagnosis'){
                $final_diagnosis.= $rowsx['disease_name']."<b>(".$rowsx['disease_code'].")</b>"."; ";
            }else{
                $diff_diagnosis.= $rowsx['disease_name']."(".$rowsx['disease_code'].")"."; ";
            }
        }

        $output .="<table class='table'>";
        $output .="<tr style='background-color: rgba(34, 138, 170, 1);color:white'><td colspan=5'><b>Consultant : </b>".$payment_cache_array[$i]['Employee_Name']." <span style='margin-left:1em;margin-right:1em'>~</span> <b>Consultation Date And Time : </b>".$payment_cache_array[$i]['Consultation_Date_And_Time']."</td></tr>";
        
        $output .=" <tr style='background-color: #fff;'>
                        <td width='20%'><b>Main Complain</b></td>
                        <td width='80%'>".$payment_cache_array[$i]['maincomplain']."</td>
                </tr>";

        $output .=" <tr style='background-color: #fff;'>
                    <td width='20%'><b>Systemic Observation</b></td>
                    <td width='80%'>".($payment_cache_array[$i]['systemic_observation'] == "" ? "Empty not provided" : $payment_cache_array[$i]['systemic_observation'] )."</td>
                </tr>";

        $output .=" <tr style='background-color: #fff;'>
                <td width='20%'><b>Provisional Diagnosis</b></td>
                <td width='80%'>".$provisional_diagnosis."</td>
            </tr>";

        $output .=" <tr style='background-color: #fff;'>
                    <td width='20%'><b>Final Diagnosis</b></td>
                    <td width='80%'>".$final_diagnosis."</td>
                </tr>";

        $output .=" <tr style='background-color: #fff;'>
                    <td width='20%'><b>Medication</b></td>
                <td>";

        $select_items = mysqli_query($conn,"SELECT tilc.Payment_Cache_ID,Product_Name,Quantity,edited_quantity FROM tbl_item_list_cache AS tilc,tbl_items AS ti WHERE tilc.Check_In_Type = 'Pharmacy' AND tilc.Item_ID = ti.Item_ID AND tilc.Payment_Cache_ID = ".$payment_cache_array[$i]['Payment_Cache_ID']."") or die(mysqli_errno($conn));
                        while($row = mysqli_fetch_assoc($select_items)){
                            $output .= "<span style='margin-right:0.5em'>".$row['Product_Name']."</span>; ";
                        }
        $output .="</td></tr>"; 
        $output .="</table></br>";
    }

    echo $output;
?>