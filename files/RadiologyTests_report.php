<?php
$select_patient_tests = "
		SELECT *
			FROM 
			tbl_radiology_discription rd,
			tbl_radiology_parameter rp
				WHERE
				rp.Parameter_ID = rd.Parameter_ID AND
				rd.Item_ID = '$Item_ID' AND
				rd.Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID' AND
				rd.Registration_ID = '$Registration_ID'	
                ORDER BY rp.Parameter_ID asc";
    
   

$select_patient_tests_results = mysqli_query($conn,$select_patient_tests) or die(mysqli_error($conn));
?>
<ol>
    <?php
    while ($ptests = mysqli_fetch_assoc($select_patient_tests_results)) {
        $parameter = $ptests['Parameter_Name'];
        $comment = $ptests['comments'];
    ?>


        <li>
            <b style="font-size:17px"><?= $parameter ?></b>
            <p><?= $comment ?></p>
        </li>


        <?php
    }
    ?>
</ol>