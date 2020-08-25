@extends('layouts.data')

@section('title', 'Data Saham')
    
@section('content')

<div class="container-fluid">
  <h4 class="mb-4 text-gray-800">Data Stock UNVR</h4>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
          <div class="row">
            <div class="col-md-6 col-sm-3 align-self-center">
                <h6 class="m-0 font-weight-bold text-primary">Data Training</h6>
            </div>
            <div class="col-md-6 col-sm-3 text-right">
                  <a href="{{ route('data.create') }}" class="btn btn-primary">Create Data Training</a>
            </div>
          </div>
      </div>
      <div class="card-body" style="overflow-y: scroll; max-height: 400px;">
        <div class="table-responsive">
          <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Date</th>
                <th>Open</th>
                <th>High</th>
                <th>Low</th>
                <th>Close</th>
                <th>Volume</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($train as $trains)
                <tr>
                  <td>{{ $trains->date }}</td>
                  <td>{{ $trains->open }}</td>
                  <td>{{ $trains->high }}</td>
                  <td>{{ $trains->low }}</td>
                  <td>{{ $trains->close }}</td>
                  <td>{{ $trains->volume }}</td>
                </tr>
              @endforeach
              
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="card shadow mb-4">
      <div class="card-header py-3">
          <div class="row">
            <div class="col-md-6 col-sm-3 align-self-center">
                <h6 class="m-0 font-weight-bold text-primary">Data Testing</h6>
            </div>
            <div class="col-md-6 col-sm-3 text-right">
                  <a href="{{ route('testing.create') }}" class="btn btn-primary">Create Data Testing</a>
            </div>
          </div>
      </div>
      <div class="card-body" style="overflow-y: scroll; max-height: 400px;">
        <div class="table-responsive">
          <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Date</th>
                <th>Open</th>
                <th>High</th>
                <th>Low</th>
                <th>Close</th>
                <th>Volume</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($test as $tests)
              <tr>
                <td>{{ $tests->date }}</td>
                <td>{{ $tests->open }}</td>
                <td>{{ $tests->high }}</td>
                <td>{{ $tests->low }}</td>
                <td>{{ $tests->close }}</td>
                <td>{{ $tests->volume }}</td>
              </tr>  
              @endforeach
              
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
  <!-- /.container-fluid -->
@endsection