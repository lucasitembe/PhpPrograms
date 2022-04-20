<?php 
    include("./includes/connection.php");
    $Registration_ID=$_POST['Registration_ID'];
    $Patient_Name=$_POST['Patient_Name'];
    $Patient_Payment_ID=$_POST['Patient_Payment_ID'];
    $Patient_Payment_Item_List_ID=$_POST['Patient_Payment_Item_List_ID'];
    $consultation_ID=$_POST['consultation_ID'];


    $select_Patient = mysqli_query($conn,"select Registration_ID,Date_Of_Birth,Patient_Name,Gender,Guarantor_Name from tbl_patient_registration pr, tbl_sponsor sp where
				       sp.Sponsor_ID = pr.Sponsor_ID and
					  pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Patient);

    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Patient)) {
            $Registration_ID = $row['Registration_ID'];
            $Patient_Name = $row['Patient_Name'];
            $Guarantor_Name = $row['Guarantor_Name'];
            // $Sponsor_ID = $row['Sponsor_ID'];
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $Gender = $row['Gender'];
        }

        $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";
        // if($age == 0){

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age .= $diff->m . " Months, ";
        $age .= $diff->d . " Days";
    }
?>

<html>
<head>
    <style>
        input[type="checkbox"]{
            width: 30px; 
            height: 30px;
            cursor: pointer;
    }
    </style>
</head>

<body>
<!-- <legend align="center" style="text-align:center"> -->
        <!-- <br/> -->
        <!-- <span style='color:#0079AE;'>< ?php echo "<b>" . $Patient_Name . "</b>  | " . $Gender . "</b> | <b>" . $age . " years</b> | <b>" . $Guarantor_Name  . "</b>"; ?></b></span> -->
    <!-- </legend> -->
    <form id="form1" autocomplete="off">
    <div style="margin-top;10px;" >
        <div style="margin:0px;display:grid;grid-template-columns:1fr; gap:1em;text-align:right;">
                <div class="one">
                <input type="button" id="Previous_Records" name="Previous_Records" class="art-button-green" onclick="Previous_Records2();" value="Previous Records">
            </div>
        </div>
        
        <table class='table table-striped table-hover'>
            
            <thead>
                <th>Observation</th>
                <th>RE</th>
                <th>LE</th>
            </thead>
            <tbody>
                <?php
                $date_exam=date("y-m-d");
                // die("SELECT consultation_ID,Registration_ID FROM examination_operated_eye WHERE  DATE(created_at) ='$date_exam' and Registration_ID='$Registration_ID'");
                $select_existing_examination=mysqli_query($conn,"SELECT *FROM examination_operated_eye WHERE  DATE(created_at) ='$date_exam' and Registration_ID='$Registration_ID'");
                if(mysqli_num_rows($select_existing_examination)){

                }
                while($row=mysqli_fetch_array($select_existing_examination)){
                    $corneal_oedema_LE=$row['corneal_oedema_LE'];
                    $corneal_oedema_RE=$row['corneal_oedema_RE'];
                    $knots_exposed_LE=$row['knots_exposed_LE'];
                    $knots_exposed_RE=$row['knots_exposed_RE'];
                    $fibrin_LE=$row['fibrin_LE'];
                    $fibrin_RE=$row['fibrin_RE'];
                    $hyphaema_LE=$row['hyphaema_LE'];
                    $hyphaema_RE=$row['hyphaema_RE'];
                    $iris_prolapse_LE=$row['iris_prolapse_LE'];
                    $iris_prolapse_RE=$row['iris_prolapse_RE'];
                    $irregular_pupil_RE=$row['irregular_pupil_RE'];
                    $irregular_pupil_LE=$row['irregular_pupil_LE'];
                    $iopmmg_RE=$row['iopmmg_RE'];
                    $iopmmg_LE=$row['iopmmg_LE'];

                    $VA_WPIN_RE=$row['VA_WPIN_RE'];
                    $VA_WPIN_LE=$row['VA_WPIN_LE'];
                    $iop_LE=$row['iop_LE'];
                    $iop_RE=$row['iop_RE'];
                    $VA_WGLASSES_LE=$row['VA_WGLASSES_LE'];
                    $VA_WGLASSES_RE=$row['VA_WGLASSES_RE'];
                    $consultation_ID=$row['consultation_ID'];
                    
                }
            ?>
            <tr>
                <td>Corneal oedema</td>
                <td><input type="text" id="corneal_oedema_RE" name="corneal_oedema_RE" class="form-control" value="<?php echo $corneal_oedema_RE;?>"></td>
                <td><input type="text" class="form-control" id="corneal_oedema_LE" value="<?php echo $corneal_oedema_LE;?>"></td>
            </tr>
            <tr>
                    <td>Knots exposed</td>
                    <td><input type="text" class="form-control" name="knots_exposed_RE" id="knots_exposed_RE" value="<?php echo $knots_exposed_RE;?>"></td>
                    <td><input type="text" class="form-control" name="knots_exposed_LE" id="knots_exposed_LE" value="<?php echo $knots_exposed_LE;?>"></td>
            </tr>
            <tr>
                    <td>Fibrin</td>
                    <td><input type="text" class="form-control" name="fibrin_RE" id="fibrin_RE" value="<?php echo $fibrin_RE;?>"></td>
                    <td><input type="text" class="form-control" name="fibrin_LE" id="fibrin_LE" value="<?php echo $fibrin_LE;?>"></td>
                    <!-- <td><input type="text" class="form-control"></td> -->
            </tr>
            <tr>
                    <td>Hyphaema</td>
                    <td><input type="text" class="form-control" name="hyphaema_RE" id="hyphaema_RE" value="<?php echo $hyphaema_RE;?>"></td>
                    <td><input type="text" class="form-control" name="hyphaema_LE" id="hyphaema_LE" value="<?php echo $hyphaema_LE;?>"></td>
                    <!-- <td><input type="text" class="form-control"></td> -->
            </tr>
            <tr>
                    <td>Iris prolapse</td>
                    <td><input type="text" class="form-control" name="iris_prolapse_RE" id="iris_prolapse_RE" value="<?php echo $iris_prolapse_RE;?>"></td>
                    <td><input type="text" class="form-control" name="iris_prolapse_LE" id="iris_prolapse_LE" value="<?php echo $iris_prolapse_LE;?>"></td>
                    <!-- <td><input type="text" class="form-control"></td> -->
            </tr>
            <tr>
                    <td>Irregular pupil</td>
                    <td><input type="text" class="form-control" name="irregular_pupil_LE" id="irregular_pupil_RE" value="<?php echo $irregular_pupil_RE;?>"></td>
                    <td><input type="text" class="form-control" name="irregular_pupil_RE" id="irregular_pupil_LE" value="<?php echo $irregular_pupil_LE;?>"></td>
                    <!-- <td><input type="text" class="form-control"></td> -->
            </tr>
            <tr>
                    <td>IOP > 24mmHg</td>
                    <td><input type="text" class="form-control" nme="iopmmg_RE" id="iopmmg_RE" value="<?php echo $iopmmg_RE;?>"></td>
                    <td><input type="text" class="form-control" name="iopmmg_LE" id="iopmmg_LE" value="<?php echo $iopmmg_LE;?>"></td>
                    <!-- <td><input type="text" class="form-control"></td> -->
            </tr>
            
            <tr>
                <td>VA W/PIN</td>
                <td><input type="text" class="form-control" name="VA_WPIN_RE" id="VA_WPIN_RE" value="<?php echo $VA_WPIN_RE;?>"></td>
                <td><input type="text" class="form-control" name="VA_WPIN_LE" id="VA_WPIN_LE" value="<?php echo $VA_WPIN_LE;?>"></td>
                    <!-- <td><input type="text" class="form-control"></td> -->
            </tr>
            <tr>
                <td>IOP</td>
                <td><input type="text" class="form-control" name="iop_RE" id="iop_RE" value="<?php echo $iop_RE;?>"></td>
                <td><input type="text" class="form-control" name="iop_LE" id="iop_LE" value="<?php echo $iop_LE;?>"></td>
            </tr>
            <tr>
                <td>VA W/GLASSES</td>
                <td><input type="text" class="form-control" name="VA_WGLASSES_RE" id="VA_WGLASSES_RE"  value="<?php echo $VA_WGLASSES_RE;?>"></td>
                <td><input type="text" class="form-control" name="VA_WGLASSES_LE" id="VA_WGLASSES_LE"  value="<?php echo $VA_WGLASSES_LE;?>"></td>
            </tr>
            

        </tbody>
    </table>
    </div>
   
    <tr>
        <td><input type="button" value="Save" class="btn btn-primary  btn-block" id='save_btn' onclick="save_data();"></td>
    </tr>
    </form>    
    <div id="result"></div>
    <input type="hidden" id="Registration_ID" value="<?php echo $Registration_ID?>">
    <input type="hidden" class="Registration_ID" value="<?php echo $Registration_ID?>">
    <input type="hidden" class="consultation_ID" value="<?php echo $consultation_ID?>">

    <script>
   function save_data(){
        var Registration_ID = $(".Registration_ID").val();
        var corneal_oedema_LE=$("#corneal_oedema_LE").val();
        var corneal_oedema_RE=$("#corneal_oedema_RE").val();
        var knots_exposed_LE=$("#knots_exposed_LE").val();
        var knots_exposed_RE=$("#knots_exposed_RE").val();
        var fibrin_LE=$("#fibrin_LE").val();
        var fibrin_RE=$("#fibrin_RE").val();
        var hyphaema_LE=$("#hyphaema_LE").val();
        var hyphaema_RE=$("#hyphaema_RE").val();
        var iris_prolapse_LE=$("#iris_prolapse_LE").val();
        var iris_prolapse_RE=$("#iris_prolapse_RE").val();
        var irregular_pupil_RE=$("#irregular_pupil_RE").val();
        var irregular_pupil_LE=$("#irregular_pupil_LE").val();
        var iopmmg_RE=$("#iopmmg_RE").val();
        var iopmmg_LE=$("#iopmmg_LE").val();

        var VA_WPIN_RE=$("#VA_WPIN_RE").val();
        var VA_WPIN_LE=$("#VA_WPIN_LE").val();
        var iop_LE=$("#iop_LE").val();
        var iop_RE=$("#iop_RE").val();
        var VA_WGLASSES_LE=$("#VA_WGLASSES_LE").val();
        var VA_WGLASSES_RE=$("#VA_WGLASSES_RE").val();
        var consultation_ID=$(".consultation_ID").val();
        var save_btn=document.getElementById("save_btn");
        

         //alert(consultation_ID);
        if(corneal_oedema_LE !='' || corneal_oedema_RE !='' || knots_exposed_LE !='' || fibrin_LE !='' || fibrin_RE !='' || hyphaema_LE !='' || hyphaema_RE !='' || iris_prolapse_LE !='' || iris_prolapse_RE !='' || irregular_pupil_RE !='' || irregular_pupil_LE !='' || iopmmg_RE !='' || iopmmg_LE !=''){
            if(confirm("Are you Sure you want to Save")){
                $.ajax({
                type:'post',
                url: 'save_examination_operated_eye.php',
                data : {
                    Registration_ID:Registration_ID,
                    corneal_oedema_LE:corneal_oedema_LE,
                    corneal_oedema_RE:corneal_oedema_RE,
                    knots_exposed_LE:knots_exposed_LE,
                    knots_exposed_RE:knots_exposed_RE,
                    fibrin_LE:fibrin_LE,
                    fibrin_RE:fibrin_RE,
                    hyphaema_LE:hyphaema_LE,
                    hyphaema_RE:hyphaema_RE,
                    iris_prolapse_LE:iris_prolapse_LE,
                    iris_prolapse_RE:iris_prolapse_RE,
                    irregular_pupil_RE:irregular_pupil_RE,
                    irregular_pupil_LE:irregular_pupil_LE,
                    iopmmg_RE:iopmmg_RE,
                    iopmmg_LE:iopmmg_LE,
                    VA_WPIN_RE:VA_WPIN_RE,
                    VA_WPIN_LE:VA_WPIN_LE,
                    iop_LE:iop_LE,
                    iop_RE:iop_RE,
                    VA_WGLASSES_LE:VA_WGLASSES_LE,
                    VA_WGLASSES_RE:VA_WGLASSES_RE,
                    consultation_ID:consultation_ID
                },
                success : function(response){
                    // $("#corneal_oedema_LE").val('');
                    // $("#corneal_oedema_RE").val('');
                    // $("#knots_exposed_LE").val('');
                    // $("#knots_exposed_RE").val('');
                    // $("#fibrin_LE").val('');
                    // $("#fibrin_RE").val('');
                    // $("#hyphaema_LE").val('');
                    // $("#hyphaema_RE").val('');
                    // $("#iris_prolapse_LE").val('');
                    // $("#iris_prolapse_RE").val('');
                    // $("#irregular_pupil_RE").val('');
                    // $("#irregular_pupil_LE").val('');
                    // $("#iopmmg_RE").val('');
                    // $("#iopmmg_LE").val('');
                    // $("#VA_WPIN_RE").val('');
                    // $("#VA_WPIN_LE").val('');
                    // $("#iop_LE").val('');
                    // $("#iop_RE").val("");
                    // $("#VA_WGLASSES_LE").val("");
                    // $("#VA_WGLASSES_RE").val("");
                    // $(".consultation_ID").val("");
            
                    
                    $('#result').html(response);
                    alert(response);

                }
            }); 
            }
        }
        else{
            alert("Please fill atleast one of the field above");
        }
        }
       
    </script>
     <script>
     function Previous_Records2(){
        var Registration_ID = $(".Registration_ID").val();
         //alert(Registration_ID);
        $.ajax({
                type:'post',
                url: 'examination_of_operated_eye_previous_report.php',
                data : {
                     Registration_ID:Registration_ID
               },
               success : function(data){
                $('#result').html(data);
                    $('#result').dialog({
                        autoOpen:true,
                        width:'85%',
                        position: ['center',200],
                        title:'EXAMINATION OF OPERATED EYE:',
                        modal:true
                    });  
                    
               }
           });
     }
 </script>

    <body>

</html>