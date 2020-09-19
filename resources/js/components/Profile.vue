<template>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Profile</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><router-link to="/dashboard">Home</router-link></li>
              <li class="breadcrumb-item active">Profile</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">

              <div class="card-body">
                <div class="">


                  <div class="">
                    <div class="text-center">
                      <img class="profile-user-img img-fluid img-circle" :src="getProfilePhoto()" alt="User profile picture">
                    </div>

                    <h3 class="profile-username text-center">{{ this.form.first_name+" "+this.form.last_name}}</h3>

                    <p class="text-muted text-center">{{ this.form.type | capsText }}</p>


                    <form class="form-horizontal">
                      <div class="form-group row">
                        <label for="first_name" class="col-sm-2 col-form-label">First Name</label>
                        <div class="col-sm-10">
                          <input type="text" v-model="form.first_name" class="form-control" id="first_name" placeholder="first name" :class="{ 'is-invalid': form.errors.has('first_name') }">
                          <has-error :form="form" field="first_name"></has-error>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="last_name" class="col-sm-2 col-form-label">Last Name</label>
                        <div class="col-sm-10">
                          <input type="text" v-model="form.last_name" class="form-control" id="last_name" placeholder="first name" :class="{ 'is-invalid': form.errors.has('Last name') }">
                          <has-error :form="form" field="last_name"></has-error>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="last_name" class="col-sm-2 col-form-label">Skills</label>
                        <div class="col-sm-10">
                          <input type="text" v-model="profile_form.skills" class="form-control" id="skills" placeholder="skills" :class="{ 'is-invalid': profile_form.errors.has('skills') }">
                          <has-error :form="profile_form" field="skills"></has-error>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="last_name" class="col-sm-2 col-form-label">resume</label>
                        <div class="col-sm-10">
                          <input type="file" @change="previewProfilePhoto" class="form-control-file" id="inputProfilePhoto">

                          <input type="text" v-model="profile_form.resume" class="form-control" id="resume" placeholder="resume" :class="{ 'is-invalid': profile_form.errors.has('resume') }">
                          <has-error :form="profile_form" field="resume"></has-error>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                          <input type="email" v-model="form.email" class="form-control" id="inputEmail" placeholder="Email" :class="{ 'is-invalid': form.errors.has('email') }">
                          <has-error :form="form" field="email"></has-error>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="inputProfilePhoto" class="col-sm-2 col-form-label">Profile Photo</label>
                        <div class="col-sm-10">
                          <input type="file" @change="previewProfilePhoto" class="form-control-file" id="inputProfilePhoto">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputPassword" class="col-sm-2 col-form-label">Password (Leave empty if not changing)</label>
                        <div class="col-sm-10">
                          <input type="text" v-model="form.password" class="form-control" id="inputPassword" placeholder="Password" :class="{ 'is-invalid': form.errors.has('password') }">
                          <has-error :form="form" field="password"></has-error>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <button type="submit" @click.prevent="updateProfile" class="btn btn-primary">Update</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>

    
  </div>
  <!-- /.content-wrapper -->
</template>

<script>
    export default {
        data() {
            return {
               form: new Form({
                    id: '',
                    first_name: '',
                    last_name: '',
                    email: '',
                    password: '',
                    type: '',
                    photo: '',
                    skills: '',
                }),
               profile_form: new Form({
                    id: '',
                    user_id: '',
                    resume: '',
                    skills: '',
                }) 
            }
        },
        methods: {
            getProfilePhoto(){
               let photo = (this.form.photo.length > 200) ? this.form.photo : "img/profile/"+this.form.photo;
                return photo;
               
            },
            updateProfile(){
              this.$Progress.start()
                this.form.put("api/profile")
                    .then(()=>{                      
                      toast.fire({
                        icon: 'success',
                        title: 'Profile updated successfully'
                      })
                      this.$Progress.finish()
                    })
                    .catch(()=>{
                      this.$Progress.fail()
                    });

                this.profile_form.put("api/userprofile/"+this.profile_form.id)
                    .then(()=>{                      
                      toast.fire({
                        icon: 'success',
                        title: 'Profile updated successfully'
                      })
                      this.$Progress.finish()
                    })
                    .catch(()=>{
                      this.$Progress.fail()
                    });
                
            },
            previewProfilePhoto(e){
                let file = e.target.files[0];
                let reader = new FileReader();
                console.log(file);
                if(file['size'] < 2097152){
                  reader.onloadend = (file) => {
                      this.form.photo = reader.result;
                  }
                  reader.readAsDataURL(file);
                }else{
                  swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'File size more than 2MB!',
                  })
                }
            }
        },
        created() {
            axios.get('api/profile').then(({ data }) => { this.form.fill(data) });
            axios.get('api/userprofile').then(({ data }) => { this.profile_form.fill(data) });

        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
