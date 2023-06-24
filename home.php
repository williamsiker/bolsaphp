<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
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
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/chart.js" crossorigin="anonymus"></script>

        
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg bg-secondary text-uppercase fixed-top" id="mainNav">
            <div class="container">
                <a class="navbar-brand js-scroll-trigger" href="#page-top">Home Page</a>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#portfolio"><?php echo $_SESSION['username']; ?></a></li>
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
                Consultar Datos (API) - Simular Consulta
                </button>

                <button type="button" class="btn btn-primary" id="random">
                    Iniciar Simulacion (RANDOM)
                </button>

                <button type="button" class="btn btn-danger" id="stop">
                    Detener Simulacion (RANDOM)
                </button>

                <input type="number" id="rcapital" name="rcapital">
                <label for="rcapital">Capital de Inicio (RANDOM)</label>


                <!-- Modal (API)-->
                <div class="modal fade" id="myModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Constultar APLHAVANTAGE</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <!--Cmabiando de home.php a simular.php action="simular.php" -->
                            <form id="myForm" method="POST">
                                <label for="symbol">Símbolo de acción:</label>
                                <input type="text" id="symbol" name="symbol" required><br><br>
                                <label for="start_date">Fecha de inicio:</label>
                                <input type="date" id="start_date" name="start_date" required><br><br>
                                <label for="end_date">Fecha de fin:</label>
                                <input type="date" id="end_date" name="end_date" required><br><br>
                                <label for="capital">Capital de Inicio</label>
                                <input type="number" id="capital" name="capital" required><br><br>
                                <input type="submit" value="Consultar">
                            </form>
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
            const ctx = document.getElementById('signalChart').getContext('2d');
            $(document).ready(function(){
                let intervalID;
                let signalChart;    
                let currentIndex = 0;
                //Consulta a la API
                $('#myForm').submit(function(event) {
                    event.preventDefault();
                    let formData = new FormData(this);

                    // Acceder a los datos de formData
                    let symbol = formData.get('symbol');
                    let startDate = formData.get('start_date');
                    let endDate = formData.get('end_date');
                    let capital = formData.get('capital');
                    if (signalChart) {
                                signalChart.destroy();
                    }
                    $.ajax({
                        url: 'simular.php',
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            symbol: symbol,
                            start_date: startDate,
                            end_date: endDate,
                            capital: capital
                        },
                        success: function(response)
                        {
                            //$('#myModal').modal('hide');
                            // console.log(respuesta);
                            let modified_data = Object.values(response);

                            signalChart = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels:[],
                                    datasets: [{
                                        label: 'Precio Accion Random ',
                                        data: [],
                                        backgroundColor: 'rgba(0, 123, 255, 0.5)',
                                        borderColor: 'rgba(0, 123, 255, 1)',
                                        borderWidth: 1,
                                        pointRadius: 3,
                                        pointBackgroundColor: [],
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

                            console.log(response);
                            // Actualizar la gráfica cada segundo
                            intervalID = setInterval(function() {
                                if (response[currentIndex] && response[currentIndex]['Date']) {
                                    // Agregar un punto al gráfico
                                    signalChart.data.labels.push(response[currentIndex]['Date']);
                                    signalChart.data.datasets[0].data.push(response[currentIndex]['Close']);
                                    let signalColor = response[currentIndex]['Signal'] === 'BUY' ? 'rgba(0, 143, 57)' : 'rgba(255, 0, 0, 1)';
                                    signalChart.data.datasets[0].pointBackgroundColor.push(signalColor); 
                                    signalChart.update();
                                    currentIndex++;
                                } else {
                                    // Si se alcanza el final de los datos, detener la simulación
                                    clearInterval(intervalID);
                                }
                            }, 1000);
                            //graficar(respuesta);
                        } ,
                        error: function(xhr, status, error)
                        {
                            console.log(error);
                        }
                    });
                });

                //Simulacion con funciones Random
                
                $('#random').on('click', function(event) {
                    event.preventDefault();

                    if (signalChart) {
                        // Si ya hay un gráfico, destruirlo
                        signalChart.destroy();
                    }

                    function generarDatoAleatorio(min, max) {
                        return Math.random() * (max - min) + min;
                    }

                    function generarFechaAleatoria() {
                        const start = new Date(2022, 1, 1);
                        const end = new Date(2022, 5, 31);
                        return new Date(start.getTime() + Math.random() * (end.getTime() - start.getTime()));
                    }

                    function generarSimulacionTrading() {
                        const datosSimulacion = [];

                        for (let i = 0; i < 50; i++) {
                            const dato = {
                            "Adj Close": generarDatoAleatorio(150, 200),
                            "Close": generarDatoAleatorio(160, 170),
                            "Date": generarFechaAleatoria().toISOString().split('T')[0],
                            "High": generarDatoAleatorio(170, 180),
                            "Low": generarDatoAleatorio(150, 160),
                            "Open": generarDatoAleatorio(160, 170),
                            "Shares": Math.floor(generarDatoAleatorio(50, 100)),
                            "Signal": "",
                            "Volume": Math.floor(generarDatoAleatorio(10000000, 20000000))
                            };

                            datosSimulacion.push(dato);
                        }

                        datosSimulacion.sort(function(a, b){
                            const dateA = new Date(a.Date);
                            const dateB = new Date(b.Date);
                            return dateA.getTime() - dateB.getTime();
                        })

                        return {
                            modified_data: datosSimulacion
                        };
                    }

                    // Ejemplo de uso
                    const simulacion = generarSimulacionTrading();
                    modified_data = Object.values(simulacion.modified_data);
                    console.log(modified_data)
                    const jsonData = JSON.stringify(modified_data);
                    let capital = $('#rcapital').val();

                    $.ajax({
                        type: 'POST',
                        url: 'rsimular.php',
                        data: {data: jsonData, capital: capital},
                        dataType: 'json',
                        success: function(response){
                            console.log(response);
                            // Actualizar la gráfica cada segundo
                            intervalID = setInterval(function() {
                                if (currentIndex < modified_data.length) {
                                    // Agregar un punto al gráfico
                                    signalChart.data.labels.push(response[currentIndex]['Date']);
                                    signalChart.data.datasets[0].data.push(response[currentIndex]['Close']);
                                    let signalColor = response[currentIndex]['Signal'] === 'BUY' ? 'rgba(0, 143, 57)' : 'rgba(255, 0, 0, 1)';
                                    signalChart.data.datasets[0].pointBackgroundColor.push(signalColor); 
                                    signalChart.update();
                                    currentIndex++;
                                } else {
                                    // Si se alcanza el final de los datos, detener la simulación
                                    clearInterval(intervalID);
                                }
                            }, 1000);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log('Error', textStatus, errorThrown);
                        }
                    })

                    // Crear el gráfico con Chart.js
                    signalChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels:[],
                            datasets: [{
                                label: 'Precio Accion Random ',
                                data: [],
                                backgroundColor: 'rgba(0, 123, 255, 0.5)',
                                borderColor: 'rgba(0, 123, 255, 1)',
                                borderWidth: 1,
                                pointRadius: 3,
                                pointBackgroundColor: [],
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
                    
                });

                $('#stop').on('click', function(event) {
                    event.preventDefault();
                    // Detener la simulación
                    clearInterval(intervalID);
                });

            });             
            
        </script>
        
        <!-- Bootstrap core JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Third party plugin JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>        
        <!-- Core theme JS-->
        <script src="https://startbootstrap.github.io/startbootstrap-freelancer/js/scripts.js"></script>
    </body>
</html>
