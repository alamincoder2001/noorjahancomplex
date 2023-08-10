<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Shop extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->cbrunch = $this->session->userdata('BRANCHid');
        $access = $this->session->userdata('userId');
        if ($access == '') {
            redirect("Login");
        }
        $this->load->model("Model_myclass", "mmc", TRUE);
        $this->load->model('Model_table', "mt", TRUE);
        $this->load->model('SMS_model', 'sms', true);
    }

    public function ShopEntry()
    {
        $access = $this->mt->userAccess();
        if(!$access){
            redirect(base_url());
        }
        $data['title'] = "Office / Shop Entry";
        $data['customerCode'] = $this->mt->generateCustomerCode();
        $data['content'] = $this->load->view('Administrator/shop/add_shop', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function getShop()
    {
        $shops = $this->db->query("
            select 
                s.* ,
                f.floor_name,
                c.Customer_Name as renter_name
            from tbl_shops s
            join tbl_floors f on f.id = s.floor_id
            join tbl_customer c on c.Customer_SlNo = s.renter_id
            where s.status = 'a'
            order by s.id desc
        ")->result();
        echo json_encode($shops);
    }
    public function SaveShop()
    {
        $res = ['success' => false, 'message' => ''];
        try {
            $shopObj = json_decode($this->input->raw_input_stream);

            $query = $this->db->query("select * from tbl_shops where shop_no = ? and status = 'a'", $shopObj->shop_no)->num_rows();
            if($query > 0) {
                $res = ['success' => true, 'message' => 'Shop no already exists'];
                echo json_encode($res);
                exit;
            }

            $shop = (array)$shopObj;
            $shop['status'] = 'a';
            $shop['created_by'] = $this->session->userdata("FullName");
            $shop['created_at'] = date('Y-m-d H:i:s');
            $this->db->insert('tbl_shops', $shop);
            $res = ['success' => true, 'message' => 'Shop added successfully'];
        } catch (\Exception $e) {
            $res = ['success'=>false, 'message'=>$e->getMessage()];
        }
        echo json_encode($res);
    }

    public function shopUpdate()
    {
        $res = ['success' => false, 'message' => ''];
        try {
            $shopObj = json_decode($this->input->raw_input_stream);
            $query = $this->db->query("select * from tbl_shops where shop_no = ? and id != '$shopObj->id' and status = 'a'", $shopObj->shop_no)->num_rows();
            if($query > 0) {
                $res = ['success' => true, 'message' => 'Shop no already exists'];
                echo json_encode($res);
                exit;
            }
            $shop = (array)$shopObj;
            $shopId = $shopObj->id;
            unset($shop['id']);
            $shop['updated_by'] = $this->session->userdata("FullName");
            $shop['updated_at'] = date('Y-m-d H:i:s');
            $this->db->where('id', $shopId)->update('tbl_shops', $shop);
            $res = ['success' => true, 'message' => 'Shop Update successfully'];
        } catch (\Exception $e) {
            $res = ['success'=>false, 'message'=>$e->getMessage()];
        }
        echo json_encode($res);

    }

    public function DeleteShop()
    {
        $res = ['success' => false,  'message' => ''];
        try {
            $data = json_decode($this->input->raw_input_stream);
            $this->db->query("update tbl_shops set status = 'd' where id = ?", $data->shopId);
            $res = ['success' => true, 'message' => 'Shop Deleted!'];
        } catch (\Exception $e) {
            $res = ['success'=>false, 'message'=>$e->getMessage()];
        }
        echo json_encode($res);
    }
    public function Floor()
    {
        $access = $this->mt->userAccess();
        if(!$access){
            redirect(base_url());
        }
        $data['title'] = "Floor Entry";
        $data['content'] = $this->load->view('Administrator/shop/add_floor', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function getFloor()
    {
        $floors = $this->db->query("select * from tbl_floors where status = 'a'")->result();
        echo json_encode($floors);
    }

    public function saveFloor()
    {
        $res = ['success' => false, 'message' => ''];
        try {
            $data = json_decode($this->input->raw_input_stream);
            $floor = (array)$data;
            $floor['status'] = 'a';
            $floor['created_by'] = $this->session->userdata("FullName");
            $floor['created_at'] = date('Y-m-d H:i:s');
            $this->db->insert('tbl_floors', $floor);
            $res = ['success' => true, 'message' => 'Floor Added Successfully!'];
        } catch (\Exception $e) {
            $res = ['success' => false, 'message' => $e->getMessage()];
        }
        echo json_encode($res);
    }

    public function updateFloor()
    {
        $res = ['success' => false, 'message' => ''];
        try {
            $data = json_decode($this->input->raw_input_stream);
            $floor = (array)$data;
            $floorId = $data->id;
            unset($floor['id']);
            $floor['status'] = 'a';
            $floor['updated_by'] = $this->session->userdata("FullName");
            $floor['updated_at'] = date('Y-m-d H:i:s');
            $this->db->where('id', $floorId)->update('tbl_floors', $floor);
            $res = ['success' => true, 'message' => 'Floor Update Successfully!'];
        } catch (\Exception $e) {
            $res = ['success' => false, 'message' => $e->getMessage()];
        }
        echo json_encode($res);
    }

    public function deleteFloor() 
    {
        $res = ['success' => false, 'message' => ''];
        try {
            $data = json_decode($this->input->raw_input_stream);
            $this->db->query("update tbl_floors set status = 'd' where id = ?", $data->floorId);
            $res = ['success' => true, 'message' => 'Floor Deleted!'];
        } catch (\Exception $e) {
            $res = ['success' => false, 'message' => $e->getMessage()];
        }
        echo json_encode($res);
    }
}