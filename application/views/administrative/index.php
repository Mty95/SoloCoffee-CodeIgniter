<?php

use App\Model\User\User;
use Mty95\MenuManager\MenuManager;

/** @var User $user */
/** @var string $title */
?>

<!DOCTYPE html>
<html>

<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title><?=$title?></title>

	<script>
		const BASE_URL = '<?=base_url()?>';
		const ADMIN_URL = '<?=admin_url()?>';
	</script>

	<link href="<?=assets('backend/css/bootstrap.min.css')?>" rel="stylesheet">
	<link href="<?=assets('backend/font-awesome/css/font-awesome.css')?>" rel="stylesheet">

	<link href="<?=assets('backend/css/animate.css')?>" rel="stylesheet">
	<link href="<?=assets('backend/css/style.css')?>" rel="stylesheet">


	<!-- Mainly scripts -->
	<script src="<?=assets('backend/js/jquery-3.1.1.min.js')?>"></script>
	<script src="<?=assets('backend/js/popper.min.js')?>"></script>
	<script src="<?=assets('backend/js/bootstrap.min.js')?>"></script>
	<script src="<?=assets('backend/js/plugins/metisMenu/jquery.metisMenu.js')?>"></script>
	<script src="<?=assets('backend/js/plugins/slimscroll/jquery.slimscroll.min.js')?>"></script>
	<!--    <script src="--><?//=assets('backend/js/mty95.js')?><!--"></script>-->

	<!-- d3 and c3 charts -->
	<link href="<?=assets('backend/css/plugins/c3/c3.min.css')?>" rel="stylesheet">
	<script src="<?=assets('backend/js/plugins/d3/d3.min.js')?>"></script>
	<script src="<?=assets('backend/js/plugins/c3/c3.min.js')?>"></script>

	<!-- Custom and plugin javascript -->
	<script src="<?=assets('backend/js/inspinia.js')?>"></script>
	<script src="<?=assets('backend/js/plugins/pace/pace.min.js')?>"></script>

	<!-- Toastr -->
	<link href="<?=assets('backend/css/plugins/toastr/toastr.min.css')?>" rel="stylesheet">
	<script src="<?=assets('backend/js/plugins/toastr/toastr.min.js')?>"></script>

	<!-- FooTable -->
	<link href="<?=assets('backend/css/plugins/footable/footable.core.css')?>" rel="stylesheet">
	<script src="<?=assets('backend/js/plugins/footable/footable.all.min.js')?>"></script>

	<!-- Sweet alert -->
	<link href="<?=assets('backend/css/plugins/sweetalert/sweetalert.css')?>" rel="stylesheet">
	<script src="<?=assets('backend/js/plugins/sweetalert/sweetalert.min.js')?>"></script>

	<!-- Select2 -->
	<link href="<?=assets('backend/css/plugins/select2/select2.min.css')?>" rel="stylesheet">
	<script src="<?=assets('backend/js/plugins/select2/select2.full.min.js')?>"></script>

	<link href="<?=assets('backend/css/plugins/dataTables/datatables.min.css')?>" rel="stylesheet">
	<script src="<?=assets('backend/js/plugins/dataTables/datatables.min.js')?>"></script>
	<script src="<?=assets('backend/js/plugins/dataTables/dataTables.bootstrap4.min.js')?>"></script>

	<!--  DatePicker and Spanish Languaje -->
	<link href="<?=assets('backend/css/plugins/datapicker/datepicker3.css')?>" rel="stylesheet">
	<script src="<?=assets('backend/js/plugins/datapicker/bootstrap-datepicker.js')?>"></script>
	<script src="<?=assets('backend/plugins/bootstrap-datepicker.es.min.js')?>" type="text/javascript"></script>
	<script src="<?=assets('backend/js/plugins/fullcalendar/moment.min.js')?>"></script>

	<style>
		.form-control-static {
			margin-top: 0.5rem !important;
		}
		.ibox-tools a{
			color: inherit !important;
		}

		.table-report {font-size: 12px;}
		.table-report th, .table-report td {padding: 4px !important;}
		.table-list {font-size: 12px;}
		.table-list th, .table-list td {padding: 4px !important;}

		/* --- Fix Select2 inside Modal --- */
		.select2-container {width: 100% !important;padding: 0;}
		.select2-container--open {z-index: 999999 !important;}
		/* --- ------------------------ --- */

		.select2-selection__clear {font-size: 16px;}

		/*.md-skin .nav > li > a {padding: 10px 16px 10px 25px;}*/
		.md-skin .nav > li > a {padding: 10px 16px 10px 20px;}

		.md-skin .nav-second-level li a {padding: 5px 10px 5px 42px;}
		.md-skin .nav-third-level li a {padding: 5px 10px 5px 62px;}

		.sk-spinner-wave {
			width: 100px !important;
		}
	</style>

	<script>
		/**
		 * Author:		Alberto Yauri Ecos
		 * Github:		https://github.com/Mty95
		 */
		class Utils {
			static iBoxToggle($el = $('.ibox-content')) {
				$el.toggleClass('sk-loading');
			}

			static rePaintSelect2($selector, data, keyString, valueString, idString = 'id') {
				$selector.html('');
				$selector.append(new Option('', ''));

				$.each(data, (key, entity) => {
					$selector.append(
						new Option(`[${entity[keyString]}] - ${entity[valueString]}`, entity[idString])
					);
				});

				$selector.trigger('change');




				/*
				let $customAgent = $('#customAgentSelector');

				$customAgent.html('');
				$.each(response.data, function (key, customAgent) {
					$customAgent.append(new Option(`[${customAgent.code}] - ${customAgent.name}`, customAgent.id));
				});
				$customAgent.trigger('change');
				*/
			}

			static showFormErrors(response) {
				if (response.data === undefined) {
					return;
				}

				$.each(response.data, function (key, value) {
					let input = $(`[name="${key}"]`);
					let el = input.parent().find('.form-text');

					if (undefined === el[0]) {
						input.parent().append('<span class="form-text m-b-none text-danger"></span>');
						el = input.parent().find('.form-text');
					}

					el.text(value);
				})

			}
		}

		class HttpService {
			static url = BASE_URL;
			static hasLoadingAnimation = false;
			static loadingElement = '.ibox-content';

			/**
			 *
			 * @param resource
			 * @param callable
			 * @param headers
			 */
			static get(resource, callable, headers = {}) {
				if (resource === undefined || callable === undefined) {
					return;
				}

				if (!callable.call) {
					return;
				}

				this.toggleAnimation();

				$.get(this.url + resource, (response) => {
					if (!response.status) {
						toastr.error(response.message, 'Alerta');
						return;
					}

					callable(response);
				}).fail(() => {
					toastr.error('Sucedió un error, por favor intenta nuevamente.', 'Alerta');
				}).always(() => {
					this.toggleAnimation();
				});
			}

			static post(resource, data, callable, headers = {}) {

			}

			static toggleAnimation() {
				if (this.hasLoadingAnimation) {
					Utils.iBoxToggle($(this.loadingElement));
				}
			}
		}

		/**
		 * @deprecated
		 */
		class AuthHttpService extends HttpService {
			static token = '';

			static get(resource, callable, headers = {}) {
				return super.get(resource, callable, headers);
			}
		}

		class ProductItem {
			_id = 0;

			constructor(data = {}) {
				this.product_id = parseInt(data.product_id);
				this.brand_id = parseInt(data.brand_id);
				this.variety_id = parseInt(data.variety_id);

				this.variety_name = data.variety_name || '';
				this.product_name = data.product_name || '';
				this.brand_name = data.brand_name || '';
				this.quantity = parseInt(data.quantity);
				this.weight = parseFloat(data.weight);
			}

			export() {
				return [
					this.variety_name,
					this.product_name,
					this.brand_name,
					this.quantity,
					this.weight,
				];
			}

			me(id) {
				if (this._id === id) return this;
				return undefined;
			}
		}

		class OperationProductList {
			_counter = 0;
			$boxesTotal;
			$weightTotal;
			$productsData;

			constructor(data = {}) {
				this.items = [];

				this.$boxesTotal = $('#boxesTotal');
				this.$weightTotal = $('#weigthTotal');
				this.$productsData = $('[name="products_data"]');
			}

			/**
			 * @param {ProductItem} item
			 */
			addItem(item) {
				this.items.push(item);
				item._id = ++this._counter;
			}

			export() {
				return this.items.map(item => {return item.export()});
			}

			exportJSON() {
				return JSON.stringify(
					this.items.map(item => {
						return {
							product_id: item.product_id,
							brand_id: item.brand_id,
							variety_id: item.variety_id,
							variety_name: item.variety_name,
							product_name: item.product_name,
							brand_name: item.brand_name,
							quantity: item.quantity,
							weight: item.weight,
						}
					})
				);
			}

			remove(_id) {
				this.items = this.items.filter(item => {return item._id !== _id;});
			}

			/**
			 * Draws a Table with Products List.
			 */
			drawTable($table) {
				$table.children('tbody').empty();

				this.items.map(item => {
					let rows = '';
					item.export().map(value => {
						rows += `<td>${value}</td>`;
					});

					rows += `<td><button type="button" class="btn btn-danger btn-xs" onclick="deleteTableRow(this, ${item._id})">x</button></td>`;
					$table.append(`<tr>${rows}</tr>`);
				});

				this.$boxesTotal.text(operationProductItems.getTotalQuantity().toFixed(0));
				this.$weightTotal.text(operationProductItems.getTotalWeight().toFixed(2));
				this.$productsData.val(operationProductItems.exportJSON());
			}

			getTotalWeight() {
				return this.items.map(item => item.weight)
					.reduce((total, val) => {return total + val}, 0);
			}
			getTotalQuantity() {
				return this.items.map(item => item.quantity)
					.reduce((total, val) => {return total + val}, 0);
			}

			/**
			 * Only for test purposes.
			 *
			 * @test
			 * @param qty
			 */
			test(qty) {
				for (let i = 0; i < qty; i++) {
					let data = {brand_id: "15",brand_name: "RÍO PERU",product_id: "11",product_name: "UVA",
						quantity: Math.floor(Math.random() * (1 - 100 + 1)) + 100,
						weight: (Math.random() * 1500).toFixed(2),
						variety_id: "14",
						variety_name: "RED GLOBE"
					};
					this.addItem(new ProductItem(data));
				}

				this.drawTable($('#productList'));
			}
		}

		$(document).ready(function () {
			// $.datepicker.setDefaults($.datepicker.regional['es']);

			toastr.options = {
				"closeButton": false,
				"debug": false,
				"progressBar": true,
				"preventDuplicates": false,
				"positionClass": "toast-top-right",
				"onclick": null,
				"showDuration": "400",
				"hideDuration": "1000",
				"timeOut": "5000",
				"extendedTimeOut": "1000",
				"showEasing": "swing",
				"hideEasing": "linear",
				"showMethod": "fadeIn",
				"hideMethod": "fadeOut"
			};

			const lang_spanish = {
				"sProcessing":     "Procesando...",
				"sLengthMenu":     "Mostrar _MENU_ registros",
				"sZeroRecords":    "No se encontraron resultados",
				"sEmptyTable":     "Ningún dato disponible en esta tabla =(",
				"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
				"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
				"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
				"sInfoPostFix":    "",
				"sSearch":         "Buscar:",
				"sUrl":            "",
				"sInfoThousands":  ",",
				"sLoadingRecords": "Cargando...",
				"oPaginate": {
					"sFirst":    "Primero",
					"sLast":     "Último",
					"sNext":     "Siguiente",
					"sPrevious": "Anterior"
				},
				"oAria": {
					"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
					"sSortDescending": ": Activar para ordenar la columna de manera descendente"
				},
				"buttons": {
					"copy": "Copiar",
					"colvis": "Visibilidad",
					copyTitle: 'Agregado al portapapeles',
					copySuccess: 'Se copió %d registros al portapapeles'
				}
			};
			$.extend(true, $.fn.dataTable.defaults, {
				language: lang_spanish
			});

			$.extend(true, $.fn.datepicker.defaults, {
				format: 'dd/mm/yyyy',
				language: 'es'
			});
		});


		// --------------
		function deleteUser(el) {
			try {

				let $el = $(el);
				let url = '<?=admin_url("user/delete")?>/' + `${$el.data('id')}/${$el.data('back-url')}`;

				swal({
						title: "¿Estás seguro de eliminar al usuario?",
						text: "Esta acción no es reversible",
						type: "warning",
						showCancelButton: true,
						confirmButtonColor: "#DD6B55",
						confirmButtonText: "Si",
						cancelButtonText: "No",
						closeOnConfirm: true
					},
					function (isConfirm) {
						if (isConfirm) {
							$('.ibox-content').toggleClass('sk-loading');
							window.location = url;

						}
					});
			} catch (e) {
				alert(e);
			}
		}

		function deleteEntity(el) {
			let $el = $(el);
			// let url = ADMIN_URL + `${$el.data('id')}/${$el.data('back-url')}`;
			let url = ADMIN_URL + `${$el.data('resource')}/${$el.data('back-url')}`;

			swal({
					title: "¿Está seguro de eliminar?",
					text: "Esta acción no es reversible",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Si",
					cancelButtonText: "No",
					closeOnConfirm: true
				},
				function (isConfirm) {
					if (isConfirm) {
						$('.ibox-content').toggleClass('sk-loading');
						window.location = url;

					}
				});
		}

		function viewEntity(el) {
			let $el = $(el);
			let url = ADMIN_URL + $el.data('resource');

			if ($el.data('back-url') !== undefined)
				url += `/${$el.data('back-url')}`;

			$('.sk-spinner-message').text('Cargando');
			$('.ibox-content').toggleClass('sk-loading');
			window.location = url;
		}

		function validateForm($form, resource, successLocation) {
			let url = BASE_URL + resource;
			let data = $form.serializeArray();
			let $ibox = $('.ibox-content');

			$ibox.toggleClass('sk-loading');

			$('.form-text').each(function (index, element) {
				$(element).text('');
			});
			$('.form-message-error').each(function (index, element) {
				$(element).text('');
			});

			$.post(url, data, function(data) {
				let response = data;

				if (response.status) {
					window.location = BASE_URL + successLocation;
					return false;
				}

				toastr.error(response.message, 'Alerta');
				$ibox.toggleClass('sk-loading');

				if (response.data !== undefined) {
					$.each(response.data, function (key, value) {
						let $input = $(`[name="${key}"]`);
						let el = $input.parent().find('.form-text');

						if (undefined === el[0]) {
							$input.parent().append('<span class="form-text m-b-none text-danger"></span>');
							el = $input.parent().find('.form-text');
						}

						el.text(value);
					});
				} else {
					let $element = $form.find('.form-message-error');

					if (undefined === $element[0]) {
						$form.prepend('<p class="text-danger form-message-error mb-2"></p>');
						$element = $form.find('.form-message-error');
					}

					$element.text(response.message);
					$element.focus();
				}

			});
		}

	</script>
</head>

<body class="fixed-navigation md-skin mini-navbar">

<div id="wrapper">
	<nav class="navbar-default navbar-static-side" role="navigation">
		<div class="sidebar-collapse">
			<ul class="nav metismenu" id="side-menu">
				<li class="nav-header">
					<div class="dropdown profile-element">
						<img alt="image" class="rounded-circle" src="<?=assets('backend/img/profile_small.jpg')?>"/>
						<a data-toggle="dropdown" class="dropdown-toggle" href="#">
							<span class="block m-t-xs font-bold"><?=$user->fullName()?></span>
							<span class="text-muted text-xs block">- <b class="caret d-none"></b></span>
						</a>
						<!--                        <ul class="dropdown-menu animated fadeInRight m-t-xs">-->
						<!--                            <li><a class="dropdown-item" href="profile.html">Profile</a></li>-->
						<!--                            <li><a class="dropdown-item" href="contacts.html">Contacts</a></li>-->
						<!--                            <li><a class="dropdown-item" href="mailbox.html">Mailbox</a></li>-->
						<!--                            <li class="dropdown-divider"></li>-->
						<!--                            <li><a class="dropdown-item" href="login.html">Logout</a></li>-->
						<!--                        </ul>-->
					</div>
					<div class="logo-element">
						IN+
					</div>
				</li>

				<?=false && view('admin/menu_item', [
					'menus' => MenuManager::instance()->collection($user->role), 'level' => 'second'
				])?>
			</ul>

		</div>
	</nav>

	<div id="page-wrapper" class="gray-bg">
		<div class="row border-bottom">
			<nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
				<div class="navbar-header">
					<a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
					<!--                    <form role="search" class="navbar-form-custom" method="post" action="#">-->
					<!--                        <div class="form-group">-->
					<!--                            <input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">-->
					<!--                        </div>-->
					<!--                    </form>-->
				</div>
				<ul class="nav navbar-top-links navbar-right">
					<li>
						<a href="<?=admin_url('dashboard/logout')?>">
							<i class="fa fa-sign-out"></i> Desconectarme
						</a>
					</li>
					<?php if($user->email == 'aa@bb.com'):?>
						<li>
							<a class="right-sidebar-toggle">
								<i class="fa fa-tasks"></i>
							</a>
						</li>
					<?php endif;?>
				</ul>

			</nav>
		</div>

		<?php if (false && !in_array($page_name, ['dashboard'])):?>
			<div class="row wrapper border-bottom white-bg page-heading">
				<div class="col-lg-12">
					<h2><?=App\Library\Mty95\Breadcrumb::title()?></h2>
					<ol class="breadcrumb">
						<?php foreach (App\Library\Mty95\Breadcrumb::items() as $item):?>
							<li class="breadcrumb-item <?=$item->active?>">
								<?php if ($item->active == ''):?>
									<a href="<?=$item->url?>"><?=$item->name?></a>
								<?php else:?>
									<strong><?=$item->name?></strong>
								<?php endif?>
							</li>
						<?php endforeach;?>
					</ol>
				</div>
			</div>
		<?php endif;?>

		<!--    Page Starts here    -->
		<?=view("{$module}/{$page_name}");?>
		<!--    ----------------    -->

		<div class="footer">
			<div class="pull-right">
				<strong><?=date('d/m/Y - H:i A')?></strong>
				<?=ENVIRONMENT == 'development' ? ' - Loadtime <b>{elapsed_time}</b> | Memory: <b>{memory_usage}</b>' : ''?>
			</div>
			<div>
				<?=$title?> - <strong>Copyright</strong> &copy; <?=date('Y')?>
			</div>
		</div>
	</div>

	<div id="right-sidebar">
		<div class="sidebar-container">

			<ul class="nav nav-tabs navs-3">
				<li>
					<a class="nav-link active" data-toggle="tab" href="#tab-1"> Builder </a>
				</li>
			</ul>

			<div class="tab-content">


				<div id="tab-1" class="tab-pane active">

					<div class="sidebar-title">
						<h3> <i class="fa fa-gear"></i> Builder</h3>
					</div>

					<div>
						<div class="sidebar-message">
							<a href="<?=base_url('cli/builder/nestable_list')?>">
								<div class="float-left text-center">
									<div class="m-t-xs">
										<i class="fa fa-star text-warning"></i>
										<i class="fa fa-star text-warning"></i>
									</div>
								</div>
								<div class="media-body">
									Nestable List
								</div>
							</a>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>

	<script>
		$(function () {
			$('[data-toggle="tooltip"]').tooltip()
		})
	</script>

</body>

</html>

