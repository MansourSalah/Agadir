<template>
    <div class="container prodAdd">
       <h1>Nouveaux Produit</h1>
       <div class="row">
           <div class="col-sm-12">
                <div class="alert" v-bind:class="{ 'alert-success': flag, 'alert-danger': !flag }">
                    {{message}}
                </div>
           </div>
       </div>
       <form @submit.prevent="prodAdd">
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
                        <select class="form-control" name="categorie" v-on:change="select($event)">
                            <option hidden>Choisir une Catégorie</option>
                            <option v-for="categ  in categs" :key="categ.id" :value="categ.id">{{categ.nom}}</option>
                        </select>
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
                <div class="col-sm-6">                       
                    <div class="form-group">
                        <label>Image:</label>
                        <div class="input-group mb-3">
                            <input type="file" class="form-control" v-on:change="onFileChange" placeholder="Charger..." name="image" accept="image/*" required>
                        </div>
                    </div>
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
                    <button type="submit" class="btn btn-secondary"><div class="spinner-border spinner-border-sm"></div> Ajouter</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
export default {
        data(){
            return {
                categs:{},
                nom: '',
                categ_id:'',
                image:'',
                prix:'',
                description:'',
                flag:'',
                message:''    
            }
        },
        created(){
            axios.get('/api/admin/categorie/liste')
            .then((res) => {
                this.categs=res.data;
            });
        },
        methods: {
            prodAdd(){
                let formData = new FormData();
                formData.append('categ_id',this.categ_id);
                formData.append('prix',this.prix);
                formData.append('image',this.image);
                formData.append('nom',this.nom);
                formData.append('description',this.description);
                axios.post('/api/admin/produit/ajouter',formData)
                .then((res)=>{
                    this.flag=res.data.flag;
                    this.message=res.data.message;
                    $(".prodAdd .alert").css("display","block");
                    if(this.flag)
                        $("form input").val('');
                });
            },
            onFileChange(e){
                this.image = e.target.files[0];
            },
            select(event){
                this.categ_id=event.target.value;
            }
        }

}
</script>

