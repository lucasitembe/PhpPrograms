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


?>
  <a href='oncologyworks.php?section=oncologyworks&oncologyworks=oncologyworks' class='art-button-green'>
       BACK
    </a>
<br/><br/><br/><br/><br/>
<fieldset>  
            <legend align="center"><b>Radiotherapy Treatment</b></legend>
        <center><table width = 60%>

                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                        <a href='radiotherapy_patient_list.php'>
                            <button style='width: 100%; height: 100%'>
                              Radiotherapy Simulation
                            </button>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
              <!--radiotherapy_patient_list-->
              <!--radiotherapy_parameter_patientlist.php-->
              <!--radiation_parameter_calculation.php-->
                        <a href='radiotherapy_parameter_patientlist.php'>
                            <button style='width: 100%; height: 100%'>
                              Radiotherapy Calculation Parameter
                            </button>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
              
                        <!--treatment_delivery.php-->
                        <!--treatment_devery_patientlist.php-->
                        <!--treatment_delivery.php-->
                        <a href='treatment_devery_patientlist.php'>
                            <button style='width: 100%; height: 100%'>
                             Radiotherapy Delivery
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
