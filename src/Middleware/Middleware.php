<?php

namespace Adplay\Middleware;

use Adplay\Handler\Request;

interface Middleware{
    public function handle(Request $request);
}