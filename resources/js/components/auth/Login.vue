<template>
  <section id="auth-connexion" style="padding: 20px 0;">
    <div class="col-sm-5" style="margin:auto;">
        <div class="card">
            <article class="card-body">
                <router-link to="/inscrire" class="float-right btn btn-outline-primary">S'inscrire</router-link>
                <h4 class="card-title mb-4 mt-1">Se connecter</h4>
                <div class="row">
                    <div class="col-sm-12">
                            <div class="alert" v-bind:class="{ 'alert-success': flag, 'alert-danger': !flag }">
                                {{message}}
                            </div>
                    </div>
                </div>
                <form @submit.prevent="login" >
                    <div class="form-group">
                        <label>Votre email</label>
                        <input name="email" class="form-control" v-model="email" placeholder="Email" type="email" required>
                    </div> <!-- form-group// -->
                    <div class="form-group">
                        <label>Votre mot de passe</label>
                        <input name="password" class="form-control" v-model="password" placeholder="******" type="password" required>
                    </div> <!-- form-group// --> 
                    <hr>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block"><span class="spinner-border spinner-border-sm"></span> S'identifier</button>
                    </div> <!-- form-group// --> 
                    <!--
                    <a class="float-right" href="/mot-passe-oublie" style="cursor:pointer;color:#4e73df">J'ai perdu mon mot de passe</a>                                                          
                    -->
                </form>       
            </article>
        </div> <!-- card.// -->
    </div>
</section>  
</template>
<script>
export default {
    data(){
        return {
            email:'',
            password:'',
            flag:'',
            message:''
        }
    },
    methods: {
        login(){
             let formData = new FormData();
            formData.append('email',this.email);
            formData.append('password',this.password);
            axios.post('/api/compte/login',formData)
            .then((res)=>{
                if(!res.data.flag){
                    this.flag=res.data.flag;
                    this.message=res.data.message;
                    $("#auth-connexion .alert").css("display","block");
                }else{
                    window.location.href = "/admin/categories";
                }              
            });
        }
    }
}
</script>

<style scoped>
.spinner-border{
    display: none;
}
</style>