<?php 
    include './includes/header.php'; 
    include 'common/common.interface.php';
    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $Result = new CommonInterface();
?>

<a href="index.php?Welcome" class="art-button-green">BACK</a>
<br><br>

<fieldset style="width: 100%;height: 250px">
    <legend align='center' style="font-weight: 500;">REHABILITATION SUPERVISOR AUTHENTICATION</legend>
    <br>
    <center>
        <table width='50%' style="background-color: #f4f4f4;">
            <tr>
                <td style="padding: 8px;font-weight:500" width='25%'>Supervisor Username</td>
                <td><input type="text" id="username" placeholder="Supervisor Username"></td>
            </tr>   

            <tr>
                <td style="padding: 8px;font-weight:500">Supervisor Password</td>
                <td><input type="password" id="password" placeholder="Supervisor Password"></td>
            </tr>

            <tr>
                <td style="padding: 8px;font-weight:500"></td>
                <td>
                    <select style="padding: 5px;" id="clinic_id">
                        <option value="">Select Your Working Clinic</option>
                        <?php foreach($Result->getEmployeeAssignedClinic($Employee_ID,"",array('physiotherapy','occupationaltherapy')) as $Clinic ) :  ?>
                            <option value="<?=$Clinic['Clinic_ID']?>"><?=$Clinic['Clinic_Name']?></option>
                        <?php endforeach; ?>
                    </select>
                    <span id="notification" style="color: red;font-weight:500"></span>
                </td>
            </tr>

            <tr>
                <td style="padding: 8px;font-weight:500"></td>
                <td>
                    <a href="#" onclick="authentication()" class="art-button-green">ALLOW <?=strtoupper($_SESSION['userinfo']['Employee_Name'])?></a>
                    <a href="#" class="art-button-green">CLEAR</a>
                </td>
            </tr>
        </table>
    </center>
</fieldset>

<script>
    function authentication(){
        var supervisor_username = $('#username').val();
        var supervisor_password = $('#password').val();
        var clinic_id = $('#clinic_id').val();

        if(clinic_id == "" || clinic_id == null || clinic_id == undefined){
            $('#notification').text("Please select working clinic");
            exit();
        }
        $('#notification').text("");

        $.ajax({
            type: "POST",
            url: "common/common.php",
            cache:false,
            data: {
                supervisor_username:supervisor_username,
                supervisor_password:supervisor_password,
                authenticate_user:'authenticate_user'
            },
            success: function (response) {
                if(response == 100){
                    location.href = "physiotherapy.php?clinic="+clinic_id;
                }else{
                    alert("INVALID USERNAME OR PASSWORD");
                }
            }
        });
    }

    function clearInputs(){
        $('#username').val('');
        $('#password').val('');
    }

    (function (global) {
        if(typeof (global) === "undefined"){
            throw new Error("window is undefined");
        }

        var _hash = "!";
        var noBackPlease = function () {
            global.location.href += "#";
            global.setTimeout(function () {
                global.location.href += "!";
            }, 50);
        };

        global.onhashchange = function () {
            if (global.location.hash !== _hash) {
                global.location.hash = _hash;
            }
        };

        global.onload = function () {
            noBackPlease();
            document.body.onkeydown = function (e) {
                var elm = e.target.nodeName.toLowerCase();
                if (e.which === 8 && (elm !== 'input' && elm  !== 'textarea')) {
                    e.preventDefault();
                }
                e.stopPropagation();
            };
        };})(window);
</script>

<?php include './includes/footer.php'; ?>
