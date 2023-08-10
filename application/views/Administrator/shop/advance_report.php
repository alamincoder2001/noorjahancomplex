<style>
	.v-select{
		margin-bottom: 5px;
	}
	.v-select .dropdown-toggle{
		padding: 0px;
	}
	.v-select input[type=search], .v-select input[type=search]:focus{
		margin: 0px;
	}
	.v-select .vs__selected-options{
		overflow: hidden;
		flex-wrap:nowrap;
	}
	.v-select .selected-tag{
		margin: 2px 0px;
		white-space: nowrap;
		position:absolute;
		left: 0px;
	}
	.v-select .vs__actions{
		margin-top:-5px;
	}
	.v-select .dropdown-menu{
		width: auto;
		overflow-y:auto;
	}
</style>

<div class="row" id="customerAdvanceList">
	<div class="col-xs-12 col-md-12 col-lg-12" style="border-bottom:1px #ccc solid;">
		<div class="form-group">
			<label class="col-sm-1 control-label no-padding-right">Search Type</label>
			<div class="col-sm-2">
				<select class="form-control" v-model="searchType" v-on:change="onChangeSearchType" style="padding:0px;">
					<option value="all">All</option>
					<option value="customer">By Renter</option>
				</select>
			</div>
		</div>
		<div class="form-group" style="display: none" v-bind:style="{display: searchType == 'customer' ? '' : 'none'}">
			<label class="col-sm-2 control-label no-padding-right">Select Renter</label>
			<div class="col-sm-3">
				<v-select v-bind:options="customers" v-model="selectedCustomer" label="display_name" placeholder="Select customer"></v-select>
			</div>
		</div>
		

		<div class="form-group">
			<div class="col-sm-2">
				<input type="button" class="btn btn-primary" value="Show Report" v-on:click="getDues" style="margin-top:0px;border:0px;height:28px; border-radius: 3px;">
			</div>
		</div>
	</div>

	<div class="col-md-12" style="display: none" v-bind:style="{display: advance.length > 0 ? '' : 'none'}">
		<a href="" style="margin: 7px 0;display:block;width:50px;" v-on:click.prevent="print">
			<i class="fa fa-print"></i> Print
		</a>
		<div class="table-responsive" id="reportTable">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>Renter Id</th>
						<th>Renter Name</th>
						<th>Owner Name</th>
						<th>Address</th>
						<th>Renter Mobile</th>
						<th>Advance Amount</th>
						<th>Paid Amount</th>
						<th>Amount</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="data in advance">
						<td>{{ data.Customer_Code }}</td>
						<td>{{ data.Customer_Name }}</td>
						<td>{{ data.owner_name }}</td>
						<td>{{ data.Customer_Address }}</td>
						<td>{{ data.Customer_Mobile }}</td>
						<td style="text-align:right">{{ parseFloat(data.AdvanceAmount).toFixed(2) }}</td>
						<td style="text-align:right">{{ parseFloat(data.PaidAdvanceAmount).toFixed(2) }}</td>
						<td style="text-align:right">{{ parseFloat(data.RestAdvanceAmount).toFixed(2) }}</td>
					</tr>
				</tbody>
				<tfoot>
					<tr style="font-weight:bold;">
						<td colspan="5" style="text-align:right">Total: </td>
						<td style="text-align:right">{{ parseFloat(totalAdvance).toFixed(2) }}</td>
						<td style="text-align:right">{{ parseFloat(totalPaidAdvance).toFixed(2) }}</td>
						<td style="text-align:right">{{ parseFloat(totalRestAdvance).toFixed(2) }}</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>

<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/vue-select.min.js"></script>

<script>
	Vue.component('v-select', VueSelect.VueSelect);
	new Vue({
		el: '#customerAdvanceList',
		data(){
			return {
				searchType: 'all',
				customers: [],
				selectedCustomer: null,
				advance: [],
				totalAdvance: 0.00,
				totalPaidAdvance: 0.00,
				totalRestAdvance: 0.00,
			}
		},
		created(){

		},
		methods:{
			onChangeSearchType(){
				if(this.searchType == 'customer' && this.customers.length == 0){
					this.getCustomers();
				} 
				if(this.searchType == 'all'){
					this.selectedCustomer = null;
					this.selectedArea = null;
				}
			},
			getCustomers(){
				axios.get('/get_customers').then(res => {
					this.customers = res.data;
				})
			},
			
			getDues(){
				if(this.searchType == 'customer' && this.selectedCustomer == null){
					alert('Select Renter');
					console.log(this.selectedCustomer);
					return;
				}

				let customerId = this.selectedCustomer == null ? null : this.selectedCustomer.Customer_SlNo;
				axios.post('/get_renter_advance', {customerId: customerId}).then(res => {
					if(this.searchType == 'customer'){
						this.advance = res.data;
					} else {
						this.advance = res.data.filter(d => parseFloat(d.AdvanceAmount) != 0);
					}
					this.totalAdvance = this.advance.reduce((prev, cur) => { return prev + parseFloat(cur.AdvanceAmount) }, 0);
					this.totalPaidAdvance = this.advance.reduce((prev, cur) => { return prev + parseFloat(cur.PaidAdvanceAmount) }, 0);
					this.totalRestAdvance = this.advance.reduce((prev, cur) => { return prev + parseFloat(cur.RestAdvanceAmount) }, 0);
				})
			},
			async print(){
				let reportContent = `
					<div class="container">
						<h4 style="text-align:center">Renter Advance report</h4 style="text-align:center">
						<div class="row">
							<div class="col-xs-12">
								${document.querySelector('#reportTable').innerHTML}
							</div>
						</div>
					</div>
				`;

				var mywindow = window.open('', 'PRINT', `width=${screen.width}, height=${screen.height}`);
				mywindow.document.write(`
					<?php $this->load->view('Administrator/reports/reportHeader.php');?>
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