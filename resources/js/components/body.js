"use strict";

const themes = {
    "light": {
        "type": "light",
        "className": "light-theme",
        "theme-toggle-img": {
            "src": "/img/layout/sun.svg"
        },
        "people-toggle-visibility-img": {
            "src": "/img/people/close-light.svg"
        },
    },
    "dark": {
        "type": "dark",
        "className": "dark-theme",
        "theme-toggle-img": {
            "src": "/img/layout/moon.svg"
        },
        "people-toggle-visibility-img": {
            "src": "/img/people/close-dark.svg"
        }
    }
};

document.getElementById("theme-toggle").addEventListener("click", changeTheme);
const body = document.body;

let currentTheme = themes.dark;

function changeTheme() {
    if (currentTheme.type === "dark") {
        currentTheme = themes.light;

        body.classList.add(currentTheme.className);
        body.classList.remove(themes.dark.className);
    } else {
        currentTheme = themes.dark;

        body.classList.add(currentTheme.className);
        body.classList.remove(themes.light.className);
    }

    document.querySelectorAll(".theme-icon").forEach(element => {
        element.src = currentTheme[element.id].src;
    });
}
