<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Reception_Works'])) {
        if ($_SESSION['userinfo']['Reception_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
            
                  
    $Current_Username = $_SESSION['userinfo']['Given_Username'];
     
    $sql_check_prevalage="SELECT Edit_Patient_Information FROM tbl_privileges WHERE Edit_Patient_Information='yes' AND "
            . "Given_Username='$Current_Username'";
    
    $sql_check_prevalage_result=mysqli_query($conn,$sql_check_prevalage);
    if(!mysqli_num_rows($sql_check_prevalage_result)>0){
        ?>
                    <script>
                        var privalege= alert("You don't have the privelage to access this button")
                            document.location="./index.php?InvalidPrivilege=yes";
                    </script>
                    <?php
    }
?>

<?php
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = '';
}
?>

<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Reception_Works'] == 'yes') {
        ?>
        <a href='searchvisitorsoutpatientlist.php?SearchVisitorsOutPatientList=SearchVisitorsOutPatientListThisPage' class='art-button-green'>
            VISITORS
        </a>
    <?php }
} ?>
<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Reception_Works'] == 'yes') {
        ?>
        <a href='visitorform.php?Registration_ID=<?php echo $Registration_ID; ?>&VisitorFormPatient=VisitorFormPatientThisPage' class='art-button-green'>
            BACK
        </a>
    <?php }
} ?>
<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>

<script type="text/javascript">
    function readImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#Patient_Picture').attr('src', e.target.result).width('50%').height('70%');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function clearPatientPicture() {
        document.getElementById('Patient_Picture_td').innerHTML = "<img src='./patientImages/default.png' id='Patient_Picture' name='Patient_Picture' width=50% height=50%>"
    }
</script>

<script language="javascript" type="text/javascript">
    function searchEmployee(Employee_Name) {
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=100% src='viewpatientsIframe.php?Employee_Name=" + Employee_Name + "'></iframe>";
    }
</script>


<script type="text/javascript" language="javascript">
    function getDistricts(Region_Name) {

        $.ajax({
               type: 'get',
               url: 'GetDistricts.php',
               data: {
                Region_Name: Region_Name,
                District: $('#District').val()
               },
               success: (data) => {
                   $('#District').html("<option selected='selected'>"+$('#District').val()+"</option>"+data);
               }
            });

        // var district = 
        // alert(district);
        // if (window.XMLHttpRequest) {
        //     mm = new XMLHttpRequest();
        // } else if (window.ActiveXObject) {
        //     mm = new ActiveXObject('Micrsoft.XMLHTTP');
        //     mm.overrideMimeType('text/xml');
        // }

        // mm.onreadystatechange = AJAXP; //specify name of function that will handle server response....
        // mm.open('GET', 'GetDistricts.php?Region_Name=' + Region_Name, true);
        // mm.send();
    }
    // function AJAXP() {
    //     var data = mm.responseText;
    //     alert(data)
    //     $('#District').html("<option selected='selected'>"+$('#District').val()+"</option>"+data);
    // }

//    function to verify NHIF STATUS
    function nhifVerify() {
        //code
    }
</script>


<br/><br/>





<?php
//    select patient information to perform check in process
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];

    $select_Patient = mysqli_query($conn,"select
                            Old_Registration_Number,Title,Patient_Name,
                                Date_Of_Birth,
                                    Gender,pr.Region,pr.District,pr.Ward,country,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID,Religion_ID,Denomination_ID,Tribe,
                                                    
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,
                                                        Registration_ID,Patient_Picture
                                      
                                      
                                      
                                      
                                      from tbl_patient_registration pr, tbl_sponsor sp 
                                        where pr.Sponsor_ID = sp.Sponsor_ID and 
                                              Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Patient);

    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Patient)) {
            $Registration_ID = $row['Registration_ID'];
            $Patient_Picture = $row['Patient_Picture'];
            $Old_Registration_Number = $row['Old_Registration_Number'];
            $Title = $row['Title'];
            $Patient_Name = ucwords(strtolower($row['Patient_Name']));
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $Gender = $row['Gender'];
            $country = $row['country'];
            $Region = $row['Region'];
            $District = $row['District'];
            $Ward = $row['Ward'];
            $Guarantor_Name = $row['Guarantor_Name'];
            $Member_Number = $row['Member_Number'];
            $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
            $Phone_Number = $row['Phone_Number'];
            $Email_Address = $row['Email_Address'];
            $Occupation = $row['Occupation'];
            $Employee_Vote_Number = $row['Employee_Vote_Number'];
            $Emergence_Contact_Name = $row['Emergence_Contact_Name'];
            $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
            $Company = $row['Company'];
            $Employee_ID = $row['Employee_ID'];
            $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
            $Religion_ID = $row['Religion_ID'];
            $Denomination_ID = $row['Denomination_ID'];
            $Tribe = $row['Tribe'];
            // echo $Ward."  ".$District."  ".$Ward; exit;
        }
    } else {
        $Registration_ID = '';
        $Old_Registration_Number = '';
        $Title = '';
        $Patient_Name = '';
        $Date_Of_Birth = '';
        $Gender = '';
        $Region = '';
        $District = '';
        $Ward = '';
        $Guarantor_Name = '';
        $Member_Number = '';
        $Member_Card_Expire_Date = '';
        $Phone_Number = '';
        $Email_Address = '';
        $Occupation = '';
        $Employee_Vote_Number = '';
        $Emergence_Contact_Name = '';
        $Emergence_Contact_Number = '';
        $Company = '';
        $Employee_ID = '';
        $Registration_Date_And_Time = '';
        $Religion_ID = "";
        $Denomination_ID = "";
        $Tribe = "";
    }
} else {
    $Registration_ID = '';
    $Old_Registration_Number = '';
    $Title = '';
    $Patient_Name = '';
    $Date_Of_Birth = '';
    $Gender = '';
    $Region = '';
    $country = '';
    $District = '';
    $Ward = '';
    $Guarantor_Name = '';
    $Member_Number = '';
    $Member_Card_Expire_Date = '';
    $Phone_Number = '';
    $Email_Address = '';
    $Occupation = '';
    $Employee_Vote_Number = '';
    $Emergence_Contact_Name = '';
    $Emergence_Contact_Number = '';
    $Company = '';
    $Employee_ID = '';
    $Registration_Date_And_Time = '';
    $Religion_ID = "";
    $Denomination_ID = "";
    $Tribe = "";
}
?>





<!--  insert data from the form  -->

<?php
///decompression function
      function compress_image($source_url, $destination_url, $quality) {

       $info = getimagesize($source_url);

        if ($info['mime'] == 'image/jpeg')
              $image = imagecreatefromjpeg($source_url);

        elseif ($info['mime'] == 'image/gif')
              $image = imagecreatefromgif($source_url);

      elseif ($info['mime'] == 'image/png')
              $image = imagecreatefrompng($source_url);

        imagejpeg($image, $destination_url, $quality);
    return $destination_url;
    }
////////////////////////////////////////////////////////////////////////////////
if (isset($_POST['submittedEditPatientForm'])) {

    if (isset($_SESSION['userinfo'])) {
        if (isset($_SESSION['userinfo']['Employee_ID'])) {
            $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        } else {
            $Employee_ID = 0;
        }
    }

    $Old_Registration_Number = mysqli_real_escape_string($conn,$_POST['Old_Registration_Number']);
    $Patient_Title = mysqli_real_escape_string($conn,$_POST['Patient_Title']);
    // $Patient_Name = mysqli_real_escape_string($conn,preg_replace('/s+/', ' ', $_POST['Patient_Name']));
    $Patient_Name = mysqli_real_escape_string($conn,$_POST['Patient_Name']);
    $Date_Of_Birth = mysqli_real_escape_string($conn,$_POST['Date_Of_Birth']);
    $Gender = mysqli_real_escape_string($conn,$_POST['Gender']);
    $country = mysqli_real_escape_string($conn,$_POST['country']);
    $region = mysqli_real_escape_string($conn,$_POST['region']);
    $District = mysqli_real_escape_string($conn,$_POST['District']);
    $Ward = mysqli_real_escape_string($conn,$_POST['Ward']);
    $Phone_Number = mysqli_real_escape_string($conn,$_POST['Phone_Number']);
    $Email = mysqli_real_escape_string($conn,$_POST['Email']);
    $Guarantor_Name = mysqli_real_escape_string($conn,$_POST['Guarantor_Name']);
    $Member_Number = mysqli_real_escape_string($conn,$_POST['Member_Number']);
    $Member_Card_Expire_Date = mysqli_real_escape_string($conn,$_POST['Member_Card_Expire_Date']);
    $Occupation = mysqli_real_escape_string($conn,$_POST['Occupation']);
    $Employee_Vote_Number = mysqli_real_escape_string($conn,$_POST['Employee_Vote_Number']);
    $Emergence_Contact_Name = mysqli_real_escape_string($conn,$_POST['Emergence_Contact_Name']);
    $Emergence_Contact_Number = mysqli_real_escape_string($conn,$_POST['Emergence_Contact_Number']);
    $get_religeon = mysqli_real_escape_string($conn, $_POST['get_religeon']);
    $Company = mysqli_real_escape_string($conn,$_POST['Company']);
    if (isset($_FILES['Patient_Picture']) &&$_FILES['Patient_Picture']['name']!="") {
        $Patient_Picture = md5($_FILES['Patient_Picture']['name'] . date('Ymdhms')) . ".png";
        $Patient_Picture_temp = $_FILES['Patient_Picture']['tmp_name'];
        $Patient_Picture_path = "./patientImages/" . $Patient_Picture;
    } else {
        $Patient_Picture = 'default.png';
    }
    if (isset($_SESSION['userinfo'])) {
        if (isset($_SESSION['userinfo']['Employee_ID'])) {
            $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        } else {
            $Employee_ID = 0;
        }
    }
        //scan for changes
        $activities="Edit patient: <br>$Patient_Name</br> Reg # <b>$Registration_ID</b>";
        $sql_select_saved_data_from_database_result=mysqli_query($conn,"SELECT Old_Registration_Number,Title,Patient_Name,Date_Of_Birth,Gender,country,region,District,Ward,Phone_Number,Email_Address,Sponsor_ID,Member_Number,Member_Card_Expire_Date,Occupation,Employee_Vote_Number,Emergence_Contact_Name,Emergence_Contact_Number,Company,Religion_ID,Denomination_ID,Tribe FROM tbl_patient_registration WHERE Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        if(mysqli_num_rows($sql_select_saved_data_from_database_result)>0){
            while($saved_dt_rows= mysqli_fetch_assoc($sql_select_saved_data_from_database_result)){
                $Old_Registration_Number_old=$saved_dt_rows['Old_Registration_Number'];
                $Patient_Title_old=$saved_dt_rows['Title'];
                $Patient_Name_old=$saved_dt_rows['Patient_Name'];
                $Date_Of_Birth_old=$saved_dt_rows['Date_Of_Birth'];
                $Gender_old=$saved_dt_rows['Gender'];
                $country_old=$saved_dt_rows['country'];
                $region_old=$saved_dt_rows['region'];
                $District_old=$saved_dt_rows['District'];
                $Ward_old=$saved_dt_rows['Ward'];
                $Phone_Number_old=$saved_dt_rows['Phone_Number'];
                $Email_old=$saved_dt_rows['Email_Address'];
                $Sponsor_ID_old=$saved_dt_rows['Sponsor_ID'];
                $Member_Card_Expire_Date_old=$saved_dt_rows['Member_Card_Expire_Date'];
                $Member_Number_old=$saved_dt_rows['Member_Number'];
                $Occupation_old=$saved_dt_rows['Occupation'];
                $Employee_Vote_Number_old=$saved_dt_rows['Employee_Vote_Number'];
                $Emergence_Contact_Name_old=$saved_dt_rows['Emergence_Contact_Name'];
                $Emergence_Contact_Number_old=$saved_dt_rows['Emergence_Contact_Number'];
                $Company_old=$saved_dt_rows['Company'];

                $Religion_ID=$saved_dt_rows['Religion_ID'];
                $Denomination_ID = $saved_dt_rows['Denomination_ID'];
                $Tribe = $saved_dt_rows['Tribe'];

                $sal_select_sponsor_result=mysqli_query($conn,"select Sponsor_ID,Guarantor_Name from tbl_sponsor where Sponsor_ID = '$Sponsor_ID_old'") or die(mysqli_error($conn));
                if(mysqli_num_rows($sal_select_sponsor_result)>0){
                    $spon_rows=mysqli_fetch_assoc($sal_select_sponsor_result);
                    $Guarantor_Name_old=$spon_rows['Guarantor_Name'];
                }
                if($Old_Registration_Number_old!=$Old_Registration_Number){
                    $activities .="<br/>  Old number from <b>$Old_Registration_Number_old</b> to <b>$Old_Registration_Number</b>";
                }
                if($Patient_Title_old!=$Patient_Title){
                    $activities .="<br/>  Title from <b>$Patient_Title_old</b> to <b>$Patient_Title</b>";
                }
                if($Patient_Name_old!=$Patient_Name){
                    $Patient_Name_old= mysqli_real_escape_string($conn,str_replace("\\", "", $Patient_Name_old));
                    $Patient_Name= mysqli_real_escape_string($conn,str_replace("\\", "", $Patient_Name));
                    $activities .="<br/> Patient Name from <b>$Patient_Name_old</b> to <b>$Patient_Name</b>";
                }
                if($Date_Of_Birth_old!=$Date_Of_Birth){
                    $activities .="<br/>  Date of Birth from <b>$Date_Of_Birth_old</b> to <b>$Date_Of_Birth</b>";
                }
                if($Gender_old!=$Gender){
                    $activities .="<br/>  Gender from <b>$Gender_old</b> to <b>$Gender</b>";
                }
                if($country_old!=$country){
                    $activities .="<br/>  Country from <b>$country_old</b> to <b>$country</b>";
                }
                if($region_old!=$region){
                    $activities .="<br/>  Region from <b>$region_old</b> to <b>$region</b>";
                }
                if($District_old!=$District){
                    $activities .="<br/>  District from <b>$District_old</b> to <b>$District</b>";
                }
                if($Ward_old!=$Ward){
                    $activities .="<br/>  Ward from <b>$Ward_old</b> to <b>$Ward</b>";
                }
                if($Phone_Number_old!=$Phone_Number){
                    $activities .="<br/>  Phone Number from <b>$Phone_Number_old</b> to <b>$Phone_Number</b>";
                }
                if($Email_old!=$Email){
                    $activities .="<br/>  Email from <b>$Email_old</b> to <b>$Email</b>";
                }
                if($Guarantor_Name_old!=$Guarantor_Name){
                    $activities .="<br/>  Sponsor from <b>$Guarantor_Name_old</b> to <b>$Guarantor_Name</b>";
                }
                if($Member_Card_Expire_Date_old!=$Member_Card_Expire_Date){
                    $activities .="<br/> Member Card Expire Date  from <b>$Member_Card_Expire_Date_old</b> to <b>$Member_Card_Expire_Date</b>";
                }
                if($Member_Number_old!=$Member_Number){
                    $activities .="<br/> Member Number  from <b>$Member_Number_old</b> to <b>$Member_Number</b>";
                }
                if($Occupation_old!=$Occupation){
                    $activities .="<br/> Occupation  from <b>$Occupation_old</b> to <b>$Occupation</b>";
                }
                if($Employee_Vote_Number_old!=$Employee_Vote_Number){
                    $activities .="<br/>  Employee Vote Number  from <b>$Employee_Vote_Number_old</b> to <b>$Employee_Vote_Number</b>";
                }
                if($Emergence_Contact_Name_old!=$Emergence_Contact_Name){
                    $activities .="<br/>   Emergence Contact Name  from <b>$Emergence_Contact_Name_old</b> to <b>$Emergence_Contact_Name</b>";
                }
                if($Emergence_Contact_Number_old!=$Emergence_Contact_Number){
                    $activities .="<br/>    Emergence Contact Number  from <b>$Emergence_Contact_Number_old</b> to <b>$Emergence_Contact_Number</b>";
                }
                if($Company_old!=$Company){
                    $activities .="<br/>   Company  from <b>$Company_old</b> to <b>$Company</b>";
                }
                
            }
        }
        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
        require_once 'audit_trail_function.php';
        audit_trail_log_employee_activities($activities,$Employee_ID,$Registration_ID,"tbl_patient_registration");
       
    //select patient registration date and time
    $data = mysqli_query($conn,"select now() as Registration_Date_And_Time");
    while ($row = mysqli_fetch_array($data)) {
        $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
    }
    $image_update="";
    if($Patient_Picture != 'default.png'){
       $image_update=",Patient_Picture='$Patient_Picture'"; 
    }
    
    
    $select_denomination = $_POST['select_denomination'];
    $Patient_Name = htmlspecialchars($Patient_Name, ENT_QUOTES); 
//    die($image_update);
    $Update_Sql = "UPDATE tbl_patient_registration set
            Old_Registration_Number = '$Old_Registration_Number',Title = '$Patient_Title',Patient_Name = '$Patient_Name',
            Date_Of_Birth = '$Date_Of_Birth',Gender = '$Gender',Region = '$region',District = '$District',Ward = '$Ward',country='$country',
            Phone_Number = '$Phone_Number',Email_Address = '$Email',Occupation = '$Occupation',
            Sponsor_ID = (select Sponsor_ID from tbl_sponsor where Guarantor_Name = '$Guarantor_Name'),
            Member_Number = '$Member_Number',Member_Card_Expire_Date = '$Member_Card_Expire_Date',
            Employee_Vote_Number = '$Employee_Vote_Number',Emergence_Contact_Name = '$Emergence_Contact_Name',
            Denomination_ID = '$select_denomination',
            Religion_ID='$get_religeon',
            Emergence_Contact_Number = '$Emergence_Contact_Number',Company = '$Company' $image_update where Registration_ID = '$Registration_ID'";
    if (!mysqli_query($conn,$Update_Sql)) {
        die(mysqli_error($conn));
        die(mysqli_error($conn));

        echo "<script type='text/javascript'>
                    alert('Process Fail! Please Try Again');
                    </script>";
    } else {
        if ($Patient_Picture != 'default.png') {
//            move_uploaded_file($Patient_Picture_temp, $Patient_Picture_path);
             $sql_select_image_quality_result=mysqli_query($conn,"SELECT image_quality_value FROM tbl_image_quality LIMIT 1") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_select_image_quality_result)>0){
                    $image_quality=mysqli_fetch_assoc($sql_select_image_quality_result)['image_quality_value'];
                }else{
                   $image_quality=10; 
                }
                compress_image($Patient_Picture_temp, $Patient_Picture_path, $image_quality);
        }
        echo "<script type='text/javascript'>
                    alert('PARTIENT EDITED SUCCESSFULLY');
                    document.location = 'visitorform.php?Registration_ID=" . $Registration_ID . "&VisitorFormPatient=VisitorFormPatientThisPage';
                    </script>";
        //call the function to log the action
        $Activity_Date_And_Time = date('Y-m-d H:i:s');
        activity_log($Employee_ID, $Activity_Date_And_Time, "Edited Patient Information.");
    }
}
?>

<fieldset>  
    <legend align=right><b>EDIT PATIENT</b></legend>
    <center>
        <table width=100%>
            <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
                <tr>
                    <td width=35%>
                        <table width=100%>
                            <tr>
                                <td style="text-align:right;">New Regiatration Number</td>
                                <td><input type='text' name='Registration_Number' id='Registration_Number' disabled='disabled' value='<?php echo $_GET['Registration_ID']; ?>'></td> 
                            </tr>
                            <tr>
                                <td style="text-align:right;">Old Registration Number</td>
                                <td><input type='text' name='Old_Registration_Number' id='Old_Registration_Number' value='<?php echo $Old_Registration_Number; ?>'></td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Title</td>
                                <td>
                                    <select name='Patient_Title' id='Patient_Title'>
                                        <option selected='selected'><?php echo $Title; ?></option>
                                        <option>Mr</option>
                                        <option>Mrs</option>
                                        <option>Miss</option>
                                        <option>Master</option>
                                        <option>Dr</option>
                                        <option>Prof</option>
                                        <option>Ms</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Patient Name</td>
                                
                                <td><input type='text' name='Patient_Name' id='Patient_Name' value='<?php echo $Patient_Name; ?>'></td>
                            </tr> 
                            <tr>
                                <td style="text-align:right;">Date Of Birth</td>
                                <td><input type='text' name='Date_Of_Birth' id='date2' readonly="readonly"required='required' value='<?php echo $Date_Of_Birth; ?>'></td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Gender</td>
                                <td>
                                    <select name='Gender' id='Gender'>
                                        <?php
                                        if (strtolower($Gender) == 'male') {
                                            echo "<option selected='selected'>Male</option>";
                                            echo "<option>Female</option>";
                                        } else {
                                            echo "<option selected='selected'>Female</option>";
                                            echo "<option>Male</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Occupation</td>
                                <td><input type='text' name='Occupation' id='Occupation' value='<?php echo $Occupation; ?>'></td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Employee Vote Number</td>
                                <td><input type='text' name='Employee_Vote_Number' id='Employee_Vote_Number' value='<?php echo $Employee_Vote_Number; ?>'></td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Company</td>
                                <td><input type='text' name='Company' id='Company' value='<?php echo $Company; ?>'></td>
                            </tr>
                            <tr> 
                                <td style="text-align:right;">Patient Phone Number</td>
                                <td><input type='text' name='Phone_Number' id='Phone_Number' value='<?php echo $Phone_Number; ?>'></td> 
                            </tr>

                            <tr> 
                                <td style="text-align:right;">Religeon</td>
                                <td>
                                    <select name="get_religeon" id="">
                                    <?php 
                                        $get_religeon_result = mysqli_query($conn,"SELECT * FROM tbl_religions");
                                        while($get_religeon = mysqli_fetch_assoc($get_religeon_result)) :
                                    ?>
                                        <option value="<?=$get_religeon['Religion_ID']?>"><?=$get_religeon['Religion_Name']?></option>
                                    <?php endwhile; ?>
                                    </select>
                                    <!-- <input type='text' name='Phone_Number' id='Phone_Number' value='<?php echo $Religion_ID; ?>'> -->
                                </td> 
                            </tr>

                            <tr> 
                                <td style="text-align:right;">Denomination</td>
                                <td>
                                    <select name="select_denomination" id="">
                                            <?php $get_denomation_results = mysqli_query($conn,"SELECT * FROM tbl_denominations") ?>
                                            <?php while($get_denomation = mysqli_fetch_assoc($get_denomation_results)) :  ?>
                                            <option value="<?=$get_denomation['Denomination_ID']?>"><?=$get_denomation['Denomination_Name']?></option>
                                            <?php endwhile; ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td width=35%><table width=100%>
                            <tr>
                                <td style="text-align:right;">Country</td>
                                <td>
                                    <select name='country' id='country' onchange='get_Regions()'>
                                    <option selected='selected' value=''>Select Country</option>
                                    <option selected='selected' value='<?php echo $country; ?>'><?php echo $country; ?></option>
                                    
                                        <?php
                                        $data = mysqli_query($conn,"select Country_Name, Country_ID, (select Country_ID from tbl_regions where Region_Status = 'Selected') as Country_ID2 from tbl_country ORDER BY Country_ID ASC");
                                        while ($row = mysqli_fetch_array($data)) {
                                            ?>
                                            <option value='<?php echo $row['Country_Name']; ?>'><?php echo $row['Country_Name']; ?></option>
                                                    <?php
                                                }
                                                ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Region</td>
                                <td>
                                    <select name='region' id='region' onchange='getDistricts(this.value)'>
                                        <option selected='selected' value='<?php echo $Region; ?>'><?php echo $Region; ?></option>
                                        <?php
                                        $data = mysqli_query($conn,"select * from tbl_regions");
                                        while ($row = mysqli_fetch_array($data)) {
                                            ?>
                                            <option value='<?php echo $row['Region_Name']; ?>'>
                                                <?php echo $row['Region_Name']; ?>
                                            </option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td> 
                            </tr> 
                            <tr>
                                <td style="text-align:right;">District</td>
                                <td>
                                    <select name='District' id='District'>
                                        <option><?=$District?></option>
                                    </select>
                                </td> 
                            </tr>
                            <tr>
                                <td style="text-align:right;">Ward</td>
                                <td>
                                    <select name="Ward">
                                        <?php 
                                            $select_Wards = mysqli_query($conn,"SELECT * FROM tbl_ward");
                                            while($get_ward = mysqli_fetch_assoc($select_Wards)) : 
                                        ?>
                                            <option value="<?=$get_ward['Ward_ID']?>"><?=$get_ward['Ward_Name']?></option>
                                            <?php endwhile; ?>
                                    </select>
                                    <!-- <input type='text' name='Ward' id='Ward' value='<?php echo $Ward; ?>'> -->
                                </td> 
                            </tr>
                            <tr>
                                <td style="text-align:right;">Sponsor</td>
                                <?php if (strtolower($_SESSION['userinfo']['Modify_Credit_Information']) == 'yes' && strtolower($_SESSION['userinfo']['Modify_Cash_information']) == 'yes' && strtolower($_SESSION['userinfo']['Session_Master_Priveleges']) == 'yes') { ?>
                                    <td>
                                        <select name='Guarantor_Name' id='Guarantor_Name' required='required'>
                                            <option selected='selected'><?php echo $Guarantor_Name; ?></option>
                                            <?php
                                            $data = mysqli_query($conn,"select * from tbl_sponsor where Guarantor_Name <> '$Guarantor_Name'");
                                            while ($row = mysqli_fetch_array($data)) {
                                                echo '<option>' . $row['Guarantor_Name'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </td>
                                <?php } elseif (strtolower($_SESSION['userinfo']['Edit_Patient_Information']) == 'yes') { ?>
                                    <td>

                                        <?php
                                       
                                            echo "<select name='Guarantor_Name' id='Guarantor_Name' required='required'><option selected='selected'>$Guarantor_Name</option>";
                                            $data = mysqli_query($conn,"select * from tbl_sponsor where Guarantor_Name <> '$Guarantor_Name'");
                                            while ($row = mysqli_fetch_array($data)) {
                                                echo '<option>' . $row['Guarantor_Name'] . '</option>';
                                            }

                                            echo "</select>";
                                         
                                        ?>


                                    </td>

<?php } else { ?>
                                    <td>
                                        <select name='Guarantor_Name' id='Guarantor_Name' required='required'>
                                            <option selected='selected'><?php echo $Guarantor_Name; ?></option>
                                        </select>
                                    </td>
<?php } ?> 
                            </tr>
                            <tr>
                                <td style="text-align:right;">Member Number</td>
<?php if (strtolower($_SESSION['userinfo']['Modify_Credit_Information']) == 'yes') { ?>
                                <td><input type='text' name='Member_Number' id='Member_Number'<?= $required ?> value='<?php echo $Member_Number; ?>'></td>
                                <?php } elseif ($Member_Number == '' || $Member_Number == NULL) { ?>
                                    <?php
                                    if ($Guarantor_Name == 'CASH') {
                                        echo "<td><input type='text' disabled='true' name='Member_Number' id='Member_Number' value=''></td>";
                                    } else {
                                       
                                        echo " <td><input type='text' name='Member_Number'  id='Member_Number' value='$Member_Number'></td>";
                                    }
                                    ?>

                                <?php } elseif (strtolower($_SESSION['userinfo']['Edit_Patient_Information']) == 'yes') { ?>

                                    <?php
                                    if ($Guarantor_Name == 'CASH') {
                                        echo "<td><input type='text' disabled='true' name='Member_Number' id='Member_Number' value=''></td>";
                                    } else {
                                        echo " <td><input type='text' name='Member_Number' id='Member_Number' value='$Member_Number'></td>";
                                    }
                                    ?>

                                <?php } else { ?>
                                    <td>
                                        <input type='text' disabled='disabled' value='<?php echo $Member_Number; ?>'>
                                        <input type='hidden' name='Member_Number' id='Member_Number' value='<?php echo $Member_Number; ?>'>
                                    </td>
                                <?php } ?>
                            </tr>                             
                            <tr>
                                <td style="text-align:right;">Member Card Expire Date</td>
<?php if (strtolower($_SESSION['userinfo']['Modify_Credit_Information']) == 'yes') { ?>
                                    <td><input type='text' name='Member_Card_Expire_Date' id='date' value='<?php echo $Member_Card_Expire_Date; ?>'></td>
                                <?php } elseif (strtolower($_SESSION['userinfo']['Edit_Patient_Information']) == 'yes') { ?>

    <?php
    if ($Guarantor_Name == 'CASH') {
        echo "<td><input type='text' disabled='disabled' value=''></td>";
    } else {
        echo "  <td><input type='text' name='Member_Card_Expire_Date' id='date' value='$Member_Card_Expire_Date'></td></td>";
    }
    ?>

                                <?php } else { ?>
                                    <td>
                                        <input type='text' disabled='disabled' value='<?php echo $Member_Card_Expire_Date; ?>'>
                                        <input type='hidden' name='Member_Card_Expire_Date' id='date' value='<?php echo $Member_Card_Expire_Date; ?>'>
                                    </td>
                                <?php } ?>
                            </tr>


                            <tr>
                                <td style="text-align:right;">E Mail</td>
                                <td><input type='email' name='Email' id='Email' value='<?php echo $Email_Address; ?>'></td> 
                            </tr>
                            <tr>
                                <td style="text-align:right;">Relative Contact Name</td>
                                <td><input type='text' name='Emergence_Contact_Name' id='Emergence_Contact_Name'  value='<?php echo $Emergence_Contact_Name; ?>'></td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Relative Contact Number</td>
                                <td><input type='text' name='Emergence_Contact_Number' id='Emergence_Contact_Number'  value='<?php echo $Emergence_Contact_Number; ?>' ></td>
                            </tr>

                            <tr>
                                <td style="text-align:right;">Tribe</td>
                                <td>
                                    <select name="tribes" id="">
                                        <?php $result = mysqli_query($conn,"SELECT * FROM tbl_tribe"); ?>
                                        <?php while($get_tribe_name = mysqli_fetch_assoc($result)) : ?>
                                            <option value="<?=$get_tribe_name['id']?>"><?= $get_tribe_name['tribe_name']?></option>
                                        <?php endwhile;?>
                                    </select>
                                    </td>
                            </tr>
<?php
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = "Unknown Employee";
}
?> 
                        </table>
                    </td>
                    <td width=30%>
                        <table width=100%>
                            <tr><td style='text-align: center;'>Patient Picture</td></tr>
                            <tr>
                                <td id='Patient_Picture_td' style='text-align: center;'>
                                    <img src='./patientImages/<?= $Patient_Picture ?>' id='Patient_Picture' name='Patient_Picture' width=50% height=50%>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    SELECT PICTURE
                                    <input type="file" name="Patient_Picture" id="file" onchange='readImage(this)' title='SELECT PATIENT PICTURE'/> 
                                </td>
                            </tr>
                            <tr>
                                <td colspan=2 style='text-align: right;'>
                                    <input type='submit' name='submit' id='submit' value='   UPDATE   ' class='art-button-green'>
                                    <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green' onclick='clearPatientPicture()'>
                                    <input type='hidden' name='submittedEditPatientForm' value='true'/> 
                                </td>
                            </tr> 
                        </table>    
                    </td>
            </form>
            </tr>
        </table>
    </center>
</fieldset><br/>
<?php
include("./includes/footer.php");
?>

<script>
    $('#Guarantor_Name').on('change', function () {
        var sponsor = $(this).val();
        if (sponsor == 'CASH') {
            $('#date,#Member_Number').prop('disabled', true);
        } else {
            $('#date,#Member_Number').prop('disabled', false);
        }
    });
    $(document).ready(function(){
        var region=$("#region").val();
        getDistricts(region);
    });
    
        function get_Regions() {
        var country = document.getElementById("country").value;

        if (window.XMLHttpRequest) {
            myObjectRegs = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectRegs = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRegs.overrideMimeType('text/xml');
        }

        myObjectRegs.onreadystatechange = function () {
            dataReg = myObjectRegs.responseText;
            if (myObjectRegs.readyState == 4) {
                document.getElementById('region').innerHTML = dataReg;
                getDistricts();
            }
        }; //specify name of function that will handle server response........
        myObjectRegs.open('GET', 'get_Regions.php?country=' + country, true);
        myObjectRegs.send();
    }
</script>