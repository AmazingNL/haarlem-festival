/** @format */

(function () {
	function initTinyMce() {
		if (!window.tinymce) return;

		tinymce.remove("textarea.js-wysiwyg");

		tinymce.init({
			selector: "textarea.js-wysiwyg",
			height: 200,
			automatic_uploads: true,
			menubar: true,
			plugins: "lists link image code table",
			toolbar:
				"undo redo | styles | bold italic underline | bullist numlist | link image | table | code",
			branding: true,

		extended_valid_elements: `
        svg[class|xmlns|width|height|viewBox|fill|stroke|stroke-width|stroke-linecap|stroke-linejoin],
        path[d|fill|stroke|stroke-width|stroke-linecap|stroke-linejoin],
        g[class|fill|stroke|transform],
        defs,
        linearGradient[id|x1|y1|x2|y2|gradientUnits],
        stop[offset|stop-color|stop-opacity]`,

			valid_children: "+svg[path|g|defs|linearGradient|stop]",
			verify_html: false,

			// Keep these:
			images_upload_credentials: true,
			image_title: true,
			image_caption: true,
			image_advtab: true,

			images_upload_handler: async (blobInfo, progress) => {
				const formData = new FormData();
				formData.append("file", blobInfo.blob(), blobInfo.filename());

				// Optional: take alt from section alt input if you have one
				const altEl = document.getElementById("image_alt");
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
				if (!res.ok) {
					const txt = await res.text().catch(() => "");
					throw new Error("Upload failed (" + res.status + "): " + txt);
				}
				const data = await res.json();
				if (!data.location) throw new Error("Upload response missing location");

				if (data.alt_text && altEl) altEl.value = data.alt_text;
				return data.location;
			},
		});
	}

	// Allow other scripts (dynamic partial loaders) to re-init editors.
	window.initTinyMceEditors = initTinyMce;

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
	document.addEventListener("cms:content-updated", loadTinyMceScriptAndInit);
})();
