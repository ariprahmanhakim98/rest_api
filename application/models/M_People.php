<?php

class M_people extends CI_Model
{
    public function getdata()
    {
        return $this->db->select('*')->from('people')->get()->result();
    }

    public function postdata($param)
    {
        return $this->db->insert('people', $param);
    }

    public function updatedata($data, $id)
    {
        $dataupdate = array(
            'name' => $data['name'],
            'status' => $data['status'],
            'price' => $data['price'],
            'address' => $data['address'],
        );

        $this->db->where('id', $id);
        return $this->db->update('people', $dataupdate);
    }

    public function searchdata($id)
    {
        // return $id;
        // $this->db->where('id', $id);
        return $this->db->select('*')->from('people')->where('id', $id)->get()->result();
    }

    public function deletedata($id)
    {
        // return $id;
        $this->db->where('id', $id);
        return $this->db->delete('people');
    }

}
