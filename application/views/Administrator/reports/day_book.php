<style>
	#dayBook .buttons {
		margin-top: -5px;
	}

	.day-book-table {
		width: 100%;
		margin-bottom: 50px;
	}

	.day-book-table thead {
		background: #ebebeb;
		border-bottom: 1px solid black;
	}

	.day-book-table th {
		padding: 5px 5px;
		text-align: left;
	}

	/* .day-book-table td {
		padding: 0px 10px;
	}

	.day-book-table td {
		padding: 0px 30px;
	} */

	/* .day-book-table tbody tr td {
		border: 1px solid;
	} */

	/* .day-book-table tr td:last-child {
		text-align: right;
		padding-right: 50px;
	} */

	.day-book-table .main-heading {
		/* padding-left: 10px; */
		font-weight: bold;
	}

	.day-book-table .sub-heading {
		padding-left: 20px;
		font-weight: bold;
	}

	.day-book-table .sub-heading-2 {
		padding-left: 30px;
	}

	.day-book-table .sub-value {
		padding-right: 5px !important;
		font-weight: bold;
		text-align: right;
		/* border-left: 1px solid; */
	}

	.day-book-table .sub-value-2 {
		padding-right: 5px !important;
		text-align: right;
	}
</style>
<div id="dayBook">
	<div class="row" style="border-bottom: 1px solid #ccc;">
		<div class="col-md-12">
			<form action="" class="form-inline" @submit.prevent="getDayBookData">
				<div class="form-group">
					<label for="">Date from</label>
					<input type="date" class="form-control" v-model="filter.dateFrom">
				</div>

				<div class="form-group">
					<label for="">to</label>
					<input type="date" class="form-control" v-model="filter.dateTo">
				</div>

				<div class="form-group buttons">
					<input type="submit" value="Search">
				</div>
			</form>
		</div>
	</div>
	<div class="row" style="padding-top:15px;padding-bottom:10px;">
		<div class="col-md-6">
			<a href="" @click.prevent="print"><i class="fa fa-print"></i> Print</a>
		</div>
		<div class="col-md-6" style="text-align: right;">
			<a href="" @click.prevent="sendSMS"><i class="fa fa-envelope"></i> Send SMS</a>
		</div>
	</div>

	<div id="printContent">
		<div class="row">
			<div class="col-md-12 sohel">
				<div style="display:flex;">
					<div style="width:50%;border:1px solid black;position:relative;">
						<table class="day-book-table">
							<thead>
								<th style="">Description</th>
								<th style="text-align: right;">Amount</th>
								<th style="text-align: right;">Total Amount</th>
							</thead>
							<tbody>
								<tr>
									<td class="main-heading">Opening Balance</td>
									<td></td>
									<td></td>
								</tr>
								<template v-if="openingBalance.bankBalance.length > 0">
									<tr>
										<td class="sub-heading">Bank Accounts</td>
										<td class="sub-value"></td>
										<td class="sub-value">{{ totalBankOpeningBalance }}</td>
									</tr>
									<tr v-for="bankAccount in openingBalance.bankBalance">
										<td class="sub-heading-2">{{ bankAccount.bank_name }} {{ bankAccount.account_name }} {{ bankAccount.account_number }}</td>
										<td class="sub-value-2">{{ bankAccount.balance | decimal }}</td>
										<td></td>
									</tr>
								</template>
								<template v-if="openingBalance.cashBalance != null">
									<tr>
										<td class="sub-heading">Cash in Hand</td>
										<td></td>
										<td class="sub-value">{{ openingBalance.cashBalance.cash_balance | decimal }}</td>
									</tr>
									<tr>
										<td class="sub-heading-2">Cash</td>
										<td class="sub-value-2">{{ openingBalance.cashBalance.cash_balance | decimal }}</td>
										<td></td>
									</tr>
								</template>
								<tr>
									<td class="main-heading">Receipt</td>
									<td></td>
								</tr>
								<template v-if="sales.length > 0">
									<tr>
										<td class="sub-heading">Sales</td>
										<td class="sub-value">{{ totalSales }}</td>
										<td></td>
									</tr>
									<tr v-for="sale in sales">
										<td class="sub-heading-2">{{ sale.Customer_Name }}</td>
										<td></td>
										<td class="sub-value-2">{{ sale.totalAmount | decimal }}</td>
									</tr>
								</template>
								<template v-if="receivedFromCustomers.length > 0">
									<tr>
										<td class="sub-heading">Customer Payment</td>
										<td></td>
										<td class="sub-value">{{ totalReceivedFromCustomers }}</td>
									</tr>
									<tr v-for="payment in receivedFromCustomers">
										<td class="sub-heading-2">{{ payment.Customer_Name }}</td>
										<td class="sub-value-2">{{ payment.totalAmount | decimal }}</td>
										<td></td>
									</tr>
								</template>
								<template v-if="cashReceived.length > 0">
									<tr>
										<td class="sub-heading">Cash Received</td>
										<td></td>
										<td class="sub-value">{{ totalCashReceived }}</td>
									</tr>
									<tr v-for="transaction in cashReceived">
										<td class="sub-heading-2">{{ transaction.Acc_Name }}</td>
										<td class="sub-value-2">{{ transaction.totalAmount | decimal }}</td>
										<td></td>
									</tr>
								</template>
								<template v-if="receivedFromSuppliers.length > 0">
									<tr>
										<td class="sub-heading">Received from Suppliers</td>
										<td></td>
										<td class="sub-value">{{ totalReceivedFromSuppliers }}</td>
									</tr>
									<tr v-for="payment in receivedFromSuppliers">
										<td class="sub-heading-2">{{ payment.Supplier_Name }}</td>
										<td class="sub-value-2">{{ payment.totalAmount | decimal }}</td>
										<td></td>
									</tr>
								</template>
								<template v-if="bankWithdraws.length > 0">
									<tr>
										<td class="sub-heading">Bank Withdraws <span style="color:red;">(Not Calculated)</span></td>
										<td></td>
										<td class="sub-value">{{ totalBankWithdraw }}</td>
									</tr>
									<tr v-for="transaction in bankWithdraws">
										<td class="sub-heading-2">{{ transaction.bank_name }} {{ transaction.account_name }} {{ transaction.account_number }}</td>
										<td class="sub-value-2">{{ transaction.totalAmount | decimal }}</td>
										<td></td>
									</tr>
								</template>
								<template v-if="bankDeposits.length > 0">
									<tr>
										<td class="sub-heading">Bank Deposits <span style="color:red;">(Not Calculated)</span></td>
										<td></td>
										<td class="sub-value">{{ totalBankDeposit }}</td>
									</tr>
									<tr v-for="transaction in bankDeposits">
										<td class="sub-heading-2">{{ transaction.bank_name }} {{ transaction.account_name }} {{ transaction.account_number }}</td>
										<td class="sub-value-2">{{ transaction.totalAmount | decimal }}</td>
										<td></td>
									</tr>
								</template>
							</tbody>
						</table>
						<div style="position:absolute;bottom:0px;left:0px;padding:5px 10px;display:none;width:100%;border-top:1px solid black;font-weight:bold;" v-bind:style="{display: _.isNumber(totalIn) ? 'flex' : 'none' }">
							<div style="width:50%;">Total</div>
							<div style="width:50%;text-align:right;">{{ totalIn | decimal }}</div>
						</div>
					</div>
					<div style="width:50%;border:1px solid black;border-left:none;position:relative;">
						<table class="day-book-table">
							<thead>
								<tr>
									<th style="">Description</th>
									<th style="text-align: right;">Amount</th>
									<th style="text-align: right;">Total Amount</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class=" main-heading">Payment</td>
									<td></td>
								</tr>
								<template v-if="purchases.length > 0">
									<tr>
										<td class="sub-heading">Purchases</td>
										<td class="sub-value">{{ totalPurchase }}</td>
									</tr>
									<tr v-for="purchase in purchases">
										<td>{{ purchase.Supplier_Name }}</td>
										<td>{{ purchase.totalAmount | decimal }}</td>
									</tr>
								</template>
								<template v-if="paidToSuppliers.length > 0">
									<tr>
										<td class="sub-heading">Supplier Payment</td>
										<td></td>
										<td class="sub-value">{{ totalPaidToSuppliers }}</td>
									</tr>
									<tr v-for="payment in paidToSuppliers">
										<td class="sub-heading-2">{{ payment.Supplier_Name }}</td>
										<td class="sub-value-2">{{ payment.totalAmount | decimal }}</td>
										<td></td>
									</tr>
								</template>
								<template v-if="cashPaid.length > 0">
									<tr>
										<td class="sub-heading">Cash Paid</td>
										<td></td>
										<td class="sub-value">{{ totalCashPaid }}</td>
									</tr>
									<tr v-for="transaction in cashPaid">
										<td class="sub-heading-2">{{ transaction.Acc_Name }}</td>
										<td class="sub-value-2">{{ transaction.totalAmount | decimal }}</td>
										<td></td>
									</tr>
								</template>
								<template v-if="paidToCustomers.length > 0">
									<tr>
										<td class="sub-heading">Paid to Customers</td>
										<td></td>
										<td class="sub-value">{{ totalPaidToCustomers }}</td>
									</tr>
									<tr v-for="payment in paidToCustomers">
										<td class="sub-heading-2">{{ payment.Customer_Name }}</td>
										<td class="sub-value-2">{{ payment.totalAmount | decimal }}</td>
										<td></td>
									</tr>
								</template>
								<template v-if="employeePayments.length > 0">
									<tr>
										<td class="sub-heading">Employee Payments</td>
										<td></td>
										<td class="sub-value">{{ totalEmployeePayments }}</td>
									</tr>
									<tr v-for="payment in employeePayments">
										<td class="sub-heading-2">{{ payment.Employee_Name }}</td>
										<td class="sub-value-2">{{ payment.totalAmount | decimal }}</td>
										<td></td>
									</tr>
								</template>
								<tr>
									<td class="main-heading">Closing Balance</td>
									<td></td>
								</tr>
								<template v-if="closingBalance.bankBalance.length > 0">
									<tr>
										<td class="sub-heading">Bank Accounts</td>
										<td></td>
										<td class="sub-value">{{ totalBankClosingBalance }}</td>
									</tr>
									<template>
										<tr v-for="bankAccount in closingBalance.bankBalance">
											<td class="sub-heading-2">{{ bankAccount.bank_name }} {{ bankAccount.account_name }} {{ bankAccount.account_number }}</td>
											<td class="sub-value-2">{{ bankAccount.balance | decimal }}</td>
										</tr>
									</template>
								</template>
								<template v-if="closingBalance.cashBalance != null">
									<tr>
										<td class="sub-heading">Cash in Hand</td>
										<td></td>
										<td class="sub-value">{{ closingBalance.cashBalance.cash_balance | decimal }}</td>
									</tr>
									<tr>
										<td class="sub-heading-2">Cash</td>
										<td class="sub-value-2">{{ closingBalance.cashBalance.cash_balance | decimal }}</td>
									</tr>
								</template>
							</tbody>
						</table>
						<div style="position:absolute;bottom:0px;left:0px;padding:5px 10px;display:none;width:100%;border-top:1px solid black;font-weight:bold;" v-bind:style="{display: _.isNumber(totalOut) ? 'flex' : 'none' }">
							<div style="width:50%;">Total</div>
							<div style="width:50%;text-align:right;">{{ totalOut | decimal }}</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/lodash.min.js"></script>

<script>
	new Vue({
		el: '#dayBook',
		data() {
			return {
				filter: {
					dateFrom: moment().format('YYYY-MM-DD'),
					dateTo: moment().format('YYYY-MM-DD')
				},
				openingBalance: {
					bankBalance: [],
					cashBalance: 0.00
				},
				closingBalance: {
					bankBalance: [],
					cashBalance: 0.00
				},
				sales: [],
				purchases: [],
				receivedFromCustomers: [],
				paidToCustomers: [],
				receivedFromSuppliers: [],
				paidToSuppliers: [],
				cashReceived: [],
				cashPaid: [],
				bankDeposits: [],
				bankWithdraws: [],
				employeePayments: []
			}
		},
		filters: {
			decimal(value) {
				return value == null ? 0.00 : parseFloat(value).toFixed(2);
			}
		},
		computed: {
			totalBankOpeningBalance() {
				return this.openingBalance.bankBalance.reduce((prev, curr) => {
					return prev + parseFloat(curr.balance)
				}, 0).toFixed(2);
			},
			totalBankClosingBalance() {
				return this.closingBalance.bankBalance.reduce((prev, curr) => {
					return prev + parseFloat(curr.balance)
				}, 0).toFixed(2);
			},
			totalSales() {
				return this.sales.reduce((prev, curr) => {
					return prev + parseFloat(curr.totalAmount)
				}, 0).toFixed(2);
			},
			totalPurchase() {
				return this.purchases.reduce((prev, curr) => {
					return prev + parseFloat(curr.totalAmount)
				}, 0).toFixed(2);
			},
			totalReceivedFromCustomers() {
				return this.receivedFromCustomers.reduce((prev, curr) => {
					return prev + parseFloat(curr.totalAmount)
				}, 0).toFixed(2);
			},
			totalPaidToCustomers() {
				return this.paidToCustomers.reduce((prev, curr) => {
					return prev + parseFloat(curr.totalAmount)
				}, 0).toFixed(2);
			},
			totalReceivedFromSuppliers() {
				return this.receivedFromSuppliers.reduce((prev, curr) => {
					return prev + parseFloat(curr.totalAmount)
				}, 0).toFixed(2);
			},
			totalPaidToSuppliers() {
				return this.paidToSuppliers.reduce((prev, curr) => {
					return prev + parseFloat(curr.totalAmount)
				}, 0).toFixed(2);
			},
			totalCashReceived() {
				return this.cashReceived.reduce((prev, curr) => {
					return prev + parseFloat(curr.totalAmount)
				}, 0).toFixed(2);
			},
			totalCashPaid() {
				return this.cashPaid.reduce((prev, curr) => {
					return prev + parseFloat(curr.totalAmount)
				}, 0).toFixed(2);
			},
			totalBankDeposit() {
				return this.bankDeposits.reduce((prev, curr) => {
					return prev + parseFloat(curr.totalAmount)
				}, 0).toFixed(2);
			},
			totalBankWithdraw() {
				return this.bankWithdraws.reduce((prev, curr) => {
					return prev + parseFloat(curr.totalAmount)
				}, 0).toFixed(2);
			},
			totalEmployeePayments() {
				return this.employeePayments.reduce((prev, curr) => {
					return prev + parseFloat(curr.totalAmount)
				}, 0).toFixed(2);
			},
			totalIn() {
				return parseFloat(this.openingBalance.cashBalance.cash_balance) +
					parseFloat(this.totalBankOpeningBalance) +
					parseFloat(this.totalSales) +
					parseFloat(this.totalReceivedFromCustomers) +
					parseFloat(this.totalReceivedFromSuppliers) +
					parseFloat(this.totalCashReceived);
			},
			totalOut() {
				return parseFloat(this.totalPurchase) +
					parseFloat(this.totalPaidToCustomers) +
					parseFloat(this.totalPaidToSuppliers) +
					parseFloat(this.totalCashPaid) +
					parseFloat(this.totalEmployeePayments) +
					parseFloat(this.closingBalance.cashBalance.cash_balance) +
					parseFloat(this.totalBankClosingBalance);
			},
			cashBalance() {
				return parseFloat(this.totalIn) - parseFloat(this.totalOut);
			}
		},
		created() {
			this.getDayBookData();
		},
		methods: {
			getDayBookData() {
				this.getOpeningBalance();
				this.getClosingBalance();
				this.getSales();
				this.getPurchases();
				this.getReceivedFromCustomers();
				this.getPaidToCustomers();
				this.getPaidToSuppliers();
				this.getReceivedFromSuppliers();
				this.getCashReceived();
				this.getCashPaid();
				this.getBankDeposits();
				this.getBankWithdraws();
				this.getEmployeePayments();
			},

			getOpeningBalance() {
				axios.post('/get_cash_and_bank_balance', {
					date: this.filter.dateFrom
				}).then(res => {
					this.openingBalance = res.data;
				})
			},

			getClosingBalance() {
				axios.post('/get_cash_and_bank_balance', {
					date: moment(this.filter.dateTo).add(1, 'days').format('YYYY-MM-DD')
				}).then(res => {
					this.closingBalance = res.data;
				})
			},

			getSales() {
				axios.post('/get_sales', this.filter)
					.then(res => {
						let sales = res.data.sales.filter(sale => sale.SaleMaster_PaidAmount > 0);
						sales = _.groupBy(sales, 'SalseCustomer_IDNo');
						sales = _.toArray(sales);
						sales = sales.map(sale => {
							sale[0].totalAmount = sale.reduce((p, c) => {
								return p + parseFloat(c.SaleMaster_PaidAmount)
							}, 0);
							return sale[0];
						})
						this.sales = sales;
					})
			},

			getPurchases() {
				axios.post('/get_purchases', this.filter)
					.then(res => {
						let purchases = res.data.purchases.filter(purchase => purchase.PurchaseMaster_PaidAmount > 0);
						purchases = _.groupBy(purchases, 'Supplier_SlNo');
						purchases = _.toArray(purchases);
						purchases = purchases.map(purchase => {
							purchase[0].totalAmount = purchase.reduce((p, c) => {
								return p + parseFloat(c.PurchaseMaster_PaidAmount)
							}, 0);
							return purchase[0];
						})
						this.purchases = purchases;
					})
			},

			getReceivedFromCustomers() {
				let filter = {
					dateFrom: this.filter.dateFrom,
					dateTo: this.filter.dateTo,
					paymentType: 'received'
				}
				axios.post('/get_customer_payments', filter)
					.then(res => {
						let payments = res.data;
						payments = _.groupBy(payments, 'CPayment_customerID');
						payments = _.toArray(payments);
						payments = payments.map(payment => {
							payment[0].totalAmount = payment.reduce((p, c) => {
								return p + parseFloat(c.CPayment_amount)
							}, 0);
							return payment[0];
						})
						this.receivedFromCustomers = payments;
					})
			},

			getPaidToCustomers() {
				let filter = {
					dateFrom: this.filter.dateFrom,
					dateTo: this.filter.dateTo,
					paymentType: 'paid'
				}
				axios.post('/get_customer_payments', filter)
					.then(res => {
						let payments = res.data;
						payments = _.groupBy(payments, 'CPayment_customerID');
						payments = _.toArray(payments);
						payments = payments.map(payment => {
							payment[0].totalAmount = payment.reduce((p, c) => {
								return p + parseFloat(c.CPayment_amount)
							}, 0);
							return payment[0];
						})
						this.paidToCustomers = payments;
					})
			},

			getPaidToSuppliers() {
				let filter = {
					dateFrom: this.filter.dateFrom,
					dateTo: this.filter.dateTo,
					paymentType: 'paid'
				}
				axios.post('/get_supplier_payments', filter)
					.then(res => {
						let payments = res.data;
						payments = _.groupBy(payments, 'SPayment_customerID');
						payments = _.toArray(payments);
						payments = payments.map(payment => {
							payment[0].totalAmount = payment.reduce((p, c) => {
								return p + parseFloat(c.SPayment_amount)
							}, 0);
							return payment[0];
						})
						this.paidToSuppliers = payments;
					})
			},

			getReceivedFromSuppliers() {
				let filter = {
					dateFrom: this.filter.dateFrom,
					dateTo: this.filter.dateTo,
					paymentType: 'received'
				}
				axios.post('/get_supplier_payments', filter)
					.then(res => {
						let payments = res.data;
						payments = _.groupBy(payments, 'SPayment_customerID');
						payments = _.toArray(payments);
						payments = payments.map(payment => {
							payment[0].totalAmount = payment.reduce((p, c) => {
								return p + parseFloat(c.SPayment_amount)
							}, 0);
							return payment[0];
						})
						this.receivedFromSuppliers = payments;
					})
			},

			getCashReceived() {
				let filter = {
					dateFrom: this.filter.dateFrom,
					dateTo: this.filter.dateTo,
					transactionType: 'received'
				}
				axios.post('/get_cash_transactions', filter)
					.then(res => {
						let transactions = res.data;
						transactions = _.groupBy(transactions, 'Acc_SlID');
						transactions = _.toArray(transactions);
						transactions = transactions.map(transaction => {
							transaction[0].totalAmount = transaction.reduce((p, c) => {
								return p + parseFloat(c.In_Amount)
							}, 0);
							return transaction[0];
						})
						this.cashReceived = transactions;
					})
			},

			getCashPaid() {
				let filter = {
					dateFrom: this.filter.dateFrom,
					dateTo: this.filter.dateTo,
					transactionType: 'paid'
				}
				axios.post('/get_cash_transactions', filter)
					.then(res => {
						let transactions = res.data;
						transactions = _.groupBy(transactions, 'Acc_SlID');
						transactions = _.toArray(transactions);
						transactions = transactions.map(transaction => {
							transaction[0].totalAmount = transaction.reduce((p, c) => {
								return p + parseFloat(c.Out_Amount)
							}, 0);
							return transaction[0];
						})
						this.cashPaid = transactions;
					})
			},

			getBankDeposits() {
				let filter = {
					dateFrom: this.filter.dateFrom,
					dateTo: this.filter.dateTo,
					transactionType: 'deposit'
				}
				axios.post('/get_bank_transactions', filter)
					.then(res => {
						let transactions = res.data;
						transactions = _.groupBy(transactions, 'account_id');
						transactions = _.toArray(transactions);
						transactions = transactions.map(transaction => {
							transaction[0].totalAmount = transaction.reduce((p, c) => {
								return p + parseFloat(c.amount)
							}, 0);
							return transaction[0];
						})
						this.bankDeposits = transactions;
					})
			},

			getBankWithdraws() {
				let filter = {
					dateFrom: this.filter.dateFrom,
					dateTo: this.filter.dateTo,
					transactionType: 'withdraw'
				}
				axios.post('/get_bank_transactions', filter)
					.then(res => {
						let transactions = res.data;
						transactions = _.groupBy(transactions, 'account_id');
						transactions = _.toArray(transactions);
						transactions = transactions.map(transaction => {
							transaction[0].totalAmount = transaction.reduce((p, c) => {
								return p + parseFloat(c.amount)
							}, 0);
							return transaction[0];
						})
						this.bankWithdraws = transactions;
					})
			},

			getEmployeePayments() {
				axios.post('/get_employee_payments', this.filter)
					.then(res => {
						let payments = res.data;
						payments = _.groupBy(payments, 'Employee_SlNo');
						payments = _.toArray(payments);
						payments = payments.map(payment => {
							payment[0].totalAmount = payment.reduce((p, c) => {
								return p + parseFloat(c.payment_amount)
							}, 0);
							return payment[0];
						})
						this.employeePayments = payments;

					})
			},
			sendSMS() {
				let filter = {
					'totalBankOpeningBalance': this.totalBankOpeningBalance,
					'totalBankClosingBalance': this.totalBankClosingBalance,
					'totalSales': this.totalSales,
					'totalPurchase': this.totalPurchase,
					'totalReceivedFromCustomers': this.totalReceivedFromCustomers,
					'totalPaidToCustomers': this.totalPaidToCustomers,
					'totalReceivedFromSuppliers': this.totalReceivedFromSuppliers,
					'totalPaidToSuppliers': this.totalPaidToSuppliers,
					'totalCashReceived': this.totalCashReceived,
					'totalCashPaid': this.totalCashPaid,
					'totalBankDeposit': this.totalBankDeposit,
					'totalBankWithdraw': this.totalBankWithdraw,
					'totalEmployeePayments': this.totalEmployeePayments,
					'totalIn': this.totalIn,
					'totalOut': this.totalOut,
					'cashBalance': this.cashBalance,
					'openingBalance': this.openingBalance.cashBalance.cash_balance,
					'closingBalance': this.closingBalance.cashBalance.cash_balance,
				}
				axios.post("/send_sms_day_book", filter).then(res => {
					alert(res.data.message);
				})

			},

			async print() {
				let printContent = `
					<div class="container">
						<h4 style="text-align:center">Receipt and Payment</h4 style="text-align:center">
						<div class="row">
							<div class="col-xs-12 text-center">
								<strong>Statement from</strong> ${this.filter.dateFrom} <strong>to</strong> ${this.filter.dateTo}
							</div>
						</div>
					</div>
					<div class="container">
						${document.querySelector('#printContent').innerHTML}
						<div class="row" style="margin: 80px 0 5px 0;">
							<div class="col-xs-4">
								<span style="text-decoration:overline;">Prepared By</span><br><br>
							</div>
							<div class="col-xs-4 text-center">
								<span style="text-decoration:overline;">Accounts</span><br><br>
							</div>
							<div class="col-xs-4 text-right">
								<span style="text-decoration:overline;">Authorized By</span>
							</div>
						</div>
					</div>
				`;

				var printWindow = window.open('', 'PRINT', `width=${screen.width}, height=${screen.height}`);
				printWindow.document.write(`
					<?php $this->load->view('Administrator/reports/reportHeader_dbook.php'); ?>
				`);

				printWindow.document.body.innerHTML += printContent;
				printWindow.document.head.innerHTML += `
					<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
					<style>
					.sohel {
						padding: 0px;
						margin:0px -10px
					}
						.day-book-table {
							width: 100%;
							margin-bottom: 50px;
						}
						.day-book-table thead {
							background: #ebebeb;
							border-bottom: 1px solid black;
						}
						.day-book-table th {
							padding: 5px 5px;
							text-align: left;
						}
						.day-book-table td {
							padding: 0px 5px;
						}
						.day-book-table tr td:last-child {
							text-align: right;
							// padding-right: 50px;
						}
						.day-book-table .main-heading {
							padding-left: 10px;
							font-weight: bold;
						}
						.day-book-table .sub-heading {
							padding-left: 5px;
							font-weight: bold;
						}
						.day-book-table .sub-value {
							padding-right: 5px!important;
							font-weight: bold;
						}
					</style>
				`;

				printWindow.focus();
				await new Promise(resolve => setTimeout(resolve, 1000));
				printWindow.print();
				printWindow.close();
			}
		}
	})
</script>