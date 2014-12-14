var seconds = 30;
var start = new Date();
start = Date.parse(start)/1000;
function CountDown(){
	var now = new Date();
	now = Date.parse(now)/1000;
	var counter = parseInt(seconds-(now-start),10);
	document.getElementById('countdown').innerHTML = counter;
	if(counter > 0){
		timerID = setTimeout("CountDown()", 100)
	}else{
		//TODO: why are we redirecting to / on shutdown?
		location.href = "/"
	}
}
window.setTimeout('CountDown()',100);