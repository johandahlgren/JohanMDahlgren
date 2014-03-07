var animationSpeed 		= 100;
var activeField 		= null;
var activeDisplayText 	= null;
var activeParent		= 0;

$(document).ready(function () {
	bindEvents();
	
	/*--------------------------------------------
	  Open the menu tree to the last configuration
	  --------------------------------------------*/
	  
	var openNodes = localStorage.getItem("openNodes");
	if (openNodes != null) {
		var openNodesArray = openNodes.split(",");
		$(openNodesArray).each(function() {
			$("li div[data-entityid='" + this + "']").closest("li").addClass("open");
			$("li div[data-entityid='" + this + "']").closest("li").children("ul").show();	
		});
	}
});

function bindEvents() {
	$(".editButton").unbind().click(function () {
		$(this).closest(".menuItem").children(".buttonContainer").toggle();
	});

	$(".addButton").unbind().click(function () {
		$("#overlay").fadeIn(animationSpeed);
		$(".addMenu").toggle();
		activeParent = $(this).closest(".menuItem").attr("data-entityid");
	});

	$(".deleteButton").unbind().click(function (e) {
		e.preventDefault();
		$("#loading").show();
		var actionUrl = "admin.php?userAction=delete&entityId=" + $(this).closest(".menuItem").attr("data-entityid");
		$.ajax({
			url: actionUrl,
		}).done(function() {
			$(e.target).closest("li").slideUp(animationSpeed);
			$("#loading").hide();
		});
	});

	$("#overlay").unbind().click(function () {
		$("#overlay").fadeOut(animationSpeed);
		$(".addMenu").hide();
		activeParent = 0;
	});
	
	$(".menuItemIcon").unbind().click(function() {
		$(this).closest("li").toggleClass("open");
		$(this).closest("li").children("ul").slideToggle(animationSpeed);

		var openNodes = "";
		var temp = ""
		
		$("#nodeTree .open").each(function() {
			temp = $(this).find(".menuItem").attr("data-entityid");
			openNodes = openNodes + "," + temp;
		});
		
		localStorage.setItem("openNodes", openNodes);
	});

	$("#menuEdit").unbind().click(function() {
		$(".previewContainer").hide();
		$(".reorderContainer").hide();
		$(".buttonContainer").show();
		setActive($(this));
	});

	$("#menuPreview").unbind().click(function() {
		$(".previewContainer").show();
		$(".reorderContainer").hide();
		$(".buttonContainer").hide();
		setActive($(this));
	});

	$("#menuReorder").unbind().click(function() {
		$(".previewContainer").hide();
		$(".reorderContainer").show();
		$(".buttonContainer").hide();
		setActive($(this));
	});
	
	$("#menuReload").unbind().click(function() {
		window.location.reload(true);
	});

	$(".moveUp, .moveDown").unbind().click(function() {
		reorder($(this));
	});

	$(".selectEntity").unbind().click(function () {
		var createUrl = "formHandler.php?userAction=create&type=" + $(this).closest(".menuItem").attr("data-entityid") + "&parentId=" + activeParent;

		$("#editFormContainer").load(createUrl, function () {
			$("#overlay").fadeOut(animationSpeed);
			$(".addMenu").hide();
			$("#editFormContainer").slideDown(animationSpeed);
			$("#name").focus();
			bindEvents();
			bindLinkButtons();
		});
	});

	$(".editEntity").unbind().click(function (e) {
		e.preventDefault();
		var url = "formHandler.php?userAction=edit&entityId=" + $(this).closest(".menuItem").attr("data-entityid") + "&type=" + $(this).closest(".menuItem").attr("data-type");
		$("#editFormContainer").load(url, function () {
			$("#editFormContainer").slideDown(animationSpeed);
			bindLinkButtons();
		});
	});

	$(".connectIcon").unbind().click(function () {
		var entityId = $(this).closest(".menuItem").attr("data-entityid");
		var entityName = $(this).closest(".menuItem").attr("data-entityname");
		setValue(entityId, entityName);
		$("#overlay").fadeOut(animationSpeed);
		$("#nodeTree").css("position", "initial");
	});
	
	$(".preview").attr("target", "_blank");
}

function setActive(aElement) {
	$(".mainMenuButton").removeClass("active");
	aElement.addClass("active");
}

function bindLinkButtons() {
	$(".linkButton").click (function () {
		$("#overlay").show();
		$("#nodeTree").css("position", "absolute");
		$("#nodeTree").css("top", "20px");
		$("#nodeTree").css("right", "20px");
		
		$("#overlay").click(function() {
			$("#nodeTree").css("position", "initial");
		});
		
		$(".connectIcon").show();
		$(".previewContainer").hide();
		$(".reorderContainer").hide();
		$(".buttonContainer").hide();
		
		activeField = $(this).siblings("input")[0];
		activeDisplayText = $(this).siblings(".linkField")[0];
	});
}

function setValue(aValue, aDisplayText) {
	$(activeField).val(aValue);
	$(activeDisplayText).html(aDisplayText);
	$(".connectIcon").toggle();
	$(".buttonContainer").toggle();
}

function reorder (aElement)
{
	var selectedNode = aElement.closest(".menuItem").attr("data-entityid");
	var direction = aElement.attr("data-direction");
	var url = "admin.php?userAction=reorder&activeNode=" + selectedNode + "&direction=" + direction;
	document.location = url;
}