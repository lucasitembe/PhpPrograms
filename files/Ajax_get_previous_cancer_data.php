<?php 
    require_once('includes/connection.php');
    if(isset($_POST['registration_data'])){
        $Registration_ID = $_POST['Registration_ID'];
        $select_date = mysqli_query($conn, "SELECT cancer_id, date_and_time, Employee_Name FROM tbl_cancer_registration cr, tbl_employee e WHERE Registration_ID='$Registration_ID' AND cr.Saved_by=e.Employee_ID" ) or die(mysqli_error($conn));
        $num=0;
        echo "<div class='box box-primary'>
                <div class='box-body' >
                    <table class='table'>
                        <thead>
                            <th>SN</th>
                            <th> SAVED AT</th>
                            <th>SAVED BY</th>
                            <th>ACTION</th>
                        </thead>";

        if(mysqli_num_rows($select_date)>0){
            while($rw = mysqli_fetch_assoc($select_date)){
                $cancer_id = $rw['cancer_id'];
                $date_and_time = $rw['date_and_time']; 
                $Employee_Name = $rw['Employee_Name'];
                $num++;
                echo "<tr><td>$num</td><td>$date_and_time</td><td>$Employee_Name</td><td><input type='button' class='art-button-green' onclick='preview_patient_cancer_registration_form($cancer_id, $Registration_ID)' value='PREVIEW DATA'></td></tr>";
            }
        }else{
            echo "<tr><td colspan='3' style='color:red; text-align:center;'>No any registration done for this patient</td></tr>";
        }
        echo "</table></div></div>"; 
    }


    if(isset($_POST['view_registration_data'])){
        $cancer_id = $_POST['cancer_id'];
        $Registration_ID= $_POST['Registration_ID'];
            include('cancer_registration_form_inclusion.php');
      
       ?>
<?php 
    }
    if(isset($_POST['view_assigned_protocal'])){
        $Registration_ID = $_POST['Registration_ID'];
        $disease_ID=0;
        $disease_name="";
        $num_count=0;
        $cancer_ID=0;
       // $consultatio_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT consultation_ID FROM tbl_consultation WHERE Registration_ID='$Registration_ID' ORDER BY consultation_ID DESC"))
    
        $select_patient_procal=mysqli_query($conn,"SELECT Cancer_Name,date_and_time,cpd.Protocal_status,Remarks, Employee_Name, cpd.cancer_type_id,Patient_protocal_details_ID FROM tbl_cancer_patient_details cpd, tbl_cancer_type ct, tbl_employee e WHERE ct.cancer_type_id=cpd.cancer_type_id AND  Registration_ID='$Registration_ID' AND cpd.Employee_ID=e.Employee_ID ORDER BY Patient_protocal_details_ID DESC") or die(mysqli_error($conn));
        if((mysqli_num_rows($select_patient_procal))>0){
        while($row=mysqli_fetch_assoc($select_patient_procal)){
                $cancer_ID=$row['cancer_type_id'];
                $disease_name=$row['Cancer_Name'];
                $Patient_protocal_details_ID = $row['Patient_protocal_details_ID'];
                $assigned_by = $row['Employee_Name'];
                $Protocal_status = $row['Protocal_status'];
                $date_and_time = $row['date_and_time'];
                $Remarks = $row['Remarks'];
                $num_count++;
                
                
                    echo "<tr class='rows_list' style='background:#bdb5ac;'>
                        <td width='2%;'> $num_count</td>
                        <td style='text-align:center;'> $disease_name </td>
                        <td>$assigned_by</td>
                        <td>$date_and_time</td>
                        <td>$Protocal_status</td>
                        <td>$Remarks</td>
                        <td>
                            <span >
                                <button  class='btn btn-primary btn-xs' style='width:100px;'  type='button' onclick='protocal_type_details(\"$Patient_protocal_details_ID\",\"$disease_name\", \"$Registration_ID\")' >PREVIEW</button>";
                                
                                if($Protocal_status == 'Completed' || $Protocal_status == 'Cancelled' || $Protocal_status=='Pending'){
                                    echo "<button  class='btn btn-primary btn-xs' readonly='readonly' style='width:100px;'  type='button' onclick='administer_protocals(\"$Protocal_status\",\"$disease_name\", \"$Registration_ID\")' >PRESCRIBE</button>";
                                }else if($Protocal_status == 'Onprogress'){
                                    echo "<button  class='btn btn-primary btn-xs'  style='width:100px;'  type='button' onclick='administer_protocal(\"$Patient_protocal_details_ID\",\"$disease_name\", \"$Registration_ID\")' >PRESCRIBE</button>";
                                }
                               echo " </span>
                        </td>
                         </tr>
                         <tr>
                            <td colspan='7'>
                            <div style='height:60vh; overflow:scroll;'>
                                <table class='table' >
                                    <thead style='background:#e6eded;'>                                        
                                        <tr>
                                            <th>Sn</th>
                                            <th width='10%'>Cycle Date</th>
                                            <th>Cycles</th>                                            
                                            <th>Day No.</th>
                                            <th>Prescribed  by</th>    
                                                                                                                    
                                        </tr>
                                    </thead>
                                    <body>
                                     ";
                                    $num =0;
                                    $datenumer="";
                                    $cycleno =0;
                                    $firstcol="";
                                    $seccol="";
                                        $select_drug_in_use = mysqli_query($conn, "SELECT   cyclenumber,Cycle_ID, administer_comment,Employee_Name,saved_at FROM tbl_patient_protocal_cycles pc, tbl_employee e WHERE Patient_protocal_details_ID='$Patient_protocal_details_ID' AND pc.administed_by = e.Employee_ID ORDER BY Cycle_ID DESC") or die(mysqli_error($conn));
                                        if(mysqli_num_rows($select_drug_in_use)>0){
                                            while($rw = mysqli_fetch_assoc($select_drug_in_use)){
                                                $cyclenumber = $rw['cyclenumber'];
                                                $Cycle_ID= $rw['Cycle_ID'];
                                                $administer_comment = $rw['administer_comment'];
                                                $selected_chemo_drug = $rw['selected_chemo_drug'];
                                                $Employee_Name = $rw['Employee_Name'];
                                                $saved_at = $rw['saved_at'];
                                                $cycle=$rw['cycle'];
                                                $num++;
                                          
                                          
                                            echo "
                                            
                                            <tr>
                                                <td>$num</td>
                                                <td rowspan=''>$saved_at</td>
                                                <td rowspan=''>$cyclenumber</td>                     
                                                <td>$administer_comment</td>
                                                <td>$Employee_Name </td>
                                            </tr>";

                                            echo " 
                                            <tr>
                                                <td colspan='5'>
                                                    <table class='table'>
                                                        <tr><th colspan='10'> Cycle #. <b> $cyclenumber </b> SUPPORTIVE TREATMENT </th></tr>
                                                        <tr>
                                                            <th colspan=''> Drug</th>
                                                            <th> Dose </th>
                                                            <th> Route </th>
                                                            <th> Amount Given </th>
                                                            <th width='5%'> saved time</th>
                                                            <th width='5%'> Time Given</th>
                                                            <th>Nurse/Significant Events and Interventions </th>
                                                            <th width='5%'> Discontinued?</th>
                                                            <th > From Outside Amount</th>
                                                            <th> Given by </th> 
                                                        </tr>";
                                                        
                                                            
                                                            $select_treatment_drug_in_use = mysqli_query($conn, "SELECT  supportive_treatment, patient_supportive_ID FROM tbl_patient_supportive_treatment pc WHERE Patient_protocal_details_ID='$Patient_protocal_details_ID' AND Cycle_ID='$Cycle_ID'") or die(mysqli_error($conn));
                                                            if(mysqli_num_rows($select_treatment_drug_in_use)>0){
                                                                while($rw = mysqli_fetch_assoc($select_treatment_drug_in_use)){
                                                                    $select_supportive_treatment =$rw['supportive_treatment'];
    
                                                                    // $select_supportive_treatment = mysqli_fetch_assoc(mysqli_query($conn, "SELECT supportive_treatment FROM tbl_patient_supportive_treatment WHERE patient_supportive_ID='$selected_treatment'"))['supportive_treatment'];
                                                                    echo "
                                                                    <tr>
                                                                        <td> $select_supportive_treatment </td>";

                                                                        $selected_services = mysqli_query($conn, "	SELECT medication_time,given_time,route_type,drip_rate,Product_Name,Time_Given,Amount_Given,Nurse_Remarks,From_outside_amount,Discontinue_Status,Discontinue_Reason,Employee_Name
                                                                        FROM tbl_inpatient_medicines_given sg,	tbl_items it,	tbl_employee em WHERE Discontinue_Status='no' AND em.Employee_ID = sg.Employee_ID AND it.Item_ID = sg.Item_ID AND sg.Registration_ID='$Registration_ID' AND Product_Name='$select_supportive_treatment' ");
                                                                        if(mysqli_num_rows($selected_services)>0){ 
                                                                            while ($service = mysqli_fetch_assoc($selected_services)) {
                                                                                $given_time = $service['given_time'];
                                                                                $route_type = $service['route_type'];
                                                                                $Time_Given = $service['Time_Given'];
                                                                                $medication_time = $service['medication_time'];                                                
                                                                                $Amount_Given = $service['Amount_Given'];
                                                                                $Nurse_Remarks = $service['Nurse_Remarks'];
                                                                                $Discontinue_Status = $service['Discontinue_Status'];
                                                                                $Discontinue_Reason = $service['Discontinue_Reason'];
                                                                                $From_outside_amount = $service['From_outside_amount'];
                                                                                $Given_by = $service['Employee_Name'];
                                                                                //    // echo "<table class='table'><tr>";
                                                                                    echo "<td>" . $given_time. "</td>";
                                                                                    echo "<td>" . $route_type . "</td>";
                                                                                    echo "<td>" . $Amount_Given . "</td>";
                                                                                    echo "<td>" . $Time_Given . "</td>";
                                                                                    echo "<td>" . $medication_time . "</td>";
                                                                                    echo "<td>" . $Nurse_Remarks . "</td>";
                                                                                    echo "<td>" . $Discontinue_Status . "</td>";
                                                                                    echo "<td>" . $From_outside_amount ."</td>";
                                                                                    echo "<td>" . $Given_by . "</td>";
                                                                                //    //  echo"</tr></table>";
                                                                            }
                                                                        }else{
                                                                                echo "<td colspan='9' style='text-align:center'>The drug not administered yet.</td>";
                                                                            }
                                                                        echo"
                                                                    </tr>";
                                                                }
                                                            }
                                                        echo"
                                                    </table>
                                                <td>
                                            </tr>";
    
                                            echo"
                                            <tr>
                                                <td colspan='5'>
                                                    <table class='table'>
                                                        <tr><th colspan='10'> Cycle #. <b> $cyclenumber </b> CHEMO DRUG </th></tr>
                                                        <tr>
                                                            <th colspan=''> Drug</th>
                                                            <th> Dose </th>
                                                            <th> Route </th>
                                                            <th> Amount Given </th>
                                                            <th width='5%'> saved time</th>
                                                            <th width='5%'> Time Given</th>
                                                            <th>Nurse/Significant Events and Interventions </th>
                                                            <th width='5%'> Discontinued?</th>
                                                            <th > From Outside Amount</th>
                                                            <th> Given by </th> 
                                                        </tr>";
                                                         
                                                            $select_drug_in_use = mysqli_query($conn, "SELECT  Chemotherapy_Drug FROM tbl_patient_chemotherapy_drug pc WHERE Patient_protocal_details_ID='$Patient_protocal_details_ID' AND Cycle_ID='$Cycle_ID'") or die(mysqli_error($conn));
                                                            if(mysqli_num_rows($select_drug_in_use)>0){
                                                                while($rw = mysqli_fetch_assoc($select_drug_in_use)){
                                                                    $select_chemo_drug = $rw['Chemotherapy_Drug'];

                                                                // $select_chemo_drug = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Chemotherapy_Drug FROM tbl_patient_chemotherapy_drug WHERE patient_chemotherapy_ID='$selected_chemo_drug'"))['Chemotherapy_Drug'];
                                                                echo "
                                                                <tr> 
                                                                    <td>";
                                                                        echo $select_chemo_drug;    
                                                                        echo"
                                                                    </td>";
                                                                    $selected_services = mysqli_query($conn, "SELECT medication_time,given_time,route_type,drip_rate,Product_Name,Time_Given,Amount_Given,Nurse_Remarks,From_outside_amount,Discontinue_Status,Discontinue_Reason,Employee_Name
                                                                    FROM tbl_inpatient_medicines_given sg,	tbl_items it,	tbl_employee em WHERE Discontinue_Status='no' AND em.Employee_ID = sg.Employee_ID AND it.Item_ID = sg.Item_ID AND sg.Registration_ID='$Registration_ID' AND Product_Name='$select_chemo_drug'") or die(mysqli_error($conn));
                                                                
                                                                    if(mysqli_num_rows($selected_services)>0){ 
                                                                        while ($service = mysqli_fetch_assoc($selected_services)) {
                                                                            $Product_Name = $service['Product_Name'];
                                                                            $given_time = $service['given_time'];
                                                                            $route_type = $service['route_type'];
                                                                            $Time_Given = $service['Time_Given'];
                                                                            $medication_time = $service['medication_time'];                                                
                                                                            $Amount_Given = $service['Amount_Given'];
                                                                            $Nurse_Remarks = $service['Nurse_Remarks'];
                                                                            $Discontinue_Status = $service['Discontinue_Status'];
                                                                            $Discontinue_Reason = $service['Discontinue_Reason'];
                                                                            $From_outside_amount = $service['From_outside_amount'];
                                                                            $Given_by = $service['Employee_Name'];
                                                                        //    // echo "<table class='table'><tr>";
                                                                            echo "<td>" . $given_time. "</td>";
                                                                            echo "<td>" . $route_type . "</td>";
                                                                            echo "<td>" . $Amount_Given . "</td>";
                                                                            echo "<td>" . $Time_Given . "</td>";
                                                                            echo "<td>" . $medication_time . "</td>";
                                                                            echo "<td>" . $Nurse_Remarks . "</td>";
                                                                            echo "<td>" . $Discontinue_Status . "</td>";
                                                                            echo "<td>" . $From_outside_amount ."</td>";
                                                                            echo "<td>" . $Given_by . "</td>";
                                                                        //    //  echo"</tr></table>";
                                                                            
                                                                        }
                                                                    }else{
                                                                        echo "<td colspan='9' style='text-align:center'>The drug not administered yet.</td>";
                                                                    }
                                                                    echo "</tr>";
                                                                }
                                                            }
                                                    echo "</table>
                                                </td>
                                            <tr>";

                                               
                                               
                                        }       
                                    }else{
                                        echo "<tr><td colspan='5'>No result found</td></tr>";
                                    }
                            echo "
                        </body>
                    </table></div>
                </td>
            </tr>";
         
            }
        }else{
            echo "<tr>
                        <td colspan='7' style='color:red; text-align:center;'>No any Protocal assigned  to this patient yet!! </td>
                    </tr>";
        }
    }
?>