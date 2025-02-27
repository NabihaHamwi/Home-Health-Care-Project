<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HealthcareProviderWorktime>
 */
class HealthcareProviderWorktimeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    private static $usedDays = [];
    public function definition(): array
    {
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        $providerId = $this->faker->numberBetween(1, 101);

        // تأكد من وجود مصفوفة تتبع للأيام المستخدمة لهذا مقدم الرعاية
        if (!isset(self::$usedDays[$providerId])) {
            self::$usedDays[$providerId] = [];
        }

        // اختيار يوم عشوائي لم يتم استخدامه من قبل
        do {
            $day = $this->faker->randomElement($days);
        } while (in_array($day, self::$usedDays[$providerId]));

        // أضف اليوم إلى قائمة الأيام المستخدمة لهذا مقدم الرعاية
        self::$usedDays[$providerId][] = $day;

        $startHour = $this->faker->numberBetween(8, 15); // توليد ساعات العمل من 8 صباحًا حتى 3 مساءً
        $workHours = $this->faker->numberBetween(1, 8); // عدد ساعات العمل بين 1 و 8 ساعات

        $startTime = Carbon::createFromTime($startHour, 0, 0); // ضبط وقت البداية
        $endTime = $startTime->copy()->addHours($workHours); // حساب وقت النهاية

        return [
            'healthcare_provider_id' => $this->faker->numberBetween(1, 101), // تأكد من صحة التوافق مع healthcare_providers
            'day_name' => $this->faker->randomElement($days),
            'work_hours' => (strtotime($endTime) - strtotime($startTime)) / 3600,
            'start_time' => $startTime,
            'end_time' => $endTime,
        ];
    }
}
