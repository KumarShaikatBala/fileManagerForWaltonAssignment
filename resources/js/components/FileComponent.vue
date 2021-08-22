<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">File Table</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <button class="btn btn-success"  @click="addModal()">Add New <i class="fa fa-user-plus"></i></button>
                            <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNew" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <form @submit.prevent="createFile()" enctype="multipart/form-data">
                                            <div class="modal-header bg-success text-white">
                                                <h5 class="modal-title" id="exampleModalLabel">Upload File TO Folder</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">

                                                <div class="form-group row">
                                                    <label  class="col-sm-3 col-form-label">Folder</label>
                                                    <div class="col-sm-9">
                                                        <select class="form-control" v-model="folder_id" name="folder_id">
                                                            <option value="" disabled selected>Select</option>
                                                            <option  v-for="item in folders" :key="item.id" :value="item.id">{{item.folder}}</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group row ">
                                                    <label for="file" class="col-sm-3 col-form-label">File</label>
                                                    <div class="col-sm-5">
                                                        <input type="file" name="file" class="form-control" id="file" v-on:change="onFileChange" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-success">Submit</button>
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
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="file in files" :key="file.id">
                            <td>{{file.id}}</td>
                            <td>{{file.folder.folder}}</td>
                            <td>


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
    name: "FileComponent",
    data () {
        return {
            editMode:false,
            folders:{},
            files:{},
            folder_id: '',
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


        loadFiles(){
            this.$Progress.start();
            axios.get('/api/file/index')
                .then(({data})=>(this.files=data.data))
                .catch(()=>{
                    this.$Progress.fail();
                })
            this.$Progress.finish();
        },
        addModal(){
            $('#addNew').modal('show');
            this.editMode=false;
        },
        onFileChange(e){
            this.file = e.target.files[0];
        },
        createFile() {
            this.$Progress.start();
            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }
            let data = new FormData();
            data.append('file', document.getElementById('file').files[0]);
            data.append('folder_id', this.folder_id);
            axios.post('/api/file/store', data, config)
                .then(() => {
                    Fire.$emit('afterCreate');
                    $('#exampleModal').modal('hide');
                    this.folder_id = "";
                    this.file = "";
                    this.confidential = "";
                    Toast.fire({
                        icon: 'success',
                        title: 'successfully Added'
                    });

                })
                .catch(error => {
                    this.$Progress.fail();
                    Swal.fire(
                        'Oops!',
                        error.response.data.message,
                        'error'
                    )
                })

            this.$Progress.finish();

        },
        edit(account){

            $('#addNew').modal('show');

            this.editMode=true;
        },
        update(){

        },
    },

    mounted() {
        this.loadFolders();
        this.loadFiles();
        Fire.$on('afterCreate',()=>{
            this.loadFiles();
        });

    }
}
</script>

<style scoped>

</style>
