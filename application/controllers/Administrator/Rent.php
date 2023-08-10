<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rent extends CI_Controller
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

    public function getShopForRentGenerate()
    {
        $data = json_decode($this->input->raw_input_stream);
        $month = $data->month_id;


        $shops = $this->db->query("
            select 
                s.* ,
                f.floor_name,
                c.adjustment_amount,

            (select
            (case when eb.is_pickhour is true
            then ifnull(sum((((eb.cur_pick_hour_unit - eb.prev_pick_hour_unit) * eb.pick_hour_unit_price) + ((eb.cur_off_pick_hour_unit - eb.prev_off_pick_hour_unit) * eb.off_pick_hour_unit_price)) + ((((eb.cur_pick_hour_unit - eb.prev_pick_hour_unit) * eb.pick_hour_unit_price) + ((eb.cur_off_pick_hour_unit - eb.prev_off_pick_hour_unit) * eb.off_pick_hour_unit_price)) * (eb.pick_hour_vat/100))), 0)
            else ifnull(sum(((eb.current_unit - eb.previous_unit) * eb.per_unit_price) + (((eb.current_unit - eb.previous_unit) * eb.per_unit_price) * (eb.pick_hour_vat/100))), 0.00)
            end)
                from tbl_electricity_bills eb 
                where eb.shop_id = s.id
                and eb.month_id = $month
                and eb.status = 'a') as electricity_bill_amount

            from tbl_shops s
            join tbl_floors f on f.id = s.floor_id
            left join tbl_customer c on c.Customer_SlNo = s.renter_id
            where s.status = 'a'
            and (s.is_rent = 1 or is_sold = 1)
        ")->result();

        array_map(function ($shop) {
            $due = $this->db->query("
                select
                    c.Customer_SlNo,
                    (select ifnull(sum(rg.total_amount), 0.00) + ifnull(c.previous_due, 0.00)
                        from tbl_rent_generate rg 
                        where rg.renter_id = c.Customer_SlNo
                        and rg.Status = 'a') as billAmount,                   

                    (select ifnull(sum(rg.paid_amount), 0.00)
                        from tbl_rent_generate rg
                        where rg.renter_id = c.Customer_SlNo
                        and rg.Status = 'a') as invoicePaid,

                    (select ifnull(sum(cp.CPayment_amount), 0.00) 
                        from tbl_customer_payment cp 
                        where cp.CPayment_customerID = c.Customer_SlNo and cp.CPayment_TransactionType = 'CR'
                        and cp.CPayment_status = 'a') as cashReceived,

                    (select ifnull(sum(cp.CPayment_amount), 0.00) 
                        from tbl_customer_payment cp 
                        where cp.CPayment_customerID = c.Customer_SlNo 
                        and cp.CPayment_TransactionType = 'CP'
                        and cp.CPayment_status = 'a') as paidOutAmount,

                    (select invoicePaid + cashReceived) as paidAmount,

                    (select (billAmount + paidOutAmount) - (paidAmount)) as dueAmount
                    
                from tbl_customer c
                where Customer_SlNo = '$shop->renter_id'
            ")->row();
            return $shop->due = $due->dueAmount;
        }, $shops);

        echo json_encode($shops);
    }

    public function rentGenerate()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $invoice = $this->mt->generateRentInvoice();
        $data['invoice'] = $invoice;
        $data['title'] = "Rent Generate";
        $data['content'] = $this->load->view('Administrator/shop/rent_generate', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function SaveGenerateRent()
    {
        $res = ['success' => false, 'message' => ''];
        try {
            $data = json_decode($this->input->raw_input_stream);
            $MonthId = $data->rent->month_id;
            $generateDate = $data->rent->generate_date;
            $serviceMonth = $data->rent->service_month;

            $BatchArray = [];
            $invoice = $this->mt->generateRentInvoice();
            foreach ($data->cart as $rent) {
                // echo "<pre>";
                // print_r($rent);
                // exit;
                $RentGenerate = array(
                    'invoice_no'            => $invoice,
                    'month_id'              => $MonthId,
                    'generate_date'         => $generateDate,
                    'service_month'         => $serviceMonth,
                    'shop_id'               => $rent->shop_id,
                    'renter_id'             => $rent->renter_id,
                    'floor_id'              => $rent->floor_id,
                    'shop_rent'             => $rent->shop_or_flat_rent,
                    'adjustment_amount'     => $rent->adjustment_amount,
                    'ait_adjustment'        => $rent->ait_adjustment,
                    'shop_electricity_bill' => $rent->electricity_bill_amount,
                    'shop_water_bill'       => $rent->shop_water_bill,
                    'shop_gass_bill'        => $rent->shop_gass_bill,
                    'service_charge'        => $rent->total_service_charge,
                    'ac_bill'               => $rent->ac_bill,
                    'other_charge'          => $rent->other_charge,
                    'total_amount'          => $rent->total_amount,
                    'due_amount'            => $rent->total_amount,
                    'previous_due'          => $rent->previous_due,
                    'comment'               => $rent->comment,
                    'status'                => 'a',
                    'created_by'            => $this->session->userdata("FullName"),
                    'created_at'            => date("Y-m-d H:i:s"),
                    'branch_id'             => $this->session->userdata('BRANCHid')
                );
                $invoice++;
                array_push($BatchArray, $RentGenerate);

                //SMS Send
                $shopInfo = $this->db->query("select * from tbl_shops where id = ?", $rent->shop_id)->row();

                $customerInfo = $this->db->query("select * from tbl_customer where Customer_SlNo = ?", $rent->renter_id)->row();
                $sendToName = $customerInfo->Customer_Name;

                $monthInfo = $this->db->query("select * from tbl_month where month_id = ?", $MonthId)->row();
                $monthName = $monthInfo->month_name;

                $clauses = " and c.Customer_SlNo = '$rent->renter_id'";
                $dueResult = $this->mt->renterDue($clauses);
                $tDue = $dueResult[0]->dueAmount;

                $shopRent  = $rent->shop_or_flat_rent;
                $sCharge = $rent->total_service_charge;
                $eBill = $rent->electricity_bill_amount;
                $acBill = $rent->ac_bill;
                $totalBill = $rent->total_amount;

                $date = '15-' . date('m-y');
                $message = "Shop - {$shopInfo->shop_no}\n{$sendToName},\n{$monthName} বিল পরিশোধে অনুরোধ করা হল। \nবিলের পরিমান: \nPPREV. DUE:- {$tDue}\nSHOP RENT:- {$shopRent}\nS. CHARGE:- {$sCharge}\nELEC BILL: {$eBill}\nAC BILL: {$acBill}\nTOTAL DUE BILL: {$totalBill}. \nপরিশোধের শেষ তারিখঃ {$date} \nলেট ফি- ৫%";

                $recipient = $customerInfo->Customer_Mobile;
                $this->sms->sendSms($recipient, $message);
                //End sms send
            }

            $this->db->insert_batch('tbl_rent_generate', $BatchArray);


            $res = ['success' => true, 'message' => 'Rent Generate successfully'];
        } catch (\Exception $e) {
            $res = ['success' => false, 'message' => $e->getMessage()];
        }
        echo json_encode($res);
    }

    public function CheckGenerateMonth()
    {
        try {
            $data = json_decode($this->input->raw_input_stream);

            $MonthId = $data->month_id;


            $query = $this->db->query("SELECT month_id FROM tbl_rent_generate WHERE month_id = ?", $MonthId);
            if ($query->num_rows() > 0) {
                echo json_encode(['success' => true]);
                exit();
            }
            echo json_encode(['success' => false]);
            exit();
        } catch (\Exception $e) {
            echo json_encode(['success' => false]);
            exit();
        }
    }

    public function getGenerateMonthRent($id)
    {
        $data = $this->db->query("
            select 
                rg.* ,
                m.month_name,
                s.shop_name,
                s.shop_no,
                f.floor_name,                
            (select ifnull(sum((eb.current_unit - eb.previous_unit) * eb.per_unit_price), 0.00)
                from tbl_electricity_bills eb 
                where eb.shop_id = s.id
                and eb.month_id = $id
                and eb.status = 'a') as electricity_bill_amount

            from tbl_rent_generate rg 
            left join tbl_month m on m.month_id = rg.month_id
            left join tbl_shops s on s.id = rg.shop_id
            left join tbl_floors f on f.id = rg.floor_id
            where rg.status = 'a'
            and rg.month_id = ?
        ", $id)->result();

        echo json_encode($data);
    }

    public function UpdateGenerateRent()
    {
        $res = ['success' => false, 'message' => ''];
        try {
            $data = json_decode($this->input->raw_input_stream);
            $MonthId = $data->rent->month_id;
            $generateDate = $data->rent->generate_date;
            $serviceMonth = $data->rent->service_month;

            $this->db->where('month_id', $MonthId)->delete('tbl_rent_generate');

            $BatchArray = [];
            $invoice = $this->mt->generateRentInvoice();
            foreach ($data->rents as $rent) {
                $RentGenerate = array(
                    'invoice_no'            => $invoice,
                    'month_id'              => $MonthId,
                    'generate_date'         => $generateDate,
                    'service_month'         => $serviceMonth,
                    'shop_id'               => $rent->shop_id,
                    'renter_id'             => $rent->renter_id,
                    'floor_id'              => $rent->floor_id,
                    'shop_rent'             => $rent->shop_rent,
                    'adjustment_amount'     => $rent->adjustment_amount,
                    'ait_adjustment'        => $rent->ait_adjustment,
                    'shop_electricity_bill' => $rent->shop_electricity_bill,
                    'shop_water_bill'       => $rent->shop_water_bill,
                    'shop_gass_bill'        => $rent->shop_gass_bill,
                    'service_charge'        => $rent->service_charge,
                    'ac_bill'               => $rent->ac_bill,
                    'other_charge'          => $rent->other_charge,
                    'total_amount'          => $rent->total_amount,
                    'due_amount'            => $rent->total_amount,
                    'comment'               => $rent->comment,
                    'status'                => 'a',
                    'created_by'            => $this->session->userdata("FullName"),
                    'created_at'            => date("Y-m-d H:i:s"),
                    'branch_id'             => $this->session->userdata('BRANCHid')
                );
                $invoice++;
                array_push($BatchArray, $RentGenerate);
            }


            $res = ['success' => true, 'message' => 'Rent Update successfully'];
            $this->db->insert_batch('tbl_rent_generate', $BatchArray);
        } catch (\Exception $e) {
            $res = ['success' => false, 'message' => $e->getMessage()];
        }
        echo json_encode($res);
    }

    public function RentGenerateList()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "Rent Generate List";
        $data['content'] = $this->load->view('Administrator/shop/rent_generate_list', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function getRentGenerateList()
    {
        $data = $this->db->query("
            select 
                rg.*,
                m.month_name,
                (
                    select ifnull(sum(rg.total_amount),0)
                ) as total
            from tbl_rent_generate rg
            left join tbl_month m on m.month_id = rg.month_id
            where rg.status = 'a'
            and rg.branch_id = " . $this->session->userdata('BRANCHid') . "
            group by rg.month_id
            order by rg.id  desc
        ")->result();

        echo json_encode($data);
    }

    public function GetEachMonthRentList($id)
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "Each Month Rent List";

        $data['getMonth'] = $this->db->query("
            select 
                rg.month_id,
                m.month_name
            from tbl_rent_generate rg
            join tbl_month m on m.month_id = rg.month_id
            where rg.status = 'a'
            and rg.month_id = ?
        ", $id)->row();

        $data['EachMonthRent'] = $this->db->query("
            select 
                rg.* ,
                m.month_name,
                s.shop_name,
                s.shop_no,
                f.floor_name
            from tbl_rent_generate rg 
            left join tbl_month m on m.month_id = rg.month_id
            left join tbl_shops s on s.id = rg.shop_id
            left join tbl_floors f on f.id = rg.floor_id
            where rg.status = 'a'
            and rg.month_id = ?
        ", $id)->result();

        $data['content'] = $this->load->view('Administrator/shop/each_month_rent_list', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function rentReport()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $invoice = $this->mt->generateRentInvoice();
        $data['invoice'] = $invoice;
        $data['title'] = "Rent Report";
        $data['content'] = $this->load->view('Administrator/shop/rent_generate_report', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function getRentReport()
    {
        $data = json_decode($this->input->raw_input_stream);
        $clauses = "";

        if (isset($data->ShopId) && $data->ShopId != '') {
            $clauses .= " and rg.shop_id = '$data->ShopId'";
        }

        if (isset($data->FloorId) && $data->FloorId != '') {
            $clauses .= " and rg.floor_id = '$data->FloorId'";
        }

        if (isset($data->month) && $data->month != '') {
            $clauses .= " and rg.month_id = '$data->month'";
        }

        $rents = $this->db->query("
            select 
                rg.* ,
                m.month_name,
                s.shop_name,
                s.shop_no,
                f.floor_name
            from tbl_rent_generate rg 
            left join tbl_month m on m.month_id = rg.month_id
            left join tbl_shops s on s.id = rg.shop_id
            left join tbl_floors f on f.id = rg.floor_id
            where rg.branch_id = ?
            and rg.status = 'a'
            $clauses
            order by rg.id desc
        ", $this->session->userdata('BRANCHid'))->result();

        echo json_encode($rents);
    }

    public function getRentsInvoices()
    {
        $data = json_decode($this->input->raw_input_stream);
        $branchId = $this->session->userdata("BRANCHid");

        $invoices = $this->db->query("
            select * from (
                select 'a' as sequence,
                    'rent' as payment_for,
                    concat(rg.invoice_no, ' - ', m.month_name) as invoice_text,
                    rg.id,
                    rg.invoice_no,
                    rg.due_amount,
                    rg.renter_id,
                    (
                        select ifnull(sum(cp.CPayment_amount), 0)
                        from tbl_customer_payment cp 
                        where cp.rent_id = rg.id
                    ) as payment_amount
                from tbl_rent_generate rg 
                left join tbl_month m on m.month_id = rg.month_id
                where rg.status = 'a'
                and rg.branch_id = '$branchId'

                union

                select 'b' as sequence,
                'sale' as payment_for,
                    concat(ss.Invoice, ' - Shop rent') as invoice_text,
                    ss.Shop_Sale_id as id,
                    ss.Invoice as invoice_no,
                    ss.Due_Amount as due_amount,
                    ss.Renter_Id as renter_id,
                    (
                        select ifnull(sum(cp.CPayment_amount), 0)
                        from tbl_customer_payment cp 
                        where cp.rent_id = ss.Shop_Sale_id
                    ) as payment_amount
                from tbl_shop_sales ss
                where ss.Status = 'a'
                and ss.Brunch_Id = '$branchId'                
                ) as tbl              

            where due_amount != payment_amount
            and renter_id = ?
        ", [$data->customerId])->result();


        $Ginvoices = $this->db->query("SELECT 
                'due' as payment_for,
                'General Invoice' as invoice_text,
                '' as id,
                'general_invoice' as invoice_no,
                c.previous_due as due_amount,
                '' as renter_id,
                    (
                        select ifnull(sum(cp.CPayment_amount), 0)
                        from tbl_customer_payment cp 
                        where cp.CPayment_customerID = c.Customer_SlNo
                        and cp.rent_id is null
                        and cp.CPayment_status = 'a'
                    ) as payment_amount
                from tbl_customer c
                where c.status = 'a'
                and c.Customer_brunchid = ?
                and c.Customer_SlNo = ?", [$branchId, $data->customerId])->row();

        if ($Ginvoices->due_amount != 0) {
            array_push($invoices, $Ginvoices);
        }

        echo json_encode($invoices);
    }

    // public function getAdjustmentDue()
    // {
    //     $data = json_decode($this->input->raw_input_stream);
    //     $clauses = "";
    //     if(isset($data->RentId) && $data->RentId != '') {
    //         $clauses = " and cp.rent_id = '$data->RentI'";
    //     }
    //     $adjustment_due = $this->db->query("
    //         select 
    //             cp.* 
    //         from tbl_customer_payment cp
    //         where cp.CPayment_adjustment_amount != 0
    //         and cp.CPayment_status = 'a'
    //         $clauses
    //     ")->result();

    //     echo json_encode($adjustment_due);
    // }
    public function getInvoiceDue()
    {
        $data = json_decode($this->input->raw_input_stream);

        $clauses = "";
        if (isset($data->RentId) && $data->RentId != '') {
            $clauses .= " and rg.id = '$data->RentId'";
        }

        // if (isset($data->customerId) && $data->customerId != '') {
        //     $dueResult = $this->db->query("
        //     select 
        //         (
        //             select ifnull(sum(cp.CPayment_amount), 0)
        //             from tbl_customer_payment cp
        //             where cp.rent_id = rg.id
        //             and cp.CPayment_status = 'a'
        //         ) as paid,
        //         (
        //             select rg.due_amount - paid
        //         ) as due,
        //         (
        //             select rg.adjustment_amount
        //         ) as adjustment,
        //         (
        //             select rg.shop_electricity_bill
        //         ) as ElectricityBill

        //     from tbl_rent_generate rg
        //     where rg.branch_id = ?
        //     and rg.status = 'a'
        //     $clauses
        // ", $this->cbrunch)->result();
        // }

        $dueResult = $this->db->query("
            select 
                (
                    select ifnull(sum(cp.CPayment_amount), 0)
                    from tbl_customer_payment cp
                    where cp.rent_id = rg.id
                    and cp.CPayment_status = 'a'
                    and cp.CPayment_TransactionType = 'CR'
                ) as paid,
                (
                    select ifnull(sum(cp.CPayment_amount), 0)
                    from tbl_customer_payment cp
                    where cp.rent_id = rg.id
                    and cp.CPayment_status = 'a'
                    and cp.CPayment_TransactionType = 'CP'
                ) as Received,
                (
                    select rg.due_amount + Received - paid
                ) as due,
                (
                    select rg.adjustment_amount
                ) as adjustment,
                (
                    select rg.shop_electricity_bill
                ) as ElectricityBill
            
            from tbl_rent_generate rg
            where rg.branch_id = ?
            and rg.status = 'a'
            $clauses
        ", $this->cbrunch)->result();

        echo json_encode($dueResult);
    }

    public function AdvanceReport()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }

        $data['title'] = "Advance Report";
        $data['content'] = $this->load->view('Administrator/shop/advance_report', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function getRenterAdvance()
    {
        $data = json_decode($this->input->raw_input_stream);
        $clauses = "";
        if (isset($data->customerId) && $data->customerId != '') {
            $clauses .= " and c.Customer_SlNo = '$data->customerId'";
        }
        $advances = $this->db->query("
            select 
                c.Customer_Code,
                c.Customer_Name,
                c.owner_name,
                c.Customer_Address,
                c.Customer_Mobile,
                (
                    select ifnull(sum(ap.amount), 0)
                    from tbl_advance_payments ap 
                    where ap.renter_id = c.Customer_SlNo
                    and status = 'a'
                ) as AdvanceAmount,
                (
                    select ifnull(sum(cp.CPayment_adjustment_amount),0)
                    from tbl_customer_payment cp
                    where cp.CPayment_customerID = c.Customer_SlNo
                    and status = 'a'
                ) as PaidAdvanceAmount,
                (
                    select AdvanceAmount - PaidAdvanceAmount
                ) as RestAdvanceAmount
            from tbl_customer c
            where c.status = 'a'
            and c.Customer_brunchid = ?
            $clauses
        ", $this->cbrunch)->result();

        echo json_encode($advances);
    }


    public function AdvancePaymentReport()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }

        $data['title'] = "Advance Payment Report";
        $data['content'] = $this->load->view('Administrator/shop/advance_payment_report', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function getAdvancePayment()
    {
        $data = json_decode($this->input->raw_input_stream);
        $previousDueQuery = $this->db->query("select ifnull(previous_due, 0.00) as previous_due from tbl_customer where Customer_SlNo = '$data->customerId'")->row();

        $payments = $this->db->query("
        select 
            'a' as sequence,
            rg.id,
            rg.generate_date as date,
            concat('Rents ', rg.invoice_no, ' - ',  m.month_name) as description,
            0.00 as advance,
            rg.adjustment_amount as adjust,
            0.00 as balance
        from tbl_rent_generate rg
        left join tbl_month m on m.month_id = rg.month_id
        where rg.renter_id = '$data->customerId'
        and rg.status = 'a'
        
        UNION
        select 
            'b' as sequence,
            ap.id,
            ap.payment_date as date,
            concat('Adjustment ', ap.details) as description,
            ap.amount as advance,
            0.00 as adjust,
            0.00 as balance
            
        from tbl_advance_payments ap
        where ap.renter_id = '$data->customerId'
        and ap.status = 'a'
        order by date, sequence, id
        ")->result();

        $previousBalance = $previousDueQuery->previous_due;

        foreach ($payments as $key => $payment) {
            $lastBalance = $key == 0 ? $previousDueQuery->previous_due : $payments[$key - 1]->balance;
            $payment->balance = ($lastBalance + $payment->advance) - ($payment->adjust);
        }

        if ((isset($data->dateFrom) && $data->dateFrom != null) && (isset($data->dateTo) && $data->dateTo != null)) {
            $previousPayments = array_filter($payments, function ($payment) use ($data) {
                return $payment->date < $data->dateFrom;
            });

            $previousBalance = count($previousPayments) > 0 ? $previousPayments[count($previousPayments) - 1]->balance : $previousBalance;

            $payments = array_filter($payments, function ($payment) use ($data) {
                return $payment->date >= $data->dateFrom && $payment->date <= $data->dateTo;
            });

            $payments = array_values($payments);
        }

        $res['previousBalance'] = $previousBalance;
        $res['payments'] = $payments;
        echo json_encode($res);
        // $data = json_decode($this->input->raw_input_stream);
        // $clauses = "";
        // if(isset($data->customerId) && $data->customerId != '') {
        //     $clauses .= " and c.Customer_SlNo = '$data->customerId'";
        // }
        // $advances = $this->db->query("
        //     select 
        //         c.Customer_Code,
        //         c.Customer_Name,
        //         c.owner_name,
        //         c.Customer_Address,
        //         c.Customer_Mobile,
        //         ap.payment_date,
        //         ap.payment_type,
        //         ap.amount,
        //         ap.details
        //     from tbl_customer c
        //     left join tbl_advance_payments ap on ap.renter_id = c.Customer_SlNo
        //     where c.status = 'a'
        //     and c.Customer_brunchid = ?
        //     $clauses
        // ", $this->cbrunch)->result();

        // echo json_encode($advances);
    }

    public function getRents()
    {
        $data = json_decode($this->input->raw_input_stream);
        $branchId = $this->session->userdata("BRANCHid");

        $clauses = "";
        if (isset($data->dateFrom) && $data->dateFrom != '' && isset($data->dateTo) && $data->dateTo != '') {
            $clauses .= " and rg.generate_date between '$data->dateFrom' and '$data->dateTo'";
        }

        if (isset($data->userFullName) && $data->userFullName != '') {
            $clauses .= " and rg.created_by = '$data->userFullName'";
        }

        if (isset($data->customerId) && $data->customerId != '') {
            $clauses .= " and rg.id = '$data->customerId'";
        }

        if (isset($data->salesId) && $data->salesId != 0 && $data->salesId != '') {
            $clauses .= " and rg.id = '$data->salesId'";
        }
        $rents = $this->db->query("
            select 
                concat(rg.invoice_no, ' - ', c.Customer_Name) as invoice_text,
                rg.*,
                c.Customer_SlNo,
                c.Customer_Code,
                c.Customer_Name,
                c.Customer_Mobile,
                c.Customer_Address,
                s.shop_name,
                s.shop_no,
                s.shop_or_flat_rent,
                f.floor_name,
                m.month_name,
                br.Brunch_name
            from tbl_rent_generate rg
            left join tbl_customer c on c.Customer_SlNo = rg.renter_id
            left join tbl_shops s on s.id = rg.shop_id
            left join tbl_floors f on f.id = rg.floor_id
            left join tbl_month m on m.month_id = rg.month_id
            left join tbl_brunch br on br.brunch_id = rg.branch_id
            where rg.branch_id = ?
            and rg.status = 'a'
            $clauses
            order by rg.id desc
        ", $branchId)->result();

        $res['rents'] = $rents;

        echo json_encode($res);
    }

    public function sendSmsDayBook()
    {
        $data = json_decode($this->input->raw_input_stream);
        // echo json_encode($data);
        // exit;

        try {
            //SMS Send
            $date = date('Y-m-d');

            $openingBalance = $data->openingBalance;
            $received       = $data->totalReceivedFromCustomers + $data->totalSales + $data->totalCashReceived +    $data->totalReceivedFromSuppliers;
            $payment        = $data->totalEmployeePayments + $data->totalPaidToCustomers + $data->totalPaidToSuppliers + $data->totalPurchase + $data->totalCashPaid;
            $closingBalance = $data->closingBalance;

            $message = "Daily Book: {$date} \nOpening Balance: {$openingBalance}\nReceived: {$received}\nPayment: {$payment}\nClosing Balance: {$closingBalance}";

            $recipient = '8801713680380';
            $this->sms->sendSms($recipient, $message);
            //End sms send
            $res = ['success' => true, 'message' => 'SMS Send successfully'];
        } catch (\Exception $e) {
            $res = ['success' => false, 'message' => 'SMS Send failed'];
        }

        echo json_encode($res);
    }

    public function send_sms_to_renter()
    {
        $data = json_decode($this->input->raw_input_stream);
        try {
            $queryData = $this->db->query("SELECT rg.*,s.*,c.*
            FROM tbl_rent_generate rg
            LEFT JOIN tbl_shops s on s.id = rg.shop_id
            LEFT JOIN tbl_customer c on c.Customer_SlNo = rg.renter_id
            WHERE rg.id = ?
            and rg.status = 'a'", $data->id)->row();

            $recipient  = $queryData->Customer_Mobile;
            $sft        = $queryData->total_sft;
            $sendToName = $queryData->shop_name;
            $monthName  = $queryData->service_month;
            $prevDue    = $queryData->previous_due;
            $shopRent   = $queryData->shop_rent;
            $sCharge    = $queryData->service_charge;
            $eBill      = $queryData->shop_electricity_bill;
            $acBill     = $queryData->ac_bill;
            $totalBill  = $queryData->previous_due + $queryData->due_amount;

            $date = '15-' . date('m-y');
            $message = "Shop - {$queryData->shop_no}-(SFT-{$sft})\n মিঃ {$sendToName},\n{$monthName} বিল পরিশোধে অনুরোধ করা হল। \nবিলের পরিমান: \nPPREV. DUE:- {$prevDue}\nRENT:- {$shopRent}\nS. CHARGE:- {$sCharge}\nELEC BILL: {$eBill}\nAC BILL: {$acBill}\nTOTAL DUE BILL: {$totalBill}. \nপরিশোধের শেষ তারিখঃ {$date} \nলেট ফি- ৫%";
            $this->sms->sendSms($recipient, $message);

            $res = ['success' => true, 'message' => 'SMS Send successfully'];
        } catch (\Exception $e) {
            $res = ['success' => false, 'message' => 'SMS Send failed'];
        }

        echo json_encode($res);
    }
}
