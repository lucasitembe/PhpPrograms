<?php
    @session_start();
    
    //get employee id
    
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
    
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }
?>
<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
    <link rel="shortcut icon" href="images/icon.png">
    
    <link rel="stylesheet" href="style.css" media="screen">
        
    <!--[if lte IE 7]><link rel="stylesheet" href="style.ie7.css" media="screen" /><![endif]-->
    
    <link rel="stylesheet" href="style.responsive.css" media="all">
 

    <script src="jquery.js"></script>
    <script src="script.js"></script>
    <script src="script.responsive.js"></script>


<style>.art-content .art-postcontent-0 .layout-item-0 { margin-bottom: 10px;  }
.art-content .art-postcontent-0 .layout-item-1 { padding-right: 10px;padding-left: 10px;  }
.ie7 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
.ie6 .art-post .art-layout-cell {border:none !important; padding:0 !important; }

</style>
<?php
    include("./includes/connection.php");
    if(isset($_GET['Temp_Registration_ID'])){
        $Temp_Registration_ID = $_GET['Temp_Registration_ID'];
    }else{
        $Temp_Registration_ID = 0;
    }
    
    if($Temp_Registration_ID != 0){
        
        $select_details = mysqli_query($conn,"select pr.Patient_Name,ppc.Payment_Date_And_Time from
                                        tbl_patient_payments_cache ppc, tbl_patient_registration pr where
                                            pr.registration_id = ppc.registration_id and
                                                pr.registration_id = '$Temp_Registration_ID'") or die(mysqli_error($conn));
            
        while($row = mysqli_fetch_array($select_details)){
            $Patient_Name = $row['Patient_Name'];
            $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
        }        
        ?><br/>
        <center><span><h3>System detects that, there is another pending Patient you were working with</h3></span></center></span>
        <center>
            <table width=60%>
                <tr><td style='text-align: right;'>Patient Name : </td><td style='text-align: left;'><?php echo $Patient_Name; ?></td></tr>
                <tr><td style='text-align: right;'>Registration Number : </td><td style='text-align: left;'><?php echo $Temp_Registration_ID; ?></td></tr>
                <tr><td style='text-align: right;'>Attempt Date & Time : </td><td style='text-align: left;'><?php echo $Payment_Date_And_Time; ?></td></tr>
            </table>
            <table width=80>
                <tr>
                    <td style='text-align: center;'>
                        <a href='patientbillingprepare.php?Registration_ID=<?php echo $Temp_Registration_ID; ?>&NR=True&PatientBillingPrepare=PatientBillingPrepareThisForm' class='art-button-green' target='_Parent'>Back to previous patient</a>
                    </td>
                    <td style='text-align: center;'>
                        <a href='Patient_Billing_Prepare_Delete.php?Temp_Registration_ID=<?php echo $Temp_Registration_ID; ?>&Employee_ID=<?php echo $Employee_ID; ?>&Registration_ID=<?php echo $Registration_ID; ?>' target='_Parent' class='art-button-green'>Continue with selected patient</a>
                    </td>
                    <!--<td style='text-align: center;'><a href='' class='art-button-green'>Continue with previous patient</a></td> -->
                </tr>
            </table>
        
        </center>
        <?php
    }
    //echo 'Registration ID'.$Temp_Registration_ID;
    
    
?>