<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    //
    public function index()
    {
        $skills = Skill::all(); // استرجاع جميع المهارات
        $response = [
            $msg = "تم استرجاع المهارات",
            $status = 200,
            $data = $skills
        ];
        return response($response); // إرسال المهارات 
    }
}