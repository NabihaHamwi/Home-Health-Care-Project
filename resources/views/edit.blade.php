<form method="post" action="{{route('sessions.update' ,1)}}">
@csrf
@method('PUT')
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input name="title" type="text" class="form-control" value="{{old('title')}}">
        </div>
        <button class="btn btn-success">update</button>
