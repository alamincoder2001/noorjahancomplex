<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Report</title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
    <style>
        html,
        body {
            width: 500px !important;
        }

        .container {
            width: 500px !important;
        }

        body,
        table {
            font-size: 13px;
        }

        table th {
            text-align: center;
        }
    </style>
</head>

<body>
    <?php
    $branchId = $this->session->userdata('BRANCHid');
    $companyInfo = $this->Billing_model->company_branch_profile($branchId);
    ?>
    <div class="container">
        <div class="row">
            <div class="col-xs-12" style="margin-top:0px; text-align:center;">
                <strong style="font-size:24px; padding-bottom:15px;letter-spacing: 1px;"><?php echo $companyInfo->Company_Name; ?></strong><br>
                <p style="white-space:pre-line;font-size:17px; font-weight: 600;letter-spacing: 1px;"><?php echo $companyInfo->Repot_Heading; ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div style="border-bottom: 2px solid #000; margin-bottom:7px;"></div>
            </div>
        </div>
    </div>
</body>

</html>