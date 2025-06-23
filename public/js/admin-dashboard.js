/**
 * Admin Dashboard Charts Management
 * Handles all chart functionality for the admin dashboard
 */

class AdminDashboardCharts {
    constructor() {
        this.charts = {};
        this.currentPeriod = '6months';
        this.init();
    }

    init() {
        this.initCharts();
        this.bindEvents();
    }

    initCharts() {
        this.initUserGrowthChart();
        this.initArtworksChart();
        this.initArtworksGrowthChart();
    }

    initUserGrowthChart() {
        const ctx = document.getElementById('userGrowthChart');
        const loading = document.getElementById('userGrowthLoading');
        if (!ctx) return;

        // Hide loading and show chart
        if (loading) {
            loading.style.display = 'none';
        }
        ctx.style.display = 'block';

        this.charts.userGrowth = new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: this.getUserGrowthData(),
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#4e73df',
                        borderWidth: 1
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            color: '#858796'
                        },
                        grid: {
                            color: 'rgba(133, 135, 150, 0.1)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#858796'
                        },
                        grid: {
                            color: 'rgba(133, 135, 150, 0.1)'
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                },
                elements: {
                    point: {
                        radius: 4,
                        hoverRadius: 6
                    }
                }
            }
        });
    }

    initArtworksChart() {
        const ctx = document.getElementById('artworksChart');
        if (!ctx) return;

        this.charts.artworks = new Chart(ctx.getContext('2d'), {
            type: 'doughnut',
            data: this.getArtworksData(),
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : '0';
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '60%'
            }
        });
    }

    initArtworksGrowthChart() {
        const ctx = document.getElementById('artworksGrowthChart');
        if (!ctx) return;

        this.charts.artworksGrowth = new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: this.getArtworksGrowthData(),
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#e74a3b',
                        borderWidth: 1
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            color: '#858796'
                        },
                        grid: {
                            color: 'rgba(133, 135, 150, 0.1)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#858796'
                        },
                        grid: {
                            color: 'rgba(133, 135, 150, 0.1)'
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                },
                elements: {
                    point: {
                        radius: 4,
                        hoverRadius: 6
                    }
                }
            }
        });
    }

    getUserGrowthData() {
        return {
            labels: this.getMonthLabels(),
            datasets: [{
                label: 'New Users',
                data: window.userGrowthData || [0, 0, 0, 0, 0, 0],
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.4,
                fill: true,
                borderWidth: 3
            }]
        };
    }

    getArtworksData() {
        return {
            labels: ['Approved', 'Pending', 'Rejected'],
            datasets: [{
                data: [
                    window.approvedArtworks || 0,
                    window.pendingArtworks || 0,
                    window.rejectedArtworks || 0
                ],
                backgroundColor: [
                    'rgba(28, 200, 138, 0.8)',
                    'rgba(246, 194, 62, 0.8)',
                    'rgba(231, 74, 59, 0.8)'
                ],
                borderColor: [
                    'rgba(28, 200, 138, 1)',
                    'rgba(246, 194, 62, 1)',
                    'rgba(231, 74, 59, 1)'
                ],
                borderWidth: 2
            }]
        };
    }

    getArtworksGrowthData() {
        return {
            labels: this.getMonthLabels(),
            datasets: [{
                label: 'New Artworks',
                data: window.artworksGrowthData || [0, 0, 0, 0, 0, 0],
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.4,
                fill: true,
                borderWidth: 3
            }]
        };
    }

    getMonthLabels() {
        const months = [];
        for (let i = 5; i >= 0; i--) {
            const date = new Date();
            date.setMonth(date.getMonth() - i);
            months.push(date.toLocaleDateString('en-US', { month: 'short' }));
        }
        return months;
    }

    bindEvents() {
        // Bind dropdown events
        const dropdownItems = document.querySelectorAll('[onclick^="updateChartPeriod"]');
        dropdownItems.forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                const period = e.target.getAttribute('onclick').match(/'([^']+)'/)[1];
                this.updateChartPeriod(period);
            });
        });

        // Auto refresh every 5 minutes
        setInterval(() => {
            this.refreshData();
        }, 5 * 60 * 1000);
    }

    updateChartPeriod(period) {
        this.currentPeriod = period;
        this.refreshData();
        
        // Update dropdown text
        const dropdownButton = document.querySelector('#dropdownMenuLink');
        if (dropdownButton) {
            const periodText = this.getPeriodText(period);
            dropdownButton.innerHTML = `<i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i> ${periodText}`;
        }
    }

    getPeriodText(period) {
        const periods = {
            '6months': 'Last 6 Months',
            '12months': 'Last 12 Months',
            'yearly': 'Yearly'
        };
        return periods[period] || 'Last 6 Months';
    }

    async refreshData() {
        try {
            const response = await fetch(`/admin/dashboard/chart-data?period=${this.currentPeriod}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.updateCharts(data);
            }
        } catch (error) {
            console.error('Error refreshing chart data:', error);
        }
    }

    updateCharts(data) {
        // Update user growth chart
        if (this.charts.userGrowth && data.userGrowth) {
            this.charts.userGrowth.data.datasets[0].data = data.userGrowth;
            this.charts.userGrowth.update('none');
        }

        // Update artworks chart
        if (this.charts.artworks && data.artworks) {
            this.charts.artworks.data.datasets[0].data = [
                data.artworks.approved || 0,
                data.artworks.pending || 0,
                data.artworks.rejected || 0
            ];
            this.charts.artworks.update('none');
        }

        // Update artworks growth chart
        if (this.charts.artworksGrowth && data.artworksGrowth) {
            this.charts.artworksGrowth.data.datasets[0].data = data.artworksGrowth;
            this.charts.artworksGrowth.update('none');
        }
    }

    // Export chart as image
    exportChart(chartName, format = 'png') {
        const chart = this.charts[chartName];
        if (chart) {
            const link = document.createElement('a');
            link.download = `${chartName}-${new Date().toISOString().split('T')[0]}.${format}`;
            link.href = chart.toBase64Image();
            link.click();
        }
    }

    // Print dashboard
    printDashboard() {
        window.print();
    }
}

// Initialize dashboard charts when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Set global variables for chart data
    window.userGrowthData = window.userGrowthData || [0, 0, 0, 0, 0, 0];
    window.artworksGrowthData = window.artworksGrowthData || [0, 0, 0, 0, 0, 0];
    window.approvedArtworks = window.approvedArtworks || 0;
    window.pendingArtworks = window.pendingArtworks || 0;
    window.rejectedArtworks = window.rejectedArtworks || 0;

    // Initialize dashboard
    window.adminDashboard = new AdminDashboardCharts();
});

// Global function for chart period updates (for backward compatibility)
function updateChartPeriod(period) {
    if (window.adminDashboard) {
        window.adminDashboard.updateChartPeriod(period);
    }
} 