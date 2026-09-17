<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\FacilitiesController;
use App\Http\Controllers\GuardianController;
use App\Http\Controllers\LessonPeriodController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\MasterMapelController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MySchoolController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\RombelController;
use App\Http\Controllers\RombelStudentController;
use App\Http\Controllers\SchoolGalleryController;
use App\Http\Controllers\SchoolMajorController;
use App\Http\Controllers\SchoolMapelController;
use App\Http\Controllers\SchoolsController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TeacherScheduleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing.index');
Route::get('/login', [LoginController::class, 'index'])->name('auth.index');
Route::post('login', [LoginController::class, 'store'])->name('auth.login');
Route::post('logout', [LoginController::class, 'logout'])->name('auth.logout');

Route::middleware('auth')->group(function(){
    Route::resource('dashboard', DashboardController::class);
    Route::middleware(['role:superadmin'])->group(function () {
        Route::resource('menus', MenuController::class);
        Route::resource('roles', RolesController::class);
        Route::resource('schools', SchoolsController::class);
        Route::resource('admins', AdminController::class);
        Route::resource('mapels', MasterMapelController::class);
        Route::resource('majors', MajorController::class);
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::resource('my-school', MySchoolController::class);
        Route::get('my-school/cities/{provinceId}', [MySchoolController::class, 'cities'])->name('my-school.cities');
        Route::get('my-school/districts/{cityId}', [MySchoolController::class, 'districts'])->name('my-school.districts');
        Route::get('my-school/villages/{districtId}', [MySchoolController::class, 'villages'])->name('my-school.villages');
        Route::resource('facilities', FacilitiesController::class);
        Route::resource('students', StudentController::class);
        Route::resource('teachers', TeacherController::class);
        Route::resource('galleries', SchoolGalleryController::class);
        Route::get('/school_mapel', [SchoolMapelController::class, 'index'])->name('school_mapel.index');
        Route::get('/school_mapel/create', [SchoolMapelController::class, 'create'])->name('school_mapel.create');
        Route::post('/school_mapel', [SchoolMapelController::class, 'store'])->name('school_mapel.store');
        Route::get('/school_mapel/edit', [SchoolMapelController::class, 'edit'])->name('school_mapel.edit');
        Route::put('/school_mapel', [SchoolMapelController::class, 'update'])->name('school_mapel.update');
        Route::delete('/school_mapel/{school_mapel}', [SchoolMapelController::class, 'destroy'])->name('school_mapel.destroy');
        Route::get('/school_majors', [SchoolMajorController::class, 'index'])->name('school_majors.index');
        Route::get('/school_majors/create', [SchoolMajorController::class, 'create'])->name('school_majors.create');
        Route::post('/school_majors', [SchoolMajorController::class, 'store'])->name('school_majors.store');
        Route::get('/school_majors/edit', [SchoolMajorController::class, 'edit'])->name('school_majors.edit');
        Route::put('/school_majors', [SchoolMajorController::class, 'update'])->name('school_majors.update');
        Route::delete('/school_majors/{school_major}', [SchoolMajorController::class, 'destroy'])->name('school_majors.destroy');
        Route::resource('rombels', RombelController::class);
        Route::resource('rombels.students', RombelStudentController::class)->only(['index', 'create', 'store', 'destroy']);
        Route::resource('attendances', AttendanceController::class);
        Route::resource('lesson-periods', LessonPeriodController::class);
        Route::resource('teacher-schedules', TeacherScheduleController::class);
        Route::resource('guardians', GuardianController::class);
    });
});
