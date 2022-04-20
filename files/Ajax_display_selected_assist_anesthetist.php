
<?php
    include("./includes/connection.php");
        session_start(); 
        if(isset($_POST['Registration_ID'])){
            $Registration_ID= $_POST['Registration_ID'];
        }else{
        $Registration_ID="";  
        }  
        
        $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress' ORDER BY anasthesia_record_id DESC LIMIT 1";
     $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
     if(mysqli_num_rows($anasthesia_record_result)>0){
         $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
     }

    $sql_search_assist_anesthetist_name_result=mysqli_query($conn,"SELECT assist_anasthetist_id, td.Employee_ID, Employee_Name FROM tbl_employee td,tbl_anasthesia_assist_anasthetist ad WHERE td.Employee_ID=ad.Assist_anesthetist_ID AND Registration_ID='$Registration_ID'  AND anasthesia_record_id='$anasthesia_record_id' ") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_search_assist_anesthetist_name_result)>0){
        $count_sn=1;
        while($assistant_row=mysqli_fetch_assoc($sql_search_assist_anesthetist_name_result)){
            $assist_anasthetist_id=$assistant_row['assist_anasthetist_id'];
            $Employee_Name=$assistant_row['Employee_Name'];
            echo "<tr class='rows_list'>
                    <td>$count_sn</td>
                    <td>$Employee_Name</td>
                    <td>
                        <input type='button' value='x' class='btn btn-danger' onclick='remove_anasthesia_assist_anesthetist($assist_anasthetist_id)'>
                    </td>
                </tr>";
                $count_sn++;
        }
    }
?>