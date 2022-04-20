<?php
include("./includes/connection.php");
include("./includes/header.php");
$Brand_Id = $_SESSION['userinfo']['Branch_ID'];
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];


if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>

<a href="pharmacyworkspage_new.php" class="art-button-green" style="font-family: Arial, Helvetica, sans-serif;">LIST OF PATIENT SENT PHARMACY</a>

<style>
    table thead tr {
        background-color: #bbb;
    }

    table thead td{
        font-size:15px;
        font-weight: bold;
    }

    table td {
        padding: 10px !important;
        cursor: pointer;
    }

    table tbody tr:hover {
        background-color: #bbb;
    }
    #search_component{
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 2em;
    }
    #search_component input{
        padding: 12px;
    }
</style>

<br><br>

<fieldset>
    <legend>CURRENT INPATIENT LIST</legend>

    <div id="search_component">
        <div>
            <input type="text" placeholder="Enter Patient Names" id="patient_name" onkeyup="search_by_name()">
        </div>

        <div>
            <input type="text" placeholder="Enter Patient Number" id="patient_number" onkeyup="search_by_patient_number()">
        </div>

        <div>
            <input type="text" placeholder="Enter Patient Phone Number" id="phone_number" onkeyup="search_by_phone_number()">
        </div>
    </div>

    <br>

    <div id="current_inpatient_list">
        <table width='100%'>
            <thead>
                <tr>
                    <td width='5%'>S/No</td>
                    <td width='15%'>Patient Name</td>
                    <td width='15%'>Registration Number</td>
                    <td width='15%'>Gender</td>
                    <td width='15%'>Age</td>
                    <td width='15%'>Sponsor</td>
                    <td width='15%'>Phone Number</td>
                </tr>
            </thead>
            <tbody id="inpatient_list_data">
                
            </tbody>
        </table>
    </div>
</fieldset>

<script>
    $(document).ready(() => {
        load_inpatient_list();
    });
</script>

<script>
    function search_by_name() { 
        var patient_name = document.getElementById('patient_name').value;
        var name_search = 'name_search';

        if(name_search == ''){
            load_inpatient_list();
            exit();
        }else{
            $.ajax({
                type: "GET",
                url: "get_inpatient_list.php",
                data: {
                    name_search:name_search,
                    patient_name:patient_name
                },
                success:(data) => {
                    $("#inpatient_list_data").html(data);
                }
            });
        }
    }

    function search_by_patient_number(){
        var patient_number = document.getElementById('patient_number').value;
        var number_search = "number_search";

        if(patient_number == ''){
            load_inpatient_list();
            exit();
        }else{
            $.ajax({
                type: "GET",
                url: "get_inpatient_list.php",
                data: {
                    number_search:number_search,
                    patient_number:patient_number
                },
                success:(data) => {
                    $("#inpatient_list_data").html(data);
                }
            });
        }
    }

    function search_by_phone_number(){
        var phone_number = document.getElementById('phone_number').value;
        var phone_number_search = "phone_number_search";

        if(phone_number == ''){
            load_inpatient_list();
            exit();
        }else{
            $.ajax({
                type: "GET",
                url: "get_inpatient_list.php",
                data: {
                    phone_number_search:phone_number_search,
                    phone_number:phone_number
                },
                success:(data) => {
                    $("#inpatient_list_data").html(data);
                }
            });
        }

    }

    function load_inpatient_list(){
        var load_all = "load_all";

        $.ajax({
            type: "GET",
            url: "get_inpatient_list.php",
            data: {
                load_all:load_all
            },
            success:(data) => {
                $("#inpatient_list_data").html(data);
            }
        });
    }
</script>

<?php include("./includes/footer.php"); ?>