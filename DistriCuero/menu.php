
<?php

require_once 'Class/Orden.php';

?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Ordenes de Preventa</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #4f46e5;
        }
        
        body {
            background-color: #f3f4f6;
            font-size: 14px;
        }
        
        .menu-card {
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
            border: none;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            height: auto;
        }
        
        .menu-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .menu-card .icon {
            font-size: 2rem;
            color: white;
        }
        
        .menu-card .card-title {
            color: white;
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 0;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        .menu-card .card-body {
            padding: 1.25rem 1rem;
        }
        
        .data-table {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        
        .data-table th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 500;
            white-space: nowrap;
            padding: 0.5rem;
        }
        
        .data-table td {
            padding: 0.5rem;
            font-size: 0.9rem;
        }
        
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-weight: 500;
            font-size: 0.85rem;
        }
        
        .status-active {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .status-inactive {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .navbar {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            padding: 0.5rem 0;
        }
        
        .title-icon {
            color: var(--primary-color);
            font-size: 1.5rem;
        }
        
        .navbar-brand {
            font-size: 1.1rem;
        }
        
        .mb-5 {
            margin-bottom: 2rem !important;
        }
        
        .table-responsive {
            max-height: calc(100vh - 300px);
            overflow-y: auto;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 0 10px;
            }
            
            .menu-card .icon {
                font-size: 1.5rem;
            }
            
            .menu-card .card-title {
                font-size: 0.9rem;
            }
            
            .menu-card .card-body {
                padding: 1rem 0.5rem;
            }
            
            .title-icon {
                font-size: 1.25rem;
            }
            
            .navbar-brand {
                font-size: 1rem;
            }
            
            .data-table td, 
            .data-table th {
                padding: 0.4rem;
                font-size: 0.8rem;
            }
            
            .status-badge {
                padding: 0.2rem 0.5rem;
                font-size: 0.8rem;
            }
        }
        
        @media (max-width: 576px) {
            .col-sm-6 {
                width: 50%;
            }
            
            .mb-4 {
                margin-bottom: 1rem !important;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light mb-4">
        <div class="container">
            <span class="navbar-brand">
                <i class="fas fa-shopping-cart fa-2x title-icon me-2"></i>
                <span class="fw-bold">Gestión de Ordenes de Preventa</span>
            </span>
        </div>
    </nav>

    <div class="container">
        <div class="row mb-1">
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card menu-card h-100" onclick="location.href='index.php'">
                    <div class="card-body text-center py-4">
                        <i class="fas fa-archive icon mb-3"></i>
                        <h5 class="card-title">Carga de Orden</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card menu-card h-100" onclick="location.href='activaOrdenes.php'">
                    <div class="card-body text-center py-4">
                        <i class="fas fa-clipboard-check icon mb-3"></i>
                        <h5 class="card-title">Activar Orden</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card menu-card h-100" onclick="location.href='desactivaOrdenes.php'">
                    <div class="card-body text-center py-4">
                        <i class="far fa-calendar-times icon mb-3"></i>
                        <h5 class="card-title">Desactivar Orden</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card menu-card h-100" onclick="location.href='listOrdenesComercial.php'">
                    <div class="card-body text-center py-4">
                        <i class="fas fa-tasks icon mb-3"></i>
                        <h5 class="card-title">Auditoría Orden</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="card data-table mb-5">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Resumen últimas 10 de Órdenes</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Nro. Orden</th>
                                <th>Precio</th>
                                <th>Cantidad Unid.</th>
                                <th>Cantidad NP</th>
                                <th>% NP Cargadas</th>
                                <th>Lanzamiento</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $orden = new Orden();
                            $resumen = $orden->traerResumenOrdenes();
                       
                            $totalSucursales = $orden->obtenerTotalSucursales();
                            
                            foreach($resumen as $row) {
                                $estado = $row['ACTIVA'] == 1 ? 'Activa' : 'Inactiva';
                                $estadoClass = $row['ACTIVA'] == 1 ? 'status-active' : 'status-inactive';
                                $porcentajeCargado = $totalSucursales > 0 ? round(($row['CANT_NP'] / intval($totalSucursales)) * 100) : 0;
                                
                                echo "<tr>";
                                echo "<td>" . $row['FECHA']->format('Y-m-d') . "</td>";
                                echo "<td>" . $row['HORA'] . "</td>";
                                echo "<td>" . $row['NRO_ORDEN'] . "</td>";
                                echo "<td>$" . number_format($row['PRECIO'], 0) . "</td>";
                                echo "<td>" . $row['CANTIDAD'] . "</td>";
                                echo "<td>" . $row['CANT_NP'] . "</td>";
                                echo "<td>" . $porcentajeCargado . "%</td>";
                                echo "<td>" . $row['LANZAMIENTO'] . "</td>";
                                echo "<td><span class='status-badge " . $estadoClass . "'>" . $estado . "</span></td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>