"use strict";

import sendRequest from "@/utils/request";

export default async function writeLog(error) {
    let message = error.name + ". " + error.message + ". \r\n" + "Stack: " + error.stack;

    let formData = new FormData();
    formData.append("message", message);

    sendRequest(
        route("log"),
        {method: "POST", body: formData}
    );
}