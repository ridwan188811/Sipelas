// public/js/notif.js
document.addEventListener('DOMContentLoaded', function() {
    let currentNotifCount = null;
    let isFirstCheck = true;

    // --- Audio Unlock untuk HP (Android/iOS) ---
    let audioCtx = null;
    let isUnlocked = false;

    function unlockAudio() {
        if (isUnlocked) return;
        try {
            audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            
            // Mainkan suara kosong (bisu) selama 0.1 detik untuk mengizinkan (unlock) browser memainkan suara
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            gain.gain.value = 0; // Volume 0
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.start(0);
            osc.stop(0.1);
            
            isUnlocked = true;
            
            // Setelah terbuka, copot pendengarnya
            document.body.removeEventListener('click', unlockAudio);
            document.body.removeEventListener('touchstart', unlockAudio);
        } catch (e) {
            console.log("Web Audio tidak disupport di browser ini.");
        }
    }

    // Pasang pancingan agar saat user klik/sentuh layar pertama kali, audio terbuka
    document.body.addEventListener('click', unlockAudio);
    document.body.addEventListener('touchstart', unlockAudio);

    function playNotificationSound() {
        if (!audioCtx || !isUnlocked) {
            console.log("Audio belum di-unlock oleh interaksi user.");
            return;
        }
        
        try {
            if (audioCtx.state === 'suspended') {
                audioCtx.resume();
            }
            const oscillator = audioCtx.createOscillator();
            const gainNode = audioCtx.createGain();

            // Suara "Ting!"
            oscillator.type = 'sine';
            oscillator.frequency.setValueAtTime(880, audioCtx.currentTime); 
            oscillator.frequency.exponentialRampToValueAtTime(1760, audioCtx.currentTime + 0.1); // Naik oktav dengan cepat

            gainNode.gain.setValueAtTime(0, audioCtx.currentTime);
            gainNode.gain.linearRampToValueAtTime(0.5, audioCtx.currentTime + 0.05); // Attack cepat
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.4); // Decay pelan

            oscillator.connect(gainNode);
            gainNode.connect(audioCtx.destination);

            oscillator.start(audioCtx.currentTime);
            oscillator.stop(audioCtx.currentTime + 0.5);
        } catch(e) {
            console.log("Gagal memutar audio:", e);
        }
    }

    let currentLastUpdated = null;

    function checkNotifications() {
        fetch('/check-notif', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
            .then(res => res.json())
            .then(data => {
                const count = data.count;
                const lastUpdated = data.last_updated;
                
                // Jika baru pertama kali cek, simpan state awal dan tidak perlu bunyi
                if (isFirstCheck) {
                    currentNotifCount = count;
                    currentLastUpdated = lastUpdated;
                    isFirstCheck = false;
                    return;
                }

                // Jika ada notifikasi bertambah, bunyikan suara
                if (count > currentNotifCount) {
                    playNotificationSound();
                }

                // Jika database ada perubahan (pengajuan baru atau status berubah)
                if (lastUpdated !== currentLastUpdated) {
                    currentLastUpdated = lastUpdated;
                    document.dispatchEvent(new CustomEvent('db-updated'));
                }
                
                currentNotifCount = count;

                // Update UI Badge
                const notifBtn = document.getElementById('notifBtn');
                const notifDropdown = document.getElementById('notifDropdown');
                
                if (notifBtn) {
                    let badge = notifBtn.querySelector('.notif-badge');
                    if (count > 0) {
                        if (!badge) {
                            badge = document.createElement('span');
                            badge.className = 'notif-badge';
                            badge.style.cssText = 'position: absolute; top: 5px; right: 5px; width: 8px; height: 8px; background: #ef4444; border-radius: 50%; border: 2px solid var(--navy);';
                            notifBtn.appendChild(badge);
                        }
                    } else {
                        if (badge) badge.remove();
                    }
                }

                if (notifDropdown) {
                    const header = notifDropdown.querySelector('div');
                    if (header) {
                        let badgeSpan = header.querySelector('span');
                        if (count > 0) {
                            if (!badgeSpan) {
                                badgeSpan = document.createElement('span');
                                badgeSpan.style.cssText = 'background: #ef4444; color: #fff; font-size: 0.7rem; padding: 2px 8px; border-radius: 10px;';
                                header.appendChild(badgeSpan);
                            }
                            badgeSpan.textContent = count + ' Baru';
                        } else {
                            if (badgeSpan) badgeSpan.remove();
                        }
                    }

                    const listContainer = notifDropdown.querySelector('div:nth-child(2)');
                    if (listContainer) {
                        listContainer.innerHTML = '';
                        if (data.list.length > 0) {
                            data.list.forEach(item => {
                                const a = document.createElement('a');
                                a.href = item.url;
                                a.style.cssText = 'display: block; padding: 12px 16px; border-bottom: 1px solid #f1f5f9; text-decoration: none; transition: background .15s;';
                                a.onmouseover = () => a.style.background = '#f8fafc';
                                a.onmouseout = () => a.style.background = 'transparent';
                                a.innerHTML = `
                                    <div style="font-size: 0.85rem; color: #1e293b; font-weight: 600; margin-bottom: 4px;">${item.title}</div>
                                    <div style="font-size: 0.75rem; color: #64748b;">${item.desc}</div>
                                    <div style="font-size: 0.7rem; color: #94a3b8; margin-top: 6px;">${item.time}</div>
                                `;
                                listContainer.appendChild(a);
                            });
                        } else {
                            listContainer.innerHTML = '<div style="padding: 20px; text-align: center; color: #64748b; font-size: 0.85rem;">Belum ada notifikasi</div>';
                        }
                    }
                }
            })
            .catch(err => console.error('Error fetching notif:', err));
    }

    // Jalankan cek setiap 5 detik
    setInterval(checkNotifications, 5000);

    // Event listener saat lonceng diklik untuk menghilangkan badge merah (mark as read)
    document.addEventListener('click', function(e) {
        const notifBtn = document.getElementById('notifBtn');
        if (notifBtn && (notifBtn.contains(e.target) || e.target === notifBtn)) {
            const badge = notifBtn.querySelector('.notif-badge');
            if (badge) {
                // Hapus badge merah di tombol
                badge.remove();
                currentNotifCount = 0; // reset local count

                // Kirim request ke server untuk update database
                if (window.location.pathname.startsWith('/admin')) {
                    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                    if (tokenMeta) {
                        fetch('/admin/mark-notif-read', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': tokenMeta.content
                            }
                        }).catch(err => console.error(err));
                    }
                } else if (window.location.pathname.startsWith('/user')) {
                    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                    if (tokenMeta) {
                        fetch('/user/mark-notif-read', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': tokenMeta.content
                            }
                        }).catch(err => console.error(err));
                    }
                }
            }
        }
    });

    // Event listener untuk update DOM secara realtime (PJAX manual)
    document.addEventListener('db-updated', function() {
        const activeEl = document.activeElement;
        const isTyping = activeEl && ['INPUT', 'TEXTAREA', 'SELECT'].includes(activeEl.tagName);
        const isSwalOpen = document.body.classList.contains('swal2-shown');
        const isModalOpen = document.querySelector('.modal-overlay.active') || document.querySelector('.modal.active');
        
        // Jangan ganggu jika user sedang berinteraksi dengan form/modal
        if (!isTyping && !isSwalOpen && !isModalOpen) {
            const currentUrl = window.location.href;
            const fetchUrl = currentUrl + (currentUrl.includes('?') ? '&' : '?') + '_t=' + new Date().getTime();
            fetch(fetchUrl)
                .then(res => res.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    // Daftar container yang ingin diupdate realtime
                    const selectors = [
                        '.table-card',       // Daftar Pengajuan & Riwayat
                        '.stats-grid',       // Statistik Dashboard Admin
                        '.admin-grid',       // Layout konten Dashboard Admin
                        '.dashboard-cards',  // Statistik Dashboard User
                        '.timeline',         // Timeline status di Detail Pengajuan
                        '.doc-list'          // List dokumen di Detail
                    ];
                    
                    selectors.forEach(selector => {
                        const currentEl = document.querySelector(selector);
                        const newEl = doc.querySelector(selector);
                        if (currentEl && newEl) {
                            currentEl.innerHTML = newEl.innerHTML;
                        }
                    });
                })
                .catch(err => console.error('Gagal update realtime:', err));
        }
    });
});
