
<center>
    <table width=60%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b><?php echo $Authentication_Title; ?></b></legend>
                    <table width = '100%'>
                        <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
                           
                                <tr>
                                    <td width=30%><b>Supervisor Username</b></td>
                                    <td width=70%>
                                        <input type='text' name='Supervisor_Username' required='required' size=70 id='Supervisor_Username' placeholder='Supervisor Username'>
                                    </td>
                                </tr> 
                                <tr>
                                    <td><b>Supervisor Password</b></td>
                                    <td width=70%>
                                        <input type='password' name='Supervisor_Password' required='required' size=70 id='Supervisor_Password' placeholder='Supervisor Password'>
                                    </td> 
                                </tr>
                                <tr>
                                    <td><b>Sub Department</b></td>
                                    <td>
                                        <!--<select name='Sub_Department_ID' id='Sub_Department_ID'>-->
                                        <select name='Sub_Department_Name' id='Sub_Department_Name' required='required'>
                                            <option selected='selected'></option>
                                            <?php
                                                if(isset($_SESSION['userinfo']['Employee_ID'])){
                                                    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
                                                }
                                                $select_sub_departments = mysqli_query($conn,"select Sub_Department_Name from
                                                                                tbl_department dep, tbl_sub_department sdep,tbl_employee_sub_department ed
                                                                                    where dep.department_id = sdep.department_id and
                                                                                        ed.Employee_ID = '$Employee_ID' and
                                                                                            ed.Sub_Department_ID = sdep.Sub_Department_ID and
                                                                                            Department_Location = '$Department_Location'
                                                                                        ");
                                                while($row = mysqli_fetch_array($select_sub_departments)){
                                                    echo "<option>".$row['Sub_Department_Name']."</option>";
                                                }
                                            
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan=2 style='text-align: center;'>
                                        <input type='submit' name='submit' id='submit' value='<?php echo 'ALLOW '.strtoupper($_SESSION['userinfo']['Employee_Name']); ?>' class='art-button-green'>
                                        <input type='reset' name='clear' id='clear' value='CLEAR' class='art-button-green'> 
                                        <a href='./index.php?TransactionAccessDenied=TransactionAccessDeniedThisPage' class='art-button-green'>CANCEL</a>
                                        <input type='hidden' name='submittedSupervisorInformationForm' value='true'/> 
                                    </td>
                                </tr>
                        </form></table>
            </fieldset>
        </center></td></tr></table>
</center>