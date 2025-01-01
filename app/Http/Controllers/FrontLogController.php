<?php

namespace App\Http\Controllers;

use App\Http\Requests\FrontLogRequest;
use Illuminate\Support\Facades\Log;

final class FrontLogController extends Controller
{
    public function __invoke(FrontLogRequest $request): void
    {
        Log::error('Error frontend. '.$request->getMessage());
    }
}
