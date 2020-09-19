<template>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Find Job</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><router-link to="/dashboard">Dashboard</router-link></li>
              <li class="breadcrumb-item active">Find Job</li>
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
                        <th>Job Title</th>
                        <th>Salary</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr role="row" class="odd" v-for="(job, index) in jobs" :key="index">
                        <td><router-link :to="/job-details/+job.id">{{ job.job_title }}</router-link></td>
                        <td>{{ job.salary }}</td>

                        <td>
                            <a href="#" @click.prevent="deletejob(job.id)" class="btn btn-success">Job Apply</a>
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
