<?php 
use Restserver \Libraries\REST_Controller ; 

Class Service extends REST_Controller{ 
    
    public function __construct(){
        header('Access-Control-Allow-Origin: *');         
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");         
        header("Access-Control-Allow-Headers: Content-Type, ContentLength, Accept-Encoding");         
        parent::__construct();         
        $this->load->model('ServiceModel');         
        $this->load->library('form_validation');     
    }     
    
    public function index_get(){         
        return $this->returnData($this->db->get('service')->result(), false);     
    }     
    
    public function index_post($id = null){         
        $validation = $this->form_validation;         
        $rule = $this->ServiceModel->rules();         
        if($id == null){ 
            array_push($rule,[                     
                'field' => 'name',                     
                'label' => 'name',                     
                'rules' => 'required'                 
            ],                 
            [                     
                'field' => 'price',                     
                'label' => 'price',                     
                'rules' => 'required'                 
            ],                 
            [                     
                'field' => 'type',                     
                'label' => 'type',                     
                'rules' => 'required'                 
            ],                 
                            
                        
            );         
        }         
        else{             
            array_push($rule,                 
            [                     
                'field' => 'name',                     
                'label' => 'name',                     
                'rules' => 'required'                 
            ]             
            );         
        }         
        
        $validation->set_rules($rule);         
        if (!$validation->run()) {             
            return $this->returnData($this->form_validation>error_array(), true);         
        }         
        
        $services = new ServiceData();         
        $services->name = $this->post('name');         
        $services->price = $this->post('price');         
        $services->type = $this->post('type');   
        date_default_timezone_set('Asia/Jakarta');
        $now = date('Y-m-d H:i:s');
        $services->created_at = $now; 

        if($id == null){             
            $response = $this->ServiceModel->store($services); 
        }else{             
            $response = $this->ServiceModel->update($services,$id);         
        }         
        
        return $this->returnData($response['msg'], $response['error']);     
    }     
    
    public function index_delete($id = null){         
        if($id == null){             
            return $this->returnData('Parameter Id Tidak Ditemukan', true);         
        }         
        
        $response = $this->ServiceModel->destroy($id);         
        return $this->returnData($response['msg'], $response['error']);     
    }     
    
    public function returnData($msg,$error){         
        $response['error']=$error;         
        $response['message']=$msg;         
        return $this->response($response);     
    } 
} 

Class ServiceData{     
    public $name;     
    public $price;     
    public $type; 
    public $created_at;
}