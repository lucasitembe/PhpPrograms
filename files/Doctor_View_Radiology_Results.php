<?php
//get all item details based on item id
if(isset($_GET['Registration_ID'])){
    $Registration_ID = $_GET['Registration_ID'];
}else{
    $Registration_ID = '';
}
if(isset($_GET['Patient_Payment_Item_List_ID'])){
    $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
}else{
    $Patient_Payment_Item_List_ID = '';
}

if(isset($_GET['Patient_Payment_ID'])){
    $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
}else{
    $Patient_Payment_ID = '';
}
if(isset($_GET['Status_From'])){
    $Status_From = $_GET['Status_From'];
}else{
    $Status_From = '';
}
if(isset($_GET['Item_ID'])){
    $Item_ID = $_GET['Item_ID'];
}else{
    $Item_ID = '';
}
?>
<table id='imgViewer' style='background: rgba(0, 0, 0, .8);position:fixed;z-index: 700;visibility:hidden;height:100%'>
    <tr>
        <td width='80%'>
            <legend style='color:white;text-align:center;'><b>PATIENT IMAGE</b></legend>
            <fieldset style='height:500px;width:90%;overflow:scroll;resize:none;'>

                <center>
                    <img id='imgViewerImg' style='width:70%;' onclick='zoomIn("imgViewerImg","in")'  src=''/>
                </center>

            </fieldset>
            <div style='position:absolute;right:30px;top:30px;color:white;background-color:white;border-radius:2px;opacity:.9;'>
                <center>
                    <p onclick='CloseImage()' style='cursor:pointer'>&nbsp;<img src='./images/close.png' width='20px'/></p>

                    <p >&nbsp;&nbsp;<img onclick='zoomIn("imgViewerImg","in")' style='cursor:pointer'  src='./images/zoomin.png' width='20px'/></p>

                    <p >&nbsp;&nbsp;<img onclick='zoomIn("imgViewerImg","out")'  style='cursor:pointer' src='./images/zoomout.png' width='20px'/></p>
                </center>

            </div>
        </td>
        <td style='text-align:center;'>
            <br><br><br><br><br><br><h4 style='color:white;'><b>select other images</b></h4><br>
            <iframe width='60%' height="80%"  src='radiologyimageviewDBS_framVertical.php?Registration_ID=<?php echo $Registration_ID;?>'></iframe>
        </td>

    </tr>

</table>
<script>
    function CloseImage(){
        document.getElementById('imgViewerImg').src = '';
        document.getElementById('imgViewer').style.visibility = 'hidden';
    }

    function zoomIn(imgId,inVal){
        if(inVal == 'in'){
            var zoomVal = 10;
        }else{
            var zoomVal = -10;
        }
        var Sizevalue = document.getElementById(imgId).style.width;
        Sizevalue = parseInt(Sizevalue)+zoomVal;
        document.getElementById(imgId).style.width = Sizevalue+'%';
    }


</script>
<?php
include("./includes/header.php");
include("./includes/connection.php");
$requisit_officer=$_SESSION['userinfo']['Employee_Name'];

if(!isset($_SESSION['userinfo'])){ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");}
if(isset($_SESSION['userinfo']))
{
    if(isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work']))
    {
        if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes'){ header("Location: ./index.php?InvalidPrivilege=yes");}
    }else
    {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
}else
{ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes"); }
?>
<label style='border: 1px ;padding: 8px;margin-right: 7px;' class='art-button-green'>
    <select id='patientlist' name='patientlist'>
        <option></option>
        <option>
            MY PATIENT LIST
        </option>
        <option>
            CLINIC PATIENT LIST
        </option>
        <option>
            CONSULTED PATIENT LIST
        </option>
    </select>
    <input type='button' value='VIEW' onclick='gotolink()'>
</label>

<a href='<?php if($Registration_ID!=''){
    ?>doctorpatientfile.php?<?php if($Registration_ID!=''){echo "Registration_ID=$Registration_ID&"; } ?><?php
    if(isset($_GET['Patient_Payment_ID'])){
        echo "Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&";
    }
    if(isset($_GET['Patient_Payment_Item_List_ID'])){
        echo "Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&";
    }
    ?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage<?php
} ?>' <?php if($Registration_ID==''){ ?> onclick="alert('Choose Patient First !');" <?php } ?> class='art-button-green'>PATIENT FILE</a>
<a href='patientsignoff.php?<?php
if($Registration_ID!=''){
    echo "Registration_ID=$Registration_ID&";
}
if(isset($_GET['Patient_Payment_ID'])){
    echo "Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&";
}
if(isset($_GET['Patient_Payment_Item_List_ID'])){
    echo "Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&";
}
?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
    SIGN OFF
</a>

<!--Lab Results
<a href='laboratoryresult.php?<?php
if($Registration_ID!=''){
    echo "Registration_ID=$Registration_ID&";
}
if(isset($_GET['Patient_Payment_ID'])){
    echo "Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&";
}
if(isset($_GET['Patient_Payment_Item_List_ID'])){
    echo "Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&";
}
?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
    LABORATORY RESULTS
</a> -->
<!--Radiology Results
<a href='radiologyresult.php?<?php
if($Registration_ID!=''){
    echo "Registration_ID=$Registration_ID&";
}
if(isset($_GET['Patient_Payment_ID'])){
    echo "Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&";
}
if(isset($_GET['Patient_Payment_Item_List_ID'])){
    echo "Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&";
}
?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
    RADIOLOGY RESULTS
</a>

-->
<?php if(isset($_SESSION['userinfo'])){
    if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes')
    {
        echo "<a href='radiologyresult.php' class='art-button-green'>BACK</a>";
    }
}

?>
<script type="text/javascript">
    function readImage(input){
        if(input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e){
                $('#Patient_Picture').attr('src',e.target.result).width('30%').height('20%');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function clearPatientPicture() {
        document.getElementById('Patient_Picture_td').innerHTML="<img src='./patientImages/default.png' id='Patient_Picture' name='Patient_Picture' width=50% height=50%>"
    }
</script>


<?php
//get all item details based on item id
if(isset($_GET['Registration_ID'])){
    $Registration_ID = $_GET['Registration_ID'];
}else{
    $Registration_ID = '';
}

$Results = mysqli_query($conn,"SELECT pr.Patient_Name,pp.Registration_ID,pr.Gender,pr.Date_Of_Birth,
		       pr.Registration_ID, pp.patient_payment_ID AS receipt, ppl.Check_In_Type,ppl.Item_ID,it.Product_Name
FROM tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr,tbl_items it
WHERE pr.Registration_ID = pp.Registration_ID
AND pp.Patient_Payment_ID = ppl.Patient_Payment_ID
AND ppl.Item_ID=it.Item_ID
AND pr.Registration_ID= '$Registration_ID'") or die (mysqli_error($conn));
$no = mysqli_num_rows($Results);
if($no > 0){
    while($row = mysqli_fetch_array($Results)){
        $Registration_ID = $row['Registration_ID'];
        $Patient_Name= $row['Patient_Name'];
        $Gender= $row['Gender'];
        $Date_Of_Birth = $row['Date_Of_Birth'];
        $Product_Name=$row['Product_Name'];

        //calculate age
        $date1 = new DateTime(date('Y-m-d'));
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1 -> diff($date2);
        $age = $diff->y." Years, ";
        $age.= $diff->m." Months and ";
        $age.= $diff->d." Days ";


    }
}
?>
<?php
//connection scripts to database

if(isset($_POST['submitted'])){
    if($_FILES['Radiology_Image']['name'] != '' && $_FILES['Radiology_Image']['name']!=null && !empty($_FILES['Radiology_Image']['name'])){
        error_reporting(E_ERROR | E_PARSE);
        $Registration_ID=$_GET['Registration_ID'];
        $path = $_POST['Radiology_Image'];
        $target = "RadiologyImage/";
        $Upload_Date =date('Y-m-d H:i:s');
        $target = $target . basename($_FILES['Radiology_Image']['name']);
        $Patient_Payment_Item_List_ID=$_GET['Patient_Payment_Item_List_ID'];
        $Patient_Payment_ID=$_GET['Patient_Payment_ID'];

        if(move_uploaded_file($_FILES['Radiology_Image']['tmp_name'], $target)){
            $sql="insert into tbl_radiology_image(Registration_ID,Radiology_Image,Patient_Payment_Item_List_ID,Upload_Date) values('$Registration_ID','$target','$Patient_Payment_Item_List_ID','$Upload_Date')" or die(mysqli_error($conn));
            $result=mysqli_query($conn,$sql) or die(mysqli_error($conn));
            if($result){?>
                <script>
                    alert("Image Successfully Uploaded.");
                </script>
            <?php }else{?>
                <script>
                    alert("Failed To Upload Image,Try again or contact the system admin");
                </script>
            <?php }
        }else{?>
            <script>
                alert("Failed to move the image to the specified directory.");
            </script>
        <?php }
    }else{?>
        <script>
            alert("No image selected for upload,Please choose one to proceed.");
        </script>
    <?php }
}
?>

<br><br><br>
<fieldset>

    <legend align='center'><b>PATIENT RADIOLOGY PICTURE RESULTS</b></legend>
    <center>
        <td>
            <table width=90% height=60%>
                <tr>
                    <td style="text-align:right;"><b>Patient Name</b></td>
                    <td><input type='text' name='Patient_Name' id='Patient_Name' disabled='disabled' value='<?php echo  $Patient_Name;?>'></td>
                    <td style="text-align:right;"><b>Gender</b></td>
                    <td><input type='text' name='Age' id='Age' disabled='disabled' value='<?php echo  $Gender;?>'></td>
                </tr>
                <tr>
                    <td style="text-align:right;"><b>Test for</b></td>
                    <td><input type='text' name='test_disease' id='test_disease' disabled='disabled' value='<?php echo $Product_Name?>'></td>
                    <td style="text-align:right;"><b>Age</b></td>
                    <td><input type='text' name='Age' id='Age'  value='<?php echo  $age;?>'</td>
                </tr>
                <table width="90%" align="center">
                    <tr>
                        <td id='Search_Iframe'>
                            <iframe width='100%' height="300px" src='Radiology_Image_DBS.php?Registration_ID=<?php echo $Registration_ID;?>'></iframe>
                        </td>
                    </tr>
                </table>
    </center>
    </table>
</fieldset>


<?php
include("./includes/footer.php");
?>
