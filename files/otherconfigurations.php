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
?>
<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes') {
        ?>
        <a href='setupandconfiguration.php?BackToSetupAndConfiguration=BackTosetupAndConfigurationThisPage' class='art-button-green'>
            BACK
        </a>
    <?php }
} ?>

<br/>
<br/>
<br/>
<br/>
<br/>



<center> 
    <center>
        <fieldset>
            <legend align="center" ><b>OTHER CONFIGURATION</b></legend>
            <table width="100%">
                <tr>
                    <td style='text-align: center; height: 40px; width: 25%;'>
                        <a href='addnewcountry.php?AddNewCountry=AddNewCountryThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Add New Country
                            </button>
                        </a>
                    </td>
                    <td style='text-align: center; height: 40px; width: 25%;'>
                        <a href='#?EditCountry=EditCountryThisForm'> 
                            <button style='width: 100%; height: 100%'>
                                Edit Country
                            </button>
                        </a>
                    </td>
                    <td style='text-align: center; height: 40px; width: 25%;'>
                        <a href='addnewregion.php?AddNewRegion=AddNewRegionThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Add New Region
                            </button>
                        </a>
                    </td>
                    <td style='text-align: center; height: 40px; width: 25%;'>
                        <a href='#?Editregion=EditRegionThisForm'> 
                            <button style='width: 100%; height: 100%'>
                                Edit Region
                            </button>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: center; height: 40px; width: 25%;'>
                        <a href='addnewdistrict.php?AddNewDistrict=AddNewDistrictThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Add New District
                            </button>
                        </a>
                    </td>
                    <td style='text-align: center; height: 40px; width: 25%;'>
                        <a href='#?EditDistrict=EditDistrictThisForm'> 
                            <button style='width: 100%; height: 100%'>
                                Edit District
                            </button>
                        </a>
                    </td> 
                    <td style='text-align: center; height: 40px; width: 25%;'>
                        <a href='addnewsupplier.php?AddNewSupplier=AddNewSupplierThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Add New Supplier
                            </button>
                        </a>
                    </td>
                    <td style='text-align: center; height: 40px; width: 25%;'>
                        <a href='supplierlistedit.php'> 
                            <button style='width: 100%; height: 100%'>
                                Edit Supplier
                            </button>
                        </a>
                    </td> 
                </tr>     
                <tr>
                    <td style='text-align: center; height: 40px; width: 25%;'>
                        <a href='addnewvital.php'>
                            <button style='width: 100%; height: 100%'>
                                Add New Vital
                            </button>
                        </a>
                    </td>
                    <td style='text-align: center; height: 40px; width: 25%;'>
                        <a href='editvital.php'> 
                            <button style='width: 100%; height: 100%'>
                                Edit Vital
                            </button>
                        </a>
                    </td>
                    <td style='text-align: center; height: 40px; width: 25%;'>
                        <a href='addcurrency.php'>
                            <button style='width: 100%; height: 100%'>
                                Add New Currency
                            </button>
                        </a>
                    </td>
                    <td style='text-align: center; height: 40px; width: 25%;'>
                        <a href='religion.php'> 
                            <button style='width: 100%; height: 100%'>
                                Add Religion And Denomination
                            </button>
                        </a>
                    </td> 
                    <td class="hide" style='text-align: center; height: 40px; width: 25%;'>
                        <a href='editvital.php'> 
                            <button style='width: 100%; height: 100%'>
                                Edit Currency
                            </button>
                        </a>
                    </td> 
                </tr>
                <tr>
                    <td style='text-align: center; height: 40px; width: 25%;'>
                        <a href='setinitialregion.php?SetInitialRegion=SetInitialRegionThisForm'> 
                            <button style='width: 100%; height: 100%'>
                                Set Default Region
                            </button>
                        </a>
                    </td> 
                    <td style='text-align: center; height: 40px; width: 25%;'>
                        <a href='addnewrefhosp.php'>
                            <button style='width: 100%; height: 100%'>
                                Add New Referral Hospital
                            </button>
                        </a>
                    </td>
                    <td style='text-align: center; height: 40px; width: 25%;'>
                        <a href='editrefhostp.php'> 
                            <button style='width: 100%; height: 100%'>
                                Edit Referral Hospital
                            </button>
                        </a>
                    </td>
                    <td style='text-align: center; height: 40px; width: 25%;'>
                        <a href='to_come_again_reason.php'> 
                            <button style='width: 100%; height: 100%'>
                                Add To Come Again Reason
                            </button>
                        </a>
                    </td>                              
                </tr>
                <tr>
                    <td style='text-align: center; height: 40px; width: 25%;'>
                        <a href='add_rank.php'> 
                            <button style='width: 100%; height: 100%'>
                                Add Rank
                            </button>
                        </a>
                    </td> 
                    <td style='text-align: center; height: 40px; width: 25%;'>
                        <a href='add_unit.php'> 
                            <button style='width: 100%; height: 100%'>
                                Add Unit
                            </button>
                        </a>
                    </td> 
                </tr>
            </table>
        </fieldset>  
    </center>
    <?php
    include("./includes/footer.php");
    ?>