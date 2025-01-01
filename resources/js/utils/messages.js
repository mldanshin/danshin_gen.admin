"use strict";

export function errorDefault() {
    return document.getElementById("message-error-default")?.textContent;
}

export function error302() {
    return document.getElementById("message-error-302")?.textContent;
}

export function error422() {
    return document.getElementById("message-error-422")?.textContent;
}

export function confirmationEdit() {
    return document.getElementById("confirmation-edit")?.textContent;
}

export function confirmationDeletion() {
    return document.getElementById("confirmation-deletion")?.textContent;
}

export function saveOk() {
    return document.getElementById("save-ok")?.textContent;
}

export function deleteOk() {
    return document.getElementById("delete-ok")?.textContent;
}
