<template>
    <main class="main">
        <!-- Breadcrumb-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>

        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Artículos
                        <button type="button" @click="openModal('product','new')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                        <button type="button" @click="downloadPDF()" class="btn btn-info">
                            <i class="icon-plus"></i>&nbsp;Reporte
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
                                <input type="text" v-model="search" @keyup.enter="getProducts(1,search,criteria)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit"  @click="getProducts(1,search,criteria)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Barcode</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Categoría</th>
                                <th>Precio Venta</th>
                                <th>Stock</th>
                                <th>Stock Mínimo</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="product in arrayProducts" :key="product.id">
                                <td>
                                    <button type="button" @click="openModal('product','update', product)" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                    </button> &nbsp;
                                    <template v-if="product.deleted_at == null">
                                        <button type="button" class="btn btn-danger btn-sm" @click="deleteProduct(product.id)">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button type="button" class="btn btn-info btn-sm" @click="restore(product.id)">
                                            <i class="icon-check"></i>
                                        </button>
                                    </template>
                                </td>
                                <td v-text="product.barcode"></td>
                                <td v-text="product.name"></td>
                                <td v-text="product.description"></td>
                                <td v-text="product.category"></td>
                                <td v-text="product.price_sale"></td>
                                <td v-text="product.stock"></td>
                                <td v-text="product.stock_min"></td>
                                <td>
                                <!-- <span v-if="product.deleted_at == null" class="badge badge-success">Activo</span> -->
                                <!-- <span v-else="product.deleted_at != null" class="badge badge-danger">Inactivo</span> -->
                                    <div v-if="product.deleted_at == null">
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
                                <label class="col-md-3 form-control-label" for="text-input">Categoría</label>
                                <div class="col-md-9">
                                    <select class="form-control" v-model="category_id">
                                        <option value="0" disabled>Seleccione</option>
                                        <option v-for="category in arrayCategories" :key="category.id" :value="category.id" v-text="category.name"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Código de Barra</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="barcode" class="form-control" placeholder="Código de Barra">
                                    <barcode :value="barcode" :options="{format: 'EAN-13' }"> Generando código de barras.</barcode>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="name" class="form-control" placeholder="Nombre de Artículo">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Descripción</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="description" class="form-control" placeholder="Descripción de Artículo">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Precio</label>
                                <div class="col-md-9">
                                    <input type="number" v-model="price_sale" class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Stock</label>
                                <div class="col-md-9">
                                    <input type="number" v-model="stock" class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Stock Mínimo</label>
                                <div class="col-md-9">
                                    <input type="number" v-model="stock_min" class="form-control" placeholder="">
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

    import VueBarcode from 'vue-barcode';

    export default {
        data () {
            return {
                id: 0,
                category_id: 0,
                category: '',
                barcode: '',
                name: '',
                description: '',
                price_sale: 0,
                stock: 0,
                stock_min: 0,
                arrayProducts: [],
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
                search : '',
                arrayCategories : []
            }
        },
        components: {
            'barcode': VueBarcode
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
            getProducts(page, search, criteria){
                 let me = this;
                 let url = '/articulo?page=' + page + '&search='+ search + '&criteria=' + criteria;
                axios.get(url).then(function (response) {
                    //console.log(response.data.products.data);
                    let result = response.data;
                    me.arrayProducts = result.products.data;
                    me.pagination = result.pagination;
                })
                .catch( function (error) {
                    console.log(error);
                });
            },
            downloadPDF(){
                var base_url = window.location.origin;
                window.open(base_url + '/articulo/listaArticuloPdf','_blank');

            },
            selectCategory(){
                let me = this;
                let url = '/categoria/selectCategoria';
                axios.get(url).then(function (response) {
                    me.arrayCategories = response.data.categories;
                    //console.log(response);
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
                me.getProducts(page, search, criteria);

            },
            save(){
                if(this.validate()){
                    return;
                }

                let me = this;

                axios.post('/articulo', {
                    'barcode': this.barcode,
                    'category_id': this.category_id,
                    'name': this.name,
                    'description': this.description,
                    'price_sale': this.price_sale,
                    'stock': this.stock,
                    'stock_min': this.stock_min,
                }).then( function (response){
                    me.closeModal();
                    me.getProducts(1,'','name');
                }).catch( function (error){
                    console.log(error);
                })
            },
            update(){

                if(this.validate()){
                    return;
                }

                let me = this;
               
                axios.put('/articulo/' + this.id, {
                    'barcode': this.barcode,
                    'category_id': this.category_id,
                    'name': this.name,
                    'description': this.description,
                    'price_sale': this.price_sale,
                    'stock': this.stock,
                    'stock_min': this.stock_min,
                }).then( function (response){
                    me.closeModal();
                    me.getProducts(1,'','name');
                }).catch( function (error){
                    console.log(error);
                })
            },
            deleteProduct(id){
                let me = this;
                swal({
                    title: 'Esta seguro de Desactivar este artículo?',
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
                        axios.delete('/articulo/' + id)
                        .then( function (response){
                            me.getProducts(1,'','name');
                            swal(
                                'Desactivado!',
                                'Artículo desactivado con exito.',
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
                    title: 'Esta seguro de Activar este artículo?',
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
                        axios.put('/articulo/activar/' + id)
                        .then( function (response){
                            me.getProducts(1,'','name');
                            swal(
                                'Activado!',
                                'Artículo activado con exito.',
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

                if(this.category_id==0) this.errorMessaje.push("Seleccione una Categoría.");
                if(!this.name) this.errorMessaje.push("El Campo nombre es obligatorio.");
                if(!this.stock) this.errorMessaje.push("El stock del artículo debe ser un número y no debe estar vacío.");
                if(!this.stock_min) this.errorMessaje.push("El stock del artículo debe ser un número y no debe estar vacío.");
                if(!this.price_sale) this.errorMessaje.push("El precio del artículo debe ser un número.");

                if(this.errorMessaje.length) this.error = true;

                return this.error;
            },
            openModal(model, action, data = []){
                switch(model){
                    case "product":
                    {
                        switch(action){
                            case "new":
                            {
                                this.modal = true;
                                this.titleModal = "Registrar Artículo";
                                this.id = 0;
                                this.category_id = 0;
                                this.category = '';
                                this.barcode = '';
                                this.name = '';
                                this.description = '';
                                this.price_sale = 0;
                                this.stock = 0;
                                this.stock_min = 0;
                                this.actionType = 1;
                                break;
                            }
                            case "update":
                            {
                                this.modal = true;
                                this.titleModal = "Actualizar Artículo";
                                this.id = data['id'];
                                this.category_id = data['category_id'];
                                this.category = data['category'];
                                this.barcode = data['barcode'];
                                this.name = data['name'];
                                this.description = data['description'];
                                this.price_sale = data['price_sale'];
                                this.stock = data['stock'];
                                this.stock_min = data['stock_min'];
                                this.actionType = 2;
                                break;
                            }
                        }
                    }
                }
                this.selectCategory();
            },
            closeModal(){
                this.modal=false;
                this.title = "";
                this.id = 0;
                this.category_id = 0;
                this.category = '';
                this.barcode = '';
                this.name = '';
                this.description = '';
                this.price_sale = 0;
                this.stock = 0;
                this.stock_min = 0;
            }
        },
        mounted() {
            this.getProducts(1,this.search,this.criteria);
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