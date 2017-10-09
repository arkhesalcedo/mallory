@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('partials.side')
        <div class="col-md-9">
            <div class="panel panel-default panel-info">
                <div class="panel-heading">Stats</div>

                <div class="panel-body">
                    <mm-graph url="/stats/salesByMonth" store="US" type="line" label="Average US Sales Per Month"></mm-graph>

                    <mm-graph url="/stats/salesByMonth" store="CA" type="line" label="Average CA Sales Per Month" color="75, 192, 192"></mm-graph>

                    <mm-graph url="/stats/salesByMonth" store="UK" type="line" label="Average UK Sales Per Month" color="153, 102, 255"></mm-graph>
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
        });
    </script>
@endsection
