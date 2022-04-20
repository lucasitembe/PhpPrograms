<?php
include("./includes/header.php");
include("./includes/connection.php");

if(isset($_GET['Registration_ID'])){
   $Registration_ID=$_GET['Registration_ID'];
}else{
   $Registration_ID=""; 
}
$Patient_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Patient_Name FROM tbl_patient_registration WHERE Registration_ID='$Registration_ID'"))['Patient_Name'];
?>
<a href="all_patient_file_link_station.php?Registration_ID=<?php echo $Registration_ID; ?>&section=Patient&PatientFile=PatientFileThisForm&fromPatientFile=true&this_page_from=patient_record" class="art-button-green">BACK</a>
<input type="text" style="display: none" id="Patient_Name" value="<?php echo $Patient_Name?>">
<br/><br/><br/><br/><br/>
<fieldset>  
            <legend align="center"><b>CANCER PATIENT RECORD</b></legend>
        <center><table width = 60%>
                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                      
                    <a href='cancer_registration_record.php?Registration_ID=<?php echo $Registration_ID; ?>&section=Patient&PatientFile=PatientFileThisForm&fromPatientFile=true&this_page_from=patient_record'>
                            <button style='width: 100%; height: 100%'>
                               PATIENT FILE RADIOTHERAPY
                            </button>
                        </a>
                    </td>
                    
                </tr>
                
                <tr>
                   <td style='text-align: center; height: 40px; width: 33%;'>
              
                        <a href='chemotherapy_patient_file.php?Registration_ID=<?php echo $Registration_ID; ?>&registration_data=registration_data'>
                            <button style='width: 100%; height: 100%'>
                            PATIENT FILE CHEMOTHERAPY
                            </button>
                        </a>
                    </td>
                </tr>
		  </table>
        </center>
        
</fieldset><br/>
<!-- <input type="text" id="patient_registration_detail">
        <input type="text" id="patient_patient_registration"> -->
<script>
      function cancer_registration_previous( Registration_ID){
        var Patient_Name = $("#Patient_Name").val();
        $.ajax({
            url:'Ajax_get_previous_cancer_data.php', 
            type:'POST', 
            data:{Registration_ID:Registration_ID,registration_data:''},
            success:function(responce){
                // console.log(responce);
                $("#patient_registration_detail").dialog({
                    title: 'PREVIOUS CANCER  REGISTRATION DETAILS FOR '+Registration_ID+"  --- "+Patient_Name,
                    width: '70%',
                    height: 700,
                    modal: true,
                });
                $("#patient_registration_detail").html(responce);
                
            }
        });
    }
    
    function preview_patient_cancer_registration_form(cancer_id, Registration_ID){
      
        var Patient_Name = $("#Patient_Name").val();
        $.ajax({
            url:'Ajax_get_previous_cancer_data.php', 
            type:'POST',
            data:{cancer_id:cancer_id,Registration_ID:Registration_ID,view_registration_data:''},
            success:function(responce){
                // console.log(responce);
                $("#patient_patient_registration").dialog({
                    title: 'CANCER PATIENT REGISTRATION DETAILS FOR -------- '+Registration_ID+"  --- "+Patient_Name,
                    width: '90%',
                    height: 900,
                    modal: true,
                });
                $("#patient_patient_registration").html(responce);
                
            }
        });
    }
</script>
<?php
    include("./includes/footer.php");
?>