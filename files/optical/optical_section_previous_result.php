
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
<fieldset id="check" style="height: 550px;overflow-y: auto;">

    <?php  
    $index=1;
    include("./includes/connection.php");
    $Registration_ID=$_POST['Registration_ID'];
    $sql="SELECT  op.VA_RE,op.VA_LE, op.VA_WPIN_RE, op.VA_WPIN_LE, op.IOP_RE, op.IOP_LE, op.VA_GLASSES_RE, op.VA_GLASSES_LE, op.date_exam,emp.Employee_Name,op.picture_note FROM optical_clinic as op,tbl_employee as emp WHERE op.Registration_ID='$Registration_ID' AND op.Employee_ID=emp.Employee_ID ORDER BY date_exam DESC";
    $query=mysqli_query($conn,$sql) or die(mysqli_error($conn));
    if(mysqli_num_rows($query) > 0){
        ?>
        <table class="table table-hover table-striped">
        <thead style='background-color:#bdb5ac;'>
            <tr>
                <td>Saved date</td>
                <td>Saved By</td>
                <td>Action</td>
            </tr>
        </thead>
        <tbody>
        <?php
    while($row=mysqli_fetch_array($query)){
        $VA_RE=$row['VA_RE'];
        $VA_LE=$row['VA_LE'];
        $VA_WPIN_RE=$row['VA_WPIN_RE'];
        $VA_WPIN_LE=$row['VA_WPIN_LE'];
        $IOP_RE=$row['IOP_RE'];
        $IOP_LE=$row['IOP_LE'];
        $date_exam=$row['date_exam'];
        $VA_GLASSES_RE=$row['VA_GLASSES_RE'];
        $VA_GLASSES_LE=$row['VA_GLASSES_LE'];
        $Employee_Name=$row['Employee_Name'];
        $picture_note=$row['picture_note'];
        $filled_status=$row['filled_status'];
        
    ?>
                    <tr>
                        <td><?php echo $date_exam;?></td>
                        <td><?php echo $Employee_Name;?></td>
                        <td onclick="open_data('<?php echo $date_exam?>','<?php echo $Registration_ID?>')" class='art-button-green' style='color:white;text-align:center;'>PREVIEW DATA</td>
                    </tr>
        </div>
    <?php
    }
    }else{
        echo '<h3 style="text-align:center">No Result Found</h3>';
    }
    ?>
    </tbody>
    </table>
    </fieldset>
    <body>
   <div id="result2"></div>
</html>
<script>
    function open_data(date_exam,Registration_ID){
        //var Registration_ID = $(".Registration_ID").val();
         //alert(Registration_ID);
        $.ajax({
                type:'post',
                url: 'open_optical_previous_date.php',
                data : {
                     Registration_ID:Registration_ID,
                     date_exam:date_exam
               },
               success : function(data){
                $('#result2').html(data);
                
                    $('#result2').dialog({
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
