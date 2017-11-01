<template>
    <section>
        <div class="row">
            <div class="col-sm-6 text-center">
                <h2>{{ getTotalOrders | numbers }}</h2>
                <h4>ORDERS</h4>
            </div>
            <div class="col-sm-6 text-center">
                <h2>{{ getTotalCustomers | numbers }}</h2>
                <h4>UNIQUE CUSTOMERS</h4>
            </div>
        </div>
        
        <hr>
        <div class="row">
            <div class="col-sm-4 text-center">
                <h3>$ {{ usTotalAmount | numbers }}</h3>
                <h5>Orders: {{ usTotalOrders | numbers }}</h5>
                <h5>Unique Customers: {{ usTotalCustomers | numbers }}</h5>
                <h5>US</h5>
            </div>
            <div class="col-sm-4 text-center">
                <h3>$ {{ caTotalAmount | numbers }}</h3>
                <h5>Orders: {{ caTotalOrders | numbers }}</h5>
                <h5>Unique Customers: {{ caTotalCustomers | numbers }}</h5>
                <h5>CA</h5>
            </div>
            <div class="col-sm-4 text-center">
                <h3>£ {{ ukTotalAmount | numbers }}</h3>
                <h5>Orders: {{ ukTotalOrders | numbers }}</h5>
                <h5>Unique Customers: {{ ukTotalCustomers | numbers }}</h5>
                <h5>UK</h5>
            </div>
        </div>
        <hr>
    </section>
</template>

<script>
    export default {
        data() {
            return {
                usTotalCustomers: 0,
                caTotalCustomers: 0,
                ukTotalCustomers: 0,
                usTotalAmount: 0,
                caTotalAmount: 0,
                ukTotalAmount: 0,
                usTotalOrders: 0,
                caTotalOrders: 0,
                ukTotalOrders: 0
            }
        },

        filters: {
            numbers(number) {
                return number.toLocaleString();
            }
        },

        computed: {
            getTotalOrders() {
                return this.usTotalOrders + this.caTotalOrders + this.ukTotalOrders;
            },

            getTotalCustomers() {
                return this.usTotalCustomers + this.caTotalCustomers + this.ukTotalCustomers;
            }
        },

        methods: {
            load() {
                axios.post('/stats/orders', {
                        amount: false
                    })
                    .then(response => {
                        this.usTotalOrders = +response.data.us;
                        this.caTotalOrders = +response.data.ca;
                        this.ukTotalOrders = +response.data.uk;
                    });

                axios.post('/stats/orders', {
                        amount: true
                    })
                    .then(response => {
                        this.usTotalAmount = +response.data.us;
                        this.caTotalAmount = +response.data.ca;
                        this.ukTotalAmount = +response.data.uk;
                    });

                axios.post('/stats/customers')
                    .then(response => {
                        this.usTotalCustomers = +response.data.us;
                        this.caTotalCustomers = +response.data.ca;
                        this.ukTotalCustomers = +response.data.uk;
                    });
            }
        },

        mounted() {
            this.load();
        }
    }
</script>
