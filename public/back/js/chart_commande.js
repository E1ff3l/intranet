var config_commande = {
    type: 'line',
    data: {
        labels: MONTHS,
        datasets: [{
            label: 'Midi',
            borderColor: window.chartColors.bleu_v1_bord,
            backgroundColor: window.chartColors.bleu_v1,
            data: commande_midi
        },
        {
            label: 'Soir',
            borderColor: window.chartColors.rouge_v1_bord,
            backgroundColor: window.chartColors.rouge_v1,
            data: commande_soir
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