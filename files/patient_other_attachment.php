<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Patient_Record_Works'])){
	    if($_SESSION['userinfo']['Patient_Record_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	    @session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if (isset($_GET['Registration_ID'])) {
        $Registration_ID = $_GET['Registration_ID'];
      }
    

    if (isset($_GET['Registration_ID'])) {
        $select_patien_details = mysqli_query($conn,"SELECT pr.Sponsor_ID,Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
                  FROM
                      tbl_patient_registration pr,
                      tbl_sponsor sp
                  WHERE
                      pr.Registration_ID = '" . $Registration_ID . "' AND
                      sp.Sponsor_ID = pr.Sponsor_ID
                      ") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select_patien_details);
        if ($no > 0) {
          while ($row = mysqli_fetch_array($select_patien_details)) {
            $Member_Number = $row['Member_Number'];
            $Patient_Name = $row['Patient_Name'];
            $Registration_ID = $row['Registration_ID'];
            $Gender = $row['Gender'];
            $Guarantor_Name = $row['Guarantor_Name'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $DOB = $row['Date_Of_Birth'];
          }
        } else {
          $Guarantor_Name = '';
          $Member_Number = '';
          $Patient_Name = '';
          $Gender = '';
          $Registration_ID = 0;
        }
      }

?>

<a href="Patientfile_Record_Detail.php?Registration_ID=<?= $Registration_ID; ?>&Patient_Payment_ID=&Patient_Payment_Item_List_ID=&PatientFile=PatientFileThisForm&position=out&this_page_from=patient_record
" class="art-button-green">BACK</a>
<br />
<br />


<style media="screen">
  table tr,td{
    border:none !important;
  }

  .input{
    width:30% !important;
  }

  .label-input{
      width: 0% !important ;
      text-align: right !important;

  }
</style>
<br>
    <fieldset style='background: #006400 !important;color: white'>
                                <center>
                                    <legend align="right" style="background-color:silver;color:black;padding:5px;"> OTHER ATTACHMENT PATIENT RECORDS</legend>

                                    <!--<b>PATIENT RECORDS</b>-->
                                    <p>Patient File</p>
                                    <p><?php echo ucwords(strtolower($Patient_Name)) . ", " . $Gender . ", " . $Guarantor_Name . ", (" . $age . ")"; ?></p>
                                </center>
                            </fieldset>
                            <fieldset style='background: white; color:black'>
                                <div id="radPatTest" style="width:100%;height:400px;oveflow-y:scroll;overflow-x:hidden">
                                    <center>
    <table width = '100%'>
        <tr id='thead'>
        <!--<tr><td colspan="9"><hr></td></tr>-->
        <td style="width:5%;"><center><b>SN</b></center></td>
        <td><center><b>DATE</b></center></td>
        <td><center><b>ATTACHMENT TYPE</b></center></td>
        <td><center><b>ATTACHMENT DESCRIPTIONS</b></center></td>
        <td><center><b>ATTACHED BY</b></center></td>
        <!--<tr><td colspan="9"><hr></td></tr>-->
        </tr>
        <?php

    $query = mysqli_query($conn,"SELECT Check_In_Type,Attachment_Date, Attachment_Url,Description, em.Employee_Name from tbl_attachment a, tbl_employee em where Registration_ID='$Registration_ID' AND em.Employee_ID = a.Employee_ID");
      if($query){
                $attachment_url="";
                $count = 1;
                while ($attach = mysqli_fetch_array($query)) {
                    $Attachment_Date = $attach['Attachment_Date'];
                    $Check_In_Type = $attach['Check_In_Type'];
                    $Description = $attach['Description'];
                    $Employee_Name = $attach['Employee_Name'];
                    $Attachment_Url = $attach['Attachment_Url'];

                    if ($attach['Attachment_Url'] != '') {
                            echo "<tr>
                                      <td style='text-align: center;'><a href='patient_attachments/" . $Attachment_Url . "' class='fancybox2' rel='gallery' target='_blank' title='" . $row['Product_Name'] . "'>".$count."</a></td>
                                      <td style='text-align: center;'><a href='patient_attachments/" . $Attachment_Url . "' class='fancybox2' rel='gallery' target='_blank' title='" . $row['Product_Name'] . "'>".$Attachment_Date."</a></td>
                                      <td style='text-align: center;'><a href='patient_attachments/" . $Attachment_Url . "' class='fancybox2' rel='gallery' target='_blank' title='" . $row['Product_Name'] . "'>".$Check_In_Type."</a></td>
                                      <td style='text-align: center;'><a href='patient_attachments/" . $Attachment_Url . "' class='fancybox2' rel='gallery' target='_blank' title='" . $row['Product_Name'] . "'>".$Description."</a></td>
                                      <td style='text-align: center;'><a href='patient_attachments/" . $Attachment_Url . "' class='fancybox2' rel='gallery' target='_blank' title='" . $row['Product_Name'] . "'>".$Employee_Name."</a></td>
                                  </tr>";                            
                                        
                            $attachment_url="<object data='patient_attachments/$Attachment_Url' width='100%' height='1600'>
                                  alt :  $image 
                                </object>";
                      $count++;                    
                    }else{
                        echo "<tr>
                        <td colspan='5'><span style='font-size: 24px; color: red; text-align: center;'>NO ATTACHMENT FOR THIS PATIENT</span></td></tr>";
                    }

                }
        }else{
          echo "<tr>
                    <td colspan='5'><span style='font-size: 24px; color: red; text-align: center;'>NO ATTACHMENT FOR THIS PATIENT</span></td></tr>";
        }
?>