<?php

namespace App\Http\Controllers\Part;

use App\Exceptions\PageNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Person\MarriageAviableCreatedRequest;
use App\Http\Requests\Person\MarriageCreatedRequest;
use App\Http\Requests\Person\ParentAviableCreatedRequest;
use App\Http\Requests\Person\ParentCreatedRequest;
use App\Http\Validator;
use App\Repositories\Gender as GenderRepository;
use App\Repositories\Marriage as MarriageRepository;
use App\Repositories\ParentRepository;
use App\Repositories\PersonCreated;
use App\Repositories\PersonEditor;
use App\Repositories\Photo;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class PersonController extends Controller
{
    public function create(
        PersonCreated $personRepository,
        GenderRepository $genderRepository,
        ParentRepository $parentRepository,
        MarriageRepository $marriageRepository
    ): View {
        $person = $personRepository->get();

        return view('part.person.create', [
            'genders' => $genderRepository->getAll(),
            'person' => $person,
            'parentRoles' => $parentRepository->getRolesAll(),
            'parentsAviable' => null,
            'marriageRoles' => $marriageRepository->getRolesByGender($person->gender),
            'marriagesAviable' => null,
        ]);
    }

    public function edit(
        string $person,
        PersonEditor $personRepository,
        GenderRepository $genderRepository,
        ParentRepository $parentRepository,
        MarriageRepository $marriageRepository
    ): View {
        if (! Validator::requireInteger($person)) {
            throw new PageNotFoundException('');
        }

        $person = $personRepository->get($person);

        return view('part.person.edit', [
            'genders' => $genderRepository->getAll(),
            'person' => $person,
            'parentRoles' => $parentRepository->getRolesAll(),
            'parentsAviable' => $parentRepository->getParentsAviableByPerson($person),
            'marriageRoles' => $marriageRepository->getRolesByGender($person->gender),
            'marriagesAviable' => $marriageRepository->getAviableByPerson($person),
        ]);
    }

    public function oldSurnameCreate(): View
    {
        return view('part.person.old-surname');
    }

    public function activityCreate(): View
    {
        return view('part.person.activity');
    }

    public function emailCreate(): View
    {
        return view('part.person.email');
    }

    public function internetCreate(): View
    {
        return view('part.person.internet');
    }

    public function phoneCreate(): View
    {
        return view('part.person.phone');
    }

    public function residenceCreate(): View
    {
        return view('part.person.residence');
    }

    public function parentCreate(
        ParentCreatedRequest $request,
        ParentRepository $repository
    ): View {
        return view('part.person.parent', [
            'roles' => $repository->getRolesAll(),
            'parentAviable' => $repository->getParentsAviableByRequest($request->getModel()),
        ]);
    }

    public function parentAviableCreate(
        ParentAviableCreatedRequest $request,
        ParentRepository $repository
    ): View {
        return view('part.person.parent-aviable', [
            'tempId' => $request->getTempId(),
            'parentAviable' => $repository->getParentsAviableByRequest($request->getModel()),
        ]);
    }

    public function marriageCreate(
        MarriageCreatedRequest $request,
        MarriageRepository $repository
    ): View {
        return view('part.person.marriage', [
            'genderId' => $request->getGenderId(),
            'marriageAviable' => null,
            'roles' => $repository->getRolesByGender($request->getGenderId()),
        ]);
    }

    public function marriageAviableCreate(
        MarriageAviableCreatedRequest $request,
        MarriageRepository $repository
    ): View {
        return view('part.person.marriage-aviable', [
            'tempId' => $request->getTempId(),
            'marriageAviable' => $repository->getAviableByRequest($request->getModel()),
        ]);
    }

    public function photoCreate(): View
    {
        return view('part.person.photo', [
            'photo' => null,
        ]);
    }

    public function photoFile(
        string $personId,
        string $fileName,
        Photo $photoRepository
    ): StreamedResponse {
        if (! Validator::requireInteger($personId)) {
            throw new PageNotFoundException('');
        }

        $body = $photoRepository->getFile((int) $personId, $fileName);

        return response()->streamDownload(
            function () use ($body) {
                echo $body;
            }
        );
    }
}
