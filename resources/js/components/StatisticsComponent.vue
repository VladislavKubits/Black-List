<template>
    <div class="container-fluid" id="statistics-component">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Статистика контактов</div>
                    <VueHotelDatepicker placeholder="Выберите временной промежуток" fromText="От" toText="До" resetText="Сброс" confirmText="Подтвердить"
                     :weekList="weekList" :monthList="monthList" minDate="0" :maxDate="`${getUSADate()}`" ref="dateRange" @confirm="changeDate()" />
                    <div class="card-body">
                        <canvas id="chLine"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import VueHotelDatepicker from '@northwalker/vue-hotel-datepicker';
    import axios from 'axios';
    import monthNames from '../monthNames';
    import store from '../store/index';

    export default {
        store,
        components: {
            VueHotelDatepicker
        },
        data: function() {
            return {
                weekList: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
                monthList: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
                date: new Date(),
                dateList: [],
                chart: null,
                chLine: null
            }
        },
        methods: {
            getUSADate() {
            return `${this.date.getFullYear()}-${("0" + Number(this.date.getMonth() + 1)).slice(-2)}-${("0" + this.date.getDate()).slice(-2)} 23:59:59`;
            },
            changeDate() {

                let result = [];

                let dayContactsCounts = null;

                let dateRange = this.$refs.dateRange.value.split(" ~ ");
                
                let startDate = new Date(dateRange[0]), endDate = new Date(dateRange[1]);

                while( startDate.getTime() <= endDate.getTime()) {
                    result.push( '' + startDate.getFullYear() +'-'+ this.pad(startDate.getMonth()+1) +'-'+ this.pad(startDate.getDate()));
                    startDate.setDate( startDate.getDate()+1);
                }

                axios
                    .post(`${store.state.url}/statistic`, result)
                    .then((response) => {
                        dayContactsCounts = response.data;

                        let chartData = {
                            labels: result,
                            datasets: [{
                                data: dayContactsCounts,
                                backgroundColor: 'transparent',
                                borderColor: '#008B8B',
                                borderWidth: 2,
                                pointBackgroundColor: '#008B8B'
                            }]
                        };

                        this.chart.destroy();
                        this.chart = new Chart(this.chLine, {
                            type: 'line',
                            data: chartData,
                            options: {
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: false
                                        }
                                    }]
                                },
                                legend: {
                                    display: false
                                }
                            }
                        });
                    });
                
                return result;
            },
            pad(s) {
                return ('00' + s).slice(-2);
            }
        },
        mounted() {
            // let chartData = {
            //     labels: ["S", "M", "T", "W", "T", "F", "S"],
            //     datasets: [{
            //         data: [589, 445, 483, 503, 689, 692, 634],
            //         backgroundColor: 'transparent',
            //         borderColor: '#008B8B',
            //         borderWidth: 2,
            //         pointBackgroundColor: '#008B8B'
            //     }]
            // };

            this.chLine = document.getElementById("chLine");
            // if (chLine) {
            this.chart = new Chart(this.chLine, {
                type: 'line',
                data: null,
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: false
                            }
                        }]
                    },
                    legend: {
                        display: false
                    }
                }
            });
            // }
        }
    }
</script>
