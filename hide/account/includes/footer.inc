<?php

/* $Id: footer.inc 6594 2014-03-02 23:15:39Z turbopt $*/

echo '</div>'; // BodyWrapDiv
echo '</div>'; // BodyDiv

echo '<div id="FooterDiv">';
echo '<div id="FooterWrapDiv">';

echo '<br/>';
echo '<div id="FooterTimeDiv" style="text-align:left; font-size:small; color:black">';
	echo DisplayDateTime();
echo '</div>';

echo '<div id="FooterVersionDiv" style="text-align:left; font-size:small; color:black">';
	echo '<b>eHMS ' . _('Version 2.0</b>') . '      ' . _('Copyright') . ' &copy;      ' . Date('Y').' <a target="_blank" href="http://www.gpitg.com" style="text-decoration:none;color:#0079AE"><span><b>GPITG LTD</b></span></a>';
echo '</div>';

echo '</div>'; // FooterWrapDiv
echo '</div>'; // FooterDiv
echo '</div>'; // Canvas

echo '<hr style="width:99.5%">';
echo '<legend style="background-color:#006400; color:white; padding:23px; width:97%; text-align:left">All Rights Reserved</legend>';
echo '</body>';
echo '</html>';

?>