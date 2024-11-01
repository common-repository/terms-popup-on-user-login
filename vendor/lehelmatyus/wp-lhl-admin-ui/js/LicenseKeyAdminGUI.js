(function ($) {
	"use strict";

	$(function () {});
})(jQuery);

function lbrtyLicenseBoxToggle(e, element) {
	e.preventDefault();
	jQuery(".lbrty-license-box").toggleClass("open");
}

function lbrtyLicenseBoxActivateKey(e) {
	e.preventDefault();
	let id = e.target.id;
	let btn = e.target;
	let lbrtyBox = document.querySelector(".lbrty-license-box");
	let lbrtyLicenseInput = document.querySelector(
		'[name="lbrty_settings_general_options[lbrty_license_key]"]'
	);
	let lbrtyLicenseValue = lbrtyLicenseInput.value;
	let msg = document.querySelector("." + id + "__msg");

	jQuery
		.ajax({
			url:
				LbrtyBoxApiSettings.root + LbrtyBoxApiSettings.ActivateKeyApiEndpoint,
			type: "POST",
			contentType: "application/json",
			beforeSend: function (xhr) {
				xhr.setRequestHeader("X-WP-Nonce", LbrtyBoxApiSettings.lbrty_nonce);
				btn.disabled = true;
				msg.innerHTML = msg.dataset.waitmsg;
				lbrtyBox.classList.add("working");
			},
			data: JSON.stringify({
				license_key: lbrtyLicenseValue,
			}),
			success: function (response) {},
		})
		.done(function (results) {
			console.log("done");
			console.log(results);
			msg.innerHTML = results.message;
			lbrtyBox.classList.remove("working");
			if (results.code === "key_activated") {
				lbrtyBox.classList.add("lbrty-license-box--just-activated");
				lbrtyLicenseInput.readOnly = true;
			} else {
				btn.disabled = false;
			}
		})
		.fail(function (jqXHR, textStatus, errorThrown) {
			console.log("fail");
			console.log(jqXHR);
			console.log(textStatus);
			console.log(errorThrown);

			lbrtyBox.classList.remove("working");
			btn.disabled = false;
			msg.innerHTML =
				"Script Failed. Error: " +
				errorThrown +
				" " +
				jqXHR.responseJSON.message;
		});
}

function lbrtyLicenseBoxDeactivateKey(e) {
	e.preventDefault();
	let id = e.target.id;
	let btn = e.target;
	let lbrtyBox = document.querySelector(".lbrty-license-box");
	let lbrtyLicenseInput = document.querySelector(
		'[name="lbrty_settings_general_options[lbrty_license_key]"]'
	);

	let msg = document.querySelector("." + id + "__msg");

	jQuery
		.ajax({
			url:
				LbrtyBoxApiSettings.root + LbrtyBoxApiSettings.DeActivateKeyApiEndpoint,
			type: "POST",
			contentType: "application/json",
			beforeSend: function (xhr) {
				xhr.setRequestHeader("X-WP-Nonce", LbrtyBoxApiSettings.lbrty_nonce);
				btn.disabled = true;
				msg.innerHTML = msg.dataset.waitmsg;
				lbrtyBox.classList.add("working");
			},
			data: JSON.stringify({}),
			success: function (response) {},
		})
		.done(function (results) {
			console.log("done");
			console.log(results);
			msg.innerHTML = results.message;
			lbrtyBox.classList.remove("working");
			if (results.code === "key_deactivated") {
				lbrtyBox.classList.add("lbrty-license-box--just-deactivated");
				lbrtyLicenseInput.readOnly = true;
			} else {
				btn.disabled = false;
			}
		})
		.fail(function (jqXHR, textStatus, errorThrown) {
			console.log("fail");
			console.log(jqXHR);
			console.log(textStatus);
			console.log(errorThrown);

			lbrtyBox.classList.remove("working");
			btn.disabled = false;
			msg.innerHTML =
				"Script Failed. Error: " +
				errorThrown +
				" " +
				jqXHR.responseJSON.message;
		});
}

/**
 * Pass an OBJ to our Script
 */
// wp_localize_script($this->plugin_name, 'LbrtyBoxApiSettings', array(
// 	'root' => esc_url_raw(rest_url()),
// 	'lbrty_nonce' => wp_create_nonce('wp_rest'),
// 	'ActivateKeyApiEndpoint' => 'tgen-template-generator/v1/action/activatekey',
// 	'DeactivateKeyApiEndpoint' => 'tgen-template-generator/v1/action/deactivatekey',

// ));
