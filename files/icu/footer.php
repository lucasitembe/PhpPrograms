</div>

<!--// Toasts-->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="liveToast" class="toast hide bg-white" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <img src="../images/icon.PNG" style="height: 22px;" class="rounded me-2" alt="eHMS">
            <strong class="me-auto">eHMS</strong>
            <small>1 second ago</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toast-message"></div>
    </div>
</div>

<footer class="bg-brand justify-content-center d-flex mt-4">
    <input type="hidden" value="" id="broadcastmsg" />
    <input type="hidden" value="" id="notified" />
    <div class="p-3 text-white">
        <a href="http://www.gpitg.com/" target="_blank" class="text-decoration-none text-white fw-bold" title="GPITG LIMITED">GPITG LIMITED</a>
        <span class="fw-bold"></span> &copy; <?php echo date('Y'); ?>. All Rights Reserved.
    </div>
</footer>
</body>
<link rel="stylesheet" href="../css/jquery.notifyBar.css" />
<link rel="stylesheet" href="../js/toastr/toastr.min.css" />
<script src="../js/jquery.notifyBar.js" ></script>
<script src="../js/toastr/toastr.min.js"  ></script>
<script src="../js/bootstrap.min.js"></script>

<link rel="stylesheet" href="../css/select2.min.css" media="screen">
<script src="../js/select2.min.js"></script>
<script>
    var myVar;

    $(document).ready(function () {
        myVar = setInterval(function () {
            myTimer();
        }, 15000);

        // $.notifyBar({ cssClass: "success", html: "Your data has been changed!" });
    });
    function myTimer() {
        $.ajax({
            type: 'GET',
            url: '../broadcastmsg.php',
            data: 'action=read',
            dataType: 'json',
            success: function (result) {
                var broadcastmsg = $('#broadcastmsg').val();
                if (result.type != 'closed') {
                    if (broadcastmsg != result.msg && result.msg != '') {
                        $('#broadcastmsg').val(result.msg);
                        $('#notified').val('0');
                    }
                } else {
                    $('#broadcastmsg').val('');
                }

                if (broadcastmsg != '') {
                    if ($('#notified').val() == '0') {
                        $.notifyBar({cssClass: result.type, html: broadcastmsg, close: true, waitingForClose: true, closeOnClick: true});
                        $('#notified').val('1');
                    }
                }


            }, complete: function (jqXHR, textStatus) {
            }, error: function (jqXHR, textStatus, errorThrown) {
                // alert(errorThrown);
            }
        });
    }

</script>

<script>
    function notify(message){
        $('toast-message').html(message);
        var toast = new bootstrap.Toast(document.getElementById('liveToast'), {delay: 3000});
        toast.show();
    }
</script>

<?php
    ob_end_flush();
    mysqli_close($conn);
?>
</html>
