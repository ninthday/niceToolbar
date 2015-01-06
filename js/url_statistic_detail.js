/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
    // 讓 form.submit 的預設事件無效
    $("form").submit(function (event) {
        event.preventDefault();
    });

    var initGraph = function (url) {
        d3.json(url, function (error, data) {
            nv.addGraph(function () {
                var chart = nv.models.linePlusBarChart()
                        .margin({top: 30, right: 60, bottom: 50, left: 70})
                        //We can set x data accessor to use index. Reason? So the bars all appear evenly spaced.
                        .x(function (d, i) {
                            return i
                        })
                        .y(function (d, i) {
                            return d[1]
                        })
                        ;

                chart.xAxis.tickFormat(function (d) {
                    var dx = data[0].values[d] && data[0].values[d][0] || 0;
                    return d3.time.format("%Y-%m-%d")(new Date(dx))
                });

                chart.y1Axis
                        .tickFormat(d3.format(',f'));

                chart.y2Axis
                        .tickFormat(d3.format(',f'));

                chart.bars.forceY([0]);
                chart.lines.forceY([0]);

                d3.select('#chart svg')
                        .datum(data)
                        .transition()
                        .duration(0)
                        .call(chart);

                nv.utils.windowResize(chart.update);

                return chart;
            });

        });
    };

    var redrawGraph = function (url, resolution) {
        $("#chart svg").empty();
        d3.json(url, function (error, data) {
            nv.addGraph(function () {
                var chart = nv.models.linePlusBarChart()
                        .margin({top: 30, right: 60, bottom: 50, left: 70})
                        //We can set x data accessor to use index. Reason? So the bars all appear evenly spaced.
                        .x(function (d, i) {
                            return i
                        })
                        .y(function (d, i) {
                            return d[1]
                        })
                        ;


                chart.xAxis.tickFormat(function (d) {
                    var dx = data[0].values[d] && data[0].values[d][0] || 0;
                    if (resolution === "per-day") {
                        return d3.time.format("%Y-%m-%d")(new Date(dx));
                    } else if (resolution === "per-hour") {
                        return d3.time.format("%Y-%m-%d %H:00")(new Date(dx));
                    }

                });

                chart.y1Axis
                        .tickFormat(d3.format(',f'));

                chart.y2Axis
                        .tickFormat(d3.format(',f'));

                chart.bars.forceY([0]);
                chart.lines.forceY([0]);

                d3.select('#chart svg')
                        .datum(data)
                        .transition()
                        .duration(0)
                        .call(chart);

                nv.utils.windowResize(chart.update);

                return chart;
            });

        });
    };

    var showDomainList = function (dataset, bday, eday) {
        $.ajaxSetup({
            cache: false
        });

        var jqxhr = $.getJSON('ajax_top_domain.php', {
            ds: dataset,
            bd: bday,
            ed: eday
        });

        jqxhr.success(function (data) {
            if (data.rsStat) {
                buildDomainList(data.rsContents);
            } else {
                showErrorMsg(data.rsContents);
            }
        });
    };
    
    var buildDomainList = function (aryLists) {
        var strList = '';
        for (var i = 0; i < aryLists.length; i++) {
            strList += '<tr><td>' + (i+1).toString() + '.</td>';
            strList += '<td>' + aryLists[i].domain + '</td>';
            strList += '<td>' + aryLists[i].CNT + '</td></tr>';
        }
        
        $("#dnlist tbody").children().remove();
        $("#dnlist tbody").append(strList);
    };
    
    var showURLList = function (dataset, bday, eday) {
        $.ajaxSetup({
            cache: false
        });

        var jqxhr = $.getJSON('ajax_top.php', {
            op: 'url',
            ds: dataset,
            bd: bday,
            ed: eday
        });

        jqxhr.success(function (data) {
            if (data.rsStat) {
                buildURLList(data.rsContents);
            } else {
                showErrorMsg(data.rsContents);
            }
        });
    };
    
    var buildURLList = function (aryLists) {
        var strList = '';
        for (var i = 0; i < aryLists.length; i++) {
            strList += '<tr><td>' + (i+1).toString() + '.</td>';
            strList += '<td><a href="' + aryLists[i].url_followed + '" target="_blank">' + aryLists[i].url_followed + '</a></td>';
            strList += '<td>' + aryLists[i].CNT + '</td></tr>';
        }
        
        $("#urllist tbody").children().remove();
        $("#urllist tbody").append(strList);
    };
    
    var showPosterList = function (dataset, bday, eday) {
        $.ajaxSetup({
            cache: false
        });

        var jqxhr = $.getJSON('ajax_top.php', {
            op: 'poster',
            ds: dataset,
            bd: bday,
            ed: eday
        });

        jqxhr.success(function (data) {
            if (data.rsStat) {
                buildPosterList(data.rsContents);
            } else {
                showErrorMsg(data.rsContents);
            }
        });
    };
    
    var buildPosterList = function (aryLists) {
        var strList = '';
        for (var i = 0; i < aryLists.length; i++) {
            strList += '<tr><td>' + (i+1).toString() + '.</td>';
            strList += '<td><a href="https://twitter.com/' + aryLists[i].from_user_name + '" target="_blank">' + aryLists[i].from_user_name + '</a></td>';
            strList += '<td>' + aryLists[i].CNT + '</td></tr>';
        }
        
        $("#ptlist tbody").children().remove();
        $("#ptlist tbody").append(strList);
    };
    
//    initGraph("http://ff-proj.cs.nccu.edu.tw/~jeffy/ffToolbar/test-pdo.php");

    $(":button").click(function () {
        var ds_name = $("span[name='dataset']").text();
        var bday = $("input[name='startday']").val();
        var eday = $("input[name='endday']").val();
        var res = $("input[name='resolution']:checked").val();
        var base_url = "http://ff-proj.cs.nccu.edu.tw/~jeffy/ffToolbar/ajax_url_freq.php?" +
                "ds=" + ds_name + "&bd=" + bday + "&ed=" + eday + "&res=" + res;
        redrawGraph(base_url, res);
        showDomainList(ds_name, bday, eday);
        showURLList(ds_name, bday, eday);
        showPosterList(ds_name, bday, eday);
    });
});
