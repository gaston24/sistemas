
// Función para cambiar entre solapas
function switchTab(tabName) {
    // Actualizar botones
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active-tab');
    });
    document.getElementById(`tab-${tabName}`).classList.add('active-tab');
    
    // Mostrar/ocultar contenedores
    document.querySelectorAll('.chart-container').forEach(container => {
        container.style.display = 'none';
    });
    document.getElementById(`chart-${tabName}`).style.display = 'block';
}

// Inicialización cuando el DOM está listo
document.addEventListener('DOMContentLoaded', function() {
    console.log('Chart Data recibido:', chartData);
    // Inicializar búsqueda en tabla
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('tbody tr');

    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const shouldShow = text.includes(searchTerm);
                row.style.display = shouldShow ? '' : 'none';
            });
        });
    }

    // Inicializar gráfico de pedidos
    const pedidosCtx = document.getElementById('weeklyOrdersChart');
    if (pedidosCtx) {
        const pedidosChart = new Chart(pedidosCtx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: 'Pedido General',
                        data: chartData.generalData,
                        borderColor: '#3B82F6',
                        backgroundColor: '#3B82F6',
                        tension: 0.1,
                        pointRadius: 4
                    },
                    {
                        label: 'Pedido Accesorios',
                        data: chartData.accesoriosData,
                        borderColor: '#8B5CF6',
                        backgroundColor: '#8B5CF6',
                        tension: 0.1,
                        pointRadius: 4
                    },
                    {
                        label: 'Pedido Outlet',
                        data: chartData.outletData,
                        borderColor: '#EAB308',
                        backgroundColor: '#EAB308',
                        tension: 0.1,
                        pointRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    }

    // Inicializar gráfico de rubros
    const rubrosCtx = document.getElementById('weeklyRubrosChart');
    // En el archivo js/historial.js, actualizar la configuración de las escalas
if (rubrosCtx) {
    rubroChart = new Chart(rubrosCtx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [
                {
                    label: 'Unidades Solicitadas',
                    data: [],
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    pointRadius: 4,
                    tension: 0.1
                },
                {
                    label: 'Unidades Vendidas',
                    data: [],
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 2,
                    pointRadius: 4,
                    tension: 0.1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: {
                    position: 'top'
                },
                title: {
                    display: true,
                    text: 'Seleccione un rubro para visualizar datos'
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        callback: function(value) {
                            return value.toFixed(0);
                        }
                    },
                    afterDataLimits: (scale) => {
                        // Asegurar que haya suficiente espacio en la escala
                        const maxValue = Math.max(...scale.chart.data.datasets.flatMap(d => d.data));
                        scale.max = Math.ceil(maxValue * 1.1); // 10% más que el valor máximo
                        if (scale.max <= 1) scale.max = 10; // valor mínimo para la escala
                    }
                },
                x: {
                    display: true,
                    ticks: {
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            }
        }
    });

    // Función para actualizar el gráfico
    const updateChart = async (selectedRubro) => {
        try {
            const response = await fetch(`getRubroData.php?rubro=${encodeURIComponent(selectedRubro)}`);
            if (!response.ok) throw new Error('Error en la respuesta del servidor');
            
            const data = await response.json();
            
            // Encontrar el valor máximo para ajustar la escala
            const maxValue = Math.max(
                ...data.pedidosData, 
                ...data.ventasData
            );

            rubroChart.data.labels = data.labels;
            rubroChart.data.datasets[0].data = data.pedidosData;
            rubroChart.data.datasets[1].data = data.ventasData;
            
            // Ajustar la escala Y
            rubroChart.options.scales.y.max = Math.ceil(maxValue * 1.1);
            if (rubroChart.options.scales.y.max <= 1) {
                rubroChart.options.scales.y.max = 10;
            }
            
            rubroChart.options.plugins.title.text = 'Pedidos y Ventas - ' + selectedRubro;
            rubroChart.update();
        } catch (error) {
            console.error('Error al obtener datos del rubro:', error);
        }
    };

    // Manejar el cambio de rubro
    const rubroSelect = document.getElementById('rubroSelect');
    if (rubroSelect) {
        rubroSelect.addEventListener('change', (e) => {
            const selectedRubro = e.target.value;
            if (selectedRubro) {
                updateChart(selectedRubro);
            } else {
                // Limpiar el gráfico si no hay rubro seleccionado
                rubroChart.data.labels = [];
                rubroChart.data.datasets[0].data = [];
                rubroChart.data.datasets[1].data = [];
                rubroChart.options.plugins.title.text = 'Seleccione un rubro para visualizar datos';
                rubroChart.update();
            }
        });
    }
}
});