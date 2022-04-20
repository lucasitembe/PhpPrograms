<?php
 include("./includes/connection.php");

 if(isset($_POST['depertment_search_value'])){
    $Depertment_Name_Search = str_replace(" ", "%", $_POST['depertment_search_value']);
}else{
    $Depertment_Name_Search = '';
}

$html = '
        <div id="depertments_list">
                        <table class="table">';

                        $sql_result=mysqli_query($conn,"SELECT `Sub_Department_ID`, `Sub_Department_Name` FROM tbl_sub_department,tbl_department WHERE tbl_sub_department.Department_ID=tbl_department.Department_ID AND `Department_Location`='Admission' AND Sub_Department_Status='active' AND Sub_Department_Name like '%$Depertment_Name_Search%'") or die(mysqli_error($conn));
                                if(mysqli_num_rows($sql_result)>0){
                                        while($category_rows=mysqli_fetch_assoc($sql_result)){
                                            $Depertment_ID=$category_rows['Sub_Department_ID'];
                                            $Depertment_Name=$category_rows['Sub_Department_Name'];
                                                $html .=  "<tr>
                                                            <td>
                                                                <label style='font-weight:normal'>
                                                                    <input type='radio'class='Depertment_ID' name='Depertment_ID' value='$Depertment_ID'>$Depertment_Name
                                                                </label>
                                                            </td>
                                                            
                                                    </tr>";
                                        }
                                    }else{
                                        $html .=  "<tr>
                                                    <td>
                                                        <label style='color:red;'>
                                                            SORRY, NO RESULT FOUND!
                                                        </label>
                                                    </td>
                                                    
                                            </tr>";
                                    }
                       $html .=  '</table>
                    </div>';

                    echo $html;
?>