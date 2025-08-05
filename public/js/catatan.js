function hapusCatatan(id) {
    if (confirm('Apakah Anda yakin ingin menghapus catatan ini?')) {
        // Show loading state
        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'text-center mt-3';
        loadingDiv.innerHTML = '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>';
        document.querySelector('.main-box').appendChild(loadingDiv);
        
        fetch(`/app/controllers/catatan_controller.php?action=delete&id=${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Catatan berhasil dihapus!');
                    // Reload the page content
                    if (typeof loadContent === 'function') {
                        loadContent('/app/views/catatan_perjalanan.php');
                    } else {
                        window.location.reload();
                    }
                } else {
                    alert('Gagal menghapus catatan: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan: ' + error.message);
            })
            .finally(() => {
                // Remove loading indicator
                if (loadingDiv.parentNode) {
                    loadingDiv.parentNode.removeChild(loadingDiv);
                }
            });
    }
}

// Fungsi untuk mencetak catatan perjalanan
function printCatatan() {
    // Tampilkan dialog konfirmasi
    if (confirm('Apakah Anda ingin mencetak catatan perjalanan?')) {
        // Opsi 1: Menggunakan window.print() langsung
        window.print();
        
        // Opsi 2: Menggunakan html2pdf.js untuk menghasilkan PDF
        // Uncomment kode di bawah ini jika ingin menggunakan html2pdf
        /*
        // Tampilkan loading
        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-white bg-opacity-75';
        loadingDiv.style.zIndex = '9999';
        loadingDiv.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Generating PDF...</p></div>';
        document.body.appendChild(loadingDiv);
        
        // Generate PDF
        const element = document.getElementById('printArea');
        const opt = {
            margin: [10, 10, 10, 10],
            filename: 'catatan_perjalanan_' + new Date().toISOString().slice(0, 10) + '.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };
        
        html2pdf().set(opt).from(element).save().then(() => {
            // Hapus loading setelah selesai
            document.body.removeChild(loadingDiv);
        });
        */
    }
} 