<?php  
    $index=1;
    include("./includes/connection.php");
    $Registration_ID=$_POST['Registration_ID'];
    $date_exam=$_POST['date_exam'];
    $sql="SELECT  eye.corneal_oedema_RE, eye.corneal_oedema_LE, eye.knots_exposed_RE, eye.knots_exposed_LE, eye.fibrin_LE, eye.fibrin_RE, eye.hyphaema_RE, eye.hyphaema_LE, eye.iris_prolapse_RE, eye.iris_prolapse_LE, eye.irregular_pupil_RE, eye.irregular_pupil_LE, eye.iopmmg_RE, eye.iopmmg_LE, eye.VA_WPIN_RE, eye.VA_WPIN_LE, eye.iop_RE, eye.iop_LE, eye.VA_WGLASSES_RE, eye.VA_WGLASSES_LE, eye.Registration_ID, eye.Employee_ID, eye.created_at,emp.Employee_Name FROM examination_operated_eye as eye,tbl_employee as emp  WHERE  eye.Registration_ID='$Registration_ID' AND emp.Employee_ID=eye.Employee_ID AND eye.created_at='$date_exam'";
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
        
<table class="table table-striped table-hover" style="margin-top:25px;">
            <thead>
                <th>Oservation</th>
                <th>RE</th>
                <th>LE</th>
            </thead>
            <tbody>
            <tr>
                <td>Corneal oedema</td>
                <td><?php echo $corneal_oedema_RE;?></td>
                <td><?php echo $corneal_oedema_LE;?></td>

            </tr>
            <tr>
                <td>Knots exposed</td>
                <td><?php echo $knots_exposed_RE;?></td>
                <td><?php echo $knots_exposed_LE;?></td>
            </tr>
            <tr>
                <td>Fibrin</td>
                <td><?php echo $fibrin_RE;?></td>
                <td><?php echo $fibrin_LE;?></td>
            </tr>
            <tr>
                <td>Hyphaema</td>
                <td><?php echo $hyphaema_RE;?></td>
                <td><?php echo $hyphaema_LE;?></td>
            </tr>
            <tr>
                <td>Iris prolapse</td>
                <td><?php echo $iris_prolapse_RE;?></td>
                <td><?php echo $iris_prolapse_LE;?></td>
            </tr>
            <tr>
                <td>Irregular pupil</td>
                <td><?php echo $irregular_pupil_LE;?></td>
                <td><?php echo $irregular_pupil_RE;?></td>
            </tr>
            <tr>
                <td>IOP > 24mmHg</td>
                <td><?php echo $iopmmg_RE;?></td>
                <td><?php echo $iopmmg_LE;?></td>
            </tr>
            
            <tr>
                <td>VA W/PIN</td>
                <td><?php echo $VA_WPIN_RE;?></td>
                <td><?php echo $VA_WPIN_LE;?></td>
            </tr>
            </tr>
                <td>IOP</td>
                <td><?php echo $iop_RE;?></td>
                <td><?php echo $iop_LE;?></td>
            </tr>
                <td>VA W/GLASSES</td>
                <td><?php echo $VA_WGLASSES_RE;?></td>
                <td><?php echo $VA_WGLASSES_LE;?></td>
            
            </tr>
                <td>Saved By</td>
                <td colspan="2"><?php echo $Employee_Name;?></td>
            </tr>
                            
        </tbody>
    </table> 
    <?php
    }
    }else{
        echo '<h3 style="text-align:center">No Result Found</h3>';
    }
    ?>