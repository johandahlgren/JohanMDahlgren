var animationSpeed 		= 200;
var useParallax			= false;
var disqusUrl 			= "http://www.dahlgren.tv/johan/boksiten/index.php?entityId=" + pageEntityId;
var disqusShortName 	= "johanmdahlgren";
var apiKey 				= "KfoikqBnjya5BnL1VjHsCQirKBrNqE1TgQiHspRAjrUDBc3mHdcarLlojibjLzch";
var useCanvasBackground = true;
var topMenuSelection;

$(document).ready(function () {
	$("li.active").parents("li").addClass("active");

	/*
	var stickyNavTop = $("#topMenu").offset().top;

	var stickyNav = function(){
		var scrollTop = $(window).scrollTop();

		if (scrollTop > stickyNavTop) {
			$("#topMenu").addClass("sticky");
		} else {
			$("#topMenu").removeClass("sticky");
		}
	};

	stickyNav();

	$(window).scroll(function() {
	    stickyNav();
	});
	*/

	$(".discussionToggle").click(function() {
		var itemIdentifier = $(this).attr("data-id");

		$(".discussionDiv").hide();

		if (window.DISQUS) {
		   $("#disqus_thread").appendTo($(this).parent());

		   DISQUS.reset({
			  reload: true,
			  config: function () {
			  this.page.identifier 	= itemIdentifier;
			  this.page.url 		= disqusUrl;
			  }
		   });
		} else {
			jQuery('<div id="disqus_thread"></div>').insertAfter($(this));
			disqus_identifier 	= itemIdentifier;
			disqus_url 			= disqusUrl;

			var dsq 			= document.createElement("script");
			dsq.type 			= "text/javascript";
			dsq.async 			= true;
			dsq.src 			= "http://" + disqusShortName + ".disqus.com/embed.js";

			$("head").append(dsq);
		}

	    $(this).siblings(".discussionDiv").slideToggle(animationSpeed);
	});

	if ($(".crumbtrailContainer").children("a").length === 0) {
		$(".crumbtrailContainer").hide();
	}

	$(".shareLink").click(function (e) {
		e.preventDefault();
		$("#overlay").show();
		$("#shareDiv").fadeIn(animationSpeed);
		$("#shareDiv").load($(this).attr("href"));
  		return false;
	});

	if (useParallax && $(window).width() > 800) {
		var offset = 0;
		$(window).scroll(function() {
			$("body").css("background-position", "0 -" + Math.round($("body").scrollTop() / 5) + "px");
		});
	} else {
		useCanvasBackground = false;
	}

    if (topMenuSelection !== "") {
        $(topMenuSelection).addClass("active");
    }

   	getNumberOfPosts();

});

function getNumberOfPosts() {

	//https://disqus.com/api/3.0/threads/details.json?thread=2021592416&api_key=qMqAbBHl0VYOXLjisqfcQKZaaf6WPyQZCAoRxWR1FqyjFGoLYGBQ5AfpEGELygFR
	//https://disqus.com/api/3.0/threads/details.json?thread:ident=230&forum=johanmdahlgren&api_key=qMqAbBHl0VYOXLjisqfcQKZaaf6WPyQZCAoRxWR1FqyjFGoLYGBQ5AfpEGELygFR

	var threadId = 0;

	$(".discussionCounter").each(function() {
		var threadId 	= $(this).attr("data-id");
		var count 		= 0;
		$.ajax({
			type: "GET",
			url: "https://disqus.com/api/3.0/threads/details.json",
			data: {"api_key" : apiKey, "forum" : disqusShortName, "thread:ident" : threadId},
			cache: false,
			dataType: "json",
			success: function (result) {
				var countText = " comments";
				count = result.response.posts;
				if (count == 1) {
					countText = " comment";
				}
				var identifier = ".discussionCounter[data-id=" + threadId + "]"; // result.response.identifiers[0]
				if (count > 0) {
					$(identifier).html(count + countText);
				}
			},
			error: function (e) {
				console.log(e);
			}
		});
	});
}

function toggleCommentFields(entityId) {
    $("#toggleCommentFields" + entityId).fadeToggle(0);
    $("#insertComment" + entityId).slideToggle(200);
    $("#commenterName" + entityId).focus();
}
function postComment(entityId) {
    var commenterName   = $("#commenterName" + entityId).val();
    var commentText     = $("#commentText" + entityId).val();

    if (commenterName !== "" && commentText !== "") {
        $.ajax({
            url: "cms/ajaxService.php",
            data: {
                action: "createEntity",
                parentId: entityId,
                type: 440, // Comment type ID
                name: commenterName,
                state: "active",
                data_CommenterName: commenterName,
                data_Text: commentText
            }
        })
        .done(function(data) {
            $("#comments" + entityId).html(data);
            $("#commenterName" + entityId).val("");
            $("#commentText" + entityId).val("");
            toggleCommentFields(entityId);
        });
    }
}