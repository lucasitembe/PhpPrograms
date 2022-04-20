<style>
    .otherdoclinks:hover{
        text-decoration:underline;
        color: #000000; 
        cursor:pointer; 
    }
</style>
<?php
include("./includes/header.php");
include("./includes/connection.php");
include './includes/cleaninput.php';

echo "<a href='oncologyworks.php?section=oncologyworks&oncologyworks=oncologyworks' class='art-button-green'>BACK</a>";
session_start();

//        echo '<pre>';
//        print_r($_SESSION['hospitalConsultaioninfo']);exit;
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
    $Patient_Payment_Item_List_ID = 0;
}
if (isset($_SESSION['userinfo'])) {
    // if (isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])) {
    //     if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes') {
    //         header("Location: ./index.php?InvalidPrivilege=yes");
    //     }
    // } else {
    //     header("Location: ./index.php?InvalidPrivilege=yes");
    // }
} else {
    // @session_destroy();
    // header("Location: ../index.php?InvalidPrivilege=yes");
}
    

?>

<br/><br/><br/>
<fieldset>  
            <legend align=center><b>NUCLEAR MEDICINE WORKS</b></legend>
            <center>
        <table width = 60%>

            <tr>
                <td style='text-align: center; height:40px;width:100%'>

                    <a href='searchpatientnuclearmedicinelist.php'>
                    <button style='width: 100%; height:40px'>Patients list</button></a>
                </td>            

            </tr>

            <tr>
               <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php //if($_SESSION['userinfo']['Radiology_Works'] == 'yes'){ ?>
                    <a href='nuclear_medicine_report.php'>
                        <button style='width: 100%; height: 100%'>
                            Reports
                        </button>
                    </a>
                    <?php //}else{ ?>
                        <!-- <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Reports 
                        </button> -->
                    <?php //} ?>
                </td>
                </table>
        </center>
</fieldset><br/>


<?php
    include("./includes/footer.php");
?>
