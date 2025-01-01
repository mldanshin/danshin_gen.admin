"use strict";

import {spinnerOn, spinnerOff} from "@/components/spinner";
import writeLog from "@/utils/logger";
import sendRequest from "@/utils/request";

export async function createPhoto(nodeAttach) {
    spinnerOn();

    try {
        const response = await sendRequest(
            route('part.person.photo.create')
        );

        nodeAttach.insertAdjacentHTML("beforebegin", await response.text());

        document.querySelectorAll(".person-delete-item").forEach(element => {
            element.addEventListener("click", (event) => {
                event.currentTarget.parentNode.remove();
            })
        });
    } catch (error) {
        writeLog(error);
    } finally {
        spinnerOff();
    }
}

export function showPhoto(nodeInputFile) {
    const file = nodeInputFile.files[0];
    const id = nodeInputFile.dataset.id;

    const img = getImg();
    img.file = file;

    appendImg(id, img);

    const reader = new FileReader();
    reader.onload = (function (aImg) {
      return function (e) {
        aImg.src = e.target.result;
      };
    })(img);
    reader.readAsDataURL(file);
}

export async function loadPhotos() {
    const personId = document.getElementById("person-id")?.value;
    if (!personId) {
        return;
    }

    spinnerOn();

    try {
        const elements = document.querySelectorAll(".person-photo-file-name");
        for (const element of elements) {
            await loadPhoto(personId, element);
        }
    } catch (error) {
        writeLog(error);
    } finally {
        spinnerOff();
    }
}

async function loadPhoto(personId, node) {
    const id = node.dataset.id;

    const response = await sendRequest(
        route('part.person.photo.file', {
            "personId": personId,
            "fileName": node.value
        })
    );
    const blob = await response.blob();
    const objectURL = new Blob([blob], { type: "image/jpeg" });

    const img = getImg();
    img.src = URL.createObjectURL(objectURL);

    addFileForInput(id, blob);

    appendImg(id, img);
}

function appendImg(id, img) {
    const container = document.getElementById("person-photo-file-img-container" + id);
    if (container) {
        container.innerHTML = "";
        container.appendChild(img);
    }
}

function getImg() {
    const img = document.createElement("img");
    img.classList.add("obj");
    img.alt = "photo person";
    img.width="250";
    return img;
}

function addFileForInput(id, blob) {
    const dt  = new DataTransfer();
    dt.items.add(new File([blob], Math.random(), {type: 'image/jpeg'}));

    document.getElementById("person-photo-file" + id).files = dt.files;
}
