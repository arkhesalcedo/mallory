<template>
    <canvas ref="canvas"></canvas>
</template>

<script>
    import Chart from 'chart.js';

    export default {
        data() {
            return {
                keys: [],
                values: [],
                chartData: {
                    options: {
                        scales: {
                            xAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                }
            }
        },

        props: {
            url: {
                default: ''
            },

            type: {
                default: 'bar'
            },

            label: {
                default: 'Data'
            },

            color: {
                default: '54, 162, 235'
            },

            store: {
                default: 'US'
            }
        },

        methods: {
            load() {
                this.fetchData().then(response => {
                    this.keys = Object.keys(response.data);

                    this.values = Object.keys(response.data).map(key => response.data[key]);

                    this.build();
                })
            },

            build() {
                this.chartData.type = this.type;
                
                this.chartData.data = {
                    labels: this.keys,

                    datasets: [
                        {
                            label: this.label,
                            data: this.values,
                            backgroundColor: 'rgba(' + this.color + ', 0.5)',
                            borderColor: 'rgba(' + this.color + ', 1)',
                            borderWidth: 1
                        }
                    ]
                }

                this.render(this.chartData);
            },

            fetchData() {
                return axios.post(this.url, {
                    store: this.store
                });
            },

            render(chartData) {
                this.chart = new Chart(this.$refs.canvas, chartData);
            }
        },

        mounted() {
            this.load();
        }
    }
</script>