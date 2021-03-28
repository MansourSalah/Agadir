import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter);

import CategListe from './components/categorie/Liste.vue';
import CategAdd from './components/categorie/Add.vue';
import CategDetail from './components/categorie/Detail.vue';

import ProdListe from './components/produit/Liste.vue';
import ProdAdd from './components/produit/Add.vue';
import ProdDetail from './components/produit/Detail.vue';

import Produits from './components/client/Produits.vue';
import DetailProduit from './components/client/DetailProd.vue';
import Categories from './components/client/Categories.vue';

import Login from './components/auth/Login.vue';
import Inscription from './components/auth/Inscription.vue';

const routes= [
        {path: '/admin/categories', component: CategListe, name:'categListe'},
        {path: '/admin/categories/ajout', component:CategAdd, name:"categAdd"},
        {path: '/admin/categorie/detail/:id', component:CategDetail, name:"categDetail"},

        {path: '/admin/produits', component: ProdListe, name:'prodListe'},
        {path: '/admin/produits/ajout', component:ProdAdd, name:"prodAdd"},
        {path: '/admin/produit/detail/:id', component:ProdDetail, name:"prodDetail"},

        {path: '/', component: Categories, name:'categories'},
        {path: '/produits/:categ_id', component:Produits, name:"produits"},
        {path: '/produit/detail/:id', component:DetailProduit, name:"detailProduit"},

        {path: '/login', component: Login, name:'login'},
        {path: '/inscrire', component:Inscription, name:"inscription"}
    
    ];

const router = new VueRouter({
    routes,hashbang:false,mode:'history'
});
export default router;