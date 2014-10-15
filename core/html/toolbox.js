
	function highlight_table_col(selector){
		

		$( selector+" td" ).hover(
				function() {
					
					  $(this).parents('table').find('th:nth-child(' + ($(this).index() + 1) + ')')
					  .addClass("col_highlight");
					
				}, function() {

					  $(this).parents('table').find('th:nth-child(' + ($(this).index() + 1) + ')')
					  .removeClass("col_highlight");

				}
				);

  
}
