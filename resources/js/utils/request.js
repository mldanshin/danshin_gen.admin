"use strict";

import {errorDefault, error302, error422} from "@/utils/messages";
import notify from "@/components/notifier";

export default async function sendRequest(url, data = null) {
    const csrf = document.getElementsByName("csrf-token")[0].content;

    if (data !== null && data.body && csrf !== null) {
        data.body.append("_token", csrf);
    }

    let response = await fetch(url, data);

    if (response.headers.get("X-Authenticate")) {
        document.location.reload();
        throw new Error("Response error. " + response.status);
    }

    if (response.status === 302) {
        notify(error302());
        throw new Error("Response error. " + response.status);
    }

    if (response.status === 422) {
        notify(error422());
        throw new Error("Response error. " + response.status);
    }

    if (!response.ok) {
        notify(errorDefault());
        throw new Error("Response error. " + response.status);
    }

    return response;
}
