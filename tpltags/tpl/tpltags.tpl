<!-- BEGIN: MAIN -->

<main id="plugins">
	
	<div class="container">
	
		<div class="section-title">
		
			{TAGS_BREADCRUMBS}	

			<h1>{TAGS_TITLE}</h1>
			
			<div class="section-desc">
				
			</div>
			
		</div>

		<div class="section-body">
		
			<div class="page-text">
				{TAGS_SUBTITLE}
			</div>

			<!-- BEGIN: ERROR -->
			<div class="error">{ERROR_BODY}</div>
			<!-- END: ERROR -->
			 
			<!-- BEGIN: EDIT_TAG -->
			<form action="{TAG_EDIT_FORM_ACTION}" method="post" name="editag">
			<table class="cells">
			<tr>
			  <td>{PHP.L.tpltags_tag}</td>
			  <td>{TAG_EDIT_TITLE}</td>
			</tr>
			<tr>
			  <td>{PHP.L.tpltags_tpl}</td>
			  <td>{TAG_EDIT_TPL}</td>
			</tr>
			<tr>
			  <td>{PHP.L.tpltags_type}</td>
			  <td>{TAG_EDIT_TYPE}</td>
			</tr>
			<tr>
			  <td>{PHP.L.tpltags_version}</td>
			  <td>{TAG_EDIT_VERSION}</td>
			</tr>
			<tr>
			  <td>{PHP.L.tpltags_loc}</td>
			  <td>{TAG_EDIT_LOCATION}</td>
			</tr>
			<tr>
			  <td>{PHP.L.tpltags_details}</td>
			  <td>{TAG_EDIT_DETAILS}</td>
			</tr>

			<tr>
			  <td>{PHP.L.tpltags_delete_hint}</td>
			  <td>{TAG_EDIT_DELETE}</td>
			</tr>
			</table>
			<input type="submit" class="btn" value="{PHP.L.tpltags_update}">
			</form>
			<!-- END: EDIT_TAG -->

			<!-- BEGIN: ADD_NEW_TAG -->
			<form action="{TAG_ADD_FORM_ACTION}" method="post" name="newtag">
			<table class="cells">
			<tr>
			  <td>{PHP.L.tpltags_tag}</td>
			  <td>{TAG_ADD_TITLE}</td>
			</tr>
			<tr>
			  <td>{PHP.L.tpltags_tpl}</td>
			  <td>{TAG_ADD_TPL}</td>
			</tr>
			<tr>
			  <td>{PHP.L.tpltags_type}</td>
			  <td>{TAG_ADD_TYPE}</td>
			</tr>
			<tr>
			  <td>{PHP.L.tpltags_version}</td>
			  <td>{TAG_ADD_VERSION}</td>
			</tr>
			<tr>
			  <td>{PHP.L.tpltags_loc}</td>
			  <td>{TAG_ADD_LOCATION}</td>
			</tr>
			<tr>
			  <td>{PHP.L.tpltags_details}</td>
			  <td>{TAG_ADD_DETAILS}</td>
			</tr>
			</table>
			<input type="submit" class="btn" value="{PHP.L.tpltags_submit}">
			</form>
			<!-- END: ADD_NEW_TAG -->

			<!-- BEGIN: HOME -->
			<div class="row row-flex">
			
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			
					<table class="cells">
					<tr>
					  <td class="coltop" style="width:1px;">#</td>
					  <td class="coltop">{PHP.L.tpltags_skin_files}</td>
					  <td class="coltop" style="width:1px;">{PHP.L.tpltags_tags}</td>
					</tr>
					<!-- BEGIN: TAGS -->
					<tr>
					  <td class="{TPL_ROW_ODDEVEN}">{TPL_ROW_COUNTER}</td>
					  <td class="{TPL_ROW_ODDEVEN}"><a href="{TPL_ROW_URL}">{TPL_ROW_TITLE}</a></td>
					  <td class="{TPL_ROW_ODDEVEN} centerall">{TPL_ROW_TOTAL}</td>
					</tr>
					<!-- END: TAGS -->
					</table>
					
				</div>
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

					<table class="cells">
					<tr>
					  <td class="coltop" style="width:1px;">#</td>
					  <td class="coltop">{PHP.L.tpltags_by_version}</td>
					  <td class="coltop" style="width:1px;">{PHP.L.tpltags_tags}</td>
					</tr>
					<!-- BEGIN: VER -->
					<tr>
					  <td class="{VER_ROW_ODDEVEN}">{VER_ROW_COUNTER}</td>
					  <td class="{VER_ROW_ODDEVEN}"><a href="{VER_ROW_URL}">{VER_ROW_TITLE}</a></td>
					  <td class="{VER_ROW_ODDEVEN} centerall">{VER_ROW_TOTAL}</td>
					</tr>
					<!-- END: VER -->	
					</table>

					<table class="cells">
					<tr>
					  <td class="coltop">{PHP.L.tpltags_filters}</td>
					  <td class="coltop" style="width:1px;">{PHP.L.tpltags_tags}</td>
					</tr>
					<tr>
					  <td class="odd"><a href="{TOTAL_TAGS_URL}">{PHP.L.tpltags_all}</a></td>
					  <td class="odd centerall">{TOTAL_TAGS}</td>
					</tr>
					<tr>
					  <td class="even"><a href="{TOTAL_TAGS_GLOBAL_URL}">{PHP.L.tpltags_global}</a></td>
					  <td class="even centerall">{TOTAL_TAGS_GLOBAL}</td>
					</tr>
					</table>

					<div class="centered">
					<a href="https://seditio.org"><img src="plugins/tpltags/img/sed_80x15_00.gif" alt="We love Seditio!" border="0" /></a><br />
					<a href="https://seditio.org"><img src="plugins/tpltags/img/sed_80x15_33.gif" alt="We love Seditio!" border="0" /></a><br />
					<a href="https://seditio.org"><img src="plugins/tpltags/img/sed_80x15_16.gif" alt="We love Seditio!" border="0" /></a><br />
					<a href="https://seditio.org"><img src="plugins/tpltags/img/sed_80x15_14.gif" alt="We love Seditio!" border="0" /></a><br />
					<a href="https://seditio.org"><img src="plugins/tpltags/img/sed_80x15_20.gif" alt="We love Seditio!" border="0" /></a><br />
					</div>
					
				</div>
				
			</div>

			<!-- END: HOME -->


			<!-- BEGIN: TAGS -->
			<table class="cells">
			<tr>
			  <td class="coltop" style="width:1px;">#</td>
			  <td class="coltop">{PHP.L.tpltags_tag}{TAG_TOP_TITLE}</td>
			  <td class="coltop">{PHP.L.tpltags_loc}{TAG_TOP_LOC}</td>
			  <td class="coltop">{PHP.L.tpltags_type}{TAG_TOP_TYPE}</td>
			  <td class="coltop">{PHP.L.tpltags_tpl}{TAG_TOP_TPL}</td>
			  <td class="coltop" style="width:90px;">{PHP.L.tpltags_details}{TAG_TOP_DESC}</td>
			  <td class="coltop">{PHP.L.tpltags_ver}{TAG_TOP_VER}</td>
			</tr>
			<!-- BEGIN: ROW -->
			<tr>
			  <td class="{TAG_ROW_ODDEVEN} middleall">{TAG_ROW_COUNTER}</td>
			  <td class="{TAG_ROW_ODDEVEN} middleall">{TAG_ROW_ADMIN}{TAG_ROW_TITLE}</td>
			  <td class="{TAG_ROW_ODDEVEN} middleall centerall"><span class="desc">{TAG_ROW_LOCATION}</span></td>
			  <td class="{TAG_ROW_ODDEVEN} middleall centerall"><span class="desc">{TAG_ROW_TYPE}</span></td>
			  <td class="{TAG_ROW_ODDEVEN} middleall centerall"><span class="desc"><a href="{TAG_ROW_URL}">{TAG_ROW_TPL}</a></span></td>
			  <td class="{TAG_ROW_ODDEVEN} middleall" style="width:50px;"><span class="desc">{TAG_ROW_DETAILS}</span></td>
			  <td class="{TAG_ROW_ODDEVEN} middleall centerall"><span class="desc">{TAG_ROW_VERSION}</span></td>
			</tr>
			<!-- END: ROW -->
			</table>

			<table border="0" cellpadding="2" cellspacing="0">
			<tr>
			  <td class="coltop" colspan="2">{PHP.L.tpltags_legend}</td>
			</tr>
			<tr>
			  <td class="even">{PHP.L.tpltags_type_Alphanumerical}</td>
			  <td class="odd">{PHP.L.tpltags_type_Alphanumerical_desc}</td>
			</tr>
			<tr>
			  <td class="even">{PHP.L.tpltags_type_Array}</td>
			  <td class="odd">{PHP.L.tpltags_type_Array_desc}</td>
			</tr>
			<tr>
			  <td class="even">{PHP.L.tpltags_type_Boolean}</td>
			  <td class="odd">{PHP.L.tpltags_type_Boolean_desc}</td>
			</tr>
			<tr>
			  <td class="even">{PHP.L.tpltags_type_Composite}</td>
			  <td class="odd">{PHP.L.tpltags_type_Composite_desc}</td>
			</tr>
			<tr>
			  <td class="even">{PHP.L.tpltags_type_Date}</td>
			  <td class="odd">{PHP.L.tpltags_type_Date_desc}</td>
			</tr>
			<tr>
			  <td class="even">{PHP.L.tpltags_type_Image}</td>
			  <td class="odd">{PHP.L.tpltags_type_Image_desc}</td>
			</tr>
			<tr>
			  <td class="even">{PHP.L.tpltags_type_Input}</td>
			  <td class="odd">{PHP.L.tpltags_type_Input_desc}</td>
			</tr>
			<tr>
			  <td class="even">{PHP.L.tpltags_type_Integer}</td>
			  <td class="odd">{PHP.L.tpltags_type_Integer_desc}</td>
			</tr>
			<tr>
			  <td class="even">{PHP.L.tpltags_type_Link}</td>
			  <td class="odd">{PHP.L.tpltags_type_Link_desc}</td>
			</tr>
			<tr>
			  <td class="even">{PHP.L.tpltags_type_Level}</td>
			  <td class="odd">{PHP.L.tpltags_type_Level_desc}</td>
			</tr>
			<tr>
			  <td class="even">{PHP.L.tpltags_type_Raw_link}</td>
			  <td class="odd">{PHP.L.tpltags_type_Raw_link_desc}</td>
			</tr>
			<tr>
			  <td class="even">{PHP.L.tpltags_type_String}</td>
			  <td class="odd">{PHP.L.tpltags_type_String_desc}</td>
			</tr>
			<tr>
			  <td class="even">{PHP.L.tpltags_type_Simple_text}</td>
			  <td class="odd">{PHP.L.tpltags_type_Simple_text_desc}</td>
			</tr>
			<tr>
			  <td class="even">{PHP.L.tpltags_type_Text}</td>
			  <td class="odd">{PHP.L.tpltags_type_Text_desc}</td>
			</tr>
			<tr>
			  <td class="even">{PHP.L.tpltags_type_Time}</td>
			  <td class="odd">{PHP.L.tpltags_type_Time_desc}</td>
			</tr>
			<tr>
			  <td class="even">{PHP.L.tpltags_type_URL}</td>
			  <td class="odd">{PHP.L.tpltags_type_URL_desc}</td>
			</tr>
			<tr>
			  <td class="even">{PHP.L.tpltags_type_System}</td>
			  <td class="odd">{PHP.L.tpltags_type_System_desc}</td>
			</tr>
			</table>

			<!-- END: TAGS -->
			<div class="centered">{TAG_ADD}</div>			 

		</div>
		
	</div>	

</main>	

<!-- END: MAIN -->
