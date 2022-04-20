<?php 
require_once('includes/connection.php');
if(isset($_POST['protocal_type_details'])){
    $Patient_protocal_details_ID =$_POST['Patient_protocal_details_ID'];
    $Registration_ID =$_POST['Registration_ID'];   
        $name_cancer=$_POST['disease_name'];
               include('chemotherapy_patient_protocal_inclusion.php');
        ?>
    <table class="table">
          
        <tr> 
            <td>
                <input type="text" id="cyclenumber" placeholder="Enter Cycle number" class="form-control" required="required">
            </td>
            <td>
                <textarea name="" id="administer_comment"  rows="1" required class="form-control" placeholder="Day Number"></textarea>
            </td>
            <td>
                <button class="btn btn-primary btn-sm"  name='adminster_protocal'  onclick="save_administer_protocal( <?php echo $Patient_protocal_details_ID;?>)">PRESCRIBE </button>
            </td>
            
        </tr>
    </table>
    <script>
    $(document).ready(function(){
        calculateBSA()
    })

    function calculateBSA(){
        var height = $("#heightboo").val();
        var weight = $("#Weightboo").val();
        var productbsa = (height * weight)/3600
        var CalBSA = Math.sqrt(productbsa);
        var calculatesba = CalBSA.toFixed(1);
        $("#bodysurface").val(calculatesba);
        
    }
    function calculate_chemo_dose(){
        var get_names_array = '<?=$array_name?>';
        var get_chemo_array = '<?=$chemo_id?>';

        var new_name_string = get_names_array.replace(/,\s*$/,"");
        var new_chemo_string = get_chemo_array.replace(/,\s*$/,"");

        var names = new_name_string.split(",");
        var chemo = new_chemo_string.split(",");
        
        var bodysurface = document.getElementById('bodysurface').value;
        var adjustmentdose = document.getElementById('adjustmentdose').value;

        for(var i = 0;i < names.length; i++){
            document.getElementById('dose'+chemo[i]).value = ((bodysurface*adjustmentdose*names[i])/100).toFixed(2);
        }
        //console.log(names)
    }
    </script>
    <?php 
}