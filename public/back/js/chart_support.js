var config_support = {
    type: 'line',
    data: {
        labels: MONTHS,
        datasets: [
            {
                label: 'Autre',
                borderColor: window.chartColors.gris,
                backgroundColor: window.chartColors.gris,
                data: support_autre
            },
            {
            label: 'RM',
            borderColor: window.chartColors.orange,
            backgroundColor: window.chartColors.orange,
            data: support_rm
        },
        {
            label: 'Smart',
            borderColor: window.chartColors.rouge_v1_bord,
            backgroundColor: window.chartColors.rouge_v1,
            data: support_smart
        },
        {
            label: 'Vit',
            borderColor: window.chartColors.bleu_v1_bord,
            backgroundColor: window.chartColors.bleu_v1,
            data: support_vit
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