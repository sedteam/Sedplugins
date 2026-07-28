<!-- BEGIN: MAIN -->

<!-- BEGIN: LINKED_ACCOUNTS -->
<ul class="social-profile-list">
	<!-- BEGIN: ACCOUNT_ROW -->
	<li class="social-profile-item">
		<span class="social-profile-provider">
			<img src="{HYBRIDAUTH_ACC_ICON}" alt="{HYBRIDAUTH_ACC_NAME}" class="btn-social-icon"> {HYBRIDAUTH_ACC_NAME}
		</span>
		<!-- IF {HYBRIDAUTH_ACC_DISPLAY} -->
		<span style="color:#6b7280;font-size:13px;">({HYBRIDAUTH_ACC_DISPLAY})</span>
		<!-- ENDIF -->
		<span class="social-profile-actions">
			<a href="{HYBRIDAUTH_ACC_UNLINK_URL}" class="social-unlink" onclick="return confirm('{PHP.L.Areyousure}');">{PHP.L.hybridauth_unlink}</a>
		</span>
	</li>
	<!-- END: ACCOUNT_ROW -->
</ul>
<!-- END: LINKED_ACCOUNTS -->

<!-- BEGIN: NO_ACCOUNTS -->
<p style="color:#9ca3af;font-size:13px;">{PHP.L.hybridauth_no_accounts}</p>
<!-- END: NO_ACCOUNTS -->

<!-- BEGIN: ATTACH_BLOCK -->
<div class="social-profile-attach">
	<div class="social-profile-attach-title">{PHP.L.hybridauth_attach_more}:</div>
	<div class="social-login-buttons">
		<!-- BEGIN: ATTACH_ROW -->
		<a href="{HYBRIDAUTH_ATTACH_URL}" class="btn-social btn-social-{HYBRIDAUTH_ATTACH_CSS}">
			<img src="{HYBRIDAUTH_ATTACH_ICON}" alt="{HYBRIDAUTH_ATTACH_LABEL}" class="btn-social-icon"> {HYBRIDAUTH_ATTACH_LABEL}
		</a>
		<!-- END: ATTACH_ROW -->
	</div>
</div>
<!-- END: ATTACH_BLOCK -->

<!-- END: MAIN -->
