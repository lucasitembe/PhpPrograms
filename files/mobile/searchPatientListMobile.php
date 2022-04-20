<?php
	include("../includes/connection.php");

	if(isset($_GET['Patient_Name'])){
		$Patient_Name = $_GET['Patient_Name'];
	}else{
		$Patient_Name = '';
	}

	if(isset($_GET['payment_code'])){
		$payment_code = $_GET['payment_code'];
	}else{
		$payment_code = '';
	}

    $patientQr = "SELECT * FROM tbl_patient_registration pr, tbl_mobile_payment mp 
                  WHERE pr.Registration_ID = mp.Registration_ID 
                  AND mp.payment_status <> 'seen' AND Patient_Name LIKE '%$Patient_Name%' 
                  AND payment_code LIKE '%$payment_code%' GROUP BY payment_code";
    $data = mysqli_query($conn,$patientQr);
    while($row = mysqli_fetch_array($data)){
    ?><tr><td width="10%"><?php echo $row['payment_code'];?></td>
          <td><?php echo $row['Patient_Name']; ?></td>
          <td><?php echo $row['payment_status']; ?></td>
          <td>
              <?php
                if($row['payment_status'] == 'sent' ){
                    ?>
                        <center><input type="button" value="check online status" onclick="updatePaymentStatus('<?php echo $row['payment_code'];?>')" /><input type="button" value="CancelOnline" onclick="CancelOnline('<?php echo $row['payment_code']; ?>')"></center>
                    <?php
                }elseif($row['payment_status'] == 'pending'){
                    ?>
                        <center><input type="button" value="cancel" onclick="cancelPayment('<?php echo $row['payment_code'];?>')" /><input type="button" value="send" onclick="sendToCloud('<?php echo $row['payment_code'];?>')" /></center>
                    <?php
                }elseif($row['payment_status'] == 'paid'){
                    ?>
                        <center><input type="button" value="seen" onclick="seen('<?php echo $row['payment_code'];?>')" /></center>
                    <?php
                }
              ?>
          </td>
          <td><input type="radio" name='choose' onclick="getPatientAndMobileItems('<?php echo $row['payment_code']; ?>','<?php echo $row['Patient_Name']; ?>')"></td>
      </tr><?php
    }
?>