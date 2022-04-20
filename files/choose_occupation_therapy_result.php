<?php 
    $indexPage = true;
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Patient_Record_Works'] == 'yes'){ 
?>
<?php 
 } }

 $Patient_id = $_GET['Registration_ID'];
 $Patient_name = $_GET['Patient_name'];

?>
    <a href='./all_patient_file_link_station.php?Registration_ID=<?php echo $Patient_id ?>&&Reception=ReceptionThisPage' class='art-button-green'>
        BACK
    </a>

<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>

               
<br/><br/>                
<br/><br/>                
<br/><br/>                
<fieldset>
    <legend align=center><b>OCCUPATION THERAPY WORKS</b></legend>
        <center>
			
        <table width = 90%>
            <tr>
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                <a href="occupation_therapy_iframe.php?Registration_ID=<?= $Patient_id ?>&Patient_name=<?php echo $Patient_name?>">
                        <button style='width: 80%; height: 100%'>
                            ADULT ASSESMENT RESULT
                        </button> 
                </a>
                </td>
            </tr>
            <tr>
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                <a href="pediatric_occupation_therapy_iframe.php?Registration_ID=<?= $Patient_id ?>&Patient_name=<?php echo $Patient_name?>">
                        <button style='width: 80%; height: 100%'>
                        PEDIATRIC ASSEEMENT RESULT
                        </button>
                    </a> 
                </td>
            </tr>
        </table></center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>