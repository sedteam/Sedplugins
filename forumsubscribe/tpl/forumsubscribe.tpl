<!-- BEGIN: MAIN -->

<main id="plugins">

	<div class="container">

		<div class="section-title">
			{FORUMSUB_BREADCRUMBS}
			<h1>{FORUMSUB_PAGETITLE}</h1>
		</div>

		<div class="section-body">

			<!-- BEGIN: FORUMSUB_PAGINATION -->
			<div class="pagination-box">
				<ul class="pagination">
					<li class="page-item">{FORUMSUB_PAGEPREV}</li>
					{FORUMSUB_PAGINATION}
					<li class="page-item">{FORUMSUB_PAGENEXT}</li>
				</ul>
			</div>
			<!-- END: FORUMSUB_PAGINATION -->

			<!-- BEGIN: SUBSCRIPTIONS -->
			<div class="table-cells forums-table forumsub-table">

				<div class="table-thead">
					<div class="table-td coltop forumsub-col-topic">{PHP.L.forumsub_topic}</div>
					<div class="table-td coltop forumsub-col-section">{PHP.L.forumsub_section}</div>
					<div class="table-td coltop text-center forumsub-col-posts">{PHP.L.Posts}</div>
					<div class="table-td coltop forumsub-col-date">{PHP.L.forumsub_date}</div>
					<div class="table-td coltop text-right forumsub-col-action">{PHP.L.forumsub_action}</div>
				</div>

				<div class="table-tbody">
					<!-- BEGIN: FORUMSUB_ROW -->
					<div class="table-tr">
						<div class="table-td {FORUMSUB_ROW_ODDEVEN} forumsub-col-topic">
							<a href="{FORUMSUB_ROW_TOPIC_URL}" class="forumsub-topic-link"><strong>{FORUMSUB_ROW_TOPIC_TITLE}</strong></a>
						</div>
						<div class="table-td {FORUMSUB_ROW_ODDEVEN} forumsub-col-section">
							<a href="{FORUMSUB_ROW_SECTION_URL}">{FORUMSUB_ROW_SECTION_TITLE}</a>
						</div>
						<div class="table-td {FORUMSUB_ROW_ODDEVEN} text-center forumsub-col-posts">
							{FORUMSUB_ROW_POSTCOUNT}
						</div>
						<div class="table-td {FORUMSUB_ROW_ODDEVEN} forumsub-col-date">
							<span class="forumsub-date">{FORUMSUB_ROW_DATE}</span>
						</div>
						<div class="table-td {FORUMSUB_ROW_ODDEVEN} text-right forumsub-col-action">
							<a href="{FORUMSUB_ROW_UNSUB_URL}" class="btn btn-adm btn-forumsub-unsub" rel="nofollow">
								<span>{PHP.L.forumsub_unsubscribe}</span>
							</a>
						</div>
					</div>
					<!-- END: FORUMSUB_ROW -->
				</div>

			</div>

			<div class="forumsub-actions" style="margin-top: 20px; display: -webkit-box; display: -ms-flexbox; display: flex; -webkit-box-pack: end; -ms-flex-pack: end; justify-content: flex-end;">
				<a href="{FORUMSUB_UNSUB_ALL_URL}" class="btn" onclick="return confirm('{PHP.L.Confirm}');" rel="nofollow">
					{FORUMSUB_UNSUB_ALL_TEXT}
				</a>
			</div>
			<!-- END: SUBSCRIPTIONS -->

			<!-- BEGIN: NO_SUBSCRIPTIONS -->
			<div class="forumsub-empty-box" style="padding: 25px; text-align: center; background: rgba(0, 0, 0, 0.03); border-radius: 6px; margin: 20px 0;">
				<p>{FORUMSUB_NO_SUBSCRIPTIONS_TEXT}</p>
			</div>
			<!-- END: NO_SUBSCRIPTIONS -->

			<!-- BEGIN: FORUMSUB_PAGINATION -->
			<div class="pagination-box" style="margin-top: 20px;">
				<ul class="pagination">
					<li class="page-item">{FORUMSUB_PAGEPREV}</li>
					{FORUMSUB_PAGINATION}
					<li class="page-item">{FORUMSUB_PAGENEXT}</li>
				</ul>
			</div>
			<!-- END: FORUMSUB_PAGINATION -->

		</div>

	</div>

</main>

<!-- END: MAIN -->
