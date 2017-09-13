'use strict';
var yearRingChart   = dc.pieChart("#chart-ring-year"),
    spenderRowChart = dc.rowChart("#chart-row-spenders");
var data1 = [
    {Name: 'Ben', Spent: 330, Year: 2014, 'total':1},
    {Name: 'Aziz', Spent: 1350, Year: 2012, 'total':2},
    {Name: 'Vijay', Spent: 440, Year: 2014, 'total':2},
    {Name: 'Jarrod', Spent: 555, Year: 2015, 'total':1},
];
// set crossfilter with first dataset
var xfilter = crossfilter(data1),
    yearDim  = xfilter.dimension(function(d) {return +d.Year;}),
    spendDim = xfilter.dimension(function(d) {return Math.floor(d.Spent/10);}),
    nameDim  = xfilter.dimension(function(d) {return d.Name;}),

    spendPerYear = yearDim.group().reduceSum(function(d) {return +d.Spent;}),
    spendPerName = nameDim.group().reduceSum(function(d) {return +d.Spent;});
function render_plots(){
    yearRingChart
        .width(200).height(200)
        .dimension(yearDim)
        .group(spendPerYear)
        .innerRadius(50);
    spenderRowChart
        .width(250).height(200)
        .dimension(nameDim)
        .group(spendPerName)
        .elasticX(true);
    dc.renderAll();
}
render_plots();