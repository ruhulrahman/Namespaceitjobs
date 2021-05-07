<template>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Blood</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><router-link to="/dashboard">Dashboard</router-link></li>
              <li class="breadcrumb-item active">Blood</li>
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
                    <button type="button" class="btn btn-success" @click="addNewjobModalOpen"> Add New Blood

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
                        <th>Blood Name</th>
                        <th>Blood Name Bn</th>
                        <th>Modify</th>
                    </tr>
                </thead>
                <tbody>
                    <tr role="row" class="odd" v-for="(blood, index) in bloods" :key="index">
                        <td>{{ index+1 }}</td>
                        <td>{{ blood.blood_name }}</td>
                        <td>{{ blood.blood_name_bn }}</td>
                        <td>
                            <button @click="edit(blood)">Edit</button> || 
                            <button @click="deleteBlood(blood.id)">Delete</button>
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

            <form @submit.prevent="editMode ? udpateBlood() : createBlood()" @keydown="form.onKeydown($event)">
            <div class="modal-header  bg-success">
                <h5 v-show="!editMode" class="modal-title" id="addNewModalTitle">New Blood</h5>
                <h5 v-show="editMode" class="modal-title" id="addNewModalTitle">Edit Blood</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>Blood Name</label>
                    <input v-model="entryData.blood_name" type="text" class="form-control" :class="{ 'is-invalid': entryData.errors.has('blood_name') }">
                    <has-error :form="entryData" field="blood_name"></has-error>
                </div>
                <div class="form-group">
                    <label>Blood Name (Bn)</label>
                    <input v-model="entryData.blood_name_bn" type="text" class="form-control" :class="{ 'is-invalid': entryData.errors.has('blood_name_bn') }">
                    <has-error :form="entryData" field="blood_name_bn"></has-error>
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
                bloods:{},
                sl:0,
                entryData: new Form({
                    id: '',
                    blood_name: '',
                    blood_name_bn: ''
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
            udpateBlood(){
                this.$Progress.start()
                //Server request
                this.entryData.put('api/blood/'+this.entryData.id)
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
            deleteBlood(id){
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
                        axios.delete('api/blood/'+id)
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
                this.entryData.reset()
                $('#addNewModal').modal('show')
            },
            edit(item){
                this.editMode = true;
                this.entryData.reset()
                $('#addNewModal').modal('show')
                this.entryData.fill(item)
            },
            createBlood(){
                this.$Progress.start()
                this.entryData.post('api/blood')
                .then(() => {
                    Fire.$emit('AfterCreated')
                    $('#addNewModal').modal('hide')
                    toast.fire({
                        icon: 'success',
                        title: 'Blood Created successfully'
                    })
                    this.$Progress.finish()
                    this.form.reset()
                })
                .catch(()=>{
                    toast.fire({
                        icon: 'warning',
                        title: 'Blood didn\'t created'
                    })
                })

            },
            loadblood(){
                 axios.get('api/blood')
                 .then(response => {
                        this.bloods = response.data;
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
            this.loadblood();
            //setInterval(() => { this.loadblood(); }, 500);
            Fire.$on('AfterCreated', ()=> {
                this.loadblood();
            });
            Fire.$on('AfterDeleted', ()=> {
                this.loadblood();
            });
        },
        mounted() {
            console.log('Component mounted.')
            // Fetch initial results
            //this.getResults();
        }
    }
</script>
