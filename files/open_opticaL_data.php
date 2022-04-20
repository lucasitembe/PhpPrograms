<?php 
    include("./includes/connection.php");
    $Registration_ID=$_POST['Registration_ID'];
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
<fieldset style='height: 500px;overflow-y: scroll'>
    <?php
    $index=1;
        // $sql="SELECT o.date_exam, o.return_date, o.allergies , o.diabetes, o.Registration_ID FROM optical_clinic as o,tbl_employee as e WHERE o.Registration_ID='$Registration_ID' AND o.Employee_ID=e.Employee_ID";
        $sql="SELECT o.optical_clinic_ID, o.VA_RE,o.VA_LE,e.Employee_Name,o.VA_WPIN_RE,o.VA_WPIN_LE,o.IOP_RE,IOP_LE,o.trainee, o.VA_GLASSES_RE, o.VA_GLASSES_LE, o.date_exam, o.return_date, o.Registration_ID FROM optical_clinic as o,tbl_employee as e WHERE o.Registration_ID='$Registration_ID' AND o.Employee_ID=e.Employee_ID";
        
        $check_sql=mysqli_query($conn,$sql) or die (mysqli_error($conn));
        while($row=mysqli_fetch_array($check_sql)){

            $VA_RE=$row['VA_RE'];
            $VA_LE=$row['VA_LE'];;
            $examiner=$row['Employee_Name'];
            $VA_WPIN_RE=$row['VA_WPIN_RE'];
            $VA_WPIN_LE=$row['VA_WPIN_LE'];
            $IOP_RE=$row['IOP_RE'];
            $IOP_RE=$row['IOP_RE'];
            $IOP_LE=$row['IOP_LE'];
            $trainee=$row['trainee'];
            $VA_GLASSES_RE=$row['VA_GLASSES_RE'];
            $VA_GLASSES_LE=$row['VA_GLASSES_LE'];
            $date_exam=$row['date_exam'];
            $return_date=$row['return_date'];
            $allergies =$row['allergies '];
            $diabetes=$row['diabetes'];
            $pressure=$row['pressure'];
            $hypertention =$row['hypertention'];

        ?>
        
        <!-- <center><h3 style='color:white' class="art-button-green">VIsit Number <?=$index++ ?></h3></center> -->
        <center><h3 style='color:white' class="art-button-green">Examination Date <?=$date_exam?></h3></center>
       
              
  
    <!-- <div style="display:grid;grid-template-columns:1fr 1fr 1fr; gap:1em;">
        <div class="one">
            Return Date<td><input type='text' id='return_date' class="form-control" value="<?= $return_date;?>" readonly>
        </div>
        <div class="two">
            Trainee<input type="text" id="txt_supervisor" class="form-control" value="<?= $trainee;?>" readonly>
        </div>
        <div class="three">
            Supervisor<input type="text" id="txt_supervisor" class="form-control" value="<?= $examiner;?>" readonly>
        </div>
    </div> -->
    <!-- <div style="display:grid;grid-template-columns:1fr 1fr 1fr; gap:1em;margin-top:10px;">
        <div class="one">
            Allergies<td><input type='text' id='return_date' class="form-control" value="<?= $allergies ;?>" readonly>
        </div>
        <div class="three">
            Pressure<input type="text" id="txt_supervisor" class="form-control" value="<?= $pressure;?>" readonly>
        </div>
        <div class="three">
            Hypertention<input type="text" id="txt_supervisor" class="form-control" value="<?= $hypertention;?>" readonly>
        </div>
    </div> -->



    <table class="table table-striped" style="margin-top:25px;">
        <thead class='thead-dark'>
            <tr>
                <th> PROCEDURE</th>
                <th> RE</th>
                <th> LE</th>

            </tr>
        </thead>
        <tbody>
            <tr>
                <td>VA</td>
                <td><input type='text' id='VA_RE' class="form-control" value="<?= $VA_RE;?>" readonly></td>
                <td><input type='text' id='VA_LE' class="form-control" value="<?= $VA_LE;?>" readonly></td>
            </tr>
            <tr>
                <td>VA W/PIN</td>
                <td><input type='text' id='VA_WPIN_RE' class="form-control" value="<?= $VA_WPIN_RE;?>" readonly></td>
                <td><input type='text' id='VA_WPIN_LE' class="form-control" value="<?= $VA_LE;?>" readonly></td>
            </tr>
            <tr>
                <td>IOP</td>
                <td><input type="text" id="IOP_RE" class="form-control" value="<?= $IOP_RE;?>" readonly></td>
                <td><input type="text" id="IOP_LE" class="form-control" value="<?= $IOP_LE;?>" readonly></td>

            </tr>
         
            <tr>
                <td>VA W/GLASSES</td>
                <td><input type='text' id='VA_WGLASSES_RE' class="form-control" value="<?= $VA_GLASSES_RE;?>" readonly></td>
                <td><input type="text" id="VA_WGLASSES_LE" class="form-control" value="<?= $VA_GLASSES_LE;?>" readonly></td>

            </tr>
            <!--tr>
                <td>Avastin inj</td>
                <td colspan="2"><input type='text' id='txt_avastin_inj' class="form-control" value="<?= $avastin_inj;?>" readonly></td>

            </tr>
            <tr>
                <td>Day Case</td>
                <td colspan="2"><input type='text' id='txt_day_case' class="form-control" value="<?= $day_case;?>" readonly></td>

            </tr>
            <tr>
                <td>Probing</td>
                <td colspan="2"><input type='text' id='txt_probing' class="form-control" value="<?= $probing;?>" readonly></td>

            </tr>
            <tr>
                <td>Other</td>
                <td colspan="2"><input type='text' id='txt_other' class="form-control" value="<?= $other;?>" readonly></td>
            </tr-->
            
        <tbody>
    </table>
    <?php

        }
        ?>

   
    <div id="result"></div>
    </fieldset>

    </body>
        

</html>