<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="alert alert-danger" role="alert" v-if="error !== null">
                    {{ error }}
                </div>

                <div class="card card-default">
                    <div class="card-header">Reset Password</div>
                    <div class="card-body">
                        <form>
                            <div class="form-group row">
                                <label for="email" class="col-sm-4 col-form-label text-md-right">E-Mail Address</label>
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" v-model="email" required
                                           autofocus autocomplete="off">
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-success" @click="handleSubmit">
                                        Reset Password
                                    </button>
                                </div>
							</div>
							<p></p>
							<div class="form-group" >
								<div class="alert alert-info" role="alert" v-if="progress == 'sending'">
									Sending email..
								</div>
								<div class="alert alert-info" role="alert" v-if="progress == 'done'">
									Email sent. Please check your email.
								</div>
								<div class="alert alert-danger" role="alert" v-if="progress == 'error'">
									Error sending email.
								</div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            email: "",
            error: null,
			progress: ""
        }
    },
    methods: {
        handleSubmit(e) {
            e.preventDefault();
			this.error = null;
            if (this.email.length > 0) {
				this.progress = "sending";
                this.$axios.get('/sanctum/csrf-cookie').then(response => {
                    this.$axios.post('api/forgotPassword', {
                        email: this.email
                    })
                        .then(response => {
                            if (response.data.success) {
								this.progress = "done";
                                //window.location.href = "/login"
                            } else {
                                this.error = response.data.message
								this.progress = "error";
                            }
                        })
                        .catch(function (error) {
							this.progress = "error";
                            console.error(error);
                        });
                })
            }
        }
    },
    beforeRouteEnter(to, from, next) {
        if (window.Laravel.isLoggedin) {
            return next('dashboard');
        }
        next();
    }
}
</script>