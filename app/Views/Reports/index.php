<?php $title = 'Buisness Intelligence'; include(APPDIR.'views/layouts/header.php');

use App\Helpers\Session;
use App\Helpers\Url;
?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

<?php if(Session::get('is_admin') == 0){
    Url::redirect('/404');
}?>

    // Load the Visualization API and the piechart package.
    google.charts.load('current', {'packages':['corechart', 'table']});
    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawLocationChart);
    google.charts.setOnLoadCallback(drawManagerChart);
    google.charts.setOnLoadCallback(drawPositionChart);
    function drawChart(chartName) {
        var req = new XMLHttpRequest;
        var inputName = document.getElementById("nameText").value;
        req.open('GET', "/reports/get/" + inputName, false);  
        // req.responseType = 'json';
        req.send(null);
        var responseObj = JSON.parse(req.response)
        var rows = responseObj.map(x => ({
            "c": [
                {
                    "v": x["awardName"]
                },
                {
                    "v": x["count"]
                }
            ]
        }));
        
        jsonData = {
            "cols": [
                {"id":"","label":"Award Type","pattern":"","type":"string"},
                {"id":"","label":"Number of Awards","pattern":"","type":"number"}
            ],
            "rows": rows
        }
        // Create our data table out of JSON data loaded from server.
        var data = new google.visualization.DataTable(jsonData);
        var table = new google.visualization.Table(document.getElementById('table_div'));
        table.draw(data, {showRowNumber: true, width: '100%', height: '100%'});
        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById(chartName));
        chart.draw(data, {title: 'Types of awards won by: ' + inputName, width: 400, height: 240});
        
    }
    function drawLocationChart()
    {
        var req = new XMLHttpRequest;
        req.open('GET', "/reports/getLocation/", false);  
        req.send(null);
        var responseObj = JSON.parse(req.response)
        var rows = responseObj.map(x => ({
            "c": [
                {
                    "v": x["awardeeLocation"]
                },
                {
                    "v": x["count"]
                }
            ]
        }));
        
        jsonData = {
            "cols": [
                {"id":"","label":"Award Location","pattern":"","type":"string"},
                {"id":"","label":"Number of Awards","pattern":"","type":"number"}
            ],
            "rows": rows
        }
        // Create our data table out of JSON data loaded from server.
        var data = new google.visualization.DataTable(jsonData);
        var table = new google.visualization.Table(document.getElementById('table_div2'));
        table.draw(data, {showRowNumber: true, width: '100%', height: '100%'});
        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.BarChart(document.getElementById('chart_div2'));
        chart.draw(data, {title:"Total number of awards each location has", width: 400, height: 240});
    }
    function drawManagerChart()
    {
        var req = new XMLHttpRequest;
        req.open('GET', "/reports/getManager/", false);  
        req.send(null);
        var responseObj = JSON.parse(req.response)
        var rows = responseObj.map(x => ({
            "c": [
                {
                    "v": x["awardeeManager"]
                },
                {
                    "v": x["count"]
                }
            ]
        }));
        
        jsonData = {
            "cols": [
                {"id":"","label":"Manager","pattern":"","type":"string"},
                {"id":"","label":"Number of Awards","pattern":"","type":"number"}
            ],
            "rows": rows
        }
        // Create our data table out of JSON data loaded from server.
        var data = new google.visualization.DataTable(jsonData);
        var table = new google.visualization.Table(document.getElementById('table_div3'));
        table.draw(data, {showRowNumber: true, width: '100%', height: '100%'});
        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.BarChart(document.getElementById('chart_div3'));
        chart.draw(data, {title:"Total number of awards by manager", width: 400, height: 240});
    }
    function drawPositionChart()
    {
        var req = new XMLHttpRequest;
        req.open('GET', "/reports/getPosition/", false);  
        req.send(null);
        var responseObj = JSON.parse(req.response)
        var rows = responseObj.map(x => ({
            "c": [
                {
                    "v": x["awardeePosition"]
                },
                {
                    "v": x["count"]
                }
            ]
        }));
        
        jsonData = {
            "cols": [
                {"id":"","label":"Position/Role","pattern":"","type":"string"},
                {"id":"","label":"Number of Awards","pattern":"","type":"number"}
            ],
            "rows": rows
        }
        // Create our data table out of JSON data loaded from server.
        var data = new google.visualization.DataTable(jsonData);
        var table = new google.visualization.Table(document.getElementById('table_div4'));
        table.draw(data, {showRowNumber: true, width: '100%', height: '100%'});
        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.BarChart(document.getElementById('chart_div4'));
        chart.draw(data, {title:"Total number of awards by position", width: 400, height: 240});
    }
    function exportCSVAwardee()
    {
        var req = new XMLHttpRequest;
        var inputName = document.getElementById("nameText").value;
        req.open('GET', "/reports/get/" + inputName, false);  
        req.send(null);
        var responseObj = JSON.parse(req.response)
        const headers = [
            ["AwardType", "Count"]
        ]
        let csvContent = "data:text/csv;charset=utf-8,";
        headers.forEach((rowArray) => {
            let row = rowArray.join(",");
            csvContent += row + "\r\n";
        });
        responseObj.forEach((rowObj) => {
            let row = rowObj['awardName'] + ',' + rowObj['count'];
            csvContent += row + "\r\n";
        });
        var encodedUri = encodeURI(csvContent);
        window.open(encodedUri);
    }
    function exportCSVLocation()
    {
        var req = new XMLHttpRequest;
        req.open('GET', "/reports/getLocation/", false);  
        req.send(null);
        var responseObj = JSON.parse(req.response)
        const headers = [
            ["Location", "Count"]
        ]
        let csvContent = "data:text/csv;charset=utf-8,";
        headers.forEach((rowArray) => {
            let row = rowArray.join(",");
            csvContent += row + "\r\n";
        });
        responseObj.forEach((rowObj) => {
            let row = rowObj['awardeeLocation'] + ',' + rowObj['count'];
            csvContent += row + "\r\n";
        });
        var encodedUri = encodeURI(csvContent);
        window.open(encodedUri);
    }
    function exportCSVManager()
    {
        var req = new XMLHttpRequest;
        req.open('GET', "/reports/getManager/", false);  
        req.send(null);
        var responseObj = JSON.parse(req.response)
        const headers = [
            ["Manager", "Count"]
        ]
        let csvContent = "data:text/csv;charset=utf-8,";
        headers.forEach((rowArray) => {
            let row = rowArray.join(",");
            csvContent += row + "\r\n";
        });
        responseObj.forEach((rowObj) => {
            let row = rowObj['awardeeManager'] + ',' + rowObj['count'];
            csvContent += row + "\r\n";
        });
        var encodedUri = encodeURI(csvContent);
        window.open(encodedUri);
    }
    function exportCSVPosition()
    {
        var req = new XMLHttpRequest;
        req.open('GET', "/reports/getPosition/", false);  
        req.send(null);
        var responseObj = JSON.parse(req.response)
        const headers = [
            ["Position", "Count"]
        ]
        let csvContent = "data:text/csv;charset=utf-8,";
        headers.forEach((rowArray) => {
            let row = rowArray.join(",");
            csvContent += row + "\r\n";
        });
        responseObj.forEach((rowObj) => {
            let row = rowObj['awardeePosition'] + ',' + rowObj['count'];
            csvContent += row + "\r\n";
        });
        var encodedUri = encodeURI(csvContent);
        window.open(encodedUri);
    }
</script>

<h1>Business Intelligence Page</h1>
<hr>

<h2>Types of Awards per Awardee</h2>

<p>Please enter awardee's name:</p><input type="text" name="FullName" id="nameText">
<button onclick="drawChart('chart_div')">Submit</button>
<br>

<!--Div that will hold the pie chart-->
<table class="columns">
      <tr>
        <td><div style="margin-top:25px;" id="chart_div" style="border: 1px solid #ccc"></div></td>
        <td><div style="margin-left:100px;" id="table_div" style="border: 1px solid #ccc"></div></td>
      </tr>
</table>

<button onclick="exportCSVAwardee()">Export to CSV</button>

<h2>Number of Awards by Location</h2>
<table class="columns">
      <tr>
        <td><div style="margin-top:25px;" id="chart_div2" style="border: 1px solid #ccc"></div></td>
        <td><div style="margin-left:100px;" id="table_div2" style="border: 1px solid #ccc"></div></td>
      </tr>
</table>
<button onclick="exportCSVLocation()">Export to CSV</button>
<!-- <div id="chart_div2"></div> -->
<h2>Number of Awards by Manager</h2>
<table class="columns">
      <tr>
        <td><div style="margin-top:25px;" id="chart_div3" style="border: 1px solid #ccc"></div></td>
        <td><div style="margin-left:100px;" id="table_div3" style="border: 1px solid #ccc"></div></td>
      </tr>
</table>
<button onclick="exportCSVManager()">Export to CSV</button>
<!-- <div id="chart_div3"></div> -->
<h2>Number of Awards by Position</h2>
<table class="columns">
      <tr>
        <td><div style="margin-top:25px;" id="chart_div4" style="border: 1px solid #ccc"></div></td>
        <td><div style="margin-left:100px;" id="table_div4" style="border: 1px solid #ccc"></div></td>
      </tr>
</table>
<button onclick="exportCSVPosition()">Export to CSV</button>
<!-- <div id="chart_div4"></div> -->

<article>
<br style="clear: both" />
</article>

<?php include(APPDIR.'views/layouts/footer.php');?>