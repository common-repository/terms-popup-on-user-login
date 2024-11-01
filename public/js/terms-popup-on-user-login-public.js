(function ($) {
	"use strict";

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	/**
	 * Save a visitor ID to a cookie
	 */
	if (window.TPUL) {
	} else {
		console.error("TPUL namespace is not available.");
	}

	$(function () {
		window.TPUL.__getTpulVisitorId();
	});

	/**
	 * Helper Functions
	 */

	function __disable_esc() {
		$(document).keydown(function (event) {
			if (event.keyCode === 27) {
				event.stopImmediatePropagation();
			}
		});
	}

	function __update_body_is_show_modal() {
		$("body").addClass("is_show_terms_popup");
	}
	function __update_body_is_closed_modal() {
		$("body").removeClass("is_show_terms_popup");
	}

	function closeCompare(a, b, margin) {
		if (Math.abs(a - b) < margin) {
			return 1;
		}
		return 0;
	}

	function show_accept_transient_message() {
		$(".modal__subtitle_wrapper").addClass("hide");
		$(".modal__terms_wrapper").addClass("hide");
		$(".modal__btn").addClass("hide");

		$(".modal__accepting_wrapper").removeClass("hide");
		$(".modal__loader_wrapper").removeClass("hide");
	}

	function show_cancel_transient_message() {
		$(".modal__subtitle_wrapper").addClass("hide");
		$(".modal__terms_wrapper").addClass("hide");
		$(".modal__btn").addClass("hide");

		$(".modal__logginout_wrapper").removeClass("hide");
		$(".modal__loader_wrapper").removeClass("hide");
	}

	/***********************************************************************************
	 * Ajax Calls
	 ***********************************************************************************/

	/**
	 * Handle Logout
	 */
	function logoutUser(e) {
		$.ajax({
			url:
				tpulApiSettings.root +
				"terms-popup-on-user-login/v1/action/logout-user",
			type: "POST",
			contentType: "application/json",
			beforeSend: function (xhr) {
				xhr.setRequestHeader("X-WP-Nonce", tpulApiSettings.tpul_nonce);

				show_cancel_transient_message();
			},
			data: JSON.stringify({
				oo: "var",
			}),
			success: function (response) {},
		})
			.done(function (results) {
				declineCloseAndRedirect();
			})
			.fail(function (jqXHR, textStatus, errorThrown) {
				console.log("ERROR");
				console.log(jqXHR);
				console.log(textStatus);
				console.log(errorThrown);
				document.location.href = "/";
			});
	}

	/**
	 * Handle Accepted Terms
	 */

	function acceptTermsUser(clicktype = "Button Click") {
		let tpul_visitor_id = window.TPUL.__getTpulVisitorId();
		/**
		 * Set the URL where the user accepted the terms
		 * this is read out as proof and stored in the db as soon as they are logged in
		 */
		window.TPUL.__setTpulAcceptUrl();
		window.TPUL.__setLastAcceptedDateCookie();

		$.ajax({
			url:
				tpulApiSettings.root +
				"terms-popup-on-user-login/v1/action/accept-terms",
			type: "POST",
			dataType: "json",
			contentType: "application/json",
			beforeSend: function (xhr) {
				xhr.setRequestHeader("X-WP-Nonce", tpulApiSettings.tpul_nonce);

				show_accept_transient_message();
			},
			data: JSON.stringify({
				oo: "var",
				useragent: navigator.userAgent,
				locationCoordinates: window.TPUL.__tpul_getGeolocation(),
				tpul_visitor_id: tpul_visitor_id,
				currentURL: window.location.href,
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
			success: function (response) {},
		})
			.done(function (results) {
				if (results) {
					console.log(results);

					__update_body_is_closed_modal();

					let data_is_logged_in = $(this).attr("data-isloggedin");
					if (results.data.redirect && data_is_logged_in == "true") {
						window.location.replace(results.data.redirect);
					} else {
						if (results.data.accepted) {
							MicroModal.close();
						}
					}
				} else {
					// No redirect
					MicroModal.close();
				}
			})
			.fail(function (jqXHR, textStatus, errorThrown) {
				console.log("ERROR");
				console.log(jqXHR);
				console.log(textStatus);
				console.log(errorThrown);
				// document.location.href = "/";
			});
	}

	/*************************************************************************************
	 * Actions
	 ************************************************************************************/

	function declineCloseAndRedirect() {
		var redirectUrl = $("#tpul-modal-btn-decline-wait").attr(
			"data-redirectUrl"
		);
		console.log(redirectUrl);
		if (redirectUrl) {
			console.log("redirecting");
			window.location.replace(redirectUrl);
		} else {
			MicroModal.close();
		}
	}

	/**
	 * Test mode on Close
	 */
	function closeInTestmode(e) {
		show_cancel_transient_message();

		setTimeout(function () {
			MicroModal.close();
			alert(
				"You would now be logged out. -- TEST MODE is still ON. Remember to turn it OFF at Settings > Terms Popup on User Login."
			);
		}, 3000);
		__update_body_is_closed_modal();
	}

	/**
	 * Test mode on Accept
	 */
	function acceptInTestmode() {
		show_accept_transient_message();

		setTimeout(function () {
			MicroModal.close();
			alert(
				"TEST MODE is still ON. Remember to Turn Test mode OFF at Settings > Terms Popup on User Login."
			);
		}, 3000);
		__update_body_is_closed_modal();
	}

	/**
	 * Login Page ACTION
	 */
	function loginPageCancelAction() {
		declineCloseAndRedirect();
	}

	/**
	 * Anon user has accepted the terms on Login Page
	 */
	function loginPageAcceptAction() {
		window.TPUL.__setLastAcceptedDateCookie();
		window.TPUL.__setTpulAcceptUrl();
		let tpul_visitor_id = window.TPUL.__getTpulVisitorId(); // saves it to cookies as well
		show_accept_transient_message();
		setTimeout(function () {
			MicroModal.close();
		}, 1000);
		__update_body_is_closed_modal();
	}

	/*************************************************************************************
	 * Setup and Call Modal on Page Load
	 ************************************************************************************/

	$(function () {
		// https://micromodal.now.sh/

		/**
		 * Check if user is logged in and has already accepted the terms
		 * before he logged in and we have an acceptance token for him
		 */

		// saveAnonAcceptanceTokenToUserObject();

		/**
		 * Show Accept Terms Modal
		 */
		let popup_type = window.TPUL.__getPopupType();
		console.log("Popup Type: " + popup_type);
		if (
			"terms_and_conditions_modal" == popup_type ||
			"terms_and_conditions_modal_include_admin" == popup_type ||
			"terms_and_conditions_modal_anypageeveryone" == popup_type ||
			"terms_and_conditions_modal_woo" == popup_type ||
			window.TPUL.__isTestMode() ||
			(window.TPUL.__isLoginPage() &&
				window.TPUL.__TpulResetHappenedSinceLastAccept())
		) {
			window.TPUL.__tpul_determinGeoLocation();

			let data_check_cookie = $("#modal-accept-terms").attr("data-checkcookie");

			let show_modal = true;

			// should check cookie for this session
			if (
				data_check_cookie &&
				data_check_cookie.length &&
				data_check_cookie == "true"
			) {
				// cookie already present
				// anon user has already accepted this modal
				// modal was set to be remembered in cookie and no reset happened since
				if (
					window.TPUL.__get_accept_session_cookkie() == true &&
					window.TPUL.__noTpulResetSinceLastAccept()
				) {
					show_modal = false;
				}
			}

			if (show_modal) {
				// dissable Esc key
				__disable_esc();

				MicroModal.init({
					onShow: (modal) => console.info(`${modal.id} is shown`), // [1]
					onClose: (modal) => console.info(`${modal.id} is hidden`), // [2]
					openTrigger: "data-custom-open", // [3]
					closeTrigger: "data-custom-close", // [4]
					openClass: "is-open", // [5]
					disableScroll: true, // [6]
					disableFocus: true, // [7]
					awaitOpenAnimation: false, // [8]
					awaitCloseAnimation: false, // [9]
					debugMode: false, // [10]
				});

				/**
				 * Show Modal
				 */

				MicroModal.show("modal-accept-terms");
				console.log("Modal is shown");
				__update_body_is_show_modal();
			}
		}

		/***********************************************************************************
		 * Accept and Decline button events for Terms Popup on User logn
		 ***********************************************************************************/

		/**
		 * Decline button was clicked
		 */
		$(".modal__close_login").click(function () {
			if (window.TPUL.__isTestMode()) {
				closeInTestmode();
			} else {
				logoutUser();
			}
			__update_body_is_closed_modal();
		});

		/**
		 * Accept button was clicked
		 */
		$(".modal_accept_login").click(function () {
			if (!$(this).hasClass("disabled")) {
				if (window.TPUL.__isTestMode()) {
					acceptInTestmode();
				} else {
					acceptTermsUser();
				}
				__update_body_is_closed_modal();
			}
		});

		/***********************************************************************************
		 * WooCommerce
		 * Accept and Close button functionality
		 ***********************************************************************************/

		/**
		 * Decline button was clicked
		 */
		$(".modal__close_woo").click(function () {
			if (window.TPUL.__isTestMode()) {
				closeInTestmode();
			} else {
				let data_is_logged_in = $(this).attr("data-isloggedin");
				let data_should_logout = $(this).attr("data-logout");
				let data_decline_url = $(this).attr("data-declineredirect");

				if (
					data_is_logged_in == "true" &&
					data_should_logout.length &&
					data_should_logout == "logout"
				) {
					logoutUser();
					show_cancel_transient_message();
				} else {
					setTimeout(function () {
						show_cancel_transient_message();
						if (data_decline_url.length) {
							window.location.replace(data_decline_url);
						} else {
							window.location.replace("/");
						}
					}, 1000);
				}
			}
			__update_body_is_closed_modal();
		});

		/**
		 * Accept button was clicked in Woo Mode
		 */
		$(".modal_accept_woo").click(function () {
			if (!$(this).hasClass("disabled")) {
				if (window.TPUL.__isTestMode()) {
					acceptInTestmode();
				} else {
					let data_is_logged_in = $(this).attr("data-isloggedin");
					let data_save_cookie = $(this).attr("data-savecookie");

					if (data_save_cookie == "true") {
						window.TPUL.__set_accept_session_cookkie();
					}
					// send even anonymous users request to the backend
					acceptTermsUser();
				}
			}
			__update_body_is_closed_modal();
		});

		/***********************************************************************************
		 * Any Page Everyone MODE
		 * Accept and Close button functionality
		 ***********************************************************************************/
		/**
		 * Decline button was clicked
		 */
		$(".modal_close_anypageeveryone").click(function () {
			if (window.TPUL.__isTestMode()) {
				closeInTestmode();
			} else {
				let data_is_logged_in = $(this).attr("data-isloggedin");
				let data_should_logout = $(this).attr("data-logout");
				let data_decline_url = $(this).attr("data-declineredirect");

				if (
					data_is_logged_in == "true" &&
					data_should_logout.length &&
					data_should_logout == "logout"
				) {
					logoutUser();
					show_cancel_transient_message();
				} else {
					setTimeout(function () {
						show_cancel_transient_message();
						if (data_decline_url.length) {
							window.location.replace(data_decline_url);
						} else {
							window.location.replace("/");
						}
					}, 1000);
				}
			}
			__update_body_is_closed_modal();
		});

		/**
		 * Accept in any page everyone mode
		 */
		$(".modal_accept_anypageeveryone").click(function () {
			if (!$(this).hasClass("disabled")) {
				if (window.TPUL.__isTestMode()) {
					acceptInTestmode();
				} else {
					window.TPUL.__setTpulAcceptUrl();
					window.TPUL.__setLastAcceptedDateCookie();

					let data_is_logged_in = $(this).attr("data-isloggedin");
					let data_save_cookie = $(this).attr("data-savecookie");

					if (data_save_cookie == "true") {
						window.TPUL.__set_accept_session_cookkie();
					}
					// send even anonymous users request to the backend
					if (data_is_logged_in == "true") {
						acceptTermsUser();
					} else {
						MicroModal.close();
					}
				}
			}
			__update_body_is_closed_modal();
		});

		/***********************************************************************************
		 * LOGIN PAGE
		 * Accept and Close button functionality
		 ***********************************************************************************/

		/**
		 * Decline button was clicked
		 */
		$(".modal__close_loginpage").click(function () {
			loginPageCancelAction();
			__update_body_is_closed_modal();
		});

		/**
		 * Accept button was clicked
		 */
		$(".modal_accept_loginpage").click(function () {
			loginPageAcceptAction();
			__update_body_is_closed_modal();
		});

		/***********************************************************************************
		 * Extra Functionality
		 ***********************************************************************************/
		/*
		 * Disable Accept button until User Scrolls down if content surpasses container and scroll is available
		 */

		if ($(".modal__terms__inner").height() > 577) {
			$(".modal__btn-primary.disabled-by-default").attr("disabled");
			$(".modal__btn-primary.disabled-by-default").addClass("disabled");

			$(".modal__terms_wrapper").scroll(function () {
				if (window.devicePixelRatio) {
					var browserZoomLevel = Math.round(window.devicePixelRatio * 100);
					var compare = closeCompare(scrollToptoBottom, scrollPosition, 25);
				}

				var scrollToptoBottom = $(this).scrollTop();
				var scrollPosition = $(this)[0].scrollHeight - $(this).height();

				if (closeCompare(scrollToptoBottom, scrollPosition, 25)) {
					$(".modal__btn-primary.disabled-by-default").removeAttr("disabled");
					$(".modal__btn-primary.disabled-by-default").removeClass("disabled");
				}
			});
		}
	});
})(jQuery);
