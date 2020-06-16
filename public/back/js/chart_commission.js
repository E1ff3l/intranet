var config_commission = {
    type:                       'line',
    data: {
        labels:                 MONTHS,
        datasets: [{
            label:              'Comm. fixe',
            borderColor:        window.chartColors.bleu_v1_bord,
            backgroundColor:    window.chartColors.bleu_v1,
            data:               comm_variable_periode
        },
        {
            label:              'Comm. variable',
            borderColor:        window.chartColors.rouge_v1_bord,
            backgroundColor:    window.chartColors.rouge_v1,
            data:               comm_variable_fixe
        }]
    },
    options: {
        responsive:             true,
        title: {
            display:            false
        },
        tooltips: {
            mode:               'index',
        },
        hover: {
            mode:               'index'
        },
        scales: {
            xAxes: [{
                scaleLabel: {
                    display:    true,
                    labelString: 'Mois'
                }
            }],
            yAxes: [{
                stacked:        true,
                scaleLabel: {
                    display:    true,
                    labelString: 'Valeur en €'
                }
            }]
        }
    }
};