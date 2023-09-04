<template>
    <div style="margin-top: 40px;">
    <footer>
        <h6>Copyright &copy; {{ new Date().getFullYear() }}, {{ appName }}</h6>
        <nav>
            <router-link :to="{name: 'tos'}">Terms of Services</router-link>
            <router-link :to="{name: 'privacy-policy'}">Privacy Policy</router-link>
        </nav>
    </footer>
    </div>
</template>

<script>
export default {
    data: () => ({
        appName: window.config.appName,        
        logo: '/img/logo.png',
    }),
}
</script>

<style scope>
footer {
    text-align: center;
    margin-bottom: 20px;
}
footer nav a {
    text-decoration: none !important;
    margin: 0 10px;
}
</style>