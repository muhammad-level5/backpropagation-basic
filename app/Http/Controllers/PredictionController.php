<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PredictionRequest;
use App\Testing;
use DB;

class PredictionController extends Controller
{
    private $neuron_input, $neuron_hidden, $neuron_output;
    private $IHWeight;
    private $IHBias;
    private $HOWeight;
    private $HOBias;
    private $open, $high, $low, $close, $target;
    private $data_saham;
    private $max_data, $min_data;
    private $normalization_test;
    private $row_input;
    private $target_row;
    private $y_in;

    public function index(Request $request) {
        return view('pages.prediction');
    }

    public function process(PredictionRequest $request) {
        
        $this->open = $request->input('open');
        $this->high = $request->input('high');
        $this->low = $request->input('low');
        $this->close = $request->input('close');
        $this->target = $request->input('target');
        $this->IHWeight = json_decode($_COOKIE['IHWeight']);
        $this->IHBias = json_decode($_COOKIE['IHBias']);
        $this->HOWeight = json_decode($_COOKIE['HOWeight']);
        $this->HOBias = json_decode($_COOKIE['HOBias']);
        $this->neuron_input = json_decode($_COOKIE['neuron_input']);
        $this->neuron_hidden = json_decode($_COOKIE['neuron_hidden']);
        $this->neuron_output = json_decode($_COOKIE['neuron_output']);

        $data_ts = PredictionController::data_test();
        $normalization_ts = PredictionController::normalization_ts();
        $error_sum = 0.00;
        for ($i=0; $i<count($data_ts); $i++) { 
            $number = 1;
            for ($j=0; $j<$this->neuron_input; $j++) {
                $this->row_input[$j] = $this->normalization_test[$i]['X'.$number];
                $number++;
            }
            $this->target_row = $this->normalization_test[$i]['X5'];
            PredictionController::FeedForward();
            $error = $this->target_row - $this->y_in;
            $error_sum = $error_sum + (pow($error, 2));
        }
        $mse = $error_sum/$i;
        $prediction = (($this->y_in-0.1)/0.8)*($this->max_data - $this->min_data)+$this->min_data;

        return view('pages.prediction', [
            'prediction'=>$prediction,
            'mse'=>$mse,
        ]);
    }

    public function data_test() {
        $data = Testing::orderBy('date', 'asc')->get();

        $loop=1;
        $previousColumn = 0;
        for($i=0; $i<count($data); $i++) {  
            if($loop==1) {
                $previousColumn = $data[$i]['open']-10;
                $loop++;
            }

            $data_ts[] = array(
                'X1' => $data[$i]["open"],
                'X2' => $data[$i]["high"],
                'X3' => $data[$i]["low"],
                'X4' => $data[$i]["close"],
                'X5' => $previousColumn
            );

            $previousColumn = $data[$i]["open"];
        }
         $data_ts[] = array(
            'X1' => $this->open,
            'X2' => $this->high,
            'X3' => $this->low,
            'X4' => $this->close,
            'X5' => $this->target
         );

        $this->data_saham = $data_ts; 
        return $data_ts;
    }

    public function max_min_ts() {
        $x_open = Testing::max('open');
        $x_high = Testing::max('high');
        $x_low = Testing::max('low');
        $x_close = Testing::max('close');
        $x_target = Testing::max('target');
        
        $max_data_ts = max(array($x_open, $x_high, $x_low, $x_close, $x_target));
        
        $n_open = Testing::min('open');
        $n_high = Testing::min('high');
        $n_low = Testing::min('low');
        $n_close = Testing::min('close');
        $n_target = Testing::min('target');
        $min_data_ts = min(array($n_open, $n_high, $n_low, $n_close, $n_target));

        $this->max_data = $max_data_ts;
        $this->min_data = $min_data_ts;
    }

    public function normalization_ts() {
        PredictionController::max_min_ts();
        foreach ($this->data_saham as $key => $value) {
            $normalization_ts[] = array(
                'X1'=>(0.8*($this->data_saham[$key]['X1']-$this->min_data)/($this->max_data-$this->min_data)), 
                'X2'=>(0.8*($this->data_saham[$key]['X2']-$this->min_data)/($this->max_data-$this->min_data)), 
                'X3'=>(0.8*($this->data_saham[$key]['X3']-$this->min_data)/($this->max_data-$this->min_data)), 
                'X4'=>(0.8*($this->data_saham[$key]['X4']-$this->min_data)/($this->max_data-$this->min_data)), 
                'X5'=>(0.8*($this->data_saham[$key]['X5']-$this->min_data)/($this->max_data-$this->min_data))
            );
        }
        
        $this->normalization_test = $normalization_ts;
    }

    public function FeedForward() {
        for ($i=0; $i<$this->neuron_hidden; $i++) { 
            $error_sum = 0.00;
            for ($j=0; $j<$this->neuron_input; $j++) { 
                $error_sum = $error_sum + ($this->row_input[$j] * $this->IHWeight[$j][$i]);
            }
            $error_sum = $this->IHBias[0][$i] + $error_sum;
            $z_in[$i] = (1/(1+exp(-$error_sum)));
        }

        $biasHO = 0.00;
        for ($i=0; $i<$this->neuron_hidden; $i++) { 
            $biasHO = $biasHO + ($z_in[$i]*$this->HOWeight[$i][0]);
        }
        $biasHO = $this->HOBias[0][0] + $biasHO;
        $y_output = (1/(1 + exp(-$biasHO)));
        $this->y_in = $y_output;  
    }

}
