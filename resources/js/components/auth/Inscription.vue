<template>
    <section id="auth-inscription" style="padding: 20px 0;">
    <div class="col-sm-5" style="margin:auto;">
        <div class="card">
            <article class="card-body">
                <div class="row">
                    <h4 class="card-title mb-4 mt-1" style="margin: auto;text-align: center;">Inscription</h4>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                            <div class="alert" v-bind:class="{ 'alert-success': flag, 'alert-danger': !flag }">
                                {{message}}
                            </div>
                    </div>
                </div>   
                <form @submit.prevent="inscription">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Nom :</label>
                                <input name="nom" class="form-control" v-model="nom"  type="text" required>
                            </div>
                        </div>
                        <div class="col-sm-6">  
                            <div class="form-group">
                                <label>Prénom :</label>
                                <input name="prenom" class="form-control" v-model="prenom"  type="text" required>
                            </div>         
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Télephone :</label>
                                <input name="phone" type="text" class="form-control" v-model="phone" placeholder="06x...x" pattern="0(5|6|7)[0-9]{8}" >
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Adresse e-mail :</label>
                                <input name="email" class="form-control" v-model="email"  type="email" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Mot de passe :</label>
                                <input name="pass1" class="form-control" v-model="pass1" type="password" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Vérifier le mot de passe :</label>
                                <input name="pass2" class="form-control" v-model="pass2" type="password" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12" style="text-align:center;">
                            <button type="submit" class="btn btn-primary btn-block" style="margin-top: 15px;"> Je m'inscris !</button>
                        </div>
                    </div>
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
            nom:'',
            prenom:'',
            email:'',
            phone:'',
            pass1:'',
            pass2:'',
            flag:'',
            message:''
        }
    },
    methods: {
        inscription(){
            let formData = new FormData();
            formData.append('nom',this.nom);
            formData.append('prenom',this.prenom);
            formData.append('email',this.email);
            formData.append('phone',this.phone);
            formData.append('pass1',this.pass1);
            formData.append('pass2',this.pass2);
            axios.post('/api/compte/add',formData)
            .then((res)=>{
                if(!res.data.flag){
                    this.flag=res.data.flag;
                    this.message=res.data.message;
                    $("#auth-inscription .alert").css("display","block");
                }else{
                    window.location.href = "/login";
                }              
            });
        }
    }
}
</script>