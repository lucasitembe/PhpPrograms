<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<link rel='stylesheet' href='css/fonts/fonts.css' />

<div id="pleaseWaitDialog" style="z-index:99999; background: #191f26;">
    <center><h1 style="color: #fff;font-family: 'Raleway_Medium';">eHMS 2.0</h1><center>
    <center><img width="100%" src="images/loading.gif"/></center>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#pleaseWaitDialog").dialog({ autoOpen: false, width:'400', height:'400', title: false, modal: true});
        $(".ui-dialog-titlebar").hide();
    });

    function showPleaseWaitDialog(){
        $("#pleaseWaitDialog").dialog("open");
    }

    function hidePleaseWaitDialog(){
        $("#pleaseWaitDialog").dialog("close");
    }
</script>

<script language="javascript" type="text/javascript">
    function OpenPopupCenter(pageURL, title, w, h) {
        var left = (screen.width - w) / 2;
        var top = (screen.height - h) / 4;  // for 25% - devide by 4  |  for 33% - devide by 3
        var targetWin = window.open(pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
    }
</script>

<a id="linkDynamic" target="_blank" href="#"></a>
<script language="javascript" type="text/javascript">
    function OpenNewTab(href) {
        document.getElementById('linkDynamic').href = href;
        document.getElementById('linkDynamic').click();
    }
</script>

<div id="areYouSure" style="z-index:99999;"></div>
<script type="text/javascript">
    function areYouSure(message, okButtonTitle, okButtonFunction, cancelButtonTitle, cancelButtonFunction){
        $("#areYouSure").dialog({ autoOpen: false, width:'400', height:'150', title: false, modal: true});

        var areYouSureHtml = message + "<br/><br/>";

        if (cancelButtonTitle != "") {
            areYouSureHtml = areYouSureHtml + "<a href='#' class='art-button-green' style='float: right;' onclick='closeAreYouSure();" + cancelButtonFunction + "'>" + cancelButtonTitle + "<a>";
        }

        if (okButtonTitle != "") {
            areYouSureHtml = areYouSureHtml + "<a href='#' class='art-button-green' style='float: right;' onclick='closeAreYouSure();" + okButtonFunction + "'>" + okButtonTitle + "<a>";
        }

        $("#areYouSure").html(areYouSureHtml);
        $("#areYouSure").dialog("open");
    }

    function closeAreYouSure(){
        $("#areYouSure").dialog("close");
    }
</script>
