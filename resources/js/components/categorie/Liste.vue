<template>
    <div class="container categList">
       <h1>Catégories</h1>

        <router-link class="btn btn-primary add" to="/admin/categories/ajout"><i class="icofont-ui-add"></i> Nouvelle Catégorie</router-link>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Catégorie</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <tr v-for="(categ,index) in categs" v-bind:key="index">
                <td>{{categ.nom}}</td>
                <td>{{categ.description}}</td>
                <td><button class="btn btn-danger" v-on:click="CategDelete(categ.id,index)"><i class="icofont-ui-delete"></i></button> 
                <router-link class="btn btn-primary" :to="{name: 'categDetail', params: { id: categ.id }}" ><i class="icofont-edit"></i></router-link></td>
            </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
export default {
    data(){
        return {
            categs:{}
        }
    },
    created(){
        axios.get('/api/admin/categorie/liste')
        .then(res => this.categs=res.data )
        .catch(err => console.log(err));
    },
    methods:{
        CategDelete(id,index){           
            axios.post('/api/admin/categorie/supprimer',{categ_id:id})
            .then((res)=>{
                if(res.data.flag)
                this.categs.splice(index, 1);
            });
            
        }
    }     
}
</script>

<style scoped>
.categList .add{
    margin-bottom: 10px;
}
</style>


