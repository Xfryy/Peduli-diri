// Validasi suhu tubuh
document.addEventListener('DOMContentLoaded', function() {
    const suhuInput = document.getElementById('suhu_tubuh');
    if (suhuInput) {
        suhuInput.addEventListener('input', function() {
            const suhu = parseFloat(this.value);
            if (suhu >= 37.5) {
                this.style.color = '#dc3545';
                this.style.fontWeight = 'bold';
            } else {
                this.style.color = '#28a745';
                this.style.fontWeight = 'normal';
            }
        });

        // Set warna awal suhu tubuh
        const suhu = parseFloat(suhuInput.value);
        if (suhu >= 37.5) {
            suhuInput.style.color = '#dc3545';
            suhuInput.style.fontWeight = 'bold';
        } else {
            suhuInput.style.color = '#28a745';
            suhuInput.style.fontWeight = 'normal';
        }
    }
}); 