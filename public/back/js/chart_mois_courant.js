
var config_mois_courant = {
    type:                       'doughnut',
    data: {
        datasets: [
            {
                data:           ca,
                backgroundColor: [
                    window.chartColors.orange,
                    window.chartColors.grey,
                    window.chartColors.blue,
                    window.chartColors.orange,
                    window.chartColors.grey,
                    window.chartColors.green,
                    window.chartColors.grey,
                ],
                label:          'CA Restomalin du mois en cours'
            },
            {
                data:           commission,
                backgroundColor: [
                    window.chartColors.grey,
                    window.chartColors.grey,
                    window.chartColors.blue,
                    window.chartColors.orange,
                    window.chartColors.grey,
                    window.chartColors.green,
                    window.chartColors.grey,
                ],
                label:          'Commission du mois en cours'
            },
            {
                data:           ma_comm,
                backgroundColor: [
                    window.chartColors.grey,
                    window.chartColors.grey,
                    window.chartColors.blue,
                    window.chartColors.orange,
                    window.chartColors.grey,
                    window.chartColors.green,
                    window.chartColors.grey,
                ],
                label:          'Commission du mois en cours'
            }
        ],
        labels: [
            'CA Réalisé',
            'CA Prévisionnel',
            'Fixe',
            'Réalisé',
            'Prévisionnel',
            '5%',
            'Prévisionnel'
        ]
    },
    options: {
        responsive:             true,
        legend: {
            position:           'right'
        },
        title: {
            display:            false
        },
        animation: {
            animateScale:       true,
            animateRotate:      true
        }
    }
};