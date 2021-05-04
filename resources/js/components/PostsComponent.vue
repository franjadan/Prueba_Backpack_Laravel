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
        </div>

        <div class="d-flex flex-row justify-content-center list-group">
            <button v-for="page in pagination.last_page" :key="page" @click="doPagination(page)" class="btn btn-white my-2 mx-2 list-group-item">{{ page }}</button>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                posts: [],
                pagination: {},
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
            fetchPosts() {
                    axios.get('/posts')
                        .then(response => {
                            this.posts = response.data.data;
                            this.makePagination({ ...response.data.meta, ...response.data.links });
                            console.log(this.posts);
                        })
                        .catch(err => {
                            console.log(err);
                        });
            },

            makePagination(data) {
                this.pagination = data;
            },

            doPagination(page) {
                this.fetchPosts(`${this.endpoint}?page=${page}`)
            }
        }
    }
</script>

