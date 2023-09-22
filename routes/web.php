<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
	return view('welcome');
});

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\ChangePassword;
use App\Http\Controllers\NeedRequests\NeedRequestController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\UserVoluntary\VolunteerRegistrationController;
use App\Http\Controllers\Voluntary\VoluntaryController;
use App\Http\Controllers\WhatsApp\DialogsTemplatesController;
use App\Http\Controllers\WhatsAppController;
use App\Http\Controllers\ZApiWebHookController;


Route::get('/enviarMensagem', [WhatsAppController::class, 'enviarMensagemPersonalizada']);



Route::get('/', function () {
	return redirect('/admin/needRequests');
})->middleware('auth');
Route::get('/register', [RegisterController::class, 'create'])->middleware('guest')->name('register');
Route::get('/register-voluntary', [VolunteerRegistrationController::class, 'create'])->middleware('guest');
Route::post('/register-voluntary-store', [VolunteerRegistrationController::class, 'store'])->middleware('guest')->name('register.voluntary.store');
Route::post('/register', [RegisterController::class, 'store'])->middleware('guest')->name('register.perform');
Route::get('/login', [LoginController::class, 'show'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.perform');
Route::get('/reset-password', [ResetPassword::class, 'show'])->middleware('guest')->name('reset-password');
Route::post('/reset-password', [ResetPassword::class, 'send'])->middleware('guest')->name('reset.perform');
Route::get('/change-password', [ChangePassword::class, 'show'])->middleware('guest')->name('change-password');
Route::post('/change-password', [ChangePassword::class, 'update'])->middleware('guest')->name('change.perform');
Route::get('/dashboard', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::group(['middleware' => 'auth'], function () {
	Route::get('/virtual-reality', [PageController::class, 'vr'])->name('virtual-reality');
	Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
	Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');
	Route::get('/profile-static', [PageController::class, 'profile'])->name('profile-static');
	Route::get('/sign-in-static', [PageController::class, 'signin'])->name('sign-in-static');
	Route::get('/cadastrovoluntario', [PageController::class, 'signup'])->name('sign-up-static');
	Route::get('/{page}', [PageController::class, 'index'])->name('page');
	Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});

Route::group(['middleware' => 'auth', 'prefix' => 'admin'], function () {
	Route::resources([
		'voluntary' => VoluntaryController::class,
		'user' => UserController::class,
		'needRequests' => NeedRequestController::class,
		'dialog-whatsapp' => DialogsTemplatesController::class,
	]);
	Route::get('aprove-voluntary-index', [VolunteerRegistrationController::class, 'index'])->name('register.voluntary.index');
	Route::get('aprove-voluntary-show/{id}', [VolunteerRegistrationController::class, 'show'])->name('register.voluntary.show');
	Route::put('aprove-voluntary-update/{id}', [VolunteerRegistrationController::class, 'update'])->name('register.voluntary.update');
	Route::post('voluntary/{id}/aprove', [VoluntaryController::class, 'aprove'])->name('voluntary.aprove');
});
