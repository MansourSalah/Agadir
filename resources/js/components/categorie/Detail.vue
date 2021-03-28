<template>
    <div class="container categEdit">
       <h1>Detail Catégorie</h1>
        <div class="row">
           <div class="col-sm-12">
                <div class="alert" v-bind:class="{ 'alert-success': flag, 'alert-danger': !flag }">
                    {{message}}
                </div>
           </div>
       </div>
       <form @submit.prevent="categEdit">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="usr">Titre:</label>
                        <input type="text" class="form-control" name="nom" v-model="nom" required>
                        <input type="hidden" v-model="id" name="categ_id">
                    </div>
                </div>
                <div class="col-sm-4">                       
                    <div class="form-group">
                        <label>Image:</label>
                        <div class="input-group mb-3">
                            <input type="file" class="form-control" v-on:change="onFileChange" name="image" accept="image/*" >
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
            image:null,
            imageLink:'',
            description:'',
            flag:'',
            message:''
        }
    },
    created() {
        axios.get('/api/admin/categorie/first/'+this.$route.params.id)
        .then((res) => {
            this.id=res.data.id;
            this.nom=res.data.nom;
            this.imageLink=res.data.image;
            this.description=res.data.description;
        });
    },
    methods: {
        categEdit(){
            let formData = new FormData();
            if(this.image!=null)
                formData.append('image',this.image);
            formData.append('nom',this.nom);
            formData.append('description',this.description);
            formData.append('categ_id',this.id);
            axios.post('/api/admin/categorie/modifier',formData)
            .then((res)=>{
                this.flag=res.data.flag;
                this.message=res.data.message;
                $(".categEdit .alert").css("display","block");
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
