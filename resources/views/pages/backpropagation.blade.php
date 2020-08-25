@extends('layouts.backpropagation')

@section('title', 'Training Data')
    
@section('content')
<div class="container-fluid">
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </div>
    @endif
    <div class="row">
        <div class="col-md-7 col-sm-7">
            <form action="{{ route('traindata') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="neuron_input">Neuron Input</label>
                    <input type="hidden" name="testing" value="4">
                    <input type="number" name="input_layer" class="form-control" id="neuron_input" readonly value="4">
                </div>
        
                <div class="form-group">
                    <label for="neuron_hidden">Neuron Hidden</label>
                    <input type="number" name="hidden_layer" class="form-control" id="neuron_hidden" required>
                </div>
                <div class="form-group">
                    <label for="neuron_output">Neuron Output</label>
                    <input type="number" name="output_layer" class="form-control" id="neuron_output" readonly value="1">
                </div>
                <div class="form-group">
                    <label for="learning_rate">Learning Rate</label>
                    <select name="learning_rate" id="learning_rate" class="form-control select2" style="width: 100%;" required>
                        <option disabled selected>Pilih</option>
                        <option value="0.2">0.2</option>
                        <option value="0.3">0.3</option>
                        <option value="0.4">0.4</option>
                        <option value="0.5">0.5</option>
                        <option value="0.6">0.6</option>
                        <option value="0.8">0.8</option>
                  </select>
                </div>
                <div class="form-group">
                    <label for="error_tolerance">Error Tolerance</label>
                    <select name="error_tolerance" id="error_tolerance" class="form-control select2" style="width: 100%;" required>
                        <option  disabled selected>Pilih</option>
                        <option value="0.005">0.005</option>
                        <option value="0.001">0.001</option>
                        <option value="0.0005">0.0005</option>
                        <option value="0.0001">0.0001</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="epoch">Epoch</label>
                    <select id="epoch" name="epoch" class="form-control select2" style="width: 100%;" required>
                        <option value="" disabled selected>Pilih</option>
                        <option value="10000">10000</option>
                        <option value="30000">30000</option>
                        <option value="50000">50000</option>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">Training Data</button>
                </div>
            </form>
        </div>
        <div class="col-md-5 col-sm-5 mt-4" style="overflow-y: scroll; max-height: 600px;">
            <h6>Input Hidden</h6>
            @if (isset($IHWeight))
                @foreach ($IHWeight as $IHWeights)
                    @foreach ($IHWeights as $InputHidden)
                        {{ $InputHidden }}
                    @endforeach
                @endforeach
            @endif
            
            

            <h6 class="mt-4">Bias Hidden</h6>
            @if (isset($IHBias))
                @foreach ($IHBias as $IHBiass)
                    @foreach ($IHBiass as $biasH)
                        {{ $biasH }}
                    @endforeach
                @endforeach
            @endif
            

            <h6 class="mt-4">Hidden Output</h6>
            @if (isset($HOWeight))
                @foreach ($HOWeight as $HOWeights)
                    @foreach ($HOWeights as $HiddenOutput)
                        {{ $HiddenOutput }}
                    @endforeach
                @endforeach  
            @endif
            
            <h6 class="mt-4">Bias Output</h6>
            @if (isset($HOBias))
                @foreach ($HOBias as $HOBiass)
                    @foreach ($HOBiass as $biasO)
                        {{ $biasO }}
                    @endforeach
                @endforeach
            @endif
            
        </div>
    </div>
</div>
@endsection