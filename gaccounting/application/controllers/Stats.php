<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Stats extends CI_Controller {

    private $access_type, $NUM_WEEKS, $MONTHS;

    public function __construct() {
        parent::__construct();

        if (!isset($_SESSION['userinfo'])) {
           redirect('account/login?src=' . current_url());
        }

        $this->access_type = $this->session->userdata['userinfo']['access_type'];
        $this->NUM_WEEKS = $this->config->item('NUM_WEEKS');
        $this->MONTHS = $this->config->item('MONTHS');

        $this->load->model('statistics');
    }

    public function index() {
        $data['diseses'] = $this->Getinformations->getDiseases();
        $data['years'] = $this->Getinformations->getYearDistinct();

        if ($this->access_type != null && ( $this->access_type == 'global' || $this->access_type == 'district_wise' || $this->access_type == 'zonal_wise')) {
            $data['facilities'] = $this->Getinformations->getAllFacilites($this->access_type);
        }

        $data['weeks'] = $this->NUM_WEEKS;

        $data['months'] = $this->MONTHS;

        $this->load->view('shared/header.php');
        $this->load->view('stats/index.php', $data);
        $this->load->view('shared/footer.php');
    }

    private function jpgraph($data1y, $data2y, $datax, $graphTitle, $xAxisTitle) {
        //this the the PDF filename that user will get to download
        //$pdfFilePath = "output_pdf_name.pdf";
        //load mPDF library
        $this->load->library('jp_graph');

        if ($xAxisTitle == 'Diseases Codes') {
            $graphTitle = "Diseases Statistics " . $graphTitle;
        }

// Create the graph. These two calls are always required
        $this->jp_graph->graph = new Graph(750, 320, 'auto');
        $this->jp_graph->graph->SetScale("textlin");


        $theme_class = new UniversalTheme;
        $this->jp_graph->graph->SetTheme($theme_class);

        $this->jp_graph->graph->SetBox(false);

        $this->jp_graph->graph->ygrid->SetFill(false);
        $this->jp_graph->graph->yaxis->HideTicks(false, false);
// Setup month as labels on the X-axis 
        $this->jp_graph->graph->xaxis->SetTickLabels($datax);

        $this->jp_graph->graph->xaxis->title->Set($xAxisTitle);
        $this->jp_graph->graph->xaxis->title->SetFont(FF_FONT1, FS_BOLD);

        if ($data2y == 'none') {
            // Create the bar plots

            $barPlot = new BarPlot($data1y);
            // ...and add it to the graPH
            $this->jp_graph->graph->Add($barPlot);
        } else {


// Create the bar plots

            $b1plot = new BarPlot($data1y);
            $b2plot = new BarPlot($data2y);

// Create the grouped bar plot
            $gbplot = new GroupBarPlot(array($b1plot, $b2plot));

// ...and add it to the graPH
            $this->jp_graph->graph->Add($gbplot);
//$this->jp_graph->graph->AddY2($lplot);

            $b1plot->SetColor("#0000CD");
            $b1plot->SetFillColor("#0000CD");
            $b1plot->SetLegend("Male");

            $b2plot->SetColor("#B0C4DE");
            $b2plot->SetFillColor("#B0C4DE");
            $b2plot->SetLegend("Female");
        }

        $this->jp_graph->graph->legend->SetFrameWeight(5);
        $this->jp_graph->graph->legend->SetColumns(6);
        $this->jp_graph->graph->legend->SetColor('#4E4E4E', '#00A78A');

        $band = new PlotBand(VERTICAL, BAND_RDIAG, 18, "max", 'khaki4');
        $band->ShowFrame(true);
        $band->SetOrder(DEPTH_BACK);
        $this->jp_graph->graph->Add($band);

        $this->jp_graph->graph->title->Set($graphTitle);

// Display the graph
        //$this->jp_graph->graph->Stroke();
        $contentType = 'image/png';
        $gdImgHandler = $this->jp_graph->graph->Stroke(_IMG_HANDLER);

        ob_start(); // start buffering
        $this->jp_graph->graph->img->Stream();             // print data to buffer
        $image_data = ob_get_contents();   // retrieve buffer contents
        ob_end_clean();
        // stop buffer

        return "data:image/png;base64," . base64_encode($image_data);
    }

    public function getStatsData() {
        $data = '';
        $this->form_validation->set_data($this->input->get());
        $this->form_validation->set_rules('stat_year', 'Year', 'trim|required|numeric');
        $this->form_validation->set_rules('stat_week', 'Week', 'trim');
        $this->form_validation->set_rules('stat_disease_code', 'Disease', 'trim|callback_disease_check');
        $this->form_validation->set_rules('stat_facility_id', 'Facility', 'trim|required');
        $this->form_validation->set_rules('stat_type', 'Report type', 'trim|required');
        $this->form_validation->set_rules('stat_period', 'Statistics period', 'trim|required');
        $this->form_validation->set_rules('stat_month', 'Month', 'trim');

        $exportType = '';
        if ($this->input->get("export", TRUE) != null) {
            $exportType = $this->input->get("export", TRUE);
        }


        if ($this->form_validation->run() == FALSE) {
            $stat_facility_id = $this->input->get("stat_facility_id", TRUE);
            $data = validation_errors('<li>', '</li>');
            $facinfo = $this->Getinformations->getFacilityInfo($stat_facility_id);

            echo json_encode(array('data' => $data, 'facDetails' => $facinfo));
        } else {
            $display = '';

            $stat_year = $this->input->get("stat_year", TRUE);
            $stat_week = $this->input->get("stat_week", TRUE);
            $stat_disease_code = $this->input->get("stat_disease_code", TRUE);
            $stat_facility_id = $this->input->get("stat_facility_id", TRUE);
            $stat_type = $this->input->get("stat_type", TRUE);
            $stat_period = $this->input->get("stat_period", TRUE);
            $stat_month = $this->input->get("stat_month", TRUE);

            $graphTitle = 'For The Year ' . $stat_year;
            if ($stat_period == 'monthly') {
                $graphTitle .=' Month ' . date('F', mktime(0, 0, 0, $stat_month, 10));
                ;
            } else if ($stat_period == 'weekly') {
                $graphTitle .=' Week ' . $stat_week;
            }

            if ($stat_type == 'cases') { //Graphs of Cases > Numbers of Cases  
                if ($stat_facility_id == 'All') {
                    $facilityIDs = array();
                    if ($this->access_type != null && ( $this->access_type == 'global' || $this->access_type == 'district_wise' || $this->access_type == 'zonal_wise')) {
                        $facilities = $this->Getinformations->getAllFacilites($this->access_type);
                        foreach ($facilities as $row) {
                            $facilityIDs[] = $row->facility_id;
                        }
                    }
                    $result = $this->statistics->getCasesData($stat_year, $stat_week, $stat_disease_code, $stat_facility_id, $stat_type, $stat_period, $stat_month, 'all', $facilityIDs);
                } else {
                    $result = $this->statistics->getCasesData($stat_year, $stat_week, $stat_disease_code, $stat_facility_id, $stat_type, $stat_period, $stat_month, '', '');
                }

                if (is_array($result) && count($result) > 0) {

                    $caselessfrom_male = $result[0]['caselessfrom_male'];
                    $caselessfrom_female = $result[0]['caselessfrom_female'];

                    $deathlessto_male = $result[0]['deathlessto_male'];
                    $deathlessto_female = $result[0]['deathlessto_female'];

                    $caseplusfrom_male = $result[0]['caseplusfrom_male'];
                    $caseplusfrom_female = $result[0]['caseplusfrom_female'];

                    $deathplusfromlessto_male = $result[0]['deathplusfromlessto_male'];
                    $deathplusfromlessto_female = $result[0]['deathplusfromlessto_female'];

                    $data1y = array($caselessfrom_male, $deathlessto_male, $caseplusfrom_male, $deathplusfromlessto_male);


//bar2
                    $data2y = array($caselessfrom_female, $deathlessto_female, $caseplusfrom_female, $deathplusfromlessto_female);

                    $datax = array("Case <5", "Death <5", "Case 5++", "Death 5++");

                    // $this->jpgraph($data1y, $data2y, $datax, $graphTitle);

                    $display = ' <img src="' . $this->jpgraph($data1y, $data2y, $datax, $graphTitle, "Cases") . '" />';
                } else if (result == 0) {
                    $display = '<h3>Insufficient data to plot a graph</h3>';
                } else {
                    $display = '<h3>An error has occured!. Please try again later.</h3>';
                }
            } else if ($stat_type == 'diseases') { //Graph showing most reported disease 
                if ($stat_facility_id == 'All') {
                    $facilityIDs = array();
                    if ($this->access_type != null && ( $this->access_type == 'global' || $this->access_type == 'district_wise' || $this->access_type == 'zonal_wise')) {
                        $facilities = $this->Getinformations->getAllFacilites($this->access_type);
                        foreach ($facilities as $row) {
                            $facilityIDs[] = $row->facility_id;
                        }
                    }
                    $result = $this->statistics->getDisesStat($stat_year, $stat_week, $stat_disease_code, $stat_facility_id, $stat_type, $stat_period, $stat_month, 'all', $facilityIDs);
                } else {
                    $result = $this->statistics->getDisesStat($stat_year, $stat_week, $stat_disease_code, $stat_facility_id, $stat_type, $stat_period, $stat_month, '', '');
                }

                if (is_array($result) && count($result) > 0) {
                    $display = ' <img src="' . $this->jpgraph($result['datay'], 'none', $result['datax'], $graphTitle, "Diseases Codes") . '" />';
                }
            } else {
                $display = '<h3>Invalid Data</h3>';
            }

            //$disesesIDs = $this->Getinformations->getDiseasesIDs();
            //$diseses_codes = $this->Getinformations->getArrayFromObjectData($disesesIDs, 'disease_code');

            if ($stat_facility_id == 'All') {
                $facinfo = "All Facilities";
            } else {
                $facinfo = $this->Getinformations->getFacilityInfo($stat_facility_id);
            }

            if (!empty($exportType)) {   
                if ($exportType == 'pdf') {
                    $this->mpdf($display, $facinfo);
                } else if ($exportType == 'excel') {
                    $this->excel($display, $facinfo);
                }
            } else {
                echo json_encode(array('data' => $display, 'facDetails' => $facinfo));
            }
        }
    }

    private function mpdf($display, $facinfo) {
        //this the the PDF filename that user will get to download
        $data = "<h1 style='text-align:center;'>INFECTIOUS DISEASES WEEKLY ENDING</h1>";
        $data .='<h5 style="text-align:center;font-weight:200">' . $facinfo . '</h5><br/>';
        $data .=$display;

        $pdfFilePath = "statistics.pdf";
        $param[] = false;
        //load mPDF library
        $this->load->library('m_pdf', $param);

        //generate the PDF from the given html
        $stylesheet = file_get_contents(base_url() . 'assets/css/mpdf.css'); // external css
        $this->m_pdf->pdf->WriteHTML($stylesheet, 1);
        $this->m_pdf->pdf->WriteHTML($data, 2);



        //download it.
        $this->m_pdf->pdf->Output($pdfFilePath, "D");
    }

    private function excel($display, $facinfo) {
        $file = "statistics.xls";

        $data = "<h1 style='text-align:center;'>INFECTIOUS DISEASES WEEKLY ENDING</h1>";
        $data .='<h5 style="text-align:center;font-weight:200">' . $facinfo . '</h5><br/>';
        $data .=$display;

        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$file");

//The header for .xlsx files is Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet

        echo $data;
    }

    public function disease_check($disese) {
        if ($this->input->get("stat_type", TRUE) == 'cases' && empty($disese)) {
            $this->form_validation->set_message('disease_check', 'The {field} field is required');
            return FALSE;
        } else {
            return TRUE;
        }
    }

}
