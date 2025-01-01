"use strict";

export function spinnerOn() {
    let elem = document.getElementById("spinner");
    let lockScreen = document.getElementById("lock-screen");

    elem.classList.remove("spinner-hidden");
        lockScreen.classList.remove("spinner-hidden");
}

export function spinnerOff() {
    let elem = document.getElementById("spinner");
    let lockScreen = document.getElementById("lock-screen");

    elem.classList.add("spinner-hidden");
        lockScreen.classList.add("spinner-hidden");
}
