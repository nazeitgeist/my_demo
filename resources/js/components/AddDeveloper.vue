<template>
    <div>
        <h4 class="text-center">Add new</h4>
        <div class="row">
            <div class="col-md-12">
                <form @submit.prevent="addDeveloper">
					<div class="form-row">
						<div class="form-group col-md-6">
							<label>First Name</label>
							<input type="text" required class="form-control" v-model="developer.firstName">
						</div>
						<div class="form-group col-md-6">
							<label>Last Name</label>
							<input type="text" required class="form-control" v-model="developer.lastName">
						</div>
					</div>
                    <div class="form-row">
						<div class="form-group col-md-6">
							<label>Email Address</label>
							<input type="email" required class="form-control" v-model="developer.email">
						</div>
						<div class="form-group col-md-6">
							<label>Phone Number</label>
							<input type="text" required class="form-control" v-model="developer.phoneNo">
						</div>
					</div>
					<div class="form-group">
                        <label>Address</label>
                        <input type="text" required class="form-control" v-model="developer.address">
                    </div>
					<div class="form-row">
						<div class="form-group col-md-4">
							<label>Avatar</label>
							<div class="custom-file">
								<input type="file" class="custom-file-input" id="customFile" ref="file" @change="handleFileObject()">
								<label class="custom-file-label" for="customFile">{{ avatarName }}</label>
							</div>
						</div>
					</div>
					<p></p>
					<div class="btn-group" role="group">
						<router-link to="/developers" class="btn btn-dark"> &lt; Back</router-link>
                    	<button type="submit" class="btn btn-primary">Add New</button>
					</div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            developer: {},
			avatarImg: null,
			avatarName: null
        }
    },
    methods: {
	
        addDeveloper() {
			
			let formData = new FormData();
			_.each(this.developer, (value, key) => {
				formData.append(key, value)
			})

			let config = { headers: { 'Content-Type': 'multipart/form-data' } }
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('/api/developers/add', formData, config)
                    .then(response => {
                        this.$router.push({name: 'developers'})
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        },
		handleFileObject() {
			console.log(this.$refs.file.files[0])
			this.developer.avatarImg = this.$refs.file.files[0],
			this.avatarName = this.developer.avatarImg.name
		},

    },
    beforeRouteEnter(to, from, next) {
        if (!window.Laravel.isLoggedin) {
            window.location.href = "/";
        }
        next();
    }
}
</script>