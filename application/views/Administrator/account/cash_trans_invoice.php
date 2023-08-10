<div id="chalan">
    <div class="row" style="display:none;" v-bind:style="{display: transactions.length > 0 ? '' : 'none'}">
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
                            Cash Transaction Invoice
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <strong>Transaction Id:</strong> {{ transaction.Tr_Id }}<br>
                    </div>
                    <div class="col-xs-4 text-right">
                        <strong>Transaction by:</strong> {{ transaction.AddBy }}<br>
                        <strong>Transaction Date:</strong> {{ transaction.Tr_date }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div _d9283dsc></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <table _a584de>
                            <thead>
                                <tr>
                                    <td>SL No</td>
                                    <td>Description</td>
                                    <td>Account Name</td>
                                    <td>Transacton Type</td>
                                    <td>In Amount</td>
                                    <td>Out Amount</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(trans, sl) in transactions">
                                    <td>{{ sl + 1 }}</td>
                                    <td>{{ trans.Tr_Description }}</td>
                                    <td>{{ trans.Acc_Name }}</td>
                                    <td>{{ trans.Tr_Type }}</td>
                                    <td>{{ trans.In_Amount }}</td>
                                    <td>{{ trans.Out_Amount }}</td>
                                </tr>
                                <tr style="font-weight: bold" v-for="(trans, sl) in transactions">
                                    <td colspan="4" style="text-align: right;"> Total </td>
                                    <td>{{ transactions[0].In_Amount }}</td>
                                    <td>{{ transactions[0].Out_Amount }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row" style="margin-top: 20px">
                    <div class="col-xs-12">
                        <strong v-if="transaction.In_Amount != 0">In Word: {{ convertNumberToWords(transaction.In_Amount) }}</strong><br><br>
                        <strong v-else>In Word: {{ convertNumberToWords(transaction.Out_Amount) }}</strong><br><br>
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
        el: '#chalan',
        data() {
            return {
                transaction: {
                    Tr_SlNo: parseInt('<?php echo $transId; ?>'),
                    Tr_Id: '',
                    Tr_date: '',
                    AddBy: ''
                },
                transactions: [],
                style: null,
                companyProfile: null,
                currentBranch: null
            }
        },
        created() {
            this.setStyle();
            this.getCashTransaction();
            this.getCompanyProfile();
            this.getCurrentBranch();
        },
        methods: {
            getCashTransaction() {
                axios.post('/get_cash_transactions', {
                    transSLNo: this.transaction.Tr_SlNo
                }).then(res => {
                    this.transactions = res.data;
                    this.transaction = res.data[0]
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
                    font-size:15px;
                    margin-bottom:15px;
                    padding: 5px;
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
                    padding: 3px;
                    border: 1px solid #ccc;
                }
                table[_t92sadbc2]{
                    width: 100%;
                }
                table[_t92sadbc2] td{
                    padding: 2px;
                }
            `;
                document.head.appendChild(this.style);
            },
            convertNumberToWords(amountToWord) {
                var words = new Array();
                words[0] = "";
                words[1] = "One";
                words[2] = "Two";
                words[3] = "Three";
                words[4] = "Four";
                words[5] = "Five";
                words[6] = "Six";
                words[7] = "Seven";
                words[8] = "Eight";
                words[9] = "Nine";
                words[10] = "Ten";
                words[11] = "Eleven";
                words[12] = "Twelve";
                words[13] = "Thirteen";
                words[14] = "Fourteen";
                words[15] = "Fifteen";
                words[16] = "Sixteen";
                words[17] = "Seventeen";
                words[18] = "Eighteen";
                words[19] = "Nineteen";
                words[20] = "Twenty";
                words[30] = "Thirty";
                words[40] = "Forty";
                words[50] = "Fifty";
                words[60] = "Sixty";
                words[70] = "Seventy";
                words[80] = "Eighty";
                words[90] = "Ninety";
                amount = amountToWord == null ? "0.00" : amountToWord.toString();
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
                        if (
                            (i == 1 && value != 0) ||
                            (i == 0 && value != 0 && n_array[i + 1] == 0)
                        ) {
                            words_string += "Crores ";
                        }
                        if (
                            (i == 3 && value != 0) ||
                            (i == 2 && value != 0 && n_array[i + 1] == 0)
                        ) {
                            words_string += "Lakhs ";
                        }
                        if (
                            (i == 5 && value != 0) ||
                            (i == 4 && value != 0 && n_array[i + 1] == 0)
                        ) {
                            words_string += "Thousand ";
                        }
                        if (
                            i == 6 &&
                            value != 0 &&
                            n_array[i + 1] != 0 &&
                            n_array[i + 2] != 0
                        ) {
                            words_string += "Hundred and ";
                        } else if (i == 6 && value != 0) {
                            words_string += "Hundred ";
                        }
                    }
                    words_string = words_string.split("  ").join(" ");
                }
                return words_string + " only";
            },
            async print() {
                let reportContent = `
					<div class="container">
						<div class="row">
							<div class="col-xs-12">
								${document.querySelector('#invoiceContent').innerHTML}
							</div>
						</div>
                        <div class="row" style="margin:100px 0 5px 0;padding-bottom:6px;">
                                <div class="col-xs-6">
                                    <span style="text-decoration:overline;">Preoared By</span><br><br>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <span style="text-decoration:overline;">Received By</span>
                                </div>
                            </div>
					</div>
				`;

                var reportWindow = window.open('', 'PRINT', `height=${screen.height}, width=${screen.width}`);
                reportWindow.document.write(`
					<?php $this->load->view('Administrator/reports/reportHeader.php'); ?>
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