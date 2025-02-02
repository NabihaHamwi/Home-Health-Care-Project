<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HealthcareProvider;
use App\Models\User;
use App\Models\Document;
use App\Models\Service;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends UserController
{
  protected function validateProviderData(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'role' => 'required|in:provider,employee',
      'national_number' => 'required|string|unique:healthcare_providers',
      'age' => 'required|integer',
      'min_working_hours_per_day' => ['required', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
      'relationship_status' => 'required|in:أعزب,متزوج,أرمل,مطلق,-',
      'experience' => 'required|integer',
      'personal_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
      'physical_strength' => 'required|in:basic,advanced,professional',
      'license_number' => 'required|string|unique:healthcare_providers',
      'document_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

    ]);

    if ($validator->fails()) {
      response()->json(['errors' => $validator->errors()], 400)->send();
      exit;
    }
    return null;
  }
  /***********************************************************/


  //add healthcareprovider
  public function addProvider(Request $request)
  {
    DB::beginTransaction();
    try {
      $registerResponse = parent::register($request);

      // json decode to register function response
      $responseContent  = json_decode($registerResponse->getContent());

      if ($registerResponse->status() == 200) {
        $this->validateProviderData($request);
        $newuser = $responseContent->data;
        $personal_img_path = null;
        if ($request->hasFile('personal_image')) {
          $folder = 'personal_images';
          $personal_img_path = $this->savePersonalImage($request->file('personal_image'), $folder);
        }
        $createProvider = HealthcareProvider::create([
          'user_id' => $newuser->id,
          'national_number' => $request->national_number,
          'age' => $request->age,
          'min_working_hours_per_day' => $request->min_working_hours_per_day,
          'relationship_status' => $request->relationship_status,
          'experience' => $request->experience,
          'personal_image' => $personal_img_path,
          'license_number' => $request->license_number
        ]);
        $documentImages = 'null';
        // التحقق من وجود صور المستند
        if ($request->hasFile('document_image')) {
          $this->saveDocumentImage($request->file('document_image'), $createProvider->id);
        }
        DB::commit();

        return response()->json([
          'data' => $createProvider,
          'documentImage' => $documentImages
        ]);
      }
    } catch (\Exception $e) {
      DB::rollBack();
      return response()->json(['message' => 'فشل في إضافة مقدم الرعاية', 'error' => $e->getMessage()], 500);
    }
  }
  public function savePersonalImage(\Illuminate\Http\UploadedFile $image, $folder)
  {
    // التحقق من صلاحية الملف
    if ($image->isValid()) {
      $path = $folder . '/' . uniqid() . '.' . $image->getClientOriginalExtension();
      Storage::disk('public')->put($path, file_get_contents($image));
      return $path; // إعادة المسار لاستخدامه في تخزين المسار في قاعدة البيانات
    } else {
      return response()->json(['message' => 'الملف غير صالح'], 400);
    }
  }

  public function saveDocumentImage($imagePath, $providerId)
  {
    $folder = 'DocumentImage';
    $image = $this->savePersonalImage($imagePath, $folder);
    Document::create([
      'healthcare_provider_id' => $providerId,
      'document_image' => $image,
    ]);
    return response()->json(['message' => 'Document image saved successfully', 'path' => $imagePath], 201);
  }
}
