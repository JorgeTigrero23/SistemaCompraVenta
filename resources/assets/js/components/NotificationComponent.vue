<template>
    <li class="nav-item d-md-down-none">
        <a class="nav-link" href="#" data-toggle="dropdown">
            <i class="icon-bell"></i>
            <span class="badge badge-pill badge-danger">{{ notifications.length }}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            <div class="dropdown-header text-center">
                <strong>Notificaciones</strong>
            </div>
            <div v-if="notifications.length">
                <li v-for="item in list" :key="item.id">
                    <a class="dropdown-item" href="#">
                        <i class="fa fa-envelope-o"></i> {{ item.purchases.msj }}
                        <span class="badge badge-success">{{ item.purchases.number }}</span>
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="fa fa-tasks"></i> {{ item.sales.msj }}
                        <span class="badge badge-danger">{{ item.sales.number }}</span>
                    </a>
                </li>
            </div>
            <div v-else>
                <a><span> No tiene notificaciones</span></a>
            </div>
        </div>
    </li>
</template>
<script>

export default{

    props : ['notifications'],
    data (){
        return {
            arrayNotifications : []
        }
    },
    computed: {
        list: function() {
            //return this.notifications[0];
            
            if (this.notifications == ''){
                return this.arrayNotifications = [];
            }else{
                //Capturo la ultima notificacion agregada
                this.arrayNotifications = Object.values(this.notifications[0]);
                //Validacion por indice fuea de rango
                if (this.arrayNotifications.length > 3) {
                    //Si el tamano es mayor a 3 Es cuando las notificaciones son obtenidas a traves de axios
                    return Object.values(this.arrayNotifications[4]);
                }else{
                    //Si el tamano es menor a 3 Es cuando las notificaciones son obtenidas desde echo y laravrel pusher
                    return Object.values(this.arrayNotifications[0]);
                }
               
            }
        }
    }

}
</script>