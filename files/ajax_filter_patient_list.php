<?php
    include("./includes/connection.php");
    $patient_name=$_POST['patient_name'];
    $Hospital_Ward_ID=$_POST['Hospital_Ward_ID'];
   
    $SelectRadiItems = "
						SELECT *
							FROM 
							tbl_admission ad,
							tbl_patient_registration pr,
							tbl_hospital_ward hw,
                                                        tbl_ward_rooms wr
								WHERE
                                                                ad.ward_room_id=wr.ward_room_id AND
								ad.Registration_ID = pr.Registration_ID AND
								ad.Admission_Status = 'Admitted' AND 
								ad.Hospital_Ward_ID = hw.Hospital_Ward_ID 
                                                                AND  ward_type='ordinary_ward'
					";	

					//if($WardSelected != ''){
						$SelectRadiItems .= " AND ad.Hospital_Ward_ID = '$Hospital_Ward_ID' AND Patient_Name like '%$patient_name%' limit 50";

    $select = mysqli_query($conn,$SelectRadiItems) or die(mysqli_error($conn));
    $no = mysqli_num_rows($select);
    echo '<table class="table">';
    if($no > 0){
        while ($row = mysqli_fetch_array($select)) {
            $Registration_ID=$row['Registration_ID'];
            $Admision_ID=$row['Admision_ID'];
            $patient_name=ucwords(strtolower($row['Patient_Name']));
            echo "<tr>
                    <td>
                        <label style='font-weight:normal'>
                            <input type='radio' class='Admision_ID' name='Admision_ID' value='$Admision_ID'> $patient_name
                        </label>
                    </td>
                    
            </tr>";
        }
    }else{
//        echo "<tr>
//                    <td>
//                        <label style='color:red;'>
//                            SORRY,NO PATIENT FOUND!
//                        </label>
//                    </td>  
//                </tr>";
    }
    echo '</table>';