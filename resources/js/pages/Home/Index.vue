<template>
    <div>
        <h1>Home</h1>
        <p>This is the home page.</p>
        <div>
            <div v-if="authInfo">
                <p>User Name: {{ authInfo.name }}</p>
                <p>User Email: {{ authInfo.email }}</p>
                <button @click="logout">Logout</button>
                <router-link :to="{ name: 'admin.services' }" class="mx-2">Services</router-link>
            </div>
            <div v-else>
                <p>You are not logged in.</p>
                <router-link :to="{ name: 'authLogin' }">Login</router-link>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Home',
    data() {
        return {
        };
    },
    computed: {
        authInfo() {
            const user = this.$store.getters['auth/authInfo'];
            if (!user || Object.keys(user).length === 0) {
                return null;
            }
            return this.$store.getters['auth/authInfo'];
        }
    },
    mounted() {
        this.getAuthInfo();
    },
    methods: {
        logout() {
            this.$store.dispatch('auth/logout').then(() => {
                this.$router.push({ name: 'authLogin' });
            }).catch(error => {
            });
        },
        getAuthInfo() {
            const auth = JSON.parse(localStorage.getItem('auth'));
            if (!auth || !auth.access_token) {
                return Promise.resolve(null);
            }

            return this.$store.dispatch('auth/getAuthInfo')
                .then(response => {
                    console.log('Auth info retrieved:', response);
                })
                .catch(error => {
                    console.log('Not logged in');
                });
        }
    }
}
</script>
