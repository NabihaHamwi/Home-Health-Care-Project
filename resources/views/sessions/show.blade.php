<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    {{-- $sessionid = $session->id;
$duration = $session->duration; --}}

                    <span class="session-number"> sessionId: {{ $sessionrow->session_id }} </span>
                </div>
                <div class="col text-end">
                    <span class="session-duration"> sessionDuration: {{ $sessionrow->duration }} </span>
                </div>
            </div>
        </div>

        <div class="card-body">
            <h5 class="card-title"> العلامات الحيوية</h5>
            <div class="mb-3">
                <label></label>
                <div class="input-group">
                    @dd($activitiesnames);
                    {{-- @dd($measurementssession); --}}
                    @dd($activitiesnames);
                    {{-- @dd($measurementssession -> activity_id[0]); --}}
                    <div>{{ $activitiesnames[0]->activity_name }}</div>
                    <span class="form-control"></span>
                    <span class="form-control">measurementtime1</span>
                </div>
            </div>
            <div class="mb-3">
                <label></label>
                <div class="input-group">
                    <span class="form-control">measurementvalue2</span>
                    <span class="form-control">measurementtime2</span>
                </div>
            </div>
            <div class="mb-3">
                <label></label>
                <div class="input-group">
                    <span class="form-control">measurementvalue3</span>
                    <span class="form-control">measurementtime3</span>
                </div>
            </div>
            <div class="mb-3">
                <label></label>
                <div class="input-group">
                    <span class="form-control">measurementvalue4</span>
                    <span class="form-control">{{}}</span>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
