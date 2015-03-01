var keyCode = "note_";
function initNoteBoard() {
    "use strict";
    window.addEventListener("storage", onStorageEvent, false);
    var btnAddNote = document.getElementById("btnAddNote");
    var btnUpdateNote = document.getElementById("btnUpdateNote");
    btnAddNote.addEventListener("click", addNote, false);
    btnUpdateNote.addEventListener("click", updateNote, false);
    updateNoteBoard();
}
function onStorageEvent(eventObj) {
    "use strict";
    if (eventObj.storageArea == localStorage) {
        alert(eventObj.key + " changed from '" + eventObj.oldValue + "' to '" + eventObj.newValue + "'");
        updateNoteBoard();
    }
}
function addNote() {
    "use strict";
    var numNotes = parseInt(localStorage.getItem(keyCode + "numNotes"));
    if (isNaN(numNotes)) {
        numNotes = 0;
    }
    var noteKey = keyCode + numNotes;
    var noteValue = document.getElementById("note").value;
    ++numNotes;
    localStorage.setItem(keyCode + "numNotes", numNotes);
    localStorage.setItem(keyCode + (numNotes-1), noteValue);
    updateNoteBoard();
}
function changeNote(noteKey) {
    "use strict";
    document.getElementById("oldKey").value = noteKey;
    document.getElementById("oldNote").value = localStorage.getItem(noteKey);
    document.getElementById("updateNote").style.display = "block";
    document.getElementById("addNote").style.display = "none";
}
function updateNote() {
    "use strict";
    var key = document.getElementById("oldKey").value;
    var note = document.getElementById("oldNote").value;
    localStorage.setItem(key, note);
    document.getElementById("updateNote").style.display = "none";
    document.getElementById("addNote").style.display = "block";
    document.getElementById("oldKey").value = "";
    document.getElementById("oldNote").value = "";
    updateNoteBoard();
}
function removeNote(noteKey) {
    "use strict";
    var numNotes = parseInt(localStorage.getItem(keyCode + "numNotes"));
    var keyIdx = parseInt(noteKey.substring(keyCode.length, noteKey.length));
    for (var i = keyIdx; i < numNotes; i++) {
        localStorage.setItem(keyCode + i, localStorage.getItem(keyCode + (i + 1)));
    }
    --numNotes;
    localStorage.setItem(keyCode + "numNotes", numNotes);
    localStorage.removeItem(keyCode + numNotes);
    updateNoteBoard();
}
function clearAllNotes() {
    "use strict";
    var numNotes = parseInt(localStorage.getItem(keyCode + "numNotes"));
    if (isNaN(numNotes)) {
        numNotes = 0;
    }
    for (var i = 0; i < numNotes; i++) {
        localStorage.removeItem(keyCode + i);
    }
    localStorage.setItem(keyCode + "numNotes", "0");
    updateNoteBoard();
}
function updateNoteBoard() {
    "use strict";
    var noteBoard = document.getElementById("noteBoard");
    var numNotes = parseInt(localStorage.getItem(keyCode + "numNotes"));
    if (isNaN(numNotes)) {
        numNotes = 0;
    }
    var notes = "<div>My Notes</div>";
    var key = "";
    var value = "";
    for (var i = 0; i < numNotes; i++) {
        key = keyCode + i;
        value = localStorage.getItem(key);
        notes += '<div><p>' + value + '</p><div class="buttons">';
        notes += '<button onclick=changeNote("' + key + '");>Change</button>';
        notes += '<button onclick=removeNote("' + key + '");>Remove</button>' +
            '</div>' +
            '</div>';
    }
    notes += '<div class="notes"><button id="clearAllNotes">Remove All Notes</button></div>';
    noteBoard.innerHTML = notes;
    var btnClearAllNotes = document.getElementById("clearAllNotes");
    btnClearAllNotes.addEventListener("click", clearAllNotes, false);
}

window.addEventListener("load", initNoteBoard, false);