<?php
include("./includes/header.php");
include("./includes/connection.php");
include("./includes/constants.php");
//include("medical_record_encrypt_decrypt.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if(isset($_GET['Registration_ID'])){
   $Registration_ID=$_GET['Registration_ID'];
}else{
   $Registration_ID=""; 
}

         $select_patien_details = mysqli_query($conn,"
		SELECT pr.Sponsor_ID,Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
			FROM
				tbl_patient_registration pr,
				tbl_sponsor sp
			WHERE
				pr.Registration_ID = '" . $Registration_ID . "' AND
				sp.Sponsor_ID = pr.Sponsor_ID
				") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_patien_details);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_patien_details)) {
            $Member_Number = $row['Member_Number'];
            $Patient_Name = $row['Patient_Name'];
            $Registration_ID = $row['Registration_ID'];
            $Gender = $row['Gender'];
            $Guarantor_Name  = $row['Guarantor_Name'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $DOB = $row['Date_Of_Birth'];
        }
    } else {
        $Guarantor_Name  = '';
        $Member_Number = '';
        $Patient_Name = '';
        $Gender = '';
        $Registration_ID = 0;
    }
    $age = date_diff(date_create($DOB), date_create('today'))->y;
    $sql_select_card_no_result=mysqli_query($conn,"SELECT card_no FROM tbl_member_afya_card WHERE Registration_ID='$Registration_ID' LIMIT 1") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_card_no_result)>0){
        $card_no=mysqli_fetch_assoc($sql_select_card_no_result)['card_no'];
    }
        ?>
<a href="all_patient_file_link_station.php?Registration_ID=<?= $Registration_ID ?>" class="art-button-green">BACK</a>
<style>
    .link_text:hover{
        text-decoration: none;
        
    }
    a:hover {
  text-decoration: none!important;
  color: #2996cc;
  font-weight: bold;
}
</style>
<fieldset style="height:450px;overflow-y: scroll">
    <legend align="center" style="text-align:center"><b>AFYA CARD MEDICAL RECORD DATA</b>
        <br/>
               <span style='color:yellow'><?php echo "<b>" . $Patient_Name . "</b>  | " . $Gender . "</b> | <b>" . $age . " years</b> | <b>" . $Guarantor_Name  . "</b>"; ?></b></span>
    </legend>
    <table class="table table-bordered" style='background: #FFFFFF;'>
        <tr>
            <th width="50px">S/No.</th>
            <th>HOSPITAL ID</th>
            <th>HOSPITAL NAME</th>
            <th>CONSULTATION DATE</th>
        </tr>
        <tbody>
            <?php 
                //check for online data
function is_connected()
{
  $connected = fopen(ehms_mr_local_url.":80/","r");
  if($connected)
  {
     return true;
  } else {
   return false;
  }

}
if(is_connected()){
    // connection_available
    $received_data_from_online_server= json_decode(retrived_data_from_online_server($card_no));
    $count_sn=1;
    foreach($received_data_from_online_server as $medical_rec_data){
        $consultation_record_id=$medical_rec_data[0];
        $hospital_id=$medical_rec_data[1];
        $complain=$medical_rec_data[2];
        $consultation_date=$medical_rec_data[3];
        $final_diagnosis=$medical_rec_data[4];

        $Hospital_name="";
        $sql_select_hospital_name_result=mysqli_query($conn,"SELECT Hospital_name FROM tbl_ehms_mr_all_hospital_list_local WHERE ehms_mr_all_hospital_list_online_id='$hospital_id'") or die(mysqli_error($conn));
        if(mysqli_num_rows($sql_select_hospital_name_result)>0){
            $Hospital_name=mysqli_fetch_assoc($sql_select_hospital_name_result)['Hospital_name'];
        }
        echo "<tr>
                <td style='text-align: center'><a href='afya_card_medica_detail_with_ordered_item.php?consultation_record_id=$consultation_record_id&Registration_ID=$Registration_ID&data_from_online_server=yes' class='link_text'>$count_sn.</a></td>
                <td style='text-align: center'><a href='afya_card_medica_detail_with_ordered_item.php?consultation_record_id=$consultation_record_id&Registration_ID=$Registration_ID&data_from_online_server=yes' class='link_text'>$hospital_id</a></td>
                <td style='text-align: center'><a href='afya_card_medica_detail_with_ordered_item.php?consultation_record_id=$consultation_record_id&Registration_ID=$Registration_ID&data_from_online_server=yes' class='link_text'>$Hospital_name</a></td>
                <td style='text-align: center'><a href='afya_card_medica_detail_with_ordered_item.php?consultation_record_id=$consultation_record_id&Registration_ID=$Registration_ID&data_from_online_server=yes' class='link_text'>$consultation_date</a></td>
            </tr>";
        $count_sn++;
    }
}else{
   //connection fail/not available
                $count_sn=1;
                $sql_select_consultation_history_result=mysqli_query($conn,"SELECT hospital_id,consultation_record_id,consultation_date FROM tbl_card_consultation_record WHERE card_no='$card_no'") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_select_consultation_history_result)>0){
                    while($cons_rows=mysqli_fetch_assoc($sql_select_consultation_history_result)){
                        $hospital_id=$cons_rows['hospital_id'];
                        $consultation_record_id=$cons_rows['consultation_record_id'];
                        $consultation_date=$cons_rows['consultation_date'];
                        //select hospital name
                        $Hospital_name="";
                        $sql_select_hospital_name_result=mysqli_query($conn,"SELECT Hospital_name FROM tbl_ehms_mr_all_hospital_list_local WHERE ehms_mr_all_hospital_list_online_id='$hospital_id'") or die(mysqli_error($conn));
                        if(mysqli_num_rows($sql_select_hospital_name_result)>0){
                            $Hospital_name=mysqli_fetch_assoc($sql_select_hospital_name_result)['Hospital_name'];
                        }
                        echo "<tr>
                                <td style='text-align: center'><a href='afya_card_medica_detail_with_ordered_item.php?consultation_record_id=$consultation_record_id&Registration_ID=$Registration_ID' class='link_text'>$count_sn.</a></td>
                                <td style='text-align: center'><a href='afya_card_medica_detail_with_ordered_item.php?consultation_record_id=$consultation_record_id&Registration_ID=$Registration_ID' class='link_text'>$hospital_id</a></td>
                                <td style='text-align: center'><a href='afya_card_medica_detail_with_ordered_item.php?consultation_record_id=$consultation_record_id&Registration_ID=$Registration_ID' class='link_text'>$Hospital_name</a></td>
                                <td style='text-align: center'><a href='afya_card_medica_detail_with_ordered_item.php?consultation_record_id=$consultation_record_id&Registration_ID=$Registration_ID' class='link_text'>$consultation_date</a></td>
                            </tr>";
                        $count_sn++;
                    }
                }
}
            ?>
        </tbody>
    </table>
</fieldset>
<fieldset>
    <center><input type="button" value="Next Page >>" disabled="disabled" class="btn btn-default"/></center>
</fieldset>
<?php
    function retrived_data_from_online_server($card_no){
        /*****gkcchief april-17-2019******/
             $post_data_array=array(
                "username"=>"gpitg",
                "password"=>"gpitgmronline",
                "card_no"=>$card_no,
            );
           $post_data= json_encode($post_data_array);
            $post_data;
            $header = [
                'Content-Type: application/json',
                'Accept: application/json'
            ];
            $url = ehms_mr_local_url.'/ehms_mr_local/get_online_mr_data_local.php';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url );
            curl_setopt($ch, CURLOPT_POST, true );
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data );
            curl_setopt($ch, CURLOPT_POSTREDIR, 3);
            $result = curl_exec($ch);
            curl_close($ch); 
            return $result;
    }
    include("./includes/footer.php");
?>