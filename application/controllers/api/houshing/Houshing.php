<?php

class Houshing extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('M_houshing', 'houshing');
        $this->load->library('form_validation');
    }

    public function getdata()
    {
        $status = false;
        $result = null;
        $message = 'Error_error!!';
        $page = $this->input->post('current_page');
        $limit = $this->input->post('limit');
        $count = 0;
		$totalPages = null;
		$offset = ($page - 1) * $limit;

		$result = $this->houshing->getdata($limit, $offset);
        
        if ($result == null) {
            $message = 'Data tidak ditemukan!';
        } else {
            $status = true;
            $message = 'Successfull!';
            $resultcount = $this->houshing->getdatatotals();
			$count = count($resultcount);
			$totalPages = ceil($count / $limit);
        }

        header('Content-Type: application/json');

        echo json_encode(
            array(
                'status' => $status,
                'message' => $message,
                'data' => $result,
				'page_on' => $page,
				'total_page' => $totalPages,
				'limit_data' => $limit,
				'total_data' => $count
            )
        );
    }

    public function postdata()
    {
        $status = false;
        $message = null;
        $data = '[]';
        $name = null;
        $price = null;
        $address = null;

        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required');
        $this->form_validation->set_rules('price', 'Price', 'required');
        $this->form_validation->set_rules('address', 'Address', 'required');

        if ($this->form_validation->run() == FALSE) {
            $message = 'Failed';
            $nm = $this->input->post('name') == null;
            $sts = $this->input->post('status') == null;
            $pr = $this->input->post('price') == null;
            $add = $this->input->post('address') == null;

            if($nm == true){
                $name = "Name required";
            }
            if($sts == true){
                $status = "Status required";
            }
            if($pr == true){
                $price = "Price required";
            }
            if($add == true){
                $address = "Address required";
            }
            
        } else {
            $this->m_people->postdata($this->input->post());
            $status = true;
            $message = 'Successfull!';
            $data = $this->input->post();
        }

        header('Content-Type: application/json');

        $err = array(            
            'name' => $name,
            'status' => $status,
            'price' => $price,
            'address' => $address
        );

        if($name == null){
            unset($err['name']);
        } 
        if($status == null){
            unset($err['status']);
        }
        if($price == null){
            unset($err['price']);
        } 
        if($address == null){
            unset($err['address']);
        }

        echo json_encode(
            array(
                'status' => $status,
                'message' => $message,
                'data' => $data,
                'err' => $err,
            )
        );
    }

    public function updatedata()
    {
        $status = false;
        $message = 'Update Failed!';
        $data = null;

        $id = $this->input->post('id');
        $data = $this->m_people->searchdata($id);

        if ($data == null) {
            $message = 'Data tidak ditemukan!';
            $result = '{}';
        } else {
            $this->m_people->updatedata($this->input->post(), $id);
            $status = true;
            $message = 'Successfull!';
            $result = $this->input->post();
        }

        header('Content-Type: application/json');

        echo json_encode(
            array(
                'status' => $status,
                'message' => $message,
                'data' => $result
            )
        );
    }

    public function searchdata()
    {
        $status = false;
        $result = null;
        $message = 'Error_error!!';

        $id = $this->input->get('id');
        $data = $this->m_people->searchdata($id);

        if ($data == null) {
            $message = 'Data tidak ditemukan!';
            $result = '{}';
        } else {
            $status = true;
            $message = 'Successfull!';
            $result = $data;
        }

        header('Content-Type: application/json');

        echo json_encode(
            array(
                'status' => $status,
                'message' => $message,
                'data' => $result
            )
        );
    }

    public function deletedata()
    {
        $status = false;
        $message = 'Delete Failed!';
        $data = null;

        $id = $this->input->get('id');
        $datax = $this->m_people->searchdata($id);

        if ($datax == null) {
            $message = 'Data tidak ditemukan!';
        } else {
            $this->m_people->deletedata($id);
            $status = true;
            $message = 'Successfull!';
        }

        header('Content-Type: application/json');

        echo json_encode(
            array(
                'status' => $status,
                'message' => $message
            )
        );
    }
}
