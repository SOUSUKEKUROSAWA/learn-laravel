<template>
    <div>
        <button class="btn btn-primary ml-4" @click="followUser">Follow</button>
    </div>
</template>

<script>
import Axios from 'axios';

    export default {
        props: ["userId"],

        mounted() {
            console.log('Component mounted.')
        },

        methods: {
            followUser() {
                Axios.post("/follows/" + this.userId)
                    .then(response => {
                        alert(response.data);
                    });
            }
        }
    }
</script>
