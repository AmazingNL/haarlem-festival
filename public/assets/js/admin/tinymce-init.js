/** @format */

(function () {
	function initTinyMce() {
		if (!window.tinymce) return;

		// Remove any existing editors on the target selector before init
		tinymce.remove("textarea.js-wysiwyg");

		tinymce.init({
			selector: "textarea.js-wysiwyg",
			height: 350,
			menubar: true,
			plugins: "lists link image code table",
			toolbar:
				"undo redo | styles | bold italic underline | bullist numlist | link image | table | code",
			branding: true,

			// Keep these:
			images_upload_credentials: true,
			image_title: true,
			image_caption: true,
			image_advtab: true,

			// Use custom handler so we can read image_id + alt_text
			images_upload_handler: async (blobInfo, progress) => {
				const formData = new FormData();
				formData.append("file", blobInfo.blob(), blobInfo.filename());

				// Optional: take alt from section alt input if you have one
				const altEl = document.getElementById("section_image_alt");
				const altText = altEl ? (altEl.value || "").trim() : "";
				if (altText) formData.append("alt_text", altText);

				const res = await fetch("/admin/media/upload", {
					method: "POST",
					body: formData,
					headers: {
						Accept: "application/json",
					},
					credentials: "include",
				});

				// If your controller returns non-200, this will throw nicely
				if (!res.ok) {
					const txt = await res.text().catch(() => "");
					throw new Error("Upload failed (" + res.status + "): " + txt);
				}

				const data = await res.json();

				// 1) TinyMCE needs the URL
				if (!data.location) throw new Error("Upload response missing location");

				// 2) Save returned image_id into your section form
				if (data.image_id) {
					const idEl = document.getElementById("section_image_id");
					if (idEl) idEl.value = data.image_id;
				}

				// 3) If API echoes back alt_text, keep it synced (optional)
				if (data.alt_text && altEl) altEl.value = data.alt_text;

				console.log("upload response:", data);
				return data.location;
			},
		});
	}

	function loadTinyMceScriptAndInit() {
		if (window.tinymce) {
			initTinyMce();
			return;
		}

		var key = (window.TINYMCE_API_KEY || "").trim();
		var srcKey = key ? key : "no-api-key";
		var src =
			"https://cdn.tiny.cloud/1/" +
			encodeURIComponent(srcKey) +
			"/tinymce/6/tinymce.min.js";

		if (document.querySelector("script[data-tinymce-loader]")) {
			document
				.querySelector("script[data-tinymce-loader]")
				.addEventListener("load", initTinyMce);
			return;
		}

		var s = document.createElement("script");
		s.setAttribute("src", src);
		s.setAttribute("referrerpolicy", "origin");
		s.setAttribute("data-tinymce-loader", "1");
		s.addEventListener("load", function () {
			try {
				initTinyMce();
			} catch (e) {
				console.error("TinyMCE init failed", e);
			}
		});
		s.addEventListener("error", function (e) {
			console.error("Failed to load TinyMCE from", src, e);
		});
		document.head.appendChild(s);
	}

	document.addEventListener("DOMContentLoaded", loadTinyMceScriptAndInit);
})();