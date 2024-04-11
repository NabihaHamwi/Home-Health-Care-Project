 <!doctype html>
 <html lang="en">

 <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
         integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
 </head>
 <body >
    <form method="POST" action="{{route('sessions.store')}}" >
    @csrf
        <div class="card">
         <div class="card-header">
             <div class="row">
                 <div class="col">
                     <span class="session-number"> sessionId: </span>
                 </div>
                 <div class="col text-end">
                     <span class="session-duration"> sessionDuration: </span>
                 </div>
             </div>
         </div>
         <div class="card-body">
             <h5 class="card-title"> العلامات الحيوية</h5>

          

             <div   class="mb-3">
                {{-- استرجاع اسماءالنشاطات لكل ادخال بدلا من كتابته  --}}
                 <label for="pulseInput">{{$activities[0]->activity_name}}</label>
                 <div class="input-group">
                     <input  name="measurementvalue0" class="form-control" id="pulseInput" placeholder="أدخل القيمة">
                     <input  name="measurementtime0" type="time" class="form-control" id="pulseTime" placeholder="أدخل الوقت">
                 </div>
             </div>

             <div   class="mb-3">
                <label for="pulseInput">{{$activities[1]->activity_name}}</label>
                <div class="input-group">
                    <input  name="measurementvalue1" class="form-control" id="pulseInput" placeholder="أدخل القيمة">
                    <input  name="measurementtime1" type="time" class="form-control" id="pulseTime" placeholder="أدخل الوقت">
                </div>
            </div>
            <div   class="mb-3">
                <label for="pulseInput">{{$activities[2]->activity_name}}</label>
                <div class="input-group">
                    <input  name="measurementvalue2" class="form-control" id="pulseInput" placeholder="أدخل القيمة">
                    <input  name="measurementtime2" type="time" class="form-control" id="pulseTime" placeholder="أدخل الوقت">
                </div>
            </div>

            <div   class="mb-3">
                <label for="pulseInput">{{$activities[3]->activity_name}}</label>
                <div class="input-group">
                    <input  name="measurementvalue3" class="form-control" id="pulseInput" placeholder="أدخل القيمة">
                    <input  name="measurementtime3" type="time" class="form-control" id="pulseTime" placeholder="أدخل الوقت">
                </div>
            </div>
            
            <div class="mb-3">
                
                <label for="pulseInput">{{$activities[4]->activity_name}}</label>
                <div class="input-group">
                    <input  name="measurementvalue4" class="form-control" id="pulseInput" placeholder="أدخل القيمة">
                    <input  name="measurementtime4" type="time" class="form-control" id="pulseTime" placeholder="أدخل الوقت">
                </div>
            </div>

            <div   class="mb-3">
                <label for="pulseInput">{{$activities[5]->activity_name}}</label>
                <div class="input-group">
                    <input  name="measurementvalue5" class="form-control" id="pulseInput" placeholder="أدخل القيمة">
                    <input  name="measurementtime5" type="time" class="form-control" id="pulseTime" placeholder="أدخل الوقت">
                </div>
            </div>

            <div   class="mb-3">
                <label for="pulseInput">{{$activities[6]->activity_name}}</label>
                <div class="input-group">
                    <input  name="measurementvalue6" class="form-control" id="pulseInput" placeholder="أدخل القيمة">
                    <input  name="measurementtime6" type="time" class="form-control" id="pulseTime" placeholder="أدخل الوقت">
                </div>
            </div>
             </div class="mb-3">
             <button class="btn btn-success">حفظ  </button>         </div>
     </div>

    </form>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
         integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
     </script>
 </body>

 </html>
