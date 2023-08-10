const salesInvoice = Vue.component("sales-invoice", {
  template: `
        <div>
            <div class="row">
                <div class="col-xs-12">
                    <a href="" v-on:click.prevent="print"><i class="fa fa-print"></i> Print</a>
                </div>
            </div>

            <div id="invoiceContent">
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <div _h098asdh>
                            Monthly Rent & Bill Statement
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-7">
                        <strong>Month: {{ sales.month_name }} </strong><br>
                        <strong class="c-name">Clients Name: {{ sales.shop_name }} </strong><br>
                        <span class="address">{{ sales.Customer_Address }}</span>
                    </div>
                    <div class="col-xs-5 text-right">
                        <strong>Date: {{ sales.generate_date }} {{ sales.created_at | formatDateTime('h:mm a') }} </strong>
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
                                    <td colspan="3" class="table-head">Statement No:</td>
                                    <td class="table-head">Amount</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td class="text-start">Rent</td>
                                    <td>{{ sales.month_name }}</td>
                                    <td class="text-end">{{ sales.shop_rent }}</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td class="text-start">AIT Adjusted</td>
                                    <td>{{ sales.month_name }}</td>
                                    <td class="text-end">{{ sales.ait_adjustment }}</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td class="text-start">Advance Adjusted</td>
                                    <td>{{ sales.month_name }}</td>
                                    <td class="text-end">{{ sales.adjustment_amount }}</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td class="text-start" colspan="2">Payable Rent Amount:</td>
                                    <td class="text-end">{{ payableRent }} </td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td class="text-start">Service Charge</td>
                                    <td>{{ sales.service_month }}</td>
                                    <td class="text-end">{{ sales.service_charge }}</td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td class="text-start">Electricity Bill</td>
                                    <td>{{ sales.service_month }}</td>
                                    <td class="text-end">{{ sales.shop_electricity_bill }}</td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td class="text-start">Gass Bill</td>
                                    <td>{{ sales.service_month }}</td>
                                    <td class="text-end">{{ sales.shop_gass_bill }}</td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td class="text-start">Water Bill</td>
                                    <td>{{ sales.service_month }}</td>
                                    <td class="text-end">{{ sales.shop_water_bill }}</td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td class="text-start">AC Bill</td>
                                    <td>{{ sales.service_month }}</td>
                                    <td class="text-end">{{ sales.ac_bill }}</td>
                                </tr>
                                <tr>
                                    <td>7</td>
                                    <td class="text-start">Other</td>
                                    <td></td>
                                    <td class="text-end">{{ sales.other_charge }}</td>
                                </tr>
                                <tr>
                                    <td class="text-start" colspan="3">Amount Payable for the Month:</td>
                                    <td class="text-end">{{ totalPayable }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="table-head">Outstanding Bills:</td>
                                </tr>
                                <tr>
                                    <td>001</td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-end">-</td>
                                </tr>
                                <tr>
                                    <td>002</td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-end">-</td>
                                </tr>
                                <tr>
                                    <td class="text-start" colspan="3">Total Payable Amount:</td>
                                    <td class="text-end">{{ totalPayable }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4"></td>
                                </tr>


                                <tr>
                                    <td class="text-start">Pervious Due:</td>
                                    <td class="text-start">{{ sales.previous_due }}</td>
                                    <td class="text-start">Current Due:</td>
                                    <td class="text-end">{{ totalPayable }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3">Total Due:</td>
                                    <td class="text-end"> {{ parseFloat(+ sales.previous_due + + totalPayable).toFixed(2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-start" colspan="3">Late fee after 10 {{moment().format('-MM-YYYY')}} @ 5%</td>
                                    <td class="text-end">{{ lateFee }}</td>
                                </tr>
                                <tr>
                                    <td class="text-start" colspan="3">Total Due Amount Including Late Fee:</td>
                                    <td class="text-end">{{ payWithLateFee }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <strong>In Word: {{ convertNumberToWords(totalPayable) }}</strong><br><br>
                    </div>
                </div>
            </div>
        </div>
    `,
  props: ["sales_id"],
  data() {
    return {
      sales: {
        invoice_no: null,
        renter_id: null,
        generate_date: null,
        Customer_SlNo: null,
        Customer_Name: null,
        Customer_Address: null,
        Customer_Mobile: null,
        shop_rent: null,
        shop_electricity_bill: null,
        shop_gass_bill: null,
        shop_water_bill: null,
        service_charge: null,
        other_charge: null,
        total_amount: null,
        paid_amount: null,
        ac_bill: null,
        due_amount: null,
        total_amount: null,
        previous_due: null,
        comment: null,
        created_by: null,
      },
      cart: [],
      style: null,
      companyProfile: null,
      currentBranch: null,
      customer_previous_due: 0,
    };
  },
  computed: {
    payableRent: function () {
      return parseFloat(
        this.sales.shop_rent -
          this.sales.ait_adjustment -
          this.sales.adjustment_amount
      ).toFixed(2);
    },
    payableService: function () {
      return parseFloat(
        +this.sales.service_charge +
          +this.sales.shop_electricity_bill +
          +this.sales.shop_water_bill +
          +this.sales.shop_gass_bill +
          +this.sales.other_charge +
          +this.sales.ac_bill
      ).toFixed(2);
    },
    totalPayable: function () {
      return parseFloat(+this.payableRent + +this.payableService).toFixed(2);
    },
    lateFee: function () {
      return ((parseFloat(this.totalPayable) * 5) / 100).toFixed(2);
    },
    payWithLateFee: function () {
      return parseFloat(
        +this.sales.previous_due + +this.totalPayable + +this.lateFee
      ).toFixed(2);
      // return parseFloat(+this.totalPayable + +this.lateFee).toFixed(2);
    },
  },
  filters: {
    formatDateTime(dt, format) {
      return dt == "" || dt == null ? "" : moment(dt).format(format);
    },
  },
  watch: {
    sales(sales) {
      if (sales == undefined) return;
      axios
        .post("/get_customer_due", { customerId: sales.Customer_SlNo })
        .then((res) => {
          this.customer_previous_due = res.data[0].dueAmount;
        });
    },
  },
  created() {
    this.setStyle();
    this.getSales();
    this.getCurrentBranch();
  },
  methods: {
    getSales() {
      axios.post("/get_rents", { salesId: this.sales_id }).then((res) => {
        this.sales = res.data.rents[0];
        // console.log(this.sales);
      });
    },

    getCurrentBranch() {
      axios.get("/get_current_branch").then((res) => {
        this.currentBranch = res.data;
      });
    },
    setStyle() {
      this.style = document.createElement("style");
      this.style.innerHTML = `
      * {
      font-family: arial;
      font-size:12px;
      }
                div[_h098asdh]{
                    /*background-color:#e0e0e0;*/
                    font-weight: bold;
                    font-size:15px;
                    margin-bottom:10px;
                    padding: 5px;
                    border-top: 1px dotted #454545;
                    border-bottom: 1px dotted #454545;
                    font-family: arial;
                }
                div[_d9283dsc]{
                    margin-bottom: 10px;
                }
                strong {
                    letter-spacing: .5px;
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
                    border: 1px solid #000;
                    // font-weight: bold;
                }
                table[_t92sadbc2]{
                    width: 100%;
                }
                table[_t92sadbc2] td{
                    padding: 2px;
                }
                .c-name {
                    font-size: 14px;
                    font-family: arial;
                }
                .address {
                    font-size: 14px;
                    font-weight: 500;
                }
                .text-start {
                    text-align: left;
                }
                .text-end {
                    text-align: right;
                }
                .table-head {
                    font-size: 16px;
                    font-family: arial;
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
      let invoiceContent = document.querySelector("#invoiceContent").innerHTML;
      let printWindow = window.open(
        "",
        "PRINT",
        `width=${screen.width}, height=${screen.height}, left=0, top=0`
      );
      if (this.currentBranch.print_type == "3") {
        printWindow.document.write(`
                    <html>
                        <head>
                            <title>Invoice</title>
                            <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
                            <style>
                                body, table{
                                    font-size:11px;
                                }
                            </style>
                        </head>
                        <body>
                            <div style="text-align:center;">
                                <strong style="font-size:18px;">${this.currentBranch.Company_Name}</strong><br>
                                <p style="white-space:pre-line;">${this.currentBranch.Repot_Heading}</p>
                            </div>
                            ${invoiceContent}
                        </body>
                    </html>
                `);
      } else if (this.currentBranch.print_type == "2") {
        printWindow.document.write(`
                    <!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <meta http-equiv="X-UA-Compatible" content="ie=edge">
                        <title>Invoice</title>
                        <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
                        <style>
                            html, body{
                                width:500px!important;
                            }
                            body, table{
                                font-size: 12px;
                            }

                        </style>
                    </head>
                    <body>
                        <div class="row">
                            <div class="col-xs-12" style="padding-top:0px; text-align:center;">
                                <strong style="font-size:24px; padding-bottom:5px;">${this.currentBranch.Company_Name}</strong><br>
                                <p style="white-space:pre-line;font-size:17px; font-weight: 600;">${this.currentBranch.Repot_Heading}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                ${invoiceContent}
                            </div>
                        </div>
                        <div class="row" style="margin-top:30px;">
                          <div class="col-xs-4">
                              <span style="text-decoration:overline;">Prepared By</span><br><br>
                          </div>
                          <div class="col-xs-4" style="text-align:center">
                              <span style="text-decoration:overline;">Accounts</span><br><br>
                          </div>
                          <div class="col-xs-4 text-right">
                              <span style="text-decoration:overline;">Authorize By</span>
                          </div>
                      </div>
                    </body>
                    </html>
				`);
      } else {
        printWindow.document.write(`
                    <!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <meta http-equiv="X-UA-Compatible" content="ie=edge">
                        <title>Invoice</title>
                        <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
                        <style>
                            body, table{
                                font-size: 13px;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <table style="width:100%;">
                                <thead>
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col-xs-12" style="padding-top:20px;text-align:center;">
                                                    <strong style="font-size:24px;padding-bottom:15px;">${
                                                      this.currentBranch
                                                        .Company_Name
                                                    }</strong><br>
                                                    <p style="white-space:pre-line; font-size:17px; font-weight: 600;">${
                                                      this.currentBranch
                                                        .Repot_Heading
                                                    }</p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    ${invoiceContent}
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>
                                            <div style="width:100%;height:50px;">&nbsp;</div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                            <div class="row" style="border-bottom:1px solid #ccc;margin-bottom:5px;padding-bottom:6px;">
                                <div class="col-xs-6">
                                    <span style="text-decoration:overline;">Prepared By</span><br><br>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <span style="text-decoration:overline;">Received By</span>
                                </div>
                            </div>
                            <div style="position:fixed;left:0;bottom:15px;width:100%;">
                                <div class="row" style="font-size:12px;">
                                    <div class="col-xs-6">
                                        Print Date: ${moment().format(
                                          "DD-MM-YYYY h:mm a"
                                        )}, Printed by: ${this.sales.created_by}
                                    </div>
                                    <div class="col-xs-6 text-right">
                                        Developed by: Link-Up Technologoy, Contact no: 01911978897
                                    </div>
                                </div>
                            </div>
                        </div>

                    </body>
                    </html>
				`);
      }
      let invoiceStyle = printWindow.document.createElement("style");
      invoiceStyle.innerHTML = this.style.innerHTML;
      printWindow.document.head.appendChild(invoiceStyle);
      printWindow.moveTo(0, 0);

      printWindow.focus();
      await new Promise((resolve) => setTimeout(resolve, 1000));
      printWindow.print();
      printWindow.close();
    },
  },
});
