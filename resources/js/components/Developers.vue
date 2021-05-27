<template>
    <div>
        <h4 class="text-center">List of Developers</h4><br/>
		<div class="table-responsive">
			<table class="table table-bordered table-striped">
				<thead class="thead-dark">
					<tr>
						<th><input type="checkbox" @click="onCheckedAll" v-model="all_select"></th>
						<th>ID</th>
						<th>Avatar</th>
						<th width="20%">Name</th>
						<th width="20%">Email</th>
						<th>Phone No.</th>
						<th>Address</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody v-if="developers.length">
					<tr v-for="developer in developers" :key="developer.id">
						<td><input type="checkbox" v-model="checked" :value="developer.id"></td>
						<td>{{ developer.id }}</td>
						<td align="center"><img :src=developer.avatar_url class="rounded-circle" :width="50"></td>
						<td>{{ developer.firstName + " " + developer.lastName }}</td>
						<td>{{ developer.email }}</td>
						<td>{{ developer.phoneNo }}</td>
						<td>{{ developer.address }}</td>
						<td align="center">
							<div class="btn-group" role="group">
								<router-link :to="{name: 'editdeveloper', params: { id: developer.id }}" class="btn btn-primary">Edit
								</router-link>
								<button class="btn btn-danger" @click="deleteDeveloper(developer.id)">Delete</button>
							</div>
						</td>
					</tr>
				</tbody>
				<tbody v-else>
					<tr>
						<td colspan="10" style="text-align:center">No data found.</td>
					</tr>
				</tbody>
			</table>
		</div>
		<buttonGroup>
        <button type="button" class="btn btn-success" @click="this.$router.push('/developers/add')">Add New Developer</button>
		<form @submit.prevent="deleteDeveloperMultiple" style="display:inline-block; margin-left:5px">
				<input type="hidden" v-model="developers"> 
				<button type="submit" class="btn btn-primary">Delete selected</button>
		</form>
		</buttonGroup>
    </div>
</template>

<script>
export default {
    data() {
        return {
            developers: [],
			checked:[],
			all_select: false
        }
    },
    created() {
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get('/api/developers')
                .then(response => {
                    this.developers = response.data;
                })
                .catch(function (error) {
                    console.error(error);
                });
        })
    },
    methods: {
		onCheckedAll(){
			if(this.all_select == false){
				this.all_select = true
				this.developers.forEach(user => {
					this.checked.push(user.id)
				});
			}else{
				this.all_select = false
				this.checked = []
			}
			//console.log(this.checked);
		},
		deleteDeveloperMultiple() {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('/api/developers/deleteMultitple', { checked: this.checked, aa: this.developers })
                    .then(response => {
						console.log("sss", this.developers);
						this.checked.forEach( element => {
							let i = this.developers.map(item => item.id).indexOf(element);
							this.developers.splice(i, 1);
						});

                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        },
        deleteDeveloper(id) {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.delete(`/api/developers/delete/${id}`)
                    .then(response => {
                        let i = this.developers.map(item => item.id).indexOf(id); // find index of your object
                        this.developers.splice(i, 1)
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        }
    },
    beforeRouteEnter(to, from, next) {
        if (!window.Laravel.isLoggedin) {
            window.location.href = "/";
        }
        next();
    }
}
</script>