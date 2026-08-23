/* ---------- Rich text editor ---------- */
if (typeof tinymce !== 'undefined') {
    document.querySelectorAll('.rich-editor').forEach((el) => {
        tinymce.init({
            target: el,
            height: el.id === 'body' ? 480 : 340,
            menubar: false,
            plugins: 'lists link image table code help',
            toolbar: 'undo redo | blocks | bold italic underline | bullist numlist | link image table | blockquote removeformat | code',
            images_upload_url: null,
            automatic_uploads: false,
            file_picker_types: 'image',
            skin: 'oxide',
            content_css: 'default',
            convert_urls: false,
            promotion: false,
            branding: false,
        });
    });
}

/* ---------- Sidebar toggle (mobile) ---------- */
const burger = document.getElementById('admin-burger');
burger?.addEventListener('click', () => {
    const open = document.body.classList.toggle('sidebar-open');
    burger.setAttribute('aria-expanded', String(open));
});

document.addEventListener('click', (e) => {
    if (document.body.classList.contains('sidebar-open')
        && !e.target.closest('#admin-sidebar')
        && !e.target.closest('#admin-burger')) {
        document.body.classList.remove('sidebar-open');
        burger?.setAttribute('aria-expanded', 'false');
    }
});

/* ---------- Confirm dangerous actions ---------- */
document.querySelectorAll('form[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (e) => {
        if (!window.confirm(form.dataset.confirm)) e.preventDefault();
    });
});

/* ---------- Auto slug from title ---------- */
const slugSource = document.querySelector('[data-slug-source]');
const slugTarget = document.querySelector('[data-slug-target]');
if (slugSource && slugTarget) {
    slugSource.addEventListener('input', () => {
        if (slugTarget.dataset.touched === '1') return;
        slugTarget.value = slugSource.value
            .toLowerCase()
            .normalize('NFKD')
            .replace(/[^\w\s-]/g, '')
            .trim()
            .replace(/[\s_]+/g, '-')
            .replace(/-+/g, '-');
    });
    slugTarget.addEventListener('input', () => { slugTarget.dataset.touched = '1'; });
}

/* ---------- Image input previews ---------- */
document.querySelectorAll('input[type="file"][accept*="image"], #favicon_file').forEach((input) => {
    input.addEventListener('change', () => {
        const panel = input.closest('.admin-panel') ?? input.closest('form');
        if (!panel || !input.files?.[0]) return;

        let preview = panel.querySelector('.upload-preview');
        if (!preview) {
            preview = document.createElement('img');
            preview.className = 'img-preview upload-preview';
            preview.alt = '';
            input.insertAdjacentElement('beforebegin', preview);
        }
        preview.src = URL.createObjectURL(input.files[0]);
    });
});

/* ---------- SEO character counters ---------- */
['meta_title', 'meta_description'].forEach((name) => {
    const field = document.getElementById(name);
    const counter = document.querySelector(`[data-meta-count="${name}"]`);
    if (!field || !counter) return;

    const update = () => {
        counter.textContent = `${field.value.length} characters.`;
    };
    update();
    field.addEventListener('input', update);
});
