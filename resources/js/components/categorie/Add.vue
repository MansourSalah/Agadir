<template>
    <div class="container categAdd">
       <h1>Nouvelle Catégerie</h1>

       <div class="row">
           <div class="col-sm-12">
                <div class="alert" v-bind:class="{ 'alert-success': flag, 'alert-danger': !flag }">
                    {{message}}
                </div>
           </div>
       </div>
       <form @submit.prevent="categAdd">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="usr">Titre:</label>
                        <input type="text" class="form-control" name="nom" v-model="nom" required>
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
              nom: '',
              image:'',
              description:'',
              flag:'',
              message:''
            }
        },
        methods: {
            categAdd(){
                let formData = new FormData();
                formData.append('image',this.image);
                formData.append('nom',this.nom);
                formData.append('description',this.description);
                axios.post('/api/admin/categorie/ajouter',formData)
                .then((res)=>{
                    this.flag=res.data.flag;
                    this.message=res.data.message;
                    $(".categAdd .alert").css("display","block");
                    if(this.flag)
                        $("form input").val('');
                });
            },
            onFileChange(e){
                this.image = e.target.files[0];
            },
        }

}
</script>

