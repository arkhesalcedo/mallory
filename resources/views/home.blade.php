@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('partials.side')
        <div class="col-md-9">
            <div class="panel panel-default panel-primary">
                <div class="panel-heading">Analytics</div>

                <div class="panel-body">
                    <mm-total></mm-total>
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
