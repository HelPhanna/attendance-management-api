<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // RBAC - Roles and Permissions
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,

            // Users and Profiles
            UserSeeder::class,
            UserProfileSeeder::class,
            UserRoleSeeder::class,

            // Academic Structure
            AcademicYearSeeder::class,
            TermSeeder::class,
            GradeLevelSeeder::class,
            SubjectSeeder::class,
            GradeLevelSubjectSeeder::class,

            // Classes (before class sessions)
            ClassSeeder::class,

            // Students and Teachers (must be before class_teacher)
            StudentSeeder::class,
            TeacherSeeder::class,

            // Class assignments (needs classes and teachers)
            ClassTeacherSeeder::class,

            // Enrollments and Sessions (needs classes, students, teachers)
            EnrollmentSeeder::class,
            ClassSessionSeeder::class,

            // Attendance (needs class sessions and students)
            AttendanceRecordSeeder::class,
            BlacklistSeeder::class,

            // Reports
            ReportExportSeeder::class,
        ]);
    }
}
