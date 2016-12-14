/* 
Pour la partie statistiques
 */

$(document).ready(function () { 
	
//GRAPHIQUE


var $data = [[], [], [], []];

var ctx = document.getElementById("myChart");
var ctx2 = document.getElementById("myChart2");

$.ajax({
	url: '../libs/serv.statistique.graph.php',
	type: 'POST',
	dataType: 'json',
	success: function (data_json) {

		$data[0] = data_json[0];
		$data[1] = data_json[1];
		$data[2] = data_json[2];
		$data[3] = data_json[3];

	},
	error: function (resultat, statut, erreur) {
		console.log('erreur : ' + resultat + " ; " + statut + " ; " + erreur);
	},
	complete: function (data) {

		//grraph 1
		var myChart = new Chart(ctx, {
			type: 'line',
			data: {
				labels: $data[0],
				datasets: [{
						label: 'Cumul des commandes passées en €',
						data: $data[1],
						lineTension: 0.1,
						backgroundColor: "rgba(75,192,192,0.4)",
						borderColor: "rgba(75,192,192,1)"
					}]
			},
			options: {
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero: false
						}
					}]
				}
			}
		});

		//grraph 2
		var myChart2 = new Chart(ctx2, {
			type: 'radar',
			data: {
				labels: $data[2],
				datasets: [{
					label: '% de reservation',
					data: $data[3],
					lineTension: 0.1,
					backgroundColor: "rgba(75,192,192,0.4)",
					borderColor: "rgba(75,192,192,1)"
				}]
			},
			options: {
				scale: {
					reverse: false,
					ticks: {
						beginAtZero: true,
						max:100
					}
				}
			}
		});

	}
});

console.log('global: ' + $data[0]);


});