<?php

namespace App\Http\Controllers;

use App\Exceptions\PageNotFoundException;
use App\Http\Requests\People\FilterOrderRequest as PeopleFilterOrderRequest;
use App\Http\Requests\Request;
use App\Http\Validator;
use App\Repositories\Gender as GenderRepository;
use App\Repositories\Marriage as MarriageRepository;
use App\Repositories\Order as OrderRepository;
use App\Repositories\ParentRepository;
use App\Repositories\People as PeopleRepository;
use App\Repositories\PersonCreated as PersonCreatedRepository;
use App\Repositories\PersonDeleted as PersonDeletedRepository;
use App\Repositories\PersonEditor as PersonEditorRepository;
use App\Repositories\PersonStored as PersonStoredRepository;
use App\Repositories\PersonUpdated as PersonUpdatedRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class PersonController extends Controller
{
    public function __construct(
        private PeopleRepository $peopleRepository,
        private OrderRepository $orderRepository,
        private GenderRepository $genderRepository,
        private ParentRepository $parentRepository,
        private MarriageRepository $marriageRepository
    ) {}

    public function create(
        PeopleFilterOrderRequest $request,
        PersonCreatedRepository $personRepository
    ): View {
        $person = $personRepository->get();

        return view('person.create', [
            'people' => $this->peopleRepository->show($request->getModel()),
            'orders' => $this->orderRepository->getList(),
            'peopleFilterOrderRequest' => $request->getModel(),
            'genders' => $this->genderRepository->getAll(),
            'person' => $person,
            'parentRoles' => $this->parentRepository->getRolesAll(),
            'parentsAviable' => null,
            'marriageRoles' => $this->marriageRepository->getRolesByGender($person->gender),
            'marriagesAviable' => null,
        ]);
    }

    public function store(
        Request $request,
        PersonStoredRepository $repository
    ): JsonResponse {
        return response()->json(
            $repository->store($request)
        );
    }

    public function edit(
        string $person,
        PeopleFilterOrderRequest $request,
        PersonEditorRepository $personRepository
    ): View {
        if (! Validator::requireInteger($person)) {
            throw new PageNotFoundException('');
        }

        $person = $personRepository->get($person);

        return view('person.edit', [
            'people' => $this->peopleRepository->show($request->getModel()),
            'orders' => $this->orderRepository->getList(),
            'peopleFilterOrderRequest' => $request->getModel(),
            'genders' => $this->genderRepository->getAll(),
            'person' => $person,
            'parentRoles' => $this->parentRepository->getRolesAll(),
            'parentsAviable' => $this->parentRepository->getParentsAviableByPerson($person),
            'marriageRoles' => $this->marriageRepository->getRolesByGender($person->gender),
            'marriagesAviable' => $this->marriageRepository->getAviableByPerson($person),
        ]);
    }

    public function update(
        Request $request,
        string $person,
        PersonUpdatedRepository $repository
    ): JsonResponse {
        if (! Validator::requireInteger($person)) {
            throw new PageNotFoundException('');
        }

        return response()->json(
            $repository->update($person, $request)
        );
    }

    public function destroy(
        string $person,
        PersonDeletedRepository $repository
    ): JsonResponse {
        if (! Validator::requireInteger($person)) {
            throw new PageNotFoundException('');
        }

        $repository->delete($person);

        return response()->json();
    }
}
