document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('image-upload-container');
    if (!container) return;

    const addBtn = document.getElementById('add-image-btn');
    const finalInput = document.getElementById('final-upload-input');
    const selectorInput = document.getElementById('file-selector');
    const form = document.getElementById('edit-review-form');

    const dt = new DataTransfer();
    const maxImages = 3;

    function updateState() {
        const imageCount = container.querySelectorAll('.preview-box').length;
        addBtn.style.display = imageCount >= maxImages ? 'none' : 'flex';
    }

    addBtn.addEventListener('click', () => {
        if (container.querySelectorAll('.preview-box').length >= maxImages) return;
        selectorInput.click();
    });

    selectorInput.addEventListener('change', function () {
        let currentTotal = container.querySelectorAll('.preview-box').length;

        for (const file of this.files) {
            if (currentTotal >= maxImages) break;
            if (!file.type.startsWith('image/')) continue;

            dt.items.add(file);
            createPreview(file);
            currentTotal++;
        }

        finalInput.files = dt.files;
        updateState();
        this.value = '';
    });

    container.addEventListener('click', function (e) {
        const removeBtn = e.target.closest('.remove-btn');
        if (!removeBtn) return;

        const previewBox = removeBtn.closest('.preview-box');

        // gambar lama
        if (removeBtn.classList.contains('remove-existing')) {
            if (!confirm('Hapus gambar lama ini?')) return;

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'deleted_images[]';
            input.value = removeBtn.dataset.id;
            form.appendChild(input);

            previewBox.remove();
            updateState();
            return;
        }

        // gambar baru
        if (removeBtn.classList.contains('remove-new')) {
            const file = removeBtn.fileReference;

            for (let i = 0; i < dt.items.length; i++) {
                if (dt.items[i].getAsFile() === file) {
                    dt.items.remove(i);
                    break;
                }
            }

            previewBox.remove();
            finalInput.files = dt.files;
            updateState();
        }
    });

    function createPreview(file) {
        const div = document.createElement('div');
        div.className = 'preview-box';

        const reader = new FileReader();
        reader.onload = e => div.style.backgroundImage = `url(${e.target.result})`;
        reader.readAsDataURL(file);

        const btn = document.createElement('div');
        btn.className = 'remove-btn remove-new';
        btn.innerHTML = '<i class="bi bi-x"></i>';
        btn.fileReference = file;

        div.appendChild(btn);
        container.insertBefore(div, addBtn);
    }

    form.addEventListener('submit', () => {
        finalInput.files = dt.files;
    });

    updateState();
});
