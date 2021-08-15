/*
 * Project Wise Statistics
 * */
(function ($) {
    "use strict"

    Morris.Bar({
        element: 'project-wise-statistics',
        data: project_wise_statistics,
        xkey: 'chart_label',
        ykeys: ['income', 'expense', 'balance'],
        labels: ['INCOME', 'EXPENSE', 'BALANCE'],
        barColors: ['#36b37e', '#ffab00', 'rgba(132, 38, 255,1)'],
        hideHover: 'auto',
        fillOpacity: 0.6,
        resize: true,
        hoverCallback: function (index, options, content, row) {
            var hover = '<div class="morris-hover-row-label">' + row.hover_label + '</div><div class="morris-hover-point"> INCOME: ' + row.income_hover + '</div><div class="morris-hover-point"> EXPENSE: ' + row.expense_hover + '</div><div class="morris-hover-point"> <strong>BALANCE: ' + row.balance_hover + '</strong></div>';
            return hover;
        },
        xLabelMargin: 10,
    });


})(jQuery);
/*
 * Monthly Project Statistics
 * */
(function ($) {
    "use strict"

    var ctx = document.getElementById("monthly-project-statistics");
    ctx.height = 280;
    new Chart(ctx, {
        type: 'line',
        data: monthly_project_statistics,
        options: {
            responsive: !0,
            maintainAspectRatio: false,
            tooltips: {
                mode: 'index',
                enabled: false,
                intersect: false,
                titleFontSize: 12,
                titleFontFamily: 'Poppins',
                bodyFontFamily: 'Poppins',
                custom: function (tooltipModel) {
                    // Tooltip Element
                    var tooltipEl = document.getElementById('chartjs-tooltip');

                    // Create element on first render
                    if (!tooltipEl) {
                        tooltipEl = document.createElement('div');
                        tooltipEl.id = 'chartjs-tooltip';
                        tooltipEl.innerHTML = '<table class="monthly_stats_chart_tooltip"></table>';
                        document.body.appendChild(tooltipEl);
                    }

                    // Hide if no tooltip
                    if (tooltipModel.opacity === 0) {
                        tooltipEl.style.opacity = 0;
                        return;
                    }

                    // Set caret Position
                    tooltipEl.classList.remove('above', 'below', 'no-transform');

                    if (tooltipModel.yAlign) {
                        tooltipEl.classList.add(tooltipModel.yAlign);
                    } else {
                        tooltipEl.classList.add('no-transform');
                    }

                    function getBody(bodyItem) {
                        return bodyItem.lines;
                    }

                    function trimString(x) {
                        return x.replace(/^\s+|\s+$/gm, '');
                    }

                    // Set Text
                    if (tooltipModel.body) {
                        var titleLines = tooltipModel.title || [];
                        var bodyLines = tooltipModel.body.map(getBody);
                        var innerHtml = '<thead>';
                        titleLines.forEach(function (title) {
                            innerHtml += '<tr><th colspan="2">' + title + '</th></tr>';
                        });
                        innerHtml += '</thead><tbody>';

                        bodyLines.forEach(function (body, i) {
                            var bodyString = body[0].split(':');
                            var title = trimString(bodyString[0]);
                            var value = 'Rs.' + number_format(trimString(bodyString[1]), 2, '.', ',');

                            var colors = tooltipModel.labelColors[i];
                            var style = 'background:' + colors.backgroundColor;
                            style += '; border-color:' + colors.borderColor;
                            style += ';';

                            var span = '<span class="color_box" style="' + style + '"></span>';
                            innerHtml += '<tr><td>' + span + title + '</td><td>' + value + '</td></tr>';
                        });

                        innerHtml += '</tbody>';
                        var tableRoot = tooltipEl.querySelector('table');
                        tableRoot.innerHTML = innerHtml;
                    }

                    // `this` will be the overall tooltip
                    var position = this._chart.canvas.getBoundingClientRect();

                    // Display, position, and set styles for font
                    tooltipEl.style.opacity = 1;
                    tooltipEl.style.position = 'absolute';
                    tooltipEl.style.left = position.left + window.pageXOffset + tooltipModel.caretX + 'px';
                    tooltipEl.style.top = position.top + window.pageYOffset + tooltipModel.caretY + 'px';
                    tooltipEl.style.fontFamily = tooltipModel._bodyFontFamily;
                    tooltipEl.style.fontSize = tooltipModel.bodyFontSize + 'px';
                    tooltipEl.style.fontStyle = tooltipModel._bodyFontStyle;
                    tooltipEl.style.padding = tooltipModel.yPadding + 'px ' + tooltipModel.xPadding + 'px';
                    tooltipEl.style.pointerEvents = 'none';
                    tooltipEl.style.transition = '0.23s';
                }
            },
            legend: {
                display: true,
                position: 'top',
                labels: {
                    usePointStyle: true,
                    fontFamily: 'Poppins',
                },
            },
            scales: {
                xAxes: [{
                    display: true,
                    gridLines: {
                        display: true,
                        drawBorder: false
                    },
                }],
                yAxes: [{
                    display: true,
                    gridLines: {
                        display: true,
                        drawBorder: false
                    },
                }]
            },
            title: {
                display: false,
            },
        }
    });


})(jQuery);

const my_tasks = new PerfectScrollbar('#my_tasks');
const assigned_tasks = new PerfectScrollbar('#assigned_tasks');
const monthly_report = new PerfectScrollbar('#monthly_report');
const monthly_due_report = new PerfectScrollbar('#monthly_due_report');