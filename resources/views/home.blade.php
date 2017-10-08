@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-default panel-primary">
                <div class="panel-heading">Status</div>

                <div class="panel-body">
                    <ul class="list-group">
                        @foreach($jobs as $job)
                            <li class="list-group-item {{ $job->successful ? '' : 'list-group-item-danger' }}">
                                <span class="badge">{{ $job->fetch_record_count }}</span>
                                <h4 class="list-group-item-heading">
                                    {{ $job->store }}
                                </h4>
                                <p class="list-group-item-text">Last Run: {{ \Carbon\Carbon::parse($job->last_run, 'UTC')->toDayDateTimeString() }}</p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="panel panel-default panel-success">
                <div class="panel-heading">Export</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-danger">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{ route('export') }}" method="POST">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="store">Select Store</label>
                            <select class="form-control" name="store" id="store">
                                <option value="US" selected>US</option>
                                <option value="CA">CA</option>
                                <option calue="UK">UK</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="range">Select Date Range</label>
                            <input type="text" class="form-control" id="range" name="range">
                        </div>
                        
                        <button type="submit" class="btn btn-default pull-right btn-success">Download</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="panel panel-default panel-info">
                <div class="panel-heading">Stats</div>

                <div class="panel-body">
                    <mm-total></mm-total>
                    <canvas id="total"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
    <script>
        $(document).ready(function(){
            $('#range').daterangepicker({
                locale: {
                  format: 'YYYY-MM-DD'
                }
            });
            
            var total = document.getElementById('total').getContext('2d');
            var myChart = new Chart(total, {
                type: 'bar',
                data: {
                    labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
                    datasets: [{
                        label: '# of Votes',
                        data: [12, 19, 3, 5, 2, 3],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255,99,132,1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            }
                        }]
                    }
                }
            });
        });
    </script>
@endsection
