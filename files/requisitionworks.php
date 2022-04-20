<?php
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(!isset($_SESSION['Pharmacy_ID'])){
        header("Location: ./pharmacysupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
    }

    if(isset($_SESSION['userinfo'])){
        echo "<a href='pharmacyworks.php?section=Pharmacy&PharmacyWorks=PharmacyWorksThisPage' class='art-button-green'>BACK</a>";
    }

    if(isset($_SESSION['Pharmacy_ID'])){
        $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
    }else{
        $Sub_Department_ID = 0;
    }
    
    $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while($data = mysqli_fetch_array($select)){
            $Sub_Department_Name = $data['Sub_Department_Name'];
        }
    }else{
        $Sub_Department_Name = '';
    }
?>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>

<br/><br/>
<fieldset>
    <legend align=right><b>Requisitions ~ <?php if(isset($_SESSION['Pharmacy_ID'])){ echo ucwords(strtolower($Sub_Department_Name)); } ?> </b></legend>
        <center>
            <table width = 60%>
	    <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Pharmacy'] == 'yes'){ ?>
                    <a href='pharmacyrequisition.php?PharmacyRequisition=PharmacyRequisitionThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Requisitions
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Requisitions
                        </button>
                  
                    <?php } ?>
                </td>
            </tr>

            <!-- <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Pharmacy'] == 'yes'){ ?>
                    <a href='#'>
                        <button style='width: 100%; height: 100%'>
                            Requisition Other Items
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Requisition Other Items 
                        </button>
                  
                    <?php } ?>
                </td>
            </tr> -->
        </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>