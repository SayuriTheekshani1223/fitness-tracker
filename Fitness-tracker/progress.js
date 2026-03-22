const ctx = document.getElementById('progressChart');

new Chart(ctx, {

type: 'line',

data: {

labels: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],

datasets: [

{
label: 'Steps',
data: [2000,4000,7000,6000,5500,9500,3200],
borderColor: '#28a745',
backgroundColor: 'rgba(40,167,69,0.2)',
tension:0.4,
fill:true
},

{
label: 'Calories',
data: [800,1200,2600,2400,4500,6000,5300],
borderColor: '#4a90e2',
backgroundColor: 'rgba(74,144,226,0.2)',
tension:0.4,
fill:true
}

]

},

options: {

responsive:true,

plugins:{
legend:{
position:'bottom'
}
}

}

});