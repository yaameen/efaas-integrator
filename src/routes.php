<?php

use Illuminate\Support\Facades\URL;
use eFaasIntegrator\eFaasIntegrator;

Route::get( config('efaas.app.login_url') , function ( eFaasIntegrator $eFaasIntegrator ) {
    session()->put('url.intended', request('redirect', request()->headers->get('referer')));
    return redirect( $eFaasIntegrator->buildQuery());
})->name('efaas.login_url');

Route::get( config('efaas.app.callback_url') , function () {
    return view('efaas::callback');
});

Route::get( config('efaas.post_logout_url'), function (eFaasIntegrator $eFaasIntegrator) {
    return redirect()->to($eFaasIntegrator->home());
})->name('efaas.post_logout');

Route::get('/oauth/redirect', function (eFaasIntegrator $eFaasIntegrator) {
    $eFaasIntegrator->setSession(request()->all())
            ->getUser();
    return redirect()->to($eFaasIntegrator->home());
    
})->name('efaas.callback.internal');

Route::get('/logout', function (eFaasIntegrator $eFaasIntegrator) {
    if($eFaasIntegrator->isValidState(request()->get('state'))) {
        return redirect($eFaasIntegrator->flush()->home());
    }
    return redirect()->to( $eFaasIntegrator->home() ); 
})->name('efaas.logout');

Route::post('/logout', function (eFaasIntegrator $eFaasIntegrator) {
    return  redirect()->to( $eFaasIntegrator->flush()->buildLogoutQuery() );    
})->name('efaas.logout');