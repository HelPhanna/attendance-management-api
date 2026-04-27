<?php

use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\AttendanceRuleController;
use App\Http\Controllers\AttendanceRecordController;
use App\Http\Controllers\AttendanceReportExportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlacklistController;
use App\Http\Controllers\ClassTeacherController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\ClassSessionController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\GradeLevelController;
use App\Http\Controllers\GradeLevelSubjectController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\SchoolSettingController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TermController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\UserRoleController;
use App\Actions\User\ForgotPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ─── Public Auth Routes ───────────────────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('register',         [AuthController::class, 'store']);
    Route::post('login',            [AuthController::class, 'login']);
    Route::post('forgot-password',  function (Request $request) {
        return app(ForgotPassword::class)->execute($request);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('index',      [AuthController::class, 'index']);
        Route::put('update',     [AuthController::class, 'update']);
        Route::get('show/{id}',  [AuthController::class, 'show']);
        Route::post('logout',    [AuthController::class, 'logout']);
    });
});

// ─── All Protected Routes ─────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // User Profile
    Route::prefix('user-profile')->group(function () {
        Route::post('/create',   [UserProfileController::class, 'store']);
        Route::get('/show',      [UserProfileController::class, 'show']);
        Route::put('/update',    [UserProfileController::class, 'update']);
        Route::delete('/delete', [UserProfileController::class, 'destroy']);
    });

    // Permissions CRUD — read: any authenticated user; write: super_admin or admin only
    Route::prefix('permissions')->group(function () {
        Route::get('/',             [PermissionController::class, 'index']);
        Route::get('/{permission}', [PermissionController::class, 'show']);

        Route::middleware(['role:super_admin,admin'])->group(function () {
            Route::post('/',              [PermissionController::class, 'store']);
            Route::put('/{permission}',   [PermissionController::class, 'update']);
            Route::delete('/{permission}',[PermissionController::class, 'destroy']);
        });
    });

    // Roles CRUD — read: any authenticated user; write: super_admin or admin only
    Route::prefix('roles')->group(function () {
        Route::get('/',       [RoleController::class, 'index']);
        Route::get('/{role}', [RoleController::class, 'show']);

        Route::middleware(['role:super_admin,admin'])->group(function () {
            Route::post('/create',       [RoleController::class, 'store']);
            Route::put('/update/{role}', [RoleController::class, 'update']);
            Route::delete('/{role}',     [RoleController::class, 'destroy']);
        });
    });

    // User-Role Management — super_admin or admin only
    Route::middleware(['role:super_admin,admin'])->prefix('user-roles')->group(function () {
        Route::get('/',        [UserRoleController::class, 'index']);
        Route::post('/create', [UserRoleController::class, 'store']);
        Route::post('/update', [UserRoleController::class, 'update']);
        Route::post('/delete', [UserRoleController::class, 'destroy']);
        Route::get('/{id}',    [UserRoleController::class, 'show']);
    });

    // Role-Permission Management — super_admin or admin only
    Route::middleware(['role:super_admin,admin'])->prefix('rolespermissions')->group(function () {
        Route::get('/',    [RolePermissionController::class, 'index']);
        Route::post('/',   [RolePermissionController::class, 'store']);
        Route::put('/',    [RolePermissionController::class, 'update']);
        Route::delete('/', [RolePermissionController::class, 'destroy']);
    });

    // Classes CRUD
    Route::prefix('classes')->group(function () {
        Route::get('/',               [ClassesController::class, 'index']);
        Route::post('/create',        [ClassesController::class, 'store']);
        Route::get('/{class}',        [ClassesController::class, 'show']);
        Route::put('/update/{class}', [ClassesController::class, 'update']);
        Route::delete('/{class}',     [ClassesController::class, 'destroy']);
    });

    // Grade Levels
    Route::prefix('grade-levels')->group(function () {
        Route::get('/',            [GradeLevelController::class, 'index']);
        Route::post('/create',     [GradeLevelController::class, 'store']);
        Route::get('/{id}',        [GradeLevelController::class, 'show']);
        Route::put('/update/{id}', [GradeLevelController::class, 'update']);
        Route::delete('/{id}',     [GradeLevelController::class, 'destroy']);
    });

    // Grade Level Subjects
    Route::prefix('grade-level-subjects')->group(function () {
        Route::get('/',            [GradeLevelSubjectController::class, 'index']);
        Route::post('/create',     [GradeLevelSubjectController::class, 'store']);
        Route::get('/{id}',        [GradeLevelSubjectController::class, 'show']);
        Route::put('/update/{id}', [GradeLevelSubjectController::class, 'update']);
        Route::delete('/{id}',     [GradeLevelSubjectController::class, 'destroy']);
    });

    // Blacklist CRUD
    Route::prefix('blacklists')->group(function () {
        Route::get('/',                   [BlacklistController::class, 'index']);
        Route::post('/create',            [BlacklistController::class, 'store']);
        Route::get('/{blacklist}',        [BlacklistController::class, 'show']);
        Route::put('/update/{blacklist}', [BlacklistController::class, 'update']);
        Route::delete('/{blacklist}',     [BlacklistController::class, 'destroy']);
    });

    // Class Teacher CRUD
    Route::prefix('class-teachers')->group(function () {
        Route::get('/',                      [ClassTeacherController::class, 'index']);
        Route::post('/create',               [ClassTeacherController::class, 'store']);
        Route::get('/{classTeacher}',        [ClassTeacherController::class, 'show']);
        Route::put('/update/{classTeacher}', [ClassTeacherController::class, 'update']);
        Route::delete('/{classTeacher}',     [ClassTeacherController::class, 'destroy']);
    });

    // Students CRUD
    Route::prefix('students')->group(function () {
        Route::get('/',                 [StudentController::class, 'index']);
        Route::post('/create',          [StudentController::class, 'store']);
        Route::get('/{student}',        [StudentController::class, 'show']);
        Route::put('/update/{student}', [StudentController::class, 'update']);
        Route::delete('/{student}',     [StudentController::class, 'destroy']);
    });

    // Teachers (admin only)
    Route::middleware(['role:admin'])->prefix('teachers')->group(function () {
        Route::get('/',                  [TeacherController::class, 'index']);
        Route::get('/{teacher}',         [TeacherController::class, 'show']);
        Route::put('/update/{teacher}',  [TeacherController::class, 'update']);
    });

    // Enrollments CRUD
    Route::prefix('enrollments')->group(function () {
        Route::get('/',                         [EnrollmentController::class, 'index']);
        Route::post('/create',                  [EnrollmentController::class, 'store']);
        Route::get('/classes/{class}/students', [EnrollmentController::class, 'listClassStudents']);
        Route::get('/{enrollment}',             [EnrollmentController::class, 'show']);
        Route::put('/update/{enrollment}',      [EnrollmentController::class, 'update']);
        Route::delete('/{enrollment}',          [EnrollmentController::class, 'destroy']);
    });

    // Academic Years CRUD
    Route::prefix('academic-year')->group(function () {
        Route::get('/',                 [AcademicYearController::class, 'index']);
        Route::post('/',                [AcademicYearController::class, 'store']);
        Route::get('/{id}',             [AcademicYearController::class, 'show']);
        Route::put('/{academicYear}',   [AcademicYearController::class, 'update']);
        Route::delete('/all',           [AcademicYearController::class, 'destroyAll']);
        Route::delete('/',              [AcademicYearController::class, 'destroyMulti']);
        Route::delete('/{academicYear}',[AcademicYearController::class, 'destroy']);
    });

    // Terms CRUD
    Route::prefix('term')->group(function () {
        Route::get('/',           [TermController::class, 'index']);
        Route::post('/',          [TermController::class, 'store']);
        Route::get('/{id}',       [TermController::class, 'show']);
        Route::put('/{term}',     [TermController::class, 'update']);
        Route::delete('/all',     [TermController::class, 'destroyAll']);
        Route::delete('/',        [TermController::class, 'destroyMulti']);
        Route::delete('/{idTerm}',[TermController::class, 'destroy']);
    });

    // Class Sessions CRUD
    Route::prefix('class-session')->group(function () {
        Route::get('/',                  [ClassSessionController::class, 'index']);
        Route::post('/',                 [ClassSessionController::class, 'store']);
        Route::get('/{id}',              [ClassSessionController::class, 'show']);
        Route::put('/{classSession}',    [ClassSessionController::class, 'update']);
        Route::delete('/all',            [ClassSessionController::class, 'destroyAll']);
        Route::delete('/',               [ClassSessionController::class, 'destroyMulti']);
        Route::delete('/{classSession}', [ClassSessionController::class, 'destroy']);
    });

    // Settings
    Route::prefix('settings')->group(function () {
        Route::get('/school', [SchoolSettingController::class, 'show']);
        Route::put('/school', [SchoolSettingController::class, 'update']);

        Route::get('/attendance-rules', [AttendanceRuleController::class, 'show']);
        Route::put('/attendance-rules', [AttendanceRuleController::class, 'update']);
    });

    // Attendance Records
    Route::prefix('attendance-records')->group(function () {
        Route::get('/filter', [AttendanceRecordController::class, 'filter']);
        Route::get('/',       [AttendanceRecordController::class, 'index']);
        Route::get('/{id}',   [AttendanceRecordController::class, 'show']);
        Route::post('/',      [AttendanceRecordController::class, 'store']);
        Route::put('/',       [AttendanceRecordController::class, 'update']);
        Route::delete('/',    [AttendanceRecordController::class, 'destroy']);
    });

    // Report Export (PDF / XLSX)
    Route::prefix('report-export')->group(function () {
        Route::get('/history', [AttendanceReportExportController::class, 'history']);
        Route::get('/{id}/download', [AttendanceReportExportController::class, 'download'])
            ->whereNumber('id');
        Route::post('/{format}', [AttendanceReportExportController::class, 'export'])
            ->where('format', 'pdf|xlsx');
    });

}); // end auth:sanctum
