<template>
    <div>
        <div class="row justify-content-md-center">
            <div class="card mx-2 my-2 col-md-6" style="min-height: 300px;">
                <div class="card-body">
                    <h5 class="card-title">{{ post.title }}</h5>
                    <p v-html="post.description" class="card-text">{{ post.description }}</p>
                </div>
            </div>
            <h5 class="mx-2 my-2 col-md-6">Comentarios</h5>
            <div class="card mx-2 my-2 col-md-6" v-for="comment of commentComputed" :key="comment.id">
                <div class="card-body">
                    <p class="card-title"><small>{{ comment.created_at }}</small></p>
                    <p v-html="comment.comment" class="card-text">{{ comment.comment }}</p>
                </div>
            </div>

            <div class="mx-2 my-2 col-md-6">
                <pagination :data="laravelData" @pagination-change-page="fetchComments"></pagination>
            </div>

            <div class="submit-form mx-2 my-2 col-md-6">
                <div class="form-group">
                    <label for="comment">Comentario</label>
                    <textarea class="form-control" id="comment" name="comment" rows="5" v-model="fields.comment"></textarea>
                    <div v-if="errors && errors.name" class="text-danger">{{ errors.name[0] }}</div>
                </div>

                <button @click="saveComment" class="btn btn-success">Publicar comentario</button>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        data() {
            return {
                post: {},
                comments: [],
                laravelData : {},
                fields: {
                    comment: ''
                },
                errors: {},
                
            }
        },

        props: ['id'],

        created(){
           this.fetchPost();
           this.fetchComments();
        },
        
        computed: {
            commentComputed: function(){
                if(Object.keys(this.comments).length != 0){
                    return this.comments.map(function(row) {
                        var date = new Date(row.created_at);

                        var dd = date.getDate();
                        var mm = date.getMonth()+1; 
                        var yyyy = date.getFullYear();

                        row.created_at = dd + '/' + mm + '/' + yyyy;
                        return row;
                    });
                }
            }
        },
        
        methods: {
            fetchPost() {
                axios.get('/post/' + this.$props.id)
                    .then(response => {
                        this.post = response.data;
                        console.log(this.post);
                    })
                    .catch(err => {
                        console.log(err);
                });
            },

            fetchComments(page) {

                if (typeof page === 'undefined') {
                    page = 1;
                }

                axios.get('/post/' + this.$props.id + '/comentarios?page=' + page)
                    .then(response => {
                        this.comments = response.data.data;
                        this.laravelData = response.data;
                        console.log("Comentarios: " + this.comments);
                    })
                    .catch(err => {
                        console.log(err);
                });
            },

            saveComment(){

                axios.post('/post/' + this.$props.id + '/comentarios/nuevo', this.fields)
                    .then(response => {
                        alert("OK");
                        this.fetchComments();
                    })
                    .catch(err => {
                         this.errors = error.response.data.errors || {};
                    })
                
            
            }
        }
    }
</script>

