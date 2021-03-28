<template>
    <div class="container prodList">
       <h1>Produits</h1>

        <router-link class="btn btn-primary add" to="/admin/produits/ajout"><i class="icofont-ui-add"></i> Nouveau Produit</router-link>
        
        <table  id="myTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Prix</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <tr v-for="(prod,index) in produits" v-bind:key="index">
                <td>{{prod.nom}}</td>
                <td>{{prod.prix}}</td>
                <td><router-link class="btn btn-primary" :to="{name: 'prodDetail', params: { id: prod.id }}" ><i class="icofont-edit"></i></router-link>
                <button class="btn btn-danger" v-on:click="produitDelete(prod.id,index)"><i class="icofont-ui-delete"></i></button></td>
            </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
export default {
    data(){
        return {
            produits:{}
        }
    },
    created(){
        axios.get('/api/admin/produit/liste')
        .then(res => this.produits=res.data )
        .catch(err => console.log(err));

    },
    methods:{
        produitDelete(id,index){           
            axios.post('/api/admin/produit/supprimer',{prod_id:id})
            .then((res)=>{
                if(res.data.flag)
                this.produits.splice(index, 1);
            });
            
        }
    }     
}
</script>
<style scoped>
.prodList .add{
    margin-bottom: 10px;
}
</style>


