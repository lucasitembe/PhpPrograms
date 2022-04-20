<?php
/*writen by gkcchief on 26.11.2017*/
require 'includes/constants.php';
//$DBUSER="backup_user";
//$DBPASSWD="ehms2gpitg2014";
//$DATABASE="ehms_database";
$DBUSER=BACKUP_SERVER_DB_USER;
$DBPASSWD=BACKUP_SERVER_DB_PSSWRD;
$DATABASE=BACKUP_SERVER_DATABASE;
$filename = "ehms_database_backup-" . date("d-m-Y") . ".sql.gz";
$mime = "application/x-gzip";

header( "Content-Type: " . $mime );
header( 'Content-Disposition: attachment; filename="' . $filename . '"' );

//$cmd = "mysqldump -u $DBUSER --password=$DBPASSWD $DATABASE | gzip --best";   
$cmd = "mysqldump -u $DBUSER --password=$DBPASSWD $DATABASE --ignore-table=$DATABASE.tbl_assessment1 | gzip --best";   

passthru( $cmd );

exit(0);
?>
