/**
 * File only get loaded after login
 * the pourpose of this file is to save the anon acceptance token to the user object
 */

(function ($) {
	"use strict";

	function ___getLastAcceptedDateCookie() {
		let has_user_accepted = Cookies.get("tpul_loginpage_cookie_accepted");
		if (typeof has_user_accepted !== "undefined") {
			return has_user_accepted;
		}
		return 0;
	}

	function ___isTpulVisitorIdUpdated() {
		let has_user_accepted = Cookies.get("tpul_visitor_id_updated_in_db");
		if (typeof has_user_accepted !== "undefined") {
			return has_user_accepted;
		}
		return 0;
	}

	function __setTpulVisitorIdUpdated() {
		Cookies.set("tpul_visitor_id_updated_in_db", "true", {
			expires: 365,
		});
	}

	/**
	 * Get or generate a unique visitor id
	 */
	function __removeTpulVisitorId() {
		Cookies.remove("tpul_visitor_id");
	}
	function ___hasVisitorId() {
		return Cookies.get("tpul_visitor_id") ? true : false;
	}
	function ___getTpulVisitorId() {
		let tpul_visitor_id = Cookies.get("tpul_visitor_id");
		if (!tpul_visitor_id) {
			tpul_visitor_id = window.TPUL.___generateUniqueId();
			Cookies.set("tpul_visitor_id", tpul_visitor_id, {
				expires: 364,
			});
		}
		return tpul_visitor_id;
	}

	/**
	 * Save Anonymous acceptance token onto the user
	 */
	function saveAnonAcceptanceTokenToUserObject() {
		let tpul_loginpage_cookie_accepted_date = ___getLastAcceptedDateCookie();

		let clicktype = "Button Click";

		if (!___hasVisitorId()) {
			return;
		}
		if (___isTpulVisitorIdUpdated()) {
			return;
		}
		let tpul_visitor_id = ___getTpulVisitorId();

		tpul_visitor_id = tpul_visitor_id ? tpul_visitor_id : "0";
		console.log("tpul_visitor_id", tpul_visitor_id);

		$.ajax({
			url:
				tpulApiSettings.root +
				"terms-popup-on-user-login/v1/action/save-anon-acceptance-token",
			type: "POST",
			contentType: "application/json",
			beforeSend: function (xhr) {
				xhr.setRequestHeader("X-WP-Nonce", tpulApiSettings.tpul_nonce);
			},
			data: JSON.stringify({
				tpul_loginpage_cookie_accepted_date:
					tpul_loginpage_cookie_accepted_date,
				useragent: navigator.userAgent,
				locationCoordinates: window.TPUL.__tpul_getGeolocation(),
				tpul_visitor_id: tpul_visitor_id,
				currentURL: window.TPUL.__getTpulAcceptUrl(),
				user_action_method: clicktype,
				user_device_info: JSON.stringify({
					cookieEnabled: navigator.cookieEnabled,
					viewport: {
						width: window.innerWidth,
						height: window.innerHeight,
					},
					screen: {
						colorDepth: window.screen.colorDepth,
						pixelDepth: window.screen.pixelDepth,
					},
				}),
				user_language_preference: navigator.language,
				user_action_log: '["' + clicktype + '"]',
			}),
			success: function (response) {
				//Cookies.remove("tpul_user_accepted");
			},
		})
			.done(function (results) {
				console.log("SUCCESS");
				console.log(results);
				// __removeTpulVisitorId();
				__setTpulVisitorIdUpdated();
			})
			.fail(function (jqXHR, textStatus, errorThrown) {
				console.log("ERROR");
				console.log(jqXHR);
				console.log(textStatus);
				console.log(errorThrown);
			});
		// }
	}

	$(function () {
		saveAnonAcceptanceTokenToUserObject();
	});
})(jQuery);
