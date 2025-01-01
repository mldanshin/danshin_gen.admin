"use strict";

import {spinnerOn, spinnerOff} from "@/components/spinner";
import writeLog from "@/utils/logger";
import sendRequest from "@/utils/request";

export async function createMarriage(nodeAttach) {
    spinnerOn();

    try {
        const genderId = document.getElementById("person-gender").value;

        const response = await sendRequest(
            route('part.person.marriage.create') + "?gender_id=" + genderId
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

export async function createMarriageAviable(select) {
    spinnerOn();

    try {
        if (select.dataset.type !== "person-marriages-role") {
            return;
        }

        let query = "?temp_id=" + select.dataset.tempId;

        const personId = document.getElementById("person-id")?.value;
        if (personId) {
            query += "&person_id=" + personId;
        }

        query += "&birth_date=" + document.getElementById("person-birth-date")?.value;
        query += "&role_id=" + select.value;
        query += queryParents(document.getElementById("person-form"));
        
        const response = await sendRequest(
            route('part.person.marriage_aviable.create') + query
        );

        const container = document.getElementById("person-marriage-aviable-container-" + select.dataset.tempId);
        container.innerHTML = await response.text();

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

function queryParents(form) {
    let query = "";
    for (const element of form.elements) {
        if (element.name.indexOf("parents") === 0
            && element.name.indexOf("person") > 0
        ) {
            query += "&parents[]=" + element.value;
        }
    }
    return query;
}
