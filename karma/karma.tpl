<!-- BEGIN: MAIN -->

<div id="karma">

	<div class="section-title">
		<h2>{KARMA_TITLE}</h2>
	</div>

	<!-- BEGIN: ERROR -->
		{KARMA_ERROR}
	<!-- END: ERROR -->
	
	<!-- BEGIN: SUCCESS -->
		{KARMA_SUCCESS}
	<!-- END: SUCCESS -->	

	<!-- BEGIN: CHANGE -->

		<div class="section-subtitle">{KARMA_CHANGE_SUBTITLE}</div>
		<div class="block2">{KARMA_CHANGE_FP_TEXT}</div>

		<div class="block2">
			<form action="{KARMA_CHANGE_FORM_SEND}" method="POST">			
				<ul class="form responsive-form">
					<li class="form-row">
						<div class="form-field-100">
							{PHP.L.karma_comm}:<br />
							{KARMA_CHANGE_REASON_MESSAGE}
						</div>
					</li>
					<li class="form-row">
						<div class="form-field-100">
							<button type="submit" class="btn submit" name="submit">{PHP.L.Submit}</button>
						</div>
					</li>
				</ul>
			</form>
		</div>

	<!-- END: CHANGE -->

	<!-- BEGIN: SHOW -->
	
		<h3>{SHOW_USERNAME}:</h3>
		<div class="table cells striped resp-table">
			<div class="table-head resp-table-head">
				<div class="table-row resp-table-row">
					<div class="table-th coltop text-left">{PHP.L.Name}</div>
					<div class="table-th coltop text-left">{PHP.L.Value}</div>
					<div class="table-th coltop text-left">{PHP.L.Comment}</div>
					<div class="table-th coltop text-left">{PHP.L.Reason}</div>
					<div class="table-th coltop text-left"></div>
				</div>		
			</div>
			<div class="table-body resp-table-body">
			
				<!-- BEGIN: SHOW_ROW -->
				
				<div class="table-row resp-table-row" style="background-color:#{KARMA_COLOR};">
				
					<div class="table-td text-left resp-table-td">
						{KARMA_NAME}
					</div>
					<div class="table-td text-left resp-table-td">
						{KARMA_VALUE}
					</div>
					<div class="table-td text-left resp-table-td">
						{KARMA_TEXT}
					</div>	
					<div class="table-td text-left resp-table-td">
						{KARMA_VIEW}
					</div>	
					<div class="table-td text-left resp-table-td">
						{KARMA_ACTION}
					</div>						

				</div>
				
				<!-- END: SHOW_ROW -->
							
			</div>
		</div>

	<!-- END: SHOW -->
   
</div>

<!-- END: MAIN -->