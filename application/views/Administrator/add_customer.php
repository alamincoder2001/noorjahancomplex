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

	#customers label {
		font-size: 13px;
	}

	#customers select {
		border-radius: 3px;
	}

	#customers .add-button {
		padding: 2.5px;
		width: 28px;
		background-color: #298db4;
		display: block;
		text-align: center;
		color: white;
	}

	#customers .add-button:hover {
		background-color: #41add6;
		color: white;
	}

	#customers input[type="file"] {
		display: none;
	}

	#customers .custom-file-upload {
		border: 1px solid #ccc;
		display: inline-block;
		padding: 5px 12px;
		cursor: pointer;
		margin-top: 5px;
		background-color: #298db4;
		border: none;
		color: white;
	}

	#customers .custom-file-upload:hover {
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
<div id="customers">
	<div class="customer-form">
		<div class="col-sm-12 renter_heading" style="margin-bottom: 10px;"> <strong>Renter Information</strong> </div>
		<form @submit.prevent="saveCustomer">
			<div class="row" style="margin-top: 10px; padding-bottom:7px;">
				<div class="col-md-5">
					<div class="form-group clearfix">
						<label class="control-label col-md-4">Renter Id:</label>
						<div class="col-md-7">
							<input type="text" class="form-control" v-model="customer.Customer_Code" required readonly>
						</div>
					</div>

					<div class="form-group clearfix">
						<label class="control-label col-md-4">Position Holder:</label>
						<div class="col-md-7">
							<input type="text" class="form-control" v-model="customer.Customer_Name" required>
						</div>
					</div>

					<div class="form-group clearfix">
						<label class="control-label col-md-4">Shop No:</label>
						<div class="col-md-7">
							<input type="text" class="form-control" v-model="customer.owner_name">
						</div>
					</div>

					<div class="form-group clearfix">
						<label class="control-label col-md-4">Address:</label>
						<div class="col-md-7">
							<input type="text" class="form-control" v-model="customer.Customer_Address">
						</div>
					</div>

					<div class="form-group clearfix">
						<label class="control-label col-md-4">Mobile:</label>
						<div class="col-md-7">
							<input type="text" class="form-control" v-model="customer.Customer_Mobile" required>
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-md-4">Office Phone:</label>
						<div class="col-md-7">
							<input type="text" class="form-control" v-model="customer.Customer_OfficePhone">
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-md-4">NID:</label>
						<div class="col-md-7">
							<input type="number" class="form-control" v-model="customer.renter_nid" required>
						</div>
					</div>
				</div>

				<div class="col-md-5">
					<div class="form-group clearfix">
						<label class="control-label col-md-4">Renter Birthday:</label>
						<div class="col-md-7">
							<input type="date" class="form-control" v-model="customer.renter_birthday">
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-md-4">Renter Marriage Date:</label>
						<div class="col-md-7">
							<input type="date" class="form-control" v-model="customer.renter_marriage_day" required>
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-md-4">Previous Due:</label>
						<div class="col-md-7">
							<input type="number" class="form-control" v-model="customer.previous_due" required>
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-md-4">Adjustment Amount:</label>
						<div class="col-md-7">
							<input type="number" class="form-control" v-model="customer.adjustment_amount" required>
						</div>
					</div>

					<div class="form-group clearfix">
						<label class="control-label col-md-4"></label>
						<div class="col-md-7">
							<input type="checkbox" value="1" v-model="customer.is_active"> is_active
						</div>
					</div>
					<div class="information">
						<div class="col-sm-12 advance_heading"> <strong>Advance Amount</strong> <button class="btn btn-EduAdd" type="button" data-toggle="modal" data-target="#myModal">+Add</button></div>
						<div class="table-responsive" style="padding: 5px 3px 0px 3px;">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>Date</th>
										<th>Amount</th>
										<th>Details</th>
										<th>Payment Type</th>
										<th>Actioin</th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="(advance, ind) in cart" :key="ind">
										<td>{{ advance.payment_date }}</td>
										<td>{{ advance.amount }}</td>
										<td>{{ advance.details }}</td>
										<td>{{ advance.payment_type }}</td>
										<td><button type="button" @click="removeFromCart(ind)"><i class="fa fa-trash"></i></button></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>

				</div>
				<div class="col-md-2 text-center;">
					<div class="form-group clearfix">
						<div style="width: 100px;height:100px;border: 1px solid #ccc;overflow:hidden;">
							<img id="customerImage" v-if="imageUrl == '' || imageUrl == null" src="/assets/no_image.gif">
							<img id="customerImage" v-if="imageUrl != '' && imageUrl != null" v-bind:src="imageUrl">
						</div>
						<div style="text-align:center;">
							<label class="custom-file-upload">
								<input type="file" @change="previewImage" />
								Select Image
							</label>
						</div>
					</div>
					<div class="form-group clearfix">
						<div style="text-align:center; margin-top:10px;">
							<input type="submit" class="btn btn-success btn-sm" value="Save">
						</div>
					</div>
				</div>
			</div>
		</form>

		<!-- Modal -->
		<div id="myModal" class="modal fade" role="dialog">
			<div class="modal-dialog modal-dialog-centered">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Add Advance Payment</h4>
					</div>
					<form @submit.prevent="addToCart">
						<div class="modal-body">
							<div class="row">
								<div class="form-group">
									<label class="col-sm-3 control-label" for="examination"> Payment Date </label>
									<label class="col-sm-1 control-label">:</label>
									<div class="col-sm-7">
										<input type="date" v-model="advance.payment_date" class="form-control">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" for="amount"> Amount </label>
									<label class="col-sm-1 control-label">:</label>
									<div class="col-sm-7">
										<input type="text" v-model="advance.amount" id="amount" class="form-control">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" for="details"> Details </label>
									<label class="col-sm-1 control-label">:</label>
									<div class="col-sm-7">
										<textarea class="form-control" v-model="advance.details" id="details" cols="2" rows="2"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" for="pay_type"> Payment Type </label>
									<label class="col-sm-1 control-label">:</label>
									<div class="col-sm-7">
										<select name="" id="pay_type" class="form-control" v-model="advance.payment_type">
											<option value="">--select one--</option>
											<option value="cash">Cash</option>
											<option value="bank">Bank</option>
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-sm btn-success">Add To Cart</button>
						</div>
				</div>
				</form>
			</div>
		</div>
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
				<datatable :columns="columns" :data="customers" :filter-by="filter" style="margin-bottom: 5px;">
					<template scope="{ row }">
						<tr>
							<td>{{ row.AddTime | dateOnly('DD-MM-YYYY') }}</td>
							<td>{{ row.Customer_Code }}</td>
							<td>{{ row.Customer_Name }}</td>
							<td>{{ row.owner_name }}</td>
							<td>{{ row.Customer_Mobile }}</td>
							<td>{{ row.is_active == 1 ? 'Active' : 'Deactive' }}</td>
							<td>
								<?php if ($this->session->userdata('accountType') != 'u') { ?>
									<button type="button" class="button edit" @click="editCustomer(row)">
										<i class="fa fa-pencil"></i>
									</button>
									<button type="button" class="button" @click="deleteCustomer(row.Customer_SlNo)">
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
		el: '#customers',
		data() {
			return {
				advance: {
					id: null,
					renter_id: null,
					payment_date: moment().format("YYYY-MM-DD"),
					amount: 0.00,
					details: '',
					payment_type: '',
					status: '',
				},
				customer: {
					Customer_SlNo: 0,
					Customer_Code: '<?php echo $customerCode; ?>',
					Customer_Name: '',
					is_active: true,
					Customer_Phone: '',
					Customer_Mobile: '',
					Customer_Email: '',
					Customer_OfficePhone: '',
					Customer_Address: '',
					renter_birthday: '',
					renter_marriage_day: '',
					owner_name: '',
					renter_nid: '',
					adjustment_amount: '',
					Customer_Credit_Limit: 0,
					previous_due: 0,
				},
				cart: [],
				customers: [],
				// districts: [],
				// selectedDistrict: null,
				imageUrl: '',
				selectedFile: null,

				columns: [{
						label: 'Added Date',
						field: 'AddTime',
						align: 'center',
						filterable: false
					},
					{
						label: 'Renter Id',
						field: 'Customer_Code',
						align: 'center',
						filterable: false
					},
					{
						label: 'Position Holder',
						field: 'Customer_Name',
						align: 'center'
					},
					{
						label: 'Shop No',
						field: 'owner_name',
						align: 'center'
					},
					{
						label: 'Contact Number',
						field: 'Customer_Mobile',
						align: 'center'
					},
					{
						label: 'Is Active',
						field: 'Customer_Type',
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
		filters: {
			dateOnly(datetime, format) {
				return moment(datetime).format(format);
			}
		},
		created() {
			// this.getDistricts();
			this.getCustomers();
		},
		methods: {
			addToCart() {
				let advanceCart = {
					payment_date: this.advance.payment_date,
					amount: this.advance.amount,
					details: this.advance.details,
					payment_type: this.advance.payment_type,
				}
				if (this.advance.payment_date == '') {
					alert('Payment Date is Required!');
					return;
				}
				if (this.advance.amount == '') {
					alert('Amount is Required!');
					return;
				}
				if (this.advance.payment_type == '') {
					alert('Please Select Payment Type');
					return;
				}
				this.cart.push(advanceCart);
				this.resetAdvance();
				$('#myModal').modal('hide');
			},
			removeFromCart(ind) {
				this.cart.splice(ind, 1);
			},
			// getDistricts(){
			// 	axios.get('/get_districts').then(res => {
			// 		this.districts = res.data;
			// 	})
			// },
			getCustomers() {
				axios.get('/get_customers').then(res => {
					this.customers = res.data;
				})
			},
			previewImage() {
				if (event.target.files.length > 0) {
					this.selectedFile = event.target.files[0];
					this.imageUrl = URL.createObjectURL(this.selectedFile);
				} else {
					this.selectedFile = null;
					this.imageUrl = null;
				}
			},
			saveCustomer() {
				// if(this.selectedDistrict == null){
				// 	alert('Select area');
				// 	return;
				// }

				// this.customer.area_ID = this.selectedDistrict.District_SlNo;

				let url = '/add_customer';
				if (this.customer.Customer_SlNo != 0) {
					url = '/update_customer';
				}

				let fd = new FormData();
				fd.append('image', this.selectedFile);
				fd.append('data', JSON.stringify(this.customer));
				fd.append('cart', JSON.stringify(this.cart));

				axios.post(url, fd, {
					onUploadProgress: upe => {
						let progress = Math.round(upe.loaded / upe.total * 100);
						console.log(progress);
					}
				}).then(res => {
					let r = res.data;
					alert(r.message);
					if (r.success) {
						this.resetForm();
						this.cart = [];
						this.customer.Customer_Code = r.customerCode;
						this.getCustomers();
					}
				})
			},
			editCustomer(customer) {
				let keys = Object.keys(this.customer);
				keys.forEach(key => {
					this.customer[key] = customer[key];
				})
				this.cart = [];
				customer.advance.forEach(advance => {
					let getAdvance = {
						payment_date: advance.payment_date,
						amount: advance.amount,
						details: advance.details,
						payment_type: advance.payment_type,
					}
					this.cart.push(getAdvance);
				});

				// this.selectedDistrict = {
				// 	District_SlNo: customer.area_ID,
				// 	District_Name: customer.District_Name
				// }
				if (customer.is_active == 1) {
					this.customer.is_active = true;
				} else {
					this.customer.is_active = false;
				}
				if (customer.image_name == null || customer.image_name == '') {
					this.imageUrl = null;
				} else {
					this.imageUrl = '/uploads/customers/' + customer.image_name;
				}
			},
			deleteCustomer(customerId) {
				let deleteConfirm = confirm('Are you sure?');
				if (deleteConfirm == false) {
					return;
				}
				axios.post('/delete_customer', {
					customerId: customerId
				}).then(res => {
					let r = res.data;
					alert(r.message);
					if (r.success) {
						this.getCustomers();
					}
				})
			},
			resetForm() {
				let keys = Object.keys(this.customer);
				keys = keys.filter(key => key != "Customer_Type");
				keys.forEach(key => {
					if (typeof(this.customer[key]) == 'string') {
						this.customer[key] = '';
					} else if (typeof(this.customer[key]) == 'number') {
						this.customer[key] = 0;
					}
				})
				this.imageUrl = '';
				this.selectedFile = null;
			},
			resetAdvance() {
				this.advance = {
					id: null,
					renter_id: null,
					payment_date: moment().format("YYYY-MM-DD"),
					amount: 0.00,
					details: '',
					payment_type: '',
					status: '',
				}
			}
		}
	})
</script>