"use strict";

import {changePeople} from "@/components/people/people";

document.getElementById("content")
    ?.addEventListener("personstore", (event) => {
        changePeople();
    });

document.getElementById("content")
    ?.addEventListener("personupdate", (event) => {
        changePeople();
    });

document.getElementById("content")
    ?.addEventListener("persondelete", (event) => {
        changePeople();
    });