<template>
	<div class="container">
		<div class="row mt-5">
			  <div class="col-12">
				<div class="card">
				  <div class="card-header">
					<h3 class="card-title">Recruiters</h3>

					<div class="card-tools">
						<button class="btn btn-success" @click="newModal()">Add New <i class="fas fa-user-plus fa-fw"></i></button>
					</div>
				  </div>
				  <!-- /.card-header -->
				  <div class="card-body table-responsive p-0">
					<table class="table table-hover text-nowrap">
					  <thead>
						<tr>
						  <th>ID</th>
						  <th>Name</th>
						  <th>Email</th>
						  <th>Type</th>
						  <th>Modify</th>
						</tr>
					  </thead>
					  <tbody>
						<tr v-for="user in users.data" :key="user.id">
						  <td>{{ user.id }}</td>
						  <td>{{ user.name }}</td>
						  <td>{{ user.email }}</td>
						  <td v-if="user.type === 1">
							Super Admin
						  </td>
						  <td v-else>
							Recruiter
						  </td>
						  <td>
							<a href="#" @click="editModal(user)">
								<i class="fa fa-edit blue"></i>
							</a>
							<a href="#" @click="deleteUser(user.id)">
								<i class="fa fa-trash red"></i>
							</a>
						  </td>
						</tr>
					  </tbody>
					</table>
				  </div>
				  <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination :data="users" @pagination-change-page="loadUser"></pagination>
                    </div>
				</div>
				<!-- /.card -->
			  </div>
		</div>
		<!-- Modal -->
		<div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
			  <div class="modal-header">
				<h5 class="modal-title" v-show="!editMode" id="addNewLabel">Add New</h5>
				<h5 class="modal-title" v-show="editMode" id="addNewLabel">Update user's info</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
				</button>
			  </div>
			  <form @submit.prevent="editMode ? updateUser() : createUser()">
			  <div class="modal-body">
				<div class="form-group">
				  <input v-model="form.name" type="text" name="name"
					placeholder="Name"
					class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
				  <has-error :form="form" field="name"></has-error>
				</div>
				<div class="form-group">
				  <input v-model="form.email" type="email" name="email"
					placeholder="Email"
					class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
				  <has-error :form="form" field="email"></has-error>
				</div>
				<div class="form-group">
				  <input v-model="form.password" type="password" name="password"
					placeholder="Password"
					class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
				  <has-error :form="form" field="password"></has-error>
				</div>
				<div class="form-group">
				  <select class="form-control" name="type" v-model="form.type" id="type"
					:class="{ 'is-invalid': form.errors.has('type') }" >
					  <option value="">Select user type</option>
					  <option value="1">Super admin</option>
					  <option value="2">Recruiter</option>
					</select>
				  <has-error :form="form" field="type"></has-error>
				</div>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="submit" v-show="!editMode" class="btn btn-primary">Create</button>
				<button type="submit" v-show="editMode" class="btn btn-primary">Update</button>
			  </div>
			  </form>
			</div>
		  </div>
		</div>
    </div>
</template>

<script>
    export default {
		data() {
			return {
				editMode : false,
				users : {},
				form : new Form({
					id : '',
					name : '',
					email : '',
					password : '',
					type : ''
				})
			}
		},
		methods: {
			newModal(){
				this.editMode = false;
				this.form.reset();
				$('#addNew').modal('show');
			},
			editModal(user){
				this.editMode = true;
				this.form.clear();
				$('#addNew').modal('show');
				this.form.fill(user);

			},
			editUser(){
				this.form.put()
				.then(() => {
				});
			},
			loadUser(page = 1){
				axios.get('api/users?page=' + page).then(({data}) => (this.users = data));
			},
			createUser(){
				this.$Progress.start();
				this.form.post('api/users')
				.then(()=> {
					Fire.$emit('UserCreated');
					Toast.fire({
					  icon: 'success',
					  title: 'User successfully created.'
					});
					$('#addNew').modal('hide');
					this.$Progress.finish();
				});
			},
			updateUser(){
				this.$Progress.start();
				this.form.put('api/users/' + this.form.id)
				.then(()=> {
					Fire.$emit('UserCreated');
					Toast.fire({
					  icon: 'success',
					  title: 'User successfully updated.'
					});
					$('#addNew').modal('hide');
					this.$Progress.finish();
				});
			},
			deleteUser(id){
				Swal.fire({
				  title: 'Are you sure?',
				  text: "You won't be able to revert this!",
				  icon: 'warning',
				  showCancelButton: true,
				  confirmButtonColor: '#3085d6',
				  cancelButtonColor: '#d33',
				  confirmButtonText: 'Yes, delete it!'
				}).then((result) => {
				  if (result.value) {
					this.form.delete('api/users/'+id)
						.then(()=> {
							Fire.$emit('UserDeleted');
							Swal.fire(
							  'Deleted!',
							  'Your file has been deleted.',
							  'success'
							)
						});
				  }
				}).catch(() => {
					Swal.fire({
					  icon: 'error',
					  title: 'Oops...',
					  text: 'Something went wrong!'
					})
				});
			}
		},
        created() {
            this.loadUser();
			Fire.$on('UserCreated',() => {
				this.loadUser();
			});
			Fire.$on('UserDeleted',() => {
				this.loadUser();
			});
        }
    }
</script>
