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

	#shopSale label {
		font-size: 13px;
	}

	#shopSale select {
		border-radius: 3px;
		padding: 0;
	}

	#shopSale .add-button {
		padding: 2.5px;
		width: 28px;
		background-color: #298db4;
		display: block;
		text-align: center;
		color: white;
	}

	#shopSale .add-button:hover {
		background-color: #41add6;
		color: white;
	}
</style>
<div id="shopSale">
	<div class="row" style="border-bottom: 1px solid #ccc;padding-bottom: 15px;margin-bottom: 15px;">
		<div class="col-md-12">
			<form @submit.prevent="saveShopSale">
				<div class="row">
					<div class="col-md-5 col-md-offset-1">
						<div class="form-group">
							<label class="col-md-4 control-label">Sale Date</label>
							<label class="col-md-1">:</label>
							<div class="col-md-7">
								<input type="date" class="form-control" v-model="sale.Date" required v-bind:disabled="userType == 'u' ? true : false">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">Transaction Type</label>
							<label class="col-md-1">:</label>
							<div class="col-md-7">
								<select class="form-control" v-model="sale.Transaction_Type" required>
									<option value=""></option>
									<option value="CR">Receive</option>
									<option value="CP">Payment</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">Payment Type</label>
							<label class="col-md-1">:</label>
							<div class="col-md-7">
								<select class="form-control" v-model="sale.Payment_Type" required>
									<option value="cash">Cash</option>
									<option value="bank">Bank</option>
								</select>
							</div>
						</div>
						<div class="form-group" style="display:none;" v-bind:style="{display: sale.Payment_Type == 'bank' ? '' : 'none'}">
							<label class="col-md-4 control-label">Bank Account</label>
							<label class="col-md-1">:</label>
							<div class="col-md-7">
								<v-select v-bind:options="filteredAccounts" v-model="selectedAccount" label="display_text"></v-select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">Renter</label>
							<label class="col-md-1">:</label>
							<div class="col-md-6">
								<v-select v-bind:options="customers" v-model="selectedCustomer" label="display_name" v-on:input="getShops"></v-select>
							</div>
							<div class="col-md-1" style="padding-left:0;margin-left: -3px;">
								<a href="/customer" target="_blank" class="add-button"><i class="fa fa-plus"></i></a>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">Office/Shop No:</label>
							<label class="col-md-1">:</label>
							<div class="col-md-7">
								<v-select v-bind:options="shops" v-model="selectedShop" label="shop_no"></v-select>
								<!-- <input type="text" class="form-control" placeholder="Office / Shop no" v-model="sale.Office_or_Shop_No"> -->
							</div>
						</div>
					</div>

					<div class="col-md-5">
						<div class="form-group">
							<label class="col-md-4 control-label">Sale Amount</label>
							<label class="col-md-1">:</label>
							<div class="col-md-7">
								<input type="number" class="form-control" placeholder="Sale amount" v-model="sale.Sale_Amount" v-on:input="calDue" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">Paid Amount</label>
							<label class="col-md-1">:</label>
							<div class="col-md-7">
								<input type="number" class="form-control" placeholder="Paid amount" v-model="sale.Paid_Amount" v-on:input="calDue" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">Due Amount</label>
							<label class="col-md-1">:</label>
							<div class="col-md-7">
								<input type="number" class="form-control" placeholder="Due amount" v-model="sale.Due_Amount" readonly>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Description</label>
							<label class="col-md-1">:</label>
							<div class="col-md-7">
								<textarea class="form-control" v-model="sale.Notes" placeholder="Description" cols="30" rows="2"></textarea>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-7 col-md-offset-5" style="text-align: right;">
								<input type="submit" class="btn btn-success btn-sm" value="Save">
							</div>
						</div>
					</div>
				</div>
			</form>
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
				<datatable :columns="columns" :data="shopSales" :filter-by="filter" style="margin-bottom: 5px;">
					<template scope="{ row }">
						<tr>
							<td>{{ row.Date }}</td>
							<td>{{ row.Invoice }}</td>
							<td>{{ row.Customer_Name }}</td>
							<td>{{ row.shop_name }}</td>
							<td>{{ row.Transaction_Type }}</td>
							<td>{{ row.Payment_Type }}</td>
							<td>{{ row.Sale_Amount }}</td>
							<td>{{ row.Paid_Amount }}</td>
							<td>{{ row.Due_Amount }}</td>
							<td>{{ row.Notes }}</td>
							<td>
								<button type="button" class="button edit" @click="window.location = `/paymentAndReport/${row.Shop_Sale_id}`">
									<i class="fa fa-file-o"></i>
								</button>
								<?php if ($this->session->userdata('accountType') != 'u') { ?>
									<button type="button" class="button edit" @click="editSale(row)">
										<i class="fa fa-pencil"></i>
									</button>
									<button type="button" class="button" @click="deleteSale(row.Shop_Sale_id)">
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
		el: '#shopSale',
		data() {
			return {
				sale: {
					Shop_Sale_id: '',
					Date: moment().format('YYYY-MM-DD'),
					Transaction_Type: 'CR',
					Payment_Type: 'cash',
					Bank_Account_Id: '',
					Renter_Id: '',
					Office_or_Shop_No: '',
					Sale_Amount: '',
					Paid_Amount: '',
					Due_Amount: '',
					Notes: ''
				},
				customers: [],
				selectedCustomer: {
					Customer_SlNo: '',
					display_name: 'Select---',
					Customer_Name: ''
				},
				shops: [],
				selectedShop: {
					id: '',
					shop_no: 'Select---',
				},
				accounts: [],
				selectedAccount: {
					account_id: '',
					account_name: '',
					display_text: 'Select---'
				},
				shopSales: [],
				userType: '<?php echo $this->session->userdata("accountType"); ?>',

				columns: [{
						label: 'Date',
						field: 'Date',
						align: 'center'
					},
					{
						label: 'Invoice',
						field: 'Invoice ',
						align: 'center'
					},
					{
						label: 'Customer',
						field: 'Customer_Name',
						align: 'center'
					},
					{
						label: 'Office/Shop Name',
						field: 'shop_name',
						align: 'center'
					},
					{
						label: 'Transaction Type',
						field: 'Transaction_Type',
						align: 'center'
					},
					{
						label: 'Payment Type',
						field: 'Payment_Type',
						align: 'center'
					},
					{
						label: 'Sale Amount',
						field: 'Sale_Amount',
						align: 'center'
					},
					{
						label: 'Paid Amount',
						field: 'Paid_Amount',
						align: 'center'
					},
					{
						label: 'Due Amount',
						field: 'Due_Amount',
						align: 'center'
					},
					{
						label: 'Description',
						field: 'Notes',
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
		computed: {
			filteredAccounts() {
				let accounts = this.accounts.filter(account => account.status == '1');
				return accounts.map(account => {
					account.display_text = `${account.account_name} - ${account.account_number} (${account.bank_name})`;
					return account;
				})
			},
		},
		watch: {

		},
		created() {
			this.getCustomers();
			this.getAccounts();
			this.getShopSales();
		},
		methods: {
			calDue() {
				this.sale.Due_Amount = this.sale.Sale_Amount - this.sale.Paid_Amount
			},
			getShops() {
				if (this.selectedCustomer.Customer_SlNo == '') {
					return
				}
				let renterId = this.selectedCustomer.Customer_SlNo
				// console.log(renterId);
				axios.get('/get_shop').then(res => {
					this.shops = res.data.filter((obj) => {
						return obj.renter_id == renterId
					});
				})
			},
			getShopSales() {
				axios.get('/get_shop_sale').then(res => {
					this.shopSales = res.data;
				})
			},
			getCustomers() {
				axios.get('/get_customers').then(res => {
					this.customers = res.data;
				})
			},
			getAccounts() {
				axios.get('/get_bank_accounts')
					.then(res => {
						this.accounts = res.data;
					})
			},
			saveShopSale() {
				if (this.sale.Payment_Type == 'bank' && this.selectedAccount.account_id == '') {
					alert('Select an bank account');
					return;
				} else {
					this.sale.Bank_Account_Id = this.selectedAccount.account_id;
				}
				if (parseFloat(this.sale.Paid_Amount) > parseFloat(this.sale.Sale_Amount)) {
					alert('The payment amount is more than the sale amount.');
					return;
				}

				if (this.selectedCustomer.Customer_SlNo == '') {
					alert('Select Renter');
					return;
				} else {
					this.sale.Renter_Id = this.selectedCustomer.Customer_SlNo;
				}
				if (this.selectedShop.id == '') {
					alert('Select Shop/Office no');
					return;
				} else {
					this.sale.Office_or_Shop_No = this.selectedShop.id;
				}

				let url = '/add_shop_sale';
				if (this.sale.Shop_Sale_id != '') {
					url = '/update_shop_sale';
				}
				axios.post(url, this.sale).then(res => {
					let r = res.data;
					alert(r.message);
					if (r.success) {
						this.resetForm();
						this.getShopSales();
						let invoiceConfirm = confirm('Do you want to view invoice?');
						if (invoiceConfirm == true) {
							// window.open('/paymentAndReport/' + r.paymentId, '_blank');
						}
					}
				})
			},
			async editSale(sale) {
				let keys = Object.keys(this.sale);
				keys.forEach(key => {
					this.sale[key] = sale[key];
				})

				this.selectedCustomer = {
					Customer_SlNo: sale.Renter_Id,
					Customer_Name: sale.Customer_Name,
					display_name: `${sale.Customer_Code} - ${sale.Customer_Name}`
				}

				this.selectedShop = {
					id: sale.Office_or_Shop_No,
					shop_no: sale.shop_no,
				}

				if (sale.Bank_Account_Id != '') {
					this.selectedAccount = {
						account_id: sale.Bank_Account_Id,
						account_name: sale.account_name,
						display_text: sale.account_name + ' - ' + sale.account_number + ' (' + sale.bank_name + ')'
					}
				}

			},
			deleteSale(saleId) {
				let deleteConfirm = confirm('Are you sure?');
				if (deleteConfirm == false) {
					return;
				}
				axios.post('/delete_shop_sale', {
					saleId: saleId
				}).then(res => {
					let r = res.data;
					alert(r.message);
					if (r.success) {
						this.getShopSales();
					}
				})
			},
			resetForm() {
				this.sale.Shop_Sale_id = '';
				this.sale.Date = moment().format('YYYY-MM-DD');
				this.sale.Transaction_Type = 'CR';
				this.sale.Payment_Type = 'cash';
				this.sale.Bank_Account_Id = '';
				this.sale.Renter_Id = '';
				this.sale.Office_or_Shop_No = '';
				this.sale.Sale_Amount = '';
				this.sale.Paid_Amount = '';
				this.sale.Due_Amount = '';
				this.sale.Notes = ''

				this.selectedCustomer = {
					Customer_SlNo: '',
					display_name: 'Select---',
					Customer_Name: ''
				}
				this.selectedAccount = {
					account_id: '',
					account_name: '',
					display_text: 'Select---'
				}
				this.shops = [];
				this.selectedShop = {
					id: '',
					shop_no: 'Select---',
				}
			}
		}
	})
</script>