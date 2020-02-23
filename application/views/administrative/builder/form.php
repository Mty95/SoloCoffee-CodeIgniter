<?php
use NewFramework\ActiveRecordConfig;

/** @var ActiveRecordConfig $config */
/** @var string[] $tables */
?>

<style>
	.w-10px {width: 10px;}
	.w-20px {width: 20px;}
	.w-30px {width: 30px;}
	.w-40px {width: 40px;}
	.w-50px {width: 50px;}
	.w-60px {width: 60px;}
	.w-70px {width: 70px;}
	.w-80px {width: 80px;}
	.w-90px {width: 90px;}
	.w-100px {width: 100px;}

	.w-150px {width: 150px;}
	.w-200px {width: 200px;}
	.w-250px {width: 250px;}

	.container-type, .container-type input {
		font-size: 12px !important;
	}
	.container-type input {
		padding: 2px 8px;
	}

	.container-type .form-group {
		margin-bottom: 0.2rem;
	}

	.type-div {margin-top: 10px;}
	.form-control-rule {
		border: none;
		border-bottom: 1px dashed;
		color: #888888 !important;
		font-weight: 400;
		padding: 0 2px;
	}
	.feed-element {
		padding: 5px;
		border: 1px solid #e7eaec;
		margin-top: 2px;
		margin-bottom: 2px;
	}

	.select2-results__option {padding: 4px 6px;}
</style>


<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row">

		<?php if (!isset($fields)):?>
			<div class="col-lg-6">
				<table class="table">
					<?php foreach ($tables as $table):?>
						<tr>
							<td><?=$table?></td>
							<td>
								<a href="<?=admin_url("builder/form/{$table}")?>" class="btn btn-dark btn-sm">
									<i class="fa fa-arrow-right"></i>
								</a>
							</td>
						</tr>
					<?php endforeach;?>
				</table>
			</div>
		<?php endif;?>

		<?php if(null === $config):?>
			Por favor crea un orm.config para la tabla actual.
		<?php endif;?>
		
		<?php if (isset($fields) && null !== $config):?>
			<div class="col-md-8 col-lg-6">
				<div class="ibox ">
					<div class="ibox-content">
						<form method="post" action="<?=current_url()?>">
							<div class="form-group row"><label class="col-sm-2 col-form-label">TableName</label>
								<div class="col-sm-10"><input type="text" class="form-control" value="<?=$config->getTableName()?>" disabled></div>
							</div>
							<div class="form-group row"><label class="col-sm-2 col-form-label">TableName</label>
								<div class="col-sm-10"><input type="text" class="form-control" value="<?=$config->getPrimaryKey()?>"></div>
							</div>

							<?php if(false):?>
								<div class="hr-line-dashed"></div>
								<div class="form-group row">
									<div class="col-sm-4 col-sm-offset-2">
										<button class="btn btn-white btn-sm" type="submit">Cancel</button>
										<button class="btn btn-primary btn-sm" type="submit">Save changes</button>
									</div>
								</div>
							<?php endif;?>
						</form>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-lg-6">
				<textarea name="" id="textareaJson" class="form-control" cols="30" rows="4">
{"id":{"label":"Id","input_type":"input"},"title":{"label":"T\u00edtulo","page_list":"on","page_view":"on","page_create":"on","page_edit":"on","input_type":"input","validation":{"rules":{"required":"","alpha_numeric_spaces":"","is_unique":"categories.title"}}},"original_link":{"label":"Original_link","page_list":"on","page_view":"on","page_create":"on","page_edit":"on","input_type":"input","validation":{"rules":{"required":""}}},"created_at":{"label":"Created_at","page_view":"on","page_create":"on","page_edit":"on","input_type":"input"},"updated_at":{"label":"Updated_at","input_type":"input"},"deleted_at":{"label":"Deleted_at","input_type":"input"}}
				</textarea>
				<button class="btn btn-danger" onclick="transformJsonToObject()">¡Load!</button>
			</div>


			<div class="col-lg-12">
				<div class="ibox ">
					<div class="ibox-title">
						<h5>Form Builder</h5>
					</div>
					<div class="ibox-content">

						<form method="post" id="builderForm" action="<?=current_url()?>">
							<input type="hidden" value="<?=$config->getTableName()?>" name="tableName">

							<table class="table">
								<thead>
								<tr>
									<th style="width: 30px;">#</th>
									<th style="width: 100px;">Column</th>
									<th style="width: 100px;">Label</th>
									<th class="w-80px">Type</th>

									<th class="w-10px text-center">List</th>
									<th class="w-10px text-center">View</th>
									<th class="w-10px text-center">Create</th>
									<th class="w-10px text-center">Edit</th>

									<th class="w-250px">HTML Element</th>
									<th class="w-250px">Rules</th>
								</tr>
								</thead>
								<tbody>
								<?php foreach ($fields as $key => $field):?>
									<tr>
										<td><?=$key+1?></td>
										<td>
											<?=$field->name?>
											<?=$field->primary_key ? '<i class="fa fa-key text-danger"></i>': ''?>
										</td>
										<td>
											<input type="text" class="form-control-rule" name="crud[<?=$field->name?>][label]" value="<?=ucfirst($field->name)?>">
										</td>
										<td>
											<?=$field->type . (null === $field->max_length ? '' : "({$field->max_length})")?>
										</td>


										<td class="text-center"><div class="i-checks"><label><input type="checkbox" name="crud[<?=$field->name?>][page_list]" checked><i></i></label></div></td>
										<td class="text-center"><div class="i-checks"><label><input type="checkbox" name="crud[<?=$field->name?>][page_view]"><i></i></label></div></td>
										<td class="text-center"><div class="i-checks"><label><input type="checkbox" name="crud[<?=$field->name?>][page_create]"><i></i></label></div></td>
										<td class="text-center"><div class="i-checks"><label><input type="checkbox" name="crud[<?=$field->name?>][page_edit]"><i></i></label></div></td>


										<td>
											<select class="select3 form-control input-type" required id="input_type_<?=$key+1?>" data-index="<?=$key+1?>"
													name="crud[<?=$field->name?>][input_type]">
												<option value="" class="address_map checkboxes current_user_id custom_checkbox custom_option custom_select custom_select_multiple date datetime editor email file file_multiple input number options password select select_multiple text time timestamp true_false user_username year yes_no "></option>
												<option value="input" class="input" title="input" relation="0" custom-value="0" selected="selected">Input</option>
												<option value="password" class="password" title="password" relation="0" custom-value="0">Input > Password</option>
												<option value="number" class="number" title="number" relation="0" custom-value="0">Input > Number</option>
												<option value="textarea" class="textarea" title="text" relation="0" custom-value="0">Textarea</option>
												<option value="select" class="select" title="select" relation="1" custom-value="0">Select</option>
												<option value="editor_wysiwyg" class="editor_wysiwyg" title="editor" relation="0" custom-value="0">Editor Wysiwyg</option>
												<option value="email" class="email" title="email" relation="0" custom-value="0">Email</option>
												<option value="address_map" class="address_map" title="address_map" relation="0" custom-value="0">Address Map</option>
												<option value="file" class="file" title="file" relation="0" custom-value="0">File</option>
												<option value="file_multiple" class="file_multiple" title="file_multiple" relation="0" custom-value="0">File Multiple</option>
												<option value="datetime" class="datetime" title="datetime" relation="0" custom-value="0">Datetime</option>
												<option value="date" class="date" title="date" relation="0" custom-value="0">Date</option>
												<option value="timestamp" class="timestamp" title="timestamp" relation="0" custom-value="0">Timestamp</option>
												<option value="yes_no" class="yes_no" title="yes_no" relation="0" custom-value="0">Yes No</option>
												<option value="time" class="time" title="time" relation="0" custom-value="0">Time</option>
												<option value="year" class="year" title="year" relation="0" custom-value="0">Year</option>
												<option value="select_multiple" class="select_multiple" title="select_multiple" relation="1" custom-value="0">Select Multiple</option>
												<option value="checkboxes" class="checkboxes" title="checkboxes" relation="1" custom-value="0">Checkboxes</option>
												<option value="options" class="options" title="options" relation="1" custom-value="0">Options</option>
												<option value="true_false" class="true_false" title="true_false" relation="0" custom-value="0">True False</option>
												<option value="current_user_username" class="current_user_username" title="user_username" relation="0" custom-value="0">Current User Username</option>
												<option value="current_user_id" class="current_user_id" title="current_user_id" relation="0" custom-value="0">Current User Id</option>
												<option value="custom_option" class="custom_option" title="custom_option" relation="0" custom-value="1">Custom Option</option>
												<option value="custom_checkbox" class="custom_checkbox" title="custom_checkbox" relation="0" custom-value="1">Custom Checkbox</option>
												<option value="custom_select_multiple" class="custom_select_multiple" title="custom_select_multiple" relation="0" custom-value="1">Custom Select Multiple</option>
												<option value="custom_select" class="custom_select" title="custom_select" relation="0" custom-value="1">Custom Select</option>
											</select>

											<div class="container-type" id="container_type_<?=$key+1?>" data-index="<?=$key+1?>">
												<div class="type-div d-none select">
													<div class="form-group row"><label class="col-sm-4 col-form-label">table</label>
														<div class="col-sm-8">
															<select class="select3 form-control">
																<option value=""></option>
																<?php foreach ($tables as $table):?>
																	<option value="<?=$table?>"><?=$table?></option>
																<?php endforeach;?>
															</select>
														</div>
													</div>
													<div class="form-group row"><label class="col-sm-4 col-form-label">value</label>
														<div class="col-sm-8">
															<select class="select4 form-control">
															</select>
														</div>
													</div>
													<div class="form-group row"><label class="col-sm-4 col-form-label">label</label>
														<div class="col-sm-8">
															<select class="select4 form-control">
															</select>
														</div>
													</div>
												</div>
											</div>
										</td>
										<td>
											<select class="select4 form-control chosen chosen-select validation" id="validation_selector_<?=$field->name?>" data-placeholder="+ " style="display: none;"
													data-index="<?=$key + 1?>" data-field="<?=$field->name?>">
												<option value=""></option>
												<option value="required" title="no" data-placeholder="">Required</option>
												<option value="min_length" title="yes" data-placeholder="">Min Length</option>
												<option value="max_length" title="yes" data-placeholder="">Max Length</option>
												<option value="valid_email" title="no" data-placeholder="">Valid Email</option>
												<option value="valid_emails" title="no" data-placeholder="">Valid Emails</option>
												<option value="regex" title="yes" data-placeholder="">Regex</option>
												<option value="decimal" title="no" data-placeholder="">Decimal</option>
												<option value="valid_url" title="no" data-placeholder="">Valid Url</option>
												<option value="alpha" title="no" data-placeholder="">Alpha</option>
												<option value="alpha_numeric" title="no" data-placeholder="">Alpha Numeric</option>
												<option value="alpha_numeric_spaces" title="no" data-placeholder="">Alpha Numeric Spaces</option>
												<option value="valid_number" title="no" data-placeholder="">Valid Number</option>
												<option value="valid_datetime" title="no" data-placeholder="">Valid Datetime</option>
												<option value="valid_date" title="no" data-placeholder="">Valid Date</option>
												<option value="valid_alpha_numeric_spaces_underscores" title="no" data-placeholder="">Valid Alpha Numeric Spaces Underscores</option>
												<option value="matches" title="yes" data-placeholder="any field">Matches</option>
												<option value="valid_url" title="no" data-placeholder="">Valid Url</option>
												<option value="exact_length" title="yes" data-placeholder="0 - 99999*">Exact Length</option>
												<option value="alpha_dash" title="no" data-placeholder="">Alpha Dash</option>
												<option value="integer" title="no" data-placeholder="">Integer</option>
												<option value="differs" title="yes" data-placeholder="any field">Differs</option>
												<option value="is_natural" title="no" data-placeholder="">Is Natural</option>
												<option value="is_natural_no_zero" title="no" data-placeholder="">Is Natural No Zero</option>
												<option value="less_than" title="yes" data-placeholder="">Less Than</option>
												<option value="less_than_equal_to" title="yes" data-placeholder="">Less Than Equal To</option>
												<option value="greater_than" title="yes" data-placeholder="">Greater Than</option>
												<option value="greater_than_equal_to" title="yes" data-placeholder="">Greater Than Equal To</option>
												<option value="in_list" title="yes" data-placeholder="">In List</option>
												<option value="valid_ip" title="no" data-placeholder="">Valid Ip</option>

												<option value="is_unique" title="no" data-params="true" data-placeholder="table.field">Is Unique</option>

												<!-- New by other framework -->
												<option value="valid_json" title="no" data-params="false" data-placeholder="" data-external="true">Valid Json</option>
												<!-- /New by other framework -->

												<!-- Modifiers -->
												<option value="urldecode" title="no" data-params="false" data-placeholder="" data-modifier="true">urldecode</option>
												<!-- /Modifiers -->
											</select>

											<div class="feed-activity-list">
											</div>

											<?php if(false):?>
												<input type="text" class="form-control form-control-sm"name="crud[<?=$key?>][<?=$field->name?>][validation][rules]">
											<?php endif;?>
										</td>
									</tr>
								<?php endforeach;?>
								</tbody>
							</table>
						</form>

						<button type="submit" form="builderForm" class="btn mag-btn btn-xs mt-30 btn-block">Save</button>

					</div>
				</div>
			</div>
		<?php endif;?>
	</div>
</div>

<!-- CodeMirror -->
<link href="<?=assets('backend/css/plugins/codemirror/codemirror.css')?>" rel="stylesheet">
<link href="<?=assets('backend/css/plugins/codemirror/ambiance.css')?>" rel="stylesheet">
<link href="<?=assets('backend/css/plugins/iCheck/custom.css')?>" rel="stylesheet">

<script src="<?=assets('backend/js/plugins/codemirror/codemirror.js')?>"></script>
<script src="<?=assets('backend/js/plugins/codemirror/mode/javascript/javascript.js')?>"></script>
<script src="<?=assets('backend/js/plugins/iCheck/icheck.min.js')?>"></script>

<script src="<?=assets('backend/js/plugins/jquery-ui/jquery-ui.min.js')?>"></script>
<script src="<?=assets('backend/js/plugins/touchpunch/jquery.ui.touch-punch.min.js')?>"></script>

<script>
	function formatState (opt) {
		if (!opt.id) { return opt.text; }
		let fontColor = 'text-default';

		if ($(opt.element).data('external')) {
			fontColor = 'text-warning font-bold';
		}

		if ($(opt.element).data('modifier')) {
			return $(`<span><i class="fa fa-cogs text-danger"></i>  <span class="${fontColor} font-bold">${opt.text}</span></span>`);
		}

		return $(`<span><i class="fa fa-check-square-o text-success"></i>  <span class="${fontColor}">${opt.text}</span>`);
	}


	$(document).ready(function () {
		$(".select3").select2({
			placeholder: 'Seleccionar',
			allowClear: true,
		});
		$(".select4").select2({
			placeholder: 'Select',
			allowClear: false,
			templateResult: formatState,
			templateSelection: formatState,
		});

		$('.i-checks').iCheck({
			checkboxClass: 'icheckbox_square-green',
			radioClass: 'iradio_square-green',
		});
		$('.feed-activity-list').sortable();

		// ---------------------------------------------------

		$('.input-type').on('change', function () {
			const index = $(this).data('index');
			const value = $(this).val();
			const input = $('#input_type_' + index);
			const container = $('#container_type_' + index);

			$('#container_type_' + index + ' > div.type-div').addClass('d-none');
			$('#container_type_' + index + ' > div.type-div.' + value).removeClass('d-none');
			console.log(value);
		});

		$(document).on('click', '.feed-element-remove', function () {
			const el = $(this);
			const parent = el.parent().parent().parent();
			parent.remove();
		});

		$('.select4.validation').on('change', function () {
			const index = $(this).data('index');
			const value = $(this).val();

			if (value === '')
			{
				return false;
			}

			setRuleElement($(this), value);
			$(this).select2('val', null);
		});

		// ----------------
		$('#builderForm').on('submit', function (e) {
			e.preventDefault();
			const data = $(this).serializeArray();
			console.log(data);
			// $('.form-text').each((index, element) => {$(element).text('')});

			$.post(BASE_URL + 'administrator/builder/form_post', data, function (response) {
				console.log(response.data);
				// console.table(response.data);
				if (!response.status) {
					// Utils.showFormErrors(response);
					return false;
				}

				// window.location = BASE_URL;
			});

			return false;
		});
	});

	function setRuleElement($el, rule, value = '') {
		const template_basic = '<div class="feed-element">\n\t<div class="media-body ">\n\t\t<small class="float-right text-navy"><i class="fa fa-2x fa-trash text-danger feed-element-remove"></i></small>\n\t\t<strong>{name}</strong><br>\n\t\t<input type="hidden" name="crud[{field}][validation][rules][{rule}]" value="{value}">\n\t</div>\n</div>';
		const template_one_input = '<div class="feed-element">\n\t<div class="media-body ">\n\t\t<small class="float-right text-navy"><i class="fa fa-2x fa-trash text-danger feed-element-remove"></i></small>\n\t\t<strong>{name}</strong><br>\n\t\t<input type="text" class="form-control-rule" placeholder="{placeholder}" name="crud[{field}][validation][rules][{rule}]" value="{value}">\n\t</div>\n</div>';

		const feedList = $el.parent().find('.feed-activity-list');
		const selected = $el.find(':selected');
		let str = '';
		let data = {};

		// -------------------------------
		data.index = $el.data('index');
		data.field = $el.data('field');
		data.rule = rule;
		data.name = selected.text();
		data.placeholder = selected.data('placeholder');
		data.value = value;

		if (['required', 'valid_email', 'decimal', 'valid_url', 'alpha', 'alphanumeric', 'alpha_numeric_spaces'].includes(rule)) {
			str = buildFeedElement(template_basic, data);
		}

		if (['max_length', 'min_length', 'regex', 'matches', 'in_list'].includes(rule)) {
			str = buildFeedElement(template_one_input, data);
		}

		if (undefined !== selected.data('params')) {
			console.table(selected.data('params'));
			str = buildFeedElement(
				selected.data('params') === false ?
					template_basic
					: template_one_input
				, data
			);
		}

		feedList.append(str);
	}

	function buildFeedElement(template, data = {index: '', field: '', rule: '', name: '', placeholder: '', value: ''}) {
		let temp = template;

		temp = temp.replace(/{index}/g, data.index);
		temp = temp.replace(/{field}/g, data.field);
		temp = temp.replace(/{rule}/g, data.rule);

		temp = temp.replace(/{name}/g, data.name);
		temp = temp.replace(/{placeholder}/g, data.placeholder);
		temp = temp.replace(/{value}/g, data.value);

		return temp;
	}

	function transformJsonToObject() {
		const data = JSON.parse($('#textareaJson').val());
		// console.log(data);

		for (const [key, value] of Object.entries(data)) {
			let el = $('#validation_selector_' + key);
			let checkboxList = $(`[name="crud[${key}][page_list]"]`);
			let checkboxView = $(`[name="crud[${key}][page_view]"]`);
			let checkboxCreate = $(`[name="crud[${key}][page_create]"]`);
			let checkboxEdit = $(`[name="crud[${key}][page_edit]"]`);
			let $label = $(`[name="crud[${key}][label]"]`);

			checkboxList.prop('checked', false);
			checkboxView.prop('checked', false);
			checkboxCreate.prop('checked', false);
			checkboxEdit.prop('checked', false);

			if (undefined !== value.page_list) {
				checkboxList.prop('checked', true);
			}

			if (undefined !== value.page_view) {
				checkboxView.prop('checked', true);
			}

			if (undefined !== value.page_create) {
				checkboxCreate.prop('checked', true);
			}

			if (undefined !== value.page_edit) {
				checkboxEdit.prop('checked', true);
			}

			$label.val(value.label);

			/*page_list
			page_view
			page_create
			page_edit*/

			if (null === value.validation || value.validation === undefined) {
				continue;
			}

			for (const [rule, val] of Object.entries(value.validation.rules)) {
				el.val(rule); // No eliminar esta línea
				setRuleElement(el, rule, val);
			}
		}

		$('.i-checks').iCheck({
			checkboxClass: 'icheckbox_square-green',
			radioClass: 'iradio_square-green',
		});
	}
</script>
