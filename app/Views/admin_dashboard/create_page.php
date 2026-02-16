<?php
// Admin create page with TinyMCE and dynamic page sections
declare(strict_types=1);
// variables expected: $title
?>

<h1><?= htmlspecialchars($title ?? 'Create Page') ?></h1>

<form method="post" action="/admin/pages/create" id="create-page-form">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf ?? '') ?>">

    <div class="form-row">
        <label>Title</label>
        <input type="text" name="title" required />
    </div>

    <div class="form-row">
        <label>Slug</label>
        <input type="text" name="slug" required />
    </div>

    <div class="form-row">
        <label>Status</label>
        <select name="status">
            <option value="draft">Draft</option>
            <option value="published">Published</option>
            <option value="archived">Archived</option>
        </select>
    </div>

    <div class="form-row">
        <label>Content</label>
        <textarea id="page_content" name="content" class="tinymce" rows="10"></textarea>
    </div>

    <hr />

    <h2>Page Sections</h2>
    <p>Use the button to add one or more page sections. Each section will be submitted with the page.</p>
    <div>
        <button type="button" id="open-section-modal">Add Page Section</button>
    </div>

    <div id="sections_list">
        <!-- added sections will appear here -->
    </div>

    <div class="form-row">
        <button type="submit">Create Page</button>
    </div>
</form>

<!-- Section modal (hidden) -->
<div id="section-modal" style="display:none; position:fixed; left:0; right:0; top:0; bottom:0; background:rgba(0,0,0,0.5);">
    <div style="background:#fff; max-width:800px; margin:40px auto; padding:20px; position:relative;">
        <button type="button" id="close-section-modal" style="position:absolute; right:10px; top:10px;">✕</button>
        <h3>Add Page Section</h3>

        <div class="form-row">
            <label>Section Type</label>
            <input type="text" id="sec_section_type" placeholder="e.g. hero, text_block, image_left" />
        </div>

        <div class="form-row">
            <label>Title</label>
            <input type="text" id="sec_title" />
        </div>

        <div class="form-row">
            <label>Content</label>
            <textarea id="modal_section_content" rows="6"></textarea>
        </div>

        <div class="form-row">
            <label>Image ID</label>
            <input type="number" id="sec_image_id" min="1" />
        </div>

        <div class="form-row">
            <label>Button Text</label>
            <input type="text" id="sec_button_text" />
        </div>

            <label>Button Link</label>
            <input type="text" id="sec_button_link" />
        </div>

        <div class="form-row">
            <label>Published?</label>
            <input type="checkbox" id="sec_is_published" />
        </div>

        <div class="form-row">
            <button type="button" id="add-section">Add Section</button>
        </div>
    </div>
</div>

<style>
    .form-row { margin-bottom:12px; }
    .flash { padding:8px; margin-bottom:8px; border-radius:4px; }
    .flash-success { background:#e6ffed; border:1px solid #b7f2c7; }
    .flash-error { background:#ffecec; border:1px solid #f5b6b6; }
    .section-card { border:1px dashed #ccc; padding:8px; margin:8px 0; position:relative; }
    .section-card button.remove { position:absolute; right:8px; top:8px; }
</style>

<?php if (!empty($_ENV['TINYMCE_API_KEY'])): ?>
    <script src="https://cdn.tiny.cloud/1/<?= htmlspecialchars($_ENV['TINYMCE_API_KEY'], ENT_QUOTES, 'UTF-8') ?>/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<?php else: ?>
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<?php endif; ?>
<script>
document.addEventListener('DOMContentLoaded', function(){
    // init page content editor
    tinymce.init({ selector: '#page_content', menubar: false, height: 300 });

    let sectionIndex = 0;

    const modal = document.getElementById('section-modal');
    const openBtn = document.getElementById('open-section-modal');
    const closeBtn = document.getElementById('close-section-modal');
    const addBtn = document.getElementById('add-section');
    const sectionsList = document.getElementById('sections_list');
    const form = document.getElementById('create-page-form');

    openBtn.addEventListener('click', openModal);
    closeBtn.addEventListener('click', closeModal);

    function openModal(){
        modal.style.display = 'block';
        // init editor for modal content (ensure removed first)
        if (tinymce.get('modal_section_content')) tinymce.get('modal_section_content').remove();
        tinymce.init({ selector: '#modal_section_content', menubar:false, height: 160 });
    }
    function closeModal(){
        modal.style.display = 'none';
        if (tinymce.get('modal_section_content')) tinymce.get('modal_section_content').remove();
        // clear modal fields
        document.getElementById('sec_section_type').value = '';
        document.getElementById('sec_title').value = '';
        document.getElementById('sec_image_id').value = '';
        document.getElementById('sec_button_text').value = '';
        document.getElementById('sec_button_link').value = '';
        document.getElementById('sec_is_published').checked = false;
    }

    addBtn.addEventListener('click', function(){
        // ensure editor content is saved
        const modalEditor = tinymce.get('modal_section_content');
        const content = modalEditor ? modalEditor.getContent() : document.getElementById('modal_section_content').value;

        const sectionData = {
            section_type: document.getElementById('sec_section_type').value || '',
            title: document.getElementById('sec_title').value || '',
            content: content || '',
            image_id: document.getElementById('sec_image_id').value || '',
            button_text: document.getElementById('sec_button_text').value || '',
            button_link: document.getElementById('sec_button_link').value || '',
            is_published: document.getElementById('sec_is_published').checked ? 1 : 0
        };

        appendSection(sectionIndex++, sectionData);
        closeModal();
    });

    function appendSection(index, data){
        // create container for hidden inputs
        const container = document.createElement('div');
        container.className = 'section-card';
        container.dataset.index = index;

        // visible summary
        const title = document.createElement('div');
        title.innerHTML = '<strong>' + (data.title || 'Untitled') + '</strong> (' + (data.section_type || 'type') + ')';
        container.appendChild(title);

        const contentPreview = document.createElement('div');
        contentPreview.innerHTML = data.content ? data.content : '<em>No content</em>';
        container.appendChild(contentPreview);

        // remove button
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'remove';
        removeBtn.textContent = 'Remove';
        removeBtn.addEventListener('click', function(){ container.remove(); });
        container.appendChild(removeBtn);

        // hidden inputs for submission
        for (const k in data){
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'sections['+index+']['+k+']';
            input.value = data[k];
            container.appendChild(input);
        }

        sectionsList.appendChild(container);
    }

    // ensure TinyMCE content is copied to textarea on submit
    form.addEventListener('submit', function(){
        if (tinymce.get('page_content')) tinymce.get('page_content').save();
        // also remove modal editor if present
        if (tinymce.get('modal_section_content')) tinymce.get('modal_section_content').save();
    });
});
</script>
