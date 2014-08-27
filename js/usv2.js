//
// Voir core/js/config.js
//
$("#usv2FormID").ready(function() {
	$('#usv2tabs').tabs();
	var appName = "user_servervars2";
	//OC.AppConfig.setValue(appName, "sso_url");
	//
	$("#usv2FormID :input").change(function(e){


        var s = e.target;
        var jqTarget = $("#usv2tabs :input[name='"+s.name+"']");

		var fn = function(key, value, okFn) {
            //jqTarget.removeClass("usv2saved");
            jqTarget.addClass("usv2changed");
            OC.AppConfig.postCall('setValue',{app:appName,key:key,value:value}, function() {
                jqTarget.removeClass("usv2changed");
                jqTarget.addClass("usv2saved");
                jqTarget.removeClass("usv2saved",2000);
            });
        }


		switch(s.type) {
            case 'text':
                fn(s.name, s.value);
                break;
            case 'checkbox':
                fn(s.name, s.checked);
                break;
            default: 
            	console.error('unhandled event '+s);
        }
	});
});