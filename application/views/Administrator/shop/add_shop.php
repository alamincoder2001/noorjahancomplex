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

	#Shop label {
		font-size: 13px;
	}

	#Shop select {
		border-radius: 3px;
	}

	#Shop .add-button {
		padding: 2.5px;
		width: 28px;
		background-color: #298db4;
		display: block;
		text-align: center;
		color: white;
		border-radius: 3px;
	}

	#Shop .add-button:hover {
		background-color: #41add6;
		color: white;
	}

	#Shop input[type="file"] {
		display: none;
	}

	#Shop .custom-file-upload {
		border: 1px solid #ccc;
		display: inline-block;
		padding: 5px 12px;
		cursor: pointer;
		margin-top: 5px;
		background-color: #298db4;
		border: none;
		color: white;
	}

	#Shop .custom-file-upload:hover {
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
</style>
<div id="Shop">
	<div class="customer-form">
		<div class="col-sm-12 renter_heading" style="margin-bottom: 10px;"> <strong>Office / Sop Information</strong> </div>
		<form @submit.prevent="saveShop">
			<div class="row" style="margin-top: 10px; padding-bottom:7px;">
				<div class="col-md-6">
					<div class="form-group clearfix">
						<label class="control-label col-md-4">Client Name:</label>
						<div class="col-md-7">
							<input type="text" class="form-control" v-model="shop.shop_name" required>
						</div>
					</div>

					<div class="form-group clearfix">
						<label class="control-label col-md-4">Select Renter:</label>
						<div class="col-md-7">
							<v-select :options="renters" v-model="renter" label="Customer_Name"></v-select>
						</div>
						<div class="col-md-1" style="padding:0;margin-left: -15px;">
							<a href="/customer" target="_blank" class="add-button"><i class="fa fa-plus"></i></a>
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-md-4">Select Floor:</label>
						<div class="col-md-7">
							<v-select :options="floors" v-model="floor" label="floor_name"></v-select>
						</div>
						<div class="col-md-1" style="padding:0;margin-left: -15px;">
							<a href="/floor_entry" target="_blank" class="add-button"><i class="fa fa-plus"></i></a>
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-md-4">Flat/Shop No:</label>
						<div class="col-md-7">
							<input type="text" class="form-control" v-model="shop.shop_no">
						</div>
					</div>
					<!-- <div class="form-group clearfix">
						<label class="control-label col-md-4">Office/Shop Rent:</label>
						<div class="col-md-7">
							<input type="text" class="form-control" v-model="shop.shop_rent">
						</div>
					</div> -->
					<div class="form-group clearfix">
						<label class="control-label col-md-4">Total SFT:</label>
						<div class="col-md-7">
							<input type="number" step="0.01" min="0.00" class="form-control" v-model="shop.total_sft" v-on:input="calRent" required>
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-md-4">Per SFT Rent:</label>
						<div class="col-md-7">
							<input type="number" step="0.01" min="0.00" class="form-control" v-model="shop.per_sft_rent" v-on:input="calRent">
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-md-4">Flat/Shop Rent:</label>
						<div class="col-md-7">
							<input type="number" step="0.01" min="0.00" class="form-control" v-model="shop.shop_or_flat_rent" readonly>
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-md-4">Per SFT Service Rate:</label>
						<div class="col-md-7">
							<input type="number" step="0.01" min="0.00" class="form-control" v-model="shop.per_sft_service_rate" v-on:input="calRent" required>
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-md-4">Total Service Charge:</label>
						<div class="col-md-7">
							<input type="number" step="0.01" min="0.00" class="form-control" v-model="shop.total_service_charge" readonly>
						</div>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group clearfix">
						<label class="control-label col-md-4">AC Bill:</label>
						<div class="col-md-7">
							<input type="number" step="0.01" min="0.00" class="form-control" v-model="shop.ac_bill">
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-md-4">Electricity Bill:</label>
						<div class="col-md-7">
							<input type="number" step="0.01" min="0.00" class="form-control" v-model="shop.shop_electricity_bill">
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-md-4">Gass Bill:</label>
						<div class="col-md-7">
							<input type="number" step="0.01" min="0.00" class="form-control" v-model="shop.shop_gass_bill">
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-md-4">Water Bill:</label>
						<div class="col-md-7">
							<input type="number" step="0.01" min="0.00" class="form-control" v-model="shop.shop_water_bill">
						</div>
					</div>

					<div class="form-group clearfix">
						<label class="control-label col-md-4">Other Charge:</label>
						<div class="col-md-7">
							<input type="number" step="0.01" min="0.00" class="form-control" v-model="shop.other_charge">
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-md-4">Master Meter No:</label>
						<div class="col-md-7">
							<input type="text" class="form-control" v-model="shop.master_meter_no">
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-md-4">Sub-Meter No:</label>
						<div class="col-md-7">
							<input type="text" class="form-control" v-model="shop.sub_meter_no">
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-md-4"></label>
						<div class="col-md-3">
							<input type="checkbox" value="1" id="is_rent" v-model="shop.is_rent">
							<label for="is_rent">is_rent</label>
						</div>
						<div class="col-md-3">
							<input type="checkbox" value="1" id="is_sold" v-model="shop.is_sold">
							<label for="is_sold">is_sold</label>
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
				<datatable :columns="columns" :data="shops" :filter-by="filter" style="margin-bottom: 5px;">
					<template scope="{ row }">
						<tr>
							<td>{{ row.shop_name }}</td>
							<td>{{ row.floor_name }}</td>
							<td>{{ row.shop_no }}</td>
							<td>{{ row.shop_or_flat_rent }}</td>
							<td>{{ row.shop_electricity_bill }}</td>
							<td>{{ row.ac_bill }}</td>
							<td>{{ row.total_service_charge }}</td>
							<td>{{ row.is_rent == 1 ? 'is_rent' : '' }}</td>
							<td>{{ row.is_sold == 1 ? 'is_sold' : '' }}</td>
							<td>
								<?php if ($this->session->userdata('accountType') != 'u') { ?>
									<button type="button" class="button edit" @click="editShop(row)">
										<i class="fa fa-pencil"></i>
									</button>
									<button type="button" class="button" @click="deleteShop(row.id)">
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
		el: '#Shop',
		data() {
			return {
				shop: {
					id: 0,
					shop_name: '',
					renter_id: null,
					floor_id: null,
					shop_no: '',
					shop_rent: '',
					shop_electricity_bill: 0,
					shop_gass_bill: 0,
					shop_water_bill: 0,
					total_service_charge: 0,
					other_charge: 0,
					master_meter_no: '',
					sub_meter_no: '',
					is_rent: false,
					is_sold: false,
					total_sft: '',
					per_sft_rent: 0,
					shop_or_flat_rent: 0,
					per_sft_service_rate: '',
					ac_bill: 0,
				},
				shops: [],
				floors: [],
				floor: null,
				renters: [],
				renter: null,

				columns: [{
						label: 'Shop Name',
						field: 'shop_name',
						align: 'center',
						filterable: false
					},
					{
						label: 'Floor',
						field: 'floor_name',
						align: 'center',
						filterable: false
					},
					{
						label: 'Flat/Shop No',
						field: 'shop_no',
						align: 'center'
					},
					{
						label: 'Flat/Shop Rent',
						field: 'shop_or_flat_rent',
						align: 'center'
					},
					{
						label: 'Electricity Bill',
						field: 'shop_electricity_bill',
						align: 'center'
					},
					{
						label: 'AC Bill',
						field: 'ac_bill',
						align: 'center'
					},
					{
						label: 'Service Charge',
						field: 'total_service_charge',
						align: 'center'
					},
					{
						label: 'Is_Rent',
						field: 'is_rent',
						align: 'center'
					},
					{
						label: 'Is_Sold',
						field: 'is_sold',
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
			floor(floor) {
				if (this.floor == undefined) return;
				this.shop.floor_id = floor.id;
			},
			renter(renter) {
				if (this.renter == undefined) return;
				this.shop.renter_id = renter.Customer_SlNo;
				// console.log(this.shop.renter_id);
			}
		},
		created() {
			this.getFloor();
			this.getShop();
			this.getRenter();
		},
		methods: {
			calRent() {
				this.shop.shop_or_flat_rent = parseFloat(this.shop.per_sft_rent) * parseFloat(this.shop.total_sft);
				console.log(this.shop.shop_or_flat_rent, this.shop.per_sft_rent, this.shop.total_sft);
				if (this.shop.per_sft_service_rate != '') {
					this.shop.total_service_charge = this.shop.total_sft * this.shop.per_sft_service_rate
				}
			},
			getFloor() {
				axios.post('/get_floor').then(res => {
					this.floors = res.data;
				})
			},
			getRenter() {
				axios.get('/get_customers').then(res => {
					this.renters = res.data;
				})
			},
			getShop() {
				axios.get('/get_shop').then(res => {
					this.shops = res.data;
				})
			},
			saveShop() {
				if (this.renter == null) {
					alert('Please Select Renter');
					return;
				}
				if (this.floor == null) {
					alert('Please Select floor');
					return;
				}
				if (this.shop.shop_no == '') {
					alert('Shop No. Required');
					return;
				}
				// if (this.shop.shop_rent == '') {
				// 	alert('Office/Shop Rent Required');
				// 	return;
				// }
				if (this.shop.master_meter_no == '') {
					alert('Master Meter No Required');
					return;
				}
				if (this.shop.sub_meter_no == '') {
					alert('Sub-Meter No Required');
					return;
				}
				// if (this.shop.shop_or_flat_rent == '') {
				// 	alert('Shop/Flat rent cannot be empty');
				// 	return;
				// }
				// if (this.shop.is_rent == false && this.shop.is_sold == false) {
				// 	alert('Is rent / is sold anyone Required');
				// 	return;
				// }
				// if (this.shop.is_rent == true && this.shop.is_sold == true) {
				// 	alert('Is rent / is sold anyone Required');
				// 	return;
				// }

				let url = '/save_shop';
				if (this.shop.id != 0) {
					url = '/update_shop';
				}

				axios.post(url, this.shop).then(res => {
					let r = res.data;
					alert(r.message);
					if (r.success) {
						this.resetShop();
						this.getShop();
					}
				});
			},
			editShop(shop) {
				let keys = Object.keys(this.shop);
				keys.forEach(key => {
					this.shop[key] = shop[key];
				})

				this.floor = {
					id: shop.floor_id,
					floor_name: shop.floor_name,
				}
				this.renter = {
					Customer_SlNo: shop.renter_id,
					Customer_Name: shop.renter_name,
				}
				this.shop.is_rent = shop.is_rent == 1 ? true : false;
				this.shop.is_sold = shop.is_sold == 1 ? true : false;

			},
			deleteShop(shopId) {
				let deleteConfirm = confirm('are you sure?');
				if (deleteConfirm == false) {
					return;
				}
				axios.post('/delete_shop', {
					shopId: shopId
				}).then(res => {
					let r = res.data;
					alert(r.message);
					if (r.success) {
						this.getShop();
					}
				})
			},
			resetShop() {
				this.shop = {
					id: 0,
					shop_name: '',
					floor_id: null,
					shop_no: '',
					shop_rent: '',
					shop_electricity_bill: 0,
					shop_gass_bill: 0,
					shop_water_bill: 0,
					total_service_charge: 0,
					other_charge: 0,
					master_meter_no: '',
					sub_meter_no: '',
					is_rent: false,
					is_sold: false,
					total_sft: '',
					per_sft_rent: '',
					shop_or_flat_rent: 0,
					per_sft_service_rate: '',
					ac_bill: 0,
				};
				this.floor = null;
				this.renter = null;
			}
		}
	})
</script>