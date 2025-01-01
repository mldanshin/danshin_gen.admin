<?php

namespace App\Http\Controllers\Part;

use App\Http\Controllers\Controller;
use App\Http\Requests\Person\ParentCreatedRequest;
use App\Repositories\Gender as GenderRepository;
use App\Repositories\ParentRepository;
use App\Repositories\PersonCreated;
use Illuminate\Contracts\View\View;

final class PersonController extends Controller
{
    public function create(
        PersonCreated $repository,
        GenderRepository $genderRepository,
        ParentRepository $parentRepository
    ): View {
        return view("part.person.create", [
            "genders" => $genderRepository->getAll(),
            "person" => $repository->get(),
            "parentRoles" => $parentRepository->getRolesAll(),
            "parentsAviable" => null
        ]);
    }

    public function edit(string $person)
    {
        //
    }

    public function genderCreate(): View
    {
        return view("part.person.old-surname");
    }

    public function activityCreate(): View
    {
        return view("part.person.activity");
    }

    public function emailCreate(): View
    {
        return view("part.person.email");
    }

    public function internetCreate(): View
    {
        return view("part.person.internet");
    }

    public function phoneCreate(): View
    {
        return view("part.person.phone");
    }

    public function residenceCreate(): View
    {
        return view("part.person.residence");
    }

    public function parentCreate(
        ParentCreatedRequest $request,
        ParentRepository $parentRepository
    ): View {
        return view("part.person.parent", [
            "parent" => null,
            "roles" => $parentRepository->getRolesAll(),
            "parentsAviable" => $parentRepository->getParentsAviable($request->getModel())
        ]);
    }

    public function parentAviableCreate(
        ParentCreatedRequest $request,
        ParentRepository $parentRepository
    ): View {
        return view("part.person.parent-aviable", [
            "parent" => null,
            "parentsAviable" => $parentRepository->getParentsAviable($request->getModel())
        ]);
    }
}
