<style>
	.v-select {
		margin-bottom: 5px;
		float: right;
		min-width: 200px;
		margin-left: 5px;
	}

	.v-select .dropdown-toggle {
		padding: 0px;
		height: 25px;
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

	#salaryReport label {
		font-size: 13px;
		margin-top: 3px;
	}

	#salaryReport select {
		border-radius: 3px;
		padding: 0px;
		font-size: 13px;
	}

	#salaryReport .form-group {
		margin-right: 10px;
	}
</style>

<div id="salaryReport">
	<div class="row" style="border-bottom:1px solid #ccc;padding: 10px 0;">
		<div class="col-md-12">
			<form class="form-inline" @submit.prevent="getRentReport">
				<div class="form-group">
					<label>Month</label>
					<select class="form-control" style="min-width:150px;" v-bind:style="{display: months.length > 0 ? 'none' : ''}"></select>
					<v-select v-bind:options="months" v-model="selectedMonth" label="month_name" style="display:none" v-bind:style="{display: months.length > 0 ? '' : 'none'}"></v-select>
				</div>
				<div class="form-group">
					<label>Floor</label>
					<v-select v-bind:options="floors" v-model="selectedFloor" label="floor_name" style="display:none" v-bind:style="{display: floors.length > 0 ? '' : 'none'}"></v-select>
				</div>
				<div class="form-group">
					<label>Shop Name</label>
					<v-select v-bind:options="shops" v-model="selectedShop" label="shop_name" style="display:none" v-bind:style="{display: shops.length > 0 ? '' : 'none'}"></v-select>
				</div>

				<div class="form-group" style="margin-top: -5px;">
					<input type="submit" class="search-button" value="Search">
				</div>
			</form>
		</div>
	</div>

	<div class="row" style="margin-top: 10px;display:none;" v-bind:style="{display: payments.length > 0 ? '' : 'none'}">
		<div class="col-md-12">
			<a href="" @click.prevent="print"><i class="fa fa-print"></i> Print</a>
		</div>
		<div class="col-md-12">
			<div class="table-responsive" id="reportContent">
				<div>
					<h3 style="text-align:center;">Rent Bill Records</h3>
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
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(payment, sl) in payments">
								<td>{{ sl + 1 }}</td>
								<td>{{ payment.invoice_no }}</td>
								<td>{{ payment.month_name }}</td>
								<td>{{ payment.shop_name }}</td>
								<td>{{ payment.floor_name }}</td>
								<td>{{ payment.shop_no }}</td>
								<td>{{ payment.shop_rent }}</td>
								<td>{{ payment.shop_electricity_bill }}</td>
								<td>{{ payment.shop_gass_bill }}</td>
								<td>{{ payment.shop_water_bill }}</td>
								<td>{{ payment.ac_bill }}</td>
								<td>{{ payment.service_charge }}</td>
								<td>{{ payment.other_charge }}</td>
								<td>{{ payment.total_amount }}</td>
								<td>
									<a v-bind:href="`/sale_invoice_print/${payment.id}`" target="_blank" title="invoice"><i class="fa fa-file"></i></a>
									<a href="" @click.prevent="sendSMS(payment.id)" title="Send SMS">
										<i class="fa fa-envelope"></i>
									</a>
								</td>
							</tr>
						</tbody>
						<!-- <tfoot>
							<tr style="font-weight:bold;">
								<td colspan="7" style="text-align:right;">Total</td>
								<td style="text-align:right;">{{ payments.reduce((prev, curr) => { return prev + parseFloat(curr.payment_amount)}, 0).toFixed(2) }}</td>
								<td style="text-align:right;">{{ payments.reduce((prev, curr) => { return prev + parseFloat(curr.deduction_amount)}, 0).toFixed(2) }}</td>
							</tr>
						</tfoot> -->
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
		el: '#salaryReport',
		data() {
			return {
				months: [],
				selectedMonth: null,
				floors: [],
				selectedFloor: null,
				shops: [],
				selectedShop: [],
				payments: [],
			}
		},
		computed: {
			comEmployees() {
				return this.employees.map(employee => {
					employee.display_text = employee.Employee_SlNo == '' ? employee.Employee_Name : `${employee.Employee_Name} - ${employee.Employee_ID}`;
					return employee;
				})
			}
		},
		created() {
			this.getShop();
			this.getMonths();
			this.getFloor();
		},
		methods: {
			sendSMS(id) {
				console.log(id);
				axios.post("send_sms_to_renter", {
					id: id
				}).then(res => {
					alert(res.data.message);
				})
			},

			getMonths() {
				axios.get('/get_months').then(res => {
					this.months = res.data;
					this.months.unshift({
						month_id: '',
						month_name: 'All'
					})
				})
			},
			getFloor() {
				axios.get('/get_floor').then(res => {
					this.floors = res.data;
					this.floors.unshift({
						id: '',
						floor_name: 'All'
					})
				})
			},
			getShop() {
				axios.get('/get_shop').then(res => {
					this.shops = res.data;
					this.shops.unshift({
						id: '',
						shop_name: 'All'
					})
				})
			},
			getRentReport() {
				let data = {}
				if (this.selectedFloor == null) {
					data.FloorId = '';
				} else {
					data.FloorId = this.selectedFloor.id;
				}

				if (this.selectedShop == null) {
					data.ShopId = '';
				} else {
					data.ShopId = this.selectedShop.id;
				}

				if (this.selectedMonth == null) {
					data.month = '';
				} else {
					data.month = this.selectedMonth.month_id;
				}
				axios.post('/get_rent_report', data)
					.then(res => {
						this.payments = res.data;
					})
			},

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