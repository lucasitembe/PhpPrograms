<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>
<div class="col-md-2"></div>
<div class="col-md-8">
    <input type='text' placeholder="~~~~~Search Assistant Anasthetist Name~~~~~" onkeyup='search_assist_anesthetist()' id="assistant_name" style='text-align:center'>
</div>
<div class="col-md-2">
</div>
<div class="row" id="investigation_table">
    <div class="col-md-12" id="investigation_table">
             asdsddsd
    </div>
</div>
<div class="col-md-12" id="send_data">
    <input type="button" id="send_data" Value="DONE" class="art-button-green pull-right" onclick="view_assist_anesthetist_selected()">
</div>