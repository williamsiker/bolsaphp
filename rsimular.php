<?php 
// Obtener los datos enviados desde la petición AJAX
$data = $_POST['data'];
$capital = intval($_POST['capital']);

// Decodificar los datos JSON en un array asociativo
$modified_data = json_decode($data, true);

// Función para simular compra y venta de acciones
function simulate_trading($data, $capital) {
    $position = null;
    $buy_price = 0.0;
    $total_shares = 0;
    $portfolio_value = $capital;

    foreach ($data as $index => &$row) {
        if (intval($index) == 0) {
            continue;
        }

        $previous_row = $data[intval($index) - 1];

        if ($row['Close'] < $previous_row['Close']) {
            if ($position == 'SELL') {
                $portfolio_value += $total_shares * $row['Close'];
                $total_shares = 0;
            }

            $position = 'BUY';
            $buy_price = $row['Close'];
            $total_shares = intval($portfolio_value / $buy_price);
            $portfolio_value -= $total_shares * $buy_price;

            $row['Signal'] = 'BUY';
            $row['Shares'] = $total_shares;
            $row['Portfolio Value'] = $portfolio_value;
        } elseif ($row['Close'] > $previous_row['Close']) {
            if ($position == 'BUY') {
                $portfolio_value += $total_shares * $row['Close'];
                $total_shares = 0;
            }

            $position = 'SELL';
            $sell_price = $row['Close'];
            $total_shares = intval($total_shares);
            $portfolio_value += $total_shares * $sell_price;

            $row['Signal'] = 'SELL';
            $row['Shares'] = $total_shares;
            $row['Portfolio Value'] = $portfolio_value;
        }
    }

    return $data;
}

// Simular compra y venta de acciones
$modified_data = simulate_trading($modified_data, $capital);

// Devolver los datos procesados como respuesta en formato JSON
$response = json_encode($modified_data);
echo $response;
?>