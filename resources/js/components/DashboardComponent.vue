<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Directory  Tree</div>

                    <div class="card-body">
                        <ul v-for="folder in folders.data" :key="folder.id">
                            <b class="text-info" v-if="folder.children">{{folder.folder}}</b>
<li v-for="child in folder.children" :key="child.id">{{child.folder}}</li>
                        </ul>
                    </div>
                    <div class="card-footer">
                        <pagination :data="folders" @pagination-change-page="getResults"></pagination>
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
                    .then(({data})=>(this.folders=data.folders))
                    .catch(()=>{
                        this.$Progress.fail();
                    })
                this.$Progress.finish();
            },
            getResults(page = 1) {
                axios.get('/api/folder/index?page=' + page)
                    .then(({data})=>(this.folders=data.folders))
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
