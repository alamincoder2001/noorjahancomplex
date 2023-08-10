<?php
    class Graph extends CI_Controller{
        public function __construct(){
            parent::__construct();
            $access = $this->session->userdata('userId');
            $this->branchId = $this->session->userdata('BRANCHid');
            if($access == '' ){
                redirect("Login");
            }
            $this->load->model('Model_table', "mt", TRUE);
        }
        
        public function graph(){
            $access = $this->mt->userAccess();
            if(!$access){
                redirect(base_url());
            }
            $data['title'] = "Graph";
            $data['content'] = $this->load->view('Administrator/graph/graph', $data, true);
            $this->load->view('Administrator/index', $data);
        }

        public function getGraphData(){
            // Monthly Record
            $monthlyRecord = [];
            $year = date('Y');
            $month = date('m');
            $dayNumber = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            for($i = 1; $i <= $dayNumber; $i++){
                $date = $year . '-' . $month . '-'. sprintf("%02d", $i);
                $query = $this->db->query("
                    select ifnull(sum(rg.total_amount), 0) as sales_amount 
                    from tbl_rent_generate rg 
                    where rg.generate_date = ?
                    and rg.status = 'a'
                    and rg.branch_id = ?
                    group by rg.generate_date
                ", [$date, $this->branchId]);

                $amount = 0.00;

                if($query->num_rows() == 0){
                    $amount = 0.00;
                } else {
                    $amount = $query->row()->sales_amount;
                }
                $sale = [sprintf("%02d", $i), $amount];
                array_push($monthlyRecord, $sale);
            }

            $yearlyRecord = [];
            for($i = 1; $i <= 12; $i++) {
                $yearMonth = $year . sprintf("%02d", $i);
                $query = $this->db->query("
                    select ifnull(sum(rg.total_amount), 0) as sales_amount 
                    from tbl_rent_generate rg 
                    where extract(year_month from rg.generate_date) = ?
                    and rg.status = 'a'
                    and rg.branch_id = ?
                    group by extract(year_month from rg.generate_date)
                ", [$yearMonth, $this->branchId]);

                $amount = 0.00;
                $monthName = date("M", mktime(0, 0, 0, $i, 10));

                if($query->num_rows() == 0){
                    $amount = 0.00;
                } else {
                    $amount = $query->row()->sales_amount;
                }
                $sale = [$monthName, $amount];
                array_push($yearlyRecord, $sale);
            }

            // Sales text for marquee
            $sales = $this->db->query("
                select 
                    concat(
                        'Invoice: ', rg.invoice_no,
                        ', Customer: ', c.Customer_Code, ' - ', c.Customer_Name,
                        ', Amount: ', rg.total_amount,
                        ', Paid: ', rg.paid_amount,
                        ', Due: ', rg.due_amount
                    ) as sale_text
                from tbl_rent_generate rg 
                join tbl_customer c on c.Customer_SlNo = rg.renter_id
                where rg.status = 'a'
                and rg.branch_id = ?
                order by rg.id desc limit 20
            ", $this->branchId)->result();

            // Today's Sale
            $todaysSale = $this->db->query("
                select 
                    ifnull(sum(ifnull(rg.total_amount, 0)), 0) as total_amount
                from tbl_rent_generate rg
                where rg.status = 'a'
                and rg.generate_date = ?
                and rg.branch_id = ?
            ", [date('Y-m-d'), $this->branchId])->row()->total_amount;

            // This Month's Sale
            $thisMonthSale = $this->db->query("
                select 
                    ifnull(sum(ifnull(rg.total_amount, 0)), 0) as total_amount
                from tbl_rent_generate rg
                where rg.status = 'a'
                and month(rg.generate_date) = ?
                and year(rg.generate_date) = ?
                and rg.branch_id = ?
            ", [date('m'), date('Y'), $this->branchId])->row()->total_amount;

            // Today's Cash Collection
            $todaysCollection = $this->db->query("
                select 
                    ifnull((
                        select sum(ifnull(rg.paid_amount, 0)) 
                        from tbl_rent_generate rg
                        where rg.status = 'a'
                        and rg.branch_id = " . $this->branchId . "
                        and rg.generate_date = '" . date('Y-m-d') . "'
                    ), 0) +
                    ifnull((
                        select sum(ifnull(cp.CPayment_amount, 0)) 
                        from tbl_customer_payment cp
                        where cp.CPayment_status = 'a'
                        and cp.CPayment_brunchid = " . $this->branchId . "
                        and cp.CPayment_date = '" . date('Y-m-d') . "'
                    ), 0) +
                    ifnull((
                        select sum(ifnull(ct.In_Amount, 0)) 
                        from tbl_cashtransaction ct
                        where ct.status = 'a'
                        and ct.Tr_branchid = " . $this->branchId . "
                        and ct.Tr_date = '" . date('Y-m-d') . "'
                    ), 0) as total_amount
            ")->row()->total_amount;

            // Cash Balance
            $cashBalance = $this->mt->getTransactionSummary()->cash_balance;

            // Top Customers
            $topCustomers = $this->db->query("
                select 
                    c.Customer_Name as customer_name,
                    ifnull(sum(rg.total_amount), 0) as amount
                from tbl_rent_generate rg 
                join tbl_customer c on c.Customer_SlNo = rg.renter_id
                where rg.branch_id = ?
                group by rg.renter_id
                order by amount desc 
                limit 5
            ", $this->branchId)->result();

            // Top Products
            $topProducts = $this->db->query("
                select 
                    p.Product_Name as product_name,
                    ifnull(sum(sd.SaleDetails_TotalQuantity), 0) as sold_quantity
                from tbl_saledetails sd
                join tbl_product p on p.Product_SlNo = sd.Product_IDNo
                group by sd.Product_IDNo
                order by sold_quantity desc
                limit 5
            ")->result();

            // Customer Due
            $customerDueResult = $this->mt->customerDue();
            $customerDue = array_sum(array_map(function($due) {
                return $due->dueAmount;
            }, $customerDueResult));

            // Bank balance
            $bankTransactions = $this->mt->getBankTransactionSummary();
            $bankBalance = array_sum(array_map(function($bank){
                return $bank->balance;
            }, $bankTransactions));

            $responseData = [
                'monthly_record' => $monthlyRecord,
                'yearly_record' => $yearlyRecord,
                'sales_text' => $sales,
                'todays_sale' => $todaysSale,
                'this_month_sale' => $thisMonthSale,
                'todays_collection' => $todaysCollection,
                'cash_balance' => $cashBalance,
                'top_customers' => $topCustomers,
                'top_products' => $topProducts,
                'customer_due' => $customerDue,
                'bank_balance' => $bankBalance
            ];

            echo json_encode($responseData, JSON_NUMERIC_CHECK);
        }
    }
?>