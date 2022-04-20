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
    <fieldset id="check" style="height: 640px;overflow-y: auto;">
    <?php  
    $index=1;
    include("./includes/connection.php");
    $Registration_ID=$_POST['Registration_ID'];
    $sql="SELECT  eye.corneal_oedema_RE, eye.corneal_oedema_LE, eye.knots_exposed_RE, eye.knots_exposed_LE, eye.fibrin_LE, eye.fibrin_RE, eye.hyphaema_RE, eye.hyphaema_LE, eye.iris_prolapse_RE, eye.iris_prolapse_LE, eye.irregular_pupil_RE, eye.irregular_pupil_LE, eye.iopmmg_RE, eye.iopmmg_LE, eye.VA_WPIN_RE, eye.VA_WPIN_LE, eye.iop_RE, eye.iop_LE, eye.VA_WGLASSES_RE, eye.VA_WGLASSES_LE, eye.Registration_ID, eye.Employee_ID, eye.created_at,emp.Employee_Name FROM examination_operated_eye as eye,tbl_employee as emp  WHERE  eye.Registration_ID='$Registration_ID' AND emp.Employee_ID=eye.Employee_ID ORDER BY created_at asc";
    $query=mysqli_query($conn,$sql) or die(mysqli_error($conn));
    if(mysqli_num_rows($query) > 0){

   
    while($row=mysqli_fetch_array($query)){
        $corneal_oedema_RE=$row['corneal_oedema_RE'];
        $corneal_oedema_LE=$row['corneal_oedema_LE'];
        $knots_exposed_RE=$row['knots_exposed_RE'];
        $knots_exposed_LE=$row['knots_exposed_LE'];
        $fibrin_LE=$row['fibrin_LE'];
        $fibrin_RE=$row['fibrin_RE'];
        $hyphaema_RE=$row['hyphaema_RE'];
        $hyphaema_LE=$row['hyphaema_LE'];
        $iris_prolapse_RE=$row['iris_prolapse_RE'];
        $iris_prolapse_LE=$row['iris_prolapse_LE'];
        $iopmmg_RE=$row['iopmmg_RE'];
        $iopmmg_LE=$row['iopmmg_LE'];
        $VA_WPIN_RE=$row['VA_WPIN_RE'];
        $VA_WPIN_LE=$row['VA_WPIN_LE'];
        $iop_RE=$row['iop_RE'];
        $iop_LE=$row['iop_LE'];
        $VA_WGLASSES_RE=$row['VA_WGLASSES_RE'];
        $VA_WGLASSES_LE=$row['VA_WGLASSES_LE'];
        $irregular_pupil_RE=$row['irregular_pupil_RE'];
        $irregular_pupil_LE=$row['irregular_pupil_LE'];  
        $created_at=$row['created_at'];
        $Employee_Name=$row['Employee_Name'];
    ?>
        <center>
            <h3 style='color:white' class="art-button-green"  onclick="open_data_exam('<?php echo $created_at?>','<?php echo $Registration_ID?>')">Visit Date <?=$created_at?></h3><br>
        </center>
        
    <?php
    }
    }
    else{
        echo '<h3 style="text-align:center">No Result Found</h3>';
    }
    ?>
</fieldset>
<div id="result3"></div>
<body>
   
</html>
<script>
    function open_data_exam(date_exam,Registration_ID){
        //var Registration_ID = $(".Registration_ID").val();
        //  alert(Registration_ID);
        //  alert(date_exam);
        $.ajax({
                type:'post',
                url: 'examination_date.php',
                data : {
                     Registration_ID:Registration_ID,
                     date_exam:date_exam
               },
               success : function(data){
                $('#result3').html(data);
                    $('#result3').dialog({
                        autoOpen:true,
                        width:'85%',
                        position: ['center',0],
                        title:'PATIENT RECORD OF : '+date_exam,
                        modal:true
                    });  
                    
               }
           });
    }
</script>
