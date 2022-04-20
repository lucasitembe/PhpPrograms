<div id="login_to_phamacy_from_billing">
    <table width='100%' class="table">
        <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">

            <tr>
                <td width=30% style="text-align:right;">Supervisor Username</td>
                <td width=70%>
                    <input type='text' name='Supervisor_Username' required='required' autocomplete='off' size=70 id='Supervisor_Username' placeholder='Supervisor Username'>
                </td>
            </tr>
            <tr>
                <td style="text-align:right;">Supervisor Password</td>
                <td width=70%>
                    <input type='password' name='Supervisor_Password' required='required' size=70 autocomplete='off' id='Supervisor_Password' placeholder='Supervisor Password'>
                </td>
            </tr>
            <tr>
                <td style="text-align:right;">Sub Department</td>
                <td>
                    <!--<select name='Sub_Department_ID' id='Sub_Department_ID'>-->
                    <select name='Sub_Department_Name' id='Sub_Department_Name' required='required'>
                        <option selected='selected'></option>
                        <?php
                        if (isset($_SESSION['userinfo']['Employee_ID'])) {
                            $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
                        }
                        $select_sub_departments = mysqli_query($conn, "SELECT Sub_Department_Name from
                                                                                tbl_department dep, tbl_sub_department sdep,tbl_employee_sub_department ed
                                                                                    where dep.department_id = sdep.department_id and
                                                                                        ed.Employee_ID = '$Employee_ID' and
                                                                                            ed.Sub_Department_ID = sdep.Sub_Department_ID and
                                                                                            Department_Location = 'Pharmacy' and
                                                                                            sdep.Sub_Department_Status = 'active'
                                                                                        ");
                        while ($row = mysqli_fetch_array($select_sub_departments)) {
                            echo "<option>" . $row['Sub_Department_Name'] . "</option>";
                        }

                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan=2 style='text-align: center;'>
                    <input type='button' name='submit' id='submit' value='<?php echo 'ALLOW ' . strtoupper($_SESSION['userinfo']['Employee_Name']); ?>' onclick="login_to_phamacy()" class='art-button-green'>
                    <input type='reset' name='clear' id='clear' value='CLEAR' class='art-button-green'>
                    <?php if (isset($_SESSION['Pharmacy_Supervisor'])) { ?>
                        <a href='./pharmacyworks.php?section=Pharmacy&PharmacyWorks=PharmacyWorksThisPage' class='art-button-green'>CANCEL PROCESS</a>
                    <?php } else { ?>
                        <a href='./index.php?TransactionAccessDenied=TransactionAccessDeniedThisPage' class='art-button-green'>CANCEL</a>
                    <?php } ?>
                    <input type='hidden' name='submittedSupervisorInformationForm' value='true' />
                </td>
            </tr>
        </form>
    </table>
</div>