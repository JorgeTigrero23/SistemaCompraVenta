<template>
    <main class="main">
        <!-- Breadcrumb-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item active">Dashboard</li>
          <!-- Breadcrumb Menu-->
        </ol>
        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="card-group mb-4">
              <div class="card">
                <div class="card-body">
                  <div class="h1 text-muted text-right mb-4">
                    <i class="icon-people"></i>
                  </div>
                  <div class="text-value" v-text="totalClients"></div>
                  <small class="text-muted text-uppercase font-weight-bold">Clientes</small>
                </div>
              </div>
              <div class="card">
                <div class="card-body">
                  <div class="h1 text-muted text-right mb-4">
                    <i class="icon-user-follow"></i>
                  </div>
                  <div class="text-value" v-text="totalUsers"></div>
                  <small class="text-muted text-uppercase font-weight-bold">Usuarios</small>
                </div>
              </div>
              <div class="card">
                <div class="card-body">
                  <div class="h1 text-muted text-right mb-4">
                    <i class="icon-basket-loaded"></i>
                  </div>
                  <div class="text-value" v-text="totalProducts"></div>
                  <small class="text-muted text-uppercase font-weight-bold">Productos</small>
                </div>
              </div>
              <div class="card">
                <div class="card-body">
                  <div class="h1 text-muted text-right mb-4">
                    <i class="icon-bag"></i>
                  </div>
                  <div class="text-value" v-text="totalPurchasesAll"></div>
                  <small class="text-muted text-uppercase font-weight-bold">Compras</small>
                </div>
              </div>
              <div class="card">
                <div class="card-body">
                  <div class="h1 text-muted text-right mb-4">
                    <i class="icon-basket"></i>
                  </div>
                  <div class="text-value" v-text="totalSalesAll"></div>
                  <small class="text-muted text-uppercase font-weight-bold">Ventas</small>
                </div>
              </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-char">
                        <div class="card-header">
                            <h1>Ingresos</h1>
                        </div>
                        <div class="card-content">
                            <div class="ct-chart">
                                <div class="ct-chart">
                                    <canvas id="purchases"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <p>Compras de los ultimos meses.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-char">
                        <div class="card-header">
                            <h1>Ventas</h1>
                        </div>
                        <div class="card-content">
                            <div class="ct-chart">
                                <div class="ct-chart">
                                    <canvas id="sales"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <p>Ventas de los ultimos meses.</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row-->
          </div>
        </div>
      </main>
</template>

<script>
    export default {
        data (){
            return {
                purchase : null,
                chartPurchase :  null,
                purchases : [],
                totalPurchases : [],
                mountPurchases : [],
                sale : null,
                chartSale:  null,
                sales : [],
                totalSales : [],
                mountSales : [],
                totalUsers : 0,
                totalClients : 0,
                totalProducts : 0,
                totalSalesAll : 0,
                totalPurchasesAll : 0,
            }
        },
        methods: {
            getDashboard(){
                let me = this;
                let url = '/dashboard';
                axios.get(url).then(function (response) {
                    let result = response.data;
                    me.purchases = result.purchases;
                    me.sales = result.sales;
                    me.totalUsers = result.total_records_users;
                    me.totalClients = result.total_records_clients;
                    me.totalProducts = result.total_records_products;
                    me.totalSalesAll = result.total_records_sales;
                    me.totalPurchasesAll = result.total_records_purchases;

                    //cargamos los datos del chart
                    me.loadPurchases();
                    me.loadSales();
                }).catch(function (error) {
                    console.log(error);
                })
            },
            loadPurchases(){
                let me= this;
                me.purchases.map(function(x) {
                    me.mountPurchases.push(x.mes);
                    me.totalPurchases.push(x.total);
                });

                me.purchase = document.getElementById('purchases').getContext('2d');

                me.chartPurchase = new Chart(me.purchase, {
                    type: 'bar',
                    data: {
                        labels: me.mountPurchase,
                        datasets: [{
                            label: 'Compras',
                            data: me.totalPurchases,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255,99,132,1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });
            },
            loadSales(){
                let me= this;
                me.sales.map(function(x) {
                    me.mountSales.push(x.mes);
                    me.totalSales.push(x.total);
                });

                me.sale = document.getElementById('sales').getContext('2d');

                me.chartSale = new Chart(me.sale, {
                    type: 'bar',
                    data: {
                        labels: me.mountSales,
                        datasets: [{
                            label: 'Compras',
                            data: me.totalSales,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54,162,235,0.2)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });
            }

        },
        mounted() {
            this.getDashboard();
        },
    }
</script>