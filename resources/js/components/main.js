"use strict";

import {createPerson, storePerson, editPerson, updatePerson, deletePerson} from "@/components/person/person";
import sendRequest from "@/utils/request";
import {spinnerOn, spinnerOff} from "@/components/spinner";
import writeLog from "@/utils/logger";

document.getElementById("main")
    ?.addEventListener("click", (event) => {
        if (event.target?.dataset.type === "person-create") {
            event.preventDefault();
            createPerson(event);
        }
    });

document.getElementById("main")
    ?.addEventListener("submit", (event) => {
        if (event.target?.dataset.type === "person-store") {
            event.preventDefault();
            storePerson(event.target);
        }
    });

document.getElementById("main")
    ?.addEventListener("personstore", (event) => {
        editPerson(event.detail);
    });

document.getElementById("main")
    ?.addEventListener("submit", (event) => {
        if (event.target?.dataset.type === "person-update") {
            event.preventDefault();
            updatePerson(event.target);
        }
    });

document.getElementById("main")
    ?.addEventListener("personupdate", (event) => {
        
    });

document.getElementById("main")
    ?.addEventListener("click", (event) => {
        if (event.target?.dataset.type === "person-delete") {
            event.preventDefault();
            deletePerson(event.target.dataset.personId);
        }
    });

document.getElementById("main")
    ?.addEventListener("persondelete", (event) => {
        createIndex();
    });

async function createIndex() {
    spinnerOn();

    try {
        const response = await sendRequest(route("part.index"));

        main.innerHTML = "";
        main.insertAdjacentHTML("afterbegin", await response.text());

        const urlCurrent = new URL(window.location.href);
        const urlNext = new URL(route("index"));
        history.pushState({people: "create"}, null, urlNext.pathname + urlCurrent.search);
    } catch (error) {
        writeLog(error);
    } finally {
        spinnerOff();
    }
}
