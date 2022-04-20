<?php
@session_start();

$htm = '<table align="center" width="100%">
                <tr><td style="text-align:center"><img src="../branchBanner/branchBanner.png"></td></tr>
                <tr><td style="text-align:center"><b>ICU</b></td></tr>
            </table>';


$htm .= '<table width=100% border=1 style="border-collapse: collapse;">
                <thead>
                    <tr>
                        <td style="width:50px"><b>SN</b></td>
                        <td><b>Patient Name</b></td>
                        <td>07:00</td>
                        <td>08:00</td>
                        <td>09:00</td>
                        <td>10:00</td>
                        <td>11:00</td>
                        <td>12:00</td>
                        <td>13:00</td>
                        <td>14:00</td>
                        <td>15:00</td>
                        <td>16:00</td>
                        <td>17:00</td>
                        <td>18:00</td>
                        <td>19:00</td>
                        <td>20:00</td>
                        <td>21:00</td>
                        <td>22:00</td>
                        <td>23:00</td>
                        <td>00:00</td>
                        <td>01:00</td>
                        <td>02:00</td>
                        <td>03:00</td>
                        <td>04:00</td>
                        <td>05:00</td>
                        <td>06:00</td>
                        <td><b>Date</b>
                    </td>
                </tr>
            </thead>';


$htm .= "</table>";
include("../MPDF/mpdf.php");
$mpdf = new mPDF('utf-8', 'A4-L', '', '', '12', '12', '6');
$mpdf->SetDisplayMode('fullpage');
$mpdf->SetFooter('Printed By ' . ucwords(strtolower("Sample Name")) . '  {DATE d-m-Y}|Page {PAGENO} of {nb}| Powered By eHMS');
$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('mpdfstyletables.css');
$mpdf->WriteHTML($stylesheet, 1);    // The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($htm, 2);

$mpdf->Output('mpdf.pdf', 'I');
exit;