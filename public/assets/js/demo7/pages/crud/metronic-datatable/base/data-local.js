'use strict';
// Class definition

var KTDatatableDataLocalDemo = function() {
	// Private functions

	// demo initializer
	var demo = function() {

		var KTdt = document.querySelector('.kt-datatable');
		var dataJSONArray = JSON.parse(KTdt.dataset.allSurveys);

		//var dataJSONArray = JSON.parse('[{"RecordID":1,"name":"Survey 1","description":"This is a description"}]');

		var datatable = $('.kt-datatable').KTDatatable({
			// datasource definition
			data: {
				type: 'local',
				source: dataJSONArray,
				pageSize: 10,
			},

			// layout definition
			layout: {
				scroll: false, // enable/disable datatable scroll both horizontal and vertical when needed.
				// height: 450, // datatable's body's fixed height
				footer: false, // display/hide footer
			},

			// column sorting
			sortable: true,

			pagination: true,

			search: {
				input: $('#generalSearch'),
			},

			// columns definition
			columns: [
				{
					field: 'RecordID',
					title: '#',
					sortable: false,
					width: 20,
					type: 'number',
					selector: {class: 'kt-checkbox--solid'},
					textAlign: 'center',
				}, {
					field: 'name',
					title: 'Survey Name',
				}, {
					field: 'description',
					title: 'Description'
				}, {
					field: 'format',
					title: 'Format'
				}, {
					field: 'user_id',
					title: 'User ID',
				}, {
					field: 'questions',
					title: 'Questions',
				}, {
					field: 'Actions',
					title: 'Edit',
					sortable: false,
					width: 110,
					overflow: 'visible',
					autoHide: false,
					template: function(dataJSONArray) {

						//{{ path('admin_delete_survey', {id: ${dataJSONArray.id}}  )  }}
						return `\
						<a href="/survey_api/public/index.php/admin/survey/${dataJSONArray.id}/edit" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit details">\
							<i class="la la-edit"></i>\
						</a>\
						<button data-toggle="modal" data-target="#kt_modal_6" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Delete">\
							<i class="la la-trash"></i>\
						</button>\
						<!-- Modal -->\
						<div class="modal fade" id="kt_modal_6" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">\
							<div class="modal-dialog modal-dialog-centered" role="document">\
								<div class="modal-content">\
									<div class="modal-header">\
										<h5 class="modal-title" id="exampleModalLongTitle">Are you sure you want to delete survey: "${dataJSONArray.name}"?</h5>\
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">\
										</button>\
									</div>\
									<div class="modal-body">\
										<p>If you click on 'Delete', your survey will be permanently deleted and all the relevant data will be removed as well. Make sure you really want to delete, or else you will not be able to restore it.</p>\
									</div>\
									<div class="modal-footer">\
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>\
										<a href="/survey_api/public/index.php/admin/survey/${dataJSONArray.id}/delete">\
											<button type="button" class="btn btn-primary">Delete</button>\
										</a>\
									</div>\
								</div>\
							</div>\
						</div>\
					`;
					},
				}],
		});

		$('#kt_form_status').on('change', function() {
			datatable.search($(this).val().toLowerCase(), 'Status');
		});

		$('#kt_form_type').on('change', function() {
			datatable.search($(this).val().toLowerCase(), 'Type');
		});

		$('#kt_form_status,#kt_form_type').selectpicker();

	};

	return {
		// Public functions
		init: function() {
			// init dmeo
			demo();
		},
	};
}();

jQuery(document).ready(function() {
	KTDatatableDataLocalDemo.init();
});