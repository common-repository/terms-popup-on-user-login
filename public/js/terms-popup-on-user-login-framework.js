(function ($) {
	"use strict";

	// Create the TPUL namespace if it doesn't already exist

	/**
	 * -----------------------
	 * BEGIN Framework
	 * -----------------------
	 */

	function __getPopupType() {
		if (
			typeof tpulApiSettings !== "undefined" &&
			typeof tpulApiSettings.popup_type !== "undefined" &&
			tpulApiSettings.popup_type !== "" &&
			tpulApiSettings.popup_type !== "0"
		) {
			return tpulApiSettings.popup_type;
		}
		return "";
	}

	function __isTestMode() {
		// Check if tpulApiSettings is defined and has the popup_is_test property
		if (
			typeof tpulApiSettings !== "undefined" &&
			typeof tpulApiSettings.popup_is_test !== "undefined" &&
			tpulApiSettings.popup_is_test !== "" &&
			tpulApiSettings.popup_is_test !== "0"
		) {
			console.log("Test Mode is ON");
			return true;
		}
		return false;
	}

	function __isLoginPage() {
		if (
			typeof tpulApiSettings !== "undefined" &&
			typeof tpulApiSettings.popup_is_loginpage !== "undefined" &&
			tpulApiSettings.popup_is_loginpage !== "" &&
			tpulApiSettings.popup_is_loginpage !== "0"
		) {
			return true;
		}
		return false;
	}

	function __isGeoLocationTrackingEnabled() {
		if (
			typeof tpulApiSettings !== "undefined" &&
			typeof tpulApiSettings.tpul_geolocation !== "undefined" &&
			tpulApiSettings.tpul_geolocation !== "" &&
			tpulApiSettings.tpul_geolocation !== "0"
		) {
			return true;
		}
		return false;
	}

	function __tpul_determinGeoLocation() {
		let coord = {
			lat: "not Tracked",
			long: "not Tracked",
		};

		if (__isGeoLocationTrackingEnabled()) {
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(
					function (position) {
						// Success callback
						coord = {
							lat: position.coords.latitude,
							long: position.coords.longitude,
						};
						window.tpul_GeoLocationResult = JSON.stringify(coord);
					},
					function (error) {
						// Error callback
						console.error("Error getting geolocation:", error.message);
						coord = {
							lat: "Browser or OS denied",
							long: "Browser or OS denied",
						};
						window.tpul_GeoLocationResult = JSON.stringify(coord);
					}
				);
			} else {
				// Geolocation not supported
				coord = {
					lat: "browser Denied",
					long: "browser Denied",
				};
				window.tpul_GeoLocationResult = JSON.stringify(coord);
			}
		} else {
			// GeoLocationTracking is not enabled
			window.tpul_GeoLocationResult = JSON.stringify(coord);
		}
	}

	function __set_accept_session_cookkie() {
		Cookies.set("tpul_user_accepted", "true");
	}

	function __get_accept_session_cookkie() {
		let has_user_accepted = Cookies.get("tpul_user_accepted");
		if (
			typeof has_user_accepted !== "undefined" &&
			has_user_accepted == "true"
		) {
			return true;
		}
		return false;
	}

	/**
	 * Generate a unique identifier
	 *
	 */
	function __generateUniqueId() {
		return "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(
			/[xy]/g,
			function (c) {
				const r = (Math.random() * 16) | 0;
				const v = c === "x" ? r : (r & 0x3) | 0x8;
				return v.toString(16);
			}
		);
	}

	/**
	 * Get and Set current url from cookie
	 */
	function __getTpulAcceptUrl() {
		let tpul_accept_url = Cookies.get("tpul_accept_url");
		if (!tpul_accept_url) {
			tpul_accept_url = window.location.href;
			Cookies.set("tpul_accept_url", tpul_accept_url, {
				expires: 364,
			});
		}
		return tpul_accept_url;
	}

	function __setTpulAcceptUrl() {
		let tpul_accept_url = window.location.href;
		Cookies.set("tpul_accept_url", tpul_accept_url, {
			expires: 364,
		});
	}

	/**
	 * Get or generate a unique visitor id
	 */
	function __getTpulVisitorId() {
		let tpul_visitor_id = Cookies.get("tpul_visitor_id");
		if (!tpul_visitor_id) {
			tpul_visitor_id = __generateUniqueId();
			Cookies.set("tpul_visitor_id", tpul_visitor_id, {
				expires: 364,
			});
		}
		return tpul_visitor_id;
	}

	function __setLastAcceptedDateCookie() {
		let $currenTime = Math.floor(Date.now() / 1000);
		Cookies.set("tpul_loginpage_cookie_accepted", $currenTime, {
			expires: 364,
		});
		// console.log("cookie set");
		// console.log($currenTime);
	}

	function __getLastAcceptedDateCookie() {
		let has_user_accepted = Cookies.get("tpul_loginpage_cookie_accepted");
		if (typeof has_user_accepted !== "undefined") {
			return has_user_accepted;
		}
		return 0;
	}

	function __getLastResetTime() {
		if (
			typeof tpulApiSettings !== "undefined" &&
			typeof tpulApiSettings.tpul_last_reset_ran !== "undefined"
		) {
			return tpulApiSettings.tpul_last_reset_ran;
		}
		return 0;
	}

	function __tpul_getGeolocation() {
		if (window.tpul_GeoLocationResult) {
			return window.tpul_GeoLocationResult;
		} else {
			let coord = {
				lat: "missing data",
				long: "missing data",
			};
			return JSON.stringify(coord);
		}
	}

	function __TpulResetHappenedSinceLastAccept() {
		let $cookie = __getLastAcceptedDateCookie();
		let $lastResetTime = __getLastResetTime();

		if ($cookie > $lastResetTime) {
			return false;
		}
		console.log("Reset Happened Since Last Accept");
		return true;
	}

	function __noTpulResetSinceLastAccept() {
		return !__TpulResetHappenedSinceLastAccept();
	}

	/**
	 * -----------------------
	 * END Framework
	 * -----------------------
	 */

	window.TPUL = window.TPUL || {};
	// Add the public functions to the TPUL namespace

	/**
	 * -----------------------
	 * TPUL Public Functions
	 */
	window.TPUL.__getPopupType = __getPopupType;
	window.TPUL.__isTestMode = __isTestMode;
	window.TPUL.__isLoginPage = __isLoginPage;
	window.TPUL.__isGeoLocationTrackingEnabled = __isGeoLocationTrackingEnabled;
	window.TPUL.__tpul_determinGeoLocation = __tpul_determinGeoLocation;
	window.TPUL.__set_accept_session_cookkie = __set_accept_session_cookkie;
	window.TPUL.__get_accept_session_cookkie = __get_accept_session_cookkie;
	window.TPUL.__generateUniqueId = __generateUniqueId;
	window.TPUL.__getTpulVisitorId = __getTpulVisitorId;
	window.TPUL.__setLastAcceptedDateCookie = __setLastAcceptedDateCookie;
	window.TPUL.__getLastAcceptedDateCookie = __getLastAcceptedDateCookie;
	window.TPUL.__getLastResetTime = __getLastResetTime;
	window.TPUL.__tpul_getGeolocation = __tpul_getGeolocation;
	window.TPUL.__setTpulAcceptUrl = __setTpulAcceptUrl;
	window.TPUL.__getTpulAcceptUrl = __getTpulAcceptUrl;
	window.TPUL.__TpulResetHappenedSinceLastAccept =
		__TpulResetHappenedSinceLastAccept;
	window.TPUL.__noTpulResetSinceLastAccept = __noTpulResetSinceLastAccept;
})(jQuery);
