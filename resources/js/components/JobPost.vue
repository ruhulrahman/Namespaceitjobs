<template>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Job</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><router-link to="/dashboard">Dashboard</router-link></li>
              <li class="breadcrumb-item active">Job</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">

            <div class="card-header print-hide">
                <h3 class="card-title green">Jobs</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-success" @click="addNewjobModalOpen"> Add New Job

                        <i class="fab fa-bandcamp"></i>
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                  <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="dataTables_length" id="example1_length">

                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <button @click="print()" class="print-hide float-right mb-3">Print</button>
                    </div>
                    </div>
                  </div>
                          <div class="row">
                             <div class="col-sm-12">
                              <table id="example1" class="table table-bordered table-striped dataTable table-head-fixed" role="grid" aria-describedby="example1_info">
                <thead>
                    <tr role="row" class="purple">
                        <th>SL</th>
                        <th>Job Title</th>
                        <th>Job Description</th>
                        <th>Salary</th>
                        <th>Location</th>
                        <th>Country</th>
                        <th>Modify</th>
                    </tr>
                </thead>
                <tbody>
                    <tr role="row" class="odd" v-for="(job, index) in jobs" :key="index">
                        <td>{{ index+1 }}</td>
                        <td>{{ job.job_title }}</td>
                        <td>{{ job.job_description }}</td>
                        <td>{{ job.salary }}</td>
                        <td>{{ job.location }}</td>
                        <td>{{ job.country }}</td>

                        <td>
                            <router-link :to="/job-details/+job.id"><i class="fas fa-eye"></i></router-link>
                            <a href="#" @click.prevent="editjob(job)" class="btn btn-primary"><i class="fas fa-edit"></i></a> ||
                            <a href="#" @click.prevent="deletejob(job.id)" class="btn btn-danger"><i class="fas fa-trash"></i></a>
                        </td>

                    </tr>
                </tbody>
              </table>


              </div>
              </div>

                </div>
            </div>
            <!-- /.card-body -->
          </div>
        </div>





        <!-- Modal -->
        <div class="modal fade" id="addNewModal" tabindex="-1" role="dialog" aria-labelledby="addNewModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

            <form @submit.prevent="editMode ? udpatejob() : createjob()" @keydown="form.onKeydown($event)">
            <div class="modal-header  bg-success">
                <h5 v-show="!editMode" class="modal-title" id="addNewModalTitle">New job</h5>
                <h5 v-show="editMode" class="modal-title" id="addNewModalTitle">Edit job</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>Job Title</label>
                    <input v-model="form.job_title" type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('job_title') }">
                    <has-error :form="form" field="job_title"></has-error>
                </div>
                <div class="form-group">
                    <label>Job Description</label>
                    <textarea class="form-control" rows="6" v-model="form.job_description" placeholder="Job job_description...." :class="{ 'is-invalid': form.errors.has('job_description') }"></textarea>
                    <has-error :form="form" field="job_description"></has-error>
                </div>
                <div class="form-group">
                    <label>Salary</label>
                    <input v-model="form.salary" type="number" class="form-control" :class="{ 'is-invalid': form.errors.has('salary') }">
                    <has-error :form="form" field="salary"></has-error>
                </div>
                <div class="form-group">
                    <label>location</label>
                    <input v-model="form.location" type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('location') }">
                    <has-error :form="form" field="location"></has-error>
                </div>
                <div class="form-group">
                    <label>country</label>
                    <input v-model="form.country" type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('country') }">
                    <has-error :form="form" field="country"></has-error>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" @click="resetData">Reset</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button v-show="editMode" type="submit" class="btn btn-success">Update</button>
                <button v-show="!editMode" type="submit" class="btn btn-primary">Create</button>
            </div>
            </form>
            </div>
        </div>
        </div>

    </div>

    </section>


  </div>
  <!-- /.content-wrapper -->
</template>

<script>
    export default {
        data(){
            return{
                editMode:false,
                jobs:{},
                sl:0,
                form: new Form({
                    id: '',
                    job_title: '',
                    job_description: '',
                    salary: '',
                    location: '',
                    country: '',
                })
            }
        },
        methods: {
            print(){
                window.print()
            },
            // Our method to GET results from a Laravel endpoint
            getResults(page = 1) {
                axios.get('api/jobpost?page=' + page)
                    .then(response => {
                        this.jobs = response.data;
                    });
            },
            udpatejob(){
                this.$Progress.start()
                //Server request
                this.form.put('api/jobpost/'+this.form.id)
                .then(() => {
                    Fire.$emit('AfterCreated')
                    $('#addNewModal').modal('hide')
                    toast.fire({
                        icon: 'success',
                        title: 'job updated successfully'
                    })
                    this.$Progress.finish()
                })
                .catch(() => {
                    this.$Progress.fail()
                })
            },
            resetData(){
                this.form.reset();
            },
            deletejob(id){
                swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        //send to server
                        axios.delete('api/jobpost/'+id)
                        .then(()=>{
                            Fire.$emit('AfterDeleted');
                            swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                            )
                        })
                        .catch(()=>{
                            swal.fire(
                                'Failed!',
                                'Something wrong here.',
                                'error'
                                )
                        })
                    }
                })
            },
            addNewjobModalOpen(){
                this.editMode = false;
                this.form.reset()
                $('#addNewModal').modal('show')
            },
            editjob(job){
                this.editMode = true;
                this.form.reset()
                $('#addNewModal').modal('show')
                this.form.fill(job)
            },
            createjob(){
                this.$Progress.start()
                this.form.post('api/jobpost')
                .then(()=>{
                    Fire.$emit('AfterCreated')
                    $('#addNewModal').modal('hide')
                    toast.fire({
                        icon: 'success',
                        title: 'job Created successfully'
                    })
                    this.$Progress.finish()
                    this.form.reset()
                })
                .catch(()=>{
                    toast.fire({
                        icon: 'warning',
                        title: 'job didn\'t created'
                    })
                })

            },
            loadjobs(){
                 axios.get('api/jobpost')
                 .then(response => {
                        this.jobs = response.data;
                    });
            },
        },
        created() {
            Fire.$on("searching", ()=> {
                let query = this.$parent.search;
                axios.get('api/findjob?q='+ query)
                .then((data)=>{
                    this.jobs = data.data;
                })
                .catch(()=>{

                })
            });
            this.loadjobs();
            //setInterval(() => { this.loadjobs(); }, 500);
            Fire.$on('AfterCreated', ()=> {
                this.loadjobs();
            });
            Fire.$on('AfterDeleted', ()=> {
                this.loadjobs();
            });
        },
        mounted() {
            console.log('Component mounted.')
            // Fetch initial results
            //this.getResults();
        }
    }
</script>
