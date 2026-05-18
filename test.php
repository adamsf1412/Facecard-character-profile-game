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
    <title>Character Selection System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-cyan: #00d2ff;
            --dark-overlay: rgba(5, 15, 25, 0.85);
            --neon-glow: 0 0 15px rgba(0, 210, 255, 0.6);
            --neon-red: 0 0 15px rgba(255, 0, 85, 0.6);
        }

        body {
            margin: 0; background: #050a0f; color: white;
            font-family: 'Segoe UI', Roboto, sans-serif; overflow: hidden; 
            height: 100vh; width: 100vw;
        }

        @keyframes swipeIn {
            0% { clip-path: polygon(0 0, 0 0, 0 100%, 0% 100%); opacity: 0.5; }
            100% { clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%); opacity: 1; }
        }

        @keyframes cardGlow {
            0% { box-shadow: 0 0 10px rgba(0, 210, 255, 0.4); border-color: rgba(0, 210, 255, 0.6); }
            50% { box-shadow: 0 0 20px rgba(0, 210, 255, 0.9); border-color: var(--primary-cyan); }
            100% { box-shadow: 0 0 10px rgba(0, 210, 255, 0.4); border-color: rgba(0, 210, 255, 0.6); }
        }

        @keyframes pulseGlow {
            0% { transform: translate(-50%, -50%) scale(0.9); opacity: 0.6; }
            50% { transform: translate(-50%, -50%) scale(1.15); opacity: 0.9; }
            100% { transform: translate(-50%, -50%) scale(0.9); opacity: 0.6; }
        }

        .game-container {
            height: 100vh; display: flex; flex-direction: column;
            background-size: cover; background-position: center; background-repeat: no-repeat; position: relative;
        }

        .game-container::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background: radial-gradient(circle at center, rgba(16, 32, 48, 0.6) 0%, rgba(5, 10, 15, 0.9) 100%);
            backdrop-filter: blur(8px); z-index: 0;
        }

        .swipe-effect { animation: swipeIn 0.4s cubic-bezier(0.25, 1, 0.5, 1) forwards; }
        .floating-char-name { display: none; }

        .character-display { flex: 1; display: flex; justify-content: flex-end; align-items: center; padding-right: 8%; position: relative; z-index: 1; }
        .character-art { position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); z-index: 1; display: flex; align-items: center; justify-content: center; height: 100vh; width: 100vw; pointer-events: none; }
        
        .character-art::before {
            content: ''; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
            width: 550px; height: 550px; background: radial-gradient(circle, rgba(0, 210, 255, 0.3) 0%, rgba(0, 210, 255, 0.08) 50%, transparent 70%);
            z-index: -1; pointer-events: none; animation: pulseGlow 4s infinite ease-in-out;
        }

        .character-art img {
            max-height: 80vh; width: auto; object-fit: contain; 
            filter: drop-shadow(0 0 15px rgba(0, 210, 255, 0.6)) drop-shadow(0 0 35px rgba(0, 210, 255, 0.3)); 
            cursor: pointer; pointer-events: auto;
        }

        .character-stats { width: 420px; background: var(--dark-overlay); padding: 30px; border-right: 6px solid var(--primary-cyan); backdrop-filter: blur(15px); clip-path: polygon(0 0, 100% 0, 100% 94%, 94% 100%, 0 100%); z-index: 2; }
        .stats-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; }
        h1 { font-style: italic; text-transform: uppercase; margin: 0; letter-spacing: 2px; flex: 1; }
        
        .edit-stat-btn {
            background: transparent; border: 1px solid rgba(0, 210, 255, 0.4); color: var(--primary-cyan);
            width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; font-size: 14px;
        }
        .edit-stat-btn:hover { background: var(--primary-cyan); color: #050a0f; box-shadow: var(--neon-glow); border-color: var(--primary-cyan); transition: none; }

        .uid-info { font-family: 'Courier New', monospace; font-size: 14px; margin: 5px 0 15px 0; color: #ccc; }
        .uid-tag { color: var(--primary-cyan); font-weight: bold; }
        hr { border: 0; border-top: 1px solid rgba(0, 210, 255, 0.3); margin: 15px 0; }
        .base-stats { margin-bottom: 15px; }
        .stat-item { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; color: #aaa; text-transform: uppercase; }
        .stat-val { color: var(--primary-cyan); font-weight: bold; }

        .social-container { display: flex; justify-content: flex-start; margin-top: 15px; padding-top: 10px; border-top: 1px dashed rgba(0, 210, 255, 0.2); }
        .social-icon { font-size: 15px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-weight: 700; padding: 5px 12px; border-radius: 4px; background: rgba(255,255,255,0.03); }
        .social-icon:hover { transform: scale(1.05); }
        .social-icon.brand-tiktok { color: #ffffff; text-shadow: -1px -1px 0 #00f2fe, 1px 1px 0 #fe0979; }
        .social-icon.brand-instagram { background: -webkit-linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 800; }
        .social-icon.brand-facebook { color: #1877F2; }
        .social-icon.brand-youtube { color: #FF0000; }
        .social-icon.brand-default { color: var(--primary-cyan); }
        .quote { font-style: italic; color: #777; font-size: 13px; margin-top: 15px; border-top: 1px solid #333; padding-top: 12px; min-height: 40px; }

        .bottom-ui { background: rgba(0, 0, 0, 0.8); border-top: 2px solid rgba(0, 210, 255, 0.2); padding: 15px 0 25px 0; z-index: 3; display: flex; flex-direction: column; gap: 12px; position: relative; }
        .search-container { padding: 0 50px; display: flex; align-items: center; justify-content: center; gap: 15px; }
        
        .search-box { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(0, 210, 255, 0.3); padding: 10px 15px; border-radius: 0; color: white; outline: none; width: 260px; text-align: center; transition: all 0.2s; font-style: italic; font-weight: bold; }
        .search-box:focus { border-color: var(--primary-cyan); box-shadow: var(--neon-glow); background: rgba(0, 210, 255, 0.05); }

        .download-btn, .add-char-btn { 
            background: rgba(0, 210, 255, 0.08); border: 1px solid rgba(0, 210, 255, 0.6); color: var(--primary-cyan); 
            padding: 10px 22px; border-radius: 0; cursor: pointer; font-weight: 800; font-size: 13px; display: inline-flex; align-items: center; gap: 10px; transition: all 0.2s ease-in-out; text-transform: uppercase; letter-spacing: 1px; font-style: italic;
            clip-path: polygon(0 0, 88% 0, 100% 40%, 100% 100%, 12% 100%, 0 60%);
        }
        .download-btn:hover, .add-char-btn:hover { background: var(--primary-cyan); color: #050a0f; box-shadow: var(--neon-glow); border-color: var(--primary-cyan); transform: translateY(-2px); }

        .btn-delete-char {
            background: rgba(255, 0, 85, 0.1); border: 1px solid rgba(255, 0, 85, 0.6); color: #ff0055;
            padding: 10px 18px; border-radius: 0; cursor: pointer; font-weight: 800; font-size: 12px; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; text-transform: uppercase; font-style: italic;
            clip-path: polygon(0 0, 88% 0, 100% 40%, 100% 100%, 12% 100%, 0 60%); margin-right: auto;
        }
        .btn-delete-char:hover { background: #ff0055; color: white; box-shadow: var(--neon-red); border-color: #ff0055; }

        .character-grid { display: flex; gap: 15px; padding: 10px 50px; justify-content: flex-start; overflow-x: auto; overflow-y: hidden; flex-wrap: nowrap; width: 100vw; box-sizing: border-box; }
        .char-card { width: 110px; height: 140px; background: #101820; transform: skewX(-12deg); border: 2px solid transparent; cursor: pointer; overflow: hidden; flex: 0 0 auto; transition: transform 0.2s, border-color 0.2s; }
        .char-card img { width: 140%; height: 100%; object-fit: cover; transform: skewX(12deg) translateX(-15%); filter: grayscale(0.2) brightness(0.75); transition: filter 0.2s, transform 0.2s; }
        .char-card:hover { transform: skewX(-12deg) translateY(-5px); border-color: rgba(0, 210, 255, 0.5); }
        .char-card.active { transform: skewX(-12deg) translateY(-10px); animation: cardGlow 2s infinite ease-in-out; }
        .char-card.active img, .char-card:hover img { filter: grayscale(0) brightness(1); }

        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(3, 8, 15, 0.85); backdrop-filter: blur(12px); z-index: 100; display: none; align-items: center; justify-content: center; }
        .modal-box { 
            background: rgba(8, 18, 28, 0.95); border: 2px solid var(--primary-cyan); width: 500px; max-width: 92%; max-height: 85vh;
            overflow-y: auto; padding: 30px; clip-path: polygon(0 0, 100% 0, 100% 94%, 94% 100%, 0 100%); box-shadow: 0 0 35px rgba(0, 210, 255, 0.25); box-sizing: border-box; border-left: 5px solid var(--primary-cyan);
        }
        .modal-box::-webkit-scrollbar { width: 4px; }
        .modal-box::-webkit-scrollbar-thumb { background: var(--primary-cyan); }
        .modal-title { font-style: italic; text-transform: uppercase; margin-top: 0; color: var(--primary-cyan); letter-spacing: 2px; font-size: 22px; text-shadow: var(--neon-glow); }
        
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 11px; text-transform: uppercase; color: #88a0b0; margin-bottom: 6px; letter-spacing: 1.5px; font-weight: bold; }
        .form-control { width: 100%; background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(0, 210, 255, 0.25); padding: 11px 14px; border-radius: 0; color: white; box-sizing: border-box; outline: none; transition: all 0.2s; font-size: 14px; }
        .form-control:focus { border-color: var(--primary-cyan); background: rgba(0, 210, 255, 0.04); box-shadow: inset 0 0 8px rgba(0, 210, 255, 0.2); }
        
        .password-highlight { border-color: #ff0055 !important; }
        .password-highlight:focus { box-shadow: 0 0 10px rgba(255, 0, 85, 0.5) !important; }

        .modal-actions { display: flex; justify-content: flex-end; align-items: center; gap: 12px; margin-top: 25px; position: sticky; bottom: 0; background: rgba(8, 18, 28, 0.01); padding-top: 10px; }
        .btn-cancel { 
            background: transparent; border: 1px solid rgba(255, 255, 255, 0.15); color: #888; padding: 10px 22px; border-radius: 0; 
            cursor: pointer; font-weight: 800; text-transform: uppercase; font-size: 12px; letter-spacing: 1px; font-style: italic;
            clip-path: polygon(0 0, 88% 0, 100% 40%, 100% 100%, 12% 100%, 0 60%); transition: all 0.2s;
        }
        .btn-cancel:hover { background: rgba(255, 255, 255, 0.05); color: #fff; border-color: #fff; }

        @media (max-width: 768px) {
            .game-container::before {
                backdrop-filter: none !important;
                -webkit-backdrop-filter: none !important;
                background: radial-gradient(circle at center, rgba(0, 0, 0, 0) 0%, rgba(5, 10, 15, 0.2) 100%) !important;
            }

            .search-container .fa-search {
                display: none !important;
            }

            .character-display { flex: 1; display: flex; flex-direction: column; justify-content: flex-end; align-items: center; padding: 0 0 10px 0; z-index: 5; pointer-events: none; }
            .character-art { display: none; }
            .character-stats { width: 92%; max-width: 420px; padding: 15px 20px; border-right: none; border-left: 4px solid var(--primary-cyan); clip-path: none; border-radius: 8px; box-sizing: border-box; background: rgba(5, 15, 25, 0.93); backdrop-filter: blur(15px); box-shadow: 0 -5px 25px rgba(0, 0, 0, 0.8); display: none; pointer-events: none; margin-bottom: 15px; }
            .game-container.stats-open .character-stats { display: block; pointer-events: auto; }
            h1 { font-size: 16px; text-align: left; }
            .edit-stat-btn { width: 28px; height: 28px; font-size: 12px; }
            .uid-info { font-size: 10px; text-align: left; }
            hr { margin: 6px 0; }
            .stat-item { font-size: 11px; margin-bottom: 3px; }
            .quote { display: block; font-size: 11px; margin-top: 6px; padding-top: 6px; min-height: auto; } 
            
            .floating-char-name { display: block; text-align: center; width: auto; padding: 6px 25px; font-size: 18px; font-weight: 900; font-style: italic; text-transform: uppercase; color: #ffffff; background: rgba(0, 0, 0, 0.4); border: 1px solid rgba(0, 210, 255, 0.2); border-radius: 30px; margin: 0 auto 15px auto; z-index: 6; cursor: pointer; pointer-events: auto; }
            .floating-char-name::after { content: ' \25b2'; font-size: 10px; color: var(--primary-cyan); }
            .game-container.stats-open .floating-char-name { color: var(--primary-cyan); background: rgba(0, 210, 255, 0.1); border-color: var(--primary-cyan); }
            .game-container.stats-open .floating-char-name::after { content: ' \25bc'; }
            
            .bottom-ui { flex: none; height: auto; padding: 15px 0; gap: 10px; background: rgba(0, 0, 0, 0.9); border-top: 1px solid rgba(0, 210, 255, 0.2); z-index: 10; }
            .search-container { padding: 0 20px; width: 100%; box-sizing: border-box; gap: 10px; }
            .search-box { flex: 1; width: auto; padding: 9px 10px; font-size: 12px; border-radius: 0; }
            
            .download-btn, .add-char-btn { padding: 9px 15px; font-size: 12px; display: inline-flex; align-items: center; justify-content: center; }
            .download-btn span, .add-char-btn span { display: none; } 
            
            .character-grid { align-items: center; gap: 12px; padding: 4px 20px; width: 100vw; }
            .char-card { width: 22vw; height: 30vw; max-width: 90px; max-height: 120px; transform: skewX(-8deg); }
            .char-card img { transform: skewX(8deg) translateX(-15%); }
        }
    </style>
</head>
<body>

    <div class="game-container" id="main-container">
        <section class="character-display">
            <div class="character-art">
                <img id="main-art" src="" alt="Character">
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
                    <input type="url" name="social" id="form-social" class="form-control" placeholder="https://tiktok.com/@username">
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

    <script>
        // Membuat array charData kosong di JavaScript
        const charData = [];

        <?php
        // Mengisi array charData langsung menggunakan perulangan PHP tanpa json_encode
        if (!empty($charData)) {
            foreach ($charData as $char) {
                ?>
                charData.push({
                    "id": "<?php echo addslashes($char['id']); ?>",
                    "uid": "<?php echo addslashes($char['uid']); ?>",
                    "name": "<?php echo addslashes($char['name']); ?>",
                    "server": "<?php echo addslashes($char['server']); ?>",
                    "game": "<?php echo addslashes($char['game']); ?>",
                    "guild": "<?php echo addslashes($char['guild']); ?>",
                    "class": "<?php echo addslashes($char['class']); ?>",
                    "social": "<?php echo addslashes($char['social']); ?>",
                    "quote": "<?php echo addslashes($char['quote']); ?>",
                    "avatar": "<?php echo addslashes($char['avatar']); ?>",
                    "preview": "<?php echo addslashes($char['preview']); ?>"
                });
                <?php
            }
        }
        ?>

        // JAMINAN SINKRONISASI JIKA DATABASE KOSONG / BELUM TERBACA HOSING
        if (charData.length === 0) {
            charData.push(
                {
                    "id": "2",
                    "uid": "7732-2005-02",
                    "name": "Roronoa Zoro",
                    "server": "SEA-05",
                    "game": "One Piece Bounty",
                    "guild": "STRAW HAT",
                    "class": "Santoryu Style",
                    "social": "https://instagram.com/zoro",
                    "quote": "Scars on the back are a swordsman shame.",
                    "avatar": "images/char2.png",
                    "preview": "images/bg2.png"
                },
                {
                    "id": "5",
                    "uid": "4013589389",
                    "name": "YangHei",
                    "server": "GLOBAL",
                    "game": "Where Winds Meet",
                    "guild": "Indo-Pavilion",
                    "class": "bellstrike splendor",
                    "social": "https://www.tiktok.com/@lulababy00",
                    "quote": "The right man in the wrong place can make all the difference in the world.",
                    "avatar": "https://i.ibb.co.com/rfkq4TYP/char1.png",
                    "preview": "https://i.ibb.co.com/p6PJkFLD/Pix-Verse-V6-Image-Text-360-P-kasih-dedaunan-ber-ezgif-com-optimize.gif"
                }
            );
        }

        let currentActiveCharacter = null; 
        let modalMode = 'add'; 
        const grid = document.getElementById('character-list');
        const container = document.getElementById('main-container');

        const clickSound = new Audio('sound.mp3');
        clickSound.volume = 0.4;

        function playClickSound() {
            clickSound.currentTime = 0; clickSound.play().catch(o => {});
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
            document.getElementById('modalTitle').innerText = "Deploy Character";
            document.getElementById('passwordLabel').innerHTML = "<i class='fas fa-key'></i> Create Password";
            document.getElementById('passwordHelp').innerText = "Password wajib diisi untuk mengedit atau menghapus kartu ini di kemudian hari.";
            document.getElementById('form-password').placeholder = "Tentukan password karaktermu";
            document.getElementById('submitBtn').innerText = "Deploy Core";
            document.getElementById('formModal').style.display = 'flex';
        }

        function openModalForEdit(event) {
            event.stopPropagation(); playClickSound();
            if (!currentActiveCharacter || currentActiveCharacter.id === "0") {
                alert("Tidak ada data karakter untuk diedit."); return;
            }
            modalMode = 'edit';
            document.getElementById('form-password').value = ""; 
            document.getElementById('deleteBtn').style.display = 'inline-flex';
            document.getElementById('passwordLabel').innerHTML = "<i class='fas fa-lock'></i> Input Karakter Password";
            document.getElementById('passwordHelp').innerText = "Masukkan password karakter ini untuk melakukan perubahan atau menghapus.";
            document.getElementById('form-password').placeholder = "Masukkan password untuk verifikasi";

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

            document.getElementById('modalTitle').innerText = "Modify Override System";
            document.getElementById('submitBtn').innerText = "Apply Changes";
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

            fetch(targetUrl, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.status === 403) {
                    alert('ACCESS DENIED: Password Salah!');
                    throw new Error('Auth Failed');
                }
                if (!response.ok) {
                    alert('Terjadi kesalahan pada server.');
                    throw new Error('Server Error');
                }
                return response.text();
            })
            .then(data => {
                alert(modalMode === 'edit' ? 'Data berhasil diperbarui!' : 'Karakter berhasil ditambahkan!'); 
                closeModal();
                window.location.reload();
            })
            .catch(error => console.error('Error:', error));
        }

        function handleDeleteCharacter(event) {
            event.stopPropagation(); playClickSound();
            const inputPass = document.getElementById('form-password').value.trim();
            const charId = document.getElementById('form-char-id').value;

            if (inputPass === "") {
                alert("Harap isi kolom password terlebih dahulu sebagai konfirmasi penghapusan!");
                document.getElementById('form-password').focus();
                return;
            }

            if (!confirm("Apakah kamu yakin ingin menghapus data karakter ini secara permanen?")) {
                return;
            }

            const formData = new FormData();
            formData.append('char_id', charId);
            formData.append('password', inputPass);

            fetch('deletechar.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.status === 403) {
                    alert('ACCESS DENIED: Password Salah! Gagal menghapus.');
                    throw new Error('Auth Failed');
                }
                if (!response.ok) {
                    alert('Terjadi kesalahan server saat menghapus.');
                    throw new Error('Server Error');
                }
                return response.text();
            })
            .then(data => {
                alert('Data karakter berhasil dihapus dari sistem!');
                closeModal();
                window.location.reload();
            })
            .catch(error => console.error('Error:', error));
        }

        function toggleMobileStats(event) {
            event.stopPropagation(); activateFullscreen(); playClickSound();
            container.classList.toggle('stats-open');
        }

        function downloadCurrentImage(event) {
            event.stopPropagation(); 
            if (!currentActiveCharacter) return;
            const imageUrl = currentActiveCharacter.preview;

            if (window.innerWidth <= 768) {
                window.open(imageUrl, '_blank');
            } else {
                const imageName = currentActiveCharacter.name.replace(/\s+/g, '-').toLowerCase() + "-bg.png";
                const downloadLink = document.createElement('a');
                downloadLink.href = imageUrl;
                downloadLink.download = imageName;
                document.body.appendChild(downloadLink);
                downloadLink.click();
                document.body.removeChild(downloadLink);
            }
        }

        function initGrid() {
            grid.innerHTML = '';
            charData.forEach((char) => {
                if (char.id === "0") return; 
                const card = document.createElement('div');
                card.className = 'char-card';
                card.setAttribute('data-name', char.name.toLowerCase());
                card.innerHTML = `<img src="${char.avatar}" alt="${char.name}">`;
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
            if (lowerUrl.includes('facebook.com') || lowerUrl.includes('fb.com')) return { icon: 'fab fa-facebook-f', name: 'Facebook', styleClass: 'brand-facebook' };
            if (lowerUrl.includes('youtube.com') || lowerUrl.includes('youtu.be')) return { icon: 'fab fa-youtube', name: 'YouTube', styleClass: 'brand-youtube' };
            return { icon: 'fas fa-link', name: 'Website', styleClass: 'brand-default' }; 
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
             
            const quoteElem = document.getElementById('char-quote');
            if(quoteElem) quoteElem.innerText = `"${data.quote}"`;

            const socialBox = document.getElementById('char-socials');
            socialBox.innerHTML = ''; 

            if (data.social && data.social.trim() !== "") {
                const brand = getSocialDetails(data.social);
                const aTag = document.createElement('a');
                aTag.href = data.social; aTag.target = '_blank'; aTag.className = `social-icon ${brand.styleClass}`; 
                aTag.innerHTML = `<i class="${brand.icon}"></i> ${brand.name}`;
                socialBox.appendChild(aTag);
                socialBox.style.display = 'flex';
            } else {
                socialBox.style.display = 'none'; 
            }

            document.querySelectorAll('.char-card').forEach(c => c.classList.remove('active'));
            element.classList.add('active');
        }

        window.addEventListener('click', () => {
            if (window.innerWidth <= 768) {
                if(container.classList.contains('stats-open')) {
                    container.classList.remove('stats-open');
                }
                activateFullscreen(); 
            }
        });

        document.getElementById('searchInput').addEventListener('input', (e) => {
            const val = e.target.value.toLowerCase();
            document.querySelectorAll('.char-card').forEach(card => {
                const name = card.getAttribute('data-name');
                if (name.includes(val)) {
                    card.style.removeProperty('display'); 
                } else {
                    card.style.display = 'none'; 
                }
            });
        });

        initGrid();
        window.onload = () => {
            const firstCard = document.querySelector('.char-card');
            if (firstCard) updateSelection(charData[0], firstCard);
        };
    </script>
</body>
</html>