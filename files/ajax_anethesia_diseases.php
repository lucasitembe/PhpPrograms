<?php 
include("./includes/connection.php");
session_start();
if(isset($_POST['open_dialog'])){?>
<div class="col-md-6">
    <input type='text' placeholder="~~~~~Search Disease Name~~~~~" onkeyup='search_disease()' id="disease_name" style='text-align:center'>
</div>
<div class="col-md-6">
    <input type='text' placeholder="~~~~~Search Disease Code ~~~~~" onkeyup='search_disease()' id="disease_code" style='text-align:center'>
</div>
<div class="col-md-6" style='height:400px;overflow-y:scroll' id="background_disease">
    <table class='table table-bordered' style='background:#FFFFFF'>
        <caption><b>LIST OF ALL DISEASE</b></caption>
        <tr>
            <th>S/No.</th>
            <th>DISEASE NAME</th>
            <th>DISEASE CODE</th>
        </tr>
        <tbody id='list_of_all_disease'>
 
        </tbody>
    </table>
</div>
<div class="col-md-6" style='height:400px;overflow-y:scroll'>
    <table class='table' style='background:#FFFFFF' >
        <caption><b>LIST OF SELECTED DISEASE</b></caption>
        <tr>
            <th>S/No.</th>
            <th>DISEASE NAME</th>
            <th>DISEASE CODE</th>
        </tr>
        <tbody id='list_of_selected_disease'>

        </tbody>
    </table>
</div>
<div class="col-md-12" id="send_data">
    <input type="button" id="send_data" Value="DONE" class="art-button-green pull-right" onclick="view_desease_selected()">
</div>
<?php
}

if(isset($_POST['search_diseases'])){ 
    $disease_code=mysqli_real_escape_string($conn,$_POST['disease_code']);
    $disease_name=mysqli_real_escape_string($conn,$_POST['disease_name']);

    $sql_search_disease_code_result=mysqli_query($conn,"SELECT disease_ID,disease_code,disease_name FROM tbl_disease WHERE disease_code LIKE '%$disease_code%' AND disease_name LIKE '%$disease_name%' AND disease_version='icd_10' LIMIT 50") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_search_disease_code_result)>0){
        $count_sn=1;
        while($disease_rows=mysqli_fetch_assoc($sql_search_disease_code_result)){
            $disease_ID=$disease_rows['disease_ID'];
            $disease_code=$disease_rows['disease_code'];
            $disease_name=$disease_rows['disease_name'];
            echo "<tr class='rows_list' onclick='save_disease_anathesia($disease_ID)'>
                    <td>$count_sn</td>
                    <td>$disease_name</td>
                    <td>$disease_code</td>
                    
                </tr>";
                $count_sn++;
        }
    }
}
if(isset($_POST['save_diseases'])){
        if(isset($_POST['disease_ID'])){
        $disease_ID= $_POST['disease_ID']; 
        }else{
        $disease_ID="";  
        }
        if(isset($_POST['Registration_ID'])){ 
            $Registration_ID= $_POST['Registration_ID'];
        }else{
        $Registration_ID="";  
        }
        if(isset($_POST['Payment_Cache_ID'])){
            $Payment_Cache_ID = $_POST['Payment_Cache_ID'];
        }else{
            $Payment_Cache_ID= "";
        }
        $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress'";
        $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
        if(mysqli_num_rows($anasthesia_record_result)>0){
            $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
        }else{
            $anasthesia_employee_id=$_SESSION['userinfo']['Employee_ID'];
            $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTo tbl_anasthesia_record_chart(Registration_ID, anasthesia_created_at, anasthesia_employee_id,Payment_Cache_ID) VALUES('$Registration_ID', NOW(), '$anasthesia_employee_id','$Payment_Cache_ID')") or die(mysqli_error($conn));
            $anasthesia_record_id=mysqli_insert_id($conn);
            
        }
        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
        $diagnosis_record = mysqli_query($conn, "SELECT disease_ID FROM tbl_anasthesia_diagnosis WHERE Registration_ID = '$Registration_ID' AND DATE(created_at)=CURDATE() AND disease_ID ='$disease_ID' ");
        if((mysqli_num_rows($diagnosis_record))>0){
            $disease_ID = mysqli_fetch_assoc($diagnosis_record);
        }else{
        $sql_insert_selected_diagnosis_result=mysqli_query($conn,"INSERT INTO tbl_anasthesia_diagnosis(anasthesia_record_id, disease_ID, Registration_Id, Employee_ID, created_at ) VALUES('$anasthesia_record_id','$disease_ID', '$Registration_ID', '$Employee_ID', NOW() )") or die(mysqli_error($conn));
        if($sql_insert_selected_diagnosis_result){
            echo "Save";
        }else{
            echo "failed";
        }
    }

}

if(isset($_POST['display_diagnosis'])){
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

    $sql_search_disease_code_result=mysqli_query($conn,"SELECT anasthesia_diagnosis_id,disease_code,disease_name FROM tbl_disease td,tbl_anasthesia_diagnosis ad WHERE td.disease_ID=ad.disease_ID AND Registration_ID='$Registration_ID' AND saving_status='Pending' AND anasthesia_record_id='$anasthesia_record_id' ") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_search_disease_code_result)>0){
    $count_sn=1;
        while($disease_rows=mysqli_fetch_assoc($sql_search_disease_code_result)){
            $anasthesia_diagnosis_id=$disease_rows['anasthesia_diagnosis_id'];
            $disease_code=$disease_rows['disease_code'];
            $disease_name=$disease_rows['disease_name'];
            echo "<tr class='rows_list' onclick='save_disease_anathesia($disease_ID)'>
                    <td>$count_sn</td>
                    <td>$disease_name</td>
                    <td>$disease_code</td>
                    <td>
                        <input type='button' class='btn btn-danger' value='x' onclick='remove_anasthesia_disease($anasthesia_diagnosis_id)'>
                    </td>
                </tr>";
                $count_sn++;
        }
    }
}

if(isset($_POST['remove_diagnosis'])){
    if(isset($_POST['anasthesia_diagnosis_id'])){
        $anasthesia_diagnosis_id= $_POST['anasthesia_diagnosis_id'];
    }else{
        $anasthesia_diagnosis_id="";  
    }

    $remove_disease_selected = "DELETE FROM `tbl_anasthesia_diagnosis` WHERE anasthesia_diagnosis_id = '$anasthesia_diagnosis_id'";
    $remove_disease_selected_result = mysqli_query($conn, $remove_disease_selected);
    if($remove_disease_selected_result){
        echo "Removed";
    }else{
        echo "Failed";
    }
}

if(isset($_POST['view_disease_added'])){
    if(isset($_POST['Registration_ID'])){
        $Registration_ID= $_POST['Registration_ID'];
    }else{
    $Registration_ID="";  
    }
    $added_disease="";  
 
    $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress'";
    $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
    if(mysqli_num_rows($anasthesia_record_result)>0){
        $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
    }
 

    $sql_search_disease_code_result=mysqli_query($conn,"SELECT anasthesia_diagnosis_id,disease_code,disease_name FROM tbl_disease td,tbl_anasthesia_diagnosis ad WHERE td.disease_ID=ad.disease_ID AND Registration_ID='$Registration_ID' AND anasthesia_record_id='$anasthesia_record_id'") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_search_disease_code_result)>0){
    $count_sn=1;
    while($disease_rows=mysqli_fetch_assoc($sql_search_disease_code_result)){
        $anasthesia_diagnosis_id=$disease_rows['anasthesia_diagnosis_id'];
        $disease_code=$disease_rows['disease_code'];
        $disease_name=$disease_rows['disease_name'];
        $added_disease .= "$disease_name($disease_code) ; ";
    }
    }
    echo $added_disease;
}

if(isset($_POST['uploadconsertform'])){

    /* Getting file name */
    $filename = $_FILES['file']['name'];

    /* Location */
    $location = "attachment/".$filename;
    $uploadOk = 1;
    $imageFileType = pathinfo($location,PATHINFO_EXTENSION);

    /* Valid Extensions */
    $valid_extensions = array("jpg","jpeg","png","pdf");
    /* Check file extension */
    if( !in_array(strtolower($imageFileType),$valid_extensions) ) {
    $uploadOk = 0;
    }
    if($uploadOk == 0){
        echo 0;
    }else{
    /* Upload file */
    echo 1;
    $ext=substr(strrchr($_FILES['file']['name'],'.'),1);
    $picName="attchment".microtime().".$ext";
    if(move_uploaded_file($_FILES['file']['tmp_name'],"upload/".$picName)){
        echo $picName;
    }else{
        echo 'Nothing to show';
    }
    }
}





if(isset($_POST['rest'])){
    $path = "images/";
    $valid_file_formats = array("jpg", "png", "gif", "bmp","jpeg");
    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
    {
        $name = $_FILES['photoimg']['name'];
        $size = $_FILES['photoimg']['size'];
        //print_R($_POST);die;
        if(strlen($name)) {
            list($txt, $ext) = explode(".", $name);
            if(in_array($ext,$valid_file_formats)) {
                if($size<(1024*1024)) {
                    $user_id = 1;
                    $image_name = time().'_'.$user_id.".".$ext;
                    $tmp = $_FILES['photoimg']['tmp_name'];
                if(move_uploaded_file($tmp, $path.$image_name)){
                    
                    $sql = "UPDATE users_image SET image='".$image_name."' WHERE id=$user_id";
            
                    $result = mysqli_query($connString, $sql) or die("error to update image data");

                    echo json_encode(array('error'=>0, 'msg' => "Successfully!  Uploaded image.."));
                }
                else
                    echo json_encode(array('error'=>1, 'msg' => "Image Upload failed..!"));
                }
                else
                    echo json_encode(array('error'=>1, 'msg' => "Image file size maximum 1 MB..!"));
            }
            else
                echo json_encode(array('error'=>1, 'msg' => "Invalid file format..!"));
        }
        else
            echo json_encode(array('error'=>1, 'msg' => "Please select image..!"));
        exit;
    }
}
?>
