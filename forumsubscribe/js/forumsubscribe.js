/**
 * Forum Subscribe Plugin JavaScript
 * Seditio CMS
 * Uses core sedjs AJAX API and ajx=forumsubscribe endpoint
 */

if (typeof sedjs !== 'undefined') {
	sedjs.ready(function() {
		var subButtons = document.querySelectorAll('a[data-forumsub-topic]');
		
		subButtons.forEach(function(btn) {
			btn.addEventListener('click', function(e) {
				e.preventDefault();
				
				if (btn.classList.contains('loading')) {
					return;
				}
				
				var topicId = btn.getAttribute('data-forumsub-topic');
				var action = btn.getAttribute('data-forumsub-action') || (btn.classList.contains('btn-forumsub-unsub') ? 'unsubscribe' : 'subscribe');
				var baseHref = btn.getAttribute('href');
				var ajaxUrl = btn.getAttribute('data-forumsub-ajax-url');
				
				if (baseHref) {
					baseHref = baseHref.replace(/&amp;/g, '&');
				}
				
				if (ajaxUrl) {
					ajaxUrl = ajaxUrl.replace(/&amp;/g, '&');
				} else {
					ajaxUrl = 'plug.php?ajx=forumsubscribe&a=' + encodeURIComponent(action) + '&q=' + encodeURIComponent(topicId);
				}
				
				sedjs.ajax({
					url: ajaxUrl,
					method: 'GET',
					dataType: 'json',
					beforeSend: function() {
						sedjs.addClass(btn, 'loading');
					},
					success: function(res) {
						if (res && res.status === 'success') {
							var span = btn.querySelector('span');
							if (span) {
								span.textContent = res.text;
							} else {
								btn.textContent = res.text;
							}
							
							if (res.action) {
								btn.setAttribute('data-forumsub-action', res.action);
							}
							if (res.url) {
								btn.setAttribute('href', res.url.replace(/&amp;/g, '&'));
							}
							if (res.ajax_url) {
								btn.setAttribute('data-forumsub-ajax-url', res.ajax_url.replace(/&amp;/g, '&'));
							}
							
							if (res.subscribed === 1) {
								sedjs.addClass(btn, 'btn-forumsub-unsub');
							} else {
								sedjs.removeClass(btn, 'btn-forumsub-unsub');
							}
						} else {
							if (baseHref) {
								window.location.href = baseHref;
							}
						}
					},
					error: function() {
						if (baseHref) {
							window.location.href = baseHref;
						}
					},
					complete: function() {
						sedjs.removeClass(btn, 'loading');
					}
				});
			});
		});
	});
}
