<template>
    <ul class="navbar-nav ml-auto">
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" @click="markRead()" data-toggle="dropdown" href="#">
                <i class="fas far fa-bell"></i>
                <span v-if="notifications.length" class="badge badge-warning">{{ notifications.length }}</span>
            </a>
            <div v-if="notifications.length" class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">{{ notifications.length }} Notifications</span>
                <div v-for="notification in notifications">
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <p class="float-right text-muted text-sm"><i class="fas fa-envelope mr-2"></i> {{ notification.data.message }}</p>
                    </a>
                </div>
            </div>
        </li>
    </ul>
</template>

<script>
    export default {
        data() {
            return {
                notifications : [],
            }
        },
        methods: {
            loadNotification(){
                axios.get('/api/users/notifications').then(({data}) => {
                    this.notifications = data.data;
                });
            },
            markRead(){
                axios.get('/api/users/notifications/mark-as-read').then(({data}) => {

                });
            }
        },
        created() {
            this.loadNotification();
            setInterval(() => this.loadNotification(), 60000);
        }
    }
</script>
