"use strict";

import {createPerson, editPerson} from "@/components/person/person";
import {setSearch, setOrder} from "@/components/people/people";

let hasPopstate = false;

export default function initHistory() {
    window.addEventListener("popstate", () => updateDocument());
    document.body.addEventListener("personcreate", (event) => {
        if (hasPopstate === false) {
            updateHistory(event.detail.historyState, event.detail.url);
        }
    });
    document.body.addEventListener("personedit", (event) => {
        if (hasPopstate === false) {
            updateHistory(event.detail.historyState, event.detail.url);
        }
    });
}

function updateHistory(historyState, url) {
    window.history.pushState(
        historyState,
        null,
        url
    );
}

async function updateDocument() {
    if (!history.state) {
        location.reload();
        return;
    }

    hasPopstate = true;

    const obj = history.state;
    switch (history.state.person.type) {
        case "create": {
            await createPerson();
            break;
        }
        case "edit": {
            await editPerson(obj.person.id);
            break;
        }
        default: {
            break;
        }
    }

    setSearch();
    setOrder();

    hasPopstate = false;
}
