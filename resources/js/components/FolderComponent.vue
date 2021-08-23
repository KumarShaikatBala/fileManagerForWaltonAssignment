<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Folder Table</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <button class="btn btn-success" @click="addModal()">Add New <i class="fa fa-user-plus"></i></button>
                            <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNew" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <form @submit.prevent="editMode? update() :create()">
                                            <div class="modal-header">
                                                <h5 class="modal-title" v-show="!editMode">Add New</h5>
                                                <h5 class="modal-title" v-show="editMode">Edit Account</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Folder Name</label>
                                                    <input v-model="form.folder" type="text" name="folder"
                                                           class="form-control" :class="{ 'is-invalid': form.errors.has('folder') }">
                                                    <has-error :form="form" field="folder"></has-error>
                                                </div>
                                                <div class="form-group row">
                                                    <label  class="col-sm-3 col-form-label">Parent Folder</label>
                                                    <div class="col-sm-9">
                                                        <select class="form-control" v-model="form.parent_id" name="parent_id">
                                                            <option value="" disabled selected>Select</option>
                                                            <option  v-for="item in folders" :key="item.id" :value="item.id">{{item.folder}}</option>
                                                        </select>
                                                    </div>
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
                            <th>Folder Name</th>
                            <th>Parent Folder Name</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="folder in folders" :key="folder.id">
                            <td>{{folder.id}}</td>
                            <td>{{folder.folder}}</td>
                            <td><span v-if="folder.parent">{{folder.parent.folder}}</span></td>
                            <td>
                                <a href="javascript:void(0)" @click="deleteFolder(folder.id)"><i class="fa fa-trash"></i></a>
                                <a href="javascript:void(0)" @click="edit(folder)"><i class="fa fa-edit"></i></a>
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
    name: "FolderComponent",
    data () {
        return {
            editMode:false,
            folders:{},
            form: new Form({
                id: '',
                folder: '',
                parent_id: '',
            })

        }
    },
    methods:{
        loadFolders(){
            this.$Progress.start();
            axios.get('/api/folder/index')
            .then(({data})=>(this.folders=data.data))
        .catch(()=>{
                this.$Progress.fail();
            })
            this.$Progress.finish();
        },
        addModal(){
            this.form.reset();
            $('#addNew').modal('show');
            this.editMode=false;
        },
        create(){
            this.$Progress.start();
            this.form.post('api/folder/store')
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
        edit(folder){
            this.form.reset();
            $('#addNew').modal('show');
            this.form.fill(folder);
            this.editMode=true;
        },
        update(){
            this.$Progress.start();
            this.form.put('/api/folder/'+this.form.id)
                .then(()=>{
                    Fire.$emit('afterCreate');
                    $('#addNew').modal('hide');
                    Swal.fire(
                        'Updated!',
                        'Your Folder has been Updated.',
                        'success'
                    );
                })
                .catch(()=>{
                    this.$Progress.fail();
                });
            this.$Progress.finish();
        },
        deleteFolder(id){
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
                    this.form.delete('/api/folder/'+id)
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

    mounted() {
        this.loadFolders();
        Fire.$on('afterCreate',()=>{
            this.loadFolders();
        });

    }
}
</script>

<style scoped>

</style>
