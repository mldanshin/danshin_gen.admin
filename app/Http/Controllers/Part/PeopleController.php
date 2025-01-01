<?php

namespace App\Http\Controllers\Part;

use App\Http\Controllers\Controller;
use App\Http\Requests\People\FilterOrderRequest;
use App\Repositories\People as PeopleRepository;
use Illuminate\Contracts\View\View;

final class PeopleController extends Controller
{
    public function __construct(private PeopleRepository $peopleRepository) {}

    public function show(FilterOrderRequest $request): View
    {
        return view('part.people.show', [
            'people' => $this->peopleRepository->show($request->getModel()),
        ]);
    }
}
