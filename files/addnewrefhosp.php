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
<a href="otherconfigurations.php?OtherConfigurations=OtherConfigurationsThisForm" class="art-button-green">BACK</a>
<br/><br/>

<?php
if (isset($_POST['submittedAddNewRefForm'])) {
    $Vital_Name = mysqli_real_escape_string($conn,$_POST['ref_hosp_name']);

    $sql = "insert into tbl_referral_hosp(ref_hosp_name)
                    values('$Vital_Name')";

    if (!mysqli_query($conn,$sql)) {
        $error = '1062yes';
        if (mysql_errno() . "yes" == $error) {
            ?>

            <script type='text/javascript'>
                alert('REFERRAL HOSPITAL NAME ALREADY EXISTS! \nTRY ANOTHER NAME');
            </script>

            <?php
        }
    } else {
        echo "<script type='text/javascript'>
			    alert('REFERRAL HOSPITAL NAME ADDED SUCCESSFUL');
			    </script>";
    }
}

if (isset($_POST['non_referral_submit'])) {
    $non_ref_hosp_name = mysqli_real_escape_string($conn,$_POST['non_ref_hosp_name']);

    $sql = "insert into tbl_referred_from_hospital(hospital_name)
                    values('$non_ref_hosp_name')";

    if (!mysqli_query($conn,$sql)) {
        $error = '1062yes';
        if (mysql_errno() . "yes" == $error) {
            ?>

            <script type='text/javascript'>
                alert('NON REFERRAL HOSPITAL NAME ALREADY EXISTS! \nTRY ANOTHER NAME');
            </script>

            <?php
        }
    } else {
        echo "<script type='text/javascript'>
			    alert('NON REFERRAL HOSPITAL NAME ADDED SUCCESSFUL');
			    </script>";
    }
}
?>
<br/><br/><br/><br/><br/>

<center>
    <table width=80%><tr><td>
        <center>
            <fieldset>
                <legend align="center" ><b>ADD NEW NON REFERRALS HOSPITAL</b></legend>
                <table width=80%>
                    <form action='#' method='post' name='myForm' id='myForm'>

                        <tr>
                            <td width=40% style='text-align: right;'><b>NON REFERRAL HOSPITAL NAME</b></td>
                            <td width=80%><input type='text' name='non_ref_hosp_name' required='required' id='non_ref_hosp_name' placeholder='Enter Non Referral Hospital Name'></td>
                        </tr>                                  
                        <tr>
                            <td colspan=2 style='text-align: right;'>
                                <input type='submit' name='non_referral_submit' id='submit' value='   SAVE   ' class='art-button-green'>
                                <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                            </td>
                        </tr>
                    </form></table>
            </fieldset>           
            <fieldset>
                <legend align="center" ><b>ADD NEW REFERRALS HOSPITAL</b></legend>
                <table width=80%>
                    <form action='#' method='post' name='myForm' id='myForm'>

                        <tr>
                            <td width=40% style='text-align: right;'><b>REFERRAL HOSPITAL NAME</b></td>
                            <td width=80%><input type='text' name='ref_hosp_name' required='required' id='ref_hosp_name' placeholder='Enter Referral Hospital Name'></td>
                        </tr>                                  
                        <tr>
                            <td colspan=2 style='text-align: right;'>
                                <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                                <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                                <input type='hidden' name='submittedAddNewRefForm' value='true'/> 
                            </td>
                        </tr>
                    </form></table>
            </fieldset>
        </center></td></tr></table>
</center>


<?php
include("./includes/footer.php");
?>