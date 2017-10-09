<div class="col-md-3">
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