<?php
/** @var array $page */
/** @var string $title */
/** @var string $csrf */

$pageId = (int) ($page['page_id'] ?? 0);

// Safe defaults for form fields
$valTitle = (string) ($page['title'] ?? '');
$valSlug = (string) ($page['slug'] ?? '');
$valBody = (string) ($page['content'] ?? '');
$isPublished = ((isset($page['status']) && ($page['status']->value ?? (string) $page['status']) === 'published'));
?>

<section class="admin-card">
    <h1><?= htmlspecialchars($title ?? 'Edit Page', ENT_QUOTES, 'UTF-8') ?></h1>

    <?php if (!empty($errors) && is_array($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars((string) $err, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="/admin/pages/<?= $pageId ?>/edit" class="edit-page-form">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf ?? '', ENT_QUOTES, 'UTF-8') ?>">

        <div class="form-row">
            <label for="title">Title</label>
            <input id="title" class="form-input" name="title" type="text" required
                value="<?= htmlspecialchars($valTitle, ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div class="form-row">
            <label for="slug">Slug <span class="hint">Example: jazz-night or history-of-haarlem</span></label>
            <input id="slug" class="form-input" name="slug" type="text" required
                value="<?= htmlspecialchars($valSlug, ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div class="form-row">
            <label for="content">Content</label>
            <textarea id="content" class="form-textarea" name="content"
                rows="12"><?= htmlspecialchars($valBody, ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>

        <div class="form-row" style="flex-direction:row; align-items:center; gap:1rem;">

            <label for="status">Status
                <select id="status" class="form-select" name="status">
                    <option value="draft" <?= (isset($page['status']) && ($page['status']->value ?? $page['status']) === 'draft') ? 'selected' : '' ?>>Draft</option>
                    <option value="published" <?= (isset($page['status']) && ($page['status']->value ?? $page['status']) === 'published') ? 'selected' : '' ?>>Published</option>
                </select>
            </label>
        </div>

        <div class="form-actions">
            <button class="btn-primary" type="submit">Save Changes</button>
            <a class="btn-secondary" href="/admin/pages">Cancel</a>
        </div>
    </form>
</section>

    <hr />

    <section class="admin-card">
        <h2>Page Sections</h2>
        <p>Add or edit page sections for this page.</p>
        <div>
            <button type="button" id="open-section-modal">Add Page Section</button>
        </div>

        <div id="sections_list">
            <?php
            $existingSections = $sections ?? [];
            foreach ($existingSections as $i => $s):
                $sid = (int) ($s['section_id'] ?? 0);
                $st = htmlspecialchars((string)($s['section_type'] ?? ''), ENT_QUOTES, 'UTF-8');
                $stitle = htmlspecialchars((string)($s['title'] ?? ''), ENT_QUOTES, 'UTF-8');
                $scontent = $s['content'] ?? '';
                $simage = htmlspecialchars((string)($s['image_id'] ?? ''), ENT_QUOTES, 'UTF-8');
                $sbtn = htmlspecialchars((string)($s['button_text'] ?? ''), ENT_QUOTES, 'UTF-8');
                $slink = htmlspecialchars((string)($s['button_link'] ?? ''), ENT_QUOTES, 'UTF-8');
                $spub = !empty($s['is_published']) ? 1 : 0;
            ?>
            <div class="section-card" data-index="<?= $i ?>">
                <div><strong><?= $stitle ?: 'Untitled' ?></strong> (<?= $st ?: 'type' ?>)</div>
                <div><?= $scontent ? $scontent : '<em>No content</em>' ?></div>
                <button type="button" class="remove">Remove</button>
                <input type="hidden" name="sections[<?= $i ?>][section_id]" value="<?= $sid ?>">
                <input type="hidden" name="sections[<?= $i ?>][section_type]" value="<?= $st ?>">
                <input type="hidden" name="sections[<?= $i ?>][title]" value="<?= $stitle ?>">
                <input type="hidden" name="sections[<?= $i ?>][content]" value='<?= htmlspecialchars($scontent, ENT_QUOTES, 'UTF-8') ?>'>
                <input type="hidden" name="sections[<?= $i ?>][image_id]" value="<?= $simage ?>">
                <input type="hidden" name="sections[<?= $i ?>][button_text]" value="<?= $sbtn ?>">
                <input type="hidden" name="sections[<?= $i ?>][button_link]" value="<?= $slink ?>">
                <input type="hidden" name="sections[<?= $i ?>][is_published]" value="<?= $spub ?>">
            </div>
            <?php endforeach; ?>
        </div>
    </section>

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
        // init content editor
        if (typeof tinymce !== 'undefined') {
            if (!tinymce.get('content')) {
                tinymce.init({ selector: '#content', menubar:false, height: 300 });
            }
        }

        let sectionIndex = document.querySelectorAll('#sections_list .section-card').length || 0;
        const modal = document.getElementById('section-modal');
        const openBtn = document.getElementById('open-section-modal');
        const closeBtn = document.getElementById('close-section-modal');
        const addBtn = document.getElementById('add-section');
        const sectionsList = document.getElementById('sections_list');
        const form = document.querySelector('.edit-page-form');

        if (openBtn) openBtn.addEventListener('click', openModal);
        if (closeBtn) closeBtn.addEventListener('click', closeModal);

        function openModal(){
            modal.style.display = 'block';
            if (tinymce.get('modal_section_content')) tinymce.get('modal_section_content').remove();
            tinymce.init({ selector: '#modal_section_content', menubar:false, height: 160 });
        }
        function closeModal(){
            modal.style.display = 'none';
            if (tinymce.get('modal_section_content')) tinymce.get('modal_section_content').remove();
            document.getElementById('sec_section_type').value = '';
            document.getElementById('sec_title').value = '';
            document.getElementById('sec_image_id').value = '';
            document.getElementById('sec_button_text').value = '';
            document.getElementById('sec_button_link').value = '';
            document.getElementById('sec_is_published').checked = false;
        }

        addBtn.addEventListener('click', function(){
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
            const container = document.createElement('div');
            container.className = 'section-card';
            container.dataset.index = index;

            const title = document.createElement('div');
            title.innerHTML = '<strong>' + (data.title || 'Untitled') + '</strong> (' + (data.section_type || 'type') + ')';
            container.appendChild(title);

            const contentPreview = document.createElement('div');
            contentPreview.innerHTML = data.content ? data.content : '<em>No content</em>';
            container.appendChild(contentPreview);

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'remove';
            removeBtn.textContent = 'Remove';
            removeBtn.addEventListener('click', function(){ container.remove(); });
            container.appendChild(removeBtn);

            for (const k in data){
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'sections['+index+']['+k+']';
                input.value = data[k];
                container.appendChild(input);
            }

            sectionsList.appendChild(container);
        }

        // hook remove buttons for existing ones
        document.querySelectorAll('#sections_list .section-card .remove').forEach(btn => btn.addEventListener('click', function(){ this.closest('.section-card').remove(); }));

        form && form.addEventListener('submit', function(){
            if (tinymce.get('content')) tinymce.get('content').save();
            if (tinymce.get('modal_section_content')) tinymce.get('modal_section_content').save();
        });
    });
    </script>
<!-- TinyMCE: load only when TINYMCE_API_KEY is set in .env to avoid the "valid API key" warning -->
<?php if (!empty($_ENV['TINYMCE_API_KEY'])): ?>
    <script
        src="https://cdn.tiny.cloud/1/<?= htmlspecialchars($_ENV['TINYMCE_API_KEY'], ENT_QUOTES, 'UTF-8') ?>/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#content',
            plugins: 'link lists code image media table',
            toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright | bullist numlist | link image media table | code',
            images_upload_url: '/admin/media/upload',
            images_upload_credentials: true
        });
    </script>

<?php else: ?>
    <!-- TinyMCE not loaded. To enable WYSIWYG editor, add TINYMCE_API_KEY to your .env file:
         TINYMCE_API_KEY=your_api_key_here
    -->
<?php endif; ?>