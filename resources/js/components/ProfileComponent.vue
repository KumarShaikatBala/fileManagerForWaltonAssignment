<template>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Settings</a></li>
                    </ul>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="tab-content">
                        <div class="active tab-pane" id="settings">
                            <form class="form-horizontal">
                                <div class="form-group row">
                                    <label  class="col-sm-2 col-form-label">Name</label>
                                    <div class="col-sm-10">
                                        <input v-model="form.name" type="text" name="name"
                                               class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                        <has-error :form="form" field="name"></has-error>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-10">
                                        <input v-model="form.email" type="email" name="email"
                                               class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                        <has-error :form="form" field="email"></has-error>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label  class="col-sm-2 col-form-label">Bio</label>
                                    <div class="col-sm-10">
                                        <textarea v-model="form.bio"  name="bio"
                                                  class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }">
                                                        <has-error :form="form" field="bio"></has-error></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label  class="col-sm-2 col-form-label">Type</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" :class="{ 'is-invalid': form.errors.has('type') }"  v-model="form.type" name="type">
                                            <option value="Admin">Admin</option>
                                            <option value="User">User</option>
                                        </select>
                                        <has-error :form="form" field="type"></has-error>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Photo</label>
                                    <div class="col-sm-10">
                                        <input type="file" @change="updateProfile"  name="photo" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label  class="col-sm-2 col-form-label">Password</label>
                                    <div class="col-sm-10">
                                        <input v-model="form.password" type="password" name="password"
                                               class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                                        <has-error :form="form" field="password"></has-error>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="offset-sm-2 col-sm-10">
                                        <button type="submit" @click.prevent="uploadInfo"  class="btn btn-danger">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                form: new Form({
                    id: '',
                    name: '',
                    email: '',
                    bio: '',
                    type: '',
                    photo: '',
                    password: ''
                })
            }
        },


        methods:{
            getProfilePhoto(){
return "img/"+this.form.photo
            },
            uploadInfo(){
                this.$Progress.start();
                this.form.put('api/profile')
                    .then(()=>{
                        Fire.$emit('afterCreate');
                        this.$Progress.finish();
                    })
                    .catch(()=>{
                        this.$Progress.fail();
                    });

            },

            updateProfile(e){
                let file = e.target.files[0];
                let reader = new FileReader();
                let limit = 1024 * 1024 * 2;
                if(file['size'] > limit){
                    swal({
                        type: 'error',
                        title: 'Oops...',
                        text: 'You are uploading a large file',
                    })
                    return false;
                }

                reader.onloadend = (file) => {
                    this.form.photo = reader.result;
                };
                reader.readAsDataURL(file);
            },

            loadUsers(){
                axios.get('api/profile').then(({data})=>(this.form.fill(data)));
            }

        },


        /*        mounted() {
                },*/
        created() {
            axios.get('api/profile').then(({data})=>(this.form.fill(data)));
            Fire.$on('afterCreate',()=>{
                this.loadUsers();
            });
        }





    }
</script>
