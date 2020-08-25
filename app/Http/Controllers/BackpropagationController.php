<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BackpropagationRequest;
use App\Create;
use DB;

class BackpropagationController extends Controller
{

    private $neuron_input;
    private $neuron_hidden;
    private $neuron_output;
    private $bias;
    private $learning_rate;
    private $error_tolerance;
    private $epoch;
    private $IHWeight;
    private $IHBias;
    private $HOWeight;
    private $HOBias;
    private $max_data;
    private $min_data;
    private $data_saham;
    private $normalization_train;
    private $row_input;
    private $target_output;
    private $error;
    private $z_in;
    private $y_in;
    private $deltaIH;
    private $deltaIHB;
    private $deltaHO;
    private $deltaHOB;

    public function index(Request $request) {
        return view('pages.backpropagation');
    }

    public function traindata(BackpropagationRequest $request) {
        ini_set('max_execution_time', 3600); 
        $this->neuron_input = $request->input('input_layer');
        $this->neuron_hidden = $request->input('hidden_layer');
        $this->neuron_output = $request->input('output_layer');
        $this->bias = 1;
        $this->learning_rate = $request->input('learning_rate');
        $this->error_tolerance = $request->input('error_tolerance');
        $this->epoch = $request->input('epoch');
        

        $data_tr = BackpropagationController::data_tr();
        $init_rand = BackpropagationController::init_rand();
        $normalization_tr = BackpropagationController::normalization_tr();
        for ($epoch=0; $epoch<$this->epoch; $epoch++) { 
            $error_sum = 0.00;
            $totData = 0;
            for ($data=0; $data <count($data_tr); $data++) { 
                $number = 1;
                for ($i=0; $i<$this->neuron_input; $i++) { 
                    $this->row_input[$i] = $this->normalization_train[$data]['X'.$number];
                    $number++;
                }
                $this->target_output = $this->normalization_train[$data]['X'.$number];
                BackpropagationController::FeedForward();
                BackpropagationController::Backpropagation();
                BackpropagationController::UpdateWeight();

                $error_sum = $error_sum + (pow($this->error, 2));
            }
            $mse = $error_sum/$data;
            if($mse<$this->error_tolerance) {
                break;
            }
        }
        setcookie('IHWeight', json_encode($this->IHWeight), time()+3600);
        setcookie('IHBias', json_encode($this->IHBias), time()+3600);
        setcookie('HOWeight', json_encode($this->HOWeight), time()+3600);
        setcookie('HOBias', json_encode($this->HOBias), time()+3600);
        setcookie('neuron_input', json_encode($this->neuron_input), time()+3600);
        setcookie('neuron_hidden', json_encode($this->neuron_hidden), time()+3600);
        setcookie('neuron_output', json_encode($this->neuron_output), time()+3600);

        return view('pages.backpropagation', [
            'IHWeight' => $this->IHWeight,
            'IHBias' => $this->IHBias,
            'HOWeight' => $this->HOWeight,
            'HOBias' => $this->HOBias,
        ]); 
    }

    public function data_tr() {
        $data = Create::orderBy('date', 'asc')->get();

        $loop=1;
        $previousColumn = 0;
        for($i=0; $i<count($data); $i++) {  
            if($loop==1) {
                $previousColumn = $data[$i]['open']-10;
                $loop++;
            }

            $data_tr[] = array(
                'X1' => $data[$i]["open"],
                'X2' => $data[$i]["high"],
                'X3' => $data[$i]["low"],
                'X4' => $data[$i]["close"],
                'X5' => $previousColumn
            );

            $previousColumn = $data[$i]["open"];
        }
        $this->data_saham = $data_tr; 
        return $data_tr;
    }

    public function init_rand() {
        for($i=0; $i<$this->neuron_input; $i++) {
            for($j=0; $j<$this->neuron_hidden; $j++) {
                $weightIH[$i][$j] = (rand()/getrandmax()*1)-0.5;
            }
        }
        for ($i=0; $i<$this->bias; $i++) { 
            for ($j=0; $j<$this->neuron_hidden; $j++) { 
                $biasIH[$i][$j] = (rand()/getrandmax()*1)-0.5;
            }
        } 
        for($i=0; $i<$this->neuron_hidden; $i++) {
            for ($j=0; $j<$this->neuron_output; $j++) { 
                $weightHO[$i][$j] = (rand()/getrandmax()*1)-0.5;
            }
        }

        for ($i=0; $i<$this->bias; $i++) { 
            for ($j=0; $j<$this->neuron_output; $j++) { 
                $biasHO[$i][$j] = (rand()/getrandmax()*1)-0.5;
            }
        }
        $this->IHWeight = $weightIH;
        $this->IHBias = $biasIH;
        $this->HOWeight = $weightHO;
        $this->HOBias = $biasHO;
    }

    public function max_min_tr() {
        $x_open = Create::max('open');
        $x_high = Create::max('high');
        $x_low = Create::max('low');
        $x_close = Create::max('close');
        $x_target = Create::max('target');
        $max_data_tr = max(array($x_open, $x_high, $x_low, $x_close, $x_target));
        
        $n_open = Create::min('open');
        $n_high = Create::min('high');
        $n_low = Create::min('low');
        $n_close = Create::min('close');
        $n_target = Create::min('target');
        $min_data_tr = min(array($n_open, $n_high, $n_low, $n_close, $n_target));

        $this->max_data = $max_data_tr;
        $this->min_data = $min_data_tr;
    }

    public function normalization_tr() {

        BackpropagationController::max_min_tr();
        foreach ($this->data_saham as $key => $value) {
            $normalization_tr[] = array(
                'X1'=>(0.8*($this->data_saham[$key]['X1']-$this->min_data)/($this->max_data-$this->min_data)), 
                'X2'=>(0.8*($this->data_saham[$key]['X2']-$this->min_data)/($this->max_data-$this->min_data)), 
                'X3'=>(0.8*($this->data_saham[$key]['X3']-$this->min_data)/($this->max_data-$this->min_data)), 
                'X4'=>(0.8*($this->data_saham[$key]['X4']-$this->min_data)/($this->max_data-$this->min_data)), 
                'X5'=>(0.8*($this->data_saham[$key]['X5']-$this->min_data)/($this->max_data-$this->min_data))
            );
        }

        $this->normalization_train = $normalization_tr;
    }

    public function FeedForward() {
        for ($i=0; $i<$this->neuron_hidden; $i++) { 
            $sum_errors = 0.00;
            for ($j=0; $j<$this->neuron_input; $j++) { 
                $sum_errors = $sum_errors + ($this->row_input[$j]*$this->IHWeight[$j][$i]);
            }
            $sum_errors = $this->IHBias[0][$i] + $sum_errors;
            $sigmoidBiner_x[$i] = (1/(1 + exp(-$sum_errors)));
        }

        $HOSum = 0.00;
        for ($i=0; $i<$this->neuron_hidden; $i++) { 
            $HOSum = $HOSum + ($sigmoidBiner_x[$i]*$this->HOWeight[$i][0]);
        }
        $HOSum = $this->HOBias[0][0] + $HOSum;
        $sigmoidBiner_y = (1/(1 + exp(-$HOSum)));
        
        $this->z_in =  $sigmoidBiner_x;
        $this->y_in = $sigmoidBiner_y;
    }

    public function Backpropagation() {
        $this->error = ($this->target_output - $this->y_in);
        $error_value = (($this->target_output - $this->y_in) * $this->y_in * (1-$this->y_in));

        for ($i=0; $i <$this->neuron_hidden; $i++) { 
            $deltaW[$i][0] = $this->learning_rate * $error_value * $this->z_in[$i];
        }
        $biasW[0][0] = $this->learning_rate * $error_value;

        for ($i=0; $i<$this->neuron_hidden; $i++) { 
            $delta_in[$i] = ($error_value * $this->HOWeight[$i][0]);
        }

        for ($i=0; $i <$this->neuron_hidden; $i++) { 
            $delta[$i] = $delta_in[$i] * $this->z_in[$i] * (1-$this->z_in[$i]);
        }

        for ($i=0; $i<$this->neuron_input; $i++) { 
            for ($j=0; $j<$this->neuron_hidden; $j++) { 
                $deltaV[$i][$j] = $this->learning_rate * $delta[$j] * $this->row_input[$i];
            }
        }

        for ($i=0; $i<$this->neuron_hidden; $i++) { 
            $biasV[0][$i] = $this->learning_rate * $delta[$i];
        }

        $this->deltaIH = $deltaV;
        $this->deltaIHB = $biasV;
        $this->deltaHO = $deltaW;
        $this->deltaHOB = $biasW;
    }

    public function UpdateWeight() {

        for ($i=0; $i<$this->neuron_hidden; $i++) { 
            for ($j=0; $j<$this->neuron_input; $j++) { 
                $this->IHWeight[$j][$i] = $this->IHWeight[$j][$i] + $this->deltaIH[$j][$i];
            }
            $this->IHBias[0][$i] = $this->IHBias[0][$i] + $this->deltaIHB[0][$i];
        }

        for ($i=0; $i<$this->neuron_output; $i++) { 
            for ($j=0; $j<$this->neuron_hidden; $j++) { 
                $this->HOWeight[$j][$i] = $this->HOWeight[$j][$i] + $this->deltaHO[$j][$i];
            }
            $this->HOBias[0][0] = $this->HOBias[0][0] + $this->deltaHOB[0][0];
        }
    }
}
