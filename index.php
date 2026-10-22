<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$ztmyEscape = static function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
};
$ztmyJsonFlags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_INVALID_UTF8_SUBSTITUTE;
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $ztmyEscape($this->options->title); ?></title>
    <link rel="stylesheet" href="<?php echo $ztmyEscape(rtrim($this->options->themeUrl, '/') . '/style.css'); ?>">
    <link href="https://fonts.geekzu.org/css2?family=VT323&family=Zen+Kaku+Gothic+New:wght@700&family=DotGothic16&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet" />
    <style>body { background-image: url(<?php echo json_encode(rtrim($this->options->themeUrl, '/') . '/images/Ayanami.png', $ztmyJsonFlags); ?>) !important; }</style>
</head>
<body>

    <div id="boot-screen">
        <div class="boot-text">
            <p>> SYSTEM_INIT...</p>
            <p>> LOADING_KERNEL: ZTMY_OS_V14.1</p>
            <p>> MOUNTING_PLAYLIST: 25_TRACKS_DETECTED</p>
            <h1 class="glitch-text pixel-jp">ずっと真夜中でいいのに。</h1>
            <div class="loader-bar"><div class="bar-fill"></div></div>
        </div>
    </div>

    <div class="crt-overlay"></div>
    <div class="scanlines"></div>

    <div id="desktop">
        <div class="desktop-icons">
            <div class="icon" onclick="openWindow('win-blog')"><div class="icon-img">📂</div><span>LOGS</span></div>
            <div class="icon" onclick="openWindow('win-terminal'); focusTerminal();"><div class="icon-img">💻</div><span>TERMINAL</span></div>
            <div class="icon" onclick="openWindow('win-walkman'); initWalkman();"><div class="icon-img">📼</div><span>WALKMAN</span></div>
            <div class="icon" onclick="openWindow('win-chat')"><div class="icon-img">💬</div><span>SIGNAL_ROOM</span></div>
            <div class="icon" onclick="openWindow('win-minesweeper'); setTimeout(initMinesweeper, 100);"><div class="icon-img">💣</div><span>MINESWEEPER</span></div>
            <div class="icon" onclick="openWindow('win-xmas')"><div class="icon-img">🎄</div><span>XMAS_TREE</span></div>
            <div class="icon" data-admin-url="<?php echo $ztmyEscape($this->options->adminUrl); ?>" onclick="window.open(this.dataset.adminUrl, '_blank', 'noopener,noreferrer')"><div class="icon-img">⚙️</div><span>ADMIN</span></div>
        </div>

        <div id="win-walkman" class="window" style="display:none; top: 15%; left: 20%; width: 420px; z-index: 160;">
            <div class="win-header" onmousedown="dragWindow(event, 'win-walkman')">
                <span class="win-title">/// ZTMY_WALKMAN</span>
                <button class="win-close" onclick="closeWindow('win-walkman')">X</button>
            </div>
            <div class="win-content" style="background: #111; padding: 15px; overflow: hidden;">
                <div id="tape-rack-view">
                    <div class="rack-title">>> INSERT_CASSETTE:</div>
                    <div id="tape-grid" class="tape-grid"></div>
                </div>
                <div id="player-view" style="display:none;">
                    <button class="back-to-rack-btn" onclick="showRackView()">[⏏ EJECT TAPE]</button>
                    <div class="cassette-window">
                        <div class="tape-spool left"><div class="spool-teeth"></div></div>
                        <div class="tape-label" id="current-tape-label">NO TAPE</div>
                        <div class="tape-spool right"><div class="spool-teeth"></div></div>
                        <div class="tape-mechanism"></div>
                    </div>
                    <div class="player-status">
                        <div class="mech-counter" id="mech-counter">000</div>
                        <div class="play-state" id="play-state">STOPPED</div>
                    </div>
                    <div class="player-controls">
                        <button class="ctrl-btn" onclick="prevTrack()">|&lt;</button>
                        <button class="ctrl-btn play-btn" id="play-toggle-btn" onclick="togglePlay()">▶</button>
                        <button class="ctrl-btn" onclick="nextTrack()">&gt;|</button>
                    </div>
                    <div class="volume-ctrl">VOL: <input type="range" min="0" max="1" step="0.1" value="0.8" oninput="setVolume(this.value)"></div>
                </div>
            </div>
        </div>

        <div id="win-blog" class="window" style="top: 10%; left: 10%; width: 850px; height: 550px; z-index: 50;">
            <div class="win-header" onmousedown="dragWindow(event, 'win-blog')"><span class="win-title">/// FILE_EXPLORER</span><button class="win-close" onclick="closeWindow('win-blog')">X</button></div>
            <div class="explorer-toolbar">
                <button class="nav-btn" onclick="filterCategory('all')">🏠</button>
                <div class="address-bar"><span class="prompt">PATH:</span><input type="text" id="explorer-path" value="C:/SYSTEM/LOGS/" readonly></div>
                <div class="search-box"><input type="text" placeholder="SEARCH..." onkeyup="filterFiles(this.value)"></div>
            </div>
            <div class="explorer-body">
                <div class="explorer-sidebar">
                    <div class="sidebar-item active" id="cat-all" onclick="filterCategory('all')"><span class="icon">💾</span> ROOT (ALL)</div>
                    <div class="sidebar-divider">CATEGORIES</div>
                    <?php $this->widget('Widget_Metas_Category_List')->to($cats); ?>
                    <?php while($cats->next()): ?>
                        <div class="sidebar-item" id="cat-<?php echo $ztmyEscape($cats->slug); ?>" data-slug="<?php echo $ztmyEscape($cats->slug); ?>" onclick="filterCategory(this.dataset.slug)"><span class="icon">📁</span> <?php echo $ztmyEscape($cats->name); ?></div>
                    <?php endwhile; ?>
                </div>
                <div class="explorer-main">
                    <div class="file-header"><span style="flex:2">NAME</span><span style="flex:1">DATE</span><span style="flex:1">CATEGORY</span></div>
                    <div class="file-list-container">
                    <?php while($this->next()): ?>
                        <?php
                        $ztmyCategories = $this->categories ?: [];
                        $ztmyCategorySlugs = array_column($ztmyCategories, 'slug');
                        $ztmyCategoryNames = implode(', ', array_column($ztmyCategories, 'name'));
                        ?>
                        <div class="file-row" onclick="openPost(this.dataset.postId, this.dataset.title)" data-post-id="post-<?php echo (int) $this->cid; ?>" data-cats="<?php echo $ztmyEscape(json_encode($ztmyCategorySlugs, $ztmyJsonFlags)); ?>" data-title="<?php echo $ztmyEscape($this->title); ?>">
                            <span class="file-name" style="flex:2"><span class="f-icon">📄</span> <?php echo $ztmyEscape($this->title); ?></span>
                            <span class="file-date" style="flex:1"><?php $this->date('Y-m-d'); ?></span>
                            <span class="file-size" style="flex:1"><?php echo $ztmyEscape($ztmyCategoryNames); ?></span>
                        </div>
                        <div id="post-<?php echo (int) $this->cid; ?>" style="display:none;">
                            <div class="post-meta">DATE: <?php $this->date(); ?> | CATEGORY: <?php echo $ztmyEscape($ztmyCategoryNames); ?></div>
                            <div class="post-body line-numbers"><?php $this->content(); ?></div>
                            <hr><?php $this->need('comments.php'); ?>
                        </div>
                    <?php endwhile; ?>
                    </div>
                </div>
            </div>
            <div class="explorer-statusbar"><span>STATUS: ONLINE</span><span>DRIVER: ZTMY_FS_V1.1</span></div>
        </div>

        <div id="win-xmas" class="window" style="display:none; top: 15%; left: 60%; width: 350px; z-index: 201;">
            <div class="win-header" onmousedown="dragWindow(event, 'win-xmas')"><span class="win-title">/// XMAS_PATCH</span><button class="win-close" onclick="closeWindow('win-xmas')">X</button></div>
            <div class="win-content" style="text-align: center; background: #000; padding: 20px;">
                <pre class="ascii-tree">
      <span class="star">★</span>
     / \
    /<span class="light r">o</span>  \
   /  <span class="light g">+</span>  \
  /<span class="light b">*</span>      \
 /   <span class="light r">o</span>  <span class="light y">^</span>  \
/___________\
    | |
                </pre>
                <h2 class="glitch-text" style="font-size: 1.8rem;">MERRY XMAS</h2>
            </div>
        </div>

        <div id="win-terminal" class="window" style="display:none; top: 15%; left: 15%; width: 600px; height: 400px; z-index: 180;"><div class="win-header" onmousedown="dragWindow(event, 'win-terminal')"><span class="win-title">/// TERMINAL.exe</span><button class="win-close" onclick="closeWindow('win-terminal')">X</button></div><div class="win-content" style="background: #000; padding: 10px; overflow-y: auto;" onclick="focusTerminal()"><div id="term-output" class="terminal-output">Welcome to KyokuyaOS v14.1<br>Type 'help' for commands.<br><br></div><div class="terminal-input-line"><span class="terminal-prompt">root@kyokuya:~$</span><input type="text" id="term-input" class="terminal-input" autocomplete="off"></div></div></div>
        <div id="win-welcome" class="window" style="display:none; top: 20%; left: 35%; width: 400px; z-index: 200;"><div class="win-header" onmousedown="dragWindow(event, 'win-welcome')"><span class="win-title">/// MESSAGE</span><button class="win-close" onclick="closeWindow('win-welcome')">X</button></div><div class="win-content" style="text-align: center; padding: 25px;"><p style="font-size: 1.2rem; color: var(--green);">欢迎来到kyokuya的博客，<br>祝你们玩的开心</p><button onclick="closeWindow('win-welcome')" style="margin-top:20px; padding: 5px 15px; background: var(--purple); color: #fff; border: 1px solid #fff; cursor:pointer;">ACKNOWLEDGE</button></div></div>
        <div id="win-minesweeper" class="window" style="display:none; top: 15%; left: 25%; width: auto; z-index: 150;"><div class="win-header" onmousedown="dragWindow(event, 'win-minesweeper')"><span class="win-title">/// MINESWEEPER</span><button class="win-close" onclick="closeWindow('win-minesweeper')">X</button></div><div class="win-content" style="background: #000; padding: 10px; display:flex; flex-direction:column; align-items:center;"><div class="mine-status-bar"><div class="mine-stat">💣 <span id="mine-count">10</span></div><button class="mine-reset-btn" onclick="initMinesweeper()">😊</button><div class="mine-stat">⏱️ <span id="mine-timer">000</span></div></div><div id="mine-grid" class="mine-grid"></div><div id="mine-result" class="mine-result"></div></div></div>
        <div id="win-chat" class="window" style="display:none; top: 10%; left: 30%; width: 500px; height: 600px;"><div class="win-header" onmousedown="dragWindow(event, 'win-chat')"><span class="win-title">/// CHAT</span><button class="win-close" onclick="closeWindow('win-chat')">X</button></div><div class="win-content" style="padding: 0; background: #000;"><iframe src="https://hack.chat/?zutomayo_lobby" width="100%" height="100%" frameborder="0"></iframe></div></div>
        <div id="win-reader" class="window" style="display:none; top: 5%; left: 5%; width: 70vw; height: 85vh;"><div class="win-header" onmousedown="dragWindow(event, 'win-reader')"><span class="win-title" id="reader-title">READING...</span><button class="win-close" onclick="closeWindow('win-reader')">X</button></div><div class="win-content" id="reader-content"></div></div>
        <div class="taskbar"><div class="start-btn">START</div><div class="clock" id="clock">00:00:00</div></div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>
    <script>
        // === WALKMAN 歌单配置 (25首) ===
        const themePath = <?php echo json_encode(rtrim($this->options->themeUrl, '/'), $ztmyJsonFlags); ?>;
        const playlist = [
            { title: "Keil Keygen.exe", file: "keil_keygen.mp3" },
            { title: "ZTMY: MILABO", file: "milabo.mp3" },
            { title: "ZTMY: Reunion", file: "dousoukai.mp3" },
            { title: "EVA: One Last Kiss", file: "one_last_kiss.mp3" },
            { title: "EVA: Beautiful World", file: "beautiful_world.mp3" },
            { title: "First Love", file: "first_love.mp3" },
            { title: "Yorushika", file: "yorushika.mp3" },
            { title: "Sheena Ringo", file: "marunouchi.mp3" },
            { title: "Hotel California", file: "hotel_california.mp3" },
            { title: "I Can't Tell You Why", file: "cant_tell_you_why.mp3" },
            { title: "Money - Pink Floyd", file: "money.mp3" },
            { title: "Wish You Were Here", file: "wish_you_were_here.mp3" },
            { title: "No Surprises", file: "no_surprises.mp3" },
            { title: "Paranoid Android", file: "paranoid_android.mp3" },
            { title: "Nujabes: Aruarian", file: "aruarian_dance.mp3" },
            { title: "Nujabes: Luv(sic)", file: "luv_sic.mp3" },
            { title: "King Crimson: Epitaph", file: "epitaph.mp3" },
            { title: "Stairway to Heaven", file: "stairway_to_heaven.mp3" },
            { title: "November Rain", file: "november_rain.mp3" },
            { title: "People in Cycle", file: "people_in_cycle.mp3" },
            { title: "Let It Be", file: "let_it_be.mp3" },
            { title: "Baba O'Riley", file: "baba_oriley.mp3" },
            { title: "Atarayo: Kyokuya", file: "atarayo_kyokuya.mp3" },
            { title: "Atarayo: Haruru", file: "atarayo_haruru.mp3" },
            { title: "Kinoko Teikoku", file: "kinoko_teikoku.mp3" }
        ];

        let audio = new Audio();
        let currentTrackIdx = 0;
        let isWalkmanInit = false;

        function initWalkman() {
            if(isWalkmanInit) return;
            const grid = document.getElementById('tape-grid');
            grid.innerHTML = '';
            playlist.forEach((track, index) => {
                let tape = document.createElement('div');
                tape.className = 'cassette-tape';
                tape.innerHTML = `<div class="tape-body"><div class="tape-reel left"></div><div class="tape-window"><span class="tape-title">${track.title}</span></div><div class="tape-reel right"></div></div>`;
                tape.onclick = () => loadAndPlay(index);
                grid.appendChild(tape);
            });
            audio.addEventListener('timeupdate', updatePlayerUI);
            audio.addEventListener('ended', nextTrack);
            audio.volume = 0.8;
            isWalkmanInit = true;
        }

        function loadAndPlay(index) {
            currentTrackIdx = index;
            audio.src = themePath + '/music/' + playlist[index].file;
            document.getElementById('current-tape-label').innerText = playlist[index].title;
            showPlayerView();
            playTrack();
        }

        function togglePlay() { if (audio.paused) playTrack(); else pauseTrack(); }
        function playTrack() {
            audio.play().then(() => {
                document.getElementById('play-toggle-btn').innerText = '❚❚';
                document.getElementById('play-state').innerText = 'PLAYING >>';
                document.querySelector('.cassette-window').classList.add('playing');
            }).catch(e => { console.log("Audio Error:", e); document.getElementById('play-state').innerText = 'ERR: NO FILE'; });
        }
        function pauseTrack() {
            audio.pause();
            document.getElementById('play-toggle-btn').innerText = '▶';
            document.getElementById('play-state').innerText = 'PAUSED ||';
            document.querySelector('.cassette-window').classList.remove('playing');
        }
        function nextTrack() { currentTrackIdx = (currentTrackIdx + 1) % playlist.length; loadAndPlay(currentTrackIdx); }
        function prevTrack() { currentTrackIdx = (currentTrackIdx - 1 + playlist.length) % playlist.length; loadAndPlay(currentTrackIdx); }
        function setVolume(val) { audio.volume = val; }
        function updatePlayerUI() { let count = Math.floor(audio.currentTime * 10); document.getElementById('mech-counter').innerText = count.toString().padStart(3, '0'); }
        function showRackView() { document.getElementById('tape-rack-view').style.display='block'; document.getElementById('player-view').style.display='none'; pauseTrack(); }
        function showPlayerView() { document.getElementById('tape-rack-view').style.display='none'; document.getElementById('player-view').style.display='block'; }

        // === 资源管理器与搜索 (修复版) ===
        function filterCategory(slug) {
            const rows = document.querySelectorAll('.file-row');
            document.querySelectorAll('.sidebar-item').forEach(el => el.classList.remove('active'));
            const activeBtn = document.getElementById('cat-' + slug);
            if(activeBtn) activeBtn.classList.add('active');
            document.getElementById('explorer-path').value = 'C:/SYSTEM/LOGS/' + (slug === 'all' ? '' : slug.toUpperCase());
            rows.forEach(row => {
                if (slug === 'all' || JSON.parse(row.dataset.cats || '[]').includes(slug)) row.style.display = 'flex';
                else row.style.display = 'none';
            });
        }
        function filterFiles(text) {
            const rows = document.querySelectorAll('.file-row');
            rows.forEach(row => {
                if (row.getAttribute('data-title').toLowerCase().includes(text.toLowerCase())) row.style.display = 'flex';
                else row.style.display = 'none';
            });
        }

        // === 系统基础 ===
        window.onload=()=>{setTimeout(()=>{document.getElementById('boot-screen').style.display='none'; openWindow('win-welcome'); setTimeout(()=>openWindow('win-xmas'),500);},2000); setInterval(()=>document.getElementById('clock').innerText=new Date().toLocaleTimeString(),1000);};
        function openWindow(id){let win=document.getElementById(id); win.style.display='flex'; bringToFront(win); if(id=='win-minesweeper'&&(!grid.length)) initMinesweeper();}
        function closeWindow(id){document.getElementById(id).style.display='none'; if(id==='win-walkman') pauseTrack();}
        function openPost(cid,title){document.getElementById('reader-content').innerHTML=document.getElementById(cid).innerHTML; document.getElementById('reader-title').innerText=title; openWindow('win-reader'); if(window.Prism) Prism.highlightAll();}
        function bringToFront(el){document.querySelectorAll('.window').forEach(w=>w.style.zIndex=20); el.style.zIndex=100;}
        function dragWindow(e,id){let win=document.getElementById(id); bringToFront(win); let sX=e.clientX-win.getBoundingClientRect().left, sY=e.clientY-win.getBoundingClientRect().top; function onM(ev){win.style.left=ev.pageX-sX+'px'; win.style.top=ev.pageY-sY+'px';} document.addEventListener('mousemove',onM); document.onmouseup=()=>document.removeEventListener('mousemove',onM);}

        // === 终端 & 扫雷 ===
        const termInput=document.getElementById('term-input'),termOutput=document.getElementById('term-output');function focusTerminal(){termInput.focus();}termInput.addEventListener('keydown',function(e){if(e.key==='Enter'){const cmd=this.value.trim().toLowerCase();printTerm('root@kyokuya:~$ '+this.value);this.value='';processCommand(cmd);}});function printTerm(text){termOutput.appendChild(document.createTextNode(String(text)));termOutput.appendChild(document.createElement('br'));termOutput.scrollTop=termOutput.scrollHeight;}function processCommand(cmd){switch(cmd){case'help':printTerm('Commands: ls, date, whoami, clear, mines');break;case'ls':printTerm('--- FILES ---');document.querySelectorAll('.file-row').forEach(p=>printTerm(p.getAttribute('data-title')));break;case'date':printTerm(new Date().toString());break;case'whoami':printTerm('Guest User');break;case'clear':termOutput.innerHTML='';break;case'mines':openWindow('win-minesweeper');initMinesweeper();printTerm('Starting...');break;default:if(cmd!=='')printTerm('Unknown command: '+cmd);}}const ROWS=10,COLS=10,MINES=10;let grid=[],mineLocations=[],flags=0,isGameOver=false,timer=0,timerInterval;function initMinesweeper(){const el=document.getElementById('mine-grid');if(!el)return;el.innerHTML='';grid=[];mineLocations=[];flags=0;isGameOver=false;clearInterval(timerInterval);timer=0;document.getElementById('mine-timer').innerText='000';document.getElementById('mine-count').innerText=MINES;document.querySelector('.mine-reset-btn').innerText='😊';document.getElementById('mine-result').innerText='';for(let r=0;r<ROWS;r++){let row=[];for(let c=0;c<COLS;c++){let cell=document.createElement('div');cell.classList.add('mine-cell');cell.onclick=()=>clickCell(r,c);cell.oncontextmenu=(e)=>{e.preventDefault();toggleFlag(r,c);};el.appendChild(cell);row.push({el:cell,mine:false,revealed:false,flagged:false,count:0});}grid.push(row);}let p=0;while(p<MINES){let r=Math.floor(Math.random()*ROWS),c=Math.floor(Math.random()*COLS);if(!grid[r][c].mine){grid[r][c].mine=true;mineLocations.push({r,c});p++;}}for(let r=0;r<ROWS;r++)for(let c=0;c<COLS;c++)if(!grid[r][c].mine){let n=0;for(let i=-1;i<=1;i++)for(let j=-1;j<=1;j++){let nr=r+i,nc=c+j;if(nr>=0&&nr<ROWS&&nc>=0&&nc<COLS&&grid[nr][nc].mine)n++;}grid[r][c].count=n;}timerInterval=setInterval(()=>{timer++;document.getElementById('mine-timer').innerText=timer.toString().padStart(3,'0');},1000);}function clickCell(r,c){if(isGameOver||grid[r][c].revealed||grid[r][c].flagged)return;if(grid[r][c].mine)gameOver(false);else{reveal(r,c);checkWin();}}function toggleFlag(r,c){if(isGameOver||grid[r][c].revealed)return;let cell=grid[r][c];cell.flagged=!cell.flagged;cell.el.innerText=cell.flagged?'🚩':'';flags+=cell.flagged?1:-1;document.getElementById('mine-count').innerText=MINES-flags;}function reveal(r,c){if(r<0||r>=ROWS||c<0||c>=COLS||grid[r][c].revealed||grid[r][c].flagged)return;let cell=grid[r][c];cell.revealed=true;cell.el.classList.add('revealed');if(cell.count>0){cell.el.innerText=cell.count;cell.el.setAttribute('data-num',cell.count);}else for(let i=-1;i<=1;i++)for(let j=-1;j<=1;j++)reveal(r+i,c+j);}function gameOver(w){isGameOver=true;clearInterval(timerInterval);let r=document.getElementById('mine-result');let b=document.querySelector('.mine-reset-btn');if(w){r.innerText="SECURED";r.style.color="var(--green)";b.innerText="😎";}else{r.innerText="BOOM!";r.style.color="red";b.innerText="💀";mineLocations.forEach(l=>{grid[l.r][l.c].el.innerText='💣';grid[l.r][l.c].el.classList.add('mine-hit');});}}function checkWin(){let rv=0;for(let r=0;r<ROWS;r++)for(let c=0;c<COLS;c++)if(grid[r][c].revealed)rv++;if(rv===(ROWS*COLS-MINES))gameOver(true);}
    </script>
</body>
</html>
