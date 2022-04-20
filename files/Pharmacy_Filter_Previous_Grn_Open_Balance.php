<?php
    @session_start();
    include("./includes/connection.php");
    
    if(isset($_GET['Start_Date'])){
        $Start_Date = $_GET['Start_Date'];
    }else{
        $Start_Date = '';
    }
    
    if(isset($_GET['End_Date'])){
        $End_Date = $_GET['End_Date'];
    }else{
        $End_Date = '';
    }
    
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
    
    if(isset($_SESSION['userinfo']['Employee_Name'])){
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
        $Employee_Name = 0;
    }
    
    //get sub department details
    if(isset($_SESSION['Pharmacy_ID'])){
        $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
        
        $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if($no > 0){
            while($row = mysqli_fetch_array($select)){
                $Sub_Department_Name = $row['Sub_Department_Name'];
            }
        }else{
            $Sub_Department_Name = '';
        }
    }
    
?>


<legend align=right><b><?php if(isset($_SESSION['Pharmacy_ID'])){ echo $Sub_Department_Name; }?>, Previous grn open balances</b></legend>
<?php
    $temp = 1;
        echo '<center><table width = 100% border=0>';
        echo '<tr><td width=5% style="text-align: center;"><b>Sn</b></td>
                    <td width=10%><b>Grn Number</b></td>
                        <td width=15%><b>Prepared By</b></td>
                            <td width=15%><b>Created Date</b></td>
                                <td width=30%><b>Grn Description</b></td>
                                    <td width=15%><b>Supervisor Name</b></td>
                                        <td width=7%></td></tr>';
    
    //get top 50 grn open balances based on selected employee id
    $sql_select = mysqli_query($conn,"select gob.Grn_Open_Balance_ID, emp.Employee_Name, gob.Created_Date_Time, gob.Grn_Open_Balance_Description, gob.Employee_ID
                                    from tbl_grn_open_balance gob, tbl_employee emp where
                                    emp.Employee_ID = gob.Supervisor_ID and 
                                    gob.Sub_Department_ID = '$Sub_Department_ID' and
                                    gob.Created_Date_Time between STR_TO_DATE('$Start_Date','%Y-%m-%d') and STR_TO_DATE('$End_Date','%Y-%m-%d') and
                                    gob.Grn_Open_Balance_Status = 'saved' order by Grn_Open_Balance_ID desc") or die(mysqli_error($conn));
    $num = mysqli_num_rows($sql_select);
    if($num > 0){
        while($row = mysqli_fetch_array($sql_select)){
            //get employee prepared
            $Prep_Employee = $row['Employee_ID'];
            $sel = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Prep_Employee'") or die(mysqli_error($conn));
            $Pre_no = mysqli_num_rows($sel);
            if($Pre_no > 0){
                while ($dt = mysqli_fetch_array($sel)) {
                    $Created_By = $dt['Employee_Name'];
                }
            }else{
                $Created_By = '';
            }

            echo '<tr><td style="text-align: center;">'.++$temp.'</td>
                    <td>'.$row['Grn_Open_Balance_ID'].'</td>
                    <td>'.$Created_By.'</td>
                    <td>'.$row['Created_Date_Time'].'</td>
                    <td>'.$row['Grn_Open_Balance_Description'].'</td>
                    <td>'.$row['Employee_Name'].'</td>
                    <td style="text-align: center;"><input type="button" name="Preview" id="Preview" class="art-button-green" value="Preview" onclick="Preview_Details('.$row['Grn_Open_Balance_ID'].')"></td></tr>';
        }
    }
    echo '</table>';
?>