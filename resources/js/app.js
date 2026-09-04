import './bootstrap';

// Toggle navigasi mobile di header.
document.addEventListener('DOMContentLoaded', () => {
    const imageInput = document.querySelector('#image[type="file"]');
    const dropzone = document.querySelector('[data-image-dropzone]');
    const preview = document.querySelector('[data-image-preview]');
    const placeholder = document.querySelector('[data-image-placeholder]');
    const imageName = document.querySelector('[data-image-name]');

    if (imageInput && dropzone && preview && placeholder && imageName) {
        const showPreview = (file) => {
            if (!file || !file.type.startsWith('image/')) {
                return;
            }

            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
            imageName.textContent = file.name;
            imageName.classList.remove('hidden');
        };

        imageInput.addEventListener('change', () => showPreview(imageInput.files[0]));

        ['dragenter', 'dragover'].forEach((eventName) => {
            dropzone.addEventListener(eventName, (event) => {
                event.preventDefault();
                dropzone.classList.add('border-brand-400', 'bg-brand-50');
            });
        });

        ['dragleave', 'drop'].forEach((eventName) => {
            dropzone.addEventListener(eventName, (event) => {
                event.preventDefault();
                dropzone.classList.remove('border-brand-400', 'bg-brand-50');
            });
        });

        dropzone.addEventListener('drop', (event) => {
            const file = event.dataTransfer.files[0];

            if (file) {
                imageInput.files = event.dataTransfer.files;
                showPreview(file);
            }
        });
    }

    const toggle = document.querySelector('[data-nav-toggle]');
    const panel = document.querySelector('[data-nav-panel]');

    if (!toggle || !panel) {
        return;
    }

    const setOpen = (open) => {
        toggle.setAttribute('aria-expanded', String(open));
        panel.hidden = !open;
    };

    toggle.addEventListener('click', () => {
        setOpen(toggle.getAttribute('aria-expanded') !== 'true');
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setOpen(false);
        }
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const editor = document.querySelector('[data-rich-editor]');
    const input = document.querySelector('#content[name="content"]');

    if (!editor || !input) {
        return;
    }

    const syncContent = () => {
        input.value = editor.innerHTML;
    };

    document.querySelectorAll('[data-editor-command]').forEach((control) => {
        const eventName = control.tagName === 'SELECT' ? 'change' : 'click';

        if (control.tagName !== 'SELECT') {
            control.addEventListener('mousedown', (event) => event.preventDefault());
        }

        control.addEventListener(eventName, () => {
            const command = control.dataset.editorCommand;

            if (command === 'createLink') {
                const url = window.prompt('Masukkan URL tautan:');
                if (url) {
                    document.execCommand(command, false, url);
                }
            } else {
                document.execCommand(command, false, control.value || null);
            }

            editor.focus();
            syncContent();
        });
    });

    editor.addEventListener('input', syncContent);
    editor.closest('form')?.addEventListener('submit', syncContent);
    syncContent();
});
