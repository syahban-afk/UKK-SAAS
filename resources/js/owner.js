import Chart from 'chart.js/auto';

function getDaisyUIColor(className, alpha = 1) {
    const tempDiv = document.createElement('div');
    tempDiv.className = `${className} invisible absolute`;
    document.body.appendChild(tempDiv);

    const color = getComputedStyle(tempDiv).backgroundColor;
    document.body.removeChild(tempDiv);

    if (color.startsWith('rgba')) {
        const [r, g, b] = color.match(/\d+/g).slice(0, 3);
        return `rgba(${r}, ${g}, ${b}, ${alpha})`;
    } else if (color.startsWith('rgb')) {
        const [r, g, b] = color.match(/\d+/g);
        return `rgba(${r}, ${g}, ${b}, ${alpha})`;
    }

    return color;
}

function getDaisyUITextColor(className) {
    const tempDiv = document.createElement('div');
    tempDiv.className = `${className} invisible absolute`;
    document.body.appendChild(tempDiv);

    const color = getComputedStyle(tempDiv).color;
    document.body.removeChild(tempDiv);

    return color || '#ffffff';
}

function getChartGradient(ctx) {
    const primaryColor = getDaisyUIColor('bg-primary');
    const secondaryColor = getDaisyUIColor('bg-secondary');
    const secondaryColorHalf = getDaisyUIColor('bg-secondary/50');
    const successColorHalf = getDaisyUIColor('bg-success/50');
    const warningColorHalf = getDaisyUIColor('bg-warning/50');
    const infoColorHalf = getDaisyUIColor('bg-info/50');
    const primaryColorHalf = getDaisyUIColor('bg-primary/50');
    const primaryColorFade = getDaisyUIColor('bg-primary/20');
    const textColor = getDaisyUITextColor('text-primary-content');

    // Buat gradient
    const gradient = ctx.createLinearGradient(0, 0, 0, 200);
    gradient.addColorStop(0, primaryColor.replace('1)'));
    gradient.addColorStop(1, primaryColorFade.replace('1)'));

    return {
        primaryHalf: primaryColorHalf,
        gradient: gradient,
        primaryColor: primaryColor,
        PrimaryText: textColor,
        secondaryColor: secondaryColor,
        secondaryColorHalf: secondaryColorHalf,
        successColorHalf: successColorHalf,
        warningColorHalf: warningColorHalf,
        infoColorHalf: infoColorHalf
    };
}

document.addEventListener('DOMContentLoaded', function () {
    const revenueChart = document.getElementById('revenueChart');
    if (!revenueChart) return;

    const ctx = revenueChart.getContext('2d');
    const chartColors = getChartGradient(ctx);

    const Mainchart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: (window.ownerData && window.ownerData.revenue && window.ownerData.revenue.labels) || ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Pendapatan',
                data: (window.ownerData && window.ownerData.revenue && window.ownerData.revenue.data) || [20, 15, 25, 40, 35, 30, 32],
                borderColor: chartColors.primaryHalf,
                backgroundColor: chartColors.gradient,
                fill: true,
                pointHoverRadius: 7,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animations: {
                tension: {
                    duration: 1000,
                    easing: 'linear',
                    from: 1,
                    to: 0,
                    loop: true
                },
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Pendapatan Bulanan',
                    color: chartColors.PrimaryText,
                    font: {
                        size: 18
                    }
                },
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: chartColors.primaryColor,
                    titleColor: chartColors.PrimaryText,
                    bodyColor: chartColors.PrimaryText,
                    borderWidth: 1,
                    padding: 12,
                    boxPadding: 6,
                    usePointStyle: true,
                    callbacks: {
                        label: function (context) {
                            return `Rp ${context.parsed.y} jt`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    ticks: {
                        color: chartColors.PrimaryText
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: chartColors.PrimaryText,
                        callback: function (val) {
                            return 'Rp ' + val + ' jt';
                        }
                    }
                }
            }
        }
    });

    const packageChart = document.getElementById('pkgChart');
    if (!packageChart) return;

    const pkgCtx = packageChart.getContext('2d');
    const pkgChartColors = getChartGradient(pkgCtx);

    const chartDataByTime = window.chartDataByTime || {
        '3hari': [5, 3, 4, 2, 3],
        'minggu': [18, 15, 16, 14, 12],
        'bulan': [60, 55, 58, 52, 50],
        'tahun': [320, 280, 300, 350, 290]
    };

    const pkgChart = new Chart(pkgCtx, {
        type: 'bar',
        data: {
            labels: (window.ownerData && window.ownerData.pkg && window.ownerData.pkg.labels) || ['Pernikahan', 'Selamatan', 'Ulang Tahun', 'Studytour', 'Rapat'],
            datasets: [{
                label: 'Jumlah Pesanan',
                data: chartDataByTime.tahun,
                backgroundColor: [
                    pkgChartColors.primaryHalf,
                    pkgChartColors.secondaryColorHalf,
                    pkgChartColors.successColorHalf,
                    pkgChartColors.warningColorHalf,
                    pkgChartColors.infoColorHalf
                ],
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Paket Terlaris',
                    color: pkgChartColors.PrimaryText,
                    font: {
                        size: 18
                    }
                },
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    ticks: {
                        color: pkgChartColors.PrimaryText
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: pkgChartColors.PrimaryText
                    }
                }
            }
        }
    });

    window.pkgChart = pkgChart;
    window.chartDataByTime = chartDataByTime;

    const summaryChart = document.getElementById('summaryChart');
    if (!summaryChart) return;

    const summaryCtx = summaryChart.getContext('2d');
    const summaryChartColors = getChartGradient(summaryCtx);

    const summaryData = (window.ownerData && window.ownerData.summary && window.ownerData.summary.data) || [65, 30, 15, 90];
    const totalSummary = summaryData.reduce((a, b) => a + b, 0);

    const centerTextPlugin = {
        id: 'centerText',
        beforeDraw(chart) {
            const { ctx, width, height } = chart;
            ctx.save();

            const text = totalSummary.toString();
            const subText = 'Total';

            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';

            // angka utama
            ctx.font = 'bold 28px sans-serif';
            ctx.fillStyle = summaryChartColors.PrimaryText;
            ctx.fillText(text, width / 2, height / 2 - 6);

            // text kecil
            ctx.font = 'bold 14px sans-serif';
            ctx.fillStyle = summaryChartColors.primaryColor;
            ctx.fillText(subText, width / 2, height / 2 + 18);

            ctx.restore();
        }
    };

    const smrChart = new Chart(summaryCtx, {
        type: 'doughnut',
        data: {
            labels: (window.ownerData && window.ownerData.summary && window.ownerData.summary.labels) || [
                'Menunggu konfirmasi',
                'Sedang Diproses',
                'Menunggu Kurir',
                'Selesai'
            ],
            datasets: [{
                label: 'Status Pesanan',
                data: summaryData,
                backgroundColor: [
                    summaryChartColors.primaryHalf,
                    summaryChartColors.secondaryColorHalf,
                    summaryChartColors.successColorHalf,
                    summaryChartColors.infoColorHalf
                ],
                hoverOffset: 30,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Status Pesanan',
                    color: summaryChartColors.PrimaryText,
                    font: { size: 18 }
                },
                legend: {
                    display: false
                }
            }
        },
        plugins: [centerTextPlugin]
    });

});
