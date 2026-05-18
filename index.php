<?php
// 1. MEMANGGIL FILE KONFIGURASI DATABASE
include('config.php');

$charData = [];

// 2. MENGAMBIL DATA KARAKTER MENGGUNAKAN GAYA PROSEDURAL
if (isset($conn) && $conn) {
    $sql = "SELECT * FROM characters ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $charData[] = [
                "id"      => $row['id'], 
                "uid"     => $row['uid'], 
                "server"  => $row['server'],
                "name"    => $row['name'],
                "game"    => $row['game'],
                "guild"   => $row['guild'],
                "class"   => $row['class'],
                "quote"   => $row['quote'],
                "preview" => $row['preview'],
                "avatar"  => $row['avatar'],
                "social"  => $row['social']
            ];
        }
    }
}

if (isset($conn) && $conn) {
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
     <!-- Primary Meta Tags -->
	<title>Facecard</title>
	<meta name="title" content="Facecard" />
	<meta name="description" content="Facecard Gamer membantu Anda terhubung, berbagi, dan memamerkan profil karakter game serta kartu identitas gamer Anda dengan pemain lain di seluruh dunia secara
	instan melalui sosial media" />

	<!-- Open Graph / Facebook -->
	<meta property="og:type" content="website" />
	<meta property="og:url" content="https://facecard.gamer.gd/" />
	<meta property="og:title" content="Facecard" />
	<meta property="og:description" content="Facecard Gamer membantu Anda terhubung, berbagi, dan memamerkan profil karakter game serta kartu identitas gamer Anda dengan pemain lain di seluruh dunia secara instan melalui sosial media" />
	<meta property="og:image" content="https://i.ibb.co.com/1jWgvjs/banner.png" />

	<!-- X (Twitter) -->
	<meta property="twitter:card" content="summary_large_image" />
	<meta property="twitter:url" content="https://facecard.gamer.gd/" />
	<meta property="twitter:title" content="Facecard" />
	<meta property="twitter:description" content="Facecard Gamer membantu Anda terhubung, berbagi, dan memamerkan profil karakter game serta kartu identitas gamer Anda dengan pemain lain di seluruh dunia secara instan melalui sosial media" />
	<meta property="twitter:image" content="https://i.ibb.co.com/1jWgvjs/banner.png" />

	<!-- Meta Tags Generated with https://metatags.io -->
	
	
	<meta property="og:title" content="Facecard" />
    <meta property="og:description" content="Facecard Gamer membantu Anda terhubung, berbagi, dan memamerkan profil karakter game serta kartu identitas gamer Anda dengan pemain lain di seluruh dunia secara
	instan melalui sosial media" />
    <meta property="og:image" content="https://facecard.gamer.gd/" />
    <meta name="theme-color" content="#5865F2" /> <!-- Discord Blurple -->
	
	
	
	
    <meta name="description" content="Facecard Gamer membantu Anda terhubung, berbagi, dan memamerkan profil karakter game serta kartu identitas gamer Anda dengan pemain lain di seluruh dunia secara instan melalui sosial media.">
    <meta name="keywords" content="facecard gamer, profile gamer, game character profile, guild, clan, gaming card">
    
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Cg transform='skewX(-10) translate(10, 0)'%3E%3Crect width='80' height='80' rx='15' fill='%23050a0f' stroke='%2300d2ff' stroke-width='6'/%3E%3Ctext x='40' y='58' font-size='55' font-weight='900' font-style='italic' font-family='Impact, sans-serif' fill='%2300d2ff' text-anchor='middle'%3EF%3C/text%3E%3C/g%3E%3C/svg%3E">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
   
</head>
<body>

    <div class="start-gate-overlay" id="start-gate">
        <button class="start-btn" onclick="triggerStartGame()">Start Game</button>
    </div>

    <div class="video-overlay" id="video-gate">
        <video id="sao-video" src="src/vid.mp4" playsinline onclick="endVideoEarly()"></video>
    </div>

    <audio id="bgm-player" src="src/bgm.mp3" loop></audio>

    <div class="game-container" id="main-container">
        <section class="character-display">
            <div class="character-art">
                <img id="main-art" src="" alt="Character" loading="lazy">
            </div>

            <div class="character-stats" id="stats-panel">
                <div class="stats-header">
                    <h1 id="char-name"></h1>
                    <button class="edit-stat-btn" title="Edit Character Data" onclick="openModalForEdit(event)">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
                <div class="uid-info">
                    <span class="uid-tag" id="char-uid"></span> | SERVER : <span id="char-server"></span>
                </div>
                <hr>
                <div class="base-stats">
                    <div class="stat-item">Game <span class="stat-val" id="char-game"></span></div>
                    <div class="stat-item">Guild / Clan <span class="stat-val" id="char-guild"></span></div>
                    <div class="stat-item">Main Class / Weapon <span class="stat-val" id="char-class"></span></div>
                </div>
                <div class="social-container" id="char-socials"></div>
                <p class="quote" id="char-quote"></p>
            </div>

            <div class="floating-char-name" id="mobile-floating-name" onclick="toggleMobileStats(event)"></div>
        </section>

        <div class="bottom-ui">
            <div class="search-container">
                <i class="fas fa-search" style="color: var(--primary-cyan)"></i>
                <input type="text" id="searchInput" class="search-box" placeholder="SEARCH DATA">
                
                <button class="add-char-btn" onclick="openModalForAdd(event)">
                    <i class="fas fa-plus"></i> <span>Add Character</span>
                </button>
                 
                <button class="download-btn" onclick="downloadCurrentImage(event)">
                    <i class="fas fa-download"></i> <span>Download</span>
                </button>
            </div>
            <div class="character-grid" id="character-list"></div>
        </div>
    </div>

    <div class="modal-overlay" id="formModal" onclick="closeModal(event)">
        <div class="modal-box" onclick="event.stopPropagation()">
            <h2 class="modal-title" id="modalTitle">Deploy Character</h2>
            <hr style="border-top: 1px solid rgba(0, 210, 255, 0.4); margin-bottom: 20px;">
            <form id="addCharForm" onsubmit="handleFormSubmit(event)">
                <input type="hidden" name="char_id" id="form-char-id">

                <div class="form-group" style="background: rgba(255, 0, 85, 0.04); padding: 12px; border: 1px dashed rgba(255, 0, 85, 0.3); margin-bottom: 20px;">
                    <label id="passwordLabel" style="color: #ff3b70;"><i class="fas fa-key"></i> Create Password</label>
                    <input type="password" name="password" id="form-password" class="form-control password-highlight" required placeholder="Tentukan password karaktermu">
                    <small style="color: #aaa; font-size: 11px; margin-top: 4px; display: block;" id="passwordHelp">Password wajib diisi untuk mengedit atau menghapus kartu ini di kemudian hari.</small>
                </div>

                <div class="form-group">
                    <label>User ID (UID)</label>
                    <input type="text" name="uid" id="form-uid" class="form-control" required placeholder="Contoh: 8827-1001-01">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-user-astronaut" style="color:var(--primary-cyan)"></i> Character Name</label>
                    <input type="text" name="name" id="form-name" class="form-control" required placeholder="Contoh: Giorno Giovanna">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-globe" style="color:var(--primary-cyan)"></i> Server Region</label>
                    <input type="text" name="server" id="form-server" class="form-control" required placeholder="Contoh: IND-01 / SEA">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-gamepad" style="color:var(--primary-cyan)"></i> Game Title</label>
                    <input type="text" name="game" id="form-game" class="form-control" required placeholder="Contoh: Genshin Impact">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-shield-alt" style="color:var(--primary-cyan)"></i> Guild / Clan Name</label>
                    <input type="text" name="guild" id="form-guild" class="form-control" required placeholder="Contoh: GOLDEN WIND">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-bolt" style="color:var(--primary-cyan)"></i> Main Class / Weapon Type</label>
                    <input type="text" name="class" id="form-class" class="form-control" required placeholder="Contoh: Swordsman">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-link" style="color:var(--primary-cyan)"></i> Social Media URL</label>
                    <input type="url" name="social" id="form-social" class="form-control" placeholder="https://facebook.com/username">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-quote-left" style="color:var(--primary-cyan)"></i> Signature Quote</label>
                    <input type="text" name="quote" id="form-quote" class="form-control" placeholder="Kata-kata keren karaktermu...">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-link" style="color:var(--primary-cyan)"></i> Character Face URL (Avatar Card)</label>
                    <input type="url" name="avatar" id="form-avatar" class="form-control" required placeholder="https://example.com/avatar.png">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-link" style="color:var(--primary-cyan)"></i> Character Preview URL (Full Background Art)</label>
                    <input type="url" name="preview" id="form-preview" class="form-control" required placeholder="https://example.com/full-art.png">
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-delete-char" id="deleteBtn" style="display: none;" onclick="handleDeleteCharacter(event)">
                        <i class="fas fa-trash-alt"></i> Delete Data
                    </button>
                    <button type="button" class="btn-cancel" onclick="closeModal(event)">Cancel</button>
                    <button type="submit" class="add-char-btn" id="submitBtn">Deploy Core</button>
                </div>
            </form>
        </div>
    </div>
	<script>   const charData = [];

        function decodeHTML(html) {
            const txt = document.createElement('textarea');
            txt.innerHTML = html;
            return txt.value;
        }

        <?php
        if (!empty($charData)) {
            foreach ($charData as $char) {
                ?>
                charData.push({
                    "id": "<?php echo addslashes($char['id']); ?>",
                    "uid": "<?php echo addslashes($char['uid']); ?>",
                    "name": decodeHTML("<?php echo addslashes($char['name']); ?>"),
                    "server": decodeHTML("<?php echo addslashes($char['server']); ?>"),
                    "game": decodeHTML("<?php echo addslashes($char['game']); ?>"),
                    "guild": decodeHTML("<?php echo addslashes($char['guild']); ?>"),
                    "class": decodeHTML("<?php echo addslashes($char['class']); ?>"),
                    "social": "<?php echo addslashes($char['social']); ?>",
                    "quote": decodeHTML("<?php echo addslashes($char['quote']); ?>"),
                    "avatar": "<?php echo addslashes($char['avatar']); ?>",
                    "preview": "<?php echo addslashes($char['preview']); ?>"
                });
                <?php
            }
        }
        ?>

        let currentActiveCharacter = null; 
        let modalMode = 'add'; 
        const grid = document.getElementById('character-list');
        const container = document.getElementById('main-container');

        const clickSound = new Audio('src/sound.mp3');
        clickSound.volume = 0.5;

        function triggerStartGame() {
            activateFullscreen();
            
            clickSound.play().then(() => {
                clickSound.pause();
                clickSound.currentTime = 0;
            }).catch(() => {});

            const gate = document.getElementById('start-gate');
            const videoGate = document.getElementById('video-gate');
            const video = document.getElementById('sao-video');

            gate.style.display = 'none';
            videoGate.style.display = 'flex';
            
            video.play().catch(err => {
                cleanUpVideoAndStartBGM();
            });

            video.onended = () => {
                cleanUpVideoAndStartBGM();
            };
        }

        function endVideoEarly() {
            cleanUpVideoAndStartBGM();
        }

        function cleanUpVideoAndStartBGM() {
            const videoGate = document.getElementById('video-gate');
            const video = document.getElementById('sao-video');
            video.pause();
            videoGate.style.opacity = '0';
            setTimeout(() => {
                videoGate.style.display = 'none';
                tryPlayBGM();
            }, 600);
        }

        function tryPlayBGM() {
            const bgm = document.getElementById('bgm-player');
            if (bgm && bgm.paused) {
                bgm.volume = 0.3;
                bgm.play().catch(e => {});
            }
        }

        function playClickSound() {
            tryPlayBGM(); 
            clickSound.currentTime = 0; 
            clickSound.play().catch(o => {});
        }

        function activateFullscreen() {
            if (window.innerWidth <= 768) { 
                const docElm = document.documentElement;
                if (!document.fullscreenElement && !document.webkitFullscreenElement) {
                    if (docElm.requestFullscreen) docElm.requestFullscreen();
                }
            }
        }

        function openModalForAdd(event) {
            event.stopPropagation(); playClickSound();
            modalMode = 'add';
            document.getElementById('addCharForm').reset();
            document.getElementById('deleteBtn').style.display = 'none';
            document.getElementById('formModal').style.display = 'flex';
        }

        function openModalForEdit(event) {
            event.stopPropagation(); playClickSound();
            if (!currentActiveCharacter) return;
            modalMode = 'edit';
            document.getElementById('form-char-id').value = currentActiveCharacter.id;
            document.getElementById('form-uid').value = currentActiveCharacter.uid;
            document.getElementById('form-name').value = currentActiveCharacter.name;
            document.getElementById('form-server').value = currentActiveCharacter.server;
            document.getElementById('form-game').value = currentActiveCharacter.game;
            document.getElementById('form-guild').value = currentActiveCharacter.guild;
            document.getElementById('form-class').value = currentActiveCharacter.class;
            document.getElementById('form-social').value = currentActiveCharacter.social;
            document.getElementById('form-quote').value = currentActiveCharacter.quote;
            document.getElementById('form-avatar').value = currentActiveCharacter.avatar;
            document.getElementById('form-preview').value = currentActiveCharacter.preview;
            document.getElementById('deleteBtn').style.display = 'inline-flex';
            document.getElementById('formModal').style.display = 'flex';
        }

        function closeModal(event) {
            if(event) event.stopPropagation();
            playClickSound();
            document.getElementById('formModal').style.display = 'none';
        }

        function handleFormSubmit(event) {
            event.preventDefault(); playClickSound();
            const form = document.getElementById('addCharForm');
            const formData = new FormData(form);
            const targetUrl = (modalMode === 'edit') ? 'updatechar.php' : 'addchar.php';

            fetch(targetUrl, { method: 'POST', body: formData })
            .then(response => {
                if (response.status === 403) { alert('ACCESS DENIED: Password Salah!'); throw new Error(); }
                return response.text();
            })
            .then(() => window.location.reload());
        }

        function handleDeleteCharacter(event) {
            event.stopPropagation(); playClickSound();
            const inputPass = document.getElementById('form-password').value.trim();
            const charId = document.getElementById('form-char-id').value;
            if (inputPass === "") { alert("Isi password!"); return; }
            if (!confirm("Hapus data?")) return;

            const formData = new FormData();
            formData.append('char_id', charId);
            formData.append('password', inputPass);

            fetch('deletechar.php', { method: 'POST', body: formData })
            .then(response => {
                if (response.status === 403) { alert('Password Salah!'); throw new Error(); }
                return response.text();
            })
            .then(() => window.location.reload());
        }

        function toggleMobileStats(event) {
            event.stopPropagation(); playClickSound();
            container.classList.toggle('stats-open');
        }

        function downloadCurrentImage(event) {
            event.stopPropagation(); playClickSound();
            if (!currentActiveCharacter) return;
            window.open(currentActiveCharacter.preview, '_blank');
        }

        function initGrid() {
            grid.innerHTML = '';
            charData.forEach((char) => {
                const card = document.createElement('div');
                card.className = 'char-card';
                card.setAttribute('data-name', char.name.toLowerCase());
                // LAZY LOAD IMPLEMENTATION
                card.innerHTML = `<img src="${char.avatar}" alt="${char.name}" loading="lazy">`;
                card.onclick = () => {
                    activateFullscreen(); playClickSound();
                    updateSelection(char, card);
                };
                grid.appendChild(card);
            });
        }

        function getSocialDetails(url) {
            const lowerUrl = url.toLowerCase();
            if (lowerUrl.includes('tiktok.com')) return { icon: 'fab fa-tiktok', name: 'TikTok', styleClass: 'brand-tiktok' };
            if (lowerUrl.includes('instagram.com')) return { icon: 'fab fa-instagram', name: 'Instagram', styleClass: 'brand-instagram' };
            if (lowerUrl.includes('facebook.com')) return { icon: 'fab fa-facebook-f', name: 'Facebook', styleClass: 'brand-facebook' };
            if (lowerUrl.includes('youtube.com')) return { icon: 'fab fa-youtube', name: 'YouTube', styleClass: 'brand-youtube' };
            return { icon: 'fas fa-link', name: 'Social', styleClass: 'brand-default' }; 
        }

        function updateSelection(data, element) {
            currentActiveCharacter = data; 
            const statsPanel = document.getElementById('stats-panel');
            const art = document.getElementById('main-art');
            const isMobile = window.innerWidth <= 768;

            if (isMobile) {
                container.style.backgroundImage = `url('${data.preview}')`;
            } else {
                statsPanel.classList.remove('swipe-effect');
                void statsPanel.offsetWidth; 
                statsPanel.classList.add('swipe-effect');

                art.classList.remove('swipe-effect');
                void art.offsetWidth; 
                art.src = data.preview; 
                art.classList.add('swipe-effect');
                container.style.backgroundImage = `url('${data.preview}')`;
            }

            document.getElementById('char-name').innerText = data.name;
            document.getElementById('mobile-floating-name').innerText = data.name;
            document.getElementById('char-uid').innerText = "UID : " + data.uid;
            document.getElementById('char-server').innerText = data.server;
            document.getElementById('char-game').innerText = data.game;
            document.getElementById('char-guild').innerText = data.guild;
            document.getElementById('char-class').innerText = data.class;
            document.getElementById('char-quote').innerText = `"${data.quote}"`;

            const socialBox = document.getElementById('char-socials');
            socialBox.innerHTML = ''; 
            if (data.social) {
                const brand = getSocialDetails(data.social);
                socialBox.innerHTML = `<a href="${data.social}" target="_blank" class="social-icon ${brand.styleClass}"><i class="${brand.icon}"></i> ${brand.name}</a>`;
            }

            // DIMMING LOGIC
            document.querySelectorAll('.char-card').forEach(c => c.classList.remove('active'));
            element.classList.add('active');
        }

        window.addEventListener('click', () => {
            tryPlayBGM();
            if (window.innerWidth <= 768 && container.classList.contains('stats-open')) {
                container.classList.remove('stats-open');
            }
        });

        document.getElementById('searchInput').addEventListener('input', (e) => {
            const val = e.target.value.toLowerCase();
            document.querySelectorAll('.char-card').forEach(card => {
                card.style.display = card.getAttribute('data-name').includes(val) ? 'block' : 'none';
            });
        });

        initGrid();
        window.onload = () => {
            const firstCard = document.querySelector('.char-card');
            if (firstCard) updateSelection(charData[0], firstCard);
        }; </script>
</body>
</html>