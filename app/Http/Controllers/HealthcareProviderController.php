<?php

namespace App\Http\Controllers;

use App\Models\HealthcareProvider;
use App\Models\Skill;
use Illuminate\Http\Request;

class HealthcareProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    // تطبيق الفلاتر بناءً على سنوات الخبرة والعمر والقوة البدنية والمهارات
    $providers = HealthcareProvider::when($request->experience, function ($query, $experience) {
        return $query->where('experience', '>=', $experience);
    })->when($request->age, function ($query, $age) {
        return $query->where('age', '<=', $age);
    })->when($request->input('strength'), function ($query, $strength) {
        if (!in_array('لايهم', $strength)) {
            return $query->whereIn('physical_strength', $strength);
        }
    })->when($request->input('skills'), function ($query, $skills) {
        return $query->whereHas('skills', function ($q) use ($skills) {
            $q->whereIn('id', $skills);
        });
    })->get();

    return view('providers.index', compact('providers'));
}


    // ->where('age', '>=', $age)
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('providers.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
