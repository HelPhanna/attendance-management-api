<?php

/**
 * PhpStorm Meta file
 *
 * This file teaches PhpStorm / Intelephense about Laravel internals that
 * static analysers cannot infer on their own.
 *
 * Specifically, it suppresses the false-positive "Undefined method 'extend'"
 * warning on Illuminate\Database\Eloquent\Builder::withGlobalScope().
 *
 * Laravel checks method_exists($scope, 'extend') at runtime before calling it,
 * so there is NO real error — the warning is purely a static-analysis artifact.
 *
 * DO NOT modify vendor/laravel/framework files to fix this warning.
 */

namespace PHPSTORM_META {

    // Tell PhpStorm that any object passed to withGlobalScope() may have extend()
    override(
        \Illuminate\Database\Eloquent\Builder::withGlobalScope(0, 1),
        map(['' => '@'])
    );
}
