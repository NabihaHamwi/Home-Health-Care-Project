<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index()
    {
        $skills = Skill::all(); // استرجاع جميع المهارات
        return view('search', compact('skills')); // إرسال المهارات إلى نموذج البحث
    }
}