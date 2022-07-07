Add forums.sections.tpl
=======================

<div class="table-cells forums-table forums-main-table">

	<div class="table-thead forums-table-head">
		<div class="table-td coltop"><a href="plug/forumstats">{PHP.L.Statistics}</a></div>
		<div class="table-td coltop"><a href="plug/whosonline">{PHP.skinlang.index.Online}</a></div>
	</div>	

	<div class="table-tbody">

		<div class="table-tr">						
			<div class="table-td table-td-valign-top">
				<div>{FORUMS_BASICSTATS}</div>
			</div>
			<div class="table-td table-td-valign-top">
				<div>{PHP.out.whosonline} : {PHP.out.whosonline_reg_list}</div>
			</div>						
		</div>
		
	</div>

</div>
