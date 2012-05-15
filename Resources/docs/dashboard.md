Google Dashboard API
====================

The bundle adds support for the [google dashboard library](http://googledevelopers.blogspot.com/2012/05/new-google-analytics-easy-dashboard.html). 

## Usage


### Configuration

Add your google api credentials in your configuration (see the above link how to set it up):

``` yml
google:
    analytics:
	    # .... other settings 
        dashboard: 
            api_key:    your-api-key
            client_id:  your-client-id
            table_id:   your-table-id
```

### Initializtation

In your dashboard, simply include the `dashboard.html.twig` template with an optional `initCallback` parameter:

``` jinja
  {% include "GoogleBundle:Analytics:dashboard.html.twig" with { 'initCallback' : 'myDashCallback' } %}
```

The `myDashCallback` is a name of a javascript function which is being called after the google dashboard library has been initialized.


### Authorization

The dashboard library needs a button to initialize the google authorization, this defaults to the id `authorize-button`:

``` html
<button id="authorize-button" style="visibility:hidden">Authorize Google Analytics</button>
```

The id of the element can be configured by passing a `authorizeButton` parameter to the dashboard twig template.

### Drawing charts

Once the user is authorized at google, you can start drawing your analytics charts in your templates:

``` html

<div class="gadash-container">
    <h3>Visits</h3>
    <div id='dataOverTimeConfig'></div>
<div>

<script type="text/javascript">

function adminInit() {

    // Create new Chart.
    var dataOverTime = new gadash.Chart({
	    'last-n-days': 30,
        'chartOptions': {
            width: 700
        },
        'divContainer': 'dataOverTimeConfig',
        'type': 'LineChart',
        'query': {
          'dimensions': 'ga:date',
          'sort': 'ga:date',
          'metrics': 'ga:visitors, ga:visits, ga:pageviews',
          'ids' : gadash.tableId      
        },
        'chartOptions': {
            height: 300,
            legend: {position: 'bottom'},
            hAxis: {title:'Date'},
            curveType: 'function'
        }
    }).render();
}

</script>
```

Note that you need to pass the `gadash.tableId` in each chart, otherwise you'll get an api error.



