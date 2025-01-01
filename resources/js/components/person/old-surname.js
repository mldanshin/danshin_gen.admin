"use strict";

import {spinnerOn, spinnerOff} from "@/components/spinner";
import writeLog from "@/utils/logger";
import sendRequest from "@/utils/request";

export default async function createOldSurname(nodeAttach) {
    spinnerOn();

    try {
        const response = await sendRequest(
            route('part.person.old_surname.create')
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
