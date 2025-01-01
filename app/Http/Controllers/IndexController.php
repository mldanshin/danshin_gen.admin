<?php

namespace App\Http\Controllers;

use App\Http\Requests\People\FilterOrderRequest as PeopleFilterOrderRequest;
use App\Repositories\Order as OrderRepository;
use App\Repositories\People as PeopleRepository;
use Illuminate\Contracts\View\View;

final class IndexController extends Controller
{
    public function __construct(
        private PeopleRepository $peopleRepository,
        private OrderRepository $orderRepository
    ) {}

    public function __invoke(PeopleFilterOrderRequest $request): View
    {
        return view('index', [
            'people' => $this->peopleRepository->show($request->getModel()),
            'orders' => $this->orderRepository->getList(),
            'peopleFilterOrderRequest' => $request->getModel(),
        ]);
    }
}
