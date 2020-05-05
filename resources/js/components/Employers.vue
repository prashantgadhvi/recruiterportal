<template>
	<div class="container">
		<div class="row mt-5">
			  <div class="col-12">
				<div class="card">
				  <div class="card-header">
					<h3 class="card-title">Employers</h3>
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
						  <th>Phone</th>
						  <th>State</th>
						  <th>Location</th>
						  <th>Industry</th>
						  <th>Recruiter</th>
						  <th>Status</th>
						  <th>Modify</th>
						</tr>
					  </thead>
					  <tbody>
						<tr v-for="employer in employers.data" :key="employer.id">
						  <td>{{ employer.id }}</td>
						  <td>{{ employer.name }}</td>
						  <td>{{ employer.email }}</td>
						  <td>{{ employer.phone }}</td>
						  <td>{{ employer.state.name }}</td>
						  <td>{{ employer.city.name }}</td>
						  <td>{{ employer.industry.industry }}</td>
						  <td>{{ employer.user.name }}</td>
                          <td><span class="badge badge-success">{{ employer.status.status }}</span></td>
						  <td>
                              <router-link :to="`/employers/${employer.id}`"><i class="fa fa-eye green"></i></router-link>
                              <a href="#" v-show="employer.can_comment" @click="commentModal(employer)">
                                  <i class="fa fa-comment-dots orange"></i>
                              </a>
                              <a href="#" v-show="employer.can_assign" @click="assignModal(employer)">
                                  <i class="fa fa-user indigo"></i>
                              </a>
                              <a href="#" v-show="employer.can_edit" @click="editModal(employer)">
                                <i class="fa fa-edit blue"></i>
                            </a>
                            <a href="#" v-show="employer.can_delete" @click="deleteUser(employer.id)">
								<i class="fa fa-trash red"></i>
							</a>
						  </td>
						</tr>
					  </tbody>
					</table>
				  </div>
				  <!-- /.card-body -->
                  <div class="card-footer">
                      <pagination :data="employers" @pagination-change-page="loadEmployer"></pagination>
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
				<h5 class="modal-title" v-show="editMode" id="addNewLabel">Update employer's info</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
				</button>
			  </div>
			  <form @submit.prevent="editMode ? updateEmployer() : createEmployer()">
			  <div class="modal-body">
				<div class="form-group">
				  <input v-model="form.name" type="text" name="name"
					placeholder="Contact Name"
					class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
				  <has-error :form="form" field="name"></has-error>
				</div>
                  <div class="form-group">
                      <input v-model="form.designation" type="text" name="designation"
                             placeholder="Designation"
                             class="form-control" :class="{ 'is-invalid': form.errors.has('designation') }">
                      <has-error :form="form" field="designation"></has-error>
                  </div>
                  <div class="form-group">
                      <input v-model="form.business_name" type="text" name="business_name"
                             placeholder="Business Name"
                             class="form-control" :class="{ 'is-invalid': form.errors.has('business_name') }">
                      <has-error :form="form" field="business_name"></has-error>
                  </div>
                  <div class="form-group">
                      <input v-model="form.phone" type="text" name="phone"
                             placeholder="Phone number"
                             class="form-control" :class="{ 'is-invalid': form.errors.has('phone') }">
                      <has-error :form="form" field="phone"></has-error>
                  </div>
                  <div class="form-group">
                      <select class="form-control" name="country_id" v-model="form.country_id"
                              :class="{ 'is-invalid': form.errors.has('country_id') }" @change="getStates(form.country_id)">
                          <option :value="null">Select country</option>
                          <option v-for="country in countries" :value="country.id">{{ country.name }}</option>
                      </select>
                      <has-error :form="form" field="country_id"></has-error>
                  </div>
                  <div class="form-group">
                      <select class="form-control" name="state_id" v-model="form.state_id"
                              :class="{ 'is-invalid': form.errors.has('state_id') }" @change="getCities(form.state_id)">
                          <option :value="null">Select state</option>
                          <option v-for="state in states" :value="state.id">{{ state.name }}</option>
                      </select>
                      <has-error :form="form" field="state_id"></has-error>
                  </div>
                  <div class="form-group">
                      <select class="form-control" name="city_id" v-model="form.city_id"
                              :class="{ 'is-invalid': form.errors.has('city_id') }" >
                          <option :value="null">Select city</option>
                          <option v-for="city in cities" :value="city.id">{{ city.name }}</option>
                      </select>
                      <has-error :form="form" field="city_id"></has-error>
                  </div>
                  <div class="form-group">
                      <select class="form-control" name="industry_id" v-model="form.industry_id"
                              :class="{ 'is-invalid': form.errors.has('industry_id') }" >
                          <option :value="null">Select industry type</option>
                          <option v-for="industry in industries" :value="industry.id">{{ industry.industry }}</option>
                      </select>
                      <has-error :form="form" field="industry_id"></has-error>
                  </div>
				<div class="form-group">
				  <input v-model="form.email" type="email" name="email"
					placeholder="Email"
					class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
				  <has-error :form="form" field="email"></has-error>
				</div>
                  <div class="form-group">
                      <select class="form-control" name="messenger_type" v-model="form.messenger_type"
                              :class="{ 'is-invalid': form.errors.has('messenger_type') }" >
                          <option :value="null">Select messenger type</option>
                          <option v-for="messenger in messengers" :value="messenger">{{ messenger }}</option>
                      </select>
                      <has-error :form="form" field="messenger_type"></has-error>
                  </div>
                  <div class="form-group">
                      <input v-model="form.messenger_id" type="text" name="messenger_id"
                             placeholder="Messenger Id"
                             class="form-control" :class="{ 'is-invalid': form.errors.has('messenger_id') }">
                      <has-error :form="form" field="messenger_id"></has-error>
                  </div>
                  <div class="form-group">
                      <select class="form-control" name="status_id" v-model="form.status_id"
                              :class="{ 'is-invalid': form.errors.has('status_id') }" >
                          <option :value="null">Select status</option>
                          <option v-for="status in statuses" :value="status.id">{{ status.status }}</option>
                      </select>
                      <has-error :form="form" field="status_id"></has-error>
                  </div>
                  <div class="form-group">
                      <select class="form-control" name="requisition_id" v-model="form.requisition_id"
                              :class="{ 'is-invalid': form.errors.has('requisition_id') }" >
                          <option :value="null">Select requisition</option>
                          <option v-for="requisition in requisitions" :value="requisition.id">{{ requisition.requisition }}</option>
                      </select>
                      <has-error :form="form" field="requisition_id"></has-error>
                  </div>
                  <div class="form-group">
                      <input v-model="form.fees" type="text" name="fees"
                             placeholder="Fees(CAD)"
                             class="form-control" :class="{ 'is-invalid': form.errors.has('fees') }">
                      <has-error :form="form" field="fees"></has-error>
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
        <div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="commentModalLabel">Comment for {{ employer.name }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="saveComment(employer)">
                        <div class="modal-body">
                            <div class="form-group">
                                <textarea v-model="comment_form.message" name="message" placeholder="Enter your message"
                                          class="form-control" :class="{ 'is-invalid': comment_form.errors.has('message') }">
                                </textarea>
                                <has-error :form="comment_form" field="message"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="assignModal" tabindex="-1" role="dialog" aria-labelledby="assignModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="assignModalLabel">Delegate Employer : {{ employer.name }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="updateAssign(employer)">
                        <div class="modal-body">
                            <div class="form-group">
                                <select class="form-control" name="user_id" v-model="assign_form.user_id"
                                        :class="{ 'is-invalid': assign_form.errors.has('user_id') }" >
                                    <option :value="null">Select user</option>
                                    <option v-for="user in users" :value="user.id">{{ user.name }}</option>
                                </select>
                                <has-error :form="assign_form" field="user_id"></has-error>
                            </div>
                            <div class="form-group">
                                <textarea v-model="assign_form.message" name="message" placeholder="Enter your message"
                                          class="form-control" :class="{ 'is-invalid': assign_form.errors.has('message') }">
                                </textarea>
                                <has-error :form="assign_form" field="message"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
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
				editMode        : false,
				employer        : {},
				employers       : {},
				users           : {},
                messengers      : {},
                industries      : {},
                statuses        : {},
                requisitions    : {},
                countries       : {},
                states          : {},
                cities          : {},
				form : new Form({
					id : '',
                    name : '',
                    designation : '',
                    business_name : '',
                    city_id : null,
                    state_id : null,
                    country_id : null,
                    industry_id : null,
                    phone : '',
                    messenger_type : null,
                    messenger_id : '',
                    email : '',
                    status_id : null,
                    requisition_id : null,
                    fees : '',
				}),
                comment_form : new Form({
                    message : '',
                }),
                assign_form : new Form({
                    user_id : null,
                    message : '',
                }),
			}
		},
		methods: {
			newModal(){
				this.editMode = false;
				this.form.reset();
				$('#addNew').modal('show');
			},
			editModal(employer){
				this.editMode = true;
				this.form.clear();
                $('#addNew').modal('show');
                employer.status_id = employer.status.id;
                employer.requisition_id = employer.requisition.id;
                employer.industry_id = employer.industry.id;
                employer.country_id = employer.country.id;
                employer.state_id = employer.state.id;
                employer.city_id = employer.city.id;
                console.log(employer);
                this.getStates(employer.country_id, employer.state_id);
				this.form.fill(employer);
            },
            commentModal(employer){
			    this.employer = employer;
                $('#commentModal').modal('show');
            },
            assignModal(employer){
                this.$Progress.start();
			    this.employer = employer;
                axios.get('/api/employers/'+ employer.id +'/users').then(({data}) => {
                    this.users = data.data;
                    this.$Progress.finish();
                    $('#assignModal').modal('show');
                }).catch(() => {
                    this.$Progress.fail();
                });

            },
            saveComment(employer){
                this.$Progress.start();
                this.comment_form.post('/api/employers/' + employer.id + '/comment')
                    .then(()=> {
                        Toast.fire({
                            icon: 'success',
                            title: 'Comment successfully added.'
                        });
                        $('#commentModal').modal('hide');
                        this.$Progress.finish();
                    }).catch(() => {
                        this.$Progress.fail();
                    });
            },
            updateAssign(employer){
                this.$Progress.start();
                this.assign_form.post('/api/employers/' + employer.id + '/assign')
                    .then(()=> {
                        Fire.$emit('EmployerCreated');
                        Toast.fire({
                            icon: 'success',
                            title: 'Employer delegated successfully.'
                        });
                        $('#assignModal').modal('hide');
                        this.$Progress.finish();
                    }).catch(() => {
                    this.$Progress.fail();
                });
            },
			loadEmployer(page = 1){
                this.$Progress.start();
				axios.get('/api/employers?page=' + page).then(({data}) => {
				    //this.employers = data;
                    //this.$Progress.finish();
                });
			},
            loadOptions(){
                if (localStorage.getItem('messengers')){
                    this.messengers = JSON.parse(localStorage.getItem('messengers'));
                }else{
                    axios.get('/api/employers/messenger-type').then(({data}) => {
                        this.messengers = data.data;
                        localStorage.setItem('messengers', JSON.stringify(this.messengers));
                    });
                }

                if (localStorage.getItem('industries')){
                    this.industries = JSON.parse(localStorage.getItem('industries'));
                }else{
                    axios.get('/api/employers/industries').then(({data}) => {
                        this.industries = data.data;
                        localStorage.setItem('industries', JSON.stringify(this.industries));
                    });
                }
                if (localStorage.getItem('statuses')){
                    this.statuses = JSON.parse(localStorage.getItem('statuses'));
                }else{
                    axios.get('/api/employers/statuses').then(({data}) => {
                        this.statuses = data.data;
                        localStorage.setItem('statuses', JSON.stringify(this.statuses));
                    });
                }
                if (localStorage.getItem('requisitions')){
                    this.requisitions = JSON.parse(localStorage.getItem('requisitions'));
                }else{
                    axios.get('/api/employers/requisitions').then(({data}) => {
                        this.requisitions = data.data;
                        localStorage.setItem('requisitions', JSON.stringify(this.requisitions));
                    });
                }
            },
            createEmployer(){
				this.$Progress.start();
				this.form.post('/api/employers')
				.then(()=> {
					Fire.$emit('EmployerCreated');
					Toast.fire({
					  icon: 'success',
					  title: 'Employer successfully created.'
					});
					$('#addNew').modal('hide');
					this.$Progress.finish();
				}).catch(() => {
                    this.$Progress.fail();
                });
			},
			updateEmployer(){
				this.$Progress.start();
				this.form.put('/api/employers/' + this.form.id)
				.then(()=> {
					Fire.$emit('EmployerCreated');
					Toast.fire({
					  icon: 'success',
					  title: 'User successfully updated.'
					});
					$('#addNew').modal('hide');
					this.$Progress.finish();
				}).catch(() => {
                    this.$Progress.fail();
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
					this.form.delete('/api/employers/'+id)
						.then(()=> {
							Fire.$emit('EmployerDeleted');
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
			},
            getCountries: function(){
                axios.get('/api/countries')
                    .then(({data}) => {
                        this.countries = data.data;
                        this.states = '';
                        this.cities = '';
                    });
            },
            getStates: function(country_id, state_id = null){
                axios.get('/api/countries/states/' + country_id)
                    .then(({data}) => {
                        this.states = data.data;
                        if(state_id !== null){
                            this.getCities(state_id, true);
                        }else{
                            this.cities = '';
                            this.form.state_id = null;
                            this.form.city_id = null;
                        }
                    });
            },
            getCities: function(state_id, edit=false){
                axios.get('/api/countries/cities/' + state_id)
                    .then(({data}) => {
                        this.cities = data.data;
                        if(edit === false){
                            this.form.city_id = null;
                        }
                    });
            }
		},
        created() {
		    this.getCountries();
            this.loadEmployer();
            this.loadOptions();
			Fire.$on('EmployerCreated',() => {
				this.loadEmployer();
			});
			Fire.$on('EmployerDeleted',() => {
				this.loadEmployer();
			});
        }
    }
</script>
