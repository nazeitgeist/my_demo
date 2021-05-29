<template>
    <div class="container">
        <div class="text-center" style="margin: 20px 0px 20px 0px;">
			<img :src="image_src" ><br/>
            <span class="text-secondary">DEMO by Nazri</span>
        </div>

        <nav class="navbar navbar-expand-lg navbar-light bg-dark">
            <div class="collapse navbar-collapse">
                <!-- for logged-in user-->
                <div class="navbar-nav" v-if="isLoggedIn">
                    <router-link to="/dashboard" class="nav-item nav-link text-white">Dashboard</router-link>
                    <router-link to="/developers" class="nav-item nav-link text-white">Developers</router-link>
                    <a class="nav-item nav-link text-white" style="cursor: pointer;" @click="logout">Logout</a>
                </div>
                <!-- for non-logged user-->
                <div class="navbar-nav" v-else>
                    <router-link to="/" class="nav-item nav-link text-white">Home</router-link>
                    <router-link to="/login" class="nav-item nav-link text-white">Login</router-link>
                    <router-link to="/register" class="nav-item nav-link text-white">Register
                    </router-link>
                    <router-link to="/about" class="nav-item nav-link text-white">About</router-link>
                </div>
            </div>
        </nav>
        <br/>
        <router-view/>
    </div>
</template>

<script>
export default {
    name: "App",
    data() {
        return {
			image_src: '/logotype.min.svg',
            isLoggedIn: false,
        }
    },
    created() {
        if (window.Laravel.isLoggedin) {
            this.isLoggedIn = true
        }
    },
    methods: {
        logout(e) {
            e.preventDefault()
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('/api/logout')
                    .then(response => {
                        if (response.data.success) {
                            window.location.href = "/"
                        } else {
                            console.log(response)
                        }
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        }
    },
}
</script>