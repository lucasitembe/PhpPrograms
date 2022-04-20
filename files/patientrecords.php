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
    <a href='./index.php?Reception=ReceptionThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./patientrecords.php?PatientFile=PatientFileThisPage";
    }
</script>

               
<br/><br/>                
<br/><br/>                
<br/><br/>                
<fieldset>
    <legend align=center><b>PATIENT RECORDS WORKS</b></legend>
        <center>
			
        <table width = 90%>
            <tr>
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                <?php if(isset($_SESSION['userinfo']['Patient_Record_Works'])){ ?>
                    <?php if($_SESSION['userinfo']['Patient_Record_Works'] == 'yes'){ ?>
                    <a href='Patientfile_Record.php?section=Patient&DialysisWorks=DialysisWorksThisPage'>
                        <button style='width: 80%; height: 100%'>
                            Patient File
                        </button>
                    </a>
                    <?php }else{ ?> 
                        <button style='width: 80%; height: 100%' onclick="return access_Denied();">
                            Patient File
                        </button> 
                <?php } }else{ ?> 
                        <button style='width: 80%; height: 100%' onclick="return access_Denied();">
                            Patient File 
                    </button> 
                <?php } ?>
                </td>
            </tr>
            <tr>
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                <?php if(isset($_SESSION['userinfo']['Patient_Record_Works'])){ ?>
                    <?php if($_SESSION['userinfo']['Patient_Record_Works'] == 'yes'){ ?>
                    <a href='patientfile.php?section=Patient&DialysisWorks=DialysisWorksThisPage'>
                        <button style='width: 80%; height: 100%'>
                            Attachment
                        </button>
                    </a>
                    <?php }else{ ?> 
                        <button style='width: 80%; height: 100%' onclick="return access_Denied();">
                            Attachment
                        </button> 
                <?php } }else{ ?> 
                    <button style='width: 80%; height: 100%' onclick="return access_Denied();">
                        Attachment
                    </button> 
                <?php } ?>
                </td>
            </tr>
            <tr>
                <td style='text-align: center;color:  #ccc;border: 1px solid' height='40px' width='33%'> 
                <?php if(isset($_SESSION['userinfo']['Patient_Record_Works'])){ ?>
                    <?php if($_SESSION['userinfo']['Patient_Record_Works'] == 'yes'){ ?>
                    <a href='patientmerge.php?section=PatientMerge&PatientMerge=PatientMerge'>
                        <button style='width: 80%; height: 100%'>
                            Patient Merge
                        </button>
                    </a>
                    <?php }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Patient Merge
                        </button> 
                <?php } }else{ ?> 
                    <button style='width: 80%; height: 100%' onclick="return access_Denied();">
                       Patient Merge
                    </button> 
                <?php } ?>
                </td>
            </tr>
        </table></center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>