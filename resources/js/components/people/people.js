"use strict";

import {editPerson} from "@/components/person/person";
import sendRequest from "@/utils/request";
import {spinnerOn, spinnerOff} from "@/components/spinner";
import writeLog from "@/utils/logger";

export function initPeople() {
    document.getElementById("people-search")?.addEventListener("input", (event) => {
        event.preventDefault();
        changePeople();
    });
    document.getElementsByName("order")?.forEach((element) => {
        element.addEventListener("change", (event) => {
            event.preventDefault();
            changePeople();
        })
    });
    document.getElementById("people-form")?.addEventListener("submit", (event) => {
        event.preventDefault();
        changePeople();
    });
    document.getElementById("people-list-container")?.addEventListener("click", (event) => {
        if (event.target.dataset.type === "person-edit") {
            event.preventDefault();
            editPerson(event.target.dataset.personId);
        }
        if (document.body.offsetWidth < 550) {
            changeVisibility();
        }
    });
    document.querySelectorAll(".people-toggle-visibility").
        forEach(element => element.addEventListener("click", () => {
            changeVisibility();
    }))
    document.addEventListener("DOMContentLoaded", () => {
        setSearch();
        setOrder();
    })
}

export async function changePeople() {
    spinnerOn();

    try {
        const container = document.getElementById("people-list-container");

        const response = await sendRequest(route('part.people.show') + query());

        container.innerHTML = "";
        container.insertAdjacentHTML("afterbegin", await response.text());
    } catch (error) {
        writeLog(error);
    } finally {
        spinnerOff();
    }
}

export function query() {
    const form = document.getElementById("people-form");
    const search = form.elements.search;
    const order = form.elements.order;

    let query = "?";
    query += "search=" + search?.value;
    query += "&order=" + order?.value;

    return query;
}

export function changeVisibility() {
    const state = document.getElementById("people-state-visibility");
    const container = document.getElementById("aside");

    if (state.checked === true) {
        state.checked = false;
        container.classList.toggle("hidden");
    } else {
        state.checked = true;
        container.classList.toggle("hidden");
    }
}

export function setSearch() {
    const search = document.getElementById("people-search");
    const url = new URL(window.location.href);
    search.value = url.searchParams.get("search");
}

export function setOrder() {
    const url = new URL(window.location.href);
    const param = url.searchParams.get("order");

    document.getElementsByName("order").forEach(element => {
        if (element.value === param) {
            element.checked = true;
        }
    });
}
