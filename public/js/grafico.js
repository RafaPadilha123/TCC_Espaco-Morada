let chart = null;

document.addEventListener('DOMContentLoaded', () => {

    const btn = document.getElementById('btnGraficoKeywords');
    const modal = document.getElementById('graficoModal');
    const fechar = document.getElementById('fecharGrafico');

    if (!btn || !modal || !fechar) return;

    btn.addEventListener('click', () => {

        modal.style.display = 'flex';

        gerarGrafico();
    });

    fechar.addEventListener('click', () => {

        modal.style.display = 'none';
    });

    modal.addEventListener('click', (e) => {

        if (e.target === modal) {

            modal.style.display = 'none';
        }
    });
});

function gerarGrafico() {

    const contador = {};

    const sessoes = window.sessoesData || [];

    sessoes.forEach(sessao => {

        let palavras = [];

        try {

            palavras = sessao.palavras_chave
                ? JSON.parse(sessao.palavras_chave)
                : [];

        } catch {

            palavras = [];
        }

        palavras.forEach(p => {

            p = p.toLowerCase().trim();

            contador[p] =
                (contador[p] || 0) + 1;
        });
    });

    const labels = Object.keys(contador);

    const valores = Object.values(contador);

    const ctx =
        document.getElementById('keywordsChart');

    if (!ctx) return;

    if (chart) {

        chart.destroy();
    }

    chart = new Chart(ctx, {

        type: 'bar',

        data: {

            labels,

            datasets: [{

                label: 'Ocorrências',

                data: valores,

                borderRadius: 12,

                borderSkipped: false,

                backgroundColor: [

                    '#3fad8c',
                    '#4da3ff',
                    '#f39c12',
                    '#9b59b6',
                    '#e74c3c',
                    '#16a085',
                    '#ff6b81',
                    '#6c5ce7'
                ],

                hoverBackgroundColor: [

                    '#33a482',
                    '#3b82f6',
                    '#e67e22',
                    '#8e44ad',
                    '#c0392b',
                    '#138d75',
                    '#ff4d6d',
                    '#5f4dd0'
                ]
            }]
        },

        options: {

            responsive: true,

            maintainAspectRatio: true,

            plugins: {

                legend: {

                    display: false
                },

                title: {

                    display: true,

                    text: 'Gráfico de Indicadores',

                    color: '#1e293b',

                    font: {

                        size: 22,

                        weight: 'bold'
                    },

                    padding: {

                        bottom: 25
                    }
                },

                tooltip: {

                    backgroundColor: '#1e293b',

                    titleColor: '#ffffff',

                    bodyColor: '#ffffff',

                    padding: 12,

                    borderWidth: 0,

                    cornerRadius: 12
                }
            },

            scales: {

                y: {

                    beginAtZero: true,

                    ticks: {

                        stepSize: 1,

                        precision: 0,

                        color: '#64748b',

                        font: {

                            size: 13,

                            weight: '600'
                        }
                    },

                    grid: {

                        color: 'rgba(0,0,0,0.05)'
                    },

                    border: {

                        display: false
                    }
                },

                x: {

                    ticks: {

                        color: '#1e293b',

                        font: {

                            size: 13,

                            weight: '600'
                        }
                    },

                    grid: {

                        display: false
                    },

                    border: {

                        display: false
                    }
                }
            },

            animation: {

                duration: 1000,

                easing: 'easeOutQuart'
            }
        }
    });
}
