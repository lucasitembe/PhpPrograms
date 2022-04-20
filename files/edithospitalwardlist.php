<?php
include("./includes/connection.php");
include("./includes/header.php");
$controlforminput = '';
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo']['Setup_And_Configuration'])) {
    if ($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes') {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

    if(isset($_GET['frompage']) && $_GET['frompage'] == "addmission") {
?>
<a href='admissionconfiguration.php?AdmisionWorks=AdmisionWorksThisPage&frompage=addmission' class='art-button-green'>
    <b>BACK</b>
</a>

<?php
    } else {
?>
<a href='admissionconfiguration.php?AdmisionWorks=AdmisionWorksThisPage' class='art-button-green'>
    <b>BACK</b>
</a>

<?php
    }
?>

<br/>
<br/>
<?php
if (isset($_POST['submittedAddNewWardForm'])) {

    $Hospital_Ward_Name = mysqli_real_escape_string($conn,$_POST['Hospital_Ward_Name']);
    $Number_Of_Beds = $_POST['Number_Of_Beds'];
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    $insert_ward = "
			INSERT INTO tbl_hospital_ward(Hospital_Ward_Name, Branch_ID, Number_of_Beds)
				VALUES('$Hospital_Ward_Name', '$Branch_ID', '$Number_Of_Beds')";

    if (mysqli_query($conn,$insert_ward)) {
        //header("location: addhospitalward.php?added=true&alladded=7&addhospitalward=true");
        $Ward_ID = mysql_insert_id();
        echo 'Ward Added';

        $Bed_Number = 1;

        while ($Bed_Number < $Number_Of_Beds) {
            $Bed_Name = 'Bed No. ' . $Bed_Number;
            $Status = 'available';

            $insert_beds = "
					INSERT INTO tbl_beds(Bed_Name, Status, Ward_ID)
						VALUES('$Bed_Name', '$Status', '$Ward_ID')";

            mysqli_query($conn,$insert_beds);
            $Bed_Number++;
        }
    } else {
        echo 'Error! Ward NOT Added';
        //header("location: addhospitalward.php?added=true&alladded=7&addhospitalward=true");
    }
}

if (isset($_GET['added'])) {
    if ($_GET['added'] == 'true') {
        ?>
        <script>
            alert("Ward Added Successfully !");
        </script>
    <?php } else {
        ?>
        <script>
            alert("Ward Not Added !");
        </script>
        <?php
    }
}

$sql = "SELECT * FROM tbl_hospital_ward WHERE Branch_ID='" . $_SESSION['userinfo']['Branch_ID'] . "'";

$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
?>
<center>
    <center>
        <fieldset>

            <legend align="center" ><b>EDIT HOSPITAL WARD</b></legend>
            <div id="Search_Iframe" style="height: 500px;overflow-y: auto;overflow-x: hidden">
                <table width="100%" id="patientList" >
                    <thead>
                        <tr>
                            <td style="width:3% ">S/N</td><td>Hospital Ward Name</td><td>Number of Beds</td><td>Ward Nature</td><td>Remark</td>
                        </tr>
                    </thead>  

                    <?php
                    $i = 1;
                    while ($row = mysqli_fetch_array($result)) {
                        echo '<tr><td>' . ( ++$i) . '</td><td><a href="edithospitalward.php?Hospital_Ward_ID=' . $row['Hospital_Ward_ID'] . '&AdmisionWorks=AdmisionWorksThisPage">' . $row['Hospital_Ward_Name'] . '</td>   
                        
                        
                        <td><a href="edithospitalward.php?Hospital_Ward_ID=' . $row['Hospital_Ward_ID'] . '&AdmisionWorks=AdmisionWorksThisPage">' . $row['Number_of_Beds'] . '</td>    
                        <td><a href="edithospitalward.php?Hospital_Ward_ID=' . $row['Hospital_Ward_ID'] . '&AdmisionWorks=AdmisionWorksThisPage">' . $row['ward_nature'] . '</td> 
                        
                        <td>';
                        echo "
                            <select id='remark".$row['Hospital_Ward_ID']."' onchange='update_remark(".$row['Hospital_Ward_ID'].")'>
                            <option>".$row['remark']."</option>
                            <option value='Yes'>Yes</option>
                            <option value='No'>No</option>
    
                            </select>
                          </td>

                        </tr>";
                    }
                    ?>
                </table>
            </div>


        </fieldset>
    </center>
</center>

<script type="text/javascript">
    $(document).ready(function () {
        $('#patientList').DataTable({
            "bJQueryUI": true

        });
    });

    function update_remark(Hospital_Ward_ID){
        var remark = $("#remark"+Hospital_Ward_ID).val();
        $.ajax({
            type: 'POST',
            url: 'update_clinic_remark.php',
            data: {
                remark:remark,
                Hospital_Ward_ID:Hospital_Ward_ID

            },
            success: function(response) {

                
            }
        });
    }
</script>

<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<?php
include("./includes/footer.php");
?>