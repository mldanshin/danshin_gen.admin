"use strict";

import sendRequest from "@/utils/request";
import {spinnerOn, spinnerOff} from "@/components/spinner";
import writeLog from "@/utils/logger";

export default async function createEmail(nodeAttach) {
    spinnerOn();

    try {
        const response = await sendRequest(
            route('part.person.email.create')
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
