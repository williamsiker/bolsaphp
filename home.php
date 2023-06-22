<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

function simulate_trading($stock_data, $capital) {
    $position = null;
    $buy_price = 0.0;
    $total_shares = 0;
    $portfolio_value = $capital;

    $stock_data['Signal'] = '';  

    foreach ($stock_data as $index => &$row) {
        if (intval($index) == 0) {
            continue;
        }

        $previous_row = $stock_data[intval($index) - 1];

        if ($row['Close'] > $previous_row['Close']) {
            if ($position == 'SELL') {
                $portfolio_value += $total_shares * $row['Close'];
                $total_shares = 0;
            }

            $position = 'BUY';
            $buy_price = $row['Close'];
            $total_shares = intval($portfolio_value / $buy_price);
            $portfolio_value -= $total_shares * $buy_price;

            $stock_data[$index]['Signal'] = 'BUY';  
            $stock_data[$index]['Shares'] = $total_shares; 
            $stock_data[$index]['Portfolio Value'] = $portfolio_value;  
            //echo "BUY: " . $row['Date'] . " - Price: " . $buy_price . " - Shares: " . $total_shares . " - Portfolio Value: " . $portfolio_value . "\n";
        } elseif ($row['Close'] < $previous_row['Close']) {
            if ($position == 'BUY') {
                $portfolio_value += $total_shares * $row['Close'];
                $total_shares = 0;
            }

            $position = 'SELL';
            $sell_price = $row['Close'];
            $total_shares = intval($total_shares);
            $portfolio_value += $total_shares * $sell_price;

            $stock_data[$index]['Signal'] = 'SELL';  
            $stock_data[$index]['Shares'] = $total_shares;  
            $stock_data[$index]['Portfolio Value'] = $portfolio_value;  
            //echo "SELL: " . $row['Date'] . " - Price: " . $sell_price . " - Shares: " . $total_shares . " - Portfolio Value: " . $portfolio_value . "\n";
        }
    }

    return $stock_data;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $symbol = $_POST['symbol'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $capital = intval($_POST['capital']);

    $api_key = 'IE6F252F3FWX1UUO';

    $url = "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY_ADJUSTED&symbol=$symbol&apikey=$api_key&outputsize=full";

    $response = file_get_contents($url);
    $data = json_decode($response, true);

    if (isset($data['Time Series (Daily)'])) {
        $timeSeries = $data['Time Series (Daily)'];
        $df = array();

        foreach ($timeSeries as $date => $values) {
            if ($start_date <= $date && $date <= $end_date) {
                $row = array(
                    'Date' => $date,
                    'Open' => floatval($values['1. open']),
                    'High' => floatval($values['2. high']),
                    'Low' => floatval($values['3. low']),
                    'Close' => floatval($values['4. close']),
                    'Adj Close' => floatval($values['5. adjusted close']),
                    'Volume' => floatval($values['6. volume'])
                );
                $df[] = $row;
            }
        }

        /* Imprimir el dataframe
        echo '<pre>';
        print_r($df);
        echo '</pre>';*/
        $modified_data = simulate_trading($df,$capital);
        $signals = array_column($modified_data, 'Signal');
        $signalValues = array_map(function($signal) {
            if ($signal === 'BUY') {
                return '1';
            } elseif ($signal === 'SELL') {
                return '-1';
            } else {
                return 0;
            }
        }, $signals);

        //print_r($signalValues);

    } else {
        echo 'No se encontraron datos del historial de precios.';
    }
    
    
}
?>





<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Trading Bot</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="https://startbootstrap.github.io/startbootstrap-freelancer/assets/img/favicon.ico" />
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="https://startbootstrap.github.io/startbootstrap-freelancer/css/styles.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

        <!-- JS de Bootstrap (jQuery primero, luego Popper.js y finalmente el archivo JS de Bootstrap) -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"  crossorigin="anonymous" ></script>
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg bg-secondary text-uppercase fixed-top" id="mainNav">
            <div class="container">
                <a class="navbar-brand js-scroll-trigger" href="#page-top">Home Page</a>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#portfolio">{{current_user.fullname}}</a></li>
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#about">About</a></li>
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="{{url_for('logout')}}">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <br>
        <!-- Portfolio Section-->
        <section class="page-section portfolio" id="portfolio">
            <div class="container">
                <!-- Portfolio Section Heading-->
                <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">BOT DE SIMULACIÓN</h2>
                <!-- Icon Divider-->
                
                <div class="divider-custom">
                    <div class="divider-custom-line"></div>
                    <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                    <div class="divider-custom-line"></div>
                </div>
                <!-- Portfolio Grid Items-->
                 <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" data-animation="true">
                Iniciar Simulacion (API)
                </button>


                <!-- Modal -->
                <div class="modal fade" id="myModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Constultar APLHAVANTAGE</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="home.php">
                                <label for="symbol">Símbolo de acción:</label>
                                <input type="text" id="symbol" name="symbol" required><br><br>
                                <label for="start_date">Fecha de inicio:</label>
                                <input type="date" id="start_date" name="start_date" required><br><br>
                                <label for="end_date">Fecha de fin:</label>
                                <input type="date" id="end_date" name="end_date" required><br><br>
                                <input type="number" id="capital" name="capital" required><br><br>
                                <input type="submit" value="Consultar">
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary">Guardar</button>
                        </div>
                        </div>
                    </div>
                    </div>

                <h1>Grafico de Señales</h1>
                <canvas id="signalChart"></canvas>

            </div>
        </section>
        <!-- Footer-->
        <footer class="footer text-center">
            <div class="container">
                <div class="row">
                    <!-- Footer Location-->
                    <div class="col-lg-4 mb-5 mb-lg-0">
                        <h4 class="text-uppercase mb-4">Location</h4>
                        <p class="lead mb-0">
                            2215 John Daniel Drive
                            <br />
                            Clark, MO 65243
                        </p>
                    </div>
                    <!-- Footer Social Icons-->
                    <div class="col-lg-4 mb-5 mb-lg-0">
                        <h4 class="text-uppercase mb-4">Around the Web</h4>
                        <a class="btn btn-outline-light btn-social mx-1" href="#!"><i class="fab fa-fw fa-facebook-f"></i></a>
                        <a class="btn btn-outline-light btn-social mx-1" href="#!"><i class="fab fa-fw fa-twitter"></i></a>
                        <a class="btn btn-outline-light btn-social mx-1" href="#!"><i class="fab fa-fw fa-linkedin-in"></i></a>
                        <a class="btn btn-outline-light btn-social mx-1" href="#!"><i class="fab fa-fw fa-dribbble"></i></a>
                    </div>
                    <!-- Footer About Text-->
                    <div class="col-lg-4">
                        <h4 class="text-uppercase mb-4">About Freelancer</h4>
                        <p class="lead mb-0">
                            Freelance is a free to use, MIT licensed Bootstrap theme created by
                            <a href="http://startbootstrap.com">Start Bootstrap</a>
                            .
                        </p>
                    </div>
                </div>
            </div>
        </footer>
        <!-- Copyright Section-->
        <div class="copyright py-4 text-center text-white">
            <div class="container"><small>Copyright © Your Website 2020</small></div>
        </div>
        <!-- Scroll to Top Button (Only visible on small and extra-small screen sizes)-->
        <div class="scroll-to-top d-lg-none position-fixed">
            <a class="js-scroll-trigger d-block text-center text-white rounded" href="#page-top"><i class="fa fa-chevron-up"></i></a>
        </div>
        <!-- Portfolio Modals-->
        <!-- Portfolio Modal 1-->
        <div class="portfolio-modal modal fade" id="portfolioModal1" tabindex="-1" role="dialog" aria-labelledby="portfolioModal1Label" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times"></i></span>
                    </button>
                    <div class="modal-body text-center">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <!-- Portfolio Modal - Title-->
                                    <h2 class="portfolio-modal-title text-secondary text-uppercase mb-0" id="portfolioModal1Label">Log Cabin</h2>
                                    <!-- Icon Divider-->
                                    <div class="divider-custom">
                                        <div class="divider-custom-line"></div>
                                        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                                        <div class="divider-custom-line"></div>
                                    </div>
                                    <!-- Portfolio Modal - Image-->
                                    <img class="img-fluid rounded mb-5" src="/stock_chart.png" alt="" />
                                    <!-- Portfolio Modal - Text-->
                                    <p class="mb-5">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Mollitia neque assumenda ipsam nihil, molestias magnam, recusandae quos quis inventore quisquam velit asperiores, vitae? Reprehenderit soluta, eos quod consequuntur itaque. Nam.</p>
                                    <button class="btn btn-primary" data-dismiss="modal">
                                        <i class="fas fa-times fa-fw"></i>
                                        Close Window
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
        
        /* Extrae las fechas y señales del arreglo $stock_data
        var dates = <?php echo json_encode(array_column($modified_data, 'Date')); ?>;
        var signals = <?php echo json_encode(array_column($modified_data, 'Signal')); ?>;*/

        var dates = <?php echo isset($modified_data) ? json_encode(array_column($modified_data, 'Date')) : '[]'; ?>;
        //var signals = <?php echo isset($modified_data) ? json_encode(array_column($modified_data, 'Signal')) : '[]'; ?>;
        var signals = <?php echo json_encode($signalValues); ?>
        // Crea un nuevo gráfico con Chart.js
        var ctx = document.getElementById('signalChart').getContext('2d');
        var signalChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Precio Accion <?php echo $symbol ?>',
                    data: <?php echo json_encode(array_column($modified_data, 'Close')); ?>, //signals,
                    backgroundColor: 'rgba(0, 123, 255, 0.5)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 1,
                    pointBackgroundColor: function(context) {
                        var index = context.dataIndex;
                        var signal = signals[index];
                        return signal === '1' ? 'rgba(0,143,57)' : 'rgba(255, 0, 0, 1)';
                    },
                    pointRadius: 3,
                    pointHoverRadius: 5
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Fecha'
                        }
                    },
                    y: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Señal'
                        }
                    }
                }
            }
        });
    </script>
        
        <!-- Bootstrap core JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Third party plugin JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>        <!-- Core theme JS-->
        <script src="https://startbootstrap.github.io/startbootstrap-freelancer/js/scripts.js"></script>
    </body>
</html>
