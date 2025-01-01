"use strict";

import {
    eventPersonCreate,
    eventPersonStore,
    eventPersonEdit,
    eventPersonUpdate,
    eventPersonDelete
} from "@/events/events";
import {query} from "@/components/people/people";
import createActivity from "@/components/person/activity";
import createEmail from "@/components/person/email";
import createInternet from "@/components/person/internet";
import {createMarriage, createMarriageAviable} from "@/components/person/marriage";
import createOldSurname from "@/components/person/old-surname";
import {createParent, createParentAviable} from "@/components/person/parent";
import initPatronymic from "@/components/person/patronymic";
import createPhone from "@/components/person/phone";
import {createPhoto, showPhoto, loadPhotos} from "@/components/person/photo";
import createResidence from "@/components/person/residence";
import notify from "@/components/notifier";
import {confirmationEdit, confirmationDeletion, saveOk, deleteOk} from "@/utils/messages";
import sendRequest from "@/utils/request";
import {spinnerOn, spinnerOff} from "@/components/spinner";
import writeLog from "@/utils/logger";

export function initPerson() {
    document.getElementById("person-create-old-surname")
        ?.addEventListener("click", (event) => createOldSurname(event.currentTarget));

    document.getElementById("person-create-activity")
        ?.addEventListener("click", (event) => createActivity(event.currentTarget));

    document.getElementById("person-create-email")
        ?.addEventListener("click", (event) => createEmail(event.currentTarget));

    document.getElementById("person-create-internet")
        ?.addEventListener("click", (event) => createInternet(event.currentTarget));

    document.getElementById("person-create-phone")
        ?.addEventListener("click", (event) => createPhone(event.currentTarget));

    document.getElementById("person-create-residence")
        ?.addEventListener("click", (event) => createResidence(event.currentTarget));

    document.getElementById("person-create-parent")
        ?.addEventListener("click", (event) => createParent(event.currentTarget));

    //выбор роли родителя
    document.getElementById("person-parents-container")
        ?.addEventListener("change", (event) => createParentAviable(event.target));

    document.getElementById("person-create-marriage")
        ?.addEventListener("click", (event) => createMarriage(event.currentTarget));

    //выбор роли лица в браке
    document.getElementById("person-marriages-container")
        ?.addEventListener("change", (event) => createMarriageAviable(event.target));

    document.getElementById("person-create-photo")
        ?.addEventListener("click", (event) => createPhoto(event.currentTarget));

    //выбор файла с фото
    document.getElementById("person-photo-container")
        ?.addEventListener("change", (event) => {
            if (event.target.dataset.type === "person-photo-file-button") {
                showPhoto(event.target);
            }
        });

    initPatronymic();

    document.querySelectorAll(".person-delete-item").forEach(element => {
        element.addEventListener("click", (event) => {
            event.currentTarget.parentNode.remove();
        })
    });

    loadPhotos();
}

export async function createPerson() {
    spinnerOn();

    try {
        const response = await sendRequest(route("part.person.create"));

        main.innerHTML = "";
        main.insertAdjacentHTML("afterbegin", await response.text());
        initPerson();

        const url = new URL(route("person.create"));
        document.getElementById("person-form")
            .dispatchEvent(eventPersonCreate(
                {
                    person: {
                        type: "create",
                        id: null
                    }
                },
                url.pathname + query()
            )
        );
    } catch (error) {
        writeLog(error);
    } finally {
        spinnerOff();
    }
}

export async function storePerson(form) {
    spinnerOn();

    try {
        const formData = new FormData(form);

        const response = await sendRequest(form.action, {method: 'POST', body: formData});
        const personId = await response.json();

        notify(saveOk());
        form.dispatchEvent(eventPersonStore(personId));
    } catch (error) {
        writeLog(error);
    } finally {
        spinnerOff();
    }
}

export async function editPerson(personId) {
    spinnerOn();

    try {
        const response = await sendRequest(
            route("part.person.edit", {"person": personId})
        );

        main.innerHTML = "";
        main.insertAdjacentHTML("afterbegin", await response.text());
        initPerson();

        const url = new URL(route("person.edit", {"person": personId}));
        document.getElementById("person-form")
            .dispatchEvent(eventPersonEdit(
                {
                    person: {
                        type: "edit",
                        id: personId
                    }
                },
                url.pathname + query()
            )
        );
    } catch (error) {
        writeLog(error);
    } finally {
        spinnerOff();
    }
}

export async function updatePerson(form) {
    if (!confirm(confirmationEdit())) {
        return;
    }

    spinnerOn();

    try {
        const formData = new FormData(form);
        formData.append("_method", "put");

        const response = await sendRequest(form.action, {method: 'POST', body: formData});
        const personId = await response.json();
        
        notify(saveOk());
        form.dispatchEvent(eventPersonUpdate(personId));
    } catch (error) {
        writeLog(error);
    } finally {
        spinnerOff();
    }
}

export async function deletePerson(personId) {
    if (!confirm(confirmationDeletion())) {
        return;
    }

    spinnerOn();

    try {
        const formData = new FormData();
        formData.append("_method", "delete");

        const response = await sendRequest(
            route("person.destroy", {"person": personId}),
            {method: 'POST', body: formData}
        );

        notify(deleteOk());
        main.dispatchEvent(eventPersonDelete(personId));
    } catch (error) {
        writeLog(error);
    } finally {
        spinnerOff();
    }
}
