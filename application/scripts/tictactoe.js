(function () {

    "use strict";

    var COMPUTER = false;
    var MENSCH = true;
    var WIN = 1;
    var DRAW = 0;
    var LOSS = -1;
    var CONTINUE = null;
    var LEICHT = 0;
    var MITTEL = 1;
    var SCHWER = 2;
    var container;
    var canvas;
    var context;
    var messages;
    var X;
    var O;
    var spieler = COMPUTER;
    var WERX = COMPUTER;
    var stufe = SCHWER;
    var board;
    var spielfeld = [];
    var zug = 0;
    var kodierteZuege = {
        0: { x: 0, y: 0 },
        1: { x: 1, y: 0 },
        2: { x: 2, y: 0 },
        3: { x: 0, y: 1 },
        4: { x: 1, y: 1 },
        5: { x: 2, y: 1 },
        6: { x: 0, y: 2 },
        7: { x: 1, y: 2 },
        8: { x: 2, y: 2 }
    };

    var schwierigkeitsGrade = ["leicht", "mittel", "schwer"];
    var lock = false;
    var letzteErgebnisse = [];
    var neustartButton;
    var stufenButtons;
    var wCanvas = 150;
    var hCanvas = 150;
    var wSpieler = 50;
    var hSpieler = 50;

    function zeichneSpieler(spieler, spalte, reihe) {
        var x = spalte * wSpieler;
        var y = reihe * hSpieler;
        var index;
        index = (3 * (+reihe)) + (+spalte);
        if (spielfeld[index] === null) {
            var element = spieler === WERX ? X.cloneNode(true) : O.cloneNode(true);
            element.style.top = y + "px";
            element.style.left = x + "px";
            board.appendChild(element);
            spielfeld[index] = spieler;
        } else {
            messages.innerHTML += "Feld " + index + " ist belegt!";
            spieler = !spieler;
            --zug;
        }
    };

    function testeAufSieg(feld) {
        if (feld[0] === COMPUTER && feld[1] === COMPUTER && feld[2] === COMPUTER) return WIN;
        else if (feld[3] === COMPUTER && feld[4] === COMPUTER && feld[5] === COMPUTER) return WIN;
        else if (feld[6] === COMPUTER && feld[7] === COMPUTER && feld[8] === COMPUTER) return WIN;
        else if (feld[0] === COMPUTER && feld[3] === COMPUTER && feld[6] === COMPUTER) return WIN;
        else if (feld[1] === COMPUTER && feld[4] === COMPUTER && feld[7] === COMPUTER) return WIN;
        else if (feld[2] === COMPUTER && feld[5] === COMPUTER && feld[8] === COMPUTER) return WIN;
        else if (feld[0] === COMPUTER && feld[4] === COMPUTER && feld[8] === COMPUTER) return WIN;
        else if (feld[2] === COMPUTER && feld[4] === COMPUTER && feld[6] === COMPUTER) return WIN;
        else if (feld[0] === MENSCH && feld[1] === MENSCH && feld[2] === MENSCH) return LOSS;
        else if (feld[3] === MENSCH && feld[4] === MENSCH && feld[5] === MENSCH) return LOSS;
        else if (feld[6] === MENSCH && feld[7] === MENSCH && feld[8] === MENSCH) return LOSS;
        else if (feld[0] === MENSCH && feld[3] === MENSCH && feld[6] === MENSCH) return LOSS;
        else if (feld[1] === MENSCH && feld[4] === MENSCH && feld[7] === MENSCH) return LOSS;
        else if (feld[2] === MENSCH && feld[5] === MENSCH && feld[8] === MENSCH) return LOSS;
        else if (feld[0] === MENSCH && feld[4] === MENSCH && feld[8] === MENSCH) return LOSS;
        else if (feld[2] === MENSCH && feld[4] === MENSCH && feld[6] === MENSCH) return LOSS;
        else {
            for (var i = 0; i < 9; i++) {
                if (feld[i] === null) return CONTINUE;
            }
            return DRAW;
        }
    };

// Der Mensch sucht den niedrigsten Score
// Der Computer sucht den hoechsten Scrore.
    function waehleZug(spieler, spielfeld, tiefe) {
        var feld = spielfeld.slice();
        var best = { tiefe: 99, punkte: spieler === COMPUTER ? -99 : 99 }, reply, m;
        for (var i = 0, j = 9; i < j; i++) {

            if (feld[i] === null) {
                if ((tiefe === 1 || tiefe === 2 /* || tiefe === 3 || tiefe === 4 */) && spieler === COMPUTER) {
                    if (Math.floor((Math.random() * 1000) % 10) > 5) continue;
                }
                m = {};
                m.zug = kodierteZuege[i];
                m.tiefe = tiefe;

                if (stufe !== LEICHT) {

                    if (spieler === COMPUTER && zug === tiefe) {
                        feld[i] = !spieler;
                        if (testeAufSieg(feld) === LOSS) {
                            return m;
                        }
                    }

                }

                feld[i] = spieler;
                m.punkte = testeAufSieg(feld);
                if (m.punkte === CONTINUE) m.punkte = 0;
                if ((tiefe === zug && spieler === COMPUTER && m.punkte === WIN) || (tiefe === zug + 1 && spieler === MENSCH && m.punkte === LOSS)) {
                    return m;
                }

                reply = waehleZug(!spieler, feld, tiefe + 1);
                if (spieler === COMPUTER && tiefe === zug && reply.punkte === LOSS) return reply;
                if (((spieler === COMPUTER) && (reply.punkte > best.punkte || (reply.punkte === best.punkte && reply.tiefe < best.tiefe ) || (stufe === SCHWER && tiefe === zug && m.punkte >= best.punkte) ))
                    || ((spieler === MENSCH) && (reply.punkte < best.punkte || (reply.punkte === best.punkte && reply.tiefe < best.tiefe ) || (stufe === SCHWER && tiefe === zug && m.punkte >= best.punkte)))) {
                    best = m;
                }
            }
        }
        return best;
    };


    function berechne(e) {
        var x, y, spalte, reihe, i, best;
        if (!lock && zug < 10) {
            ++zug;
            if (zug > 9) {
                return beendeMitUnentschieden();
            }
            messages.innerHTML = "Zug: " + (zug + 1) + ", Spieler: " + (spieler === COMPUTER ? "Computer" : "Mensch") + "<br>\n";
            spieler = !spieler;
            if (spieler === COMPUTER) {
                best = waehleZug(spieler, spielfeld, zug);
                zeichneSpieler(spieler, best.zug.x, best.zug.y);
            } else if (spieler === MENSCH) {
                e = e || event;
                x = e.offsetX;
                y = e.offsetY;
                for (i = 0; i < 3; i++) {
                    if (x >= i * wSpieler && x <= (i + 1) * wSpieler) {
                        spalte = i;
                    }
                    if (y >= i * hSpieler && y <= (i + 1) * hSpieler) {
                        reihe = i;
                    }
                }
                zeichneSpieler(spieler, spalte, reihe);
            }

            switch (testeAufSieg(spielfeld)) {
                case WIN:
                    beendeMitSieg();
                    break;
                case LOSS:
                    beendeMitNiederlage();
                    break;
                case DRAW:
                    beendeMitUnentschieden();
                    break;
                default:
                    if (spieler === MENSCH) {
                        // trigger computer zug
                        lock = true;
                        setTimeout(function () {
                            lock = false;
                            berechne();
                        }, 200);
                    }
            }
        } // lock
    };

    function beendeMitSieg() {
        messages.innerHTML = "Der Computer gewinnt im " + zug + ". Zug!<br>\n";
        erfrageNeuStart();
    }

    function beendeMitNiederlage() {
        messages.innerHTML = "Der Mensch gewinnt im " + zug + ". Zug!<br>\n";
        erfrageNeuStart();
    }

    function beendeMitUnentschieden() {
        messages.innerHTML = "Das Spiel endet unentschieden.<br>\n";
        erfrageNeuStart();
    }

    function erfrageNeuStart() {
        var button;

        if (!stufenButtons) {
            stufenButtons = document.createElement("div");
            stufenButtons.className = "tictactoe-stufe";
            for (var i = 0, j = 3; i < j; i++) {
                button = document.createElement("button");
                button.className = "tictactoe-stufe-button";
                button.innerHTML = schwierigkeitsGrade[i];
                button.onclick = (function (neueStufe) {
                    return function (e) {
                        stufe = neueStufe;
                        stufenButtons.parentNode.removeChild(stufenButtons);
                        neuesSpielFeld();
                    };
                }(i));
                stufenButtons.appendChild(button);
            }
        }
        if (!neustartButton) {
            neustartButton = document.createElement("button");
            neustartButton.className = "tictactoe-neustart-button";
            neustartButton.innerHTML = "Das Spiel neu starten";
            neustartButton.onclick = function () {
                neustartButton.parentNode.removeChild(neustartButton);
                neuesSpielFeld();
            };
        }
        messages.appendChild(neustartButton);
        messages.appendChild(stufenButtons);
    }

    function zeichneGitter(context) {
        var i, g;
        for (i = 1; i < 3; i++) {
            context.save();
            context.moveTo(wSpieler * i, 0);
            context.lineTo(wSpieler * i, hCanvas);
            context.stroke();
            context.restore();
        }
        for (i = 1; i < 3; i++) {
            context.save();
            context.moveTo(0, hSpieler * i);
            context.lineTo(wCanvas, wSpieler * i);
            context.stroke();
            context.restore();
        }
    };


    function ersteWorte(spieler) {
        messages.innerHTML = WERX === COMPUTER ? "X: Computer. O: Mensch.<br>\n" : "X: Mensch, O: Computer.<br>\n" + "Stufe: " + schwierigkeitsGrade[stufe] + "<br>\n";
    }

    function neuesSpielFeld() {
        board.innerHTML = "";
        zug = 0;
        spielfeld = [
            null, null, null,
            null, null, null,
            null, null, null
        ];

        if (Math.floor((Math.random() * 16) % 2) === 1) {
            spieler = COMPUTER;
            WERX = MENSCH;
            ersteWorte(!spieler);
        } else {
            spieler = MENSCH;
            WERX = COMPUTER;
            ersteWorte(!spieler);
            lock = true;
            setTimeout(function () {
                lock = false;
                berechne();
            }, 200);
        }
    };

    function init() {
        container = document.querySelector(".tictactoe");
        messages = document.createElement("div");
        messages.className = "tictactoe-messages";
        canvas = document.createElement("canvas");
        canvas.className = "tictactoe-canvas";
        canvas.width = wCanvas;
        canvas.height = hCanvas;
        canvas.className = "background";
        canvas.onclick = berechne;
        context = canvas.getContext("2d");
        zeichneGitter(context);
        container.appendChild(canvas);
        board = document.createElement("div");
        board.className = "foreground";
        container.appendChild(board);
        container.appendChild(messages);
        O = document.createElement("div");
        O.className = "sprite o figur";
        X = document.createElement("div");
        X.className = "sprite x figur";
        neuesSpielFeld();
    };

    window.addEventListener("DOMContentLoaded", function (e) {
        init(e);
    }, false);

}());
