<template>
    <main class="main">
        <!-- Breadcrumb-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>

        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Proveedores
                        <button type="button" @click="openModal('provider','new')" class="btn btn-secondary">
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
                                <input type="text" v-model="search" @keyup.enter="getProviders(1,search,criteria)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit"  @click="getProviders(1,search,criteria)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
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
                                <th>Contacto</th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="provider in arrayProviders" :key="provider.id">
                                <td>
                                    <button type="button" @click="openModal('provider','update', provider)" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                    </button>
                                </td>
                                <td v-text="provider.name"></td>
                                <td v-text="provider.document_type"></td>
                                <td v-text="provider.document_number"></td>
                                <td v-text="provider.address"></td>
                                <td v-text="provider.phone"></td>
                                <td v-text="provider.email"></td>
                                <td v-text="provider.contact"></td>
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
                                    <input type="text" v-model="name" class="form-control" placeholder="Nombre del Cliente">
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
                                    <input type="text" v-model="document_number" class="form-control" placeholder="Documento del Cliente">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Dirección</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="address" class="form-control" placeholder="Dirección del Cliente">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Teléfono</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="phone" class="form-control" placeholder="Teléfono del Cliente">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Email</label>
                                <div class="col-md-9">
                                    <input type="email" v-model="email" class="form-control" placeholder="Email del Cliente">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Contacto</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="contact" class="form-control" placeholder="Nombre de Contacto">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Télefono de Contacto</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="contact_phone" class="form-control" placeholder="Teléfono de Contacto">
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
                contact: '',
                contact_phone: '',
                arrayProviders: [],
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
            getProviders(page, search, criteria){
                 let me = this;
                 let url = '/proveedor?page=' + page + '&search='+ search + '&criteria=' + criteria;
                axios.get(url).then(function (response) {
                    //console.log(response.data.products.data);
                    let result = response.data;
                    me.arrayProviders = result.providers.data;
                    me.pagination = result.pagination;
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
                me.getProviders(page, search, criteria);

            },
            save(){
                if(this.validate()){
                    return;
                }

                let me = this;

                axios.post('/proveedor', {
                    'name': this.name,
                    'document_type': this.document_type,
                    'document_number': this.document_number,
                    'address': this.address,
                    'phone': this.phone,
                    'email': this.email,
                    'contact': this.contact,
                    'contact_phone': this.contact_phone,
                }).then( function (response){
                    me.closeModal();
                    me.getProviders(1,'','name');
                }).catch( function (error){
                    console.log(error);
                })
            },
            update(){

                if(this.validate()){
                    return;
                }

                let me = this;
               
                axios.put('/proveedor/' + this.id, {
                    'name': this.name,
                    'document_type': this.document_type,
                    'document_number': this.document_number,
                    'address': this.address,
                    'phone': this.phone,
                    'email': this.email,
                    'contact': this.contact,
                    'contact_phone': this.contact_phone,
                }).then( function (response){
                    me.closeModal();
                    me.getProviders(1,'','name');
                }).catch( function (error){
                    console.log(error);
                })
            },
            validate(){
                this.error = false;
                this.errorMessage =  [];

                if(!this.name) this.errorMessage.push("El Campo nombre es obligatorio.");

                if(this.errorMessage.length) this.error = true;

                return this.error;
            },
            openModal(model, action, data = []){
                switch(model){
                    case "provider":
                    {
                        switch(action){
                            case "new":
                            {
                                this.modal = true;
                                this.titleModal = "Registrar Proveedor";
                                this.id = 0;
                                this.name = '';
                                this.document_type = 'RUC';
                                this.document_number = '';
                                this.address = '';
                                this.phone = '';
                                this.email = '';
                                this.contact = '';
                                this.contact_phone = '';
                                this.actionType = 1;
                                break;
                            }
                            case "update":
                            {
                                this.modal = true;
                                this.titleModal = "Actualizar Cliente";
                                this.id = data['id'];
                                this.name = data['name'];
                                this.document_type = data['document_type'];
                                this.document_number = data['document_number'];
                                this.address = data['address'];
                                this.phone = data['phone'];
                                this.email = data['email'];
                                this.contact = data['contact'];
                                this.contact_phone = data['contact_phone'];
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
            }
        },
        mounted() {
            this.getProviders(1,this.search,this.criteria);
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