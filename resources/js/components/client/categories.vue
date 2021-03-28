<template>
    <div class="container categories">
        <h1>Nos Catégories</h1>
       <div v-for="(categ,index) in categs" v-bind:key="index"  class="card mb-3" style="max-width: 540px;margin: auto;padding: 10px;">
            <router-link :to="{name: 'produits', params: { categ_id: categ.id }}" style="color: black;text-decoration: auto;">
            <div class="row no-gutters">
                <div class="col-md-4">
                    <img :src="'/img/categorie/' +categ.image" class="card-img" alt="...">
                </div>
                <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title">{{categ.nom}}</h5>
                    <p class="card-text">{{categ.description}}</p>
                </div>
                </div>
            </div>
            </router-link>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return {
          categs:{}
        }
    },
    created() {
        axios.get('/api/admin/categorie/liste')
        .then(res => this.categs=res.data )
        .catch(err => console.log(err));
    },
    methods: {
        
    }
}
</script>
<style scoped>
.categories .Categimg{
    width: 100%;
    height: 100px;
    cursor:pointer;
}
</style>