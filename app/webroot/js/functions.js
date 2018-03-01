/** FORM **/
var ableToSend = true;

function main() {
	sendForm();
	selectInfluencer();
	settings();
}

function settings() {

	// Show settings
	$("#to-settings").on('click',function() {
		var active = ($(this).attr('data-active')=="1")? true : false;

		if (!active) {
			$("#settings").slideDown('slow');
			$(this).attr('data-active',1);
		} else {
			$("#settings").slideUp('slow');
			$(this).attr('data-active',0);
		}
	});

	// Select settings option
	$(".supra-option").on('click',function() {
		$(this).parent().find('div[class*="active"]').removeClass('active');
		$(this).addClass('active');
	});
}

function sendForm() {
	$("#to-send-form").on('click',function() {
		submitForm();
		return false;
	});

	$("#username").on('keyup',function(e) {
		var code = e.keyCode || e.which;
		 if(code == 13) {
			 e.preventDefault();
			 submitForm();
			 return false;
		 }
	});
}

function submitForm() {
	$("#settings").slideUp('slow');
	$("#to-settings").attr('data-active',0);
	$("#progress-message").off('click');
	$(".disabler").show();
	if (ableToSend) {
		ableToSend = false;
		addSettingsToForm();
		$("#main-form").submit();
	}
}

function addSettingsToForm() {
	var visibility = $(".visibility").find('div[class*="active"]').attr('data-value');
	var optimization = $(".optimization").find('div[class*="active"]').attr('data-value');

	$("#form-visibility").val(visibility);
	$("#form-optimization").val(optimization);
}

function selectInfluencer() {
	$(".to-select-influencer, .influencer .body").click(function() {
		var screenName = $(this).closest('.influencer').attr("data-screen-name");
		$("#username").val("@"+screenName);
		$(".influencername").text("@"+screenName);
		$('html,body').animate({ scrollTop: 0 }, 'slow');
	});
}

/** PROGRESS **/
function updateProgressBar(percentage) {
	$("#progress-bar").css('width',percentage+"%");
}

function updateProgressMessage(message,type) {
	$("#progress-message").text(message);
	$("#progress-message").removeClass();
	$("#progress-message").addClass(type);
}

function finishCreateList(type,url) {
	ableToSend = true;
	$(".disabler").hide();

	if (type=="error") {
		$("#progress-bar").css('width',"0");
	}

	if (type=="success" && url != "") {
		$("#username,.username").val('');
		$("#progress-message").on('click',function() {
			window.open(url, "_blank");
		});
	}

}