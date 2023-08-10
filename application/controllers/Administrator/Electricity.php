<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Electricity extends CI_Controller
{
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

    public function index()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "Electricity Bill Entry";
        $data['content'] = $this->load->view('Administrator/shop/add_electricity', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function getElectricityBill()
    {
        $data = json_decode($this->input->raw_input_stream);
        $clauses = "";

        if (isset($data->electricityId) && $data->electricityId != '') {
            $clauses .= " and eb.id = '$data->electricityId'";
        }

        if (isset($data->ShopId) && $data->ShopId != '') {
            $clauses .= " and eb.shop_id = '$data->ShopId'";
        }

        if (isset($data->month) && $data->month != '') {
            $clauses .= " and eb.month_id = '$data->month'";
        }

        $data = $this->db->query("
            select 
                eb.*,
                s.shop_name,
                s.shop_no,
                s.master_meter_no,
                s.sub_meter_no,
                f.floor_name,
                m.month_name
            from tbl_electricity_bills eb 
            left join tbl_shops s on s.id = eb.shop_id
            left join tbl_floors f on f.id = s.floor_id
            left join tbl_month m on m.month_id = eb.month_id
            where eb.status = 'a'
            and eb.branch_id = ?
            $clauses
            order by eb.id desc
        ", $this->cbrunch)->result();

        echo json_encode($data);
    }

    public function saveElectricityBill()
    {
        $res = ['success' => false, 'message' => ''];

        try {
            $data = json_decode($this->input->raw_input_stream);
            $MonthId = $data->month_id;
            $ShopId = $data->shop_id;
            $check_exist = $this->db->query("select * from tbl_electricity_bills where month_id = ? and shop_id = ?", [$MonthId, $ShopId])->row();
            if ($check_exist) {
                $res = ['success' => false, 'message' => 'This month bill already generated for this shop'];
                echo json_encode($res);
                exit();
            }
            $electricity = (array)$data;
            $electricity['status'] = 'a';
            $electricity['created_by'] = $this->session->userdata("FullName");
            $electricity['created_at'] = date('Y-m-d H:i:s');
            $electricity['branch_id'] = $this->cbrunch;
            $this->db->insert('tbl_electricity_bills', $electricity);
            $res = ['success' => true, 'message' => 'Electricity Bill Added Successfully!'];
        } catch (\Exception $e) {
            $res = ['success' => false, 'message' => $e->getMessage()];
        }
        echo json_encode($res);
    }

    public function updateElectricityBill()
    {
        $res = ['success' => false, 'message' => ''];

        try {
            $data = json_decode($this->input->raw_input_stream);
            $electricity = (array)$data;
            $electricityId = $data->id;
            unset($electricity['id']);
            $electricity['status'] = 'a';
            $electricity['updated_by'] = $this->session->userdata("FullName");
            $electricity['updated_at'] = date('Y-m-d H:i:s');
            $electricity['branch_id'] = $this->cbrunch;
            $this->db->where('id', $electricityId)->update('tbl_electricity_bills', $electricity);
            $res = ['success' => true, 'message' => 'Electricity Bill Update Successfully!'];
        } catch (\Exception $e) {
            $res = ['success' => false, 'message' => $e->getMessage()];
        }
        echo json_encode($res);
    }

    public function deleteElectricityBill()
    {
        $res = ['success' => false, 'message' => ''];
        try {
            $data = json_decode($this->input->raw_input_stream);
            $this->db->query("update tbl_electricity_bills set status = 'd' where id = ?", $data->electricityId);
            $res = ['success' => true, 'message' => 'Electricity Bill Deleted!'];
        } catch (\Exception $e) {
            $res = ['success' => false, 'message' => $e->getMessage()];
        }
        echo json_encode($res);
    }

    public function getElectricityInvoice($id)
    {
        $data['title'] = "Electricity Bill Invoice";
        $data['ElecId'] = $id;
        $data['content'] = $this->load->view('Administrator/shop/electricity_bill_invoice', $data, true);
        $this->load->view('Administrator/index', $data);
    }

    public function getPreviousUnit()
    {
        $data = json_decode($this->input->raw_input_stream);
        $clauses = "";
        $clauses .= " and eb.shop_id = '$data->ShopId'";
        $data = $this->db->query("
            select 
                eb.current_unit,
                eb.cur_pick_hour_unit,
                eb.cur_off_pick_hour_unit
            from tbl_electricity_bills eb 
            left join tbl_shops s on s.id = eb.shop_id
            where eb.status = 'a'
            and eb.branch_id = ?
            $clauses
            order by eb.id desc
        ", $this->cbrunch)->result();
        echo json_encode($data);
    }

    public function ElectricityBillReport()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "Electricity Bill Report";
        $data['content'] = $this->load->view('Administrator/shop/electricity_bill_report', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }
}
