var config_mode = {
    type: 'line',
    data: {
        labels: MONTHS,
        datasets: [{
            label: 'Livraison',
            borderColor: window.chartColors.bleu_v1_bord,
            backgroundColor: window.chartColors.bleu_v1,
            data: mode_livraison
        },
        {
            label: 'A Emporter',
            borderColor: window.chartColors.rouge_v1_bord,
            backgroundColor: window.chartColors.rouge_v1,
            data: mode_ae
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