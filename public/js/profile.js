function previewImage(input) {
    const preview = document.getElementById('preview-foto');
    const file = input.files[0];
    const reader = new FileReader();

    reader.onloadend = function () {
        preview.src = reader.result;
    }

    if (file) {
        reader.readAsDataURL(file);
    } else {
        preview.src = '<?php echo $foto; ?>';
    }
} 