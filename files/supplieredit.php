<?php
include("./includes/connection.php");
include("./includes/header.php");
// include_once("./functions/items.php");
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

// include '../account/includes/LanguagesArray.php';
$InputError = 0;
// include '../account/includes/CountriesArray.php';
?>

<?php
if (isset($_SESSION['userinfo'])) {
    ?>
    <a href='supplierlistedit.php?OtherConfigurations=OtherConfigurationsThisForm' class='art-button-green'>
        BACK
    </a>
<?php } ?>

<br/><br/>
<?php
// $acc1 = file_get_contents("http://localhost/Final_One2/gaccounting/Api/accounts");
// $acc2 = json_decode($acc1);
//echo "<pre/>";
//print_r($acc2);
//exit();
if (isset($_GET['Supplier_ID'])) {
    $Supplier_ID = $_GET['Supplier_ID'];
    $qry = mysqli_query($conn,"SELECT * FROM tbl_supplier WHERE Supplier_ID='$Supplier_ID'") or die(mysqli_error($conn));
    $data = mysqli_fetch_array($qry);
    $Supplier_Name = $data['Supplier_Name'];
    $Supplier_Address = $data['Postal_Address'];
    $Mobile_Number = $data['Mobile_Number'];
    $Supplier_Email = $data['Supplier_Email'];
    $Contact_person = $data['Contact_person'];
}
?>

<center>
    <table width=100%><tr><td>
        <center>
            <fieldset>
                <legend align="center" ><b>EDIT NEW SUPPLIER</b></b></legend>
                <table width=100%>
                    <form action='#' method='post' name='myForm' id='myForm'>
                        <tr>
                            <td style='text-align: right;' width=20%>Supplier Name</b></td>
                            <td width='30%'>
                                <input type='text' name='Supplier_Name' id='Supplier_Name' autocomplete='off' value='<?= $Supplier_Name ?>'>
                            </td>
                            <?php if (isset($_SESSION['configData']['IntegratedToAccounting']) && strtolower($_SESSION['configData']['IntegratedToAccounting']) == 'yes') { ?>
                                <td width=20% style='text-align: right;'>Account Name</td>
                                <td width=30%>
                                    <select required="required" name='account_code' width=20% id='account_code'  >                              
                                        <option selected="selected" disabled="disabled" value="">--Select account--</option>
                                        <?php foreach ($acc2 as $acc) { ?>
                                            <option value="<?php echo $acc->acc_code; ?>"><?php echo $acc->acc_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td style='text-align: right;'>Contact person name</b></td>
                            <td>
                                <input type='text' name='Contact_person' size=70 id='Contact_person' autocomplete='off' value="<?= $Contact_person ?>">
                            </td>

                            <td style='text-align: right;'>Contact person mobile Number</b></td>
                            <td>
                                <input type='text' name='Mobile_Number' size=70 id='Mobile_Number' autocomplete='off' value="<?= $Mobile_Number ?>">
                            </td>
                        </tr> 
                        <tr>
                            <td style='text-align: right;'>Contact person Email</b></td>
                            <td>
                                <input type='text' name='Supplier_Email' required='required' autocomplete='off' size=70 id='Supplier_Email' value="<?= $Supplier_Email ?>">
                            </td>

                            <td style='text-align: right;'>Supplier Address</td>
                            <td>
                                <textarea name='Supplier_Address' size=70 id='Supplier_Address' autocomplete='off'   cols='10' rows=2><?= $Supplier_Address ?></textarea>                   
                            </td>
                        </tr> 
                        <tr>
                            <td colspan="3"style='text-align: right;'>
                                <input type='hidden' name='submittedAddNewSupplierForm' value='true'/> </td>
                            <td style='text-align: right;'>
                                <input type='submit' name='submit' id='submit' value='   UPDATE   ' class='art-button-green'>      
                            </td>
                        </tr>
                    </form></table>
            </fieldset>
        </center></td></tr></table>
</center>


<?php
if (isset($_POST['submittedAddNewSupplierForm'])) {
    $has_error = FALSE;
    $Supplier_Name = mysqli_real_escape_string($conn,$_POST['Supplier_Name']);
    $Supplier_Address = mysqli_real_escape_string($conn,$_POST['Supplier_Address']);
    $Mobile_Number = mysqli_real_escape_string($conn,$_POST['Mobile_Number']);
    $Supplier_Email = mysqli_real_escape_string($conn,$_POST['Supplier_Email']);
    $Contact_person = mysqli_real_escape_string($conn,$_POST['Contact_person']);
    // Start_Transaction();
    $sql = "UPDATE  tbl_supplier SET Supplier_Name='$Supplier_Name',Postal_Address='$Supplier_Address',Mobile_Number='$Mobile_Number',Supplier_Email='$Supplier_Email',Contact_person='$Contact_person' WHERE Supplier_ID='$Supplier_ID'";
    $query = mysqli_query($conn,$sql) or die(mysqli_error($conn));
    if (!$query) {
        $has_error = TRUE;
        $error = '1062yes';
        if (mysql_errno() . "yes" == $error) {
            ?>

            <script type='text/javascript'>
                alert('SUPPLIER NAME ALREADY EXISTS! \nTRY ANOTHER NAME');
            </script>

            <?php
        }
    } else {
        
//         if (isset($_SESSION['configData']['IntegratedToAccounting']) && strtolower($_SESSION['configData']['IntegratedToAccounting']) == 'yes') {
//             $account_name = mysqli_real_escape_string($conn,$_POST['account_code']);
//             $data = array(
//                 'Supplier_Name' => $Supplier_Name,
//                 'account_name' => $account_name
//             );
//             $endata = json_encode($data);
//             $opts = array('http' =>
//                 array(
//                     'method' => 'GET',
//                     'header' => 'Content-type: application/json',
//                     'content' => $endata
//                 )
//             );

//             $context = stream_context_create($opts);
//             $acc = file_get_contents("http://127.0.0.1/Final_One/gaccounting/Api/ledgerFromEhms", false, $context);
//             if ($acc != "success") {
//                 $has_error = TRUE;
//             }
// //        echo "<script>alert('" . $acc . "')</script>";
//         }
        if ($has_error) {
            // Rollback_Transaction();
        } else {
            // Commit_Transaction();
            echo "<script type='text/javascript'>
                alert('SUPPLIER UPDATED SUCCESSFULLY');
                document.location.href='supplieredit.php?Supplier_ID=" . $Supplier_ID . "';
            </script>";
        }
    }
}
?>
<?php
include("./includes/footer.php");
