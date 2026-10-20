<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VeiculoController;
use App\Http\Controllers\AnuncioController;
use App\Http\Controllers\ReservaController;
use App\Models\Anuncio;
use App\Http\Controllers\CepController;

Route::get('/buscar-cep', [CepController::class, 'buscar']);

/*
|--------------------------------------------------------------------------
| Página Inicial
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    if (auth()->check()) {
        $data = Anuncio::where('user_id', '!=', auth()->id())->get();
    } else {
        $data = Anuncio::all();
    }

    return view('welcome', compact('data'));

});

Route::get('/dashboard', function () {
    return redirect('/');
})->middleware(['auth'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
*/

// VEÍCULO
Route::get('/veiculo', [VeiculoController::class, 'index']);
Route::get('/veiculo/edit/{id}', [VeiculoController::class, 'edit']);
Route::post('/veiculo/update/{id}', [VeiculoController::class, 'update']);
Route::get('/veiculo/delete/{id}', [VeiculoController::class, 'delete']);

// ANÚNCIO
Route::get('/anuncio', [AnuncioController::class, 'index']);
Route::get('/anuncio/edit/{id}', [AnuncioController::class, 'edit']);
Route::post('/anuncio/update/{id}', [AnuncioController::class, 'update']);
Route::get('/anuncio/delete/{id}', [AnuncioController::class, 'delete']);
Route::get('/anuncio/{id}', [AnuncioController::class, 'show'])
    ->whereNumber('id');

// RESERVA
Route::get('/reserva', [ReservaController::class, 'index']);
Route::get('/reserva/edit/{id}', [ReservaController::class, 'edit']);
Route::post('/reserva/update/{id}', [ReservaController::class, 'update']);
Route::get('/reserva/delete/{id}', [ReservaController::class, 'delete']);


/*
|--------------------------------------------------------------------------
| Rotas Protegidas (Login Obrigatório)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Criar Veículo
    Route::get('/veiculo/create', [VeiculoController::class, 'create']);
    Route::post('/veiculo/store', [VeiculoController::class, 'store']);

    // Criar Anúncio
    Route::get('/anuncio/create', [AnuncioController::class, 'create']);
    Route::post('/anuncio/store', [AnuncioController::class, 'store']);

    // Criar Reserva
    Route::get('/reserva/create', [ReservaController::class, 'create']);
    Route::post('/reserva/store', [ReservaController::class, 'store']);

    // Responder Reserva (Aceitar / Recusar)
    Route::post('/reserva/{id}/aceitar', [ReservaController::class, 'aceitar'])->name('reserva.aceitar');
    Route::post('/reserva/{id}/recusar', [ReservaController::class, 'recusar'])->name('reserva.recusar');

    // Cancelar Reserva (pelo Solicitante)
    Route::post('/reserva/{id}/cancelar', [ReservaController::class, 'cancelar'])->name('reserva.cancelar');

});


require __DIR__.'/auth.php';