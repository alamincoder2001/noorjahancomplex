<style>
	.v-select {
		margin-bottom: 5px;
	}

	.v-select .dropdown-toggle {
		padding: 0px;
	}

	.v-select input[type=search],
	.v-select input[type=search]:focus {
		margin: 0px;
	}

	.v-select .vs__selected-options {
		overflow: hidden;
		flex-wrap: nowrap;
	}

	.v-select .selected-tag {
		margin: 2px 0px;
		white-space: nowrap;
		position: absolute;
		left: 0px;
	}

	.v-select .vs__actions {
		margin-top: -5px;
	}

	.v-select .dropdown-menu {
		width: auto;
		overflow-y: auto;
	}
</style>
<div class="row" id="customerPaymentReport">
	<div class="col-xs-12 col-md-12 col-lg-12" style="border-bottom:1px #ccc solid;">
		<div class="form-group">
			<label class="col-sm-1 control-label no-padding-right"> Renter </label>
			<div class="col-sm-2">
				<v-select v-bind:options="customers" v-model="selectedCustomer" label="display_name"></v-select>
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-1 control-label no-padding-right"> Date from </label>
			<div class="col-sm-2">
				<input type="date" class="form-control" v-model="dateFrom">
			</div>
			<label class="col-sm-1 control-label no-padding-right text-center" style="width:30px"> to </label>
			<div class="col-sm-2">
				<input type="date" class="form-control" v-model="dateTo">
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-1">
				<input type="button" class="btn btn-primary" value="Show" v-on:click="getReport" style="margin-top:0px;border:0px;height:28px;">
			</div>
		</div>
	</div>

	<div class="col-sm-12" style="display:none;" v-bind:style="{display: showTable ? '' : 'none'}">
		<a href="" style="margin: 7px 0;display:block;width:50px;" v-on:click.prevent="print">
			<i class="fa fa-print"></i> Print
		</a>
		<div class="table-responsive" id="reportTable">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th style="text-align:center">Particulars</th>
						<th style="text-align:center">AC Bill</th>
						<th style="text-align:center">Electricity</th>
						<th style="text-align:center">Service</th>
						<th style="text-align:center">Shop Rent</th>
						<th style="text-align:center">Shop Sale</th>
						<th style="text-align:center">Due Amount</th>
						<th style="text-align:center">late Fee</th>
						<th style="text-align:center">Total Due</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="payment in payments">
						<td style="text-align:left;">{{ payment.Customer_Code }} - {{ payment.Customer_Name }} - {{ payment.Customer_Mobile }}</td>
						<td style="text-align:right;">{{ parseFloat(payment.ac_bill).toFixed(2) }}</td>
						<td style="text-align:right;">{{ parseFloat(payment.electricity_bill).toFixed(2) }}</td>
						<td style="text-align:right;">{{ parseFloat(payment.service_charge).toFixed(2) }}</td>
						<td style="text-align:right;">{{ parseFloat(payment.shop_rent).toFixed(2) }}</td>
						<td style="text-align:right;">{{ parseFloat(payment.shop_sale_amt).toFixed(2) }}</td>
						<td style="text-align:right;">{{ parseFloat(payment.due_amount).toFixed(2) }}</td>
						<td style="text-align:right;">{{ parseFloat(payment.Interest).toFixed(2) }}</td>
						<td style="text-align:right;">{{ parseFloat(payment.total).toFixed(2) }}</td>
					</tr>
					<tr style="font-weight: bold">
						<td style="text-align:left;">Grand Total = </td>
						<td style="text-align:right;">{{ payments.reduce((prev,curr)=>{return prev + +curr.ac_bill},0).toFixed(2) }}</td>
						<td style="text-align:right;">{{ payments.reduce((prev,curr)=>{return prev + +curr.electricity_bill},0).toFixed(2) }}</td>
						<td style="text-align:right;">{{ payments.reduce((prev,curr)=>{return prev + +curr.service_charge},0).toFixed(2) }}</td>
						<td style="text-align:right;">{{ payments.reduce((prev,curr)=>{return prev + +curr.shop_rent},0).toFixed(2) }}</td>
						<td style="text-align:right;">{{ payments.reduce((prev,curr)=>{return prev + +curr.shop_sale_amt},0).toFixed(2) }}</td>
						<td style="text-align:right;">{{ payments.reduce((prev,curr)=>{return prev + +curr.due_amount},0).toFixed(2) }}</td>
						<td style="text-align:right;">{{ payments.reduce((prev,curr)=>{return prev + +curr.Interest},0).toFixed(2) }}</td>
						<td style="text-align:right;">{{ payments.reduce((prev,curr)=>{return prev + +curr.total},0).toFixed(2) }}</td>
					</tr>
				</tbody>
			</table>
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
		el: '#customerPaymentReport',
		data() {
			return {
				customers: [],
				selectedCustomer: null,
				dateFrom: moment().format('YYYY-MM-DD'),
				dateTo: moment().format('YYYY-MM-DD'),
				payments: [],
				showTable: false
			}
		},
		created() {
			this.getCustomers();
		},
		methods: {
			getCustomers() {
				axios.get('/get_customers').then(res => {
					this.customers = res.data;
				})
			},
			getReport() {
				let data = {
					dateFrom: this.dateFrom,
					dateTo: this.dateTo,
					customerId: (this.selectedCustomer == null || this.selectedCustomer.Customer_SlNo == '') ? '' : this.selectedCustomer.Customer_SlNo
				}

				axios.post('/get_cost_center_column_wise', data).then(res => {
					this.payments = res.data;
					this.showTable = true;
				})
			},
			async print() {
				let reportContent = `
					<div class="container">
						<h4 style="text-align:center">Cost Center Report</h4 style="text-align:center">
						<div class="row">
							<div class="col-xs-6" style="font-size:12px;">
							</div>
							<div class="col-xs-6 text-right">
								<strong>Statement from</strong> ${this.dateFrom} <strong>to</strong> ${this.dateTo}
							</div>
						</div>
					</div>
					<div class="container">
						<div class="row">
							<div class="col-xs-12">
								${document.querySelector('#reportTable').innerHTML}
							</div>
						</div>
					</div>
				`;

				var mywindow = window.open('', 'PRINT', `height=${screen.height}, width=${screen.width}`);
				mywindow.document.write(`
					<?php $this->load->view('Administrator/reports/reportHeader.php'); ?>
				`);

				mywindow.document.body.innerHTML += reportContent;

				mywindow.focus();
				await new Promise(resolve => setTimeout(resolve, 1000));
				mywindow.print();
				mywindow.close();
			}
		}
	})
</script>