(function ($, DataTable) {
    "use strict";

    var currentPath = window.location.pathname;
    var routeName = currentPath.split('/').pop();

    var _buildParams = function (dt, action) {
        var params = dt.ajax.params();
        params.action = action;
        params._token = $('meta[name="csrf-token"]').attr('content');

        return params;
    };

    var _downloadFromUrl = function (url, params) {
        var postUrl = url + '/export';
        var xhr = new XMLHttpRequest();
        xhr.open('POST', postUrl, true);
        xhr.responseType = 'arraybuffer';
        xhr.onload = function () {
            if (this.status === 200) {
                var filename = "";
                var disposition = xhr.getResponseHeader('Content-Disposition');
                if (disposition && disposition.indexOf('attachment') !== -1) {
                    var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                    var matches = filenameRegex.exec(disposition);
                    if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                }
                var type = xhr.getResponseHeader('Content-Type');

                var blob = new Blob([this.response], {type: type});
                if (typeof window.navigator.msSaveBlob !== 'undefined') {
                    // IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                    window.navigator.msSaveBlob(blob, filename);
                } else {
                    var URL = window.URL || window.webkitURL;
                    var downloadUrl = URL.createObjectURL(blob);

                    if (filename) {
                        // use HTML5 a[download] attribute to specify filename
                        var a = document.createElement("a");
                        // safari doesn't support this yet
                        if (typeof a.download === 'undefined') {
                            window.location = downloadUrl;
                        } else {
                            a.href = downloadUrl;
                            a.download = filename;
                            document.body.appendChild(a);
                            a.click();
                        }
                    } else {
                        window.location = downloadUrl;
                    }

                    setTimeout(function () {
                        URL.revokeObjectURL(downloadUrl);
                    }, 100); // cleanup
                }
            }
        };
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send($.param(params));
    };

    var _buildUrl = function(dt, action) {
        var url = dt.ajax.url() || '';
        var params = dt.ajax.params();
        params.action = action;

        if (url.indexOf('?') > -1) {
            return url + '&' + $.param(params);
        }
        
        return url + '?' + $.param(params);
    };

    DataTable.ext.buttons.excel = {
        className: 'buttons-excel',

        text: function (dt) {
            return '<i class="fa fa-file-excel-o"></i> ' + dt.i18n('buttons.excel', 'Excel');
        },

        action: function (e, dt, button, config) {
            $('.export_all_csv').trigger('click');
            // var url = _buildUrl(dt, 'excel');
            // window.location = url;
        }
    };

    DataTable.ext.buttons.postExcel = {
        className: 'buttons-excel',

        text: function (dt) {
            return '<i class="fa fa-file-excel-o"></i> ' + dt.i18n('buttons.excel', 'Excel');
        },

        action: function (e, dt, button, config) {
            var url = dt.ajax.url() || window.location.href;
            var params = _buildParams(dt, 'excel');

            _downloadFromUrl(url, params);
        }
    };

    DataTable.ext.buttons.export = {
        extend: 'collection',

        className: 'buttons-export',

        text: function (dt) {
            return '<i class="fa fa-download"></i> ' + dt.i18n('buttons.export', 'Export') + '&nbsp;<span class="caret"/>';
        },

        buttons: ['excel', 'pdf']
    };

    DataTable.ext.buttons.csv = {
        className: 'buttons-csv',

        text: function (dt) {
            return '<i class="fa fa-file-excel-o"></i> ' + dt.i18n('buttons.csv', 'CSV');
        },

        action: function (e, dt, button, config) {
            var url = _buildUrl(dt, 'csv');
            window.location = url;
        }
    };

    DataTable.ext.buttons.postCsv = {
        className: 'buttons-csv',

        text: function (dt) {
            return '<i class="fa fa-file-excel-o"></i> ' + dt.i18n('buttons.csv', 'CSV');
        },

        action: function (e, dt, button, config) {
            var url = dt.ajax.url() || window.location.href;
            var params = _buildParams(dt, 'csv');

            _downloadFromUrl(url, params);
        }
    };

    DataTable.ext.buttons.pdf = {
        className: 'buttons-pdf',

        text: function (dt) {
            return '<i class="fa fa-file-pdf-o"></i> ' + dt.i18n('buttons.pdf', 'PDF');
        },

        action: function (e, dt, button, config) {
            $('.export_all_pdf').trigger('click');
            // var url = _buildUrl(dt, 'pdf');
            // window.location = url;
        }
    };

    DataTable.ext.buttons.postPdf = {
        className: 'buttons-pdf',

        text: function (dt) {
            return '<i class="fa fa-file-pdf-o"></i> ' + dt.i18n('buttons.pdf', 'PDF');
        },

        action: function (e, dt, button, config) {
            var url = dt.ajax.url() || window.location.href;
            var params = _buildParams(dt, 'pdf');

            _downloadFromUrl(url, params);
        }
    };

    DataTable.ext.buttons.print = {
        className: 'buttons-print',

        text: function (dt) {
            return  '<i class="fa fa-print"></i> ' + dt.i18n('buttons.print', 'Print');
        },

        action: function (e, dt, button, config) {
            var url = _buildUrl(dt, 'print');
            window.location = url;
        }
    };

    DataTable.ext.buttons.reset = {
        className: 'buttons-reset',

        text: function (dt) {
            return '<i class="fa fa-undo"></i> ' + dt.i18n('buttons.reset', 'Reset');
        },

        action: function (e, dt, button, config) {
            dt.search('');
            dt.columns().search('');
            dt.draw();
        }
    };

    DataTable.ext.buttons.reload = {
        className: 'buttons-reload',

        text: function (dt) {
            return '<i class="fa fa-refresh"></i> ' + dt.i18n('buttons.reload', 'Reload');
        },

        action: function (e, dt, button, config) {
            dt.draw(false);
        }
    };

    DataTable.ext.buttons.create = {
        className: 'buttons-create',

        text: function (dt) {
            return '<i class="fa fa-plus"></i> ' + dt.i18n('buttons.create', 'Create');
        },

        action: function (e, dt, button, config) {
            var appendUrl='';

            if(routeName== 'formfields'){
                appendUrl='?form_category_id='+window.form_category_id;
            }
            
            window.location = window.location.href.replace(/\/+$/, "").split('?')[0]  + '/create'+appendUrl;
        }
    };

    DataTable.ext.buttons.back = {
        className: 'buttons-reset',

        text: function (dt) {
            return '<i class="fa fa-arrow-left"></i> ' + dt.i18n('buttons.back', 'Back');
        },

        action: function (e, dt, button, config) {
            var url='';

            if(routeName== 'formfields'){
                url='formcategories';
            }
            else if(routeName== 'classformcategories'){
                url='formcategories';
            }
            else if(routeName== 'classadditionalfields'){
                url='classformcategories?form_category_id='+window.form_category_id;
            }
            
            window.location = url;
        }
    };
    
    DataTable.ext.buttons.bulk = {
        className: 'buttons-bulk',

        text: function (dt) {
            return '<i class="fa fa-plus"></i> ' + dt.i18n('buttons.bulk', 'bulk');
        },

        action: function (e, dt, button, config) {
//             dt.draw(false);
        }
    };

    DataTable.ext.buttons.schedule = {
        className: 'buttons-schedule',

        text: function (dt) {
            return  dt.i18n('buttons.schedule', 'schedule');
        },

        action: function (e, dt, button, config) {
//             dt.draw(false);
        }
    };


    if (typeof DataTable.ext.buttons.copyHtml5 !== 'undefined') {
        $.extend(DataTable.ext.buttons.copyHtml5, {
            text: function (dt) {
                return '<i class="fa fa-copy"></i> ' + dt.i18n('buttons.copy', 'Copy');
            }
        });
    }

    if (typeof DataTable.ext.buttons.colvis !== 'undefined') {
        $.extend(DataTable.ext.buttons.colvis, {
            text: function (dt) {
                return '<i class="fa fa-eye"></i> ' + dt.i18n('buttons.colvis', 'Column visibility');
            }
        });
    }
})(jQuery, jQuery.fn.dataTable);
