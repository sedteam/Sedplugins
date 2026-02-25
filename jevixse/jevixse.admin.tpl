<!-- BEGIN: ADMIN_JEVIX -->

<ul class="shortcut-buttons-set">
	<li>
		<a class="shortcut-button {JEVIX_NAV_TAGS_ACTIVE}" href="{JEVIX_URL_TAGS}"><span>
			<i class="ic-page ic-3x"></i><br />
			{PHP.L.jevix_tags}
		</span></a>
	</li>
	<li>
		<a class="shortcut-button {JEVIX_NAV_BEHAVIOR_ACTIVE}" href="{JEVIX_URL_BEHAVIOR}"><span>
			<i class="ic-page ic-3x"></i><br />
			{PHP.L.jevix_behavior}
		</span></a>
	</li>

</ul>

<div class="clear"></div>	

<div class="content-box">

	<div class="content-box-header">
		<h3>{PHP.L.jevix_admin_title}</h3>
		<div class="content-box-header-right">
			{PHP.L.jevix_mode_set} {JEVIX_MODE_SELECT}
		</div>
	</div>

	<div class="content-box-content">

		<!-- BEGIN: JEVIX_CONTENT -->
		
		<!-- BEGIN: JEVIX_MAIN_TAGS -->
		
		<!-- BEGIN: JEVIX_TAG_LIST -->

		<form action="{JEVIX_FORM_ACTION_SAVE}" method="post" class="sed_form">
			<div class="marginbottom10">
				<input type="text" name="newtag" id="newtag" size="20" maxlength="32" placeholder="{PHP.L.jevix_add_tag}" class="input" />
				<button type="submit" name="a" value="savetags" class="btn">{PHP.L.Add}</button>
			</div>

			<div class="table cells striped resp-table">
				<div class="table-head resp-table-head">
					<div class="table-row resp-table-row">
						<div class="table-th coltop text-center" style="width:40px;">{PHP.L.jevix_active}</div>
						<div class="table-th coltop">{PHP.L.jevix_tag_name}</div>
						<div class="table-th coltop">{PHP.L.jevix_attributes}</div>
						<div class="table-th coltop text-center" style="width:140px;">{PHP.L.Action}</div>
					</div>
				</div>
				<div class="table-body resp-table-body">
					<!-- BEGIN: JEVIX_TAG_ROW -->
					<div class="table-row resp-table-row">
						<div class="table-td text-center resp-table-td" data-label="{PHP.L.jevix_active}">
							<input type="hidden" name="tags_list[]" value="{JEVIX_TAG_ROW_NAME}" />
							<input type="checkbox" name="tags_active[]" value="{JEVIX_TAG_ROW_NAME}" {JEVIX_TAG_ROW_CHECKED} />
						</div>
						<div class="table-td resp-table-td" data-label="{PHP.L.jevix_tag_name}">
							&lt;{JEVIX_TAG_ROW_NAME}&gt;
						</div>
						<div class="table-td resp-table-td" data-label="{PHP.L.jevix_attributes}">
							{JEVIX_TAG_ROW_ATTRS}
						</div>
						<div class="table-td text-center resp-table-td" data-label="{PHP.L.Action}">
							<a href="{JEVIX_TAG_ROW_ATTR_URL}" class="btn btn-small" title="{PHP.L.jevix_edit_attrs}">Atr</a>
							<a href="{JEVIX_TAG_ROW_OPT_URL}" class="btn btn-small" title="{PHP.L.jevix_tag_options}">Opt</a>
							<a href="{JEVIX_TAG_ROW_DEL_URL}" class="btn btn-small" title="{PHP.L.Delete}" onclick="return sedjs.confirmact('{PHP.L.jevix_confirm_del_tag}');">Del</a>
						</div>
					</div>
					<!-- END: JEVIX_TAG_ROW -->
				</div>
			</div>

			<div class="table-btn text-center">
				<button type="submit" name="a" value="savetags" class="btn">{PHP.L.Update}</button>
			</div>
		</form>

		<!-- END: JEVIX_TAG_LIST -->
		
		<!-- END: JEVIX_MAIN_TAGS -->

		<!-- BEGIN: JEVIX_ATTRS -->

		<p><a href="{JEVIX_ATTRS_BACK_URL}" class="btn btn-small">&larr; {PHP.L.jevix_back_to_tags}</a></p>

		<div class="content-box-header">
			<h3>{PHP.L.jevix_attrs_for_tag}: &lt;{JEVIX_ATTRS_TAG}&gt;</h3>
		</div>

		<form action="{JEVIX_ATTRS_FORM_ACTION}" method="post" class="sed_form">
			<div class="table cells striped resp-table">
				<div class="table-head resp-table-head">
					<div class="table-row resp-table-row">
						<div class="table-th coltop">{PHP.L.jevix_attr_name}</div>
						<div class="table-th coltop" style="width:180px;">{PHP.L.jevix_attr_type}</div>
						<div class="table-th coltop">{PHP.L.jevix_attr_values}</div>
						<div class="table-th coltop text-center" style="width:90px;">{PHP.L.jevix_attr_required}</div>
						<div class="table-th coltop text-center" style="width:60px;">{PHP.L.Action}</div>
					</div>
				</div>
				<div class="table-body resp-table-body">
					<!-- BEGIN: JEVIX_ATTR_ROW -->
					<div class="table-row resp-table-row">
						<div class="table-td resp-table-td" data-label="{PHP.L.jevix_attr_name}">
							<input type="text" name="attr_name[]" value="{JEVIX_ATTR_ROW_NAME}" size="20" maxlength="32" class="input" />
						</div>
						<div class="table-td resp-table-td" data-label="{PHP.L.jevix_attr_type}">
							<select name="attr_type[]" class="input">
								{JEVIX_ATTR_ROW_TYPE_OPTIONS}
							</select>
						</div>
						<div class="table-td resp-table-td" data-label="{PHP.L.jevix_attr_values}">
							<input type="text" name="attr_values[]" value="{JEVIX_ATTR_ROW_VALUES}" size="40" class="input" />
						</div>
						<div class="table-td text-center resp-table-td" data-label="{PHP.L.jevix_attr_required}">
							<input type="checkbox" name="attr_required[]" value="{JEVIX_ATTR_ROW_NAME}" {JEVIX_ATTR_ROW_REQUIRED} />
						</div>
						<div class="table-td text-center resp-table-td" data-label="{PHP.L.Action}">
							<a href="{JEVIX_ATTR_ROW_DEL_URL}" class="btn btn-small" title="{PHP.L.Delete}" onclick="return sedjs.confirmact('{PHP.L.jevix_confirm_del_attr}');">Del</a>
						</div>
					</div>
					<!-- END: JEVIX_ATTR_ROW -->
					<div class="table-row resp-table-row jevix-add-attr-row">
						<div class="table-td resp-table-td">
							<input type="text" name="attr_name[]" value="" size="20" maxlength="32" placeholder="{PHP.L.jevix_attr_name}" class="input" />
						</div>
						<div class="table-td resp-table-td">
							<select name="attr_type[]" class="input">
								<option value="">{PHP.L.jevix_type_none}</option>
								<option value="#text">#text</option>
								<option value="#int">#int</option>
								<option value="#link">#link</option>
								<option value="#image">#image</option>
								<option value="list">{PHP.L.jevix_type_list}</option>
							</select>
						</div>
						<div class="table-td resp-table-td">
							<input type="text" name="attr_values[]" value="" size="40" placeholder="{PHP.L.jevix_attr_values_hint}" class="input" />
						</div>
						<div class="table-td resp-table-td"></div>
						<div class="table-td resp-table-td"></div>
					</div>
				</div>
			</div>
			<div class="table-btn text-center">
				<button type="submit" class="btn">{PHP.L.Update}</button>
			</div>
		</form>

		<!-- END: JEVIX_ATTRS -->

		<!-- BEGIN: JEVIX_OPTS -->

		<p><a href="{JEVIX_OPTS_BACK_URL}" class="btn btn-small">&larr; {PHP.L.jevix_back_to_tags}</a></p>

		<div class="content-box-header">
			<h3>{PHP.L.jevix_tag_options}: &lt;{JEVIX_OPTS_TAG}&gt;</h3>
		</div>

		<form action="{JEVIX_OPTS_FORM_ACTION}" method="post" class="sed_form">
			<div class="table cells striped resp-table">
				<div class="table-body resp-table-body">
					<div class="table-row resp-table-row">
						<div class="table-td resp-table-td">
							<label><input type="checkbox" name="opt_short" value="1" {JEVIX_OPTS_SHORT} /> {PHP.L.jevix_opt_short}</label>
						</div>
					</div>
					<div class="table-row resp-table-row">
						<div class="table-td resp-table-td">
							<label><input type="checkbox" name="opt_preformatted" value="1" {JEVIX_OPTS_PREFORMATTED} /> {PHP.L.jevix_opt_preformatted}</label>
						</div>
					</div>
					<div class="table-row resp-table-row">
						<div class="table-td resp-table-td">
							<label><input type="checkbox" name="opt_cut_with_content" value="1" {JEVIX_OPTS_CUT} /> {PHP.L.jevix_opt_cut_with_content}</label>
						</div>
					</div>
					<div class="table-row resp-table-row">
						<div class="table-td resp-table-td">
							<label><input type="checkbox" name="opt_empty" value="1" {JEVIX_OPTS_EMPTY} /> {PHP.L.jevix_opt_empty}</label>
						</div>
					</div>
					<div class="table-row resp-table-row">
						<div class="table-td resp-table-td">
							<label><input type="checkbox" name="opt_no_typography" value="1" {JEVIX_OPTS_NO_TYPOGRAPHY} /> {PHP.L.jevix_opt_no_typography}</label>
						</div>
					</div>
				</div>
			</div>
			<div class="table-btn text-center">
				<button type="submit" name="a" value="saveopts" class="btn">{PHP.L.Update}</button>
			</div>
		</form>

		<!-- END: JEVIX_OPTS -->

		<!-- BEGIN: JEVIX_MAIN_BEHAVIOR -->

		<form action="{JEVIX_BEHAVIOR_FORM_ACTION}" method="post" class="sed_form">

			<div class="table cells striped resp-table marginbottom20">
				<div class="table-body resp-table-body">
					<div class="table-row resp-table-row">
						<div class="table-td resp-table-td">
							<label><input type="checkbox" name="behavior_opt_xhtml" value="1" {JEVIX_BEHAVIOR_XHTML} /> {PHP.L.jevix_behavior_xhtml}</label>
						</div>
					</div>
					<div class="table-row resp-table-row">
						<div class="table-td resp-table-td">
							<label><input type="checkbox" name="behavior_opt_auto_br" value="1" {JEVIX_BEHAVIOR_AUTO_BR} /> {PHP.L.jevix_behavior_auto_br}</label>
						</div>
					</div>
					<div class="table-row resp-table-row">
						<div class="table-td resp-table-td">
							<label><input type="checkbox" name="behavior_opt_auto_link" value="1" {JEVIX_BEHAVIOR_AUTO_LINK} /> {PHP.L.jevix_behavior_auto_link}</label>
						</div>
					</div>
				</div>
			</div>
		
			<h4>{PHP.L.jevix_auto_replace}</h4>
		
			<div class="table cells striped resp-table marginbottom20">
				<div class="table-head resp-table-head">
					<div class="table-row resp-table-row">
						<div class="table-th coltop">{PHP.L.jevix_auto_replace_from}</div>
						<div class="table-th coltop">{PHP.L.jevix_auto_replace_to}</div>
					</div>
				</div>
				<div class="table-body resp-table-body">
					<!-- BEGIN: JEVIX_AUTOREPLACE_ROW -->
					<div class="table-row resp-table-row">
						<div class="table-td resp-table-td"><input type="text" name="auto_from[]" value="{JEVIX_AR_FROM}" size="30" class="input" /></div>
						<div class="table-td resp-table-td"><input type="text" name="auto_to[]" value="{JEVIX_AR_TO}" size="30" class="input" /></div>
					</div>
					<!-- END: JEVIX_AUTOREPLACE_ROW -->
				</div>
			</div>

			<h4>{PHP.L.jevix_childs}</h4>

			<div class="table cells striped resp-table marginbottom20">
				<div class="table-head resp-table-head">
					<div class="table-row resp-table-row">
						<div class="table-th coltop">{PHP.L.jevix_childs_parent}</div>
						<div class="table-th coltop">{PHP.L.jevix_childs_children}</div>
						<div class="table-th coltop text-center" style="width:100px;">Container only</div>
						<div class="table-th coltop text-center" style="width:90px;">Child only</div>
					</div>
				</div>
				<div class="table-body resp-table-body">
					<!-- BEGIN: JEVIX_CHILDS_ROW -->
					<div class="table-row resp-table-row">
						<div class="table-td resp-table-td"><input type="text" name="childs_parent[{JEVIX_CHILD_INDEX}]" value="{JEVIX_CHILD_PARENT}" size="15" class="input" /></div>
						<div class="table-td resp-table-td"><input type="text" name="childs_children[{JEVIX_CHILD_INDEX}]" value="{JEVIX_CHILD_CHILDREN}" size="40" class="input" /></div>
						<div class="table-td text-center resp-table-td"><input type="checkbox" name="childs_container[{JEVIX_CHILD_INDEX}]" value="1" {JEVIX_CHILD_CONTAINER} /></div>
						<div class="table-td text-center resp-table-td"><input type="checkbox" name="childs_childonly[{JEVIX_CHILD_INDEX}]" value="1" {JEVIX_CHILD_CHILDONLY} /></div>
					</div>
					<!-- END: JEVIX_CHILDS_ROW -->
				</div>
			</div>

			<h4>{PHP.L.jevix_param_default}</h4>

			<div class="table cells striped resp-table marginbottom20">
				<div class="table-head resp-table-head">
					<div class="table-row resp-table-row">
						<div class="table-th coltop">{PHP.L.jevix_param_default_tag}</div>
						<div class="table-th coltop">{PHP.L.jevix_param_default_attr}</div>
						<div class="table-th coltop">{PHP.L.jevix_param_default_value}</div>
						<div class="table-th coltop text-center" style="width:100px;">{PHP.L.jevix_param_default_rewrite}</div>
					</div>
				</div>
				<div class="table-body resp-table-body">
					<!-- BEGIN: JEVIX_PARAM_DEFAULT_ROW -->
					<div class="table-row resp-table-row">
						<div class="table-td resp-table-td"><input type="text" name="param_default_tag[{JEVIX_PD_INDEX}]" value="{JEVIX_PD_TAG}" size="15" class="input" /></div>
						<div class="table-td resp-table-td"><input type="text" name="param_default_attr[{JEVIX_PD_INDEX}]" value="{JEVIX_PD_ATTR}" size="15" class="input" /></div>
						<div class="table-td resp-table-td"><input type="text" name="param_default_value[{JEVIX_PD_INDEX}]" value="{JEVIX_PD_VALUE}" size="20" class="input" /></div>
						<div class="table-td text-center resp-table-td"><input type="checkbox" name="param_default_rewrite[{JEVIX_PD_INDEX}]" value="1" {JEVIX_PD_REWRITE} /></div>
					</div>
					<!-- END: JEVIX_PARAM_DEFAULT_ROW -->
				</div>
			</div>
			<div class="table-btn text-center">
				<button type="submit" name="a" value="savebehavior" class="btn">{PHP.L.Update}</button>
			</div>
		</form>
		<!-- END: JEVIX_MAIN_BEHAVIOR -->
		
		<!-- END: JEVIX_CONTENT -->

	</div>

</div>

<!-- END: ADMIN_JEVIX -->
