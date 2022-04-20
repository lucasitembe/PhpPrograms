<?php
    session_start();
    include("./includes/connection.php");

    if(isset($_SESSION['Laboratory_Consumption_ID'])){
        $Consumption_ID = $_SESSION['Laboratory_Consumption_ID'];
    }else{
        $Consumption_ID = 0;
    }


    //get employee need
    $select = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from tbl_employee emp, tbl_Consumption c
                            where emp.Employee_ID = c.Employee_Need and
                            c.Consumption_ID = '$Consumption_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while (($row = mysqli_fetch_array($select))) {
?>
            <option selected="selected" value="<?php echo $row['Employee_ID']; ?>"><?php echo $row['Employee_Name']; ?></option>
<?php
        }
    }
?>