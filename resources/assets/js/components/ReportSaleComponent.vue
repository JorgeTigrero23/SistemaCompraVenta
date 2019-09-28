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
                                            </button>
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
                                                {{ detail.price * detail.quantity - detail.discount }}
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

    </main>
</template>

<script>

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
            showPDF(id){
                var base_url = window.location.origin;
                window.open(base_url + '/venta/comprobante/' + id +',' + '_blank');
            },
            changePage(page, search, criteria) {
                let me = this;
                //Refresh current page
                me.pagination.current_page = page;

                // Call methos getSales
                me.getSales(page, search, criteria);

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