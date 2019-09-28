<template>
    <main class="main">
        <!-- Breadcrumb-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>

        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Categorías
                        <button type="button" @click="openModal('category','new')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criteria">
                                    <option value="name">Nombre</option>
                                    <option value="description">Descripción</option>
                                </select>
                                <input type="text" v-model="search" @keyup.enter="getCategories(1,search,criteria)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit"  @click="getCategories(1,search,criteria)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="category in arrayCategories" :key="category.id">
                                <td>
                                    <button type="button" @click="openModal('category','update', category)" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                    </button> &nbsp;
                                    <template v-if="category.deleted_at == null">
                                        <button type="button" class="btn btn-danger btn-sm" @click="deleteCategory(category.id)">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button type="button" class="btn btn-info btn-sm" @click="restore(category.id)">
                                            <i class="icon-check"></i>
                                        </button>
                                    </template>
                                </td>
                                <td v-text="category.name"></td>
                                <td v-text="category.description"></td>
                                <td>
                                <!-- <span v-if="category.deleted_at == null" class="badge badge-success">Activo</span> -->
                                <!-- <span v-else="category.deleted_at != null" class="badge badge-danger">Inactivo</span> -->
                                    <div v-if="category.deleted_at == null">
                                        <span class="badge badge-success"> Activo</span>
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-danger"> Inactivo</span>
                                    </div>
                                    
                                </td>
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
                                    <input type="text" v-model="name" class="form-control" placeholder="Nombre de categoría">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Descripción</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="description" class="form-control" placeholder="Ingrese descripción">
                                </div>
                            </div>
                            <div v-show="error" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMessaje" :key="error" v-text="error">

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
                id: '',
                name: '',
                description: '',
                arrayCategories: [],
                modal: false,
                titleModal: '',
                actionType: 0,
                error: false,
                errorMessaje: [],
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
           
            getCategories(page, search, criteria){
                 let me = this;
                 let url = '/categoria?page=' + page + '&search='+ search + '&criteria=' + criteria;
                axios.get(url).then(function (response) {
                    let result = response.data;
                    me.arrayCategories = result.categories.data;
                    me.pagination = result.pagination;
                })
                .catch( function (error) {
                    console.log(error);
                });
            },

            changePage(page, search, criteria) {
                let me = this;
                //Refres current page
                me.pagination.current_page = page;

                // Call methos getCategories
                me.getCategories(page, search, criteria);

            },
            save(){
                if(this.validate()){
                    return;
                }

                let me = this;

                axios.post('/categoria', {
                    'name': this.name,
                    'description': this.description,
                }).then( function (response){
                    me.closeModal();
                    me.getCategories(1,'','name');
                }).catch( function (error){
                    console.log(error);
                })
            },
            update(){

                if(this.validate()){
                    return;
                }

                let me = this;
               
                axios.put('/categoria/' + this.id, {
                    'name': this.name,
                    'description': this.description,
                }).then( function (response){
                    me.closeModal();
                    me.getCategories(1,'','name');
                }).catch( function (error){
                    console.log(error);
                })
            },
            deleteCategory(id){
                let me = this;
                swal({
                    title: 'Esta seguro de Desactivar esta categoria',
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
                        axios.delete('/categoria/' + id)
                        .then( function (response){
                            me.getCategories(1,'','name');
                            swal(
                                'Desactivado!',
                                'Categoria desactivado con exito.',
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
                    title: 'Esta seguro de Activar esta categoria',
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
                        axios.put('/categoria/activar/' + id)
                        .then( function (response){
                            me.getCategories(1,'','name');
                            swal(
                                'Activado!',
                                'Categoria activado con exito.',
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
            validate(){
                this.error = false;
                this.errorMessaje =  [];

                if(!this.name) this.errorMessaje.push("El Campo nombre es obligatorio.");

                if(this.errorMessaje.length) this.error = true;

                return this.error;
            },
            openModal(model, action, data = []){
                switch(model){
                    case "category":
                    {
                        switch(action){
                            case "new":
                            {
                                this.modal = true;
                                this.titleModal = "Registrar Categoría";
                                this.name = "";
                                this.description = "";
                                this.actionType = 1;
                                break;
                            }
                            case "update":
                            {
                                this.modal = true;
                                this.titleModal = "Actualizar Categoría";
                                this.id = data['id'];
                                this.name = data['name'];
                                this.description = data['description'];
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
                this.nombre = "";
                this.description = "";
                this.actionType = 0;
            }
        },
        mounted() {
            this.getCategories(1,this.search,this.criteria);
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