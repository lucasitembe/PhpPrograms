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


<br/><br/><br/><br/><br/>
<fieldset>  
            <legend align="center"><b>RADIOTHERAPY & CANCER TREATMENT </b></legend>
        <center><table width = 60%>
				<tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href='Cancer_patient_list.php'>
                        <button style='width: 100%; height: 100%'>
                           Tumorboard & Cancer  Registration 
                        </button>
                    </a>
  
                </td>
				</tr>
               <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
              
                        <a href='cancer_setup.php?Registration=Registration'>
                            <button style='width: 100%; height: 100%'>
                              Chemotherapy Setup
                            </button>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
              
                        <a href='#'>
                            <button style='width: 100%; height: 100%'>
                              Cancer Reports
                            </button>
                        </a>
                    </td>
                </tr>
                </table>
        </center>
</fieldset><br/>


<?php
    include("./includes/footer.php");
?>