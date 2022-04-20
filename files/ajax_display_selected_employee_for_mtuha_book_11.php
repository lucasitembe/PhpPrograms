<?php 
   include("./includes/connection.php");
   session_start();
   ?>
<table class="table" id="list_of_all_employee">
    <tr>
        <td style="width:50px"><b>S/No</b></td>
        <td style="width:80%"><b>Employee Name</b></td>
        <td style="width:50px"><b>Action</b></td>
    </tr>
    <?php 
        $sql_select_employee_result=mysqli_query($conn,"SELECT Employee_Name,mtuha_book_11_report_employee_id FROM tbl_employee emp,tbl_mtuha_book_11_report_employee mtemp WHERE emp.Employee_ID=mtemp.Employee_ID") or die(mysqli_error($conn));
        if(mysqli_num_rows($sql_select_employee_result)>0){
            $count=1;
            while($employee_rows=mysqli_fetch_assoc($sql_select_employee_result)){
               $Employee_Name=$employee_rows['Employee_Name'];
               $mtuha_book_11_report_employee_id=$employee_rows['mtuha_book_11_report_employee_id'];
               echo "
                   <tr>
                        <td>$count</td>
                        <td>$Employee_Name</td>
                        <td>
                            <input type='button' class='art-button-green' onclick='remove_employee_for_mtuha_book_11(\"$mtuha_book_11_report_employee_id\")' value='X'/>
                        </td>
                    </tr>
                    ";
               $count++;
            }
        }
    ?> 
</table>
