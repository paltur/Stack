<template>
   <div>
       <span class="text-muted">Question {{ postDate }}</span>
       <div class="media">
           <a class="pr-2" :href=" user.url">
               <img :src=" user.avatar ">
           </a>
           <div class="media-body mt-1">
               <a class="pr-2" :href=" user.url">
                   {{ user.name }}
               </a>
           </div>
       </div>
   </div>
</template>

<script>
    export default {
        props: ['model', 'label'],
        computed:{
            postDate(){
                return this.label +" "+ this.model.created_date;
            }
        },
        data(){
            return{
                user: this.model.user
            }
        }
    }
</script>
