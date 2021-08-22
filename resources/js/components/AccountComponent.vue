<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Accounts Table</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <button class="btn btn-success" @click="addModal()">Add New <i class="fa fa-user-plus"></i></button>
                            <!-- Modal -->
                            <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNew" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <form @submit.prevent="editMode? updateAccount() :createAccount()">
                                        <div class="modal-header">
                                            <h5 class="modal-title" v-show="!editMode">Add New</h5>
                                            <h5 class="modal-title" v-show="editMode">Edit Account</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Account Name</label>
                                                    <input v-model="form.account_name" type="text" name="account_name"
                                                           class="form-control" :class="{ 'is-invalid': form.errors.has('account_name') }">
                                                    <has-error :form="form" field="account_name"></has-error>
                                                </div>
                                            <div class="form-group">
                                                <label>Bank</label>
                                                <select  name="financial_organization_id" v-model="form.financial_organization_id"  class="form-control" :class="{ 'is-invalid': form.errors.has('financial_organization_id') }">
                                                    <option  v-for="item in banks" :key="item.id" :value="item.id">{{item.name}}</option>
                                                </select>
                                                <has-error :form="form" field="financial_organization_id"></has-error>
                                            </div>
                                                <div class="form-group">
                                                    <label>Account No</label>
                                                    <input v-model="form.account_no" type="text" name="account_no"
                                                           class="form-control" :class="{ 'is-invalid': form.errors.has('account_no') }">
                                                    <has-error :form="form" field="account_no"></has-error>
                                                </div>
                                                <div class="form-group">
                                                    <label>branch</label>
                                                    <textarea v-model="form.branch" type="text" name="branch" placeholder=" branch For User"
                                                           class="form-control" :class="{ 'is-invalid': form.errors.has('branch') }">
                                                    </textarea>
                                                    <has-error :form="form" field="branch"></has-error>
                                                </div>
                                            <div class="form-group">
                                                <label>Account Type</label>
                                                <select name="account_type" v-model="form.account_type" id="type" class="form-control" :class="{ 'is-invalid': form.errors.has('account_type') }">
                                                    <option value="">Select account_type</option>
                                                    <option value="1">Savings</option>
                                                    <option value="2">Current</option>
                                                    <option value="3">Join</option>
                                                </select>
                                                <has-error :form="form" field="account_type"></has-error>
                                            </div>

                                            <div class="form-group">
                                                    <label>swift_code</label>
                                                    <input v-model="form.swift_code" type="text" name="swift_code"
                                                           class="form-control" :class="{ 'is-invalid': form.errors.has('swift_code') }">
                                                    <has-error :form="form" field="swift_code"></has-error>
                                                </div>
                                            <div class="form-group">
                                                <label>route_no</label>
                                                <input v-model="form.route_no" type="text" name="route_no"
                                                       class="form-control" :class="{ 'is-invalid': form.errors.has('route_no') }">
                                                <has-error :form="form" field="route_no"></has-error>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                            <button v-show="!editMode" type="submit" class="btn btn-primary">Create</button>
                                            <button v-show="editMode" type="submit" class="btn btn-secondary">Update</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Account Name</th>
                            <th>Bank</th>
                            <th>Account No</th>
                            <th>Branch</th>
                            <th>Account Type</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="account in accounts" :key="account.id">
                            <td>{{account.id}}</td>
                            <td>{{account.account_name}}</td>
                            <td>{{account.bank.name}}</td>
                            <td>{{account.account_no}}</td>
                            <td>{{account.branch}}</td>
                            <td>{{account.account_type}}</td>
                            <td>
                                <a href="javascript:void(0)" @click="deleteAccount(account.id)"><i class="fa fa-trash"></i></a>
                                <a href="javascript:void(0)" @click="editAccount(account)"><i class="fa fa-edit"></i></a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                editMode:false,
                // Create a new form instance
                accounts:{},
                banks:{},
                form: new Form({
                    id: '',
                    financial_organization_id: '',
                    store_id: '',
                    account_name: '',
                    account_no: '',
                    branch: '',
                    account_type: '',
                    swift_code: '',
                    route_no: '',
                })
            }
        },
        methods:{
            addModal(){
                this.form.reset();
                $('#addNew').modal('show');
                this.editMode=false;
            },
            loadBanks(){
                axios.get('api/banks').then(({data})=>(this.banks=data)).catch();
            },
            loadAccounts(){
                axios.get('api/accounts').then(({data})=>(this.accounts=data.data));
            },

            createAccount(){
                this.$Progress.start();
                this.form.post('api/account')
                .then(()=>{
                    Fire.$emit('afterCreate');
                    $('#addNew').modal('hide');
                    Toast.fire({
                        icon: 'success',
                        title: 'successfully Added'
                    });

                })
                .catch(()=>{
                    this.$Progress.fail();
                })
                this.$Progress.finish();
            },
            editAccount(account){
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(account);
                this.editMode=true;
            },
            updateAccount(){
                this.$Progress.start();
                this.form.put('api/account/'+this.form.id)
                .then(()=>{
                    Fire.$emit('afterCreate');
                    $('#addNew').modal('hide');
                    Swal.fire(
                        'Updated!',
                        'Your Data has been Updated.',
                        'success'
                    );
                })
                .catch(()=>{
                    this.$Progress.fail();
                });
                this.$Progress.finish();
            },
            deleteAccount(id){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                    this.form.delete('api/account/'+id)
                        .then(()=>{
                            this.$Progress.start();
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        );
                        Fire.$emit('afterCreate');
                            this.$Progress.finish();
                    })
                        .catch(()=>{
                            this.$Progress.fail();
                        })
                    }
                })
            }

        },
        /*        created() {
                },*/
        mounted() {
            this.loadAccounts();
            this.loadBanks();
            Fire.$on('afterCreate',()=>{
                this.loadAccounts();
                this.loadBanks();
            });

        }
    }
</script>
