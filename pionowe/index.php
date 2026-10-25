```php
<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>Flipper Zero Simulator</title>
<style>
:root {
    --orange: #ff8200;
    --black: #17130e;
    --case: #ff8200;
    --darkcase: #d86c00;
    --lcd: #ff9d24;
}
* { box-sizing: border-box; }
html, body {
    width: 100%;
    height: 100%;
    margin: 0;
    overflow: hidden;
    background: radial-gradient(ellipse at center, #393027 0%, #171411 75%);
    font-family: Arial, sans-serif;
    color: white;
    user-select: none;
    -webkit-user-select: none;
}
#stage {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
#device {
    position: relative;
    width: 320px;
    height: 800px;
    flex: 0 0 auto;
    transform-origin: center center;
    background: var(--case);
    border: 3px solid #ffad38;
    border-radius: 32px;
    box-shadow:
        inset 0 0 0 3px #e87600,
        inset 5px 5px 10px #ffb247,
        inset -6px -7px 12px #bd5900,
        0 22px 50px #000b;
}
#led {
    position: absolute;
    top: 21px;
    left: 50%;
    transform: translateX(-50%);
    width: 9px;
    height: 9px;
    background: #442400;
    border-radius: 50%;
    border: 2px solid #a95000;
}
#led.active {
    background: #40ff60;
    box-shadow: 0 0 10px #40ff60;
}
#screen-frame {
    position: absolute;
    left: 18px;
    top: 48px;
    width: 278px;
    height: 166px;
    border-radius: 9px;
    background: #39210e;
    border: 3px solid #b75c00;
    box-shadow: inset 0 0 0 3px #713800, 0 3px 4px #b35b00;
    padding: 8px;
}
#screen {
    display: block;
    width: 100%;
    height: 100%;
    background: var(--lcd);
    image-rendering: pixelated;
    border: 1px solid #e67b00;
}
#logo {
    position: absolute;
    top: 224px;
    width: 100%;
    text-align: center;
    color: #321800;
    font-weight: 900;
    letter-spacing: 3px;
    font-size: 17px;
}
#logo small {
    display: block;
    font-size: 7px;
    letter-spacing: 2px;
    margin-top: 2px;
}
#dpad {
    position: absolute;
    top: 325px;
    left: 50%;
    transform: translateX(-50%);
    width: 150px;
    height: 150px;
}
.key {
    position: absolute;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 0;
    color: #e5e5e5;
    background: linear-gradient(145deg, #444, #171717 70%);
    box-shadow: 0 5px 0 #5b3000, 0 7px 7px #6c3800, inset 0 1px 2px #777;
    cursor: pointer;
    touch-action: manipulation;
    -webkit-tap-highlight-color: transparent;
}
.key:active, .key.pressed {
    transform: translateY(3px);
    box-shadow: 0 2px 0 #5b3000, inset 0 2px 3px #111;
}
.arrow {
    width: 48px;
    height: 48px;
    font-size: 25px;
    border-radius: 8px;
}
#up { top: 0; left: 51px; }
#down { bottom: 0; left: 51px; }
#left { top: 51px; left: 0; }
#right { top: 51px; right: 0; }
#ok {
    top: 51px;
    left: 51px;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(145deg, #282828, #090909);
    font-size: 12px;
    font-weight: bold;
}
#back {
    top: 505px;
    left: 50%;
    transform: translateX(-50%);
    width: 75px;
    height: 38px;
    border-radius: 22px;
    font-size: 12px;
    letter-spacing: 1px;
}
#back:active, #back.pressed {
    transform: translate(-50%, 3px);
}
#help {
    position: fixed;
    right: 12px;
    top: 12px;
    z-index: 20;
    border: 1px solid #777;
    border-radius: 50%;
    width: 34px;
    height: 34px;
    color: white;
    background: #222;
    font-size: 18px;
    cursor: pointer;
}
#helpbox {
    position: fixed;
    z-index: 30;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    width: min(340px, 90vw);
    padding: 18px;
    border: 2px solid var(--orange);
    border-radius: 12px;
    background: #191512;
    color: white;
    line-height: 1.5;
    box-shadow: 0 10px 50px #000c;
    display: none;
}
#helpbox button {
    background: var(--orange);
    border: 0;
    border-radius: 6px;
    padding: 8px 15px;
    font-weight: bold;
}
#hint {
    position: fixed;
    bottom: 8px;
    left: 0;
    right: 0;
    text-align: center;
    color: #b8b1a7;
    font-size: 11px;
    pointer-events: none;
}
@media (max-height: 600px) {
    #hint { display: none; }
}
</style>
</head>
<body>
<div id="stage">
    <div id="device">
        <div id="led"></div>
        <div id="screen-frame">
            <canvas id="screen" width="128" height="64"></canvas>
        </div>
        <div id="logo">FLIPPER<small>ZERO</small></div>
        <div id="dpad">
            <button class="key arrow" id="up" data-key="up" aria-label="Góra">▲</button>
            <button class="key arrow" id="down" data-key="down" aria-label="Dół">▼</button>
            <button class="key arrow" id="left" data-key="left" aria-label="Lewo">◀</button>
            <button class="key arrow" id="right" data-key="right" aria-label="Prawo">▶</button>
            <button class="key" id="ok" data-key="ok">OK</button>
        </div>
        <button class="key" id="back" data-key="back">BACK</button>
    </div>
</div>

<button id="help" aria-label="Pomoc">?</button>
<div id="helpbox">
    <h3>Flipper Zero Simulator</h3>
    <p>▲ ▼ — poruszanie się po menu i grach</p>
    <p>◀ ▶ — zmiana opcji i sterowanie</p>
    <p><b>OK</b> — wybór / uruchomienie</p>
    <p><b>BACK</b> — powrót do poprzedniego ekranu</p>
    <p>Możesz też używać strzałek, Enter i Escape na klawiaturze.</p>
    <p>Funkcje radiowe, RFID i NFC są tylko symulowane.</p>
    <button id="closehelp">Zamknij</button>
</div>
<div id="hint">Strzałki + Enter + Escape · Klikaj przyciski na urządzeniu</div>

<script>
(() => {
"use strict";

const canvas = document.getElementById("screen");
const ctx = canvas.getContext("2d");
const led = document.getElementById("led");
const device = document.getElementById("device");
const helpbox = document.getElementById("helpbox");

const W = 128, H = 64;
const BG = "#ff9d24";
const FG = "#211307";

let screen = "home";
let menuIndex = 0;
let scrollOffset = 0;
let previousScreen = "home";
let currentGame = null;
let gameTimer = null;
let screenOn = true;
let helpMode = false;
let backLongPress = null;
let longBackTriggered = false;

const menu = [
    ["Sub-GHz", "subghz"],
    ["RFID", "rfid"],
    ["NFC", "nfc"],
    ["Infrared", "infrared"],
    ["GPIO", "gpio"],
    ["iButton", "ibutton"],
    ["Autor", "Autor"],
    ["Gry", "games"],
    ["18+", "18+"],
    ["Ustawienia", "settings"],
    ["O urzadzeniu", "about"]
];

const games = [
    ["Snake", "snake"],
    ["Tetris", "tetris"],
    ["Pong", "pong"]
];

function text(str, x, y, size=7, color=FG, align="left") {
    ctx.fillStyle = color;
    ctx.font = `${size}px monospace`;
    ctx.textAlign = align;
    ctx.textBaseline = "top";
    ctx.fillText(String(str), x, y);
}
function clear() {
    ctx.fillStyle = BG;
    ctx.fillRect(0, 0, W, H);
}
function frame(title, subtitle="") {
    clear();
    ctx.fillStyle = FG;
    ctx.fillRect(0, 0, W, 9);
    text(title.toUpperCase(), 3, 1, 7, BG);
    if (subtitle) text(subtitle, 125, 1, 6, BG, "right");
    ctx.fillStyle = FG;
    ctx.fillRect(0, 10, W, 1);
}
function footer(str="BACK: Powrot") {
    ctx.fillStyle = FG;
    ctx.fillRect(0, 55, W, 1);
    text(str, 3, 57, 6);
}
function drawMenu(title, items, index, hint="OK: Wybierz") {
    frame(title, `${index+1}/${items.length}`);
    if (index < scrollOffset) scrollOffset = index;
    if (index >= scrollOffset + 5) scrollOffset = index - 4;
    for (let i=0; i<5; i++) {
        const idx = scrollOffset + i;
        if (idx >= items.length) break;
        const y = 12 + i*8;
        if (idx === index) {
            ctx.fillStyle = FG;
            ctx.fillRect(1, y-1, 126, 9);
            text(">"+items[idx][0], 4, y, 7, BG);
        } else {
            text(" "+items[idx][0], 4, y, 7);
        }
    }
    footer(hint);
}
function drawHome() {
    clear();
    ctx.fillStyle = FG;
    ctx.fillRect(0,0,128,9);
    text("FLIPPER ZERO",3,1,7,BG);
    text("100%",125,1,6,BG,"right");
    // Dolphin-like pixel sprite. It is decorative and never harmed.
    const sprite = [
        "................",
        "......###.......",
        "....##...##.....",
        "...#.......#....",
        "..#..##.....#...",
        ".#..#..#.....#...",
        "#..#....#.....##.",
        ".##......#..##..#",
        "..........##....#",
        ".............####",
        "................"
    ];
    const sx=55, sy=15;
    for(let y=0;y<sprite.length;y++){
        for(let x=0;x<sprite[y].length;x++){
            if(sprite[y][x]==="#"){
                ctx.fillStyle=FG;
                ctx.fillRect(sx+x*2,sy+y*2,2,2);
            }
        }
    }
    text("Witaj!",64,41,8,FG,"center");
    text("Symulator Flippera",64,48,6,FG,"center");
    footer("OK: Menu");
}
function drawInfo(title, lines) {
    frame(title);
    lines.forEach((line,i)=>text(line,3,14+i*9,7));
    footer();
}
function drawSettings() {
    frame("Ustawienia");
    text("Motyw: Pomaranczowy",3,17);
    text("Dzwiek: Symulowany",3,28);
    text("Ekran: 128x64",3,39);
    text("Wersja: Web 1.0",3,48,6);
    footer("BACK: Powrot");
}
function render() {
    if (!screenOn) {
        clear();
        ctx.fillStyle = "#170d04";
        ctx.fillRect(0,0,W,H);
        led.classList.remove("active");
        return;
    }
    led.classList.add("active");
    switch(screen) {
        case "home": drawHome(); break;
        case "menu": drawMenu("Menu",menu,menuIndex); break;
        case "games": drawMenu("Gry",games,menuIndex,"OK: Start"); break;
        case "settings": drawSettings(); break;
        case "about":
            drawInfo("O urzadzeniu",["Flipper Zero","Symulator przegladarkowy","Funkcje sa fikcyjne","Nie steruje sprzetem"]);
            break;
        case "subghz":
            drawInfo("Sub-GHz",["Tryb demonstracyjny","Brak transmisji radiowej","Funkcja tylko symulowana","Wybierz BACK"]);
            break;
        case "rfid":
            drawInfo("RFID",["Czytnik: symulacja","Brak prawdziwego odczytu","Nie wykrywa kart"]);
            break;
        case "nfc":
            drawInfo("NFC",["NFC: symulacja","Brak dostepu do NFC","To tylko demonstracja"]);
            break;
        case "infrared":
            drawInfo("Infrared",["Pilot IR: symulacja","Brak nadajnika IR","Wybierz BACK"]);
            break;
        case "gpio":
            drawInfo("GPIO",["Piny: symulacja","Brak podlaczonego sprzetu","Tylko ekran demonstracyjny"]);
            break;
        case "ibutton":
            drawInfo("iButton",["iButton: symulacja","Brak fizycznego czytnika"]);
            break;
        case "Autor":
            drawInfo("autor",["autor: autorzy" , "@PanDzbanYT, ChatGPT"]);
            break;
        case "18+":
            drawInfo("18+",["18+: A co ty myslales cwaniaczku" , "Nieladnie nieladnie"]);
            break;
        case "snake": drawSnake(); break;
        case "tetris": drawTetris(); break;
        case "pong": drawPong(); break;
        case "gameover": drawGameOver(); break;
    }
}
function enterMenu() {
    screen = "menu";
    menuIndex = 0;
    scrollOffset = 0;
    render();
}
function openItem(item) {
    previousScreen = screen;
    if (item === "games") {
        screen = "games";
        menuIndex = 0;
        scrollOffset = 0;
    } else {
        screen = item;
    }
    render();
}
function stopGame() {
    if(gameTimer) clearInterval(gameTimer);
    gameTimer = null;
    currentGame = null;
}
function goBack() {
    if (helpbox.style.display === "block") {
        helpbox.style.display = "none";
        return;
    }
    if (!screenOn) {
        screenOn = true;
        render();
        return;
    }
    if (screen === "home") return;
    if (["snake","tetris","pong","gameover"].includes(screen)) {
        stopGame();
        screen = "games";
        menuIndex = 0;
    } else if (screen === "games" || screen === "settings" || screen === "about") {
        screen = "menu";
        menuIndex = 0;
    } else {
        screen = "menu";
        menuIndex = 0;
    }
    render();
}
function choose() {
    if (!screenOn) {
        screenOn = true;
        render();
        return;
    }
    if (screen === "home") {
        enterMenu();
    } else if (screen === "menu") {
        openItem(menu[menuIndex][1]);
    } else if (screen === "games") {
        startGame(games[menuIndex][1]);
    } else if (screen === "gameover") {
        startGame(currentGame || "snake");
    } else if (["snake","tetris","pong"].includes(screen)) {
        if (screen === "snake") resetSnake();
        if (screen === "tetris") resetTetris();
        if (screen === "pong") resetPong();
    }
}
function move(dir) {
    if (!screenOn) return;
    if (screen === "menu") {
        if(dir==="up") menuIndex=(menuIndex+menu.length-1)%menu.length;
        if(dir==="down") menuIndex=(menuIndex+1)%menu.length;
        render();
    } else if (screen === "games") {
        if(dir==="up") menuIndex=(menuIndex+games.length-1)%games.length;
        if(dir==="down") menuIndex=(menuIndex+1)%games.length;
        render();
    } else if (screen === "snake") {
        const s=currentGame;
        if(!s) return;
        if(dir==="up" && s.dy!==1) {s.dx=0;s.dy=-1;}
        if(dir==="down" && s.dy!==-1) {s.dx=0;s.dy=1;}
        if(dir==="left" && s.dx!==1) {s.dx=-1;s.dy=0;}
        if(dir==="right" && s.dx!==-1) {s.dx=1;s.dy=0;}
    } else if (screen === "tetris") {
        if(dir==="left") movePiece(-1);
        if(dir==="right") movePiece(1);
        if(dir==="down") dropPiece();
        if(dir==="up") rotatePiece();
        render();
    } else if (screen === "pong") {
        if(dir==="up") currentGame.py=Math.max(13,currentGame.py-5);
        if(dir==="down") currentGame.py=Math.min(45,currentGame.py+5);
        render();
    }
}
function action(key) {
    if(helpMode) {
        helpMode=false;
        helpbox.style.display="block";
        return;
    }
    if(key==="back") {goBack();return;}
    if(key==="ok") {choose();return;}
    move(key);
}

function startGame(name) {
    stopGame();
    screen=name;
    if(name==="snake") resetSnake();
    else if(name==="tetris") resetTetris();
    else if(name==="pong") resetPong();
}
function resetSnake() {
    screen="snake";
    currentGame={snake:[{x:8,y:4},{x:7,y:4},{x:6,y:4}],dx:1,dy:0,food:{x:15,y:4},score:0};
    placeFood();
    gameTimer=setInterval(tickSnake,145);
    render();
}
function placeFood() {
    if(!currentGame) return;
    currentGame.food={x:Math.floor(Math.random()*30),y:Math.floor(Math.random()*17)};
}
function tickSnake() {
    if(screen!=="snake"||!currentGame)return;
    const s=currentGame;
    const head={x:s.snake[0].x+s.dx,y:s.snake[0].y+s.dy};
    if(head.x<0||head.x>=32||head.y<0||head.y>=18||
       s.snake.some(p=>p.x===head.x&&p.y===head.y)){
        stopGame();
        screen="gameover";
        currentGame={lastGame:"snake",score:s.score};
        render();return;
    }
    s.snake.unshift(head);
    if(head.x===s.food.x&&head.y===s.food.y){
        s.score++;
        placeFood();
    }else s.snake.pop();
    render();
}
function drawSnake() {
    frame("Snake",`Wynik:${currentGame.score}`);
    ctx.fillStyle=FG;
    ctx.fillRect(0,11,128,43);
    ctx.fillStyle=BG;
    currentGame.snake.forEach(p=>ctx.fillRect(2+p.x*4,12+p.y*2.3,3,2));
    ctx.fillRect(2+currentGame.food.x*4,12+currentGame.food.y*2.3,3,2);
    footer("Strzalki: ruch");
}

const SHAPES = [
    [[1,1,1,1]],
    [[1,1],[1,1]],
    [[0,1,0],[1,1,1]],
    [[1,0,0],[1,1,1]],
    [[0,0,1],[1,1,1]],
    [[1,1,0],[0,1,1]],
    [[0,1,1],[1,1,0]]
];
function resetTetris() {
    screen="tetris";
    currentGame={board:Array.from({length:16},()=>Array(10).fill(0)),piece:null,x:3,y:0,score:0};
    newPiece();
    gameTimer=setInterval(tickTetris,450);
    render();
}
function newPiece() {
    const g=currentGame;
    g.piece=SHAPES[Math.floor(Math.random()*SHAPES.length)].map(r=>r.slice());
    g.x=3;g.y=0;
    if(collides(g.x,g.y,g.piece)){
        stopGame();
        screen="gameover";
        currentGame={lastGame:"tetris",score:g.score};
    }
}
function collides(x,y,piece) {
    const g=currentGame;
    for(let py=0;py<piece.length;py++) for(let px=0;px<piece[py].length;px++){
        if(!piece[py][px])continue;
        const bx=x+px, by=y+py;
        if(bx<0||bx>=10||by>=16||(by>=0&&g.board[by][bx]))return true;
    }
    return false;
}
function lockPiece() {
    const g=currentGame;
    g.piece.forEach((row,py)=>row.forEach((v,px)=>{
        if(v&&g.y+py>=0)g.board[g.y+py][g.x+px]=1;
    }));
    for(let y=15;y>=0;y--){
        if(g.board[y].every(Boolean)){
            g.board.splice(y,1);
            g.board.unshift(Array(10).fill(0));
            g.score+=10;y++;
        }
    }
    newPiece();
}
function movePiece(dx) {
    if(!currentGame||!currentGame.piece)return;
    if(!collides(currentGame.x+dx,currentGame.y,currentGame.piece))currentGame.x+=dx;
}
function dropPiece() {
    if(!currentGame||!currentGame.piece)return;
    if(!collides(currentGame.x,currentGame.y+1,currentGame.piece))currentGame.y++;
    else lockPiece();
}
function rotatePiece() {
    if(!currentGame||!currentGame.piece)return;
    const p=currentGame.piece;
    const rotated=p[0].map((_,i)=>p.map(row=>row[i]).reverse());
    if(!collides(currentGame.x,currentGame.y,rotated))currentGame.piece=rotated;
}
function tickTetris() {
    if(screen!=="tetris"||!currentGame)return;
    if(!collides(currentGame.x,currentGame.y+1,currentGame.piece))currentGame.y++;
    else lockPiece();
    render();
}
function drawTetris() {
    frame("Tetris",`Pkt:${currentGame.score}`);
    ctx.fillStyle=FG;
    ctx.fillRect(42,12,44,42);
    ctx.fillStyle=BG;
    for(let y=0;y<16;y++)for(let x=0;x<10;x++){
        if(currentGame.board[y][x])ctx.fillRect(43+x*4,13+y*2.5,3,2);
    }
    if(currentGame.piece)currentGame.piece.forEach((row,py)=>row.forEach((v,px)=>{
        if(v) {
            ctx.fillStyle=BG;
            ctx.fillRect(43+(currentGame.x+px)*4,13+(currentGame.y+py)*2.5,3,2);
        }
    }));
    footer("Gora: obr. Dol: spad.");
}
function resetPong() {
    screen="pong";
    currentGame={x:62,y:32,dx:1.5,dy:1,py:30,ai:30,score:0,opp:0};
    gameTimer=setInterval(tickPong,60);
    render();
}
function tickPong() {
    if(screen!=="pong"||!currentGame)return;
    const g=currentGame;
    g.x+=g.dx;g.y+=g.dy;
    if(g.y<13||g.y>52)g.dy*=-1;
    g.ai+=(g.y-g.ai-3)*0.08;
    if(g.x<9&&g.y>g.py&&g.y<g.py+12)g.dx=Math.abs(g.dx)*1.05;
    if(g.x>119&&g.y>g.ai&&g.y<g.ai+12)g.dx=-Math.abs(g.dx)*1.05;
    if(g.x<0){g.opp++;g.x=64;g.y=32;g.dx=1.5;}
    if(g.x>128){g.score++;g.x=64;g.y=32;g.dx=-1.5;}
    render();
}
function drawPong() {
    frame("Pong",`${currentGame.score}:${currentGame.opp}`);
    ctx.fillStyle=FG;
    ctx.fillRect(5,currentGame.py,3,12);
    ctx.fillRect(120,currentGame.ai,3,12);
    ctx.fillRect(currentGame.x,currentGame.y,3,3);
    for(let y=13;y<54;y+=5)ctx.fillRect(63,y,1,2);
    footer("Gora/Dol: paletka");
}
function drawGameOver() {
    frame("Koniec gry");
    text("GAME OVER",64,20,10,FG,"center");
    text(`Wynik: ${currentGame?.score ?? 0}`,64,34,8,FG,"center");
    text("OK: ponownie",64,45,7,FG,"center");
    footer("BACK: Gry");
}

document.querySelectorAll("[data-key]").forEach(button=>{
    button.addEventListener("click",()=>{
        const key=button.dataset.key;
        if(key==="back"&&longBackTriggered){
            longBackTriggered=false;
            return;
        }
        action(key);
    });
    button.addEventListener("pointerdown",()=>{
        button.classList.add("pressed");
        if(button.dataset.key==="back"){
            longBackTriggered=false;
            backLongPress=setTimeout(()=>{
                longBackTriggered=true;
                screenOn=!screenOn;
                if(!screenOn)stopGame();
                render();
            },1200);
        }
    });
    ["pointerup","pointercancel","pointerleave"].forEach(ev=>{
        button.addEventListener(ev,()=>{
            button.classList.remove("pressed");
            if(backLongPress){
                clearTimeout(backLongPress);
                backLongPress=null;
            }
        });
    });
});

document.addEventListener("keydown",e=>{
    if(["ArrowUp","ArrowDown","ArrowLeft","ArrowRight","Enter","Escape","Backspace"].includes(e.key))e.preventDefault();
    if(e.repeat)return;
    const map={
        ArrowUp:"up",ArrowDown:"down",ArrowLeft:"left",ArrowRight:"right",
        Enter:"ok",Escape:"back",Backspace:"back"
    };
    if(map[e.key])action(map[e.key]);
});
document.getElementById("help").addEventListener("click",()=>{
    helpbox.style.display=helpbox.style.display==="block"?"none":"block";
});
document.getElementById("closehelp").addEventListener("click",()=>helpbox.style.display="none");
document.addEventListener("keydown",e=>{
    if(e.key==="?"||e.key==="/")helpbox.style.display=helpbox.style.display==="block"?"none":"block";
});

function fitDevice() {
    const scale=Math.min((window.innerWidth-24)/320,(window.innerHeight-30)/800);
    device.style.transform=`scale(${Math.max(0.25,scale)})`;
}
window.addEventListener("resize",fitDevice);
fitDevice();
render();
})();
</script>
</body>
</html>
```
