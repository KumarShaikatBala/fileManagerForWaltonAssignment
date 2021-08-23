<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Directory  Tree</div>

                    <div class="card-body">
                        <ul v-for="folder in folders" :key="folder.id">
                            <b class="text-info" v-if="folder.children">{{folder.folder}}</b>
<li v-for="child in folder.children" :key="child.id">{{child.folder}}</li>
                        </ul>
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
                editMode:false,
                folders:{},
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

        },
        mounted() {
            this.loadFolders();
        }
    }
</script>
