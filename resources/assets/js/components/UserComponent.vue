<template>
    <main class="main">
        <!-- Breadcrumb-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>

        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Usuarios
                        <button type="button" @click="openModal('user','new')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criteria">
                                    <option value="name">Nombre</option>
                                    <option value="document_number">Documento</option>
                                    <option value="email">Email</option>
                                    <option value="phone">Teléfono</option>
                                </select>
                                <input type="text" v-model="search" @keyup.enter="getUsers(1,search,criteria)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit"  @click="getUsers(1,search,criteria)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Nombre</th>
                                <th>Tipo de Documento</th>
                                <th>Número de Documento</th>
                                <th>Dirección</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                                <th>Usuario</th>
                                <th>Rol</th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in arrayUsers" :key="user.id">
                                <td>
                                    <button type="button" @click="openModal('user','update', user)" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                    </button>&nbsp;
                                    <template v-if="user.deleted_at == null">
                                        <button type="button" class="btn btn-danger btn-sm" @click="inactive(user.id)">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button type="button" class="btn btn-info btn-sm" @click="restore(user.id)">
                                            <i class="icon-check"></i>
                                        </button>
                                    </template>
                                </td>
                                <td v-text="user.name"></td>
                                <td v-text="user.document_type"></td>
                                <td v-text="user.document_number"></td>
                                <td v-text="user.address"></td>
                                <td v-text="user.phone"></td>
                                <td v-text="user.email"></td>
                                <td v-text="user.username"></td>
                                <td v-text="user.rol"></td>
                            </tr>
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page - 1, search, criteria)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="changePage(page, search, criteria)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page" >
                                <a class="page-link" href="#" @click.prevent="changePage( pagination.current_page + 1, search, criteria)">Sig</a>
                            </li>
                        </ul>
                    </nav>

                </div>

            </div>
        </div>

        <!--Modal New/Updated-->
        <div class="modal fade" tabindex="-1" :class="{'show-modal' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="titleModal"></h4>
                        <button type="button" class="close" @click="closeModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="name" class="form-control" placeholder="Nombre del Usuario">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input"> Tipo de Documento</label>
                                <div class="col-md-9">
                                    <select v-model="document_type" class="form-control">
                                        <option value="DNI">DNI</option>
                                        <option value="RUC">RUC</option>
                                        <option value="PASSPORT">PASAPORTE</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Documento</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="document_number" class="form-control" placeholder="Documento del Usuario">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Dirección</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="address" class="form-control" placeholder="Dirección del Usuario">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Teléfono</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="phone" class="form-control" placeholder="Teléfono del Usuario">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Email</label>
                                <div class="col-md-9">
                                    <input type="email" v-model="email" class="form-control" placeholder="Email del Usuario">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Rol (*)</label>
                                <div class="col-md-9">
                                    <select class="form-control" v-model="rol_id">
                                        <option value="0"> Seleccione un Rol</option>
                                        <option v-for="rol in arrayRoles" :key="rol.id" :value="rol.id" v-text="rol.name"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Usuario (*)</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="username" class="form-control" placeholder="Nombre de Usuario">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Contraseña (*)</label>
                                <div class="col-md-9">
                                    <input type="password" v-model="password" class="form-control" placeholder="Contraseña de usuario">
                                </div>
                            </div>
                            <div v-show="error" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMessage" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                        <button type="button" v-if="actionType==1" class="btn btn-primary" @click="save()">Guardar</button>
                        <button type="button" v-if="actionType==2" class="btn btn-primary" @click="update()">Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- End Modal New/Update -->

    </main>
</template>

<script>

    export default {
        data () {
            return {
                id: 0,
                name: '',
                document_type: 'DNI',
                document_number: '',
                address: '',
                phone: '',
                email: '',
                username: '',
                password: '',
                rol_id: 0,
                arrayUsers: [],
                arrayRoles: [],
                modal: false,
                titleModal: '',
                actionType: 0,
                error: false,
                errorMessage: [],
                pagination: {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criteria: 'name',
                search : ''
            }
        },
        computed : {
            isActived: function(){
                return this.pagination.current_page;
            },

            // Calcula los elementos de la paginacion
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
               
                var from = this.pagination.current_page -this.offset;
                if(from < 1) {
                    from = 1;
                }
               
                var to = from  + (this.offset * 2);
                if(to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }

                return pagesArray;
            }
        },
        methods: {    
            getUsers(page, search, criteria){
                 let me = this;
                 let url = '/user?page=' + page + '&search='+ search + '&criteria=' + criteria;
                axios.get(url).then(function (response) {
                    let result = response.data;
                    me.arrayUsers = result.users.data;
                    me.pagination = result.pagination;
                })
                .catch( function (error) {
                    console.log(error);
                });
            },
            selectRol(){
                 let me = this;
                 let url = '/rol/selectRol';
                axios.get(url).then(function (response) {
                    let result = response.data;
                    me.arrayRoles = result.roles;
                })
                .catch( function (error) {
                    console.log(error);
                });
            },
            changePage(page, search, criteria) {
                let me = this;
                //Refresh current page
                me.pagination.current_page = page;

                // Call methos getProducts
                me.getUsers(page, search, criteria);

            },
            save(){
                if(this.validate()){
                    return;
                }

                let me = this;

                axios.post('/user', {
                    'name': this.name,
                    'document_type': this.document_type,
                    'document_number': this.document_number,
                    'address': this.address,
                    'phone': this.phone,
                    'email': this.email,
                    'username': this.username,
                    'password': this.password,
                    'rol_id': this.rol_id,
                }).then( function (response){
                    me.closeModal();
                    me.getUsers(1,'','name');
                }).catch( function (error){
                    console.log(error);
                })
            },
            update(){

                if(this.validate()){
                    return;
                }

                let me = this;
               
                axios.put('/user/' + this.id, {
                    'name': this.name,
                    'document_type': this.document_type,
                    'document_number': this.document_number,
                    'address': this.address,
                    'phone': this.phone,
                    'email': this.email,
                    'username': this.username,
                    'password': this.password,
                    'rol_id': this.rol_id,
                }).then( function (response){
                    me.closeModal();
                    me.getUsers(1,'','name');
                }).catch( function (error){
                    console.log(error);
                })
            },
            validate(){
                this.error = false;
                this.errorMessage =  [];

                if(!this.name) this.errorMessage.push("El Campo nombre es obligatorio.");
                if(!this.username) this.errorMessage.push("El Campo Nombre de Usuario es obligatorio.");
                if(!this.password) this.errorMessage.push("El Campo Contraseña es obligatorio.");
                if(this.rol_id==0) this.errorMessage.push("El Campo Rol es obligatorio. Por favor seleccione un rol.");

                if(this.errorMessage.length) this.error = true;

                return this.error;
            },
            openModal(model, action, data = []){
                this.selectRol();
                switch(model){
                    case "user":
                    {
                        switch(action){
                            case "new":
                            {
                                this.modal = true;
                                this.titleModal = "Registrar Usuario";
                                this.id = 0;
                                this.name = '';
                                this.document_type = 'RUC';
                                this.document_number = '';
                                this.address = '';
                                this.phone = '';
                                this.email = '';
                                this.username = '';
                                this.password = '';
                                this.rol_id = 0;
                                this.actionType = 1;
                                break;
                            }
                            case "update":
                            {
                                this.modal = true;
                                this.titleModal = "Actualizar Usuario";
                                this.id = data['id'];
                                this.name = data['name'];
                                this.document_type = data['document_type'];
                                this.document_number = data['document_number'];
                                this.address = data['address'];
                                this.phone = data['phone'];
                                this.email = data['email'];
                                this.username =  data['username'];
                                this.password =  data['password'];
                                this.rol_id =  data['rol_id'];
                                this.actionType = 2;
                                break;
                            }
                        }
                    }
                }
            },
            closeModal(){
                this.modal=false;
                this.title = "";
                this.id = 0;
                this.name = '';
                this.document_type = 'DNI';
                this.document_number = '';
                this.address = '';
                this.phone = '';
                this.email = '';
                this.contact = '';
                this.contact_phone = '';
                this.error = false;
            },
            inactive(id){
                let me = this;
                swal({
                    title: 'Esta seguro de Desactivar esta usuario',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                    reverseButtons: true
                    }).then((result) => {
                    if (result.value) {
                        axios.delete('/user/' + id)
                        .then( function (response){
                            me.getUsers(1,'','name');
                            swal(
                                'Desactivado!',
                                'Usuario desactivado con exito.',
                                'success'
                            )
                        }).catch( function (error){
                            console.log(error);
                        })

                        
                    }else if (
                        // Read more about handling dismissals
                        result.dismiss === swal.DismissReason.cancel
                    ) {
                        swal(
                            'Cancelled',
                            'Your imaginary file is safe :)',
                            'error'
                        )
                    }
                })
            },
            restore(id){
                 let me = this;
                swal({
                    title: 'Esta seguro de Activar esta usuario',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                    reverseButtons: true
                    }).then((result) => {
                    if (result.value) {
                        axios.put('/user/activar/' + id)
                        .then( function (response){
                            me.getUsers(1,'','name');
                            swal(
                                'Activado!',
                                'Usuario activado con exito.',
                                'success'
                            )
                        }).catch( function (error){
                            console.log(error);
                        })

                        
                    }else if (
                        // Read more about handling dismissals
                        result.dismiss === swal.DismissReason.cancel
                    ) {
                        swal(
                            'Cancelled',
                            'Your imaginary file is safe :)',
                            'error'
                        )
                    }
                })
            },
        },
        mounted() {
            this.getUsers(1,this.search,this.criteria);
        }
        
    }
</script>
<style>
    .modal-content {
        width: 100% !important;
        position: absolute !important;
    }
    .show-modal {
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;

    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>