@extends('layouts.prediction')

@section('title', 'Prediction')
    
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
            <form action="{{ route('process') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="open">Open</label>
                    <input type="number" class="form-control" id="open" name="open" required>
                </div>
                <div class="form-group">
                    <label for="high">High</label>
                    <input type="number"  class="form-control" id="high" name="high" required>
                </div>
                <div class="form-group">
                    <label for="low">Low</label>
                    <input type="number" class="form-control" id="low" name="low" required>
                </div>
                <div class="form-group">
                    <label for="close">Close</label>
                    <input type="number" class="form-control" id="close" name="close" required>
                </div>
                <div class="form-group">
                    <label for="target">Target</label>
                    <input type="number" class="form-control" id="target" name="target" required>
                </div>
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">Prediksi</button>
                  </div>
            </form>
        </div>
        <div class="col-md-5 col-sm-5 mt-4">
            <table style="width: 100%;">
                <tr>
                    <th style="width:50%;">Prediction</th>
                    <td class="text-right" style="width:50%;">
                        @if (isset($prediction))
                            {{ round($prediction, 5)  }}
                        @endif
                    </td>
                </tr>
                <tr>
                  <th style="width:50%;">MSE</th>
                  <td class="text-right" style="width:50%;">
                        @if (isset($mse))
                            {{ round($mse, 5) }}
                        @endif  
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection