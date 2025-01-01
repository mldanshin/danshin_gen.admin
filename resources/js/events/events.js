"use strict";

export function eventPersonCreate (historyState, url) {
    return new CustomEvent("personcreate", {
        bubbles: true,
        detail: {
            historyState: historyState,
            url: url
        }
    });
}

export function eventPersonStore (personId) {
    return new CustomEvent("personstore", {
        bubbles: true,
        detail: personId
    });
}

export function eventPersonEdit (historyState, url) {
    return new CustomEvent("personedit", {
        bubbles: true,
        detail: {
            historyState: historyState,
            url: url
        }
    });
}

export function eventPersonUpdate (personId) {
    return new CustomEvent("personupdate", {
        bubbles: true,
        detail: personId
    });
}

export function eventPersonDelete (personId) {
    return new CustomEvent("persondelete", {
        bubbles: true,
        detail: personId
    });
}
