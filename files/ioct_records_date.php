
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
<!-- <input type="button" class="art-button-green" onclick="location.reload(true)" value="BACK"> -->
<!-- <a href="" onclick="location.reload(true)" class="art-button-green">BACK</a> -->
<fieldset id="check" style="height: 550px;overflow-y: auto;">

    <?php  
    $index=1;
    include("./includes/connection.php");
    $Registration_ID=$_POST['Registration_ID'];
    $sql="SELECT ioct.ioct_file,emp.Employee_Name,ioct.date_exam FROM tbl_ioct as ioct,tbl_employee as emp WHERE ioct.Registration_ID='$Registration_ID' AND ioct.Employee_ID=emp.Employee_ID ORDER BY date_exam DESC";
    $query=mysqli_query($conn,$sql) or die(mysqli_error($conn));
    if(mysqli_num_rows($query) > 0){
        ?>
        <table class="table table-hover table-striped">
            <thead style='background-color:#bdb5ac;'>
                <tr>
                    <td>Saved date</td>
                    <td>Saved By</td>
                    <td>Attachment</td>
                </tr>
            </thead>

            <tbody>
            <?php
    while($row=mysqli_fetch_array($query)){
        $ioct_file=$row['ioct_file'];
        $Employee_Name=$row['Employee_Name'];
        $date_exam=$row['date_exam'];
        $attachment="<a href='attachment_ioct/$ioct_file' target='_blank'class='art-button-green' >PREVIEW</a>";
        
    ?>
                    <tr>
                        <td><?php echo $date_exam;?></td>
                        <td><?php echo $Employee_Name;?></td>
                        <td style='color:white;text-align:center;' class='art-button-green'><?php echo $attachment;?></td>
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
</html>
<script>
  
</script>
