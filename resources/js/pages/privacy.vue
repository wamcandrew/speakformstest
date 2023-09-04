<template>
    <div class="card gutted_container" style="margin-top: 30px; padding-top: 20px;">
        <div v-html="pageData">
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import marked from 'marked'

export default {
    data() {
        return {
            pageData: ''
        }
    },
    metaInfo () {
        return { title: 'Privacy Policy' }
    },
    created() {
        axios
            .get('/api/pages/privacy')
            .then(res => {
                this.pageData = marked(res.data.body, {sanitize: true});
            });
    }
}
</script>

<style scope>
</style>