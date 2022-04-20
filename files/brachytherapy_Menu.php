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
            <legend align="center"><b>Brachytherapy Treatment</b></legend>
        <center><table width = 60%>

                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                        <a href='brachytherapy_patient_list.php'>
                            <button style='width: 100%; height: 100%'>
                              Insertion Prescription
                            </button>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                        <a href='brachytherapy_parameter_patientlist.php'>
                            <button style='width: 100%; height: 100%'>
                              Brachytherapy Calculation Parameter
                            </button>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                        <a href='Brachytherapy_treatmeny_dilivery_list.php'>
                            <button style='width: 100%; height: 100%'>
                             Brachytherapy Delivery
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
