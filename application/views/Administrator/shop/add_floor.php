<style>
    .pagination {
		margin: 5px 0;
	}
</style>
<div id="Floor">
    <div class="row" >
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <form @submit.prevent="saveFloor">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Floor Name  </label>
                        <label class="col-sm-1 control-label no-padding-right">:</label>
                        <div class="col-sm-8">
                            <input type="text" v-model="floor.floor_name"  placeholder="Floor Name" class="col-xs-10 col-sm-4" />
                            <span id="msg"></span>
                        </div>
                    </div>
                    <div class="form-group" style="margin-top: 5px;">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
                        <label class="col-sm-1 control-label no-padding-right"></label>
                        <div class="col-sm-8">
                            <button type="submit" class="btn btn-sm btn-success" name="btnSubmit">
                                Submit
                                <i class="ace-icon fa fa-arrow-right icon-on-right bigger-110"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row" style="margin-top: 30px;">
		<div class="col-sm-8 col-sm-offset-2 form-inline">
			<div class="form-group">
				<label for="filter" class="sr-only">Filter</label>
				<input type="text" class="form-control" v-model="filter" placeholder="Filter">
			</div>
		</div>
		<div class="col-md-8 col-sm-offset-2">
			<div class="table-responsive">
				<datatable :columns="columns" :data="floors" :filter-by="filter" style="margin-bottom: 5px;">
					<template scope="{ row }">
						<tr>
							<td>{{ row.id }}</td>
							<td>{{ row.floor_name }}</td>
							<td>
								<?php if($this->session->userdata('accountType') != 'u'){?>
								<button type="button" class="button edit" @click="editFloor(row)">
									<i class="fa fa-pencil"></i>
								</button>
								<button type="button" class="button" @click="deleteFloor(row.id)">
									<i class="fa fa-trash"></i>
								</button>
								<?php }?>
							</td>
						</tr>
					</template>
				</datatable>
				<datatable-pager v-model="page" type="abbreviated" :per-page="per_page" style="margin-bottom: 50px;"></datatable-pager>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/vuejs-datatable.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>

<script>
    var app = new Vue({
        el: '#Floor',
        data: {
            floor: {
                id: 0,
                floor_name: '',
            },
            floors: [],
            columns: [
                    { label: 'SL NO', field: 'shop_name', align: 'center', filterable: false },
                    { label: 'Floor Name', field: 'floor_name', align: 'center', filterable: false },
                    { label: 'Action', align: 'center', filterable: false }
                ],
            page: 1,
            per_page: 10,
            filter: ''
        },
        created() {
            this.getFloor();
        },
        methods: {
            getFloor() {
				axios.post('/get_floor').then(res => {
					this.floors = res.data;
				})
			},
            saveFloor() {
                if(this.floor.floor_name == '') {
                    alert('Floor Name Required!');
                    return;
                }
                let url = '/save_floor';
                if(this.floor.id != 0) {
                    url = '/update_floor';
                }
                axios.post(url, this.floor).then(res => {
                    let r = res.data;
                    alert(r.message);
                    if(r.success) {
                        this.resetForm();
                        this.getFloor();
                    }
                })
            },
            editFloor(floor) {
                let keys = Object.keys(this.floor);
                keys.forEach(key => {
                    this.floor[key] = floor[key];
                })
            },
            deleteFloor(floorId)
            {
                let deleteConfirm = confirm('are you sure?');
                if(deleteConfirm == false) {
                    return;
                }
                axios.post('/delete_floor', {floorId: floorId}).then (res => {
                    let r = res.data;
                    alert(r.message);
                    if(r.success) {
                        this.getFloor();
                    }
                })
            },
            resetForm() {
                this.floor = {
                    id: 0,
                    floor_name: '',
                }
            }
        }
    })
</script>