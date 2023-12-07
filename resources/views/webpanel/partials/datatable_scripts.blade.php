@push('css')
    <link rel="stylesheet" href="{{ asset('webpanel/css/plugins/datatables.bundle.css') }}">
    <link rel="stylesheet" href="{{ asset('webpanel/css/plugins/sweetalert.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.2.0/css/rowReorder.dataTables.min.css">
@endpush

@push('js')
    <script src="{{ asset('webpanel/js/plugins/datatables.bundle.js') }}"></script>
    <script src="{{ asset('webpanel/js/plugins/datatable_buttons.server-side.js') }}"></script>
    <script src="{{ asset('webpanel/js/plugins/sweetalert.js') }}"></script>
    <script src="https://cdn.datatables.net/rowreorder/1.2.0/js/dataTables.rowReorder.min.js"></script>
    {!! $dataTable->scripts() !!}
    @if(!empty($assignedDataTable))
    {!! $assignedDataTable->scripts() !!}
    @endif
    @if(!empty($nonAssignedDataTable))
    {!! $nonAssignedDataTable->scripts() !!}
    @endif
    <script type="text/javascript">
        var baseurl = "{{ url('/') }}";

        $(document).ready(function () {
            $('.dataTable').find('thead tr:eq(0)').addClass('text-start text-dark fw-black fs-7 text-uppercase gs-0');
            $('.dataTable').find('tbody').addClass('text-dark fw-bold');
            $('body').on('click', '.btn-delete', function () {
                $this = $(this);
                swal({
                    title: "Are you sure?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#dc3545",
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true,
                }, function () {
                    $.ajax({
                        method: 'DELETE',
                        data:{
                            "_token": "{{ csrf_token() }}",
                        },
                        url:  $this.data('model') + '/' + $this.data('id'),
                        success: function (result) {
                            if(result.status) {
                                swal("Deleted!", result.message, "success");
                            } else {
                                swal("Not Deleted!", result.message, "error");
                            }
                            $('#datatable-buttons').DataTable().draw(false);
                        },
                        error: function (jqXhr) {
                            swalError(jqXhr);
                        }
                    });
                });
            });

            $('body').on('change', '.toggle', function() {

                var checked = $(this).prop('checked') == true ? 1 : 0;
                var id = $(this).attr("data-id");
                var url = $(this).attr("data-url");
                $.ajax({
                    url: baseurl+'/'+url+'/'+id,
                    type: "POST",
                    async: false,
                    data: {
                        "id": id,
                        "status": checked,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function() {
                        if (checked == 1) {
                            swal("Active", "The status has been updated to Active.", "success");
                        } else {
                            swal("InActive", "The status has been updated to InActive.","success");
                        }
                    }
                });

            });
        });

        // To sort the rows in the table
        function initSortable(model) {
            var url = model + '/reorder';
            var table = $('#datatable-buttons').DataTable();
            // $('.dataTable').addClass('loading');
            table.rowReorder.disable();


            table.on('row-reorder', function (e, diff) {
                const allRows = [];
                const rowData = table.rows().data();

                for (let i = 0; i < rowData.length; i++) {
                    allRows.push({
                    id: rowData[i].id,
                    position: i
                    });
                }

                for (let i = 0; i < diff.length; i++) {
                    const row = diff[i].node;
                    const rowData = table.row(row).data();

                    const rowToUpdate = allRows.find(rowObj => rowObj.id === rowData.id);
                    if (rowToUpdate) {
                    rowToUpdate.position = diff[i].newPosition;
                    }
                }

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        'data': allRows,
                        "_token": "{{ csrf_token() }}",
                    },
                    beforeSend: function () {
                        $('.dataTable').addClass('loading');
                    },
                    complete: function () {
                        $('.dataTable').removeClass('loading');
                    },
                    success: function () {
                        table.ajax.reload();
                    },
                    error: function (jqXhr) {
                        swalError(jqXhr);
                    }
                });
            });

        }

    </script>
@endpush
