<style>
	.v-select {
		margin-bottom: 5px;
	}

	.v-select.open .dropdown-toggle {
		border-bottom: 1px solid #ccc;
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

	#Electricity label {
		font-size: 13px;
	}

	#Electricity select {
		border-radius: 3px;
	}

	#Electricity .add-button {
		padding: 2.5px;
		width: 28px;
		background-color: #298db4;
		display: block;
		text-align: center;
		color: white;
		border-radius: 3px;
	}

	#Electricity .add-button:hover {
		background-color: #41add6;
		color: white;
	}

	#Electricity input[type="file"] {
		display: none;
	}

	#Electricity .custom-file-upload {
		border: 1px solid #ccc;
		display: inline-block;
		padding: 5px 12px;
		cursor: pointer;
		margin-top: 5px;
		background-color: #298db4;
		border: none;
		color: white;
	}

	#Electricity .custom-file-upload:hover {
		background-color: #41add6;
	}

	#customerImage {
		height: 100%;
	}

	.pagination {
		margin: 5px 0;
	}

	.btn-EduAdd {
		padding: 2px 7px;
		background: #B74635 !important;
		border: none !important;
		border-radius: 3px;
		float: right;
	}

	.advance_heading,
	.renter_heading {
		background: #DDDDDD;
		padding: 5px;
		font-size: 12px;
		color: #323A89;
	}

	.information {
		/* border: 1px solid #89AED8; */
		background-color: #EEEEEE;
		border-radius: 3px;
		margin: 7px 13px;
	}

	.customer-form {
		border: 1px solid #89AED8;
		margin-bottom: 15px;
	}

	select.form-control {
		padding: 0px 6px;
	}

	.table {
		margin-bottom: 10px;
	}

	a.invoice {
		border: 1px solid #6a6666 !important;
		padding: 2px 6px !important;
		border-radius: 2px !important;
		background: #efefef !important;
	}
</style>
<div id="Electricity">
	<div class="customer-form">
		<div class="col-sm-12 renter_heading" style="margin-bottom: 10px;"> <strong>Electricity Bill Entry</strong> </div>
		<form @submit.prevent="saveElectricity">
			<div class="row" style="margin-top: 10px; padding-bottom:7px;">
				<div class="col-md-6">
					<div class="form-group clearfix">
						<label class="control-label col-md-4">Client Name:</label>
						<div class="col-md-7">
							<v-select :options="shops" v-model="shop" label="shop_name" v-on:input="shopOnChange"></v-select>
						</div>
						<div class="col-md-1" style="padding:0;margin-left: -15px;">
							<a href="/shop_entry" target="_blank" class="add-button"><i class="fa fa-plus"></i></a>
						</div>
					</div>

					<div class="form-group clearfix">
						<label class="control-label col-md-4">Select Month:</label>
						<div class="col-md-7">
							<v-select :options="months" v-model="month" label="month_name"></v-select>
						</div>
						<div class="col-md-1" style="padding:0;margin-left: -15px;">
							<a href="/month" target="_blank" class="add-button"><i class="fa fa-plus"></i></a>
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-md-4">Issue Date:</label>
						<div class="col-md-7">
							<input type="date" class="form-control" v-model="electricity.issue_date">
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-md-4">Last Date:</label>
						<div class="col-md-7">
							<input type="date" class="form-control" v-model="electricity.last_date">
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-md-4"></label>
						<div class="col-md-7">
							<label for="pick_hour">
								<input type="checkbox" id="pick_hour" v-model="is_pick_hour"> Is Flat</label>
						</div>
					</div>
				</div>

				<div class="col-md-6">
					<div v-if="is_pick_hour">
						<div class="form-group clearfix">
							<label class="control-label col-md-4">First Status Unit</label>
							<div class="col-md-3" style="padding-right: 0px;">
								<input type="text" class="form-control" placeholder="Prev Unit" v-model.number="electricity.prev_pick_hour_unit">
							</div>
							<div class="col-md-4">
								<input type="text" class="form-control" placeholder="Current Unit" v-model.number="electricity.cur_pick_hour_unit">
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="control-label col-md-4">First Status Unit Price</label>
							<div class="col-md-7">
								<input type="text" class="form-control" v-model.number="electricity.pick_hour_unit_price">
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="control-label col-md-4">Second Status Unit</label>
							<div class="col-md-3" style="padding-right: 0px;">
								<input type="text" class="form-control" placeholder="Prev Unit" v-model.number="electricity.prev_off_pick_hour_unit">
							</div>
							<div class="col-md-4">
								<input type="text" class="form-control" placeholder="Current Unit" v-model.number="electricity.cur_off_pick_hour_unit">
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="control-label col-md-4">Second Status Unit Price</label>
							<div class="col-md-7">
								<input type="text" class="form-control" v-model.number="electricity.off_pick_hour_unit_price">
							</div>
						</div>

					</div>
					<div v-else>
						<div class="form-group clearfix">
							<label class="control-label col-md-4">Previous Unit:</label>
							<div class="col-md-7">
								<input type="text" class="form-control" v-model="electricity.previous_unit">
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="control-label col-md-4">Current Unit:</label>
							<div class="col-md-7">
								<input type="text" class="form-control" v-model="electricity.current_unit">
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="control-label col-md-4">Common Unit</label>
							<div class="col-md-7">
								<input type="text" class="form-control" v-model="electricity.common_unit">
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="control-label col-md-4">Per Unit Price</label>
							<div class="col-md-7">
								<input type="text" class="form-control" v-model="electricity.per_unit_price">
							</div>
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-md-4">Vat (%)</label>
						<div class="col-md-7">
							<input type="number" class="form-control" v-model="electricity.pick_hour_vat">
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-md-4">D & S Charge:</label>
						<div class="col-md-7">
							<input type="number" class="form-control" v-model="electricity.ds_charge">
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-md-4"></label>
						<div class="col-md-7" style="margin-top:5px;">
							<input type="submit" class="btn btn-success btn-sm" value="Save">
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
	<div class="row">
		<div class="col-sm-12 form-inline">
			<div class="form-group">
				<label for="filter" class="sr-only">Filter</label>
				<input type="text" class="form-control" v-model="filter" placeholder="Filter">
			</div>
		</div>
		<div class="col-md-12">
			<div class="table-responsive">
				<datatable :columns="columns" :data="electricities" :filter-by="filter" style="margin-bottom: 5px;">
					<template scope="{ row }">
						<tr>
							<td>{{ row.shop_name }}</td>
							<td>{{ row.month_name }}</td>
							<td>{{ row.issue_date }}</td>
							<td>{{ row.last_date }}</td>
							<td>{{ row.previous_unit }}</td>
							<td>{{ row.current_unit }}</td>
							<td>{{ row.common_unit }}</td>
							<td>{{ row.per_unit_price }}</td>
							<td>{{ row.cur_pick_hour_unit }}</td>
							<td>{{ row.pick_hour_unit_price }}</td>
							<td>{{ row.cur_off_pick_hour_unit }}</td>
							<td>{{ row.off_pick_hour_unit_price }}</td>
							<td>
								<?php if ($this->session->userdata('accountType') != 'u') { ?>
									<a class="invoice" v-bind:href="`/electricity_bill_invoice/${row.id}`" title="invoice"><i class="fa fa-file"></i></a>
									<button type="button" class="button edit" @click="editElectricity(row)">
										<i class="fa fa-pencil"></i>
									</button>
									<button type="button" class="button" @click="deleteElectricity(row.id)">
										<i class="fa fa-trash"></i>
									</button>

								<?php } ?>
							</td>
						</tr>
					</template>
				</datatable>
				<datatable-pager v-model="page" type="abbreviated" :per-page="per_page" style="margin-bottom: 50px;"></datatable-pager>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vuejs-datatable.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>

<script>
	Vue.component('v-select', VueSelect.VueSelect);
	new Vue({
		el: '#Electricity',
		data() {
			return {
				electricity: {
					id: 0,
					shop_id: null,
					month_id: null,
					issue_date: moment().format("YYYY-MM-DD"),
					last_date: moment().format("YYYY-MM-DD"),
					previous_unit: 0,
					current_unit: 0,
					common_unit: 0,
					per_unit_price: 0,
					prev_pick_hour_unit: '',
					cur_pick_hour_unit: '',
					prev_off_pick_hour_unit: '',
					cur_off_pick_hour_unit: '',
					pick_hour_unit_price: '',
					off_pick_hour_unit_price: '',
					pick_hour_vat: 0,
					is_pickhour: false,
					ds_charge: 0,
				},
				electricities: [],
				shops: [],
				shop: null,
				months: [],
				month: null,

				is_pick_hour: false,

				columns: [{
						label: 'Client Name',
						field: 'shop_name',
						align: 'center',
						filterable: false
					},
					{
						label: 'Month',
						field: 'month_name',
						align: 'center',
						filterable: false
					},
					{
						label: 'Issue Date',
						field: 'issue_date',
						align: 'center'
					},
					{
						label: 'Last Date',
						field: 'last_date',
						align: 'center'
					},
					{
						label: 'Privious Unit',
						field: 'previous_unit',
						align: 'center'
					},
					{
						label: 'Current Unit',
						field: 'current_unit',
						align: 'center'
					},
					{
						label: 'Common Unit',
						field: 'common_unit',
						align: 'center'
					},
					{
						label: 'Unit Price',
						field: 'per_unit_price',
						align: 'center'
					},
					{
						label: 'First Status Unit',
						field: 'cur_pick_hour_unit',
						align: 'center'
					},
					{
						label: 'First Status Unit Price',
						field: 'pick_hour_unit_price',
						align: 'center'
					},
					{
						label: 'Second Status Hour Unit',
						field: 'cur_off_pick_hour_unit',
						align: 'center'
					},
					{
						label: 'Second Status Unit Price',
						field: 'off_pick_hour_unit_price',
						align: 'center'
					},
					{
						label: 'Action',
						align: 'center',
						filterable: false
					}
				],
				page: 1,
				per_page: 10,
				filter: ''
			}
		},
		watch: {
			shop(shop) {
				if (this.shop == undefined) return;
				this.electricity.shop_id = shop.id;
				console.log(this.is_pick_hour);
			},
			month(month) {
				if (this.month == undefined) return;
				this.electricity.month_id = month.month_id;
			}

		},
		created() {
			this.getShop();
			this.getElectricity();
			this.getMonths();
			this.getElectricity();
		},
		methods: {
			getShop() {
				axios.get('/get_shop').then(res => {
					this.shops = res.data;
				})
			},
			getMonths() {
				axios.post('/get_months').then(res => {
					this.months = res.data;
				})
			},
			getElectricity() {
				axios.get('/get_electricity_bill').then(res => {
					this.electricities = res.data;
				})
			},
			async shopOnChange() {
				if (this.shop != null && this.shop.id != '') {
					await axios.post('/get_previous_unit', {
							ShopId: this.shop.id
						})
						.then(res => {
							if (this.electricity.id == 0) {
								this.electricity.previous_unit = res.data[0].current_unit;
								this.electricity.prev_pick_hour_unit = res.data[0].cur_pick_hour_unit;
								this.electricity.prev_off_pick_hour_unit = res.data[0].cur_off_pick_hour_unit;
							}
						})
				}
			},
			saveElectricity() {
				if (this.shop == null) {
					alert('Please Select Shop');
					return;
				}
				if (this.month == null) {
					alert('Please Select Month');
					return;
				}
				if (this.is_pick_hour == false) {
					if (this.electricity.previous_unit == '') {
						alert('previous unit Required');
						return;
					}
					if (this.electricity.current_unit == '') {
						alert('Current unit Required');
						return;
					}
					if (this.electricity.per_unit_price == '') {
						alert('Unit Price Required');
						return;
					}
				} else {
					if (this.electricity.pick_hour_unit == '') {
						alert('pick hour unit Required');
						return;
					}
					if (this.electricity.pick_hour_unit_price == '') {
						alert('pick hour unit price Required');
						return;
					}

					this.electricity.common_unit = 0;
					this.electricity.is_pickhour = true;

				}

				let url = '/save_electricity_bill';
				if (this.electricity.id != 0) {
					url = '/update_electricity_bill';
				}

				axios.post(url, this.electricity).then(res => {
					let r = res.data;
					alert(r.message);
					if (r.success) {
						this.resetElectricity();
						this.getElectricity();
					}
				});
			},
			editElectricity(electricity) {
				let keys = Object.keys(this.electricity);
				keys.forEach(key => {
					this.electricity[key] = electricity[key];
				})

				this.shop = {
					id: electricity.shop_id,
					shop_name: electricity.shop_name,
				}
				this.month = {
					month_id: electricity.month_id,
					month_name: electricity.month_name,
				}

				if (electricity.is_pickhour == 1) {
					this.is_pick_hour = true
				} else {
					this.is_pick_hour = false
				}

			},
			deleteElectricity(electricityId) {
				let deleteConfirm = confirm('are you sure?');
				if (deleteConfirm == false) {
					return;
				}
				axios.post('/delete_electricity_bill', {
					electricityId: electricityId
				}).then(res => {
					let r = res.data;
					alert(r.message);
					if (r.success) {
						this.getElectricity();
					}
				})
			},
			resetElectricity() {
				this.electricity = {
					id: 0,
					shop_id: null,
					month_id: null,
					issue_date: moment().format("YYYY-MM-DD"),
					last_date: moment().format("YYYY-MM-DD"),
					previous_unit: 0,
					current_unit: 0,
					common_unit: 70,
					per_unit_price: 0,
					prev_pick_hour_unit: 0,
					cur_pick_hour_unit: 0,
					prev_off_pick_hour_unit: 0,
					cur_off_pick_hour_unit: 0,
					pick_hour_unit_price: 0,
					off_pick_hour_unit_price: 0,
					pick_hour_vat: 0,
					is_pickhour: false,
					ds_charge: 0,
				}
				this.shop = null;
				this.month = null;
				this.is_pick_hour = false;
			}
		}
	})
</script>