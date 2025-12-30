<?php




Route::fallback(function (Request $request) {
    return response()->view('errors.404', [], 404);
});

Route::get('/unauthorized', function () {
    return view('error.401');
})->name('unauthorized');





// foreach (glob(__DIR__ . '/page/*.php') as $routeFile) {
//     require $routeFile;
// }

foreach (glob(__DIR__ . '/admin-auth/*.php') as $routeFile) {
    require $routeFile;
}


Route::middleware('auth')->name('admin.')->group(function () {
    foreach (glob(__DIR__ . '/admin/*.php') as $routeFile) {
        require $routeFile;
    }
});






// Route::middleware('auth:investor')->name('investor.')->group(function () {
//     foreach (glob(__DIR__ . '/investor/*.php') as $routeFile) {
//         require $routeFile;
//     }
// });