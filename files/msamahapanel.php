<?php
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes"); 
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Msamaha_Works'])){
	    if($_SESSION['userinfo']['Msamaha_Works'] != 'yes'){
		    header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	    @session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
    <a href='index.php?RegisterPatient=RegisterPatientThisPage' class='art-button-green'>
       BACK 
    </a>

<script type='text/javascript'>
    function access_Denied(){ 
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>

<br/><br/><br/><br/><br/>
<fieldset>  
            <legend align="center"><b>COST SHARING WORKS</b></legend>
        <center><table width = 60%>
        <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Msamaha_Works'] == 'yes'){ ?>
                    <a href='msamaha.php?DischargeDeadBody=DeliveryPage'>
                        <button style='width: 100%; height: 100%'>
                            Cost Sharing Patients
                        </button>
                    </a>
                    <?php }
                    else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Msamaha
                        </button>
                  
                    <?php } ?>
                </td>
				</tr>
                <tr>
                <?php 
                    $numberofexemption= 0;
                    $select_Nurse_Exemption_ID = mysqli_query($conn, "SELECT COUNT(Nurse_Exemption_ID) as exemptionnumber from tbl_nurse_exemption_form nef, tbl_patient_registration pr, tbl_sponsor sp  WHERE pr.Registration_ID=nef.Registration_ID AND Nurse_Exemption_ID NOT IN (SELECT Nurse_Exemption_ID FROM tbl_temporary_exemption_form) AND pr.sponsor_id = sp.sponsor_id AND payment_method='cash' GROUP BY nef.Registration_ID") or die(mysqli_error($conn));
                    
                    while($countID = mysqli_fetch_assoc($select_Nurse_Exemption_ID)){
                        $numberofexemption += $countID['exemptionnumber'];
                    }
                ?>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                        <a href="temporaryExemptionList.php">
                            <button style='width: 100%; height: 100%'>
                                Exemption Forms<span class="badge " style="background-color: red;"><?php echo $numberofexemption; ?></span>
                            </button>
                        </a>
                    </td>
                </tr>

			   <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                        <?php if($_SESSION['userinfo']['Msamaha_Works'] == 'yes'){ ?>
                        <a href='credittransactions.php?Section=Msamaha&CreditTransactions=CreditTransactionsThisForm'>
                            <button style='width: 100%; height: 100%'>
                                Credit Transaction / Approval
                            </button>
                        </a>
                        <?php }else{ ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Credit Transaction / Approval
                            </button>
                        <?php } ?>
                    </td>
                </tr>
               
                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                        <?php if($_SESSION['userinfo']['Msamaha_Works'] == 'yes'){ ?>
                        <a href='msamahareports.php?MsamahaReports=MsamahaReportsThisForm'>
                            <button style='width: 100%; height: 100%'>
                             Reports
                            </button>
                        </a>
                        <?php }else{ ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                               Reports
                            </button>
                      
                        <?php } ?> 
                    </td> 
                </tr>
                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                        <?php if($_SESSION['userinfo']['Msamaha_Works'] == 'yes'){ ?>
                        <a href='free_items_setup.php'>
                            <button style='width: 100%; height: 100%'>
                             Free Items Configuration
                            </button>
                        </a>
                        <?php }else{ ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Free Items configuration
                            </button>
                      
                        <?php } ?>
                    </td>
                </tr>
		  </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php"); 
?>