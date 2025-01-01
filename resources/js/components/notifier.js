"use strict";

export default function notify(message) {
    const container = document.getElementById("toast-container");

    const elem = document.getElementById("toast");
    if (elem) {
        elem.remove();
    }

    let elemNew = document.createElement("div");
    elemNew.id = "toast";
    elemNew.classList.add("toast");
    elemNew.innerHTML = message;
    container.append(elemNew);
}