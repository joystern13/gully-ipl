var response;
function onScoring(json){
  console.log(json);
  
  $.ajax({
	  url: "php/updateMatchResult.php",
	  type: "POST",
	  data: 'json='+encodeURIComponent(JSON.stringify(json)),
         success: function(data) {
         	console.log(data);
         	response = data;
         },
         error: function(err) {
         	console.log(err);
         }
	});
  return response;
}
function getJSON(matchname) {
	$.ajax({
	  url: "https://datacdn.iplt20.com/dynamic/data/core/cricket/2012/ipl2018/"+matchname+"/scoring.js",
	  dataType: "jsonp"
	});
	return response;
};