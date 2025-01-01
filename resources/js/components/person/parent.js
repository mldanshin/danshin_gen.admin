"use strict";

import {spinnerOn, spinnerOff} from "@/components/spinner";
import writeLog from "@/utils/logger";
import sendRequest from "@/utils/request";

export async function createParent(nodeAttach) {
    spinnerOn();

    try {
        const response = await sendRequest(
            route('part.person.parent.create')
        );

        nodeAttach.insertAdjacentHTML("beforebegin", await response.text());

        document.querySelectorAll(".person-delete-item").forEach(element => {
            element.addEventListener("click", (event) => {
                event.currentTarget.parentNode.remove();
            })
        });
    } catch (error) {
        writeLog(error);
    } finally {
        spinnerOff();
    }
}

export async function createParentAviable(select) {
    spinnerOn();

    try {
        if (select.dataset.type !== "person-parents-role") {
            return;
        }

        let query = "?temp_id=" + select.dataset.tempId;

        const personId = document.getElementById("person-id")?.value;
        if (personId) {
            query += "&person_id=" + personId;
        }
        
        query += "&birth_date=" + document.getElementById("person-birth-date")?.value;
        query += "&role_id=" + select.value;
        query += queryMarriages(document.getElementById("person-form"));

        const response = await sendRequest(
            route('part.person.parent_aviable.create') + query
        );

        if (response.ok) {
            const container = document.getElementById("person-parent-aviable-container-" + select.dataset.tempId);
            container.innerHTML = await response.text();

            document.querySelectorAll(".person-delete-item").forEach(element => {
                element.addEventListener("click", (event) => {
                    event.currentTarget.parentNode.remove();
                })
            });
        }
    } catch (error) {
        writeLog(error);
    } finally {
        spinnerOff();
    }
}

function queryMarriages(form) {
    let query = "";
    for (const element of form.elements) {
        if (element.name.indexOf("marriages") === 0
            && element.name.indexOf("soulmate") > 0
        ) {
            query += "&marriages[]=" + element.value;
        }
    }
    return query;
}
