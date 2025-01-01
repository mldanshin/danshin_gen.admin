"use strict";

export default function initPatronymic() {
    const hasPatronymic = document.getElementById("person-has-patronymic");
    if (hasPatronymic) {
        setPatronymic(hasPatronymic);

        hasPatronymic.addEventListener("input", (event) => {
            setPatronymic(event.currentTarget);
        });
    }
}

function setPatronymic(hasPatronymic) {
    const patronymic = document.getElementById("person-patronymic");

    if (!hasPatronymic.checked) {
        patronymic.disabled = true;
    } else {
        patronymic.disabled = false;
    }
}
