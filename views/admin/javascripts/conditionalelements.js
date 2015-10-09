jQuery(document).bind("omeka:elementformload", function() {

	var $ = jQuery; // use noConflict version of jQuery as the short $ within this block

	if (typeof conditionalElementsDep !== 'undefined') { 
		$.each(conditionalElementsDep, function(i, dependency) {
			establishDependency(dependency[0], dependency[1], dependency[2]);
		});
	}

	function establishDependency(dependee, showTerm, dependent) {
		showHideDependency(dependee, showTerm, dependent);
		$("#element-"+dependee+" select").change(function() { showHideDependency(dependee, showTerm, dependent); });
	}

	function showHideDependency(dependee, showTerm, dependent) {
		if (dependee && showTerm && dependent) {
			var hideAndEmptyDependent = true;
			$("#element-"+dependee+" select").each(function(index) {
				var val = $(this).val();
				if (val == showTerm) { hideAndEmptyDependent = false; }
			});
			if (hideAndEmptyDependent) {
				$("#element-"+dependent+" textarea").each(function(index) { $(this).val("").change(); });
				$("#element-"+dependent+" select").each(function(index) { $(this).val(null).change(); });
				$("#element-"+dependent).hide(200);
			}
			else { $("#element-"+dependent).show(200); }
		}
	}

});
