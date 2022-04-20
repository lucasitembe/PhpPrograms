<?php
include("./includes/connection.php");
include("./includes/header.php");

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Setup_And_Configuration'])) {
        if ($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>

<script type="text/javascript" language="javascript">
    function getDistricts(Region_Name) {
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }

        mm.onreadystatechange = AJAXP; //specify name of function that will handle server response....
        mm.open('GET', 'GetDistricts.php?Region_Name=' + Region_Name, true);
        mm.send();
    }
    function AJAXP() {
        var data = mm.responseText;
        document.getElementById('District').innerHTML = data;
    }

//    function to verify NHIF STATUS
    function nhifVerify() {
        //code
    }
</script>
<a href='sponsorpage.php?SponsorConfiguration=SponsorConfigurationThisPage' class='art-button-green'>BACK</a>
<br/><br/>

<?php
if (isset($_POST['submittedAddNewSponsorForm'])) {
    $Customer_Name = mysqli_real_escape_string($conn,$_POST['Customer_Name']);
    //$Postal_Address = mysqli_real_escape_string($conn,$_POST['Postal_Address']);
    $region = mysqli_real_escape_string($conn,$_POST['region']);
    $District = mysqli_real_escape_string($conn,$_POST['District']);
    $Phone_Number = mysqli_real_escape_string($conn,$_POST['Phone_Number']);
    $Email_Address = mysqli_real_escape_string($conn,$_POST['Email_Address']);

    $sql = mysqli_query($conn,"insert into tbl_patient_registration(
                            Patient_Name,phone_number,Country,region,district
                            ,Email_Address,registration_type)
                            values('$Customer_Name','$Phone_Number','Tanzania','$region',
                                '$District','$Email_Address','customer')") or die(mysqli_error($conn));
    if ($sql) {
        echo "
            <script type='text/javascript'>
                alert('CUSTOMER REGISTERED SUCCESSIFULLY');
            </script>";
    }else{
         echo "
            <script type='text/javascript'>
                alert('REGISTRATIION FAILS');
            </script>";
    }
}
?>

<br/><br/><br/><br/><br/><br/>

<center>
    <table width=60%><tr><td>
        <center>
            <fieldset>
                <legend align="center" ><b>ADD NEW CUSTOMER</b></legend>
                <table>
                    <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">

                        <tr>
                            <td width=40% style='text-align: right;'><b>Customer Name</b></td>
                            <td width=60%><input type='text' name='Customer_Name' required='required' size=70 id='Customer_Name' placeholder='Enter Customer Name' autocomplete='off' required></td>
                        </tr>
                        <tr>
                            <td style='text-align: right;'><b>Phone Number</b></td>
                            <td>
                                <input type='text' name='Phone_Number' id='Phone_Number' autocomplete='off' placeholder='Enter Phone Number' required>
                            </td>
                        </tr>
                        <tr>
                            <td style='text-align: right;'><b>Email Address</b></td>
                            <td>
                                <input type='email' name='Email_Address' id='Email_Address' autocomplete='off' placeholder='Enter Email Address'>
                            </td>
                        </tr>
                        <!--tr>
                            <td width=40% style='text-align: right;'><b>Postal Address</b></td>
                            <td width=60%><textarea name='Postal_Address' id='Postal_Address' cols=20 rows=1 placeholder='Enter Postal Address' autocomplete='off'></textarea></td>
                        </tr-->
                        <tr>
                            <td style='text-align: right;'><b>Region</b></td>
                            <td>
                                <select name='region' id='region' onchange='getDistricts(this.value)'>
                                    <option selected='selected' value='Dar es salaam'>Dar es salaam</option>
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
                            <td style='text-align: right;'>
                                <b>District</b>
                            </td>
                            <td>
                                <select name='District' id='District'>
                                    <option selected='selected'>Kinondoni</option>
                                    <option>Ilala</option>
                                    <option>Temeke</option>  
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan=2 style='text-align: right;'>
                                <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                                <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                                <input type='hidden' name='submittedAddNewSponsorForm' value='true'/> 
                            </td>
                        </tr>
                    </form></table>
            </fieldset>
        </center></td></tr></table>
</center>
<br/><br/><br/><br/><br/><br/>
<?php
include("./includes/footer.php");
