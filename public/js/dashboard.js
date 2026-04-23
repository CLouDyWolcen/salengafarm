/**
 * Dashboard JS - Enhances the dashboard with interactive features
 * For Salenga Farm Plant Inventory System
 */

// Global chart instances
let stockChart = null;
let salesChart = null;
let trendsChart = null;

document.addEventListener('DOMContentLoaded', function() {
    // Setup sidebar toggle for mobile
    setupSidebarToggle();
    
    // Add animation classes to cards when page loads
    animateCards();
    
    // Filter functionality for Update Stock modal
    setupStockFilters();
    
    // Search functionality for Update Stock modal
    setupStockSearch();
    
    // Apply hover effects to all cards
    setupCardHoverEffects();
    
    // Make list items in low stock alerts and recent plants consistent
    equalizeListItems();
    
    // Setup analytics controls
    setupAnalyticsControls();
    
    // Listen for window resize to maintain proper sizing
    window.addEventListener('resize', handleResize);
});

/**
 * Setup sidebar toggle functionality for mobile
 */
function setupSidebarToggle() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebarMenu');
    const overlay = document.getElementById('sidebarOverlay');
    
    if (!sidebarToggle || !sidebar || !overlay) return;
    
    // Toggle sidebar on button click
    sidebarToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
    });
    
    // Close sidebar when clicking overlay
    overlay.addEventListener('click', function() {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
    });
    
    // Close sidebar when clicking a link (mobile only)
    if (window.innerWidth <= 991) {
        const sidebarLinks = sidebar.querySelectorAll('.sidebar-link');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function() {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            });
        });
    }
}

/**
 * Animates dashboard cards with a staggered fade-in effect
 */
function animateCards() {
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        // Add a staggered delay based on the index
        setTimeout(() => {
            card.classList.add('card-animated');
        }, 80 * index); // Slightly faster animation
    });
}

/**
 * Sets up category filters in the Update Stock modal
 */
function setupStockFilters() {
    const categoryLinks = document.querySelectorAll('[data-category]');
    
    categoryLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all links
            categoryLinks.forEach(l => l.classList.remove('active'));
            
            // Add active class to clicked link
            this.classList.add('active');
            
            const selectedCategory = this.getAttribute('data-category');
            filterTableByCategory(selectedCategory);
        });
    });
}

/**
 * Filters the stock table based on selected category
 */
function filterTableByCategory(category) {
    const tableRows = document.querySelectorAll('#stockUpdateTableBody tr');
    
    tableRows.forEach(row => {
        const rowCategory = row.getAttribute('data-category');
        
        if (category === 'all' || rowCategory === category) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

/**
 * Sets up search functionality for the stock table
 */
function setupStockSearch() {
    const searchInput = document.getElementById('stockSearchInput');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            const tableRows = document.querySelectorAll('#stockUpdateTableBody tr');
            
            tableRows.forEach(row => {
                const plantName = row.cells[0].textContent.toLowerCase();
                const category = row.cells[1].textContent.toLowerCase();
                
                if (plantName.includes(searchTerm) || category.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
}

/**
 * Sets up hover effects for dashboard cards
 */
function setupCardHoverEffects() {
    const cards = document.querySelectorAll('.card');
    
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.classList.add('card-hover');
        });
        
        card.addEventListener('mouseleave', function() {
            this.classList.remove('card-hover');
        });
    });
}

/**
 * Ensures list items in low stock alerts and recent plants have consistent heights
 */
function equalizeListItems() {
    const listItems = document.querySelectorAll('.list-group-item');
    listItems.forEach(item => {
        // Ensure text doesn't overflow
        const title = item.querySelector('h6');
        if (title) {
            // Add tooltip for truncated text
            title.setAttribute('title', title.textContent);
        }
    });
}

/**
 * Handles window resize to maintain proper sizing
 */
function handleResize() {
    // Check if we're on mobile
    if (window.innerWidth < 768) {
        // Adjust card heights for mobile
        document.querySelectorAll('.low-stock-card, .stock-distribution-card, .right-column-card, .recent-plants-card').forEach(card => {
            card.style.height = 'auto';
        });
    } else if (window.innerWidth < 992) {
        // Tablet sizes
        document.querySelectorAll('.low-stock-card').forEach(card => card.style.height = '280px');
        document.querySelectorAll('.stock-distribution-card').forEach(card => card.style.height = '350px');
        document.querySelectorAll('.right-column-card').forEach(card => card.style.height = '120px');
        document.querySelectorAll('.recent-plants-card').forEach(card => card.style.height = '280px');
    } else {
        // Desktop - reset to default heights
        document.querySelectorAll('.low-stock-card').forEach(card => card.style.height = '310px');
        document.querySelectorAll('.stock-distribution-card').forEach(card => card.style.height = '380px');
        document.querySelectorAll('.right-column-card').forEach(card => card.style.height = '130px');
        document.querySelectorAll('.recent-plants-card').forEach(card => card.style.height = '310px');
    }
    
    // Also adjust summary cards based on screen size
    const summaryCards = document.querySelectorAll('.row.mb-4 .card');
    if (window.innerWidth < 768) {
        summaryCards.forEach(card => card.style.height = 'auto');
    } else if (window.innerWidth < 992) {
        summaryCards.forEach(card => card.style.height = '80px');
    } else {
        summaryCards.forEach(card => card.style.height = '90px');
    }
    
    // Ensure chart is responsive
    if (window.Chart && window.Chart.instances) {
        Object.values(window.Chart.instances).forEach(chart => {
            chart.resize();
        });
    }
}

/**
 * Initializes and configures the stock distribution chart with animation
 * Note: This is called directly from the dashboard.blade.php
 * 
 * @param {string} chartId - The ID of the canvas element for the chart
 * @param {Object} data - Chart data including labels and values
 */
function initStockChart(chartId, data) {
    const ctx = document.getElementById(chartId).getContext('2d');
    
    // Updated color scheme based on user specifications
    const categoryColors = {
        'shrub': '#2E7D32',      // Dark Green
        'tree': '#1B5E20',       // Deep Green
        'grass': '#81C784',      // Light Green
        'veggies': '#FB8C00',    // Orange
        'fruits': '#E53935',     // Red
        'herbs': '#66BB6A',      // Mint Green
        'palm': '#C0CA33',       // Yellow-Green
        'bamboo': '#6D8B74',     // Muted Green
        'fertilizer': '#6D4C41', // Brown
        'flowers': '#ff5722',    // Deep Orange-Red (fallback)
        'seeds': '#37474f',      // Dark Grey (fallback)
        'tools': '#00bcd4'       // Cyan (fallback)
    };
    
    // Map colors to actual categories in the data
    const chartColors = data.labels.map(label => {
        const lowerLabel = label.toLowerCase();
        return categoryColors[lowerLabel] || '#2E7D32'; // Default to dark green if category not found
    });
    
    // Fallback colors if we need more than defined categories
    const fallbackColors = [
        '#4CAF50',  // Material Green
        '#FF9800',  // Material Orange
        '#9C27B0',  // Material Purple
        '#2196F3',  // Material Blue
        '#FFC107',  // Material Amber
        '#795548'   // Material Brown
    ];
    
    // Add fallback colors if needed
    while (chartColors.length < data.labels.length) {
        chartColors.push(fallbackColors[chartColors.length % fallbackColors.length]);
    }
    
    stockChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: data.labels,
            datasets: [{
                data: data.values,
                backgroundColor: chartColors.slice(0, data.labels.length),
                borderColor: '#ffffff',
                borderWidth: 2,
                hoverOffset: 15
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: {
                            family: "'Nunito', sans-serif",
                            size: 12,
                            weight: 'bold'
                        },
                        color: '#333'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                    titleColor: '#333',
                    bodyColor: '#555',
                    borderColor: '#ddd',
                    borderWidth: 1,
                    padding: 12,
                    boxPadding: 5,
                    usePointStyle: true,
                    callbacks: {
                        label: function(context) {
                            const value = context.raw;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${context.label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            },
            animation: {
                animateScale: true,
                animateRotate: true,
                duration: 1200, // Slightly faster animation
                easing: 'easeOutCubic'
            }
        }
    });
    
    return stockChart;
}

/**
 * Shows a dismissible alert message
 * 
 * @param {string} message - The message to display
 * @param {string} type - Alert type (success, danger, warning, info)
 * @param {number} duration - How long to show the alert in milliseconds
 */
function showAlert(message, type = 'success', duration = 3000) {
    // Create alert element
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 mt-3 me-3`;
    alert.style.zIndex = '1060';
    alert.style.minWidth = '300px';
    alert.style.maxWidth = '400px';
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Add to document
    document.body.appendChild(alert);
    
    // Remove after duration
    setTimeout(() => {
        alert.classList.remove('show');
        setTimeout(() => {
            alert.remove();
        }, 300);
    }, duration);
} 

/**
 * Initializes and configures the sales distribution chart
 * 
 * @param {string} chartId - The ID of the canvas element for the chart
 * @param {Object} data - Chart data including labels, values, and type
 */
function initSalesChart(chartId, data) {
    const ctx = document.getElementById(chartId).getContext('2d');
    
    // Format category labels to capitalize first letter
    const formattedLabels = data.labels.map(label => 
        label.charAt(0).toUpperCase() + label.slice(1)
    );
    
    // Use the SAME color scheme as Stock Distribution for consistency
    const categoryColors = {
        'shrub': '#2E7D32',      // Dark Green
        'tree': '#1B5E20',       // Deep Green
        'grass': '#81C784',      // Light Green
        'veggies': '#FB8C00',    // Orange
        'fruits': '#E53935',     // Red
        'herbs': '#66BB6A',      // Mint Green
        'palm': '#C0CA33',       // Yellow-Green
        'bamboo': '#6D8B74',     // Muted Green
        'fertilizer': '#6D4C41', // Brown
        'flowers': '#ff5722',    // Deep Orange-Red (fallback)
        'seeds': '#37474f',      // Dark Grey (fallback)
        'tools': '#00bcd4'       // Cyan (fallback)
    };
    
    // Map colors to actual categories in the data (same as stock chart)
    const chartColors = data.labels.map(label => {
        const lowerLabel = label.toLowerCase();
        return categoryColors[lowerLabel] || '#2E7D32'; // Default to dark green if category not found
    });
    
    // Fallback colors if we need more than defined categories
    const fallbackColors = [
        '#4CAF50',  // Material Green
        '#FF9800',  // Material Orange
        '#9C27B0',  // Material Purple
        '#2196F3',  // Material Blue
        '#FFC107',  // Material Amber
        '#795548'   // Material Brown
    ];
    
    // Add fallback colors if needed
    while (chartColors.length < data.labels.length) {
        chartColors.push(fallbackColors[chartColors.length % fallbackColors.length]);
    }
    
    const isRevenue = data.type === 'revenue';
    const labelSuffix = isRevenue ? 'Revenue' : 'Quantity';
    
    salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: formattedLabels,
            datasets: [{
                label: `Sales Percentage by Category (${labelSuffix})`,
                data: data.values,
                backgroundColor: chartColors.slice(0, data.labels.length),
                borderColor: '#ffffff',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y', // Horizontal bar chart
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                    titleColor: '#333',
                    bodyColor: '#555',
                    borderColor: '#ddd',
                    borderWidth: 1,
                    padding: 12,
                    boxPadding: 5,
                    callbacks: {
                        label: function(context) {
                            const suffix = isRevenue ? 'of total revenue' : 'of total sales';
                            return `${parseFloat(context.raw).toFixed(1)}% ${suffix}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    max: 100,
                    title: {
                        display: true,
                        text: `Percentage of Total ${labelSuffix}`,
                        color: '#666',
                        font: {
                            size: 12,
                            weight: 'bold'
                        }
                    },
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                },
                y: {
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                }
            },
            animation: {
                duration: 1200,
                easing: 'easeOutQuart'
            }
        }
    });
    
    return salesChart;
} 

/**
 * Setup analytics controls for interactive charts
 */
function setupAnalyticsControls() {
    // Sales chart controls
    const salesPeriod = document.getElementById('salesPeriod');
    const salesType = document.getElementById('salesType');
    
    if (salesPeriod && salesType) {
        salesPeriod.addEventListener('change', updateSalesChart);
        salesType.addEventListener('change', updateSalesChart);
    }
    
    // Trends chart controls
    const trendsPeriod = document.getElementById('trendsPeriod');
    const trendsMetric = document.getElementById('trendsMetric');
    
    if (trendsPeriod && trendsMetric) {
        trendsPeriod.addEventListener('change', updateTrendsChart);
        trendsMetric.addEventListener('change', updateTrendsChart);
    }
    
    // Tab change events to initialize charts when needed
    const salesTab = document.getElementById('sales-tab');
    const trendsTab = document.getElementById('trends-tab');
    
    if (salesTab) {
        salesTab.addEventListener('shown.bs.tab', function() {
            if (!salesChart) {
                updateSalesChart();
            }
        });
    }
    
    if (trendsTab) {
        trendsTab.addEventListener('shown.bs.tab', function() {
            if (!trendsChart) {
                updateTrendsChart();
            }
        });
    }
}

/**
 * Update sales chart based on selected period and type
 */
function updateSalesChart() {
    const period = document.getElementById('salesPeriod')?.value || '30';
    const type = document.getElementById('salesType')?.value || 'quantity';
    
    // Show loading state
    const canvas = document.getElementById('salesDistributionChart');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = '#666';
        ctx.font = '14px Arial';
        ctx.textAlign = 'center';
        ctx.fillText('Loading...', canvas.width / 2, canvas.height / 2);
    }
    
    // Fetch analytics data
    fetch(`/dashboard/analytics?period=${period}&type=${type}`)
        .then(response => response.json())
        .then(data => {
            if (salesChart) {
                salesChart.destroy();
            }
            
            const labels = Object.keys(data.salesByCategory);
            const values = Object.values(data.salesByCategory).map(item => 
                type === 'quantity' ? item.quantity_percentage : item.revenue_percentage
            );
            
            salesChart = initSalesChart('salesDistributionChart', {
                labels: labels,
                values: values,
                type: type
            });
        })
        .catch(error => {
            console.error('Error fetching sales data:', error);
            showAlert('Failed to load sales data', 'danger');
        });
}

/**
 * Update trends chart based on selected period and metric
 */
function updateTrendsChart() {
    const period = document.getElementById('trendsPeriod')?.value || '30';
    const metric = document.getElementById('trendsMetric')?.value || 'quantity';
    
    // Show loading state
    const canvas = document.getElementById('salesTrendsChart');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = '#666';
        ctx.font = '14px Arial';
        ctx.textAlign = 'center';
        ctx.fillText('Loading...', canvas.width / 2, canvas.height / 2);
    }
    
    // Fetch analytics data
    fetch(`/dashboard/analytics?period=${period}&type=${metric}`)
        .then(response => response.json())
        .then(data => {
            if (trendsChart) {
                trendsChart.destroy();
            }
            
            const labels = data.salesTrends.map(item => {
                const date = new Date(item.sale_date);
                return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            });
            
            const values = data.salesTrends.map(item => 
                metric === 'quantity' ? item.daily_quantity : item.daily_revenue
            );
            
            trendsChart = initTrendsChart('salesTrendsChart', {
                labels: labels,
                values: values,
                metric: metric
            });
        })
        .catch(error => {
            console.error('Error fetching trends data:', error);
            showAlert('Failed to load trends data', 'danger');
        });
}

/**
 * Initializes and configures the sales trends chart
 * 
 * @param {string} chartId - The ID of the canvas element for the chart
 * @param {Object} data - Chart data including labels, values, and metric type
 */
function initTrendsChart(chartId, data) {
    const ctx = document.getElementById(chartId).getContext('2d');
    
    const isRevenue = data.metric === 'revenue';
    const color = isRevenue ? '#28a745' : '#007bff';
    
    return new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [{
                label: isRevenue ? 'Daily Revenue (₱)' : 'Daily Quantity Sold',
                data: data.values,
                borderColor: color,
                backgroundColor: color + '20',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: color,
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: {
                            family: "'Nunito', sans-serif",
                            size: 12,
                            weight: 'bold'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                    titleColor: '#333',
                    bodyColor: '#555',
                    borderColor: '#ddd',
                    borderWidth: 1,
                    padding: 12,
                    callbacks: {
                        label: function(context) {
                            const value = context.raw;
                            if (isRevenue) {
                                return `Revenue: ₱${parseFloat(value).toLocaleString()}`;
                            } else {
                                return `Quantity: ${value} plants`;
                            }
                        }
                    }
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Date',
                        color: '#666',
                        font: {
                            size: 12,
                            weight: 'bold'
                        }
                    },
                    grid: {
                        color: '#f0f0f0'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: isRevenue ? 'Revenue (₱)' : 'Quantity',
                        color: '#666',
                        font: {
                            size: 12,
                            weight: 'bold'
                        }
                    },
                    grid: {
                        color: '#f0f0f0'
                    },
                    ticks: {
                        callback: function(value) {
                            if (isRevenue) {
                                return '₱' + parseFloat(value).toLocaleString();
                            }
                            return value;
                        }
                    }
                }
            },
            animation: {
                duration: 1200,
                easing: 'easeOutQuart'
            }
        }
    });
}