<template>
    <div>
        <div class="row justify-content-md-center">
            <div class="card mx-2 my-2 col-md-6" v-for="post of postsComputed" :key="post.id">
                <div class="card-body">
                    <h5 class="card-title">{{ post.title }}</h5>
                    <p v-html="post.description" class="card-text">{{ post.strippedDescription }}</p>
                    <a :href="'/post/' + post.id + '/ver'">Leer más</a>
                </div>
            </div>

            <div class="mx-2 my-2 col-md-6">
                <pagination :data="laravelData" @pagination-change-page="fetchPosts"></pagination>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                posts: [],
                laravelData : {},
            }
        },

        created(){
           this.fetchPosts();
        },

        computed: {
            postsComputed: function(){
                return this.posts.map(function(row) {
                    if(row.description.length > 100){
                        row.description =  row.description.substring(0,100) + "...";
                    }

                    return row;
                });
            }
        },

        methods: {
            fetchPosts(page) {

                if (typeof page === 'undefined') {
                    page = 1;
                }

                axios.get('/posts?page=' + page)
                    .then(response => {
                        this.posts = response.data.data;
                        this.laravelData = response.data;
                        console.log(this.posts);
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },

        }
    }
</script>

