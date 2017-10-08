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
            options: null
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
                console.log(this);
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
                return axios.post(this.url, this.options);
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