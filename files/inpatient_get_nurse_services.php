<?php
include("./includes/connection.php");
?>
<style>
    .servicesNameSel:hover{
        cursor: pointer; 
    }
</style>
<?php
$service_name = '';
$Registration_ID = '';

if (isset($_GET['service_name'])) {
    $service_name = $_GET['service_name'];
}

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
}
?>
<table border="0" width="100%">
    <tr>
        <td>
            <table border="0" width="100%">
                <tr>
                    <td>Action</td>
                    <td>Service Name</td>
                </tr>
                <?php
                $select_services = "SELECT * FROM tbl_items WHERE Item_Type = 'Service' AND  Product_Name LIKE '%$service_name%' order by Product_Name limit 50";
                $selected_services = mysqli_query($conn,$select_services) or die(mysqli_error($conn));
                $discontinue_status = '';
                while ($items = mysqli_fetch_assoc($selected_services)) {
                    $service_name = $items['Product_Name'];
                    $service_ID = $items['Item_ID'];

                    $select_service = "
                                                                    SELECT * FROM tbl_inpatient_services_given 
                                                                            WHERE Service_ID = '$service_ID' AND 
                                                                                  Registration_ID = '" . $_GET['Registration_ID'] . "' AND
                                                                                  consultation_ID = '" . $_GET['consultation_ID'] . "' AND
                                                                                  Discontinue_Status='yes'    
                                                                            ";
                    $selected_service = mysqli_query($conn,$select_service) or die(mysqli_error($conn));
                    if (mysqli_num_rows($selected_service) == 0) {
                        echo "<td><input type='radio' name='servieSel' onclick='Get_Last_Given_Time($service_ID)' id='" . $service_ID . "' value='" . $service_ID . "'/></td>
                                                    <td style='color:red'><label for='" . $service_ID . "' class='servicesNameSel'>$service_name</label></td>
                                                   ";
                        echo '</tr>';
                    }
                }
                    ?>
            </table>
        </td>
    </tr>
</table>