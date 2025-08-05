// Function to submit new catatan
function submitCatatan(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...';

    fetch('/app/controllers/catatan_controller.php?action=create', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('Catatan berhasil disimpan!');
            loadContent('/app/views/catatan_perjalanan.php');
        } else {
            alert(data.message || 'Terjadi kesalahan saat menyimpan catatan.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan catatan: ' + error.message);
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
    });
}

// Function to handle catatan deletion
function hapusCatatan(id) {
    if (confirm('Apakah Anda yakin ingin menghapus catatan ini?')) {
        // Show loading state
        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'text-center mt-3';
        loadingDiv.innerHTML = '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>';
        document.querySelector('#mainContent').appendChild(loadingDiv);
        
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
                    loadContent('/app/views/catatan_perjalanan.php');
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

// Function to load content via AJAX
function loadContent(page, clickedElement) {
    const mainContent = document.getElementById('mainContent');
    const welcomeContent = document.getElementById('welcomeContent');
    
    // Show loading
    mainContent.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';
    
    fetch(page)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(data => {
            mainContent.innerHTML = data;
            if (welcomeContent) {
                welcomeContent.style.display = 'none';
            }
            
            // Update active nav link
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
            });
            if (clickedElement) {
                clickedElement.classList.add('active');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mainContent.innerHTML = '<div class="alert alert-danger">Error loading content: ' + error.message + '</div>';
        });
}

// Function to print catatan
function printCatatan() {
    window.print();
}

// Function to generate PDF from catatan
function generatePDF(elementId, filename) {
    const element = document.getElementById(elementId);
    const opt = {
        margin: [10, 10, 10, 10],
        filename: filename || 'catatan-perjalanan.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2, useCORS: true },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };
    
    // Show loading
    const loadingDiv = document.createElement('div');
    loadingDiv.className = 'position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-white bg-opacity-75';
    loadingDiv.style.zIndex = '9999';
    loadingDiv.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Generating PDF...</p></div>';
    document.body.appendChild(loadingDiv);
    
    // Generate PDF
    html2pdf().set(opt).from(element).save().then(() => {
        document.body.removeChild(loadingDiv);
    });
}

// Function to print detail catatan
function printDetail() {
    window.print();
}

// Add click handlers to nav links
document.addEventListener('DOMContentLoaded', function() {
    // Add click handlers to navigation links
    const navLinks = document.querySelectorAll('nav a');
    navLinks.forEach(link => {
        if (link.href.includes('catatan_perjalanan.php') || 
            link.href.includes('isi_data.php') || 
            link.href.includes('profile_view.php')) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                loadContent(this.href, this);
            });
        }
    });
}); 