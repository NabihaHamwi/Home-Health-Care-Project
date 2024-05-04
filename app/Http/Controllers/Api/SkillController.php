<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SkillResource;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    //
    public function index()
    {
        $skills = SkillResource::collection(Skill::all()); // استرجاع جميع المهارات
        if ($skills->isEmpty()) {
            $response = [
                'msg' => 'Skills not found',
                'status' => 404,
                'data' => null,
            ];
        } else {
            $response = [
                'msg' => 'تم استرجاع المهارات',
                'status' => 200,
                'data' => $skills,
            ];
        }
        return response($response); // إرسال المهارات 
    }
}
