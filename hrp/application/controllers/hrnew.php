<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Hrnew extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->database();
		$this->load->helper('url');
                $this->load->model('hr_model', 'HR');
                
                
        }
        
        
        
        
            function getExtension($str) {
        $i = strrpos($str, ".");
        if (!$i) {
            return "";
        }
        $l = strlen($str) - $i;
        $ext = substr($str, $i + 1, $l);
        return $ext;
    }
    
       #upload file

    function upload_file($array, $name, $folder) {
        $filename = time() . $array[$name]['name'];

        $path = './uploads/' . $folder . '/';
        $path1 = './uploads/' . $folder . '/';
        $path = $path . basename($filename);

        if (move_uploaded_file($_FILES[$name]['tmp_name'], $path)) {
            // chmod($path1.$filename, 777);
            return $filename;
        } else {
            return 0;
        }
    }

        
        function applynew($id){
            $this->data['id']= $id;
            
            
      $this->form_validation->set_error_delimiters('<div class="error">', '</div>'); 
      $this->form_validation->set_rules('fname', 'First Name', 'xss_clean|required');
      $this->form_validation->set_rules('mname', 'Middle Name', 'xss_clean');
      $this->form_validation->set_rules('lname', 'Last Name', 'xss_clean|required');
      $this->form_validation->set_rules('email', 'Email', 'xss_clean|valid_email');
      $this->form_validation->set_rules('mobile', 'Mobile', 'xss_clean');
      $this->form_validation->set_rules('dob', 'Date of Birth', 'xss_clean|required|callback_validate_date');
      $this->form_validation->set_rules('sex', 'Gender', 'xss_clean|required');
      $this->form_validation->set_rules('marital', 'Marital Status', 'xss_clean|required');
      $this->form_validation->set_rules('education', 'Education Level', 'xss_clean|required');
       
      $upload_photo = true;
       if($this->input->post('SAVE')){
         if(isset ($_FILES['file']['name']) && $_FILES['file']['name'] !='' ){
            $extension = $this->getExtension($_FILES['file']['name']);
           
            if (($extension != "doc") && ($extension != "docx") && ($extension != "pdf") && ($extension != "txt")) {
                $this->data['photo']='Invalid  format, only doc,docx,pdf and txt is supported';
                $upload_photo = FALSE;
            }               
            
        }else{
             $this->data['photo']='The Application Letter field is required';
                $upload_photo = FALSE;
        }
        
         if(isset ($_FILES['file1']['name']) && $_FILES['file1']['name'] !='' ){
            $extension = $this->getExtension($_FILES['file1']['name']);
             if (($extension != "doc") && ($extension != "docx") && ($extension != "pdf") && ($extension != "txt")) {
                $this->data['photo1']='Invalid  format, only doc,docx,pdf and txt is supported';
                $upload_photo = FALSE;
            }
        }else{
             $this->data['photo1']='The Current CV field is required';
                $upload_photo = FALSE;
        }
        
       }
       
      if ($this->form_validation->run()==TRUE && $upload_photo==TRUE) {
           $app_letter = $this->upload_file($_FILES, 'file', 'application/letter');
           $cv = $this->upload_file($_FILES, 'file1', 'application/cv');
          
          $subdata = array(
             'vacancy_id'=>$id,
             'FirstName'=> ucwords(strtolower(trim($this->input->post('fname')))),
             'MiddleName'=> ucwords(strtolower(trim($this->input->post('mname')))),
             'LastName'=> ucwords(strtolower(trim($this->input->post('lname')))),
             'Sex'=> ucwords(strtolower(trim($this->input->post('sex')))),
             'MaritalStatus'=> $this->input->post('marital'),
             'Email'=> trim($this->input->post('email')),
             'Mobile'=> trim($this->input->post('mobile')),
             'dob'=> trim($this->input->post('dob')),
             'EducationLevel'=> $this->input->post('education'),
             'CV'=> $cv,
             'Letter'=> $app_letter,
             'postdate'=> date('Y-m-d'),
         ); 
          
          $insert = $this->HR->apply_job($subdata);
          
          if($insert){
              $this->session->set_flashdata('message','Thank you for apply this position.');
              redirect('hrnew/applynew/'.$id,'refresh');
          }
      }
            
            
            $this->data['vacancy_info'] = $this->HR->vacancy($id);
            $this->data['marital']=  $this->HR->marital();
            $this->data['educationlevel']=  $this->HR->educationlevel();
            $this->data['content']='newppl/applynew';
            $this->load->view('newppl/template',  $this->data);
        }


        function available(){
            
            $this->data['list']=  $this->HR->vacancy();
            
            $this->data['content']='newppl/newvacancy';
            $this->load->view('newppl/template',  $this->data);
        }
}
?>
