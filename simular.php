<?php
// Obtener los datos del formulario
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

    header('Content-Type: application/json');
    echo json_encode($modified_data);

} else {
    echo 'No se encontraron datos del historial de precios.';
}

//Funcion Simular trading 
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

      if ($row['Close'] < $previous_row['Close']) {
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
      } elseif ($row['Close'] > $previous_row['Close']) {
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

?>





