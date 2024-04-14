@extends('layouts.app')

@section('content')
    <h1>بحث عن مقدمي الرعاية الصحية</h1>

    <!-- نموذج البحث -->
    <form action="{{ route('providers.index') }}" method="GET">
        <div class="form-group">
            <label for="experience">سنوات الخبرة:</label>
            <input type="number" id="experience" name="experience" class="form-control"
                placeholder="أدخل الحد الأدنى لسنوات الخبرة">
            <label for="age">العمر</label>
            <input type="number" id="age" name="age" class="form-control" placeholder="أدخل الحد الأعلى للعمر">
        </div>
        <div class="form-group">
            <label for="">القوة البدنية:</label>
            <select id="strength" name="strength[]" class="form-control" multiple>
                <option value="ضعيف">ضعيف</option>
                <option value="متوسط">متوسط</option>
                <option value="قوي">قوي</option>
                <option value="لايهم">لا يهم</option>
            </select>
            <label for="">الاختصاص :</label>
            <select id="specialization" name="specialization[]" class="form-control" multiple>
                <option value="ممرض">ممرض</option>
                <option value="مرافق صحي">مرافق صحي</option>
                <option value="معالج فيزيائي">معالج فيزيائي</option>
            </select>
            <select id="skills" name="skills[]" class="form-control" multiple>
                @foreach ($skills as $skill)
                    <option value="{{ $skill->id }}">{{ $skill->skill_name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">بحث</button>
    </form>
@endsection
