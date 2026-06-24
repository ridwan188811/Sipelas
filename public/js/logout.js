window.confirmLogout = function(event, href) {
    event.preventDefault();
    Swal.fire({
      showConfirmButton: false,
      customClass: { 
        popup: 'custom-conf-popup', 
        htmlContainer: 'custom-conf-html-container'
      },
      html: `
        <div class="custom-conf-icon-wrapper" style="background-color: #fee2e2;">
          <div class="custom-conf-icon-inner" style="background-color: #991b1b;">?</div>
        </div>
        <h2 class="custom-conf-title">Konfirmasi Keluar</h2>
        <p class="custom-conf-text">Apakah Anda yakin ingin keluar dari sistem?</p>
        <div class="custom-conf-actions">
          <button type="button" class="custom-conf-btn-cancel" onclick="Swal.close()">Batal</button>
          <button type="button" class="custom-conf-btn-confirm" style="background-color: #b91c1c !important;" onclick="window.location.href='${href}'" onmouseover="this.style.backgroundColor='#991b1b'" onmouseout="this.style.backgroundColor='#b91c1c'">Ya, Keluar</button>
        </div>
      `
    });
};

if (!document.getElementById('custom-conf-css')) {
    const style = document.createElement('style');
    style.id = 'custom-conf-css';
    style.innerHTML = `
      .custom-conf-popup { border-radius: 16px !important; padding: 32px 24px 28px !important; width: 420px !important; }
      .custom-conf-html-container { margin: 0 !important; padding: 0 !important; overflow: visible !important; }
      .custom-conf-icon-wrapper { width: 72px; height: 72px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; }
      .custom-conf-icon-inner { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 22px; font-weight: 700; font-family: 'Inter', sans-serif; }
      .custom-conf-title { font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 0 0 10px; font-family: 'Inter', sans-serif; text-align: center; }
      .custom-conf-text { font-size: 0.92rem; color: #64748b; line-height: 1.5; margin: 0 0 28px; font-family: 'Inter', sans-serif; text-align: center; }
      .custom-conf-actions { display: flex; gap: 12px; margin: 0; width: 100%; }
      .custom-conf-btn-cancel { flex: 1; background-color: #cbd5e1 !important; color: #1e293b !important; border: none !important; border-radius: 9999px !important; padding: 12px 0 !important; font-weight: 600 !important; font-size: 0.9rem !important; cursor: pointer; text-align: center; font-family: 'Inter', sans-serif; transition: background-color 0.2s; outline: none !important; box-shadow: none !important; }
      .custom-conf-btn-cancel:hover { background-color: #94a3b8 !important; }
      .custom-conf-btn-confirm { flex: 1; color: white !important; border: none !important; border-radius: 9999px !important; padding: 12px 0 !important; font-weight: 600 !important; font-size: 0.9rem !important; cursor: pointer; text-align: center; font-family: 'Inter', sans-serif; outline: none !important; box-shadow: none !important; transition: background-color 0.2s; }
    `;
    document.head.appendChild(style);
}
