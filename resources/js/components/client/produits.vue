<template>
    <div class="container produits">
        <h1>Nos Produits</h1>        
        <div v-for="(prod,index) in produits" v-bind:key="index"  class="card mb-3" style="max-width: 540px;margin: auto;padding: 10px;">
            <div class="row no-gutters">
                <div class="col-md-4">
                    <img :src="'/img/produit/' +prod.image" class="card-img" alt="...">
                </div>
                <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title">{{prod.nom}}</h5>
                    <p class="card-text">{{prod.description}}</p>
                    <p class="card-text"><small class="text-muted">{{prod.prix}} dh</small></p>
                </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return {
          produits:{}
        }
    },
    created() {

        axios.get('/api/admin/produit/where/'+this.$route.params.categ_id)
        .then((res) => {
            this.produits=res.data 
            console.log(this.produits);
        })
        .catch(err => console.log(err));
    },
    methods: {
        
    }
}
</script>
<style scoped>
.produits .Prodimg{
    width: 100%;
    height: 100px;
    cursor:pointer;
}
</style>