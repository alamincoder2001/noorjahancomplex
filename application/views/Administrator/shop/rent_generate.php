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

    .pagination {
        margin: 10px 0 !important;
    }

    label {
        padding: 0px 10px;
    }

    .fixTableHead {
        overflow-y: auto;
        height: 500px;
    }

    .fixTableHead thead th {
        position: sticky;
        top: 0;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    th {
        background: #ABDD93;
    }
</style>
<div id="RentGenerate">
    <div class="row" style="border-bottom:1px solid #ccc;padding: 10px 0;">
        <div class="col-md-12">
            <form class="form-inline" @submit.prevent="getShop">
                <div class="form-group">
                    <label>Month</label>
                    <v-select :options="months" label="month_name" v-model="month" style="display:none;" v-bind:style="{display: months.length > 0 ? '' : 'none'}"></v-select>
                </div>
                <div class="form-group">
                    <label>Generte Date</label>
                    <input style="height: 25px;" type="date" v-model="rent.generate_date">
                </div>
                <div class="form-group">
                    <label>Service Month</label>
                    <v-select :options="months" label="month_name" v-model="serviceMonth" style="display:none;" v-bind:style="{display: months.length > 0 ? '' : 'none'}"></v-select>
                </div>
                <div class="form-group" style="margin-top: -5px;">
                    <input type="submit" class="search-button" value="Show">
                </div>
            </form>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive fixTableHead">
                <table class="table table-bordered" v-if="shops.length > 0">
                    <thead id="header-fixed">
                        <tr>
                            <th>SL</th>
                            <th>Shop Name</th>
                            <th>Floor</th>
                            <th>Shop No</th>
                            <th>Shop Rent</th>
                            <th>Adjustment</th>
                            <th>AIT Adjustment</th>
                            <th>Electricity Bill</th>
                            <th>Water Bill</th>
                            <th>Gass Bill</th>
                            <th>AC Bill</th>
                            <th>Service Charge</th>
                            <th>Other Charge</th>
                            <th>Total Payable</th>
                            <th>Comment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(rent, i) in cart">

                            <td>{{ i + 1 }}</td>
                            <td>{{ rent.shop_name }}</td>
                            <td>{{ rent.floor_name }}</td>
                            <td>{{ rent.shop_no }}</td>
                            <td><input style="width: 95px; text-align:center;" type="text" v-model="rent.shop_or_flat_rent" @input="totalAmount(rent)"></td>
                            <td><input style="width: 85px; text-align:center;" type="text" v-model="rent.adjustment_amount" readonly></td>
                            <td><input style="width: 70px; text-align:center;" type="text" v-model="rent.ait_adjustment" @input="Calculate(i)"></td>
                            <td><input style="width: 80px; text-align:center;" type="text" v-model="rent.electricity_bill_amount" readonly></td>
                            <td><input style="width: 80px; text-align:center;" type="text" v-model="rent.shop_water_bill" @input="totalAmount(rent)"></td>
                            <td><input style="width: 80px; text-align:center;" type="text" v-model="rent.shop_gass_bill" @input="totalAmount(rent)"></td>
                            <td><input style="width: 80px; text-align:center;" type="text" v-model="rent.ac_bill" @input="totalAmount(rent)"></td>
                            <td><input style="width: 80px; text-align:center;" type="text" v-model="rent.total_service_charge" @input="totalAmount(rent)"></td>
                            <td><input style="width: 80px; text-align:center;" type="text" v-model="rent.other_charge" @input="totalAmount(rent)"></td>
                            <td><input style="width: 120px; text-align:center;" type="text" v-model="rent.total_amount" readonly @input="totalAmount(rent)"></td>
                            <td><textarea style="width: 120px; height: 30px; font-size:12px;" cols="" rows="1" v-model="rent.comment"></textarea></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="14">
                                <button type="button" @click="SaveGenerate" name="btnSubmit" title="Save" class="btn btn-sm btn-success pull-right" :disabled='isProcess'>
                                    Save
                                    <i class="ace-icon fa fa-arrow-right icon-on-right bigger-110"></i>
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                </table>

                <table class="table table-bordered" v-if="generate">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Shop Name</th>
                            <th>Floor</th>
                            <th>Shop No</th>
                            <th>Shop Rent</th>
                            <th>Adjustment</th>
                            <th>AIT Adjustment</th>
                            <th>Electricity Bill</th>
                            <th>Water Bill</th>
                            <th>Gass Bill</th>
                            <th>AC Bill</th>
                            <th>Service Charge</th>
                            <th>Other Charge</th>
                            <th>Total Payable</th>
                            <th>Comment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(rent, i) in rents">
                            <td>{{ ++i }}</td>
                            <td>{{ rent.shop_name }}</td>
                            <td>{{ rent.floor_name }}</td>
                            <td>{{ rent.shop_no }}</td>
                            <td><input style="width: 95px; text-align:center;" type="text" v-model="rent.shop_or_flat_rent" @input="totalAmount(rent)"></td>
                            <td><input style="width: 85px; text-align:center;" type="text" v-model="rent.adjustment_amount" @input="totalAmount(rent)"></td>
                            <td><input style="width: 70px; text-align:center;" type="text" @input="totalAmount(rent)" v-model="rent.ait_adjustment" readonly></td>
                            <td><input style="width: 80px; text-align:center;" type="text" v-model="rent.electricity_bill_amount" readonly></td>
                            <td><input style="width: 80px; text-align:center;" type="text" v-model="rent.shop_water_bill" @input="totalAmount(rent)"></td>
                            <td><input style="width: 80px; text-align:center;" type="text" v-model="rent.shop_gass_bill" @input="totalAmount(rent)"></td>
                            <td><input style="width: 80px; text-align:center;" type="text" v-model="rent.ac_bill" @input="totalAmount(rent)"></td>
                            <td><input style="width: 80px; text-align:center;" type="text" v-model="rent.total_service_charge" @input="totalAmount(rent)"></td>
                            <td><input style="width: 80px; text-align:center;" type="text" v-model="rent.other_charge" @input="totalAmount(rent)"></td>
                            <td><input style="width: 120px; text-align:center;" type="text" v-model="rent.total_amount" readonly @input="totalAmount(rent)"></td>
                            <td><textarea style="width: 120px; height: 30px; font-size:12px;" cols="" rows="1" v-model="rent.comment"></textarea></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="14">
                                <button type="button" @click="SaveGenerate" name="btnSubmit" title="Save" class="btn btn-sm btn-success pull-right">
                                    Update
                                    <i class="ace-icon fa fa-arrow-right icon-on-right bigger-110"></i>
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
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
    var app = new Vue({
        el: '#RentGenerate',
        data: {
            rent: {
                id: 0,
                generate_date: moment().format("YYYY-MM-DD"),
                month_id: null,
                service_month: '',
            },
            shops: [],
            rents: [],
            months: [],
            cart: [],
            month: null,
            serviceMonth: null,
            generate: false,
            aitCheck: true,
            previousDue: 0,
            isProcess: false,
        },
        watch: {
            month(month) {
                if (this.month == undefined) return;
                this.rent.month_id = month.month_id;
                // console.log(this.rent.month_id);
            },
            serviceMonth(serviceMonth) {
                if (this.serviceMonth == undefined) return;
                this.rent.service_month = serviceMonth.month_name;
                // console.log(this.rent.service_month);
            }
        },
        created() {
            this.getMonth();
        },
        methods: {
            Calculate(ind) {
                let total_amount = parseFloat((parseFloat(this.cart[ind].shop_or_flat_rent) + parseFloat(this.cart[ind].electricity_bill_amount) + parseFloat(this.cart[ind].shop_water_bill) + parseFloat(this.cart[ind].shop_gass_bill) + parseFloat(this.cart[ind].total_service_charge) + parseFloat(this.cart[ind].other_charge)) - parseFloat(this.cart[ind].adjustment_amount) - parseFloat(this.cart[ind].ait_adjustment)).toFixed(2);
                this.cart[ind].total_amount = total_amount;
            },
            getMonth() {
                axios.get('/get_months').then(res => {
                    this.months = res.data;
                    // console.log(this.months);
                })
            },
            async getShop() {
                if (this.rent.month_id == null) {
                    alert('Please Select Month');
                    return;
                }
                if (this.rent.service_month == '') {
                    alert('Please Select Sevice Month');
                    return;
                }
                await axios.post('/check_generate_month', {
                        month_id: this.month.month_id
                    })
                    .then(res => {
                        this.generate = false;
                        if (res.data.success) {
                            this.generate = true;
                        }
                    })

                if (this.generate) {
                    await axios.get('/get_generate_month_rent/' + this.month.month_id)
                        .then(res => {
                            this.cart = [];
                            this.shops = [];
                            this.rents = res.data.map((item, ait) => {
                                item.ait = '';
                                item.electricity_bill_amount = parseFloat(item.electricity_bill_amount).toFixed(2)
                                item.shop_or_flat_rent = item.shop_rent
                                item.total_service_charge = item.service_charge
                                return item;
                            });
                        })
                } else {
                    let filter = {
                        month_id: this.month.month_id,
                        // service_month: this.rent.service_month
                    }
                    await axios.post('/get_shop_for_rent_generate', filter).then(res => {
                        this.rents = [];
                        this.shops = res.data;
                        console.log(this.shops);

                        this.shops.forEach(item => {
                            let CartRent = {
                                shop_name: item.shop_name,
                                shop_id: item.id,
                                renter_id: item.renter_id,
                                floor_name: item.floor_name,
                                floor_id: item.floor_id,
                                shop_no: item.shop_no,
                                shop_or_flat_rent: item.shop_or_flat_rent,
                                adjustment_amount: item.adjustment_amount,
                                ait_adjustment: 0.00,
                                shop_electricity_bill: item.shop_electricity_bill,
                                shop_water_bill: item.shop_water_bill,
                                shop_gass_bill: item.shop_gass_bill,
                                ac_bill: item.ac_bill,
                                electricity_bill_amount: parseFloat(item.electricity_bill_amount).toFixed(2),
                                total_service_charge: item.total_service_charge,
                                other_charge: item.other_charge,
                                total_amount: parseFloat((parseFloat(item.shop_or_flat_rent) + parseFloat(item.electricity_bill_amount) + parseFloat(item.shop_electricity_bill) + parseFloat(item.shop_water_bill) + parseFloat(item.shop_gass_bill) + parseFloat(item.total_service_charge) + parseFloat(item.other_charge)) - parseFloat(item.adjustment_amount)).toFixed(2),
                                previous_due: item.due,
                                comment: '',
                            }
                            this.cart.push(CartRent);
                        })
                    })
                }
            },
            totalAmount(rent) {
                // console.log(rent);
                let total_amount = parseFloat(parseFloat(rent.ac_bill) + parseFloat(rent.shop_or_flat_rent) + parseFloat(rent.shop_electricity_bill) + parseFloat(rent.shop_water_bill) + parseFloat(rent.shop_gass_bill) + parseFloat(rent.total_service_charge) + parseFloat(rent.other_charge)) - (parseFloat(rent.adjustment_amount) - parseFloat(rent.ait_adjustment)).toFixed(2);
                rent.total_amount = parseFloat(total_amount).toFixed(2);
            },

            SaveGenerate() {
                let data = {
                    rent: this.rent,
                    cart: this.cart,
                    rents: this.rents
                }
                let url = "/save_generate_rent";
                if (this.generate) {
                    url = "/update_generate_rent";
                }
                this.isProcess = true;

                axios.post(url, data).then(res => {
                    let r = res.data;
                    alert(r.message);
                    if (r.success) {
                        this.resetForm();
                        this.isProcess = false;
                    }
                })
            },

            resetForm() {
                this.rent = {
                    id: 0,
                    generate_date: moment().format("YYYY-MM-DD"),
                    month_id: null,
                    service_month: '',
                }
                this.month = null;
                this.cart = [];
                this.rents = [];
            }
        }
    })
</script>