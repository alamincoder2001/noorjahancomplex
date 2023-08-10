<style>
    table {
        margin-bottom: 0px !important;
    }
    .filter-box {
        margin-bottom: 7px;
        float: right;
    }
    .rent-head {
        margin-bottom: 3px;
        display: flex;
        justify-content: space-between;
        background-color: #DDDDDD;
    }
</style>
<div class="rent-list" id="root" style="margin-top: 15px;">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="rent-head">
                <h4 class="text-left">Rent Generate List</h4>
                <input style="margin: 4px;" type="text" class="filter-box" v-model="filter" placeholder="Search..">
            </div>
            <table class="table table-bordered">
                <tbody>
                    <datatable :columns="columns" :data="rentList" :filter-by="filter">
                        <template scope="{ row }">
                            <tr>
                                <td>{{ row.ind }}</td>
                                <td>{{ row.generate_date }}</td>
                                <td>{{ row.month_name }}</td>
                                <td>{{ row.total }}</td>
                                <td><a target="_blank" :href="`/get_each_month_rent_list/${row.month_id}`" ><i class="fa fa-print"></i></a></td>
                            </tr>
                        </template>
                    </datatable>
                    <datatable-pager v-model="page" type="abbreviated" :per-page="per_page"></datatable-pager>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- all js -->
<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/vuejs-datatable.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>
<script>
    var app = new Vue({
        el: "#root",
        data: {
            rentList: [],
            columns: [
                {label: 'SL', field: 'ind', align: 'center', filterable: false},
                {label: 'Generate Date', field: 'generate_date', align: 'center'},
                {label: 'Month', field: 'month_name', align: 'center'},
                {label: 'Total Amount', field: 'total', align: 'center'},
                {label: 'Action', align: 'center', filterable: false},
            ],
            page: 1,
            per_page: 15,
            filter: ''

        },
        created(){
            this.getRentList();
        },
        methods: {
            getRentList() {
                axios.post('get_rent_generate_list')
                .then(res => {
                    this.rentList = res.data.map((item, ind ) => {
                        item.ind = ++ind;
                        return item;
                    });
                });
            },
            
        }
    });
</script>