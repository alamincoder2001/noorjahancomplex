<div id="ElectricityInvoice">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="row">
                <div class="col-xs-12">
                    <a href="" v-on:click.prevent="print"><i class="fa fa-print"></i> Print</a>
                </div>
            </div>

            <div id="invoiceContent">
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <div _h098asdh>
                            Electricity Bill
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <table _a584de>
                            <thead>
                                <tr>
                                    <td class="text-start">Client Name :</td>
                                    <td colspan="3">{{ electricity.shop_name }} ({{ electricity.shop_no }})</td>
                                </tr>
                                <tr>
                                    <td class="text-start">Location :</td>
                                    <td colspan="3">{{ electricity.floor_name }}</td>
                                </tr>
                                <tr>
                                    <td class="text-start">Master Meter No :</td>
                                    <td>{{ electricity.master_meter_no }}</td>
                                    <td class="text-start">Sub-Meter No :</td>
                                    <td>{{ electricity.sub_meter_no }}</td>
                                </tr>
                                <tr>
                                    <td class="text-start">Bill For :</td>
                                    <td>Electricity</td>
                                    <td class="text-start">Billing Month :</td>
                                    <td>{{ electricity.month_name }}</td>
                                </tr>
                                <tr>
                                    <td class="text-start">Issue Date :</td>
                                    <td>{{ moment(electricity.issue_date).format("DD-MM-YYYY") }}</td>
                                    <td class="text-start">Last Date :</td>
                                    <td>{{ moment(electricity.last_date).format("DD-MM-YYYY") }}</td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-xs-12">
                        <table _a584de>
                            <thead>
                                <tr>
                                    <td>Reading</td>
                                    <td>Unit</td>
                                    <td>Details</td>
                                    <td>Amount</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-bind:style="{display: electricity.is_pickhour == '0' ? '' : 'none'}">
                                    <td class="text-start">Current Unit :</td>
                                    <td>{{ electricity.current_unit }}</td>
                                    <td class="text-start"> Unit rate :</td>
                                    <td>{{ electricity.per_unit_price }}</td>
                                </tr>
                                <tr v-bind:style="{display: electricity.is_pickhour == '0' ? '' : 'none'}">
                                    <td class="text-start">Previous Unit :</td>
                                    <td>{{ electricity.previous_unit }}</td>
                                    <td class="text-start">Price Of Bill Units :</td>
                                    <td>{{ PriceOfBill }}</td>
                                </tr>
                                <tr v-bind:style="{display: electricity.is_pickhour == '0' ? '' : 'none'}">
                                    <td class="text-start">Use Unit :</td>
                                    <td>{{ usesUnit }}</td>
                                    <td class="text-start"></td>
                                    <td></td>
                                </tr>

                                <tr v-bind:style="{display: electricity.is_pickhour == '1' ? '' : 'none'}">
                                    <td class="text-start">Current Pick Hour Unit :</td>
                                    <td>{{ electricity.cur_pick_hour_unit }}</td>
                                    <td class="text-start">Pick Hour Units rate :</td>
                                    <td>{{ electricity.pick_hour_unit_price }}</td>
                                </tr>
                                <tr v-bind:style="{display: electricity.is_pickhour == '1' ? '' : 'none'}">
                                    <td class="text-start">Previous Pick Hour Unit :</td>
                                    <td>{{ electricity.prev_pick_hour_unit }}</td>
                                    <td class="text-start">Price Of Bill Units :</td>
                                    <td>{{ PriceOfPickHourBill }}</td>
                                </tr>
                                <tr v-bind:style="{display: electricity.is_pickhour == '1' ? '' : 'none'}">
                                    <td class="text-start">Use Unit :</td>
                                    <td>{{ usePickHour }}</td>
                                    <td class="text-start"></td>
                                    <td></td>
                                </tr>

                                <tr v-bind:style="{display: electricity.is_pickhour == '1' ? '' : 'none'}">
                                    <td class="text-start">Current Off Pick Hour Unit :</td>
                                    <td>{{ electricity.cur_off_pick_hour_unit }}</td>
                                    <td class="text-start">Off Pick Hour Units rate :</td>
                                    <td>{{ electricity.off_pick_hour_unit_price }}</td>
                                </tr>
                                <tr v-bind:style="{display: electricity.is_pickhour == '1' ? '' : 'none'}">
                                    <td class="text-start">Previous Off Pick Hour Unit :</td>
                                    <td>{{ electricity.prev_off_pick_hour_unit }}</td>
                                    <td class="text-start">Price Of Bill Units :</td>
                                    <td>{{ PriceOfOffPickHourBill }}</td>
                                </tr>
                                <tr v-bind:style="{display: electricity.is_pickhour == '1' ? '' : 'none'}">
                                    <td class="text-start">Use Unit :</td>
                                    <td>{{ useOffPickHour }}</td>
                                    <td class="text-start"></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td class="text-start"></td>
                                    <td></td>
                                    <td class="text-start">D & S Charge :</td>
                                    <td>{{ electricity.ds_charge }}</td>
                                </tr>
                                <tr>
                                    <td class="text-start">Applicable Common Unit :</td>
                                    <td>{{ electricity.common_unit }}</td>
                                    <td class="text-start">Vat (on Current) :</td>
                                    <td>{{ vatAmount }}</td>
                                </tr>
                                <tr>
                                    <td class="text-start">Total Units For Biling :</td>
                                    <td>{{ totalUnit }}</td>
                                    <td class="text-start">Including Bill Amount :</td>
                                    <td>{{ TotalBillAmountWithVat }}</td>
                                </tr>
                                <tr>
                                    <td class="table-head" colspan="4">Outstanding Bills :</td>
                                </tr>
                                <tr>
                                    <td class="text-start">Late fee after 10{{moment().format('-MM-YYYY')}}:</td>
                                    <td>5%</td>
                                    <td class="text-start">Amount :</td>
                                    <td>{{ LateFee }}</td>
                                </tr>
                                <tr>
                                    <td class="text-start"></td>
                                    <td></td>
                                    <td class="text-start">Total Amount :</td>
                                    <td>{{ PayWithLateFee }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row" style="margin-top: 15px;">
                    <div class="col-xs-12">
                        <strong>In Word : {{ convertNumberToWords(TotalBillAmountWithVat) }}</strong><br><br>
                        <p style="white-space: pre-line; text-align: center; font-weight:700; border: 2px solid #ccc;">Minimum Bill Amount Tk. 200.00 only will be paid if total bill amount is less than tk. 200.00</p>
                    </div>
                </div>
                <div class="row" style="margin-top:60px;">
                    <div class="col-xs-4">
                        <span style="text-decoration:overline;">Prepared By</span>
                    </div>
                    <div class="col-xs-4" style="text-align:center">
                        <span style="text-decoration:overline;">Accounts</span>
                    </div>
                    <div class="col-xs-4 text-right">
                        <span style="text-decoration:overline;">Authorize By</span>
                    </div>
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
    new Vue({
        el: '#ElectricityInvoice',
        data() {
            return {
                electricity: {
                    id: parseInt('<?php echo $ElecId; ?>'),
                    shop_id: null,
                    month_id: null,
                    issue_date: moment().format("YYYY-MM-DD"),
                    last_date: moment().format("YYYY-MM-DD"),
                    previous_unit: 0,
                    current_unit: 0,
                    common_unit: 0,
                    per_unit_price: 0,
                    cur_pick_hour_unit: 0,
                    pick_hour_unit_price: 0,
                    cur_off_pick_hour_unit: 0,
                    off_pick_hour_unit_price: 0,
                    prev_off_pick_hour_unit: 0,
                    prev_pick_hour_unit: 0,
                    pick_hour_vat: 0,
                    ds_charge: 0,
                },
                style: null,
                companyProfile: null,
                currentBranch: null
            }
        },
        computed: {
            usesUnit() {
                return parseFloat(this.electricity.current_unit - this.electricity.previous_unit).toFixed(2);
            },
            usePickHour() {
                return parseFloat(this.electricity.cur_pick_hour_unit - this.electricity.prev_pick_hour_unit).toFixed(2);
            },
            useOffPickHour() {
                return parseFloat(this.electricity.cur_off_pick_hour_unit - this.electricity.prev_off_pick_hour_unit).toFixed(2);
            },
            totalUnit() {
                return parseFloat(+this.usesUnit + +this.usePickHour + +this.useOffPickHour + +this.electricity.common_unit).toFixed(2);
            },

            PriceOfBill() {
                return parseFloat(this.totalUnit * this.electricity.per_unit_price).toFixed(2);
            },

            PriceOfPickHourBill() {
                return parseFloat(this.usePickHour * this.electricity.pick_hour_unit_price).toFixed(2);
            },

            PriceOfOffPickHourBill() {
                return parseFloat(this.useOffPickHour * this.electricity.off_pick_hour_unit_price).toFixed(2);
            },

            TotalBillAmount() {
                return parseFloat(+this.PriceOfBill + +this.PriceOfPickHourBill + +this.PriceOfOffPickHourBill + +this.electricity.ds_charge).toFixed(2);
            },

            vatAmount() {
                return parseFloat((this.TotalBillAmount / 100) * this.electricity.pick_hour_vat).toFixed(2);
            },
            TotalBillAmountWithVat() {
                return parseFloat(+this.TotalBillAmount + +this.vatAmount).toFixed(2);
            },
            LateFee() {
                return parseFloat((this.TotalBillAmountWithVat / 100) * 5).toFixed(2);
            },
            PayWithLateFee() {
                return parseFloat(+this.TotalBillAmountWithVat + +this.LateFee).toFixed(2);
            }
        },
        created() {
            this.setStyle();
            this.getCompanyProfile();
            this.getCurrentBranch();
            this.getElectricity();
            // console.log(this.electricity.id);
        },
        methods: {
            async getElectricity() {
                await axios.post('/get_electricity_bill', {
                        electricityId: this.electricity.id
                    })
                    .then(res => {
                        this.electricity = res.data[0];
                    })
            },
            getCompanyProfile() {
                axios.get('/get_company_profile').then(res => {
                    this.companyProfile = res.data;
                })
            },
            getCurrentBranch() {
                axios.get('/get_current_branch').then(res => {
                    this.currentBranch = res.data;
                })
            },
            formatDateTime(datetime, format) {
                return moment(datetime).format(format);
            },
            setStyle() {
                this.style = document.createElement('style');
                this.style.innerHTML = `
                div[_h098asdh]{
                    background-color:#e0e0e0;
                    font-weight: bold;
                    margin-bottom:15px;
                    padding: 5px;
                    font-size: 17px;
                    font-family: arial;
                    letter-spacing: 1px;
                }
                div[_d9283dsc]{
                    padding-bottom:25px;
                    border-bottom: 1px solid #ccc;
                    margin-bottom: 15px;
                }
                table[_a584de]{
                    width: 100%;
                    text-align:center;
                }
                table[_a584de] thead{
                    font-weight:bold;
                }
                table[_a584de] td{
                    padding: 1px;
                    border: 1px solid #ccc;
                    font-size: 12px;
                }
                table[_t92sadbc2]{
                    width: 100%;
                }
                table[_t92sadbc2] td{
                    padding: 2px;
                }
                .text-start {
                    text-align: left;
                    padding-left: 7px !important;
                }
                .table-head {
                    font-size: 16px;
                    font-family: arial;
                    letter-spacing: 1px;
                    background-color:#e0e0e0;
                    font-weight: bold;
                }
            `;
                document.head.appendChild(this.style);
            },
            convertNumberToWords(amountToWord) {
                var words = new Array();
                words[0] = '';
                words[1] = 'One';
                words[2] = 'Two';
                words[3] = 'Three';
                words[4] = 'Four';
                words[5] = 'Five';
                words[6] = 'Six';
                words[7] = 'Seven';
                words[8] = 'Eight';
                words[9] = 'Nine';
                words[10] = 'Ten';
                words[11] = 'Eleven';
                words[12] = 'Twelve';
                words[13] = 'Thirteen';
                words[14] = 'Fourteen';
                words[15] = 'Fifteen';
                words[16] = 'Sixteen';
                words[17] = 'Seventeen';
                words[18] = 'Eighteen';
                words[19] = 'Nineteen';
                words[20] = 'Twenty';
                words[30] = 'Thirty';
                words[40] = 'Forty';
                words[50] = 'Fifty';
                words[60] = 'Sixty';
                words[70] = 'Seventy';
                words[80] = 'Eighty';
                words[90] = 'Ninety';
                amount = amountToWord == null ? '0.00' : amountToWord.toString();
                var atemp = amount.split(".");
                var number = atemp[0].split(",").join("");
                var n_length = number.length;
                var words_string = "";
                if (n_length <= 9) {
                    var n_array = new Array(0, 0, 0, 0, 0, 0, 0, 0, 0);
                    var received_n_array = new Array();
                    for (var i = 0; i < n_length; i++) {
                        received_n_array[i] = number.substr(i, 1);
                    }
                    for (var i = 9 - n_length, j = 0; i < 9; i++, j++) {
                        n_array[i] = received_n_array[j];
                    }
                    for (var i = 0, j = 1; i < 9; i++, j++) {
                        if (i == 0 || i == 2 || i == 4 || i == 7) {
                            if (n_array[i] == 1) {
                                n_array[j] = 10 + parseInt(n_array[j]);
                                n_array[i] = 0;
                            }
                        }
                    }
                    value = "";
                    for (var i = 0; i < 9; i++) {
                        if (i == 0 || i == 2 || i == 4 || i == 7) {
                            value = n_array[i] * 10;
                        } else {
                            value = n_array[i];
                        }
                        if (value != 0) {
                            words_string += words[value] + " ";
                        }
                        if ((i == 1 && value != 0) || (i == 0 && value != 0 && n_array[i + 1] == 0)) {
                            words_string += "Crores ";
                        }
                        if ((i == 3 && value != 0) || (i == 2 && value != 0 && n_array[i + 1] == 0)) {
                            words_string += "Lakhs ";
                        }
                        if ((i == 5 && value != 0) || (i == 4 && value != 0 && n_array[i + 1] == 0)) {
                            words_string += "Thousand ";
                        }
                        if (i == 6 && value != 0 && (n_array[i + 1] != 0 && n_array[i + 2] != 0)) {
                            words_string += "Hundred and ";
                        } else if (i == 6 && value != 0) {
                            words_string += "Hundred ";
                        }
                    }
                    words_string = words_string.split("  ").join(" ");
                }
                return words_string + ' only';
            },
            async print() {
                let reportContent = `
					<div class="container">
						<div class="row">
							<div class="col-xs-12">
								${document.querySelector('#invoiceContent').innerHTML}
							</div>
						</div>
					</div>
				`;

                var reportWindow = window.open('', 'PRINT', `height=${screen.height}, width=${screen.width}`);
                reportWindow.document.write(`
					<?php $this->load->view('Administrator/reports/reportHeader_eb.php'); ?>
				`);

                reportWindow.document.body.innerHTML += reportContent;

                if (this.searchType == '' || this.searchType == 'user') {
                    let rows = reportWindow.document.querySelectorAll('.record-table tr');
                    rows.forEach(row => {
                        row.lastChild.remove();
                    })
                }

                let invoiceStyle = reportWindow.document.createElement('style');
                invoiceStyle.innerHTML = this.style.innerHTML;
                reportWindow.document.head.appendChild(invoiceStyle);

                reportWindow.focus();
                await new Promise(resolve => setTimeout(resolve, 1000));
                reportWindow.print();
                reportWindow.close();
            }
        }
    })
</script>