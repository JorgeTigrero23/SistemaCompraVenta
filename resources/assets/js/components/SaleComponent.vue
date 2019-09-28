<template>
    <main class="main">
        <!-- Breadcrumb-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>

        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Ventas
                        <button type="button" @click="showDetail()" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                </div>
                <!--Table Purchases -->
                <template v-if="showTemplate==1">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criteria">
                                        <option value="voucher_type">Tipo de Comprobante</option>
                                        <option value="voucher_number">Número Comprobante</option>
                                        <option value="date">Fecha-Hora</option>
                                    </select>
                                    <input type="text" v-model="search" @keyup.enter="getSales(1,search,criteria)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit"  @click="getSales(1,search,criteria)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Usuario</th>
                                        <th>Cliente</th>
                                        <th>Tipo de Comprobante</th>
                                        <th>Serie de Comprobante</th>
                                        <th>Número de Comprobante</th>
                                        <th>Fecha Hora</th>
                                        <th>Total</th>
                                        <th>Impuesto</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="sale in arraySales" :key="sale.id">
                                        <td>
                                            <button type="button" @click="showSale(sale.id)" class="btn btn-success btn-sm">
                                                <i class="icon-eye"></i>
                                            </button>&nbsp;
                                            <button type="button" @click="showPDF(sale.id)" class="btn btn-info btn-sm">
                                                <i class="icon-doc"></i>
                                            </button>&nbsp;
                                            <template v-if="sale.status == 'Registrado'">
                                                <button type="button" class="btn btn-danger btn-sm" @click="inactive(sale.id)">
                                                    <i class="icon-trash"></i>
                                                </button>
                                            </template>
                                        </td>
                                        <td v-text="sale.username"></td>
                                        <td v-text="sale.name"></td>
                                        <td v-text="sale.voucher_type"></td>
                                        <td v-text="sale.voucher_serie"></td>
                                        <td v-text="sale.voucher_number"></td>
                                        <td v-text="sale.date"></td>
                                        <td v-text="sale.total"></td>
                                        <td v-text="sale.tax"></td>
                                        <td v-text="sale.status"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
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
                </template>
                <!--End Table Purchase -->
                <!-- Form Purchase -->
                <template v-else-if="showTemplate==2">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Cliente(*)</label>
                                    <v-select 
                                        :on-search="selectClient"
                                        label="name" 
                                        :options="arrayClient"
                                        placeholder="Buscar Cliente"
                                        :onChange="getDataClient">

                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Impuesto(*)</label>
                                    <input type="text" class="form-control" v-model="tax">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo de Comprobante(*)</label>
                                    <select class="form-control" v-model="voucher_type">
                                        <option value="0">Seleccione</option>
                                        <option value="BOLETA">Boleta</option>
                                        <option value="FACTURA">Factura</option>
                                        <option value="TICKET">Ticket</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Serie de Comprobante</label>
                                    <input type="text" class="form-control" v-model="voucher_serie" placeholder="000x">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Número de Comprobante(*)</label>
                                    <input type="text" class="form-control" v-model="voucher_number" placeholder="0000xx">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div v-show="error" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMessage" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="col-md-4">
                                <label>Artículo <span style="color:red;" v-show="product_id==0">(*Seleccione)</span></label>
                                <div class="form-inline">
                                    <input type="text" class="form-control" v-model="barcode" @keyup.enter="searchProduct()" placeholder="Ingrese Artículo">
                                    <button class="btn btn-primary" @click="openModal()">...</button>
                                    <input type="text" class="form-control" v-model="product" readonly>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Precio <span style="color:red;" v-show="price==0">(*Ingrese)</span></label>
                                    <input type="number" class="form-control" value="0" v-model="price" step="any">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Cantidad <span style="color:red;" v-show="quantity==0">(*Ingrese)</span></label>
                                    <input type="number" class="form-control" value="0" v-model="quantity">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Descuento</label>
                                    <input type="number" class="form-control" value="0" v-model="discount">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button class="btn btn-success form-control btnAdd" @click="addDetails()"><i class="icon-plus"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <th>Opciones</th>
                                        <th>Artículo</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th>Descuento</th>
                                        <th>Subtotal</th>
                                    </thead>
                                    <tbody v-if="arrayDetails.length">
                                        <tr v-for="(detail, index) in arrayDetails" :key="detail.id">
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm" @click="removeDetail(index)">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td v-text="detail.product"></td>
                                            <td> 
                                                <input v-model="detail.price" type="number" class="form-control">
                                            </td>
                                            <td>
                                                <span style="color:red;" v-show="detail.quantity > detail.stock">Stok: {{ detail.stock}}</span>
                                                <input v-model="detail.quantity" type="number" class="form-control">
                                            </td>
                                            <td>
                                                <span style="color:red;" v-show="detail.discount > (detail.price * detail.quantity)">Descuento Superior</span>
                                                <input v-model="detail.discount" type="number" class="form-control">
                                            </td>
                                            <td>
                                                {{ (detail.price * detail.quantity - detail.discount).toFixed(2) }}
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="5" align="right"><strong>Subtotal: </strong></td>
                                            <td>$ {{ subtotal = (total-totalTax).toFixed(2) }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="5" align="right"><strong>Igv: </strong></td>
                                            <td>$ {{ totalTax = ((total*tax)/(1+tax)).toFixed(2) }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="5" align="right"><strong>Total: </strong></td>
                                            <td>$ {{ total=(calculate).toFixed(2) }}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                        <td colspan="6">No hay articulos agragados</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-secundary" @click="hiddenDetail()">Cerrar</button>
                                <button type="button" class="btn btn-primary" @click="save()">Registrar Venta</button>
                            </div>
                        </div>
                    </div>
                </template>
                <!--End Form Purchase -->
                 <!-- Show Purchase -->
                <template v-else-if="showTemplate==3">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for=""><strong>Cliente</strong></label>
                                    <p v-text="client"></p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for=""><strong>Impuesto</strong></label>
                                    <p v-text="tax"></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><strong>Tipo de Comprobante</strong></label>
                                    <p v-text="voucher_type"></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><strong>Serie de Comprobante</strong></label>
                                    <p v-text="voucher_serie"></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><strong>Número de Comprobante</strong></label>
                                    <p v-text="voucher_number"></p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div v-show="error" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMessage" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <th>Artículo</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th>Descuento</th>
                                        <th>Subtotal</th>
                                    </thead>
                                    <tbody v-if="arrayDetails.length">
                                        <tr v-for="detail in arrayDetails" :key="detail.id">
                                            <td v-text="detail.product"></td>
                                            <td v-text="detail.price"></td>
                                            <td v-text="detail.quantity"></td>
                                            <td v-text="detail.discount"></td>
                                            <td> 
                                                {{ (detail.price * detail.quantity - detail.discount).toFixed(2) }}
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Subtotal: </strong></td>
                                            <td>$ {{ subtotal = (total-totalTax).toFixed(2) }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Igv: </strong></td>
                                            <td>$ {{ totalTax = (total*tax).toFixed(2) }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total: </strong></td>
                                            <td>$ {{ total }}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                        <td colspan="5">No hay articulos agragados</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-secundary" @click="hiddenDetail()">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </template>
                <!--End Show Purchase -->
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
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criteriaP">
                                        <option value="name">Nombre</option>
                                        <option value="description">Descripción</option>
                                        <option value="barcode">Código de Barra</option>
                                    </select>
                                    <input type="text" v-model="searchP" @keyup.enter="listProducts(searchP,criteriaP)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit"  @click="listProducts(searchP,criteriaP)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Barcode</th>
                                        <th>Nombre</th>
                                        <th>Categoría</th>
                                        <th>Precio Venta</th>
                                        <th>Stock</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="product in arrayProducts" :key="product.id">
                                        <td>
                                            <button type="button" @click="addDetailsModal(product)" class="btn btn-warning btn-sm">
                                                <i class="icon-check"></i>
                                            </button>
                                        </td>
                                        <td v-text="product.barcode"></td>
                                        <td v-text="product.name"></td>
                                        <td v-text="product.category"></td>
                                        <td v-text="product.price_sale"></td>
                                        <td v-text="product.stock"></td>
                                        <td>
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
                        </div>
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

    import vSelect from 'vue-select'

    export default {
        data () {
            return {
                id: 0,
                client_id:0,
                client: '',
                voucher_type: 'BOLETA',
                voucher_serie:'',
                voucher_number: '',
                tax: 0.12,
                total: 0.0,
                discount: 0.0,
                subtotal: 0.0,
                totalTax: 0.0,
                arraySales: [],
                arrayDetails: [],
                arrayClient: [],
                showTemplate: 1,
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
                criteria: 'voucher_number',
                search : '',
                arrayProducts: [],
                product_id: 0,
                barcode: '',
                product: '',
                price: '',
                quantity:'',
                criteriaP: 'name',
                searchP: '',
                stock: 0,
            }
        },
        components: {
            vSelect
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
            },

            calculate: function(){
                var result = 0.0;

                for(let i=0; i<this.arrayDetails.length; i++){
                    result = result + (this.arrayDetails[i].price * this.arrayDetails[i].quantity - this.arrayDetails[i].discount);
                }

                return result;
            }


        },
        methods: {    
            getSales(page, search, criteria){
                let me = this;
                let url = '/venta?page=' + page + '&search='+ search + '&criteria=' + criteria;
                axios.get(url).then(function (response) {
                    let result = response.data;
                    me.arraySales = result.sales.data;
                    me.pagination = result.pagination;
                })
                .catch( function (error) {
                    console.log(error);
                });
            },
            selectClient(search, loading){
                let me = this;
                loading(true)

                let url = '/cliente/selectCliente?filter=' + search;
                axios.get(url).then(function (response) {
                    let result = response.data;
                    me.arrayClient = result.clients;
                    loading(false)
                })
                .catch( function (error) {
                    console.log(error);
                });
            },
            getDataClient(value){
                let me = this;

                me.loading = true;
                me.client_id = value.id;
            },
            searchProduct(){
                let me = this;
                let url = 'articulo/buscarArticuloVenta?filter=' + me.barcode;

                axios.get(url).then(function (response) {
                    let result = response.data;
                    me.arrayProducts = result.products;
                    
                    if (me.arrayProducts.length > 0){
                        me.product = me.arrayProducts[0]['name'];
                        me.product_id = me.arrayProducts[0]['id'];
                        me.price = me.arrayProducts[0]['price'];
                        me.stock = me.arrayProducts[0]['stock'];
                    }else{
                        me.product = 'No existe producto';
                        me.product_id = 0;
                    }

                })
                .catch( function (error) {
                    console.log(error);
                });
            },
            showPDF(id){
                var base_url = window.location.origin;
                window.open(base_url + '/venta/comprobante/' + id +',' + '_blank');
            },
            find(id){
                let sw = false;
                for(let i=0; i<this.arrayDetails.length; i++){
                    if(this.arrayDetails[i].product_id==id){
                        sw = true;
                    }
                }

                return sw;
            },
            addDetails(){
                let me= this;

                if(me.product_id==0 || me.price==0 ||me.quantity==0) {

                }else{
                    if (me.find(me.product_id)){
                        swal({
                            type: 'error',
                            title: 'Error...',
                            text: 'Ese artículo ya se encuentra agregado',

                        })
                    }else{

                        if(me.quantity > me.stock){
                            swal({
                                type: 'error',
                                title: 'Error...',
                                text: 'No hay stock disponible.',
                            })
                        }else{
                            me.arrayDetails.push({
                                product_id: me.product_id,
                                product: me.product,
                                quantity: me.quantity,
                                price: me.price,
                                discount: me.discount,
                                stock: me.stock
                            });

                            me.barcode = "";
                            me.product_id = 0; 
                            me.product = "";
                            me.quantity = 0;
                            me.price = 0;
                            me.discount = 0;
                            me.stock = 0;
                        }

                    }
                }
            },
            addDetailsModal(data = []){
                let me = this;
                if (me.find(data['id'])){
                    swal({
                        type: 'error',
                        title: 'Error...',
                        text: 'Ese artículo ya se encuentra agregado',

                    })
                }else{
                    me.arrayDetails.push({
                        product_id: data['id'],
                        product: data['name'],
                        quantity:1,
                        price: data['price_sale'],
                        discount: 0,
                        stock: data['stock']
                    });
                }
            },
            listProducts(search, criteria){
                 let me = this;
                 let url = '/articulo/listaArticuloVenta?search='+ search + '&criteria=' + criteria;
                axios.get(url).then(function (response) {
                    let result = response.data;
                    me.arrayProducts = result.products.data;
                })
                .catch( function (error) {
                    console.log(error);
                });
            },
            removeDetail(index){
                let me = this;

                me.arrayDetails.splice(index,1);

            },
            changePage(page, search, criteria) {
                let me = this;
                //Refresh current page
                me.pagination.current_page = page;

                // Call methos getSales
                me.getSales(page, search, criteria);

            },
            save(){

                if(this.validate()){
                    return;
                }

                let me = this;

                axios.post('/venta', {
                    'client_id': this.client_id,
                    'voucher_type': this.voucher_type,
                    'voucher_serie': this.voucher_serie,
                    'voucher_number': this.voucher_number,
                    'tax': this.tax,
                    'total': this.total,
                    'details': this.arrayDetails
                }).then( function (response){
                    me.showTemplate = 1;
                    me.getSales(1,'','voucher_number');
                    me.hiddenDetail();
                    me.client_id = 0;
                    me.voucher_type = "BOLETA";
                    me.voucher_serie = '';
                    me.voucher_number = '';
                    me.tax = 0.12;
                    me.total = 0.0;
                    me.product_id = 0;
                    me.product = '';
                    me.quantity = 0;
                    me.price = 0;
                    me.arrayDetails = [];
                    me.searchP = '';
                    me.stock = 0;
                    me.barcode = '',
                    me.discount = 0;

                    var base_url = window.location.origin;
                    window.open(base_url + '/venta/comprobante/' + response.data.sale.id +',' + '_blank');

                }).catch( function (error){
                    console.log(error);
                })
            },
            validate(){
                let me = this;
                me.error = false;
                me.errorMessage =  [];

                me.arrayDetails.map(function(obj){

                    if( obj.quantity > obj.stock ){
                        me.errorMessage.push(obj.product + " con stock insuficiente.");
                    }
                });

                if(me.client_id==0) me.errorMessage.push("El Campo Cliente es obligatorio.  Por favor seleccione un Cliente.");
                if(me.voucher_type==0) me.errorMessage.push("El Campo Tipo de comprobante es obligatorio.  Por favor seleccione un Comprobante.");
                if(me.voucher_number==0) me.errorMessage.push("El Campo Número de comprobante es obligatorio.");
                if(me.tax==0) me.errorMessage.push("El CampoImpuesto es obligatorio.");
                if(me.arrayDetails<=0) me.errorMessage.push("Ingrese Detalle.");

                if(this.errorMessage.length) this.error = true;

                return this.error;
            },
            openModal(){
                this.arrayProducts = [];
                this.modal = true;
                this.titleModal = "Seleccione uno a varios artículos";
            },
            closeModal(){
                this.modal=false;
                this.title = "";
            },
            inactive(id){
                let me = this;
                    swal({
                        title: 'Esta seguro de Anular esta Venta',
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
                        axios.delete('/venta/' + id)
                        .then( function (response){
                            me.getSales(1,'','name');
                            swal(
                                'Anulado!',
                                'La venta anulada con exito.',
                                'success'
                            )
                        }).catch( function (error){
                            console.log(error);
                        })

                        
                    }else if (
                        // Read more about handling dismissals
                        result.dismiss === swal.DismissReason.cancel
                    ) {

                    }
                })
            },
            showDetail(){
                this.showTemplate = 2;
                this.client_id = 0;
                this.voucher_type = 'BOLETA';
                this.voucher_serie = '';
                this.voucher_number = '';
                this.tax = 0.12;
                this.total = 0.0;
                this.product_id = 0;
                this.product = '';
                this.quantity = 0;
                this.price = 0;
                this.arrayDetails = [];
                this.searchP = '';
            },
            hiddenDetail(){
                this.showTemplate = 1;
            },
            showSale(id){
                this.showTemplate = 3;
                let me = this;
                let url = '/venta/' + id;
                axios.get(url).then(function (response) {
                    let result = response.data;
                    me.client = result.sale.name;
                    me.voucher_type = result.sale.voucher_type;
                    me.voucher_serie = result.sale.voucher_serie;
                    me.voucher_number = result.sale.voucher_number;
                    me.tax = result.sale.tax;
                    me.total = result.sale.total;
                    me.arrayDetails = result.details;
                })
                .catch( function (error) {
                    console.log(error);
                });

            }
        },
        mounted() {
            this.getSales(1,this.search,this.criteria);
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

    @media (min-width: 600px) {
        .btnAdd {
            margin-top: 2rem;
        }
    }
</style>