<!-- BEGIN: MAIN -->

<main id="forums">
	
	<div class="container">
	
		<div class="section-title">
		
			{FTSEARCH_BREADCRUMBS}

			<h1>{FTSEARCH_TITLE}</h1>
			
			<div class="section-desc">
				
			</div>
			
		</div>

		<div class="section-body">
			
			<div class="inline-menu">
				<ul>          
					<li><a href="plug/ftsearch">{PHP.L.ftsearch}</a></li>
					<li><a href="plug/ftsearch?a=getdaily">{PHP.L.getdaily}</a></li>
					<li><a href="plug/ftsearch?a=unanswered">{PHP.L.unanswered}</a></li>                   
				</ul>
			</div>
			
          	<!-- BEGIN: NO_TOPICS_FOUND -->
          	<div class="error" style="margin:15px 0;">{NO_TOPICS_FOUND_BODY}</div>
          	<!-- END: NO_TOPICS_FOUND -->			
          
			<!-- BEGIN: FTSEARCH -->
		  
			<div class="table-cells forums-table forums-main-table">

				<div class="table-thead">
					<div class="table-td coltop" style="width:50px;"></div>
					<div class="table-td coltop">{FTSEARCH_TITLE_TOPICS}</div>
					<div class="table-td coltop" style="width:160px;">{FTSEARCH_TITLE_STARTED}</div>
					<div class="table-td coltop" style="width:250px;">{FTSEARCH_TITLE_LASTPOST}</div>
					<div class="table-td coltop" style="width:50px;">{FTSEARCH_TITLE_POSTS}</div>
					<div class="table-td coltop" style="width:50px;">{FTSEARCH_TITLE_VIEWS}</div>
				</div>
				
				<div class="table-tbody">		  
          
					<!-- BEGIN: TOPICS_ROW -->
	
					<div class="table-tr {FTSEARCH_ROW_ODDEVEN}">
              
						<div class="table-td centerall forum-topic-icon {FTSEARCH_ROW_ODDEVEN}">
							{FTSEARCH_ROW_ICON}
						</div>
				
						<div class="table-td forum-topic-title {FTSEARCH_ROW_ODDEVEN}">
							<strong><a href="{FTSEARCH_ROW_URL}">{FTSEARCH_ROW_TITLE}</a></strong><br />
							<span class="desc">{FTSEARCH_ROW_DESC} &nbsp; {FTSEARCH_ROW_PAGES}</span>
						</div>
				
						<div class="table-td centerall forums-firstposter {FTSEARCH_ROW_ODDEVEN}">
							{FTSEARCH_ROW_CREATIONDATE}<br />{FTSEARCH_ROW_FIRSTPOSTER}
						</div>
				
						<div class="table-td centerall forums-lastposter {FTSEARCH_ROW_ODDEVEN}">
							{FTSEARCH_ROW_UPDATED} {FTSEARCH_ROW_LASTPOSTER}<br />
							{FTSEARCH_ROW_TIMEAGO}
						</div>
				
						<div class="table-td centerall forums-postcount {FTSEARCH_ROW_ODDEVEN}">
							{FTSEARCH_ROW_POSTCOUNT}
						</div>
				
						<div class="table-td centerall forums-viewcount {FTSEARCH_ROW_ODDEVEN}">
							{FTSEARCH_ROW_VIEWCOUNT}
						</div>
            
					</div>			
			
          
					<!-- END: TOPICS_ROW -->
          
				</div>
				
			</div>
			
			<div class="pagination-box">

				<ul class="pagination">
					<li class="page-item">{FTSEARCH_PAGEPREV}</li>
					{FTSEARCH_PAGES}
					<li class="page-item">{FTSEARCH_PAGENEXT}</li>
				</ul>

			</div>
			
			<div class="table">

				<div class="table-tr">
					<div class="table-td"><img src="skins/{PHP.skin}/img/system/posts.gif" alt="" /> : {PHP.skinlang.forumstopics.Nonewposts}</div>
					<div class="table-td"><img src="skins/{PHP.skin}/img/system/posts_new.gif" alt="" /> :{PHP.skinlang.forumstopics.Newposts}</div>
					<div class="table-td"><img src="skins/{PHP.skin}/img/system/posts_sticky.gif" alt="" /> : {PHP.skinlang.forumstopics.Sticky}</div>
				</div>
				
				<div class="table-tr">
					<div class="table-td"><img src="skins/{PHP.skin}/img/system/posts_hot.gif" alt="" /> : {PHP.skinlang.forumstopics.Nonewpostspopular}</div>
					<div class="table-td"><img src="skins/{PHP.skin}/img/system/posts_new_hot.gif" alt="" /> :{PHP.skinlang.forumstopics.Newpostspopular}</div>
					<div class="table-td"><img src="skins/{PHP.skin}/img/system/posts_new_sticky.gif" alt="" /> : {PHP.skinlang.forumstopics.Newpostssticky}</div>
				</div>
				
				<div class="table-tr">
					<div class="table-td"><img src="skins/{PHP.skin}/img/system/posts_locked.gif" alt="" /> : {PHP.skinlang.forumstopics.Locked}</div>
					<div class="table-td"><img src="skins/{PHP.skin}/img/system/posts_new_locked.gif" alt="" /> : {PHP.skinlang.forumstopics.Newpostslocked}</div>
					<div class="table-td"><img src="skins/{PHP.skin}/img/system/posts_sticky_locked.gif" alt="" /> : {PHP.skinlang.forumstopics.Announcment}</div>
				</div>
				
				<div class="table-tr">
					<div class="table-td"><img src="skins/{PHP.skin}/img/system/posts_moved.gif" alt="" /> : {PHP.skinlang.forumstopics.Movedoutofthissection}</div>
					<div class="table-td"><img src="skins/{PHP.skin}/img/system/posts_new_sticky_locked.gif" alt="" /> : {PHP.skinlang.forumstopics.Newannouncment}</div>
					<div class="table-td"></div>
				</div>

			</div>			
          
			<!-- END: FTSEARCH -->
          
        </div>

	</div>

</main>

<!-- END: MAIN -->
