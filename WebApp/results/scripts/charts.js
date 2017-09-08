<script type="text/javascript">

	/* Proportion of original tweets and retweets */
	function pieRetweets(tweets, retweets) {
	
		$('#activity').highcharts({
		chart: {
		    plotBackgroundColor: "#F5F5DC",
		    plotBorderWidth: 0,
		    plotShadow: false
		},
		title: {
		    text: 'Tweets<br>vs<br>Retweets',
		    align: 'center',
		    verticalAlign: 'middle',
		    y: 50
		},
		tooltip: {
		    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
		},
		plotOptions: {
		    pie: {
			dataLabels: {
			    enabled: true,
			    distance: -50,
			    style: {
				fontWeight: 'bold',
				color: 'white',
				textShadow: '0px 1px 2px black'
			    }
			},
			startAngle: -90,
			endAngle: 90,
			center: ['50%', '75%']
		    }
		},
		series: [{
		    type: 'pie',
		    name: 'Proportion',
		    innerSize: '50%',
		    data: [
			['retweets',   parseFloat(retweets)],
			['original tweets',     parseFloat(tweets) - parseFloat(retweets)]
		    ]
		}]
	    });
	
	}
	
	/* Sources used to produce the tweets */
	function pieSources(web, management, follow, automatic, tierces, devices) {
	
		$('#activity1').highcharts({
		chart: {
		    plotBackgroundColor: "#F5F5DC",
		    plotBorderWidth: 0,
		    plotShadow: false
		},
		title: {
		    text: 'Sources used<br>to Tweet<br>',
		    align: 'center',
		    verticalAlign: 'middle',
		    y: 50
		},
		tooltip: {
		    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
		},
		plotOptions: {
		    pie: {
			dataLabels: {
			    enabled: false,
			    distance: -50,
			    style: {
				fontWeight: 'bold',
				color: 'white',
				textShadow: '0px 1px 2px black'
			    }
			},
			startAngle: -90,
			endAngle: 90,
			center: ['50%', '75%']
		    }
		},
		series: [{
		    type: 'pie',
		    name: 'Proportion',
		    innerSize: '50%',
		    data: [
			['Twitter website',   parseFloat(web)],
			['Management dashboard tools',       parseFloat(management)],
			['Automatic follow tools', parseFloat(follow)], 
			['Automatic tweet tools', parseFloat(automatic)], 
			['Other applications', parseFloat(tierces)], 
			['Devices', parseFloat(devices)]
		    ]
		}]
	    });
	
	}
	
	function lineTweets(h0, h1, h2, h3, h4, h5, h6, h7, h8, h9, h10, h11, h12, h13, h14, h15, h16, h17, h18, h19, h20, h21, h22, h23) {
	
		$(function () {
		$('#activity2').highcharts({
		    title: {
			text: 'Tweets per hour',
			x: -20 //center
		    },
		    subtitle: {
			text: 'taken over the last 200 tweets',
			x: -20
		    },
		    xAxis: {
			categories: ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24']
		    },
		    yAxis: {
			title: {
			    text: 'Number of tweets'
			},
			plotLines: [{
			    value: 0,
			    width: 1,
			    color: '#808080'
			}]
		    },
		    legend: {
			layout: 'vertical',
			align: 'right',
			verticalAlign: 'middle',
			borderWidth: 0
		    },
		    series: [{
			name: 'Tweets',
			data: [parseFloat(h0), parseFloat(h1), parseFloat(h2), parseFloat(h3), parseFloat(h4), parseFloat(h5), parseFloat(h6), parseFloat(h7), parseFloat(h8), parseFloat(h9), parseFloat(h10), parseFloat(h11), parseFloat(h12), parseFloat(h13), parseFloat(h14), parseFloat(h15), parseFloat(h16), parseFloat(h17), parseFloat(h18), parseFloat(h19), parseFloat(h20), parseFloat(h21), parseFloat(h22), parseFloat(h23)]
		    }]
			});
		    });
	
		}

		/* Proportion of positive and negative tweets */
	function piePolarity(count_pos, count_neg) {
	
		$('#polarity').highcharts({
		chart: {
		    plotBackgroundColor: "#F5F5DC",
		    plotBorderWidth: 0,
		    plotShadow: false
		},
		title: {
		    text: 'Potential <br/>polarity',
		    align: 'center',
		    verticalAlign: 'middle',
		    y: 50
		},
		tooltip: {
		    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
		},
		plotOptions: {
		    pie: {
			dataLabels: {
			    enabled: true,
			    distance: -50,
			    style: {
				fontWeight: 'bold',
				color: 'white',
				textShadow: '0px 1px 2px black'
			    }
			},
			startAngle: -90,
			endAngle: 90,
			center: ['50%', '75%']
		    }
		},
		series: [{
		    type: 'pie',
		    name: 'Polarity',
		    innerSize: '50%',
		    data: [
			['Positive<br/>tweets',   parseFloat(count_pos)],
			['Negative<br/>tweets',     parseFloat(count_neg)]
		    ]
		}]
	    });
	
	}
		
</script>
