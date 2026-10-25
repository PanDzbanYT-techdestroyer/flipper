<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>Flipper Zero — Pixel Simulator by @PanDzbanYT</title>
<style>
:root {
    --orange: #ff8a00;
    --lcd: #ff9b20;
    --black: #241508;
    --case: #ff8500;
}
* { box-sizing: border-box; }
html, body {
    width: 100%;
    height: 100%;
    margin: 0;
    overflow: hidden;
    background: #171411;
    color: white;
    font-family: monospace;
    user-select: none;
    -webkit-user-select: none;
}
#stage {
    position: fixed;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
#device {
    width: 1000px;
    height: 440px;
    position: absolute;
    flex: none;
    transform-origin: center;
    border-radius: 58px;
    background: var(--case);
    border: 5px solid #ffb13d;
    box-shadow:
        inset 0 0 0 5px #d86b00,
        inset 0 8px 12px #ffb23d,
        inset 0 -9px 12px #bd5900,
        0 28px 65px #000c;
}
#screenframe {
    position: absolute;
    width: 540px;
    height: 300px;
    left: 230px;
    top: 42px;
    padding: 13px;
    background: #39200c;
    border: 4px solid #ad5700;
    border-radius: 17px;
    box-shadow: inset 0 0 0 4px #703600, 0 4px 7px #a34e00;
}
#screen {
    display: block;
    width: 100%;
    height: 100%;
    background: var(--lcd);
    image-rendering: pixelated;
    image-rendering: crisp-edges;
}
#led {
    position: absolute;
    width: 10px;
    height: 10px;
    top: 18px;
    left: 50%;
    transform: translateX(-50%);
    border-radius: 50%;
    background: #522900;
    border: 2px solid #a95200;
}
#led.on {
    background: #42ff54;
    box-shadow: 0 0 10px #42ff54;
}
#logo {
    position: absolute;
    top: 355px;
    left: 0;
    width: 100%;
    text-align: center;
    font-size: 21px;
    font-weight: 900;
    letter-spacing: 5px;
    color: #371900;
}
#logo small {
    display: block;
    font-size: 8px;
    letter-spacing: 3px;
    margin-top: 2px;
}
#dpad {
    position: absolute;
    left: 42px;
    top: 143px;
    width: 145px;
    height: 145px;
}
.key {
    position: absolute;
    display: flex;
    justify-content: center;
    align-items: center;
    border: 0;
    color: #eee;
    background: linear-gradient(145deg, #444, #141414 75%);
    box-shadow: 0 6px 0 #633400, 0 8px 7px #773900,
                inset 0 2px 2px #777;
    cursor: pointer;
    touch-action: manipulation;
    -webkit-tap-highlight-color: transparent;
    font-family: Arial, sans-serif;
}
.key:active, .key.pressed {
    transform: translateY(4px);
    box-shadow: 0 2px 0 #633400, inset 0 2px 4px #111;
}
.arrow {
    width: 45px;
    height: 45px;
    border-radius: 9px;
    font-size: 22px;
}
#up { left: 50px; top: 0; }
#down { left: 50px; bottom: 0; }
#left { left: 0; top: 50px; }
#right { right: 0; top: 50px; }
#ok {
    width: 45px;
    height: 45px;
    left: 50px;
    top: 50px;
    border-radius: 50%;
    font-weight: bold;
    font-size: 12px;
}
#back {
    width: 70px;
    height: 35px;
    right: 45px;
    top: 30px;
    border-radius: 20px;
    font-size: 12px;
    letter-spacing: 1px;
}
#buttons {
    position: absolute;
    right: 45px;
    top: 150px;
    width: 155px;
    height: 125px;
}
.gamekey {
    width: 55px;
    height: 55px;
    border-radius: 50%;
    font-weight: bold;
    font-size: 17px;
}
#btnB { left: 0; top: 38px; }
#btnA { right: 0; top: 0; }
#help {
    position: fixed;
    z-index: 10;
    top: 12px;
    right: 12px;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: 1px solid #777;
    background: #25211c;
    color: white;
    font-size: 18px;
}
#helpbox {
    display: none;
    position: fixed;
    z-index: 20;
    top: 50%;
    left: 50%;
    transform: translate(-50%,-50%);
    width: min(360px,90vw);
    padding: 18px;
    background: #201a14;
    border: 2px solid var(--orange);
    border-radius: 12px;
    box-shadow: 0 12px 40px #000c;
    line-height: 1.5;
}
#helpbox button {
    padding: 8px 15px;
    border: 0;
    border-radius: 5px;
    background: var(--orange);
    font-weight: bold;
}
#hint {
    position: fixed;
    bottom: 8px;
    width: 100%;
    text-align: center;
    color: #aaa;
    font-size: 12px;
    pointer-events: none;
}
@media (max-height: 450px) {
    #hint { display: none; }
}
</style>
</head>
<body>
<div id="stage">
<div id="device">
    <div id="led"></div>

    <div id="dpad">
        <button class="key arrow" data-key="up" id="up">▲</button>
        <button class="key arrow" data-key="down" id="down">▼</button>
        <button class="key arrow" data-key="left" id="left">◀</button>
        <button class="key arrow" data-key="right" id="right">▶</button>
        <button class="key" data-key="ok" id="ok">OK</button>
    </div>

    <div id="screenframe">
        <canvas id="screen" width="128" height="64"></canvas>
    </div>

    <div id="buttons">
        <button class="key gamekey" id="btnB" data-key="b">B</button>
        <button class="key gamekey" id="btnA" data-key="a">A</button>
    </div>

    <button class="key" data-key="back" id="back">BACK</button>
    <div id="logo">FLIPPER<small>ZERO</small></div>
</div>
</div>

<button id="help">?</button>
<div id="helpbox">
    <h3>Flipper Zero Simulator</h3>
    <p>▲ ▼ — nawigacja / ruch</p>
    <p>◀ ▶ — ruch / zmiana kierunku</p>
    <p>OK — wybór, start i obrót klocka</p>
    <p>BACK — powrót do menu</p>
    <p>A/B — dodatkowe przyciski do gier</p>
    <p>Klawiatura: strzałki, Enter, Escape i Z/X.</p>
    <p>Funkcje radiowe są tylko symulowane.</p>
    <button id="closehelp">Zamknij</button>
</div>
<div id="hint">STRZAŁKI · ENTER · ESC · Z / X</div>

<script>
(() => {
"use strict";

const canvas = document.getElementById("screen");
const ctx = canvas.getContext("2d");
const led = document.getElementById("led");
const device = document.getElementById("device");
const helpbox = document.getElementById("helpbox");

const W = 128, H = 64;
const BG = "#ff9b20";
const FG = "#241508";

let screen = "home";
let menuIndex = 0;
let scroll = 0;
let game = null;
let timer = null;
let screenOn = true;
let longBackTimer = null;
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
    ["Ustawienia", "settings"],
    ["O urzadzeniu", "about"]
];
const games = [["Snake","snake"],["Tetris","tetris"],["Pong","pong"]];

function txt(s,x,y,size=7,color=FG,align="left") {
    ctx.fillStyle=color;
    ctx.font=`${size}px monospace`;
    ctx.textAlign=align;
    ctx.textBaseline="top";
    ctx.fillText(String(s),x,y);
}
function clear() {
    ctx.fillStyle=BG;
    ctx.fillRect(0,0,W,H);
}
function header(title,right="") {
    clear();
    ctx.fillStyle=FG;
    ctx.fillRect(0,0,W,10);
    txt(title.toUpperCase(),3,1,7,BG);
    txt(right,125,1,6,BG,"right");
    ctx.fillRect(0,10,W,1);
}
function footer(s="BACK: Powrot") {
    ctx.fillStyle=FG;
    ctx.fillRect(0,55,W,1);
    txt(s,3,57,6);
}
function drawMenu(title,items,index) {
    header(title,`${index+1}/${items.length}`);
    if(index<scroll)scroll=index;
    if(index>=scroll+5)scroll=index-4;
    for(let i=0;i<5;i++){
        const n=scroll+i;
        if(n>=items.length)break;
        const y=12+i*8;
        if(n===index){
            ctx.fillStyle=FG;
            ctx.fillRect(1,y-1,126,9);
            txt(">"+items[n][0],4,y,7,BG);
        } else txt(" "+items[n][0],4,y);
    }
    footer("OK: Wybierz");
}
function drawHome() {
    header("FLIPPER ZERO","100%");
    // Mały pikselowy delfinek. Nie ma animacji obrażeń ani śmierci.
    const dolphin=[
        "...................",
        ".......###.........",
        ".....##...##.......",
        "....#.......#......",
        "...#..##......#....",
        "..#..#..#......#...",
        ".#..#....#......##..",
        "#..#......#..###..#.",
        ".##........##.....#.",
        "...............####..",
        "..................."
    ];
    const sx=45,sy=14;
    dolphin.forEach((row,y)=>[...row].forEach((p,x)=>{
        if(p==="#") {
            ctx.fillStyle=FG;
            ctx.fillRect(sx+x*2,sy+y*2,2,2);
        }
    }));
    txt("Witaj!",64,40,8,FG,"center");
    txt("Nacisnij OK",64,48,6,FG,"center");
    footer("OK: Menu");
}
function info(title,lines) {
    header(title);
    lines.forEach((s,i)=>txt(s,3,14+i*9,7));
    footer();
}
function drawSettings() {
    header("Ustawienia");
    txt("Ekran: 128x64",3,16);
    txt("Motyw: Pomaranczowy",3,27);
    txt("Dzwiek: Symulowany",3,38);
    txt("Web Simulator",3,47,6);
    footer();
}
function stopGame() {
    if(timer!==null)clearInterval(timer);
    timer=null;
    game=null;
}
function render() {
    if(!screenOn) {
        ctx.fillStyle="#170d04";
        ctx.fillRect(0,0,W,H);
        led.classList.remove("on");
        return;
    }
    led.classList.add("on");
    switch(screen) {
        case "home":drawHome();break;
        case "menu":drawMenu("Menu",menu,menuIndex);break;
        case "games":drawMenu("Gry",games,menuIndex);break;
        case "settings":drawSettings();break;
        case "about":info("O urzadzeniu",["Flipper Zero","Symulator w przegladarce","Brak dostepu do sprzetu"]);break;
        case "subghz":info("Sub-GHz",["Tryb demonstracyjny","Brak transmisji radiowej"]);break;
        case "rfid":info("RFID",["Symulacja czytnika","Nie odczytuje prawdziwych kart"]);break;
        case "nfc":info("NFC",["Tryb symulacji","Brak dostepu do NFC"]);break;
        case "infrared":info("Infrared",["Pilot IR: symulacja","Brak nadajnika IR"]);break;
        case "gpio":info("GPIO",["Piny symulowane","Brak podlaczonego sprzetu"]);break;
        case "ibutton":info("iButton",["Symulacja iButton","Brak czytnika"]);break;
        case "Autor":info("Autor",["Autor: @PanDzbanYT","youtube.com/@PanDzbanYT"]);break;
        case "snake":drawSnake();break;
        case "tetris":drawTetris();break;
        case "pong":drawPong();break;
        case "gameover":drawGameOver();break;
    }
}
function back() {
    if(helpbox.style.display==="block") {
        helpbox.style.display="none";
        return;
    }
    if(!screenOn){screenOn=true;render();return;}
    if(screen==="home")return;
    if(["snake","tetris","pong","gameover"].includes(screen)) {
        stopGame();screen="games";menuIndex=0;
    } else if(screen==="games"||screen==="settings"||screen==="about") {
        screen="menu";menuIndex=0;
    } else {
        screen="menu";menuIndex=0;
    }
    render();
}
function choose() {
    if(!screenOn){screenOn=true;render();return;}
    if(screen==="home") {
        screen="menu";menuIndex=0;scroll=0;
    } else if(screen==="menu") {
        const item=menu[menuIndex][1];
        screen=item;
        menuIndex=0;scroll=0;
    } else if(screen==="games") {
        startGame(games[menuIndex][1]);
        return;
    } else if(screen==="gameover") {
        const name=game?.name||"snake";
        startGame(name);
        return;
    } else if(screen==="tetris") {
        rotatePiece();
    } else if(screen==="snake") {
        // OK nie resetuje gry — reset dostępny po przegranej.
    } else if(screen==="pong") {
        game.dy*=-1;
    }
    render();
}
function move(dir) {
    if(!screenOn)return;
    if(screen==="menu") {
        if(dir==="up")menuIndex=(menuIndex+menu.length-1)%menu.length;
        if(dir==="down")menuIndex=(menuIndex+1)%menu.length;
        render();
    } else if(screen==="games") {
        if(dir==="up")menuIndex=(menuIndex+games.length-1)%games.length;
        if(dir==="down")menuIndex=(menuIndex+1)%games.length;
        render();
    } else if(screen==="snake"&&game) {
        if(dir==="up"&&game.dy!==1){game.dx=0;game.dy=-1;}
        if(dir==="down"&&game.dy!==-1){game.dx=0;game.dy=1;}
        if(dir==="left"&&game.dx!==1){game.dx=-1;game.dy=0;}
        if(dir==="right"&&game.dx!==-1){game.dx=1;game.dy=0;}
    } else if(screen==="tetris"&&game) {
        if(dir==="left")movePiece(-1);
        if(dir==="right")movePiece(1);
        if(dir==="down")dropPiece();
        if(dir==="up")rotatePiece();
        render();
    } else if(screen==="pong"&&game) {
        if(dir==="up")game.py=Math.max(12,game.py-5);
        if(dir==="down")game.py=Math.min(42,game.py+5);
        render();
    }
}
function act(key) {
    if(helpbox.style.display==="block")return;
    if(key==="back"){back();return;}
    if(key==="ok"||key==="a"){choose();return;}
    if(key==="b"){back();return;}
    move(key);
}

/* SNAKE */
function startGame(name) {
    stopGame();
    screen=name;
    if(name==="snake")resetSnake();
    if(name==="tetris")resetTetris();
    if(name==="pong")resetPong();
}
function resetSnake() {
    screen="snake";
    game={name:"snake",snake:[{x:8,y:8},{x:7,y:8},{x:6,y:8}],
          dx:1,dy:0,food:{x:20,y:8},score:0};
    foodSnake();
    timer=setInterval(tickSnake,160);
    render();
}
function foodSnake() {
    game.food={x:Math.floor(Math.random()*32),y:Math.floor(Math.random()*17)};
}
function tickSnake() {
    if(screen!=="snake"||!game)return;
    const head={x:game.snake[0].x+game.dx,y:game.snake[0].y+game.dy};
    if(head.x<0||head.x>=32||head.y<0||head.y>=17||
       game.snake.some(p=>p.x===head.x&&p.y===head.y)) {
        const score=game.score;
        stopGame();
        game={name:"snake",score};
        screen="gameover";render();return;
    }
    game.snake.unshift(head);
    if(head.x===game.food.x&&head.y===game.food.y) {
        game.score++;foodSnake();
    } else game.snake.pop();
    render();
}
function drawSnake() {
    header("Snake",`Pkt:${game.score}`);
    ctx.fillStyle=FG;ctx.fillRect(0,11,128,43);
    ctx.fillStyle=BG;
    game.snake.forEach(p=>ctx.fillRect(p.x*4+1,p.y*2.45+12,3,2));
    ctx.fillRect(game.food.x*4+1,game.food.y*2.45+12,3,2);
    footer("Strzalki: ruch");
}

/* TETRIS */
const SHAPES=[
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
    game={name:"tetris",board:Array.from({length:16},()=>Array(10).fill(0)),
          piece:null,x:3,y:0,score:0};
    newPiece();
    if(screen==="tetris")timer=setInterval(tickTetris,500);
    render();
}
function collide(x,y,p) {
    if(!game)return true;
    for(let py=0;py<p.length;py++)for(let px=0;px<p[py].length;px++) {
        if(!p[py][px])continue;
        const bx=x+px,by=y+py;
        if(bx<0||bx>=10||by>=16||(by>=0&&game.board[by][bx]))return true;
    }
    return false;
}
function newPiece() {
    game.piece=SHAPES[Math.floor(Math.random()*SHAPES.length)].map(r=>r.slice());
    game.x=3;game.y=0;
    if(collide(game.x,game.y,game.piece)) {
        const score=game.score;
        stopGame();
        game={name:"tetris",score};
        screen="gameover";
    }
}
function movePiece(dx) {
    if(game&&!collide(game.x+dx,game.y,game.piece))game.x+=dx;
}
function rotatePiece() {
    if(!game||!game.piece)return;
    const p=game.piece;
    const r=p[0].map((_,i)=>p.map(row=>row[i]).reverse());
    if(!collide(game.x,game.y,r))game.piece=r;
}
function dropPiece() {
    if(!game||!game.piece)return;
    if(!collide(game.x,game.y+1,game.piece))game.y++;
    else lockPiece();
}
function lockPiece() {
    game.piece.forEach((row,py)=>row.forEach((v,px)=>{
        if(v&&game.y+py>=0)game.board[game.y+py][game.x+px]=1;
    }));
    for(let y=15;y>=0;y--) {
        if(game.board[y].every(Boolean)) {
            game.board.splice(y,1);
            game.board.unshift(Array(10).fill(0));
            game.score+=10;y++;
        }
    }
    newPiece();
}
function tickTetris() {
    if(screen!=="tetris"||!game)return;
    dropPiece();
    render();
}
function drawTetris() {
    header("Tetris",`Pkt:${game.score}`);
    ctx.fillStyle=FG;ctx.fillRect(43,12,42,42);
    ctx.fillStyle=BG;
    for(let y=0;y<16;y++)for(let x=0;x<10;x++)
        if(game.board[y][x])ctx.fillRect(44+x*4,13+y*2.5,3,2);
    if(game.piece)game.piece.forEach((row,py)=>row.forEach((v,px)=>{
        if(v)ctx.fillRect(44+(game.x+px)*4,13+(game.y+py)*2.5,3,2);
    }));
    footer("Gora: obrot Dol: spad");
}

/* PONG */
function resetPong() {
    screen="pong";
    game={name:"pong",x:62,y:32,dx:1.4,dy:0.8,py:29,ai:29,score:0,opp:0};
    timer=setInterval(tickPong,60);
    render();
}
function tickPong() {
    if(screen!=="pong"||!game)return;
    game.x+=game.dx;game.y+=game.dy;
    if(game.y<12||game.y>52)game.dy*=-1;
    game.ai+=(game.y-game.ai-3)*0.075;
    if(game.x<9&&game.y>=game.py&&game.y<=game.py+12)game.dx=Math.abs(game.dx);
    if(game.x>117&&game.y>=game.ai&&game.y<=game.ai+12)game.dx=-Math.abs(game.dx);
    if(game.x<0){game.opp++;game.x=64;game.y=32;game.dx=1.4;}
    if(game.x>128){game.score++;game.x=64;game.y=32;game.dx=-1.4;}
    render();
}
function drawPong() {
    header("Pong",`${game.score}:${game.opp}`);
    ctx.fillStyle=FG;
    ctx.fillRect(5,game.py,3,12);
    ctx.fillRect(120,game.ai,3,12);
    ctx.fillRect(game.x,game.y,3,3);
    for(let y=12;y<54;y+=5)ctx.fillRect(63,y,1,2);
    footer("Gora/Dol: paletka");
}
function drawGameOver() {
    header("Koniec gry");
    txt("GAME OVER",64,19,10,FG,"center");
    txt(`Wynik: ${game?.score||0}`,64,33,8,FG,"center");
    txt("A/OK: ponownie",64,44,7,FG,"center");
    footer("BACK/B: Gry");
}

/* Buttons */
document.querySelectorAll("[data-key]").forEach(button=>{
    button.addEventListener("click",()=>{
        const key=button.dataset.key;
        if(key==="back"&&longBackTriggered) {
            longBackTriggered=false;
            return;
        }
        act(key);
    });
    button.addEventListener("pointerdown",()=>{
        button.classList.add("pressed");
        if(button.dataset.key==="back") {
            longBackTriggered=false;
            longBackTimer=setTimeout(()=>{
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
            if(longBackTimer!==null) {
                clearTimeout(longBackTimer);
                longBackTimer=null;
            }
        });
    });
});
document.addEventListener("keydown",e=>{
    const map={
        ArrowUp:"up",ArrowDown:"down",ArrowLeft:"left",ArrowRight:"right",
        Enter:"ok",Escape:"back",Backspace:"back",
        z:"a",Z:"a",x:"b",X:"b"
    };
    if(map[e.key]) {
        e.preventDefault();
        if(!e.repeat)act(map[e.key]);
    }
});
document.getElementById("help").addEventListener("click",()=>{
    helpbox.style.display=helpbox.style.display==="block"?"none":"block";
});
document.getElementById("closehelp").addEventListener("click",()=>{
    helpbox.style.display="none";
});

/* Dopasowanie konsolki do ekranu bez zmiany proporcji. */
function fit() {
    const scale=Math.min((innerWidth-24)/1000,(innerHeight-35)/440);
    device.style.transform=`scale(${Math.max(.2,scale)})`;
}
addEventListener("resize",fit);
fit();
render();
})();
</script>
</body>
</html>