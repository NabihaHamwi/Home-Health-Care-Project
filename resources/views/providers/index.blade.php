@extends('layouts.app')

@section('title')
Available Providers
@endsection

@section('content')
{{-- عرض نتائج البحث --}}
@foreach ($providers as $provider)
    <div class="provider">
        {{-- @dd($provider->skills) --}}
        <h3>الاسم: {{ $provider->user->name }}</h3>
        <p>الخبرة: {{ $provider->experience }} سنوات</p>
        <p>العمر: {{ $provider->age }}</p>
        <p>القوة البدنية : {{ $provider->physical_strength}}</p>
        <p>المهارات الخاصة:</p>
        @foreach($provider->skills as $skill)
                <p>{{$skill->skill_name}}</p>
        @endforeach
        <hr>
    </div>
@endforeach

@endsection