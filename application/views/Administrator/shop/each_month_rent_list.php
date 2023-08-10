<div class="eachMonthSalary" id="RentReport">
	<div class="row" style="margin-top: 10px;">
		<div class="col-md-12">
			<a href="" @click.prevent="print"><i class="fa fa-print"></i> Print</a>
		</div>
		<div class="col-md-12" id="reportContent">
			<div class="table-responsive" id="reportContent">
				<h3 style="text-align:center; margin:0px;">Rent Sheet</h3>
				<p style="text-align:center;margin-bottom:0px;"><strong>Month : <?php echo $getMonth->month_name ?></strong> </p>
				<table class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th>Sl</th>
							<th>Invoice No</th>
							<th>Month</th>
							<th>Shop Name</th>
							<th>Floor</th>
							<th>Shop No</th>
							<th>Shop Rent</th>
							<th>Electricity Bill</th>
							<th>Gass Bill</th>
							<th>Water Bill</th>
							<th>AC Bill</th>
							<th>Service Charge</th>
							<th>Other Charge</th>
							<th>Toatal Amount</th>
						</tr>
					</thead>
					<tbody>
						<?php
						floatval($totalRent = 0.00);
						floatval($totalElectricity = 0.00);
						floatval($totalGassBill = 0.00);
						floatval($totalWaterBill = 0.00);
						floatval($totalAcBill = 0.00);
						floatval($totalServiceCharge = 0.00);
						floatval($totalOtherCharge = 0.00);
						floatval($TotalAmount = 0.00);
						?>
						<?php foreach ($EachMonthRent as $key => $rent) {
							$totalRent += floatval($rent->shop_rent);
							$totalElectricity += floatval($rent->shop_electricity_bill);
							$totalGassBill += floatval($rent->shop_gass_bill);
							$totalWaterBill += floatval($rent->shop_water_bill);
							$totalAcBill += floatval($rent->ac_bill);
							$totalServiceCharge += floatval($rent->service_charge);
							$totalOtherCharge += floatval($rent->other_charge);
							$TotalAmount += floatval($rent->total_amount);
						?>
							<tr>
								<td><?php echo ++$key ?></td>
								<td><?php echo $rent->invoice_no ?></td>
								<td><?php echo $rent->month_name ?></td>
								<td><?php echo $rent->shop_name ?></td>
								<td><?php echo $rent->floor_name ?></td>
								<td><?php echo $rent->shop_no ?></td>
								<td><?php echo $rent->shop_rent ?></td>
								<td><?php echo $rent->shop_electricity_bill ?></td>
								<td><?php echo $rent->shop_gass_bill ?></td>
								<td><?php echo $rent->shop_water_bill ?></td>
								<td><?php echo $rent->ac_bill ?></td>
								<td><?php echo $rent->service_charge ?></td>
								<td><?php echo $rent->other_charge ?></td>
								<td><?php echo $rent->total_amount ?></td>
							</tr>
						<?php } ?>
					</tbody>
					<tfoot>
						<tr style="font-weight:bold;">
							<td colspan="6" style="text-align:right;">Total:</td>
							<td><?php echo number_format($totalRent, 2) ?></td>
							<td><?php echo number_format($totalElectricity, 2) ?></td>
							<td><?php echo number_format($totalGassBill, 2) ?></td>
							<td><?php echo number_format($totalWaterBill, 2) ?></td>
							<td><?php echo number_format($totalAcBill, 2) ?></td>
							<td><?php echo number_format($totalServiceCharge, 2) ?></td>
							<td><?php echo number_format($totalOtherCharge, 2) ?></td>
							<td><?php echo number_format($TotalAmount, 2) ?></td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>

<script>
	Vue.component('v-select', VueSelect.VueSelect);
	new Vue({
		el: '#RentReport',
		data: {

		},

		methods: {

			async print() {
				let reportContent = `
					<div class="container">
						<div class="row">
							<div class="col-xs-12">
								${document.querySelector('#reportContent').innerHTML}
							</div>
						</div>
					</div>
				`;

				var reportWindow = window.open('', 'PRINT', `height=${screen.height}, width=${screen.width}, left=0, top=0`);
				reportWindow.document.write(`
					<?php $this->load->view('Administrator/reports/reportHeader.php'); ?>
				`);

				reportWindow.document.body.innerHTML += reportContent;

				reportWindow.focus();
				await new Promise(resolve => setTimeout(resolve, 1000));
				reportWindow.print();
				reportWindow.close();
			}
		}
	})
</script>