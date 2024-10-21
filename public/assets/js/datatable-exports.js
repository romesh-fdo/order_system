export function getExportButtons(customFilename, customTitle, excludeColumnIndices) {
    return [
        {
            extend: 'csv',
            text: 'CSV',
            className: 'btn btn-primary',
            filename: customFilename,
            exportOptions: {
                columns: function (idx, data, node) {
                    // Exclude specified column indices
                    return !excludeColumnIndices.includes(idx);
                },
                modifier: {
                    selected: true
                }
            }
        },
        {
            extend: 'excel',
            text: 'Excel',
            className: 'btn btn-success',
            filename: customFilename,
            title: customTitle,
            exportOptions: {
                columns: function (idx, data, node) {
                    // Exclude specified column indices
                    return !excludeColumnIndices.includes(idx);
                },
                modifier: {
                    selected: true
                }
            }
        },
        // {
        //     extend: 'pdf',
        //     text: 'PDF',
        //     className: 'btn btn-danger',
        //     filename: customFilename,
        //     title: customTitle,
        //     exportOptions: {
        //         columns: function (idx, data, node) {
        //             // Exclude specified column indices
        //             return !excludeColumnIndices.includes(idx);
        //         },
        //         modifier: {
        //             selected: true
        //         }
        //     },
        //     customize: function (doc) {
        //         doc.header = function () { return false; };
        //         doc.footer = function () { return false; };

        //         if (doc.content && doc.content.length > 0) {
        //             const table = doc.content.find(item => item.table);

        //             if (table && table.table && Array.isArray(table.table.widths)) {
        //                 table.layout = {
        //                     hLineColor: function (i, node) {
        //                         return '#ddd';
        //                     },
        //                     vLineColor: function (i, node) {
        //                         return '#ddd';
        //                     },
        //                     paddingLeft: function (i) {
        //                         return i === 0 ? 0 : 8;
        //                     },
        //                     paddingRight: function (i, node) {
        //                         return i === node.table.widths.length - 1 ? 0 : 8;
        //                     },
        //                     paddingTop: function () {
        //                         return 4;
        //                     },
        //                     paddingBottom: function () {
        //                         return 4;
        //                     }
        //                 };
        //             }
        //         }

        //         doc.styles.title = {
        //             color: '#141824',
        //             fontSize: '20',
        //             alignment: 'left'
        //         };
        //         doc.styles.tableHeader = {
        //             fillColor: '#141824',
        //             color: 'white',
        //             alignment: 'center'
        //         };
        //         doc.styles.tableBodyOdd = {
        //             fillColor: '#f3f3f3'
        //         };
        //         doc.styles.tableBodyEven = {
        //             fillColor: '#ffffff'
        //         };
        //     }
        // },
        {
            extend: 'print',
            text: 'Print / PDF',
            className: 'btn btn-info',
            title: customTitle,
            exportOptions: {
                columns: function (idx, data, node) {
                    // Exclude specified column indices
                    return !excludeColumnIndices.includes(idx);
                },
                modifier: {
                    selected: true
                }
            },
            customize: function (win) {
                $(win.document.body)
                    .prepend('<span style="text-align:center; font-size:30px; margin-bottom:10px">' + customTitle + '</span>')
                    .css('font-size', '10pt')
                    .find('h1').remove()
                    .find('h2').remove()
                    .find('h3').remove()
                    .find('footer').remove();
                $(win.document.body).find('table')
                    .addClass('compact')
                    .css('font-size', 'inherit');
            }
        }
    ];
}

window.getExportButtons = getExportButtons;
