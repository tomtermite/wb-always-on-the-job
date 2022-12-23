(function(){
	"use strict";
	$(document).ready(function(){
		$("#document-advanced").treetable({ expandable: true });
		$("#document-advanced tbody").on("mousedown", "tr", function() {
			$(".selected").not(this).removeClass("selected");
			$(this).toggleClass("selected");
		});
		// Drag & Drop document Code
		$("#document-advanced .file, #document-advanced .folder").draggable({
			helper: "clone",
			opacity: .75,
			refreshPositions: true,
			revert: "invalid",
			revertDuration: 300,
			scroll: true
		});

		$("#document-advanced .folder").each(function() {
			$(this).parents("#document-advanced tr").droppable({
				accept: ".file, .folder",
				drop: function(e, ui) {
					var droppedEl = ui.draggable.parents("tr");
					$("#document-advanced").treetable("move", droppedEl.data("ttId"), $(this).data("ttId"));
				},
				hoverClass: "accept",
				over: function(e, ui) {
					var droppedEl = ui.draggable.parents("tr");
					if(this != droppedEl[0] && !$(this).is(".expanded")) {
						$("#document-advanced").treetable("expandNode", $(this).data("ttId"));
					}
				}
			});
		});
	})
})(jQuery);