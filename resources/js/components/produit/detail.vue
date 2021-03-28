<template>
    <div class="container prodEdit">
       <h1>Detail Produit</h1>
        <div class="row">
           <div class="col-sm-12">
                <div class="alert" v-bind:class="{ 'alert-success': flag, 'alert-danger': !flag }">
                    {{message}}
                </div>
           </div>
       </div>
       <form @submit.prevent="produitEdit">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="usr">Titre:</label>
                        <input type="text" class="form-control" name="nom" v-model="nom" required>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Catégories:</label>
                        <input type="text" class="form-control" name="categorie" v-model="categorie" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="usr">Prix (dh):</label>
                        <input type="number" min="0" step="0.01" class="form-control" name="prix" v-model="prix" required>
                    </div>
                </div>
                <div class="col-sm-4">                       
                    <div class="form-group">
                        <label>Image:</label>
                        <div class="input-group mb-3">
                            <input type="file" class="form-control" v-on:change="onFileChange" placeholder="Charger..." name="image" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <a class="btn btn-secondary" style="width: 100%;margin-top: 22px;"  :href="imageLink" target="_blank">Voir Image</a>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="usr">Description:</label>
                        <textarea class="form-control" name="description" v-model="description" rows="4"></textarea>
                    </div>
                </div>
            </div><br>
            <div class="row">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-success"><div class="spinner-border spinner-border-sm"></div> Modifier</button>
                </div>
            </div>
        </form>
    </div>
</template>
<script>
export default {
    data(){
        return {
            id:'',
            nom: '',
            prix:'',
            categorie:'',
            image:null,
            imageLink:'',
            description:'',
            flag:'',
            message:''
        }
    },
    created() {
        axios.get('/api/admin/produit/first/'+this.$route.params.id)
        .then((res) => {
            this.id=res.data.produit.id;
            this.nom=res.data.produit.nom;
            this.prix=res.data.produit.prix;
            this.imageLink=res.data.produit.image;
            this.description=res.data.produit.description;
            this.categorie=res.data.categorie;
        });
    },
    methods: {
        produitEdit(){
            let formData = new FormData();
            if(this.image!=null)
                formData.append('image',this.image);
            formData.append('nom',this.nom);
            formData.append('prix',this.prix);
            formData.append('description',this.description);
            formData.append('prod_id',this.id);
            axios.post('/api/admin/produit/modifier',formData)
            .then((res)=>{
                this.flag=res.data.flag;
                this.message=res.data.message;
                $(".prodEdit .alert").css("display","block");
                if(this.image!=null){
                    this.imageLink=res.data.imageLink;
                    this.image=null;
                }
            });
        },
        onFileChange(e){
            this.image = e.target.files[0];
        }
    }
}
</script>
