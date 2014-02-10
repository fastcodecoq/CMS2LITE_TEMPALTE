var sedes = {"Corozal" : 0 , "Chinu" : 1,  "El Carmen" : 2, "Magangue" : 3, "Sampues" : 4 ,"San Onofre" : 5};	 
var ls = window.localStorage;
var MAP;
var locations = new Array( [9.326129,-75.285727], [9.108029183211615,-75.40011405944824] , [9.716667,-75.133333], [9.25,-74.766667], [9.181439,-75.376534], [9.733333,-75.533333]  );
var zone;

var clickController = function(obj){


	  $(obj.attr("data-target")).click();


}

var showController = function(obj){


	 var elems = obj.attr("data-target").split(",");
	 
	 for(x in elems)	 	
	    $(elems[x]).show();

	 var toHide = obj.attr("data-hide-this").split(",");
  
	  for(x in toHide)	 	
	    $(toHide[x]).hide();
	  
	 if(obj.attr("data-hide-me"))
	   obj.hide();


}



var closeController = function(obj){


	  var elems = obj.attr("data-target").split(",");
	 
	 console.log(elems)
	 for(x in elems)	 	
	    $(elems[x]).hide();


	 var toShow = obj.attr("data-show-this").split(",");
	
	 console.log(toShow);
	  
	  for(x in toShow)	 	
	    $(toShow[x]).show();
	  
	 if(obj.attr("data-hide-me"))
	   obj.hide();


}

var prevents = function(e){

	e.preventDefault();
    e.stopPropagation();

}

$.fn.cmd = function(){
	return $(this).attr("data-cmd");
}



$.fn.id = function(){
	return $(this).attr("data-id");
}

var lowRate = function(){
		var msg = $("#lowRate").val();

		$.ajax({
			url : window.$_PATH + "/controlers/rate.php?rate",
			data : {rate : window.rate , msg : msg},
			type : "POST",
			dataType : "JSON",
			success : function(rs){

				console.log(rs);

				if(rs.success === 0)
				{
					dialog.show("No pudimos procesar tu solicitud, intenta de nuevo más tarde.");
					return false;
				}
				if(rs.success === -1)
			    {
				  	dialog.show("Gracias pero, solo permitimos un voto por persona");
					return false;
				}

				if(rs.success === 1)
					rateResults(rs);
				

			},
			error : function(err){console.log(err);}		
		});
 }
var Rate = function(){ 
     
     $.ajax({
			url : window.$_PATH + "/controlers/rate.php?rate",
			data : {rate : window.rate },
			type : "POST",
			dataType : "JSON",
			success : function(rs){
				console.log(rs);

				if(rs.success === 0)
				{
					dialog.show("No pudimos procesar tu solicitud, intenta de nuevo más tarde.");
					return false;
				}
				if(rs.success === -1)
			    {
				  	dialog.show("Gracias pero, solo permitimos un voto por persona");
					return false;
				}

				if(rs.success === 1)
					rateResults(rs);
				

			},
			error : function(err){console.log(err);}		
		});

 }
var rateResults = function(rs) { 
	
	dialog.show("Gracias por tu valoración: <br><br> Muy Buena: " + rs.data[4] + " <br> Buena: " + rs.data[3] + " <br> Regular: " + rs.data[2] + "<br> Mala: " + rs.data[1] + "<br> Muy Mala: " + rs.data[0] + "<br><br>");

    }
var feedback_controller = function(el){
	
	 window.rate = parseInt(el.attr("data-val"));	 
    
    if(rate > 3)
	 Rate();
	else
     dialog.show("&nbsp;Ayudanos a mejorar: <br><br> <textarea style='width:97%; height:130px; overflow:auto' id='lowRate' class='toCenter' data-lentgh='200' placeholder='Escribe aquí, en lo que crees que estamos fallando...'></textarea>", {onAccept: lowRate , ask : true, acceptText : "Enviar", title: "Enviar un Comentario"});
}

var sController = function(el){

	 var sed = "";
	  for(x in sedes)
         sed += "<li data-cmd='sel-sede' data-id='" + sedes[x] + "' data-action><span>" + x + "</span><a href='#' data-cmd='sel-sede' data-id='" + sedes[x] + "' data-action></a></li>";

	 dialog.show("<ul class='sedes-picker'>" + sed + "</ul>");
}

var sede_checker = function(){

    if(!ls.sede){
    	ls.sede = sedes["Corozal"];

    $(".sedee").text("Corozal").attr("data-id", ls.sede);
     }else{
       for(x in sedes)
		if(ls.sede == sedes[x])
	      $(".sedee").text(x).attr("data-id", ls.sede);  
     }


}

$.fn.target = function(){ return $($(this).attr("data-target"));}

var action_controller = function(e){
	   prevents(e);
	   var cmd = $(this).cmd();


	   switch(cmd){

	   	 case "sede":
	   	     sController($(this));
	   	 break;

	   	 case "feedback":
	   	     feedback_controller($(this));
	   	 break;

	   	 case "sel-sede":	   	      
	   	      change_sede($(this).attr("data-id"));
	   	 break;

	   	 case "go-to-contact":	   	      
	   	      change_sede(sedes[$(this).attr("data-sede")], true);
	   	      document.location = "/contacto";
	   	 break;

	   	 case "cober-day":
	   	      load_route($(this));
	   	 break;

	   	 case "close":
	   	 var target = $(this).target();	   	   
	   	 console.log(target);     
	   	        target.toggleClass("hidy").hide();
	   	 break;

	   	 case "show":
	   	    var target = $(this).target();	   	        
	   	    console.log(target);
	   	        target.toggleClass("hidy").show();
	   	 break;

	   	 case "close-video":
	   	 var target = $(this).target();	   	   
	   	 var video = document.getElementById($(this).attr("data-video"));	   		
	   	 video.src = "";
	   	 video.src = video.src;
	   	 console.log(target);     
	   	        target.toggleClass("hidy").hide();
	   	 break;

	   	 case "show-video":
	   	    var target = $(this).target();	   	       
	   	    var video = document.getElementById($(this).attr("data-video"));
	   	 var src = $(video).attr("data-src");
	   	 video.src = src;
	   	 video.src = video.src;	   	
	   	    console.log(target);
	   	        target.toggleClass("hidy").show();
	   	 break;


	   	 default:
	   	     dialog.show("&nbsp; Esta función no esta disponible aún.")
	   	 break;

	   }
}


function resetRoute(){
	if(zone)
	zone.setMap(null);
	$(".barrios").text("");
	$(".horario").text("");
	$(".recolection").text("");
}

var load_route = function(el){
   
     var day = el.attr("data-day");
     resetRoute();     
     $("[data-day]").removeClass("active");
     el.addClass("active");
     ls.day = day;

    get_polygons(day, true);

}

function get_polygons(day, changing){

 var data = {
     	  sede : parseInt(ls.sede),
     	  day : parseInt(day)
     };
          

     $.ajax({
     	url : "/api/route.php?find", 
     	data : data,
     	type : "POST",
     	dataType : "JSON",
        success : function(rs){
     	  console.log(rs);
     	  if(rs.success === 0)	
     	  {	
 
     	  	$(".horario").html("Aún no hay información disponible para este día");   
     	  	if(zone)   	 
     	  	zone.setMap(null);   
     	  	return false;
          }else if(rs.success === 1)            
            load_map_data(rs);


         } ,
        error : fail
      
      });

      function fail(err){console.log(err);}

}

function load_map_data(rs){

	   var rs = rs.data;	   
	   var points = [];
	   var polygones = JSON.parse(rs.polygone);

	   console.log("map_data", polygones);

	   if(polygones.length === 0 )
	   	 return false;

    for(x in polygones)
	 {
	 	
	 	var polygone = polygones[x];

	   for(x in polygone)
	   	 if(polygone[x].b)
	   	   points.push(new google.maps.LatLng(polygone[x].b, polygone[x].d));
	   	 else if(polygone[x].e)
	   	   points.push(new google.maps.LatLng(polygone[x].d, polygone[x].e));
                                                                                                

	   	console.log(points);

	   var zone = new google.maps.Polygon({
							paths: points,
							strokeWeight: 1,
							strokeColor: "#FCDF0A",
							strokeOpacity: 0.99,    
							fillColor: "#005EB2",
							fillOpacity: 0.50
  						});
   
	   zone.setMap(MAP);
	   google.maps.event.addListener(zone, 'click', function(){	   	  
	   	  $(".see-more").click();
	   });
    }
	   var zones = rs.zone.split(",");
	   var Short = [];
	   window.zones = rs.zone;

	    if(zones.length > 2)
	       for(i=0;i<3;i++)
	       	 Short.push(zones[i]);
	    else
	    	for(i=0;i<zones.length;i++)
	       	 Short.push(zones[i]);
		
		var zonesList = "";
		for(x in zones)
			zonesList += "<li>&nbsp;&nbsp;" +zones[x]+ "</li>";

		var more = (zones.length > 3) ? " ("+ (zones.length - 3) +" más) <a href='#' class='see-more no-under' data-msg='<h4 class=\"tlc grayy\">Barrios</h4><ul class=\"sedes-picker\">" + zonesList + "</ul>' data-dialog>Ver todos</a>" : "";	       	
  
	   $(".barrios").html("Barrios: " + Short.join(",") + more);
	   $(".horario").text("Horario: " + rs.start + " - " + rs.end);
 		
 	   var start = format_hr(rs.start);
 	   var end = format_hr(rs.end);

 	   console.log("res", start.res + end.res);
 	   var res = start.res + end.res; 	        	   
 	   var recolection = end.hr - start.hr;
 	       recolection = (res > 30 && res > 0) ? (recolection + 1) + ":00 hrs" : (recolection - 1) + ":" + res + " hrs";
 	       recolection = (res === 0) ? (end.hr - start.hr)  + ":00 hrs" : recolection;

 	   $(".recolection").text("Tiempo total de recogida: " + recolection);



}


function format_hr(hr){
 	        
 	        var hr = hr.split(" ");
  		    res = parseInt(hr[0].split(":")[1]);
  		    mer = hr[1];
 		    hr = (mer === "am") ? parseInt(hr[0]) : parseInt(hr[0]) + 12;

 		    return {hr : hr, mer : mer , res : res};

}

function toCenter(){
     var sede = parseInt($("select[name='sede']").val());
     MAP.setCenter(new google.maps.LatLng(locations[sede][0], locations[sede][1]));
     google.maps.event.trigger(MAP, 'resize');
}

function inimap(zoom){

 var sede = (ls.sede) ? parseInt(ls.sede) : 0;
 var day = (ls.day) ? parseInt(ls.day) : 0;

 var zoom =  15;

 var mapOptions = {
    zoom: zoom,
    center: new google.maps.LatLng(locations[sede][0], locations[sede][1]),
      mapTypeId: google.maps.MapTypeId.ROADMAP
  };

 
 MAP = new google.maps.Map(document.getElementById('map'),
  mapOptions);
    // google.maps.event.trigger(MAP, 'resize');

 get_polygons(day);


}

var change_sede = function(id, nomap){
	ls.sede = id;

	for(x in sedes)
		if(id == sedes[x])
	      $(".sedee").text(x).attr("data-id", ls.sede);  
   
    dialog.hide();

    if(!nomap)
      inimap();
    

}

var controllers = function(){
	
		   
	   $("[data-action]").die("click").live("click", action_controller);


	}

var error = function(err){ console.log(err); }
var done = function(form, rs){ console.log(rs); }



var ini_controllers = function(){

		   controllers();
		   sede_checker();

	        $("[data-dialog]").live("click", function(e){
	        	 e.preventDefault();
	        	 e.stopPropagation();

	        	 var msg = $(this).attr("data-msg");
	        	 dialog.show(msg);
	        });


		    ls.day = new Date().getDay() - 1;		
		   if($("#map").length > 0)   
		   inimap();				   
		   if(ls.day)
		   {		   	
		   	$("[data-day]").removeClass("active");
		   	$("[data-day='"+parseInt(ls.day)+"'").addClass("active");
		   }else
		    ls.day = new Date().getDay() - 1;
		  if(!$$.environment().isMobile)
		   	$("a[href='tel']").attr("href","#").addClass("normal"); 

		   	

	}


function exists( elem ){
	return $(elem).length > 0 ? true : false;
}



