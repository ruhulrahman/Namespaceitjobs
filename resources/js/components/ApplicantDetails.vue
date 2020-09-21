<template>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Applicant Details</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><router-link to="/home">Home</router-link></li>
              <li class="breadcrumb-item active">Applicant Details</li>
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
                <h3 class="card-title green">Details</h3>

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
                             <div class="col-sm-12" >
                                <div  class="text-center">

                                 <img class="profile-user-img img-fluid img-circle" :src="getProfilePhoto(user.photo)" alt="User profile picture">

                                 <h2>Applicant Name: <span class="text-primary">{{ user.first_name+" "+user.last_name }}</span></h2>
                                 <br>
                                 <h2>Email: <span class="text-danger">{{ user.email }}</span></h2>
                                 <br>
 
                                </div>
                                <hr>
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
                user:{},
                show:true,
                sl:0,
            }
        },
        methods: {
            print(){
                window.print()
            },            
            getProfilePhoto(photo){
               let pt = "img/profile/"+photo;
                return pt;               
            },
            loadapplicants(){
                 axios.get('/api/user/'+this.$route.params.user_id)
                 .then(response => {
                        this.user = response.data;
                    });
            }
        },
        created() {
            Fire.$on("searching", ()=> {
                let query = this.$parent.search;
                axios.get('api/findapplicant?q='+ query)
                .then((data)=>{
                    this.applicants = data.data;
                })
                .catch(()=>{

                })
            });
            this.loadapplicants();
            //setInterval(() => { this.loadapplicants(); }, 500);
            Fire.$on('AfterCreated', ()=> {
                this.loadapplicants();
            });
            Fire.$on('AfterDeleted', ()=> {
                this.loadapplicants();
            });
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
