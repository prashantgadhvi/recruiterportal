<template>
	<div class="container">
		<div class="row mt-5">
			  <div class="col-12">
				<div class="card">
				  <div class="card-header">
					<h3 class="card-title">Employer Details</h3>
                      <div class="card-tools">
                          <router-link :to="`/employers`">
                              <button type="button" class="btn btn-block btn-outline-primary btn-sm">Back</button>
                          </router-link>
                      </div>
                  </div>
				  <!-- /.card-header -->
				  <div class="card-body table-responsive">
                      <div class="form-group">
                          <label>Contact Name:</label>
                          {{ employer.name }}
                      </div>
                      <div class="form-group">
                          <label>Designation:</label>
                          {{ employer.designation }}
                      </div>
                      <div class="form-group">
                          <label>Business Name:</label>
                          {{ employer.business_name }}
                      </div>
                      <div class="form-group">
                          <label>Phone Number:</label>
                          <a :href="`tel:${employer.phone}`">{{ employer.phone }}</a>
                      </div>
                      <div class="form-group">
                          <label>Location:</label>
                          {{ employer.city.name }}, {{ employer.state.name }}, {{ employer.country.name }}
                      </div>
                      <div class="form-group">
                          <label>Industry:</label>
                          {{ employer.industry.industry }}
                      </div>
                      <div class="form-group">
                          <label>Email:</label>
                          <a :href="`mailto:${employer.email}`">{{ employer.email }}</a>
                      </div>
                      <div class="form-group">
                          <label>Messanger Type / Id:</label>
                          {{ employer.messenger_type }} : {{ employer.messenger_id }}
                      </div>
                      <div class="form-group">
                          <label>Status:</label>
                          {{ employer.status.status }}
                      </div>
                      <div class="form-group">
                          <label>Requisition:</label>
                          {{ employer.requisition.requisition }}
                      </div>
                      <div class="form-group">
                          <label>Fees:</label>
                          {{ employer.fees}} CAD
                      </div>
                      <div class="form-group">
                          <label>Notes:</label>
                          <div v-for="note in employer.notes" :key="note.id" class="callout callout-info">
                              <h5> {{ note.created_at | moment("dddd, MMMM Do YYYY, h:mm:ss a") }}</h5>
                                {{ note.message }}
                              <p>- {{ note.user.name }}</p>
                          </div>
                      </div>

                  </div>
				  </div>
				<!-- /.card -->
			  </div>
		</div>
	</div>
</template>

<script>
    export default {
		data() {
			return {
				employer : {
                    id : '',
                    name : '',
                    designation : '',
                    business_name : '',
                    city : '',
                    state : '',
                    country : '',
                    industry : '',
                    phone : '',
                    messenger_type : '',
                    messenger_id : '',
                    email : '',
                    status : {},
                    requisition : {},
                    fees : '',
                    notes : {}
                },
			}
		},
		methods: {
			loadEmployer(){
                this.$Progress.start();
				axios.get('/api/employers/' + this.$route.params.id).then(({data}) => {
				    this.employer = data.data;
                    this.$Progress.finish();
                });
			}
		},
        created() {
		    this.loadEmployer();
        }
    }
</script>
