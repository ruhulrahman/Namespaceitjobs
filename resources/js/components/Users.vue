<template>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Users</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><router-link to="/dashboard">Home</router-link></li>
              <li class="breadcrumb-item active">Users</li>
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
            <div class="col-12" v-if="$gate.isAdminOrAuthorOrSuper()">
                <div class="card">
                    
            <div class="card-header print-hide">
                <h3 class="card-title green">Users</h3>
                
                <div class="card-tools">
                    <button type="button" class="btn btn-success" @click="addNewUserModalOpen"> Add New User
                        <i class="fas fa-user-plus fa-fw" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                  <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="dataTables_length" id="example1_length">
                            <label>Show <select name="example1_length" aria-controls="example1" class="custom-select custom-select-sm form-control form-control-sm"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> entries</label>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <button @click="print()" class="print-hide float-right">Print</button>                     
                    </div>
                    </div>
                  </div>
                          <div class="row">
                             <div class="col-sm-12">
                              <table id="example1" class="table table-bordered table-striped dataTable table-head-fixed" role="grid" aria-describedby="example1_info">
                <thead>
                    <tr role="row" class="purple">
                        <th>SL</th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th >Register At</th>
                        <th>Modify</th>
                    </tr>
                </thead>
                <tbody>
                    <tr role="row" class="odd" v-for="(user, index) in users.data" :key="index">
                        <td>{{ index+1 }}</td>
                        <td>{{ user.id }}</td>
                        <td>{{ user.name | capsText }}</td>
                        <td>{{ user.email | lowText }}</td>
                       
                        <td v-if="user.type === 'admin'">  
                            <span class="badge badge-pill badge-danger">{{ user.type |  capsText }}</span>
                        </td>
                        <td v-else-if="user.type === 'super'">  
                            <span class="badge badge-pill badge-warning">{{ user.type |  capsText }}</span>
                        </td>
                        <td v-else-if="user.type === 'author'">  
                            <span class="badge badge-pill badge-primary">{{ user.type |  capsText }}</span>
                        </td>
                        <td v-else>                             
                            <span class="badge badge-pill badge-success">{{ user.type |  capsText }}</span>                     
                        </td>
                        <td>{{ user.created_at | myDate}}</td>
                        
                        <td>
                            <a v-on:click.once="doThis"></a>
                            <a href="#" @click="editUser(user)"><i class="fas fa-edit blue"></i></a> ||
                            <a href="#" @click="deleteUser(user.id)"><i class="fas fa-trash red"></i></a>
                        </td>
                        
                    </tr>
                </tbody>
              </table>
                  
              
              </div>
              </div>
              
              <div class="row">
                  <div class="col-sm-12 col-md-5">
                      <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">Showing 1 to 10 of 57 entries</div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <pagination :data="users" @pagination-change-page="getResults">
                            <span slot="prev-nav">&lt; Previous</span>
	<span slot="next-nav">Next &gt;</span>
                        </pagination>
                    </div>
                </div>
                </div>
            </div>
            <!-- /.card-body -->
          </div>
        </div>


        <div v-if="!$gate.isAdminOrAuthorOrSuper()">
            <not-found></not-found>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="addNewModal" tabindex="-1" role="dialog" aria-labelledby="addNewModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

            <form @submit.prevent="editMode ? udpateUser() : createUser()" @keydown="form.onKeydown($event)">
            <div class="modal-header  bg-success">
                <h5 v-show="!editMode" class="modal-title" id="addNewModalTitle">New User</h5>
                <h5 v-show="editMode" class="modal-title" id="addNewModalTitle">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Name</label>
                    <input v-model="form.name" type="text" name="name"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                    <has-error :form="form" field="name"></has-error>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input v-model="form.email" type="email" name="email"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                    <has-error :form="form" field="email"></has-error>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input v-model="form.password" type="text" name="password"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                    <has-error :form="form" field="password"></has-error>
                </div>
                <div class="form-group">
                    <label for="userType">User Type</label>
                    <select name="type" class="form-control" id="userType" v-model="form.type" :class="{ 'is-invalid': form.errors.has('type') }">
                        <option value="" disabled="disabled">Select User Type</option>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                        <option value="author">Author</option>
                        @can('isSuperAdmin')
                        <option value="super">Super</option>
                        @endcan
                    </select>
                    <has-error :form="form" field="type"></has-error>
                </div>
                <div class="form-group">
                    <label for="bio">Bio</label>
                    <textarea name="bio" placeholder="Bio (Optional)" class="form-control" id="bio" rows="2" v-model="form.bio" :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
                    <has-error :form="form" field="bio"></has-error>
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
                users:{},
                sl:0,
                form: new Form({
                    id: '',
                    name: '',
                    email: '',
                    password: '',
                    type: '',
                    bio: '',
                    photo: '',
                })
            }
        },
        methods: {
            print(){
                window.print()
            },
            // Our method to GET results from a Laravel endpoint
            getResults(page = 1) {
                axios.get('api/user?page=' + page)
                    .then(response => {
                        this.users = response.data;
                    });
            },
            udpateUser(){
                this.$Progress.start()
                //Server request
                this.form.put('api/user/'+this.form.id)
                .then(() => {
                    Fire.$emit('AfterCreated')
                    $('#addNewModal').modal('hide')
                    toast.fire({
                        icon: 'success',
                        title: 'User updated successfully'
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
            deleteUser(id){
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
                        this.form.delete('api/user/'+id)
                        .then(()=>{
                            Fire.$emit('AfterDeleted')                            
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
            addNewUserModalOpen(){                
                this.editMode = false;
                this.form.reset()
                $('#addNewModal').modal('show')
            },
            editUser(user){
                this.editMode = true;
                this.form.reset()
                $('#addNewModal').modal('show')
                this.form.fill(user)
            },
            createUser(){                
                this.$Progress.start()
                this.form.post('api/user')
                .then(()=>{
                    Fire.$emit('AfterCreated')
                    $('#addNewModal').modal('hide')
                    toast.fire({
                        icon: 'success',
                        title: 'User Created successfully'
                    })
                    this.$Progress.finish()
                    this.form.reset()
                })
                .catch(()=>{

                })
                
            },
            loadUsers(){
                if(this.$gate.isAdminOrAuthorOrSuper()){
                    axios.get('api/user').then(({ data }) => { this.users = data })
                }
            }
        },
        created() {
            Fire.$on("searching", ()=> {
                let query = this.$parent.search;
                axios.get('api/findUser?q='+ query)
                .then((data)=>{
                    this.users = data.data;
                })
                .catch(()=>{

                })
            });
            this.loadUsers();
            //setInterval(() => { this.loadUsers(); }, 500);
            Fire.$on('AfterCreated', ()=> {
                this.loadUsers();
            });
            Fire.$on('AfterDeleted', ()=> {
                this.loadUsers();
            });
        },
        mounted() {
            console.log('Component mounted.')
            // Fetch initial results
            //this.getResults();
        }
    }
</script>
