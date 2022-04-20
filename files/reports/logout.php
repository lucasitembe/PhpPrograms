<?php
session_start();
echo '<a href="./login.php"></a>';
session_destroy();
?>
<script type="text/javascript">
window.location="./index.php"
</script>