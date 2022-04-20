<?php
    include("./includes/connection.php");
    $Employee_Name=$_POST['employee_search_value'];
    $filter="";
     if($Employee_Name != null && $Employee_Name != ''){
        $filter .= " where emp.Employee_Name like '%$Employee_Name%' ";
    }


    $select = mysqli_query($conn,"select * from tbl_employee emp 
    $filter
    order by Employee_Name limit 50") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select);
    echo '<table class="table">';
    if($no > 0){
        while ($row = mysqli_fetch_array($select)) {
            $Employee_ID=$row['Employee_ID'];
            $Employee_Name=ucwords(strtolower($row['Employee_Name']));
            echo "<tr>
                    <td>
                        <label style='font-weight:normal'>
                            <input type='radio' class='Employee_ID' name='Employee_ID' value='$Employee_ID'> $Employee_Name
                        </label>
                    </td>
                    
            </tr>";
        }
    }else{
        echo "<tr>
                    <td>
                        <label style='color:red;'>
                            SORRY,NO EMPLOYEE FOUND!
                        </label>
                    </td>  
                </tr>";
    }
    echo '</table>';