var config_client = {
    type: 'line',
    data: {
        labels: MONTHS,
        datasets: [{
            label: 'Connecté',
            borderColor: window.chartColors.bleu_v1_bord,
            backgroundColor: window.chartColors.bleu_v1,
            data: client_connecte
        },
        {
            label: 'Express',
            borderColor: window.chartColors.rouge_v1_bord,
            backgroundColor: window.chartColors.rouge_v1,
            data: client_express
        }]
    },
    options: {
        responsive: true,
        title: {
            display: false
        },
        tooltips: {
            mode: 'index',
        },
        hover: {
            mode: 'index'
        },
        scales: {
            xAxes: [{
                scaleLabel: {
                    display: true,
                    labelString: 'Mois'
                }
            }],
            yAxes: [{
                stacked: true,
                scaleLabel: {
                    display: true,
                    labelString: 'Valeur en €'
                }
            }]
        }
    }
};